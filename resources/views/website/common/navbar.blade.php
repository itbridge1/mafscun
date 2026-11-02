@section('css')
@parent
<style>
    /* ============================================================
       TOP-LEVEL NAVIGATION BAR
    ============================================================ */
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

    .main-navbar .nav-link {
        font-weight: 600;
        color: #334155 !important;
        transition: color 0.2s ease;
        padding: 8px 12px !important;
    }
    .main-navbar .nav-link:hover,
    .main-navbar .nav-link.active-nav {
        color: #01a755 !important;
    }

    .navbar-brand img {
        height: 60px;
        width: 200px;
        max-width: 200px;
        object-fit: contain;
        transition: all 0.2s ease;
    }

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

    /* Single-level Bootstrap Dropdowns (About Us, HRM, Notices, User) */
    .main-navbar .dropdown-menu {
        border: 1px solid #ddf3e8;
        border-top: 4px solid #01a755;
        border-radius: 12px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.08);
        padding: 8px;
        min-width: 220px;
        background-color: #ffffff;
    }
    .main-navbar .dropdown-item {
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
    .main-navbar .dropdown-item:hover,
    .main-navbar .dropdown-item:focus {
        background-color: #eaf8f1;
        color: #01a755;
        padding-left: 20px;
    }
    .main-navbar .dropdown-item.active-nav {
        background-color: #01a755;
        color: #ffffff;
    }
    .main-navbar .dropdown-divider {
        border-top: 1px dashed #ddf3e8;
        margin: 6px 8px;
    }

    /* ============================================================
       CLEAN 2-COLUMN MEGAMENU (Services & Resources)
       Left column = Categories list
       Right column = Submenu content panel (outside, right beside)
       NO MORE FLIMSY FLYOUTS THAT CLOSE ACCIDENTALLY!
    ============================================================ */
    @media (min-width: 1200px) {
        .nav-has-megamenu {
            position: relative;
        }

        .custom-megamenu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1200;
            background: #ffffff;
            border: 1px solid #ddf3e8;
            border-top: 4px solid #01a755;
            border-radius: 16px;
            box-shadow: 0 20px 45px rgba(10, 45, 25, 0.12);
            min-width: 640px;
            max-width: 820px;
            padding: 0;
            overflow: hidden;
            animation: megaMenuFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes megaMenuFadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Keep megamenu open on hover */
        .nav-has-megamenu:hover .custom-megamenu {
            display: flex;
        }

        .megamenu-inner {
            display: flex;
            width: 100%;
            min-height: 320px;
            max-height: 520px;
        }

        /* LEFT SIDEBAR: Category Tabs */
        .megamenu-tabs {
            flex: 0 0 240px;
            width: 240px;
            background: #f8faf8;
            border-right: 1px solid #e7efe9;
            padding: 12px 8px;
            overflow-y: auto;
            list-style: none;
            margin: 0;
        }

        .megamenu-tab-item {
            list-style: none;
            margin-bottom: 3px;
        }

        .megamenu-tab-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 10px 14px;
            border-radius: 10px;
            color: #334155;
            font-size: 0.92rem;
            font-weight: 600;
            text-decoration: none !important;
            transition: all 0.18s ease;
            cursor: pointer;
            border: none;
            background: transparent;
            text-align: left;
            gap: 8px;
        }

        .megamenu-tab-btn .tab-chevron {
            font-size: 0.72rem;
            opacity: 0.4;
            transition: transform 0.18s ease;
        }

        .megamenu-tab-btn:hover,
        .megamenu-tab-item.active .megamenu-tab-btn {
            background: #ffffff;
            color: #01a755;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
        }

        .megamenu-tab-btn:hover .tab-chevron,
        .megamenu-tab-item.active .megamenu-tab-btn .tab-chevron {
            opacity: 1;
            color: #01a755;
            transform: translateX(3px);
        }

        /* RIGHT CONTENT: Submenu items grid */
        .megamenu-content-area {
            flex: 1 1 auto;
            background: #ffffff;
            padding: 20px 24px;
            overflow-y: auto;
        }

        .megamenu-panel {
            display: none;
        }

        .megamenu-panel.active {
            display: block;
            animation: megaMenuContentFade 0.2s ease forwards;
        }

        @keyframes megaMenuContentFade {
            from { opacity: 0; transform: translateX(6px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        .megamenu-panel-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 12px;
            margin-bottom: 16px;
            border-bottom: 1px solid #edf4ef;
        }

        .megamenu-panel-title {
            font-size: 1.05rem;
            font-weight: 750;
            color: #17382a;
            margin: 0;
        }

        .megamenu-view-all {
            font-size: 0.82rem;
            font-weight: 650;
            color: #01a755;
            text-decoration: none !important;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 6px;
            background: #edf7ef;
            transition: all 0.15s ease;
        }
        .megamenu-view-all:hover {
            background: #01a755;
            color: #ffffff;
        }

        /* Subcategories & Services grid */
        .megamenu-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .megamenu-group {
            background: #fbfdfc;
            border: 1px solid #e7efe9;
            border-radius: 12px;
            padding: 12px 14px;
            transition: border-color 0.18s ease, box-shadow 0.18s ease;
        }
        .megamenu-group:hover {
            border-color: #b7e3cb;
            box-shadow: 0 4px 15px rgba(1, 167, 85, 0.06);
        }

        .megamenu-group-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 0.9rem;
            font-weight: 700;
            color: #01a755;
            text-decoration: none !important;
            margin-bottom: 8px;
            padding-bottom: 6px;
            border-bottom: 1px dashed #ddf3e8;
        }
        .megamenu-group-title:hover {
            color: #008f48;
        }

        .megamenu-service-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .megamenu-service-item {
            margin-bottom: 4px;
        }

        .megamenu-service-link {
            display: block;
            font-size: 0.85rem;
            font-weight: 550;
            color: #475569;
            text-decoration: none !important;
            padding: 5px 8px;
            border-radius: 6px;
            transition: all 0.15s ease;
            position: relative;
        }
        .megamenu-service-link:hover {
            background: #eaf8f1;
            color: #01a755;
            padding-left: 12px;
        }
        .megamenu-service-link::before {
            content: "•";
            margin-right: 6px;
            color: #01a755;
            opacity: 0.7;
        }

        /* Direct services (no sub-category) list */
        .megamenu-direct-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        .megamenu-direct-link {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 12px;
            border-radius: 10px;
            border: 1px solid #edf4ef;
            background: #fbfdfc;
            color: #334155;
            font-size: 0.88rem;
            font-weight: 600;
            text-decoration: none !important;
            transition: all 0.18s ease;
        }
        .megamenu-direct-link:hover {
            background: #eaf8f1;
            border-color: #01a755;
            color: #01a755;
            transform: translateX(3px);
        }
    }

    /* ============================================================
       MOBILE ACCORDION (Screens < 1200px)
    ============================================================ */
    @media (max-width: 1199.98px) {
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

        /* Mobile Megamenu Collapse */
        .custom-megamenu {
            display: none;
            background: #f8faf8;
            border-left: 3px solid #01a755;
            padding: 8px 12px;
            border-radius: 0 8px 8px 0;
            margin-top: 6px;
        }
        .nav-has-megamenu.open .custom-megamenu {
            display: block;
        }
        .megamenu-inner { display: block; }
        .megamenu-tabs { display: none; }
        .megamenu-panel { display: block !important; margin-bottom: 14px; }
        .megamenu-panel-header {
            padding-bottom: 6px;
            margin-bottom: 8px;
            border-bottom: 1px dashed #ddf3e8;
        }
        .megamenu-grid {
            display: block;
        }
        .megamenu-group {
            margin-bottom: 10px;
            padding: 8px 10px;
        }

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

                {{-- ABOUT US --}}
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

                {{-- HRM --}}
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

                {{-- SERVICES (Modern 2-Column MegaMenu) --}}
                @if(isset($_service_categories) && $_service_categories->count() > 0)
                <li class="nav-item nav-has-megamenu">
                    <a class="nav-link {{ request()->is('service*') ? 'active-nav' : '' }}"
                       href="{{ route('service') }}">
                        Services <i class="fas fa-chevron-down ml-1" style="font-size:0.65rem;opacity:0.6;"></i>
                    </a>

                    <div class="custom-megamenu">
                        <div class="megamenu-inner">
                            <!-- Left: Categories list -->
                            <ul class="megamenu-tabs">
                                @foreach($_service_categories as $cIdx => $category)
                                    <li class="megamenu-tab-item {{ $cIdx === 0 ? 'active' : '' }}" data-target="svc-panel-{{ $category->id }}">
                                        <a href="{{ $category->url }}" class="megamenu-tab-btn">
                                            <span>{{ $category->title }}</span>
                                            <i class="fas fa-chevron-right tab-chevron"></i>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>

                            <!-- Right: Content panels for the hovered category -->
                            <div class="megamenu-content-area">
                                @foreach($_service_categories as $cIdx => $category)
                                    <div class="megamenu-panel {{ $cIdx === 0 ? 'active' : '' }}" id="svc-panel-{{ $category->id }}">
                                        <div class="megamenu-panel-header">
                                            <h4 class="megamenu-panel-title">{{ $category->title }}</h4>
                                            <a href="{{ $category->url }}" class="megamenu-view-all">
                                                सम्पूर्ण हेर्नुहोस् <i class="fas fa-arrow-right"></i>
                                            </a>
                                        </div>

                                        @if($category->subCategories->count() > 0)
                                            <div class="megamenu-grid">
                                                @foreach($category->subCategories as $subCat)
                                                    <div class="megamenu-group">
                                                        <a href="{{ $subCat->url }}" class="megamenu-group-title">
                                                            <span>{{ $subCat->title }}</span>
                                                            <i class="fas fa-arrow-right" style="font-size:0.75rem;"></i>
                                                        </a>
                                                        @if($subCat->services->count() > 0)
                                                            <ul class="megamenu-service-list">
                                                                @foreach($subCat->services as $svc)
                                                                    <li class="megamenu-service-item">
                                                                        <a href="{{ $svc->url }}" class="megamenu-service-link">
                                                                            {{ $svc->title }}
                                                                        </a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif

                                        @if($category->directServices->count() > 0)
                                            <div class="mt-3">
                                                <div class="megamenu-direct-grid">
                                                    @foreach($category->directServices as $dsvc)
                                                        <a href="{{ $dsvc->url }}" class="megamenu-direct-link">
                                                            <i class="fas fa-check-circle text-success" style="font-size:0.85rem;"></i>
                                                            <span>{{ $dsvc->title }}</span>
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
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

                {{-- NOTICES --}}
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

                {{-- RESOURCES (Modern 2-Column MegaMenu) --}}
                @if(isset($_resource_types) && $_resource_types->count() > 0)
                <li class="nav-item nav-has-megamenu">
                    <a class="nav-link {{ request()->is('resource-list*') ? 'active-nav' : '' }}"
                       href="{{ route('resource-list') }}">
                        Resources <i class="fas fa-chevron-down ml-1" style="font-size:0.65rem;opacity:0.6;"></i>
                    </a>

                    <div class="custom-megamenu" style="min-width: 580px;">
                        <div class="megamenu-inner">
                            <ul class="megamenu-tabs">
                                <li class="megamenu-tab-item active" data-target="res-panel-all">
                                    <a href="{{ route('resource-list') }}" class="megamenu-tab-btn">
                                        <span>All Resources</span>
                                        <i class="fas fa-chevron-right tab-chevron"></i>
                                    </a>
                                </li>
                                @foreach($_resource_types as $rType)
                                    <li class="megamenu-tab-item" data-target="res-panel-{{ $rType->id }}">
                                        <a href="{{ route('resource-list', ['filter_resource_type' => $rType->id]) }}" class="megamenu-tab-btn">
                                            <span>{{ $rType->title }}</span>
                                            <i class="fas fa-chevron-right tab-chevron"></i>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="megamenu-content-area">
                                <div class="megamenu-panel active" id="res-panel-all">
                                    <div class="megamenu-panel-header">
                                        <h4 class="megamenu-panel-title">स्रोत तथा सामग्रीहरू</h4>
                                        <a href="{{ route('resource-list') }}" class="megamenu-view-all">
                                            सबै हेर्नुहोस् <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                    <div class="megamenu-direct-grid">
                                        @foreach($_resource_types as $rType)
                                            <a href="{{ route('resource-list', ['filter_resource_type' => $rType->id]) }}" class="megamenu-direct-link">
                                                <i class="fas fa-folder text-success"></i>
                                                <span>{{ $rType->title }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>

                                @foreach($_resource_types as $rType)
                                    <div class="megamenu-panel" id="res-panel-{{ $rType->id }}">
                                        <div class="megamenu-panel-header">
                                            <h4 class="megamenu-panel-title">{{ $rType->title }}</h4>
                                            <a href="{{ route('resource-list', ['filter_resource_type' => $rType->id]) }}" class="megamenu-view-all">
                                                सबै हेर्नुहोस् <i class="fas fa-arrow-right"></i>
                                            </a>
                                        </div>
                                        @if($rType->subTypes->count() > 0)
                                            <div class="megamenu-direct-grid">
                                                @foreach($rType->subTypes as $subType)
                                                    <a href="{{ route('resource-list', ['filter_resource_type' => $rType->id, 'filter_sub_type' => $subType->id]) }}" class="megamenu-direct-link">
                                                        <i class="fas fa-file-alt text-muted"></i>
                                                        <span>{{ $subType->title }}</span>
                                                    </a>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-muted small">यस अन्तर्गतका सामग्रीहरू अवलोकन गर्नुहोस्।</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
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

@section('js')
@parent
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Desktop: Tab Hover switching for Megamenu
    document.querySelectorAll('.megamenu-tab-item').forEach(function (tab) {
        tab.addEventListener('mouseenter', function () {
            var targetId = this.getAttribute('data-target');
            if (!targetId) return;

            var parentMegamenu = this.closest('.custom-megamenu');
            if (!parentMegamenu) return;

            // Update tab active state
            parentMegamenu.querySelectorAll('.megamenu-tab-item').forEach(function (t) {
                t.classList.remove('active');
            });
            this.classList.add('active');

            // Switch content panel
            parentMegamenu.querySelectorAll('.megamenu-panel').forEach(function (panel) {
                panel.classList.remove('active');
            });
            var targetPanel = parentMegamenu.querySelector('#' + targetId);
            if (targetPanel) {
                targetPanel.classList.add('active');
            }
        });
    });

    // Mobile: Toggle megamenu on touch
    document.querySelectorAll('.nav-has-megamenu > .nav-link').forEach(function (link) {
        link.addEventListener('click', function (e) {
            if (window.innerWidth < 1200) {
                e.preventDefault();
                var parent = this.closest('.nav-has-megamenu');
                var isOpen = parent.classList.contains('open');
                document.querySelectorAll('.nav-has-megamenu').forEach(function (el) {
                    el.classList.remove('open');
                });
                if (!isOpen) {
                    parent.classList.add('open');
                }
            }
        });
    });
});
</script>
@endsection
