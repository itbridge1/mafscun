@section('css')
@parent
<style>
    /* =============================================
       NAVBAR BASE
    ============================================= */
    nav.main-navbar {
        box-shadow: 0 4px 20px rgba(0,0,0,0.04);
        border-bottom: 1px solid #ddf3e8;
        transition: all 0.3s ease-in-out;
    }
    nav.main-navbar.navbar-scrolled {
        background-color: rgba(255,255,255,0.96) !important;
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        padding-top: 4px !important;
        padding-bottom: 4px !important;
        box-shadow: 0 6px 25px rgba(0,0,0,0.1) !important;
        border-bottom: 1px solid #01a755 !important;
    }

    /* =============================================
       NAV LINKS
    ============================================= */
    .main-navbar .nav-link {
        font-weight: 600;
        color: #334155;
        transition: color 0.2s ease;
    }
    .main-navbar .nav-link:hover,
    .main-navbar .nav-link.active-nav {
        color: #01a755 !important;
    }

    /* =============================================
       BRAND LOGO
    ============================================= */
    .navbar-brand img {
        height: 60px;
        width: 200px;
        max-width: 200px;
        object-fit: contain;
        transition: all 0.2s ease;
    }

    /* =============================================
       HAMBURGER BUTTON
    ============================================= */
    .hamburger-btn {
        display: flex !important;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 44px;
        height: 44px;
        padding: 6px;
        background: #eaf8f1;
        border: 2px solid #01a755;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        outline: none;
    }
    .hamburger-btn:hover { background: #d4f3e3; }
    .hamburger-btn:focus { outline: none; box-shadow: 0 0 0 3px rgba(1,167,85,0.35); }
    .hamburger-btn i { color: #01a755; font-size: 20px; transition: all 0.3s ease; }
    .hamburger-btn[aria-expanded="true"] { border-color: #dc3545; background: #fde8e8; }
    .hamburger-btn[aria-expanded="true"] i { color: #dc3545; }

    /* =============================================
       BOOTSTRAP DROPDOWN (About, HRM, Notices, User)
       — kept as-is, just styled nicely
    ============================================= */
    .main-navbar .dropdown-menu {
        border: 1px solid #ddf3e8;
        border-top: 4px solid #01a755;
        border-radius: 14px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.08);
        padding: 10px 8px;
        min-width: 220px;
        background-color: #ffffff;
        overflow: visible;
    }
    .main-navbar .dropdown-menu.show {
        display: block;
        animation: navFadeIn 0.22s ease forwards;
    }
    .main-navbar .dropdown-menu .dropdown-item {
        font-size: 0.92rem;
        font-weight: 600;
        color: #475569;
        padding: 9px 16px;
        border-radius: 8px;
        transition: all 0.2s ease;
        margin-bottom: 2px;
        white-space: normal;
        line-height: 1.4;
    }
    .main-navbar .dropdown-menu .dropdown-item:hover,
    .main-navbar .dropdown-menu .dropdown-item:focus {
        background-color: #eaf8f1;
        color: #01a755;
        padding-left: 20px;
    }
    .main-navbar .dropdown-menu .dropdown-item.active-nav {
        background-color: #01a755;
        color: #ffffff;
    }
    .main-navbar .dropdown-divider {
        border-top: 1px dashed #ddf3e8;
        margin: 6px 8px;
    }

    /* =============================================
       CUSTOM FLYOUT MENU SYSTEM
       Used for: Services & Resources (replaces
       Bootstrap dropdown for multi-level menus)
    ============================================= */

    /* The top-level nav item that owns a flyout */
    .nav-has-flyout {
        position: relative;
    }

    /* Level-1 flyout panel (opens below nav link) */
    .nav-flyout {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 1200;
        min-width: 230px;
        background: #ffffff;
        border: 1px solid #ddf3e8;
        border-top: 4px solid #01a755;
        border-radius: 14px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.1);
        padding: 10px 8px;
        list-style: none;
        margin: 0;
    }

    /* Show level-1 on hover of the parent li */
    .nav-has-flyout:hover > .nav-flyout {
        display: block;
        animation: navFadeIn 0.22s ease forwards;
    }

    /* Items inside any flyout */
    .nav-flyout-item {
        position: relative;
        list-style: none;
    }

    /* Anchor inside a flyout item */
    .nav-flyout-item > a {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.92rem;
        font-weight: 600;
        color: #475569;
        padding: 9px 16px;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
        margin-bottom: 2px;
        white-space: nowrap;
        line-height: 1.4;
        gap: 8px;
    }
    .nav-flyout-item > a:hover,
    .nav-flyout-item > a:focus {
        background-color: #eaf8f1;
        color: #01a755;
        padding-left: 20px;
    }
    .nav-flyout-item > a.active-nav {
        background-color: #01a755;
        color: #ffffff;
    }
    .nav-flyout-item > a .flyout-arrow {
        font-size: 0.7rem;
        opacity: 0.5;
        flex-shrink: 0;
    }
    .nav-flyout-item > a:hover .flyout-arrow {
        opacity: 1;
        color: #01a755;
    }
    .nav-flyout-item > a.active-nav .flyout-arrow {
        opacity: 1;
        color: #fff;
    }

    /* Level-2 flyout panel (opens to the right of a flyout-item) */
    .nav-flyout-sub {
        display: none;
        position: absolute;
        top: -6px;
        left: calc(100% - 2px);
        margin-left: 0;
        z-index: 1300;
        min-width: 220px;
        max-height: 80vh;
        overflow-y: auto;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-top: 3px solid #01a755;
        border-radius: 12px;
        box-shadow: 0 12px 35px rgba(0,0,0,0.12);
        padding: 8px 6px;
        list-style: none;
        margin-top: 0;
    }

    /* Invisible hover buffer so moving mouse diagonally doesn't close the submenu */
    .nav-flyout-sub::before,
    .nav-flyout-sub2::before {
        content: '';
        position: absolute;
        top: 0;
        left: -20px;
        width: 24px;
        height: 100%;
        pointer-events: auto;
    }

    /* Show level-2 on hover */
    .nav-flyout-item:hover > .nav-flyout-sub {
        display: block;
        animation: navSlideIn 0.18s ease forwards;
    }

    /* Items inside level-2 flyout */
    .nav-flyout-sub .nav-flyout-item > a {
        font-size: 0.88rem;
        padding: 8px 14px;
    }

    /* Level-3 flyout (services inside a sub-category) */
    .nav-flyout-sub2 {
        display: none;
        position: absolute;
        top: -6px;
        left: calc(100% - 2px);
        margin-left: 0;
        z-index: 1400;
        min-width: 200px;
        max-height: 75vh;
        overflow-y: auto;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-top: 3px solid #00acf1;
        border-radius: 12px;
        box-shadow: 0 12px 35px rgba(0,0,0,0.12);
        padding: 8px 6px;
        list-style: none;
    }
    .nav-flyout-item:hover > .nav-flyout-sub2 {
        display: block;
        animation: navSlideIn 0.18s ease forwards;
    }
    .nav-flyout-sub2 .nav-flyout-item > a {
        font-size: 0.84rem;
        padding: 7px 12px;
    }

    /* Animations */
    @keyframes navFadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes navSlideIn {
        from { opacity: 0; transform: translateX(-8px); }
        to   { opacity: 1; transform: translateX(0); }
    }

    /* =============================================
       ACTION BUTTONS
    ============================================= */
    .action-buttons-mobile {
        display: flex;
        align-items: center;
    }

    /* =============================================
       MOBILE & TABLET  (≤ 1199px)
    ============================================= */
    @media (max-width: 1199px) {
        .navbar-brand img {
            height: 30px !important;
            width: 100px !important;
            max-width: 100px !important;
        }
        #navbarSupportedContent {
            background-color: #ffffff;
            border-top: 3px solid #01a755;
            padding: 15px 20px 25px;
            border-radius: 0 0 16px 16px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 1100;
        }
        #navbarSupportedContent .navbar-nav { margin-bottom: 15px; }
        #navbarSupportedContent .nav-item { margin-bottom: 5px; }
        #navbarSupportedContent .nav-link {
            color: #334155 !important;
            font-weight: 600;
            font-size: 1.05rem;
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid transparent;
        }
        #navbarSupportedContent .nav-link:hover,
        #navbarSupportedContent .nav-link.active-nav {
            background-color: #eaf8f1 !important;
            color: #01a755 !important;
            border-color: #d4f3e3;
            padding-left: 20px;
        }

        /* Bootstrap dropdowns on mobile */
        .main-navbar .dropdown-menu {
            position: static !important;
            box-shadow: none !important;
            border: none !important;
            border-left: 3px solid #01a755 !important;
            border-top: none !important;
            border-radius: 0 8px 8px 0 !important;
            background-color: #f4fcf7 !important;
            padding-left: 10px !important;
            margin-top: 4px !important;
        }

        /* Custom flyouts on mobile — collapse to accordion-style */
        .nav-has-flyout { position: static; }
        .nav-flyout {
            display: none;
            position: static !important;
            box-shadow: none !important;
            border: none !important;
            border-left: 3px solid #01a755 !important;
            border-top: none !important;
            border-radius: 0 8px 8px 0 !important;
            background-color: #f4fcf7 !important;
            padding: 5px 0 5px 8px !important;
            margin-top: 4px !important;
            animation: none !important;
        }
        /* On mobile open flyout with a toggle class added by JS */
        .nav-has-flyout.flyout-open > .nav-flyout { display: block !important; }

        .nav-flyout-item > a {
            padding: 8px 12px;
            font-size: 0.95rem;
            white-space: normal;
        }
        .nav-flyout-sub,
        .nav-flyout-sub2 {
            display: none;
            position: static !important;
            box-shadow: none !important;
            border: none !important;
            border-left: 3px solid #00acf1 !important;
            border-top: none !important;
            border-radius: 0 !important;
            background-color: #edf7ef !important;
            padding: 4px 0 4px 8px !important;
            margin-top: 2px !important;
            animation: none !important;
        }
        .nav-flyout-item.flyout-open > .nav-flyout-sub,
        .nav-flyout-item.flyout-open > .nav-flyout-sub2 { display: block !important; }

        .action-buttons-mobile {
            margin-top: 15px;
            padding-top: 20px;
            border-top: 1px dashed #ddf3e8;
            flex-direction: column !important;
            align-items: stretch !important;
            width: 100%;
        }
        .action-buttons-mobile .btn,
        .action-buttons-mobile .dropdown { margin-bottom: 12px; width: 100%; text-align: center; display: block; }
        .action-buttons-mobile .btn { padding: 10px 15px !important; font-size: 0.9rem !important; font-weight: 600; border-radius: 8px !important; }
        .action-buttons-mobile .dropdown-menu { position: static !important; width: 100%; text-align: center; box-shadow: none !important; border: 1px solid #ddf3e8 !important; margin-top: 8px; }
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

            {{-- HOME --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->is('/') ? 'active-nav' : '' }}" href="/">
                    Home <span class="sr-only">(current)</span>
                </a>
            </li>

            {{-- ABOUT US — Bootstrap dropdown (single level) --}}
            <li class="nav-item dropdown">
                <a class="nav-link {{ request()->is('about-us*') || request()->is('organization') ? 'active-nav' : '' }} dropdown-toggle"
                   href="{{ route('about') }}" id="navbarDropdownAbout" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    About Us
                </a>
                @if(isset($_aboutMenu))
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownAbout">
                        @foreach($_aboutMenu as $key => $menu)
                            <li>
                                <a class="dropdown-item {{ request()->is('about-us/'.$menu->slug) ? 'active-nav' : '' }}"
                                   href="{{ route('about.detail', $menu->slug) }}">
                                    {{ $menu->title ?? 'Menu'.++$key }}
                                </a>
                            </li>
                        @endforeach
                        <li>
                            <a class="dropdown-item {{ request()->is('organization') ? 'active-nav' : '' }}"
                               href="{{ route('organization') }}">Organization</a>
                        </li>
                    </ul>
                @endif
            </li>

            {{-- HRM — Bootstrap dropdown (single level) --}}
            <li class="nav-item dropdown">
                <a class="nav-link {{ request()->is('hrm*') ? 'active-nav' : '' }} dropdown-toggle"
                   href="#" id="navbarDropdownHrm" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    HRM
                </a>
                @if(isset($_hrm))
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownHrm">
                        @foreach($_hrm as $menuHrm)
                            <li>
                                <a class="dropdown-item {{ request()->is('hrm/'.$menuHrm->slug) ? 'active-nav' : '' }}"
                                   href="{{ route('hrm.detail', $menuHrm->slug) }}">
                                    {{ $menuHrm->title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>

            {{-- SERVICES — Custom flyout (multi-level, pure CSS hover) --}}
            @if(isset($_service_categories) && $_service_categories->count() > 0)
            <li class="nav-item nav-has-flyout">
                <a class="nav-link {{ request()->is('service*') ? 'active-nav' : '' }}"
                   href="{{ route('service') }}">
                    Services <i class="fas fa-chevron-down ml-1" style="font-size:0.65rem;opacity:0.6;"></i>
                </a>

                {{-- Level-1 flyout: list of categories --}}
                <ul class="nav-flyout">
                    @foreach($_service_categories as $category)
                        @php
                            $hasSubCats       = $category->subCategories->count() > 0;
                            $hasDirectServices = $category->directServices->count() > 0;
                            $hasChildren      = $hasSubCats || $hasDirectServices;
                        @endphp
                        <li class="nav-flyout-item">
                            <a class="{{ request()->is('service/'.$category->slug.'*') ? 'active-nav' : '' }}"
                               href="{{ $category->url }}">
                                {{ $category->title }}
                                @if($hasChildren)
                                    <i class="fas fa-chevron-right flyout-arrow"></i>
                                @endif
                            </a>

                            {{-- Level-2 flyout: sub-categories + direct services --}}
                            @if($hasChildren)
                                <ul class="nav-flyout-sub">
                                    @foreach($category->subCategories as $subCat)
                                        @php $hasServices = $subCat->services->count() > 0; @endphp
                                        <li class="nav-flyout-item">
                                            <a href="{{ $subCat->url }}">
                                                {{ $subCat->title }}
                                                @if($hasServices)
                                                    <i class="fas fa-chevron-right flyout-arrow"></i>
                                                @endif
                                            </a>

                                            {{-- Level-3 flyout: services inside sub-category --}}
                                            @if($hasServices)
                                                <ul class="nav-flyout-sub2">
                                                    @foreach($subCat->services as $svc)
                                                        <li class="nav-flyout-item">
                                                            <a href="{{ $svc->url }}">{{ $svc->title }}</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                    @foreach($category->directServices as $directSvc)
                                        <li class="nav-flyout-item">
                                            <a href="{{ $directSvc->url }}">{{ $directSvc->title }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </li>
            @endif

            {{-- NEWS --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->is('news*') ? 'active-nav' : '' }}" href="{{ route('news') }}">News</a>
            </li>

            {{-- EVENTS --}}
            @if(config('services.events_enabled', true))
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('events*') ? 'active-nav' : '' }}" href="{{ route('events.index') }}">Events</a>
                </li>
            @endif

            {{-- NOTICES — Bootstrap dropdown (single level) --}}
            <li class="nav-item dropdown">
                <a class="nav-link {{ request()->is('notices*') ? 'active-nav' : '' }} dropdown-toggle"
                   href="{{ route('notices') }}" id="navbarDropdownNotices" role="button"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Notices
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdownNotices">
                    <li>
                        <a class="dropdown-item {{ request()->is('notices*') ? 'active-nav' : '' }}" href="{{ route('notices') }}">Notices</a>
                    </li>
                    <li>
                        <a class="dropdown-item {{ request()->is('career*') ? 'active-nav' : '' }}" href="{{ route('career') }}">Career</a>
                    </li>
                </ul>
            </li>

            {{-- RESOURCES — Custom flyout (two-level) --}}
            @if(isset($_resource_types) && $_resource_types->count() > 0)
            <li class="nav-item nav-has-flyout">
                <a class="nav-link {{ request()->is('resource-list*') ? 'active-nav' : '' }}"
                   href="{{ route('resource-list') }}">
                    Resources <i class="fas fa-chevron-down ml-1" style="font-size:0.65rem;opacity:0.6;"></i>
                </a>

                <ul class="nav-flyout">
                    <li class="nav-flyout-item">
                        <a href="{{ route('resource-list') }}">All Resources</a>
                    </li>
                    @foreach($_resource_types as $rType)
                        @php $hasSubTypes = $rType->subTypes->count() > 0; @endphp
                        <li class="nav-flyout-item">
                            <a class="{{ request('filter_resource_type') == $rType->id && !request('filter_sub_type') ? 'active-nav' : '' }}"
                               href="{{ route('resource-list', ['filter_resource_type' => $rType->id]) }}">
                                {{ $rType->title }}
                                @if($hasSubTypes)
                                    <i class="fas fa-chevron-right flyout-arrow"></i>
                                @endif
                            </a>
                            @if($hasSubTypes)
                                <ul class="nav-flyout-sub">
                                    @foreach($rType->subTypes as $subType)
                                        <li class="nav-flyout-item">
                                            <a class="{{ request('filter_resource_type') == $rType->id && request('filter_sub_type') == $subType->id ? 'active-nav' : '' }}"
                                               href="{{ route('resource-list', ['filter_resource_type' => $rType->id, 'filter_sub_type' => $subType->id]) }}">
                                                {{ $subType->title }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </li>
            @endif

            {{-- GALLERY --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->is('gallery*') ? 'active-nav' : '' }}" href="{{ route('gallery') }}">Gallery</a>
            </li>

            {{-- CONTACT --}}
            <li class="nav-item">
                <a class="nav-link {{ request()->is('contact*') ? 'active-nav' : '' }}" href="{{ route('contact') }}">Contact Us</a>
            </li>
        </ul>

        <!-- Right side: action buttons -->
        @if(config('services.events_enabled', true))
            <div class="d-flex align-items-center ml-auto order-xl-3 action-buttons-mobile">
                @if(auth()->check())
                    <div class="dropdown">
                        <a class="btn btn-sm btn-outline-success dropdown-toggle font-weight-bold"
                           href="#" id="userDropdown" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                           style="font-size:0.78rem !important;padding:5px 10px !important;border-radius:20px !important;white-space:nowrap !important;">
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
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown" style="position:absolute;">
                            <a class="dropdown-item font-weight-bold text-dark" href="{{ route('user.dashboard') }}">
                                <i class="fas fa-ticket-alt mr-2 text-success"></i> My Bookings
                            </a>
                            <a class="dropdown-item font-weight-bold text-dark" href="{{ route('user.profile') }}">
                                <i class="fas fa-user-edit mr-2 text-success"></i> Edit Profile
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-danger font-weight-bold" href="#"
                               onclick="event.preventDefault(); document.getElementById('logout-user-form').submit();">
                                <i class="fas fa-sign-out-alt mr-2"></i> Sign Out
                            </a>
                            <form id="logout-user-form" action="{{ route('logout.user') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline-success font-weight-bold"
                       style="font-size:0.78rem !important;padding:5px 10px !important;border-radius:20px !important;white-space:nowrap !important;">
                        <i class="fas fa-sign-in-alt mr-1"></i> <span class="d-sm-inline">Sign In</span>
                    </a>
                @endif
            </div>
        @endif
    </div>
    </div>
</nav>
