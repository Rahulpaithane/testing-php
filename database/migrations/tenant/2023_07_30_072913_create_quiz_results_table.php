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
        Schema::create('quiz_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quiz_room_id');
            $table->unsignedBigInteger('student_id');
            $table->string('attemptDate',20);
            $table->integer('totalCorrect');
            $table->integer('totalWrong');
            $table->integer('totalSkipped');
            $table->string('attemptTotalMarks', 10);
            $table->string('rank',10);
            $table->timestamp('created_at')->nullable()->default(\Carbon\Carbon::now('Asia/Kolkata'));
            $table->timestamp('updated_at')->nullable()->default(\Carbon\Carbon::now('Asia/Kolkata'))->useCurrentOnUpdate();
            $table->softDeletes();
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('quiz_room_id')->references('id')->on('quiz_rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_results');
    }
};
