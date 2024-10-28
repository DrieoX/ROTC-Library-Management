<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Librarian extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'position',
        'contact_number',
    ];

    public function borrowingTransactions()
    {
        return $this->hasMany(BorrowingTransaction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
