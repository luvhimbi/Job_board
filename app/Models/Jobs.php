<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobs extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * (Optional, Laravel will auto-detect 'job_postings' from 'JobPosting')
     */
    protected $table = 'job_postings';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'description',
        'location',
        'type',
        'salary_min',
        'salary_max',
        'requirements',
        'expires_at',
        'status',
        'company_id',
    ];

    /**
     * Cast attributes to specific types.
     */
    protected $casts = [
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'expires_at' => 'date',
    ];

    /**
     * A job posting belongs to a company.
     */
    public function company()
    {
        return $this->belongsTo(companies::class, 'company_id');
    }
    public function applications()
    {
        return $this->hasMany(Applications::class, 'job_id');
    }
}
