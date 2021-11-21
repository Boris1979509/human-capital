<?php

use App\Models\Dictionary;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateContentsTable1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('contents', 'target_audience')) {
            Schema::table('contents', function (Blueprint $table) {
                $table->dropColumn('target_audience');
            });
        }
        if (Schema::hasColumn('contents', 'participants_age')) {
            Schema::table('contents', function (Blueprint $table) {
                $table->dropColumn('participants_age');
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
        Schema::table('contents', function (Blueprint $table) {
            $table->foreignIdFor(Dictionary::class, 'target_audience')->nullable();
            $table->foreignIdFor(Dictionary::class, 'participants_age')->nullable();
        });
    }
}
