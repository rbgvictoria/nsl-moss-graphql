<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTropicosAcceptedNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tropicos.tropicos_accepted_names', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->bigInteger('tropicos_name_id');
            $table->bigInteger('tropicos_accepted_name_id');
            $table->bigInteger('reference_id')->nullable();
            $table->index('tropicos_name_id');
            $table->index('tropicos_accepted_name_id');
            $table->index('reference_id');
            $table->unique(['tropicos_name_id', 'tropicos_accepted_name_id', 'reference_id'], 'tropicos_accepted_names_uniq');
            $table->foreign('tropicos_name_id')->references('id')->on('tropicos.tropicos_names');
            $table->foreign('tropicos_accepted_name_id')->references('id')->on('tropicos.tropicos_names');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tropicos.tropicos_accepted_names');
    }
}
