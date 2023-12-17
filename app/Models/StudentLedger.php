<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Illuminate\Database\Eloquent\Relations\MorphTo;
// use App\Models\Batch;


class StudentLedger extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $guarded = [];
    protected $table = 'student_ledgers';
    protected $id='';

    protected $casts = [
        'student_id' => 'integer',
    ];

    public function studentLedgerable()
    {
        return $this->morphTo();
        // return $this->morphTo(__FUNCTION__, 'studentLedgerable_type', 'studentLedgerable_id');
    }

    public function studentBasicDetail()
    {
        return $this->belongsTo(Student::class, 'student_id')
                    ->withTrashed() // Include soft-deleted records
                    ->select('id', 'name');
    }

    public function getPaymentIdAttribute($value)
    {
        $details=json_decode($this->attributes['payment_id']);

        return $details->razorpay_payment_id;
    }
}
