<?php

namespace App\Http\Controllers;

use App\Models\File;

class FilesController extends Controller
{
    /**
     * Menampilkan daftar file keseluruhan program studi
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'message' => 'File list found.',
            'data' => File::with(['user_file' => function ($query) {
                $query->where('user_id', request()->auth->id);
            }])->orderBy('name')->get()->makeHidden([
                'id', 'description', 'detail', 'example1', 'example2', 'created_at', 'updated_at', 'max_size', 'file_type'
            ])
        ]);
    }

    public function show($slug)
    {
        return response()->json([
            'success' => true,
            'message' => 'File found.',
            'data' => File::with(['faqs', 'user_file.verificator', 'user_file' => function ($query) {
                $query->where('user_id', request()->auth->id);
            },])->whereSlug($slug)->first()->makeHidden([
                'created_at', 'updated_at'
            ])
        ]);
    }
}
