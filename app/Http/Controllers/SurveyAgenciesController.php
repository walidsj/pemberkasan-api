<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\SurveyAgency;
use Illuminate\Http\Request;

class SurveyAgenciesController extends Controller
{
    /**
     * Menampilkan daftar instansi keseluruhan program studi
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'first_agency_id' => 'required|numeric',
            'second_agency_id' => 'required|numeric',
            'third_agency_id' => 'required|numeric',
        ]);

        $survey_agencies = new SurveyAgency();
        $survey_agencies->user_id = $request->auth->id;
        $survey_agencies->first_agency_id = $request->first_agency_id;
        $survey_agencies->second_agency_id = $request->second_agency_id;
        $survey_agencies->third_agency_id = $request->third_agency_id;
        $survey_agencies->save();

        return response()->json([
            'success' => true,
            'message' => 'Survei berhasil disimpan.',
            'data' => $survey_agencies
        ]);
    }
}
