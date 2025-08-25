<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\companies;

class ProfileController extends Controller
{


    public function show()
    {
        $user = Auth::user();
        $company = $user->role === 'employer' ? $user->company()->first() : null;
        return view('profile.show', compact('user', 'company'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'firstname' => 'nullable|string|max:255',
            'lastname' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'resume' => $user->role === 'applicant' ? 'nullable|file|mimes:pdf|max:2048' : 'nullable',
            'name' => $user->role === 'employer' ? 'required|string|max:255' : 'nullable',
            'description' => $user->role === 'employer' ? 'nullable|string' : 'nullable',
            'location' => $user->role === 'employer' ? 'nullable|string|max:255' : 'nullable',
            'website' => $user->role === 'employer' ? 'nullable|url|max:255' : 'nullable',
            'logo' => $user->role === 'employer' ? 'nullable|file|mimes:jpg,png|max:2048' : 'nullable',
        ]);

        $data = [
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'resume_path'=>$request->resume
        ];

        if ($user->role === 'applicant' && $request->hasFile('resume')) {
            if ($user->resume_path) {
                Storage::disk('public')->delete($user->resume_path);
            }
            $data['resume_path'] = $request->file('resume')->store('resumes', 'public');
        }

        $user->update($data);

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

            companies::updateOrCreate(
                ['user_id' => $user->id],
                $companyData
            );
        }

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully.');
    }

    public function showResume()
    {
        $user = Auth::user();
        if ($user->role !== 'applicant' || !$user->resume_path) {
            return redirect()->route('profile.show')->with('error', 'No resume available to view.');
        }
        return view('profile.resume', compact('user'));
    }
}
