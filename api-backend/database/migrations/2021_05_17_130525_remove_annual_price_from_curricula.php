<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveAnnualPriceFromCurricula extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('institution_curricula', 'annual_price')) {
            Schema::table('institution_curricula', function (Blueprint $table) {
                $table->dropColumn('annual_price');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('institution_curricula', function (Blueprint $table) {
            $table->string('annual_price')->nullable();
        });
    }
}
