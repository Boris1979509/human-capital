<?php

use App\Models\Dictionary;
use App\Models\Journal\Content;
use App\Models\UI\Panel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentParticipantsAgeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('content_participants_age')) {
            Schema::create('content_participants_age', function (Blueprint $table) {
                $table->foreignIdFor(Content::class, 'content_id');
                $table->foreignIdFor(Dictionary::class, 'participants_age_id');
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
        Schema::dropIfExists('content_participants_age');
    }
}
