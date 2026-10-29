@extends('website.common.layout')
@section('title', "AGM Nomination Form")

@section('content')
    <!-- Unified Page Heading & Breadcrumb -->
    @include('website.common.page-heading')

    <div class="container-xl my-4 pb-5">
        <div class="bg-white rounded-4 border p-4 p-md-5 shadow-sm">
            <div class="mb-4 pb-2 border-bottom">
                <span class="statistics-eyebrow">NOMINATION</span>
                <h2 class="fw-bold mb-1" style="color: var(--public-ink); font-size: 1.55rem;">प्रतिनिधी मनोनयन फारम (AGM Nomination)</h2>
                <p class="text-muted small mb-0">Please fill out all the required fields marked with an asterisk (*).</p>
            </div>
      
            <form id="agm-form" action="{{ route('agmform.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="form-group col-md-4 mb-3">
                        <label for="name" class="font-weight-bold text-dark small">Representative Name (English) *</label>
                        <input type="text" class="form-control rounded-3" id="name" name="name" value="{{ old('name') }}" placeholder="Full name in English" required>
                        @error('name')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        <label for="nepali_name" class="font-weight-bold text-dark small">प्रतिनिधीको नाम (देवनागरीमा) *</label>
                        <input type="text" class="form-control rounded-3" id="nepali_name" name="nepali_name" value="{{ old('nepali_name') }}" placeholder="नेपालीमा पूरा नाम">
                        @error('nepali_name')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        <label for="gender" class="font-weight-bold text-dark small">Gender / लिङ्ग *</label>
                        <select class="form-control rounded-3" id="gender" name="gender" required>
                            <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        <label for="saccos_union" class="font-weight-bold text-dark small">Name of SACCOS/Union / संघसंस्थाको नाम *</label>
                        <input type="text" class="form-control rounded-3" id="saccos_union" name="saccos_union" value="{{ old('saccos_union') }}" placeholder="Organization / Union name" required>
                        @error('saccos_union')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        <label for="cooperative_name" class="font-weight-bold text-dark small">Cooperative Name / सहकारीको नाम *</label>
                        <input type="text" class="form-control rounded-3" id="cooperative_name" name="cooperative_name" value="{{ old('cooperative_name') }}" placeholder="Cooperative name" required>
                        @error('cooperative_name')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        <label for="post" class="font-weight-bold text-dark small">Post in SACCOS / बहाल रहेको पद *</label>
                        <select class="form-control rounded-3" id="post" name="post" required>
                            @foreach(config('helper.post') as $post)
                                <option value="{{ $post }}" {{ old('post') == $post ? 'selected' : '' }}>{{ $post }}</option>
                            @endforeach
                        </select>
                        @error('post')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-4 mb-3">
                        <label for="email" class="font-weight-bold text-dark small">Email Address / इमेल ठेगाना *</label>
                        <input type="email" class="form-control rounded-3" id="email" name="email" value="{{ old('email') }}" placeholder="example@email.com" required>
                        @error('email')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group col-md-4 mb-3">
                        <label for="district" class="font-weight-bold text-dark small">District / जिल्ला *</label>
                        <select class="form-control rounded-3" id="district" name="district_id" required onchange="fetchMunicipalities(this.value)">
                            @forelse($data['districts'] as $district)
                                <option value="{{ $district->id }}" {{ old('district') == $district->id || 29 == $district->id ? 'selected' : '' }}>{{ $district->name}}</option>
                            @empty
                            @endforelse
                        </select>
                        @error('district_id')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        <label for="address" class="font-weight-bold text-dark small">Local Level (Municipality) / स्थानीय तह *</label>
                        <select class="form-control rounded-3" id="address" name="municipality_id" required>
                            <option value="">Select Municipality</option>
                        </select>
                        @error('address')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        <label for="ward_no" class="font-weight-bold text-dark small">Ward No / वडा नं. *</label>
                        <input type="number" class="form-control rounded-3" id="ward_no" name="ward_no" value="{{ old('ward_no') }}" placeholder="Ward number" required>
                        @error('ward_no')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        <label for="membership_no" class="font-weight-bold text-dark small">Membership No / सदस्यता नं. *</label>
                        <input type="text" class="form-control rounded-3" id="membership_no" name="membership_no" value="{{ old('membership_no') }}" placeholder="Membership number" required>
                        @error('membership_no')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-4 mb-3">
                        <label for="mobile_no" class="font-weight-bold text-dark small">Mobile No / सम्पर्क नं. *</label>
                        <input type="text" class="form-control rounded-3" id="mobile_no" name="mobile_no" value="{{ old('mobile_no') }}" required minlength="8" maxlength="15" placeholder="98XXXXXXXX">
                        @error('mobile_no')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6 mb-3">
                        <label for="nomination_form" class="font-weight-bold text-dark small">Approved Nomination Form / मनोनयन फारम (Max: 5MB) *</label>
                        <input type="file" class="form-control-file p-2 border rounded-3 bg-light w-100" id="nomination_form" name="main_nomination_form" required>
                        @error('nomination_form')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group col-md-6 mb-3">
                        <label for="photo" class="font-weight-bold text-dark small">Passport Size Photo / पासपोर्ट साइज फोटो (Max: 5MB, optional)</label>
                        <input type="file" class="form-control-file p-2 border rounded-3 bg-light w-100" id="photo" name="main_photo" accept="image/jpeg,image/png" onchange="previewImage(event)">
                        <img id="photo-preview" src="#" alt="Passport Size Photo preview" style="display:none; margin-top:10px; max-width:100px; border-radius: 8px; border: 1px solid #ddd;">
                        @error('main_photo')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="mt-4 pt-2">
                    <button type="submit" class="btn btn-primary px-5 py-2 font-weight-bold rounded-pill shadow-sm" style="background: var(--public-green); border-color: var(--public-green);">
                        <i class="fas fa-paper-plane mr-2"></i> Submit Nomination
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

    <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('photo-preview');
                output.src = reader.result;
                output.style.display = 'block';
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
    <script>
        $(document).ready(function() {
            var districtId = $('#district').val();
            if (districtId) {
                fetchMunicipalities(districtId);
            }
        });
        function fetchMunicipalities(districtId) {
            $.ajax({
                url:"{{ route('municipalities.get') }}",
                method: 'GET',
                data:{
                    _token: '{{ csrf_token() }}',
                    districtId: districtId,
                },
                success: function(data) {
                    let municipalitySelect = $('#address');
                    municipalitySelect.html('<option value="">Select Municipality</option>');
                    data.forEach(function(municipality) {
                        let option = $('<option></option>').val(municipality.id).text(municipality.name);
                        municipalitySelect.append(option);
                    });
                },
                error: function(error) {
                    console.error('Error fetching municipalities:', error);
                }
            });
        }
    </script>
@endsection
