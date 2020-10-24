<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTropicosNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tropicos.tropicos_names', function (Blueprint $table) {
            $table->string('rank', 32)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tropicos.tropicos_names', function (Blueprint $table) {
            $table->string('rank', 16)->change();
        });
    }
}
