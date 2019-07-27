<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTropicosReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tropicos.tropicos_references', function (Blueprint $table) {
            $table->string('author_string', 256)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tropicos.tropicos_references', function (Blueprint $table) {
            $table->string('author_string', 128)->change();
        });
    }
}
