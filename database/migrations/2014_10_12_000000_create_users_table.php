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
            $table->date('date_of_birth')->nullable();
            $table->string('email')->unique();
            $table->string('phone', 20)->nullable();
            $table->string('password')->nullable(); // for google register
            $table->string('fullname');
            $table->string('bio_desc')->nullable();
            $table->string('skill_desc')->nullable();
            $table->enum('gender', array('male', 'female'));
            $table->string('profile_photo')->nullable();
            $table->enum('role', array('admin', 'client', 'psychologist'))->default('client');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('users');
        Schema::enableForeignKeyConstraints();
    }
};
