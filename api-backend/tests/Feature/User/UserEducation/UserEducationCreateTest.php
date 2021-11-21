<?php

namespace Tests\Feature\User\UserEducation;

use App\Models\User;
use App\Models\UserPersonal\UserEducation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Storage;
use Tests\TestCase;

class UserEducationCreateTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;
    private string $method = "post";
    private User $user;
    private UserEducation $userEducation;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createUser();
        $this->userEducation = $this->user->education()->create();
        $this->endpoint = route('user.education.store');
    }

    /** @test */
    public function it_updates_user_education_files(): void
    {
        $this->actingAs($this->user);
        Storage::fake('public');

        $deletedMedia = $this->userEducation->addMedia(UploadedFile::fake()->create('deleted.jpg'))
            ->toMediaCollection('education');
        $unchangedMedia = $this->userEducation->addMedia(UploadedFile::fake()->create('unchanged.jpg'))
            ->toMediaCollection('education');

        $this->assertCount(2, $this->userEducation->getMedia('education'));
        $this->assertCount(2, Storage::disk('public')->allFiles());
        Storage::disk('public')->assertExists($deletedMedia->id.'/'.$deletedMedia->file_name);
        Storage::disk('public')->assertExists($unchangedMedia->id.'/'.$unchangedMedia->file_name);

        $newFile = $this->createTemporaryUpload();

        $response = $this->json($this->method, $this->endpoint, [
            'education' => [
                [
                    'id' => $this->userEducation->id,
                    'university' => 'university',
                    'document_number' => '123',
                    'files' => [
                        $unchangedMedia->id,
                        $newFile->id
                    ]
                ]
            ]
        ]);
        $response->assertCreated();

        $this->userEducation = $this->userEducation->fresh();
        $this->assertCount(2, Storage::disk('public')->allFiles());
        $this->assertFalse($this->userEducation->getMedia('education')->pluck('id')->contains($deletedMedia->id));
        Storage::disk('public')->assertMissing($deletedMedia->id.'/'.$deletedMedia->file_name);
        Storage::disk('public')->assertExists($unchangedMedia->id.'/'.$unchangedMedia->file_name);
        $this->assertCount(2, $this->userEducation->getMedia('education'));
    }
}
