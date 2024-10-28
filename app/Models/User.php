<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Role attribute for distinguishing user types
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Ensure password is hashed
    ];

    /**
     * Relationship with Student model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    /**
     * Relationship with Librarian model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function librarian()
    {
        return $this->hasOne(Librarian::class);
    }

    /**
     * Relationship with Achievement model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function achievements()
    {
        return $this->hasMany(Achievement::class);
    }

    /**
     * Check if the user is a librarian.
     *
     * @return bool
     */
    public function isLibrarian()
    {
        return $this->role === 'librarian';
    }
}
