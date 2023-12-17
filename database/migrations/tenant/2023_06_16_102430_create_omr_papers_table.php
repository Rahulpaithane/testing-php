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
        Schema::create('omr_papers', function (Blueprint $table) {
            $table->id();
            $table->enum('omr_type', ['Manual', 'Collection'])->nullable();
            $table->string('omr_code', 200)->nullable();
            $table->unsignedBigInteger('batch_id')->nullable();
            $table->unsignedBigInteger('batch_category_id')->nullable();
            $table->unsignedBigInteger('batch_sub_category_id')->nullable();
            $table->string('paper_name', 100)->nullable();
            $table->text('question_id')->nullable();
            $table->enum('option_format', ['Alphabetical', 'Numbering', 'Roman'])->nullable();
            $table->integer('total_question')->nullable();
            $table->string('numberPerQuestion',10)->nullable();
            $table->integer('total_marks')->nullable();
            $table->date('exam_date')->nullable();
            $table->string('exam_time')->nullable();
            $table->string('examDuration',10)->nullable();
            $table->enum('isNegative', ['Yes', 'No'])->nullable();
            $table->string('numberPerNegative',10)->nullable();
            $table->unsignedBigInteger('added_by')->nullable();
            $table->tinyInteger('status')->default(0)->comment('1=Active, 0=Inactive');
            $table->timestamp('created_at')->nullable()->default(\Carbon\Carbon::now('Asia/Kolkata'));
            $table->timestamp('updated_at')->nullable()->default(\Carbon\Carbon::now('Asia/Kolkata'))->useCurrentOnUpdate();
            $table->softDeletes();

            $table->foreign('batch_id')->references('id')->on('batches')->onDelete('cascade');
            $table->foreign('batch_category_id')->references('id')->on('batch_categories')->onDelete('cascade');
            $table->foreign('batch_sub_category_id')->references('id')->on('batch_sub_categories')->onDelete('cascade');
            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('omr_papers');
    }
};
