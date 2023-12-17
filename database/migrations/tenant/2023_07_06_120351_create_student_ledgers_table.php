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
        Schema::create('student_ledgers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->string('student_ledgerable_type', 100)->nullable();
            $table->integer('student_ledgerable_id');
            $table->string('invoice_id', 100)->nullable();
            $table->string('receipt_no', 100)->nullable();
            $table->enum('ledger_type', ['Debit', 'Credit']);
            $table->string('debit', 100)->nullable();
            $table->string('credit', 100)->nullable();
            $table->string('balance', 100);
            $table->text('remarks')->nullable();
            $table->text('payment_mode')->nullable();
            $table->text('payment_mode_details')->nullable();
            $table->text('comment')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1=Active, 0=Inactive');
            $table->timestamp('created_at')->nullable()->default(\Carbon\Carbon::now('Asia/Kolkata'));
            $table->timestamp('updated_at')->nullable()->default(\Carbon\Carbon::now('Asia/Kolkata'))->useCurrentOnUpdate();
            $table->softDeletes();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_ledgers');
    }
};
