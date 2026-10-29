@extends('admin.layout.frame')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Dashboard</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item active">
                                <a href="{{route('home')}}">Home</a>
                            </li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Welcome Banner -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="p-4 rounded-4 shadow-sm bg-white border d-flex flex-wrap align-items-center justify-content-between" style="border-radius: 14px;">
                            <div class="d-flex align-items-center mb-3 mb-md-0">
                                <div class="bg-primary text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 52px; height: 52px; font-size: 1.4rem; background: linear-gradient(135deg, #0f766e, #0d9488) !important;">
                                    <i class="fas fa-tachometer-alt"></i>
                                </div>
                                <div>
                                    <h4 class="font-weight-bold mb-1 text-dark">Welcome back, {{ auth()->user()->name ?? 'Administrator' }}!</h4>
                                    <p class="text-muted mb-0 small">Manage your cooperative portal, content, district coverage, notices, and events from one central place.</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <a href="{{ route('index') }}" target="_blank" class="btn btn-outline-success btn-sm rounded-pill px-3 mr-2 font-weight-bold">
                                    <i class="fas fa-external-link-alt mr-1"></i> Visit Website
                                </a>
                                <a href="{{ route('admin.district.index') }}" class="btn btn-primary btn-sm rounded-pill px-3 font-weight-bold" style="background-color: #0f766e; border-color: #0f766e;">
                                    <i class="fas fa-map-marked-alt mr-1"></i> District Coverage
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box shadow-sm">
                            <span class="info-box-icon bg-light elevation-1">
                                <i class="fas fa-eye text-info nav-icon"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Views</span>
                                <span class="info-box-number">
                                    {{ number_format($site_profile->view_count ?? 0) }}
                                    <small>Views</small>
                                </span>
                            </div>
                        </div>
                    </div>
                    @can(['aboutUs-index'])
                        <div class="col-12 col-sm-6 col-md-3">
                        <a href="{{ route('admin.aboutUs.index') }}" class="">
                            <div class="info-box">
                                <span class="info-box-icon bg-light elevation-1">
                                    <i class="fas fa-address-card blue nav-icon"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">
                                        About Us
                                    </span>
                                    <span class="info-box-number">
                                        Go To Page
                                        <small>
                                            <i class="fas fa-chevron-right"></i>
                                        </small>
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endcan

                    @can(['slider-index'])
                        <div class="col-12 col-sm-6 col-md-3">
                        <a href="{{ route('admin.slider.index') }}" class="">
                            <div class="info-box">
                                <span class="info-box-icon bg-light elevation-1">
                                    <i class="fas fa-sliders-h green nav-icon"></i>
                                </span>
                                <div class="info-box-content">
                                    <span class="info-box-text">
                                        Slider
                                    </span>
                                    <span class="info-box-number">
                                        Go To Page
                                        <small>
                                            <i class="fas fa-chevron-right"></i>
                                        </small>
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endcan
                    @can(['notice-index'])
                        <div class="col-12 col-sm-6 col-md-3">
                            <a href="{{ route('admin.notice.index') }}" class="">
                                <div class="info-box">
                                    <span class="info-box-icon bg-light elevation-1">
                                        <i class="fas fa-broadcast-tower blue nav-icon"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">
                                           Notice
                                        </span>
                                        <span class="info-box-number">
                                            Go To Page
                                            <small>
                                                <i class="fas fa-chevron-right"></i>
                                            </small>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endcan

                        @can(['gallery-index'])
                            <div class="col-12 col-sm-6 col-md-3">
                                <a href="{{ route('admin.gallery.index') }}" class="">
                                    <div class="info-box">
                                    <span class="info-box-icon bg-light elevation-1">
                                        <i class="fas fa-images indigo nav-icon"></i>
                                    </span>
                                        <div class="info-box-content">
                                        <span class="info-box-text">
                                           Gallery
                                        </span>
                                            <span class="info-box-number">
                                            Go To Page
                                            <small>
                                                <i class="fas fa-chevron-right"></i>
                                            </small>
                                        </span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endcan

                        @can(['resource-index'])
                            <div class="col-12 col-sm-6 col-md-3">
                                <a href="{{ route('admin.resource.index') }}" class="">
                                    <div class="info-box">
                                    <span class="info-box-icon bg-light elevation-1">
                                        <i class="fas fa-file-upload teal nav-icon"></i>
                                    </span>
                                        <div class="info-box-content">
                                        <span class="info-box-text">
                                           Resource
                                        </span>
                                            <span class="info-box-number">
                                            Go To Page
                                            <small>
                                                <i class="fas fa-chevron-right"></i>
                                            </small>
                                        </span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endcan

                        @can(['statistics-index'])
                            <div class="col-12 col-sm-6 col-md-3">
                                <a href="{{ route('admin.statistic.index') }}" class="">
                                    <div class="info-box">
                                    <span class="info-box-icon bg-light elevation-1">
                                        <i class="fas fa-database blue nav-icon"></i>
                                    </span>
                                        <div class="info-box-content">
                                        <span class="info-box-text">
                                           Statistics
                                        </span>
                                            <span class="info-box-number">
                                            Go To Page
                                            <small>
                                                <i class="fas fa-chevron-right"></i>
                                            </small>
                                        </span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endcan

                        @can(['successStory-index'])
                            <div class="col-12 col-sm-6 col-md-3">
                                <a href="{{ route('admin.successStory.index') }}" class="">
                                    <div class="info-box">
                                    <span class="info-box-icon bg-light elevation-1">
                                        <i class="fas fa-user-check green nav-icon"></i>
                                    </span>
                                        <div class="info-box-content">
                                        <span class="info-box-text">
                                           Success Story
                                        </span>
                                            <span class="info-box-number">
                                            Go To Page
                                            <small>
                                                <i class="fas fa-chevron-right"></i>
                                            </small>
                                        </span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endcan

                        @can(['faq-index'])
                            <div class="col-12 col-sm-6 col-md-3">
                                <a href="{{ route('admin.faq.index') }}" class="">
                                    <div class="info-box">
                                    <span class="info-box-icon bg-light elevation-1">
                                        <i class="fas fa-question blue nav-icon"></i>
                                    </span>
                                        <div class="info-box-content">
                                        <span class="info-box-text">
                                           FAQ
                                        </span>
                                            <span class="info-box-number">
                                            Go To Page
                                            <small>
                                                <i class="fas fa-chevron-right"></i>
                                            </small>
                                        </span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endcan
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
@endsection
@section('title','Dashboard')
