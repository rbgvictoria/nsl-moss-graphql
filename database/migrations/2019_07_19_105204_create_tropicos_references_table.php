<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTropicosReferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tropicos.tropicos_references', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->timestamps();
            $table->integer('publication_id')->nullable();
            $table->string('author_string', 128)->nullable();
            $table->string('article_title')->nullable();
            $table->string('collation', 256)->nullable();
            $table->string('abbreviated_title')->nullable();
            $table->string('title_page_year', 32)->nullable();
            $table->text('full_citation')->nullable();
            $table->index('publication_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tropicos.tropicos_references');
    }
}
