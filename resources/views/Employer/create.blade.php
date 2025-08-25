@extends('layouts.app')

@section('title', 'Create Job Posting')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center mb-4">Create Job Posting</h2>
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            <form method="POST" action="{{ route('jobs.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label">Job Title</label>
                    <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                    @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" required>{{ old('description') }}</textarea>
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="location" class="form-label">Location</label>
                    <input type="text" name="location" id="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location') }}" required>
                    @error('location')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="type" class="form-label">Job Type</label>
                    <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
                        <option value="" disabled {{ old('type') ? '' : 'selected' }}>Select Job Type</option>
                        <option value="full-time" {{ old('type') === 'full-time' ? 'selected' : '' }}>Full-Time</option>
                        <option value="part-time" {{ old('type') === 'part-time' ? 'selected' : '' }}>Part-Time</option>
                        <option value="contract" {{ old('type') === 'contract' ? 'selected' : '' }}>Contract</option>
                    </select>
                    @error('type')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="salary_min" class="form-label">Minimum Salary (Optional)</label>
                    <input type="number" name="salary_min" id="salary_min" class="form-control @error('salary_min') is-invalid @enderror" value="{{ old('salary_min') }}" step="0.01" min="0">
                    @error('salary_min')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="salary_max" class="form-label">Maximum Salary (Optional)</label>
                    <input type="number" name="salary_max" id="salary_max" class="form-control @error('salary_max') is-invalid @enderror" value="{{ old('salary_max') }}" step="0.01" min="0">
                    @error('salary_max')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="requirements" class="form-label">Requirements (Optional)</label>
                    <textarea name="requirements" id="requirements" class="form-control @error('requirements') is-invalid @enderror">{{ old('requirements') }}</textarea>
                    @error('requirements')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="expires_at" class="form-label">Expires At (Optional)</label>
                    <input type="date" name="expires_at" id="expires_at" class="form-control @error('expires_at') is-invalid @enderror" value="{{ old('expires_at') }}">
                    @error('expires_at')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Create Job Posting</button>
                    <a href="{{ route('employer.dashboard') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
