<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    protected $casts = [
        'class_id' => 'integer',
    ];

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class, 'subject_id')->select('chapters.*', 'chapters.name as chapter_name');
    }
}
