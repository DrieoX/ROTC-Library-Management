<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AchievementController extends Controller
{
    public function index()
{
    // Fetch achievements for the authenticated user
    $achievements = auth()->user()->student->achievements;

    // Render the main view and pass the achievements
    return view('achievements.index', compact('achievements'));
}

}