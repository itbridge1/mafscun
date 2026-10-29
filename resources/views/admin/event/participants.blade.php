@extends('admin.layout.frame')
@section('title', 'Event Participants')
@section('css')
    <link rel="stylesheet" href="{{asset('backend/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('backend/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6"><h1>Participants for: {{ $data['event']->title }}</h1></div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ $dashboard_url }}"><i class="fa fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.event.index') }}">Events</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.event.bookings', $data['event']->id) }}">Bookings</a></li>
                            <li class="breadcrumb-item active">Participants</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <!-- Dashboard Metrics Row -->
                <div class="row">
                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $data['stats']['total'] }}</h3>
                                <p>Total Participants</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $data['stats']['present'] }}</h3>
                                <p>Total Present</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-check"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-6">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $data['stats']['absent'] }}</h3>
                                <p>Total Absent</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-times"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="card card-outline card-primary mb-2">
                            <div class="card-body p-2">
                                <div class="d-flex flex-wrap justify-content-between align-items-center">
                                    <div class="d-flex align-items-center flex-wrap my-1">
                                        <span class="text-bold mr-3"><i class="fa fa-filter"></i> Attendance:</span>
                                        <a href="{{ route('admin.event.participants', array_merge(request()->except('status'), ['id' => $data['event']->id])) }}" class="btn btn-sm {{ request('status') === null ? 'btn-primary' : 'btn-outline-primary' }} mr-2">
                                            All ({{ $data['stats']['total'] }})
                                        </a>
                                        <a href="{{ route('admin.event.participants', array_merge(request()->all(), ['id' => $data['event']->id, 'status' => 1])) }}" class="btn btn-sm {{ request('status') === '1' ? 'btn-success' : 'btn-outline-success' }} mr-2">
                                            <i class="fa fa-check-circle"></i> Present All Days ({{ $data['stats']['present'] }})
                                        </a>
                                        <a href="{{ route('admin.event.participants', array_merge(request()->all(), ['id' => $data['event']->id, 'status' => 0])) }}" class="btn btn-sm {{ request('status') === '0' ? 'btn-danger' : 'btn-outline-danger' }} mr-2">
                                            <i class="fa fa-times-circle"></i> Absent / Partial ({{ $data['stats']['absent'] }})
                                        </a>
                                    </div>

                                    @if(isset($data['questions']) && $data['questions']->count() > 0)
                                    <form method="GET" action="{{ route('admin.event.participants', $data['event']->id) }}" class="form-inline my-1 ml-auto flex-wrap">
                                        @if(request('status') !== null)
                                            <input type="hidden" name="status" value="{{ request('status') }}">
                                        @endif
                                        <span class="text-bold mr-2 text-sm"><i class="fas fa-question-circle text-info"></i> Question Filter:</span>
                                        
                                        <select name="question_id" id="filter_question_id" class="form-control form-control-sm mr-2 mb-1 mb-sm-0" style="max-width: 220px;">
                                            <option value="">-- All Questions --</option>
                                            @foreach($data['questions'] as $q)
                                                <option value="{{ $q->id }}" {{ request('question_id') == $q->id ? 'selected' : '' }} data-type="{{ $q->type }}" data-options="{{ json_encode($q->parsed_options) }}">
                                                    {{ Str::limit($q->title, 30) }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <input type="text" name="answer_value" id="filter_answer_value" list="answer_options_list"
                                               class="form-control form-control-sm mr-2 mb-1 mb-sm-0" 
                                               placeholder="Filter answer..." 
                                               value="{{ request('answer_value') }}" style="max-width: 160px;">
                                        <datalist id="answer_options_list"></datalist>

                                        <button type="submit" class="btn btn-sm btn-info mr-1">
                                            <i class="fa fa-search"></i> Apply
                                        </button>
                                        @if(request()->filled('question_id') || request()->filled('answer_value'))
                                            <a href="{{ route('admin.event.participants', ['id' => $data['event']->id, 'status' => request('status')]) }}" class="btn btn-sm btn-outline-secondary" title="Clear Question Filter">
                                                <i class="fa fa-times"></i> Clear
                                            </a>
                                        @endif
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary card-outline card-outline-tabs">
                            <div class="card-header p-0 border-bottom-0">
                                <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link {{ request('status') !== '1' ? 'active' : '' }}" id="tab-overall-tab" data-toggle="pill" href="#tab-overall" role="tab" aria-controls="tab-overall" aria-selected="{{ request('status') !== '1' ? 'true' : 'false' }}">Overall (All Participants)</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link {{ request('status') === '1' ? 'active' : '' }}" id="tab-alldays-tab" data-toggle="pill" href="#tab-alldays" role="tab" aria-controls="tab-alldays" aria-selected="{{ request('status') === '1' ? 'true' : 'false' }}">
                                            <i class="fa fa-award text-success mr-1"></i> All Days Present
                                        </a>
                                    </li>
                                    @foreach($data['event_dates'] as $date)
                                    <li class="nav-item">
                                        <a class="nav-link" id="tab-{{ $date }}-tab" data-toggle="pill" href="#tab-{{ $date }}" role="tab" aria-controls="tab-{{ $date }}" aria-selected="false">{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="card-body">
                                <div class="tab-content" id="custom-tabs-four-tabContent">
                                    <!-- Overall Tab -->
                                    <div class="tab-pane fade {{ request('status') !== '1' ? 'show active' : '' }}" id="tab-overall" role="tabpanel" aria-labelledby="tab-overall-tab">
                                        <form action="{{ route('admin.event.certificate.send') }}" method="POST" id="form-send-certificates">
                                            @csrf
                                            <input type="hidden" name="event_id" value="{{ $data['event']->id }}">
                                            
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h3 class="card-title text-bold mb-0">Overall Participants List</h3>
                                                <div class="ml-auto">
                                                    @if($data['event']->certificate_bg_image && !empty($data['event']->certificate_settings))
                                                    <button type="submit" class="btn btn-sm btn-success mr-2">
                                                        <i class="fa fa-envelope"></i> Send Certificates
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-info mr-2" id="btn-bulk-download">
                                                        <i class="fa fa-download"></i> Bulk Download
                                                    </button>
                                                    @endif
                                                    <a href="{{ route('admin.event.bookings', $data['event']->id) }}" class="btn btn-sm btn-secondary">
                                                        <i class="fa fa-arrow-left"></i> Back to Bookings
                                                    </a>
                                                </div>
                                            </div>
                                            <table class="table table-bordered table-striped text-sm datatable-list">
                                                <thead>
                                                    <tr>
                                                        <th><input type="checkbox" id="check-all-participants"></th>
                                                        <th>SN</th>
                                                        <th>Participant Name</th>
                                                        <th>Gender</th>
                                                        <th>Cooperative Name</th>
                                                        <th>Phone</th>
                                                        <th>Email</th>
                                                        @if(isset($data['questions']) && $data['questions']->count() > 0)
                                                            <th>Additional Info</th>
                                                        @endif
                                                        <th>Booked By</th>
                                                        <th>Overall Presence</th>
                                                        <th>Cert. Sent</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($data['participants'] as $key => $p)
                                                    @php
                                                        $attDates = $p->attendance_dates ?: [];
                                                        $presentDays = count(array_intersect($data['event_dates'], $attDates));
                                                        $isOverallPresent = count($data['event_dates']) > 0 ? ($presentDays === count($data['event_dates'])) : (bool)$p->is_present;
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                             <input type="checkbox" name="participants[]" value="{{ $p->id }}" class="participant-checkbox">
                                                        </td>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td><strong>{{ $p->name }}</strong></td>
                                                        <td>{{ $p->gender ?? 'N/A' }}</td>
                                                        <td>
                                                            @if($p->booking->is_member)
                                                            @if($p->booking->cooperative)
                                                                <strong>{{ $p->booking->cooperative->cooperative_name }}</strong>
                                                                <small class="text-muted d-block">Member No: {{ $p->booking->cooperative->member_no }}</small>
                                                            @else
                                                                <strong class="text-danger">Member (Deleted Cooperative)</strong>
                                                            @endif
                                                        @else
                                                            <strong>{{ $p->booking->cooperative_name ?: 'N/A' }}</strong> <span class="badge badge-secondary ml-1">Non-member</span>
                                                        @endif
                                                        </td>
                                                        <td>{{ $p->phone ?? 'N/A' }}</td>
                                                        <td>{{ $p->email ?? 'N/A' }}</td>
                                                        @if(isset($data['questions']) && $data['questions']->count() > 0)
                                                            <td>
                                                                @php $hasAnyAns = false; @endphp
                                                                @foreach($data['questions'] as $q)
                                                                    @php $ans = $p->getAnswerForQuestion($q->id); @endphp
                                                                    @if($ans !== null && $ans !== '')
                                                                        @php $hasAnyAns = true; @endphp
                                                                        <div class="mb-1 text-xs">
                                                                            <span class="badge badge-light border text-muted" title="{{ $q->title }}">{{ Str::limit($q->title, 20) }}:</span>
                                                                            <strong class="text-primary">{{ $ans }}</strong>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                                @if(!$hasAnyAns)
                                                                    <span class="text-muted text-xs font-italic">-</span>
                                                                @endif
                                                            </td>
                                                        @endif
                                                        <td>
                                                            @if($p->booking)
                                                                @if($p->booking->user)
                                                                    {{ $p->booking->user->name }}
                                                                @else
                                                                    Guest ({{ $p->booking->contact_email ?? 'N/A' }})
                                                                @endif
                                                            @else
                                                                N/A
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($isOverallPresent)
                                                                <span class="badge badge-success">Present ({{ $presentDays }}/{{ count($data['event_dates']) ?: 1 }} days)</span>
                                                            @else
                                                                <span class="badge badge-secondary">Absent ({{ $presentDays }}/{{ count($data['event_dates']) ?: 1 }} days)</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($p->certificate_sent_at)
                                                                <span class="badge badge-info" title="{{ $p->certificate_sent_at->format('Y-m-d H:i') }}">
                                                                    {{ $p->certificate_sent_at->diffForHumans() }}
                                                                </span>
                                                            @else
                                                                <span class="text-muted text-xs">Not Sent</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-xs btn-warning btn-edit-participant mr-1" 
                                                                    data-id="{{ $p->id }}" 
                                                                    data-name="{{ $p->name }}" 
                                                                    data-gender="{{ $p->gender ?? 'Male' }}" 
                                                                    data-designation="{{ $p->designation }}" 
                                                                    data-phone="{{ $p->phone }}" 
                                                                    data-email="{{ $p->email }}" 
                                                                    data-dob_bs="{{ $p->dob_bs }}" 
                                                                    data-answers="{{ json_encode($p->custom_answers ?? []) }}"
                                                                    title="Edit Participant Details">
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                                            @if($data['event']->certificate_bg_image && !empty($data['event']->certificate_settings))
                                                                <a href="{{ route('admin.event.certificate.download', ['id' => $data['event']->id, 'participant' => $p->id]) }}" class="btn btn-xs btn-info" title="Download HD Certificate">
                                                                    <i class="fa fa-download"></i>
                                                                </a>
                                                            @else
                                                                <span class="text-muted text-xs">No Template</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </form>
                                    </div>

                                    <!-- All Days Present Tab -->
                                    <div class="tab-pane fade {{ request('status') === '1' ? 'show active' : '' }}" id="tab-alldays" role="tabpanel" aria-labelledby="tab-alldays-tab">
                                        <form action="{{ route('admin.event.certificate.send') }}" method="POST" class="form-send-certificates-alldays">
                                            @csrf
                                            <input type="hidden" name="event_id" value="{{ $data['event']->id }}">
                                            
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h3 class="card-title text-bold mb-0 text-success">
                                                    <i class="fa fa-check-circle"></i> Participants Present All {{ count($data['event_dates']) ?: 1 }} Days
                                                </h3>
                                                <div class="ml-auto">
                                                    @if($data['event']->certificate_bg_image && !empty($data['event']->certificate_settings))
                                                    <button type="submit" class="btn btn-sm btn-success mr-2">
                                                        <i class="fa fa-envelope"></i> Send Certificates
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-info mr-2 btn-bulk-download-alldays">
                                                        <i class="fa fa-download"></i> Bulk Download
                                                    </button>
                                                    @endif
                                                    <a href="{{ route('admin.event.bookings', $data['event']->id) }}" class="btn btn-sm btn-secondary">
                                                        <i class="fa fa-arrow-left"></i> Back to Bookings
                                                    </a>
                                                </div>
                                            </div>
                                            <table class="table table-bordered table-striped text-sm datatable-list">
                                                <thead>
                                                    <tr>
                                                        <th><input type="checkbox" class="check-all-alldays"></th>
                                                        <th>SN</th>
                                                        <th>Participant Name</th>
                                                        <th>Gender</th>
                                                        <th>Cooperative Name</th>
                                                        <th>Phone</th>
                                                        <th>Email</th>
                                                        @if(isset($data['questions']) && $data['questions']->count() > 0)
                                                            <th>Additional Info</th>
                                                        @endif
                                                        <th>Booked By</th>
                                                        <th>Overall Presence</th>
                                                        <th>Cert. Sent</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @php $snAll = 1; @endphp
                                                @foreach($data['participants'] as $p)
                                                    @php
                                                        $attDates = $p->attendance_dates ?: [];
                                                        $totalDatesCount = count($data['event_dates']);
                                                        $presentDays = count(array_intersect($data['event_dates'], $attDates));
                                                        $isOverallPresent = $totalDatesCount > 0 ? ($presentDays === $totalDatesCount) : (bool)$p->is_present;
                                                    @endphp
                                                    @if($isOverallPresent)
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" name="participants[]" value="{{ $p->id }}" class="participant-checkbox">
                                                        </td>
                                                        <td>{{ $snAll++ }}</td>
                                                        <td><strong>{{ $p->name }}</strong></td>
                                                        <td>{{ $p->gender ?? 'N/A' }}</td>
                                                        <td>
                                                            @if($p->booking->is_member)
                                                                @if($p->booking->cooperative)
                                                                    <strong>{{ $p->booking->cooperative->cooperative_name }}</strong>
                                                                    <small class="text-muted d-block">Member No: {{ $p->booking->cooperative->member_no }}</small>
                                                                @else
                                                                    <strong class="text-danger">Member (Deleted Cooperative)</strong>
                                                                @endif
                                                            @else
                                                                <strong>{{ $p->booking->cooperative_name ?: 'N/A' }}</strong> <span class="badge badge-secondary ml-1">Non-member</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $p->phone ?? 'N/A' }}</td>
                                                        <td>{{ $p->email ?? 'N/A' }}</td>
                                                        @if(isset($data['questions']) && $data['questions']->count() > 0)
                                                            <td>
                                                                @php $hasAnyAns = false; @endphp
                                                                @foreach($data['questions'] as $q)
                                                                    @php $ans = $p->getAnswerForQuestion($q->id); @endphp
                                                                    @if($ans !== null && $ans !== '')
                                                                        @php $hasAnyAns = true; @endphp
                                                                        <div class="mb-1 text-xs">
                                                                            <span class="badge badge-light border text-muted" title="{{ $q->title }}">{{ Str::limit($q->title, 20) }}:</span>
                                                                            <strong class="text-primary">{{ $ans }}</strong>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                                @if(!$hasAnyAns)
                                                                    <span class="text-muted text-xs font-italic">-</span>
                                                                @endif
                                                            </td>
                                                        @endif
                                                        <td>
                                                            @if($p->booking)
                                                                @if($p->booking->user)
                                                                    {{ $p->booking->user->name }}
                                                                @else
                                                                    Guest ({{ $p->booking->contact_email ?? 'N/A' }})
                                                                @endif
                                                            @else
                                                                N/A
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-success"><i class="fa fa-check-double"></i> Present ({{ $presentDays }}/{{ $totalDatesCount ?: 1 }} days)</span>
                                                        </td>
                                                        <td>
                                                            @if($p->certificate_sent_at)
                                                                <span class="badge badge-info" title="{{ $p->certificate_sent_at->format('Y-m-d H:i') }}">
                                                                    {{ $p->certificate_sent_at->diffForHumans() }}
                                                                </span>
                                                            @else
                                                                <span class="text-muted text-xs">Not Sent</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <button type="button" class="btn btn-xs btn-warning btn-edit-participant mr-1" 
                                                                    data-id="{{ $p->id }}" 
                                                                    data-name="{{ $p->name }}" 
                                                                    data-gender="{{ $p->gender ?? 'Male' }}" 
                                                                    data-designation="{{ $p->designation }}" 
                                                                    data-phone="{{ $p->phone }}" 
                                                                    data-email="{{ $p->email }}" 
                                                                    data-dob_bs="{{ $p->dob_bs }}" 
                                                                    data-answers="{{ json_encode($p->custom_answers ?? []) }}"
                                                                    title="Edit Participant Details">
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                                            @if($data['event']->certificate_bg_image && !empty($data['event']->certificate_settings))
                                                                <a href="{{ route('admin.event.certificate.download', ['id' => $data['event']->id, 'participant' => $p->id]) }}" class="btn btn-xs btn-info" title="Download HD Certificate">
                                                                    <i class="fa fa-download"></i>
                                                                </a>
                                                            @else
                                                                <span class="text-muted text-xs">No Template</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endif
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </form>
                                    </div>

                                    <!-- Daily Tabs -->
                                    @foreach($data['event_dates'] as $date)
                                    <div class="tab-pane fade" id="tab-{{ $date }}" role="tabpanel" aria-labelledby="tab-{{ $date }}-tab">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h3 class="card-title text-bold mb-0">Participants List - {{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</h3>
                                            <div class="ml-auto">
                                                <a href="{{ route('admin.event.bookings', $data['event']->id) }}" class="btn btn-sm btn-secondary">
                                                    <i class="fa fa-arrow-left"></i> Back to Bookings
                                                </a>
                                            </div>
                                        </div>
                                        <table class="table table-bordered table-striped text-sm datatable-list">
                                            <thead>
                                                <tr>
                                                    <th>SN</th>
                                                    <th>Participant Name</th>
                                                    <th>Gender</th>
                                                    <th>Cooperative Name</th>
                                                    <th>Phone</th>
                                                    <th>Email</th>
                                                    @if(isset($data['questions']) && $data['questions']->count() > 0)
                                                        <th>Additional Info</th>
                                                    @endif
                                                    <th>Presence ({{ \Carbon\Carbon::parse($date)->format('M d') }})</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($data['participants'] as $key => $p)
                                                @php
                                                    $attDates = $p->attendance_dates ?: [];
                                                    $isPresentToday = in_array($date, $attDates);
                                                @endphp
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td><strong>{{ $p->name }}</strong></td>
                                                    <td>{{ $p->gender ?? 'N/A' }}</td>
                                                    <td>
                                                        @if($p->booking->is_member)
                                                        @if($p->booking->cooperative)
                                                            <strong>{{ $p->booking->cooperative->cooperative_name }}</strong>
                                                        @else
                                                            <strong class="text-danger">Member (Deleted Cooperative)</strong>
                                                        @endif
                                                    @else
                                                        <strong>{{ $p->booking->cooperative_name ?: 'N/A' }}</strong>
                                                    @endif
                                                    </td>
                                                    <td>{{ $p->phone ?? 'N/A' }}</td>
                                                    <td>{{ $p->email ?? 'N/A' }}</td>
                                                    @if(isset($data['questions']) && $data['questions']->count() > 0)
                                                        <td>
                                                            @php $hasAnyAns = false; @endphp
                                                            @foreach($data['questions'] as $q)
                                                                @php $ans = $p->getAnswerForQuestion($q->id); @endphp
                                                                @if($ans !== null && $ans !== '')
                                                                    @php $hasAnyAns = true; @endphp
                                                                    <div class="mb-1 text-xs">
                                                                        <span class="badge badge-light border text-muted" title="{{ $q->title }}">{{ Str::limit($q->title, 20) }}:</span>
                                                                        <strong class="text-primary">{{ $ans }}</strong>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                            @if(!$hasAnyAns)
                                                                <span class="text-muted text-xs font-italic">-</span>
                                                            @endif
                                                        </td>
                                                    @endif
                                                    <td>
                                                        @if($isPresentToday)
                                                            <span class="badge badge-success">Present</span>
                                                        @else
                                                            <span class="badge badge-danger">Absent</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-xs btn-warning btn-edit-participant mr-1" 
                                                                data-id="{{ $p->id }}" 
                                                                data-name="{{ $p->name }}" 
                                                                data-gender="{{ $p->gender ?? 'Male' }}" 
                                                                data-designation="{{ $p->designation }}" 
                                                                data-phone="{{ $p->phone }}" 
                                                                data-email="{{ $p->email }}" 
                                                                data-dob_bs="{{ $p->dob_bs }}" 
                                                                data-answers="{{ json_encode($p->custom_answers ?? []) }}"
                                                                title="Edit Participant Details">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                        @if(!$isPresentToday)
                                                            <form action="{{ route('admin.event.booking.participant.mark-present', $p->id) }}" method="POST" class="d-inline mt-1">
                                                                @csrf
                                                                <input type="hidden" name="date" value="{{ $date }}">
                                                                <button type="submit" class="btn btn-xs btn-primary" title="Mark Present on {{ $date }}"><i class="fa fa-user-check"></i></button>
                                                            </form>
                                                        @else
                                                            <span class="text-muted text-xs ml-1" title="Marked Present"><i class="fa fa-check text-success"></i></span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- Edit Participant Modal -->
    <div class="modal fade" id="editParticipantModal" tabindex="-1" role="dialog" aria-labelledby="editParticipantModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form id="editParticipantForm" method="POST" action="">
                    @csrf
                    <div class="modal-header bg-primary text-white p-3">
                        <h5 class="modal-title font-weight-bold" id="editParticipantModalLabel"><i class="fa fa-user-edit mr-2"></i> Edit Participant Details</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-3">
                        <div class="form-group">
                            <label for="edit_p_name" class="font-weight-bold">Participant Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit_p_name" class="form-control" required placeholder="Full Name">
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="edit_p_gender" class="font-weight-bold">Gender <span class="text-danger">*</span></label>
                                <select name="gender" id="edit_p_gender" class="form-control" required>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="edit_p_designation" class="font-weight-bold">Designation</label>
                                <input type="text" name="designation" id="edit_p_designation" class="form-control" placeholder="e.g. Manager, Officer">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="edit_p_phone" class="font-weight-bold">Phone Number</label>
                                <input type="text" name="phone" id="edit_p_phone" class="form-control" placeholder="Mobile Number">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="edit_p_email" class="font-weight-bold">Email Address</label>
                                <input type="email" name="email" id="edit_p_email" class="form-control" placeholder="email@example.com">
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label for="edit_p_dob_bs" class="font-weight-bold">Date of Birth (BS)</label>
                            <input type="text" name="dob_bs" id="edit_p_dob_bs" class="form-control" placeholder="YYYY-MM-DD">
                        </div>

                        @if(isset($data['questions']) && $data['questions']->count() > 0)
                            <hr class="my-3">
                            <h6 class="font-weight-bold text-dark mb-2"><i class="fa fa-question-circle text-primary mr-1"></i> Additional Questions</h6>
                            @foreach($data['questions'] as $q)
                                <div class="form-group mb-2">
                                    <label class="font-weight-bold mb-1">
                                        {{ $q->title }}
                                        @if($q->is_required) <span class="text-danger">*</span> @endif
                                    </label>
                                    @if($q->type === 'text')
                                        <input type="text" name="custom_answers[{{ $q->id }}]" class="form-control form-control-sm edit-q-field" data-qid="{{ $q->id }}" data-type="text" placeholder="Enter {{ strtolower($q->title) }}">
                                    @elseif($q->type === 'number')
                                        <input type="number" name="custom_answers[{{ $q->id }}]" class="form-control form-control-sm edit-q-field" data-qid="{{ $q->id }}" data-type="number" placeholder="Enter number">
                                    @elseif($q->type === 'select')
                                        <select name="custom_answers[{{ $q->id }}]" class="form-control form-control-sm edit-q-field" data-qid="{{ $q->id }}" data-type="select">
                                            <option value="">-- Select --</option>
                                            @foreach($q->parsed_options as $opt)
                                                <option value="{{ $opt }}">{{ $opt }}</option>
                                            @endforeach
                                        </select>
                                    @elseif($q->type === 'radio')
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($q->parsed_options as $optIdx => $opt)
                                                <div class="custom-control custom-radio mr-3">
                                                    <input type="radio" id="edit_q_{{ $q->id }}_{{ $optIdx }}" name="custom_answers[{{ $q->id }}]" value="{{ $opt }}" class="custom-control-input edit-q-field edit-q-radio-{{ $q->id }}" data-qid="{{ $q->id }}" data-type="radio">
                                                    <label class="custom-control-label font-weight-normal" for="edit_q_{{ $q->id }}_{{ $optIdx }}">{{ $opt }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif($q->type === 'checkbox')
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($q->parsed_options as $optIdx => $opt)
                                                <div class="custom-control custom-checkbox mr-3">
                                                    <input type="checkbox" id="edit_q_{{ $q->id }}_{{ $optIdx }}" name="custom_answers[{{ $q->id }}][]" value="{{ $opt }}" class="custom-control-input edit-q-field edit-q-check-{{ $q->id }}" data-qid="{{ $q->id }}" data-type="checkbox">
                                                    <label class="custom-control-label font-weight-normal" for="edit_q_{{ $q->id }}_{{ $optIdx }}">{{ $opt }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="modal-footer bg-light p-2">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm font-weight-bold"><i class="fa fa-save mr-1"></i> Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{asset('backend/assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('backend/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{asset('backend/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('backend/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
    <script src="{{asset('backend/assets/plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{asset('backend/assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{asset('backend/assets/plugins/jszip/jszip.min.js')}}"></script>
    <script src="{{asset('backend/assets/plugins/pdfmake/pdfmake.min.js')}}"></script>
    <script src="{{asset('backend/assets/plugins/pdfmake/vfs_fonts.js')}}"></script>
    <script src="{{asset('backend/assets/plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{asset('backend/assets/plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
    <script>
        $(function () {
            var dt = $(".datatable-list").DataTable({
                "responsive": true, "lengthChange": true, "autoWidth": false, "info": true, "paging": true,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            });
            dt.buttons().container().appendTo('.dataTables_wrapper .col-md-6:eq(0)');

            // Question filter datalist autocomplete
            function updateFilterOptions() {
                var selected = $('#filter_question_id').find(':selected');
                var options = selected.data('options');
                var datalist = $('#answer_options_list');
                datalist.empty();
                if (options && Array.isArray(options)) {
                    options.forEach(function(opt) {
                        datalist.append($('<option>').attr('value', opt));
                    });
                }
            }
            $('#filter_question_id').on('change', updateFilterOptions);
            updateFilterOptions();

            $('.check-all-alldays, #check-all-participants').on('change', function() {
                var form = $(this).closest('form');
                form.find('input[type="checkbox"].participant-checkbox').prop('checked', this.checked);
            });
            
            $('.datatable-list tbody').on('change', 'input[type="checkbox"]', function(){
                if(!this.checked){
                   var el = $(this).closest('form').find('.check-all-alldays, #check-all-participants').get(0);
                   if(el && el.checked && ('indeterminate' in el)){
                      el.indeterminate = true;
                   }
                }
            });

            $(document).on('click', '.btn-edit-participant', function() {
                let btn = $(this);
                let id = btn.data('id');
                let name = btn.data('name') || '';
                let gender = btn.data('gender') || 'Male';
                let designation = btn.data('designation') || '';
                let phone = btn.data('phone') || '';
                let email = btn.data('email') || '';
                let dob_bs = btn.data('dob_bs') || '';
                let rawAnswers = btn.data('answers');
                let answers = {};
                if (typeof rawAnswers === 'object' && rawAnswers !== null) {
                    answers = rawAnswers;
                } else if (typeof rawAnswers === 'string') {
                    try { answers = JSON.parse(rawAnswers); } catch(e) { answers = {}; }
                }

                let url = '{{ route("admin.event.booking.participant.update", ":id") }}'.replace(':id', id);
                $('#editParticipantForm').attr('action', url);
                $('#edit_p_name').val(name);
                $('#edit_p_gender').val(gender);
                $('#edit_p_designation').val(designation);
                $('#edit_p_phone').val(phone);
                $('#edit_p_email').val(email);
                $('#edit_p_dob_bs').val(dob_bs);

                // Populate custom answers
                $('.edit-q-field').each(function() {
                    let field = $(this);
                    let qid = field.data('qid');
                    let type = field.data('type');
                    let val = (answers[qid] !== undefined) ? answers[qid] : (answers[String(qid)] !== undefined ? answers[String(qid)] : '');

                    if (type === 'text' || type === 'number' || type === 'select') {
                        field.val(val);
                    } else if (type === 'radio') {
                        field.prop('checked', field.val() == val);
                    } else if (type === 'checkbox') {
                        let arr = Array.isArray(val) ? val : (val ? [val] : []);
                        field.prop('checked', arr.includes(field.val()));
                    }
                });

                $('#editParticipantModal').modal('show');
            });

            $('#form-send-certificates, .form-send-certificates-alldays').on('submit', async function(e) {
                e.preventDefault();
                var form = this;
                var selectedIds = [];
                
                $(form).find('input[type="checkbox"]:checked.participant-checkbox').each(function(){
                    if (this.value && this.value !== 'on') {
                        selectedIds.push(this.value);
                    }
                });

                if (selectedIds.length === 0) {
                    alert('Please select at least one participant.');
                    return;
                }

                if (!confirm('Are you sure you want to send certificates to ' + selectedIds.length + ' participants? This will be processed in background chunks to prevent timeouts.')) {
                    return;
                }

                let btn = $(form).find('button[type="submit"]');
                let originalText = btn.html();
                btn.prop('disabled', true);
                
                let chunkSize = 5;
                let chunks = [];
                for (let i = 0; i < selectedIds.length; i += chunkSize) {
                    chunks.push(selectedIds.slice(i, i + chunkSize));
                }

                let totalSent = 0;
                let hasError = false;

                for (let i = 0; i < chunks.length; i++) {
                    btn.html('<i class="fa fa-spinner fa-spin"></i> Sending chunk ' + (i+1) + '/' + chunks.length + '...');
                    
                    try {
                        let response = await $.ajax({
                            url: $(form).attr('action'),
                            type: 'POST',
                            data: {
                                _token: $(form).find('input[name="_token"]').val(),
                                event_id: $(form).find('input[name="event_id"]').val(),
                                participants: chunks[i]
                            }
                        });
                        
                        if (!response.success) {
                            alert('Error in chunk ' + (i+1) + ': ' + response.message);
                            hasError = true;
                            break;
                        }
                        totalSent += chunks[i].length;
                    } catch (err) {
                        alert('Network error in chunk ' + (i+1) + '. Process stopped.');
                        hasError = true;
                        break;
                    }
                }

                btn.html(originalText).prop('disabled', false);
                
                if (!hasError) {
                    alert('Success! Certificates are currently being dispatched to ' + totalSent + ' participants. The page will now reload.');
                    window.location.reload();
                }
            });

            $('#btn-bulk-download, .btn-bulk-download-alldays').click(function() {
                var formParent = $(this).closest('form');
                var selectedIds = [];
                formParent.find('input[type="checkbox"]:checked.participant-checkbox').each(function(){
                    if (this.value && this.value !== 'on') {
                        selectedIds.push(this.value);
                    }
                });

                if (selectedIds.length === 0) {
                    alert('Please select at least one participant.');
                    return;
                }

                var form = $('<form action="{{ route('admin.event.certificate.bulk-download') }}" method="POST"></form>');
                form.append('<input type="hidden" name="_token" value="{{ csrf_token() }}">');
                form.append('<input type="hidden" name="event_id" value="{{ $data['event']->id }}">');
                selectedIds.forEach(function(id) {
                    form.append('<input type="hidden" name="participants[]" value="' + id + '">');
                });
                $('body').append(form);
                form.submit();
                form.remove();
            });
        });
    </script>
@endsection
