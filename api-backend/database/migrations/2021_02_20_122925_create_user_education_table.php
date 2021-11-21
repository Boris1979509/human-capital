<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserEducationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_education', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('university_id')->nullable();
            $table->unsignedInteger('edu_degree_id')->nullable();
            $table->unsignedInteger('edu_status_id')->nullable();
            $table->unsignedInteger('edu_quality_id')->nullable();
            $table->unsignedInteger('year_begin')->nullable();
            $table->unsignedInteger('year_end')->nullable();
            $table->string('specialty', 128)->nullable();
            $table->string('document_number', 64)->nullable();
            $table->timestamp('document_date')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('user_id');
            $table->index('university_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_education');
    }
}
