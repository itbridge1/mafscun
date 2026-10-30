<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ServiceSubCategory\StoreServiceSubCategoryValidation;
use App\Http\Requests\Admin\ServiceSubCategory\UpdateServiceSubCategoryValidation;
use App\Models\ServiceCategory;
use App\Models\ServiceSubCategory;
use Illuminate\Http\Request;

class ServiceSubCategoryController extends BaseController
{
    protected $base_route = 'admin.serviceSubCategory';
    protected $view_path = 'admin.serviceSubCategory';
    protected $panel = 'Service Sub Category';
    protected $folder = 'serviceSubCategory';
    protected $folder_path;

    public function __construct(ServiceSubCategory $model)
    {
        $this->model = $model;
        $this->folder_path = 'images' . DIRECTORY_SEPARATOR . $this->folder;
        $this->generateAllMiddlewareByPermission();
    }

    public function index(Request $request)
    {
        $data = [];
        $query = $this->model->with(['category', 'services'])->rank();

        if ($request->filled('category_id')) {
            $query->where('service_category_id', $request->category_id);
        }

        $data['rows'] = $query->paginated();
        $data['categories'] = ServiceCategory::rank()->pluck('title', 'id');
        $data['selected_category'] = $request->get('category_id');

        return view(parent::loadCommonDataToView($this->view_path . '.index'), compact('data'));
    }

    public function create(Request $request)
    {
        $data = [];
        $data['categories'] = ServiceCategory::rank()->pluck('title', 'id');
        $data['selected_category'] = $request->get('category_id');

        return view(parent::loadCommonDataToView($this->view_path . '.create'), compact('data'));
    }

    public function store(StoreServiceSubCategoryValidation $request)
    {
        $data = $request->validated();

        if ($request->hasFile('main_image')) {
            $this->processImage($data['main_image']);
            $data['photo'] = $this->file_name;
        }

        $row = $this->model->create($data);

        $request->session()->flash('success_message', $this->panel . ' ' . $row->title . ' added successfully');
        return redirect()->route($this->base_route . '.index');
    }

    public function show($id)
    {
        $data = [];
        $data['row'] = $this->model->with(['category', 'services'])->find($id);
        parent::rowExist($data['row']);

        return view(parent::loadCommonDataToView($this->view_path . '.show'), compact('data'));
    }

    public function edit($id)
    {
        $data = [];
        $data['row'] = $this->model->find($id);
        parent::rowExist($data['row']);
        $data['categories'] = ServiceCategory::rank()->pluck('title', 'id');

        return view(parent::loadCommonDataToView($this->view_path . '.edit'), compact('data'));
    }

    public function update(UpdateServiceSubCategoryValidation $request, $id)
    {
        $row = $this->model->find($id);
        parent::rowExist($row);

        $data = $request->validated();

        if ($request->hasFile('main_image')) {
            $this->processImage($data['main_image']);
            $data['photo'] = $this->file_name;
            if ($row->photo) {
                @$this->removeFile($this->folder_path . DIRECTORY_SEPARATOR . $row->photo);
            }
        }

        $row->update($data);

        $request->session()->flash('success_message', $this->panel . ' ' . $row->title . ' updated successfully');
        return redirect()->route($this->base_route . '.index');
    }

    public function destroy($id)
    {
        $row = $this->model->find($id);
        parent::rowExist($row);

        if ($row->photo) {
            @$this->removeFile($this->folder_path . DIRECTORY_SEPARATOR . $row->photo);
        }

        $row->delete();

        return redirect()->route($this->base_route . '.index')
            ->with('success_message', $this->panel . ' deleted successfully');
    }

    public function sort(Request $request)
    {
        if ($request->has('ids')) {
            foreach ($request->get('ids') as $key => $id) {
                $row = $this->model->find($id);
                if ($row) {
                    $row->rank = $key + 1;
                    $row->save();
                }
            }
        }

        return redirect()->route($this->base_route . '.index')
            ->with('success_message', $this->panel . ' sorted successfully');
    }

    public function byCategory($categoryId)
    {
        $subCategories = $this->model
            ->where('service_category_id', $categoryId)
            ->rank()
            ->get(['id', 'title']);

        return response()->json($subCategories);
    }
}
