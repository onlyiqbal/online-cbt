<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipantSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participant_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_session_id');
            $table->foreign('exam_session_id')->references('id')
                    ->on('exam_sessions')
                    ->onUpdate('cascade');
            $table->string('participant_id',16);
            $table->foreign('participant_id')->references('id')
                    ->on('participants')
                    ->onUpdate('cascade');
            $table->unsignedBigInteger('exam_id');
            $table->foreign('exam_id')
                    ->references('id')
                    ->on('exams')
                    ->onUpdate('cascade');
            $table->string('status');
            $table->date('date');
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
        Schema::dropIfExists('participant_sessions');
    }
}
