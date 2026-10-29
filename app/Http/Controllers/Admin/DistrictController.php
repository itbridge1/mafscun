<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\District\StoreDistrictValidation;
use App\Http\Requests\Admin\District\UpdateDistrictValidation;
use App\Models\District;
use App\Models\Province;
use Illuminate\Http\Request;

class DistrictController extends BaseController
{
    //properties
    protected $base_route = 'admin.district';
    protected $view_path = 'admin.district';
    protected $panel = 'District';
    protected $folder = 'district';
    protected $folder_path;


    public function __construct(District $model)
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
    public function index()
    {
        $data = [];
        $data['rows'] = $this->model->latest()->paginated();

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
        return view(parent::loadCommonDataToView($this->view_path . '.create'),compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDistrictValidation $request)
    {
        $data = $request->validated();
//        dd($data);
        if($request->hasFile('main_image')){
            $this->processImage($data['main_image']);
            $data['photo'] = $this->file_name;
        }

        if(!empty($data['autoselect'])){
            District::query()->update(['autoselect' => 0]);
        }

        $district = $this->model->create($data);

        $request->session()->flash('success_message', $this->panel.' '.$district->alt_text. ' added Successfully');
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

        return view(parent::loadCommonDataToView($this->view_path . '.edit'), compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDistrictValidation $request, $id)
    {
        $row = $this->model->find($id);

        $data = $request->validated();

        if($request->hasFile('main_image')){
            $this->processImage($data['main_image']);
            $data['photo'] = $this->file_name;
            //remove old photo
            @$this->removeFile($this->folder_path.DIRECTORY_SEPARATOR.$row->photo);
        }

        if(!empty($data['autoselect'])){
            District::where('id', '!=', $row->id)->update(['autoselect' => 0]);
        }

        $row->update($data);

        $request->session()->flash('success_message',$this->panel.' '. $row->name . ' updated Successfully');
        return redirect()->route($this->base_route.'.index');
    }

    public function toggleAutoselect($id)
    {
        $district = District::findOrFail($id);
        if ($district->autoselect) {
            $district->autoselect = 0;
            $district->save();
            $msg = "Autoselect removed from {$district->name}. All areas will now display in the frontend dropdown.";
        } else {
            District::where('id', '!=', $district->id)->update(['autoselect' => 0]);
            $district->autoselect = 1;
            $district->save();
            $msg = "{$district->name} is now set as the autoselected area for the frontend chart.";
        }

        return redirect()->back()->with('success_message', $msg);
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
}
