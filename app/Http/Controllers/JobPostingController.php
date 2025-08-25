<?php

namespace App\Http\Controllers;

use App\Models\Applications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Jobs;

class JobPostingController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $jobs = $user->company()->exists() ? $user->company()->first()->jobs()->get() : collect([]);
        return view('employer.dashboard', compact('jobs','user'));
    }
    public function applicantDashboard(Request $request)
    {
        $query = Jobs::with('company')
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

        // Pass both to the view
        return view('applicant.dashboard', compact('jobs', 'appliedJobIds'));
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
}
