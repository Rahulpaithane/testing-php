<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class omrPaperResult extends Model
{
    use HasFactory, SoftDeletes;
    protected $casts = [
        'student_id' => 'integer',
        'batch_id' => 'integer',
        'omr_paper_id' => 'integer',
        'totalCorrect' => 'integer',
        'totalWrong' => 'integer',
        'totalSkipped' => 'integer',
    ];
    public function getRankAttribute()
    {
        
        return strval($this->attributes['rank']);

    }

}
