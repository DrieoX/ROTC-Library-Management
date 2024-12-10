<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    protected $fillable = ['transaction_id', 'student_id', 'fine_amount', 'fine_date', 'payment_status'];

    /**
     * Relationship with BorrowingTransaction
     */
    public function borrowingTransaction()
    {
        return $this->belongsTo(BorrowingTransaction::class, 'transaction_id');
    }

    /**
     * Relationship with Student
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
