<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAusmossNamesTropicosNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tropicos.ausmoss_names_tropicos_names', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->bigInteger('ausmoss_name_id')->index();
            $table->bigInteger('tropicos_name_id')->index();
            $table->unique(['ausmoss_name_id', 'tropicos_name_id']);
            $table->foreign('ausmoss_name_id')->references('id')->on('public.name');
            $table->foreign('tropicos_name_id')->references('id')->on('tropicos.tropicos_names');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tropicos.ausmoss_names_tropicos_names');
    }
}
