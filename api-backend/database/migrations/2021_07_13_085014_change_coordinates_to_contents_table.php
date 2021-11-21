<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCoordinatesToContentsTable extends Migration
{
    public function up(): void
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
            $table->jsonb('coords')->nullable();
        });
    }

    public function down(): void
    {
    }
}
