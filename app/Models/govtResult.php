<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class govtResult extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'student_id' => 'integer',
        'class_id' => 'integer',
        'batch_id' => 'integer',
        'exam_paper_id' => 'integer',
        'totalCorrect' => 'integer',
        'totalWrong' => 'integer',
        'totalSkipped' => 'integer',
        'submitionType' => 'integer',
    ];

    public function questionInfo()
    {
        return $this->hasMany(govtResultAttemptQuestion::class)
        ->select('id', 'govt_result_id', 'exam_paper_id', 'question_type', 'language_type', 'question_bank_id',
    'sub_question_id', 'attemptAnswer', 'attemptTime', 'attemptType', 'correctAnswer', 'isAnswerCorrect');

    }

    public function getRankAttribute()
    {
        
        return strval($this->attributes['rank']);

    }
}
