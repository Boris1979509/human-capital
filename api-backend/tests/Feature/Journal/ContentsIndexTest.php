<?php

namespace Tests\Feature\Journal;

use App\Http\Resources\Journal\ContentResource;
use App\Models\Journal\Content;
use App\Models\Journal\ContentType;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class ContentsIndexTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;
    private string $method = "get";

    protected function setUp(): void
    {
        parent::setUp();
        $this->endpoint = route('contents.index');
        Content::truncate();
    }

    /** @test */
    public function it_fetches_all_content(): void
    {
        $contents = [
            $this->createNewsArticle(),
            $this->createArticle(),
            $this->createEvent()
        ];

        $this->assertDatabaseCount('contents', 3);

        $response = $this->json($this->method, $this->endpoint);
        $response->assertOk();
        $this->assertCount(3, $response->json('data'));
        $this->assertEquals(
            ContentResource::collection($contents)->response()->getData(true)['data'],
            $response->json('data')
        );
    }

    /** @test */
    public function it_fetches_only_published_content(): void
    {
        $institution = $this->createInstitution();
        $institutionManager = $this->createManagerOfInstitution($institution);

        $draft = $this->createArticle([
            'is_published' => false,
            'institution_id' => $institution->id
        ]);
        $draftOfAnotherInstitution = $this->createArticle([
            'is_published' => false,
            'institution_id' => $this->createInstitution()->id
        ]);
        $published = $this->createArticle([
            'is_published' => true,
            'institution_id' => $institution->id
        ]);

        $this->assertDatabaseCount('contents', 3);

        //as regular user
        $response = $this->json($this->method, $this->endpoint);
        $response->assertOk();
        $fetchedData = $response->json('data');
        $this->assertCount(1, $fetchedData);
        $this->assertEquals($published->id, $fetchedData[0]['id']);

        $response = $this->json($this->method, $this->endpoint, ['with_drafts' => true]);
        $response->assertOk();
        $fetchedData = $response->json('data');
        $this->assertCount(1, $fetchedData);
        $this->assertEquals($published->id, $fetchedData[0]['id']);

        //as manager
        $response = $this->actingAs($institutionManager)->json($this->method, $this->endpoint);
        $response->assertOk();
        $fetchedData = $response->json('data');
        $this->assertCount(1, $fetchedData);
        $this->assertEquals($published->id, $fetchedData[0]['id']);
    }

    /** @test */
    public function it_filters_contents_by_institution(): void
    {
        $mgu = $this->createInstitution();
        $mguArticle = $this->createArticle([
            'institution_id' => $mgu->id
        ]);
        $spgu = $this->createInstitution();
        $spguArticle = $this->createArticle([
            'institution_id' => $spgu->id
        ]);

        $this->assertDatabaseCount('contents', 2);

        $response = $this->json($this->method, $this->endpoint, ['institution_id' => $mgu->id]);
        $response->assertOk();
        $fetchedData = $response->json('data');
        $this->assertCount(1, $fetchedData);
        $this->assertEquals($mguArticle->id, $fetchedData[0]['id']);
    }

    /** @test */
    public function it_filters_contents_by_employer_id(): void
    {
        $google = $this->createEmployer();
        $googleNews = $this->createNewsArticle([
            'user_id' => $google->id
        ]);

        $yandex = $this->createEmployer();
        $yandexArticle = $this->createNewsArticle([
            'user_id' => $yandex->id
        ]);

        $this->assertDatabaseCount('contents', 2);

        $response = $this->json($this->method, $this->endpoint, ['employer_id' => $google->id]);
        $response->assertOk();
        $fetchedData = $response->json('data');
        $this->assertCount(1, $fetchedData);
        $this->assertEquals($googleNews->id, $fetchedData[0]['id']);
    }

    /** @test */
    public function it_filters_contents_by_employer_author(): void
    {
        $google = $this->createEmployer();
        $googleNews = $this->createNewsArticle([
            'user_id' => $google->user->id
        ]);

        $yandex = $this->createEmployer();
        $yandexArticle = $this->createNewsArticle([
            'user_id' => $yandex->user->id
        ]);

        $institutionArticle = $this->createArticle();

        $this->assertDatabaseCount('contents', 3);
        $response = $this->json($this->method, $this->endpoint, ['from_employers' => true]);
        $response->assertOk();
        $fetchedData = $response->json('data');
        $this->assertCount(2, $fetchedData);
        $this->assertEquals($googleNews->id, $fetchedData[0]['id']);
        $this->assertEquals($yandexArticle->id, $fetchedData[1]['id']);

        $response = $this->json($this->method, $this->endpoint, ['from_employers' => false]);
        $response->assertOk();
        $fetchedData = $response->json('data');
        $this->assertCount(1, $fetchedData);
        $this->assertEquals($institutionArticle->id, $fetchedData[0]['id']);
    }

    /** @test */
    public function it_filters_contents_by_type(): void
    {
        $news = $this->createNewsArticle();
        $article = $this->createArticle();
        $event = $this->createEvent();

        $response = $this->json($this->method, $this->endpoint, ['type' => ContentType::EVENT]);
        $response->assertOk();
        $fetchedData = $response->json('data');
        $this->assertCount(1, $fetchedData);
        $this->assertEquals($event->id, $fetchedData[0]['id']);

        $response = $this->json($this->method, $this->endpoint, ['type' => ContentType::NEWS]);
        $response->assertOk();
        $fetchedData = $response->json('data');
        $this->assertCount(1, $fetchedData);
        $this->assertEquals($news->id, $fetchedData[0]['id']);

        $response = $this->json($this->method, $this->endpoint, ['type' => ContentType::ARTICLE]);
        $response->assertOk();
        $fetchedData = $response->json('data');
        $this->assertCount(1, $fetchedData);
        $this->assertEquals($article->id, $fetchedData[0]['id']);


        $this->json($this->method, $this->endpoint, ['type' => 1000])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors('type');
    }

    /** @test */
    public function it_filters_contents_by_filter_abstraction(): void
    {
        $forChildrenTargetAudienceIds = config('app.content_audience.for_children');

        $forChildren1 = $this->createDictionary($forChildrenTargetAudienceIds[0]);
        $forChildren2 = $this->createDictionary($forChildrenTargetAudienceIds[1]);

        $notForChildren = $this->createDictionary();

        $eventBoth = $this->createEvent();
        $eventBoth->targetAudience()->attach($forChildren1->id);
        $eventBoth->targetAudience()->attach($notForChildren->id);

        $eventForChildren = $this->createEvent();
        $eventForChildren->targetAudience()->attach($forChildren1->id);
        $eventForChildren->targetAudience()->attach($forChildren2->id);

        $eventNotForChildren = $this->createEvent();
        $eventNotForChildren->targetAudience()->attach($notForChildren->id);

        $this->assertDatabaseCount('contents', 3);

        $response = $this->json($this->method, $this->endpoint, ['filter' => 'for_children']);
        $response->assertOk();
        $fetchedData = $response->json('data');
        $this->assertCount(2, $fetchedData);
        $this->assertEquals($eventBoth->id, $fetchedData[0]['id']);
        $this->assertEquals($eventForChildren->id, $fetchedData[1]['id']);
    }

    /** @test */
    public function it_orders_journal_by_published_at_field(): void
    {
        $second = $this->createArticle();
        $first = $this->createArticle();
        $second->update(['published_at' => Carbon::yesterday()]);
        $first->update(['published_at' => Carbon::tomorrow()]);

        $this->assertDatabaseCount('contents', 2);

        $response = $this->json($this->method, $this->endpoint, ['order_by' => '-published_at']);
        $response->assertOk();

        $fetchedData = $response->json('data');
        $this->assertCount(2, $fetchedData);
        $this->assertEquals($first->id, $fetchedData[0]['id']);
        $this->assertEquals($second->id, $fetchedData[1]['id']);
    }

    /** @test */
    public function it_orders_journal_by_date_start_field(): void
    {
        $second = $this->createEvent(['date_start' => Carbon::tomorrow()->addHour()]);
        $first = $this->createEvent(['date_start' => Carbon::tomorrow()]);

        $this->assertDatabaseCount('contents', 2);

        $response = $this->json($this->method, $this->endpoint, ['order_by' => 'date_start']);
        $response->assertOk();

        $fetchedData = $response->json('data');
        $this->assertCount(2, $fetchedData);
        $this->assertEquals($first->id, $fetchedData[0]['id']);
        $this->assertEquals($second->id, $fetchedData[1]['id']);
    }

    /** @test */
    public function it_limits_journal(): void
    {
        $this->createArticle();
        $this->createArticle();

        $this->assertDatabaseCount('contents', 2);

        $response = $this->json($this->method, $this->endpoint, ['limit' => 1]);
        $response->assertOk();

        $this->assertCount(1, $response->json('data'));
    }

    /** @test */
    public function it_paginates_content(): void
    {
        $firstPageItem = $this->createArticle();
        $secondPageItem = $this->createArticle();
        $thirdPageItem = $this->createArticle();

        $this->assertDatabaseCount('contents', 3);

        $response = $this->json($this->method, $this->endpoint, ['per_page' => 1, 'page' => 1]);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($firstPageItem->id, $response->json('data.0.id'));

        $response = $this->json($this->method, $this->endpoint, ['per_page' => 1, 'page' => 2]);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($secondPageItem->id, $response->json('data.0.id'));

        $response = $this->json($this->method, $this->endpoint, ['per_page' => 1, 'page' => 3]);
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($thirdPageItem->id, $response->json('data.0.id'));
    }

    /** @test */
    public function it_doesnt_fetch_finished_events(): void
    {
        $this->createArticle();
        $this->createNewsArticle();
        $this->createEvent(['date_end' => Carbon::yesterday()]);

        $this->assertDatabaseCount('contents', 3);

        $response = $this->json($this->method, $this->endpoint);
        $response->assertOk();

        $this->assertCount(2, $response->json('data'));

        $response = $this->json($this->method, $this->endpoint, ['type' => ContentType::EVENT]);
        $response->assertOk();

        $this->assertCount(0, $response->json('data'));

        $response = $this->json($this->method, $this->endpoint, ['type' => ContentType::ARTICLE]);
        $response->assertOk();

        $this->assertCount(1, $response->json('data'));

        $response = $this->json($this->method, $this->endpoint, ['type' => ContentType::NEWS]);
        $response->assertOk();

        $this->assertCount(1, $response->json('data'));
    }

    /** @test */
    public function it_filters_contents_by_favorite(): void
    {
        $this->withoutExceptionHandling();
        $user = $this->createUser();
        $favoritedContent = $this->createNewsArticle();
        $user->addFavorite($favoritedContent);

        $notFavoritedContent = $this->createEvent();
        $this->createUser()->addFavorite($notFavoritedContent);

        $this->assertDatabaseCount('contents', 2);

        $response = $this->json($this->method, $this->endpoint, ['favorited' => true]);
        $response->assertOk();
        $this->assertCount(2, $response->json('data'));

        $response = $this->actingAs($user)->json($this->method, $this->endpoint, ['favorited' => true]);
        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
        $this->assertEquals($favoritedContent->id, $response->json('data.0.id'));
    }
}
