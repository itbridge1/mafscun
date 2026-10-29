<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\AboutUs\StoreAboutUsValidation;
use App\Http\Requests\Admin\AboutUs\UpdateAboutUsValidation;
use App\Http\Requests\Admin\Category\StoreCategoryValidation;
use App\Http\Requests\Admin\Category\UpdateCategoryValidation;
use App\Http\Requests\Admin\News\StoreNewsValidation;
use App\Http\Requests\Admin\News\UpdateNewsValidation;
use App\Http\Requests\Admin\Resource\StoreResourceValidation;
use App\Http\Requests\Admin\Resource\UpdateResourceValidation;
use App\Models\AboutUs;
use App\Models\Category;
use App\Models\News;
use App\Models\Resource;
use App\Models\ResourceType;
use Illuminate\Http\Request;

class ResourceController extends BaseController
{
    //properties
    protected $base_route = 'admin.resource';
    protected $view_path = 'admin.resource';
    protected $panel = 'Resource';
    protected $folder = 'resource';
    protected $folder_path;


    public function __construct(Resource $model)
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
        $data['rows'] = $this->model->latest()->paginate(config('helper.pagination_limit'));
         return view(parent::loadCommonDataToView($this->view_path . '.index'), compact('data'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['resource_types']= ResourceType::active()->pluck('title','id');
        return view(parent::loadCommonDataToView($this->view_path . '.create'),compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreResourceValidation $request)
    {
        $data = $request->validated();

        if($request->hasFile('main_file')){
            $this->processImage($data['main_file']);
            $data['file'] = $this->file_name;
        }else{
            if($data['type'] == 'link'){
                $data['file'] = $data['link'];
            }
        }

        $aboutUs = $this->model->create($data);

        $request->session()->flash('success_message', $this->panel.' '.$aboutUs->name. ' added Successfully');
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
        $data['resource_types']= ResourceType::active()->pluck('title','id');


        return view(parent::loadCommonDataToView($this->view_path . '.edit'), compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateResourceValidation $request, $id)
    {
        $row = $this->model->find($id);

        $data = $request->validated();

        if($request->hasFile('main_file')){
            $this->processImage($data['main_file']);
            $data['file'] = $this->file_name;
            //remove old photo
            @$this->removeFile($this->folder_path.DIRECTORY_SEPARATOR.$row->file);
        }
        if($data['type'] == 'link'){
            //remove old photo
            @$this->removeFile($this->folder_path.DIRECTORY_SEPARATOR.$row->file);

            $data['file'] = $data['link'];
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
        @$this->removeFile($this->folder_path.DIRECTORY_SEPARATOR.$row->file);

        $row->delete();

        return redirect()->route($this->base_route.'.index')->with('success_message', $this->panel . ' deleted Successfully');
    }

    public function sort(Request $request)
    {
        if($request->has('ids')){
            foreach ($request->get('ids') as $key => $id){
                $row = $this->model->find($id);
//                dd($row);
                $row->rank = $key + 1;
                $row->save();
            }
        }
        return redirect()->route($this->base_route.'.index')
            ->with('success_message', $this->panel . ' sorted Successfully');
    }
}
