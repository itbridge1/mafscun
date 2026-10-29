@extends('website.common.layout')

@section('title', $_hrmDetail['title'] ?? "Team")

@section('meta')
    @include('website.common.meta', [
    '_title' => $_hrmDetail->title,
    '_keywords' => $_site_setting->keywords,
    '_detail' => strip_tags($_hrmDetail->description),
    '_folder' => 'hrm',
    '_photo' => $_hrmDetail->photo,
])
@endsection

@section('css')
<style>
    /* Premium Team Profile Styling */
    .bod {
        padding: 50px 15px !important;
        background-color: #fafdfb;
    }

    .bod h1 {
        font-size: 2.25rem !important;
        font-weight: 800 !important;
        color: var(--brand-green, #01a755) !important;
        text-align: center;
        margin-bottom: 45px !important;
        position: relative;
        padding-bottom: 15px;
        border-bottom: none !important; /* Remove old solid border */
        letter-spacing: 0.5px;
    }

    /* Elegant double accent line under title */
    .bod h1::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 4px;
        background-color: var(--brand-green, #01a755);
        border-radius: 2px;
    }

    .bod h1::before {
        content: '';
        position: absolute;
        bottom: -4px;
        left: 50%;
        transform: translateX(-50%);
        width: 30px;
        height: 4px;
        background-color: var(--brand-cyan, #00acf1);
        border-radius: 2px;
        z-index: 1;
    }

    .bod .team-card-container {
        margin-bottom: 30px;
    }

    .bod .team-row {
        margin-bottom: 8px;
    }

    .bod .team-row--lead .card {
        border-top: 4px solid var(--brand-green, #01a755) !important;
    }

    .bod .team-row--lead .image-wrapper {
        width: 172px;
        height: 172px;
    }

    /* Premium card design */
    .bod .card {
        border: 1px solid var(--brand-border, #ddf3e8) !important;
        border-radius: 16px !important;
        background: #ffffff !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04) !important;
        transition: var(--transition-smooth, all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1)) !important;
        overflow: hidden;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 30px 20px 25px 20px;
    }

    /* Modern hover animations */
    .bod .card:hover {
        transform: translateY(-8px) !important;
        box-shadow: 0 20px 40px rgba(1, 167, 85, 0.12) !important;
        border-color: var(--brand-green-light, #35b673) !important;
    }

    /* Image wrapper & frame */
    .bod .image-wrapper {
        position: relative;
        width: 150px;
        height: 150px;
        margin-bottom: 20px;
        border-radius: 50%;
        padding: 5px;
        background: linear-gradient(135deg, var(--brand-green, #01a755) 0%, var(--brand-cyan, #00acf1) 100%);
        transition: transform 0.5s ease;
    }

    .bod .card:hover .image-wrapper {
        transform: rotate(5deg);
    }

    .bod .card img.circle {
        width: 100% !important;
        height: 100% !important;
        object-fit: cover !important;
        border-radius: 50% !important;
        border: 4px solid #ffffff !important;
        box-shadow: none !important;
        margin: 0 !important;
        max-height: none !important;
    }

    /* Text & Typography */
    .bod .card-body {
        padding: 0 !important;
        text-align: center;
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
    }

    .bod .card-title.title {
        font-size: 1.15rem !important;
        font-weight: 700 !important;
        color: #1e293b !important;
        margin-bottom: 8px !important;
        line-height: 1.4 !important;
        transition: color 0.3s ease;
    }

    .bod .card:hover .card-title.title {
        color: var(--brand-green, #01a755) !important;
    }

    /* Designation Badge styling */
    .bod .team-designation-badge {
        font-size: 0.78rem !important;
        font-weight: 700 !important;
        color: var(--brand-green, #01a755) !important;
        background-color: var(--brand-bg-light, #f4fcf7) !important;
        padding: 6px 14px !important;
        border-radius: 20px !important;
        border: 1px solid var(--brand-border, #ddf3e8) !important;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 20px !important;
        display: inline-block;
        max-width: 90%;
        text-align: center;
    }

    /* Elegant, premium social buttons */
    .bod .card-body ul.social {
        display: flex !important;
        gap: 12px !important;
        justify-content: center !important;
        align-items: center !important;
        margin: 0 !important;
        padding: 0 !important;
        list-style: none !important;
    }

    .bod .card-body ul.social li {
        margin: 0 !important;
        padding: 0 !important;
    }

    .bod .card-body ul.social li a.team-social-btn {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 38px !important;
        height: 38px !important;
        border-radius: 50% !important;
        background-color: #ffffff !important;
        border: 1px solid var(--brand-border, #ddf3e8) !important;
        color: var(--brand-green, #01a755) !important;
        text-decoration: none !important;
        font-size: 15px !important;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02) !important;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
        position: relative;
    }

    .bod .card-body ul.social li a.team-social-btn.email-btn {
        color: var(--brand-green, #01a755) !important;
    }

    .bod .card-body ul.social li a.team-social-btn.phone-btn {
        color: var(--brand-cyan, #00acf1) !important;
    }

    /* Hover states for social buttons */
    .bod .card-body ul.social li a.team-social-btn:hover {
        transform: translateY(-3px) scale(1.05) !important;
    }

    .bod .card-body ul.social li a.team-social-btn.email-btn:hover {
        background-color: #ea4335 !important;   /* Gmail red — clearly distinct, white icon visible */
        border-color: #ea4335 !important;
        box-shadow: 0 6px 12px rgba(234, 67, 53, 0.25) !important;
    }
    .bod .card-body ul.social li a.team-social-btn.email-btn:hover i,
    .bod .card-body ul.social li a.team-social-btn.email-btn:hover .fa {
        color: #ffffff !important;
    }

    .bod .card-body ul.social li a.team-social-btn.phone-btn:hover {
        background-color: var(--brand-cyan, #00acf1) !important;
        border-color: var(--brand-cyan, #00acf1) !important;
        box-shadow: 0 6px 12px rgba(0, 172, 241, 0.25) !important;
    }
    .bod .card-body ul.social li a.team-social-btn.phone-btn:hover i,
    .bod .card-body ul.social li a.team-social-btn.phone-btn:hover .fa {
        color: #ffffff !important;
    }

    /* Custom tooltip for phone/email */
    .team-social-btn[title]::after {
        content: attr(title);
        position: absolute;
        bottom: calc(100% + 10px);
        left: 50%;
        transform: translateX(-50%);
        background-color: #1e293b;
        color: #ffffff;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 11px;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        transition: opacity 0.25s ease, visibility 0.25s ease;
        z-index: 100;
        pointer-events: none;
    }

    .team-social-btn[title]::before {
        content: '';
        position: absolute;
        bottom: calc(100% + 4px);
        left: 50%;
        transform: translateX(-50%);
        border: 5px solid transparent;
        border-top-color: #1e293b;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.25s ease, visibility 0.25s ease;
        z-index: 100;
        pointer-events: none;
    }

    .team-social-btn[title]:hover::after,
    .team-social-btn[title]:hover::before {
        opacity: 1;
        visibility: visible;
    }
</style>
@endsection

@section('content')
    <!-- Unified Page Heading & Breadcrumb -->
    @include('website.common.page-heading')

    <div class="container-xl bod pt-3 pb-5">
        <div class="team-rows">
            @if(isset($_hrmDetail->teamRows) && $_hrmDetail->teamRows->isNotEmpty())
                @foreach($_hrmDetail->teamRows as $rowLevel => $teamRow)
                    <div class="row team-row {{ $rowLevel === 1 ? 'team-row--lead justify-content-center' : 'justify-content-center' }} w-100">
                        @foreach($teamRow as $team)
                            <div class="{{ $rowLevel === 1 ? 'col-lg-4 col-md-6 col-sm-8' : 'col-lg-3 col-md-4 col-sm-6' }} col-12 team-card-container">
                                <div class="card">
                                    <div class="image-wrapper">
                                        <img
                                            class="circle"
                                            src="{{ $team->photo && file_exists(public_path('images/team/' . $team->photo)) ? \App\Facades\ViewHelper::getImagePath('team', $team->photo) : asset('images/no-image.png') }}"
                                            alt="{{ $team->name ?? 'Team Member' }}"
                                            loading="lazy"
                                        />
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title title">{{ $team->name ?? 'NAME' }}</h5>
                                        <span class="team-designation-badge">{{ $team->designation ?? 'Designation' }}</span>
                                        <ul class="social">
                                            @if($team->email)
                                                <li><a href="mailto:{{ $team->email }}" class="team-social-btn email-btn" title="{{ $team->email }}"><i class="fa fa-envelope"></i></a></li>
                                            @endif
                                            @if($team->phone)
                                                <li><a href="tel:{{ $team->phone }}" class="team-social-btn phone-btn" title="{{ $team->phone }}"><i class="fa fa-phone"></i></a></li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @else
                <div class="col-lg-12 col-md-12 col-sm-12 col-12 text-center">
                    <div class="card">
                        <h5 class="card-title title">
                            No Content To Display
                        </h5>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @include('website.home.affiliation')
@endsection
