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
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">List of {{$_panel}}</h3>
                                <a href="{{ route('admin.downloadQRFolder') }}"  class="btn btn-secondary float-right mr-2">
                                    <i class="fas fa-download"></i>
                                    Download QRCode Image ZIP
                                </a>
                                <a href="{{ route('admin.downloadPassportFolder') }}"  class="btn btn-secondary float-right mr-2">
                                    <i class="fas fa-download"></i>
                                    Download Passport Size Image ZIP
                                </a>
                                <a href="{{ route('admin.downloadAgmExcel') }}"  class="btn btn-secondary float-right mr-2">
                                    <i class="fas fa-download"></i>
                                    Download Excel
                                </a>
                               
                                {{-- @can(lcfirst($_panel).'-create')
                                    <a href="{{route($_base_route.'.create')}}" class="btn btn-primary float-right">
                                        <i class="fas fa-plus"></i>
                                        Add New {{$_panel}}
                                    </a>
                                   
                                @endcan --}}
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">

                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>   
                                            <th>SN</th>
                                            <th>Name</th>
                                            <th>Nepali Name</th>
                                            <th>Gender</th>
                                            <th>Saccos Union</th>
                                            <th>Cooperative Name</th>
                                            <th>Post</th>
                                            <th>Email</th>
                                            <th>District</th>
                                            <th>Municipality</th>
                                            <th>Ward No</th>
                                            <th>Membership No</th>
                                            <th>Mobile No</th>
                                            <th>Nomination Form</th>
                                            <th>Photo</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="ui-sortable">
                                    @if(isset($data['rows']) && $data['rows'] -> count () >= 0)
                                        @foreach($data['rows'] as $key => $row)
                                            <tr class="ui-sortable-handle">
                                               
                                                <td>{{$key+1}}</td>
                                                
                                                <td>{{$row->name}}</td>
                                                <td>{{$row->nepali_name}}</td>
                                                <td>{{$row->gender}}</td>
                                                <td>{{$row->saccos_union}}</td>
                                                <td>{{$row->cooperative_name}}</td>
                                                <td>{{$row->post}}</td>
                                                <td>{{$row->email??'-'}}</td>
                                                <td>{{$row->district->name}}</td>
                                                <td>{{$row->municipality->name}}</td>
                                                <td>{{$row->ward_no}}</td>
                                                <td>{{$row->membership_no}}</td>
                                                <td>{{$row->mobile_no}}</td>
                                                <td> 
                                                    <a href="{{ ViewHelper::getImagePath('AgmForm/NominationForm', $row->nomination_form) }}" target="_blank" class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i>  
                                                    </a>
                                                    <a href="{{ ViewHelper::getImagePath('AgmForm/NominationForm', $row->nomination_form) }}" download class="btn btn-success btn-sm">
                                                        <i class="fas fa-download"></i>  
                                                    </a>
                                                </td>
                                                
                                                <td>
                                                    @if($row->photo)
                                                        <img src="{{ asset('images/AgmForm/PassportSize/' . $row->photo) }}" alt="Photo" style="width: 80px; height: auto;">
                                                    @else
                                                        <span class="text-muted">—</span>
                                                    @endif
                                                </td>
                                                <td>
                                                     
                                                        @if($row->status == 1)
                                                            <span class="badge badge-success">Approved</span>
                                                        @else
                                                            <form action="{{ route('admin.AgmApprove',$row->id) }}" method="POST" style="display:inline;">
                                                                @csrf
                                                                 <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                                            </form>
                                                        @endif

                                                        {{-- <a href="review.html" class="btn btn-sm btn-warning">Review</a> --}}

                                                      
                                                </td>
                                                <td>
                                                    {{-- @can(lcfirst($_panel).'-edit') --}}
                                                        <a href="{{route($_base_route.'.edit', $row->id)}}" class="btn btn-sm btn-success">
                                                            Edit
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    {{-- @endcan --}}
                                                    {{-- @can(lcfirst($_panel).'-delete') --}}
                                                        <a href="javascript:void(0)" onclick="var c = confirm('{{ "Are you sure?" }}'); if(c){document.getElementById('delete-{{$row->id}}').submit();}" class="btn btn-sm swalDefaultQuestion btn-danger">
                                                            Delete
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    {{-- @endcan --}}
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
                                        <th>Nepali Name</th>
                                        <th>Gender</th>
                                        <th>Saccos Union</th>
                                        <th>Post</th>
                                        <th>District</th>
                                        <th>Municipality</th>
                                        <th>Ward No</th>
                                        <th>Membership No</th>
                                        <th>Mobile No</th>
                                        <th>Nomination Form</th>
                                        <th>Photo</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </tfoot>
                                </table>

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
{{--    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>--}}
    <script>
        $(function () {
            $("#example1").DataTable({
                "responsive": true, "lengthChange": true, "autoWidth": false,"info": true,"paging": true,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

            // $('table tbody').sortable({
            //     helper: fixWidthHelper,
            //     update: function (event,ui) {
            //         $('#sorting-update-form').submit();
            //     }
            // }).disableSelection();

            // function fixWidthHelper (e, ui) {
            //     ui.children().each(function () {
            //         $(this).width($(this).width());
            //     });
            //     return ui;
            // }
        });
        
    </script>
     <script>
        $(document).ready(function() {
            $('#download-passport').on('click', function() {
                $.ajax({
                    url: "{{ route('admin.downloadPassportFolder') }}",
                    method: 'POST',
                    data:{
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        // Handle the response here
                        alert('Download started successfully.');
                    },
                    error: function(xhr) {
                        // Handle the error here
                        alert('An error occurred while starting the download.');
                    }
                });
            });
        });
    </script>
@endsection
