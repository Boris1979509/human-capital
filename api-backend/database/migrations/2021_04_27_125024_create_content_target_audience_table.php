<?php

use App\Models\Dictionary;
use App\Models\Journal\Content;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentTargetAudienceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('content_target_audience')) {
            Schema::create('content_target_audience', function (Blueprint $table) {
                $table->foreignIdFor(Content::class, 'content_id');
                $table->foreignIdFor(Dictionary::class, 'target_audience_id');
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
        Schema::dropIfExists('content_target_audience');
    }
}
