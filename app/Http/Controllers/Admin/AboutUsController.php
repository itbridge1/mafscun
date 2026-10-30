<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\AboutUs\StoreAboutUsValidation;
use App\Http\Requests\Admin\AboutUs\UpdateAboutUsValidation;
use App\Models\AboutUs;
use Illuminate\Http\Request;

class AboutUsController extends BaseController
{
    //properties
    protected $base_route = 'admin.aboutUs';
    protected $view_path = 'admin.aboutUs';
    protected $panel = 'AboutUs';
    protected $folder = 'aboutUs';
    protected $folder_path;


    public function __construct(AboutUs $model)
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
        $data['rows'] = $this->model->where('is_message', 0)->rank()->paginate(config('helper.pagination_limit'));

        return view(parent::loadCommonDataToView($this->view_path . '.index'), compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view(parent::loadCommonDataToView($this->view_path . '.create'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAboutUsValidation $request)
    {
        $data = $request->validated();
        $data['is_message'] = 0;

        if($request->hasFile('main_image')){
            $this->processImage($data['main_image']);
            $data['photo'] = $this->file_name;
        }

        $aboutUs = $this->model->create($data);

        $request->session()->flash('success_message', $this->panel.' '.$aboutUs->title. ' added Successfully');
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

        return view(parent::loadCommonDataToView($this->view_path . '.edit'), compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAboutUsValidation $request, $id)
    {
        $row = $this->model->find($id);

        $data = $request->validated();
        $data['is_message'] = 0;

        if($request->hasFile('main_image')){
            $this->processImage($data['main_image']);
            $data['photo'] = $this->file_name;
            //remove old photo
            @$this->removeFile($this->folder_path.DIRECTORY_SEPARATOR.$row->photo);
        }
        $row->update($data);

        $request->session()->flash('success_message',$this->panel.' '. $row->title . ' updated Successfully');
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
//                dd($row);
                $row->rank = $key + 1;
                $row->save();
            }
        }
        return redirect()->route($this->base_route.'.index')
            ->with('success_message', $this->panel . ' sorted Successfully');
    }
}
