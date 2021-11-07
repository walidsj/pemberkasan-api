<?php

namespace App\Http\Controllers;

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

        $data = SurveyAgency::with(['first_agency', 'second_agency', 'third_agency'])
            ->findOrFail($survey_agencies->id)
            ->makeHidden(['id', 'first_agency_id', 'second_agency_id', 'third_agency_id', 'created_at', 'updated_at']);
        $data->first_agency->makeHidden(['group_link', 'created_at', 'updated_at']);
        $data->second_agency->makeHidden(['group_link', 'created_at', 'updated_at']);
        $data->third_agency->makeHidden(['group_link', 'created_at', 'updated_at']);

        return response()->json([
            'success' => true,
            'message' => 'Survei berhasil disimpan.',
            // 'data' => $survey_agencies->with(['first_agency', 'second_agency', 'third_agency'])
            //     ->makeHidden(['id', 'created_at', 'updated_at'])
            'data' => $data
        ]);
    }

    public function me(Request $request)
    {
        $data = SurveyAgency::with(['first_agency', 'second_agency', 'third_agency'])->whereUserId($request->auth->id)->firstOrFail()->makeHidden(['id', 'first_agency_id', 'second_agency_id', 'third_agency_id', 'created_at', 'updated_at']);
        $data->first_agency->makeHidden(['group_link', 'created_at', 'updated_at']);
        $data->second_agency->makeHidden(['group_link', 'created_at', 'updated_at']);
        $data->third_agency->makeHidden(['group_link', 'created_at', 'updated_at']);

        return response()->json([
            'success' => true,
            'message' => 'Survei ditemukan.',
            'data' => $data
        ]);
    }
}
