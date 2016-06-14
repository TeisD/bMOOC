<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameArtefactTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('artefact_types', function (Blueprint $table) {
            Schema::rename('artefact_types', 'types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('artefact_types', function (Blueprint $table) {
            Schema::rename('types', 'artefact_types');
        });
    }
}
