@extends('website.common.layout')
@section('title', $_notice->title ??  $head ?? "Notices" )

@section('meta')
    @include('website.common.meta', [
    '_title' => $_notice->title,
    '_detail' => strip_tags($_notice->description),
    '_folder' => 'notice',
    '_photo' => $_notice->photo,
])
@endsection

@section('css')
<style>
    /* Premium Notice Detail Styling */


    .notice-meta-list {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        list-style: none;
        padding: 0;
        margin: 20px 0 0 0;
        font-size: 0.9rem;
    }

    .notice-meta-list li {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #475569 !important;
    }

    .notice-meta-list li i {
        color: var(--brand-cyan, #00acf1);
    }

    .notice-meta-list li .badge-duration {
        font-size: 0.78rem;
        padding: 5px 12px;
        border-radius: 20px;
        font-weight: 700;
        display: inline-block;
        border: none !important;
    }

    .notice-meta-list li .badge-start {
        background-color: #e0f0e8 !important;
        color: var(--brand-green, #01a755) !important;
    }

    .notice-meta-list li .badge-end {
        background-color: #fee2e2 !important;
        color: #dc2626 !important;
    }

    .notice-container {
        padding: 50px 15px !important;
        background-color: #fafdfb;
    }

    .notice-detail-card {
        background: #ffffff !important;
        border: 1px solid var(--brand-border, #ddf3e8) !important;
        border-radius: 16px !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04) !important;
        padding: 35px !important;
        margin-bottom: 40px;
    }

    .notice-detail-card blockquote {
        display: flow-root;
        width: 100%;
        box-sizing: border-box;
    }

    .notice-detail-card .detail-featured-image-wrapper {
        box-sizing: border-box;
        max-width: 100%;
    }

    .notice-detail-card .detail-featured-image-wrapper img {
        display: block;
        width: 100%;
        max-width: 100%;
        height: auto;
    }

    @media (max-width: 768px) {
        .notice-detail-card {
            padding: 20px !important;
        }
    }

    .notice-detail-card .desc {
        font-size: 0.95rem !important;
        line-height: 1.75 !important;
        color: #475569 !important;
        text-align: justify;
    }

    .notice-detail-card .desc p {
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

    .notices-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
        padding: 0 !important;
        margin: 0 !important;
        list-style: none !important;
    }

    .notice-item {
        display: flex;
        gap: 15px;
        align-items: flex-start;
        padding-bottom: 15px;
        border-bottom: 1px dashed var(--brand-border, #ddf3e8);
        transition: transform 0.2s ease;
    }

    .notice-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .notice-item:hover {
        transform: translateX(4px);
    }

    .notice-num {
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

    .notice-item:hover .notice-num {
        background-color: var(--brand-green, #01a755);
        color: #ffffff;
        border-color: var(--brand-green, #01a755);
    }

    .notice-content {
        flex-grow: 1;
        text-align: left;
    }

    .notice-content h6 {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 5px;
        line-height: 1.4;
        transition: color 0.2s ease;
    }

    .notice-item:hover .notice-content h6 {
        color: var(--brand-green, #01a755);
    }

    .notice-content p {
        font-size: 0.78rem;
        color: #64748b;
        margin: 0;
    }

    .notice-content a {
        color: var(--brand-cyan, #00acf1);
        text-decoration: none;
        font-weight: 600;
        margin-left: 5px;
    }

    .notice-content a:hover {
        color: var(--brand-cyan-hover, #0093ce);
        text-decoration: underline;
    }
</style>
@endsection

@section('content')
    <!-- Notice Detail Page Hero Header -->
    @include('website.common.page-heading')

    <!-- Main Detail Section -->
    <div class="container-xl notice-container">
        <div class="row justify-content-center">
            <!-- Notice Article Column -->
            <div class="col-lg-8 col-md-12">
                <div class="notice-detail-card">
                    <h2 class="font-weight-bold mb-3" style="color: #1e293b; font-size: 1.8rem; line-height: 1.3;">
                        {{ $_notice->title }}
                    </h2>
                    
                    <ul class="notice-meta-list mb-4 pl-0" style="display: flex; flex-wrap: wrap; gap: 15px; list-style: none;">
                        @if($_notice->start_date)
                            <li><span class="badge-duration badge-start" style="font-size: 0.78rem; padding: 5px 12px; border-radius: 20px; font-weight: 700; background-color: #e0f0e8; color: var(--brand-green);">From: {{ $_notice->start_date }}</span></li>
                        @endif
                        @if($_notice->end_date)
                            <li><span class="badge-duration badge-end" style="font-size: 0.78rem; padding: 5px 12px; border-radius: 20px; font-weight: 700; background-color: #fee2e2; color: #dc2626;">To: {{ $_notice->end_date }}</span></li>
                        @endif
                        <li style="color: #64748b; font-size: 0.9rem;"><i class="fa fa-clock" style="color: var(--brand-green);"></i> <span class="ml-1">Published: {{ $_notice->created_at->format('M d, Y') }} ({{ $_notice->created_at->diffForHumans() }})</span></li>
                    </ul>
                    
                    @if($_notice->photo || !empty(trim(strip_tags(preg_replace('~<style\\b[^>]*>.*?</style\\s*>~is', '', $_notice->description ?? '')))))
                    <blockquote style="text-align: justify; border-left: 5px solid var(--brand-green, #01a755); padding-left: 20px; margin: 0;">
                        @if($_notice->photo)
                            <span class="detail-featured-image-wrapper">
                                <img src="{{ \App\Facades\ViewHelper::getImagePath('notice', $_notice->photo) }}" 
                                     alt="{{ $_notice->title }}" />
                            </span>
                        @endif
                        @if(!empty(trim(strip_tags(preg_replace('~<style\\b[^>]*>.*?</style\\s*>~is', '', $_notice->description ?? '')))))
                            <div class="desc">{!! $_notice->description !!}</div>
                        @endif
                    </blockquote>
                    @endif
                </div>
            </div>
            
            <!-- Sidebar Column -->
            <div class="col-lg-4 col-md-12">
                <div class="sidebar-card">
                    <h5>Latest Notices</h5>
                    <div class="notices-list">
                        @if(isset($_other_notices) && $_other_notices->count() > 0)
                            @foreach($_other_notices as $key => $latest_notice)
                                <div class="notice-item">
                                    <div class="notice-num">{{ ++$key }}</div>
                                    <div class="notice-content">
                                        <a href="{{ route('notices.detail', $latest_notice->slug) }}" class="text-decoration-none">
                                            <h6>{{ $latest_notice->title }}</h6>
                                        </a>
                                        <p>
                                            @if($latest_notice->start_date)
                                                Active: {{ $latest_notice->start_date }}
                                            @else
                                                Bagmati Proscun
                                            @endif
                                            <a href="{{ route('notices.detail', $latest_notice->slug) }}">View</a>
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-center text-muted m-0">No notices available.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('website.home.affiliation')
@endsection
