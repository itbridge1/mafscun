<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Service\StoreServiceValidation;
use App\Http\Requests\Admin\Service\UpdateServiceValidation;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceSubCategory;
use Illuminate\Http\Request;

class ServiceController extends BaseController
{
    protected $base_route = 'admin.service';
    protected $view_path = 'admin.service';
    protected $panel = 'Service';
    protected $folder = 'service';
    protected $folder_path;

    public function __construct(Service $model)
    {
        $this->model = $model;
        $this->folder_path = 'images' . DIRECTORY_SEPARATOR . $this->folder;
        $this->generateAllMiddlewareByPermission();
    }

    public function index(Request $request)
    {
        $data = [];
        $query = $this->model->with(['category', 'subCategory'])->rank();

        if ($request->filled('category_id')) {
            $query->where('service_category_id', $request->category_id);
        }

        if ($request->filled('sub_category_id')) {
            $query->where('service_sub_category_id', $request->sub_category_id);
        }

        $data['rows'] = $query->paginated();
        $data['categories'] = ServiceCategory::rank()->pluck('title', 'id');
        $data['subCategories'] = $request->filled('category_id')
            ? ServiceSubCategory::where('service_category_id', $request->category_id)->rank()->pluck('title', 'id')
            : [];
        $data['selected_category'] = $request->get('category_id');
        $data['selected_sub_category'] = $request->get('sub_category_id');

        return view(parent::loadCommonDataToView($this->view_path . '.index'), compact('data'));
    }

    public function create(Request $request)
    {
        $data = [];
        $data['categories'] = ServiceCategory::rank()->pluck('title', 'id');
        $categoryId = $request->old('service_category_id', $request->get('category_id'));
        $data['selected_category'] = $categoryId;
        $data['subCategories'] = $categoryId
            ? ServiceSubCategory::where('service_category_id', $categoryId)->rank()->pluck('title', 'id')
            : [];

        return view(parent::loadCommonDataToView($this->view_path . '.create'), compact('data'));
    }

    public function store(StoreServiceValidation $request)
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
        $data['row'] = $this->model->with(['category', 'subCategory'])->find($id);
        parent::rowExist($data['row']);

        return view(parent::loadCommonDataToView($this->view_path . '.show'), compact('data'));
    }

    public function edit($id)
    {
        $data = [];
        $data['row'] = $this->model->find($id);
        parent::rowExist($data['row']);

        $data['categories'] = ServiceCategory::rank()->pluck('title', 'id');
        $categoryId = old('service_category_id', $data['row']->service_category_id);
        $data['subCategories'] = ServiceSubCategory::where('service_category_id', $categoryId)->rank()->pluck('title', 'id');

        return view(parent::loadCommonDataToView($this->view_path . '.edit'), compact('data'));
    }

    public function update(UpdateServiceValidation $request, $id)
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
}
