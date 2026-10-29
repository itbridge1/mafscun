<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | {{ env('APP_NAME') }}</title>

    <!-- Google Font: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{asset('backend/assets/plugins/fontawesome-free/css/all.min.css')}}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{asset('backend/assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('backend/assets/dist/css/adminlte.min.css')}}">
    @yield('css')
    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{asset('backend/assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/plugins/summernote/summernote-bs4.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/plugins/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/style.css')}}">
    <!-- Modern Admin Refresh Design Layer -->
    <link rel="stylesheet" href="{{asset('backend/assets/admin-refresh.css')}}?v={{ filemtime(public_path('backend/assets/admin-refresh.css')) }}">
</head>
