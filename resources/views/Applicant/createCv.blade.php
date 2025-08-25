
@extends('Layouts.app')

@section('title', 'Build Your CV')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h2 class="text-center mb-4">Build Your CV</h2>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="row">
                <!-- Form Section -->
                <div class="col-md-6">
                    <form method="POST" action="{{ route('cv.store') }}">
                        @csrf

                        <!-- Full Name -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Full Name</label>
                            <input type="text" name="full_name" class="form-control cv-input" id="full_name"
                                   value="{{ old('full_name', $cv->full_name ?? auth()->user()->name) }}" required>
                            <div class="form-text text-muted">
                                Enter your first and last name as you want it to appear on your CV.
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" class="form-control cv-input" id="email"
                                   value="{{ old('email', $cv->email ?? auth()->user()->email) }}" required>
                            <div class="form-text text-muted">
                                Provide a professional email address that employers can reach you at.
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Phone</label>
                            <input type="text" name="phone" class="form-control cv-input" id="phone"
                                   value="{{ old('phone', $cv->phone ?? '') }}" required>
                            <div class="form-text text-muted">
                                Include a valid phone number with country/area code.
                            </div>
                        </div>

                        <!-- Professional Summary -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Professional Summary</label>
                            <textarea name="summary" class="form-control cv-input" id="summary" rows="3">{{ old('summary', $cv->summary ?? '') }}</textarea>
                            <div class="form-text text-muted">
                                Write 2–3 sentences summarizing your experience, strengths, and career goals.
                            </div>
                        </div>

                        <!-- Skills -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Skills (comma separated)</label>
                            <textarea name="skills" class="form-control cv-input" id="skills" rows="2">{{ old('skills', $cv->skills ?? '') }}</textarea>
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
                                            <input type="text" name="experiences[{{ $index }}][company]" class="form-control cv-input" value="{{ $exp->company ?? '' }}" required>
                                        </div>
                                        <div class="mb-2">
                                            <label>Position</label>
                                            <input type="text" name="experiences[{{ $index }}][position]" class="form-control cv-input" value="{{ $exp->position ?? '' }}" required>
                                        </div>
                                        <div class="mb-2">
                                            <label>Start Date</label>
                                            <input type="text" name="experiences[{{ $index }}][start_date]" class="form-control cv-input" value="{{ $exp->start_date ?? '' }}" required>
                                        </div>
                                        <div class="mb-2">
                                            <label>End Date (leave blank if current)</label>
                                            <input type="text" name="experiences[{{ $index }}][end_date]" class="form-control cv-input" value="{{ $exp->end_date ?? '' }}">
                                        </div>
                                        <div class="mb-2">
                                            <label>Achievements</label>
                                            <textarea name="experiences[{{ $index }}][achievements]" class="form-control cv-input" rows="3">{{ $exp->achievements ?? '' }}</textarea>
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
                                            <input type="text" name="educations[{{ $index }}][institution]" class="form-control cv-input" value="{{ $edu->institution ?? '' }}" required>
                                        </div>
                                        <div class="mb-2">
                                            <label>Degree</label>
                                            <input type="text" name="educations[{{ $index }}][degree]" class="form-control cv-input" value="{{ $edu->degree ?? '' }}" required>
                                        </div>
                                        <div class="mb-2">
                                            <label>Start Year</label>
                                            <input type="text" name="educations[{{ $index }}][start_year]" class="form-control cv-input" value="{{ $edu->start_year ?? '' }}" required>
                                        </div>
                                        <div class="mb-2">
                                            <label>End Year (leave blank if ongoing)</label>
                                            <input type="text" name="educations[{{ $index }}][end_year]" class="form-control cv-input" value="{{ $edu->end_year ?? '' }}">
                                        </div>
                                        <div class="mb-2">
                                            <label>Details</label>
                                            <textarea name="educations[{{ $index }}][details]" class="form-control cv-input" rows="3">{{ $edu->details ?? '' }}</textarea>
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

                <!-- Preview Section -->
                <div class="col-md-6">
                    <h3 class="mb-3">CV Preview</h3>
                    <div id="cv-preview" class="border p-4 bg-white">
                        <div class="header">
                            <h1 id="preview-full_name">{{ old('full_name', $cv->full_name ?? auth()->user()->name) }}</h1>
                            <p class="contact">
                                Email: <span id="preview-email">{{ old('email', $cv->email ?? auth()->user()->email) }}</span> |
                                Phone: <span id="preview-phone">{{ old('phone', $cv->phone ?? '') }}</span>
                            </p>
                        </div>

                        <div class="section">
                            <h2>Professional Summary</h2>
                            <p id="preview-summary">{{ old('summary', $cv->summary ?? 'No summary provided.') }}</p>
                        </div>

                        <div class="section">
                            <h2>Skills</h2>
                            <ul id="preview-skills">
                                @php
                                    $skills = array_map('trim', explode(',', old('skills', $cv->skills ?? '')));
                                @endphp
                                @if(!empty($skills) && $skills[0] !== '')
                                    @foreach($skills as $skill)
                                        @if($skill)
                                            <li>{{ $skill }}</li>
                                        @endif
                                    @endforeach
                                @else
                                    <li>No skills provided.</li>
                                @endif
                            </ul>
                        </div>

                        <div class="section">
                            <h2>Work Experience</h2>
                            <div id="preview-experiences">
                                @foreach($experiences as $exp)
                                    <div class="entry">
                                        <p class="entry-title">{{ $exp->position }} - {{ $exp->company }}</p>
                                        <p class="entry-dates">{{ $exp->start_date }} - {{ $exp->end_date ?? 'Present' }}</p>
                                        <p>{{ $exp->achievements ?? 'No achievements listed.' }}</p>
                                    </div>
                                @endforeach
                                @if(empty($experiences))
                                    /

                                    <p>No experience provided.</p>
                                @endif
                            </div>
                        </div>

                        <div class="section">
                            <h2>Education</h2>
                            <div id="preview-educations">
                                @foreach($educations as $edu)
                                    <div class="entry">
                                        <p class="entry-title">{{ $edu->degree }} - {{ $edu->institution }}</p>
                                        <p class="entry-dates">{{ $edu->start_year }} - {{ $edu->end_year ?? 'Present' }}</p>
                                        <p>{{ $edu->details ?? 'No details provided.' }}</p>
                                    </div>
                                @endforeach
                                @if(empty($educations))
                                    <p>No education provided.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        #cv-preview {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            margin: 1in;
        }
        #cv-preview h1 { font-size: 14pt; margin-bottom: 0.5em; color: #000; }
        #cv-preview h2 { font-size: 12pt; margin-bottom: 0.5em; color: #000; }
        #cv-preview p { margin: 0 0 0.5em; }
        #cv-preview ul { margin: 0 0 0.5em; padding-left: 1em; list-style-type: disc; }
        #cv-preview .section { margin-bottom: 1.2em; }
        #cv-preview .header { text-align: left; margin-bottom: 1.5em; border-bottom: 1px solid #000; padding-bottom: 0.5em; }
        #cv-preview .contact { font-size: 10pt; }
        #cv-preview .entry { margin-bottom: 1em; }
        #cv-preview .entry-title { font-weight: bold; font-size: 11pt; }
        #cv-preview .entry-dates { font-style: italic; font-size: 10pt; }
    </style>

    <script>
        // Update preview for simple input fields
        function updatePreview() {
            document.getElementById('preview-full_name').textContent = document.getElementById('full_name').value || 'Your Name';
            document.getElementById('preview-email').textContent = document.getElementById('email').value || 'your.email@example.com';
            document.getElementById('preview-phone').textContent = document.getElementById('phone').value || 'No phone provided';
            document.getElementById('preview-summary').textContent = document.getElementById('summary').value || 'No summary provided.';

            const skills = document.getElementById('skills').value.split(',').map(s => s.trim()).filter(s => s);
            const skillsList = document.getElementById('preview-skills');
            skillsList.innerHTML = skills.length > 0 ? skills.map(skill => `<li>${skill}</li>`).join('') : '<li>No skills provided.</li>';
        }

        // Update preview for experiences and educations
        function updateDynamicSections() {
            const experienceSections = document.querySelectorAll('.experience-section');
            const educationSections = document.querySelectorAll('.education-section');
            const previewExperiences = document.getElementById('preview-experiences');
            const previewEducations = document.getElementById('preview-educations');

            // Update experiences
            let experiencesHtml = '';
            experienceSections.forEach(section => {
                const company = section.querySelector('input[name$="[company]"]').value;
                const position = section.querySelector('input[name$="[position]"]').value;
                const startDate = section.querySelector('input[name$="[start_date]"]').value;
                const endDate = section.querySelector('input[name$="[end_date]"]').value;
                const achievements = section.querySelector('textarea[name$="[achievements]"]').value;

                if (company || position || startDate) {
                    experiencesHtml += `
                        <div class="entry">
                            <p class="entry-title">${position ? position : 'Position'} - ${company ? company : 'Company'}</p>
                            <p class="entry-dates">${startDate ? startDate : 'Start Date'} - ${endDate ? endDate : 'Present'}</p>
                            <p>${achievements ? achievements : 'No achievements listed.'}</p>
                        </div>
                    `;
                }
            });
            previewExperiences.innerHTML = experiencesHtml || '<p>No experience provided.</p>';

            // Update educations
            let educationsHtml = '';
            educationSections.forEach(section => {
                const institution = section.querySelector('input[name$="[institution]"]').value;
                const degree = section.querySelector('input[name$="[degree]"]').value;
                const startYear = section.querySelector('input[name$="[start_year]"]').value;
                const endYear = section.querySelector('input[name$="[end_year]"]').value;
                const details = section.querySelector('textarea[name$="[details]"]').value;

                if (institution || degree || startYear) {
                    educationsHtml += `
                        <div class="entry">
                            <p class="entry-title">${degree ? degree : 'Degree'} - ${institution ? institution : 'Institution'}</p>
                            <p class="entry-dates">${startYear ? startYear : 'Start Year'} - ${endYear ? endYear : 'Present'}</p>
                            <p>${details ? details : 'No details provided.'}</p>
                        </div>
                    `;
                }
            });
            previewEducations.innerHTML = educationsHtml || '<p>No education provided.</p>';
        }

        // Initialize preview
        document.querySelectorAll('.cv-input').forEach(input => {
            input.addEventListener('input', () => {
                updatePreview();
                updateDynamicSections();
            });
        });

        // Handle adding new experience
        let expIndex = {{ count($experiences ?? []) }};
        document.getElementById('add-experience').addEventListener('click', function() {
            const section = document.createElement('div');
            section.classList.add('experience-section', 'mb-3', 'border', 'p-3');
            section.innerHTML = `
                <div class="mb-2">
                    <label>Company</label>
                    <input type="text" name="experiences[${expIndex}][company]" class="form-control cv-input" required>
                </div>
                <div class="mb-2">
                    <label>Position</label>
                    <input type="text" name="experiences[${expIndex}][position]" class="form-control cv-input" required>
                </div>
                <div class="mb-2">
                    <label>Start Date</label>
                    <input type="text" name="experiences[${expIndex}][start_date]" class="form-control cv-input" required>
                </div>
                <div class="mb-2">
                    <label>End Date (leave blank if current)</label>
                    <input type="text" name="experiences[${expIndex}][end_date]" class="form-control cv-input">
                </div>
                <div class="mb-2">
                    <label>Achievements</label>
                    <textarea name="experiences[${expIndex}][achievements]" class="form-control cv-input" rows="3"></textarea>
                </div>
                <button type="button" class="btn btn-danger remove-section">Remove</button>
            `;
            document.getElementById('experience-sections').appendChild(section);
            section.querySelectorAll('.cv-input').forEach(input => {
                input.addEventListener('input', updateDynamicSections);
            });
            section.querySelector('.remove-section').addEventListener('click', () => {
                section.remove();
                updateDynamicSections();
            });
            expIndex++;
            updateDynamicSections();
        });

        // Handle adding new education
        let eduIndex = {{ count($educations ?? []) }};
        document.getElementById('add-education').addEventListener('click', function() {
            const section = document.createElement('div');
            section.classList.add('education-section', 'mb-3', 'border', 'p-3');
            section.innerHTML = `
                <div class="mb-2">
                    <label>Institution</label>
                    <input type="text" name="educations[${eduIndex}][institution]" class="form-control cv-input" required>
                </div>
                <div class="mb-2">
                    <label>Degree</label>
                    <input type="text" name="educations[${eduIndex}][degree]" class="form-control cv-input" required>
                </div>
                <div class="mb-2">
                    <label>Start Year</label>
                    <input type="text" name="educations[${eduIndex}][start_year]" class="form-control cv-input" required>
                </div>
                <div class="mb-2">
                    <label>End Year (leave blank if ongoing)</label>
                    <input type="text" name="educations[${eduIndex}][end_year]" class="form-control cv-input">
                </div>
                <div class="mb-2">
                    <label>Details</label>
                    <textarea name="educations[${eduIndex}][details]" class="form-control cv-input" rows="3"></textarea>
                </div>
                <button type="button" class="btn btn-danger remove-section">Remove</button>
            `;
            document.getElementById('education-sections').appendChild(section);
            section.querySelectorAll('.cv-input').forEach(input => {
                input.addEventListener('input', updateDynamicSections);
            });
            section.querySelector('.remove-section').addEventListener('click', () => {
                section.remove();
                updateDynamicSections();
            });
            eduIndex++;
            updateDynamicSections();
        });

        // Handle removing sections
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-section')) {
                e.target.parentElement.remove();
                updateDynamicSections();
            }
        });

        // Initialize preview on page load
        updatePreview();
        updateDynamicSections();
    </script>
@endsection

