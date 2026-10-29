<div class="card-body">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="name">Name <span class="red">*</span> </label>
                {!! Form::text('name', null,[
                      'class'=> $errors->has('name')?'form-control is-invalid':'form-control',
                      'placeholder'=>'Eg. Basic Saving and Credit Co-operative Ltd.',
                      ]) !!}
                @error('name')
                    <span class="help-block invalid-feedback">
                        <strong> {{ $message }} </strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="name">BOD Chief <span class="red">*</span></label>
                {!! Form::text('bod_chief', null,[
                      'class'=> $errors->has('bod_chief')?'form-control is-invalid':'form-control',
                      'placeholder'=>'Eg. Board of Director Chief Name..',
                      ]) !!}
                @error('bod_chief')
                <span class="help-block invalid-feedback">
                        <strong> {{ $message }} </strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="name">Manager <span class="red">*</span></label>
                {!! Form::text('manager', null,[
                      'class'=> $errors->has('manager')?'form-control is-invalid':'form-control',
                      'placeholder'=>'Eg. Manager Name..',
                      ]) !!}
                @error('manager')
                <span class="help-block invalid-feedback">
                        <strong> {{ $message }} </strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="name">Contact No <span class="red">*</span></label>
                {!! Form::text('contact_no', null,[
                      'class'=> $errors->has('contact_no')?'form-control is-invalid':'form-control',
                      'placeholder'=>'Eg. +97798184050**',
                      ]) !!}
                @error('contact_no')
                <span class="help-block invalid-feedback">
                        <strong> {{ $message }} </strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label>
                    District
                    <span class="red">*</span>
                </label>
                <select name="district_id"
                        class="{{$errors->has('district_id')?'form-control district_id is-invalid':'form-control district_id'}}"
                        id="district_id" tabindex="13">
                    @if(!old('district_id'))
                        <option selected disabled>--SELECT DISTRICT--</option>
                    @endif
                    @foreach($data['districts'] as $district)
                        <option value="{{ $district->id }}"
                            {{old('district_id') == $district->id || isset( $data['row']) && $data['row']->district_id
                                == $district->id ? 'selected':''}}>
                            {{$district->name}}
                        </option>
                    @endforeach
                </select>
                @error('district_id')
                <span class="help-block invalid-feedback">
                                     <strong> {{ $message }} </strong>
                                </span>
                @enderror
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label>
                    Municipality
                    <span class="red">*</span>
                </label>
                <select name="municipality_id"
                        class="{{$errors->has('municipality_id')?'form-control municipality_id is-invalid':'form-control municipality_id'}}"
                        id="municipality_id" tabindex="14">
                    @if(!old('municipality_id'))
                        <option selected disabled>--SELECT MUNICIPALITY--</option>
                    @endif
                    @foreach($data['municipalities'] as $municipality)
                        <option value="{{ $municipality->id }}"
                                {{old('municipality_id') == $municipality->id
                        || isset( $data['row']) && $data['row']->municipality_id == $municipality->id ?'selected':''}}
                                data-district="{{ $municipality->district_id }}">
                            {{$municipality->name}}
                        </option>
                    @endforeach
                </select>
                @error('municipality_id')
                <span class="help-block invalid-feedback">
                                     <strong> {{ $message }} </strong>
                                </span>
                @enderror
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="name">PhoneNo.</label>
                {!! Form::text('phone', null,[
                      'class'=> $errors->has('phone')?'form-control is-invalid':'form-control',
                      'placeholder'=>'Eg.9841000000',
                      ]) !!}
                @error('phone')
                <span class="help-block invalid-feedback">
                        <strong> {{ $message }} </strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="name">Email*</label>
                {!! Form::email('email', null,[
                      'class'=> $errors->has('email')?'form-control is-invalid':'form-control',
                      'placeholder'=>'Eg. abc@gmail.com',
                      ]) !!}
                @error('email')
                <span class="help-block invalid-feedback">
                        <strong> {{ $message }} </strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="name">Website*</label>
                {!! Form::text('website', null,[
                      'class'=> $errors->has('website')?'form-control is-invalid':'form-control',
                      'placeholder'=>'Eg. xyz.com.np',
                      ]) !!}
                @error('website')
                <span class="help-block invalid-feedback">
                        <strong> {{ $message }} </strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="name">Total Shareholder*</label>
                {!! Form::number('total_shareholder', null,[
                      'class'=> $errors->has('total_shareholder')?'form-control is-invalid':'form-control',
                      'placeholder'=>'Eg. 514',
                      ]) !!}
                @error('total_shareholder')
                <span class="help-block invalid-feedback">
                        <strong> {{ $message }} </strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="name">Join Year*</label>
                {!! Form::text('join_year', null,[
                      'class'=> $errors->has('join_year')?'form-control is-invalid':'form-control',
                      'placeholder'=>'Eg. 2028',
                      ]) !!}
                @error('join_year')
                <span class="help-block invalid-feedback">
                        <strong> {{ $message }} </strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="name">PAN Number*</label>
                {!! Form::text('pan_no', null,[
                      'class'=> $errors->has('pan_no')?'form-control is-invalid':'form-control',
                      'placeholder'=>'Eg. Enter PAN NO',
                      ]) !!}
                @error('pan_no')
                <span class="help-block invalid-feedback">
                        <strong> {{ $message }} </strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="name">Registration Number*</label>
                {!! Form::text('reg_no', null,[
                      'class'=> $errors->has('reg_no')?'form-control is-invalid':'form-control',
                      'placeholder'=>'Eg. Darta No..',
                      ]) !!}
                @error('registratioreg_non_number')
                <span class="help-block invalid-feedback">
                        <strong> {{ $message }} </strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="title">Status</label>
                {!! Form::select('status',['1' => 'Active', '0' => 'InActive'], null,[
                     'class'=> $errors->has('status')?'form-control is-invalid':'form-control',

                ]) !!}
                @error('status')
                <span class="help-block invalid-feedback">
                     <strong> {{ $message }} </strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="name">Association*</label>
                {!! Form::select('association',['1' => 'Associated', '0' => 'NotAssociated'], null,[
                     'class'=> $errors->has('status')?'form-control is-invalid':'form-control',

                ]) !!}
                @error('association')
                <span class="help-block invalid-feedback">
                        <strong> {{ $message }} </strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label for="name">Description*</label>
                {!! Form::textarea('description', null,[
                      'class'=> $errors->has('description')?'form-control summernote is-invalid':'form-control summernote',
                      'placeholder'=>'Eg.Introduction',
                      ]) !!}
                @error('description')
                <span class="help-block invalid-feedback">
                        <strong> {{ $message }} </strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-12">
            <div class="card card-gray-dark">
                <div class="card-header">
                    Photo
                    {{ $errors }}
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="photo_upload">Upload Photo</label>
                            {!! Form::file('main_image',[
                                'class'=> $errors->has('main_image')?'form-control btn btn-sm is-invalid':'form-control btn btn-sm',
                                'id'=>'photo',
                                'onchange'=>"showPreview(event);",
                                'accept'=>'image/png, image/gif, image/jpeg'
                             ]) !!}
                            @error('main_image')
                            <span class="help-block invalid-feedback">
                                    <strong> {{ $message }} </strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="photo">Existing Image</label>
                            <div>
                                <img width="150" height="150" src="{{ ViewHelper::getImagePath(@$_folder,@$data['row']->photo) }}" class="img-fluid" id="file-ip-1-preview">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- /.card-body -->
<div class="card-footer">
    <button type="submit" class="btn btn-primary">{{$button}}</button>
</div>

@section('formJs')
    <script>
        function showPreview(event){
            if(event.target.files.length > 0){
                var src = URL.createObjectURL(event.target.files[0]);
                var preview = document.getElementById("file-ip-1-preview");
                var photo = document.getElementById("photo");
                preview.src = src;
                photo.value=src;
                preview.style.display = "block";

            }
        }
        $(function (){
            //initailize the vairables
            let
                $dsc = $('#district_id'),
                $mun = $('#municipality_id');

            $('body').on("change", "#district_id", function() {
                var selectedDistrict = $(this).val();
                $("#municipality_id").find("option").each(function() {
                    if ($(this).data("district") == selectedDistrict) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            $('body').on("change", "#municipality_id", function() {
                var selectedDistrict = $(this).val();
                var districtSelected = $(this).find('option:selected').attr('data-district');
                $dsc.val(districtSelected)
            });
        });
    </script>
@endsection
