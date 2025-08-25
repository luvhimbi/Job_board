@extends('Layouts.app')

@section('title', 'My Applications')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="text-center mb-4">My Applications</h2>
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if ($applications->isEmpty())
                <p class="text-center">You have not applied for any jobs yet.</p>
            @else
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Job Title</th>
                        <th>Company</th>
                        <th>Location</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Applied On</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($applications as $application)
                        <tr>
                            <td>{{ $application->job->title }}</td>
                            <td>{{ $application->job->company?->name ?? 'Unknown Company' }}</td>
                            <td>{{ $application->job->location }}</td>
                            <td>{{ ucfirst($application->job->type) }}</td>
                            <td>{{ ucfirst($application->status) }}</td>
                            <td>{{ $application->created_at->format('M d, Y') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
            <p class="text-center">
                <a href="{{ route('applicant.dashboard') }}" class="btn btn-secondary">Back to Dashboard</a>
            </p>
        </div>
    </div>
@endsection
