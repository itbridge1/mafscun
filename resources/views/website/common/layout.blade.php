<!doctype html>
<html lang="en" style="overflow-x: hidden;">
@include('website.common.head')
<body>
@include('website.common.mis-dock')
@include('website.common.news-events-dock')
<!-- Full-width contact strip -->
@include('website.common.header')
<!-- ==========navbar section========== -->
@include('website.common.navbar')
<!-- ==========End nabar section========== -->
<script>
    (function () {
        function isMobile() { return window.innerWidth < 1200; }

        document.querySelectorAll('.nav-has-flyout > .nav-link').forEach(function (link) {
            link.addEventListener('click', function (event) {
                if (!isMobile()) return;
                event.preventDefault();
                var parent = this.closest('.nav-has-flyout');
                var isOpen = parent.classList.contains('flyout-open');
                document.querySelectorAll('.nav-has-flyout').forEach(function (item) {
                    item.classList.remove('flyout-open');
                });
                if (!isOpen) parent.classList.add('flyout-open');
            });
        });

        document.querySelectorAll('.nav-flyout-item > a').forEach(function (link) {
            link.addEventListener('click', function (event) {
                if (!isMobile()) return;
                var parent = this.parentElement;
                var submenu = Array.from(parent.children).find(function (child) {
                    return child.matches('.nav-flyout-sub, .nav-flyout-sub2');
                });
                if (!submenu) return;
                event.preventDefault();
                var isOpen = parent.classList.contains('flyout-open');
                parent.parentElement.querySelectorAll(':scope > .nav-flyout-item').forEach(function (item) {
                    item.classList.remove('flyout-open');
                });
                if (!isOpen) parent.classList.add('flyout-open');
            });
        });

        document.addEventListener('click', function (event) {
            if (isMobile() && !event.target.closest('.nav-has-flyout')) {
                document.querySelectorAll('.nav-has-flyout').forEach(function (item) {
                    item.classList.remove('flyout-open');
                });
            }
        });
    })();
</script>

{{-- Load last so the public-site design layer wins over styles emitted by shared partials. --}}
<link rel="stylesheet" href="{{ asset('website/assets/public-refresh.css') }}?v={{ filemtime(public_path('website/assets/public-refresh.css')) }}">
<style>
    :root {
        --public-green: {{ preg_match('/^#[0-9A-Fa-f]{6}$/', $_site_setting->primary_color ?? '') ? $_site_setting->primary_color : '#08784b' }};
        --public-deep: color-mix(in srgb, var(--public-green) 82%, #000);
        --brand-green: var(--public-green);
        --brand-green-hover: var(--public-deep);
        --public-secondary: {{ preg_match('/^#[0-9A-Fa-f]{6}$/', $_site_setting->secondary_color ?? '') ? $_site_setting->secondary_color : '#00acf1' }};
        --brand-cyan: var(--public-secondary);
    }
</style>

{{--<div class="main-content">--}}
    @yield('content')
{{--</div>--}}
@include('website.common.footer')
