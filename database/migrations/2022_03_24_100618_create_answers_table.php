<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->integer('detail_questions_id');
            $table->string('answer')->nullable();
            $table->string('status')->nullable();
            $table->string('participant_id', 16);
            $table->foreign('participant_id')->references('id')
                ->on('participants')
                ->onUpdate('cascade');
            $table->unsignedBigInteger('exam_id');
            $table->foreign('exam_id')
                ->references('id')
                ->on('exams')
                ->onUpdate('cascade');
            $table->unsignedBigInteger('participant_session_id');
            $table->foreign('participant_session_id')
                ->references('id')
                ->on('participant_sessions')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('correct_by')->nullable();
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
        Schema::dropIfExists('answers');
    }
}