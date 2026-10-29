@extends('website.common.layout')

@section('title', "News")

@section('meta')
    @include('website.common.meta', [
    '_title' => "News",
    '_detail' => "News List Of Bagmati Proscun",
    '_folder' => 'news',
    '_photo' => @$_news->first()->photo,
])
@endsection

@section('css')
<style>
    .news-container-grid {
        padding: 40px 15px !important;
        background-color: #fafdfb;
    }

    /* Reset bootstrap col margin */
    .cards-a {
        margin-bottom: 30px !important;
    }

    /* Premium News Card Styling */
    .news-card {
        background: #ffffff !important;
        border: 1px solid var(--brand-border, #ddf3e8) !important;
        border-radius: 16px !important;
        overflow: hidden !important;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.02) !important;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .news-card:hover {
        transform: translateY(-6px) !important;
        box-shadow: 0 15px 35px rgba(1, 167, 85, 0.08) !important;
        border-color: var(--brand-green-light, #35b673) !important;
    }

    /* Image wrapper */
    .news-img-wrapper {
        position: relative;
        overflow: hidden;
        aspect-ratio: 16/8;
        background-color: #f8fafc;
        border-bottom: 1px solid var(--brand-border, #ddf3e8);
    }

    .news-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease !important;
    }

    .news-card:hover .news-img-wrapper img {
        transform: scale(1.08) !important;
    }

    /* Card Content Area */
    .news-card-body {
        padding: 18px !important;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        justify-content: space-between;
        text-align: left;
    }

    .news-card-title {
        font-size: 1.15rem !important;
        font-weight: 700 !important;
        color: #1e293b !important;
        margin-bottom: 10px !important;
        line-height: 1.4 !important;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 2.8rem;
    }

    .news-card:hover .news-card-title {
        color: var(--brand-green, #01a755) !important;
    }

    /* Date and Metadata with Calendar Icon gap */
    .news-card-meta {
        font-size: 0.82rem !important;
        color: #64748b !important;
        margin-bottom: 12px !important;
        display: flex;
        align-items: center;
        gap: 8px; /* Clean gap space between icon and date */
    }

    .news-card-meta i {
        color: var(--brand-green, #01a755);
        font-size: 0.88rem;
    }

    .news-card-desc {
        font-size: 0.92rem !important;
        line-height: 1.6 !important;
        color: #475569 !important;
        text-align: justify;
        margin-bottom: 12px !important;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 3rem;
    }

    /* Read Details button action */
    .news-card-action a.btn-read {
        border-radius: 30px !important;
        font-weight: 700 !important;
        font-size: 0.85rem !important;
        padding: 8px 24px !important;
        transition: all 0.25s ease !important;
        display: inline-block;
        border: 1px solid var(--brand-green, #01a755) !important;
        color: var(--brand-green, #01a755) !important;
        text-decoration: none !important;
        text-align: center;
    }

    .news-card-action a.btn-read:hover {
        background-color: var(--brand-green, #01a755) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(1, 167, 85, 0.2) !important;
    }
</style>
@endsection

@section('content')
    <!-- Unified Premium Breadcrumb Hero Banner -->
    @include('website.common.page-heading')
    
    <div class="container-xl news-container-grid">
        <div class="row">
            @if(isset($_news) && $_news->count() > 0)
                @foreach($_news as $key => $news)
                    <div class="col-12 col-md-6 col-lg-4 cards-a">
                        <div class="news-card">
                            <div class="news-img-wrapper">
                                <img src="{{ \App\Facades\ViewHelper::getImagePath('news', $news->photo) }}"
                                     alt="{{ $news->title ?? "News Photo" }}">
                            </div>
                            <div class="news-card-body">
                                <div>
                                    <h4 class="news-card-title"><b>{{ $news->title ?? "News Title" }}</b></h4>
                                    <div class="news-card-meta">
                                        <i class="fa fa-calendar"></i>
                                        <span>{{ $news->created_at->format('M d, Y') }}</span>
                                    </div>
                                    <p class="news-card-desc">
                                        {{ \App\Facades\ViewHelper::getStrippedContent($news->description, 150) }}
                                    </p>
                                </div>
                                <div class="news-card-action">
                                    <a href="{{ route('news.detail', $news->slug) }}" class="btn-read">
                                        Read More <i class="fas fa-arrow-right ml-1" style="font-size: 0.75rem;"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-md-12">
                    <div class="card border-0 shadow-sm text-center p-5 text-muted" style="border-radius: 16px; border: 1px solid var(--brand-border, #ddf3e8) !important;">
                        <i class="fa fa-newspaper-o fa-3x mb-3 text-light"></i>
                        <h5>No news items found.</h5>
                    </div>
                </div>
            @endif
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="d-flex justify-content-center mt-5">
                    {!! $_news->links() !!}
                </div>
            </div>
        </div>
    </div>
    <!-- -------News Section Ends--------- -->
@endsection
