<head>
    <title>@yield('title') ||  {{ $_site_setting->title ?? "Bagmati PROSCUN" }} </title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon -->
    <link rel="icon" href="{{ \App\Facades\ViewHelper::getImagePath('siteSetting', $_site_setting->site_favicon) }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ \App\Facades\ViewHelper::getImagePath('siteSetting', $_site_setting->site_favicon) }}" type="image/x-icon">
    <link rel="apple-touch-icon" href="{{ \App\Facades\ViewHelper::getImagePath('siteSetting', $_site_setting->site_favicon) }}">
	<meta name="google-site-verification" content="ctGa90qa43WdsGLltz-5BelYcGLsDfAhPzaX69j7rXw" />
    @yield('meta')
	    <meta name="google-site-verification" content="ctGa90qa43WdsGLltz-5BelYcGLsDfAhPzaX69j7rXw" />
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('website/assets/css/bootstrap.min.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('website/assets/style.css') }}?v={{ filemtime(public_path('website/assets/style.css')) }}">
    <!-- Font Awesome 5 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Pagewise CSS -->
    @yield('css')
	<script type='text/javascript' src='https://platform-api.sharethis.com/js/sharethis.js#property=63a16983b92caa0012f80f19&product=sop' async='async'></script>
</head>
