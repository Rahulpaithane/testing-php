<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class QuestionBank extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    protected $casts = [
        'subject_id' => 'integer',
        'chapter_id' => 'integer',
    ];


    public function questionBankInfo()
    {
        return $this->hasMany(QuestionBankInfo::class)
        ->select('id', 'question_bank_id', 'question', 'option', 'answer', 'ans_desc');
        
    }

    

    public function getPreviousYearQuestionsAttribute()
    {
        $questionBankInfoIds = [$this->id];
        return PreviousYearQuestion::whereJsonContains('questionBankInfoId', $questionBankInfoIds)->get();
    }


 

    // public function previousYearQuestions()
    // {
    //     return DB::table('previous_year_questions')
    //         ->whereJsonContains('questionBankInfoId', $this->id);
    // }
    

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class, 'chapter_id');
    }

     
    
}
