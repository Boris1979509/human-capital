<?php

namespace Tests\Feature\Resume;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResumeIndexTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;
    private string $method = "get";

    protected function setUp(): void
    {
        parent::setUp();
        $this->endpoint = route('user.resume');
    }

    /** @test */
    public function it_generates_and_fetches_resume(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)->json($this->method, $this->endpoint);
        $response->assertOk();
        $response->assertHeader('content-disposition', 'inline; filename="resume.pdf"');
        $response->assertHeader('content-type', 'application/pdf');
    }
}
