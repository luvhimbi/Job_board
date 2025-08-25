@extends('Layouts.app')

@section('title', 'Build Your CV')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="text-center mb-4">Build Your CV</h2>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('cv.store') }}">
                @csrf

                <!-- Full Name -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Full Name</label>
                    <input type="text" name="full_name" class="form-control"
                           value="{{ old('full_name', $cv->full_name ?? auth()->user()->name) }}" required>
                    <div class="form-text text-muted">
                        Enter your first and last name as you want it to appear on your CV.
                    </div>
                </div>

                <!-- Email -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email', $cv->email ?? auth()->user()->email) }}" required>
                    <div class="form-text text-muted">
                        Provide a professional email address that employers can reach you at.
                    </div>
                </div>

                <!-- Phone -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Phone</label>
                    <input type="text" name="phone" class="form-control"
                           value="{{ old('phone', $cv->phone ?? '') }}" required>
                    <div class="form-text text-muted">
                        Include a valid phone number with country/area code.
                    </div>
                </div>

                <!-- Professional Summary -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Professional Summary</label>
                    <textarea name="summary" class="form-control" rows="3">{{ old('summary', $cv->summary ?? '') }}</textarea>
                    <div class="form-text text-muted">
                        Write 2–3 sentences summarizing your experience, strengths, and career goals.
                    </div>
                </div>

                <!-- Skills -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Skills (comma separated)</label>
                    <textarea name="skills" class="form-control" rows="2">{{ old('skills', $cv->skills ?? '') }}</textarea>
                    <div class="form-text text-muted">
                        List your core skills, e.g., "Java, Project Management, Leadership".
                    </div>
                </div>

                <!-- Work Experience Sections -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Work Experience</label>
                    <div id="experience-sections">
                        @php
                            $experiences = old('experiences', $cv ? $cv->experiences : []);
                            $index = 0;
                        @endphp
                        @foreach($experiences as $exp)
                            <div class="experience-section mb-3 border p-3">
                                <div class="mb-2">
                                    <label>Company</label>
                                    <input type="text" name="experiences[{{ $index }}][company]" class="form-control" value="{{ $exp->company ?? '' }}" required>
                                </div>
                                <div class="mb-2">
                                    <label>Position</label>
                                    <input type="text" name="experiences[{{ $index }}][position]" class="form-control" value="{{ $exp->position ?? '' }}" required>
                                </div>
                                <div class="mb-2">
                                    <label>Start Date</label>
                                    <input type="text" name="experiences[{{ $index }}][start_date]" class="form-control" value="{{ $exp->start_date ?? '' }}" required>
                                </div>
                                <div class="mb-2">
                                    <label>End Date (leave blank if current)</label>
                                    <input type="text" name="experiences[{{ $index }}][end_date]" class="form-control" value="{{ $exp->end_date ?? '' }}">
                                </div>
                                <div class="mb-2">
                                    <label>Achievements</label>
                                    <textarea name="experiences[{{ $index }}][achievements]" class="form-control" rows="3">{{ $exp->achievements ?? '' }}</textarea>
                                </div>
                                <button type="button" class="btn btn-danger remove-section">Remove</button>
                            </div>
                            @php $index++; @endphp
                        @endforeach
                    </div>
                    <button type="button" id="add-experience" class="btn btn-secondary">Add Experience</button>
                    <div class="form-text text-muted">
                        Add your most recent roles first. Include company name, position, dates, and key achievements.
                    </div>
                </div>

                <!-- Education Sections -->
                <div class="mb-4">
                    <label class="form-label fw-bold">Education</label>
                    <div id="education-sections">
                        @php
                            $educations = old('educations', $cv ? $cv->educations : []);
                            $index = 0;
                        @endphp
                        @foreach($educations as $edu)
                            <div class="education-section mb-3 border p-3">
                                <div class="mb-2">
                                    <label>Institution</label>
                                    <input type="text" name="educations[{{ $index }}][institution]" class="form-control" value="{{ $edu->institution ?? '' }}" required>
                                </div>
                                <div class="mb-2">
                                    <label>Degree</label>
                                    <input type="text" name="educations[{{ $index }}][degree]" class="form-control" value="{{ $edu->degree ?? '' }}" required>
                                </div>
                                <div class="mb-2">
                                    <label>Start Year</label>
                                    <input type="text" name="educations[{{ $index }}][start_year]" class="form-control" value="{{ $edu->start_year ?? '' }}" required>
                                </div>
                                <div class="mb-2">
                                    <label>End Year (leave blank if ongoing)</label>
                                    <input type="text" name="educations[{{ $index }}][end_year]" class="form-control" value="{{ $edu->end_year ?? '' }}">
                                </div>
                                <div class="mb-2">
                                    <label>Details</label>
                                    <textarea name="educations[{{ $index }}][details]" class="form-control" rows="3">{{ $edu->details ?? '' }}</textarea>
                                </div>
                                <button type="button" class="btn btn-danger remove-section">Remove</button>
                            </div>
                            @php $index++; @endphp
                        @endforeach
                    </div>
                    <button type="button" id="add-education" class="btn btn-secondary">Add Education</button>
                    <div class="form-text text-muted">
                        Add your highest or most relevant qualifications first. Include institution name, degree, years, and details.
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                    Save CV
                </button>
            </form>
        </div>
    </div>

    <script>
        let expIndex = {{ count($experiences ?? []) }};
        document.getElementById('add-experience').addEventListener('click', function() {
            const section = document.createElement('div');
            section.classList.add('experience-section', 'mb-3', 'border', 'p-3');
            section.innerHTML = `
                <div class="mb-2">
                    <label>Company</label>
                    <input type="text" name="experiences[${expIndex}][company]" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label>Position</label>
                    <input type="text" name="experiences[${expIndex}][position]" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label>Start Date</label>
                    <input type="text" name="experiences[${expIndex}][start_date]" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label>End Date (leave blank if current)</label>
                    <input type="text" name="experiences[${expIndex}][end_date]" class="form-control">
                </div>
                <div class="mb-2">
                    <label>Achievements</label>
                    <textarea name="experiences[${expIndex}][achievements]" class="form-control" rows="3"></textarea>
                </div>
                <button type="button" class="btn btn-danger remove-section">Remove</button>
            `;
            document.getElementById('experience-sections').appendChild(section);
            expIndex++;
        });

        let eduIndex = {{ count($educations ?? []) }};
        document.getElementById('add-education').addEventListener('click', function() {
            const section = document.createElement('div');
            section.classList.add('education-section', 'mb-3', 'border', 'p-3');
            section.innerHTML = `
                <div class="mb-2">
                    <label>Institution</label>
                    <input type="text" name="educations[${eduIndex}][institution]" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label>Degree</label>
                    <input type="text" name="educations[${eduIndex}][degree]" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label>Start Year</label>
                    <input type="text" name="educations[${eduIndex}][start_year]" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label>End Year (leave blank if ongoing)</label>
                    <input type="text" name="educations[${eduIndex}][end_year]" class="form-control">
                </div>
                <div class="mb-2">
                    <label>Details</label>
                    <textarea name="educations[${eduIndex}][details]" class="form-control" rows="3"></textarea>
                </div>
                <button type="button" class="btn btn-danger remove-section">Remove</button>
            `;
            document.getElementById('education-sections').appendChild(section);
            eduIndex++;
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-section')) {
                e.target.parentElement.remove();
            }
        });
    </script>
@endsection
