<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Role\StoreRoleValidation;
use App\Http\Requests\Admin\Role\UpdateRoleValidation;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends BaseController
{
    //properties
    protected $base_route = 'admin.role';
    protected $view_path = 'admin.role';
    protected $panel = 'Role';
    protected $folder = 'role';
    protected $folder_path;


    public function __construct(Role $model)
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
//        Session::put('success_message','Successfully Loaded '. $this->panel);
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
        $permissions = Permission::get();
        $data['permissions'] = $permissions->groupBy('group');

        return view(parent::loadCommonDataToView($this->view_path . '.create'),compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRoleValidation $request)
    {
        $role = $this->model->create($request->validated());

        $role->syncPermissions($request->permission);

        $request->session()->flash('success_message', $this->panel.' '.$role->name. ' added Successfully');
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
        $permissions = Permission::get();
        $data['permissions'] = $permissions->groupBy('group');
        $data['role_permission'] = $data['row']->permissions()->pluck('id','id')->toArray();

        return view(parent::loadCommonDataToView($this->view_path . '.edit'), compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRoleValidation $request, $id)
    {
        $row = $this->model->find($id);

        $row->update($request->validated());

        $row->syncPermissions($request['permission']);

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

        //getting permissions of given role and removing the permissions before deleting
        $permissions = $row->permissions;
        if($permissions){
            $row->revokePermissionTo($permissions);
        }

        $row->delete();

        return redirect()->route($this->base_route.'.index')
            ->with('success_message', $this->panel . ' deleted Successfully');
    }
}
