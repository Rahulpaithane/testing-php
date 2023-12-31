<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentNotification extends Model 
{
    use HasFactory, SoftDeletes;

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }
}
