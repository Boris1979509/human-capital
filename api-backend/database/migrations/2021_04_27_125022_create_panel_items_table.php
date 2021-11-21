<?php

use App\Models\UI\Panel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePanelItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('panel_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Panel::class, 'panel_id');
            $table->string('title', 100)->nullable();
            $table->string('description', 100)->nullable();
            $table->unsignedInteger('sort')->default(0)->nullable();
            $table->unsignedInteger('dictionary_type')->nullable();
            $table->unsignedInteger('dictionary_id');
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
        Schema::dropIfExists('panel_items');
    }
}
