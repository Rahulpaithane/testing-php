<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuestionBankInfo extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'question_bank_id' => 'integer',
    ];

    // public function getQuestionAttribute()
    // {

    //     $data=json_decode($this->attributes['question'],true);

    //     $english=$data['English'];
    //     $hindi=$data['Hindi'];

    //     $english2='';
    //     $hindi2='';

    //     if($english !=''){
    //         // Remove unnecessary attributes and inline styles
    //         $cleanedData = preg_replace('/style="[^"]+"/', '', $english);

    //         // Convert span elements to a more readable format
    //         $cleanedData = preg_replace('/<span[^>]*>/', '', $cleanedData);
    //         $cleanedData = str_replace('</span>', '', $cleanedData);

    //         // Replace <sup> and <sub> with their actual characters
    //         $cleanedData = strip_tags($cleanedData, '<sub><sup><br>');

    //         // Remove any remaining tags
    //         $english2 = strip_tags($cleanedData);
    //     }

    //     if($hindi !=''){
    //         // Remove unnecessary attributes and inline styles
    //         $cleanedData = preg_replace('/style="[^"]+"/', '', $hindi);

    //         // Convert span elements to a more readable format
    //         $cleanedData = preg_replace('/<span[^>]*>/', '', $cleanedData);
    //         $cleanedData = str_replace('</span>', '', $cleanedData);

    //         // Replace <sup> and <sub> with their actual characters
    //         $cleanedData = strip_tags($cleanedData, '<sub><sup><br>');

    //         // Remove any remaining tags
    //         $hindi2 = strip_tags($cleanedData);
    //     }

    //     return ['English'=>$english2, 'Hindi'=>$hindi2];
    //     // Assuming 'questionBankInfo' is a relationship
    //     // and the related model has a 'question' attribute
    //     return $this->questionBankInfo->question ?? null;
    // }

    // Define the relationship with govtResultAttemptQuestion
    public function resultAttemptQuestions()
    {
        
        return $this->hasMany(govtResultAttemptQuestion::class, 'sub_question_id')
        ->select('id', 'sub_question_id', 'isAnswerCorrect', 'attemptAnswer');
    }
}
