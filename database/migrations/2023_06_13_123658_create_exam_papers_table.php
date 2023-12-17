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
        Schema::create('exam_papers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('batch_id')->nullable();
            $table->unsignedBigInteger('batch_category_id')->nullable();
            $table->unsignedBigInteger('batch_sub_category_id')->nullable();
            $table->string('paper_name', 200)->nullable();
            $table->boolean('is_live')->default(false)->comment('1=live, 0=not live');
            $table->enum('language_type', ['English', 'Hindi', 'Both'])->nullable();
            $table->string('total_question', 10)->nullable();
            $table->integer('total_duration')->comment('in minutes')->nullable();
            $table->integer('total_marks')->nullable();
            $table->text('question_id')->nullable();
            $table->date('exam_date')->nullable();
            $table->string('exam_time')->nullable();
            $table->string('per_question_no', 10)->nullable();
            $table->enum('negative_marking_type', ['Yes', 'No'])->nullable();
            $table->string('per_negative_no', 10)->nullable();
            $table->boolean('is_active')->default(true)->comment('1=active, 0=inactive');
            $table->unsignedBigInteger('added_by');
            $table->tinyInteger('status')->default(1)->comment('1=Active, 0=Inactive');
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
        Schema::dropIfExists('exam_papers');
    }
};
