<?php

use App\Models\EventRegistration;
use App\Models\Journal\Content;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventRegistrationsTable extends Migration
{
    public function up(): void
    {
        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Content::class, 'event_id')->constrained('contents');
            $table->foreignIdFor(User::class, 'user_id')->constrained('users');
            $table->smallInteger('status')->default(EventRegistration::STATUS_PENDING);
            $table->jsonb('fields')->nullable();
            $table->jsonb('questions')->nullable();
            $table->dateTime('accept_after')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_registrations');
    }
}
