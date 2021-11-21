<?php

namespace Tests\Feature\Resume;

use App\Models\City;
use App\Models\Resume\Resume;
use App\Models\University;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResumeDTOTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_fetches_data_for_resume_correctly(): void
    {
        $user = $this->createUser([
            'phone' => 'phone',
            'email' => 'some@email.com'
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
            'edu_quality_id' => $this->createDictionary(1, ['option' => 'quality2'])->id,
            'university_id' => University::create(['title' => 'university2'])->id
        ]);
        $user->education()->create([
            'year_begin' => 2012,
            'year_end' => 2016,
            'specialty' => 'specialty1',
            'edu_quality_id' => $this->createDictionary(2, ['option' => 'quality1'])->id,
            'university_id' => University::create(['title' => 'university1'])->id
        ]);

        $resume = new Resume($user);
        $this->assertEquals('first_name last_name', $resume->getName());
        $this->assertEquals('20', $resume->getAge());
        $this->assertEquals('city', $resume->getCity());
        $this->assertEquals('some@email.com', $resume->getMail());
        $this->assertEquals('phone', $resume->getPhone());
        //TODO: rewrite for automated dгration calculation
        $this->assertEquals('1 год, 9 месяцев', $resume->getTotalExperience());
        $this->assertEquals('skill1, skill2', $resume->getSkills());
        $this->assertEquals('description', $resume->getDescription());

        $this->assertCount(2, $resume->getJobs());

        //TODO: rewrite for automated duration calculation
        $this->assertEquals('8 месяцев', $resume->getJobs()[0]->getPeriodDuration());
        $this->assertEquals('Декабрь 2020 - н.в.', $resume->getJobs()[0]->getPeriod());
        $this->assertEquals('company1', $resume->getJobs()[0]->getCompany());
        $this->assertEquals('position1', $resume->getJobs()[0]->getPosition());
        $this->assertEquals('website1', $resume->getJobs()[0]->getCompanyWebsite());
        $this->assertEquals('description1', $resume->getJobs()[0]->getDescription());

        $this->assertEquals('1 год, 1 месяц', $resume->getJobs()[1]->getPeriodDuration());
        $this->assertEquals('Ноябрь 2018 - Декабрь 2019', $resume->getJobs()[1]->getPeriod());
        $this->assertEquals('company2', $resume->getJobs()[1]->getCompany());
        $this->assertEquals('position2', $resume->getJobs()[1]->getPosition());
        $this->assertEquals('website2', $resume->getJobs()[1]->getCompanyWebsite());
        $this->assertEquals('description2', $resume->getJobs()[1]->getDescription());

        $this->assertCount(2, $resume->getEducation());

        $this->assertEquals('quality1', $resume->getEducation()[0]->getQuality());
        $this->assertEquals('2016', $resume->getEducation()[0]->getYear());
        $this->assertEquals('university1', $resume->getEducation()[0]->getUniversity());
        $this->assertEquals('specialty1', $resume->getEducation()[0]->getSpecialty());

        $this->assertEquals('quality2', $resume->getEducation()[1]->getQuality());
        $this->assertEquals('2012', $resume->getEducation()[1]->getYear());
        $this->assertEquals('university2', $resume->getEducation()[1]->getUniversity());
        $this->assertEquals('specialty2', $resume->getEducation()[1]->getSpecialty());
    }
}
