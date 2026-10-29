@extends('website.common.layout')
@section('title', $_aboutUs['title'] ?? "About Us")

@section('meta')
    @include('website.common.meta', [
    '_title' => $_aboutUs->title ?? "About Us",
    '_keywords' => $_site_setting->keywords,
    '_detail' => strip_tags($_aboutUs->description ?? '') ?? "Bagmati Proscun",
    '_folder' => 'aboutUs',
    '_photo' => $_aboutUs->photo ?? '',
])
@endsection

@section('content')
    <!-- Unified Page Heading & Breadcrumb -->
    @include('website.common.page-heading')

    <div class="container-xl my-4 pb-5">
        @if(isset($_aboutUs))
            <!-- Featured About Article -->
            <div class="about-featured-card mb-5 p-4 p-md-5 bg-white rounded-4 border">
                <div class="row align-items-center g-4">
                    <div class="col-lg-7">
                        <span class="statistics-eyebrow mb-2 d-inline-block">WHO WE ARE</span>
                        <h2 class="fw-bold mb-3" style="color: var(--public-ink); font-size: clamp(1.5rem, 2.4vw, 2.1rem); letter-spacing: -.03em;">
                            {{ $_aboutUs['title'] ?? "About Us" }}
                        </h2>
                        <div class="home-section-accent mb-4"></div>
                        <div class="about-featured-text text-muted" style="line-height: 1.8; font-size: 1rem;">
                            {!! $_aboutUs['description'] ?? "" !!}
                        </div>
                    </div>
                    @if(!empty($_aboutUs->photo))
                        <div class="col-lg-5">
                            <div class="about-featured-image-wrap rounded-4 overflow-hidden shadow-sm border">
                                <img src="{{ \App\Facades\ViewHelper::getImagePath('aboutUs', $_aboutUs->photo) }}"
                                     alt="{{ $_aboutUs['title'] ?? 'About Us Photo' }}"
                                     class="w-100 d-block"
                                     style="max-height: 420px; object-fit: cover;">
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Other About Us / Articles -->
        @if(isset($_aboutUsList) && $_aboutUsList->count() > 0)
            <div class="other-about-section mt-5 pt-3">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <span class="statistics-eyebrow">EXPLORE MORE</span>
                        <h3 class="fw-bold mb-0" style="color: var(--public-ink); font-size: 1.45rem;">Related Information</h3>
                    </div>
                </div>

                <div class="row g-4">
                    @foreach($_aboutUsList as $aboutUsL)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="news-card h-100 d-flex flex-column bg-white rounded-4 border overflow-hidden">
                                @if(!empty($aboutUsL->photo))
                                    <div class="news-img-wrapper" style="aspect-ratio: 16/10; overflow: hidden;">
                                        <img src="{{ \App\Facades\ViewHelper::getImagePath('aboutUs', $aboutUsL->photo) }}"
                                             alt="{{ $aboutUsL->title ?? 'Photo' }}"
                                             class="w-100 h-100" style="object-fit: cover;">
                                    </div>
                                @endif
                                <div class="p-4 d-flex flex-column flex-grow-1">
                                    <div class="text-muted small mb-2 d-flex align-items-center gap-1">
                                        <i class="far fa-calendar-alt text-success"></i>
                                        <span>{{ $aboutUsL->created_at ? $aboutUsL->created_at->format('M d, Y') : '' }}</span>
                                    </div>
                                    <h5 class="fw-bold mb-2" style="color: var(--public-ink); font-size: 1.12rem;">
                                        {{ $aboutUsL->title ?? "Title" }}
                                    </h5>
                                    <p class="text-muted small flex-grow-1 mb-3" style="line-height: 1.6;">
                                        {{ \App\Facades\ViewHelper::getStrippedContent($aboutUsL->description, 160) }}
                                    </p>
                                    <a href="{{ route('about.detail', $aboutUsL->slug) }}" class="btn btn-sm btn-outline-success rounded-pill align-self-start px-3 py-1 font-weight-bold" style="font-size: 0.8rem;">
                                        Read More <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($_aboutUsList->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {!! $_aboutUsList->links() !!}
                    </div>
                @endif
            </div>
        @endif
    </div>
@endsection
