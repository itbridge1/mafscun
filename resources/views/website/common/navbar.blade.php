<style>
    /* ============================================================
       NAVBAR BASE STYLES
    ============================================================ */
    nav.main-navbar {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04) !important;
        border-bottom: 1px solid var(--brand-border, #ddf3e8);
        transition: all 0.3s ease-in-out !important;
    }

    nav.main-navbar.navbar-scrolled {
        background-color: rgba(255, 255, 255, 0.96) !important;
        backdrop-filter: blur(10px) !important;
        -webkit-backdrop-filter: blur(10px) !important;
        padding-top: 4px !important;
        padding-bottom: 4px !important;
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1) !important;
        border-bottom: 1px solid var(--brand-border, #01a755) !important;
    }

    .main-navbar .nav-link {
        font-weight: 600 !important;
        color: #334155 !important;
        transition: color 0.2s ease !important;
    }

    .main-navbar .nav-link:hover,
    .main-navbar .nav-link.active-nav {
        color: #01a755 !important;
    }

    /* Brand Logo */
    .navbar-brand img {
        height: 60px !important;
        width: 200px !important;
        max-width: 200px !important;
        object-fit: contain !important;
        transition: all 0.2s ease;
    }

    /* Hamburger Toggle Button */
    .hamburger-btn {
        display: flex !important;
        flex-direction: column !important;
        justify-content: center !important;
        align-items: center !important;
        width: 44px !important;
        height: 44px !important;
        min-width: 44px !important;
        min-height: 44px !important;
        padding: 6px !important;
        background: #eaf8f1 !important;
        border: 2px solid var(--brand-green, #01a755) !important;
        border-radius: 10px !important;
        cursor: pointer !important;
        transition: all 0.3s ease !important;
        position: relative !important;
        z-index: 10 !important;
        outline: none !important;
    }
    
    .hamburger-btn:hover { background: #d4f3e3 !important; }
    .hamburger-btn:focus { outline: none !important; box-shadow: 0 0 0 3px rgba(1, 167, 85, 0.35) !important; }
    .hamburger-btn i { color: var(--brand-green, #01a755) !important; font-size: 20px !important; transition: all 0.3s ease !important; }
    .hamburger-btn[aria-expanded="true"] { border-color: #dc3545 !important; background: #fde8e8 !important; }
    .hamburger-btn[aria-expanded="true"] i { color: #dc3545 !important; }

    /* ============================================================
       DROPDOWN & SUBMENU STYLING (PROPER RIGHT-FLYOUT)
    ============================================================ */
    @media (min-width: 1200px) {
        /* Open Level 1 on hover */
        .main-navbar .navbar-nav > li.dropdown {
            position: relative;
        }

        .main-navbar .navbar-nav > li.dropdown:hover > .dropdown-menu {
            display: block !important;
            animation: dropdownEntrance 0.2s cubic-bezier(0.25, 0.8, 0.25, 1) forwards !important;
        }

        /* Level 1 dropdown menu container */
        .main-navbar .dropdown-menu {
            display: none;
            position: absolute !important;
            top: 100% !important;
            left: 0 !important;
            border: 1px solid #ddf3e8 !important;
            border-top: 4px solid #01a755 !important;
            border-radius: 14px !important;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08) !important;
            padding: 10px 8px !important;
            min-width: 220px !important;
            background-color: #ffffff !important;
            list-style: none !important;
            margin: 0 !important;
            overflow: visible !important;
            z-index: 1050 !important;
        }

        /* Each item inside dropdown must be relative so submenu anchors to it */
        .main-navbar .dropdown-menu li {
            position: relative !important;
            list-style: none !important;
        }

        /* SUBMENU (Level 2 & Level 3) — Positioned outside on the RIGHT */
        .main-navbar .dropdown-menu .dropdown-submenu {
            display: none !important;
            position: absolute !important;
            left: 100% !important;
            top: -6px !important;
            margin-left: 0 !important;
            min-width: 230px !important;
            background: #ffffff !important;
            border-radius: 12px !important;
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.14) !important;
            border: 1px solid #e2e8f0 !important;
            border-top: 3px solid #01a755 !important;
            padding: 8px 6px !important;
            list-style: none !important;
            z-index: 1080 !important;
            overflow: visible !important;
        }

        /* Seamless hover bridge: prevents closing when cursor moves toward submenu */
        .main-navbar .dropdown-menu .dropdown-submenu::before {
            content: '';
            position: absolute;
            top: 0;
            left: -15px;
            width: 15px;
            height: 100%;
            pointer-events: auto;
        }

        /* Level 3 submenu (nested inside Level 2) */
        .main-navbar .dropdown-menu .dropdown-submenu .dropdown-submenu {
            z-index: 1090 !important;
            border-top-color: #00acf1 !important;
        }

        /* OPEN SUBMENU ON HOVER OUTSIDE TO THE RIGHT */
        .main-navbar .dropdown-menu li:hover > .dropdown-submenu {
            display: block !important;
            animation: dropdownEntrance 0.18s cubic-bezier(0.25, 0.8, 0.25, 1) forwards !important;
        }

        /* Dropdown Item Styling */
        .main-navbar .dropdown-menu .dropdown-item {
            font-size: 0.92rem !important;
            font-weight: 550 !important;
            color: #475569 !important;
            padding: 9px 16px !important;
            border-radius: 8px !important;
            transition: all 0.2s ease !important;
            margin-bottom: 2px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            white-space: nowrap !important;
            text-decoration: none !important;
            line-height: 1.4 !important;
            gap: 12px !important;
        }

        .main-navbar .dropdown-menu .dropdown-item:hover,
        .main-navbar .dropdown-menu .dropdown-item:focus {
            background-color: #eaf8f1 !important;
            color: #01a755 !important;
            padding-left: 20px !important;
        }

        .main-navbar .dropdown-menu .dropdown-item.active-nav {
            background-color: #01a755 !important;
            color: #ffffff !important;
        }

        .main-navbar .dropdown-menu .dropdown-item .arrow-icon {
            font-size: 0.72rem !important;
            opacity: 0.5 !important;
            flex-shrink: 0 !important;
            transition: transform 0.2s ease !important;
        }

        .main-navbar .dropdown-menu .dropdown-item:hover .arrow-icon {
            opacity: 1 !important;
            transform: translateX(3px) !important;
        }

        @keyframes dropdownEntrance {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    }

    /* ============================================================
       MOBILE & TABLET RESPONSIVE OVERRIDES (Screen width < 1200px)
    ============================================================ */
    @media (max-width: 1199px) {
        .navbar-brand img {
            height: 30px !important;
            width: 100px !important;
            max-width: 100px !important;
        }

        #navbarSupportedContent {
            background-color: #ffffff !important;
            border-top: 3px solid #01a755 !important;
            padding: 15px 20px 25px !important;
            border-radius: 0 0 16px 16px !important;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15) !important;
            position: absolute !important;
            top: 100% !important;
            left: 0 !important;
            right: 0 !important;
            z-index: 1000 !important;
        }

        #navbarSupportedContent .navbar-nav {
            margin-bottom: 15px !important;
        }

        #navbarSupportedContent .nav-item {
            margin-bottom: 5px !important;
        }

        #navbarSupportedContent .nav-link {
            color: #334155 !important;
            font-weight: 600 !important;
            font-size: 1.05rem !important;
            padding: 12px 15px !important;
            border-radius: 8px !important;
        }

        #navbarSupportedContent .nav-link:hover,
        #navbarSupportedContent .nav-link.active-nav {
            background-color: #eaf8f1 !important;
            color: #01a755 !important;
            padding-left: 20px !important;
        }

        /* Mobile Dropdown Styling */
        .main-navbar .dropdown-menu {
            position: static !important;
            float: none !important;
            box-shadow: none !important;
            border: none !important;
            background-color: #f8fbf9 !important;
            padding-left: 15px !important;
            border-radius: 8px !important;
            margin-top: 5px !important;
            list-style: none !important;
        }

        .main-navbar .dropdown-menu .dropdown-submenu {
            position: static !important;
            display: none !important;
            box-shadow: none !important;
            border: none !important;
            border-left: 2px solid #01a755 !important;
            padding-left: 10px !important;
            background: transparent !important;
            list-style: none !important;
            margin-top: 4px !important;
        }

        .main-navbar .dropdown-menu li:hover > .dropdown-submenu,
        .main-navbar .dropdown-menu li.mobile-open > .dropdown-submenu {
            display: block !important;
        }

        .action-buttons-mobile {
            margin-top: 15px !important;
            padding-top: 20px !important;
            border-top: 1px dashed #ddf3e8 !important;
            flex-direction: column !important;
            align-items: stretch !important;
            width: 100% !important;
        }

        .action-buttons-mobile .btn, 
        .action-buttons-mobile .dropdown {
            margin-bottom: 12px !important;
            width: 100% !important;
            text-align: center !important;
            display: block !important;
        }

        .action-buttons-mobile .btn {
            padding: 10px 15px !important;
            font-size: 0.9rem !important;
            font-weight: 600 !important;
            border-radius: 8px !important;
        }
    }
