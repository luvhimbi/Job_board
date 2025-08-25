<?php

namespace App\Http\Controllers;

use App\Models\Applications;
use App\Models\Jobs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class ApplicationController extends Controller
{


    public function create($jobId)
    {
        $job = Jobs::findOrFail($jobId);
        if ($job->status !== 'open') {
            return redirect()->route('applicant.dashboard')->with('error', 'This job is not open for applications.');
        }

        if (Applications::where('user_id', Auth::id())->where('job_id', $jobId)->exists()) {
            return redirect()->route('Applicant.dashboard')
                ->with('error', 'You have already applied for this job.');
        }
        return view('applications.create', compact('job'));
    }

    public function store(Request $request, $jobId)
    {
        $job = Jobs::findOrFail($jobId);
        if ($job->status !== 'open') {
            return redirect()->route('applicant.dashboard')->with('error', 'This job is not open for applications.');
        }
        if (Applications::where('user_id', Auth::id())->where('job_id', $jobId)->exists()) {
            return redirect()->route('applicant.dashboard')->with('error', 'You have already applied for this job.');
        }

        $request->validate([
            'cover_letter' => 'nullable|string',
            'resume' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $resumePath = null;
        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('resumes', 'public');
        } elseif (Auth::user()->resume_path) {
            $resumePath = Auth::user()->resume_path;
        }

        Applications::create([
            'user_id' => Auth::id(),
            'job_id' => $job->id,
            'cover_letter' => $request->cover_letter,
            'resume_path' => $resumePath,
            'status' => 'pending',
        ]);

        return redirect()->route('applicant.dashboard')->with('success', 'Application submitted successfully.');
    }

    public function index()
    {
        $user = Auth::user();
        if (!$user->company()->exists()) {
            return redirect()->route('employer.dashboard')->with('error', 'You must have a company profile to view applicants.');
        }
        $jobs = $user->company()->first()->jobs()->with('applications.user')->get();
        return view('Employer.index', compact('jobs'));
    }

    public function myApplications()
    {
        $applications = Auth::user()->applications()->with('job.company')->get();
        return view('Applicant.my_applications', compact('applications'));
    }
}
