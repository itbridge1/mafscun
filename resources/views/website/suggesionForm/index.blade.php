@extends('website.common.layout')
@section('title', "Suggestion Form")

@section('content')
    <!-- Unified Page Heading & Breadcrumb -->
    @include('website.common.page-heading')

    <div class="container-xl my-4 pb-5">
        <div class="bg-white rounded-4 border p-4 p-md-5 shadow-sm">
            <div class="mb-4 pb-2 border-bottom">
                <span class="statistics-eyebrow">FEEDBACK</span>
                <h2 class="fw-bold mb-1" style="color: var(--public-ink); font-size: 1.55rem;">सुझाव फारम (Suggestion & Feedback Form)</h2>
                <p class="text-muted small mb-0">तपाईंको अमूल्य सल्लाह तथा सुझाव हाम्रो मार्गदर्शन हो ।</p>
            </div>
    
            <form id="suggestion-form" action="{{ route('suggestionform.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="form-group col-md-4 mb-3">
                        <label for="name" class="font-weight-bold text-dark small">सुझाव दिने व्यक्तिको नाम (स्वेच्छिक):</label>
                        <input type="text" class="form-control rounded-3" id="name" name="name" value="{{ old('name') }}" placeholder="तपाईंको नाम">
                        @error('name')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
        
                    <div class="form-group col-md-4 mb-3">
                        <label for="organization" class="font-weight-bold text-dark small">संघ / संस्थाको नाम (स्वेच्छिक):</label>
                        <input type="text" class="form-control rounded-3" id="organization" name="organization" value="{{ old('organization') }}" placeholder="संस्थाको नाम">
                        @error('organization')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
        
                    <div class="form-group col-md-4 mb-3">
                        <label for="address" class="font-weight-bold text-dark small">ठेगाना (स्वेच्छिक):</label>
                        <input type="text" class="form-control rounded-3" id="address" name="address" value="{{ old('address') }}" placeholder="ठेगाना">
                        @error('address')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
        
                    <div class="form-group col-md-12 mb-3">
                        <label class="font-weight-bold text-dark small d-block mb-2">साधारण सभाको व्यवस्थापन कस्तो लाग्यो? *</label>
                        <div class="d-flex flex-wrap gap-3">
                            <label class="btn btn-outline-light border text-dark py-2 px-3 rounded-3 mr-2 mb-2 font-weight-normal">
                                <input type="radio" name="management_feedback" value="very_good" {{ old('management_feedback') == 'very_good' ? 'checked' : '' }} class="mr-1"> उत्तम
                            </label>
                            <label class="btn btn-outline-light border text-dark py-2 px-3 rounded-3 mr-2 mb-2 font-weight-normal">
                                <input type="radio" name="management_feedback" value="good" {{ old('management_feedback') == 'good' ? 'checked' : '' }} class="mr-1"> राम्रो
                            </label>
                            <label class="btn btn-outline-light border text-dark py-2 px-3 rounded-3 mr-2 mb-2 font-weight-normal">
                                <input type="radio" name="management_feedback" value="satisfactory" {{ old('management_feedback') == 'satisfactory' ? 'checked' : '' }} class="mr-1"> सन्तोषजनक
                            </label>
                            <label class="btn btn-outline-light border text-dark py-2 px-3 rounded-3 mr-2 mb-2 font-weight-normal">
                                <input type="radio" name="management_feedback" value="needs_improvement" {{ old('management_feedback') == 'needs_improvement' ? 'checked' : '' }} class="mr-1"> सुधार गर्नुपर्ने
                            </label>
                        </div>
                        @error('management_feedback')
                            <span class="text-danger small d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-12 mb-3">
                        <label class="font-weight-bold text-dark small d-block mb-2">साधारण सभाको सूचना समयमै प्राप्त भएको थियो वा थिएन ? *</label>
                        <div class="d-flex flex-wrap gap-3">
                            <label class="btn btn-outline-light border text-dark py-2 px-3 rounded-3 mr-2 mb-2 font-weight-normal">
                                <input type="radio" name="notification" value="yes" {{ old('notification') == 'yes' ? 'checked' : '' }} class="mr-1"> थियो
                            </label>
                            <label class="btn btn-outline-light border text-dark py-2 px-3 rounded-3 mr-2 mb-2 font-weight-normal">
                                <input type="radio" name="notification" value="no" {{ old('notification') == 'no' ? 'checked' : '' }} class="mr-1"> थिएन
                            </label>
                        </div>
                        @error('notification')
                            <span class="text-danger small d-block">{{ $message }}</span>
                        @enderror
                    </div>
        
                    <div class="form-group col-md-12 mb-3">
                        <label for="report-feedback" class="font-weight-bold text-dark small">वार्षिक साधारण सभाको प्रतिवेदन माथि केही भन्नु छ?</label>
                        <textarea class="form-control rounded-3" id="report-feedback" name="report_feedback" rows="3" placeholder="आफ्नो विचार यहाँ लेख्नुहोस्...">{{ old('report_feedback') }}</textarea>
                        @error('report_feedback')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
        
                    <div class="form-group col-md-12 mb-3">
                        <label for="policy-feedback" class="font-weight-bold text-dark small">संघको वार्षिक नीति तथा कार्यक्रममा तपाईंको सुझाव केहि छ?</label>
                        <textarea class="form-control rounded-3" id="policy-feedback" name="policy_feedback" rows="3" placeholder="आफ्नो सुझाव यहाँ लेख्नुहोस्...">{{ old('policy_feedback') }}</textarea>
                        @error('policy_feedback')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
        
                    <div class="form-group col-md-12 mb-3">
                        <label for="other-suggestions" class="font-weight-bold text-dark small">संघलाई तपाईंको अन्य कुनै सुझाव छ?</label>
                        <textarea class="form-control rounded-3" id="other-suggestions" name="other_suggestions" rows="3" placeholder="थप सुझावहरू...">{{ old('other_suggestions') }}</textarea>
                        @error('other_suggestions')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
        
                <div class="mt-4 pt-2">
                    <button type="submit" class="btn btn-primary px-5 py-2 font-weight-bold rounded-pill shadow-sm" style="background: var(--public-green); border-color: var(--public-green);">
                        <i class="fas fa-paper-plane mr-2"></i> सुझाव पठाउनुहोस्
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
    
@section('js')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(\Illuminate\Support\Facades\Session::has('success_message'))
            Swal.fire({
                icon: 'success',
                title: 'Thank you',
                text: '{{ \Illuminate\Support\Facades\Session::get('success_message') }}',
                footer: '<a href="{{ route('index') }}"><i class="fa fa-home"></i> Go to Home Page</a>'
            })
        @endif
        @if(\Illuminate\Support\Facades\Session::has('error_message'))
            Swal.fire({
                icon: 'error',
                title: 'Sorry',
                text: '{{ \Illuminate\Support\Facades\Session::get('error_message') }}',
                footer: '<a href="{{ route('index') }}"><i class="fa fa-home"></i> Go to Home Page</a>'
            })
        @endif
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
