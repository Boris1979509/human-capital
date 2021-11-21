<?php

use App\Models\Dictionary;
use App\Models\Institution\Institution;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::dropIfExists('contents');
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('type');
            $table->foreignIdFor(Institution::class, 'institution_id');
            $table->string('title', 256);
            $table->string('slug', 256);
            $table->string('reading_time')->nullable();
            $table->text('text');
            $table->boolean('comments_enabled')->default(false);
            $table->boolean('is_published')->default(false);

            $table->date('date_start')->nullable();
            $table->date('date_end')->nullable();
            $table->string('time_start')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->json('tags')->nullable();
            $table->foreignIdFor(Dictionary::class, 'target_audience')->nullable();
            $table->foreignIdFor(Dictionary::class, 'participants_age')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('contents');
    }
}
