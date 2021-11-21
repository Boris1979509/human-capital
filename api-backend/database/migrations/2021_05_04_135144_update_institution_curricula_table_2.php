<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateInstitutionCurriculaTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('institution_curricula', function (Blueprint $table) {
            $table->json('worth')->nullable();
            $table->jsonb('result_skills')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('institution_curricula', function (Blueprint $table) {
            $table->dropColumn('worth');
            $table->dropColumn('result_skills');
        });
    }
}
