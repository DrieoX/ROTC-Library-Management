<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    // Define the relationship with the BorrowingTransaction model
    public function borrowingTransaction()
    {
        return $this->belongsTo(BorrowingTransaction::class, 'transaction_id');
    }

    // Define the relationship with the Student model
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
