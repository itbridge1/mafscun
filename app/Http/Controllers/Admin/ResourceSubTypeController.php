<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ResourceSubType\StoreResourceSubTypeValidation;
use App\Http\Requests\Admin\ResourceSubType\UpdateResourceSubTypeValidation;
use App\Models\ResourceSubType;
use App\Models\ResourceType;
use Illuminate\Http\Request;

class ResourceSubTypeController extends BaseController
{
    protected $base_route = 'admin.resourceSubType';
    protected $view_path = 'admin.resourceSubType';
    protected $panel = 'Resource Sub Type';

    public function __construct(ResourceSubType $model)
    {
        $this->model = $model;
        $this->generateAllMiddlewareByPermission();
    }

    public function index(Request $request)
    {
        $data = [];
        $query = $this->model->with(['resourceType', 'resources'])->rank();

        if ($request->filled('type_id')) {
            $query->where('resource_type_id', $request->type_id);
        }

        $data['rows'] = $query->paginated();
        $data['types'] = ResourceType::rank()->pluck('title', 'id');
        $data['selected_type'] = $request->get('type_id');

        return view(parent::loadCommonDataToView($this->view_path . '.index'), compact('data'));
    }

    public function create(Request $request)
    {
        $data = [];
        $data['types'] = ResourceType::rank()->pluck('title', 'id');
        $data['selected_type'] = $request->get('type_id');

        return view(parent::loadCommonDataToView($this->view_path . '.create'), compact('data'));
    }

    public function store(StoreResourceSubTypeValidation $request)
    {
        $data = $request->validated();
        $row = $this->model->create($data);

        $request->session()->flash('success_message', $this->panel . ' ' . $row->title . ' added successfully');
        return redirect()->route($this->base_route . '.index');
    }

    public function show($id)
    {
        $data = [];
        $data['row'] = $this->model->with(['resourceType', 'resources'])->find($id);
        parent::rowExist($data['row']);

        return view(parent::loadCommonDataToView($this->view_path . '.show'), compact('data'));
    }

    public function edit($id)
    {
        $data = [];
        $data['row'] = $this->model->find($id);
        parent::rowExist($data['row']);
        $data['types'] = ResourceType::rank()->pluck('title', 'id');

        return view(parent::loadCommonDataToView($this->view_path . '.edit'), compact('data'));
    }

    public function update(UpdateResourceSubTypeValidation $request, $id)
    {
        $row = $this->model->find($id);
        parent::rowExist($row);

        $row->update($request->validated());

        $request->session()->flash('success_message', $this->panel . ' ' . $row->title . ' updated successfully');
        return redirect()->route($this->base_route . '.index');
    }

    public function destroy($id)
    {
        $row = $this->model->find($id);
        parent::rowExist($row);

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

    public function byType($typeId)
    {
        $subTypes = $this->model
            ->where('resource_type_id', $typeId)
            ->rank()
            ->get(['id', 'title']);

        return response()->json($subTypes);
    }
}
