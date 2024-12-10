<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use App\Models\BorrowingTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FineController extends Controller
{
    /**
     * Display all unpaid fines for the authenticated student.
     */
    public function index()
    {
        $fines = Fine::where('payment_status', 'unpaid')
            ->where('student_id', auth()->id()) // Ensure the fines belong to the logged-in student
            ->get();

        return view('transactions.index', compact('fines'));
    }

    /**
     * Mark a fine as paid.
     *
     * @param int $fineId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function payFine(Request $request, $transactionId)
{
    $transaction = BorrowingTransaction::findOrFail($transactionId);

    // Calculate fine amount
    $fineAmount = $request->input('fine_amount', 0);

    // Store the fine in the database
    Fine::create([
        'student_id' => $transaction->student_id,
        'transaction_id' => $transaction->id,
        'fine_amount' => $fineAmount,
        'fine_date' => Carbon::now(),
        'payment_status' => 'paid',
    ]);

    // Update the transaction status
    $transaction->status = 'returned';
    $transaction->return_date = Carbon::now();
    $transaction->save();

    // Mark the book copy as available
    $bookCopy = $transaction->bookCopy;
    $bookCopy->available = true;
    $bookCopy->save();

    return redirect()->route('transactions.index')->with('success', 'Book returned and fine paid successfully.');
}

public function confirmPayment($transactionId)
{
    $transaction = BorrowingTransaction::findOrFail($transactionId);

    // Calculate fine
    $currentDate = Carbon::now();
    $finePerDay = 10; // Fine per day
    $daysOverdue = $currentDate->diffInDays($transaction->due_date);
    $fineAmount = $daysOverdue * $finePerDay;

    return view('transactions.confirm', compact('transaction', 'fineAmount', 'daysOverdue'));
}



    /**
     * Issue fines for overdue borrowing transactions.
     */
    public function issueFines()
    {
        $overdueTransactions = BorrowingTransaction::where('status', 'active')
            ->where('due_date', '<', Carbon::now())
            ->get();

        foreach ($overdueTransactions as $transaction) {
            $fineAmount = $transaction->calculateFine();

            if ($fineAmount > 0) {
                Fine::updateOrCreate(
                    ['transaction_id' => $transaction->id],
                    [
                        'student_id' => $transaction->student_id,
                        'fine_amount' => $fineAmount,
                        'fine_date' => Carbon::now(),
                        'payment_status' => 'unpaid',
                    ]
                );
            }
        }

        return redirect()->back()->with('success', 'Fines issued successfully.');
    }
}
