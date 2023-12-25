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
        Schema::create('counselings', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('psikolog_id')->constrained('users');
            $table->foreignId('invoice_id')->constrained('invoices');
            $table->foreignId('result_id')->constrained('results')->nullable();
            $table->date('meet_date')->nullable();
            $table->time('meet_time')->nullable();
            $table->string('meet_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('counselings');
        Schema::enableForeignKeyConstraints();

    }
};
