<?php

namespace App\Http\Controllers;

use App\Models\File;

class FilesController extends Controller
{
    /**
     * Menampilkan daftar instansi keseluruhan program studi
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'message' => 'File list found.',
            'data' => File::orderBy('name')->get()->makeHidden(['created_at', 'updated_at'])
        ]);
    }
}
