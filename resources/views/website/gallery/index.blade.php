@extends('website.common.layout')

@section('title', "Gallery")

@section('meta')
    @include('website.common.meta', [
    '_title' => "Gallery",
    '_detail' => "Photo Gallery and Media Albums",
    '_folder' => 'gallery',
    '_photo' => @$_gallery->first()->cover_photo,
])
@endsection

@section('content')
    <!-- Unified Page Heading & Breadcrumb -->
    @include('website.common.page-heading')

    <div class="container-xl my-4 pb-5">
        <div class="row g-4">
            @if(isset($_gallery) && $_gallery->count() > 0)
                @foreach($_gallery as $album)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="news-card h-100 d-flex flex-column bg-white rounded-4 border overflow-hidden">
                            <div class="news-img-wrapper" style="position: relative; aspect-ratio: 16/10; overflow: hidden; background: #f8fafc;">
                                <img src="{{ \App\Facades\ViewHelper::getImagePath('gallery', $album->cover_photo) }}"
                                     alt="{{ $album->title ?? 'Gallery Album' }}"
                                     class="w-100 h-100" style="object-fit: cover; transition: transform 0.4s ease;">
                                <div class="position-absolute" style="bottom: 12px; right: 12px;">
                                    <span class="badge badge-dark bg-dark px-2 py-1 rounded-pill small" style="opacity: 0.85;">
                                        <i class="fas fa-camera mr-1"></i> Album
                                    </span>
                                </div>
                            </div>
                            <div class="p-4 d-flex flex-column flex-grow-1">
                                <div class="text-muted small mb-2 d-flex align-items-center gap-1">
                                    <i class="far fa-calendar-alt text-success"></i>
                                    <span>{{ $album->created_at ? $album->created_at->format('M d, Y') : '' }}</span>
                                </div>
                                <h5 class="fw-bold mb-2" style="color: var(--public-ink); font-size: 1.15rem;">
                                    <a href="{{ route('gallery.detail', $album->slug) }}" class="text-decoration-none" style="color: inherit;">
                                        {{ $album->title ?? "Gallery Album" }}
                                    </a>
                                </h5>
                                <p class="text-muted small flex-grow-1 mb-3" style="line-height: 1.6;">
                                    {{ \App\Facades\ViewHelper::getStrippedContent($album->description, 130) }}
                                </p>
                                <a href="{{ route('gallery.detail', $album->slug) }}" class="btn btn-sm btn-outline-success rounded-pill align-self-start px-3 py-1 font-weight-bold" style="font-size: 0.8rem;">
                                    View Album <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-12">
                    <div class="events-empty-state text-center p-5 bg-white rounded-4 border">
                        <i class="far fa-images fa-3x mb-3 text-muted"></i>
                        <h4 class="fw-bold text-dark">No Albums Found</h4>
                        <p class="text-muted mb-0">Photo albums will appear here once published.</p>
                    </div>
                </div>
            @endif
        </div>

        @if(isset($_gallery) && $_gallery->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {!! $_gallery->links() !!}
            </div>
        @endif
    </div>
@endsection
