<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('institute_exam_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('institute_teacher_id')->nullable();
            $table->unsignedBigInteger('institute_exam_id');
            $table->string('name', 100);
            $table->tinyInteger('status')->default(1)->comment('1=Active, 0=Inactive');
            $table->timestamp('created_at')->nullable()->default(\Carbon\Carbon::now('Asia/Kolkata'));
            $table->timestamp('updated_at')->nullable()->default(\Carbon\Carbon::now('Asia/Kolkata'))->useCurrentOnUpdate();
            $table->softDeletes();
            $table->foreign('institute_teacher_id')->references('id')->on('institute_teachers')->onDelete('cascade');
            $table->foreign('institute_exam_id')->references('id')->on('institute_exams')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institute_exam_categories');
    }
};
