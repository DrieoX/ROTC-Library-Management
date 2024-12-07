<?php

namespace App\Jobs;

use App\Models\BorrowingTransaction;
use App\Models\Fine;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CheckOverdueTransactions implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Get all active borrowing transactions where due date has passed
        $transactions = BorrowingTransaction::where('status', 'active')
                                            ->where('due_date', '<', Carbon::now())
                                            ->get();

        foreach ($transactions as $transaction) {
            // Check if a fine already exists for this transaction
            $existingFine = Fine::where('transaction_id', $transaction->id)->first();

            if (!$existingFine) {
                // If no fine exists, create a new fine for this transaction
                $fineAmount = 10.00; // For example, a fine of $10 per overdue transaction
                Fine::create([
                    'transaction_id' => $transaction->id,
                    'fine_amount' => $fineAmount,
                    'fine_date' => Carbon::now(),
                    'payment_status' => 'unpaid',
                ]);
            }

            // Check if the transaction should be marked as 'overdue'
            $transaction->status = 'overdue';
            $transaction->save();
        }
    }
}
