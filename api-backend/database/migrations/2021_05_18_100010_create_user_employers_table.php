<?php

use App\Models\City;
use App\Models\Dictionary;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserEmployersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_employers', function (Blueprint $table) {
            $table->unsignedInteger('user_id')->index();

            $table->string('name', 125)->nullable();
            $table->string('description', 1000)->nullable();
            $table->foreignIdFor(Dictionary::class, 'branch_id')->nullable();
            $table->unsignedInteger('count_employees')->nullable();
            $table->boolean('is_internship')->default(false);
            $table->foreignIdFor(City::class, 'city_id')->nullable();
            $table->string('address')->nullable();
            $table->decimal('latitude', 11, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('website')->nullable();
            $table->boolean('show_contacts')->default(true);
            $table->json('contacts')->nullable();

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
        Schema::dropIfExists('user_employers');
    }
}
