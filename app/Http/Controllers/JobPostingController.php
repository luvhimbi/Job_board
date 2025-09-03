<?php

namespace App\Http\Controllers;

use App\Models\Applications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Jobs;

class JobPostingController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$user->company()->exists()) {
            // No company linked → no jobs
            $jobs = collect([]);
        } else {
            $company = $user->company()->first();

            if ($request->has('search') && $request->search !== '') {
                // Use Laravel Scout search to find jobs matching the query,
                // then filter to only include jobs from this company.
                $jobs = \App\Models\Jobs::search($request->search)
                    ->get()
                    ->where('company_id', $company->id);
            } else {
                // If no search, just fetch all jobs from the company
                $jobs = $company->jobs()->latest()->get();
            }
        }

        return view('employer.dashboard', compact('jobs', 'user'));
    }

    public function applicantDashboard(Request $request)
    {
        $query = Jobs::with('company')
            ->withCount('applications')
            ->where('status', 'open')
            ->orderBy('created_at', 'desc');

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                    ->orWhere('location', 'like', '%' . $search . '%')
                    ->orWhereHas('company', function ($q) use ($search) {
                        $q->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        $jobs = $query->get();
        $appliedJobIds = Applications::where('user_id', Auth::id())
            ->pluck('job_id')
            ->toArray();

        return view('Applicant.dashboard', compact('jobs', 'appliedJobIds'));
    }


    public function create()
    {
        $user = Auth::user();
        if (!$user->company()->exists()) {
            return redirect()->route('profile.show')->with('error', 'You must have a company profile to create a job posting.');
        }
        return view('Employer.create', ['company' => $user->company()->first()]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->company()->exists()) {
            return redirect()->route('profile.show')->with('error', 'You must have a company profile to create a job posting.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'type' => 'required|in:full-time,part-time,contract',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
            'requirements' => 'nullable|string',
            'expires_at' => 'nullable|date|after:today',
        ]);

        $user->company()->first()->jobs()->create([
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'type' => $request->type,
            'salary_min' => $request->salary_min,
            'salary_max' => $request->salary_max,
            'requirements' => $request->requirements,
            'expires_at' => $request->expires_at,
            'status' => 'open',
            'company_id' => $user->company()->first()->id,
        ]);

        return redirect()->route('employer.dashboard')->with('success', 'Job posting created successfully.');
    }
    public function show($id)
    {
        $job = Jobs::with('company')->findOrFail($id);
        return view('Applicant.show', compact('job'));
    }

    public function showApplicationInfo($id)
    {
        $job = Jobs::withCount('applications')->findOrFail($id);
        return view('Employer.ShowJobInfo', compact('job'));
    }
    public function edit($id)
    {
        $job = Jobs::findOrFail($id);
        return view('employer.jobsEdit', compact('job'));
    }

    public function update(Request $request, $id)
    {
        $job = Jobs::findOrFail($id);
        $job->update($request->all());
        return redirect()->route('employer.dashboard')->with('success', 'Job updated successfully!');
    }
    public function destroy($id)
    {
        // Fetch job posting
        $job = Jobs::findOrFail($id);

        // Ensure the authenticated user owns the job
        if ($job->employer_id !== Auth::id()) {
            return redirect()->route('employer.dashboard')->with('error', 'You are not authorized to delete this job.');
        }

        // Delete the job
        $job->delete();

        // Redirect back with success message
        return redirect()->route('employer.dashboard')->with('success', 'Job deleted successfully.');
    }

}
