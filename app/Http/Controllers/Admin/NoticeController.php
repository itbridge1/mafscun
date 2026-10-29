<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\AboutUs\StoreAboutUsValidation;
use App\Http\Requests\Admin\AboutUs\UpdateAboutUsValidation;
use App\Http\Requests\Admin\Notice\StoreNoticeValidation;
use App\Http\Requests\Admin\Notice\UpdateNoticeValidation;
use App\Models\Notice;
use Illuminate\Http\Request;

class NoticeController extends BaseController
{
    //properties
    protected $base_route = 'admin.notice';
    protected $view_path = 'admin.notice';
    protected $panel = 'Notice';
    protected $folder = 'notice';
    protected $folder_path;


    public function __construct(Notice $model)
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
        $data['rows'] = $this->model->rank()
            ->where(function ($query) use ($request){
                if($request->has('filter_popup')) {
                    $this->has_filter = true;
                    $query->where('is_popup',$request->get('filter_popup'));
                }
                if($request->has('filter_career')) {
                    $this->has_filter = true;
                    $query->where('is_career',$request->get('filter_career'));
                }
                if($request->has('filter_status')) {
                    $this->has_filter = true;
                    $query->where('status',$request->get('filter_status'));
                }
                if($request->has('filter_start_date') && $request->has('filter_end_date')) {
                    $this->has_filter = true;
                    $query->whereDate("start_date",'>=',$request->get('filter_start_date'))
                        ->whereDate("end_date",'<=',$request->get('filter_end_date'));
//                    $query->whereBetween('created_at', [$request->get('filter_start_date'), $request->get('filter_end_date')]);

                } else{
                    if($request->has('filter_start_date')){
                        $this->has_filter = true;
                        $query->where('start_date', '>=', $request->get('filter_start_date'));
                    }
                    if($request->has('filter_end_date')){
                        $this->has_filter = true;
                        $query->where('end_date', '<=', $request->get('filter_end_date'));
                    }

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
        return view(parent::loadCommonDataToView($this->view_path . '.create'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreNoticeValidation $request)
    {
        $data = $request->validated();
        if($request->hasFile('main_image')){
            $this->processImage($data['main_image']);
            $data['photo'] = $this->file_name;
        }

        $notice = $this->model->create($data);

        $request->session()->flash('success_message', $this->panel.' '.$notice->title. ' added Successfully');
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
    public function update(UpdateNoticeValidation $request, $id)
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
//                dd($row);
                $row->rank = $key + 1;
                $row->save();
            }
        }
        return redirect()->route($this->base_route.'.index')
            ->with('success_message', $this->panel . ' sorted Successfully');
    }
}
