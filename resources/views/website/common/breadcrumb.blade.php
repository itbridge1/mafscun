@php
    $breadcrumbParents = [];
    if (request()->routeIs('events.show')) {
        $breadcrumbParents[] = ['label' => 'Events', 'url' => route('events.index')];
    } elseif (request()->routeIs('service.item')) {
        $breadcrumbParents[] = ['label' => 'Services', 'url' => route('service')];
        if (isset($_category)) {
            $breadcrumbParents[] = ['label' => $_category->title, 'url' => $_category->url];
        }
        if (isset($_subCategory)) {
            $breadcrumbParents[] = ['label' => $_subCategory->title, 'url' => $_subCategory->url];
        }
    } elseif (request()->routeIs('service.second')) {
        $breadcrumbParents[] = ['label' => 'Services', 'url' => route('service')];
        if (isset($_category)) {
            $breadcrumbParents[] = ['label' => $_category->title, 'url' => $_category->url];
        }
    } elseif (request()->routeIs('service.detail')) {
        $breadcrumbParents[] = ['label' => 'Services', 'url' => route('service')];
    } elseif (request()->routeIs('news.detail')) {
        $breadcrumbParents[] = ['label' => 'News', 'url' => route('news')];
    } elseif (request()->routeIs('notices.detail')) {
        $breadcrumbParents[] = ['label' => 'Notices', 'url' => route('notices')];
    } elseif (request()->routeIs('about.detail')) {
        $breadcrumbParents[] = ['label' => 'About us', 'url' => route('about')];
    } elseif (request()->routeIs('gallery.detail')) {
        $breadcrumbParents[] = ['label' => 'Gallery', 'url' => route('gallery')];
    } elseif (request()->routeIs('career')) {
        $breadcrumbParents[] = ['label' => 'Notices', 'url' => route('notices')];
    }
@endphp
<nav class="public-breadcrumb" aria-label="Breadcrumb">
    <ol>
        <li><a href="{{ route('index') }}">Home</a></li>
        @foreach($breadcrumbParents as $parent)
            <li><a href="{{ $parent['url'] }}">{{ $parent['label'] }}</a></li>
        @endforeach
        <li aria-current="page">@yield('title', 'Page')</li>
    </ol>
</nav>
