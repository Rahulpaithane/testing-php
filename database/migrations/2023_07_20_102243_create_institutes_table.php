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
        Schema::create('institutes', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id')->nullable();
            $table->string('institute_name', 200);
            $table->string('gstNo', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('contactNo',20)->nullable();
            $table->string('ownerName',100)->nullable();
            $table->string('ownerMobileNo',10)->nullable();
            $table->string('ownerEmailId',100)->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('tenant_id')->references('id')->on('tenants')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institutes');
    }
};
