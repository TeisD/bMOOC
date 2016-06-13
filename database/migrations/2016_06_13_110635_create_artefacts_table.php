<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArtefactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('artefacts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('topic');
            $table->integer('author');
            $table->string('title', 100);
            $table->integer('type');
            $table->text('content');
            $table->text('notes');
            $table->string('copyright', 255);
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
        Schema::drop('artefacts');
    }
}
