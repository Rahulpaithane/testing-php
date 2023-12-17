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
        Schema::create('institute_teachers', function (Blueprint $table) {
            $table->id();
            $table->text('instituteName')->nullable();
            $table->text('address')->nullable();
            $table->string('teacherName', 100);
            $table->string('email', 200)->nullable();
            $table->string('mobile', 10)->nullable();
            $table->string('password', 250);
            $table->text('reset_token')->nullable();
            $table->string('institute_image', 250)->nullable();
            $table->string('profile_image', 250)->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->string('device_id', 100)->nullable();
            $table->string('app_version', 10)->nullable();
            $table->tinyInteger('status')->default(1)->comment('1=Active, 0=Inactive');
            $table->timestamp('created_at')->nullable()->default(\Carbon\Carbon::now('Asia/Kolkata'));
            $table->timestamp('updated_at')->nullable()->default(\Carbon\Carbon::now('Asia/Kolkata'))->useCurrentOnUpdate();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institute_teachers');
    }
};
