@php
    $today = \Carbon\Carbon::today();

    $headerEvents = \App\Models\Event::where('status', 1)
        ->where(function($q) use ($today) {
            // Show LIVE events: start_date <= today <= end_date
            $q->where(function($q2) use ($today) {
                $q2->whereNotNull('start_date')
                   ->where('start_date', '<=', $today)
                   ->where(function($q3) use ($today) {
                       $q3->whereNotNull('end_date')->where('end_date', '>=', $today)
                          ->orWhere(function($q4) use ($today) {
                              $q4->whereNull('end_date')->where('start_date', '>=', $today);
                          });
                   });
            })
            // Show UPCOMING events: start_date > today
            ->orWhere(function($q2) use ($today) {
                $q2->whereNotNull('start_date')
                   ->where('start_date', '>', $today);
            });
        })
        ->orderByRaw("CASE WHEN start_date <= '{$today->format('Y-m-d')}' THEN 0 ELSE 1 END")
        ->orderBy('start_date')
        ->limit(8)
        ->get(['title', 'slug', 'start_date', 'end_date']);
@endphp

@if(config('services.events_enabled', true) && $headerEvents->count() > 0)
<div id="event-ticker-bar">
    <style>
        #event-ticker-bar {
            background: #1a1a2e;
            color: #fff;
            width: 100%;
            overflow: hidden;
            height: 34px;
            display: flex;
            align-items: center;
            border-bottom: 2px solid #dc3545;
            position: relative;
            z-index: 1050;
        }
        #event-ticker-bar .ticker-label {
            flex-shrink: 0;
            background: #dc3545;
            color: #fff;
            font-weight: 800;
            font-size: 0.7rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 0 14px;
            height: 100%;
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
            border-right: 2px solid rgba(255,255,255,0.2);
        }
        #event-ticker-bar .ticker-label .ticker-dot {
            width: 7px;
            height: 7px;
            background: #fff;
            border-radius: 50%;
            animation: tickerPulse 1s infinite;
            flex-shrink: 0;
        }
        @keyframes tickerPulse {
            0%,100% { opacity: 1; transform: scale(1); }
            50%      { opacity: 0.3; transform: scale(0.7); }
        }
        #event-ticker-bar .ticker-track {
            overflow: hidden;
            flex: 1;
            height: 100%;
            display: flex;
            align-items: center;
        }
        #event-ticker-bar .ticker-inner {
            display: flex;
            align-items: center;
            gap: 0;
            white-space: nowrap;
            animation: tickerScroll 20s linear infinite;
            will-change: transform;
            padding-left: 100%; /* Push it off-screen to start */
        }
        #event-ticker-bar .ticker-inner:hover {
            animation-play-state: paused;
        }
        @keyframes tickerScroll {
            0%   { transform: translateX(0); }
            100% { transform: translateX(-100%); }
        }
        #event-ticker-bar .ticker-item {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 0 24px;
            font-size: 0.8rem;
            font-weight: 500;
            color: #e2e8f0;
            text-decoration: none;
            border-right: 1px solid rgba(255,255,255,0.12);
            height: 34px;
            transition: background 0.2s, color 0.2s;
            cursor: pointer;
        }
        #event-ticker-bar .ticker-item:hover {
            background: rgba(255,255,255,0.08);
            color: #ffffff;
            text-decoration: none;
        }
        #event-ticker-bar .badge-live {
            background: #dc3545;
            color: #fff;
            font-size: 0.6rem;
            font-weight: 800;
            letter-spacing: 1px;
            padding: 2px 6px;
            border-radius: 3px;
            text-transform: uppercase;
            flex-shrink: 0;
        }
        #event-ticker-bar .badge-upcoming {
            background: #f59e0b;
            color: #1a1a2e;
            font-size: 0.6rem;
            font-weight: 800;
            letter-spacing: 1px;
            padding: 2px 6px;
            border-radius: 3px;
            text-transform: uppercase;
            flex-shrink: 0;
        }
        #event-ticker-bar .ticker-date {
            font-size: 0.7rem;
            color: #94a3b8;
            flex-shrink: 0;
        }
        #event-ticker-bar .ticker-sep {
            color: rgba(255,255,255,0.2);
            padding: 0 8px;
            font-size: 1rem;
        }
        @media (max-width: 576px) {
            #event-ticker-bar .ticker-label { font-size: 0.65rem; padding: 0 10px; }
            #event-ticker-bar .ticker-item { font-size: 0.72rem; padding: 0 14px; }
        }
    </style>

    <div class="ticker-label">
        <span class="ticker-dot"></span>
        <span>Events</span>
    </div>

    <div class="ticker-track">
        <div class="ticker-inner" id="tickerInner">
            @foreach($headerEvents as $ev)
                @php
                    $isLive = $ev->start_date
                        && \Carbon\Carbon::parse($ev->start_date)->lte($today)
                        && ($ev->end_date ? \Carbon\Carbon::parse($ev->end_date)->gte($today) : \Carbon\Carbon::parse($ev->start_date)->gte($today));
                    $dateLabel = $ev->start_date
                        ? \Carbon\Carbon::parse($ev->start_date)->format('M d')
                        : '';
                    if ($ev->end_date) {
                        $dateLabel .= ' – ' . \Carbon\Carbon::parse($ev->end_date)->format('M d');
                    }
                @endphp
                <a href="{{ route('events.show', $ev->slug) }}" class="ticker-item">
                    @if($isLive)
                        <span class="badge-live">● Live</span>
                    @else
                        <span class="badge-upcoming">↑ Upcoming</span>
                    @endif
                    <span>{{ Str::limit($ev->title, 45) }}</span>
                    @if($dateLabel)
                        <span class="ticker-date">{{ $dateLabel }}</span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</div>
