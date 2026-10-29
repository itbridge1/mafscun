@extends('admin.layout.frame')

@section('title', 'Suggestion List')
@section('css')
    <style>
        .ui-sortable-handle {
            cursor: all-scroll;
        }
    </style>
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Suggestion List</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item">
                                <a href="{{ url('/admin/dashboard') }}">
                                    <i class="fa fa-home"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">List Suggestions</li>
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
                                <h3 class="card-title">List of Suggestions</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>SN</th>
                                            <th>Name</th>
                                            <th>Organization</th>
                                            <th>Address</th>
                                            <th>Management Feedback</th>
                                            <th>Notification</th>
                                            <th>Report Feedback</th>
                                            <th>Policy Feedback</th>
                                            <th>Other Suggestions</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="ui-sortable">
                                        <!-- Assuming $suggestions is passed from the controller -->
                                        @if(isset($suggestions) && $suggestions->count() > 0)
                                            @foreach($suggestions as $key => $suggestion)
                                                <tr class="ui-sortable-handle">
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $suggestion->name }}</td>
                                                    <td>{{ $suggestion->organization }}</td>
                                                    <td>{{ $suggestion->address }}</td>
                                                    <td>{{ config('helper.feedback_options.management_feedback.' . $suggestion->management_feedback) }}</td>
                                                    <td>{{ config('helper.feedback_options.notification.' . $suggestion->notification) }}</td>
                                                    <td>{{ $suggestion->report_feedback }}</td>
                                                    <td>{{ $suggestion->policy_feedback }}</td>
                                                    <td>{{ $suggestion->other_suggestions }}</td>
                                                    <td>
                                                        <a href="javascript:void(0)" onclick="var c = confirm('Are you sure?'); if(c){document.getElementById('delete-{{ $suggestion->id }}').submit();}" class="btn btn-sm swalDefaultQuestion btn-danger">
                                                            Delete
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>SN</th>
                                            <th>Name</th>
                                            <th>Organization</th>
                                            <th>Address</th>
                                            <th>Management Feedback</th>
                                            <th>Notification</th>
                                            <th>Report Feedback</th>
                                            <th>Policy Feedback</th>
                                            <th>Other Suggestions</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                </table>

                                @if(isset($suggestions) && $suggestions->count() > 0)
                                    @foreach($suggestions as $key => $suggestion)
                                        <form id="delete-{{ $suggestion->id }}" action="{{ route('admin.suggestionform.destroy', $suggestion->id) }}" method="POST" style="display: none;">
                                            @method('DELETE')
                                            @csrf
                                        </form>
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
    <script src="{{ asset('backend/assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('backend/assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <script>
        $(function () {
            $("#example1").DataTable({
                "responsive": true, "lengthChange": true, "autoWidth": false, "info": true, "paging": true,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection

