<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BatchCategory extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $guarded = [];
    protected $table = 'batch_categories';

    protected $casts = [
        'batch_id' => 'integer',
    ];

    public function batchSubCategory()
    {
        return $this->hasMany(BatchSubCategory::class, 'batch_category_id')->select('id', 'batch_category_id', 'sub_category_name')->where('status', 1);
    }
}
