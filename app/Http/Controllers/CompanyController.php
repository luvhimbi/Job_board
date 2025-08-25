<?php

namespace App\Http\Controllers;



use App\Models\companies;

class CompanyController extends Controller
{

public function show($id)
{
$company = companies::with('jobs')->findOrFail($id);
return view('Applicant.CompanyDetails', compact('company'));

}

}
