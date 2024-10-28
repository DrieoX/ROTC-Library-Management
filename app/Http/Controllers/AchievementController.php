<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AchievementController extends Controller
{
    public function index()
    {
        // Logic to fetch achievements for the authenticated user
        $achievements = auth()->user()->student->achievements;

        return view('achievements.index', compact('achievements'));
    }
}