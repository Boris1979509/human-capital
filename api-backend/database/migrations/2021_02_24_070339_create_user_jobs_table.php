<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('company', 256)->nullable();
            $table->string('website', 256)->nullable();
            $table->string('position', 256)->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('year_begin')->nullable();
            $table->unsignedInteger('year_end')->nullable();
            $table->unsignedTinyInteger('month_begin')->nullable();
            $table->unsignedTinyInteger('month_end')->nullable();
            $table->boolean('until_now')->nullable();
            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('user_jobs');
    }
}
