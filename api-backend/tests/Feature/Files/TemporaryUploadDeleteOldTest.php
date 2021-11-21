<?php

namespace Tests\Feature\Files;

use App\Models\TemporaryUpload;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Storage;
use Tests\TestCase;

class TemporaryUploadDeleteOldTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_deletes_old_unattached_files(): void
    {
        Storage::fake('public');
        $recentMedia = $this->createTemporaryUpload();
        $oldMedia = $this->createTemporaryUpload(['created_at' => Carbon::now()->subDays(2)]);

        $this->assertCount(2, Storage::disk('public')->allFiles());
        $this->assertDatabaseCount('media', 2);

        TemporaryUpload::deleteOldImages();

        $this->assertDatabaseCount('media', 1);
        $this->assertDatabaseMissing('media', ['id' => $oldMedia->id]);
        $this->assertDatabaseHas('media', ['id' => $recentMedia->id]);
        $this->assertCount(1, Storage::disk('public')->allFiles());
    }
}
