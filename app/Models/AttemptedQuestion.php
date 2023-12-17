<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class AttemptedQuestion extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    protected $casts = [
        'result_id' => 'integer',
        'question_id' => 'integer',
        'resulsub_question_idt_id' => 'integer',
    ];
}
