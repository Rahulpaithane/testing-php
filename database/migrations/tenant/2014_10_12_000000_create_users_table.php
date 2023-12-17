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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email', 200);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('mobile', 10)->nullable();
            $table->string('password', 250)->nullable();
            $table->text('reset_token')->nullable();
            $table->enum('role_type', ['Admin', 'Teacher']);
            $table->string('education', 100)->nullable();
            $table->string('profile_image', 300)->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->string('aadhar_no', 20)->nullable();
            $table->text('address')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
