<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Menu\StoreMenuValidation;
use App\Http\Requests\Admin\Menu\UpdateMenuValidation;
use App\Http\Requests\Admin\ServiceCategory\StoreServiceCategoryValidation;
use App\Http\Requests\Admin\ServiceCategory\UpdateServiceCategoryValidation;
use App\Models\Menu;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;


class MenuController extends BaseController
{
    //properties
    protected $base_route = 'admin.menu';
    protected $view_path = 'admin.menu';
    protected $panel = 'Menu';
    protected $folder = 'menu';
    protected $folder_path;


    public function __construct(Menu $model)
    {
        $this->model = $model;
        $this->folder_path = 'images' . DIRECTORY_SEPARATOR . $this->folder;
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
        $data['rows'] = $this->model->whereNull('parent_id')->rank()->paginated();

        return view(parent::loadCommonDataToView($this->view_path . '.index'), compact('data'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data= [];
        $data['groups'] = Menu::active()->whereNull('parent_id')->pluck('title','id');
        return view(parent::loadCommonDataToView($this->view_path . '.create'),compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMenuValidation $request)
    {
        $menu = $this->model->create($request->validated());

        $request->session()->flash('success_message', $this->panel.' '.$menu->title. ' added Successfully');
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
        $data['groups'] = Menu::active()->whereNull('parent_id')->pluck('title','id');

        return view(parent::loadCommonDataToView($this->view_path . '.edit'), compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMenuValidation $request, $id)
    {
        $row = $this->model->find($id);

        $row->update($request->validated());

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

        $row->delete();

        return redirect()->route($this->base_route.'.index')
            ->with('success_message', $this->panel . ' deleted Successfully');
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
        if($request->has('menu_id')){
            return redirect()->route($this->base_route.'.show', $request->menu_id)
                ->with('success_message', $this->panel . ' sorted Successfully');
        }
        return redirect()->route($this->base_route.'.index')
            ->with('success_message', $this->panel . ' sorted Successfully');
    }
}
