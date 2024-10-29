<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requests extends Model
{
    public function book()
{
    return $this->belongsTo(Book::class);
}

    public function student()
{
    return $this->belongsTo(User::class, 'student_id');
}

protected $table = 'requests';

}
