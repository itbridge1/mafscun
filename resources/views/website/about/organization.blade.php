@extends('website.common.layout')

@section('title', "Organization List")

@section('meta')
    @include('website.common.meta', [
    '_title' => "Organization List",
    '_keywords' => 'member organizations, cooperatives, organization list',
    '_detail' => "Member Organization List",
    '_folder' => 'siteSetting',
    '_photo' => $_site_setting->company_logo,
])
@endsection

@section('content')
    <!-- Unified Page Heading & Breadcrumb -->
    @include('website.common.page-heading')

    <div class="container-xl my-4 pb-5">
        <div class="bg-white rounded-4 border p-4 p-md-5 shadow-sm">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                <div>
                    <span class="statistics-eyebrow">NETWORK</span>
                    <h2 class="fw-bold mb-1" style="color: var(--public-ink); font-size: 1.45rem;">Member Organizations</h2>
                    <p class="text-muted small mb-0">Browse associated cooperatives and affiliated organizations.</p>
                </div>
                @if($_organizations && $_organizations->total() > 0)
                    <span class="badge badge-light px-3 py-2 border rounded-pill text-muted font-weight-bold" style="font-size: 0.82rem;">
                        Total: {{ $_organizations->total() }} Organizations
                    </span>
                @endif
            </div>

            <div class="table-responsive rounded-3 border">
                <table class="table statistics-table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th scope="col" style="width: 50px;">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Municipality</th>
                            <th scope="col">President / Chief</th>
                            <th scope="col">Manager</th>
                            <th scope="col">Contact</th>
                            <th scope="col">PAN No</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if($_organizations && $_organizations->count() > 0)
                        @foreach($_organizations as $key => $organization)
                            <tr>
                                <th scope="row" class="text-muted font-weight-normal">{{ $loop->iteration + ($_organizations->currentPage() - 1) * $_organizations->perPage() }}</th>
                                <td class="font-weight-bold" style="color: var(--public-ink);">{{ $organization->name }}</td>
                                <td>{{ $organization->municipality->name ?? $organization->district->name ?? '—' }}</td>
                                <td>{{ $organization->bod_chief ?? '—' }}</td>
                                <td>{{ $organization->manager ?? '—' }}</td>
                                <td>{{ $organization->contact_no ?? '—' }}</td>
                                <td><code>{{ $organization->pan_no ?? '—' }}</code></td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="text-center py-5 text-muted" colspan="7">
                                <i class="fas fa-building fa-2x mb-3 text-black-50 d-block"></i>
                                Organization list will be updated soon.
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>

            @if($_organizations && $_organizations->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {!! $_organizations->links() !!}
                </div>
            @endif
        </div>
    </div>
@endsection
