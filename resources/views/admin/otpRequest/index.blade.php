@extends('admin.layout.frame')

@section('title', 'OTP Requests Logs')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6"><h1>OTP Verification Requests</h1></div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.event.index') }}"><i class="fa fa-home"></i></a></li>
                            <li class="breadcrumb-item active">OTP Requests</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <!-- Dashboard Metrics Row -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $stats['total_requests'] }}</h3>
                                <p>Total OTP Requests</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-list-ol"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $stats['phone_requests'] }}</h3>
                                <p>Phone Requests (SMS)</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-mobile-alt"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3>{{ $stats['email_requests'] }}</h3>
                                <p>Email Requests</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $stats['verified_requests'] }}</h3>
                                <p>Verified Successes</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-check-double"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card card-outline card-primary mb-4">
                    <div class="card-header">
                        <h3 class="card-title text-bold"><i class="fa fa-filter"></i> Filter & Search OTP Requests</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.otp-requests.index') }}" method="GET">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group mb-0">
                                        <label class="font-weight-bold">Search Identifier (Phone or Email)</label>
                                        <input type="text" name="identifier" class="form-control" placeholder="e.g. 98XXXXXXXX or user@email.com" value="{{ request('identifier') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <label class="font-weight-bold">Filter by Type</label>
                                        <select name="type" class="form-control">
                                            <option value="">All Types</option>
                                            <option value="phone" {{ request('type') == 'phone' ? 'selected' : '' }}>Phone</option>
                                            <option value="email" {{ request('type') == 'email' ? 'selected' : '' }}>Email</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary mr-2 w-50"><i class="fa fa-search"></i> Filter</button>
                                    <a href="{{ route('admin.otp-requests.index') }}" class="btn btn-secondary w-50"><i class="fa fa-sync"></i> Reset</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row">
                    <!-- Top Requesters List -->
                    <div class="col-md-4">
                        <div class="card card-warning card-outline">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-chart-line mr-1"></i> Top Requesters (Spam Check)</h3>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Identifier</th>
                                            <th>Type</th>
                                            <th class="text-center">Count</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($topRequesters as $requester)
                                            <tr>
                                                <td>
                                                    <span class="font-weight-bold">{{ $requester->identifier }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge {{ $requester->type === 'email' ? 'badge-primary' : 'badge-success' }}">
                                                        {{ ucfirst($requester->type) }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-danger px-3 py-1 font-weight-bold" style="font-size: 0.9rem;">
                                                        {{ $requester->request_count }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted">No requests recorded yet.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- OTP Requests Logs -->
                    <div class="col-md-8">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-list mr-1"></i> OTP Requests Log</h3>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>SN</th>
                                            <th>Identifier</th>
                                            
                                            <th>Type</th>
                                            <th>Expires At</th>
                                            <th>Status</th>
                                            <th>Requested At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($otpRequests as $key => $otp)
                                            <tr>
                                                <td>{{ $otpRequests->firstItem() + $key }}</td>
                                                <td><strong>{{ $otp->identifier }}</strong></td>
                                                 <td>
                                                    <span class="badge {{ $otp->type === 'email' ? 'badge-primary' : 'badge-success' }}">
                                                        {{ ucfirst($otp->type) }}
                                                    </span>
                                                </td>
                                                <td>{{ $otp->expires_at->format('h:i:s A') }}</td>
                                                <td>
                                                    @if($otp->is_verified)
                                                        <span class="badge badge-success"><i class="fa fa-check-circle mr-1"></i> Verified</span>
                                                    @elseif($otp->isExpired())
                                                        <span class="badge badge-secondary"><i class="fa fa-times-circle mr-1"></i> Expired</span>
                                                    @else
                                                        <span class="badge badge-warning"><i class="fa fa-spinner fa-spin mr-1"></i> Active</span>
                                                    @endif
                                                </td>
                                                <td>{{ $otp->created_at->diffForHumans() }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted">No OTP requests found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-footer clearfix">
                                <div class="float-right">
                                    {{ $otpRequests->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
