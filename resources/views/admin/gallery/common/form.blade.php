<div class="card-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="title">Title*</label>
                {!! Form::text('title', null,[
                      'class'=> $errors->has('title')?'form-control is-invalid':'form-control',
                      'placeholder'=>'Eg. Gallery Title',
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
                <label for="sub_title">Sub Title</label>
                {!! Form::text('sub_title', null,[
                     'class'=> $errors->has('sub_title')?'form-control is-invalid':'form-control',
                     'placeholder'=>'Eg. Sub Title',
                     ]) !!}
                @error('sub_title')
                <span class="help-block invalid-feedback">
                     <strong> {{ $message }} </strong>
                </span>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-gray-dark">
                <div class="card-header">
                    Cover Image
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="main_photo">Upload Cover Image</label>
                            {!! Form::file('main_photo',[
                                'class'=> $errors->has('main_photo')?'form-control btn btn-sm is-invalid':'form-control btn btn-sm',
                                'id'=>'photo',
                                'onchange'=>"showPreview(event);",
                                'accept'=>'image/png, image/gif, image/jpeg'
                             ]) !!}
                            @error('main_photo')
                            <span class="help-block invalid-feedback">
                                    <strong> {{ $message }} </strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="photo">Existing Image</label>
                            <div class="preview">
                                <img width="200" height="200" src="{{ ViewHelper::getImagePath(@$_folder,@$data['row']->cover_photo) }}" class="img-responsive" id="file-ip-1-preview">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="description">Description</label>
                {!! Form::textarea('description', null,[
                    'class'=> $errors->has('description')?'form-control summernote is-invalid':'form-control summernote',
                     'placeholder'=>'Enter brief description.',
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
                {!! Form::select('status', ['1'=>'Active','0'=>'InActive'],null,[
                     'class'=> $errors->has('status')?'form-control is-invalid':'form-control',

                ]) !!}
                @error('status')
                <span class="help-block invalid-feedback">
                     <strong> {{ $message }} </strong>
                </span>
                @enderror
            </div>
        </div>

       @include('admin.gallery.common.gallery_table')

    </div>
</div>
<!-- /.card-body -->
<div class="card-footer">
    <button type="submit" class="btn btn-primary">{{$button}}</button>
</div>
@section('formJs')
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
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
@for($i = 0 ; $i<=20; $i++)
        function showPreview{{$i}}Gallery(event){
            if(event.target.files.length > 0){
                var src = URL.createObjectURL(event.target.files[0]);
                var preview = document.getElementById("file-image-{{$i}}-preview");
                var image = document.getElementById("image{{$i}}");
                preview.src = src;
                image.value=src;
                preview.style.display = "block";

            }
        }
@endfor
    </script>

    <script>
        $(document).ready(function(){

            var count1 = {{ isset($data['row']->images) && $data['row']->images->count() > 0 ? $data['row']->images->count() : 1 }};

            var max_count = 20;

            $('#gallery_detail').click(function(){
                if(count1 <= max_count){
                    var html = '<tr class="ui-sortable-handle">' +
                        '<td> <input class="form-control" name="gallery['+count1+'][gallery_image]" type="file" onchange="showPreview'+count1+'Gallery(event);"> ' +
                        '<div class="gallery-preview">' +
                            '<img width="125" height="125" src="{{ ViewHelper::getImagePath(@$_folder,@$data['row']->photo) }}" class="img-responsive" id="file-image-'+count1+'-preview">' +
                        '</div>' +
                        ' </td> ' +
                        '<td> <input class="form-control" name="gallery['+count1+'][alt_text]" type="text">' +
                        ' </td> ' +
                        '<td> <input class="form-control" name="gallery['+count1+'][caption]" type="text"> </td>' +
                        '<td>' +
                        '<select class="form-control" name="gallery['+count1+'][status]" type="text"><option value="1">Active</option><option value="0">Inactive</option> </select></td> ' +
                        '<td>' +
                        '<button id="remove" type="button" class="btn btn-danger btn-sm"><i class="fa fa-minus"></i>' +
                        '</button>' +
                        '</td>' +
                        '</tr>';
                    $('#gallery_form').append(html);
                    count1++;

                    Cookies.set('gallery', count1 ,{ expires: 0.04/24 });

                }
                else{
                    swal.fire(
                        'माफ गर्नुहोस्',
                        'तपाईं आफ्नो ग्यालेरीमा अधिकतम २० फोटोहरू अपलोड गर्न सक्नुहुन्छ।',
                        'warning'
                    )
                }
            });


            $('#gallery_form').on('click','#remove',function(){
                if(count1 === 1 ){
                    swal.fire(
                        'माफ गर्नुहोस्',
                        'तपाईंले कम्तिमा एउटा फोटो अपलोड गर्नुपर्नेछ|',
                        'warning'
                    )
                }
                else{
                    $(this).closest('tr').remove();
                    count1--;
                    Cookies.set('gallery', count1 ,{ expires: 0.04/24 });

                }
            });
            var data=Cookies.get('gallery');
            // //alert(data);
            // //getting local storage count and appending html accordingly
            for(count1; count1 < data ; count1++){
            };
        });
        $(function (){
            $('table tbody').sortable({
                helper: fixWidthHelper,
                update: function (event,ui) {
                    //
                }
            }).disableSelection();

            function fixWidthHelper (e, ui) {
                ui.children().each(function () {
                    $(this).width($(this).width());
                });
                return ui;
            }
        })
    </script>
@endsection
