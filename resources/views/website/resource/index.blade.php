@extends('website.common.layout')
@section('title', "Resources")
@section('meta')
    @include('website.common.meta', [
    '_title' => "Resources",
    '_detail' => "Resources List - " . ($_site_setting->title ?? "MAFSCUN"),
    '_folder' => 'resource',
    '_photo' => $_resources->first()->photo?? "",
])
@endsection

@section('css')
<style>
    .resource-container-grid {
        padding: 40px 15px !important;
        background-color: #fafdfb;
    }

    /* Filter Panel Styling */
    .resource-filter-panel {
        background: #ffffff !important;
        border: 1px solid var(--brand-border, #ddf3e8) !important;
        border-radius: 16px !important;
        padding: 24px 30px !important;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.02) !important;
        margin-bottom: 40px !important;
    }

    .resource-filter-panel form {
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .resource-filter-panel form .form-group {
        margin: 0 !important;
        flex: 1;
        min-width: 200px;
    }

    .resource-filter-actions {
        flex: 0 0 auto;
        min-width: 140px;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .resource-filter-panel .form-control {
        height: 48px !important;
        border: 1.5px solid var(--brand-border, #ddf3e8) !important;
        border-radius: 10px !important;
        font-weight: 500 !important;
        color: #334155 !important;
        font-size: 0.95rem !important;
        transition: all 0.25s ease !important;
        box-shadow: none !important;
    }

    .resource-filter-panel .form-control:focus {
        border-color: var(--brand-green, #01a755) !important;
        background-color: #fcfefe !important;
    }

    .resource-filter-panel button.btn-search {
        height: 48px !important;
        width: 100% !important;
        border-radius: 10px !important;
        font-weight: 700 !important;
        font-size: 0.95rem !important;
        background-color: var(--brand-green, #01a755) !important;
        border-color: var(--brand-green, #01a755) !important;
        color: #ffffff !important;
        transition: all 0.25s ease !important;
    }

    .resource-filter-panel button.btn-search:hover {
        background-color: var(--brand-green-hover, #008f48) !important;
        box-shadow: 0 4px 12px rgba(1, 167, 85, 0.2) !important;
    }

    .resource-clear-link {
        color: #64748b;
        font-size: .84rem;
        font-weight: 650;
        white-space: nowrap;
        text-decoration: none;
    }

    .resource-clear-link:hover { color: var(--brand-green, #01a755); text-decoration: underline; }

    .resource-filter-count {
        margin: 14px 0 0;
        color: #64748b;
        font-size: .82rem;
    }

    /* Resources List Row Cards */
    .resource-row-card {
        background: #ffffff !important;
        border: 1px solid var(--brand-border, #ddf3e8) !important;
        border-left: 5px solid var(--brand-green, #01a755) !important;
        border-radius: 16px !important;
        padding: 24px 30px !important;
        margin-bottom: 20px !important;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.02) !important;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
    }

    .resource-card-heading { display: flex; align-items: flex-start; gap: 14px; }
    .resource-kind-icon { display: grid; flex: 0 0 42px; width: 42px; height: 42px; place-items: center; border-radius: 12px; background: #edf6ef; color: var(--brand-green, #08784b); font-size: 1rem; }
    .resource-labels { display: flex; flex-wrap: wrap; align-items: center; gap: 7px; margin: 0 0 8px; }
    .resource-label { padding: 4px 8px; border-radius: 99px; background: #f1f6f2; color: #557163; font-size: .68rem; font-weight: 750; line-height: 1.2; }
    .resource-label--type { background: color-mix(in srgb, var(--brand-cyan, #00acf1) 10%, white); color: #27627c; }
    .resource-filter-title { color: #18352a; font-size: 1rem; font-weight: 750; margin: 0 0 3px; }
    .resource-filter-caption { color: #718078; font-size: .8rem; margin: 0; }

    .resource-row-card:hover {
        transform: translateY(-3px) !important;
        box-shadow: 0 15px 35px rgba(1, 167, 85, 0.06) !important;
        border-left-color: var(--brand-cyan, #00acf1) !important;
    }

    /* Date Styling */
    .resource-date-col {
        border-right: 1px dashed var(--brand-border, #ddf3e8);
        padding-right: 20px;
        text-align: center;
    }

    .resource-date-day {
        font-size: 2rem !important;
        font-weight: 800 !important;
        color: #1e293b !important;
        line-height: 1 !important;
        margin-bottom: 4px !important;
    }

    .resource-date-month {
        font-size: 0.8rem !important;
        font-weight: 700 !important;
        color: #64748b !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 0 !important;
    }

    /* Info Column */
    .resource-info-col {
        padding-left: 20px !important;
    }

    .resource-title {
        font-size: 1.2rem !important;
        font-weight: 700 !important;
        color: #1e293b !important;
        margin-bottom: 8px !important;
        line-height: 1.35 !important;
        text-align: left;
    }

    .resource-row-card:hover .resource-title {
        color: var(--brand-green, #01a755) !important;
    }

    .resource-desc {
        font-size: 0.92rem !important;
        line-height: 1.6 !important;
        color: #475569 !important;
        text-align: left;
        margin: 0 !important;
    }

    /* Action Buttons */
    .resource-actions-col {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        align-items: center;
        flex-wrap: wrap;
    }

    .resource-btn {
        border-radius: 30px !important;
        font-weight: 700 !important;
        font-size: 0.85rem !important;
        padding: 8px 20px !important;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none !important;
        transition: all 0.25s ease !important;
    }

    .btn-download {
        background-color: #f0fdf4 !important;
        color: var(--brand-green, #01a755) !important;
        border: 1px solid var(--brand-border, #ddf3e8) !important;
    }

    .btn-download:hover {
        background-color: var(--brand-green, #01a755) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(1, 167, 85, 0.15) !important;
    }

    .btn-view {
        background-color: #f0f9ff !important;
        color: #0369a1 !important;
        border: 1px solid #e0f2fe !important;
    }

    .btn-view:hover {
        background-color: #0369a1 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(3, 105, 161, 0.15) !important;
    }

    @media (max-width: 768px) {
        .resource-row-card {
            padding: 20px !important;
        }
        .resource-date-col {
            border-right: none !important;
            border-bottom: 1px dashed var(--brand-border, #ddf3e8);
            padding-right: 0 !important;
            padding-bottom: 15px !important;
            margin-bottom: 15px !important;
            text-align: left !important;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .resource-date-day {
            font-size: 1.5rem !important;
            margin-bottom: 0 !important;
        }
        .resource-info-col {
            padding-left: 0 !important;
            margin-bottom: 20px !important;
        }
        .resource-actions-col {
            justify-content: flex-start !important;
        }
    }
</style>
@endsection

@section('content')
    <!-- Unified Premium Breadcrumb Hero Banner -->
    @include('website.common.page-heading')
    
    <!-- ============= Download Section ============== -->
    <div class="container-xl resource-container-grid">
        <div class="row">
            <!-- Filter Panel -->
            <div class="col-12">
                <div class="resource-filter-panel">
                    <div class="mb-3"><h2 class="resource-filter-title"><i class="fas fa-folder-open mr-2 text-success"></i>Resource library</h2><p class="resource-filter-caption">Browse publications, guides and useful documents from {{ $_site_setting->title ?? 'MAFSCUN' }}.</p></div>
                    <form action="{{ route('resource-list') }}" method="get" role="search" aria-label="Filter resources">
                        <div class="form-group">
                            <label class="sr-only" for="filter_resource_type">Resource type</label>
                            {!! Form::select('filter_resource_type',$_resourceTypes, request()->query('filter_resource_type'), [
                                'class' => 'form-control',
                                'id' => 'filter_resource_type',
                                'placeholder' => '-- SELECT RESOURCE TYPE --'
                            ]) !!}
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="filter_title">Search resources by title</label>
                            {!! Form::text('filter_title', request()->query('filter_title'), [
                                'class' => 'form-control',
                                'id' => 'filter_title',
                                'type' => 'search',
                                'placeholder' => 'Search by title keywords...'
                            ]) !!}
                        </div>
                        <div class="resource-filter-actions">
                            <button type="submit" class="btn-search btn">
                                <i class="fas fa-search mr-1"></i> Search
                            </button>
                            @if(request()->filled('filter_resource_type') || request()->filled('filter_title'))
                                <a class="resource-clear-link" href="{{ route('resource-list') }}">Clear filters</a>
                            @endif
                        </div>
                    </form>
                    <p class="resource-filter-count" aria-live="polite">Showing {{ $_resources->count() }} of {{ $_resources->total() }} resources</p>
                </div>
            </div>
            
            <!-- Download List -->
            <div class="col-12">
                @if(isset($_resources) && $_resources->count() > 0)
                    @foreach($_resources as $key => $resource)
                        <div class="resource-row-card">
                            <div class="row align-items-center">
                                <!-- Date Column -->
                                <div class="col-md-2 col-12 resource-date-col">
                                    <div class="resource-date-day">{{ optional($resource->created_at)->format('d') ?? '—' }}</div>
                                    <div class="resource-date-month">{{ optional($resource->created_at)->format('M, Y') ?? 'Resource' }}</div>
                                </div>
                                
                                <!-- Info Column -->
                                <div class="col-md-7 col-12 resource-info-col">
                                    <div class="resource-card-heading">
                                        <span class="resource-kind-icon" aria-hidden="true"><i class="{{ $resource->type === 'file' ? 'far fa-file-alt' : 'fas fa-link' }}"></i></span>
                                        <div class="min-width-0">
                                            <div class="resource-labels">
                                                @if($resource->parent)<span class="resource-label">{{ $resource->parent->title }}</span>@endif
                                                <span class="resource-label resource-label--type">{{ $resource->type === 'file' ? 'Downloadable document' : 'Online resource' }}</span>
                                            </div>
                                            <h3 class="resource-title">{{ $resource->title ?? "Resource Title" }}</h3>
                                        </div>
                                    </div>
                                    <div class="resource-desc text-muted">
                                        {!! $resource->description !!}
                                    </div>
                                </div>
                                
                                <!-- Actions Column -->
                                <div class="col-md-3 col-12 resource-actions-col">
                                    @if($resource->type == "file")
                                        <a href="{{ \App\Facades\ViewHelper::getImagePath('resource', $resource->file) }}"
                                           download="{{ \Illuminate\Support\Str::slug($resource->title)."-".$resource->file }}"
                                           class="resource-btn btn-download"
                                           title="Download file">
                                            <i class="fa fa-download" aria-hidden="true"></i>
                                            <span>Download</span>
                                        </a>
                                    @endif
                                    <a target="_blank"
                                       href="{{ $resource->type == "file" ? \App\Facades\ViewHelper::getImagePath('resource', $resource->file) : $resource->file }}"
                                       class="resource-btn btn-view"
                                       title="View online">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                        <span>View</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                        <div class="card border-0 shadow-sm text-center p-5 text-muted" style="border-radius: 16px; border: 1px solid var(--brand-border, #ddf3e8) !important;">
                        <i class="fa fa-folder-open fa-3x mb-3" style="color:var(--brand-cyan,#00acf1)"></i>
                        <h5>No resources found matching those filters.</h5><p class="mb-3">Try another document type or a shorter search phrase.</p><a class="resource-clear-link" href="{{ route('resource-list') }}">View all resources</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <br>
    <!-- =============End Download Section ============== -->

    <!-- =============Pagination Section ============== -->
    <div class="container-xl">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="d-flex justify-content-center mt-3 mb-5">
                    {!! $_resources->links() !!}
                </div>
            </div>
        </div>
    </div>
    <!-- =============End Pagination Section ============== -->
@endsection
