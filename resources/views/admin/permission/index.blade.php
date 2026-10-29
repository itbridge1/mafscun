@extends('admin.layout.frame')

@section('title',$_panel.' List')
@section('css')
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
                        <h1>{{$_panel}}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{$dashboard_url}}">
                                    <i class="fa fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">List {{$_panel}}</li>
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
                                <h3 class="card-title">List of {{$_panel}}</h3>
                                @can(lcfirst($_panel).'-create')
                                    <a href="{{route($_base_route.'.create')}}" class="btn btn-primary float-right">
                                        <i class="fas fa-plus"></i>
                                        Add New {{$_panel}}
                                    </a>
                                @endcan
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                @include('admin.layout.pagination')
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>SN</th>
                                            <th>Name</th>
                                            <th>DisplayName</th>
                                            <th>Group</th>
                                            <th>GroupHead</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @if($data['rows'] -> count () >= 0)
                                        @foreach($data['rows'] as $key => $row)
                                            <tr>
                                                <td class="center">
                                                    <label class="pos-rel">
                                                        <input type="checkbox" class="ace" />
                                                        <span class="lbl"></span>
                                                    </label>
                                                </td>
                                                <td>{{$key+1}}</td>
                                                <td>
                                                  {{$row->name}}
                                                </td>
                                                <td>{{$row->display_name}}</td>
                                                <td>{{$row->group}}</td>
                                                <td>{{$row->group_head?'YES':'NO'}}</td>
                                                <td>
                                                    @can(lcfirst($_panel).'-edit')
                                                        <a href="{{route($_base_route.'.edit', $row->id)}}" class="btn btn-sm btn-success">
                                                            Edit
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    @endcan
                                                    @can(lcfirst($_panel).'-delete')
                                                        <a href="javascript:void(0)" onclick="var c = confirm('{{ "Are you sure?" }}'); if(c){document.getElementById('delete-{{$row->id}}').submit();}" class="btn btn-sm swalDefaultQuestion btn-danger">
                                                            Delete
                                                            <i class="fa fa-trash"></i>
                                                            <form id="delete-{{$row->id}}" action="{{ route($_base_route.'.destroy',$row->id) }}" method="POST"  style="display: none;">
                                                                @method('DELETE')
                                                                @csrf
                                                            </form>
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
                                        <th>Name</th>
                                        <th>DisplayName</th>
                                        <th>Group</th>
                                        <th>GroupHead</th>
                                        <th>Action</th>
                                    </tr>
                                    </tfoot>
                                </table>
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


    <script>
        $(function () {
            $("#example1").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,"info": true,"paging": true,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

            $('.swalDefaultQuestion').click(function() {
                Toast.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                })
            });
        });
    </script>
@endsection
