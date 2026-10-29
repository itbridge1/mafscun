<div class="card-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Name*</label>
                {!! Form::text('name', null,[
                      'class'=> $errors->has('name')?'form-control is-invalid':'form-control',
                      'placeholder'=>'super-admin',
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
                     'placeholder'=>'Super Admin',
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
                <label for="name">Type*</label>
                {!! Form::text('type', null,[
                     'class'=> $errors->has('type')?'form-control is-invalid':'form-control',
                     'placeholder'=>'Super Admin',
                     ]) !!}
                @error('type')
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
                     'placeholder'=>'Super Admin',
                     'rows'=>2
                ]) !!}
                @error('description')
                <span class="help-block invalid-feedback">
                     <strong> {{ $message }} </strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="col-md-12">
            <div class="card card-dark">
                <div class="card-header">
                    <div class="card-title">
                        Permissions
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="permission-table" class="table table-sm">
                            <thead>
                            <tr>
                                <th width="20%">Group</th>
                                <th class="center">
                                    <label class="pos-rel">
                                        <input type="checkbox" class="ace" />
                                        <span class="lbl"></span>
                                    </label>
                                </th>
                                <th>Permissions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if($data['permissions'] && $data['permissions']->count() > 0)
                                @foreach($data['permissions'] as $key => $permission)
                                    <tr>
                                        @if(isset($permission[0]->group_head) && $permission[0]->group_head == 1)
                                            <td colspan="3" style="background: #337ab7; color: white;">
                                                <strong>{{$permission[0]->group}}</strong>
                                            </td>
                                        @else
                                            <td><strong>{{$permission[0]->group}}</strong></td>

                                            <td class="center first-child">
                                                <label>
                                                    <input type="checkbox" name="chkIds[]" value="1" class="ace group" />
                                                    <span class="lbl"></span>
                                                </label>
                                            </td>
                                            <td>
                                                @foreach($permission as $access)
                                                    <label>
                                                        @if (!isset($data['role_permission']))
                                                            {!! Form::checkbox('permission[]', $access->id, false, ['class' => 'ace']) !!}
                                                        @else
                                                            {!! Form::checkbox('permission[]', $access->id, array_key_exists($access->id, $data['role_permission']), ['class' => 'ace']) !!}
                                                        @endif
                                                        <span class="lbl"> {{ $access->display_name}} </span>
                                                    </label>
                                                @endforeach
                                            </td>
                                        @endif

                                    </tr>
                                @endforeach
                            @else
                                <tr><td colspan="7">No data found.</td></tr>
                            @endif
                            </tbody>
                        </table>
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
        $(document).ready(function () {
            $('table th input:checkbox').on('click' , function(){
                var that = this;
                $(this).closest('table').find('input:checkbox')
                    .each(function(){
                        this.checked = that.checked;
                        $(this).closest('tr').toggleClass('selected');
                    });

            });

            $('.group').on('click' , function(){
                var that = this;
                $(this).closest('tr').find('input:checkbox')
                    .each(function(){
                        this.checked = that.checked;
                        $(this).closest('tr').toggleClass('selected');
                    });

            });


        });
    </script>
@endsection
