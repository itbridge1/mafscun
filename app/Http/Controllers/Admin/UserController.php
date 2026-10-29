<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\User\PasswordUpdateValidation;
use App\Http\Requests\Admin\User\ProfileUpdateValidation;
use App\Http\Requests\Admin\User\StoreUserValidation;
use App\Http\Requests\Admin\User\UpdateUserValidation;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends BaseController
{
    //properties
    protected $base_route = 'admin.user';
    protected $view_path = 'admin.user';
    protected $panel = 'User';
    protected $folder = 'user';
    protected $folder_path;


    public function __construct(User $model)
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
        $data['rows'] = $this->model->whereHas(
            'roles', function($q){
            $q->where('id','>=', $this->getMinRoleId());
        })->latest()
            ->paginate(config('helper.pagination_limit'));

        return view(parent::loadCommonDataToView($this->view_path . '.index'), compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['roles'] = Role::where('id','>=',$this->getMinRoleId())
            ->pluck('display_name','id');
        return view(parent::loadCommonDataToView($this->view_path . '.create'),compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserValidation $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($request['password']);

        if($request->hasFile('main_image')){
            $this->processImage($data['main_image']);
            $data['photo'] = $this->file_name;
        }
        if($request->hasFile('main_signature')){
            $this->processImage($data['main_signature']);
            $data['signature'] = $this->file_name;
        }
        $user = $this->model->create($data);

        $user->assignRole($data['role']);

        $request->session()->flash('success_message', $this->panel.' '.$user->name. ' added Successfully');
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
        $data['roles'] = Role::where('id','>=',$this->getMinRoleId())
            ->pluck('display_name','id');
        $data['role'] = $data['row']->roles()->pluck('id')->toArray();

        return view(parent::loadCommonDataToView($this->view_path . '.edit'), compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserValidation $request, $id)
    {
        $row = $this->model->find($id);

        $data = $request->validated();
        if($request->has('password')){
            $data['password'] = bcrypt($request['password']);
        }
        if($request->hasFile('main_image')){
            $this->processImage($data['main_image']);
            $data['photo'] = $this->file_name;
            //remove old photo
            @$this->removeFile($this->folder_path.DIRECTORY_SEPARATOR.$row->photo);
        }
        if($request->hasFile('main_signature')){
            $this->processImage($data['main_signature']);
            $data['signature'] = $this->file_name;
            //remove old photo
            @$this->removeFile($this->folder_path.DIRECTORY_SEPARATOR.$row->signature);
        }
        $row->update($data);

        $row->syncRoles([$data['role']]);

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

        $row->delete();

        return redirect()->route($this->base_route.'.index')->with('success_message', $this->panel . ' deleted Successfully');
    }

    public function profile()
    {
        $data['row'] = auth()->user();
        return view($this->loadCommonDataToView($this->view_path.'.profile'),compact('data'));
    }

    public function profileUpdate(ProfileUpdateValidation $request)
    {
        $user = auth()->user();
        $data = $request->validated();
        if($request->hasFile('profile_photo')){
            $this->processImage($request->profile_photo);
            $data['photo'] = $this->file_name;

            @$this->removeFile($this->folder_path.DIRECTORY_SEPARATOR.$user->photo);

        }
        if($request->hasFile('main_signature')){
            $this->processImage($data['main_signature']);
            $data['signature'] = $this->file_name;
            //remove old photo
            @$this->removeFile($this->folder_path.DIRECTORY_SEPARATOR.$user->signature);
        }
        $user->update($data);

        return redirect()->back()
            ->with('success_message', 'Profile updated Successfully');
    }

    public function passwordUpdate(PasswordUpdateValidation $request)
    {
        $user = auth()->user();
        $user->password = Hash::make($request->password);
        $user->update();

        return redirect()->back()
            ->with('success_message', 'Password updated Successfully');
    }

    public function getMinRoleId()
    {
        $minRoleId = auth()->user()->roles()->min('id');
        $role_id = $minRoleId > 0 ? $minRoleId:1;

        return $role_id;
    }
}
