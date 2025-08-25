@extends('layouts.app')

@section('title', 'Your Job Applicants')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="text-center mb-4">Your Job Applicants</h2>
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if ($jobs->isEmpty())
                <p class="text-center">No job postings yet. Create one to get started!</p>
            @else
                @foreach ($jobs as $job)
                    <div class="mb-4">
                        <h4>{{ $job->title }} ({{ ucfirst($job->status) }})</h4>
                        <p>
                            <strong>Location:</strong> {{ $job->location }} |
                            <strong>Type:</strong> {{ ucfirst($job->type) }} |
                            <strong>Posted On:</strong> {{ $job->created_at->format('M d, Y') }}
                        </p>
                        @if ($job->applications->isEmpty())
                            <p>No applications for this job yet.</p>
                        @else
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Cover Letter</th>
                                    <th>Resume</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($job->applications as $application)
                                    <tr>
                                        <td>{{ $application->user->firstname }} {{ $application->user->lastname }}</td>
                                        <td>{{ $application->user->email }}</td>
                                        <td>{{ ucfirst($application->status) }}</td>
                                        <td>{{ Str::limit($application->cover_letter ?? 'Not provided', 50) }}</td>
                                        <td>
                                            @if ($application->resume_path)
                                                <a href="{{ Storage::url($application->resume_path) }}" target="_blank">View Application Resume</a>
                                            @elseif ($application->user->resume_path)
                                                <a href="{{ Storage::url($application->user->resume_path) }}" target="_blank">View Profile Resume</a>
                                            @else
                                                Not provided
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                @endforeach
            @endif
            <p class="text-center">
                <a href="{{ route('employer.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
            </p>
        </div>
    </div>
@endsection
