<?php

namespace Tests;

use App\Models\City;
use App\Models\Comment;
use App\Models\Dictionary;
use App\Models\Employer\VacancyResponse;
use App\Models\EventRegistration;
use App\Models\Institution\Institution;
use App\Models\Institution\InstitutionCurriculum;
use App\Models\InstitutionRoles;
use App\Models\Journal\Content;
use App\Models\Journal\ContentType;
use App\Models\Journal\EventSpeaker;
use App\Models\Profession;
use App\Models\Selection\Selection;
use App\Models\TemporaryUpload;
use App\Models\User;
use App\Models\Employer\Employer;
use App\Models\Vacancy;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Laravel\Passport\Passport;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * @param  Institution  $institution
     * @param  int  $count
     * @param  array  $attributes
     * @return Collection|InstitutionCurriculum[]
     */
    protected function createCurriculaForInstitution(Institution $institution, int $count = 3, array $attributes = [])
    {
        return InstitutionCurriculum::factory(['institution_id' => $institution->id])
            ->count($count)
            ->create($attributes);
    }

    protected function createInstitution(array $attributes = []): Institution
    {
        return Institution::factory()->create($attributes);
    }

    protected function createManagerOfInstitution(Institution $institution): User
    {
        $manager = $this->createUser();
        $institution->managers()->attach($manager, ['role' => InstitutionRoles::OWNER, 'approved' => true]);
        return $manager;
    }

    protected function createUser($attributes = []): User
    {
        return User::factory()->create($attributes);
    }

    protected function createNewsArticle(array $attributes = []): Content
    {
        return $this->createContent(array_merge(['type' => ContentType::NEWS], $attributes));
    }

    protected function createArticle(array $attributes = []): Content
    {
        return $this->createContent(array_merge(['type' => ContentType::ARTICLE], $attributes));
    }

    protected function createEvent(array $attributes = []): Content
    {
        return $this->createContent(array_merge(
            ['type' => ContentType::EVENT, 'available_registration_slots' => 1000], $attributes
        ));
    }

    protected function createContent(array $attributes = []): Content
    {
        return Content::factory()->create($attributes);
    }

    /**
     * @param  Content  $event
     * @param  int  $count
     * @return Collection|EventSpeaker[]
     */
    protected function createSpeakersForEvent(Content $event, int $count = 2)
    {
        return EventSpeaker::factory()->count($count)->create(['content_id' => $event->id]);
    }

    protected function createSpeakerForEvent(Content $content): EventSpeaker
    {
        return EventSpeaker::factory()->create(['content_id' => $content->id]);
    }

    public function actingAs(UserContract $user, $driver = null): TestCase
    {
        Passport::actingAs($user);
        return $this;
    }

    protected function createTemporaryUpload(array $attributes = []): Media
    {
        $file = UploadedFile::fake()->image(Str::random().'.jpg');
        $temporaryUpload = TemporaryUpload::create($attributes);
        $temporaryUpload->addMedia($file)->usingFileName($file->hashName())->toMediaCollection();
        return $temporaryUpload->getFirstMedia();
    }

    protected function createDictionary(int $dictionaryId = null, array $attributes = []): Dictionary
    {
        if ($dictionaryId) {
            return Dictionary::factory()->create(array_merge(['id' => $dictionaryId], $attributes));
        }
        return Dictionary::factory()->create($attributes);
    }

    protected function createSelection(): Selection
    {
        return Selection::factory()->create();
    }

    protected function createComment(array $attributes = []): Comment
    {
        return Comment::factory()->create($attributes);
    }

    protected function createEmployer(User $user = null, array $attributes = []): Employer
    {
        if (!$user) {
            $user = $this->createUser(['type' => User::TYPE_USER_EMPLOYER]);
        }
        return Employer::factory()->create(array_merge(['user_id' => $user->id], $attributes));
    }

    protected function createVacancy(array $attributes = []): Vacancy
    {
        return Vacancy::factory()->create($attributes);
    }

    protected function createVacancyForEmployer(Employer $employer, array $attributes = []): Vacancy
    {
        return Vacancy::factory()->create(array_merge($attributes, ['employer_id' => $employer->id]));
    }

    protected function createCity(array $attributes = []): City
    {
        return City::factory()->create($attributes);
    }

    protected function createProfession(array $attributes = []): Profession
    {
        return Profession::factory()->create($attributes);
    }

    protected function createVacancyResponse(array $attributes = []): VacancyResponse
    {
        return VacancyResponse::factory()->create($attributes);
    }

    protected function createEventRegistration(array $attributes = []): EventRegistration
    {
        return EventRegistration::factory()->create($attributes);
    }
}
