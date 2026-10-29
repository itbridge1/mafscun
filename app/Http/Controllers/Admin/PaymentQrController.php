<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\PaymentQr\StorePaymentQrValidation;
use App\Http\Requests\Admin\PaymentQr\UpdatePaymentQrValidation;
use App\Models\PaymentQr;

class PaymentQrController extends BaseController
{
    protected $base_route = 'admin.paymentQr';
    protected $view_path  = 'admin.paymentQr';
    protected $panel      = 'Payment QR';
    protected $folder     = 'paymentQr';
    protected $folder_path;

    public function __construct(PaymentQr $model)
    {
        $this->model       = $model;
        $this->folder_path = 'images' . DIRECTORY_SEPARATOR . $this->folder;
        // $this->generateAllMiddlewareByPermission();
    }

    public function index()
    {
        $data['rows'] = $this->model->latest()->paginate(10);
        return view(parent::loadCommonDataToView($this->view_path . '.index'), compact('data'));
    }

    public function create()
    {
        return view(parent::loadCommonDataToView($this->view_path . '.create'));
    }

    public function store(StorePaymentQrValidation $request)
    {
        $data = $request->validated();

        if ($request->hasFile('main_qr')) {
            $this->processImage($request->file('main_qr'));
            $data['qr_image'] = $this->file_name;
        }
        unset($data['main_qr']);

        $row = $this->model->create($data);

        $request->session()->flash('success_message', $this->panel . ' "' . $row->title . '" added successfully');
        return redirect()->route($this->base_route . '.index');
    }

    public function show($id)
    {
        $data['row'] = $this->model->find($id);
        parent::rowExist($data['row']);
        return view(parent::loadCommonDataToView($this->view_path . '.show'), compact('data'));
    }

    public function edit($id)
    {
        $data['row'] = $this->model->find($id);
        parent::rowExist($data['row']);
        return view(parent::loadCommonDataToView($this->view_path . '.edit'), compact('data'));
    }

    public function update(UpdatePaymentQrValidation $request, $id)
    {
        $row  = $this->model->find($id);
        parent::rowExist($row);
        $data = $request->validated();

        if ($request->hasFile('main_qr')) {
            if ($row->qr_image) {
                @$this->removeFile($this->folder_path . DIRECTORY_SEPARATOR . $row->qr_image);
            }
            $this->processImage($request->file('main_qr'));
            $data['qr_image'] = $this->file_name;
        } else {
            $data['qr_image'] = $row->qr_image;
        }
        unset($data['main_qr']);

        $row->update($data);

        $request->session()->flash('success_message', $this->panel . ' "' . $row->title . '" updated successfully');
        return redirect()->route($this->base_route . '.index');
    }

    public function destroy($id)
    {
        $row = $this->model->find($id);
        parent::rowExist($row);

        if ($row->qr_image) {
            @$this->removeFile($this->folder_path . DIRECTORY_SEPARATOR . $row->qr_image);
        }
        $row->delete();

        return redirect()->route($this->base_route . '.index')
            ->with('success_message', $this->panel . ' deleted successfully');
    }
}
