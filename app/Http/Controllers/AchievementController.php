<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AchievementController extends Controller
{
    public function index()
    {
        // Fetch the achievements for the authenticated student
        $user = auth()->user();
        $student = $user->student;

        $achievements = $student
            ? $student->achievements()->get()
            : collect(); // Return empty collection if not a student

        // Fetch all achievements for display
        $allAchievements = \App\Models\Achievement::all();

        return view('achievements.index', compact('achievements', 'allAchievements'));
    }
}
