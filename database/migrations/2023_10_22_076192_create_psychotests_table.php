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
        Schema::create('psychotests', function (Blueprint $table) {
            $table->timestamps();
            $table->uuid('code')->primary();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('invoice_id')->constrained('invoices');
            $table->foreignId('answer_id')->nullable()->constrained('answers');
            $table->foreignId('result_id')->nullable()->constrained('results');
            $table->datetime('attempt_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('psychotests');
        Schema::enableForeignKeyConstraints();
    }
};
