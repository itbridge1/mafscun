<div class="card-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="title">Name*</label>
                {!! Form::text('name', null,[
                      'class'=> $errors->has('name')?'form-control is-invalid':'form-control',
                      'placeholder'=>'Eg.Karuna',
                      ]) !!}
                @error('name')
                    <span class="help-block invalid-feedback">
                        <strong> {{ $message }} </strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="title">Email </label>
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

        <div class="col-md-6">
            <div class="form-group">
                <label for="title">Subject </label>
                {!! Form::text('subject', null,[
                     'class'=> $errors->has('subject')?'form-control is-invalid':'form-control',
                     'placeholder'=>'',
                ]) !!}
                @error('subject')
                <span class="help-block invalid-feedback">
                     <strong> {{ $message }} </strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="title">Phone No. </label>
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

        <div class="col-md-12">
            <div class="form-group">
                <label for="title">Message</label>
                {!! Form::textarea('message', null,[
                     'class'=> $errors->has('message')?'form-control summernote is-invalid':'form-control summernote',
                     'placeholder'=>'Eg. Description',
                ]) !!}
                @error('message')
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
                    'data-on-text' => 'RESPONDED',
                    'data-off-text' => 'NOT RESPONDED'
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
    <!-- Bootstrap Switch -->
    <script src="{{ asset('backend/assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <script>


        $(function (){
            $("input[data-bootstrap-switch]").each(function(){
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            })
        })
    </script>
@endsection
