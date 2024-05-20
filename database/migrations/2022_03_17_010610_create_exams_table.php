<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('kode_ujian');
            $table->string('name');
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
            $table->enum('random_question', ['random', 'not_random']);
            $table->string('soal_1')->nullable();
            $table->string('soal_2')->nullable();
            $table->string('description')->nullable();
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
        Schema::dropIfExists('exams');
    }
}
