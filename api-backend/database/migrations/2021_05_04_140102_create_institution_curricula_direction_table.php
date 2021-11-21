<?php

use App\Models\Dictionary;
use App\Models\Institution\InstitutionCurriculum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstitutionCurriculaDirectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('institution_curricula_direction', function (Blueprint $table) {
            $table->foreignIdFor(InstitutionCurriculum::class, 'curriculum_id');
            $table->foreignIdFor(Dictionary::class, 'direction_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('institution_curricula_direction');
    }
}
