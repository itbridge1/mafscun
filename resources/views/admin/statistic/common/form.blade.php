<div class="card-body">
    <h5 class="mb-3">Municipality-wise Statistics</h5>
    <div class="row">
        @if(isset($data['districts']) && $data['districts']->count() > 0)
            @foreach($data['districts'] as $key => $district)
                <div class="col-md-12">
                    <div class="card card-gray-dark {{ $key==0? '':'collapsed-card' }}">
                        <div class="card-header">
                            <h3 class="card-title">
                                {{ $district->name }}
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                               <table class="table table-bordered">
                                   <thead>
                                   <tr>
                                       <th></th>
                                       <th>Municipality</th>
                                       <th>Total</th>
                                       <th>Associated</th>
                                   </tr>
                                   </thead>
                                   @if(isset($district->municipalities) && $district->municipalities->count() > 0)
                                   <tbody>
                                   @foreach($district->municipalities as $myKey => $municipality)
                                       <tr>
                                           <td> {{ ++$myKey }}
                                               <input type="hidden" name="detail[{{ $municipality->id }}][district_id]" value="{{$district->id}}">
                                               <input type="hidden" name="detail[{{ $municipality->id }}][municipality_id]" value="{{ $municipality->id }}">
                                           </td>
                                           <td> {{ $municipality->name }} </td>
                                           <td>
                                               {!! Form::number('detail['.$municipality->id.'][total]',\App\Facades\ViewHelper::getValueFromStat($district->id,$municipality->id,'total')??0,[
                                                    'class' => 'form-control',

                                                ]) !!}
                                           </td>
                                           <td>
                                               {!! Form::number('detail['.$municipality->id.'][associated]',\App\Facades\ViewHelper::getValueFromStat($district->id,$municipality->id,'associated')??0,[
                                                    'class' => 'form-control',

                                                ]) !!}
                                           </td>
                                       </tr>
                                   @endforeach
                                   </tbody>
                                   @endif
                               </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

    </div>
</div>
<!-- /.card-body -->
<div class="card-footer">
    <button type="submit" class="btn btn-primary">{{$button}}</button>
</div>

@section('formJs')
    <script src="{{ asset('backend/assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <script>
        $(function (){
            $("input[data-bootstrap-switch]").each(function(){
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            })
        })
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
