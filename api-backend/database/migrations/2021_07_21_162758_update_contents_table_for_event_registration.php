<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateContentsTableForEventRegistration extends Migration
{
    public function up()
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->boolean('is_registration_required')->default(false);
            $table->dateTime('registration_available_till')->nullable();
            $table->integer('available_registration_slots')->nullable();
            $table->jsonb('registration_fields')->nullable();
            $table->jsonb('registration_questions')->nullable();
            $table->boolean('is_registration_auto')->default(false);
            $table->string('registration_auto_period')->nullable();
            $table->boolean('is_registration_reminders_enabled')->default(false);
            $table->json('registration_reminder_periods')->nullable();
        });
    }

    public function down()
    {
        //
    }
}
