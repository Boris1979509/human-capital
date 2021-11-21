<?php

namespace Tests\Feature\Files;

use App\Models\TemporaryUpload;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Storage;
use Tests\TestCase;

class TemporaryUploadCreateTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;
    private string $method = "post";

    protected function setUp(): void
    {
        parent::setUp();
        $this->endpoint = route('uploads.store');
    }

    /** @test */
    public function it_creates_and_stores_new_file(): void
    {
        Storage::fake('public');
        $fileName = 'file.jpg';
        $file = UploadedFile::fake()->image($fileName);

        $this->assertDatabaseCount('media', 0);
        $response = $this->json($this->method, $this->endpoint, ['files' => [$file]]);
        $response->assertCreated();
        $this->assertDatabaseCount('media', 1);

        $this->assertArrayHasKey('id', $response->json('0'));
        $this->assertArrayHasKey('url', $response->json('0'));

        $createdTempUpload = TemporaryUpload::first();
        $createdMedia = $createdTempUpload->getMedia()->first();
        $this->assertNotNull($createdMedia);
        $this->assertEquals($fileName, $createdMedia->custom_properties['original_file_name']);

        $filePath = $createdMedia->file_name;
        Storage::disk('public')->assertExists($createdMedia->id.'/'.$filePath);
    }

    /** @test */
    public function it_creates_and_stores_multiple_new_files(): void
    {
        Storage::fake('public');
        $file1 = UploadedFile::fake()->image('file1.jpg');
        $file2 = UploadedFile::fake()->image('file2.jpg');

        $this->assertDatabaseCount('media', 0);
        $response = $this->json($this->method, $this->endpoint, ['files' => [$file1, $file2]]);
        $response->assertCreated();
        $this->assertDatabaseCount('media', 2);

        $data = $response->json();
        foreach ($data as $file) {
            $this->assertArrayHasKey('id', $file);
            $this->assertArrayHasKey('url', $file);
        }


        TemporaryUpload::all()->each(function (TemporaryUpload $createdTempUpload) {
            $createdMedia = $createdTempUpload->getMedia()->first();
            $this->assertNotNull($createdMedia);
            $filePath = $createdMedia->file_name;
            Storage::disk('public')->assertExists($createdMedia->id.'/'.$filePath);
        });
    }
}
