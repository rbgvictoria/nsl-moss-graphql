<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTropicosNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tropicos.tropicos_names', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->timestamps();
            $table->string('scientific_name', 128);
            $table->string('scientific_name_with_authors', 256);
            $table->string('author', 64)->nullable();
            $table->string('basionym_author', 64)->nullable();
            $table->string('family', 64)->nullable();
            $table->string('rank', '16')->nullable();
            $table->string('rank_abbreviation', 16)->nullable();
            $table->string('genus', 64)->nullable();
            $table->string('specific_epithet', 64)->nullable();
            $table->string('infraspecific_epithet', 64)->nullable();
            $table->string('symbol', 16)->nullable();
            $table->integer('nomenclatural_status_id')->nullable();
            $table->string('nomenclatural_status_name', 64)->nullable();
            $table->text('display_reference')->nullable();
            $table->string('display_date', 64)->nullable();
            $table->integer('accepted_name_count')->nullable();
            $table->integer('synonym_count')->nullable();
            $table->string('citation', 128)->nullable();
            $table->string('copyright', 128)->nullable();
            $table->string('name_published_citation', 256)->nullable();
            $table->boolean('type_specimens')->nullable();
            $table->string('source')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tropicos.tropicos_names');
    }
}
