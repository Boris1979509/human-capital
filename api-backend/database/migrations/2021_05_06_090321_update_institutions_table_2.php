<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateInstitutionsTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('institutions', function (Blueprint $table) {
            $table->renameColumn('rating_show', 'show_rating_students');

            $table->boolean('show_rating_employers')->default(true)->nullable();
            $table->unsignedInteger('avg_score_ege')->nullable();
            $table->unsignedInteger('avg_score_oge')->nullable();
            $table->unsignedInteger('percent_enrolled_budget')->nullable();
            $table->unsignedInteger('count_directions')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('institutions', function (Blueprint $table) {
            $table->renameColumn('show_rating_students', 'rating_show');

            $table->dropColumn('show_rating_employers');
            $table->dropColumn('avg_score_ege');
            $table->dropColumn('avg_score_oge');
            $table->dropColumn('percent_enrolled_budget');
            $table->dropColumn('count_directions');
        });
    }
}
