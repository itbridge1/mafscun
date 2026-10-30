@extends('website.common.layout')

@section('title', isset($_service) ? $_service->title : "Services")
@section('meta')
    @include('website.common.meta', [
    '_title' => isset($_service) ? $_service->title : 'Services',
    '_detail' => isset($_service) ? strip_tags($_service->description) : ('Explore the services offered by ' . ($_site_setting->title ?? 'MAFSCUN') . '.'),
    '_folder' => 'serviceCategory',
    '_photo' => isset($_service) ? $_service->photo : null,
])
@endsection

@section('css')
<style>


    /* Subservices Main Heading */
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
        border-bottom: 1px solid #e2e8f0 !important; /* Override style.css */
        border-right: 1px solid #e2e8f0 !important;  /* Override style.css */
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

    /* Desktop Content Card Styling */
    .subservice-content-card {
        background: #ffffff !important;
        border: 1px solid var(--brand-border, #ddf3e8) !important;
        border-radius: 16px !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04) !important;
        padding: 35px !important;
        min-height: 380px;
    }

    .subservice-body {
        text-align: left;
        width: 100%;
    }

    .subservice-detail-title {
        font-size: 1.6rem !important;
        font-weight: 800 !important;
        color: var(--brand-green, #01a755) !important;
        margin-bottom: 25px !important;
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
        border-left: 5px solid var(--brand-green, #01a755);
        padding-left: 20px;
    }

    /* Floating container for the featured image */
    .subservice-featured-img-wrapper {
        float: right;
        margin-left: 4%;
        margin-bottom: 20px;
        width: 32%;
        max-width: 32%;
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

    .subservice-desc-text p {
        margin-bottom: 15px;
    }

    /* CRITICAL: Styles to handle embedded flyer/scanned images inside description */
    .subservice-desc-text img {
        clear: both !important;
        max-width: 100% !important;
        height: auto !important;
        border-radius: 8px;
        margin: 20px 0 !important;
        display: block !important;
        box-shadow: 0 5px 15px rgba(0,0,0,0.03);
    }

    /* Parallel Image Layout: When BOTH a featured photo and description scan exist */
    .subservice-featured-img-wrapper + .subservice-desc-text img {
        width: 32% !important;
        max-width: 32% !important;
        float: left !important;
        clear: none !important;
        display: inline-block !important;
        margin: 0 4% 15px 0 !important;
    }

    @media (max-width: 768px) {
        .subservice-featured-img-wrapper {
            float: none !important;
            margin: 0 auto 20px auto !important;
            width: 100% !important;
            max-width: 100% !important;
        }

        .subservice-featured-img-wrapper + .subservice-desc-text img {
            float: none !important;
            width: 100% !important;
            max-width: 100% !important;
            margin: 15px 0 !important;
            display: block !important;
        }

        .detail-featured-image-wrapper {
            float: none !important;
            margin: 0 auto 15px auto !important;
            width: 100% !important;
            display: block !important;
        }
    }

    /* Mobile Accordion Styling */
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

    .accordion-desc p {
        margin-bottom: 12px;
    }

    /* Ensure embedded flyer images do not overflow on mobile collapse view */
    .accordion-desc img {
        max-width: 100% !important;
        height: auto !important;
        border-radius: 8px;
        margin: 15px 0 !important;
        display: block !important;
    }

    .service-overview-desc img {
        max-width: 100% !important;
        height: auto !important;
        border-radius: 12px;
        margin: 20px 0 !important;
        display: block !important;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
    }

    .service-directory { margin-bottom: 54px; }
    .service-directory-intro { display: block !important; width: auto; max-width: 720px; height: auto !important; margin: 0 auto 30px; padding: 0; background: transparent !important; box-shadow: none !important; text-align: center; }
    .service-directory-kicker { margin-bottom: 8px; color: var(--brand-green); font-size: .75rem; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
    .service-directory-title { margin-bottom: 10px; color: #17382a; font-size: clamp(1.65rem, 3vw, 2.25rem); font-weight: 800; letter-spacing: -.035em; }
    .service-directory-summary { margin: 0; color: #64748b; line-height: 1.7; }
    .service-directory-card { position: relative; display: flex; height: 100%; min-height: 230px; flex-direction: column; overflow: hidden; padding: 24px; border: 1px solid var(--brand-border, #ddf3e8); border-radius: 15px; background: #fff; color: inherit; text-decoration: none !important; box-shadow: 0 8px 26px rgba(20, 55, 38, .045); transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease; }
    .service-directory-card:hover { transform: translateY(-4px); border-color: #9ed0ad; color: inherit; box-shadow: 0 16px 35px rgba(20, 55, 38, .11); }
    .service-directory-card__icon { display: grid; width: 46px; height: 46px; margin-bottom: 18px; place-items: center; border-radius: 13px; background: #edf7ef; color: var(--brand-green); font-size: 1.1rem; }
    .service-directory-card__title { margin: 0 0 9px; color: #17382a; font-size: 1.1rem; font-weight: 750; line-height: 1.4; }
    .service-directory-card__description { margin: 0 0 20px; color: #64748b; font-size: .9rem; line-height: 1.65; }
    .service-directory-card__action { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-top: auto; color: var(--brand-green); font-size: .84rem; font-weight: 750; }
    .service-directory-empty { padding: 42px 20px; border: 1px dashed #cddfd2; border-radius: 14px; background: #fff; color: #64748b; text-align: center; }
    .service-directory-pagination { margin: 30px 0 0; }
</style>

@section('content')
    <!-- Service Page Hero Header -->
    @include('website.common.page-heading')

    @if(isset($_service))
    <!-- Main Service Overview -->
    <div class="container-xl mb-5">
        <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 16px; border: 1px solid var(--brand-border, #ddf3e8) !important; background: #ffffff;">
            <div class="row align-items-center no-gutters">
                <div class="col-lg-{{ $_service->photo ? '8' : '12' }}">
                    <div class="card-body p-4 p-lg-5 text-left">
                        <h2 class="font-weight-bold mb-4" style="color: var(--brand-green) !important; font-size: 1.8rem; position: relative; padding-bottom: 10px; border-bottom: 2px solid var(--brand-border, #ddf3e8); display: inline-block;">
                            {{ $_service->title }} Overview
                        </h2>
                        
                        <div class="service-overview-desc text-justify text-muted" style="font-size: 1.05rem; line-height: 1.8;">
                            {!! $_service->description !!}
                        </div>
                    </div>
                </div>
                @if($_service->photo)
                    <div class="col-lg-4">
                        <div class="p-4 pr-lg-5">
                            <div class="service-overview-img-wrapper" style="border-radius: 12px; overflow: hidden; box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05); border: 1px solid var(--brand-border, #ddf3e8);">
                                <img src="{{ \App\Facades\ViewHelper::getImagePath('serviceCategory', $_service->photo) }}"
                                     class="img-fluid w-100" 
                                     alt="{{ $_service->title }}"
                                     style="display: block; cursor: zoom-in;">
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Desktop Subservices Layout -->
    @if(isset($_service->children) && $_service->children->count() > 0)
        <div class="container-xl my-5 d-none d-md-block">
            <div class="text-center mb-5">
                <h3 class="subservices-section-title">Explore Our Sub-Services</h3>
            </div>
            
            <div class="row">
                <!-- Left Nav Menu -->
                <div class="col-md-4 col-lg-3">
                    <div class="nav flex-column nav-pills subservices-nav" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        @foreach($_service->children as $key => $_child)
                            <button class="nav-link text-left py-3 px-4 mb-2 d-flex align-items-center justify-content-between {{ $key == 0 ? 'active' : '' }}" 
                                    id="tab-{{ $_child->slug }}" 
                                    data-toggle="pill" 
                                    data-target="#pane-{{ $_child->slug }}"
                                    type="button" 
                                    role="tab" 
                                    aria-controls="pane-{{ $_child->slug }}" 
                                    aria-selected="{{ $key == 0 ? 'true' : 'false' }}">
                                <span class="subservice-tab-title">{{ $_child->title }}</span>
                                <i class="fas fa-chevron-right ml-2 arrow-icon"></i>
                            </button>
                        @endforeach
                    </div>
                </div>
                
                <!-- Right Tab Content -->
                <div class="col-md-8 col-lg-9">
                    <div class="tab-content" id="v-pills-tabContent">
                        @foreach($_service->children as $key => $_child)
                            <div class="tab-pane fade show {{ $key == 0 ? 'active' : '' }}" 
                                 id="pane-{{ $_child->slug }}" 
                                 role="tabpanel" 
                                 aria-labelledby="tab-{{ $_child->slug }}">
                                <div class="subservice-content-card">
                                    <div class="subservice-body">
                                        <h3 class="subservice-detail-title">{{ $_child->title }}</h3>
                                        
                                        <div class="subservice-desc-wrapper clearfix">
                                            @if($_child->photo)
                                                <div class="subservice-featured-img-wrapper">
                                                    <img src="{{ \App\Facades\ViewHelper::getImagePath('serviceCategory', $_child->photo) }}"
                                                         class="img-fluid" 
                                                         alt="{{ $_child->title }}">
                                                </div>
                                            @endif
                                            
                                            <div class="subservice-desc-text">
                                                {!! $_child->description !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Mobile Accordion Layout -->
    @if(isset($_service->children) && $_service->children->count() > 0)
        <div class="container-xl my-4 d-block d-md-none mobile-subservices">
            <div class="text-center mb-4">
                <h3 class="subservices-section-title" style="font-size: 1.5rem !important;">Explore Sub-Services</h3>
            </div>
            
            <div class="accordion" id="subservicesAccordion">
                @foreach($_service->children as $key => $_child)
                    <div class="card accordion-card mb-3">
                        <div class="card-header accordion-header" id="heading-{{ $_child->slug }}">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left d-flex align-items-center justify-content-between p-3 {{ $key == 0 ? '' : 'collapsed' }}" 
                                        type="button" 
                                        data-toggle="collapse" 
                                        data-target="#collapse-{{ $_child->slug }}" 
                                        aria-expanded="{{ $key == 0 ? 'true' : 'false' }}" 
                                        aria-controls="collapse-{{ $_child->slug }}">
                                    <span class="accordion-title">{{ $_child->title }}</span>
                                    <i class="fas fa-chevron-down accordion-arrow"></i>
                                </button>
                            </h2>
                        </div>

                        <div id="collapse-{{ $_child->slug }}" 
                             class="collapse {{ $key == 0 ? 'show' : '' }}" 
                             aria-labelledby="heading-{{ $_child->slug }}" 
                             data-parent="#subservicesAccordion">
                            <div class="card-body accordion-body p-3">
                                @if($_child->photo)
                                    <div class="text-center mb-3">
                                        <img src="{{ \App\Facades\ViewHelper::getImagePath('serviceCategory', $_child->photo) }}"
                                             class="img-fluid rounded" 
                                             style="max-height: 200px; object-fit: cover;"
                                             alt="{{ $_child->title }}">
                                    </div>
                                @endif
                                <div class="accordion-desc">
                                    {!! $_child->description !!}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    @else
    <section class="container-xl service-directory" aria-labelledby="serviceDirectoryTitle">
        <div class="service-directory-intro">
            <p class="service-directory-kicker">{{ $_site_setting->title ?? 'MAFSCUN' }}</p>
            <h2 class="service-directory-title" id="serviceDirectoryTitle">Explore our services</h2>
            <p class="service-directory-summary">Find the guidance, programs, and support available to our member cooperatives.</p>
        </div>

        @if(isset($_services) && $_services->count() > 0)
            <div class="row">
                @foreach($_services as $_serviceItem)
                    <div class="col-md-6 col-xl-4 mb-4">
                        <a class="service-directory-card" href="{{ route('service.detail', $_serviceItem->slug) }}">
                            <span class="service-directory-card__icon" aria-hidden="true"><i class="fa fa-handshake-o"></i></span>
                            <h3 class="service-directory-card__title">{{ $_serviceItem->title }}</h3>
                            <p class="service-directory-card__description">{{ \App\Facades\ViewHelper::getStrippedContent($_serviceItem->description, 150) }}</p>
                            <span class="service-directory-card__action">Explore service <span aria-hidden="true">→</span></span>
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="service-directory-pagination d-flex justify-content-center">{{ $_services->links() }}</div>
        @else
            <div class="service-directory-empty">Services are being updated. Please check back soon.</div>
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
