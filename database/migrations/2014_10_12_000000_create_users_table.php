<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'instructor', 'student']);
            $table->bigInteger('department_id')->unsigned();
            $table->foreign('department_id')->references('id')->on('departments');
            $table->bigInteger('adviser_id')->unsigned()->nullable();
            $table->foreign('adviser_id')->references('id')->on('users')->onDelete('set null');
            $table->enum('registration_status', ['approved', 'pending', 'rejected'])->nullable();
            $table->string('avatar')->default('default-avatar.png');
            $table->enum('year_of_study', ['foundation', '1', '2', '3', '4'])->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
