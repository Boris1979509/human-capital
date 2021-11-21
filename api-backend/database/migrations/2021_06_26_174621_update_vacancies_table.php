<?php

use App\Models\Employer\Employer;
use App\Models\Vacancy;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateVacanciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Vacancy::truncate();
        Schema::table('vacancies', function (Blueprint $table) {
            $table->dropColumn('employer_id');
        });
        Schema::table('vacancies', function (Blueprint $table) {
            $table->foreignIdFor(Employer::class, 'employer_id')->constrained();
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
