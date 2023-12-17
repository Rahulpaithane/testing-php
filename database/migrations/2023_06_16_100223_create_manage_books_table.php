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
        Schema::create('manage_books', function (Blueprint $table) {

            $table->id();
            $table->text('book_name')->nullable();
            $table->string('author', 100)->nullable();
            $table->string('publication', 100)->nullable();
            $table->text('class')->nullable()->comment("['ssc', 'bank']");
            $table->enum('book_type', ['E-Book', 'Physical'])->nullable();
            $table->integer('stock')->nullable();
            $table->boolean('is_payable')->nullable();
            $table->unsignedBigInteger('added_by')->nullable();
            $table->string('price', 100)->nullable();
            $table->text('thumbnail')->nullable();
            $table->text('attachment')->nullable();
            $table->longText('description')->nullable();
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
        Schema::dropIfExists('manage_books');
    }
};
