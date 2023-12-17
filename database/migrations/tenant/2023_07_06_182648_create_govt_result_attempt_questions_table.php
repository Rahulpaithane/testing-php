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
        Schema::create('govt_result_attempt_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('govt_result_id');
            $table->unsignedBigInteger('exam_paper_id');
            $table->enum('question_type', ['Normal', 'Group'])->nullable();
            $table->enum('language_type', ['English', 'Hindi', 'Both'])->nullable();
            $table->unsignedBigInteger('question_bank_id');
            $table->unsignedBigInteger('sub_question_id')->nullable();
            $table->string('attemptAnswer',10)->nullable();
            $table->string('attemptTime',10)->nullable();
            $table->enum('attemptType', ['Attempt', 'Review', 'Blank', 'Not Attempt']);
            $table->string('correctAnswer',10);
            $table->enum('isAnswerCorrect', ['Yes', 'No', 'Blank']);
            $table->timestamp('created_at')->nullable()->default(\Carbon\Carbon::now('Asia/Kolkata'));
            $table->timestamp('updated_at')->nullable()->default(\Carbon\Carbon::now('Asia/Kolkata'))->useCurrentOnUpdate();
            $table->softDeletes();

            $table->foreign('govt_result_id')->references('id')->on('govt_results')->onDelete('cascade');
            $table->foreign('exam_paper_id')->references('id')->on('exam_papers')->onDelete('cascade');
            $table->foreign('question_bank_id')->references('id')->on('question_banks')->onDelete('cascade');
            $table->foreign('sub_question_id')->references('id')->on('question_bank_infos')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('govt_result_attampt_questions');
    }
};
