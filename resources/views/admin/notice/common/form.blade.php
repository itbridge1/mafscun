<div class="card-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Title*</label>
                {!! Form::text('title', null,[
                      'class'=> $errors->has('title')?'form-control is-invalid':'form-control',
                      'placeholder'=>'Eg.Introduction',
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
                <label for="title">Sub Title</label>
                {!! Form::text('sub_title', null,[
                     'class'=> $errors->has('sub_title')?'form-control is-invalid':'form-control',
                     'placeholder'=>'Eg. Introduction 1.1',
                ]) !!}
                @error('sub_title')
                <span class="help-block invalid-feedback">
                     <strong> {{ $message }} </strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="title">Start Date</label>
                {!! Form::date('start_date', null,[
                     'class'=> $errors->has('start_date')?'form-control is-invalid':'form-control',
                     'placeholder'=>'Eg. YYYY-MM-DD',
                ]) !!}
                @error('start_date')
                <span class="help-block invalid-feedback">
                     <strong> {{ $message }} </strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="title">End Date</label>
                {!! Form::date('end_date', null,[
                     'class'=> $errors->has('end_date')?'form-control is-invalid':'form-control',
                     'placeholder'=>'Eg. YYYY-MM-DD',
                ]) !!}
                @error('end_date')
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

        <div class="col-md-12">
            <div class="form-group">
                <label for="title">Description</label>
                {!! Form::textarea('description', null,[
                     'class'=> $errors->has('description')?'form-control summernote is-invalid':'form-control summernote',
                     'placeholder'=>'Eg. Description',
                ]) !!}
                @error('description')
                <span class="help-block invalid-feedback">
                     <strong> {{ $message }} </strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="col-md-4">
            <div class="form-group">
                <label for="title">Status</label>
                {!! Form::checkbox('status', null,isset($data['row'])?null:true, [
                    'class'=> $errors->has('status')?'is-invalid':'',
                    'data-bootstrap-switch',
                    'data-off-color' => 'danger',
                    'data-on-color' => 'success',
                    'data-on-text' => 'ACTIVE',
                    'data-off-text' => 'INACTIVE'
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
                <label for="title">Is Popup</label>
                {!! Form::checkbox('is_popup', null,isset($data['row'])?null:false, [
                    'class'=> $errors->has('is_popup')?'is-invalid':'',
                    'data-bootstrap-switch',
                    'data-off-color' => 'danger',
                    'data-on-color' => 'success',
                    'data-on-text' => 'ON',
                    'data-off-text' => 'OFF'
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
                <label for="title">Is Career</label>
                {!! Form::checkbox('is_career', null,isset($data['row'])?null:false, [
                    'class'=> $errors->has('is_career')?'is-invalid':'',
                    'data-bootstrap-switch',
                    'data-off-color' => 'danger',
                    'data-on-color' => 'success',
                    'data-on-text' => 'YES',
                    'data-off-text' => 'NO'
                ]) !!}
                @error('is_career')
                <span class="help-block invalid-feedback">
                     <strong> {{ $message }} </strong>
                </span>
                @enderror
            </div>
        </div>

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
            $("input[data-bootstrap-switch]").each(function(){
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            })
        })
    </script>
@endsection
