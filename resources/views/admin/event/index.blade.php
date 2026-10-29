@extends('admin.layout.frame')

@section('title', $_panel . ' List')
@section('css')
    <link rel="stylesheet" href="{{asset('backend/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6"><h1>{{ $_panel }}</h1></div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ $dashboard_url }}"><i class="fa fa-home"></i></a></li>
                            <li class="breadcrumb-item active">{{ $_panel }} List</li>
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
                                <a href="{{ route($_base_route . '.create') }}" class="btn btn-primary float-right">
                                    <i class="fas fa-plus"></i> Add New {{ $_panel }}
                                </a>
                            </div>
                            <div class="card-body">
                                @include('admin.layout.pagination')
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>SN</th>
                                            <th>Photo</th>
                                            <th>Title</th>
                                            <th>Total Seats</th>
                                            <th>Avail. Seats</th>
                                            <th>Reg. Deadline</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($data['rows'] as $key => $row)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                @if($row->photo)
                                                    <img src="{{ \App\Facades\ViewHelper::getImagePath('events', $row->photo) }}"
                                                         width="60" height="40" class="img-fluid rounded">
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>{{ $row->title }}</td>
                                            <td>{{ $row->total_seats }}</td>
                                            <td><span class="text-success font-weight-bold">{{ $row->availableSeats() }}</span></td>
                                            <td>
                                                @if($row->registration_deadline)
                                                    <span class="{{ $row->isRegistrationOpen() ? 'text-success' : 'text-danger font-weight-bold' }}">{{ $row->registration_deadline->format('Y-m-d') }}</span>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $row->status ? 'success' : 'danger' }}">
                                                    {{ $row->status ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.event.show', $row->id) }}" class="btn btn-sm btn-info">
                                                    <i class="fa fa-eye"></i> View
                                                </a>
                                                @php
                                                    $pendingForEvent = $row->bookings()->where('status', \App\Models\EventBooking::STATUS_PENDING)->count();
                                                @endphp
                                                <a href="{{ route('admin.event.bookings', $row->id) }}" class="btn btn-sm btn-warning">
                                                    <i class="fa fa-calendar-check-o"></i> Bookings
                                                    <span class="badge badge-{{ $pendingForEvent > 0 ? 'danger' : 'light' }} ml-1 text-dark" style="{{ $pendingForEvent == 0 ? 'background-color: rgba(255,255,255,0.7);' : '' }}">{{ $pendingForEvent }} Pending</span>
                                                </a>
                                                <a href="{{ route('admin.event.edit', $row->id) }}" class="btn btn-sm btn-success">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                                <a href="{{ route('admin.event.certificate.settings', $row->id) }}" class="btn btn-sm btn-dark">
                                                    <i class="fa fa-cog"></i> Certificate
                                                </a>
                                                <a href="javascript:void(0)"
                                                   onclick="var c=confirm('Are you sure?');if(c){document.getElementById('delete-{{ $row->id }}').submit();}"
                                                   class="btn btn-sm btn-danger">
                                                    <i class="fa fa-trash"></i> Delete
                                                </a>
                                                <form id="delete-{{ $row->id }}" action="{{ route('admin.event.destroy', $row->id) }}" method="POST" style="display:none;">
                                                    @method('DELETE') @csrf
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('js')
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
    <script>
        $(function () {
            $("#example1").DataTable({
                "responsive": true, "lengthChange": true, "autoWidth": false, "info": true, "paging": true,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>
@endsection
