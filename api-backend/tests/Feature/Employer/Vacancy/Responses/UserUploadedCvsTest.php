<?php

namespace Tests\Feature\Employer\Vacancy\Responses;

use App\Models\Employer\Employer;
use App\Models\Employer\VacancyResponse;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserUploadedCvsTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;
    private string $method = "get";
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createUser();
        $this->endpoint = route('user.uploaded-cvs');
    }

    /** @test */
    public function user_can_fetch_his_uploaded_cvs(): void
    {
        Storage::fake('public');

        $cvMedia1 = $this->user->addMedia(UploadedFile::fake()->create('cv1.pdf'))
            ->toMediaCollection('job');
        $cvMedia2 = $this->user->addMedia(UploadedFile::fake()->create('cv2.pdf'))
            ->toMediaCollection('job');
        $this->user->addMedia(UploadedFile::fake()->create('cv2.pdf'))
            ->toMediaCollection('avatar');
        $this->createUser()->addMedia(UploadedFile::fake()->create('cv2.pdf'))
            ->toMediaCollection('job');

        $this->assertDatabaseCount('media', 4);

        $response = $this->actingAs($this->user)->json($this->method, $this->endpoint);
        $response->assertOk();

        $this->assertCount(2, $response->json('data'));
        $this->assertEquals($cvMedia1->id, $response->json('data.0.id'));
        $this->assertEquals($cvMedia2->id, $response->json('data.1.id'));
    }


    /** @test */
    public function only_authorized_user_can_fetch_his_uploaded_cvs(): void
    {
        $responseData = [
            'cv_type' => VacancyResponse::CV_TYPE_GENERATED,
            'covering_letter' => 'some covering letter text'
        ];
        $this->json($this->method, $this->endpoint, $responseData)->assertUnauthorized();
    }
}
