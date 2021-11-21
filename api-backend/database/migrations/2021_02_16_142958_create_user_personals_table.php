<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPersonalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_personals', function (Blueprint $table) {
            $table->unsignedInteger('user_id');

            $table->string('first_name', 64)->nullable();
            $table->string('middle_name', 64)->nullable();
            $table->string('last_name', 64)->nullable();
            $table->unsignedTinyInteger('sex')->nullable();
            $table->timestamp('birthday')->nullable();
            $table->unsignedTinyInteger('nationality_id')->nullable();
            $table->unsignedTinyInteger('city_id')->nullable();
            $table->unsignedTinyInteger('country_id')->nullable();

            $table->unsignedTinyInteger('document_id')->nullable();
            $table->string('document_series', 50)->nullable();
            $table->string('document_number', 50)->nullable();
            $table->timestamp('document_date')->nullable();
            $table->string('inn', 50)->nullable();
            $table->string('snills', 50)->nullable();

            $table->string('link_vk', 64)->nullable();
            $table->string('link_fb', 64)->nullable();
            $table->string('description', 64)->nullable();
            $table->string('skills', 1000)->nullable();
            $table->string('qualities', 1000)->nullable();

            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_personals');
    }
}
