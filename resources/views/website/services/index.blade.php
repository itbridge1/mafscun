@extends('website.common.layout')

@section('title', $_title ?? (isset($_service) ? $_service->title : 'Services'))
@section('meta')
    @include('website.common.meta', [
    '_title' => $_title ?? (isset($_service) ? $_service->title : 'Services'),
    '_detail' => isset($_service) ? strip_tags($_service->description ?? '') : ('Explore the services offered by ' . ($_site_setting->title ?? 'MAFSCUN') . '.'),
    '_folder' => isset($_service) ? ($_service->photo_folder ?? 'serviceCategory') : 'serviceCategory',
    '_photo' => isset($_service) ? $_service->photo : null,
])
@endsection

@section('css')
<style>
    /* Section Headings */
    .subservices-section-title {
        font-size: 1.85rem !important;
        font-weight: 800 !important;
        color: var(--brand-green, #01a755) !important;
        position: relative;
        padding-bottom: 12px;
        margin-bottom: 30px;
        display: inline-block;
    }

    .subservices-section-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 60px;
        height: 3px;
        background-color: var(--brand-cyan, #00acf1);
        border-radius: 2px;
    }

    /* Desktop Navigation Pills Styling */
    .subservices-nav {
        background-color: #ffffff;
        border: 1px solid var(--brand-border, #ddf3e8);
        border-radius: 16px;
        padding: 15px !important;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.02);
    }

    .subservices-nav button.nav-link {
        background-color: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 12px !important;
        color: #475569 !important;
        font-weight: 600 !important;
        padding: 14px 18px !important;
        transition: all 0.25s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
        margin-bottom: 10px !important;
        width: 100% !important;
        text-align: left !important;
        border-bottom: 1px solid #e2e8f0 !important;
        border-right: 1px solid #e2e8f0 !important;
    }

    .subservices-nav button.nav-link:hover {
        background-color: var(--brand-bg-light, #f4fcf7) !important;
        color: var(--brand-green, #01a755) !important;
        border-color: var(--brand-green-light, #35b673) !important;
        transform: translateX(4px) !important;
    }

    .subservices-nav button.nav-link.active {
        background: linear-gradient(135deg, var(--brand-green, #01a755) 0%, var(--brand-green-hover, #008f48) 100%) !important;
        color: #ffffff !important;
        border-color: var(--brand-green, #01a755) !important;
        box-shadow: 0 6px 15px rgba(1, 167, 85, 0.2) !important;
    }

    .subservices-nav button.nav-link.active .arrow-icon {
        color: #ffffff !important;
        opacity: 1 !important;
        transform: rotate(90deg) !important;
    }

    .subservices-nav button.nav-link .arrow-icon {
        font-size: 0.8rem;
        opacity: 0.4;
        transition: transform 0.25s ease;
    }

    /* Content Card */
    .subservice-content-card {
        background: #ffffff !important;
        border: 1px solid var(--brand-border, #ddf3e8) !important;
        border-radius: 16px !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04) !important;
        padding: 32px !important;
        min-height: 380px;
    }

    .subservice-detail-title {
        font-size: 1.55rem !important;
        font-weight: 800 !important;
        color: var(--brand-green, #01a755) !important;
        margin-bottom: 18px !important;
        position: relative;
        padding-bottom: 8px;
        border-bottom: 1px solid var(--brand-border, #ddf3e8) !important;
        display: inline-block;
    }

    .subservice-detail-title::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 35px;
        height: 2px;
        background-color: var(--brand-cyan, #00acf1);
    }

    .subservice-desc-wrapper {
        position: relative;
        border-left: 4px solid var(--brand-green, #01a755);
        padding-left: 18px;
        margin-bottom: 25px;
    }

    .subservice-featured-img-wrapper {
        float: right;
        margin-left: 4%;
        margin-bottom: 20px;
        width: 34%;
        max-width: 34%;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
        border: 1px solid var(--brand-border, #ddf3e8);
    }

    .subservice-featured-img-wrapper img {
        width: 100%;
        height: auto;
        display: block;
    }

    .subservice-desc-text {
        font-size: 1.02rem !important;
        line-height: 1.8 !important;
        color: #475569 !important;
        text-align: justify;
    }

    .subservice-desc-text img {
        max-width: 100% !important;
        height: auto !important;
        border-radius: 8px;
        margin: 18px 0 !important;
        display: block !important;
    }

    /* Nested Service Mini Card */
    .nested-service-card {
        border: 1px solid var(--brand-border, #ddf3e8);
        border-radius: 14px;
        background: #ffffff;
        padding: 20px;
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        text-decoration: none !important;
        color: inherit !important;
    }

    .nested-service-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(1, 167, 85, 0.12);
        border-color: var(--brand-green, #01a755);
    }

    .nested-service-card__icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: #edf7ef;
        color: var(--brand-green, #01a755);
        display: grid;
        place-items: center;
        font-size: 1.15rem;
        margin-bottom: 14px;
    }

    .nested-service-card__title {
        font-size: 1.12rem;
        font-weight: 750;
        color: #17382a;
        margin-bottom: 6px;
        line-height: 1.35;
    }

    .nested-service-card__subtitle {
        font-size: 0.85rem;
        color: #64748b;
        margin-bottom: 10px;
    }

    .nested-service-card__desc {
        font-size: 0.9rem;
        color: #64748b;
        line-height: 1.6;
        margin-bottom: 16px;
        flex-grow: 1;
    }

    .nested-service-card__btn {
        margin-top: auto;
        display: inline-flex;
        align-items: center;
        color: var(--brand-green, #01a755);
        font-size: 0.88rem;
        font-weight: 700;
        gap: 6px;
    }

    .nested-service-card:hover .nested-service-card__btn {
        color: var(--brand-green-hover, #008f48);
    }

    /* Mobile Accordion */
    .mobile-subservices {
        padding-left: 10px !important;
        padding-right: 10px !important;
    }

    .accordion-card {
        border: 1px solid var(--brand-border, #ddf3e8) !important;
        border-radius: 12px !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02) !important;
        overflow: hidden;
        background-color: #ffffff !important;
        margin-bottom: 12px !important;
    }

    .accordion-header {
        background-color: #ffffff !important;
        padding: 0 !important;
        border-bottom: none !important;
    }

    .accordion-header button.btn-link {
        font-weight: 700 !important;
        font-size: 1.05rem !important;
        color: #334155 !important;
        text-decoration: none !important;
        transition: all 0.25s ease;
        width: 100%;
    }

    .accordion-header button.btn-link:hover, 
    .accordion-header button.btn-link:focus {
        text-decoration: none !important;
        box-shadow: none !important;
    }

    .accordion-header button.btn-link:not(.collapsed) {
        background-color: var(--brand-bg-light, #f4fcf7) !important;
        color: var(--brand-green, #01a755) !important;
        border-bottom: 1px solid var(--brand-border, #ddf3e8);
    }

    .accordion-arrow {
        font-size: 0.9rem;
        opacity: 0.6;
        transition: transform 0.25s ease;
    }

    .accordion-header button.btn-link:not(.collapsed) .accordion-arrow {
        transform: rotate(180deg);
        color: var(--brand-green, #01a755);
        opacity: 1;
    }

    .accordion-body {
        background-color: #ffffff;
    }

    .accordion-desc {
        font-size: 0.96rem !important;
        line-height: 1.7 !important;
        color: #475569 !important;
        text-align: justify;
    }

    /* Directory View Styles */
    .service-directory { margin-bottom: 54px; }
    .service-directory-intro { max-width: 720px; margin: 0 auto 30px; text-align: center; }
    .service-directory-kicker { margin-bottom: 8px; color: var(--brand-green); font-size: .75rem; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
    .service-directory-title { margin-bottom: 10px; color: #17382a; font-size: clamp(1.65rem, 3vw, 2.25rem); font-weight: 800; }
    .service-directory-summary { color: #64748b; line-height: 1.7; }
    .service-directory-card { position: relative; display: flex; height: 100%; min-height: 250px; flex-direction: column; overflow: hidden; padding: 26px; border: 1px solid var(--brand-border, #ddf3e8); border-radius: 16px; background: #fff; color: inherit; text-decoration: none !important; box-shadow: 0 8px 26px rgba(20, 55, 38, .045); transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease; }
    .service-directory-card:hover { transform: translateY(-4px); border-color: #9ed0ad; color: inherit; box-shadow: 0 16px 35px rgba(20, 55, 38, .11); }
    .service-directory-card__icon { display: grid; width: 48px; height: 48px; margin-bottom: 16px; place-items: center; border-radius: 13px; background: #edf7ef; color: var(--brand-green); font-size: 1.15rem; }
    .service-directory-card__title { margin: 0 0 8px; color: #17382a; font-size: 1.18rem; font-weight: 750; line-height: 1.35; }
    .service-directory-card__description { margin: 0 0 18px; color: #64748b; font-size: .92rem; line-height: 1.65; flex-grow: 1; }
    .service-directory-card__tags { display: flex; flex-wrap: wrap; gap: 6px; margin-bottom: 16px; }
    .service-directory-card__tag { background: #f1f8f4; color: var(--brand-green); border-radius: 6px; padding: 3px 8px; font-size: 0.78rem; font-weight: 600; }
    .service-directory-card__action { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-top: auto; color: var(--brand-green); font-size: .88rem; font-weight: 750; }

    /* Single Service Detail Page */
    .service-detail-hero {
        background: #ffffff;
        border: 1px solid var(--brand-border, #ddf3e8);
        border-radius: 16px;
        padding: 35px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.04);
        margin-bottom: 30px;
    }
    .service-detail-hero__badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.82rem;
        font-weight: 700;
        margin-right: 8px;
        margin-bottom: 12px;
    }
    .service-detail-sidebar-card {
        background: #ffffff;
        border: 1px solid var(--brand-border, #ddf3e8);
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.03);
        margin-bottom: 24px;
    }
    .service-detail-sidebar-title {
        font-size: 1.15rem;
        font-weight: 750;
        color: #17382a;
        margin-bottom: 16px;
        padding-bottom: 10px;
        border-bottom: 2px solid var(--brand-border, #ddf3e8);
    }
    .service-detail-sidebar-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .service-detail-sidebar-list li a {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 12px;
        border-radius: 8px;
        color: #475569;
        font-weight: 600;
        font-size: 0.92rem;
        text-decoration: none;
        transition: all 0.15s ease;
        margin-bottom: 4px;
    }
    .service-detail-sidebar-list li a:hover,
    .service-detail-sidebar-list li a.active {
        background: #f1f8f4;
        color: var(--brand-green, #01a755);
        padding-left: 16px;
    }

    @media (max-width: 768px) {
        .subservice-featured-img-wrapper {
            float: none !important;
            margin: 0 auto 20px auto !important;
            width: 100% !important;
            max-width: 100% !important;
        }
    }
</style>
@endsection

@section('content')
    <!-- Hero / Breadcrumb Header -->
    @include('website.common.page-heading')

    @if(isset($_level) && $_level === 'service')
        {{-- ========================================================= --}}
        {{-- LEVEL: SERVICE DETAIL (Specific Service Item)             --}}
        {{-- URL: /service/{category}/{subcategory}/{service}          --}}
        {{-- ========================================================= --}}
        <div class="container-xl mb-5">
            <div class="row">
                <!-- Main Content Column -->
                <div class="col-lg-8">
                    <div class="service-detail-hero">
                        <div class="mb-3">
                            @if(isset($_category))
                                <a href="{{ $_category->url }}" class="service-detail-hero__badge" style="background: #eaf5ed; color: var(--brand-green);">
                                    <i class="fas fa-folder-open"></i> {{ $_category->title }}
                                </a>
                            @endif
                            @if(isset($_subCategory) && $_subCategory)
                                <a href="{{ $_subCategory->url }}" class="service-detail-hero__badge" style="background: #e0f2fe; color: #0284c7;">
                                    <i class="fas fa-sitemap"></i> {{ $_subCategory->title }}
                                </a>
                            @endif
                        </div>

                        <h2 class="font-weight-bold mb-2" style="color: #17382a; font-size: 2rem;">
                            {{ $_service->title }}
                        </h2>
                        @if($_service->sub_title)
                            <p class="text-muted mb-4" style="font-size: 1.1rem; font-weight: 500;">
                                {{ $_service->sub_title }}
                            </p>
                        @endif

                        @if($_service->photo)
                            <div class="mb-4" style="border-radius: 12px; overflow: hidden; box-shadow: 0 8px 25px rgba(0,0,0,0.06); border: 1px solid var(--brand-border, #ddf3e8);">
                                <img src="{{ \App\Facades\ViewHelper::getImagePath($_service->photo_folder ?? 'service', $_service->photo) }}" 
                                     class="img-fluid w-100" 
                                     alt="{{ $_service->title }}"
                                     style="display: block;">
                            </div>
                        @endif

                        <div class="service-overview-desc text-justify text-muted" style="font-size: 1.05rem; line-height: 1.85;">
                            {!! $_service->description !!}
                        </div>

                        <!-- Inquiry CTA box -->
                        <div class="mt-5 p-4" style="background: #f8faf8; border: 1px dashed #c0e3cd; border-radius: 12px;">
                            <div class="row align-items-center">
                                <div class="col-md-8 mb-3 mb-md-0">
                                    <h5 class="font-weight-bold mb-1" style="color: var(--brand-green);">यस सेवा सम्बन्धी थप जानकारी लिन चाहनुहुन्छ?</h5>
                                    <p class="text-muted small mb-0">हाम्रो संस्थाको सम्पर्क कार्यालयमा सम्पर्क गर्नुहोस् वा अनलाइन सोधपुछ फाराम भर्नुहोस्।</p>
                                </div>
                                <div class="col-md-4 text-md-right">
                                    <a href="{{ route('contact') }}" class="btn btn-sm btn-success font-weight-bold px-3 py-2" style="border-radius: 8px;">
                                        सम्पर्क गर्नुहोस् <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Column -->
                <div class="col-lg-4">
                    @if(isset($_siblingServices) && $_siblingServices->count() > 0)
                        <div class="service-detail-sidebar-card">
                            <h4 class="service-detail-sidebar-title">
                                <i class="fas fa-list-ul mr-2" style="color: var(--brand-green);"></i>
                                @if(isset($_subCategory) && $_subCategory)
                                    यस खण्डका अन्य सेवाहरू
                                @else
                                    यस वर्गका अन्य सेवाहरू
                                @endif
                            </h4>
                            <ul class="service-detail-sidebar-list">
                                <li>
                                    <a href="{{ $_service->url }}" class="active">
                                        <span><i class="fas fa-angle-right mr-2 text-success"></i> {{ $_service->title }}</span>
                                        <span class="badge badge-success">हालको</span>
                                    </a>
                                </li>
                                @foreach($_siblingServices as $sibling)
                                    <li>
                                        <a href="{{ $sibling->url }}">
                                            <span><i class="fas fa-angle-right mr-2 text-muted"></i> {{ $sibling->title }}</span>
                                            <i class="fas fa-chevron-right text-muted" style="font-size: 0.75rem;"></i>
                                        </a>
                                    </li>
                                endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Quick Support Card -->
                    <div class="service-detail-sidebar-card">
                        <h4 class="service-detail-sidebar-title">
                            <i class="fas fa-headset mr-2" style="color: var(--brand-green);"></i>
                            सहयोग तथा सोधपुछ
                        </h4>
                        <p class="text-muted small mb-3">
                            {{ $_site_setting->title ?? 'मकवानपुर जिल्ला बचत तथा ऋण सहकारी संघ लि.' }}
                        </p>
                        @if(!empty($_site_setting->phone))
                            <div class="d-flex align-items-center mb-2 text-muted" style="font-size: 0.92rem;">
                                <i class="fas fa-phone-alt mr-2 text-success" style="width: 20px;"></i>
                                <a href="tel:{{ $_site_setting->phone }}" style="color: inherit; text-decoration: none;">{{ $_site_setting->phone }}</a>
                            </div>
                        @endif
                        @if(!empty($_site_setting->email))
                            <div class="d-flex align-items-center mb-3 text-muted" style="font-size: 0.92rem;">
                                <i class="fas fa-envelope mr-2 text-success" style="width: 20px;"></i>
                                <a href="mailto:{{ $_site_setting->email }}" style="color: inherit; text-decoration: none;">{{ $_site_setting->email }}</a>
                            </div>
                        @endif
                        <a href="{{ route('contact') }}" class="btn btn-outline-success btn-block font-weight-bold" style="border-radius: 8px;">
                            <i class="fas fa-paper-plane mr-1"></i> सन्देश पठाउनुहोस्
                        </a>
                    </div>
                </div>
            </div>
        </div>

    @elseif(isset($_level) && $_level === 'subcategory')
        {{-- ========================================================= --}}
        {{-- LEVEL: SUBCATEGORY (SubCategory and its Services list)   --}}
        {{-- URL: /service/{category}/{subcategory}                   --}}
        {{-- ========================================================= --}}
        <div class="container-xl mb-5">
            <!-- SubCategory Overview Card -->
            <div class="card border-0 shadow-sm overflow-hidden mb-5" style="border-radius: 16px; border: 1px solid var(--brand-border, #ddf3e8) !important; background: #ffffff;">
                <div class="row align-items-center no-gutters">
                    <div class="col-lg-{{ $_subCategory->photo ? '8' : '12' }}">
                        <div class="card-body p-4 p-lg-5 text-left">
                            <div class="mb-3">
                                @if(isset($_category))
                                    <a href="{{ $_category->url }}" class="service-detail-hero__badge" style="background: #eaf5ed; color: var(--brand-green);">
                                        <i class="fas fa-arrow-left mr-1"></i> {{ $_category->title }}
                                    </a>
                                @endif
                                <span class="service-detail-hero__badge" style="background: #e0f2fe; color: #0284c7;">
                                    <i class="fas fa-sitemap mr-1"></i> उप-सेवा
                                </span>
                            </div>

                            <h2 class="font-weight-bold mb-2" style="color: var(--brand-green) !important; font-size: 1.85rem;">
                                {{ $_subCategory->title }}
                            </h2>
                            @if($_subCategory->sub_title)
                                <p class="text-muted mb-4" style="font-size: 1.05rem; font-weight: 500;">
                                    {{ $_subCategory->sub_title }}
                                </p>
                            @endif
                            
                            @if($_subCategory->description)
                                <div class="service-overview-desc text-justify text-muted" style="font-size: 1.05rem; line-height: 1.8;">
                                    {!! $_subCategory->description !!}
                                </div>
                            @endif
                        </div>
                    </div>
                    @if($_subCategory->photo)
                        <div class="col-lg-4">
                            <div class="p-4 pr-lg-5">
                                <div class="service-overview-img-wrapper" style="border-radius: 12px; overflow: hidden; box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05); border: 1px solid var(--brand-border, #ddf3e8);">
                                    <img src="{{ \App\Facades\ViewHelper::getImagePath($_subCategory->photo_folder ?? 'serviceSubCategory', $_subCategory->photo) }}"
                                         class="img-fluid w-100" 
                                         alt="{{ $_subCategory->title }}"
                                         style="display: block;">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Services List under this SubCategory -->
            <div class="text-center mb-4">
                <h3 class="subservices-section-title">यस अन्तर्गतका सेवा तथा योजनाहरू</h3>
                <p class="text-muted">यस उप-सेवा अन्तर्गत सदस्य सहकारीहरूका लागि उपलब्ध विभिन्न सेवाहरूको सूची</p>
            </div>

            @if(isset($_services) && $_services->count() > 0)
                <div class="row">
                    @foreach($_services as $svc)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <a href="{{ $svc->url }}" class="nested-service-card">
                                <div class="nested-service-card__icon">
                                    <i class="fa fa-handshake-o"></i>
                                </div>
                                <h4 class="nested-service-card__title">{{ $svc->title }}</h4>
                                @if($svc->sub_title)
                                    <div class="nested-service-card__subtitle">{{ $svc->sub_title }}</div>
                                @endif
                                <div class="nested-service-card__desc">
                                    {{ \App\Facades\ViewHelper::getStrippedContent($svc->description, 140) }}
                                </div>
                                <div class="nested-service-card__btn">
                                    विवरण हेर्नुहोस् <i class="fas fa-arrow-right"></i>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="p-5 text-center bg-white rounded-lg shadow-sm" style="border: 1px dashed #c0e3cd;">
                    <i class="fas fa-info-circle fa-2x mb-3 text-success"></i>
                    <h5 class="font-weight-bold text-muted">यस उप-सेवा अन्तर्गतका विवरणहरू चाँडै अपडेट गरिनेछ।</h5>
                    <p class="text-muted small mb-0">थप जानकारीका लागि संस्थाको कार्यालयमा सम्पर्क गर्न सक्नुहुन्छ।</p>
                </div>
            @endif

            <!-- Quick Sibling Subcategories Navigation -->
            @if(isset($_allSubCategories) && $_allSubCategories->count() > 1)
                <div class="mt-5 p-4 bg-white rounded-lg shadow-sm" style="border: 1px solid var(--brand-border, #ddf3e8);">
                    <h5 class="font-weight-bold mb-3" style="color: #17382a;">
                        <i class="fas fa-th-large mr-2 text-success"></i> {{ $_category->title }} अन्तर्गतका अन्य उप-सेवाहरू
                    </h5>
                    <div class="d-flex flex-wrap" style="gap: 10px;">
                        @foreach($_allSubCategories as $siblingSub)
                            <a href="{{ $siblingSub->url }}" class="btn btn-sm {{ $siblingSub->id === $_subCategory->id ? 'btn-success' : 'btn-outline-success' }}" style="border-radius: 20px; padding: 6px 14px;">
                                {{ $siblingSub->title }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

    @elseif(isset($_level) && $_level === 'category')
        {{-- ========================================================= --}}
        {{-- LEVEL: CATEGORY (Category overview, subcategories & svcs) --}}
        {{-- URL: /service/{category}                                  --}}
        {{-- ========================================================= --}}
        <div class="container-xl mb-5">
            <!-- Main Category Overview Card -->
            <div class="card border-0 shadow-sm overflow-hidden mb-5" style="border-radius: 16px; border: 1px solid var(--brand-border, #ddf3e8) !important; background: #ffffff;">
                <div class="row align-items-center no-gutters">
                    <div class="col-lg-{{ $_category->photo ? '8' : '12' }}">
                        <div class="card-body p-4 p-lg-5 text-left">
                            <h2 class="font-weight-bold mb-3" style="color: var(--brand-green) !important; font-size: 1.85rem; position: relative; padding-bottom: 10px; border-bottom: 2px solid var(--brand-border, #ddf3e8); display: inline-block;">
                                {{ $_category->title }}
                            </h2>
                            @if($_category->sub_title)
                                <p class="text-muted mb-4" style="font-size: 1.05rem; font-weight: 500;">
                                    {{ $_category->sub_title }}
                                </p>
                            @endif
                            
                            @if($_category->description)
                                <div class="service-overview-desc text-justify text-muted" style="font-size: 1.05rem; line-height: 1.8;">
                                    {!! $_category->description !!}
                                </div>
                            @endif
                        </div>
                    </div>
                    @if($_category->photo)
                        <div class="col-lg-4">
                            <div class="p-4 pr-lg-5">
                                <div class="service-overview-img-wrapper" style="border-radius: 12px; overflow: hidden; box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05); border: 1px solid var(--brand-border, #ddf3e8);">
                                    <img src="{{ \App\Facades\ViewHelper::getImagePath($_category->photo_folder ?? 'serviceCategory', $_category->photo) }}"
                                         class="img-fluid w-100" 
                                         alt="{{ $_category->title }}"
                                         style="display: block;">
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tabbed Subcategories Layout (If Subcategories Exist) -->
            @if(isset($_subCategories) && $_subCategories->count() > 0)
                <div class="my-5 d-none d-md-block">
                    <div class="text-center mb-5">
                        <h3 class="subservices-section-title">उप-सेवा तथा योजनाहरू</h3>
                        <p class="text-muted">यस वर्ग अन्तर्गतका उप-सेवाहरू छनोट गरी सेवाहरूको विवरण हेर्नुहोस्</p>
                    </div>
                    
                    <div class="row">
                        <!-- Left Navigation Menu (Subcategories) -->
                        <div class="col-md-4 col-lg-3">
                            <div class="nav flex-column nav-pills subservices-nav" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                @foreach($_subCategories as $key => $subCat)
                                    <button class="nav-link text-left py-3 px-4 mb-2 d-flex align-items-center justify-content-between {{ $key == 0 ? 'active' : '' }}" 
                                            id="tab-{{ $subCat->slug }}" 
                                            data-toggle="pill" 
                                            data-target="#pane-{{ $subCat->slug }}" 
                                            type="button" 
                                            role="tab" 
                                            aria-controls="pane-{{ $subCat->slug }}" 
                                            aria-selected="{{ $key == 0 ? 'true' : 'false' }}">
                                        <div>
                                            <span class="subservice-tab-title d-block">{{ $subCat->title }}</span>
                                            @if($subCat->services && $subCat->services->count() > 0)
                                                <small class="badge badge-light text-muted font-weight-normal mt-1">{{ $subCat->services->count() }} सेवाहरू</small>
                                            @endif
                                        </div>
                                        <i class="fas fa-chevron-right ml-2 arrow-icon"></i>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Right Tab Content (Subcategory Details & Services Inside It) -->
                        <div class="col-md-8 col-lg-9">
                            <div class="tab-content" id="v-pills-tabContent">
                                @foreach($_subCategories as $key => $subCat)
                                    <div class="tab-pane fade show {{ $key == 0 ? 'active' : '' }}" 
                                         id="pane-{{ $subCat->slug }}" 
                                         role="tabpanel" 
                                         aria-labelledby="tab-{{ $subCat->slug }}">
                                        <div class="subservice-content-card">
                                            <!-- Subcategory Header -->
                                            <div class="d-flex justify-content-between align-items-start mb-3 flex-wrap">
                                                <div>
                                                    <h3 class="subservice-detail-title mb-1">{{ $subCat->title }}</h3>
                                                    @if($subCat->sub_title)
                                                        <div class="text-muted small mb-2">{{ $subCat->sub_title }}</div>
                                                    @endif
                                                </div>
                                                <a href="{{ $subCat->url }}" class="btn btn-sm btn-outline-success font-weight-bold" style="border-radius: 8px;">
                                                    उप-सेवा पृष्ठ <i class="fas fa-external-link-alt ml-1"></i>
                                                </a>
                                            </div>

                                            @if($subCat->description || $subCat->photo)
                                                <div class="subservice-desc-wrapper clearfix">
                                                    @if($subCat->photo)
                                                        <div class="subservice-featured-img-wrapper">
                                                            <img src="{{ \App\Facades\ViewHelper::getImagePath($subCat->photo_folder ?? 'serviceSubCategory', $subCat->photo) }}"
                                                                 class="img-fluid" 
                                                                 alt="{{ $subCat->title }}">
                                                        </div>
                                                    @endif
                                                    
                                                    @if($subCat->description)
                                                        <div class="subservice-desc-text">
                                                            {!! $subCat->description !!}
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                            <!-- Services inside this subcategory -->
                                            @if($subCat->services && $subCat->services->count() > 0)
                                                <div class="mt-4 pt-3 border-top">
                                                    <h5 class="font-weight-bold mb-3" style="color: #17382a;">
                                                        <i class="fa fa-handshake-o mr-2 text-success"></i> उपलब्ध सेवाहरू:
                                                    </h5>
                                                    <div class="row">
                                                        @foreach($subCat->services as $svc)
                                                            <div class="col-md-6 mb-3">
                                                                <a href="{{ $svc->url }}" class="nested-service-card p-3">
                                                                    <h6 class="nested-service-card__title mb-1" style="font-size: 1.02rem;">{{ $svc->title }}</h6>
                                                                    @if($svc->sub_title)
                                                                        <small class="text-muted d-block mb-2">{{ $svc->sub_title }}</small>
                                                                    @endif
                                                                    <div class="nested-service-card__desc small mb-2">
                                                                        {{ \App\Facades\ViewHelper::getStrippedContent($svc->description, 90) }}
                                                                    </div>
                                                                    <span class="nested-service-card__btn small font-weight-bold">
                                                                        विवरण हेर्नुहोस् <i class="fas fa-arrow-right"></i>
                                                                    </span>
                                                                </a>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile Accordion Layout -->
                <div class="my-4 d-block d-md-none mobile-subservices">
                    <div class="text-center mb-4">
                        <h3 class="subservices-section-title" style="font-size: 1.5rem !important;">उप-सेवा तथा योजनाहरू</h3>
                    </div>
                    
                    <div class="accordion" id="subservicesAccordion">
                        @foreach($_subCategories as $key => $subCat)
                            <div class="card accordion-card mb-3">
                                <div class="card-header accordion-header" id="heading-{{ $subCat->slug }}">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-block text-left d-flex align-items-center justify-content-between p-3 {{ $key == 0 ? '' : 'collapsed' }}" 
                                                type="button" 
                                                data-toggle="collapse" 
                                                data-target="#collapse-{{ $subCat->slug }}" 
                                                aria-expanded="{{ $key == 0 ? 'true' : 'false' }}" 
                                                aria-controls="collapse-{{ $subCat->slug }}">
                                            <div>
                                                <span class="accordion-title font-weight-bold">{{ $subCat->title }}</span>
                                                @if($subCat->services && $subCat->services->count() > 0)
                                                    <span class="badge badge-light ml-2">{{ $subCat->services->count() }} सेवा</span>
                                                @endif
                                            </div>
                                            <i class="fas fa-chevron-down accordion-arrow"></i>
                                        </button>
                                    </h2>
                                </div>

                                <div id="collapse-{{ $subCat->slug }}" 
                                     class="collapse {{ $key == 0 ? 'show' : '' }}" 
                                     aria-labelledby="heading-{{ $subCat->slug }}" 
                                     data-parent="#subservicesAccordion">
                                    <div class="card-body accordion-body p-3">
                                        @if($subCat->photo)
                                            <div class="text-center mb-3">
                                                <img src="{{ \App\Facades\ViewHelper::getImagePath($subCat->photo_folder ?? 'serviceSubCategory', $subCat->photo) }}"
                                                     class="img-fluid rounded" 
                                                     style="max-height: 200px; object-fit: cover;"
                                                     alt="{{ $subCat->title }}">
                                            </div>
                                        @endif
                                        @if($subCat->description)
                                            <div class="accordion-desc mb-3">
                                                {!! $subCat->description !!}
                                            </div>
                                        @endif

                                        <div class="mb-3 text-right">
                                            <a href="{{ $subCat->url }}" class="btn btn-xs btn-outline-success font-weight-bold">
                                                उप-सेवा पृष्ठ हेर्नुहोस् &rarr;
                                            </a>
                                        </div>

                                        @if($subCat->services && $subCat->services->count() > 0)
                                            <div class="border-top pt-3">
                                                <strong class="text-dark d-block mb-2">यस अन्तर्गतका सेवाहरू:</strong>
                                                @foreach($subCat->services as $svc)
                                                    <a href="{{ $svc->url }}" class="d-block p-2 mb-2 rounded border bg-light text-decoration-none">
                                                        <div class="font-weight-bold text-dark">{{ $svc->title }}</div>
                                                        @if($svc->sub_title)
                                                            <small class="text-muted">{{ $svc->sub_title }}</small>
                                                        @endif
                                                    </a>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Direct Services Section (Services without subcategory) -->
            @if(isset($_directServices) && $_directServices->count() > 0)
                <div class="my-5">
                    <div class="text-center mb-4">
                        <h3 class="subservices-section-title">प्रत्यक्ष सेवाहरू</h3>
                        <p class="text-muted">यस वर्ग अन्तर्गत उपलब्ध प्रत्यक्ष सेवाहरूको सूची</p>
                    </div>

                    <div class="row">
                        @foreach($_directServices as $dsvc)
                            <div class="col-md-6 col-lg-4 mb-4">
                                <a href="{{ $dsvc->url }}" class="nested-service-card">
                                    <div class="nested-service-card__icon">
                                        <i class="fa fa-handshake-o"></i>
                                    </div>
                                    <h4 class="nested-service-card__title">{{ $dsvc->title }}</h4>
                                    @if($dsvc->sub_title)
                                        <div class="nested-service-card__subtitle">{{ $dsvc->sub_title }}</div>
                                    @endif
                                    <div class="nested-service-card__desc">
                                        {{ \App\Facades\ViewHelper::getStrippedContent($dsvc->description, 130) }}
                                    </div>
                                    <div class="nested-service-card__btn">
                                        विवरण हेर्नुहोस् <i class="fas fa-arrow-right"></i>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

    @else
        {{-- ========================================================= --}}
        {{-- LEVEL: DIRECTORY (All Services Landing Page)              --}}
        {{-- URL: /service                                             --}}
        {{-- ========================================================= --}}
        <section class="container-xl service-directory" aria-labelledby="serviceDirectoryTitle">
            <div class="service-directory-intro">
                <p class="service-directory-kicker">{{ $_site_setting->title ?? 'MAFSCUN' }}</p>
                <h2 class="service-directory-title" id="serviceDirectoryTitle">सेवा तथा कार्यक्रमहरू</h2>
                <p class="service-directory-summary">सदस्य सहकारी संस्थाहरूको संस्थागत विकास, वित्तीय सबलीकरण तथा व्यावसायिक प्रवर्द्धनका लागि संघद्वारा प्रदान गरिने विभिन्न सेवाहरू।</p>
            </div>

            @if(isset($_categories) && $_categories->count() > 0)
                <div class="row">
                    @foreach($_categories as $catItem)
                        <div class="col-md-6 col-xl-4 mb-4">
                            <a class="service-directory-card" href="{{ $catItem->url }}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <span class="service-directory-card__icon" aria-hidden="true">
                                        <i class="fa fa-folder-open-o"></i>
                                    </span>
                                    @php
                                        $subCount = $catItem->subCategories ? $catItem->subCategories->count() : 0;
                                        $svcCount = $catItem->directServices ? $catItem->directServices->count() : 0;
                                    @endphp
                                    @if($subCount > 0)
                                        <span class="badge badge-success px-2 py-1" style="font-size: 0.78rem;">{{ $subCount }} उप-सेवा</span>
                                    @elseif($svcCount > 0)
                                        <span class="badge badge-info px-2 py-1" style="font-size: 0.78rem;">{{ $svcCount }} सेवा</span>
                                    @endif
                                </div>

                                <h3 class="service-directory-card__title">{{ $catItem->title }}</h3>
                                @if($catItem->sub_title)
                                    <p class="text-muted small mb-2">{{ $catItem->sub_title }}</p>
                                @endif
                                <p class="service-directory-card__description">
                                    {{ \App\Facades\ViewHelper::getStrippedContent($catItem->description, 140) }}
                                </p>

                                @if($catItem->subCategories && $catItem->subCategories->count() > 0)
                                    <div class="service-directory-card__tags">
                                        @foreach($catItem->subCategories->take(3) as $sTag)
                                            <span class="service-directory-card__tag">{{ $sTag->title }}</span>
                                        @endforeach
                                        @if($catItem->subCategories->count() > 3)
                                            <span class="service-directory-card__tag">+{{ $catItem->subCategories->count() - 3 }} अन्य</span>
                                        @endif
                                    </div>
                                @endif

                                <span class="service-directory-card__action">
                                    सेवाहरू अवलोकन गर्नुहोस् <span aria-hidden="true">&rarr;</span>
                                </span>
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="service-directory-pagination d-flex justify-content-center">
                    {{ $_categories->links() }}
                </div>
            @else
                <div class="service-directory-empty">सेवाहरूको विवरण चाँडै अपडेट गरिनेछ।</div>
            @endif
        </section>
    @endif

    @include('website.home.affiliation')
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#v-pills-tab button').on('click', function (e) {
                e.preventDefault();
                $(this).tab('show');
            });
        });
    </script>
@endsection
