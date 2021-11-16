<?php

namespace App\Http\Controllers;

use App\Models\SurveyMarriage;
use Illuminate\Http\Request;

class SurveyMarriagesController extends Controller
{
    /**
     * Menampilkan daftar instansi keseluruhan program studi
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'status' => 'required',
        ]);

        $survey_marriages = new SurveyMarriage();
        $survey_marriages->user_id = $request->auth->id;
        $survey_marriages->status = $request->status;
        $survey_marriages->save();

        $data = SurveyMarriage::findOrFail($survey_marriages->id);

        return response()->json([
            'success' => true,
            'message' => 'Survei berhasil disimpan.',
            'data' => $data
        ]);
    }

    public function me(Request $request)
    {
        $data = SurveyMarriage::whereUserId($request->auth->id)->firstOrFail();

        return response()->json([
            'success' => true,
            'message' => 'Survei ditemukan.',
            'data' => $data
        ]);
    }
}
