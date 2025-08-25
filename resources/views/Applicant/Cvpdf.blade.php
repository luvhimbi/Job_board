<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $cv->full_name }} - CV</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12pt; line-height: 1.4; margin: 1in; }
        h1, h2, h3, h4, h5 { margin-bottom: 0.5em; color: #000; }
        p { margin: 0 0 0.5em; }
        ul { margin: 0 0 0.5em; padding-left: 1em; list-style-type: disc; }
        .section { margin-bottom: 1em; }
        .header { text-align: left; margin-bottom: 1.5em; border-bottom: 1px solid #000; padding-bottom: 0.5em; }
        .contact { font-size: 10pt; }
        .entry { margin-bottom: 1em; }
        .entry-title { font-weight: bold; font-size: 11pt; }
        .entry-dates { font-style: italic; font-size: 10pt; }
    </style>
</head>
<body>
<div class="header">
    <h1>{{ $cv->full_name }}</h1>
    <p class="contact">Email: {{ $cv->email }} | Phone: {{ $cv->phone }}</p>
</div>

<div class="section">
    <h2>Professional Summary</h2>
    <p>{{ $cv->summary }}</p>
</div>

<div class="section">
    <h2>Skills</h2>
    <ul>
        @foreach(array_map('trim', explode(',', $cv->skills ?? '')) as $skill)
            @if($skill)
                <li>{{ $skill }}</li>
            @endif
        @endforeach
    </ul>
</div>

<div class="section">
    <h2>Work Experience</h2>
    @foreach($experiences as $exp)
        <div class="entry">
            <p class="entry-title">{{ $exp->position }} - {{ $exp->company }}</p>
            <p class="entry-dates">{{ $exp->start_date }} - {{ $exp->end_date ?? 'Present' }}</p>
            <p>{{ $exp->achievements }}</p>
        </div>
    @endforeach
</div>

<div class="section">
    <h2>Education</h2>
    @foreach($educations as $edu)
        <div class="entry">
            <p class="entry-title">{{ $edu->degree }} - {{ $edu->institution }}</p>
            <p class="entry-dates">{{ $edu->start_year }} - {{ $edu->end_year ?? 'Present' }}</p>
            <p>{{ $edu->details }}</p>
        </div>
    @endforeach
</div>
</body>
</html>
