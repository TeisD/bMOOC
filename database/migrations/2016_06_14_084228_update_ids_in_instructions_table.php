<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateIdsInInstructionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instructions', function (Blueprint $table) {
            $table->renameColumn('topic', 'topic_id');
            $table->renameColumn('author', 'author_id');
            $table->renameColumn('type', 'type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instructions', function (Blueprint $table) {
            $table->renameColumn('topic_id', 'topic');
            $table->renameColumn('author_id', 'author');
            $table->renameColumn('type_id', 'type');
        });
    }
}
