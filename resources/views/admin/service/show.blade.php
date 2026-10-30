@extends('admin.layout.frame')
@section('title', 'Show ' . $_panel)

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ $_panel }} Details</h1>
                    </div>
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
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">{{ $data['row']->title }}</h3>
                                <div class="card-tools">
                                    <a href="{{ route($_base_route . '.edit', $data['row']->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <a href="{{ route($_base_route . '.index') }}" class="btn btn-sm btn-secondary">Back to List</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <tr>
                                        <th style="width: 200px;">Category</th>
                                        <td>{{ $data['row']->category->title ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Sub Category</th>
                                        <td>{{ $data['row']->subCategory->title ?? 'None (Direct Service)' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Title</th>
                                        <td>{{ $data['row']->title }}</td>
                                    </tr>
                                    <tr>
                                        <th>Sub Title</th>
                                        <td>{{ $data['row']->sub_title ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Slug</th>
                                        <td><code>{{ $data['row']->slug }}</code></td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>{!! $data['row']->status ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">InActive</span>' !!}</td>
                                    </tr>
                                    <tr>
                                        <th>Photo</th>
                                        <td>
                                            @if($data['row']->photo)
                                                <img src="{{ \App\Facades\ViewHelper::getImagePath($_folder, $data['row']->photo) }}" class="img-fluid rounded" style="max-height: 150px;">
                                            @else
                                                <span class="text-muted">No photo</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Description</th>
                                        <td>{!! $data['row']->description ?? '<span class="text-muted">No description</span>' !!}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
