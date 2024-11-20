<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Requests extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'requests';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['student_id', 'book_copy_id', 'status', 'request_date', 'return_date'];

    /**
     * Define constants for possible statuses.
     */
    public const STATUS_REQUESTED = 'requested';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_BORROWED = 'borrowed';
    public const STATUS_RETURNED = 'returned';
    public const STATUS_DECLINED = 'declined';

    /**
     * Get the book copy associated with this request.
     */
    public function bookCopy()
    {
        return $this->belongsTo(BookCopy::class, 'book_copy_id');
    }

    /**
     * Get the student that made the request.
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Scope a query to only include requests with a specific status.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include requests for a specific student.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $studentId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    /**
     * Check if the request is in the requested state.
     *
     * @return bool
     */
    public function isRequested()
    {
        return $this->status === self::STATUS_REQUESTED;
    }

    /**
     * Check if the request is in the borrowed state.
     *
     * @return bool
     */
    public function isBorrowed()
    {
        return $this->status === self::STATUS_BORROWED;
    }

    /**
     * Check if the request is in the returned state.
     *
     * @return bool
     */
    public function isReturned()
    {
        return $this->status === self::STATUS_RETURNED;
    }

}
