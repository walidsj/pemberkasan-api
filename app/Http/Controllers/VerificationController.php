<?php

namespace App\Http\Controllers;

use App\Models\UserFile;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class VerificationController extends Controller
{
    public function reject($user_file_id, Request $request)
    {
        $this->validate($request, [
            'message' => 'required',
        ]);

        $user_file = UserFile::findOrFail($user_file_id);
        $user_file->locked_at = null;
        $user_file->checked_at = Carbon::now();
        $user_file->notified_at = null;
        $user_file->verified_at = null;
        $user_file->backupped_at = null;
        $user_file->verificator_id = $request->auth->id;
        $user_file->message = $request->message;
        $user_file->save();

        return response()->json([
            'success' => true,
            'message' => 'Berkas sudah ditolak, status user bisa upload lagi.',
            'data' => $user_file
        ]);
    }

    public function approve($user_file_id, Request $request)
    {
        $user_file = UserFile::findOrFail($user_file_id);
        $user_file->checked_at = Carbon::now();
        $user_file->notified_at = null;
        $user_file->verified_at = Carbon::now();
        $user_file->backupped_at = null;
        $user_file->verificator_id = $request->auth->id;
        $user_file->message = null;
        $user_file->save();

        return response()->json([
            'success' => true,
            'message' => 'Berkas sudah diverifikasi.',
            'data' => $user_file
        ]);
    }

    public function notify($user_file_id)
    {
        $user_file = UserFile::findOrFail($user_file_id);
        $user_file->notified_at = Carbon::now();
        $user_file->save();

        return response()->json([
            'success' => true,
            'message' => 'Laporan bahwa Anda sudah mengirimi user WA telah diterima.',
            'data' => $user_file
        ]);
    }
}
