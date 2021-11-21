<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateInstitutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('institutions', function (Blueprint $table) {
            $table->boolean('rating_show')->default(true)->nullable();
            $table->boolean('entrance_test')->nullable();
            $table->string('entrance_test_description', 1000)->nullable();
            $table->boolean('employment_assistance')->nullable();
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
            $table->dropColumn('rating_show');
            $table->dropColumn('entrance_test');
            $table->dropColumn('entrance_test_description');
            $table->dropColumn('employment_assistance');
        });
    }
}
