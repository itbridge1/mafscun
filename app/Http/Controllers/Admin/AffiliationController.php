<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\AboutUs\StoreAboutUsValidation;
use App\Http\Requests\Admin\AboutUs\UpdateAboutUsValidation;
use App\Http\Requests\Admin\Affiliation\StoreAffiliationValidation;
use App\Http\Requests\Admin\Affiliation\UpdateAffiliationValidation;
use App\Http\Requests\Admin\Notice\StoreNoticeValidation;
use App\Http\Requests\Admin\Notice\UpdateNoticeValidation;
use App\Models\Affiliation;
use App\Models\Notice;
use Illuminate\Http\Request;

class AffiliationController extends BaseController
{
    //properties
    protected $base_route = 'admin.affiliation';
    protected $view_path = 'admin.affiliation';
    protected $panel = 'Affiliation';
    protected $folder = 'affiliation';
    protected $folder_path;


    public function __construct(Affiliation $model)
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
        $data['rows'] = $this->model->rank()->paginated();

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
    public function store(StoreAffiliationValidation $request)
    {
        $data = $request->validated();
        if($request->hasFile('main_image')){
            $this->processImage($data['main_image']);
            $data['logo'] = $this->file_name;
        }

        $affiliation = $this->model->create($data);

        $request->session()->flash('success_message', $this->panel.' '.$affiliation->title. ' added Successfully');
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
    public function update(UpdateAffiliationValidation $request, $id)
    {
        $row = $this->model->find($id);

        $data = $request->validated();

        if($request->hasFile('main_image')){
            $this->processImage($data['main_image']);
            $data['logo'] = $this->file_name;
            //remove old photo
            @$this->removeFile($this->folder_path.DIRECTORY_SEPARATOR.$row->logo);
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
        @$this->removeFile($this->folder_path.DIRECTORY_SEPARATOR.$row->logo);

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
