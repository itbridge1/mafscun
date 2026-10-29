@if(isset($row->images) && $row->images->count() > 0)
    @foreach($row->images()->orderBy('rank')->get() as $increment => $_image)
<tr class="ui-sortable-handle">
    <td>
        <input type="hidden" name="gallery[{{$increment}}][id]" value="{{ $_image->id }}">
        <input class="form-control" name="gallery[{{$increment}}][gallery_image]" value="" type="file" onchange="showPreview{{$increment}}Gallery(event);">
        <div class="gallery-preview">
            <img width="125" height="125" src="{{ ViewHelper::getImagePath(@$_folder,@$_image->photo) }}"
                 class="img-responsive"
                 id="file-image-{{$increment}}-preview">
        </div>
    </td>
    <td>
        <input class="form-control" name="gallery[{{ $increment }}][alt_text]" value="{{ $_image->alt_text }}" type="text">
    </td>
    <td>
        <input class="form-control" name="gallery[{{$increment}}][caption]" value="{{ $_image->caption }}" type="text">
    </td>
    <td>
        <select class="form-control" name="gallery[{{ $increment }}][status]" type="number">
            <option value="1" {{ $_image->status ==1 ? "selected":"" }}>Active</option>
            <option value="0" {{ $_image->status ==0 ? "selected":"" }}>Inactive</option>
        </select>
    </td>
    <td><button id="remove" type="button" class="btn btn-danger btn-sm"><i class="fa fa-minus"></i></button></td>
</tr>
    @endforeach
@endif
