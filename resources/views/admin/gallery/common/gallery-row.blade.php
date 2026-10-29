<tr class="ui-sortable-handle">
    <td>
        <input class="form-control" name="gallery[0][gallery_image]" value="" type="file" onchange="showPreview0Gallery(event);">
        <div class="gallery-preview">
            <img width="125" height="125" src="{{ ViewHelper::getImagePath(@$_folder,@$data['row']->image) }}"
                 class="img-responsive"
                 id="file-image-0-preview">
        </div>
    </td>
    <td>
        <input class="form-control" name="gallery[0][alt_text]" value="" type="text">
    </td>
    <td>
        <input class="form-control" name="gallery[0][caption]" value="" type="text">
    </td>
    <td>
        <select class="form-control" name="gallery[0][status]" value="" type="number">
            <option value="1">Active</option>
            <option value="0">Inactive</option>
        </select>
    </td>
    <td><button id="remove" type="button" class="btn btn-danger btn-sm"><i class="fa fa-minus"></i></button></td>
</tr>
