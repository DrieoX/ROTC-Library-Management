<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
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

    // app/Models/Student.php

    public function achievements()
    {
    return $this->belongsToMany(Achievement::class, 'achievement_student');
    }
}
