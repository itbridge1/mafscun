@extends('admin.layout.frame')

@section('title', 'Messages & Commitment')
@section('css')
    <style>
        .ui-sortable-handle{
            cursor: all-scroll;
        }
    </style>
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('backend/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Messages &amp; Commitment</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{$dashboard_url}}">
                                    <i class="fa fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">List Messages &amp; Commitment</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">List of Messages &amp; Commitment (गृहपृष्ठ सन्देशहरू)</h3>
                                @can('aboutUs-create')
                                    <a href="{{route('admin.messageCommitment.create')}}" class="btn btn-primary float-right">
                                        <i class="fas fa-plus"></i>
                                        Add New Message / Commitment
                                    </a>
                                @endcan
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                @include('admin.layout.pagination')
                                {!!Form::open ([
                                    'url' => route('admin.messageCommitment.sort'),
                                    'id' =>'sorting-update-form'
                                 ]) !!}

                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>SN</th>
                                            <th>Title (शीर्षक)</th>
                                            <th>Designation / SubTitle (पद वा विवरण)</th>
                                            <th>Photo</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="ui-sortable">
                                    @if(isset($data['rows']) && $data['rows']->count() > 0)
                                        @foreach($data['rows'] as $key => $row)
                                            <tr class="ui-sortable-handle">
                                                <td class="center text-center">
                                                    <i class="fas fa-grip-vertical text-secondary mr-1" style="cursor: grab;" title="Drag to reorder"></i>
                                                    <label class="pos-rel mb-0">
                                                        <input type="hidden" name="ids[]" value="{{$row->id}}">
                                                        <input type="checkbox" class="ace" />
                                                        <span class="lbl"></span>
                                                    </label>
                                                </td>
                                                <td>{{$key+1}}</td>
                                                <td>
                                                  <strong>{{$row->title}}</strong>
                                                </td>
                                                <td>{{$row->sub_title}}</td>
                                                <td>
                                                    <a class="imageModalOpener"
                                                       data-toggle="modal"
                                                       data-src="{{ \App\Facades\ViewHelper::getImagePath($_folder,$row->photo) }}"
                                                       data-title="{{ $row->title ?? '' }}"
                                                       data-target=".imageModal">
                                                        <img src="{{ \App\Facades\ViewHelper::getImagePath($_folder,$row->photo) }}" width="50" height="50" style="object-fit: cover; border-radius: 6px;" alt="{{ $row->title }}">
                                                    </a>
                                                </td>
                                                <td>
                                                    {!!
                                                        $row->status?
                                                        '<span class="badge badge-success">Active</span>':
                                                        '<span class="badge badge-danger">InActive</span>'
                                                    !!}
                                                </td>
                                                <td>
                                                    @can('aboutUs-edit')
                                                        <a href="{{route('admin.messageCommitment.edit', $row->id)}}" class="btn btn-sm btn-success">
                                                            Edit
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    @endcan
                                                    @can('aboutUs-delete')
                                                        <a href="javascript:void(0)" onclick="var c = confirm('Are you sure you want to delete this message?'); if(c){document.getElementById('delete-{{$row->id}}').submit();}" class="btn btn-sm btn-danger">
                                                            Delete
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="text-center py-4 text-muted">
                                                <i class="fas fa-info-circle mr-1"></i> No message or commitment found. Click "Add New Message / Commitment" above to create one.
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th></th>
                                        <th>SN</th>
                                        <th>Title</th>
                                        <th>Designation / SubTitle</th>
                                        <th>Photo</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </tfoot>
                                </table>
                                {!! Form::close() !!}
                                @if(isset($data['rows']) && $data['rows']->count() > 0)
                                    @foreach($data['rows'] as $key => $row)
                                        {!! Form::open([
                                            'method' => 'DELETE',
                                            'route' => ['admin.messageCommitment.destroy', $row->id],
                                            'id' => 'delete-'.$row->id,
                                            'style' => 'display:none'
                                        ]) !!}
                                        {!! Form::close() !!}
                                    @endforeach
                                @endif
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
@section('js')
    <!-- DataTables  & Plugins -->
    <script src="{{asset('backend/assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('backend/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('backend/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('backend/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('backend/assets/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('backend/assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{asset('backend/assets/plugins/jszip/jszip.min.js')}}"></script>
    <script src="{{asset('backend/assets/plugins/pdfmake/pdfmake.min.js')}}"></script>
    <script src="{{asset('backend/assets/plugins/pdfmake/vfs_fonts.js')}}"></script>
    <script src="{{asset('backend/assets/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('backend/assets/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{asset('backend/assets/plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $(function () {
            $("#example1").DataTable({
                "responsive": true, "lengthChange": true, "autoWidth": false, "info": true, "paging": true, "ordering": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

            $('table tbody').sortable({
                helper: fixWidthHelper,
                cursor: "move",
                opacity: 0.8,
                update: function (event,ui) {
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
