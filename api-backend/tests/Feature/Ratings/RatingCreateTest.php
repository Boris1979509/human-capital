<?php

namespace Tests\Feature\Ratings;

use App\Models\Institution\Institution;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RatingCreateTest extends TestCase
{
    use RefreshDatabase;

    private Institution $institution;

    protected function setUp(): void
    {
        parent::setUp();
        $this->institution = $this->createInstitution();
    }

    /** @test */
    public function only_authorized_user_can_rate(): void
    {
        $this->json('post', route('ratings.create', ['institution', $this->institution->id]))
            ->assertUnauthorized();
    }

    /** @test */
    public function user_can_rate_institution(): void
    {
        $user = $this->createUser();

        $this->assertEquals(0, $this->institution->ratings()->count());

        $rating = 4;

        $response = $this->actingAs($user)->json(
            'post',
            route('ratings.create', ['institution', $this->institution->id]),
            ['rating' => $rating]
        );
        $response->assertCreated();

        $this->assertEquals(1, $this->institution->ratings()->count());

        $this->assertDatabaseHas('ratings', [
            'rateable_type' => 'institution',
            'rateable_id' => $this->institution->id,
            'user_id' => $user->id,
            'type' => $user->type,
            'rating' => $rating
        ]);
    }

    /** @test */
    public function user_can_rate_employer(): void
    {
        $user = $this->createUser();
        $employer = $this->createEmployer();

        $this->assertEquals(0, $employer->ratings()->count());

        $rating = 4;

        $response = $this->actingAs($user)->json(
            'post',
            route('ratings.create', ['employer', $employer->id]),
            ['rating' => $rating]
        );
        $response->assertCreated();

        $this->assertEquals(1, $employer->ratings()->count());

        $this->assertDatabaseHas('ratings', [
            'rateable_type' => 'employer',
            'rateable_id' => $employer->id,
            'user_id' => $user->id,
            'type' => $user->type,
            'rating' => $rating
        ]);
    }

    /** @test */
    public function create_rating_endpoint_is_idempotent(): void
    {
        $user = $this->createUser();

        $rating = 4;

        $this->institution->ratings()->create(['user_id' => $user->id, 'rating' => $rating, 'type' => $user->type]);

        $this->assertEquals(1, $this->institution->ratings()->count());

        $response = $this->actingAs($user)->json(
            'post',
            route('ratings.create', ['institution', $this->institution->id]),
            ['rating' => $rating]
        );
        $response->assertCreated();

        $this->assertEquals(1, $this->institution->ratings()->count());
    }

    /** @test */
    public function it_responds_with_404_when_unknown_rateable_id_or_rateable_type_is_provided(): void
    {
        $user = $this->createUser();
        $this->actingAs($user)
            ->json('post', route('ratings.create', ['unknown', $this->institution->id]), ['rating' => 4])
            ->assertNotFound();

        $unknownInstitutionId = 999;
        $this->assertDatabaseMissing('institutions', ['id' => $unknownInstitutionId]);
        $this->actingAs($user)
            ->json('post', route('ratings.create', ['institution', $unknownInstitutionId]), ['rating' => 4])
            ->assertNotFound();
    }

    /** @test */
    public function it_responds_with_422_when_user_provided_incorrect_rating(): void
    {
        $user = $this->createUser();
        $this->actingAs($user)
            ->json('post', route('ratings.create', ['institution', $this->institution->id]))
            ->assertJsonValidationErrors('rating');
        $this->actingAs($user)
            ->json('post', route('ratings.create', ['institution', $this->institution->id], ['rating' => 'good']))
            ->assertJsonValidationErrors('rating');
    }
}
