@if(config('services.mis.dock_enabled', true))
@php
    $misUrl = trim((string) config('services.mis.url'));
    if ($misUrl === '') {
        $domain = preg_replace('/^www\./i', '', request()->getHost());
        $misUrl = request()->getScheme() . '://mis.' . $domain;
    }
@endphp

<a class="mis-dock" href="{{ $misUrl }}" aria-label="Open KYM Update" title="Open KYM Update">
    <span class="mis-dock__indicator" aria-hidden="true">K</span>
    <span class="mis-dock__label"><strong>KYM Update</strong><small>Member information</small></span>
    <span class="mis-dock__arrow" aria-hidden="true">→</span>
</a>
@endif
