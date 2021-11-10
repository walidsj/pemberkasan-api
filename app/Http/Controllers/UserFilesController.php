<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\UserFile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserFilesController extends Controller
{

    public function store($file_id, Request $request)
    {
        $this->validate($request, [
            'whatsapp' => 'required',
            'file' => 'required|mimes:jpg,jpeg,png,pdf|max:2000',
        ]);

        $file = File::whereId($file_id)->first();

        if (empty($file) || !$file->is_active)
            return response()->json([
                'success' => false,
                'message' => 'Unggah berkas belum diperbolehkan atau tidak ada.'
            ], 404);

        $user = $request->auth;

        if ($request->hasFile('file')) {

            $existed_file = UserFile::whereUserId($user->id)->whereFileId($file->id)->first();
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
                $user_file->is_checked = false;
                $user_file->is_notified = false;
                $user_file->is_verified = false;
                $user_file->message = null;
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
            $user_file->save();

            return response()->json([
                'success' => true,
                'message' => 'File uploaded.',
                'data' => $user_file
            ]);
        }
    }
}
