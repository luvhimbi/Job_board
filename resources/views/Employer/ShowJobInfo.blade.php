@extends('Layouts.app')

@section('title', 'Job Details')

@section('content')
    <div class="container mt-4">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">{{ $job->title }}</h4>
                <span class="badge bg-light text-dark">{{ ucfirst($job->status) }}</span>
            </div>

            <div class="card-body">
                <p><strong>Location:</strong> {{ $job->location }}</p>
                <p><strong>Type:</strong> <span class="badge bg-info text-dark">{{ ucfirst($job->type) }}</span></p>
                <p><strong>Description:</strong> {{ $job->description }}</p>
                <p><strong>Requirements:</strong> {{ $job->requirements }}</p>
                <p><strong>Salary Range:</strong>
                    @if($job->salary_min && $job->salary_max)
                        R{{ number_format($job->salary_min) }} - R{{ number_format($job->salary_max) }}
                    @else
                        Not Specified
                    @endif
                </p>
                <p><strong>Created On:</strong> {{ $job->created_at->format('M d, Y') }}</p>

                <hr>

                <h5>Applications Received:
                    <span class="badge bg-success">{{ $job->applications_count }}</span>
                </h5>

                <div class="mt-3">
                    <a href="{{ route('jobPosting.edit', $job->id) }}" class="btn btn-warning">Edit Job</a>
                    <form action="{{ route('jobs.destroy', $job->id) }}" method="POST" class="d-inline"
                          onsubmit="return confirm('Are you sure you want to delete this job posting?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete Job</button>
                    </form>
                    <a href="{{ route('employer.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
@endsection
