@section('css')
@parent
<style>
    nav.main-navbar {
        box-shadow: 0 4px 20px rgba(0,0,0,0.04) !important;
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

    /* Premium Dropdown Styling */
    .main-navbar .dropdown-menu {
        border: 1px solid var(--brand-border, #ddf3e8) !important;
        border-top: 4px solid var(--brand-green, #01a755) !important;
        border-radius: 14px !important;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08) !important;
        padding: 10px 8px !important;
        min-width: 220px !important;
        background-color: #ffffff !important;
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
    }

    /* Dropdown Item Hover */
    .main-navbar .dropdown-menu .dropdown-item:hover,
    .main-navbar .dropdown-menu .dropdown-item:focus {
        background-color: #eaf8f1 !important;
        color: var(--brand-green, #01a755) !important;
        padding-left: 20px !important;
    }

    /* Active Nav link inside Dropdown */
    .main-navbar .dropdown-menu .dropdown-item.active-nav {
        background-color: var(--brand-green, #01a755) !important;
        color: #ffffff !important;
    }

    /* Dropdown Dividers */
    .main-navbar .dropdown-divider {
        border-top: 1px dashed var(--brand-border, #ddf3e8) !important;
        margin: 6px 8px !important;
    }

    /* Dropdown Fade & Slide Animation */
    @keyframes dropdownEntrance {
        from {
            opacity: 0;
            transform: translateY(12px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .main-navbar .dropdown-menu.show {
        display: block !important;
        animation: dropdownEntrance 0.24s cubic-bezier(0.25, 0.8, 0.25, 1) forwards !important;
    }

    /* Submenu (Level 2) dropdown positioning & styles */
    .main-navbar .dropdown-menu .dropdown-submenu {
        display: none !important;
        position: absolute !important;
        left: 100% !important;
        top: -6px !important;
        margin-left: 8px !important;
    }

    .main-navbar .dropdown-menu > li {
        position: relative !important;
    }

    .main-navbar .dropdown-menu > li:hover > .dropdown-submenu {
        display: block !important;
        animation: dropdownEntrance 0.2s cubic-bezier(0.25, 0.8, 0.25, 1) forwards !important;
    }

    /* ============================
       HAMBURGER BUTTON STYLING
       ============================ */
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
    
    .hamburger-btn:hover {
        background: #d4f3e3 !important;
    }
    
    .hamburger-btn:focus {
        outline: none !important;
        box-shadow: 0 0 0 3px rgba(1, 167, 85, 0.35) !important;
    }
    
    .hamburger-btn i {
        color: var(--brand-green, #01a755) !important;
        font-size: 20px !important;
        transition: all 0.3s ease !important;
    }
    
    /* Change colors when menu is open */
    .hamburger-btn[aria-expanded="true"] {
        border-color: #dc3545 !important;
        background: #fde8e8 !important;
    }
    
    .hamburger-btn[aria-expanded="true"] i {
        color: #dc3545 !important;
    }

    /* Brand logo default desktop styling (webview) */
    .navbar-brand,
    .navbar-brand.order-xl-1 {
        display: inline-flex;
        align-items: center;
    }
    .navbar-brand img {
        height: 60px !important;
        width: 200px !important;
        max-width: 200px !important;
        object-fit: contain !important;
        transition: all 0.2s ease;
    }

    /* ============================
       MOBILE & TABLET RESPONSIVE OVERRIDES
       ============================ */
    @media (max-width: 1199px) {
        .navbar-brand img {
            height: 30px !important;
            width: 100px !important;
            max-width: 100px !important;
            object-fit: contain !important;
        }

        .main-navbar .btn-sm {
            font-size: 0.85rem !important;
            padding: 8px 12px !important;
        }
        
        /* Premium Mobile menu styling */
        #navbarSupportedContent {
            background-color: #ffffff !important;
            border-top: 3px solid var(--brand-green, #01a755);
            padding: 15px 20px 25px;
            border-radius: 0 0 16px 16px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 1000;
            transition: all 0.3s ease-in-out;
        }

        #navbarSupportedContent .navbar-nav {
            margin-bottom: 15px;
        }

        #navbarSupportedContent .nav-item {
            margin-bottom: 5px;
        }

        #navbarSupportedContent .nav-link {
            color: #334155 !important;
            font-weight: 600;
            font-size: 1.05rem;
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid transparent;
            transition: all 0.2s ease;
        }
        
        #navbarSupportedContent .nav-link:hover,
        #navbarSupportedContent .nav-link:focus,
        #navbarSupportedContent .nav-link.active-nav {
            background-color: #eaf8f1 !important;
            color: var(--brand-green, #01a755) !important;
            border: 1px solid #d4f3e3;
            padding-left: 20px;
        }

        .action-buttons-mobile {
            margin-top: 15px;
            padding-top: 20px;
            border-top: 1px dashed var(--brand-border, #ddf3e8);
            flex-direction: column !important;
            align-items: stretch !important;
            width: 100%;
        }
        
        .action-buttons-mobile .btn, .action-buttons-mobile .dropdown {
            margin-bottom: 12px;
            width: 100%;
            text-align: center;
            display: block;
        }
        
        .action-buttons-mobile .btn {
            padding: 10px 15px !important;
            font-size: 0.9rem !important;
            font-weight: 600;
            border-radius: 8px !important;
        }
        
        .action-buttons-mobile .dropdown-menu {
            position: static !important;
            width: 100%;
            text-align: center;
            box-shadow: none !important;
            border: 1px solid var(--brand-border, #ddf3e8) !important;
            margin-top: 8px;
        }
        
        /* Mobile dropdown fixes */
        .main-navbar .dropdown-menu {
            box-shadow: none !important;
            border: none !important;
            background-color: #f8fbf9 !important;
            padding-left: 15px !important;
            border-radius: 8px !important;
            margin-top: 5px;
        }
        
        .main-navbar .dropdown-menu .dropdown-submenu {
            position: relative !important;
            left: 0 !important;
            top: 0 !important;
            margin-left: 10px !important;
            display: block !important;
            border-left: 2px solid var(--brand-green, #01a755) !important;
            background-color: transparent !important;
        }
    }

</style>
@endsection



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
            <li class="nav-item">
                <a class="nav-link {{ request()->is('/')? "active-nav":"" }}" href="/">
                    Home
                    <span class="sr-only">(current)</span>
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link {{ request()->is('about-us*')|| request()->is('organization')? "active-nav":"" }} dropdown-toggle" href="{{ route('about') }}" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    About Us
                </a>
                @if(isset($_aboutMenu))
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        @foreach($_aboutMenu as $key => $menu)
                            <li>
                                <a class="dropdown-item {{ request()->is('about-us/'.$menu->slug)? "active-nav":"" }}"
                                   href="{{ route('about.detail', $menu->slug) }}">
                                    {{ $menu->title ?? "Menu".++$key }}
                                </a>
                            </li>
                        @endforeach
                        <li>
                            <a class="dropdown-item {{ request()->is('organization')? "active-nav":"" }}"
                               href="{{ route('organization') }}">
                                {{ "Organization" }}
                            </a>
                        </li>
                    </ul>
                @endif
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link {{ request()->is('hrm*')? "active-nav":"" }} dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    HRM
                </a>
                @if(isset($_hrm))
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown" >
                        @foreach($_hrm as $key => $menuHrm)
                            <li>
                                <a class="dropdown-item {{ request()->is('hrm/'.$menuHrm->slug)? "active-nav":"" }}" href="{{ route('hrm.detail', $menuHrm->slug) }}">
                                    {{ $menuHrm->title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link {{ request()->is('service*')? "active-nav":"" }} dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Services
                </a>
                @if(isset($_service_categories))
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown" >
                        @foreach($_service_categories as $key => $category)
                            <li>
                                <a class="dropdown-item {{ request()->is('service/'.$category->slug)? "active-nav":"" }}" href="{{ route('service.detail', $category->slug) }}">
                                    {{ $category->title }}
                                </a>
                                @if(isset($category->children) && $category->children->count() > 0)
                                    <ul class="dropdown-menu dropdown-submenu" >
                                        @foreach($category->children as $key => $cat)
                                            <li>
                                                <a style="font-size: 13px" class="dropdown-item {{ request()->is('service/'.$cat->slug)? "active-nav":"" }}" href="{{ route('service.detail', $cat->slug) }}">
                                                    {{ $cat->title }}
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
            <li class="nav-item">
                <a class="nav-link {{ request()->is('news*')? "active-nav":"" }}" href="{{ route('news') }}">
                    News 
                </a>
            </li>
            @if(config('services.events_enabled', true))
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('events*')? "active-nav":"" }}" href="{{ route('events.index') }}">Events</a>
                </li>
            @endif
            <li class="nav-item dropdown">
                <a class="nav-link {{ request()->is('notices*')? "active-nav":"" }} dropdown-toggle"
                   href="{{ route('notices') }}" id="navbarDropdown" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Notices
                </a>
				<ul class="dropdown-menu" aria-labelledby="navbarDropdown" >
                    <li>
                        <a class="dropdown-item {{ request()->is('notices*')? "active-nav":"" }}" href="{{ route('notices') }}">
                            Notices
                        </a>
                    </li>
					<li>
                        <a class="dropdown-item {{ request()->is('career*')? "active-nav":"" }}" href="{{ route('career') }}">
                            Career
                        </a>
                    </li>
                </ul>
            </li>
           
            <li class="nav-item">
                <a class="nav-link  {{ request()->is('resource-list*')? "active-nav":"" }}" href="{{ route('resource-list') }}">Resources</a>
            </li>
            <li class="nav-item">
                <a class="nav-link  {{ request()->is('gallery*')? "active-nav":"" }}" href="{{ route('gallery') }}">Gallery</a>
            </li>
            <li class="nav-item">
                <a class="nav-link  {{ request()->is('contact*')? "active-nav":"" }}" href="{{ route('contact') }}">Contact Us</a>
            </li>
        </ul>
        
        <!-- Right side: action buttons inside mobile menu -->
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
