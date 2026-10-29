@if (isset($row->statistics) && $row->statistics->count() > 0)
    @foreach ($row->statistics as $increment => $_statistics_data)
        <tr>
            <td>
                <input type="hidden" name="statisticsData[{{ $increment }}][id]" value="{{ $_statistics_data->id }}">
                <input class="form-control" name="statisticsData[{{ $increment }}][name]"
                    value="{{ $_statistics_data->name }}" type="text">
            </td>
            <td>
                <input class="form-control" name="statisticsData[{{ $increment }}][value]"
                    value="{{ $_statistics_data->value }}" type="text">
            </td>
            <td>
                <input class="form-control" name="statisticsData[{{ $increment }}][rank]"
                    value="{{ $_statistics_data->rank }}" type="number">
            </td>
            <td>
                <input type="file" name="stat_photo_{{ $increment }}" class="form-control" accept="image/*" onchange="previewImage(this, 'photo_preview_{{ $increment }}')">
                @if($_statistics_data->photo)
                    <img src="{{ asset('images/siteSetting/' . $_statistics_data->photo) }}" alt="Current Photo" style="width: 50px; height: 50px; margin-top: 5px;">
                @endif
                <img id="photo_preview_{{ $increment }}" src="" alt="Photo Preview" style="width: 50px; height: 50px; margin-top: 5px; display: none;">
            </td>
            <td>
                <button id="remove" type="button" class="btn btn-danger btn-sm remove-row"><i class="fa fa-minus"></i></button>
            </td>
        </tr>
    @endforeach
@endif
