<nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom">
    <!-- Left navbar links -->
    <ul class="navbar-nav align-items-center">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{route('home')}}" class="nav-link font-weight-bold">Dashboard</a>
        </li>
        <li class="nav-item d-none d-md-inline-block">
            <a href="{{route('index')}}" target="_blank" rel="noopener noreferrer" class="nav-link text-success font-weight-bold">
                <i class="fas fa-external-link-alt mr-1"></i> Live Website
            </a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto align-items-center">
        <!-- Fullscreen toggle -->
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button" title="Full screen">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>

        <!-- User Profile Dropdown -->
        <li class="nav-item dropdown">
            <a class="nav-link d-flex align-items-center" data-toggle="dropdown" href="#" aria-expanded="false">
                <img src="{{ \App\Facades\ViewHelper::getImagePath('user', auth()->user()->photo ?? 'no-image.png') }}" class="img-circle mr-2" style="width: 28px; height: 28px; object-fit: cover;" alt="User">
                <span class="d-none d-md-inline font-weight-bold mr-1">{{ auth()->user()->name ?? 'Account' }}</span>
                <i class="fas fa-angle-down text-muted small"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right rounded-4 shadow border-0 mt-2 p-0 overflow-hidden">
                <div class="p-3 text-center border-bottom bg-light">
                    <img src="{{ \App\Facades\ViewHelper::getImagePath('user', auth()->user()->photo ?? 'no-image.png') }}" class="img-circle mb-2" style="width: 48px; height: 48px; object-fit: cover;" alt="User">
                    <h6 class="font-weight-bold mb-0 text-dark">{{ auth()->user()->name ?? 'Administrator' }}</h6>
                    <small class="text-muted">{{ auth()->user()->email ?? '' }}</small>
                </div>
                <div class="p-2">
                    <a href="{{ route('admin.user.profile') }}" class="dropdown-item rounded-3 py-2">
                        <i class="fas fa-user-circle mr-2 text-primary"></i> Profile Settings
                    </a>
                    <a href="{{ route('admin.siteSetting.index') }}" class="dropdown-item rounded-3 py-2">
                        <i class="fas fa-cogs mr-2 text-info"></i> Site Settings
                    </a>
                    <div class="dropdown-divider"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item rounded-3 py-2 text-danger w-100 text-left border-0 bg-transparent">
                            <i class="fas fa-sign-out-alt mr-2"></i> Log Out
                        </button>
                    </form>
                </div>
            </div>
        </li>
    </ul>
</nav>
