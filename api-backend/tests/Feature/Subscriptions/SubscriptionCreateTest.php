<?php

namespace Tests\Feature\Subscriptions;

use App\Models\Employer\Employer;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubscriptionCreateTest extends TestCase
{
    use RefreshDatabase;

    private Employer $employer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->employer = $this->createEmployer();
    }

    /** @test */
    public function only_authorized_user_can_subscribe_to_employer(): void
    {
        $this->json('post', route('subscriptions.create', ['employer', $this->employer->id]))
            ->assertUnauthorized();
    }

    /** @test */
    public function user_can_subscribe_to_employer(): void
    {
        $this->withoutExceptionHandling();
        $user = $this->createUser();
        $subscriptionType = Subscription::TYPE_VACANCIES;

        $this->assertEquals(0, $this->employer->subscriptions()->count());

        $response = $this->actingAs($user)->json(
            'post',
            route('subscriptions.create', ['employer', $this->employer->id]),
            ['type' => $subscriptionType]
        );
        $response->assertCreated();

        $this->assertEquals(1, $this->employer->subscriptions()->count());
        $this->assertDatabaseHas('subscriptions', [
            'subscribable_type' => 'employer',
            'subscribable_id' => $this->employer->id,
            'type' => $subscriptionType,
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function create_subscription_endpoint_is_idempotent(): void
    {
        $user = $this->createUser();

        $this->employer->subscriptions()->create(['user_id' => $user->id]);

        $this->assertEquals(1, $this->employer->subscriptions()->count());

        $response = $this->actingAs($user)->json(
            'post',
            route('subscriptions.create', ['employer', $this->employer->id]),
        );
        $response->assertCreated();

        $this->assertEquals(1, $this->employer->subscriptions()->count());
    }

    /** @test */
    public function it_responds_with_404_when_unknown_subscribable_id_or_subscribable_type_is_provided(): void
    {
        $user = $this->createUser();
        $this->actingAs($user)
            ->json('post', route('subscriptions.create', ['unknown', $this->employer->id]))
            ->assertNotFound();

        $unknownEmployerId = 999;
        $this->assertDatabaseMissing('employers', ['id' => $unknownEmployerId]);
        $this->actingAs($user)
            ->json('post', route('subscriptions.create', ['employer', $unknownEmployerId]))
            ->assertNotFound();
    }

    /** @test */
    public function it_sends_data_about_subscriptions_in_employer_detailed_resource(): void
    {
        $user = $this->createUser();

        $this->employer->subscriptions()->create(['user_id' => $user->id, 'type' => Subscription::TYPE_VACANCIES]);

        $response = $this->json('get', route('employer.public.show', [$this->employer->id]));
        $response->assertOk();

        $this->assertArrayNotHasKey('subscriptions', $response->json('data'));

        $response = $this->actingAs($user)->json('get', route('employer.public.show', [$this->employer->id]));
        $response->assertOk();

        $this->assertTrue($response->json('data.subscriptions.vacancies'));
        $this->assertFalse($response->json('data.subscriptions.journal'));
    }
}
