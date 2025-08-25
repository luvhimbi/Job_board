<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experiance extends Model
{
    use HasFactory;
    protected $table = 'experiences';
    protected $fillable = ['cv_id', 'company', 'position', 'start_date', 'end_date', 'achievements'];
}
