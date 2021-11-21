<?php

use App\Models\Journal\Content;
use Illuminate\Database\Migrations\Migration;

class UpdateFillPublishedAtField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Content::where('is_published', true)
            ->whereNull('published_at')
            ->get()
            ->each(fn (Content $content) => $content->update(['published_at' => $content->created_at]));
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
