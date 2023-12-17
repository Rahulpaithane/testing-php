<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class ExamPaper extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    protected $casts = [
        'batch_id' => 'integer',
        'batch_category_id' => 'integer',
        'batch_sub_category_id' => 'integer',
        'total_duration' => 'integer',
        'total_marks' => 'integer',
        'is_live' => 'integer',
    ];

    protected $appends = ['attemptStatus'];

    public function getAttemptStatusAttribute()
    {
        if(Auth::guard('api')->user()){
            $data = $this->attemptStatus()->where('student_id', '=', Auth::guard('api')->user()->id)->first();
            if($data){
                // return json_encode($data);
                if($data->submitionType=='0'){
                    return 'Pause';
                } else {
                    return "Submit";
                }
            } else {
                return 'Fresh';
            }
        }
    }


    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id')->with('class')->select('id', 'class_id', 'name');
    }

    public function examCategory()
    {
        return $this->belongsTo(BatchCategory::class, 'batch_category_id');
    }

    public function examSubCategory()
    {
        return $this->belongsTo(BatchSubCategory::class, 'batch_sub_category_id');
    }

    public function attemptStatus(){

        return $this->hasOne(govtResult::class)
        ->select('id', 'submitionType');
    }

    public function attemptedResult()
    {
        return $this->hasOne(govtResult::class, 'exam_paper_id', 'id')->with('questionInfo')
        ->where('student_id', '=', Auth::guard('api')->user()->id)
        ->select('id', 'class_id', 'batch_id', 'exam_paper_id', 'exam_paper_id as practiceSetId',
        'paper_name', 'attemptDate as attemptDate', 'attemptDurationTime',
        'submitionType', 'total_question', 'totalDurationTime as total_duration');

    }

}
