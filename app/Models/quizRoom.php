<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Auth;
use Illuminate\Support\Facades\Config;
class quizRoom extends Model
{
    use HasFactory, SoftDeletes;

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

    public function getImageAttribute()
    {
        
        return Config::get('app.url').$this->attributes['image'];

    }

    public function getBannerAttribute()
    {
        
        return Config::get('app.url').$this->attributes['banner'];

    }
}
