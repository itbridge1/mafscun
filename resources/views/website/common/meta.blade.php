<meta name="robots" content="all">
<meta name="language" content="en_US">
<meta name="title" content="{{ $_title ?? $_site_setting->title }}">
<meta name="author"   content="{{ $_title ?? $_site_setting->title }}" />
<meta name="description" content="{{ strip_tags($_detail) ?? "" }}">
@if(isset($_keywords))
    <meta name="keyword" content="{{ $_keywords ?? "" }}">
@endif
<meta property="og:title" content="{{ $_title ?? $_site_setting->title }}">
<meta property="og:type" content="article" />
<meta property="og:description" content="{{ strip_tags($_detail) ?? "" }}"/>
<meta itemprop="name"            content="{{ $_title ?? $_site_setting->title }}" />
<meta itemprop="description"     content="{{ strip_tags($_detail) ?? "" }}" />
<meta itemprop="image"           content="{{ \App\Facades\ViewHelper::getImagePath($_folder, $_photo) }}" />
<meta property="og:description" content="{{ strip_tags($_detail) ?? "" }}"/>
<meta property="og:type" content="website"/>
<meta property="og:title" content="{{ $_title ?? $_site_setting->title }}"/>
<meta property="og:url" content="{{ \Illuminate\Support\Facades\URL::current() }}"/>
<meta property="site_name" content="{{ $_title ?? $_site_setting->title }}"/>
<meta property="description" content="{{ strip_tags($_detail) ?? "" }}"/>
<meta property="image" content="{{ \App\Facades\ViewHelper::getImagePath($_folder, $_photo) }}"/>
<meta property="og:type" content="website" />
<meta property="og:image" content="{{ \App\Facades\ViewHelper::getImagePath($_folder, $_photo) }}"/>
