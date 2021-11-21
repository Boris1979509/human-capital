<?php

namespace Tests\Feature\Subscriptions;

use App\Models\Employer\Employer;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionDeleteTest extends TestCase
{
    use RefreshDatabase;

    private Employer $employer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->employer = $this->createEmployer();
    }

    /** @test */
    public function only_authorized_user_can_cancel_subscription(): void
    {
        $this->json('delete', route('subscriptions.delete', ['employer', $this->employer->id]))
            ->assertUnauthorized();
    }

    /** @test */
    public function user_can_cancel_his_subscription(): void
    {
        $user = $this->createUser();

        $this->employer->subscriptions()->create(['user_id' => $user->id, 'type' => Subscription::TYPE_JOURNAL]);
        $this->employer->subscriptions()->create(['user_id' => $user->id, 'type' => Subscription::TYPE_VACANCIES]);

        $this->assertEquals(2, $this->employer->subscriptions()->count());

        $response = $this->actingAs($user)->json(
            'delete',
            route('subscriptions.delete', ['employer', $this->employer->id]),
            ['type' => Subscription::TYPE_VACANCIES]
        );
        $response->assertNoContent();

        $this->assertEquals(1, $this->employer->subscriptions()->count());
        $this->assertDatabaseHas('subscriptions', [
            'subscribable_type' => 'employer',
            'subscribable_id' => $this->employer->id,
            'type' => Subscription::TYPE_JOURNAL,
            'user_id' => $user->id
        ]);
        $this->assertDatabaseMissing('subscriptions', [
            'subscribable_type' => 'employer',
            'subscribable_id' => $this->employer->id,
            'type' => Subscription::TYPE_VACANCIES,
            'user_id' => $user->id
        ]);
    }

    /** @test */
    public function delete_subscription_endpoint_is_idempotent(): void
    {
        $user = $this->createUser();

        $this->assertEquals(0, $this->employer->subscriptions()->count());

        $response = $this->actingAs($user)->json(
            'delete',
            route('subscriptions.delete', ['employer', $this->employer->id]),
        );
        $response->assertNoContent();

        $this->assertEquals(0, $this->employer->subscriptions()->count());
    }

    /** @test */
    public function it_responds_with_404_when_unknown_subscribable_id_or_subscribable_type_is_provided(): void
    {
        $user = $this->createUser();
        $this->actingAs($user)
            ->json('delete', route('subscriptions.delete', ['unknown', $this->employer->id]))
            ->assertNotFound();

        $unknownEmployerId = 999;
        $this->assertDatabaseMissing('comments', ['id' => $unknownEmployerId]);
        $this->actingAs($user)
            ->json('delete', route('subscriptions.delete', ['employer', $unknownEmployerId]))
            ->assertNotFound();
    }
}
