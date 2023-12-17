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
        Schema::create('omr_paper_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('batch_id');
            $table->unsignedBigInteger('omr_paper_id');
            $table->string('paper_name', 200)->nullable();
            $table->string('attemptDate',20);
            $table->string('total_question', 10)->nullable();
            $table->string('totalDurationTime',10);
            $table->string('attemptDurationTime',10);
            $table->integer('totalCorrect');
            $table->integer('totalWrong');
            $table->integer('totalSkipped');
            $table->string('attemptTotalMarks', 10);
            $table->string('rank',10);
            $table->string('accuracy',10);
            $table->string('average',10);
            $table->string('totalSavedTime',10);
            $table->text('attemptQuestionDetail');
            $table->timestamp('created_at')->nullable()->default(\Carbon\Carbon::now('Asia/Kolkata'));
            $table->timestamp('updated_at')->nullable()->default(\Carbon\Carbon::now('Asia/Kolkata'))->useCurrentOnUpdate();
            $table->softDeletes();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('batch_id')->references('id')->on('batches')->onDelete('cascade');
            $table->foreign('omr_paper_id')->references('id')->on('omr_papers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('omr_paper_results');
    }
};
