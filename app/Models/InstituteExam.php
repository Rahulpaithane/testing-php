<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InstituteExam extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'institute_teacher_id' => 'integer',
    ];
}
