@extends('Admin.layouts.app')

@section('title', $form ? 'Edit Feedback Form' : 'Create Feedback Form')

@section('content')
<main class="main-content">
    <div class="page-body p-3 feedback-builder-page">
        <form id="feedbackForm" method="POST" action="{{ $form ? route('admin.feedback.update', $form->form_id) : route('admin.feedback.store') }}">
            @csrf

            <div class="card border-0 shadow-sm rounded-4 mb-3 feedback-filter-card">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Select Session</label>
                            <select name="session_ids[]" id="session_ids" class="form-select feedback-multi" multiple>
                                <option value="all" {{ $form && is_array($form->session_ids) && in_array('all', $form->session_ids) ? 'selected' : '' }}>All</option>
                                @foreach($sessions as $session)
                                <option value="{{ $session->session_id }}"
                                    {{ $form && is_array($form->session_ids) && in_array((string)$session->session_id, array_map('strval', $form->session_ids)) ? 'selected' : '' }}>
                                    {{ $session->session_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Select Center</label>
                            <select name="center_ids[]" id="center_ids" class="form-select feedback-multi" multiple>
                                @if($form && is_array($form->center_ids) && in_array('all', $form->center_ids))
                                <option value="all" selected>All</option>
                                @endif
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Select Programme</label>
                            <select name="programme_ids[]" id="programme_ids" class="form-select feedback-multi" multiple>
                                @if($form && is_array($form->programme_ids) && in_array('all', $form->programme_ids))
                                <option value="all" selected>All</option>
                                @endif
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Select Technology</label>
                            <select name="technology_ids[]" id="technology_ids" class="form-select feedback-multi" multiple>
                                @if($form && is_array($form->technology_ids) && in_array('all', $form->technology_ids))
                                <option value="all" selected>All</option>
                                @endif
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Select Batch</label>
                            <select name="batch_ids[]" id="batch_ids" class="form-select feedback-multi" multiple>
                                @if($form && is_array($form->batch_ids) && in_array('all', $form->batch_ids))
                                <option value="all" selected>All</option>
                                @endif
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Form Status</label>
                            <select name="form_status" class="form-select">
                                <option value="open" {{ $form && $form->form_status === 'open' ? 'selected' : '' }}>Open</option>
                                <option value="close" {{ $form && $form->form_status === 'close' ? 'selected' : '' }}>Close</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 mb-3 google-head-card">
                <div class="card-body p-4">

                    <input type="text"
                        name="title"
                        id="form_title"
                        class="form-control form-title-input mb-2"
                        value="{{ $form->title ?? '' }}"
                        placeholder="Untitled form"
                        required>

                    <textarea name="description"
                        id="form_description"
                        class="form-control form-desc-input ps-1"
                        rows="2"
                        placeholder="Form description">{{ $form->description ?? '' }}</textarea>

                </div>
            </div>

            <div id="questionsContainer"></div>

            <input type="hidden" name="questions_json" id="questions_json">

            <div class="d-flex flex-wrap gap-2 mt-3">
                <button type="button" class="btn btn-orange" id="addQuestionBtn">+ Add question</button>
                <button type="button" class="btn btn-orange" id="previewBtn" data-bs-toggle="modal" data-bs-target="#previewModal">Preview</button>
                <button type="submit" class="btn btn-orange">{{ $form ? 'Update' : 'Submit' }}</button>
            </div>
        </form>
    </div>
</main>

<div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header">
                <h5 class="modal-title d-flex align-items-center gap-2">
                    <i class="fa fa-eye text-primary"></i>
                    <span>Form Preview</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body bg-light" id="previewBody"></div>
        </div>
    </div>
</div>
@endsection


@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
    .feedback-builder-page {
        background: #f3edf9;
        min-height: 100vh;
    }

    .feedback-filter-card {
        position: relative;
        z-index: 20;
        border-radius: 10px !important;
        box-shadow: none !important;
        border: 1px solid #e4e0eb !important;
        overflow: visible !important;
    }

    .feedback-filter-card .card-body {
        padding: 16px 18px;
    }

    .feedback-filter-card .col-md-4 {
        position: relative;
        z-index: 1;
    }

    .feedback-filter-card .col-md-4:focus-within {
        z-index: 999;
    }

    .feedback-filter-card label {
        font-size: 13px;
        font-weight: 500 !important;
        color: #5f6368;
        margin-bottom: 8px;
    }

    .choices {
        position: relative !important;
        z-index: 100;
        width: 100%;
        margin-bottom: 0;
    }

    .choices__inner {
        min-height: 40px !important;
        background: #fff !important;
        border: 1px solid #d6d6d6 !important;
        border-radius: 4px !important;
        padding: 4px 8px !important;
        font-size: 14px;
        box-shadow: none !important;
    }

    .choices.is-focused .choices__inner,
    .choices.is-open .choices__inner {
        border-color: #c9c9c9 !important;
        box-shadow: none !important;
    }

    .choices__list--multiple .choices__item {
        background: #0b7285 !important;
        border: 1px solid #0b7285 !important;
        border-radius: 14px !important;
        font-size: 12px !important;
        padding: 4px 8px !important;
    }

    .choices__list--dropdown,
    .choices__list[aria-expanded] {
        position: absolute !important;
        top: 100% !important;
        left: 0 !important;
        width: 100% !important;
        z-index: 9999 !important;
        background: #fff !important;
        border: 1px solid #d6d6d6 !important;
        border-radius: 4px !important;
        box-shadow: none !important;
        max-height: 220px !important;
        overflow-y: auto !important;
    }

    .google-head-card {
        border-top: 5px solid #ef6c2f;
        border-radius: 10px !important;
        box-shadow: none !important;
        border: 1px solid #e4e0eb !important;
    }

    .google-head-card .card-body {
        padding: 18px 20px 12px;
    }

    .form-title-input {
        font-size: 18px;
        font-weight: 400;
        color: #3c4043;
        border: none !important;
        box-shadow: none !important;
        background: transparent !important;
        padding: 0;
    }

    .form-title-input::placeholder {
        color: #5f6368;
        opacity: 1;
    }

    .form-desc-input {
        border: none !important;
        box-shadow: none !important;
        background: transparent !important;
        padding: 0;
        color: #5f6368;
        font-size: 14px;
        resize: none;
    }

    .form-desc-input::placeholder {
        color: #5f6368;
        opacity: 1;
    }

    #questionsContainer {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .question-card {
        border-radius: 10px !important;
        border: 1px solid #e4e0eb !important;
        background: #fff !important;
        box-shadow: none !important;
        padding: 14px 18px !important;
        margin-bottom: 0 !important;
    }

    .question-text {
        border: none !important;
        border-bottom: 1px solid #d6d6d6 !important;
        border-radius: 0 !important;
        background: transparent !important;
        box-shadow: none !important;
        padding: 8px 0 !important;
        font-size: 15px !important;
        font-weight: 500;
        color: #202124;
    }

    .question-text:focus {
        border-bottom: 1px solid #d6d6d6 !important;
        box-shadow: none !important;
    }

    .question-card .form-control,
    .question-card textarea {
        border: none !important;
        border-bottom: 1px dashed #dadce0 !important;
        border-radius: 0 !important;
        background: transparent !important;
        box-shadow: none !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        font-size: 14px;
    }

    .question-card .form-control:focus,
    .question-card textarea:focus {
        box-shadow: none !important;
    }

    .question-type {
        border: 1px solid #dadce0 !important;
        border-radius: 2px !important;
        background: #fff !important;
        min-height: 38px;
        font-size: 14px;
        padding: 6px 30px 6px 10px !important;
        box-shadow: none !important;
    }

    .question-handle {
        width: 26px;
        height: 26px;
        min-width: 26px;
        border: none;
        background: transparent;
        color: #b0b0b0;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: grab !important;
        padding: 0;
        margin-top: 4px;
    }

    .question-handle:hover {
        color: #8a8a8a;
        background: transparent;
    }

    .question-handle i {
        font-size: 13px;
    }

    .delete-question-top {
        width: 24px;
        height: 24px;
        min-width: 24px;
        border: none;
        background: transparent;
        color: #b0b0b0;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0;
        margin-top: 7px;
    }

    .delete-question-top:hover {
        color: #d93025;
        background: transparent;
    }

    .delete-question-top i {
        font-size: 13px;
    }

    .question-body {
        margin-top: 4px;
    }

    .option-row {
        margin-bottom: 8px !important;
    }

    .option-row .option-input {
        border: none !important;
        border-bottom: 1px solid #dadce0 !important;
        border-radius: 0 !important;
        background: transparent !important;
        font-size: 14px;
    }

    .remove-option {
        border: none !important;
        background: transparent !important;
        color: #9aa0a6 !important;
        padding: 2px 4px !important;
        box-shadow: none !important;
    }

    .remove-option:hover {
        color: #d93025 !important;
        background: transparent !important;
    }

    .add-option-btn {
        color: #5f6368 !important;
        font-size: 14px;
        padding: 0 !important;
        border: none !important;
        background: transparent !important;
        box-shadow: none !important;
    }

    .add-option-btn:hover {
        color: #5f6368 !important;
        text-decoration: none !important;
    }

    .question-card .border-top {
        border-top: 1px solid #e0e0e0 !important;
        margin-top: 10px !important;
        padding-top: 10px !important;
    }

    .form-check-input.question-required {
        width: 32px;
        height: 16px;
        margin-top: 0;
        cursor: pointer;
        box-shadow: none !important;
    }

    .form-check-label {
        font-size: 14px;
        color: #3c4043;
    }

    .btn-orange {
        background: #ef6c2f;
        border: 1px solid #ef6c2f;
        color: #fff;
        border-radius: 3px;
        font-size: 14px;
        font-weight: 500;
        padding: 7px 14px;
        box-shadow: none !important;
    }

    .btn-orange:hover {
        background: #e15d20;
        border-color: #e15d20;
        color: #fff;
    }

    .btn-outline-dark {
        border-radius: 3px;
        font-size: 14px;
        padding: 7px 14px;
        box-shadow: none !important;
    }

    .sortable-ghost {
        opacity: .7;
        background: #fafafa;
        border: 1px dashed #b39ddb !important;
    }

    .sortable-chosen {
        box-shadow: none !important;
    }

    #previewModal .modal-content {
        border-radius: 10px !important;
    }

    .google-head-card {
        border-top: 6px solid #ef6c2f;
        border-radius: 12px !important;
        border: 1px solid #dadce0 !important;
        background: #fff;
    }

    .google-head-card .card-body {
        padding: 24px 24px 18px;
    }

    /* TITLE */
    .form-title-input {
        font-size: 28px;
        font-weight: 400;
        color: #202124;
        border: none !important;
        background: transparent !important;
        padding: 0;
    }

    .form-title-input:focus {
        outline: none;
        box-shadow: none !important;
    }

    /* DESCRIPTION */
    .form-desc-input {
        font-size: 14px;
        color: #5f6368;
        border: none !important;
        background: transparent !important;
        padding: 0;
        resize: none;
    }

    .form-desc-input:focus {
        outline: none;
        box-shadow: none !important;
    }

    /* REMOVE EXTRA BOOTSTRAP EFFECT */
    .form-control {
        box-shadow: none !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

<script>
    const existingQuestions = @json($form ? $form -> questions_json : []);
    const editForm = @json($form);
    let questionSeed = 0;
    let choicesInstances = {};

    function makeUid() {
        questionSeed++;
        return 'q_' + Date.now() + '_' + questionSeed;
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.innerText = text ?? '';
        return div.innerHTML;
    }

    function initChoice(id) {
        const el = document.getElementById(id);
        if (!el) return;

        if (choicesInstances[id]) {
            choicesInstances[id].destroy();
        }

        choicesInstances[id] = new Choices(el, {
            removeItemButton: true,
            shouldSort: false,
            searchEnabled: true,
            placeholder: true,
            placeholderValue: 'Select option',
            itemSelectText: ''
        });
    }

    function initAllChoices() {
        ['session_ids', 'center_ids', 'programme_ids', 'technology_ids', 'batch_ids'].forEach(id => {
            initChoice(id);
        });
    }

    function getValues(id) {
        const instance = choicesInstances[id];
        if (!instance) return [];
        const value = instance.getValue(true);
        return Array.isArray(value) ? value : (value ? [value] : []);
    }

    function setSelectChoices(id, rows, selectedValues = []) {
        const instance = choicesInstances[id];
        if (!instance) return;

        const selected = selectedValues.map(String);

        const data = [{
            value: 'all',
            label: 'All',
            selected: selected.includes('all')
        }];

        rows.forEach(row => {
            data.push({
                value: String(row.id),
                label: row.text,
                selected: selected.includes(String(row.id))
            });
        });

        instance.clearStore();
        instance.setChoices(data, 'value', 'label', true);
    }

    async function postCascade(url, payload) {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        return await response.json();
    }

    async function loadCenters(selected = []) {
        const data = await postCascade("{{ route('admin.feedback.cascade.centers') }}", {
            session_ids: getValues('session_ids')
        });

        if (data.success) {
            setSelectChoices('center_ids', data.data, selected);
        }
    }

    async function loadProgrammes(selected = []) {
        const data = await postCascade("{{ route('admin.feedback.cascade.programmes') }}", {
            session_ids: getValues('session_ids'),
            center_ids: getValues('center_ids')
        });

        if (data.success) {
            setSelectChoices('programme_ids', data.data, selected);
        }
    }

    async function loadTechnologies(selected = []) {
        const data = await postCascade("{{ route('admin.feedback.cascade.technologies') }}", {
            session_ids: getValues('session_ids'),
            programme_ids: getValues('programme_ids')
        });

        if (data.success) {
            setSelectChoices('technology_ids', data.data, selected);
        }
    }

    async function loadBatches(selected = []) {
        const data = await postCascade("{{ route('admin.feedback.cascade.batches') }}", {
            session_ids: getValues('session_ids'),
            programme_ids: getValues('programme_ids'),
            technology_ids: getValues('technology_ids')
        });

        if (data.success) {
            setSelectChoices('batch_ids', data.data, selected);
        }
    }

    async function onSessionChange() {
        await loadCenters([]);
        setSelectChoices('programme_ids', [], []);
        setSelectChoices('technology_ids', [], []);
        setSelectChoices('batch_ids', [], []);
    }

    async function onCenterChange() {
        await loadProgrammes([]);
        setSelectChoices('technology_ids', [], []);
        setSelectChoices('batch_ids', [], []);
    }

    async function onProgrammeChange() {
        await loadTechnologies([]);
        setSelectChoices('batch_ids', [], []);
    }

    async function onTechnologyChange() {
        await loadBatches([]);
    }

    function bindCascadeEvents() {
        document.getElementById('session_ids').addEventListener('change', onSessionChange);
        document.getElementById('center_ids').addEventListener('change', onCenterChange);
        document.getElementById('programme_ids').addEventListener('change', onProgrammeChange);
        document.getElementById('technology_ids').addEventListener('change', onTechnologyChange);
    }

    async function preloadEditCascade() {
        if (!editForm) {
            setSelectChoices('center_ids', [], []);
            setSelectChoices('programme_ids', [], []);
            setSelectChoices('technology_ids', [], []);
            setSelectChoices('batch_ids', [], []);
            return;
        }

        await loadCenters(editForm.center_ids || []);
        await loadProgrammes(editForm.programme_ids || []);
        await loadTechnologies(editForm.technology_ids || []);
        await loadBatches(editForm.batch_ids || []);
    }

    function questionCardHtml(q = {}) {
        const uid = q.uid || makeUid();
        const type = q.type || 'short_answer';
        const text = q.text || '';
        const required = q.required ? 'checked' : '';
        const options = Array.isArray(q.options) && q.options.length ? q.options : ['Option 1'];

        return `
        <div class="question-card" data-uid="${uid}">
            <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                <button type="button" class="question-handle" title="Drag to reorder">
                    <i class="bi bi-grip-vertical"></i>
                </button>

                <div class="flex-grow-1">
                    <input type="text" class="form-control question-text" placeholder="Question" value="${escapeHtml(text)}">
                </div>

                <div style="width:160px">
                    <select class="form-select question-type">
                        <option value="short_answer" ${type === 'short_answer' ? 'selected' : ''}>Short answer</option>
                        <option value="paragraph" ${type === 'paragraph' ? 'selected' : ''}>Paragraph</option>
                        <option value="dropdown" ${type === 'dropdown' ? 'selected' : ''}>Dropdown</option>
                        <option value="radio" ${type === 'radio' ? 'selected' : ''}>Radio</option>
                        <option value="checkbox" ${type === 'checkbox' ? 'selected' : ''}>Checkbox</option>
                        <option value="rating" ${type === 'rating' ? 'selected' : ''}>Rating</option>
                    </select>
                </div>

                <button type="button" class="delete-question-top" title="Delete question">
                    <i class="bi bi-trash"></i>
                </button>
            </div>

            <div class="question-body"></div>

            <div class="d-flex justify-content-between align-items-center border-top">
                <button type="button" class="add-option-btn">
                    Add option
                </button>

                <div class="d-flex align-items-center gap-2">
                    <label class="form-check-label mb-0">Required</label>
                    <div class="form-check form-switch m-0">
                        <input class="form-check-input question-required" type="checkbox" ${required}>
                    </div>
                </div>
            </div>

            <input type="hidden" class="question-options-json" value='${JSON.stringify(options)}'>
        </div>
    `;
    }

    function optionRowHtml(type, value = '') {
        let icon = '';
        if (type === 'radio') icon = '<i class="bi bi-record-circle text-muted"></i>';
        if (type === 'checkbox') icon = '<i class="bi bi-square text-muted"></i>';
        if (type === 'dropdown') icon = '<i class="bi bi-caret-down-fill text-muted"></i>';

        return `
        <div class="option-row d-flex align-items-center gap-2">
            ${icon}
            <input type="text" class="form-control option-input" value="${escapeHtml(value)}" placeholder="Option">
            <button type="button" class="remove-option">
                <i class="bi bi-x"></i>
            </button>
        </div>
    `;
    }

    function syncOptions(card) {
        const options = [...card.querySelectorAll('.option-input')]
            .map(i => i.value.trim())
            .filter(Boolean);

        card.querySelector('.question-options-json').value = JSON.stringify(options.length ? options : ['Option 1']);
    }

    function bindOptionEvents(card) {
        card.querySelectorAll('.remove-option').forEach(btn => {
            btn.onclick = function() {
                const rows = card.querySelectorAll('.option-row');
                if (rows.length > 1) {
                    this.closest('.option-row').remove();
                    syncOptions(card);
                }
            };
        });

        card.querySelectorAll('.option-input').forEach(input => {
            input.oninput = function() {
                syncOptions(card);
            };
        });
    }

    function renderQuestionBody(card) {
        const type = card.querySelector('.question-type').value;
        const body = card.querySelector('.question-body');
        const addOptionBtn = card.querySelector('.add-option-btn');
        const options = JSON.parse(card.querySelector('.question-options-json').value || '[]');

        if (type === 'short_answer') {
            addOptionBtn.style.visibility = 'hidden';
            body.innerHTML = `<input type="text" class="form-control" placeholder="Short answer" disabled>`;
            return;
        }

        if (type === 'paragraph') {
            addOptionBtn.style.visibility = 'hidden';
            body.innerHTML = `<textarea class="form-control" rows="3" placeholder="Long answer" disabled></textarea>`;
            return;
        }

        if (type === 'rating') {
            addOptionBtn.style.visibility = 'hidden';
            body.innerHTML = `<div>${'<i class="fa fa-star text-warning me-1"></i>'.repeat(5)}</div>`;
            return;
        }

        addOptionBtn.style.visibility = 'visible';
        body.innerHTML = `<div class="options-wrap"></div>`;
        const wrap = body.querySelector('.options-wrap');

        options.forEach(option => {
            wrap.insertAdjacentHTML('beforeend', optionRowHtml(type, option));
        });

        bindOptionEvents(card);
    }

    function addQuestion(q = {}) {
        const container = document.getElementById('questionsContainer');
        container.insertAdjacentHTML('beforeend', questionCardHtml(q));
        const card = container.lastElementChild;

        renderQuestionBody(card);

        card.querySelector('.question-type').addEventListener('change', function() {
            renderQuestionBody(card);
        });

        const deleteBtnTop = card.querySelector('.delete-question-top');
        if (deleteBtnTop) {
            deleteBtnTop.addEventListener('click', function() {
                card.remove();
            });
        }

        card.querySelector('.add-option-btn').addEventListener('click', function() {
            const type = card.querySelector('.question-type').value;
            const wrap = card.querySelector('.options-wrap');
            if (!wrap) return;
            wrap.insertAdjacentHTML('beforeend', optionRowHtml(type, ''));
            bindOptionEvents(card);
            syncOptions(card);
        });
    }

    function collectQuestions() {
        const cards = document.querySelectorAll('.question-card');
        let list = [];

        cards.forEach((card, index) => {
            syncOptions(card);

            const type = card.querySelector('.question-type').value;
            const item = {
                uid: card.dataset.uid,
                order: index + 1,
                text: card.querySelector('.question-text').value.trim(),
                type: type,
                required: card.querySelector('.question-required').checked
            };

            if (['dropdown', 'radio', 'checkbox'].includes(type)) {
                item.options = JSON.parse(card.querySelector('.question-options-json').value || '[]');
            }

            list.push(item);
        });

        return list;
    }

    function fillPreview() {
        const title = document.getElementById('form_title').value || 'Untitled form';
        const desc = document.getElementById('form_description').value || 'Form description';
        const questions = collectQuestions();

        let html = `
            <div class="card border-0 shadow-sm rounded-4 mb-3 google-head-card">
                <div class="card-body">
                    <h2>${escapeHtml(title)}</h2>
                    <p class="text-muted mb-0">${escapeHtml(desc)}</p>
                </div>
            </div>
        `;

        questions.forEach((q, i) => {
            html += `<div class="card border-0 shadow-sm rounded-4 mb-3"><div class="card-body">`;
            html += `<label class="fw-bold d-block mb-3">${i + 1}. ${escapeHtml(q.text)} ${q.required ? '<span class="text-danger">*</span>' : ''}</label>`;

            if (q.type === 'short_answer') {
                html += `<input type="text" class="form-control" placeholder="Short answer" disabled>`;
            } else if (q.type === 'paragraph') {
                html += `<textarea class="form-control" rows="3" placeholder="Long answer" disabled></textarea>`;
            } else if (q.type === 'dropdown') {
                html += `<select class="form-select" disabled>
                            <option>Select an option</option>
                            ${(q.options || []).map(opt => `<option>${escapeHtml(opt)}</option>`).join('')}
                         </select>`;
            } else if (q.type === 'radio') {
                html += (q.options || []).map(opt => `
                    <div class="form-check mb-2">
                        <input type="radio" class="form-check-input" disabled>
                        <label class="form-check-label">${escapeHtml(opt)}</label>
                    </div>
                `).join('');
            } else if (q.type === 'checkbox') {
                html += (q.options || []).map(opt => `
                    <div class="form-check mb-2">
                        <input type="checkbox" class="form-check-input" disabled>
                        <label class="form-check-label">${escapeHtml(opt)}</label>
                    </div>
                `).join('');
            } else if (q.type === 'rating') {
                html += `<div>${'<i class="fa fa-star text-warning me-1"></i>'.repeat(5)}</div>`;
            }

            html += `</div></div>`;
        });

        document.getElementById('previewBody').innerHTML = html;
    }

    document.getElementById('feedbackForm').addEventListener('submit', function(e) {
        const questions = collectQuestions();

        if (!questions.length) {
            e.preventDefault();
            alert('At least one question is required.');
            return;
        }

        const invalid = questions.some(q => !q.text);
        if (invalid) {
            e.preventDefault();
            alert('Every question must have question text.');
            return;
        }

        document.getElementById('questions_json').value = JSON.stringify(questions);
    });

    document.getElementById('addQuestionBtn').addEventListener('click', function() {
        addQuestion({
            text: '',
            type: 'short_answer',
            options: ['Option 1'],
            required: false
        });
    });

    document.getElementById('previewBtn').addEventListener('click', fillPreview);

    new Sortable(document.getElementById('questionsContainer'), {
        animation: 180,
        handle: '.question-handle'
    });

    document.addEventListener('DOMContentLoaded', async function() {
        initAllChoices();
        bindCascadeEvents();

        await preloadEditCascade();

        if (existingQuestions.length) {
            existingQuestions.forEach(q => addQuestion(q));
        } else {
            addQuestion({
                text: '',
                type: 'short_answer',
                options: ['Option 1'],
                required: false
            });
        }
    });
</script>
@endpush