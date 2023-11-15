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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->enum('status', array('wait', 'review', 'finish'))->nullable(); // should I remove this? ::jadwal
            $table->string('personality')->nullable();
            $table->string('attachment_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {     
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('results');
        Schema::enableForeignKeyConstraints();
    }
};
