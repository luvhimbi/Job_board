<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class companies extends Model{


    protected $fillable = [
        'name',
        'description',
        'location',
        'website',
        'logo',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function jobs()
    {
        return $this->hasMany(Jobs::class, 'company_id');
    }
}

