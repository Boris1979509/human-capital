<?php

namespace Database\Seeders;

use App\Models\Institution\Institution;
use App\Models\InstitutionRoles;
use App\Models\User;
use Illuminate\Database\Seeder;

class CurriculaSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $eduForms = collect([48, 49, 50, 51, 52]);
        $auditories = collect([45, 46, 47]);

        foreach (range(1, 10) as $i) {
            /** @var User $userWithCurricula */
            $userWithCurricula = User::create([
                'email' => "user$i@with.curricula", 'password' => bcrypt('123123')
            ]);

            //{"auditory":1,"edu_form":2,"passing_score":"234","available_places":"234","cost":"234234","how_long":"33","start_date":"34"}
            /** @var Institution $institutionWithCurricula */
            $institutionWithCurricula = Institution::create([
                'full_name' => "institution with curricula $i"
            ]);
            $institutionWithCurricula->managers()->attach(
                $userWithCurricula->id,
                ['role' => InstitutionRoles::OWNER, 'approved' => true]
            );

            foreach (range(1, 3) as $j) {
                $institutionWithCurricula->curricula()->create([
                    'name' => 'curriculum',
                    'is_published' => true,
                    'learning_options' => [
                        [
                            'auditory' => $auditories->random(),
                            'edu_form' => $eduForms->random(),
                            'cost' => random_int(100, 1000),
                        ],
                        [
                            'auditory' => $auditories->random(),
                            'edu_form' => $eduForms->random(),
                            'cost' => random_int(100, 1000),
                        ],
                        [
                            'auditory' => $auditories->random(),
                            'edu_form' => $eduForms->random(),
                            'cost' => random_int(100, 1000),
                        ],
                    ]
                ]);
            }
        }
    }
}
