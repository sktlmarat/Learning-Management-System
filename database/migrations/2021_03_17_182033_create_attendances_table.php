<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->timestamp('original_date');
            $table->text('feedback');

            $table->bigInteger('student_id')->unsigned();
            $table->bigInteger('attendance_type_id')->unsigned();
            $table->bigInteger('session_id')->unsigned();

            $table->foreign('student_id')->references('id')
                ->on('users')->onDelete('cascade');
            $table->foreign('attendance_type_id')->references('id')
                ->on('attendance_types')->onDelete('cascade');
            $table->foreign('session_id')->references('id')
                ->on('sessions')->onDelete('cascade');

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
        Schema::dropIfExists('attendances');
    }
}
