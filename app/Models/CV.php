<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CV extends Model
{
    protected $table = 'cvs'; // Force Laravel to use the correct table name

    protected $fillable = [
        'user_id',
        'full_name',
        'email',
        'phone',
        'summary',
        'skills',
        'experience',
        'education',
    ];

    public function experiences()
    {
        return $this->hasMany(Experiance::class, 'cv_id');
    }

    public function educations()
    {
        return $this->hasMany(Education::class, 'cv_id');
    }
}
