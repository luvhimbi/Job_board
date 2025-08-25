@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h2 class="text-center mb-4">Your Profile</h2>

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <div class="row">
                <!-- Left Column: Current Profile Details -->
                <div class="col-md-6">
                    <h4>Current Profile Information</h4>
                    <div class="mb-3">
                        <strong>First Name:</strong> {{ $user->firstname ?? 'Not set' }}
                    </div>
                    <div class="mb-3">
                        <strong>Last Name:</strong> {{ $user->lastname ?? 'Not set' }}
                    </div>
                    <div class="mb-3">
                        <strong>Email:</strong> {{ $user->email }}
                    </div>
                    <div class="mb-3">
                        <strong>Role:</strong> {{ ucfirst($user->role) }}
                    </div>
                    @if ($user->role === 'applicant')
                        <div class="mb-3">
                            <strong>Resume:</strong>
                            @if ($user->resume_path)
                                <a href="{{ route('profile.resume') }}">View Resume</a>
                            @else
                                Not uploaded
                            @endif
                        </div>
                    @endif
                    @if ($user->role === 'employer')
                        <div class="mb-3">
                            <strong>Company Name:</strong> {{ $company?->name ?? 'Not set' }}
                        </div>
                        <div class="mb-3">
                            <strong>Company Description:</strong> {{ $company?->description ?? 'Not set' }}
                        </div>
                        <div class="mb-3">
                            <strong>Company Location:</strong> {{ $company?->location ?? 'Not set' }}
                        </div>
                        <div class="mb-3">
                            <strong>Company Website:</strong>
                            @if ($company?->website)
                                <a href="{{ $company->website }}" target="_blank">{{ $company->website }}</a>
                            @else
                                Not set
                            @endif
                        </div>
                        <div class="mb-3">
                            <strong>Company Logo:</strong>
                            @if ($company?->logo)
                                <a href="{{ Storage::url($company->logo) }}" target="_blank">View Logo</a>
                            @else
                                Not set
                            @endif
                        </div>
                    @endif
                </div>
                <!-- Right Column: Update Form -->
                <div class="col-md-6">
                    <h4>Update Profile</h4>
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="firstname" class="form-label">First Name</label>
                            <input type="text" name="firstname" id="firstname" class="form-control @error('firstname') is-invalid @enderror" value="{{ old('firstname', $user->firstname) }}">
                            @error('firstname')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="lastname" class="form-label">Last Name</label>
                            <input type="text" name="lastname" id="lastname" class="form-control @error('lastname') is-invalid @enderror" value="{{ old('lastname', $user->lastname) }}">
                            @error('lastname')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @if ($user->role === 'applicant')
                            <div class="mb-3">
                                <label for="resume" class="form-label">Resume (PDF, max 2MB)</label>
                                <input type="file" name="resume" id="resume" class="form-control @error('resume') is-invalid @enderror" accept=".pdf">
                                @error('resume')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                        @if ($user->role === 'employer')
                            <div class="mb-3">
                                <label for="name" class="form-label">Company Name</label>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $company?->name ?? '') }}" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Company Description</label>
                                <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror">{{ old('description', $company?->description ?? '') }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="location" class="form-label">Company Location</label>
                                <input type="text" name="location" id="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location', $company?->location ?? '') }}">
                                @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="website" class="form-label">Company Website</label>
                                <input type="url" name="website" id="website" class="form-control @error('website') is-invalid @enderror" value="{{ old('website', $company?->website ?? '') }}">
                                @error('website')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="logo" class="form-label">Company Logo (JPG/PNG, max 2MB)</label>
                                <input type="file" name="logo" id="logo" class="form-control @error('logo') is-invalid @enderror" accept=".jpg,.png">
                                @error('logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
