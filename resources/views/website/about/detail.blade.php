@extends('website.common.layout')

@section('title', $_aboutUs['title'] ?? "About")

@section('meta')
    @include('website.common.meta', [
    '_title' => $_aboutUs->title,
    '_keywords' => $_site_setting->keywords,
    '_detail' => strip_tags($_aboutUs->description),
    '_folder' => 'aboutUs',
    '_photo' => $_aboutUs->photo,
])
@endsection

@section('css')
<style>
    /* Premium Detail Page Styling */


    .detail-container {
        padding: 50px 15px !important;
        background-color: #fafdfb;
    }

    .detail-card {
        background: #ffffff !important;
        border: 1px solid var(--brand-border, #ddf3e8) !important;
        border-radius: 16px !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04) !important;
        padding: 40px !important;
    }

    .detail-featured-image-wrapper {
        float: right;
        margin: 0 0 20px 25px;
        width: 38%;
        max-width: 38%;
        display: inline-block;
    }

    .detail-featured-image-wrapper img {
        width: 100% !important;
        max-width: 100% !important;
        height: auto !important;
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
        border: 1px solid var(--brand-border, #ddf3e8);
        display: block;
    }

    .detail-card .desc img {
        max-width: 100% !important;
        height: auto !important;
        border-radius: 8px;
        margin: 15px 0;
        display: block;
    }

    .detail-card .desc img:first-of-type {
        float: left;
        width: 22% !important;
        max-width: 22% !important;
        height: auto !important;
        margin: 0 20px 14px 0;
        display: inline-block;
    }

    .detail-card .desc::after { content: ""; display: table; clear: both; }

    .detail-card .desc { font-size: 0.95rem; line-height: 1.75; color: #475569; }
    .detail-card .desc p { margin-bottom: 14px; }

    @media (max-width: 768px) {
        .detail-card {
            padding: 25px 18px !important;
        }

        .detail-featured-image-wrapper {
            float: none !important;
            margin: 0 0 20px 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            display: block !important;
        }

        .detail-card .desc {
            font-size: 0.98rem !important;
        }

        .detail-card .desc img:first-of-type {
            width: 30% !important;
            max-width: 30% !important;
            margin-right: 14px;
        }
    }
</style>
@endsection

@section('content')
    @php
        $description = $_aboutUs->description ?? '';
        $description = preg_replace_callback(
            '/(<img\b[^>]*\bsrc=["\'])([^"\']+)(["\'])/i',
            function ($match) {
                $src = html_entity_decode($match[2], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                if (str_starts_with($src, '/images/')) {
                    $src = asset(ltrim($src, '/'));
                }
                return $match[1] . e($src) . $match[3];
            },
            $description
        );
    @endphp
    <!-- Detail Page Hero Header -->
    @include('website.common.page-heading')

    <!-- Main Detail Section -->
    <div class="container-xl detail-container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-12">
                <div class="detail-card">
                    <h2 class="font-weight-bold mb-4" style="color: #1e293b; font-size: 1.8rem; line-height: 1.3;">
                        {{ $_aboutUs->title }}
                    </h2>
                    <div class="detail-content-wrapper clearfix {{ empty(trim(strip_tags($_aboutUs->description ?? ''))) ? 'detail-content-empty' : '' }}" style="{{ empty(trim(strip_tags($_aboutUs->description ?? ''))) ? '' : 'border-left: 5px solid var(--brand-green, #01a755); padding-left: 20px;' }}">
                        {{-- Prefer the image already embedded in the article; otherwise show the profile photo. --}}
                        @if($_aboutUs->photo && !preg_match('/<img\b/i', $description))
                            <div class="detail-featured-image-wrapper">
                                <img src="{{ \App\Facades\ViewHelper::getImagePath('aboutUs', $_aboutUs->photo) }}" 
                                     alt="{{ $_aboutUs->title }}" />
                            </div>
                        @endif
                        @if(!empty(trim(strip_tags($description))))
                            <div class="desc">{!! $description !!}</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('website.home.affiliation')
@endsection
