<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnsInstitutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('institutions', function (Blueprint $table) {
            $table->unsignedFloat('rating_employers')->nullable()->change();
            $table->unsignedFloat('rate_employment')->nullable()->change();
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
            $table->dropColumn('rating_employers');
            $table->dropColumn('rate_employment');
        });

        Schema::table('institutions', function (Blueprint $table) {
            $table->unsignedInteger('rating_employers')->nullable();
            $table->unsignedInteger('rate_employment')->nullable();
        });
    }
}
