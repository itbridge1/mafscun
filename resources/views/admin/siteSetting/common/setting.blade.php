<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <h5 class="sub-title">AboutUs Setting</h5>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>AboutUs HomePage 1</label> <span class="red">*</span>
                        {!! Form::select('about_1', $data['aboutUs'],null,[
                              'class'=> $errors->has('about_1')?'form-control is-invalid':'form-control',
                              'placeholder'=>"--SELECT ABOUT US PAGE--",
                        ]) !!}
                        @error('about_1')
                        <span class="help-block invalid-feedback">
                                    <strong> {{ $message }} </strong>
                                </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Commitment From CEO</label>
                        {!! Form::select('about_2', $data['aboutUs'],null,[
                              'class'=> $errors->has('about_2')?'form-control is-invalid':'form-control',
                              'placeholder'=>"--SELECT ABOUT US PAGE--",
                        ]) !!}
                        @error('about_2')
                        <span class="help-block invalid-feedback">
                                    <strong> {{ $message }} </strong>
                                </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>Chairman Voice</label> <span class="red">*</span>
                        {!! Form::select('about_3', $data['aboutUs'],null,[
                              'class'=> $errors->has('about_3')?'form-control is-invalid':'form-control',
                              'placeholder'=>"--SELECT ABOUT US PAGE--",
                        ]) !!}
                        @error('about_3')
                        <span class="help-block invalid-feedback">
                                    <strong> {{ $message }} </strong>
                                </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label>AboutUs HomePage 4</label> <span class="red">*</span>
                        {!! Form::select('about_4', $data['aboutUs'],null,[
                              'class'=> $errors->has('about_4')?'form-control is-invalid':'form-control',
                              'placeholder'=>"--SELECT ABOUT US PAGE--",
                        ]) !!}
                        @error('about_4')
                        <span class="help-block invalid-feedback">
                                    <strong> {{ $message }} </strong>
                                </span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <h5 class="sub-title">Footer Setting</h5>
            <p>Add important links to the public footer. Choose an existing header menu item, or enter a custom label and URL.</p>
            <div id="footer-links-list">
                @foreach(old('footer_links', $data['row']->footer_links ?? []) as $i => $link)
                    @include('admin.siteSetting.common.footer-link-row', ['index' => $i, 'link' => $link, 'menus' => $data['menus']])
                @endforeach
            </div>
            <button type="button" class="btn btn-outline-primary" id="add-footer-link">+ Add important link</button>
            @error('footer_links')<div class="text-danger">{{ $message }}</div>@enderror
            <template id="footer-link-template">@include('admin.siteSetting.common.footer-link-row', ['index' => '__INDEX__', 'link' => [], 'menus' => $data['menus']])</template>
        </div>
    </div>
</div>

@section('formJs')
@parent
<script>
(function () {
    const list = document.getElementById('footer-links-list');
    let nextIndex = list.querySelectorAll('.footer-link-row').length;
    const template = document.getElementById('footer-link-template').innerHTML;
    function updateRow(row) {
        const custom = row.querySelector('[name$="[type]"]').value === 'custom';
        row.querySelector('.footer-menu-field').hidden = custom;
        row.querySelector('.footer-custom-fields').hidden = !custom;
        row.querySelector('.footer-menu-select').disabled = custom;
        row.querySelectorAll('.footer-custom-fields input').forEach(input => input.disabled = !custom);
    }
    document.getElementById('add-footer-link').addEventListener('click', function () {
        list.insertAdjacentHTML('beforeend', template.replaceAll('__INDEX__', nextIndex++));
        updateRow(list.lastElementChild);
    });
    list.addEventListener('change', function (event) {
        if (event.target.matches('[name$="[type]"]')) updateRow(event.target.closest('.footer-link-row'));
    });
    list.addEventListener('click', function (event) {
        if (event.target.matches('.remove-footer-link')) event.target.closest('.footer-link-row').remove();
    });
    list.querySelectorAll('.footer-link-row').forEach(updateRow);
})();
</script>
@endsection
