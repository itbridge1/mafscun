@php
    $statistics = $data['_statistics'] ?? collect();
    $autoDistrict = $statistics->first(function($d) {
        return !empty($d->d_autoselect);
    });
    $associatedCount = $autoDistrict ? ($autoDistrict->associated ?? 0) : ($data['_associated'] ?? $statistics->sum('associated'));
    $municipalityCount = $autoDistrict ? $autoDistrict->count() : $statistics->count();
@endphp
<section class="home-statistics-section" aria-labelledby="statistics-heading">
    <div class="container-xl">
        <div class="statistics-intro">
            <div>
                <span class="statistics-eyebrow">OUR REACH</span>
                <h2 id="statistics-heading">{{ $_site_setting->copyright_text ?? 'Cooperative network at a glance' }}</h2>
                <p>Explore cooperative coverage across municipalities and view local level figures.</p>
            </div>
            @if($statistics->count() > 0 && !$autoDistrict)
                <label class="statistics-select-wrap" for="search-nav-select">
                    <span>Explore an area</span>
                    <select id="search-nav-select" class="form-control" aria-label="Choose an area to view its statistics">
                        <option value="home" data-toggle="#home" selected>Overview / All Municipalities</option>
                        @foreach($statistics as $item)
                            <option value="{{ \Illuminate\Support\Str::slug($item->d_name) }}" data-toggle="#{{ \Illuminate\Support\Str::slug($item->d_name) }}">{{ $item->d_name }} Coverage</option>
                        @endforeach
                    </select>
                </label>
            @elseif($autoDistrict)
                <div class="d-inline-flex align-items-center bg-white px-3 py-2 rounded-pill shadow-sm border">
                    <i class="fas fa-map-marker-alt text-success mr-2"></i>
                    <strong class="text-dark">{{ $autoDistrict->d_name }}</strong>
                    <span class="badge badge-success ml-2">Selected</span>
                </div>
            @endif
        </div>

        @if($statistics->count() > 0)
            <div class="statistics-summary" aria-label="Statistics summary">
                <div class="statistics-summary-card">
                    <span class="statistics-summary-icon"><i class="fas fa-map-marked-alt"></i></span>
                    <div>
                        <strong>{{ $municipalityCount }}</strong>
                        <span>{{ $autoDistrict ? 'Municipalities in ' . $autoDistrict->d_name : 'Municipalities covered' }}</span>
                    </div>
                </div>
                <div class="statistics-summary-card">
                    <span class="statistics-summary-icon statistics-summary-icon--blue"><i class="fas fa-users"></i></span>
                    <div>
                        <strong>{{ number_format((int) $associatedCount) }}</strong>
                        <span>Associated cooperatives</span>
                    </div>
                </div>
                <div class="statistics-summary-note">
                    <i class="fas fa-info-circle"></i>
                    <span>{{ $autoDistrict ? 'Showing cooperative coverage for ' . $autoDistrict->d_name . '.' : 'Select an area above to view cooperative coverage breakdown.' }}</span>
                </div>
            </div>

            @if($autoDistrict)
                <div class="statistics-panel">
                    <div class="statistics-report-grid">
                        <div class="statistics-map-card">
                            <span class="statistics-map-caption">{{ $autoDistrict->d_name }}</span>
                            <img src="{{ ViewHelper::getImagePath('district', $autoDistrict->d_map) }}" alt="Map of {{ $autoDistrict->d_name }}" loading="lazy">
                        </div>
                        <div class="statistics-table-card">
                            <div class="statistics-table-heading">
                                <div>
                                    <span>MUNICIPALITY BREAKDOWN</span>
                                    <h3>{{ $autoDistrict->d_name }} cooperative coverage</h3>
                                </div>
                                <i class="fas fa-chart-bar" aria-hidden="true"></i>
                            </div>
                            <div class="table-responsive statistics-table-scroll">
                                <table class="table statistics-table mb-0">
                                    <thead><tr><th scope="col">Municipality</th><th scope="col" class="text-right">Associated</th></tr></thead>
                                    <tbody>
                                        @forelse($autoDistrict as $municipality)
                                            <tr>
                                                <td>{{ $municipality->municipality_name ?? '—' }}</td>
                                                <td class="text-right"><strong>{{ number_format((int) ($municipality->associated ?? 0)) }}</strong></td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="2" class="text-center text-muted py-4">No municipality statistics are available.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="statistics-panel tab-content" id="statistics-panels">
                    <div class="tab-pane fade show active" id="home" role="tabpanel">
                        <div class="statistics-report-grid">
                            <div class="statistics-map-card">
                                <span class="statistics-map-caption">COVERAGE MAP</span>
                                <img src="{{ ViewHelper::getImagePath('siteSetting', $_site_setting->stat_map ?? null) }}" alt="Coverage Map" loading="lazy">
                            </div>
                            <div class="statistics-table-card">
                                <div class="statistics-table-heading"><div><span>COVERAGE OVERVIEW</span><h3>Cooperative coverage</h3></div><i class="fas fa-chart-pie" aria-hidden="true"></i></div>
                                <div class="table-responsive statistics-table-scroll">
                                    <table class="table statistics-table mb-0">
                                        <thead><tr><th scope="col">Municipality</th><th scope="col" class="text-right">Associated</th></tr></thead>
                                        <tbody>
                                            @foreach($statistics as $stat)
                                                <tr><td>{{ $stat->d_name ?? '—' }}</td><td class="text-right"><strong>{{ number_format((int) ($stat->associated ?? 0)) }}</strong></td></tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    @foreach($statistics as $key => $district)
                        @php $districtId = \Illuminate\Support\Str::slug($district->d_name); @endphp
                        <div class="tab-pane fade" id="{{ $districtId }}" role="tabpanel">
                            <div class="statistics-report-grid">
                                <div class="statistics-map-card">
                                    <span class="statistics-map-caption">{{ $district->d_name }}</span>
                                    <img src="{{ ViewHelper::getImagePath('district', $district->d_map) }}" alt="Map of {{ $district->d_name }}" loading="lazy">
                                </div>
                                <div class="statistics-table-card">
                                    <div class="statistics-table-heading"><div><span>MUNICIPALITY BREAKDOWN</span><h3>{{ $district->d_name }} cooperative coverage</h3></div><i class="fas fa-chart-bar" aria-hidden="true"></i></div>
                                    <div class="table-responsive statistics-table-scroll">
                                        <table class="table statistics-table mb-0">
                                            <thead><tr><th scope="col">Municipality</th><th scope="col" class="text-right">Associated</th></tr></thead>
                                            <tbody>
                                                @forelse($district as $municipality)
                                                    <tr><td>{{ $municipality->municipality_name ?? '—' }}</td><td class="text-right"><strong>{{ number_format((int) ($municipality->associated ?? 0)) }}</strong></td></tr>
                                                @empty
                                                    <tr><td colspan="2" class="text-center text-muted py-4">No municipality statistics are available.</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @else
            <div class="statistics-empty"><i class="fas fa-chart-bar"></i><p>Statistics will be available here soon.</p></div>
        @endif
    </div>
</section>
