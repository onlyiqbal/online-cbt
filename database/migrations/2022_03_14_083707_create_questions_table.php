<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('type');
            $table->unsignedBigInteger('mapel_id');
            $table->foreign('mapel_id')->references('id')
                    ->on('mapels')
                    ->onUpdate('cascade');
            $table->unsignedBigInteger('class_room_id');
            $table->foreign('class_room_id')->references('id')
                    ->on('class_rooms')
                    ->onUpdate('cascade');
            $table->unsignedBigInteger('major_id');
            $table->foreign('major_id')
                    ->references('id')
                    ->on('majors')
                    ->onUpdate('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
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
        Schema::dropIfExists('questions');
    }
}