</style>
<nav class="navbar navbar-expand-xl sticky-top navbar-light bg-white py-2 main-navbar">
    <div class="public-nav-inner">
        <!-- Brand Logo -->
        @if(isset($_site_setting) && !empty($_site_setting->site_logo) && file_exists(public_path('images/siteSetting/' . $_site_setting->site_logo)))
            <a class="navbar-brand order-xl-1" href="/">
                <img
                    src="{{ \App\Facades\ViewHelper::getImagePath('siteSetting', $_site_setting->site_logo) }}"
                    alt="{{ $_site_setting->title ?? '' }}" height="60" width="200">
            </a>
        @endif

        <!-- Hamburger toggle button -->
        <button class="navbar-toggler public-menu-toggle hamburger-btn d-xl-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Main Navigation Menu and Action Buttons -->
        <div class="collapse navbar-collapse order-xl-2" id="navbarSupportedContent">
            <ul class="navbar-nav mx-auto">
                {{-- Home --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/')? 'active-nav':'' }}" href="/">
                        Home <span class="sr-only">(current)</span>
                    </a>
                </li>

                {{-- About Us --}}
                <li class="nav-item dropdown">
                    <a class="nav-link {{ request()->is('about-us*') || request()->is('organization') ? 'active-nav':'' }} dropdown-toggle" href="{{ route('about') }}" id="navbarDropdownAbout" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        About Us
                    </a>
                    @if(isset($_aboutMenu))
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownAbout">
                            @foreach($_aboutMenu as $key => $menu)
                                <li>
                                    <a class="dropdown-item {{ request()->is('about-us/'.$menu->slug)? 'active-nav':'' }}"
                                       href="{{ route('about.detail', $menu->slug) }}">
                                        {{ $menu->title ?? 'Menu'.++$key }}
                                    </a>
                                </li>
                            @endforeach
                            <li>
                                <a class="dropdown-item {{ request()->is('organization')? 'active-nav':'' }}"
                                   href="{{ route('organization') }}">
                                    Organization
                                </a>
                            </li>
                        </ul>
                    @endif
                </li>

                {{-- HRM --}}
                <li class="nav-item dropdown">
                    <a class="nav-link {{ request()->is('hrm*')? 'active-nav':'' }} dropdown-toggle" href="#" id="navbarDropdownHrm" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        HRM
                    </a>
                    @if(isset($_hrm))
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownHrm">
                            @foreach($_hrm as $key => $menuHrm)
                                <li>
                                    <a class="dropdown-item {{ request()->is('hrm/'.$menuHrm->slug)? 'active-nav':'' }}" href="{{ route('hrm.detail', $menuHrm->slug) }}">
                                        {{ $menuHrm->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>

                {{-- Services (with Multi-level flyout submenus outside to the right) --}}
                <li class="nav-item dropdown">
                    <a class="nav-link {{ request()->is('service*')? 'active-nav':'' }} dropdown-toggle" href="{{ route('service') }}" id="navbarDropdownServices" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Services
                    </a>
                    @if(isset($_service_categories) && $_service_categories->count() > 0)
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownServices">
                            @foreach($_service_categories as $category)
                                @php
                                    $hasSubCats = $category->subCategories->count() > 0;
                                    $hasDirectServices = $category->directServices->count() > 0;
                                    $hasChildren = $hasSubCats || $hasDirectServices;
                                @endphp
                                <li>
                                    <a class="dropdown-item {{ request()->is('service/'.$category->slug.'*')? 'active-nav':'' }}" href="{{ $category->url }}">
                                        <span>{{ $category->title }}</span>
                                        @if($hasChildren)
                                            <i class="fas fa-chevron-right arrow-icon"></i>
                                        @endif
                                    </a>

                                    {{-- Submenu level 2 --}}
                                    @if($hasChildren)
                                        <ul class="dropdown-submenu">
                                            @foreach($category->subCategories as $subCat)
                                                @php $hasServices = $subCat->services->count() > 0; @endphp
                                                <li>
                                                    <a class="dropdown-item" href="{{ $subCat->url }}">
                                                        <span>{{ $subCat->title }}</span>
                                                        @if($hasServices)
                                                            <i class="fas fa-chevron-right arrow-icon"></i>
                                                        @endif
                                                    </a>

                                                    {{-- Submenu level 3 (services under subcategory) --}}
                                                    @if($hasServices)
                                                        <ul class="dropdown-submenu">
                                                            @foreach($subCat->services as $svc)
                                                                <li>
                                                                    <a class="dropdown-item" href="{{ $svc->url }}">
                                                                        <span>{{ $svc->title }}</span>
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </li>
                                            @endforeach

                                            {{-- Direct services under category --}}
                                            @foreach($category->directServices as $directSvc)
                                                <li>
                                                    <a class="dropdown-item" href="{{ $directSvc->url }}">
                                                        <span>{{ $directSvc->title }}</span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>

                {{-- News --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('news*')? 'active-nav':'' }}" href="{{ route('news') }}">
                        News 
                    </a>
                </li>

                {{-- Events --}}
                @if(config('services.events_enabled', true))
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('events*')? 'active-nav':'' }}" href="{{ route('events.index') }}">Events</a>
                    </li>
                @endif

                {{-- Notices --}}
                <li class="nav-item dropdown">
                    <a class="nav-link {{ request()->is('notices*')? 'active-nav':'' }} dropdown-toggle"
                       href="{{ route('notices') }}" id="navbarDropdownNotices" role="button"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Notices
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownNotices">
                        <li>
                            <a class="dropdown-item {{ request()->is('notices*')? 'active-nav':'' }}" href="{{ route('notices') }}">
                                Notices
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->is('career*')? 'active-nav':'' }}" href="{{ route('career') }}">
                                Career
                            </a>
                        </li>
                    </ul>
                </li>
               
                {{-- Resources (with Multi-level flyout submenus outside to the right) --}}
                <li class="nav-item dropdown">
                    <a class="nav-link {{ request()->is('resource-list*')? 'active-nav':'' }} dropdown-toggle" href="{{ route('resource-list') }}" id="navbarDropdownResources" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Resources
                    </a>
                    @if(isset($_resource_types) && $_resource_types->count() > 0)
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownResources">
                            <li>
                                <a class="dropdown-item" href="{{ route('resource-list') }}">
                                    <span>All Resources</span>
                                </a>
                            </li>
                            @foreach($_resource_types as $rType)
                                @php $hasSubTypes = $rType->subTypes->count() > 0; @endphp
                                <li>
                                    <a class="dropdown-item {{ request('filter_resource_type') == $rType->id && !request('filter_sub_type') ? 'active-nav' : '' }}" href="{{ route('resource-list', ['filter_resource_type' => $rType->id]) }}">
                                        <span>{{ $rType->title }}</span>
                                        @if($hasSubTypes)
                                            <i class="fas fa-chevron-right arrow-icon"></i>
                                        @endif
                                    </a>
                                    @if($hasSubTypes)
                                        <ul class="dropdown-submenu">
                                            @foreach($rType->subTypes as $subType)
                                                <li>
                                                    <a class="dropdown-item {{ request('filter_resource_type') == $rType->id && request('filter_sub_type') == $subType->id ? 'active-nav' : '' }}" href="{{ route('resource-list', ['filter_resource_type' => $rType->id, 'filter_sub_type' => $subType->id]) }}">
                                                        <span>{{ $subType->title }}</span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>

                {{-- Gallery --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('gallery*')? 'active-nav':'' }}" href="{{ route('gallery') }}">Gallery</a>
                </li>

                {{-- Contact Us --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('contact*')? 'active-nav':'' }}" href="{{ route('contact') }}">Contact Us</a>
                </li>
            </ul>
            
            <!-- Right side: action buttons -->
            @if(config('services.events_enabled', true))
                <div class="d-flex align-items-center ml-auto order-xl-3 action-buttons-mobile">
                    @if(auth()->check())
                        <div class="dropdown">
                            <a class="btn btn-sm btn-outline-success dropdown-toggle font-weight-bold" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 0.78rem !important; padding: 5px 10px !important; border-radius: 20px !important; white-space: nowrap !important;">
                                <i class="fas fa-user-circle mr-1"></i>
                                <span class="d-sm-inline">
                                @if(!empty(auth()->user()->name) && !str_starts_with(auth()->user()->name, 'User_'))
                                    {{ auth()->user()->name }}
                                @elseif(auth()->user()->phone)
                                    {{ substr(auth()->user()->phone, -10) }}
                                @else
                                    {{ explode('@', auth()->user()->email)[0] ?? 'Account' }}
                                @endif
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown" style="position: absolute;">
                                <a class="dropdown-item font-weight-bold text-dark" href="{{ route('user.dashboard') }}">
                                    <i class="fas fa-ticket-alt mr-2 text-success"></i> My Bookings
                                </a>
                                <a class="dropdown-item font-weight-bold text-dark" href="{{ route('user.profile') }}">
                                    <i class="fas fa-user-edit mr-2 text-success"></i> Edit Profile
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger font-weight-bold" href="#" onclick="event.preventDefault(); document.getElementById('logout-user-form').submit();">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Sign Out
                                </a>
                                <form id="logout-user-form" action="{{ route('logout.user') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-success font-weight-bold" style="font-size: 0.78rem !important; padding: 5px 10px !important; border-radius: 20px !important; white-space: nowrap !important;">
                            <i class="fas fa-sign-in-alt mr-1"></i> <span class="d-sm-inline">Sign In</span>
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</nav>

@section('js')
@parent
<script>
$(document).ready(function () {
    // For mobile: clicking a submenu parent toggles its visibility
    if ($(window).width() < 1200) {
        $('.main-navbar .dropdown-menu > li > a').on('click', function (e) {
            var $submenu = $(this).siblings('.dropdown-submenu');
            if ($submenu.length) {
                e.preventDefault();
                e.stopPropagation();
                $(this).parent('li').toggleClass('mobile-open');
                $submenu.slideToggle(200);
            }
        });
    }
});
</script>
@endsection
