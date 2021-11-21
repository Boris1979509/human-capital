<?php

use App\Models\Dictionary;
use App\Models\University;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUniversityCurriculasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('university_curricula', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(University::class, 'university_id');
            $table->string('name', 256);
            $table->boolean('is_visible')->default(true);
            $table->string('direction_of_study', 256)->nullable();
            $table->foreignIdFor(Dictionary::class, 'type_id')->nullable();
            $table->text('description')->nullable();
            $table->string('budget_places', 256)->nullable();
            $table->unsignedTinyInteger('passing_score')->nullable();
            $table->string('annual_price')->nullable();
            $table->string('duration')->nullable();
            $table->json('competitions')->nullable();
            $table->boolean('is_admission_exam')->default(false);
            $table->json('admission_exams')->nullable();
            $table->boolean('is_admission_olympiad')->default(false);
            $table->text('admission_olympiad_conditions')->nullable();
            $table->boolean('is_admission_additional_exam')->default(false);
            $table->text('admission_additional_exam_conditions')->nullable();
            $table->json('learning_options')->nullable();
            $table->json('result_professions')->nullable();
            $table->boolean('reviews_enabled')->default(true);
            $table->boolean('questions_enabled')->default(true);
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
        Schema::dropIfExists('university_curricula');
    }
}
