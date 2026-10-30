@extends('admin.layout.frame')

@section('title', $_panel . ' List')
@section('css')
    <style>
        .ui-sortable-handle {
            cursor: all-scroll;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>{{ $_panel }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ $dashboard_url }}"><i class="fa fa-home"></i></a>
                            </li>
                            <li class="breadcrumb-item active">List {{ $_panel }}</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">List of {{ $_panel }}</h3>
                                @can('serviceSubCategory-create')
                                    <a href="{{ route($_base_route . '.create', ['category_id' => $data['selected_category']]) }}" class="btn btn-primary float-right">
                                        <i class="fas fa-plus"></i> Add New {{ $_panel }}
                                    </a>
                                @endcan
                            </div>

                            <div class="card-body">
                                <!-- Filter by Category -->
                                <form method="GET" action="{{ route($_base_route . '.index') }}" class="mb-3">
                                    <div class="row align-items-end">
                                        <div class="col-md-4">
                                            <label>Filter by Service Category</label>
                                            <select name="category_id" class="form-control" onchange="this.form.submit()">
                                                <option value="">-- All Categories --</option>
                                                @foreach($data['categories'] as $catId => $catTitle)
                                                    <option value="{{ $catId }}" {{ (string)$data['selected_category'] === (string)$catId ? 'selected' : '' }}>
                                                        {{ $catTitle }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @if($data['selected_category'])
                                            <div class="col-md-2">
                                                <a href="{{ route($_base_route . '.index') }}" class="btn btn-secondary">Clear Filter</a>
                                            </div>
                                        @endif
                                    </div>
                                </form>

                                @include('admin.layout.pagination')
                                {!! Form::open([
                                    'url' => route($_base_route . '.sort'),
                                    'id' => 'sorting-update-form'
                                ]) !!}

                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>SN</th>
                                            <th>Parent Category</th>
                                            <th>Title</th>
                                            <th>SubTitle</th>
                                            <th>Services</th>
                                            <th>Photo</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="ui-sortable">
                                    @if(isset($data['rows']) && $data['rows']->count() > 0)
                                        @foreach($data['rows'] as $key => $row)
                                            <tr class="ui-sortable-handle">
                                                <td class="center">
                                                    <label class="pos-rel">
                                                        <input type="hidden" name="ids[]" value="{{ $row->id }}">
                                                        <input type="checkbox" class="ace" />
                                                        <span class="lbl"></span>
                                                    </label>
                                                </td>
                                                <td>{{ $key + 1 }}</td>
                                                <td><span class="badge badge-info">{{ $row->category->title ?? 'N/A' }}</span></td>
                                                <td><strong>{{ $row->title }}</strong></td>
                                                <td>{{ $row->sub_title }}</td>
                                                <td>
                                                    <a href="{{ route('admin.service.index', ['sub_category_id' => $row->id]) }}" class="badge badge-primary">
                                                        {{ $row->services ? $row->services->count() : 0 }} Services
                                                    </a>
                                                </td>
                                                <td>
                                                    @if($row->photo)
                                                        <a class="imageModalOpener"
                                                           data-toggle="modal"
                                                           data-src="{{ \App\Facades\ViewHelper::getImagePath($_folder, $row->photo) }}"
                                                           data-title="{{ $row->title ?? '' }}"
                                                           data-target=".imageModal">
                                                            {{ $row->photo }}
                                                        </a>
                                                    @else
                                                        <span class="text-muted">No Image</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {!! $row->status ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">InActive</span>' !!}
                                                </td>
                                                <td>
                                                    <a href="{{ route($_base_route . '.show', $row->id) }}" class="btn btn-sm btn-info" title="View">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    @can('serviceSubCategory-edit')
                                                        <a href="{{ route($_base_route . '.edit', $row->id) }}" class="btn btn-sm btn-success" title="Edit">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    @endcan
                                                    @can('serviceSubCategory-delete')
                                                        <a href="javascript:void(0)" onclick="var c = confirm('Are you sure you want to delete this sub-category?'); if(c){document.getElementById('delete-{{ $row->id }}').submit();}" class="btn btn-sm btn-danger" title="Delete">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th></th>
                                            <th>SN</th>
                                            <th>Parent Category</th>
                                            <th>Title</th>
                                            <th>SubTitle</th>
                                            <th>Services</th>
                                            <th>Photo</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                </table>

                                {!! Form::close() !!}
                                @if(isset($data['rows']) && $data['rows']->count() > 0)
                                    @foreach($data['rows'] as $key => $row)
                                        <form id="delete-{{ $row->id }}" action="{{ route($_base_route . '.destroy', $row->id) }}" method="POST" style="display: none;">
                                            @method('DELETE')
                                            @csrf
                                        </form>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('js')
    <script src="{{ asset('backend/assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(function () {
            $("#example1").DataTable({
                "responsive": true, "lengthChange": true, "autoWidth": false, "info": true, "paging": true,
            });

            $('table tbody').sortable({
                helper: fixWidthHelper,
                update: function (event, ui) {
                    $('#sorting-update-form').submit();
                }
            }).disableSelection();

            function fixWidthHelper (e, ui) {
                ui.children().each(function () {
                    $(this).width($(this).width());
                });
                return ui;
            }
        });
    </script>
@endsection
