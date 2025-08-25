@extends('Layouts.app')

@section('title', 'Apply for Job')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h2 class="text-center mb-4">Apply for {{ $job->title }}</h2>
            <form method="POST" action="{{ route('applications.store', $job->id) }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="cover_letter" class="form-label">Cover Letter (Optional)</label>
                    <textarea name="cover_letter" id="cover_letter" class="form-control @error('cover_letter') is-invalid @enderror">{{ old('cover_letter') }}</textarea>
                    @error('cover_letter')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="resume" class="form-label">Resume (PDF, max 2MB, Optional)</label>
                    <input type="file" name="resume" id="resume" class="form-control @error('resume') is-invalid @enderror" accept=".pdf">
                    @error('resume')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">If not uploaded, your profile resume will be used if available.</small>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Submit Application</button>
                    <a href="{{ route('applicant.dashboard') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
