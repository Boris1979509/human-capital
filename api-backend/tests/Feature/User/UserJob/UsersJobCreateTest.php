<?php

namespace Tests\Feature\User\UserJob;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Storage;
use Tests\TestCase;

class UsersJobCreateTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;
    private string $method = "post";
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = $this->createUser();
        $this->endpoint = route('user.job.store');
    }

    /** @test */
    public function it_updates_user_job_files(): void
    {
        $this->actingAs($this->user);
        Storage::fake('public');

        $deletedMedia = $this->user->addMedia(UploadedFile::fake()->create('deleted.jpg'))
            ->toMediaCollection('job');
        $unchangedMedia = $this->user->addMedia(UploadedFile::fake()->create('unchanged.jpg'))
            ->toMediaCollection('job');

        $this->assertCount(2, $this->user->getMedia('job'));
        $this->assertCount(2, Storage::disk('public')->allFiles());
        Storage::disk('public')->assertExists($deletedMedia->id.'/'.$deletedMedia->file_name);
        Storage::disk('public')->assertExists($unchangedMedia->id.'/'.$unchangedMedia->file_name);

        $newFile = $this->createTemporaryUpload();

        $response = $this->json($this->method, $this->endpoint, [
            'files' => [
                $unchangedMedia->id,
                $newFile->id
            ],
            'skills' => ['1'],
            'qualities' => ['1'],
        ]);
        $response->assertCreated();

        $responseData = $response->json('data');
        $this->assertCount(2, $responseData['job_files']);
        $this->assertEquals($responseData['job_files'][0]['id'], $unchangedMedia->id);
        $this->assertEquals($responseData['job_files'][1]['file_name'], $newFile->file_name);

        $this->user = $this->user->fresh();
        $this->assertCount(2, Storage::disk('public')->allFiles());
        $this->assertFalse($this->user->getMedia('job')->pluck('id')->contains($deletedMedia->id));
        Storage::disk('public')->assertMissing($deletedMedia->id.'/'.$deletedMedia->file_name);
        Storage::disk('public')->assertExists($unchangedMedia->id.'/'.$unchangedMedia->file_name);
        $this->assertCount(2, $this->user->getMedia('job'));
    }
}
