@extends('website.common.layout')
@section('title', $_event->title)

@section('meta')
    @include('website.common.meta', [
        '_title'   => $_event->title,
        '_detail'  => strip_tags($_event->short_description ?? $_event->description),
        '_folder'  => 'events',
        '_photo'   => $_event->photo,
    ])
@endsection

@section('content')

    @include('website.common.page-heading')

    {{-- ── Hero Banner ── --}}
    <div style="position:relative; width:100%; background:#1a1a2e; overflow:hidden; margin-bottom:0;">
        @if($_event->photo)
            <img src="{{ \App\Facades\ViewHelper::getImagePath('events', $_event->photo) }}"
                 alt="{{ $_event->title }}"
                 style="width:100%; height:340px; object-fit:cover; opacity:0.35; display:block;">
        @else
            <div style="height:200px; background:linear-gradient(135deg,#3EAD4D 0%,#1a6b26 100%);"></div>
        @endif
        <div style="position:absolute; inset:0; display:flex; flex-direction:column; justify-content:flex-end; padding:30px 40px; background:linear-gradient(to top, rgba(0,0,0,0.82) 0%, transparent 60%);">
            {{-- Status badges --}}
            <div class="mb-2">
                @if(!$_event->isRegistrationOpen())
                    <span style="background:#e2e3e5; color:#383d41; padding:5px 16px; border-radius:20px; font-size:0.82rem; font-weight:600; display:inline-block; margin-right:6px;">
                        <i class="fa fa-ban"></i> Booking Expired
                    </span>
                @elseif($_event->availableSeats() > 0)
                    @if($_event->registration_deadline)
                        <span style="background:#52c752; color:#fff; padding:5px 16px; border-radius:20px; font-size:0.82rem; font-weight:600; display:inline-block; margin-right:6px;">
                            <i class="fa fa-clock-o"></i> Booking Till {{ $_event->registration_deadline->format('M d') }}
                        </span>
                    @else
                        <span style="background:#52c752; color:#fff; padding:5px 16px; border-radius:20px; font-size:0.82rem; font-weight:600; display:inline-block; margin-right:6px;">
                            <i class="fa fa-check-circle"></i> Booking Open
                        </span>
                    @endif
                @else
                    <span style="background:#c0392b; color:#fff; padding:5px 16px; border-radius:20px; font-size:0.82rem; font-weight:600; display:inline-block; margin-right:6px;">
                        <i class="fa fa-times-circle"></i> Fully Booked
                    </span>
                @endif
                @php
                    $today = \Carbon\Carbon::today()->format('Y-m-d');
                    if ($_event->start_date && $_event->start_date->format('Y-m-d') > $today) {
                        $statusLabel = 'Upcoming';
                        $statusBg = 'rgba(255,193,7,0.9)';
                        $statusColor = '#333';
                        $statusIcon = 'fa-star';
                    } elseif ($_event->isEventExpired()) {
                        $statusLabel = 'Expired';
                        $statusBg = 'rgba(220,53,69,0.9)';
                        $statusColor = '#fff';
                        $statusIcon = 'fa-ban';
                    } else {
                        $statusLabel = 'Live';
                        $statusBg = 'rgba(40,167,69,0.9)';
                        $statusColor = '#fff';
                        $statusIcon = 'fa-play-circle';
                    }
                @endphp
                <span style="background:{{ $statusBg }}; color:{{ $statusColor }}; padding:5px 16px; border-radius:20px; font-size:0.82rem; font-weight:600; display:inline-block;">
                    <i class="fa {{ $statusIcon }}"></i> {{ $statusLabel }}
                </span>
            </div>
            <h1 style="color:#fff; font-size:clamp(1.5rem,4vw,2.6rem); font-weight:800; margin:0; text-shadow:0 2px 10px rgba(0,0,0,0.6); line-height:1.2;">
                {{ $_event->title }}
            </h1>
        </div>
    </div>

    {{-- ── Quick Info Strip ── --}}
    <div style="background:#f7fff7; border-top:4px solid #52c752; border-bottom:1px dashed #c5ec98; padding:14px 40px; display:flex; flex-wrap:wrap; gap:24px; align-items:center;">
        @if($_event->start_date_bs || $_event->start_date)
            <div style="display:flex; align-items:center; gap:8px;">
                <span style="width:36px; height:36px; background:#c5ec98; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <i class="fa fa-calendar" style="color:#2d7a2d;"></i>
                </span>
                <div>
                    @if($_event->start_date_bs)
                        <div style="font-weight:700; color:#1a4d1a; font-size:0.95rem; line-height:1.2;">
                            {{ $_event->start_date_bs }}@if($_event->end_date_bs) &ndash; {{ $_event->end_date_bs }}@endif
                            <small style="color:#52c752; font-weight:600;"> BS</small>
                        </div>
                    @endif
                    @if($_event->start_date)
                        <div style="font-size:0.78rem; color:#666;">
                            {{ $_event->start_date->format('M d, Y') }}@if($_event->end_date) &ndash; {{ $_event->end_date->format('M d, Y') }}@endif
                            <small style="color:#999;"> AD</small>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        @if($_event->registration_deadline)
            <div style="display:flex; align-items:center; gap:8px;">
                <span style="width:36px; height:36px; background:#f8d7da; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <i class="fa fa-exclamation-triangle" style="color:#721c24;"></i>
                </span>
                <div>
                    <div style="font-weight:700; color:#721c24; font-size:0.9rem;">Apply Deadline</div>
                    <div style="font-size:0.78rem; color:#666;">{{ $_event->registration_deadline->format('M d, Y') }}</div>
                </div>
            </div>
        @endif

        @if(!empty($locationName))
            <div style="display:flex; align-items:center; gap:8px;">
                <span style="width:36px; height:36px; background:#c5ec98; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <i class="fa fa-map-marker" style="color:#2d7a2d;"></i>
                </span>
                <div style="font-weight:600; color:#1a4d1a; font-size:0.9rem;">{{ $locationName }}</div>
            </div>
        @endif

        <div style="display:flex; align-items:center; gap:8px;">
            <span style="width:36px; height:36px; background:#c5ec98; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <i class="fa fa-users" style="color:#2d7a2d;"></i>
            </span>
            <div>
                @if($_event->availableSeats() <= 5)
                    <div style="font-weight:700; color:#c0392b; font-size:0.95rem;">Only {{ $_event->availableSeats() }} <span style="font-weight:400;color:#555;">seats left!</span></div>
                    <div style="font-size:0.78rem; color:#666;">of {{ $_event->total_seats }} total</div>
                @else
                    <div style="font-weight:700; color:#1a4d1a; font-size:0.95rem;">Seats Available</div>
                @endif
            </div>
        </div>

        <div style="display:flex; align-items:center; gap:8px;">
            <span style="width:36px; height:36px; background:#c5ec98; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <i class="fa fa-tag" style="color:#2d7a2d;"></i>
            </span>
            <div style="font-weight:700; color:#1a4d1a; font-size:0.95rem;">{{ $_event->price_label }}</div>
        </div>
    </div>

    {{-- ── Main Body ── --}}
    <div class="container-xl details" style="padding-top:20px;">
        <div class="row justify-content-around">

            {{-- ── LEFT: Content ── --}}
            <div class="col-xl-8 col-lg-8 details-item details-left">
                <div class="details-head">

                    {{-- Short description lead --}}
                    @if($_event->short_description)
                        <div style="background:#f7fff7; border-left:5px solid #52c752; border-radius:0 6px 6px 0; padding:14px 18px; margin-bottom:24px; color:#2d4d2d; font-size:1rem; font-style:italic; line-height:1.7;">
                            {{ $_event->short_description }}
                        </div>
                    @endif

                    {{-- Full description --}}
                    <div class="desc">
                        {!! $_event->description !!}
                    </div>

                    {{-- ── Routines ── --}}
                    @if($_event->routines && $_event->routines->count() > 0)
                        <div style="margin-top:36px;">
                            <div style="display:flex; align-items:center; gap:10px; margin-bottom:16px;">
                                <span style="width:4px; height:28px; background:#52c752; border-radius:2px; display:block;"></span>
                                <h4 class="heading" style="margin:0; font-size:1.3rem;">
                                    <i class="fa fa-clock-o" style="color:#52c752;"></i> Schedule / Routines
                                </h4>
                            </div>
                            <div class="row">
                                @foreach($_event->routines as $i => $routine)
                                    <div class="col-md-6 mb-3">
                                        <div style="background:#f7fff7; border:1px dashed #a8d8a8; border-left:4px solid #52c752; border-radius:6px; padding:14px 16px; height:100%; display:flex; flex-direction:column; justify-content:center;">
                                            <div style="display:flex; align-items:flex-start; gap:8px;">
                                                <span style="background:#c5ec98; color:#2d7a2d; width:26px; height:26px; border-radius:50%; font-size:0.75rem; font-weight:700; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:2px;">{{ $i+1 }}</span>
                                                <div style="flex-grow: 1;">
                                                    <strong style="color:#1a4d1a; font-size:0.95rem; display:block; word-break:break-word;">
                                                        {{ $routine->display_schedule }}
                                                    </strong>
                                                    @if($routine->about)
                                                        <p style="margin:4px 0 0 0; color:#555; font-size:0.88rem; line-height:1.4;">{{ $routine->about }}</p>
                                                    @endif
                                                    <div style="display:flex; gap: 6px; flex-wrap:wrap; margin-top:8px;">
                                                        @if($routine->price > 0 || $routine->non_member_price > 0)
                                                            @php
                                                                $rMem = (float) $routine->price;
                                                                $rNon = ($routine->non_member_price !== null && $routine->non_member_price > 0) ? (float) $routine->non_member_price : $rMem;
                                                            @endphp
                                                            @if($rNon != $rMem)
                                                                <span class="badge" style="background:#1a4d1a; color:#fff; font-size:0.75rem; padding:4px 8px; white-space:normal; text-align:left;">Single: Rs. {{ number_format($rMem, 2) }} (Member) / Rs. {{ number_format($rNon, 2) }} (Non-member)</span>
                                                            @else
                                                                <span class="badge" style="background:#1a4d1a; color:#fff; font-size:0.75rem; padding:4px 8px; white-space:normal; text-align:left;">Single: Rs. {{ number_format($rMem, 2) }}</span>
                                                            @endif
                                                        @endif
                                                        @if($routine->has_group_pricing && ($routine->group_price > 0 || $routine->non_member_group_price > 0))
                                                            @php
                                                                $rgMem = (float) $routine->group_price;
                                                                $rgNon = ($routine->non_member_group_price !== null && $routine->non_member_group_price > 0) ? (float) $routine->non_member_group_price : $rgMem;
                                                            @endphp
                                                            @if($rgNon != $rgMem)
                                                                <span class="badge" style="background:#2d7a2d; color:#fff; font-size:0.75rem; padding:4px 8px; white-space:normal; text-align:left;">Group ({{ $routine->group_size }}): Rs. {{ number_format($rgMem, 2) }} (Member) / Rs. {{ number_format($rgNon, 2) }} (Non-member)</span>
                                                            @else
                                                                <span class="badge" style="background:#2d7a2d; color:#fff; font-size:0.75rem; padding:4px 8px; white-space:normal; text-align:left;">Group of {{ $routine->group_size }}: Rs. {{ number_format($rgMem, 2) }}</span>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- ── Map ── --}}
                    @if($_event->google_map)
                        <div style="margin-top:36px;">
                            <div style="display:flex; align-items:center; gap:10px; margin-bottom:16px;">
                                <span style="width:4px; height:28px; background:#52c752; border-radius:2px; display:block;"></span>
                                <h4 class="heading" style="margin:0; font-size:1.3rem;">
                                    <i class="fa fa-map-marker" style="color:#52c752;"></i> Event Location
                                    @if(!empty($locationName))
                                        <small style="font-size:0.75rem; color:#666; font-weight:400; margin-left:6px;">{{ $locationName }}</small>
                                    @endif
                                </h4>
                            </div>
                            <div style="border-radius:10px; overflow:hidden; border:2px solid #c5ec98; box-shadow:0 4px 16px rgba(82,199,82,0.15);">
                                <div class="embed-responsive embed-responsive-16by9" style="min-height:360px;">
                                    @if(str_contains($_event->google_map, '<iframe'))
                                        {!! preg_replace('/width="[^"]*"/', 'width="100%"', preg_replace('/height="[^"]*"/', 'height="400"', $_event->google_map)) !!}
                                    @else
                                        <iframe src="{{ $_event->google_map }}" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

            {{-- ── RIGHT: Sidebar ── --}}
            <div class="col-xl-3 col-lg-4 col-sm-12 col-11 details-item details-right">

                {{-- Book Now Box --}}
                <h5><span>Book This Event</span></h5>
                <div class="row trending">
                    <div class="col-12 p-3">

                        {{-- Price highlight --}}
                        <div style="text-align:center; padding:16px 0 14px; border-bottom:1px dashed #a8d8a8; margin-bottom:14px;">
                            <div style="font-size:0.72rem; text-transform:uppercase; letter-spacing:1.5px; color:#5fa05c; font-weight:700; margin-bottom:4px;">Ticket Price</div>
                            <div style="font-size:1.8rem; font-weight:800; color:#2d7a2d; line-height:1;">{{ $_event->price_label }}</div>
                        </div>

                        {{-- Seat progress bar --}}
                        @php
                            $pct = $_event->total_seats > 0
                                ? round(($_event->availableSeats(isset($activeDraft) && $activeDraft ? $activeDraft->id : null) / $_event->total_seats) * 100)
                                : 0;
                            $barColor = $pct > 50 ? '#52c752' : ($pct > 20 ? '#f0a500' : '#c0392b');
                        @endphp
                        <div style="margin-bottom:14px;">
                            <div style="display:flex; justify-content:space-between; font-size:0.78rem; color:#555; margin-bottom:4px;">
                                @if($_event->availableSeats(isset($activeDraft) && $activeDraft ? $activeDraft->id : null) <= 5)
                                    <span style="color:#c0392b; font-weight:bold;"><i class="fa fa-users"></i> Only {{ $_event->availableSeats(isset($activeDraft) && $activeDraft ? $activeDraft->id : null) }} seats left!</span>
                                    <span>{{ $_event->total_seats }} total</span>
                                @else
                                    <span><i class="fa fa-users" style="color:#52c752;"></i> Seats Available</span>
                                @endif
                            </div>
                            <div style="height:8px; background:#e0f0d0; border-radius:10px; overflow:hidden;">
                                <div style="height:100%; width:{{ $pct }}%; background:{{ $barColor }}; border-radius:10px; transition:width 0.5s;"></div>
                            </div>
                        </div>

                        {{-- Book / Sold Out button --}}
                        @if(isset($activeDraft) && $activeDraft)
                            <a href="{{ route('events.booking.continue', $activeDraft->id) }}"
                               style="display:block; text-align:center; background:linear-gradient(135deg,#206b9b,#2980b9); color:#fff; font-weight:700; font-size:1rem; padding:13px; border-radius:30px; text-decoration:none; letter-spacing:0.5px; box-shadow:0 4px 14px rgba(41,128,185,0.4); transition:transform 0.2s; margin-bottom: 10px;"
                               onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform='scale(1)'">
                                <i class="fa fa-arrow-right mr-2"></i> Continue Booking
                            </a>
                            <div style="text-align:center; font-size:0.8rem; color:#666;">
                                You have an incomplete reservation. Please complete it before time runs out.
                            </div>
                        @elseif(!$_event->isRegistrationOpen())
                            <div style="text-align:center; background:#fdf2f2; border:1px dashed #e0a0a0; border-radius:10px; padding:20px 12px;">
                                <i class="fa fa-calendar-times-o" style="font-size:2rem; color:#c0392b; display:block; margin-bottom:8px;"></i>
                                <div style="color:#c0392b; font-weight:700; font-size:0.95rem;">Registration Closed</div>
                                <div style="color:#999; font-size:0.82rem; margin-top:4px;">The deadline to book this event has passed.</div>
                            </div>
                        @elseif($_event->availableSeats() > 0)
                            <a href="{{ route('events.book.step1', $_event->slug) }}"
                               style="display:block; text-align:center; background:linear-gradient(135deg,#52c752,#2d7a2d); color:#fff; font-weight:700; font-size:1rem; padding:13px; border-radius:30px; text-decoration:none; letter-spacing:0.5px; box-shadow:0 4px 14px rgba(82,199,82,0.4); transition:transform 0.2s;"
                               onmouseover="this.style.transform='scale(1.03)'" onmouseout="this.style.transform='scale(1)'">
                                <i class="fa fa-ticket mr-2"></i> Book Now
                            </a>
                        @else
                            <div style="text-align:center; background:#fdf2f2; border:1px dashed #e0a0a0; border-radius:10px; padding:20px 12px;">
                                <i class="fa fa-calendar-times-o" style="font-size:2rem; color:#c0392b; display:block; margin-bottom:8px;"></i>
                                <div style="color:#c0392b; font-weight:700; font-size:0.95rem;">Fully Booked</div>
                                <div style="color:#999; font-size:0.82rem; margin-top:4px;">No seats available.</div>
                            </div>
                        @endif

                    </div>
                </div>

                {{-- Event Details card --}}
                <h5 class="mt-3"><span>Event Info</span></h5>
                <div class="row trending">
                    <div class="col-12 p-0">
                        @php
                            $infoItems = [];
                            if ($_event->start_date_bs) {
                                $bs = $_event->start_date_bs;
                                if ($_event->end_date_bs) $bs .= ' — ' . $_event->end_date_bs;
                                $infoItems[] = ['icon'=>'fa-calendar','label'=>'Date (BS)', 'val'=>$bs, 'extra'=>null];
                            }
                            if ($_event->start_date) {
                                $ad = $_event->start_date->format('M d, Y');
                                if ($_event->end_date) $ad .= ' — ' . $_event->end_date->format('M d, Y');
                                $infoItems[] = ['icon'=>'fa-calendar-o','label'=>'Date (AD)', 'val'=>$ad, 'extra'=>null];
                            }
                            if (!empty($locationName)) {
                                $infoItems[] = ['icon'=>'fa-map-marker','label'=>'Location', 'val'=>$locationName, 'extra'=>null];
                            }
                            $infoItems[] = ['icon'=>'fa-chair','label'=>'Capacity', 'val'=>$_event->total_seats.' seats', 'extra'=>null];
                            $infoItems[] = ['icon'=>'fa-tag','label'=>'Price', 'val'=>$_event->price_label, 'extra'=>null];
                        @endphp
                        @foreach($infoItems as $idx => $item)
                            <div class="right-topic" style="padding:10px 16px; {{ $idx === 0 ? 'border-top:none;' : '' }} display:flex; gap:10px; align-items:flex-start;">
                                <span style="width:30px; height:30px; background:#5fa05c; color:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:2px;">
                                    <i class="fa {{ $item['icon'] }}" style="font-size:0.8rem;"></i>
                                </span>
                                <div>
                                    <div style="font-size:0.72rem; text-transform:uppercase; letter-spacing:0.8px; color:#5fa05c; font-weight:700;">{{ $item['label'] }}</div>
                                    <div style="font-weight:600; color:#1a3a1a; font-size:0.9rem; line-height:1.4;">{{ $item['val'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Other Events --}}
                @if(isset($_other_events) && $_other_events->count() > 0)
                    <h5 class="mt-3"><span>Other Events</span></h5>
                    <div class="row trending">
                        @foreach($_other_events as $key => $other)
                            <div class="col-lg-12 col-sm-6 col-11">
                                <div class="row justify-content-around right-topic trending-item" style="margin:0;">
                                    <div class="col-4 p-2">
                                        @if($other->photo)
                                            <img src="{{ \App\Facades\ViewHelper::getImagePath('events', $other->photo) }}"
                                                 style="width:100%; height:58px; object-fit:cover; border-radius:5px; border:2px solid #c5ec98;"
                                                 alt="{{ $other->title }}">
                                        @else
                                            <div class="nmbr"><h3>{{ ++$key }}</h3></div>
                                        @endif
                                    </div>
                                    <div class="col-8">
                                        <h4>{{ Str::limit($other->title, 38) }}</h4>
                                        <p>
                                            @if($other->availableSeats() <= 5)
                                                <i class="fa fa-exclamation-circle text-danger"></i> <span class="text-danger">Only {{ $other->availableSeats() }} seats &bull;</span>
                                            @elseif($other->availableSeats() > 0)
                                                <i class="fa fa-check-circle text-success"></i> <span class="text-success">Available &bull;</span>
                                            @else
                                                <i class="fa fa-times-circle text-danger"></i> Full &bull;
                                            @endif
                                            <a href="{{ route('events.show', $other->slug) }}" style="color:#3EAD4D;">View &raquo;</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>{{-- end right --}}
        </div>
    </div>

@endsection
