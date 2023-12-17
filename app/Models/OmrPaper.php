<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;

class OmrPaper extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $guarded = [];
    
    protected $appends = ['attemptStatus'];

    protected $casts = [
        'batch_id' => 'integer',
        'batch_category_id' => 'integer',
        'batch_sub_category_id' => 'integer',
        'total_question' => 'integer',
        'total_marks' => 'integer',
    ];

    public function getAttemptStatusAttribute()
    {
        if(Auth::guard('api')->user()){
            $data = $this->hasOne(omrPaperResult::class)->where('student_id', '=', Auth::guard('api')->user()->id)->first();
            if($data){
                return true;
            } else {
                return false;
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
}
