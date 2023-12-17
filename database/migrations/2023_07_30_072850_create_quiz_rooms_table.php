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
        Schema::create('quiz_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->enum('prepration', ['School', 'Govt']);
            $table->string('image', 250)->nullable();
            $table->string('banner', 250)->nullable();
            $table->longText('description')->nullable();
            $table->string('timeDurationPerQuestion', 10)->nullable();
            $table->text('question_id')->nullable(); 
            $table->enum('room_type', ['Free', 'Paid'])->nullable();
            $table->string('price', 10)->nullable();
            $table->unsignedBigInteger('added_by')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1=Active, 0=Inactive');
            $table->timestamp('created_at')->nullable()->default(\Carbon\Carbon::now('Asia/Kolkata'));
            $table->timestamp('updated_at')->nullable()->default(\Carbon\Carbon::now('Asia/Kolkata'))->useCurrentOnUpdate();
            $table->softDeletes();
            $table->foreign('added_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_rooms');
    }
};
