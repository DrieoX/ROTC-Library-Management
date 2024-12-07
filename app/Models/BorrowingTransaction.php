<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    // Add attribute casting
    protected $casts = [
        'borrow_date' => 'date',
        'due_date' => 'date',
        'return_date' => 'date', // Optional if you're using return_date as a date
    ];

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
        return $this->belongsTo(BookCopy::class, 'book_id', 'book_id');
    }

    public function isOverdue()
    {
        return $this->status === 'active' && $this->due_date < now();
    }
}
