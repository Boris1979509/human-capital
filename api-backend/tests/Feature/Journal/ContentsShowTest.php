<?php

namespace Tests\Feature\Journal;

use App\Models\Journal\Content;
use App\Models\Journal\ContentType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ContentsShowTest extends TestCase
{
    use RefreshDatabase;

    private string $routeName = 'contents.show';
    private string $method = "get";

    private function getContent(Content $content, array $data = []): TestResponse
    {
        return $this->json($this->method, route($this->routeName, [$content->id]), $data);
    }

    /** @test */
    public function it_fetches_news_and_articles(): void
    {
        $article = $this->createArticle();
        $news = $this->createNewsArticle();

        $response = $this->getContent($article);
        $response->assertOk();
        $this->assertEquals($article->id, $response->json('data.id'));
        $this->assertArrayNotHasKey('phone', $response->json('data'));

        $response = $this->getContent($article, ['type' => ContentType::ARTICLE]);
        $response->assertOk();
        $this->assertEquals($article->id, $response->json('data.id'));
        $this->assertArrayNotHasKey('phone', $response->json('data'));

        $response = $this->getContent($news);
        $response->assertOk();
        $this->assertEquals($news->id, $response->json('data.id'));
        $this->assertArrayNotHasKey('phone', $response->json('data'));

        $response = $this->getContent($news, ['type' => ContentType::NEWS]);
        $response->assertOk();
        $this->assertEquals($news->id, $response->json('data.id'));
        $this->assertArrayNotHasKey('phone', $response->json('data'));
    }

    /** @test */
    public function it_fetches_event(): void
    {
        $event = Content::factory()->event()->create();
        $this->createSpeakersForEvent($event, 2);

        $response = $this->getContent($event);
        $response->assertOk();
        $this->assertEquals($event->id, $response->json('data.id'));
        $this->assertArrayHasKey('phone', $response->json('data'));
        $this->assertArrayHasKey('speakers', $response->json('data'));
        $this->assertCount(2, $response->json('data.speakers'));

        $response = $this->getContent($event, ['type' => ContentType::EVENT]);
        $response->assertOk();
        $this->assertEquals($event->id, $response->json('data.id'));
        $this->assertArrayHasKey('phone', $response->json('data'));
        $this->assertArrayHasKey('speakers', $response->json('data'));
        $this->assertCount(2, $response->json('data.speakers'));
    }

    /** @test */
    public function only_manager_of_institution_or_employer_can_access_their_draft_content(): void
    {
        $institution = $this->createInstitution();
        $institutionManager = $this->createManagerOfInstitution($institution);
        $employer = $this->createEmployer();
        $anotherInstitutionManager = $this->createManagerOfInstitution($this->createInstitution());

        $draftArticle = $this->createArticle(['institution_id' => $institution->id, 'is_published' => false]);
        $draftEmployerArticle = $this->createNewsArticle(['user_id' => $employer->user->id, 'is_published' => false]);

        $publishedArticle = $this->createArticle(['institution_id' => $institution->id, 'is_published' => true]);

        //as guest
        $this->getContent($draftArticle)->assertForbidden();
        $this->getContent($draftEmployerArticle)->assertForbidden();
        $this->getContent($publishedArticle)->assertOk();

        //as logged in user
        $this->actingAs($this->createUser());
        $this->getContent($draftArticle)->assertForbidden();
        $this->getContent($draftEmployerArticle)->assertForbidden();
        $this->getContent($publishedArticle)->assertOk();

        //as manager of another institution
        $this->actingAs($anotherInstitutionManager);
        $this->getContent($draftArticle)->assertForbidden();
        $this->getContent($draftEmployerArticle)->assertForbidden();
        $this->getContent($publishedArticle)->assertOk();

        //as manager
        $this->actingAs($institutionManager);
        $this->getContent($draftArticle)->assertOk();
        $this->getContent($draftEmployerArticle)->assertForbidden();
        $this->getContent($publishedArticle)->assertOk();

        //as employer

        $this->actingAs($employer->user);
        $this->getContent($draftArticle)->assertForbidden();
        $this->getContent($draftEmployerArticle)->assertOk();
        $this->getContent($publishedArticle)->assertOk();
    }

    /** @test */
    public function it_responds_with_404_when_provided_type_doesnt_match_content_type(): void
    {
        $article = $this->createArticle();

        $this->getContent($article, ['type' => ContentType::EVENT])->assertNotFound();
        $this->getContent($article, ['type' => ContentType::ARTICLE])->assertOk();
    }

    /** @test */
    public function it_responds_with_content_when_provided_type_matched(): void
    {
        $article = $this->createArticle();

        $this->getContent($article, ['type' => ContentType::ARTICLE])->assertOk();
        $this->getContent($article)->assertOk();
    }

    /** @test */
    public function it_contains_correct_is_favorited_field(): void
    {
        $user = $this->createUser();
        $article = $this->createArticle();

        $article->addFavorite($user->id);

        $response = $this->getContent($article);
        $response->assertOk();
        $this->assertArrayHasKey('is_favorited', $response->json('data'));
        $this->assertFalse($response->json('data.is_favorited'));

        $response = $this->actingAs($user)->getContent($article);
        $response->assertOk();
        $this->assertArrayHasKey('is_favorited', $response->json('data'));
        $this->assertTrue($response->json('data.is_favorited'));
    }
}
