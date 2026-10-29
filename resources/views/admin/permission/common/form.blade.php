<div class="card-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Name*</label>
                {!! Form::text('name', null,[
                      'class'=> $errors->has('name')?'form-control is-invalid':'form-control',
                      'placeholder'=>'Eg. user-create',
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
                <label for="name">Display Name*</label>
                {!! Form::text('display_name', null,[
                     'class'=> $errors->has('display_name')?'form-control is-invalid':'form-control',
                     'placeholder'=>'Eg. Create',
                     ]) !!}
                @error('display_name')
                <span class="help-block invalid-feedback">
                     <strong> {{ $message }} </strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Group*</label>
                {!! Form::text('group', null,[
                     'class'=> $errors->has('group')?'form-control is-invalid':'form-control',
                     'placeholder'=>'User',
                     ]) !!}
                @error('group')
                <span class="help-block invalid-feedback">
                     <strong> {{ $message }} </strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="group_head" {{ $errors ->has ('group_head') ? 'has-error' : '' }}>Group Head</label>
                {!! Form::select('group_head',['0'=>'Not Group Head','1'=>'Is Group Head'],null,[
                    'class'=> $errors->has('group_head')?'form-control is-invalid':'form-control',
                ]) !!}
                @error('group_head')
                <span class="help-block invalid-feedback">
                     <strong> {{ $message }} </strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label for="description">Description</label>
                {!! Form::textarea('description', null,[
                     'class'=> $errors->has('description')?'form-control is-invalid':'form-control',
                     'placeholder'=>'Eg. Allows ability to create user.',
                     'rows'=>3
                ]) !!}
                @error('description')
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
