@extends('website.common.layout')
@section('title', "Careers")
@section('meta')
    @include('website.common.meta', [
    '_title' => "Careers",
    '_detail' => "Career Notice List - " . ($_site_setting->title ?? "MAFSCUN"),
    '_folder' => 'notice',
    '_photo' => $_notices->first()->photo?? "",
])
@endsection

@section('css')
<style>
    .notice-container-grid {
        padding: 50px 15px !important;
        background-color: #fafdfb;
    }

    /* Override old notice item spacing */
    .notice .notice-item {
        margin: 0 0 30px 0 !important; /* Reset margin to prevent grid breakage */
        padding: 0 15px !important;    /* Standard Bootstrap padding spacing */
    }

    .notice-card {
        background: #ffffff !important;
        border: 1px solid var(--brand-border, #ddf3e8) !important;
        border-top: 4px solid var(--brand-green, #01a755) !important;
        border-radius: 16px !important;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.02) !important;
        padding: 35px 30px !important;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
        min-height: 420px; /* Makes the card a little bigger */
    }

    .notice-card:hover {
        transform: translateY(-5px) !important;
        box-shadow: 0 15px 35px rgba(1, 167, 85, 0.08) !important;
        border-top-color: var(--brand-cyan, #00acf1) !important;
    }

    .notice-title {
        font-size: 1.35rem !important;
        font-weight: 700 !important;
        color: #1e293b !important;
        margin-bottom: 12px !important;
        line-height: 1.45 !important;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 3.8rem;
    }

    .notice-card:hover .notice-title {
        color: var(--brand-green, #01a755) !important;
    }

    .notice-metadata {
        font-size: 0.82rem !important;
        color: #64748b !important;
        margin-bottom: 12px !important;
        border-bottom: 1px dashed var(--brand-border, #ddf3e8);
        padding-bottom: 10px;
    }

    .notice-date-badge {
        font-size: 0.8rem !important;
        padding: 6px 14px !important;
        border-radius: 30px !important;
        font-weight: 600 !important;
        display: inline-block;
        margin: 4px;
    }

    .date-start {
        background-color: #e0f0e8 !important;
        color: var(--brand-green, #01a755) !important;
    }

    .date-end {
        background-color: #fee2e2 !important;
        color: #dc2626 !important;
    }

    .notice-desc {
        font-size: 0.95rem !important;
        line-height: 1.65 !important;
        color: #475569 !important;
        text-align: justify;
        margin-bottom: 25px !important;
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
        min-height: 6.4rem;
    }

    .notice-action a.btn-read {
        border-radius: 30px !important;
        font-weight: 700 !important;
        font-size: 0.85rem !important;
        padding: 8px 24px !important;
        transition: all 0.25s ease !important;
        display: inline-block;
        border: 1px solid var(--brand-green, #01a755) !important;
        color: var(--brand-green, #01a755) !important;
        text-decoration: none !important;
    }

    .notice-action a.btn-read:hover {
        background-color: var(--brand-green, #01a755) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(1, 167, 85, 0.2) !important;
    }
</style>
@endsection

@section('content')
    <!-- Unified Premium Breadcrumb Hero Banner -->
    @include('website.common.page-heading')
    
    <!---------notic section--------->
    <div class="container-xl notice notice-container-grid">
        @if(isset($_notices) && $_notices->count() > 0)
            @php
                $total = $_notices->count();
                $numCol4 = $total; // Default: all take col-lg-4 (3 per row)
                
                if ($total % 3 == 2) {
                    $numCol4 = $total - 2; // Renders remaining 2 in col-lg-6
                } elseif ($total % 3 == 1) {
                    $numCol4 = max(0, $total - 4); // Renders remaining 4 in col-lg-6
                }
            @endphp
            <div class="row justify-content-center">
                @foreach($_notices as $key => $_notice)
                    @php
                        $colClass = $key < $numCol4 ? 'col-lg-4 col-md-6' : 'col-lg-6 col-md-6';
                        if ($total == 1) {
                            $colClass = 'col-lg-6 col-md-8';
                        }
                    @endphp
                    <div class="{{ $colClass }} col-sm-12 notice-item">
                        <div class="notice-card text-center">
                            <div>
                                <h5 class="notice-title">{{ $_notice->title ?? "Notice Title" }}</h5>
                                
                                <div class="notice-metadata">
                                    <i class="far fa-clock mr-1"></i> {{ $_notice->created_at->diffForHumans() }}
                                </div>
                                
                                <p class="notice-desc">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($_notice->description), 140) }}
                                </p>
                            </div>
                            
                            <div>
                                <div class="mb-3">
                                    @if($_notice->start_date)
                                        <span class="notice-date-badge date-start">From: {{ $_notice->start_date }}</span>
                                    @endif
                                    @if($_notice->end_date)
                                        <span class="notice-date-badge date-end">To: {{ $_notice->end_date }}</span>
                                    @endif
                                </div>
                                
                                <div class="notice-action">
                                    <a href="{{ route('notices.detail', $_notice->slug) }}" class="btn-read">
                                        Read Details <i class="fas fa-arrow-right ml-1" style="font-size: 0.75rem;"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="row justify-content-center">
                <div class="col-lg-10 col-sm-10 notice-item">
                    <div class="notice-card p-5 text-center text-muted">
                        <i class="fa fa-info-circle fa-2x mb-3 text-secondary"></i>
                        <h5>No Content To Display</h5>
                    </div>
                </div>
            </div>
        @endif

        <div class="row justify-content-center mt-5">
            <div class="col-md-10">
                <div class="d-flex justify-content-center">
                    {!! $_notices->links() !!}
                </div>
            </div>
        </div>
    </div>
    <!---------End notic section--------->
@endsection
