<?php

use App\Models\City;
use App\Models\Dictionary;
use App\Models\Profession;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVacanciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacancies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignIdFor(Profession::class, 'profession_id');

            $table->unsignedBigInteger('salary_min')->nullable();
            $table->unsignedBigInteger('salary_max')->nullable();
            $table->boolean('salary_negotiable')->default(false);
            $table->foreignIdFor(Dictionary::class, 'experience_level')->nullable();
            $table->foreignIdFor(Dictionary::class, 'employment_type')->nullable();
            $table->foreignIdFor(Dictionary::class, 'schedule')->nullable();
            $table->boolean('is_internship')->default(false);

            $table->jsonb('competitions');
            $table->jsonb('skills')->nullable();

            $table->text('responsibilities')->nullable();
            $table->text('requirements')->nullable();
            $table->text('conditions')->nullable();
            $table->text('description')->nullable();

            $table->foreignIdFor(City::class, 'city_id')->nullable();
            $table->string('working_address')->nullable();
            $table->boolean('is_working_address_visible')->default(false);
            $table->boolean('show_chat')->default(false);

            $table->foreignIdFor(User::class, 'employer_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vacancies');
    }
}
