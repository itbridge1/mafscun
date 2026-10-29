<div class="card-body">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="name">Representative Name In English /प्रतिनिधीको नाम *</label>
                {!! Form::text('name', old('name'), [
                    'class'=> $errors->has('name') ? 'form-control is-invalid' : 'form-control',
                    'placeholder' => 'Eg. Karuna',
                    'required' => true
                ]) !!}
                @error('name')
                    <span class="help-block invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="nepali_name">प्रतिनिधीको नाम देवनागरीमा*</label>
                {!! Form::text('nepali_name', old('nepali_name'), [
                    'class'=> $errors->has('nepali_name') ? 'form-control is-invalid' : 'form-control',
                 ]) !!}
                @error('nepali_name')
                    <span class="help-block invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="gender">Gender/लिंग *</label>
                {!! Form::select('gender', ['male' => 'Male', 'female' => 'Female', 'other' => 'Other'], old('gender'), [
                    'class'=> $errors->has('gender') ? 'form-control is-invalid' : 'form-control',
                    'required' => true
                ]) !!}
                @error('gender')
                    <span class="help-block invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="saccos_union">Name of Saccos/Union/संघसंस्थाको नाम *</label>
                {!! Form::text('saccos_union', old('saccos_union'), [
                    'class'=> $errors->has('saccos_union') ? 'form-control is-invalid' : 'form-control',
                    'required' => true
                ]) !!}
                @error('saccos_union')
                    <span class="help-block invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="post">Post in Representative SACCOS/DU / प्रतिनिधित्व गरेको संघसस्थामा बहाल रहेको पद*</label>
                {!! Form::select('post', config('helper.post'), old('post'), [
                    'class'=> $errors->has('post') ? 'form-control is-invalid' : 'form-control',
                    'required' => true
                ]) !!}
                @error('post')
                    <span class="help-block invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>


        <div class="col-md-4">
            <div class="form-group">
                <label for="email">Email Address / इमेल ठेगाना *</label>
                {!! Form::email('email', old('email'), [
                    'class'=> $errors->has('email') ? 'form-control is-invalid' : 'form-control',
                    'placeholder' => 'Eg. example@example.com',
                    'required' => true
                ]) !!}
                @error('email')
                    <span class="help-block invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>


        <div class="col-md-4">
            <div class="form-group">
                <label for="district">District/जिल्ला *</label>
                {!! Form::select('district_id', $data['districts']->pluck('name', 'id'), old('district_id'), [
                    'class'=> $errors->has('district_id') ? 'form-control is-invalid' : 'form-control',
                    'required' => true,
                    'id'=>'district',
                    'onchange' => 'fetchMunicipalities(this.value)'
                ]) !!}
                @error('district_id')
                    <span class="help-block invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="address">Local level(Municipality) / स्थानीय तह(पालिकाको नाम) *</label>
                {!! Form::select('municipality_id', [], old('municipality_id'), [
                    'class'=> $errors->has('municipality_id') ? 'form-control is-invalid' : 'form-control',
                    'required' => true,
                    'id' => 'address'
                ]) !!}
                @error('municipality_id')
                    <span class="help-block invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="ward_no">Ward No /वडा नं. *</label>
                {!! Form::number('ward_no', old('ward_no'), [
                    'class'=> $errors->has('ward_no') ? 'form-control is-invalid' : 'form-control',
                    'required' => true
                ]) !!}
                @error('ward_no')
                    <span class="help-block invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="membership_no">Membership No in Bagmati Proscun /बागमती प्रोस्कूनमा सदस्यता नं.*</label>
                {!! Form::text('membership_no', old('membership_no'), [
                    'class'=> $errors->has('membership_no') ? 'form-control is-invalid' : 'form-control',
                    'required' => true
                ]) !!}
                @error('membership_no')
                    <span class="help-block invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="mobile_no">Mobile No of Representative /प्रतिनिधीको सम्पर्क नं.*</label>
                {!! Form::text('mobile_no', old('mobile_no'), [
                    'class'=> $errors->has('mobile_no') ? 'form-control is-invalid' : 'form-control',
                    'required' => true
                ]) !!}
                @error('mobile_no')
                    <span class="help-block invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
        <div class="col-md-4">
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="main_nomination_form">Upload Duty Filled & Approved Representative Nomination Form / मनोनयन फारम अपलोड गर्नुहोस्*</label>
                @if(isset($data['row']) && $data['row']->nomination_form)
                    <div class="mb-2">
                        <a href="{{ ViewHelper::getImagePath('AgmForm/NominationForm', $data['row']->nomination_form) }}" target="_blank" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ ViewHelper::getImagePath('AgmForm/NominationForm', $data['row']->nomination_form) }}" download class="btn btn-success btn-sm">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </div>
                @endif
                {!! Form::file('main_nomination_form', [
                    'class'=> $errors->has('main_nomination_form') ? 'form-control-file is-invalid' : 'form-control-file',
                    'required' => (isset($data['row']) && $data['row']->nomination_form) ?false:true
                ]) !!}
                @error('main_nomination_form')
                    <span class="help-block invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="photo">Upload Passport Size Photo for ID Card/परिचयपत्रको लागि पासपोर्ट साईजको फोटो अपलोड गर्नुहोस् *</label>
                @if(isset($data['row']) && $data['row']->photo)
                    <div class="mb-2">
                        <img src="{{ asset('images/AgmForm/PassportSize/'. $data['row']->photo) }}" alt="{{ asset('images/AgmForm/PassportSize/'. $data['row']->photo) }}" style="max-width:100px;">
                    </div>
                @endif
                {!! Form::file('main_photo', [
                    'class'=> $errors->has('main_photo') ? 'form-control-file is-invalid' : 'form-control-file',
                    'required' => (isset($data['row']) && $data['row']->photo) ?false:true,
                    'onchange' => 'previewImage(event)'
                ]) !!}
                @error('main_photo')
                    <span class="help-block invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <img id="photo-preview" src="#" alt="Passport Size Photo" style="display:none; margin-top:10px; max-width:100px;">
            </div>
        </div>

        {{-- <div class="col-md-4">
            <div class="form-group">
                <label for="status">Status</label>
                {!! Form::select('status', ['RESPONDED' => 'RESPONDED', 'NOT RESPONDED' => 'NOT RESPONDED'], old('status'), [
                    'class'=> $errors->has('status') ? 'form-control is-invalid' : 'form-control',
                    'required' => true,
                    'data-bootstrap-switch' => true,
                    'id' => 'status',
                ]) !!}
             
                @error('status')
                    <span class="help-block invalid-feedback">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div> --}}
    </div>
</div>
<!-- /.card-body -->
<div class="card-footer">
    <button type="submit" class="btn btn-primary">{{$button}}</button>
</div>

@section('formJs')
    <!-- Bootstrap Switch -->
    <script src="{{ asset('backend/assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <script>
        $(function (){
                     $("input[data-bootstrap-switch]").each(function(){
                        $(this).bootstrapSwitch('state', $(this).prop('checked'));
                    });
         });

        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('photo-preview');
                output.src = reader.result;
                output.style.display = 'block';
            };
            reader.readAsDataURL(event.target.files[0]);
        }
        $(document).ready(function() {
            districtId =   $('#district').val();
            municipalityId = '{{ old('municipality_id') ?? ($data['row'] ? ($data['row']->municipality_id ?? null) : null) }}';
            fetchMunicipalities(districtId, municipalityId);
        });
        function fetchMunicipalities(districtId, municipalityId = null) {
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
                let option = $('<option ></option>').val(municipality.id).text(municipality.name);
                if (municipalityId && municipality.id == municipalityId) {
                    option.attr('selected', 'selected');
                }
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
