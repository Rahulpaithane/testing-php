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
        
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email', 200)->nullable();
            $table->string('mobile', 10)->nullable();
            $table->string('password', 250);
            $table->text('reset_token')->nullable();
            $table->string('profile_image', 250)->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->string('dob', 20)->nullable();
            $table->string('aadhar_no', 12)->nullable();
            $table->text('address')->nullable();
            $table->integer('activeClassId')->nullable();
            $table->integer('activeBatchId')->nullable();
            $table->string('device_id', 100)->nullable();
            $table->string('app_version', 10)->nullable();
            $table->enum('prepration', ['School', 'Govt'])->default('Govt');
            $table->tinyInteger('status')->default(1)->comment('1=Active, 0=Inactive');
            $table->timestamp('created_at')->nullable()->default(\Carbon\Carbon::now('Asia/Kolkata'));
            $table->timestamp('updated_at')->nullable()->default(\Carbon\Carbon::now('Asia/Kolkata'))->useCurrentOnUpdate();
            $table->softDeletes();
            // $table->foreign('activeBatchId')->references('id')->on('batches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
