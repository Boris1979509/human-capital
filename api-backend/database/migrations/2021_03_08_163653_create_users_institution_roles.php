<?php

use App\Models\University;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersInstitutionRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_institution_roles', function (Blueprint $table) {
            $table->foreignIdFor(User::class, 'user_id');
            $table->foreignIdFor(University::class, 'university_id');
            $table->unsignedTinyInteger('role');
            $table->boolean('approved')->default(false);
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
        Schema::dropIfExists('users_institution_roles');
    }
}
