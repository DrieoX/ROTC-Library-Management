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
        'isbn',
        'quantity',
    ];
    public function borrowingTransactions()
    {
        return $this->hasMany(BorrowingTransaction::class);
    }
}
