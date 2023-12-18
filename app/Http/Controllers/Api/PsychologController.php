<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PsychologController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $psychologs = User::where('role', 'psychologist')->get([
            'id',
            'email',
            'phone',
            'fullname',
            'gender',
            'profile_photo',
        ]);

        return $this->success($psychologs, "Psychologs fetched successfully");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $psychologist = User::where("id", $id)->where("role", "psychologist")->first();

        if (!$psychologist) {
            return $this->error("Psychologist not found", 404);
        }

        return $this->success($psychologist, "Psychologist fetched successfully");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'bio_desc' => 'required|string',
            'skill_desc' => 'required|string',
        ]);

        $user = Auth::user();
        if ($user->role != 'psychologist') {
            return $this->error('You are not a psychologist', 403);
        }

        $user->bio_desc = $request->bio_desc;
        $user->skill_desc = $request->skill_desc;
        $user->save();

        return $this->success($user, 'Psychologist profile updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
