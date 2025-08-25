@extends('Layouts.app')

@section('title', 'View Resume')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="text-center mb-4">Your Resume</h2>
            @if ($user->resume_path)
                <div class="mb-3">
                    <embed src="{{ Storage::url($user->resume_path) }}" type="application/pdf" width="100%" height="600px">
                    <p class="text-center mt-3">
                        <a href="{{ Storage::url($user->resume_path) }}" class="btn btn-primary" download>Download Resume</a>
                        <a href="{{ route('profile.show') }}" class="btn btn-secondary">Back to Profile</a>
                    </p>
                </div>
            @else
                <div class="alert alert-warning text-center">
                    No resume uploaded. Please upload a resume in your profile.
                </div>
                <p class="text-center">
                    <a href="{{ route('profile.show') }}" class="btn btn-secondary">Back to Profile</a>
                </p>
            @endif
        </div>
    </div>
@endsection
