<?php

use App\Models\Institution\Institution;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToContents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('contents', 'user_id')) {
            Schema::table('contents', function (Blueprint $table) {
                $table->foreignIdFor(User::class)->nullable();
            });
        }
        if (Schema::hasColumn('contents', 'institution_id')) {
            Schema::table('contents', function (Blueprint $table) {
                $table->foreignIdFor(Institution::class, 'institution_id')->nullable()->change();
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
        if (Schema::hasColumn('contents', 'user_id')) {
            Schema::table('contents', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
        }
        if (Schema::hasColumn('contents', 'institution_id')) {
            Schema::table('contents', function (Blueprint $table) {
                $table->dropColumn('institution_id');
            });
        }
    }
}
