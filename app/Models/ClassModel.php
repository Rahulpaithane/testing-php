<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Config;
class ClassModel extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];
    protected $dates = ['deleted_at'];
    protected $table = 'classes';

    
 
    public function batch()
    {
        return $this->hasMany(Batch::class, 'class_id')
        ->select('id', 'class_id', 'name', 'badge', 'batch_image');
    }

    public function getImageAttribute()
    {
        return Config::get('app.url').$this->attributes['image'];
        // return  $this->purchase()->exists();
    }

}
