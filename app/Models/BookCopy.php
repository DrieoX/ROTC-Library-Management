<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookCopy extends Model
{
    use HasFactory;

    protected $fillable = ['book_id', 'isbn', 'available'];

    // Define the relationship with the parent book
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // Define the relationship with borrowing transactions
    public function borrowingTransactions()
    {
        return $this->hasMany(BorrowingTransaction::class,  'book_copy_id');
    }

    // Define the relationship with requests
    public function requests()
    {
        return $this->hasMany(Requests::class, 'book_copy_id');
    }

    // Scope to find available book copies
    public function scopeAvailable($query)
    {
        return $query->where('available', true);
    }
}
