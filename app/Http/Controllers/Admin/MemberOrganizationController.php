<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\AboutUs\StoreAboutUsValidation;
use App\Http\Requests\Admin\AboutUs\UpdateAboutUsValidation;
use App\Http\Requests\Admin\Category\StoreCategoryValidation;
use App\Http\Requests\Admin\Category\UpdateCategoryValidation;
use App\Http\Requests\Admin\MemberOrganization\StoreMemberOrganizationValidation;
use App\Http\Requests\Admin\MemberOrganization\UpdateMemberOrganizationValidation;
use App\Http\Requests\Admin\News\StoreNewsValidation;
use App\Http\Requests\Admin\News\UpdateNewsValidation;
use App\Http\Requests\Admin\Resource\StoreResourceValidation;
use App\Http\Requests\Admin\Resource\UpdateResourceValidation;
use App\Models\AboutUs;
use App\Models\Category;
use App\Models\District;
use App\Models\MemberOrganization;
use App\Models\Municipality;
use App\Models\News;
use App\Models\Resource;
use App\Models\ResourceType;
use Illuminate\Http\Request;

class MemberOrganizationController extends BaseController
{
    //properties
    protected $base_route = 'admin.memberOrganization';
    protected $view_path = 'admin.memberOrganization';
    protected $panel = 'MemberOrganization';
    protected $folder = 'memberOrganization';
    protected $folder_path;


    public function __construct(MemberOrganization $model)
    {
        $this->model = $model;
        $this->folder_path = 'images' . DIRECTORY_SEPARATOR . $this->folder;
        $this->generateAllMiddlewareByPermission();
    }
    /**
     * Display a listing of the memberOrganization.
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
     * Show the form for creating a new memberOrganization.
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

        return view(parent::loadCommonDataToView($this->view_path . '.create'),compact('data'));
    }

    /**
     * Store a newly created memberOrganization in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMemberOrganizationValidation $request)
    {
        $data = $request->validated();
//        dd($data);
        if($request->hasFile('main_image')){
            $this->processImage($data['main_image']);
            $data['photo'] = $this->file_name;
        }

        $memberOrg = $this->model->create($data);

        $request->session()->flash('success_message', $this->panel.' '.$memberOrg->name. ' added Successfully');
        return redirect()->route($this->base_route.'.index');
    }

    /**
     * Display the specified memberOrganization.
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
     * Show the form for editing the specified memberOrganization.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = [];
        $data ['row'] = $this->model->find($id);
        parent::rowExist($data['row']);
        $data['districts']= District::bagmati()->orderBy('name')
            ->select('name','id')
            ->get();
        $data['municipalities']= Municipality::bagmati()->orderBy('name')
            ->select('name','id','district_id')
            ->get();


        return view(parent::loadCommonDataToView($this->view_path . '.edit'), compact('data'));
    }

    /**
     * Update the specified memberOrganization in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMemberOrganizationValidation $request, $id)
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
     * Remove the specified memberOrganization from storage.
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
