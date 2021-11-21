<?php

namespace Tests\Feature\Employer;

use App\Http\Resources\Employer\EmployerDetailedPublicResource;
use App\Models\Employer\Employer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployerShowTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;
    private string $method = "get";
    private string $route = 'employer.public.show';
    private Employer $employer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->employer = $this->createEmployer();
        $this->endpoint = route('employer.public.show', [$this->employer]);
    }

    /** @test */
    public function it_fetches_employer(): void
    {
        $response = $this->json($this->method, $this->endpoint);
        $response->assertOk();

        $this->assertEquals(
            (new EmployerDetailedPublicResource($this->employer->fresh()))->response()->getData(true),
            $response->json()
        );
    }
}
