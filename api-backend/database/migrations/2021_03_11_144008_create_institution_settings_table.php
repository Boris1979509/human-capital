<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstitutionSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('institution_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('institution_id');
            $table->unsignedInteger('user_id');
            $table->string('key', 128);
            $table->string('value', 1000)->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'institution_id', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('institution_settings');
    }
}
