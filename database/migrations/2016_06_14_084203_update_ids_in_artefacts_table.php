<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateIdsInArtefactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('artefacts', function (Blueprint $table) {
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
        Schema::table('artefacts', function (Blueprint $table) {
            $table->renameColumn('topic_id', 'topic');
            $table->renameColumn('author_id', 'author');
            $table->renameColumn('type_id', 'type');
        });
    }
}
