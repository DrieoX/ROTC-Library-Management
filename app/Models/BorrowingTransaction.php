<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowingTransaction extends Model
{
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

}
