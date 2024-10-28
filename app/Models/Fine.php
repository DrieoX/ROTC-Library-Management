<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fine extends Model
{
    public function borrowingTransaction()
{
    return $this->belongsTo(BorrowingTransaction::class, 'transaction_id');
}

}
