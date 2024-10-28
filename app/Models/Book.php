<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    public function borrowingTransactions()
{
    return $this->hasMany(BorrowingTransaction::class);
}

}
