<div class="card-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="title">Question</label>
                {!! Form::text('question', null,[
                      'class'=> $errors->has('question')?'form-control is-invalid':'form-control',
                      'placeholder'=>'Eg.Question',
                      ]) !!}
                @error('question')
                    <span class="help-block invalid-feedback">
                        <strong> {{ $message }} </strong>
                    </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="title">Answer </label>
                {!! Form::text('answer', null,[
                     'class'=> $errors->has('answer')?'form-control is-invalid':'form-control',
                     'placeholder'=>'Eg. answer',
                ]) !!}
                @error('answer')
                <span class="help-block invalid-feedback">
                     <strong> {{ $message }} </strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="title">Link </label>
                {!! Form::text('link', null,[
                     'class'=> $errors->has('link')?'form-control is-invalid':'form-control',
                     'placeholder'=>'',
                ]) !!}
                @error('link')
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
