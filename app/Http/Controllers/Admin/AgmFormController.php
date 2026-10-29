<?php

namespace App\Http\Controllers\Admin;
  
use App\Http\Requests\Admin\ContactForm\StoreContactFormValidation;
use App\Http\Requests\Admin\ContactForm\UpdateContactFormValidation;
use App\Http\Requests\Website\StoreAgmValidation;
use App\Http\Requests\Website\UpdateAgmValidation;
use App\Models\AgmForm;
use App\Models\District;
use App\Models\Municipality;
use BaconQrCode\Encoder\QrCode;
use Intervention\Image\Facades\Image;
use ZipArchive;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode as FacadesQrCode;

class AgmFormController extends BaseController
{
    //properties
    protected $base_route = 'admin.agmForm';
    protected $view_path = 'admin.agmForm';
    protected $panel = 'Agm Nomination Form';
    protected $folder = 'AgmForm/PassportSize';
    protected $folder2 = 'AgmForm/NominationForm';
    protected $folder_path;
    protected $folder_path2;


    public function __construct(AgmForm $model)
    {
        $this->model = $model;
       $this->folder_path = 'images' . DIRECTORY_SEPARATOR .$this->folder;
       $this->folder_path2 = 'images' . DIRECTORY_SEPARATOR .$this->folder2;
         // $this->generateAllMiddlewareByPermission();
      
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        $data['rows'] = $this->model->orderByRaw('status = 0 DESC, created_at DESC')->get();

        return view(parent::loadCommonDataToView($this->view_path . '.index'), compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['districts']= District::bagmati()->orderBy('name')
        ->select('name','id')
        ->get();
    $data['municipalities']= Municipality::bagmati()->orderBy('name')
        ->select('name','id','district_id')
        ->get();
        return view(parent::loadCommonDataToView($this->view_path . '.create',compact('data')));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreContactFormValidation $request)
    {
        $data = $request->validated();
//        if($request->hasFile('main_image')){
//            $this->processImage($data['main_image']);
//            $data['photo'] = $this->file_name;
//        }

        $contactForm = $this->model->create($data);

        $request->session()->flash('success_message', $this->panel.' '.$contactForm->title. ' added Successfully');
        return redirect()->route($this->base_route.'.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = [];
        $data['row'] = $this->model->find($id);
        parent::rowExist($data['row']);

        return view(parent::loadCommonDataToView($this->view_path . '.show'), compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = [];
        $data ['row'] = $this->model->find($id);
        $data['districts']= District::bagmati()->orderBy('name')
        ->select('name','id')
        ->get();
    $data['municipalities']= Municipality::bagmati()->orderBy('name')
        ->select('name','id','district_id')
        ->get();
        parent::rowExist($data['row']);

        return view(parent::loadCommonDataToView($this->view_path . '.edit'), compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAgmValidation $request, $id)
    {
        $row = $this->model->find($id);

        $data = $request->validated();

        if ($request->hasFile('main_nomination_form')) {
            // Remove old nomination form if exists
            if ($row->nomination_form && file_exists(public_path($this->folder_path2 . DIRECTORY_SEPARATOR . $row->nomination_form))) {
                unlink(public_path($this->folder_path2 . DIRECTORY_SEPARATOR . $row->nomination_form));
            }

            $file_name = time() . '_' . $this->getRandomStringForImage() . '.' . $request['main_nomination_form']->getClientOriginalExtension();
            $request['main_nomination_form']->move(public_path($this->folder_path2), $file_name);
            // Process the uploaded file (it could be any file, not just an image)
               $data['nomination_form'] = $file_name;
        }

        if ($request->hasFile('main_photo')) {
            // Remove old photo if exists
            if ($row->photo && file_exists(public_path($this->folder_path . DIRECTORY_SEPARATOR . $row->photo))) {
                unlink(public_path($this->folder_path . DIRECTORY_SEPARATOR . $row->photo));
            }

            $file_name2 = time() . '_' . $this->getRandomStringForImage() . '.' . $request['main_photo']->getClientOriginalExtension();
            $request['main_photo']->move(public_path($this->folder_path), $file_name2);
            Image::make(public_path($this->folder_path) . DIRECTORY_SEPARATOR . $file_name2);
            $data['photo'] = $file_name2;
        }
        $row->update($data);

        $request->session()->flash('success_message',$this->panel.' '. $row->name . ' updated Successfully');
        return redirect()->route($this->base_route.'.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $row = $this->model->find($id);
        parent::rowExist($row);
        //remove old photo
        if ($row->nomination_form && file_exists(public_path($this->folder_path2 . DIRECTORY_SEPARATOR . $row->nomination_form))) {
            unlink(public_path($this->folder_path2 . DIRECTORY_SEPARATOR . $row->nomination_form));
        }

        if ($row->photo && file_exists(public_path($this->folder_path . DIRECTORY_SEPARATOR . $row->photo))) {
            unlink(public_path($this->folder_path . DIRECTORY_SEPARATOR . $row->photo));
        }
        $row->delete();

        return redirect()->route($this->base_route.'.index')->with('success_message', $this->panel . ' deleted Successfully');
    }
    public function agmformStore(StoreAgmValidation $request)
    {
         $validatedData = $request->validated();

         if ($request->hasFile('main_nomination_form')) {
         $file_name = time().'_'.$this->getRandomStringForImage().'.'. $request['main_nomination_form']->getClientOriginalExtension();
         $request['main_nomination_form']->move(public_path($this->folder_path2), $file_name);
          $validatedData['nomination_form'] = $file_name;
         }
         if ($request->hasFile('main_photo')) {
         $file_name2= time().'_'.$this->getRandomStringForImage().'.'. $request['main_photo']->getClientOriginalExtension();
  
         $request['main_photo']->move(public_path($this->folder_path), $file_name2);
         Image::make(public_path($this->folder_path).DIRECTORY_SEPARATOR.$file_name2);
         $validatedData['photo'] = $file_name2;
         }
        

        $agmForm = AgmForm::create($validatedData);

        return redirect()->back()->with('success_message', 'Form submitted successfully!');
    }


    public function downloadPassportFolder()
    {
        $zip = new ZipArchive;
        $fileName = 'PassportSizePhotos.zip';
        $zipPath = public_path($fileName);

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $files = File::allFiles(public_path($this->folder_path));
            foreach ($files as $file) {
                $relativeName = basename($file);
                $zip->addFile($file, $relativeName);
            }
            $zip->close();
        }

        return response()->download(file: $zipPath)->deleteFileAfterSend(true);
     }
    public function downloadQRFolder()
    {
        $zip = new ZipArchive;
        $fileName = 'QRPhotos.zip';
        $zipPath = public_path($fileName);

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $rows = $this->model->all();
            foreach ($rows as $row) {
            $url = 'https://bagmatiproscun.org.np/AgmVerification/'.base64_encode($row->id);
            $qrCodeSVG = FacadesQrCode::size(300)->generate($url);
            $imageName = $row->name . '.svg';
            $imagePath = tempnam(sys_get_temp_dir(), $imageName);
            file_put_contents($imagePath, $qrCodeSVG);

            // Check if file with the same name exists in the zip
            $counter = 1;
            $finalImageName = $imageName;
            while ($zip->locateName($finalImageName) !== false) {
                $finalImageName = $row->name . '_' . $counter . '.svg';
                $counter++;
            }

            $zip->addFile($imagePath, $finalImageName);
            }
            $zip->close();
        }

        return response()->download(file: $zipPath)->deleteFileAfterSend(shouldDelete: true);

      }
    public function exportToExcel()
    {
        $data = $this->model->active()->get();
        $fileName = 'AgmFormData.xlsx';
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set the header row
        $header = [
            'SN', 'Name', 'Nepali Name', 'Gender', 'Saccos Union', 'Cooperative Name', 'Post','Email','District', 'Municipality', 'Ward No', 'Membership No', 'Mobile No', 'Photo'
        ];
        $sheet->fromArray($header, NULL, 'A1'); 
        // Populate the data rows
        $rowNumber = 2;
        foreach ($data as $index => $row) {
            $sheet->fromArray([
                $index + 1,
                $row->name,
                $row->nepali_name??"-",
                $row->gender,
                $row->saccos_union,
                $row->cooperative_name??"-",
                $row->post,
                $row->email,
                $row->district->name,
                $row->municipality->name,
                $row->ward_no,
                $row->membership_no,
                $row->mobile_no,
                $row->photo
            ], NULL, 'A' . $rowNumber);
            $rowNumber++;
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName, $headers)->deleteFileAfterSend(true);
    }
    public function approve($id)
    {
        $row = $this->model->find($id);
        parent::rowExist($row);

        // Update the status
        $row->update(['status' => 1]);

        // Send approval email
        $details = [
            'created_at' => $row->created_at,
            'nepali_name' => $row->nepali_name,
            'saccos_union' => $row->saccos_union,
            'district' =>   $row->district->nepali_name,
            'municipality' => $row->municipality->nepali_name,
            'ward_no' => $row->ward_no,
            'membership_no' => $row->membership_no,


        ];

        Mail::to($row->email)->send(mailable: new \App\Mail\FormApprovalMail($details));

        return redirect()->route($this->base_route.'.index')->with('success_message', 'Form approved  successfully.');
    }
//    public function sort(Request $request)
//    {
//        if($request->has('ids')){
//            foreach ($request->get('ids') as $key => $id){
//                $row = $this->model->find($id);
// //                dd($row);
//                $row->rank = $key + 1;
//                $row->save();
//            }
//        }
//        return redirect()->route($this->base_route.'.index')
//            ->with('success_message', $this->panel . ' sorted Successfully');
//    }
}
