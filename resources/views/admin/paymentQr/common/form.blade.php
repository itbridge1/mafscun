<div class="card-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>Title <span class="text-danger">*</span></label>
                {!! Form::text('title', null, ['class' => $errors->has('title') ? 'form-control is-invalid' : 'form-control', 'placeholder' => 'QR Title (e.g. eSewa)']) !!}
                @error('title') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Status</label>
                {!! Form::select('status', ['1' => 'Active', '0' => 'Inactive'], null, ['class' => 'form-control']) !!}
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Details</label>
                {!! Form::textarea('details', null, ['class' => $errors->has('details') ? 'form-control summernote is-invalid' : 'form-control summernote', 'rows' => 3, 'placeholder' => 'Any extra instructions...']) !!}
                @error('details') <span class="invalid-feedback"><strong>{{ $message }}</strong></span> @enderror
            </div>
        </div>

        <div class="col-md-12">
            <div class="card card-gray-dark">
                <div class="card-header">QR Image</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Upload QR Code <span class="text-danger">*</span></label>
                            {!! Form::file('main_qr', ['class' => 'form-control btn btn-sm', 'id' => 'qr_image', 'onchange' => 'showQrPreview(event)', 'accept' => 'image/*']) !!}
                            @error('main_qr') <span class="text-danger"><strong>{{ $message }}</strong></span> @enderror
                        </div>
                        <div class="col-md-6">
                            <label>Preview</label>
                            <div>
                                <img id="qr_preview" width="150" height="150"
                                     src="{{ isset($data['row']) ? \App\Facades\ViewHelper::getImagePath('paymentQr', $data['row']->qr_image) : '' }}"
                                     class="img-fluid rounded border" style="{{ isset($data['row']) && $data['row']->qr_image ? '' : 'display:none' }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card-footer">
    <button type="submit" class="btn btn-primary">{{ $button }}</button>
    <a href="{{ route($_base_route . '.index') }}" class="btn btn-secondary ml-2">Cancel</a>
</div>

@section('formJs')
<script>
    function showQrPreview(event) {
        if (event.target.files.length > 0) {
            const src = URL.createObjectURL(event.target.files[0]);
            const preview = document.getElementById('qr_preview');
            preview.src = src;
            preview.style.display = 'block';
        }
    }
</script>
@endsection
