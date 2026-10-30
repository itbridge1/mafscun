<aside class="main-sidebar sidebar-dark-primary elevation-4 sidebar-light-purple">

    <a href="{{url('/home')}}" class="brand-link navbar-gray">
        <img src="{{ \App\Facades\ViewHelper::getImagePath('siteSetting', $_site_setting->company_logo) ??asset('backend/assets/dist/img/AdminLTELogo.png')}}" alt="{{ $_site_setting->company_alias ??  env('APP_NAME') }} Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text white font-weight-light">
            {{ $_site_setting->company_alias ??  env('APP_NAME') }}
        </span>
    </a>

    <div class="sidebar">
         <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                        <div class="image">
                            <img src="{{ \App\Facades\ViewHelper::getImagePath('user',auth()->user()->photo??'no-image.png') }}" class="img-circle elevation-2" alt="User Image">
                        </div>
                        <div class="info">
                            <a href="{{ route('admin.user.profile') }}" class="d-block">{{ auth()->user()->name?? 'User'}}</a>
                        </div>
                    </div>

         <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar nav-flat nav-child-indent flex-column" data-widget="treeview" role="menu" data-accordion="false">

                            <li class="nav-item">
                                <a href="{{ $dashboard_url??'#' }}" class="nav-link {{ request()->is('home') || request()->is('admin/dashboard') ? 'active':''}}">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>
                                        Dashboard
                                    </p>
                                </a>
                            </li>

                            @can(['role-create','role-index'])
                                <li class="nav-item {{request()->is('admin/role*')?'menu-open':''}}">
                                    <a href="{{route('admin.role.index')}}" class="nav-link {{request()->is('admin/role*')?'active':''}}">
                                        <i class="nav-icon fas marine fa-th marine"></i>
                                        <p>
                                            Role
                                        </p>
                                    </a>
                                </li>
                            @endcan

                            @can(['permission-create','permission-index'])
                                <li class="nav-item {{request()->is('admin/permission*')?'menu-open':''}}">
                                    <a href="{{route('admin.permission.index')}}" class="nav-link {{request()->is('admin/permission*')?'active':''}}">
                                        <i class="nav-icon fas marine fa-lock"></i>
                                        <p>
                                            Permission
                                        </p>
                                    </a>
                                </li>
                            @endcan

                            @can(['user-create','user-index'])
                                <li class="nav-item">
                                    <a href="{{route('admin.user.index')}}" class="nav-link {{request()->is('admin/user*')?'active':''}}">
                                        <i class="nav-icon fas marine fa-user-cog"></i>
                                        <p>
                                            User
                                        </p>
                                    </a>
                                </li>
                            @endcan

                            @if(config('services.events_enabled', true))
                            @php
                                $pendingBookingsCount = \App\Models\EventBooking::where('status', \App\Models\EventBooking::STATUS_PENDING)->count();
                            @endphp
                            <li class="nav-item {{request()->is('admin/event*') || request()->is('admin/paymentQr*') || request()->is('admin/otp-requests*') || request()->is('admin/cooperativeInformation*') ?'menu-open':''}}">
                                <a href="#" class="nav-link {{request()->is('admin/event*') || request()->is('admin/paymentQr*') || request()->is('admin/otp-requests*') || request()->is('admin/cooperativeInformation*') ?'active':''}}">
                                    <i class="nav-icon fas marine fa-calendar-check"></i>
                                    <p>
                                        Events Management
                                        <i class="fas marine fa-angle-left right"></i>
                                        <span class="badge {{ $pendingBookingsCount > 0 ? 'badge-warning' : 'badge-light text-dark' }} right" style="margin-right: 1.5rem;">{{ $pendingBookingsCount }}</span>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <li class="nav-item">
                                        <a href="{{route('admin.event.index')}}" class="nav-link {{request()->is('admin/event*')?'active':''}}">
                                            <i class="nav-icon fas marine fa-calendar-alt"></i>
                                            <p>
                                                Events
                                                <span class="badge {{ $pendingBookingsCount > 0 ? 'badge-warning' : 'badge-light text-dark' }} right">{{ $pendingBookingsCount }}</span>
                                            </p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{route('admin.paymentQr.index')}}" class="nav-link {{request()->is('admin/paymentQr*')?'active':''}}">
                                            <i class="nav-icon fas marine fa-qrcode"></i>
                                            <p>Payment QRs</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{route('admin.otp-requests.index')}}" class="nav-link {{request()->is('admin/otp-requests*')?'active':''}}">
                                            <i class="nav-icon fas marine fa-sms"></i>
                                            <p>OTP Requests</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{route('admin.cooperativeInformation.index')}}" class="nav-link {{request()->is('admin/cooperativeInformation*')?'active':''}}">
                                            <i class="nav-icon fas marine fa-building"></i>
                                            <p>Cooperatives</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endif

                            <li class="nav-item {{request()->is('admin/dev*')?'menu-open':''}}">
                                <a href="#" class="nav-link {{request()->is('admin/dev*')?'active':''}}">
                                    <i class="nav-icon fas marine fa-cog"></i>
                                    <p>
                                        Development Mode
                                        <i class="fas marine fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @canany(['hrm-create','hrm-index'])
                                        <li class="nav-item">
                                            <a href="{{route('admin.hrm.index')}}" class="nav-link {{request()->is('admin/dev/hrm*')?'active':''}}">
                                                <i class="nav-icon fas marine fa-users"></i>
                                                <p>
                                                    HRM
                                                </p>
                                            </a>
                                        </li>
                                    @endcanany

                                    @canany(['serviceCategory-create','serviceCategory-index'])
                                        <li class="nav-item">
                                            <a href="{{route('admin.serviceCategory.index')}}" class="nav-link {{request()->is('admin/dev/serviceCategory*')?'active':''}}">
                                                <i class="nav-icon fas marine fa-server"></i>
                                                <p>
                                                    ServiceCategory
                                                </p>
                                            </a>
                                        </li>
                                    @endcanany

                                        @canany(['category-create','category-index'])
                                            <li class="nav-item">
                                                <a href="{{route('admin.category.index')}}" class="nav-link {{request()->is('admin/dev/category*')?'active':''}}">
                                                    <i class="nav-icon fas marine fa-list"></i>
                                                    <p>
                                                        Category
                                                    </p>
                                                </a>
                                            </li>
                                        @endcanany

                                        @canany(['resourceType-create','resourceType-index'])
                                            <li class="nav-item">
                                                <a href="{{route('admin.resourceType.index')}}" class="nav-link {{request()->is('admin/dev/resourceType*')?'active':''}}">
                                                    <i class="nav-icon fas marine fa-file-archive"></i>
                                                    <p>
                                                        ResourceType
                                                    </p>
                                                </a>
                                            </li>
                                        @endcanany
                                </ul>
                            </li>

                            @canany(['aboutUs-create','aboutUs-index'])
                                <li class="nav-item">
                                    <a href="{{route('admin.aboutUs.index')}}" class="nav-link {{request()->is('admin/aboutUs*')?'active':''}}">
                                        <i class="nav-icon fas marine fa-info"></i>
                                        <p>
                                            AboutUs
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{route('admin.messageCommitment.index')}}" class="nav-link {{request()->is('admin/messageCommitment*')?'active':''}}">
                                        <i class="nav-icon fas marine fa-envelope-open-text"></i>
                                        <p>
                                            Messages & Commitment
                                        </p>
                                    </a>
                                </li>
                            @endcanany

                            @canany(['slider-create','slider-index'])
                                <li class="nav-item">
                                    <a href="{{route('admin.slider.index')}}" class="nav-link {{request()->is('admin/slider*')?'active':''}}">
                                        <i class="nav-icon fas marine fa-image"></i>
                                        <p>
                                            Slider
                                        </p>
                                    </a>
                                </li>
                            @endcanany

                            @canany(['notice-create','notice-index'])
                                <li class="nav-item">
                                    <a href="{{route('admin.notice.index')}}" class="nav-link {{request()->is('admin/notice*')?'active':''}}">
                                        <i class="nav-icon fas marine fa-broadcast-tower"></i>
                                        <p>
                                            Notice
                                        </p>
                                    </a>
                                </li>
                            @endcanany

                            @canany(['news-create','news-index'])
                                <li class="nav-item">
                                    <a href="{{route('admin.news.index')}}" class="nav-link {{request()->is('admin/news*')?'active':''}}">
                                        <i class="nav-icon fas marine fa-newspaper"></i>
                                        <p>
                                            News
                                        </p>
                                    </a>
                                </li>
                            @endcanany

                            @canany(['resource-create','resource-index','resourceSubType-create','resourceSubType-index','resourceType-create','resourceType-index'])
                                <li class="nav-item {{ request()->is('admin/resource*') || request()->is('admin/resourceSubType*') ?'menu-open':'' }}">
                                    <a href="#" class="nav-link {{ request()->is('admin/resource*') || request()->is('admin/resourceSubType*') ?'active':'' }}">
                                        <i class="nav-icon fas marine fa-download"></i>
                                        <p>Resources
                                            <i class="fas marine fa-angle-left right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        @canany(['resourceType-create','resourceType-index'])
                                        <li class="nav-item">
                                            <a href="{{route('admin.resourceType.index')}}" class="nav-link {{request()->is('admin/dev/resourceType*')?'active':''}}">
                                                <i class="nav-icon fas marine fa-server"></i>
                                                <p>Resource Types</p>
                                            </a>
                                        </li>
                                        @endcanany
                                        @canany(['resourceSubType-create','resourceSubType-index'])
                                        <li class="nav-item">
                                            <a href="{{route('admin.resourceSubType.index')}}" class="nav-link {{request()->is('admin/resourceSubType*')?'active':''}}">
                                                <i class="nav-icon fas marine fa-sitemap"></i>
                                                <p>Resource Sub Types</p>
                                            </a>
                                        </li>
                                        @endcanany
                                        @canany(['resource-create','resource-index'])
                                        <li class="nav-item">
                                            <a href="{{route('admin.resource.index')}}" class="nav-link {{request()->is('admin/resource') || request()->is('admin/resource/*')?'active':''}}">
                                                <i class="nav-icon fas marine fa-file-alt"></i>
                                                <p>Resources</p>
                                            </a>
                                        </li>
                                        @endcanany
                                    </ul>
                                </li>
                            @endcanany

                            @canany(['gallery-create','gallery-index'])
                                <li class="nav-item">
                                    <a href="{{route('admin.gallery.index')}}" class="nav-link {{request()->is('admin/gallery*')?'active':''}}">
                                        <i class="nav-icon fas marine fa-images"></i>
                                        <p>
                                            Gallery
                                        </p>
                                    </a>
                                </li>
                            @endcanany

                            @canany(['affiliation-create','affiliation-index'])
                                <li class="nav-item">
                                    <a href="{{route('admin.affiliation.index')}}" class="nav-link {{request()->is('admin/affiliation*')?'active':''}}">
                                        <i class="nav-icon fas marine fa-lightbulb"></i>
                                        <p>
                                            Affliation
                                        </p>
                                    </a>
                                </li>
                            @endcanany

                            @canany(['district-create','district-index'])
                                <li class="nav-item">
                                    <a href="{{route('admin.district.index')}}" class="nav-link {{request()->is('admin/district*')?'active':''}}">
                                        <i class="nav-icon fas marine fa-map"></i>
                                        <p>
                                            District
                                        </p>
                                    </a>
                                </li>
                            @endcanany

                            @canany(['municipality-create','municipality-index'])
                                <li class="nav-item">
                                    <a href="{{route('admin.municipality.index')}}" class="nav-link {{request()->is('admin/municipality*')?'active':''}}">
                                        <i class="nav-icon fas marine fa-map-marked"></i>
                                        <p>
                                            Municipality
                                        </p>
                                    </a>
                                </li>
                            @endcanany

                            @canany(['service-create','service-index','serviceSubCategory-create','serviceSubCategory-index','serviceCategory-create','serviceCategory-index'])
                            <li class="nav-item {{ request()->is('admin/service*') || request()->is('admin/serviceSubCategory*') || request()->is('admin/dev/serviceCategory*') ?'menu-open':'' }}">
                                <a href="#" class="nav-link {{ request()->is('admin/service*') || request()->is('admin/serviceSubCategory*') || request()->is('admin/dev/serviceCategory*') ?'active':'' }}">
                                    <i class="nav-icon fas marine fa-baby-carriage"></i>
                                    <p>Services
                                        <i class="fas marine fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    @canany(['serviceCategory-create','serviceCategory-index'])
                                    <li class="nav-item">
                                        <a href="{{route('admin.serviceCategory.index')}}" class="nav-link {{request()->is('admin/dev/serviceCategory*')?'active':''}}">
                                            <i class="nav-icon fas marine fa-server"></i>
                                            <p>Service Categories</p>
                                        </a>
                                    </li>
                                    @endcanany
                                    @canany(['serviceSubCategory-create','serviceSubCategory-index'])
                                    <li class="nav-item">
                                        <a href="{{route('admin.serviceSubCategory.index')}}" class="nav-link {{request()->is('admin/serviceSubCategory*')?'active':''}}">
                                            <i class="nav-icon fas marine fa-sitemap"></i>
                                            <p>Service Sub Categories</p>
                                        </a>
                                    </li>
                                    @endcanany
                                    @canany(['service-create','service-index'])
                                    <li class="nav-item">
                                        <a href="{{route('admin.service.index')}}" class="nav-link {{request()->is('admin/service*') && !request()->is('admin/serviceSubCategory*') && !request()->is('admin/dev/serviceCategory*') ?'active':''}}">
                                            <i class="nav-icon fas marine fa-baby"></i>
                                            <p>Services</p>
                                        </a>
                                    </li>
                                    @endcanany
                                </ul>
                            </li>
                            @endcanany

                            @canany(['team-create','team-index'])
                                <li class="nav-item {{ str_contains( \Illuminate\Support\Facades\URL::current(),'/team')?'menu-open':'' }}">
                                    <a href="#" class="nav-link {{ str_contains( \Illuminate\Support\Facades\URL::current(),'/team') ?'active':'' }}">
                                        <i class="nav-icon fas marine fa-users"></i>
                                        <p>Teams {{  str_contains('/team', \Illuminate\Support\Facades\URL::current())==1 }}
                                            <i class="fas marine fa-angle-left right"></i>
                                        </p>
                                    </a>
                                    @if(isset($_hrm) && $_hrm->count() > 0)
                                        <ul class="nav nav-treeview">
                                            @foreach($_hrm as $hrmCategory)
                                                <li class="nav-item">
                                                    <a href="{{route('admin.team.index',$hrmCategory->slug)}}" class="nav-link {{request()->is('admin/'.$hrmCategory->slug.'/team*')?'active':''}}">
                                                        <i class="nav-icon fas marine fa-user-friends"></i>
                                                        <p>
                                                            {{ $hrmCategory->title }}
                                                        </p>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endcanany

                            @canany(['statistic-create','statistic-index'])
                                <li class="nav-item">
                                    <a href="{{route('admin.statistic.index')}}" class="nav-link {{request()->is('admin/statistic*')?'active':''}}">
                                        <i class="nav-icon fas marine fa-database"></i>
                                        <p>
                                            Statistics
                                        </p>
                                    </a>
                                </li>
                            @endcanany

                            @canany(['siteSetting-create','siteSetting-index'])
                                <li class="nav-item">
                                    <a href="{{route('admin.siteSetting.index')}}" class="nav-link {{request()->is('admin/siteSetting*')?'active':''}}">
                                        <i class="nav-icon fas marine fa-sitemap"></i>
                                        <p>
                                            Site Setting
                                        </p>
                                    </a>
                                </li>
                            @endcanany

                            @canany(['memberOrganization-create','memberOrganization-index'])
                                <li class="nav-item">
                                    <a href="{{route('admin.memberOrganization.index')}}" class="nav-link {{request()->is('admin/memberOrganization*')?'active':''}}">
                                        <i class="nav-icon fas marine fa-building"></i>
                                        <p>
                                            Member Organization
                                        </p>
                                    </a>
                                </li>
                            @endcanany

                            @canany(['successStory-create','successStory-index'])
                                <li class="nav-item">
                                    <a href="{{route('admin.successStory.index')}}" class="nav-link {{request()->is('admin/successStory*')?'active':''}}">
                                        <i class="nav-icon fas marine fa-user-check"></i>
                                        <p>
                                            Success Story
                                        </p>
                                    </a>
                                </li>
                            @endcanany

                            @canany(['contactForm-create','contactForm-index'])
                                <li class="nav-item">
                                    <a href="{{route('admin.contactForm.index')}}" class="nav-link {{request()->is('admin/contactForm*')?'active':''}}">
                                        <i class="nav-icon fas marine fa-phone-alt"></i>
                                        <p>
                                            ContactForm
                                        </p>
                                    </a>
                                </li>
                            @endcanany
                            {{-- AgmForm & SuggestionForm commented out --}}
                            {{--
                            @if(config('services.agm_form_enabled', true))
                                <li class="nav-item">
                                    <a href="{{route('admin.agmForm.index')}}" class="nav-link {{request()->is('admin/agmForm*')?'active':''}}">
                                        <i class="nav-icon fas marine fa-users-alt"></i>
                                        <p>
                                           AgmForm
                                        </p>
                                    </a>
                                </li>
                            @endif

                            <li class="nav-item">
                                <a href="{{route('admin.SuggestionForm.index')}}" class="nav-link {{request()->is('admin/SuggestionForm*')?'active':''}}">
                                    <i class="nav-icon fas marine fa-users-alt"></i>
                                    <p>
                                       SuggestionForm
                                    </p>
                                </a>
                            </li>
                            --}}

                                
                            @canany(['menu-create','menu-index'])
                                <li class="nav-item">
                                    <a href="{{route('admin.menu.index')}}" class="nav-link {{request()->is('admin/menu*')?'active':''}}">
                                        <i class="nav-icon fas marine fa-list-ul"></i>
                                        <p>
                                            Menu
                                        </p>
                                    </a>
                                </li>
                            @endcanany

                            @canany(['faq-create','faq-index'])
                                <li class="nav-item">
                                    <a href="{{route('admin.faq.index')}}" class="nav-link {{request()->is('admin/faq*')?'active':''}}">
                                        <i class="nav-icon fas marine fa-question"></i>
                                        <p>
                                            FAQ
                                        </p>
                                    </a>
                                </li>
                            @endcanany

                            <li class="nav-item nav-header"></li>
                            <li class="nav-item">
                                <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"
                                   class="nav-link">
                                    <i class="nav-icon fas marine fa-backspace"></i>
                                    <p>
                                        Log Out
                                    </p>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </nav>
    </div>
</aside>
