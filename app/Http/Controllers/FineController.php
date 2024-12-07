<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use Illuminate\Http\Request;

class FineController extends Controller
{
    /**
     * Display all unpaid fines for a student.
     */
    public function index()
    {
        // Get unpaid fines for the currently authenticated student
        $fines = Fine::where('payment_status', 'unpaid')
                     ->where('student_id', auth()->id()) // Ensure fines belong to the logged-in student
                     ->get();

        return view('fines.index', compact('fines'));
    }

    /**
     * Mark a fine as paid.
     */
    public function payFine($fineId)
    {
        // Find the fine by ID
        $fine = Fine::findOrFail($fineId);

        // Ensure the fine belongs to the currently authenticated student
        if ($fine->student_id !== auth()->id()) {
            return redirect()->back()->with('error', 'This fine does not belong to you.');
        }

        // Mark the fine as paid
        $fine->payment_status = 'paid';
        $fine->save();

        return redirect()->back()->with('success', 'Fine paid successfully.');
    }
}
