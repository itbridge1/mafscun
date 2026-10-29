@extends('admin.layout.frame')
@section('title', $_panel . ' Details')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6"><h1>{{ $_panel }} Details</h1></div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ $dashboard_url }}"><i class="fa fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route($_base_route . '.index') }}">{{ $_panel }}</a></li>
                            <li class="breadcrumb-item active">Details</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">{{ $data['row']->title }}</h3>
                        <div class="card-tools">
                            <a href="{{ route($_base_route . '.edit', $data['row']->id) }}" class="btn btn-sm btn-success">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <p><strong>Status:</strong> <span class="badge badge-{{ $data['row']->status ? 'success' : 'danger' }}">{{ $data['row']->status ? 'Active' : 'Inactive' }}</span></p>
                                <hr>
                                <h4>Details</h4>
                                <div>{!! $data['row']->details !!}</div>
                            </div>
                            <div class="col-md-4">
                                <h4>QR Code</h4>
                                @if($data['row']->qr_image)
                                    <img src="{{ \App\Facades\ViewHelper::getImagePath('paymentQr', $data['row']->qr_image) }}" class="img-fluid rounded border mb-3">
                                @else
                                    <p>No QR image uploaded.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
