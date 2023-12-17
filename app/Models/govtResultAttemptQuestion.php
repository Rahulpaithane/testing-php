<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class govtResultAttemptQuestion extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'govt_result_id' => 'integer',
        'exam_paper_id' => 'integer',
        'question_bank_id' => 'integer',
        'sub_question_id' => 'integer',
    ];
}
