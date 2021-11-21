<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAutocompleteWordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('autocomplete_words', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('type');
            $table->string('word', 128)->index();
            $table->boolean('approved')->default(true);

            $table->unique(['type', 'word']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('autocomplete_words');
    }
}
