<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CooperativeInformation\StoreCooperativeInformationValidation;
use App\Http\Requests\Admin\CooperativeInformation\UpdateCooperativeInformationValidation;
use App\Models\CooperativeInformation;
use Illuminate\Http\Request;

class CooperativeInformationController extends BaseController
{
    protected $base_route = 'admin.cooperativeInformation';
    protected $view_path  = 'admin.cooperativeInformation';
    protected $panel      = 'Cooperative Information';

    public function __construct(CooperativeInformation $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        $data['rows'] = $this->model->orderBy('rank')->get();
        return view(parent::loadCommonDataToView($this->view_path . '.index'), compact('data'));
    }

    public function create()
    {
        return view(parent::loadCommonDataToView($this->view_path . '.create'));
    }

    public function store(StoreCooperativeInformationValidation $request)
    {
        $data = $request->validated();
        
        // Calculate max rank + 1
        $maxRank = CooperativeInformation::max('rank') ?? 0;
        $data['rank'] = $maxRank + 1;

        $row = $this->model->create($data);

        $request->session()->flash('success_message', $this->panel . ' "' . $row->cooperative_name . '" added successfully');
        return redirect()->route($this->base_route . '.index');
    }

    public function show($id)
    {
        $data['row'] = $this->model->find($id);
        parent::rowExist($data['row']);
        return view(parent::loadCommonDataToView($this->view_path . '.show'), compact('data'));
    }

    public function edit($id)
    {
        $data['row'] = $this->model->find($id);
        parent::rowExist($data['row']);
        return view(parent::loadCommonDataToView($this->view_path . '.edit'), compact('data'));
    }

    public function update(UpdateCooperativeInformationValidation $request, $id)
    {
        $row = $this->model->find($id);
        parent::rowExist($row);
        $data = $request->validated();

        $row->update($data);

        $request->session()->flash('success_message', $this->panel . ' "' . $row->cooperative_name . '" updated successfully');
        return redirect()->route($this->base_route . '.index');
    }

    public function destroy($id)
    {
        $row = $this->model->find($id);
        parent::rowExist($row);

        // Check if cooperative is associated with any event bookings
        $hasBookings = \App\Models\EventBooking::where('cooperative_id', $row->id)->exists();
        if ($hasBookings) {
            return redirect()->route($this->base_route . '.index')->with('error_message', 'Cannot delete this cooperative because it is associated with one or more event bookings.');
        }

        $row->delete();

        return redirect()->route($this->base_route . '.index')->with('success_message', $this->panel . ' deleted successfully');
    }

    public function sort(Request $request)
    {
        if ($request->has('ids')) {
            foreach ($request->get('ids') as $key => $id) {
                $row = $this->model->find($id);
                if ($row) {
                    $row->rank = $key + 1;
                    $row->save();
                }
            }
        }
        return redirect()->route($this->base_route . '.index')
            ->with('success_message', $this->panel . ' sorted successfully');
    }

    public function downloadDemoExcel()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setCellValue('A1', 'Cooperative Name');
        $sheet->setCellValue('B1', 'Member No');
        $sheet->setCellValue('C1', 'Address');

        $sheet->setCellValue('A2', 'Sample Cooperative Ltd.');
        $sheet->setCellValue('B2', '12345');
        $sheet->setCellValue('C2', 'Kathmandu, Nepal');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        $fileName = 'cooperative_import_template.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);
        
        return response()->download($tempFile, $fileName)->deleteFileAfterSend(true);
    }

    public function uploadExcel(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv|max:5120'
        ]);

        try {
            $file = $request->file('excel_file');
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();
            
            if (empty($rows)) {
                return redirect()->back()->withErrors(['excel_file' => 'The uploaded Excel file is empty.']);
            }

            // Read header and map columns
            $headers = array_shift($rows);
            
            $coopNameIndex = -1;
            $memberNoIndex = -1;
            $addressIndex  = -1;
            
            foreach ($headers as $index => $header) {
                if ($header === null) continue;
                
                $headerStr = strtolower(trim($header));
                if (str_contains($headerStr, 'member')) {
                    $memberNoIndex = $index;
                } elseif (str_contains($headerStr, 'cooperative') || str_contains($headerStr, 'name')) {
                    $coopNameIndex = $index;
                } elseif (str_contains($headerStr, 'address')) {
                    $addressIndex = $index;
                }
            }

            if ($coopNameIndex === -1 || $memberNoIndex === -1 || $addressIndex === -1) {
                return redirect()->back()->withErrors(['excel_file' => 'Could not find required columns in the header row. Make sure headers contain "Member", "Cooperative Name", and "Address".']);
            }

            $maxRank = \App\Models\CooperativeInformation::max('rank') ?? 0;
            $count = 0;
            $rowNum = 1;

            foreach ($rows as $row) {
                $rowNum++;
                $coopName = isset($row[$coopNameIndex]) ? trim($row[$coopNameIndex]) : '';
                $memberNo = isset($row[$memberNoIndex]) ? trim($row[$memberNoIndex]) : '';
                $address  = isset($row[$addressIndex])  ? trim($row[$addressIndex])  : '';

                if (empty($coopName) && empty($memberNo) && empty($address)) {
                    continue; // Skip entirely empty rows
                }

                if (empty($coopName) || empty($memberNo) || empty($address)) {
                    return redirect()->back()->withErrors(['excel_file' => "Row {$rowNum} is missing Cooperative Name, Member No, or Address."]);
                }

                $cooperative = \App\Models\CooperativeInformation::where('member_no', $memberNo)->first();
                
                if ($cooperative) {
                    $cooperative->update([
                        'cooperative_name' => $coopName,
                        'address'          => $address,
                        'status'           => 1
                    ]);
                } else {
                    $maxRank++;
                    \App\Models\CooperativeInformation::create([
                        'cooperative_name' => $coopName,
                        'member_no'        => $memberNo,
                        'address'          => $address,
                        'rank'             => $maxRank,
                        'status'           => 1
                    ]);
                }
                
                $count++;
            }

            return redirect()->back()->with('success_message', "Successfully imported/updated {$count} cooperatives.");
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['excel_file' => 'Error importing file: ' . $e->getMessage()]);
        }
    }
}
