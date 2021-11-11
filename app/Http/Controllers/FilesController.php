<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Support\Facades\Storage;

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

    public function user_uploads($file_folder, $file_name)
    {
        $destinationPath = storage_path('app/user_uploads/' . preg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $file_folder) . '/');
        $existed_file = $destinationPath . urldecode($file_name);
        $ext = pathinfo($existed_file, PATHINFO_EXTENSION);

        if (file_exists($existed_file)) {
            switch ($ext) {
                case "pdf":
                    $content_type = 'application/pdf';
                    break;
                case "jpg":
                    $content_type = 'image/jpeg';
                    break;
                case "jpeg":
                    $content_type = 'image/jpeg';
                    break;
                case "png":
                    $content_type = 'image/png';
                    break;
                default:
                    $content_type = 'image/*';
                    break;
            }

            $file = file_get_contents($existed_file);
            return response($file, 200)->header('Content-Type', $content_type);
        }
    }
}
