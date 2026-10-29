@php
    $isTemplate = ($qi === '__QI__');
    $qid = (!$isTemplate && isset($question->id)) ? $question->id : null;
    $qTitle = (!$isTemplate && $question) ? (is_array($question) ? ($question['title'] ?? '') : $question->title) : '';
    $qType = (!$isTemplate && $question) ? (is_array($question) ? ($question['type'] ?? 'text') : $question->type) : 'text';
    $qOptions = (!$isTemplate && $question) ? (is_array($question) ? ($question['options'] ?? '') : $question->options) : '';
    $qRequired = (!$isTemplate && $question) ? (is_array($question) ? (!empty($question['is_required'])) : (bool)$question->is_required) : false;
@endphp

<div class="card card-outline card-info question-row mb-3 shadow-sm" data-index="{{ $qi }}">
    <div class="card-header py-2 bg-light d-flex justify-content-between align-items-center">
        <h6 class="mb-0 text-info font-weight-bold">
            <i class="fas fa-question-circle mr-1"></i> Question <span class="question-number">#{{ is_numeric($qi) ? ($qi + 1) : 1 }}</span>
        </h6>
        <div>
            <button type="button" class="btn btn-tool text-danger remove-question-btn" title="Remove question">
                <i class="fas fa-trash-alt fa-lg"></i>
            </button>
        </div>
    </div>
    <div class="card-body py-3">
        @if($qid)
            <input type="hidden" name="questions[{{ $qi }}][id]" value="{{ $qid }}">
        @endif
        <input type="hidden" name="questions[{{ $qi }}][order]" value="{{ is_numeric($qi) ? $qi : 0 }}" class="question-order-input">

        <div class="row">
            {{-- Question Title --}}
            <div class="col-md-6">
                <div class="form-group mb-2">
                    <label class="font-weight-bold text-sm">Question Label / Title <span class="text-danger">*</span></label>
                    <input type="text" name="questions[{{ $qi }}][title]" class="form-control form-control-sm question-title-input" 
                           placeholder="e.g. Do you require hotel accommodation? or T-shirt Size" 
                           value="{{ $qTitle }}" required>
                </div>
            </div>

            {{-- Question Type --}}
            <div class="col-md-4">
                <div class="form-group mb-2">
                    <label class="font-weight-bold text-sm">Question Type <span class="text-danger">*</span></label>
                    <select name="questions[{{ $qi }}][type]" class="form-control form-control-sm question-type-select" required>
                        <option value="text" {{ $qType === 'text' ? 'selected' : '' }}>Text Input (Single text value)</option>
                        <option value="number" {{ $qType === 'number' ? 'selected' : '' }}>Number (Numeric value only)</option>
                        <option value="radio" {{ $qType === 'radio' ? 'selected' : '' }}>Radio Buttons (Single choice e.g. Yes/No)</option>
                        <option value="select" {{ $qType === 'select' ? 'selected' : '' }}>Dropdown / Select (Single choice from list)</option>
                        <option value="checkbox" {{ $qType === 'checkbox' ? 'selected' : '' }}>Checkbox (Multiple choice values)</option>
                    </select>
                </div>
            </div>

            {{-- Required Toggle --}}
            <div class="col-md-2 d-flex align-items-center">
                <div class="custom-control custom-switch mt-2">
                    <input type="checkbox" name="questions[{{ $qi }}][is_required]" value="1" 
                           class="custom-control-input" id="q_req_{{ $qi }}" 
                           {{ $qRequired ? 'checked' : '' }}>
                    <label class="custom-control-label text-sm font-weight-bold" for="q_req_{{ $qi }}">Mandatory?</label>
                </div>
            </div>
        </div>

        {{-- Options row (shown for radio, select, checkbox) --}}
        <div class="row question-options-wrapper mt-2" style="display: {{ in_array($qType, ['radio', 'select', 'checkbox']) ? 'flex' : 'none' }};">
            <div class="col-md-12">
                <div class="p-2 border rounded bg-white">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="font-weight-bold text-xs mb-0 text-secondary">
                            Options (Separated by comma) <span class="text-danger">*</span>
                        </label>
                        <div class="quick-preset-buttons">
                            <span class="text-xs text-muted mr-1">Quick presets:</span>
                            <button type="button" class="btn btn-xs btn-outline-info btn-preset" data-preset="Yes, No">Yes, No</button>
                            <button type="button" class="btn btn-xs btn-outline-info btn-preset" data-preset="Veg, Non-Veg">Veg, Non-Veg</button>
                            <button type="button" class="btn btn-xs btn-outline-info btn-preset" data-preset="S, M, L, XL, XXL">S, M, L, XL, XXL</button>
                        </div>
                    </div>
                    <input type="text" name="questions[{{ $qi }}][options]" 
                           class="form-control form-control-sm question-options-input" 
                           placeholder="e.g. Yes, No or Veg, Non-Veg, Vegan" 
                           value="{{ $qOptions }}">
                    <small class="form-text text-muted text-xs">
                        Enter each selectable option separated by a comma (e.g. <code>Yes, No</code>).
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
