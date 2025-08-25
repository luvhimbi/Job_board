@extends('Layouts.app')

@section('title', $company->name)

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center">
                    @if($company->logo)
                        <img src="{{ asset('storage/' . $company->logo) }}"
                             alt="{{ $company->name }}"
                             class="img-fluid mb-3 rounded"
                             style="max-height: 120px;">
                    @else
                        <img src="{{ asset('images/default-company.png') }}"
                             alt="No logo"
                             class="img-fluid mb-3 rounded"
                             style="max-height: 120px;">
                    @endif
                    <h3 class="fw-bold">{{ $company->name }}</h3>
                    <p class="text-muted mb-0">{{ $company->location ?? 'Location not specified' }}</p>
                </div>
            </div>

            <!-- About section -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">About</h5>
                    <p>{{ $company->description ?? 'No description available.' }}</p>
                </div>
            </div>

            <!-- Jobs section -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Open Jobs</h5>
                    @forelse($company->jobs as $job)
                        <div class="mb-3">
                            <h6 class="mb-1">{{ $job->title }}</h6>
                            <small class="text-muted">{{ ucfirst($job->status) }}</small>
                        </div>
                        <hr>
                    @empty
                        <p>No open positions at this time.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
