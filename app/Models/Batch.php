<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Auth;
use App\Models\StudentLedger;
use Illuminate\Support\Facades\Config;

class Batch extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $guarded = [];
    protected $table = 'batches';
    protected $casts = [
        'class_id' => 'integer',
        'no_of_student' => 'integer',
    ];


    protected $appends = ['purchase', 'isPurchased'];

    public function getPurchaseAttribute()
    {

        // $this->id=$attribute;
        // dd($this->purchase($attribute)->first());
        // return  $this->purchase($attribute)->first();
        // return $this->with('purchase')->where('student_id', $attribute)->get();
        if(Auth::guard('api')->user()){
            $status= $this->purchase()->where('student_id', Auth::guard('api')->user()->id)->exists();
 
             return $status ? false : true ;
         } else {
             return true;
         }

    }

    public function getIsPurchasedAttribute()
    {

        // $this->id=$attribute;
        // dd($this->purchase($attribute)->first());
        // return  $this->purchase($attribute)->first();
        // return $this->with('purchase')->where('student_id', $attribute)->get();
        if(Auth::guard('api')->user()){
            $check  = $this->purchase()->where('student_id', Auth::guard('api')->user()->id)->exists();
            if($check) return 2; // for true
               else return 1; //for false
        } else {
            return false;
        }

    }


    public function getBatchImageAttribute()
    {
        return Config::get('app.url').$this->attributes['batch_image'];

    }


    public function batchCategory()
    {
        return $this->hasMany(BatchCategory::class)->with('batchSubCategory');
    }

    public function examPaper()
    {
        return $this->hasMany(ExamPaper::class)
        ->select('id',
        'batch_id',
        'batch_category_id',
        'batch_sub_category_id',
        'paper_name',
        'is_live',
        'language_type', 'total_question',
        'total_duration',
        'total_marks',
        'per_question_no',
        'negative_marking_type',
        'per_negative_no',
        'exam_date',
        'exam_time'
    );
    }

    public function omrPaper()
    {
        return $this->hasMany(OmrPaper::class)
        ->select('id',
        'batch_id',
        'batch_category_id',
        'batch_sub_category_id',
        'paper_name',
        'examDuration',
        'total_marks',
        'per_question_no',
        'negative_marking_type',
        'per_negative_no',
        'exam_date',
        'exam_time',
        'isNegative',
        'numberPerNegative'
    );
    }

    public function studentLedger()
    {
        // return $id;
        return $this->hasOne(StudentLedger::class, 'student_ledgerable_id', 'id');
        // return $this->hasOne(StudentLedger::class,  'studentLedgerable_id', 'id')
            // ->select('id');
            // ->where('student_id', $id)
            // ->where('studentLedgerable_type', 'Batch');

            // return $hasPurchasee;
            // ->where('student_ledgerable_type', 'Batch');
            // return $this->morphOne(StudentLedger::class, 'studentLedgerable');

            // return $this->join('student_ledgers', 'student_ledgers.studentLedgerable_id', '=', 'batches.id')
            // ->get();

            // // return $query;
    }

    public function purchase()
    {
        // return $this->morphOne(StudentLedger::class, 'studentLedgerable')->where('ledger_type', 'Debit');
        // dd($this->id);
        // $id=intVal($this->id);
        // dd($attribute);

        $hasPurchasee = $this->morphOne(StudentLedger::class, 'student_ledgerable')
        // $hasPurchasee= $this->hasOne(StudentLedger::class, 'student_ledgerable_id', 'id')
        ->where('ledger_type', '=', 'Credit');
        // ->select('id','student_id');
        // ->where('student_ledgerable_type', 'App\Models\Batch');
        // ->first();
        // dd($hasPurchasee);
        return $hasPurchasee;

    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id')->select('id', 'name');
    }

    // public function studentLedgersdd()
    // {
    //     return $this->morphOne(StudentLedger::class, 'student_ledgerable');
    // }

    public function totalStudent()
    {
        return $this->morphMany(StudentLedger::class, 'student_ledgerable')->where('ledger_type', 'Credit');
    }

}
