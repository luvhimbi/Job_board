@extends('Layouts.app')

@section('title', 'My CV')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="text-center mb-4">My CV</h2>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(!$cv)
                <p class="text-center">You haven't created your CV yet.</p>
                <div class="text-center">
                    <a href="{{ route('cv.create') }}" class="btn btn-primary">Create CV</a>
                </div>
            @else
                <div class="card mb-3">
                    <div class="card-body">
                        <h4>{{ $cv->full_name }}</h4>
                        <p>Email: {{ $cv->email }} | Phone: {{ $cv->phone }}</p>

                        <h5>Summary</h5>
                        <p>{{ $cv->summary }}</p>

                        <h5>Skills</h5>
                        @php
                            $skills = array_map('trim', explode(',', $cv->skills));
                        @endphp
                        <ul>
                            @foreach($skills as $skill)
                                @if($skill)
                                    <li>{{ $skill }}</li>
                                @endif
                            @endforeach
                        </ul>

                        <h5>Experience</h5>
                        @foreach($cv->experiences ?? [] as $exp)
                            <div class="mb-3">
                                <strong>{{ $exp->position }} at {{ $exp->company }}</strong>
                                <p>{{ $exp->start_date }} - {{ $exp->end_date ?? 'Present' }}</p>
                                <p>{{ $exp->achievements }}</p>
                            </div>
                        @endforeach

                        <h5>Education</h5>
                        @foreach($cv->educations ?? [] as $edu)
                            <div class="mb-3">
                                <strong>{{ $edu->degree }} from {{ $edu->institution }}</strong>
                                <p>{{ $edu->start_year }} - {{ $edu->end_year ?? 'Present' }}</p>
                                <p>{{ $edu->details }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="text-center">
                    <a href="{{ route('cv.create') }}" class="btn btn-secondary">Edit CV</a>
                    <a href="{{ route('cv.download') }}" class="btn btn-success">Download CV (PDF)</a>
                </div>
            @endif
        </div>
    </div>
@endsection
