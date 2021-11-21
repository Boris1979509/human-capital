<?php

namespace Tests\Feature\Ratings;

use App\Models\Institution\Institution;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RatingCalculationTest extends TestCase
{
    use RefreshDatabase;

    private Institution $institution;

    protected function setUp(): void
    {
        parent::setUp();
        $this->institution = $this->createInstitution();
    }

    /** @test */
    public function it_divides_rating_by_type(): void
    {
        $this->rateEntity($this->institution, 4, 22, User::TYPE_USER_PERSONAL);
        $this->rateEntity($this->institution, 3, 22, User::TYPE_USER_EMPLOYER);
        $this->rateEntity($this->institution, 2, 22, User::TYPE_USER_INSTITUTION);

        $response = $this->json('get', route('institutions.public.show', [$this->institution]));
        $response->assertOk();
        $fetchedRating = $response->json('data.rating');
        $this->assertCount(3, $fetchedRating);
        $this->assertEquals(4, $fetchedRating['personal']);
        $this->assertEquals(3, $fetchedRating['employer']);
        $this->assertNull($fetchedRating['institution']);
    }

    /** @test */
    public function it_sets_rating_as_5_if_there_is_less_than_20_ratings(): void
    {
        $this->rateEntity($this->institution, 1, 19);

        $response = $this->json('get', route('institutions.public.show', [$this->institution]));
        $response->assertOk();
        $rating = $response->json('data.rating.personal');
        $this->assertEquals(5, $rating);
    }

    /** @test */
    public function it_sends_users_rating_as_well_as_total(): void
    {
        $user = $this->createUser();
        $this->rateEntity($this->institution, 3, 21);
        $rating = 4;
        $this->institution->ratings()->create([
            'user_id' => $user->id,
            'rating' => $rating,
            'type' => $user->type
        ]);
        $response = $this->actingAs($user)->json('get', route('institutions.public.show', [$this->institution]));
        $response->assertOk();
        $this->assertEquals($rating, $response->json('data.rating_user'));
        $this->assertEquals(3, $response->json('data.rating.personal'));
    }

    private function rateEntity(
        Institution $institution,
        int $ratingValue,
        int $times,
        int $userType = User::TYPE_USER_PERSONAL
    ): void {
        $ratings = [];
        for ($i = 0; $i < $times; $i++) {
            $user = $this->createUser(['type' => $userType]);
            $ratings[] = [
                'user_id' => $user->id,
                'type' => $user->type,
                'rating' => $ratingValue
            ];
        }
        $institution->ratings()->createMany($ratings);
    }
}
