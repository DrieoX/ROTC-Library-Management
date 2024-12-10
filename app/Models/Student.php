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
        return $this->belongsTo(User::class, 'user_id');
    }

    public function requests()
    {
    return $this->hasMany(Requests::class, 'student_id');
    }


    // app/Models/Student.php

    public function achievements()
    {
        return $this->belongsToMany(Student::class, 'achievement_student')
                    ->withPivot('notified')
                    ->withTimestamps(); // Include pivot table timestamps
    }
}
