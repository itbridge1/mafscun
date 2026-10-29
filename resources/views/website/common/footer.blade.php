@section('css')
@parent
<style>
    footer h3 {
        font-size: 18px !important;
        color: var(--brand-green) !important;
        font-weight: bolder !important;
        padding-top: 10px;
    }
</style>
@endsection

@php
    $siteTitle = $_site_setting->title ?? 'Cooperative Union';
    if (str_contains(strtolower($siteTitle), 'bagmati proscun')) {
        $siteTitle = 'District Cooperative Union';
    }
$footerIntro = !empty($_site_setting->about_1)
    ? \App\Models\AboutUs::find($_site_setting->about_1)
    : null;
$footerIntroTitle = $footerIntro->title ?? 'संघको संक्षिप्त परिचय';
$footerIntroText = $footerIntro
    ? \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/u', ' ', strip_tags($footerIntro->description ?? ''))), 420, '…')
    : strip_tags($_site_setting->footer_text ?? '');

    // Clean dynamic menu items to ensure e-library and old brand mentions are completely removed
    $cleanMenuFilter = function($menu) {
        if (!$menu || !isset($menu->children)) return collect();
        return $menu->children->filter(function($item) {
            $titleLower = strtolower($item->title ?? '');
            $urlLower = strtolower($item->menu_url ?? '');
            return !str_contains($titleLower, 'e-library')
                && !str_contains($titleLower, 'e library')
                && !str_contains($titleLower, 'elibrary')
                && !str_contains($titleLower, 'bagmati proscun')
                && !str_contains($urlLower, 'elibrary.bagmatiproscun');
        });
    };

    $footer1Links = $cleanMenuFilter($_footer1 ?? null);
    $footer2Links = $cleanMenuFilter($_footer2 ?? null);
    $footer3Links = $cleanMenuFilter($_footer3 ?? null);
    $footer4Links = $cleanMenuFilter($_footer4 ?? null);
@endphp

