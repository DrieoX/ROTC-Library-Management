<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'cover_image',
        'author',
        'description',
    ];

    public function borrowingTransactions()
    {
        return $this->hasMany(BorrowingTransaction::class);
    }

    public function copies()
    {
        return $this->hasMany(BookCopy::class);
    }

    public function requests()
{
    return $this->hasMany(Requests::class, 'book_copy_id');
}
}
