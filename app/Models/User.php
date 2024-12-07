<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Mass assignable attributes.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Hidden attributes for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relationship with Student model.
     */
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    /**
     * Relationship with Librarian model.
     */
    public function librarian()
    {
        return $this->hasOne(Librarian::class);
    }

    /**
     * Determine if the user is a student.
     */
    public function isStudent()
    {
        return $this->student()->exists();
    }

    /**
     * Determine if the user is a librarian.
     */
    public function isLibrarian()
    {
        return $this->librarian()->exists();
    }
}
