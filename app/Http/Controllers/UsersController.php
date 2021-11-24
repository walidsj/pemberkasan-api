<?php

namespace App\Http\Controllers;

use App\Models\User;

class UsersController extends Controller
{
    public function getClass($major_id, $class)
    {
        return response()->json([
            'success' => true,
            'message' => 'Majors found.',
            'data' => User::whereMajorId($major_id)
                ->whereClass($class)->orderBy('name')
                ->get()
        ]);
    }

    public function getByAgency($agency_id)
    {
        return response()->json([
            'success' => true,
            'message' => 'User found.',
            'data' => User::whereAgencyId($agency_id)
                ->orderBy('name')
                ->get()
        ]);
    }
}
