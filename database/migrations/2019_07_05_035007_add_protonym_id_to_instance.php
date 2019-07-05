<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Adds protonym_id field to instance table
 */
class AddProtonymIdToInstance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instance', function (Blueprint $table) {
            $table->bigInteger('protonym_id')->nullable();
            $table->index('protonym_id');
            $table->foreign('protonym_id')->references('id')->on('instance');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instance', function (Blueprint $table) {
            $table->dropColumn('protonym_id');
        });
    }
}
