<?php

namespace App\Http\Controllers;

use App\Models\CV;
use App\Models\Experiance;
use App\Models\Education;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CVController extends Controller
{
    public function index()
    {
        $cv = CV::where('user_id', Auth::id())->first();
        return view('Applicant.YourCvs', compact('cv'));
    }

    public function create()
    {
        $cv = CV::where('user_id', Auth::id())->first();
        return view('Applicant.createCv', compact('cv'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'summary' => 'nullable|string',
            'skills' => 'nullable|string',
            'experiences.*.company' => 'required|string|max:255',
            'experiences.*.position' => 'required|string|max:255',
            'experiences.*.start_date' => 'required|string|max:50',
            'experiences.*.end_date' => 'nullable|string|max:50',
            'experiences.*.achievements' => 'nullable|string',
            'educations.*.institution' => 'required|string|max:255',
            'educations.*.degree' => 'required|string|max:255',
            'educations.*.start_year' => 'required|string|max:50',
            'educations.*.end_year' => 'nullable|string|max:50',
            'educations.*.details' => 'nullable|string',
        ]);

        $cv = CV::updateOrCreate(
            ['user_id' => Auth::id()],
            $request->only('full_name', 'email', 'phone', 'summary', 'skills')
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

        return redirect()->route('cv.index')->with('success', 'Your CV has been saved.');
    }

    public function edit()
    {
        $cv = CV::where('user_id', Auth::id())->first();
        return view('cv.edit', compact('cv'));
    }

    public function update(Request $request)
    {
        return $this->store($request); // reuse same logic
    }

    public function download()
    {
        $cv = CV::where('user_id', auth()->id())->firstOrFail();
        $experiences = Experiance::where('cv_id', $cv->id)->orderBy('start_date', 'desc')->get();
        $educations = Education::where('cv_id', $cv->id)->orderBy('start_year', 'desc')->get();

        $pdf = Pdf::loadView('Cvpdf', compact('cv', 'experiences', 'educations'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('My_CV.pdf');
    }
}
