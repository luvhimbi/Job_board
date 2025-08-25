@extends('Layouts.app')

@section('title', 'Applicant Dashboard')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="text-center mb-4">Welcome, {{ auth()->user()->firstname }}!</h2>
            <p class="text-center">Browse and search for open job postings below.</p>
            <!-- Search Form -->
            <form method="GET" action="{{ route('applicant.dashboard') }}" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search by job title, location, or company" value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">Search</button>
                    @if (request('search'))
                        <a href="{{ route('applicant.dashboard') }}" class="btn btn-secondary">Clear</a>
                    @endif
                </div>
            </form>
            <!-- Job Listings -->
            @if ($jobs->isEmpty())
                <p class="text-center">No open job postings available at the moment.</p>
            @else
                <div class="row">
                    @foreach ($jobs as $job)
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $job->title }}</h5>
                                    <h6 class="card-subtitle mb-2 text-muted">{{ $job->company?->name ?? 'Unknown Company' }}</h6>
                                    <p class="card-text">
                                        <strong>Location:</strong> {{ $job->location }}<br>
                                        <strong>Type:</strong> {{ ucfirst($job->type) }}<br>
                                        <strong>Posted On:</strong> {{ $job->created_at->format('M d, Y') }}
                                    </p>
                                    @if(in_array($job->id, $appliedJobIds))
                                        <button class="btn btn-secondary" disabled>Applied</button>
                                    @else
                                        <a href="{{ route('applications.create', $job->id) }}" class="btn btn-primary">Apply</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
