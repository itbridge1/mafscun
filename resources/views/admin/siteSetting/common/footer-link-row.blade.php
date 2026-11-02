<div class="row footer-link-row align-items-end border-bottom mb-3 pb-3">
    <div class="col-md-3 form-group">
        <label>Link source</label>
        <select class="form-control" name="footer_links[{{ $index }}][type]">
            <option value="menu" @selected(($link['type'] ?? 'menu') === 'menu')>Header menu item</option>
            <option value="custom" @selected(($link['type'] ?? '') === 'custom')>Custom link</option>
        </select>
    </div>
    <div class="col-md-7 footer-menu-field form-group">
        <label>Header menu item</label>
        <select class="form-control footer-menu-select" name="footer_links[{{ $index }}][menu_id]">
            <option value="">-- Select menu item --</option>
            @foreach($menus as $id => $title)
                <option value="{{ $id }}" @selected(($link['menu_id'] ?? '') == $id)>{{ $title }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-7 footer-custom-fields form-group">
        <label>Label</label>
        <input class="form-control" name="footer_links[{{ $index }}][label]" value="{{ $link['label'] ?? '' }}" placeholder="Link label">
        <label class="mt-2">URL</label>
        <input class="form-control" name="footer_links[{{ $index }}][url]" value="{{ $link['url'] ?? '' }}" placeholder="https://example.com">
    </div>
    <div class="col-md-2 form-group">
        <button type="button" class="btn btn-outline-danger remove-footer-link">Remove</button>
    </div>
</div>
