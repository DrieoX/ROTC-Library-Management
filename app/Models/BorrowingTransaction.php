<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BorrowingTransaction extends Model
{
    protected $fillable = [
        'book_id',
        'student_id',
        'librarian_id',
        'borrow_date',
        'due_date',
        'return_date',
        'status',
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date',
    ];

    /**
     * Relationships
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function librarian()
    {
        return $this->belongsTo(Librarian::class);
    }

    public function fines()
    {
        return $this->hasMany(Fine::class);
    }

    public function bookCopy()
    {
        return $this->belongsTo(BookCopy::class, 'book_id');
    }

    public function request()
    {
        return $this->belongsTo(Requests::class, 'book_id', 'book_copy_id');
    }

    /**
     * Check if the transaction is overdue
     */
    public function isOverdue()
    {
        return $this->status === 'active' && $this->due_date->lt(Carbon::now());
    }

    /**
     * Calculate fine amount
     */
    public function calculateFine($ratePerDay = 1.50)
    {
        if ($this->isOverdue()) {
            $overdueDays = Carbon::now()->diffInDays($this->due_date);
            return $overdueDays * $ratePerDay;
        }

        return 0;
    }
}