<footer>
    <div class="container-xl">
        <section class="footer-spotlight" aria-label="Latest updates">
            <div class="footer-spotlight__copy">
                <span class="footer-spotlight__badge"><span aria-hidden="true"></span> Latest updates</span>
                <h2>Stay connected with your cooperative community.</h2>
                <p>Find the latest news, notices, and events from our cooperative network.</p>
            </div>
            <div class="footer-spotlight__actions">
                <a href="{{ route('notices') }}">View notices <span aria-hidden="true">→</span></a>
                @if(config('services.events_enabled', true))<a href="{{ route('events.index') }}">Upcoming events</a>@endif
            </div>
        </section>
    </div>

    <!-- ==========footer top section========== -->
    <div class="container-xl footer-top">
        <div class="row">
            <!-- Col 1: About Info -->
            <div class="col-lg-4 col-md-6 footer-item footer-about mb-4 mb-lg-0">
                <span class="footer-brand-label">{{ $footerIntroTitle }}</span>
                <div class="footer-text-content">
                    {{ $footerIntroText }}
                </div>
            </div>

            <!-- Col 2: Quick Links -->
            <div class="col-lg-2 col-md-6 footer-item mb-4 mb-lg-0">
                <h3>Quick links</h3>
                <ul class="footer-link-list">
                    <li><a href="{{ route('index') }}">Home</a></li>
                    <li><a href="{{ route('about') }}">About us</a></li>
                    <li><a href="{{ route('service') }}">Services</a></li>
                    <li><a href="{{ route('organization') }}">Organizations</a></li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                </ul>
            </div>

            <!-- Col 3: Dynamic Important Links -->
            <div class="col-lg-3 col-md-6 footer-item mb-4 mb-lg-0">
                <h3>{{ $_footer1->title ?? 'Important links' }}</h3>
                @if($footer1Links->count() > 0)
                    <ul class="footer-link-list">
                        @foreach($footer1Links as $link)
                            <li>
                                <a href="{{ $link->menu_url }}" 
                                   @if(\Illuminate\Support\Str::startsWith($link->menu_url, ['http://', 'https://'])) target="_blank" rel="noopener noreferrer" @endif>
                                    {{ $link->title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <ul class="footer-link-list">
                        <li><a href="{{ route('news') }}">Latest news</a></li>
                        @if(config('services.events_enabled', true))<li><a href="{{ route('events.index') }}">Events</a></li>@endif
                        <li><a href="{{ route('notices') }}">Notices</a></li>
                        <li><a href="{{ route('resource-list') }}">Resources</a></li>
                    </ul>
                @endif
            </div>

            <!-- Col 4: Additional Dynamic Links / Updates -->
            <div class="col-lg-3 col-md-6 footer-item mb-4 mb-lg-0">
                @if($footer2Links->count() > 0)
                    <h3>{{ $_footer2->title ?? 'Useful links' }}</h3>
                    <ul class="footer-link-list">
                        @foreach($footer2Links as $link)
                            <li>
                                <a href="{{ $link->menu_url }}" 
                                   @if(\Illuminate\Support\Str::startsWith($link->menu_url, ['http://', 'https://'])) target="_blank" rel="noopener noreferrer" @endif>
                                    {{ $link->title }}
                                </a>
                            </li>
                        @endforeach
                        @if(config('services.events_enabled', true))<li><a href="{{ route('events.index') }}">Upcoming events</a></li>@endif
                        <li><a href="{{ route('resource-list') }}">Resources</a></li>
                    </ul>
                @else
                    <h3>News &amp; updates</h3>
                    <ul class="footer-link-list">
                        <li><a href="{{ route('news') }}">Latest news</a></li>
                        @if(config('services.events_enabled', true))<li><a href="{{ route('events.index') }}">Events</a></li>@endif
                        <li><a href="{{ route('notices') }}">Notices</a></li>
                        <li><a href="{{ route('resource-list') }}">Resources</a></li>
                    </ul>
                @endif
            </div>
        </div>
    </div>
    <!-- ==========end footer top section========== -->

    <!-- ==========footer bottom section========== -->
    <div class="container-xl footer-bottom">
        <div class="row footer-info justify-content-between align-items-center">
            <div class="col-md-8 col-12">
                <p class="mb-0">© {{ date('Y') }} {{ $siteTitle }}. All rights reserved.</p>
            </div>
            <div class="col-md-4 col-12 d-flex justify-content-md-end mt-2 mt-md-0">
                <p class="mb-0">Powered by <a target="_blank" rel="noopener noreferrer" href="{{ $_site_setting->powered_by_link ?? '#' }}"><span style="font-weight:bold;color: var(--brand-green);">{{ $_site_setting->powered_by ?? "IT Bridge" }}</span></a></p>
            </div>
        </div>
    </div>
    <!-- ==========end footer bottom section========== -->
</footer>

<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js" type="text/javascript" ></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.js"></script>
@yield('js')
<script type="text/javascript">
    $(document).ready(function(){
        var $affiliationCarousel = $('.customer-logos');
        if ($affiliationCarousel.length && $.fn.slick) {
            var affiliationCount = $affiliationCarousel.children('.affiliation-slide').length;
            if (affiliationCount > 0) {
                $affiliationCarousel.slick({
                    slidesToShow: Math.min(4, affiliationCount),
                    slidesToScroll: 1,
                    autoplay: affiliationCount > 4,
                    autoplaySpeed: 3600,
                    speed: 500,
                    infinite: affiliationCount > 1,
                    arrows: affiliationCount > 1,
                    dots: affiliationCount > 4,
                    prevArrow: '<button type="button" class="slick-prev" aria-label="Previous affiliation">‹</button>',
                    nextArrow: '<button type="button" class="slick-next" aria-label="Next affiliation">›</button>',
                    pauseOnHover: true,
                    responsive: [{
                        breakpoint: 992,
                        settings: { slidesToShow: Math.min(3, affiliationCount) }
                    }, {
                        breakpoint: 520,
                        settings: { slidesToShow: Math.min(2, affiliationCount) }
                    }]
                });
            }
        }

        // Dynamic Image Lightbox Modal Integration
        if (!$('#imageLightboxModal').length) {
            $('body').append(`
                <div id="imageLightboxModal" class="image-lightbox-modal">
                    <span class="lightbox-close">&times;</span>
                    <div class="lightbox-content-wrapper">
                        <img class="lightbox-content" id="lightboxTargetImage">
                        <div id="lightboxCaption" class="lightbox-caption"></div>
                    </div>
                </div>
            `);
        }

        const zoomSelector = '.detail-featured-image-wrapper img, .subservice-featured-img-wrapper img, .subservice-desc-text img, .desc img, .accordion-desc img, .service-overview-img-wrapper img, .service-overview-desc img';
        
        // Add zoom cursor dynamically
        $(zoomSelector).css('cursor', 'zoom-in');

        $(document).on('click', zoomSelector, function() {
            const imgSrc = $(this).attr('src');
            const imgAlt = $(this).attr('alt') || $(this).closest('.detail-card, .news-detail-card, .notice-detail-card, .subservice-content-card, .card-body').find('h2, h3').first().text().trim();
            
            $('#lightboxTargetImage').attr('src', imgSrc);
            $('#lightboxCaption').text(imgAlt);
            
            $('#imageLightboxModal').addClass('show');
            $('body').css('overflow', 'hidden');
        });

        $(document).on('click', '#imageLightboxModal, .lightbox-close', function(e) {
            if (e.target !== document.getElementById('lightboxTargetImage')) {
                $('#imageLightboxModal').removeClass('show');
                $('body').css('overflow', '');
            }
        });

        // Sticky Navbar Scroll Observer across all pages
        $(window).on('scroll', function() {
            if ($(window).scrollTop() > 30) {
                $('.navbar.sticky-top').addClass('navbar-scrolled');
            } else {
                $('.navbar.sticky-top').removeClass('navbar-scrolled');
            }
        });
        if ($(window).scrollTop() > 30) {
            $('.navbar.sticky-top').addClass('navbar-scrolled');
        }

        // Hamburger animation sync with Bootstrap collapse
        $('#navbarSupportedContent').on('show.bs.collapse', function () {
            $('.hamburger-btn').attr('aria-expanded', 'true');
        }).on('hide.bs.collapse', function () {
            $('.hamburger-btn').attr('aria-expanded', 'false');
        });
    });
</script>
</body>
</html>
