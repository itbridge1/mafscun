@extends('website.common.layout')
@section('title', $_news->title ?? "News Title")

@section('meta')
    @include('website.common.meta', [
    '_title' => $_news->title,
    '_detail' => strip_tags($_news->description),
    '_folder' => 'news',
    '_photo' => $_news->photo,
])
@endsection

@section('css')
<style>
    /* Premium News Detail Styling */


    .news-meta-list {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        list-style: none;
        padding: 0;
        margin: 20px 0 0 0;
        font-size: 0.9rem;
    }

    .news-meta-list li {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #475569 !important;
    }

    .news-meta-list li i {
        color: var(--brand-cyan, #00acf1);
    }

    .news-meta-list li .badge {
        font-size: 0.78rem;
        padding: 5px 12px;
        border-radius: 20px;
        background-color: var(--brand-cyan, #00acf1);
        color: #ffffff;
        border: none !important;
    }

    .news-container {
        padding: 50px 15px !important;
        background-color: #fafdfb;
    }

    .news-detail-card {
        background: #ffffff !important;
        border: 1px solid var(--brand-border, #ddf3e8) !important;
        border-radius: 16px !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04) !important;
        padding: 35px !important;
        margin-bottom: 40px;
    }

    @media (max-width: 768px) {
        .news-detail-card {
            padding: 20px !important;
        }
    }

    .news-detail-card .desc {
        font-size: 0.95rem !important;
        line-height: 1.75 !important;
        color: #475569 !important;
        text-align: justify;
    }

    .news-detail-card .desc p {
        margin-bottom: 14px;
    }

    @media (max-width: 768px) {
        .detail-featured-image-wrapper {
            float: none !important;
            margin: 0 auto 15px auto !important;
            width: 100% !important;
            display: block !important;
        }
    }

    /* Sidebar styles */
    .sidebar-card {
        background: #ffffff !important;
        border: 1px solid var(--brand-border, #ddf3e8) !important;
        border-radius: 16px !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04) !important;
        padding: 25px !important;
        margin-bottom: 30px;
    }

    .sidebar-card h5 {
        font-size: 1.2rem !important;
        font-weight: 800 !important;
        color: var(--brand-green, #01a755) !important;
        margin-bottom: 20px !important;
        position: relative;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--brand-border, #ddf3e8) !important;
    }

    .sidebar-card h5::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 40px;
        height: 2px;
        background-color: var(--brand-cyan, #00acf1);
    }

    .trending-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
        padding: 0 !important;
        margin: 0 !important;
        list-style: none !important;
    }

    .trending-item {
        display: flex;
        gap: 15px;
        align-items: flex-start;
        padding-bottom: 15px;
        border-bottom: 1px dashed var(--brand-border, #ddf3e8);
        transition: transform 0.2s ease;
    }

    .trending-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .trending-item:hover {
        transform: translateX(4px);
    }

    .trending-num {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-color: var(--brand-bg-light, #f4fcf7);
        border: 1px solid var(--brand-border, #ddf3e8);
        color: var(--brand-green, #01a755);
        font-weight: 700;
        font-size: 0.9rem;
        flex-shrink: 0;
    }

    .trending-item:hover .trending-num {
        background-color: var(--brand-green, #01a755);
        color: #ffffff;
        border-color: var(--brand-green, #01a755);
    }

    .trending-content {
        flex-grow: 1;
        text-align: left;
    }

    .trending-content h6 {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 5px;
        line-height: 1.4;
        transition: color 0.2s ease;
    }

    .trending-item:hover .trending-content h6 {
        color: var(--brand-green, #01a755);
    }

    .trending-content p {
        font-size: 0.78rem;
        color: #64748b;
        margin: 0;
    }

    .trending-content a {
        color: var(--brand-cyan, #00acf1);
        text-decoration: none;
        font-weight: 600;
        margin-left: 5px;
    }

    .trending-content a:hover {
        color: var(--brand-cyan-hover, #0093ce);
        text-decoration: underline;
    }

    /* Other News section */
    .extra-news-section {
        background-color: #fafdfb;
        border-top: 1px solid var(--brand-border, #ddf3e8);
        padding: 50px 15px !important;
    }

    .extra-news-title {
        font-size: 1.75rem !important;
        font-weight: 800 !important;
        color: var(--brand-green, #01a755) !important;
        text-align: center;
        margin-bottom: 40px !important;
        position: relative;
        padding-bottom: 12px;
    }

    .extra-news-title::after {
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

    .other-news-card {
        background: #ffffff;
        border: 1px solid var(--brand-border, #ddf3e8);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.02);
        transition: all 0.25s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .other-news-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(1, 167, 85, 0.08);
        border-color: var(--brand-green-light, #35b673);
    }

    .other-news-img-wrapper {
        position: relative;
        overflow: hidden;
        aspect-ratio: 16/10;
    }

    .other-news-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }

    .other-news-card:hover .other-news-img-wrapper img {
        transform: scale(1.06);
    }

    .other-news-card-body {
        padding: 15px;
        text-align: left;
    }

    .other-news-category {
        font-size: 0.72rem;
        font-weight: 700;
        color: var(--brand-cyan, #00acf1);
        text-transform: uppercase;
        margin-bottom: 6px;
        display: block;
    }

    .other-news-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e293b;
        line-height: 1.4;
        margin: 0;
        transition: color 0.2s ease;
        text-decoration: none !important;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .other-news-card:hover .other-news-title {
        color: var(--brand-green, #01a755);
    }
</style>
@endsection

@section('content')
    <!-- News Detail Page Hero Header -->
    @include('website.common.page-heading')

    <!-- Main Detail Section -->
    <div class="container-xl news-container">
        <div class="row justify-content-center">
            <!-- News Article Column -->
            <div class="col-lg-8 col-md-12">
                <div class="news-detail-card">
                    <!-- Share Buttons -->
                    <div class="mb-4">
                        <div class="sharethis-inline-share-buttons"></div>
                    </div>
                    
                    <h2 class="font-weight-bold mb-3" style="color: #1e293b; font-size: 1.8rem; line-height: 1.3;">
                        {{ $_news->title }}
                    </h2>
                    
                    <ul class="news-meta-list mb-4 pl-0" style="display: flex; flex-wrap: wrap; gap: 15px; list-style: none;">
                        @if($_news->parent)
                            <li><span class="badge">{{ $_news->parent->title }}</span></li>
                        @endif
                        <li style="color: #64748b; font-size: 0.9rem;"><i class="fa fa-user" style="color: var(--brand-green);"></i> <span class="ml-1">{{ $_news->author ?? "Bagmati PROSCUN" }}</span></li>
                        <li style="color: #64748b; font-size: 0.9rem;"><i class="fa fa-clock" style="color: var(--brand-green);"></i> <span class="ml-1">{{ $_news->created_at->format('M d, Y') }} ({{ $_news->created_at->diffForHumans() }})</span></li>
                        @if($_news->location)
                            <li style="color: #64748b; font-size: 0.9rem;"><i class="fa fa-map-marker-alt" style="color: var(--brand-green);"></i> <span class="ml-1">{{ $_news->location }}</span></li>
                        @endif
                    </ul>
                    
                    <blockquote style="text-align: justify; border-left: 5px solid var(--brand-green, #01a755); padding-left: 20px; margin: 0;">
                        @if($_news->photo)
                            <span class="detail-featured-image-wrapper">
                                <img src="{{ \App\Facades\ViewHelper::getImagePath('news', $_news->photo) }}" 
                                     alt="{{ $_news->title }}" />
                            </span>
                        @endif
                        <div class="desc">
                            {!! $_news->description !!}
                        </div>
                    </blockquote>
                </div>
            </div>
            
            <!-- Sidebar Column -->
            <div class="col-lg-4 col-md-12">
                <div class="sidebar-card">
                    <h5>Trending News</h5>
                    <div class="trending-list">
                        @if(isset($_trending_news) && $_trending_news->count() > 0)
                            @foreach($_trending_news as $key => $trend)
                                <div class="trending-item">
                                    <div class="trending-num">{{ ++$key }}</div>
                                    <div class="trending-content">
                                        <a href="{{ route('news.detail', $trend->slug) }}" class="text-decoration-none">
                                            <h6>{{ $trend->title }}</h6>
                                        </a>
                                        <p>
                                            {{ $trend->author ?? "Bagmati Proscun" }} 
                                            @if($trend->location)
                                                - {{ $trend->location }}
                                            @endif
                                            <a href="{{ route('news.detail', $trend->slug) }}">View</a>
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-center text-muted m-0">No trending news available.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Other News Section -->
    @if(isset($_other_news) && $_other_news->count() > 0)
        <div class="extra-news-section">
            <div class="container-xl">
                <h3 class="extra-news-title">Other News</h3>
                <div class="row">
                    @foreach($_other_news as $key => $other)
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-4">
                            <div class="other-news-card">
                                <div class="other-news-img-wrapper">
                                    <img src="{{ \App\Facades\ViewHelper::getImagePath('news', $other->photo) }}"
                                         alt="{{ $other->title }}">
                                </div>
                                <div class="other-news-card-body">
                                    @if($other->parent)
                                        <span class="other-news-category">{{ $other->parent->title }}</span>
                                    @endif
                                    <a href="{{ route('news.detail', $other->slug) }}" class="text-decoration-none">
                                        <h5 class="other-news-title">{{ $other->title }}</h5>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @include('website.home.affiliation')
@endsection
