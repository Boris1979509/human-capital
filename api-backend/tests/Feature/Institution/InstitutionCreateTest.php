<?php

namespace Tests\Feature\Institution;

use App\Http\Resources\Institution\CurriculumResource;
use App\Models\Institution\Institution;
use App\Models\Institution\InstitutionCurriculum;
use App\Models\InstitutionRoles;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class InstitutionCreateTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;
    private string $method = "post";
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->endpoint = route('institutions.institutions.store');
        $this->user = $this->createUser();
    }

    /** @test */
    public function when_user_creates_institution_he_becomes_its_owner(): void
    {
        $this->actingAs($this->user);

        $this->assertDatabaseCount('users_institution_roles', 0);

        $response = $this->json($this->method, $this->endpoint, Institution::factory()->make()->toArray());
        $response->assertCreated();

        $createdInstitutionId = $response->json('data.id');

        $this->assertDatabaseCount('users_institution_roles', 1);
        $this->assertDatabaseHas('users_institution_roles', [
            'user_id' => $this->user->id,
            'institution_id' => $createdInstitutionId,
            'role' => InstitutionRoles::OWNER
        ]);
    }
}
