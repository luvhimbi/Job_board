@extends('Layouts.app')

@section('title', 'Employer Dashboard')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">

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

            <!-- Search Bar -->
            <form method="GET" action="{{ route('employer.dashboard') }}" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search jobs..."
                           value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>

            <h4>Your Job Postings</h4>
            @if ($jobs->isEmpty())
                <p>No job postings found.</p>
            @else
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Title</th>
                        <th>Location</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($jobs as $job)
                        <tr>
                            <td>
                                <a href="{{ route('jobPostingInfo.show', $job->id) }}" class="text-decoration-none">
                                    {{ $job->title }}
                                </a>
                            </td>
                            <td>{{ $job->location }}</td>
                            <td>{{ ucfirst($job->type) }}</td>
                            <td>{{ ucfirst($job->status) }}</td>
                            <td>{{ $job->created_at->format('M d, Y') }}</td>
                            <td>
                                <a href="{{ route('jobPosting.edit', $job->id) }}" class="btn btn-sm btn-primary">Edit</a>
                                <form action="{{ route('jobs.destroy', $job->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this job?');">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif

        </div>
    </div>
@endsection
