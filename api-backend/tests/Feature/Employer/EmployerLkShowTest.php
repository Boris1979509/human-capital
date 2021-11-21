<?php

namespace Tests\Feature\Employer;

use App\Http\Resources\Employer\EmployerResource;
use App\Models\Employer\Employer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployerLkShowTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;
    private string $method = "get";
    private Employer $employer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->employer = $this->createEmployer();
        $this->endpoint = route('employer.management.show');
    }

    /** @test */
    public function it_fetches_employer_for_user(): void
    {
        $this->actingAs($this->employer->user);
        $response = $this->json($this->method, $this->endpoint);
        $response->assertOk();

        $this->assertEquals(
            (new EmployerResource($this->employer->fresh()))->response()->getData(true),
            $response->json()
        );
    }

    /** @test */
    public function it_doesnt_fetch_employer_for_regular_user(): void
    {
        $user = $this->createUser();
        $this->actingAs($user);
        $response = $this->json($this->method, $this->endpoint);
        $response->assertOk();
        $this->assertEmpty($response->content());
    }
}
