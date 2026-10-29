@extends('admin.layout.frame')
@section('title', $_panel . ' Details')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6"><h1>{{ $_panel }} Details</h1></div>
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
                <!-- Dashboard Metrics Row -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $data['stats']['total_seats'] }}</h3>
                                <p>Total Capacity (Seats)</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-chair"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $data['stats']['available_seats'] }}</h3>
                                <p>Available Seats</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-ticket-alt"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3>{{ $data['stats']['present_participants'] }} <sup style="font-size: 20px">{{ $data['stats']['present_percentage'] }}%</sup></h3>
                                <p>Attendees Present</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-check"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $data['stats']['absent_participants'] }} <sup style="font-size: 20px">{{ $data['stats']['absent_percentage'] }}%</sup></h3>
                                <p>Attendees Absent</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-times"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">{{ $data['row']->title }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.event.bookings', $data['row']->id) }}" class="btn btn-sm btn-info mr-2">
                                <i class="fa fa-users"></i> View Bookings
                            </a>
                            <a href="{{ route($_base_route . '.edit', $data['row']->id) }}" class="btn btn-sm btn-success">
                                <i class="fa fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <p><strong>Status:</strong> <span class="badge badge-{{ $data['row']->status ? 'success' : 'danger' }}">{{ $data['row']->status ? 'Active' : 'Inactive' }}</span></p>
                                <p><strong>Short Description:</strong> {{ $data['row']->short_description }}</p>
                                <p><strong>Remarks:</strong> {{ $data['row']->remarks }}</p>
                                <hr>
                                <h4>Description</h4>
                                <div>{!! $data['row']->description !!}</div>
                            </div>
                            <div class="col-md-4">
                                @if($data['row']->photo)
                                    <img src="{{ \App\Facades\ViewHelper::getImagePath('events', $data['row']->photo) }}" class="img-fluid rounded mb-3">
                                @endif
                                
                                @if($data['row']->google_map)
                                    <div class="embed-responsive embed-responsive-16by9">
                                        {!! $data['row']->google_map !!}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <hr>
                        <h4>Routines &amp; Pricing</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>About</th>
                                        <th>Price</th>
                                        <th>Type</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data['row']->routines as $routine)
                                        <tr>
                                            <td>{{ $routine->display_schedule }}</td>
                                            <td>{{ $routine->about }}</td>
                                            <td>Rs. {{ number_format($routine->event_price, 2) }}</td>
                                            <td>
                                                @if($routine->price_type === 'per_group' && $routine->group_size)
                                                    Per Group ({{ $routine->group_size }} persons)
                                                @else
                                                    Per Person
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
