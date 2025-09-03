@extends('Layouts.app')

@section('title', 'Edit Job Posting')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Edit Job Posting</h2>

        <!-- Display Validation Errors -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Edit Job Form -->
        <form action="{{ route('jobPosting.update', $job->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="title" class="form-label">Job Title</label>
                <input type="text" class="form-control" id="title" name="title"
                       value="{{ old('title', $job->title) }}" required>
            </div>

            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" class="form-control" id="location" name="location"
                       value="{{ old('location', $job->location) }}" required>
            </div>

            <div class="mb-3">
                <label for="type" class="form-label">Job Type</label>
                <select class="form-select" id="type" name="type" required>
                    <option value="full-time" {{ old('type', $job->type) === 'full-time' ? 'selected' : '' }}>Full-Time</option>
                    <option value="part-time" {{ old('type', $job->type) === 'part-time' ? 'selected' : '' }}>Part-Time</option>
                    <option value="contract" {{ old('type', $job->type) === 'contract' ? 'selected' : '' }}>Contract</option>
                    <option value="internship" {{ old('type', $job->type) === 'internship' ? 'selected' : '' }}>Internship</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="salary_min" class="form-label">Minimum Salary</label>
                <input type="number" class="form-control" id="salary_min" name="salary_min"
                       value="{{ old('salary_min', $job->salary_min) }}" required>
            </div>

            <div class="mb-3">
                <label for="salary_max" class="form-label">Maximum Salary</label>
                <input type="number" class="form-control" id="salary_max" name="salary_max"
                       value="{{ old('salary_max', $job->salary_max) }}" required>
            </div>

            <div class="mb-3">
                <label for="requirements" class="form-label">Requirements</label>
                <textarea class="form-control" id="requirements" name="requirements" rows="3" required>{{ old('requirements', $job->requirements) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Job Description</label>
                <textarea class="form-control" id="description" name="description" rows="5" required>{{ old('description', $job->description) }}</textarea>
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Job Status</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="open" {{ old('status', $job->status) === 'open' ? 'selected' : '' }}>Open</option>
                    <option value="closed" {{ old('status', $job->status) === 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success">Update Job</button>
            <a href="{{ route('employer.dashboard') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
