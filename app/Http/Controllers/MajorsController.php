<?php

namespace App\Http\Controllers;

use App\Models\Major;
use App\Models\User;

class MajorsController extends Controller
{
    /**
     * Menampilkan daftar instansi keseluruhan program studi
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'message' => 'Majors found.',
            'data' => Major::orderBy('name')->get()
        ]);
    }

    public function showClass($major_id)
    {
        return response()->json([
            'success' => true,
            'message' => 'Classes found.',
            'data' => User::whereMajorId($major_id)->get()->sortBy('class')->map(function ($user) {
                return $user->class;
            })->unique()->values()->all()
        ]);
    }
}
