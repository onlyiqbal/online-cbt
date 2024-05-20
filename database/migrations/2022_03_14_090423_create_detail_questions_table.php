<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_questions', function (Blueprint $table) {
            $table->id();
            $table->string('question_id');
            $table->foreign('question_id')
                    ->references('id')
                    ->on('questions')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            $table->longtext('question');
            $table->string('image')->nullable();
            $table->longtext('choice_1')->nullable();
            $table->longtext('choice_2')->nullable();
            $table->longtext('choice_3')->nullable();
            $table->longtext('choice_4')->nullable();
            $table->longtext('choice_5')->nullable();
            $table->string('key',50)->nullable();
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
        Schema::dropIfExists('detail_questions');
    }
}
