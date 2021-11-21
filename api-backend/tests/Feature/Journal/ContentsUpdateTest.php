<?php

namespace Tests\Feature\Journal;

use App\Models\Journal\Content;
use App\Models\Journal\ContentType;
use App\Models\Journal\EventSpeaker;
use App\Models\User;
use Arr;
use DateTimeInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Queue;
use Storage;
use Tests\TestCase;

class ContentsUpdateTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;
    private string $method = "put";
    private User $institutionManager;
    private Content $content;

    protected function setUp(): void
    {
        parent::setUp();
        $institution = $this->createInstitution();
        $this->institutionManager = $this->createManagerOfInstitution($institution);
        $this->content = Content::factory()->event()->create([
            'institution_id' => $institution->id,
            'type' => ContentType::EVENT,
        ]);
        $this->endpoint = route('contents.update', [$this->content->id]);
    }

    /** @test */
    public function it_updates_content(): void
    {
        $this->actingAs($this->institutionManager);
        $this->assertDatabaseHas('contents', Arr::except($this->content->toArray(), ['tags', 'coords']));
        $contentData = Content::factory()->make()->toArray();
        unset($contentData['institution_id'], $contentData['slug'], $contentData['user_id']);
        $response = $this->json($this->method, $this->endpoint, $contentData);
        $response->assertOk();
        $this->assertDatabaseMissing('contents', Arr::except($this->content->toArray(), 'tags'));
        $this->assertDatabaseHas('contents', array_merge(['id' => $this->content->id], $contentData));
    }

    /** @test */
    public function it_updates_speakers_info(): void
    {
        Queue::fake();
        Storage::fake('public');
        $this->actingAs($this->institutionManager);

        $deletedSpeaker = $this->createSpeakerForEvent($this->content)->toArray();

        $updatedSpeaker = $this->createSpeakerForEvent($this->content);
        $updatedSpeakerData = EventSpeaker::factory()->make()->toArray();
        unset($updatedSpeakerData['content_id']);
        $updatedSpeakerData['id'] = $updatedSpeaker['id'];
        $updatedSpeaker->addMedia(UploadedFile::fake()->image('unchanged.jpg'))->toMediaCollection('avatar');
        $updatedSpeakerData['avatar'] = $updatedSpeaker->getFirstMedia('avatar')->id;
        $addedSpeaker = EventSpeaker::factory()->make()->toArray();
        unset($addedSpeaker['content_id']);

        $speakerWithDeletedPhoto = $this->createSpeakerForEvent($this->content);
        $deletedSpeakerAvatarFile = UploadedFile::fake()->image('deleted.jpg');
        $speakerWithDeletedPhoto->addMedia($deletedSpeakerAvatarFile)->toMediaCollection('avatar');
        $deletedSpeakerAvatar = $speakerWithDeletedPhoto->getFirstMedia('avatar');
        $this->assertNotNull($deletedSpeakerAvatar);
        Storage::disk('public')->assertExists($deletedSpeakerAvatar->id.'/'.$deletedSpeakerAvatar->file_name);

        $speakerWithUpdatedPhoto = $this->createSpeakerForEvent($this->content);
        $updatedSpeakerAvatarFile = UploadedFile::fake()->image('old.jpg');
        $speakerWithUpdatedPhoto->addMedia($updatedSpeakerAvatarFile)->toMediaCollection('avatar');
        $updatedSpeakerAvatar = $speakerWithUpdatedPhoto->getFirstMedia('avatar');
        $this->assertNotNull($updatedSpeakerAvatar);
        Storage::disk('public')->assertExists($updatedSpeakerAvatar->id.'/'.$updatedSpeakerAvatar->file_name);

        $this->assertCount(3, Storage::disk('public')->allFiles());

        $newSpeakerAvatarFile = $this->createTemporaryUpload();
        $speakerWithUpdatedPhotoData = $speakerWithUpdatedPhoto->getAttributes();
        $speakerWithUpdatedPhotoData['avatar'] = $newSpeakerAvatarFile->id;

        $contentData = $this->content->toArray();
        $contentData['speakers'] = [
            $updatedSpeakerData,
            $addedSpeaker,
            $speakerWithUpdatedPhotoData,
            $speakerWithDeletedPhoto->getAttributes()
        ];
        $contentData['tags'] = [1];
        $contentData['participants_age'] = [$this->createDictionary()->id];
        $contentData['target_audience'] = [$this->createDictionary()->id];

        $this->assertDatabaseCount('event_speakers', 4);
        $this->assertDatabaseHas('event_speakers', $deletedSpeaker);
        $this->assertDatabaseHas('event_speakers', $updatedSpeaker->getAttributes());
        $this->assertDatabaseHas('event_speakers', ['id' => $speakerWithUpdatedPhoto->id]);
        $this->assertDatabaseHas('event_speakers', $speakerWithDeletedPhoto->getAttributes());

        $response = $this->json($this->method, $this->endpoint, $contentData);
        $response->assertOk();

        $this->assertEquals(4, $this->content->speakers()->count());
        $this->assertDatabaseCount('event_speakers', 4);
        $this->assertDatabaseMissing('event_speakers', $deletedSpeaker);
        $this->assertDatabaseMissing('event_speakers', $updatedSpeaker->getAttributes());
        unset($updatedSpeakerData['avatar']);
        $this->assertDatabaseHas('event_speakers', $updatedSpeakerData);

        $this->assertNull($speakerWithDeletedPhoto->fresh()->getFirstMedia('avatar'));
        $this->assertNotNull($speakerWithUpdatedPhoto->fresh()->getFirstMedia('avatar'));
        Storage::disk('public')->assertMissing($deletedSpeakerAvatar->id.'/'.$deletedSpeakerAvatar->file_name);
        Storage::disk('public')->assertMissing($updatedSpeakerAvatar->id.'/'.$updatedSpeakerAvatar->file_name);

        $unchangedAvatar = $updatedSpeaker->fresh()->getFirstMedia('avatar');
        $this->assertNotNull($unchangedAvatar);
        Storage::disk('public')->assertExists($unchangedAvatar->id.'/'.$unchangedAvatar->file_name);
        $updatedAvatar = $speakerWithUpdatedPhoto->fresh()->getFirstMedia('avatar');
        Storage::disk('public')->assertExists($updatedAvatar->id.'/'.$updatedAvatar->file_name);
        $this->assertCount(2, Storage::disk('public')->allFiles());
    }

    /** @test */
    public function only_manager_of_institution_can_update_institution_content(): void
    {
        $anotherInstitutionManager = $this->createManagerOfInstitution($this->createInstitution());

        $contentData = Content::factory()->make()->toArray();

        $this->json($this->method, $this->endpoint, $contentData)->assertForbidden();

        $this->actingAs($this->createUser());
        $this->json($this->method, $this->endpoint, $contentData)->assertForbidden();

        $this->actingAs($anotherInstitutionManager);
        $this->json($this->method, $this->endpoint, $contentData)->assertForbidden();

        $this->actingAs($this->institutionManager);
        $this->json($this->method, $this->endpoint, $contentData)->assertOk();
    }

    /** @test */
    public function only_employer_can_update_his_content(): void
    {
        $anotherEmployer = $this->createEmployer();
        $employer = $this->createEmployer();

        $this->content = $this->createNewsArticle(['user_id' => $employer->user->id]);
        $this->endpoint = route('contents.update', [$this->content->id]);

        $contentData = Content::factory()->make()->toArray();

        $this->json($this->method, $this->endpoint, $contentData)->assertForbidden();

        $this->actingAs($this->createUser());
        $this->json($this->method, $this->endpoint, $contentData)->assertForbidden();

        $this->actingAs($anotherEmployer->user);
        $this->json($this->method, $this->endpoint, $contentData)->assertForbidden();

        $this->actingAs($employer->user);
        $this->json($this->method, $this->endpoint, $contentData)->assertOk();
    }

    /** @test */
    public function it_updates_images_of_content(): void
    {
        Queue::fake();
        Storage::fake('public');

        $this->actingAs($this->institutionManager);

        $deletedImage = UploadedFile::fake()->image('image_to_delete.jpg');
        $this->content->addMedia($deletedImage)
            ->usingFileName($deletedImage->hashName())
            ->toMediaCollection('images');
        $deletedMedia = $this->content->getFirstMedia('images');
        $this->assertNotNull($deletedMedia);
        Storage::disk('public')->assertExists($deletedMedia->id.'/'.$deletedMedia->file_name);

        $unchangedImage = UploadedFile::fake()->image('image_unchanged.jpg');
        $this->content->addMedia($unchangedImage)
            ->usingFileName($unchangedImage->hashName())
            ->toMediaCollection('images');
        $unchangedMedia = $this->content->fresh()->getMedia('images')[1];
        $this->assertNotNull($unchangedMedia);
        Storage::disk('public')->assertExists($unchangedMedia->id.'/'.$unchangedMedia->file_name);

        $this->assertCount(2, Storage::disk('public')->allFiles());

        $contentData = Content::factory()->make()->toArray();
        unset($contentData['institution_id'], $contentData['slug']);

        $newMedia = $this->createTemporaryUpload();
        $contentData['images'] = [
            $newMedia->id,
            $unchangedMedia->id
        ];

        $response = $this->json($this->method, $this->endpoint, $contentData);
        $response->assertOk();

        $newMedia = $this->content->fresh()->getMedia('images')[1];
        Storage::disk('public')->assertMissing($deletedMedia->id.'/'.$deletedMedia->file_name);
        Storage::disk('public')->assertExists($newMedia->id.'/'.$newMedia->file_name);
        Storage::disk('public')->assertExists($unchangedMedia->id.'/'.$unchangedMedia->file_name);
        $this->assertCount(2, Storage::disk('public')->allFiles());
    }

    /** @test */
    public function it_sets_published_at_date(): void
    {
        $this->content->update(['published_at' => null, 'is_published' => false]);
        $this->content = $this->content->fresh();

        $this->assertNull($this->content->published_at);

        $this->actingAs($this->institutionManager);
        $response = $this->json($this->method, $this->endpoint, array_merge([
            'is_published' => true,
            'title' => $this->content->title,
            'text' => $this->content->text,
            'type' => $this->content->type,
            'tags' => ['1', '2']
        ], Content::factory()->event()->withSpeakers()->withEventData()->make()->toArray()));
        $response->assertOk();

        $this->assertNotNull($this->content->fresh()->published_at);
    }

    /** @test */
    public function it_doesnt_update_published_at_when_content_is_already_published(): void
    {
        $publishedAt = now()->format(DateTimeInterface::RFC3339);
        $this->content->update(['published_at' => $publishedAt, 'is_published' => true]);

        $this->actingAs($this->institutionManager);
        $response = $this->json($this->method, $this->endpoint, array_merge([
            'is_published' => true,
            'title' => $this->content->title,
            'text' => $this->content->text,
            'type' => $this->content->type,
            'tags' => ['1', '2']
        ], Content::factory()->event()->withSpeakers()->withEventData()->make()->toArray()));
        $response->assertOk();

        $this->assertEquals($publishedAt, $this->content->fresh()->published_at->format(DateTimeInterface::RFC3339));
    }
}
