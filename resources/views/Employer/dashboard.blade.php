@extends('Layouts.app')

@section('title', 'Employer Dashboard')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">

            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4>Welcome, {{ $user->firstname }} {{ $user->lastname }}</h4>
                <span>{{ \Carbon\Carbon::now()->format('F d, Y') }}</span>
            </div>

            <h2 class="text-center mb-4">Employer Dashboard</h2>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <h4>Your Job Postings</h4>
            @if ($jobs->isEmpty())
                <p>No job postings yet. Create one to get started!</p>
            @else
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Location</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Created At</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($jobs as $job)
                        <tr>
                            <td>{{ $job->title }}</td>
                            <td>{{ $job->location }}</td>
                            <td>{{ ucfirst($job->type) }}</td>
                            <td>{{ ucfirst($job->status) }}</td>
                            <td>{{ $job->created_at->format('M d, Y') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
