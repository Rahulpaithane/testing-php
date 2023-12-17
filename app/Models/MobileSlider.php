<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
class MobileSlider extends Model
{
    use HasFactory, SoftDeletes;
    protected $casts = [
        'isClickable' => 'integer',
        'class_id' => 'integer',
        'batch_id' => 'integer',
        'status' => 'integer',
    ];
    public function getImageAttribute()
    {
        return Config::get('app.url').$this->attributes['image'];

    }
}
