@extends('admin.layout.frame')

@section('title', $_panel . ' Details')

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
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">View {{ $_panel }}</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped">
                                    <tr>
                                        <th style="width: 25%;">Cooperative Name</th>
                                        <td>{{ $data['row']->cooperative_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>Member Number</th>
                                        <td>{{ $data['row']->member_no }}</td>
                                    </tr>
                                    <tr>
                                        <th>Address</th>
                                        <td>{{ $data['row']->address }}</td>
                                    </tr>
                                    <tr>
                                        <th>Rank</th>
                                        <td>{{ $data['row']->rank }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            <span class="badge badge-{{ $data['row']->status ? 'success' : 'danger' }}">
                                                {{ $data['row']->status ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route($_base_route . '.edit', $data['row']->id) }}" class="btn btn-success">Edit</a>
                                <a href="{{ route($_base_route . '.index') }}" class="btn btn-secondary ml-2">Back to List</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
