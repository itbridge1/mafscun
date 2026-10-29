<script src="{{ asset('backend/nepali-date-picker/js/nepali.datepicker.v3.7.min.js') }}" type="text/javascript">
</script>

<script src="https://cdn.jsdelivr.net/npm/js-cookie@3.0.1/dist/js.cookie.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function showPreview(event) {
        if (event.target.files.length > 0) {
            var src = URL.createObjectURL(event.target.files[0]);
            var preview = document.getElementById("file-ip-1-preview");
            var photo = document.getElementById("photo");
            preview.src = src;
            photo.value = src;
            preview.style.display = "block";
        }
    }

    function showFaviconPreview(event) {
        if (event.target.files.length > 0) {
            var src = URL.createObjectURL(event.target.files[0]);
            var preview = document.getElementById("favicon-preview");
            var photo = document.getElementById("favicon");
            preview.src = src;
            photo.value = src;
            preview.style.display = "block";
        }
    }

    function showCompanyLogoPreview(event) {
        if (event.target.files.length > 0) {
            var src = URL.createObjectURL(event.target.files[0]);
            var preview = document.getElementById("company_logo_preview");
            var photo = document.getElementById("company_logo");
            preview.src = src;
            photo.value = src;
            preview.style.display = "block";
        }
    }

    function showPoweredByLogoPreview(event) {
        if (event.target.files.length > 0) {
            var src = URL.createObjectURL(event.target.files[0]);
            var preview = document.getElementById("powered_by_logo_preview");
            var photo = document.getElementById("powered_by_logo");
            preview.src = src;
            photo.value = src;
            preview.style.display = "block";
        }
    }

    function showStatMapPreview(event) {
        if (event.target.files.length > 0) {
            var src = URL.createObjectURL(event.target.files[0]);
            var preview = document.getElementById("stat_map_preview");
            var photo = document.getElementById("stat_map");
            preview.src = src;
            photo.value = src;
            preview.style.display = "block";
        }
    }
</script>
<script>
    $(document).ready(function() {
        // Initialize counter
        var count1 =
            {{ isset($data['row']->statistics) && $data['row']->statistics->count() > 0 ? $data['row']->statistics->count() : 0 }};
        var max_count = 30;

        // Add new row
        $('#statistics_detail').click(function() {
            if (count1 < max_count) {
                var html = `<tr>
                <td><input class="form-control" name="statisticsData[${count1}][name]" type="text"></td>
                <td><input class="form-control" name="statisticsData[${count1}][value]" type="text"></td>
                <td><input class="form-control" name="statisticsData[${count1}][rank]" type="number"></td>
                <td>
                    <input type="file" name="stat_photo_${count1}" class="form-control" accept="image/*" onchange="previewImage(this, 'photo_preview_${count1}')">
                    <img id="photo_preview_${count1}" src="" alt="Photo Preview" style="width: 50px; height: 50px; margin-top: 5px; display: none;">
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm remove-row">
                        <i class="fa fa-minus"></i>
                    </button>
                </td>
            </tr>`;

                $('#statistics_form tbody').append(html);
                count1++;
            } else {
                Swal.fire({
                    title: 'Sorry',
                    text: 'You can add maximum 30 statistics',
                    icon: 'warning'
                });
            }
        });

        // Remove row
        $(document).on('click', '.remove-row', function() {
            var rowCount = $('#statistics_form tbody tr').length;
            if (rowCount > 1) {
                $(this).closest('tr').remove();
                count1--;
            } else {
                Swal.fire({
                    title: 'Sorry',
                    text: 'You must have at least one row',
                    icon: 'warning'
                });
            }
        });
    });

    // Preview image function
    function previewImage(input, previewId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var preview = document.getElementById(previewId);
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
