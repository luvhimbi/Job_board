@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-success text-white">
                <h3 class="mb-0">Application Submitted Successfully!</h3>
            </div>
            <div class="card-body">
                <p class="text-muted">You have successfully applied for the following job:</p>

                <h4 class="mb-3">{{ $job->title }}</h4>
                <ul class="list-unstyled">
                    <li><strong>Company:</strong> {{ $job->company->name ?? 'N/A' }}</li>
                    <li><strong>Location:</strong> {{ $job->location }}</li>
                    <li><strong>Type:</strong> <span class="badge bg-info text-dark">{{ $job->type }}</span></li>
                    <li><strong>Description:</strong> {{ $job->description }}</li>
                    <li><strong>Requirements:</strong> {{ $job->requirements }}</li>
                    <li><strong>Salary:</strong>
                        @if($job->salary_min && $job->salary_max)
                            R{{ number_format($job->salary_min) }} - R{{ number_format($job->salary_max) }}
                        @else
                            Not Specified
                        @endif
                    </li>
                    <li><strong>Expires At:</strong> {{ \Carbon\Carbon::parse($job->expires_at)->format('d M Y') }}</li>
                </ul>

                <div class="alert alert-info mt-3">
                    <strong>Next Steps:</strong> The employer may contact you directly via email or phone if your application is shortlisted.
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('applications.my_applications') }}" class="btn btn-primary">View My Applications</a>
                <a href="{{ route('applicant.dashboard') }}" class="btn btn-secondary">Back to Job Listings</a>
            </div>
        </div>
    </div>
@endsection
