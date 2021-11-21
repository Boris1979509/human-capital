<?php

namespace Tests\Feature\Journal;

use App\Models\Journal\Content;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ContentsDeleteTest extends TestCase
{
    use RefreshDatabase;

    private string $routeName = 'contents.delete';
    private string $method = "delete";
    private User $institutionManager;
    private Content $content;

    protected function setUp(): void
    {
        parent::setUp();
        $institution = $this->createInstitution();
        $this->institutionManager = $this->createManagerOfInstitution($institution);
        $this->content = $this->createContent(['institution_id' => $institution->id]);
    }

    /** @test */
    public function it_deletes_content(): void
    {
        $this->actingAs($this->institutionManager);
        $this->assertDatabaseHas('contents', ['id' => $this->content->id]);
        $response = $this->deleteContent($this->content);
        $response->assertNoContent();
        $this->assertDatabaseMissing('contents', ['id' => $this->content->id]);
    }

    /** @test */
    public function only_manager_of_institution_can_delete_content(): void
    {
        $anotherInstitutionManager = $this->createManagerOfInstitution($this->createInstitution());

        $this->deleteContent($this->content)->assertForbidden();

        $this->actingAs($this->createUser());
        $this->deleteContent($this->content)->assertForbidden();

        $this->actingAs($anotherInstitutionManager);
        $this->deleteContent($this->content)->assertForbidden();

        $this->actingAs($this->institutionManager);
        $this->deleteContent($this->content)->assertNoContent();
    }

    /** @test */
    public function employer_can_delete_his_content(): void
    {
        $employer = $this->createEmployer();
        $content = $this->createNewsArticle(['user_id' => $employer->user->id]);

        $this->deleteContent($content)->assertForbidden();
        $this->actingAs($employer->user);
        $this->deleteContent($content)->assertNoContent();
        $this->assertDatabaseMissing('contents', ['id' => $content->id]);
    }

    private function deleteContent(Content $content): TestResponse
    {
        return $this->json($this->method, route($this->routeName, [$content->id]));
    }
}
