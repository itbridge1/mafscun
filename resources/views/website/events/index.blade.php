@extends('website.common.layout')

@section('title', "Events")

@section('meta')
    @include('website.common.meta', [
    '_title' => "Events",
    '_detail' => "List of all upcoming and past events.",
    '_folder' => 'events',
    '_photo' => null,
])
@endsection

@section('css')
<style>
    .event-card {
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #e2eae4;
        background: #fff;
        transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
    }
    .event-card:hover {
        transform: translateY(-3px);
        border-color: #b8d8c1;
        box-shadow: 0 14px 34px rgba(19, 54, 38, .1) !important;
    }
    .event-card-header {
        border-bottom: 1px solid #e8eee9 !important;
        position: relative;
        background: linear-gradient(110deg, #fff, #f8fbf8);
    }
    .event-title {
        font-family: inherit;
        color: #18352a;
        line-height: 1.3;
        margin-bottom: 0;
    }
    .event-title a {
        color: #18352a;
        text-decoration: none;
        transition: color 0.2s;
    }
    .event-title a:hover {
        color: #08784b;
    }
    .event-info-icon {
        font-size: 1.25rem;
        color: #00b4d8;
        flex-shrink: 0;
    }
    .btn-event-outline {
        border-radius: 20px;
        font-weight: 500;
        border: 1px solid #b8d8c1;
        color: #08784b;
        background-color: transparent;
        transition: all 0.2s;
        text-align: center;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .btn-event-outline:hover {
        background-color: #08784b;
        color: #fff !important;
    }
    .btn-event-primary {
        border-radius: 20px;
        font-weight: 500;
        background-color: #08784b;
        border: 1px solid #08784b;
        color: #ffffff !important;
        transition: all 0.2s;
        text-align: center;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .btn-event-primary:hover {
        background-color: #075b3b;
        border-color: #075b3b;
    }

    .events-page-content { max-width: 1240px !important; padding-top: 4px; padding-bottom: 48px; }
    .events-filter-card { display: flex; align-items: center; justify-content: space-between; gap: 24px; margin: 0 0 28px; padding: 22px 24px; border: 1px solid #e2eae4; border-radius: 15px; background: #fff; box-shadow: 0 8px 26px rgba(19,54,38,.045); }
    .events-filter-heading { display: flex; flex: 0 0 auto; align-items: center; gap: 12px; }
    .events-filter-icon { display: grid; width: 44px; height: 44px; place-items: center; border-radius: 12px; background: #edf7ef; color: #08784b; font-size: 1rem; }
    .events-filter-heading h2 { margin: 0 0 3px; color: #18352a; font-size: 1rem; font-weight: 750; }
    .events-filter-heading p { margin: 0; color: #718078; font-size: .78rem; }
    .events-filter-form { display: flex; flex: 1 1 auto; align-items: center; justify-content: flex-end; gap: 10px; }
    .events-filter-form .form-group { flex: 1 1 280px; max-width: 440px; margin: 0; }
    .events-filter-form .form-control { height: 44px; }
    .events-filter-submit, .events-filter-reset { display: inline-flex; height: 44px; align-items: center; justify-content: center; gap: 8px; padding: 0 16px; border-radius: 9px; font-size: .82rem; font-weight: 700; white-space: nowrap; }
    .events-filter-submit { border: 1px solid #08784b; background: #08784b; color: #fff; }
    .events-filter-submit:hover { background: #075b3b; color: #fff; }
    .events-filter-reset { border: 1px solid #d7e2da; background: #fff; color: #53645a; }
    .events-filter-reset:hover { border-color: #b8d8c1; color: #08784b; text-decoration: none; }
    .events-results-bar { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 15px; color: #718078; font-size: .82rem; }
    .events-results-count { display: inline-flex; align-items: center; gap: 8px; color: #30463b; font-weight: 700; }
    .events-results-count span { width: 8px; height: 8px; border-radius: 50%; background: #20a160; box-shadow: 0 0 0 4px #eaf5ed; }
    .events-empty-state { padding: 48px 22px; border: 1px dashed #cddfd2; border-radius: 15px; background: #fff; color: #64756b; text-align: center; }
    .events-empty-state i { display: grid; width: 54px; height: 54px; margin: 0 auto 14px; place-items: center; border-radius: 16px; background: #f0f6f1; color: #08784b; font-size: 1.2rem; }
    .events-empty-state h3 { margin: 0 0 5px; color: #18352a; font-size: 1.1rem; font-weight: 750; }
    .events-empty-state p { margin: 0; color: #718078; font-size: .88rem; }

    /* Mobile specific styling (< 992px) */
    @media (max-width: 991.98px) {
        .events-filter-card { align-items: stretch; flex-direction: column; gap: 16px; }
        .events-filter-form { justify-content: stretch; }
        .events-filter-form .form-group { max-width: none; }
        .event-card-header {
            padding: 1rem !important;
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 10px;
            min-height: auto !important;
        }
        .event-header-top {
            display: flex;
            align-items: center;
            width: 100%;
        }
        .event-header-img {
            width: 60px !important;
            height: 60px !important;
            border-radius: 6px !important;
            margin-right: 12px !important;
            object-fit: cover;
            flex-shrink: 0;
        }
        .event-title {
            font-size: 1.25rem !important;
        }
        .event-badges-container {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            width: 100%;
            margin-top: 4px;
        }
        .event-badges-container .badge {
            font-size: 0.8rem !important;
            padding: 4px 10px !important;
        }
        .event-card-body {
            padding: 1rem !important;
            flex-direction: column !important;
            gap: 12px;
        }
        .event-meta-col {
            width: 100% !important;
            flex: none !important;
        }
        .event-actions-col {
            width: 100% !important;
            flex: none !important;
            flex-direction: row !important;
            gap: 10px;
            padding-top: 12px;
            border-top: 1px dashed #e9ecef;
            margin-top: 4px;
        }
        .event-actions-col .btn {
            flex: 1 1 0;
            width: auto !important;
            padding: 8px 12px !important;
            font-size: 0.9rem;
            white-space: nowrap;
        }
    }

    /* Desktop styling (>= 992px) */
    @media (min-width: 992px) {
        .event-card-header {
            padding: 1.5rem 1.5rem 1rem 1.5rem;
            display: flex;
            align-items: center;
            min-height: 100px;
        }
        .event-header-top {
            display: flex;
            align-items: center;
            flex-grow: 1;
        }
        .event-header-img {
            width: 80px;
            height: 80px;
            border-radius: 8px;
            margin-right: 1rem;
            object-fit: cover;
            flex-shrink: 0;
        }
        .event-title {
            font-size: 1.55rem;
        }
        .event-badges-container {
            display: flex;
            align-items: center;
            flex-shrink: 0;
            margin-left: 1rem;
        }
        .event-card-body {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.25rem 1.5rem 1.5rem 1.5rem;
            gap: 15px;
        }
        .event-meta-date {
            flex: 1.1;
        }
        .event-meta-location {
            flex: 1.2;
        }
        .event-meta-seats {
            flex: 0.7;
        }
        .event-actions-col {
            flex: 0.8;
            display: flex;
            flex-direction: column;
            gap: 8px;
            align-items: stretch;
        }
        .event-actions-col .btn {
            width: 100%;
        }
    }
    @media (max-width: 575.98px) {
        .events-filter-card { padding: 17px; }
        .events-filter-form { flex-wrap: wrap; }
        .events-filter-form .form-group { flex-basis: 100%; }
        .events-filter-submit, .events-filter-reset { flex: 1 1 auto; }
        .events-results-bar { align-items: flex-start; flex-direction: column; }
    }
</style>
@endsection

@section('content')
    @include('website.common.page-heading')
    <div class="container-xl events-page-content">

        <!-- Filter Section -->
        <section class="events-filter-card" aria-label="Find events">
            <div class="events-filter-heading">
                <span class="events-filter-icon" aria-hidden="true"><i class="fa fa-calendar"></i></span>
                <div>
                    <h2>Find an event</h2>
                    <p>Search upcoming and past events</p>
                </div>
            </div>
            <form class="events-filter-form" action="{{ route('events.index') }}" method="GET" role="search">
                <div class="form-group">
                    <label for="event_name" class="sr-only">Event name</label>
                    <input type="search" name="event_name" id="event_name" class="form-control" placeholder="Search by event name" value="{{ request('event_name') }}">
                </div>
                <button type="submit" class="events-filter-submit"><i class="fa fa-search" aria-hidden="true"></i> Search events</button>
                @if(request()->filled('event_name'))
                    <a href="{{ route('events.index') }}" class="events-filter-reset"><i class="fa fa-times" aria-hidden="true"></i> Clear</a>
                @endif
            </form>
        </section>

        <div class="events-results-bar">
            <span class="events-results-count"><span aria-hidden="true"></span>{{ $_events->total() }} {{ \Illuminate\Support\Str::plural('event', $_events->total()) }}</span>
            <span>Browse events and their booking details</span>
        </div>

        <!-- Event List -->
        <div class="row">
            @if(isset($_events) && $_events->count() > 0)
                @foreach($_events as $event)
                    <div class="col-12 mb-4">
                        <div class="event-card shadow-sm">
                            <div class="event-card-header d-flex position-relative">
                                <div class="event-header-top position-relative" style="z-index: 2;">
                                    @if($event->photo)
                                        <img src="{{ \App\Facades\ViewHelper::getImagePath('events', $event->photo) }}" class="event-header-img" alt="{{ $event->title }}">
                                    @endif
                                    
                                    <h4 class="event-title flex-grow-1">
                                        <a href="{{ route('events.show', $event->slug) }}">{{ $event->title }}</a>
                                    </h4>
                                </div>
                                
                                <div class="event-badges-container position-relative" style="z-index: 2;">
                                    @if(!$event->isRegistrationOpen())
                                        <span class="badge px-3 py-2 mr-1 mr-md-2" style="background-color: #e2e3e5; color: #383d41; border: 1px solid #d6d8db; font-weight: normal; font-size: 0.9rem;">Booking Expired</span>
                                    @elseif($event->availableSeats() > 0)
                                        @if($event->registration_deadline)
                                            <span class="badge px-3 py-2 mr-1 mr-md-2" style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; font-weight: normal; font-size: 0.9rem;">Booking Till {{ $event->registration_deadline->format('M d') }}</span>
                                        @else
                                            <span class="badge px-3 py-2 mr-1 mr-md-2" style="background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; font-weight: normal; font-size: 0.9rem;">Booking Open</span>
                                        @endif
                                    @else
                                        <span class="badge px-3 py-2 mr-1 mr-md-2" style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; font-weight: normal; font-size: 0.9rem;">Booking Closed</span>
                                    @endif
                                    
                                    @php
                                        $today = \Carbon\Carbon::today()->format('Y-m-d');
                                        if ($event->start_date && $event->start_date->format('Y-m-d') > $today) {
                                            $statusLabel = 'Upcoming';
                                            $statusBg = '#fff3cd';
                                            $statusColor = '#856404';
                                            $statusBorder = '#ffeeba';
                                        } elseif ($event->isEventExpired()) {
                                            $statusLabel = 'Expired';
                                            $statusBg = '#f8d7da';
                                            $statusColor = '#721c24';
                                            $statusBorder = '#f5c6cb';
                                        } else {
                                            $statusLabel = 'Live';
                                            $statusBg = '#d4edda';
                                            $statusColor = '#155724';
                                            $statusBorder = '#c3e6cb';
                                        }
                                    @endphp
                                    <span class="badge px-3 py-2" style="background-color: {{ $statusBg }}; color: {{ $statusColor }}; border: 1px solid {{ $statusBorder }}; font-weight: normal; font-size: 0.9rem;">{{ $statusLabel }}</span>
                                </div>
                            </div>
                            
                            <div class="event-card-body d-flex position-relative bg-white" style="z-index: 2;">
                                {{-- Date Section --}}
                                <div class="event-meta-col event-meta-date d-flex align-items-start">
                                    <i class="fa fa-calendar-o event-info-icon mr-3 mt-1"></i>
                                    <div>
                                        @if($event->start_date_bs || $event->start_date)
                                            {{-- BS Date --}}
                                            @if($event->start_date_bs)
                                                <div style="font-weight: 600; color: #333; font-size: 1rem; line-height: 1.3;">
                                                    {{ $event->start_date_bs }}
                                                    @if($event->end_date_bs)
                                                        <span class="text-muted" style="font-size: 0.88rem;"> &mdash; {{ $event->end_date_bs }}</span>
                                                    @endif
                                                    <small class="text-muted" style="font-size: 0.75rem;"> BS</small>
                                                </div>
                                            @endif
                                            {{-- AD Date --}}
                                            @if($event->start_date)
                                                <div style="font-size: 0.85rem; color: #777; margin-top: 2px;">
                                                    {{ $event->start_date->format('M d, Y') }}
                                                    @if($event->end_date)
                                                        &ndash; {{ $event->end_date->format('M d, Y') }}
                                                    @endif
                                                    <small class="text-muted"> AD</small>
                                                </div>
                                            @endif
                                            @if($event->registration_deadline)
                                                <div style="font-size: 0.85rem; margin-top: 4px;">
                                                    <span class="text-danger" style="font-weight:600;"><i class="fa fa-exclamation-circle"></i> Apply Deadline:</span>
                                                    <span class="text-dark">{{ $event->registration_deadline->format('M d, Y') }}</span>
                                                </div>
                                            @endif
                                        @else
                                            <div style="color: #999;">Date not specified</div>
                                        @endif
                                    </div>
                                </div>
                                
                                @php
                                    $locationName = $event->remarks ?? 'Location not specified';
                                    if ($event->google_map) {
                                        if (preg_match('/!2s([^!&"]+)/', $event->google_map, $matches)) {
                                            $locationName = urldecode(str_replace('+', ' ', $matches[1]));
                                        }
                                    }
                                @endphp
                                
                                {{-- Location Section --}}
                                <div class="event-meta-col event-meta-location d-flex align-items-start">
                                    <i class="fa fa-map-marker event-info-icon mr-3 mt-1"></i>
                                    <div>
                                        <div style="font-weight: 500; color: #555; font-size: 1.05rem;">{{ $locationName }}</div>
                                        @if(!empty($event->short_description))
                                            <div style="font-size: 0.88rem; color: #777; margin-top: 2px;">{{ Str::limit(strip_tags($event->short_description), 50) }}</div>
                                        @endif
                                    </div>
                                </div>
                                
                                {{-- Seats Section --}}
                                <div class="event-meta-col event-meta-seats d-flex align-items-start">
                                    <i class="fa fa-chair event-info-icon mr-3 mt-1"></i>
                                    <div>
                                        @if($event->availableSeats() <= 5)
                                            <div style="font-weight: 600; color: #c0392b; font-size: 1.05rem;">Only {{ $event->availableSeats() }} <span style="font-weight: normal; font-size: 0.95rem;">seats left!</span></div>
                                        @else
                                            <div style="font-weight: 600; color: #555; font-size: 1.05rem;">Seats <span style="font-weight: normal; font-size: 0.95rem;">Available</span></div>
                                        @endif
                                    </div>
                                </div>
                                
                                {{-- Action Buttons --}}
                                <div class="event-actions-col d-flex">
                                    <a href="{{ route('events.show', $event->slug) }}" class="btn btn-event-outline btn-sm px-3">Details</a>
                                    @if($event->availableSeats() > 0 && $event->isRegistrationOpen())
                                        <a href="{{ route('events.book.step1', $event->slug) }}" class="btn btn-event-primary btn-sm px-3">Book Now</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-md-12">
                    <div class="events-empty-state">
                            <i class="fa fa-calendar-times-o" aria-hidden="true"></i>
                            <h3>No events found</h3>
                            <p>Try a different search or clear the filter to see all events.</p>
                        </div>
                </div>
            @endif
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-center mt-4">
                    {!! $_events->links() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

