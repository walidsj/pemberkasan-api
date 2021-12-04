<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\File;
use App\Models\User;
use App\Models\UserFile;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use ZipArchive;

class UserFilesController extends Controller
{

    public function store($file_id, Request $request)
    {
        $file = File::whereId($file_id)->first();

        if (empty($file) || !$file->is_active)
            return response()->json([
                'success' => false,
                'message' => 'Unggah berkas belum diperbolehkan atau tidak ada.'
            ], 404);

        $this->validate($request, [
            'whatsapp' => 'required',
            'file' => 'required|mimes:jpg,jpeg,png,pdf|max:' . $file->max_size / 1000,
        ]);


        $user = $request->auth;

        if ($request->hasFile('file')) {

            $existed_file = UserFile::whereUserId($user->id)->whereFileId($file->id)->first();

            if (!empty($existed_file->locked_at))
                return response()->json([
                    'success' => false,
                    'message' => 'Berkas terkunci karena masih dalam proses verifikasi.'
                ], 404);

            $destinationPath = storage_path('/app/user_uploads/' . preg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $file->slug) . '/');

            $upload_file = $request->file('file');
            $upload_file_name = preg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $file->slug) . '_' . preg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $user->major->code) . '_' . preg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $user->class) . '_' . preg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $user->name) . '_' . $user->id . '_' . time() . '.' . $upload_file->getClientOriginalExtension();
            $upload_file_content_type = $upload_file->getMimeType();

            $upload_file->move($destinationPath, $upload_file_name);

            if (!empty($existed_file)) {
                $exist = $destinationPath . $existed_file->file;
                if (file_exists($exist)) {
                    unlink($exist);
                }

                $user_file = $existed_file;
                $user_file->user_id = $user->id;
                $user_file->whatsapp = $request->whatsapp;
                $user_file->file_id = $file->id;
                $user_file->file = $upload_file_name;
                $user_file->content_type = $upload_file_content_type;
                $user_file->locked_at = Carbon::now();
                $user_file->checked_at = null;
                $user_file->notified_at = null;
                $user_file->verified_at = null;
                $user_file->backupped_at = null;
                $user_file->save();

                return response()->json([
                    'success' => true,
                    'message' => 'File updated.',
                    'data' => $user_file
                ]);
            }

            $user_file = new UserFile();
            $user_file->user_id = $user->id;
            $user_file->whatsapp = $request->whatsapp;
            $user_file->file_id = $file->id;
            $user_file->file = $upload_file_name;
            $user_file->content_type = $upload_file_content_type;
            $user_file->locked_at = Carbon::now();
            $user_file->save();

            return response()->json([
                'success' => true,
                'message' => 'File uploaded.',
                'data' => $user_file
            ]);
        }
    }

    public function getByMajor($file_id, $major_id)
    {
        return response()->json([
            'success' => true,
            'message' => 'User files of major ' . $major_id . ' found.',
            'data' => UserFile::with('verificator')->select('user_files.*', 'users.major_id', 'users.name', 'users.class', 'users.id AS npm')
                ->join('users', 'user_files.user_id', '=', 'users.id')
                ->whereMajorId($major_id)
                ->whereFileId($file_id)
                ->get()
        ]);
    }

    public function show($user_file_id)
    {
        return response()->json([
            'success' => true,
            'message' => 'User files found.',
            'data' => UserFile::with(['user', 'user.major', 'verificator'])
                ->findOrFail($user_file_id)
        ]);
    }

    public function getByAgency($agency_id)
    {
        return response()->json([
            'success' => true,
            'message' => 'User files of major ' . $agency_id . ' found.',
            'data' => UserFile::with('verificator')->select('user_files.*', 'users.major_id', 'users.agency_id', 'users.name', 'users.class', 'users.id AS npm')
                ->join('users', 'user_files.user_id', '=', 'users.id')
                ->whereAgencyId($agency_id)
                ->get()
        ]);
    }

    public function download($user_id)
    {
        $user = User::findOrFail($user_id);

        $user_files = UserFile::select('user_files.*', 'files.slug')
            ->whereUserId($user_id)
            ->join('files', 'user_files.file_id', '=', 'files.id')
            ->get();

        $zipFileName = $user->id . '_' . $user->name . '.zip';

        $zipPath = storage_path('app/user_uploads/ZIP');

        function path($slug)
        {
            return storage_path('app/user_uploads/' . preg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $slug));
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath . DIRECTORY_SEPARATOR . $zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            // $test = [];
            // $i = 1;
            // Add File in ZipArchive
            foreach ($user_files as $files) {
                $file = path($files->slug) . DIRECTORY_SEPARATOR . $files->file;
                $filename = !empty($files->verified_at) ? 'verif_' . $files->file : 'belum verif_' . $files->file;
                if (file_exists($file) && is_file($file)) {
                    $zip->addFile($file, $filename);
                    // $test[$i] = $file;
                    // $i++;
                }
            }

            // dd($test);
            // Close ZipArchive
            $zip->close();
        }

        $headers = [
            'Content-Type' => 'application/octet-stream',
        ];

        $filetopath = $zipPath . DIRECTORY_SEPARATOR . $zipFileName;

        if (file_exists($filetopath)) {
            return response()->download($filetopath, $zipFileName, $headers);
        }

        return response()->json([
            'success' => false,
            'message' => 'File not found'
        ], 404);
    }

    public function downloadByAgency($agency_id)
    {
        $agency = Agency::findOrFail($agency_id);

        $users = User::whereAgencyId($agency_id)
            ->get();

        $user_files = UserFile::select('user_files.*', 'files.slug')
            ->join('files', 'user_files.file_id', '=', 'files.id')
            ->join('users', 'user_files.user_id', '=', 'users.id')
            ->join('agencies', 'users.agency_id', '=', 'agencies.id')
            ->whereAgencyId($agency_id)
            ->get();

        $zipFileName = $agency->name . '.zip';

        $zipPath = storage_path('app/user_uploads/ZIP_AGENCY');

        function path_agency($slug)
        {
            return storage_path('app/user_uploads/' . preg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $slug));
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath . DIRECTORY_SEPARATOR . $zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            // $test = [];
            // $i = 1;
            // Add File in ZipArchive
            foreach ($users as $user) {
                foreach ($user_files as $files) {
                    $file = path_agency($files->slug) . DIRECTORY_SEPARATOR . $files->file;
                    $filename = !empty($files->verified_at) ? 'verif_' . $files->file : 'belum verif_' . $files->file;
                    if (file_exists($file) && is_file($file)) {
                        $zip->addFile($file, $user->npm . '_' . $user->name . DIRECTORY_SEPARATOR . $filename);
                        // $test[$i] = $file;
                        // $i++;
                    }
                }
            }

            // dd($test);
            // Close ZipArchive
            $zip->close();
        }

        $headers = [
            'Content-Type' => 'application/octet-stream',
        ];

        $filetopath = $zipPath . DIRECTORY_SEPARATOR . $zipFileName;

        if (file_exists($filetopath)) {
            return response()->download($filetopath, $zipFileName, $headers);
        }

        return response()->json([
            'success' => false,
            'message' => 'File not found'
        ], 404);
    }
}
