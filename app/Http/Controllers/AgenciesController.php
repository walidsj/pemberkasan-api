<?php

namespace App\Http\Controllers;

use App\Models\Agency;

class AgenciesController extends Controller
{
    /**
     * Menampilkan daftar instansi keseluruhan program studi
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'message' => 'Agencies found.',
            'data' => Agency::orderBy('name')->get()->makeHidden(['created_at', 'updated_at'])
        ]);
    }
}
