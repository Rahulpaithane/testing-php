<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InstituteSetContainer extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'institute_teacher_id' => 'integer',
        'institute_exam_id' => 'integer',
        'institute_exam_categories_id' => 'integer',
        'omr_paper_id' => 'integer',
    ];

    public function instituteDetails()
    {
        return $this->belongsTo(InstituteTeacher::class, 'institute_teacher_id')->select('id', 'instituteName', 'address', 'teacherName', 'email', 'mobile', 'institute_image', 'profile_image');
    }

    public function omrDetails()
    {
        return $this->belongsTo(OmrPaper::class, 'omr_paper_id')->select('id', 'omr_code', 'paper_name', 'total_question', 'total_marks', 'numberPerQuestion', 'exam_date', 'exam_time', 'examDuration', 'isNegative', 'numberPerNegative');
    }

    public function getTotalQuestionAttribute($value)
    {
        if($value =='' OR $value ==null){
            return 0; 
        } else {
            return count(json_decode($value));
        }
    }


    public function getCreatedDateAttribute($value)
    {
        // $value will contain the original 'created_at' value from the database
        return date('d-m-Y', strtotime($value));
    }
}
