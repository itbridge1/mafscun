@extends('admin.layout.frame')
@section('title', 'Certificate Settings')
@section('css')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;700&family=Great+Vibes&family=Montserrat:wght@400;700&family=Open+Sans:wght@400;700&family=Oswald:wght@400;700&family=Playfair+Display:wght@400;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
<style>
    #certificate-canvas-container {
        position: relative;
        margin: 0 auto;
        border: 2px dashed #ccc;
        background-color: #f8f9fa;
        background-size: contain;
        background-position: center;
        background-repeat: no-repeat;
    }
    .cert-field-draggable {
        position: absolute;
        cursor: move;
        border: 1px dashed transparent;
        padding: 5px;
        color: #000;
        white-space: nowrap;
        user-select: none;
        display: inline-block;
        z-index: 10;
    }

    .cert-field-draggable:hover {
        border: 1px dashed #007bff;
        background: rgba(255,255,255,0.7);
    }

    .cert-field-draggable.selected {
        border: 2px solid #007bff;
        background: rgba(0, 123, 255, 0.2);
    }
</style>
@endsection
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Certificate Settings: {{ $data['event']->title }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.event.index') }}">Events</a></li>
                        <li class="breadcrumb-item active">Certificate Settings</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <form action="{{ route('admin.event.certificate.update', $data['event']->id) }}" method="POST" enctype="multipart/form-data" id="cert-form">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="card card-primary card-outline">
                            <div class="card-header"><h3 class="card-title">Setup Certificate</h3></div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Background Image (A4 Size)</label>
                                    <input type="file" name="certificate_bg_image" id="certificate_bg_image" class="form-control" accept="image/jpeg,image/png">
                                    @if($data['event']->certificate_bg_image)
                                        <div class="mt-2">
                                            <a href="{{ asset('images/events/' . $data['event']->certificate_bg_image) }}" target="_blank">View Current Image</a>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label>Orientation</label>
                                    <select name="certificate_orientation" id="cert-orientation" class="form-control">
                                        <option value="horizontal" {{ $data['event']->certificate_orientation == 'horizontal' ? 'selected' : '' }}>Landscape (Horizontal)</option>
                                        <option value="vertical" {{ $data['event']->certificate_orientation == 'vertical' ? 'selected' : '' }}>Portrait (Vertical)</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Background Sizing</label>
                                    <select id="cert-bg-sizing" class="form-control">
                                        <option value="stretch">Stretch to Fit (Default)</option>
                                        <option value="cover">Cover (Fill & Crop)</option>
                                        <option value="contain">Contain (Fit inside perfectly)</option>
                                    </select>
                                </div>
                                
                                <hr>
                                <h5>Global Settings</h5>
                                <div class="form-group mb-3">
                                    <label>Default Font Family</label>
                                    <select id="global-font-family" class="form-control form-control-sm">
                                        <option value="Roboto">Roboto (Clean)</option>
                                        <option value="PlayfairDisplay">Playfair Display (Serif)</option>
                                        <option value="GreatVibes">Great Vibes (Cursive)</option>
                                        <option value="Montserrat">Montserrat (Modern)</option>
                                        <option value="OpenSans">Open Sans</option>
                                        <option value="Oswald">Oswald (Bold)</option>
                                        <option value="DancingScript">Dancing Script</option>
                                    </select>
                                </div>
                                <hr>
                                <h5>Available Fields</h5>
                                <p class="text-sm text-muted">Click a field to add it to the canvas, then drag it to position. Adjust size below.</p>
                                
                                <div class="mb-3" id="available-fields">
                                    <button type="button" class="btn btn-sm btn-outline-secondary mb-1 btn-add-field" data-field="participant_name" data-label="Participant Name">Participant Name</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary mb-1 btn-add-field" data-field="cooperative_name" data-label="Cooperative Name">Cooperative Name</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary mb-1 btn-add-field" data-field="cooperative_address" data-label="Cooperative Address">Cooperative Address</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary mb-1 btn-add-field" data-field="event_name" data-label="Event Name">Event Name</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary mb-1 btn-add-field" data-field="event_location" data-label="Event Location">Event Location</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary mb-1 btn-add-field" data-field="event_start_date" data-label="Event Start Date">Start Date</button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary mb-1 btn-add-field" data-field="participant_designation" data-label="Participant Designation">Designation</button>
                                </div>


                                <input type="hidden" name="certificate_settings" id="certificate_settings">
                                <hr>
                                <button type="submit" class="btn btn-success btn-block" onclick="saveSettings()">Save Configuration</button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title"><i class="fas fa-certificate text-primary mr-2"></i> Certificate Canvas</h3>
                                <div class="d-flex align-items-center">
                                    <ul class="nav nav-pills mr-3" id="cert-mode-tabs" style="margin: -5px 0;">
                                        <li class="nav-item">
                                            <a class="nav-link active py-1 px-2" href="#" data-mode="with_designation" style="font-size: 13px;">With Designation</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link py-1 px-2" href="#" data-mode="without_designation" style="font-size: 13px;">Without Designation</a>
                                        </li>
                                    </ul>
                                    <button type="button" class="btn btn-sm btn-outline-warning py-1 px-2 mr-1" id="btn-reset-layout" title="Reset layout positions to defaults">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-info py-1 px-2" id="btn-clone-layout" title="Clone layout from 'Without Designation'">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body" style="position: relative; background: #f4f6f9; padding: 20px;">
                                <div id="certificate-canvas-container" style="
                                    @if($data['event']->certificate_bg_image)
                                        background-image: url('{{ asset('images/events/' . $data['event']->certificate_bg_image) }}');
                                    @else
                                        background-image: url('{{ asset('images/events/default_certificate_bg.jpg') }}');
                                    @endif
                                    background-size: 100% 100%; background-repeat: no-repeat; background-position: top left; border: 2px solid #343a40; position: relative; overflow: hidden; box-shadow: 0 8px 25px rgba(0,0,0,0.25); border-radius: 4px; background-color: #ffffff; margin: 0 auto;
                                ">
                                    <!-- Draggable fields will appear here -->
                                </div>
                                
                                <div id="field-editor" style="display: none; position: absolute; top: 0; left: 0; z-index: 1050; background: #fff; padding: 15px; border: 1px solid #ddd; border-radius: 5px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); width: 280px;">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="m-0 text-primary">Edit: <span id="editor-field-name"></span></h6>
                                        <button type="button" class="close" id="btn-close-editor" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="row">
                                        <div class="col-6 form-group mb-2">
                                            <label class="text-xs">Font Size</label>
                                            <input type="number" id="editor-font-size" class="form-control form-control-sm" min="10" max="150" value="24">
                                        </div>
                                        <div class="col-6 form-group mb-2">
                                            <label class="text-xs">Color</label>
                                            <input type="color" id="editor-font-color" class="form-control form-control-sm" value="#000000" style="padding:0; height:31px;">
                                        </div>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label class="text-xs">Font Style</label>
                                        <select id="editor-font-family" class="form-control form-control-sm">
                                            <option value="global" class="font-weight-bold">Use Global Font</option>
                                            <option value="Roboto">Roboto (Clean)</option>
                                            <option value="PlayfairDisplay">Playfair Display (Serif)</option>
                                            <option value="GreatVibes">Great Vibes (Cursive)</option>
                                            <option value="Montserrat">Montserrat (Modern)</option>
                                            <option value="OpenSans">Open Sans</option>
                                            <option value="Oswald">Oswald (Bold)</option>
                                            <option value="DancingScript">Dancing Script</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label class="text-xs">Text Transform</label>
                                        <select id="editor-text-transform" class="form-control form-control-sm">
                                            <option value="none">Normal</option>
                                            <option value="uppercase">UPPERCASE</option>
                                            <option value="capitalize">Capitalize</option>
                                            <option value="lowercase">lowercase</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label class="text-xs">Text Alignment</label>
                                        <select id="editor-text-align" class="form-control form-control-sm">
                                            <option value="left">Left Aligned</option>
                                            <option value="center">Center Aligned</option>
                                            <option value="right">Right Aligned</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label class="text-xs">Icon Prefix</label>
                                        <select id="editor-icon" class="form-control form-control-sm">
                                            <option value="none">None</option>
                                            <option value="calendar">Calendar Icon</option>
                                            <option value="location">Location Icon</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label class="text-xs">Prefix Text (e.g. "Mr. " or ", ")</label>
                                        <input type="text" id="editor-prefix" class="form-control form-control-sm" placeholder="Prefix text">
                                    </div>
                                    <div class="form-group mb-2">
                                        <label class="text-xs">Suffix Text</label>
                                        <input type="text" id="editor-suffix" class="form-control form-control-sm" placeholder="Suffix text">
                                    </div>
                                    <div class="form-group form-check mb-2">
                                        <input type="checkbox" class="form-check-input" id="editor-font-bold">
                                        <label class="form-check-label text-xs" for="editor-font-bold">Bold Text</label>
                                    </div>
                                    <button type="button" class="btn btn-danger btn-sm btn-block mt-2" id="btn-remove-field">Remove Field</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection

@section('js')
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>
    let existingSettingsRaw = {!! $data['event']->certificate_settings ? json_encode($data['event']->certificate_settings) : '[]' !!};
    
    let defaultCertBgUrl = '{{ asset("images/events/default_certificate_bg.jpg") }}';

    // Default template layout if settings are empty (ALL centered for max compatibility)
    let defaultFieldsWithDes = [
        { key: "cooperative_name", label: "Cooperative Name", x: 50.0, y: 35.0, size: 16, color: "#333333", align: "center", font: "global", text_transform: "none", bold: true, icon: "none" },
        { key: "event_name", label: "Event Name", x: 50.0, y: 38.0, size: 20, color: "#1a4b7f", align: "center", font: "global", text_transform: "uppercase", bold: true, icon: "none" },
        { key: "participant_name", label: "Participant Name", x: 50.0, y: 50.0, size: 34, color: "#1a1a1a", align: "center", font: "global", text_transform: "none", bold: true, icon: "none" },
        { key: "participant_designation", label: "Participant Designation", x: 50.0, y: 58.0, size: 18, color: "#555555", align: "center", font: "global", text_transform: "none", bold: false, icon: "none" },
        { key: "cooperative_address", label: "Cooperative Address", x: 50.0, y: 66.0, size: 16, color: "#cc0000", align: "center", font: "global", text_transform: "none", bold: false, icon: "location" },
        { key: "event_location", label: "Event Location", x: 50.0, y: 74.0, size: 16, color: "#cc0000", align: "center", font: "global", text_transform: "none", bold: false, icon: "location" },
        { key: "event_start_date", label: "Start Date", x: 50.0, y: 82.0, size: 16, color: "#1a4b7f", align: "center", font: "global", text_transform: "none", bold: false, icon: "calendar" }
    ];

    let certSettings = {
        global_font: 'Roboto',
        bg_sizing: 'stretch',
        with_designation: [],
        without_designation: [],
        without_designation_touched: false
    };
    
    if (Array.isArray(existingSettingsRaw) && existingSettingsRaw.length > 0) {
        certSettings.with_designation = existingSettingsRaw;
        certSettings.without_designation = existingSettingsRaw.filter(f => f.key !== 'participant_designation');
    } else if (existingSettingsRaw && typeof existingSettingsRaw === 'object' && existingSettingsRaw.with_designation && existingSettingsRaw.with_designation.length > 0) {
        certSettings = existingSettingsRaw;
    } else {
        certSettings.with_designation = JSON.parse(JSON.stringify(defaultFieldsWithDes));
        certSettings.without_designation = defaultFieldsWithDes.filter(f => f.key !== 'participant_designation');
    }

    // Auto-repair missing fields in with_designation and without_designation
    let allRequiredKeys = ['event_name', 'cooperative_name', 'participant_name', 'participant_designation', 'cooperative_address', 'event_location', 'event_start_date'];
    
    allRequiredKeys.forEach(key => {
        let existsInWith = certSettings.with_designation.find(f => f.key === key);
        if (!existsInWith) {
            let defaultF = defaultFieldsWithDes.find(d => d.key === key);
            if (defaultF) certSettings.with_designation.push(JSON.parse(JSON.stringify(defaultF)));
        }
    });

    allRequiredKeys.filter(k => k !== 'participant_designation').forEach(key => {
        let existsInWithout = certSettings.without_designation.find(f => f.key === key);
        if (!existsInWithout) {
            let defaultF = defaultFieldsWithDes.find(d => d.key === key);
            if (defaultF) certSettings.without_designation.push(JSON.parse(JSON.stringify(defaultF)));
        }
    });
    
    certSettings.without_designation = certSettings.without_designation.filter(f => f.key !== 'participant_designation');

    let currentMode = 'with_designation';
    let fields = certSettings[currentMode];
    let selectedFieldId = null;

    function markTouched() {
        if (currentMode === 'without_designation') {
            certSettings.without_designation_touched = true;
        }
    }

    function updateAvailableFields() {
        $('.btn-add-field').each(function() {
            let fieldKey = $(this).data('field');
            let exists = fields.find(f => f.key === fieldKey);
            if (exists) {
                $(this).hide();
            } else {
                if (currentMode === 'without_designation' && fieldKey === 'participant_designation') {
                    $(this).hide();
                } else {
                    $(this).show();
                }
            }
        });
    }

    let canvasScale = 1;

    function resizeCanvas() {
        let orientation = $('#cert-orientation').val();
        let container = $('#certificate-canvas-container');
        let wrapperWidth = container.parent().width();
        
        let baseWidth = 800;
        let baseHeight = orientation === 'horizontal' ? (baseWidth / 1.414) : (baseWidth * 1.414);
        
        canvasScale = wrapperWidth / baseWidth;
        if (canvasScale > 1) canvasScale = 1;
        
        container.css({
            width: baseWidth + 'px',
            height: baseHeight + 'px',
            transform: 'scale(' + canvasScale + ')',
            transformOrigin: 'top left'
        });
        
        container.parent().css('height', (baseHeight * canvasScale) + 'px');
        
        renderFields();
    }

    function renderFields() {
        $('#certificate-canvas-container').empty();
        
        let eventName = '{{ addslashes($data["event"]->title) }}';
        let eventLocation = '{{ addslashes($data["event"]->getLocationName() ?: "N/A") }}';
        let eventStartDate = '{{ $data["event"]->start_date ? $data["event"]->start_date->format("M d") : "N/A" }}';
        @if($data["event"]->end_date && $data["event"]->start_date && $data["event"]->start_date->format('Y-m-d') !== $data["event"]->end_date->format('Y-m-d'))
            @if($data["event"]->start_date->format('Y') === $data["event"]->end_date->format('Y'))
                eventStartDate = '{{ $data["event"]->start_date->format("M d") }} to {{ $data["event"]->end_date->format("M d") }}, {{ $data["event"]->end_date->format("Y") }}';
            @else
                eventStartDate = '{{ $data["event"]->start_date->format("M d, Y") }} to {{ $data["event"]->end_date->format("M d, Y") }}';
            @endif
        @elseif($data["event"]->start_date)
            eventStartDate = '{{ $data["event"]->start_date->format("M d, Y") }}';
        @endif

        fields.forEach(function(field, index) {
            // Auto repair coordinates if way out of bounds (to handle corrupted old data)
            if (typeof field.x !== 'number' || isNaN(field.x) || field.x < -20 || field.x > 120) {
                field.x = field.key === 'participant_designation' ? 50 : 20;
            }
            if (typeof field.y !== 'number' || isNaN(field.y) || field.y < -20 || field.y > 120) {
                field.y = field.key === 'participant_designation' ? 46 : 20;
            }

            let text = field.label;
            if (field.key === 'event_name') text = eventName;
            if (field.key === 'event_location') text = eventLocation;
            if (field.key === 'event_start_date') text = eventStartDate;
            if (field.key === 'participant_name') text = 'John Doe';
            if (field.key === 'participant_designation') text = 'Manager';
            if (field.key === 'cooperative_name') text = 'Sample Cooperative Organization';
            if (field.key === 'cooperative_address') text = 'Lalitpur, Nepal';

            let iconHtml = '';
            if (field.icon === 'location') {
                iconHtml = '<i class="fas fa-map-marker-alt mr-1"></i>';
            } else if (field.icon === 'calendar') {
                iconHtml = '<i class="far fa-calendar-alt mr-1"></i>';
            }

            let alignStyle = field.align || 'left';
            
            let cssFontMap = {
                'Roboto': '"Roboto", sans-serif',
                'PlayfairDisplay': '"Playfair Display", serif',
                'GreatVibes': '"Great Vibes", cursive',
                'Montserrat': '"Montserrat", sans-serif',
                'OpenSans': '"Open Sans", sans-serif',
                'Oswald': '"Oswald", sans-serif',
                'DancingScript': '"Dancing Script", cursive'
            };
            
            let rawFont = field.font === 'global' ? certSettings.global_font : (field.font || certSettings.global_font);
            let cssFontFamily = cssFontMap[rawFont] || '"Roboto", sans-serif';
            if (field.prefix) text = field.prefix + text;
            if (field.suffix) text = text + field.suffix;

            let el = $('<div></div>')
                .addClass('cert-field-draggable')
                .attr('id', 'field_' + index)
                .html(iconHtml + text)
                .css({
                    left: field.x + '%',
                    top: field.y + '%',
                    fontSize: (field.size || 20) + 'px',
                    color: field.color || '#000000',
                    fontFamily: cssFontFamily,
                    textTransform: field.text_transform || 'none',
                    fontWeight: field.bold ? 'bold' : 'normal',
                    textAlign: alignStyle,
                    position: 'absolute',
                    whiteSpace: 'nowrap'
                });

            $('#certificate-canvas-container').append(el);
            
            // Fix jQuery UI Dragging by using margin-left instead of CSS transforms
            if (alignStyle === 'center') {
                el.css('margin-left', -(el.outerWidth() / 2) + 'px');
            } else if (alignStyle === 'right') {
                el.css('margin-left', -el.outerWidth() + 'px');
            }

            if (selectedFieldId === index) {
                el.addClass('selected');
            }

            el.draggable({
                start: function(event, ui) {
                    $('#field-editor').hide();
                },
                drag: function(event, ui) {
                    // Let jQuery UI handle the drag. We just apply the canvasScale adjustment.
                    let changeLeft = (ui.position.left - ui.originalPosition.left) / canvasScale;
                    let changeTop = (ui.position.top - ui.originalPosition.top) / canvasScale;
                    
                    ui.position.left = ui.originalPosition.left + changeLeft;
                    ui.position.top = ui.originalPosition.top + changeTop;
                },
                stop: function(event, ui) {
                    let pw = $('#certificate-canvas-container').width();
                    let ph = $('#certificate-canvas-container').height();
                    
                    let px = (ui.position.left / pw) * 100;
                    let py = (ui.position.top / ph) * 100;
                    
                    // Allow slight negative/overflow so fields don't get hard-clamped and stuck
                    px = Math.max(-20, Math.min(120, px));
                    py = Math.max(-20, Math.min(120, py));
                    
                    fields[index].x = parseFloat(px.toFixed(2));
                    fields[index].y = parseFloat(py.toFixed(2));
                    
                    $(this).css({
                        left: fields[index].x + '%',
                        top: fields[index].y + '%'
                    });
                    
                    markTouched();
                    selectField(index);
                }
            }).on('click', function() {
                selectField(index);
            });
        });
        updateAvailableFields();
    }

    function selectField(index) {
        selectedFieldId = index;
        $('.cert-field-draggable').removeClass('selected');
        let fieldEl = $(`#field_${index}`);
        fieldEl.addClass('selected');
        
        let field = fields[index];
        
        // Position the editor popover near the selected field
        let pos = fieldEl.position();
        let editorTop = pos.top + fieldEl.outerHeight() + 5;
        let editorLeft = pos.left;
        
        // ensure it doesn't overflow the container on the right
        let containerWidth = $('#certificate-canvas-container').width();
        if (editorLeft + 280 > containerWidth) {
            editorLeft = containerWidth - 280;
        }

        $('#field-editor').css({
            display: 'block',
            top: editorTop + 'px',
            left: editorLeft + 'px'
        });

        $('#editor-field-name').text(field.label);
        $('#editor-font-size').val(field.size || 24);
        $('#editor-font-color').val(field.color || '#000000');
        $('#editor-font-family').val(field.font || 'global');
        $('#editor-text-transform').val(field.text_transform || 'none');
        $('#editor-text-align').val(field.align || 'left');
        $('#editor-font-bold').prop('checked', field.bold || false);
        $('#editor-icon').val(field.icon || 'none');
        $('#editor-prefix').val(field.prefix || '');
        $('#editor-suffix').val(field.suffix || '');
    }

    function removeSelectedField() {
        if (selectedFieldId !== null) {
            fields.splice(selectedFieldId, 1);
            selectedFieldId = null;
            $('#field-editor').hide();
            renderFields();
        }
    }

    function saveSettings() {
        certSettings[currentMode] = fields;
        certSettings.global_font = $('#global-font-family').val();
        certSettings.bg_sizing = $('#cert-bg-sizing').val();
        
        $('#certificate_settings').val(JSON.stringify(certSettings));
        return true;
    }

    function updateCanvasBgSize() {
        let size = certSettings.bg_sizing || 'stretch';
        let cssSize = '100% 100%';
        if (size === 'cover') cssSize = 'cover';
        if (size === 'contain') cssSize = 'contain';
        $('#certificate-canvas-container').css('background-size', cssSize);
        if (size === 'contain') {
            $('#certificate-canvas-container').css('background-position', 'center');
        } else {
            $('#certificate-canvas-container').css('background-position', 'top left');
        }
    }

    $(document).ready(function() {
        $('#global-font-family').val(certSettings.global_font || 'Roboto');
        $('#cert-bg-sizing').val(certSettings.bg_sizing || 'stretch');
        
        updateCanvasBgSize();

        $('#cert-bg-sizing').change(function() {
            certSettings.bg_sizing = $(this).val();
            updateCanvasBgSize();
        });
        
        $('#global-font-family').change(function() {
            certSettings.global_font = $(this).val();
            renderFields();
        });

        $('#cert-mode-tabs a').click(function(e) {
            e.preventDefault();
            $('#cert-mode-tabs a').removeClass('active');
            $(this).addClass('active');
            
            certSettings[currentMode] = fields;
            
            let newMode = $(this).data('mode');
            
            if (newMode === 'without_designation') {
                if (!certSettings.without_designation_touched) {
                    certSettings.without_designation = JSON.parse(JSON.stringify(certSettings.with_designation))
                        .filter(f => f.key !== 'participant_designation');
                }
                $('#btn-clone-layout').attr('title', "Clone layout from 'With Designation'");
            } else {
                $('#btn-clone-layout').attr('title', "Clone layout from 'Without Designation'");
            }
            
            currentMode = newMode;
            fields = certSettings[currentMode];
            
            $('#field-editor').hide();
            selectedFieldId = null;
            renderFields();
        });

        $('#btn-reset-layout').click(function() {
            if (confirm("Reset to default certificate template? This will reset all field positions and the background image.")) {
                // Reset background image to default certificate template
                $('#certificate-canvas-container').css('background-image', 'url("' + defaultCertBgUrl + '")');
                
                // Clear the file input so it doesn't re-upload old image
                $('#certificate_bg_image').val('');

                if (currentMode === 'with_designation') {
                    certSettings.with_designation = JSON.parse(JSON.stringify(defaultFieldsWithDes));
                    fields = certSettings.with_designation;
                } else {
                    certSettings.without_designation = JSON.parse(JSON.stringify(defaultFieldsWithDes)).filter(f => f.key !== 'participant_designation');
                    certSettings.without_designation_touched = true;
                    fields = certSettings.without_designation;
                }
                
                // Reset global font
                certSettings.global_font = 'Roboto';
                $('#cert-global-font').val('Roboto');
                
                // Mark that we're using default bg
                certSettings.use_default_bg = true;
                
                $('#field-editor').hide();
                selectedFieldId = null;
                renderFields();
            }
        });

        $('#btn-clone-layout').click(function() {
            let sourceMode = currentMode === 'with_designation' ? 'without_designation' : 'with_designation';
            let sourceName = sourceMode === 'with_designation' ? 'With Designation' : 'Without Designation';
            
            if (confirm("Are you sure you want to completely overwrite the current layout with the layout from '" + sourceName + "'?")) {
                if (currentMode === 'without_designation') {
                    certSettings.without_designation = JSON.parse(JSON.stringify(certSettings.with_designation))
                        .filter(f => f.key !== 'participant_designation');
                    certSettings.without_designation_touched = true;
                    fields = certSettings.without_designation;
                } else {
                    // Cloning from without to with
                    let designationField = certSettings.with_designation.find(f => f.key === 'participant_designation');
                    certSettings.with_designation = JSON.parse(JSON.stringify(certSettings.without_designation));
                    if (designationField) {
                        certSettings.with_designation.push(designationField);
                    } else {
                        certSettings.with_designation.push({ key: "participant_designation", label: "Participant Designation", x: 50.0, y: 58.0, size: 18, color: "#555555", align: "center", font: "global", text_transform: "none", bold: false, icon: "none" });
                    }
                    fields = certSettings.with_designation;
                }
                $('#field-editor').hide();
                selectedFieldId = null;
                renderFields();
            }
        });

        $('#btn-close-editor').click(function() {
            $('#field-editor').hide();
            selectedFieldId = null;
            $('.cert-field-draggable').removeClass('selected');
        });

        resizeCanvas();
        $(window).resize(resizeCanvas);
        $('#cert-orientation').change(resizeCanvas);

        $('#certificate_bg_image').change(function(e) {
            if (e.target.files && e.target.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#certificate-canvas-container').css('background-image', 'url("' + e.target.result + '")');
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        });

        renderFields();

        $('.btn-add-field').click(function() {
            let fieldKey = $(this).data('field');
            let fieldLabel = $(this).data('label');
            
            let defaultIcon = 'none';
            if (fieldKey === 'cooperative_address' || fieldKey === 'event_location') defaultIcon = 'location';
            else if (fieldKey === 'event_start_date') defaultIcon = 'calendar';

            let defaultAlign = 'left';
            if (fieldKey === 'participant_name' || fieldKey === 'participant_designation' || fieldKey === 'cooperative_name') {
                defaultAlign = 'center';
            }

            fields.push({
                key: fieldKey,
                label: fieldLabel,
                x: defaultAlign === 'center' ? 50 : 10,
                y: 10,
                size: 24,
                color: '#000000',
                font: 'global',
                text_transform: 'none',
                align: defaultAlign,
                bold: false,
                icon: defaultIcon
            });
            
            markTouched();
            renderFields();
            selectField(fields.length - 1);
        });

        $('#editor-font-size').on('input', function() {
            if (selectedFieldId !== null) {
                fields[selectedFieldId].size = parseInt($(this).val());
                $(`#field_${selectedFieldId}`).css('font-size', fields[selectedFieldId].size + 'px');
                markTouched();
            }
        });

        $('#editor-font-color').on('input', function() {
            if (selectedFieldId !== null) {
                fields[selectedFieldId].color = $(this).val();
                $(`#field_${selectedFieldId}`).css('color', fields[selectedFieldId].color);
                markTouched();
            }
        });

        $('#editor-font-family').change(function() {
            if (selectedFieldId !== null) {
                fields[selectedFieldId].font = $(this).val();
                markTouched();
                renderFields();
            }
        });

        $('#editor-text-transform').change(function() {
            if (selectedFieldId !== null) {
                fields[selectedFieldId].text_transform = $(this).val();
                $(`#field_${selectedFieldId}`).css('text-transform', fields[selectedFieldId].text_transform);
                markTouched();
            }
        });

        $('#editor-text-align').change(function() {
            if (selectedFieldId !== null) {
                fields[selectedFieldId].align = $(this).val();
                markTouched();
                renderFields();
                selectField(selectedFieldId);
            }
        });

        $('#editor-font-bold').change(function() {
            if (selectedFieldId !== null) {
                fields[selectedFieldId].bold = $(this).is(':checked');
                $(`#field_${selectedFieldId}`).css('font-weight', fields[selectedFieldId].bold ? 'bold' : 'normal');
                markTouched();
            }
        });

        $('#editor-icon').change(function() {
            if (selectedFieldId !== null) {
                fields[selectedFieldId].icon = $(this).val();
                markTouched();
                renderFields();
                selectField(selectedFieldId);
            }
        });

        $('#editor-prefix').on('input', function() {
            if (selectedFieldId !== null) {
                fields[selectedFieldId].prefix = $(this).val();
                markTouched();
                renderFields();
                selectField(selectedFieldId);
            }
        });

        $('#editor-suffix').on('input', function() {
            if (selectedFieldId !== null) {
                fields[selectedFieldId].suffix = $(this).val();
                markTouched();
                renderFields();
                selectField(selectedFieldId);
            }
        });

        $('#btn-remove-field').click(function() {
            markTouched();
            removeSelectedField();
        });
    });
</script>
@endsection
