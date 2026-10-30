<div class="card-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Message Title (सन्देशको शीर्षक)*</label>
                {!! Form::text('title', null,[
                      'class'=> $errors->has('title')?'form-control is-invalid':'form-control',
                      'placeholder'=>'Eg. अध्यक्षको सन्देश / Chairman\'s Message',
                      'required' => 'required'
                      ]) !!}
                @error('title')
                    <span class="help-block invalid-feedback">
                        <strong> {{ $message }} </strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="sub_title">Designation / Author (पद वा व्यक्तिको नाम)</label>
                {!! Form::text('sub_title', null,[
                     'class'=> $errors->has('sub_title')?'form-control is-invalid':'form-control',
                     'placeholder'=>'Eg. कल्पना श्रेष्ठ (अध्यक्ष) / प्रमुख कार्यकारी अधिकृत',
                ]) !!}
                @error('sub_title')
                <span class="help-block invalid-feedback">
                     <strong> {{ $message }} </strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="col-md-12">
            <div class="card card-gray-dark">
                <div class="card-header">
                    Dignitary / Author Photo (फोटो)
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
                               <img width="120" height="135" style="object-fit:cover; border-radius:8px;" src="{{ ViewHelper::getImagePath('aboutUs', @$data['row']->photo) }}" class="img-fluid" id="file-ip-1-preview">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label for="description">Message Content (सन्देशको व्यहोरा)</label>
                {!! Form::textarea('description', null,[
                     'class'=> $errors->has('description')?'form-control is-invalid summernote':'form-control summernote',
                     'placeholder'=>'सन्देशको विस्तृत विवरण...',
                ]) !!}
                @error('description')
                <span class="help-block invalid-feedback">
                     <strong> {{ $message }} </strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="status">Status</label>
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

        <input type="hidden" name="is_message" value="1">
    </div>
</div>
<!-- /.card-body -->
<div class="card-footer">
    <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> {{$button}}</button>
    <a href="{{ route('admin.messageCommitment.index') }}" class="btn btn-secondary ml-2">Cancel</a>
</div>

@section('formJs')
    <!-- Summernote -->
    <script src="{{asset('backend/assets/plugins/summernote/summernote-bs4.min.js')}}"></script>
    <script>
        $(function () {
            $('.summernote').summernote({
                height: 250,
                placeholder: 'सन्देशको विवरण यहाँ टाइप गर्नुहोस्...'
            });
        });
        function showPreview(event){
            if(event.target.files.length > 0){
                var src = URL.createObjectURL(event.target.files[0]);
                var preview = document.getElementById("file-ip-1-preview");
                preview.src = src;
                preview.style.display = "block";
            }
        }
    </script>
@endsection
