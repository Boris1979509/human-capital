<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstitutionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('institutions');
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('university_id')->nullable();
            $table->string('full_name', 500)->nullable();
            $table->string('short_name', 128)->nullable();
            $table->unsignedInteger('inst_type_id')->nullable();
            $table->unsignedInteger('inst_diploma_id')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('count_students')->nullable();
            $table->unsignedInteger('count_programs')->nullable();
            $table->unsignedInteger('avg_score')->nullable();
            $table->unsignedInteger('avg_salary')->nullable();
            $table->unsignedInteger('rating_students')->nullable();
            $table->unsignedInteger('rating_employers')->nullable();
            $table->unsignedInteger('rate_employment')->nullable();

            $table->string('website', 128)->nullable();
            $table->string('link_vk', 128)->nullable();
            $table->string('link_fb', 128)->nullable();

            $table->string('inn', 128)->nullable();
            $table->string('ogrn', 128)->nullable();
            $table->string('bank', 128)->nullable();
            $table->string('bank_inn', 128)->nullable();
            $table->string('account', 128)->nullable();
            $table->string('account_corr', 128)->nullable();
            $table->string('bik', 128)->nullable();
            $table->string('kpp', 128)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('institutions');
    }
}
