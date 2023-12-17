<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BatchSubCategory extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $guarded = [];
    protected $table = 'batch_sub_categories';

    protected $casts = [
        'batch_id' => 'integer',
        'batch_category_id' => 'integer',
    ];

    // public function batchCategory(){
    //     return $this->belongsTo(BatchCategory::class);
    // }
}
