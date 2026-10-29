@extends('Admin.layouts.app')

@section('title', 'Add Subject Mock Test')

@push('styles')
<style>
  .mock-card {
    border: 0;
    border-radius: 18px;
    box-shadow: 0 10px 30px rgba(15, 23, 42, .08)
  }

  .mock-card .card-body {
    padding: 24px
  }

  .form-control,
  .form-select {
    min-height: 46px;
    border-radius: 12px
  }

  .ck-editor__editable_inline {
    min-height: 320px
  }
</style>
@endpush

@section('content')
<main class="main-content">
  <div class="page-body">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h1 class="mb-1 h4">Add Subject Mock Test</h1>
        <p class="mb-0 text-muted">One editor, one DB row, subject-wise mock test module.</p>
      </div>
      <a href="{{ route('admin.subject-mocktest.index') }}" class="btn btn-outline-secondary">Back</a>
    </div>

    <div class="card mock-card">
      <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('admin.subject-mocktest.store') }}" method="POST" enctype="multipart/form-data" id="mockTestForm">
          @csrf

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Subject Name <span class="text-danger">*</span></label>
              <select name="subject_id" class="form-select" required>
                <option value="">-- Select Subject --</option>
                @foreach($subjects as $subject)
                <option value="{{ $subject->subject_id }}" {{ old('subject_id') == $subject->subject_id ? 'selected' : '' }}>
                  {{ $subject->subject_name }}
                </option>
                @endforeach
              </select>
              @error('subject_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label class="form-label">Mocktest Title <span class="text-danger">*</span></label>
              <input type="text" name="title" class="form-control" placeholder="Mocktest Title" value="{{ old('title') }}" required>
              @error('title') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label class="form-label">Upload File (optional)</label>
              <input type="file" name="upload_file" class="form-control">
              @error('upload_file') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label class="form-label">Enter Max Mark <span class="text-danger">*</span></label>
              <input type="number" name="max_marks" class="form-control" placeholder="Enter Max Mark" min="0" value="{{ old('max_marks') }}" required>
              @error('max_marks') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
              <label class="form-label">Questions / Content <span class="text-danger">*</span></label>
              <textarea name="mock_test" id="mock_test" class="form-control">{{ old('mock_test') }}</textarea>
              <small class="text-muted">Isme multiple questions ek hi CKEditor me likho. Same HTML ek hi DB row me store hoga.</small>
              @error('mock_test') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="mt-4">
            <button type="submit" class="btn btn-primary">Save Mock Test</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
  let mockEditor;
  ClassicEditor.create(document.querySelector('#mock_test'), {
    toolbar: [
      'heading', '|',
      'bold', 'italic', 'link', '|',
      'bulletedList', 'numberedList', '|',
      'blockQuote', 'insertTable', 'undo', 'redo'
    ]
  }).then(editor => {
    mockEditor = editor;
  }).catch(error => console.error(error));

  document.getElementById('mockTestForm').addEventListener('submit', function() {
    if (mockEditor) {
      document.getElementById('mock_test').value = mockEditor.getData();
    }
  });
</script>
@endpush