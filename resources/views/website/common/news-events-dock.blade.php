<aside class="home-updates-dock" aria-label="Notices and events">
    <button class="home-updates-dock__trigger" type="button" aria-expanded="false" aria-controls="sharedUpdatesDockPanel">
        <i class="fas fa-bullhorn" aria-hidden="true"></i><span>Notices &amp; Events</span>
    </button>
    <div class="home-updates-dock__panel" id="sharedUpdatesDockPanel">
        <div class="home-notice-card">
            <div class="home-notice-header d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center"><div class="home-notice-icon-circle mr-2"><i class="fas fa-bullhorn"></i></div><h4 class="home-notice-card-title mb-0">Notices &amp; Events</h4></div>
                <a href="{{ route('notices') }}" class="home-notice-top-link" data-top-link>See all <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="home-notice-tabs" role="tablist" aria-label="Choose updates">
                <button type="button" class="active" role="tab" aria-selected="true" data-update-filter="all">Recent</button>
                <button type="button" role="tab" aria-selected="false" data-update-filter="notice">Notices</button>
                @if(config('services.events_enabled', true))<button type="button" role="tab" aria-selected="false" data-update-filter="event">Events</button>@endif
            </div>
            <div class="home-notice-list" role="tabpanel" tabindex="0" aria-live="polite">
                @forelse($_dock_updates ?? [] as $key => $item)
                    @continue($item->type === 'event' && !config('services.events_enabled', true))
                    @php
                        $isEvent = $item->type === 'event';
                        $today = \Carbon\Carbon::today();
                        $start = $item->start_date ? \Carbon\Carbon::parse($item->start_date)->startOfDay() : null;
                        $end = $item->end_date ? \Carbon\Carbon::parse($item->end_date)->endOfDay() : $start?->copy()->endOfDay();
                        $eventState = !$isEvent ? null : (!$start || $today->lt($start) ? 'upcoming' : ($today->lte($end) ? 'live' : 'expired'));
                        $categoryIndex = $item->type === 'notice' ? $loop->index : $loop->index;
                    @endphp
                    <a href="{{ $item->url }}" class="home-notice-list-item" data-update-type="{{ $item->type }}" data-update-index="{{ $key }}" data-category-index="{{ $categoryIndex }}">
                        <div class="home-notice-file-icon {{ $eventState === 'live' ? 'is-live' : '' }}">
                            <i class="{{ $isEvent ? 'far fa-calendar-alt' : 'far fa-file-alt' }}"></i><span>{{ $isEvent ? 'EVENT' : 'NOTICE' }}</span>
                        </div>
                        <div class="home-notice-item-content">
                            <h5 class="home-notice-item-title">
                                @if($eventState === 'live')<span class="badge badge-danger ml-1 mb-1"><span class="live-event-dot"></span>LIVE</span>
                                @elseif($eventState === 'expired')<span class="badge badge-secondary ml-1 mb-1">EXPIRED</span>
                                @elseif($eventState === 'upcoming')<span class="badge badge-info ml-1 mb-1">UPCOMING</span>@endif
                                <span>{{ $item->title }}</span>
                            </h5>
                            <div class="home-notice-item-time"><i class="far fa-clock"></i> {{ optional($item->date)->diffForHumans() }} <span aria-hidden="true">·</span> {{ optional($item->date)->format('M d, Y') }}</div>
                            @if($item->summary)<p class="home-notice-preview">{{ $item->summary }}</p>@endif
                        </div>
                    </a>
                @empty
                    <div class="home-notice-empty" data-empty-type="all"><p>No recent notices or events are available.</p></div>
                @endforelse
                @if(!config('services.events_enabled', true) && collect($_dock_updates ?? [])->isNotEmpty() && collect($_dock_updates ?? [])->where('type', 'notice')->isEmpty())
                    <div class="home-notice-empty" data-empty-type="all" hidden><p>No recent notices are available.</p></div>
                @endif
                @foreach(['notice' => 'No notices are available right now.', 'event' => 'No events are available right now.'] as $type => $message)
                    <div class="home-notice-empty" data-empty-type="{{ $type }}" hidden><p>{{ $message }}</p></div>
                @endforeach
            </div>
            <div class="home-notice-footer-btn"><a href="{{ route('notices') }}" data-footer-link="notice">See all notices <i class="fas fa-arrow-right"></i></a>@if(config('services.events_enabled', true))<a href="{{ route('events.index') }}" data-footer-link="event">See all events <i class="fas fa-arrow-right"></i></a>@endif</div>
        </div>
    </div>
</aside>
<script>
(() => {
    const dock = document.currentScript.previousElementSibling;
    if (!dock) return;
    const trigger = dock.querySelector('.home-updates-dock__trigger');
    const card = dock.querySelector('.home-notice-card');
    const items = [...card.querySelectorAll('[data-update-type]')];
    const tabs = [...card.querySelectorAll('[data-update-filter]')];
    const close = () => { dock.classList.remove('is-open'); trigger.setAttribute('aria-expanded', 'false'); };
    trigger.addEventListener('click', () => {
        const open = !dock.classList.contains('is-open');
        dock.classList.toggle('is-open', open); trigger.setAttribute('aria-expanded', String(open));
    });
    document.addEventListener('click', e => { if (!dock.contains(e.target)) close(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') close(); });
    function show(type) {
        let count = 0;
        items.forEach((item, i) => {
            const visible = type === 'all' ? i < 4 : item.dataset.updateType === type && count < 4;
            item.hidden = !visible;
            if (visible) count++;
        });
        tabs.forEach(tab => { const active = tab.dataset.updateFilter === type; tab.classList.toggle('active', active); tab.setAttribute('aria-selected', String(active)); });
        card.querySelectorAll('[data-empty-type]').forEach(el => { el.hidden = el.dataset.emptyType !== type || count > 0; });
        card.querySelector('[data-top-link]').href = type === 'event' ? '{{ route('events.index') }}' : '{{ route('notices') }}';
        card.querySelectorAll('[data-footer-link]').forEach(link => { link.hidden = type !== 'all' && link.dataset.footerLink !== type; });
    }
    tabs.forEach(tab => tab.addEventListener('click', () => show(tab.dataset.updateFilter)));
    items.forEach(item => {
        item.addEventListener('pointerenter', () => item.classList.add('preview-open'));
        item.addEventListener('pointerleave', () => item.classList.remove('preview-open'));
        item.addEventListener('focusin', () => item.classList.add('preview-open'));
        item.addEventListener('focusout', () => item.classList.remove('preview-open'));
    });
    show('all');
})();
</script>