@endif

<style>
    header.topbar {
        background: linear-gradient(135deg, var(--brand-green, #01a755) 0%, var(--brand-green-hover, #008f48) 100%) !important;
        color: #ffffff !important;
        width: 100% !important;
        height: auto !important;
        min-height: 42px !important;
        display: flex !important;
        align-items: center !important;
        padding: 6px 20px !important;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1) !important;
        border-bottom: 2px solid var(--brand-cyan, #00acf1) !important;
        z-index: 1000 !important;
        position: relative !important;
    }
    
    header.topbar .topbar-container {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        max-width: 1200px;
        margin: 0 auto;
    }

    header.topbar ul {
        list-style: none !important;
        margin: 0 !important;
        padding: 0 !important;
        display: flex !important;
        align-items: center !important;
        flex-wrap: wrap !important;
    }

    header.topbar ul.contact-info {
        gap: 25px !important;
    }

    header.topbar ul.contact-info li {
        font-size: 13.5px !important;
        font-weight: 500 !important;
        color: #ffffff !important;
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
        height: auto !important;
        border-right: none !important;
        padding: 0 !important;
    }

    header.topbar ul.contact-info li a {
        color: #ffffff !important;
        text-decoration: none !important;
        transition: color 0.25s ease !important;
        display: flex !important;
        align-items: center !important;
        gap: 6px !important;
    }

    header.topbar ul.contact-info li a:hover {
        color: var(--brand-border, #ddf3e8) !important;
    }

    header.topbar ul.contact-info li i {
        color: #ffffff !important;
        font-size: 14px !important;
        transition: transform 0.3s ease !important;
    }

    header.topbar ul.contact-info li a:hover i {
        transform: scale(1.2) !important;
    }

    header.topbar ul.social-links {
        gap: 15px !important;
    }

    header.topbar ul.social-links li {
        padding: 0 !important;
        border-right: none !important;
        height: auto !important;
    }

    header.topbar ul.social-links li a {
        color: #ffffff !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 28px !important;
        height: 28px !important;
        border-radius: 50% !important;
        background-color: rgba(255, 255, 255, 0.15) !important;
        text-decoration: none !important;
        font-size: 13px !important;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
    }

    header.topbar ul.social-links li a:hover {
        background-color: var(--brand-cyan, #00acf1) !important;
        color: #ffffff !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 8px rgba(0, 172, 241, 0.3) !important;
    }

    /* Responsive adjustments */
    @media (max-width: 991px) {
        header.topbar {
            padding: 8px 15px !important;
        }
        header.topbar .topbar-container {
            justify-content: center !important;
            gap: 10px !important;
        }
        header.topbar ul.contact-info {
            justify-content: center !important;
            gap: 15px !important;
        }
        header.topbar ul.social-links {
            justify-content: center !important;
            width: 100% !important;
            margin-top: 4px !important;
        }
    }

    @media (max-width: 768px) {
        header.topbar ul.contact-info li.location-item {
            display: none !important;
        }
    }

    @media (max-width: 576px) {
        header.topbar ul.contact-info {
            flex-direction: column !important;
            gap: 6px !important;
            width: 100% !important;
            text-align: center !important;
        }
        header.topbar ul.contact-info li {
            justify-content: center !important;
        }
    }
</style>

<header class="topbar">
    <div class="topbar-container">
        <!-- Contact Info Section -->
        <ul class="contact-info">
            @if(isset($_site_setting->phone) && !empty($_site_setting->phone))
            <li>
                <a href="tel:{{ $_site_setting->phone }}">
                    <i class="fa fa-phone"></i>
                    <span>{{ $_site_setting->phone }}</span>
                </a>
            </li>
            @else
            <li>
                <a href="tel:011-665368">
                    <i class="fa fa-phone"></i>
                    <span>011-665368</span>
                </a>
            </li>
            @endif

            <li class="location-item">
                <i class="fa fa-map-marker"></i>
                <span>{{ $_site_setting->location ?? $_site_setting->address ?? "हेटौंडा, मकवानपुर" }}</span>
            </li>

            @if(isset($_site_setting->email) && !empty($_site_setting->email))
            <li>
                <a href="mailto:{{ $_site_setting->email }}">
                    <i class="fa fa-envelope"></i>
                    <span>{{ $_site_setting->email }}</span>
                </a>
            </li>
            @else
            <li>
                <a href="mailto:info@mafscun.coop.np">
                    <i class="fa fa-envelope"></i>
                    <span>info@mafscun.coop.np</span>
                </a>
            </li>
            @endif
        </ul>

        <!-- Social Icons Section -->
        <ul class="social-links">
            @if(isset($_site_setting->social['facebook_link']) && $_site_setting->social['facebook_link'] != '#')
            <li>
                <a target="_blank" href="{{ $_site_setting->social['facebook_link'] }}" title="Facebook">
                    <i class="fab fa-facebook-f"></i>
                </a>
            </li>
            @endif
            @if(isset($_site_setting->social['twitter_link']) && $_site_setting->social['twitter_link'] != '#')
            <li>
                <a target="_blank" href="{{ $_site_setting->social['twitter_link'] }}" title="Twitter">
                    <i class="fab fa-twitter"></i>
                </a>
            </li>
            @endif
            @if(isset($_site_setting->social['insta_link']) && $_site_setting->social['insta_link'] != '#')
            <li>
                <a target="_blank" href="{{ $_site_setting->social['insta_link'] }}" title="Instagram">
                    <i class="fab fa-instagram"></i>
                </a>
            </li>
            @endif
            @if(isset($_site_setting->social['youtube_link']) && $_site_setting->social['youtube_link'] != '#')
            <li>
                <a target="_blank" href="{{ $_site_setting->social['youtube_link'] }}" title="YouTube">
                    <i class="fab fa-youtube"></i>
                </a>
            </li>
            @endif
        </ul>
    </div>
</header>
