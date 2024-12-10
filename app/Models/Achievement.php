<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description'];

    public function students()
{
    return $this->belongsToMany(Student::class, 'achievement_student')
                ->withPivot('notified')
                ->withTimestamps(); // Include pivot table timestamps
}

}

