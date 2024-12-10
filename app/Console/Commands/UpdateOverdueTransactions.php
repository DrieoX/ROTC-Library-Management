<?php

namespace App\Console\Commands;

use App\Models\BorrowingTransaction;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateOverdueTransactions extends Command
{
    protected $signature = 'transactions:update-overdue';
    protected $description = 'Mark overdue transactions as overdue in the database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Get all active transactions where the due date is passed
        $overdueTransactions = BorrowingTransaction::where('status', 'active')
            ->where('due_date', '<', Carbon::now())
            ->get();

        // Update the status of overdue transactions
        foreach ($overdueTransactions as $transaction) {
            $transaction->status = 'overdue';
            $transaction->save();
            $this->info("Transaction {$transaction->id} marked as overdue.");
        }

        $this->info('Overdue transactions updated successfully.');
    }
}
