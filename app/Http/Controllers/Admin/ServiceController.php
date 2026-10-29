<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Service\StoreServiceValidation;
use App\Http\Requests\Admin\Service\UpdateServiceValidation;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceController extends BaseController
{
    //properties
    protected $base_route = 'admin.service';
    protected $view_path = 'admin.service';
    protected $panel = 'Service';
    protected $folder = 'serviceCategory';
    protected $folder_path;


    public function __construct(ServiceCategory $model)
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
    public function index($slug)
    {
        $data = [];
        $data['parent'] = $this->checkParentSlug($slug);
        $data['rows'] = $this->model
            ->where('parent_id', $data['parent']->id)
            ->rank()
            ->paginated();

        return view(parent::loadCommonDataToView($this->view_path . '.index'), compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($slug)
    {
        $data['parent'] = $this->checkParentSlug($slug);

        return view(parent::loadCommonDataToView($this->view_path . '.create'),compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($slug, StoreServiceValidation $request)
    {
        $parent = $this->checkParentSlug($slug);
        $data = $request->validated();
        $data['parent_id'] = $parent->id;

        if($request->hasFile('main_image')){
            $this->processImage($data['main_image']);
            $data['photo'] = $this->file_name;
        }

        $service = $this->model->create($data);

        $request->session()->flash('success_message', $this->panel.' '.$service->title. ' added Successfully');
        return redirect()->route($this->base_route.'.index', $parent->slug);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug, $id)
    {
        $data['parent'] = $this->checkParentSlug($slug);
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
    public function edit($slug, $id)
    {
        $data = [];
        $data['parent'] = $this->checkParentSlug($slug);
        $data ['row'] = $this->model->find($id);
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
    public function update($slug, UpdateServiceValidation $request, $id)
    {
        $parent = $this->checkParentSlug($slug);

        $row = $this->model->find($id);

        $data = $request->validated();

        if($request->hasFile('main_image')){
            $this->processImage($data['main_image']);
            $data['photo'] = $this->file_name;
            //remove old photo
            @$this->removeFile($this->folder_path.DIRECTORY_SEPARATOR.$row->photo);
        }
        $row->update($data);

        $request->session()->flash('success_message',$this->panel.' '. $row->title . ' updated Successfully');
        return redirect()->route($this->base_route.'.index', $parent->slug);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug, $id)
    {
        $parent = $this->checkParentSlug($slug);

        $row = $this->model->find($id);
        parent::rowExist($row);
        //remove old photo
        @$this->removeFile($this->folder_path.DIRECTORY_SEPARATOR.$row->photo);

        $row->delete();

        return redirect()->route($this->base_route.'.index', $parent->slug)
            ->with('success_message', $this->panel . ' deleted Successfully');
    }

    public function sort($slug, Request $request)
    {
        $parent = $this->checkParentSlug($slug);

        if($request->has('ids')){
            foreach ($request->get('ids') as $key => $id){
                $row = $this->model->find($id);
//                dd($row);
                $row->rank = $key + 1;
                $row->save();
            }
        }
        return redirect()->route($this->base_route.'.index', $parent->slug)
            ->with('success_message', $this->panel . ' sorted Successfully');
    }

    protected function checkParentSlug($slug)
    {
        $parent = ServiceCategory::where('slug', $slug)->first();
        $this->parentExist($parent);
        return $parent;
    }
}
