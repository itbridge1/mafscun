<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Municipality\StoreMunicipalityValidation;
use App\Http\Requests\Admin\Municipality\UpdateMunicipalityValidation;
use App\Models\District;
use App\Models\Municipality;
use App\Models\Province;
use Illuminate\Http\Request;

class MunicipalityController extends BaseController
{
    //properties
    protected $base_route = 'admin.municipality';
    protected $view_path = 'admin.municipality';
    protected $panel = 'Municipality';
    protected $folder = 'municipality';
    protected $folder_path;


    public function __construct(Municipality $model)
    {
        $this->model = $model;
        $this->folder_path = 'images' . DIRECTORY_SEPARATOR . $this->folder;
        $this->generateAllMiddlewareByPermission();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = [];
        $data['provinces'] = Province::active()->pluck('province_name', 'id');
        $data['districts'] = District::active()->pluck('name', 'id');
        $data['rows'] = $this->model->with('district:id,name')
            ->orderBy('name')
            ->where(function ($query) use ($request){
                if($request->has('filter_province_id')) {
                    $this->has_filter = true;
                    $query->where('province_id',$request->get('filter_province_id'));
                }
                if($request->has('filter_district_id')) {
                    $this->has_filter = true;
                    $query->where('district_id',$request->get('filter_district_id'));
                }

            })
            ->paginated();

        return view(parent::loadCommonDataToView($this->view_path . '.index'), compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['provinces'] = Province::active()->pluck('province_name', 'id');
        $data['districts'] = District::active()->pluck('name', 'id');
        return view(parent::loadCommonDataToView($this->view_path . '.create'),compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMunicipalityValidation $request)
    {
        $data = $request->validated();
//        dd($data);
        if($request->hasFile('main_image')){
            $this->processImage($data['main_image']);
            $data['photo'] = $this->file_name;
        }

        $municipality = $this->model->create($data);

        $request->session()->flash('success_message', $this->panel.' '.$municipality->alt_text. ' added Successfully');
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
        parent::rowExist($data['row']);
        $data['provinces'] = Province::active()->pluck('province_name', 'id');
        $data['districts'] = District::active()->pluck('name', 'id');

        return view(parent::loadCommonDataToView($this->view_path . '.edit'), compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMunicipalityValidation $request, $id)
    {
        $row = $this->model->find($id);

        $data = $request->validated();

        if($request->hasFile('main_image')){
            $this->processImage($data['main_image']);
            $data['photo'] = $this->file_name;
            //remove old photo
            @$this->removeFile($this->folder_path.DIRECTORY_SEPARATOR.$row->photo);
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
        @$this->removeFile($this->folder_path.DIRECTORY_SEPARATOR.$row->photo);

        $row->delete();

        return redirect()->route($this->base_route.'.index')->with('success_message', $this->panel . ' deleted Successfully');
    }

    public function sort(Request $request)
    {
        if($request->has('ids')){
            foreach ($request->get('ids') as $key => $id){
                $row = $this->model->find($id);
                $row->rank = $key + 1;
                $row->save();
            }
        }
        return redirect()->route($this->base_route.'.index')
            ->with('success_message', $this->panel . ' sorted Successfully');
    }
 public function getMunicipalities(Request $request)
{
    
    $districtId = $request->query('districtId');
     $municipalities = Municipality::where('district_id', $districtId)->get();
    return response()->json($municipalities);
}
}
