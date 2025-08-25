<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Companies;
use App\Models\CV;
use App\Models\Experiance;
use App\Models\Education;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $company = $user->role === 'employer' ? $user->company()->first() : null;
        $cv = $user->role === 'applicant' ? $user->cv()->with(['experiences', 'educations'])->first() : null;
        return view('profile.show', compact('user', 'company', 'cv'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'firstname' => 'nullable|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'resume' => $user->role === 'applicant' ? 'nullable|file|mimes:pdf|max:2048' : 'nullable',
            'full_name' => $user->role === 'applicant' ? 'required|string|max:255' : 'nullable',
            'phone' => $user->role === 'applicant' ? 'required|string|max:20' : 'nullable',
            'summary' => $user->role === 'applicant' ? 'nullable|string' : 'nullable',
            'skills' => $user->role === 'applicant' ? 'nullable|string' : 'nullable',
            'experiences.*.company' => $user->role === 'applicant' ? 'required|string|max:255' : 'nullable',
            'experiences.*.position' => $user->role === 'applicant' ? 'required|string|max:255' : 'nullable',
            'experiences.*.start_date' => $user->role === 'applicant' ? 'required|string|max:50' : 'nullable',
            'experiences.*.end_date' => $user->role === 'applicant' ? 'nullable|string|max:50' : 'nullable',
            'experiences.*.achievements' => $user->role === 'applicant' ? 'nullable|string' : 'nullable',
            'educations.*.institution' => $user->role === 'applicant' ? 'required|string|max:255' : 'nullable',
            'educations.*.degree' => $user->role === 'applicant' ? 'required|string|max:255' : 'nullable',
            'educations.*.start_year' => $user->role === 'applicant' ? 'required|string|max:50' : 'nullable',
            'educations.*.end_year' => $user->role === 'applicant' ? 'nullable|string|max:50' : 'nullable',
            'educations.*.details' => $user->role === 'applicant' ? 'nullable|string' : 'nullable',
            'name' => $user->role === 'employer' ? 'required|string|max:255' : 'nullable',
            'description' => $user->role === 'employer' ? 'nullable|string' : 'nullable',
            'location' => $user->role === 'employer' ? 'nullable|string|max:255' : 'nullable',
            'website' => $user->role === 'employer' ? 'nullable|url|max:255' : 'nullable',
            'logo' => $user->role === 'employer' ? 'nullable|file|mimes:jpg,png|max:2048' : 'nullable',
        ]);

        try {
            // Update user details
            $userData = [
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email,
            ];

            if ($user->role === 'applicant' && $request->hasFile('resume')) {
                if ($user->resume_path) {
                    Storage::disk('public')->delete($user->resume_path);
                }
                $userData['resume_path'] = $request->file('resume')->store('resumes', 'public');
            }

            $user->update($userData);

            // Update CV details for applicants
            if ($user->role === 'applicant') {
                $cv = CV::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'full_name' => $request->full_name,
                        'email' => $request->email,
                        'phone' => $request->phone,
                        'summary' => $request->summary,
                        'skills' => $request->skills,
                    ]
                );

                // Delete existing experiences and educations
                Experiance::where('cv_id', $cv->id)->delete();
                Education::where('cv_id', $cv->id)->delete();

                // Create new experiences
                if ($request->has('experiences')) {
                    foreach ($request->experiences as $exp) {
                        Experiance::create([
                            'cv_id' => $cv->id,
                            'company' => $exp['company'],
                            'position' => $exp['position'],
                            'start_date' => $exp['start_date'],
                            'end_date' => $exp['end_date'] ?? null,
                            'achievements' => $exp['achievements'] ?? null,
                        ]);
                    }
                }

                // Create new educations
                if ($request->has('educations')) {
                    foreach ($request->educations as $edu) {
                        Education::create([
                            'cv_id' => $cv->id,
                            'institution' => $edu['institution'],
                            'degree' => $edu['degree'],
                            'start_year' => $edu['start_year'],
                            'end_year' => $edu['end_year'] ?? null,
                            'details' => $edu['details'] ?? null,
                        ]);
                    }
                }
            }

            // Update company details for employers
            if ($user->role === 'employer') {
                $companyData = [
                    'name' => $request->name,
                    'description' => $request->description,
                    'location' => $request->location,
                    'website' => $request->website,
                ];

                if ($request->hasFile('logo')) {
                    if ($user->company && $user->company->logo) {
                        Storage::disk('public')->delete($user->company->logo);
                    }
                    $companyData['logo'] = $request->file('logo')->store('logos', 'public');
                }

                Companies::updateOrCreate(
                    ['user_id' => $user->id],
                    $companyData
                );
            }

            return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'An error occurred while updating your profile: ' . $e->getMessage()]);
        }
    }

    public function showResume()
    {
        $user = Auth::user();
        if ($user->role !== 'applicant' || !$user->resume_path) {
            return redirect()->route('profile.show')->with('error', 'No resume available to view.');
        }
        return response()->file(Storage::disk('public')->path($user->resume_path));
    }
}
