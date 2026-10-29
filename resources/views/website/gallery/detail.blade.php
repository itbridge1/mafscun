@extends('website.common.layout')
@section('title', $_gallery->title ?? "Gallery")
@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.css" />
@endsection

@section('meta')
    @include('website.common.meta', [
    '_title' => $_gallery->title,
    '_detail' => strip_tags($_gallery->description ?? ''),
    '_folder' => 'gallery',
    '_photo' => $_gallery->cover_photo,
])
@endsection

@section('content')
    <!-- Unified Page Heading & Breadcrumb -->
    @include('website.common.page-heading')

    <div class="container-xl my-4 pb-5">
        <div class="bg-white rounded-4 border p-4 p-md-5 shadow-sm">
            <div class="mb-4 text-center">
                <span class="statistics-eyebrow">PHOTO ALBUM</span>
                <h2 class="fw-bold mb-2" style="color: var(--public-ink); font-size: 1.65rem;">{{ $_gallery->title }}</h2>
                @if($_gallery->created_at)
                    <p class="text-muted small mb-0"><i class="far fa-calendar-alt text-success mr-1"></i> {{ $_gallery->created_at->format('M d, Y') }}</p>
                @endif
                @if(!empty($_gallery->description))
                    <div class="mt-3 text-muted mx-auto" style="max-width: 780px; line-height: 1.7;">
                        {!! $_gallery->description !!}
                    </div>
                @endif
            </div>

            <div class="row g-3 justify-content-center">
                @if($_gallery->cover_photo)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                        <a data-fancybox="gallery" href="{{ ViewHelper::getImagePath('gallery', $_gallery->cover_photo) }}" class="d-block overflow-hidden rounded-3 border shadow-sm" style="aspect-ratio: 4/3; background: #f8fafc;">
                            <img class="w-100 h-100" style="object-fit: cover; transition: transform 0.3s ease;" src="{{ ViewHelper::getImagePath('gallery', '200_150_' . $_gallery->cover_photo) }}" alt="{{ $_gallery->title }}" onerror="this.onerror=null;this.src='{{ ViewHelper::getImagePath('gallery', $_gallery->cover_photo) }}';" />
                        </a>
                    </div>
                @endif
                @if(isset($_gallery->images) && $_gallery->images->count() > 0)
                    @foreach($_gallery->images as $_image)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                            <a data-fancybox="gallery" href="{{ ViewHelper::getImagePath('gallery', $_image->photo) }}" class="d-block overflow-hidden rounded-3 border shadow-sm" style="aspect-ratio: 4/3; background: #f8fafc;">
                                <img class="w-100 h-100" style="object-fit: cover; transition: transform 0.3s ease;" src="{{ ViewHelper::getImagePath('gallery', '200_150_' . $_image->photo) }}" alt="{{ $_gallery->title }}" onerror="this.onerror=null;this.src='{{ ViewHelper::getImagePath('gallery', $_image->photo) }}';" />
                            </a>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        @if(isset($_other_news) && $_other_news->count() > 0)
            <div class="mt-5 pt-3">
                <span class="statistics-eyebrow">UPDATES</span>
                <h4 class="fw-bold mb-4" style="color: var(--public-ink);">Recent News</h4>
                <div class="row g-3">
                    @foreach($_other_news->take(4) as $other_news)
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="news-card h-100 d-flex flex-column bg-white rounded-3 border overflow-hidden">
                                @if($other_news->photo)
                                    <div style="aspect-ratio: 16/10; overflow: hidden;">
                                        <img src="{{ \App\Facades\ViewHelper::getImagePath('news', $other_news->photo) }}" alt="{{ $other_news->title }}" class="w-100 h-100" style="object-fit: cover;">
                                    </div>
                                @endif
                                <div class="p-3 d-flex flex-column flex-grow-1">
                                    <h6 class="fw-bold mb-1" style="font-size: 0.95rem;">
                                        <a href="{{ route('news.detail', $other_news->slug) }}" class="text-decoration-none" style="color: var(--public-ink);">
                                            {{ $other_news->title }}
                                        </a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
@endsection
