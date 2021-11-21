<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSelectionItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('selection_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('selection_id');
            $table->unsignedTinyInteger('content_type')->nullable();
            $table->integer('selectionable_id');
            $table->string('selectionable_type');
            $table->string('title', 128)->nullable();
            $table->string('description', 1000)->nullable();
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('selection_items');
    }
}
