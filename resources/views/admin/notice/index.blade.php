@extends('admin.layout.frame')

@section('title',$_panel.' List')
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
                        <div class="card card-success">
                            <div class="card-header">
                                <h3 class="card-title">
                                    Filter <small>(Click + to see advanced {{ $_panel }} filter)</small>
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for=""> Start Date</label>
                                            {!!  Form::date('filter_start_date',request()->has('filter_start_date') ? request('filter_start_date'):null,[
                                                    'class' => 'form-control form-control-sm',
                                                    'id' => 'filter_start_date',

                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for=""> End Date</label>
                                            {!!  Form::date('filter_end_date',request()->has('filter_end_date') ? request('filter_end_date'):null,[
                                                    'class' => 'form-control form-control-sm',
                                                    'id' => 'filter_end_date'
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Popup</label>
                                            {!!  Form::select('filter_popup',['1'=>'ON', '0' => 'OFF'],request()->has('filter_popup') ? request('filter_popup'):null,[
                                                    'class' => 'form-control form-control-sm',
                                                    'id' => 'filter_popup',
                                                    'placeholder' => '--SELECT POPUP--'
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Career</label>
                                            {!!  Form::select('filter_career',['1'=>'YES', '0' => 'No'],request()->has('filter_career') ? request('filter_career'):null,[
                                                    'class' => 'form-control form-control-sm',
                                                    'id' => 'filter_career',
                                                    'placeholder' => '--SELECT CAREER--'
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">Status</label>
                                            {!!  Form::select('filter_status',['1'=>'Active', '0' => 'InActive'],request()->has('filter_status') ? request('filter_status'):null,[
                                                    'class' => 'form-control form-control-sm',
                                                    'id' => 'filter_status',
                                                    'placeholder' => '--SELECT STATUS--'
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="">&nbsp;</label>
                                            <button class="form-control form-control-sm btn-sm btn-success"
                                                    type="submit" id="filter-btn">
                                                <i class="fa fa-filter"></i>
                                                Filter
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

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
                                {!!Form::open ([
                                    'url' => route($_base_route.'.sort'),
                                    'id' =>'sorting-update-form'
                                 ]) !!}

                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>SN</th>
                                            <th>Title</th>
                                            <th>SubTitle</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Popup</th>
                                            <th>Career</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="ui-sortable">
                                    @if(isset($data['rows']) && $data['rows'] -> count () >= 0)
                                        @foreach($data['rows'] as $key => $row)
                                            <tr class="ui-sortable-handle">
                                                <td class="center">
                                                    <label class="pos-rel">
                                                        <input type="hidden" name="ids[]" value="{{$row->id}}">
                                                        <input type="checkbox" class="ace" />
                                                        <span class="lbl"></span>
                                                    </label>
                                                </td>
                                                <td>{{$key+1}}</td>
                                                <td>
                                                  {{$row->title}}
                                                </td>
                                                <td>{{$row->sub_title}}</td>
                                                <td>{{$row->start_date }}</td>
                                                <td>{{$row->end_date }}</td>
                                                <td>
                                                    {!!
                                                        $row->is_popup?
                                                        '<span class="badge badge-success">ON</span>':
                                                        '<span class="badge badge-danger">OFF</span>'
                                                    !!}
                                                </td>
                                                <td>
                                                    {!!
                                                        $row->is_career?
                                                        '<span class="badge badge-success">YES</span>':
                                                        '<span class="badge badge-danger">NO</span>'
                                                    !!}
                                                </td>
                                                <td>
                                                    {!!
                                                        $row->status?
                                                        '<span class="badge badge-success">Active</span>':
                                                        '<span class="badge badge-danger">InActive</span>'
                                                    !!}
                                                </td>
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
                                        <th>Title</th>
                                        <th>SubTitle</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Popup</th>
                                        <th>Career</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </tfoot>
                                </table>

                                {!! Form::close() !!}
                                @if(isset($data['rows']) && $data['rows'] -> count () >= 0)
                                    @foreach($data['rows'] as $key => $row)
                                        <form id="delete-{{$row->id}}" action="{{ route($_base_route.'.destroy',$row->id) }}" method="POST"  style="display: none;">
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
            $('#filter-btn').click(function () {
                var url ='{{route($_base_route.'.index')}}';
                var flag = false;
                var filter_ids = ['filter_start_date','filter_end_date', 'filter_popup', 'filter_career', 'filter_status'];

                $.each(filter_ids, function (index, value) {
                    if($('#'+value).val() !== '' && $('#'+value).val() !== null && $('#'+value).val() !== 'null') {

                        if(flag== false){
                            url= url+ '?' +value +'=' + $('#'+value).val();
                            flag= true;
                        } else {
                            url= url+ '&' +value +'=' + $('#'+value).val();
                        }
                    }
                });
                location.href=url;
            })

            $("#example1").DataTable({
                "responsive": true, "lengthChange": true, "autoWidth": false,"info": true,"paging": true,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

            $('table tbody').sortable({
                helper: fixWidthHelper,
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
