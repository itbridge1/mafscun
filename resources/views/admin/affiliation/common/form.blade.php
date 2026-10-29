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
                <label for="title">Year</label>
                {!! Form::date('year', null,[
                     'class'=> $errors->has('year')?'form-control is-invalid':'form-control',
                     'placeholder'=>'Eg. since 2072',
                ]) !!}
                @error('year')
                <span class="help-block invalid-feedback">
                     <strong> {{ $message }} </strong>
                </span>
                @enderror
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Link*</label>
                {!! Form::text('link', null,[
                      'class'=> $errors->has('link')?'form-control is-invalid':'form-control',
                      'placeholder'=>'Enter URL here..',
                      ]) !!}
                @error('link')
                <span class="help-block invalid-feedback">
                        <strong> {{ $message }} </strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-12">
            <div class="card card-gray-dark">
                <div class="card-header">
                    Logo
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="photo_upload">Upload Logo</label>
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
                            <label for="photo">Existing Logo</label>
                            <div>
                               <img width="150" height="150" src="{{ ViewHelper::getImagePath(@$_folder,@$data['row']->logo) }}" class="img-fluid" id="file-ip-1-preview">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

{{--        <div class="col-md-6">--}}
{{--            <div class="form-group">--}}
{{--                <label for="title">Rank</label>--}}
{{--                {!! Form::number('rank', null,[--}}
{{--                     'class'=> $errors->has('rank')?'form-control is-invalid':'form-control',--}}
{{--                     'placeholder'=>'Eg. 1',--}}
{{--                ]) !!}--}}
{{--                @error('rank')--}}
{{--                <span class="help-block invalid-feedback">--}}
{{--                     <strong> {{ $message }} </strong>--}}
{{--                </span>--}}
{{--                @enderror--}}
{{--            </div>--}}
{{--        </div>--}}

        <div class="col-md-6">
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
    </script>
@endsection
