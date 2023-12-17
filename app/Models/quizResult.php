<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class quizResult extends Model
{
    use HasFactory, SoftDeletes;
    protected $casts = [
        'quiz_room_id' => 'integer',
        'student_id' => 'integer',
        'totalCorrect' => 'integer',
        'totalWrong' => 'integer',
        'totalSkipped' => 'integer',
    ];
    

    public function getRankAttribute()
    {
        
        return strval($this->attributes['rank']);

    }
}
