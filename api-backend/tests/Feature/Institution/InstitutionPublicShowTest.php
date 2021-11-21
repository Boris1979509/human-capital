<?php

namespace Tests\Feature\Institution;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstitutionPublicShowTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;
    private string $method = "get";

    /** @test */
    public function it_fetches_institution(): void
    {
        $institution = $this->createInstitution();

        $response = $this->json($this->method, route('institutions.public.show', [$institution->id]));
        $response->assertOk();

        $fetchedInstitution = $response->json('data');

        $this->assertEquals($institution->id, $fetchedInstitution['id']);
        $this->assertEquals($institution->full_name, $fetchedInstitution['full_name']);
    }

    /** @test */
    public function it_responds_with_404_when_provided_unknown_institution_id(): void
    {
        $unknownInstitutionId = 999;
        $this->assertDatabaseMissing('institutions', ['id' => $unknownInstitutionId]);
        $this->json($this->method, route('institutions.public.show', [$unknownInstitutionId]))->assertNotFound();
    }
}
