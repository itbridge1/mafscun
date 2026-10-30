<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Resource\StoreResourceValidation;
use App\Http\Requests\Admin\Resource\UpdateResourceValidation;
use App\Models\Resource;
use App\Models\ResourceSubType;
use App\Models\ResourceType;
use Illuminate\Http\Request;

class ResourceController extends BaseController
{
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

    public function index(Request $request)
    {
        $data = [];
        $query = $this->model->with(['resourceType', 'subType'])->latest();

        if ($request->filled('type_id')) {
            $query->where('resource_type_id', $request->type_id);
        }

        if ($request->filled('sub_type_id')) {
            $query->where('resource_sub_type_id', $request->sub_type_id);
        }

        $data['rows'] = $query->paginate(config('helper.pagination_limit'));
        $data['resource_types'] = ResourceType::active()->rank()->pluck('title', 'id');
        $data['sub_types'] = $request->filled('type_id')
            ? ResourceSubType::where('resource_type_id', $request->type_id)->active()->rank()->pluck('title', 'id')
            : [];
        $data['selected_type'] = $request->get('type_id');
        $data['selected_sub_type'] = $request->get('sub_type_id');

        return view(parent::loadCommonDataToView($this->view_path . '.index'), compact('data'));
    }

    public function create(Request $request)
    {
        $data = [];
        $data['resource_types'] = ResourceType::active()->rank()->pluck('title', 'id');
        $typeId = old('resource_type_id', $request->get('type_id'));
        $data['selected_type'] = $typeId;
        $data['resource_sub_types'] = $typeId
            ? ResourceSubType::where('resource_type_id', $typeId)->active()->rank()->pluck('title', 'id')
            : [];

        return view(parent::loadCommonDataToView($this->view_path . '.create'), compact('data'));
    }

    public function store(StoreResourceValidation $request)
    {
        $data = $request->validated();

        if ($request->hasFile('main_file')) {
            $this->processImage($data['main_file']);
            $data['file'] = $this->file_name;
        } else {
            if ($data['type'] == 'link') {
                $data['file'] = $data['link'] ?? null;
            }
        }

        $resource = $this->model->create($data);

        $request->session()->flash('success_message', $this->panel . ' ' . $resource->title . ' added successfully');
        return redirect()->route($this->base_route . '.index');
    }

    public function show($id)
    {
        $data = [];
        $data['row'] = $this->model->with(['resourceType', 'subType'])->find($id);
        parent::rowExist($data['row']);

        return view(parent::loadCommonDataToView($this->view_path . '.show'), compact('data'));
    }

    public function edit($id)
    {
        $data = [];
        $data['row'] = $this->model->find($id);
        parent::rowExist($data['row']);
        $data['resource_types'] = ResourceType::active()->rank()->pluck('title', 'id');
        $typeId = old('resource_type_id', $data['row']->resource_type_id);
        $data['resource_sub_types'] = $typeId
            ? ResourceSubType::where('resource_type_id', $typeId)->active()->rank()->pluck('title', 'id')
            : [];

        return view(parent::loadCommonDataToView($this->view_path . '.edit'), compact('data'));
    }

    public function update(UpdateResourceValidation $request, $id)
    {
        $row = $this->model->find($id);
        parent::rowExist($row);

        $data = $request->validated();

        if ($request->hasFile('main_file')) {
            $this->processImage($data['main_file']);
            $data['file'] = $this->file_name;
            if ($row->file) {
                @$this->removeFile($this->folder_path . DIRECTORY_SEPARATOR . $row->file);
            }
        }
        if ($data['type'] == 'link') {
            if ($row->file && !str_starts_with($row->file, 'http')) {
                @$this->removeFile($this->folder_path . DIRECTORY_SEPARATOR . $row->file);
            }
            $data['file'] = $data['link'] ?? null;
        }

        $row->update($data);

        $request->session()->flash('success_message', $this->panel . ' ' . $row->title . ' updated successfully');
        return redirect()->route($this->base_route . '.index');
    }

    public function destroy($id)
    {
        $row = $this->model->find($id);
        parent::rowExist($row);

        if ($row->file && !str_starts_with($row->file, 'http')) {
            @$this->removeFile($this->folder_path . DIRECTORY_SEPARATOR . $row->file);
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
