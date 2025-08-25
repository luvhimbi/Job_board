<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Applications extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * (Optional — Laravel will auto-detect 'applications' from 'Application')
     */
    protected $table = 'applications';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'job_id',
        'cover_letter',
        'resume_path',
        'status',
    ];

    /**
     * Cast attributes to specific types.
     */
    protected $casts = [
        'status' => 'string',
    ];

    /**
     * An application belongs to a user (applicant).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * An application belongs to a job posting.
     */
    public function job()
    {
        return $this->belongsTo(Jobs::class, 'job_id');
    }
}
