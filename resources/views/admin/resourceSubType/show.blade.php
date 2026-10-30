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
                                        <th style="width: 200px;">Parent Resource Type</th>
                                        <td>{{ $data['row']->resourceType->title ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Title</th>
                                        <td>{{ $data['row']->title }}</td>
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
                                        <th>Description</th>
                                        <td>{!! $data['row']->description ?? '<span class="text-muted">No description</span>' !!}</td>
                                    </tr>
                                    <tr>
                                        <th>Resources Under This SubType ({{ $data['row']->resources->count() }})</th>
                                        <td>
                                            @if($data['row']->resources->count() > 0)
                                                <ul class="mb-0">
                                                    @foreach($data['row']->resources as $res)
                                                        <li>{{ $res->title }}</li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span class="text-muted">No resources added yet.</span>
                                            @endif
                                        </td>
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
