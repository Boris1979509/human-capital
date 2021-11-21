<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUserPersonalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_personals', function (Blueprint $table) {
            $table->dropColumn('skills');
            $table->dropColumn('qualities');
        });
        Schema::table('user_personals', function (Blueprint $table) {
            $table->json('skills')->nullable();
            $table->json('qualities')->nullable();
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
