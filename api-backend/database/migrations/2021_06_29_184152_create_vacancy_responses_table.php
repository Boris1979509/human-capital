<?php

use App\Models\Employer\VacancyResponse;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CreateVacancyResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacancy_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'user_id')->constrained();
            $table->foreignIdFor(Vacancy::class, 'vacancy_id')->constrained();
            $table->string('status');
            $table->tinyInteger('cv_type');
            $table->foreignIdFor(Media::class, 'cv_file_id')->nullable()->constrained('media');
            $table->text('covering_letter')->nullable();
            $table->boolean('deleted_by_user')->default(false);
            $table->jsonb('invite')->nullable();
            $table->jsonb('rejection')->nullable();
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
        Schema::dropIfExists('vacancy_responses');
    }
}
