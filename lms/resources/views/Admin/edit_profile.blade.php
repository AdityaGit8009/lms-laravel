@extends('Admin.layouts.app')

@section('title', 'Edit Profile')

@section('content')
<main class="main-content">
  <div class="page-body">
    <div class="page-hdr d-flex justify-content-between align-items-center">
      <div>
        <h1>Edit Profile</h1>
        <p>Update your account and training details.</p>
      </div>
      <a href="{{ route('profile') }}" class="btn btn-ghost btn-sm">
        <i class="bi bi-arrow-left"></i> Back to Profile
      </a>
    </div>

    <div class="card">
      <div class="card-body">
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
          @csrf

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Full Name</label>
              <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $student->name) }}" required>
              @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label class="form-label">College</label>
              <input type="text" name="college" class="form-control @error('college') is-invalid @enderror"
                value="{{ old('college', $student->college) }}" required>
              @error('college') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label class="form-label">Course</label>
              <select name="course" id="course" class="form-select @error('course') is-invalid @enderror" required>
                <option value="">Select Course</option>
                <option value="1" {{ old('course', $student->course) == '1' ? 'selected' : '' }}>Diploma</option>
                <option value="2" {{ old('course', $student->course) == '2' ? 'selected' : '' }}>B.Tech</option>
                <option value="3" {{ old('course', $student->course) == '3' ? 'selected' : '' }}>BCA</option>
                <option value="4" {{ old('course', $student->course) == '4' ? 'selected' : '' }}>PGDCA</option>
                <option value="5" {{ old('course', $student->course) == '5' ? 'selected' : '' }}>MCA</option>
                <option value="6" {{ old('course', $student->course) == '6' ? 'selected' : '' }}>M.Sc</option>
                <option value="7" {{ old('course', $student->course) == '7' ? 'selected' : '' }}>B.A</option>
                <option value="8" {{ old('course', $student->course) == '8' ? 'selected' : '' }}>B.Sc</option>
                <option value="9" {{ old('course', $student->course) == '9' ? 'selected' : '' }}>M.Tech</option>
              </select>
              @error('course') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label class="form-label">Branch</label>
              <input type="text" name="branch" class="form-control @error('branch') is-invalid @enderror"
                value="{{ old('branch', $student->branch) }}" required>
              @error('branch') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label class="form-label">Year</label>
              <select name="year" class="form-select @error('year') is-invalid @enderror" required>
                <option value="">Select Year</option>
                <option value="1" {{ old('year', $student->year) == '1' ? 'selected' : '' }}>First Year</option>
                <option value="2" {{ old('year', $student->year) == '2' ? 'selected' : '' }}>Second Year</option>
                <option value="3" {{ old('year', $student->year) == '3' ? 'selected' : '' }}>Third Year</option>
                <option value="4" {{ old('year', $student->year) == '4' ? 'selected' : '' }}>Fourth Year</option>
                <option value="5" {{ old('year', $student->year) == '5' ? 'selected' : '' }}>Fifth Year</option>
              </select>
              @error('year') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label class="form-label">Mobile</label>
              <input type="text" name="mobile" class="form-control @error('mobile') is-invalid @enderror"
                value="{{ old('mobile', $student->mobile) }}" required>
              @error('mobile') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label class="form-label">Gender</label>
              <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                <option value="">Select Gender</option>
                <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>Female</option>
              </select>
              @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
              <label class="form-label">Programme</label>
              <select name="pro" class="form-select @error('pro') is-invalid @enderror" required>
                <option value="">Select Programme</option>
                <option value="12" {{ old('pro', $student->program) == '12' ? 'selected' : '' }}>Summer Training 2025</option>
                <option value="13" {{ old('pro', $student->program) == '13' ? 'selected' : '' }}>In House Training</option>
                <option value="15" {{ old('pro', $student->program) == '15' ? 'selected' : '' }}>Certification Programme</option>
                <option value="16" {{ old('pro', $student->program) == '16' ? 'selected' : '' }}>Virtual Internship</option>
                <option value="8" {{ old('pro', $student->program) == '8' ? 'selected' : '' }}>Apprenticeship-2025</option>
                <option value="11" {{ old('pro', $student->program) == '11' ? 'selected' : '' }}>Full Stack Development</option>
              </select>
              @error('pro') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
              <label class="form-label">Location</label>
              <select name="location" class="form-select @error('location') is-invalid @enderror" required>
                <option value="">Select Location</option>
                <option value="House" {{ old('location', $student->location) == 'House' ? 'selected' : '' }}>Softpro House, 3/213 Sector-J, Jankipuram, Lucknow - 226021</option>
                <option value="Academy" {{ old('location', $student->location) == 'Academy' ? 'selected' : '' }}>Softpro India Full Stack Academy, 1/6 Vastu Khand, Gomti Nagar, Lucknow - 226010</option>
                <option value="Noida" {{ old('location', $student->location) == 'Noida' ? 'selected' : '' }}>Softpro India Computer Technologies P Limited, Creatons Business Park, Ground Floor, H-35, Sector 63, Noida - 201301</option>
              </select>
              @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-12">
              <label class="form-label">Profile Photo</label>
              <input type="file" name="pic" class="form-control @error('pic') is-invalid @enderror">
              @error('pic') <div class="invalid-feedback">{{ $message }}</div> @enderror

              @if($student->photo)
              <div class="mt-3">
                <img src="{{ asset('storage/' . $student->photo) }}" alt="Profile" style="width:100px;height:100px;border-radius:50%;object-fit:cover;">
              </div>
              @endif
            </div>
          </div>

          <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary">
              <i class="bi bi-check-circle"></i> Update Profile
            </button>
            <a href="{{ route('profile') }}" class="btn btn-secondary">
              Cancel
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
</main>
@endsection