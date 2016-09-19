<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameNotesColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('artefacts', function (Blueprint $table) {
            $table->renameColumn('notes', 'description');
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
            $table->renameColumn('description', 'notes');
        });
    }
}
