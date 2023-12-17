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
        Schema::create('attempted_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('result_id');
            $table->enum('question_type', ['Normal', 'Group'])->nullable();
            $table->enum('language_type', ['English', 'Hindi'])->nullable();
            $table->unsignedInteger('question_id');
            $table->unsignedInteger('sub_question_id')->nullable();
            $table->string('answer', 10)->nullable();
            $table->enum('is_correct', ['Yes', 'No'])->nullable();
            $table->tinyInteger('status')->default(1)->comment('1=Active, 0=Inactive');
            $table->timestamp('created_at')->nullable()->default(\Carbon\Carbon::now('Asia/Kolkata'));
            $table->timestamp('updated_at')->nullable()->default(\Carbon\Carbon::now('Asia/Kolkata'))->useCurrentOnUpdate();
            $table->softDeletes();

            $table->foreign('result_id')->references('id')->on('exam_results')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attempted_questions');
    }
};
