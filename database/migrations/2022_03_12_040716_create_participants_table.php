<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->string('id',16)->primary();
            $table->string('fullname',150);
            $table->string('jen_kel',10);
            $table->unsignedBigInteger('class_room_id');
            $table->foreign('class_room_id')->references('id')
                    ->on('class_rooms')
                    ->onUpdate('cascade');
            $table->unsignedBigInteger('major_id');
            $table->foreign('major_id')
                    ->references('id')
                    ->on('majors')
                    ->onUpdate('cascade');
            $table->string('shcool_name');
            $table->string('photo')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            $table->string('created_by',50)->nullable();
            $table->string('updated_by',50)->nullable();
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
        Schema::dropIfExists('participants');
    }
}
