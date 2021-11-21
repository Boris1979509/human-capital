<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Dictionary;
use App\Models\University;
use App\Models\User;
use Encore\Admin\Auth\Database\Menu;
use Illuminate\Database\Seeder;

class UserForResumeSeeder extends Seeder
{
    public function run()
    {
        $user = User::create([
            'phone' => 'phone',
            'email' => 'some@email.com',
            'password' => bcrypt('123123'),
        ]);
        $user->personal()->create([
            'first_name' => 'first_name',
            'last_name' => 'last_name',
            'birthday' => now()->subYears(20),
            'city_id' => City::create(['name' => 'city'])->id,
            'skills' => ['skill1', 'skill2'],
            'description' => 'description'
        ]);
        $user->jobs()->create([
            'company' => 'company2',
            'website' => 'website2',
            'position' => 'position2',
            'description' => 'description2',
            'year_begin' => 2018,
            'year_end' => 2019,
            'month_begin' => 11,
            'month_end' => 12,
            'until_now' => false
        ]);
        $user->jobs()->create([
            'company' => 'company1',
            'website' => 'website1',
            'position' => 'position1',
            'description' => 'description1',
            'year_begin' => 2020,
            'year_end' => null,
            'month_begin' => 12,
            'month_end' => null,
            'until_now' => true
        ]);

        $user->education()->create([
            'year_begin' => 2008,
            'year_end' => 2012,
            'specialty' => 'specialty2',
            'edu_quality_id' => Dictionary::create(['option' => 'quality2'])->id,
            'university_id' => University::create(['title' => 'university2'])->id
        ]);
        $user->education()->create([
            'year_begin' => 2012,
            'year_end' => 2016,
            'specialty' => 'specialty1',
            'edu_quality_id' => Dictionary::create(['option' => 'quality1'])->id,
            'university_id' => University::create(['title' => 'university1'])->id
        ]);
    }
}
