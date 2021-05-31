<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name_en')->nullable();
            $table->string('last_name_en')->nullable();
            $table->string('first_name_ar')->nullable();
            $table->string('last_name_ar')->nullable();
            $table->string('country')->nullable();
            $table->string('password')->nullable();
            $table->enum('gender', ['Male', 'Female'])->nullable();
            $table->json('address')->nullable();
            $table->boolean('locked')->default(false);
            $table->boolean('two_step_verification')->default(false);
            $table->boolean('auto_generated_password')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
