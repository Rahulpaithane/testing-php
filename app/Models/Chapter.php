<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chapter extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $guarded = [];
    protected $table = 'chapters';

    protected $casts = [
        'subject_id' => 'integer',
    ];

    // public function subject()
    // {
    //     return $this->belongsTo(Subject::class);
    // }
}
