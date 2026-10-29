<div class="card-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group ">
                <label for="parentCategory">Parent Menu</label>
                {!! Form::select('parent_id',$data['groups'] ,null,[
                  'class'=>'form-control',
                  'placeholder' => '--SELECT PARENT MENU--'
                    ]) !!}
                @error('parent_id')
                <span class="help-block invalid-feedback">
                     <strong> {{ $message }} </strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="title">Title </label> <span class="red">*</span>
                {!! Form::text('title', null,[
                      'class'=> $errors->has('title')?'form-control is-invalid':'form-control',
                      'placeholder'=>'eg. Enter title here..',
                      ]) !!}
                @error('title')
                    <span class="help-block invalid-feedback">
                     <strong> {{ $message }} </strong>
                    </span>
                @enderror
                @error('slug')
                <span class="help-block invalid-feedback">
                     <strong> {{ $message }} </strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="nepali_title">Sub Title</label> <span class="red">*</span>
                {!! Form::text('sub_title', null,[
                     'class'=> $errors->has('sub_title')?'form-control is-invalid':'form-control',
                     'placeholder'=>'eg. Sub Title',
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
                <label for="status">Rank</label> <span class="red">*</span>
                {!! Form::number('rank', null,[
                     'class'=> $errors->has('rank')?'form-control is-invalid':'form-control',
                     'placeholder'=>'Eg. 1',
                     'min' => 1
                     ]) !!}
                @error('rank')
                <span class="help-block invalid-feedback">
                     <strong> {{ $message }} </strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="excerpt">Menu URL</label>
                {!! Form::url('menu_url', null,[
                     'class'=> $errors->has('menu_url')?'form-control is-invalid':'form-control',
                     'placeholder'  => 'eg. https://bagmatiproscun.org.np'

                     ]) !!}
                @error('menu_url')
                <span class="help-block invalid-feedback">
                     <strong> {{ $message }} </strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="status">Status</label> <span class="red">*</span><br>
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

    </div>
</div>
<!-- /.card-body -->
<div class="card-footer">
    <button type="submit" class="btn btn-primary">{{$button}}</button>
</div>

@section('formJs')
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
