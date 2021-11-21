<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateInstitutionCurriculaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('institution_curricula', function (Blueprint $table) {
            $table->dropColumn('learning_options');
            $table->dropColumn('admission_exams');
            $table->dropColumn('result_professions');
            $table->dropColumn('competitions');
            $table->dropColumn('is_visible');
        });

        Schema::table('institution_curricula', function (Blueprint $table) {
            $table->jsonb('learning_options')->nullable();
            $table->jsonb('admission_exams')->nullable();
            $table->jsonb('result_professions')->nullable();
            $table->jsonb('competitions')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
