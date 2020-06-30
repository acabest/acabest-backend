<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizPacksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quiz_packs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('tutor_id');
            $table->string('title');
            $table->text('short_description');
            $table->text('detailed_description');
            $table->bigInteger('course_id');
            $table->text('topic');
            $table->string('thumbnail_image');
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
        Schema::dropIfExists('quiz_packs');
    }
}
