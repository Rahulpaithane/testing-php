<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;
use Illuminate\Support\Facades\Config;
class ManageBook extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $guarded = [];
    protected $appends = ['isPurchased'];


    public function getIsPurchasedAttribute()
    {

        if(Auth::guard('api')->user()){
            return $check  = $this->purchase()->where('student_id', Auth::guard('api')->user()->id)->exists();
            
        } else {
            return false;
        }

    }

    public function purchase()
    {

        $hasPurchasee = $this->morphOne(StudentLedger::class, 'student_ledgerable')
        ->where('ledger_type', '=', 'Credit');
        return $hasPurchasee;

    }

    public function getThumbnailAttribute()
    {
        return Config::get('app.url').$this->attributes['thumbnail'];

    }

    public function getAttachmentAttribute()
    {
        return Config::get('app.url').$this->attributes['attachment'];

    }

    public function getIsPayableAttribute()
    {
        if($this->attributes['is_payable']==0 ){
            return false;
        } else {
            return true;
        }

    }

}
