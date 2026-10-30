@php
    $metaTitle = !empty($_title) ? $_title : ($_site_setting->title ?? 'MAFSCUN');
    $metaDescription = !empty($_detail) ? trim(strip_tags($_detail)) : ($_site_setting->description ?? '');
    $metaKeywords = !empty($_keywords) ? $_keywords : ($_site_setting->keywords ?? '');
    $metaFolder = !empty($_folder) ? $_folder : 'siteSetting';
    $metaPhoto = !empty($_photo) ? $_photo : ($_site_setting->site_logo ?? null);
    $metaImage = \App\Facades\ViewHelper::getImagePath($metaFolder, $metaPhoto);
    $metaUrl = \Illuminate\Support\Facades\URL::current();
@endphp
<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
<meta name="language" content="ne, en">
<meta name="title" content="{{ $metaTitle }}">
<meta name="author" content="{{ $_site_setting->title ?? 'MAFSCUN' }}" />
<meta name="description" content="{{ $metaDescription }}">
@if(!empty($metaKeywords))
<meta name="keywords" content="{{ $metaKeywords }}">
@endif

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="{{ $metaUrl }}">
<meta property="og:title" content="{{ $metaTitle }}">
<meta property="og:description" content="{{ $metaDescription }}">
<meta property="og:image" content="{{ $metaImage }}">
<meta property="og:site_name" content="{{ $_site_setting->title ?? 'MAFSCUN' }}">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ $metaUrl }}">
<meta name="twitter:title" content="{{ $metaTitle }}">
<meta name="twitter:description" content="{{ $metaDescription }}">
<meta name="twitter:image" content="{{ $metaImage }}">

<!-- Schema.org item metadata -->
<meta itemprop="name" content="{{ $metaTitle }}">
<meta itemprop="description" content="{{ $metaDescription }}">
<meta itemprop="image" content="{{ $metaImage }}">
