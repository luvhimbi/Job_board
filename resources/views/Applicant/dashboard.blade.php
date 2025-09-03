@extends('Layouts.app')

@section('title', 'Applicant Dashboard')

@section('content')
    <div class="container py-5">
        <!-- Header Section -->
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold text-black">Welcome, {{ auth()->user()->firstname }}!</h2>
            <p class="text-lg text-black-50 mt-2">Explore job opportunities tailored for you.</p>
        </div>

        <!-- Search Form -->
        <form method="GET" action="{{ route('applicant.dashboard') }}" class="mb-5">
            <div class="input-group mx-auto" style="max-width: 600px;">
                <span class="input-group-text bg-white border-black">
                    <svg class="bi" width="20" height="20" fill="currentColor">
                        <use xlink:href="/bootstrap-icons/bootstrap-icons.svg#search"/>
                    </svg>
                </span>
                <input
                    type="text"
                    name="search"
                    class="form-control border-black"
                    placeholder="Search by job title, location, or company"
                    value="{{ request('search') }}"
                    aria-label="Search for jobs"
                >
                <button type="submit" class="btn btn-dark">Search</button>
                @if(request('search'))
                    <a href="{{ route('applicant.dashboard') }}" class="btn btn-outline-dark">Clear</a>
                @endif
            </div>
        </form>

        <!-- Job Listings -->
        @if($jobs->isEmpty())
            <div class="text-center py-5">
                <p class="lead text-black-50">No open job postings available at the moment.</p>
                <p class="text-black-50">Try adjusting your search or check back later.</p>
            </div>
        @else
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($jobs as $job)
                    <div class="col">
                        <div class="card h-100 border-black shadow-sm">
                            <div class="card-body">
                                <!-- Job Title -->
                                <h5 class="card-title fw-bold text-black">{{ $job->title }}</h5>

                                <!-- Company Name with link -->
                                @if($job->company)
                                    <h6 class="card-subtitle mb-2">
                                        <a
                                            href="{{ route('companies.show', $job->company->id) }}"
                                            class="text-black text-decoration-none"
                                        >
                                            {{ $job->company->name }}
                                        </a>
                                    </h6>
                                @else
                                    <h6 class="card-subtitle mb-2 text-black-50">Unknown Company</h6>
                                @endif

                                <!-- Job Details -->
                                <div class="card-text mb-3 small text-black-50">
                                    <p class="mb-1"><strong>Location:</strong> {{ $job->location }}</p>
                                    <p class="mb-1"><strong>Type:</strong> {{ ucfirst($job->type) }}</p>
                                    <p class="mb-1"><strong>Posted On:</strong> {{ $job->created_at->format('M d, Y') }}</p>
                                    <p class="mb-1"><strong>Applicants:</strong> {{ $job->applications_count }}</p>
                                </div>
                                @if(in_array($job->id, $appliedJobIds))
                                    <a href="{{ route('jobs.show', $job->id) }}">View Job</a>
                                @endif
                                <!-- Apply / Applied Button -->
                                @if(in_array($job->id, $appliedJobIds))
                                    <button class="btn btn-outline-dark w-100" disabled>Applied</button>
                                @else


                                    <a
                                        href="{{ route('applications.create', $job->id) }}"
                                        class="btn btn-dark w-100"
                                    >
                                        Apply Now
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Inline CSS for hover effect and styling -->
    <style>
        body {
            background-color: #ffffff;
        }
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #000000;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .card-subtitle a:hover {
            text-decoration: underline !important;
        }
        .text-black-50 {
            color: #6c757d !important;
        }
        .btn-dark, .btn-outline-dark {
            border-color: #000000;
        }
        .btn-dark {
            background-color: #000000;
            color: #ffffff;
        }
        .btn-dark:hover {
            background-color: #333333;
            border-color: #333333;
        }
        .btn-outline-dark:hover {
            background-color: #f8f9fa;
            color: #000000;
        }
    </style>
@endsection
