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
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('class_id')->nullable();
            $table->string('name', 100)->nullable();
            $table->string('badge', 100)->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('batch_type', ['Free', 'Paid'])->nullable();
            $table->string('batch_price', 10)->nullable();
            $table->string('batch_offer_price', 10)->nullable();
            $table->longText('description')->nullable();
            $table->string('batch_image', 250)->nullable();
            $table->integer('no_of_student')->nullable();
            $table->tinyInteger('is_popular')->default(0)->comment('0=Normal, 1=Popular');
            $table->tinyInteger('status')->default(1)->comment('1=Active, 0=Inactive');
            $table->timestamp('created_at')->nullable()->default(\Carbon\Carbon::now('Asia/Kolkata'));
            $table->timestamp('updated_at')->nullable()->default(\Carbon\Carbon::now('Asia/Kolkata'))->useCurrentOnUpdate();
            $table->softDeletes();

            $table->foreign('class_id')->references('id')->on('classes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};
