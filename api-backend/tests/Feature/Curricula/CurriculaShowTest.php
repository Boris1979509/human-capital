<?php

namespace Tests\Feature\Curricula;

use App\Http\Resources\Curricula\CurriculumDetailedForPublicResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CurriculaShowTest extends TestCase
{
    use RefreshDatabase;

    private string $method = "get";

    /** @test */
    public function it_fetches_curriculum(): void
    {
        $curriculum = $this->createCurriculaForInstitution($this->createInstitution(), 1)->first();

        $response = $this->json($this->method, route('curricula.show', $curriculum->id));
        $response->assertOk();
        $this->assertEquals(
            (new CurriculumDetailedForPublicResource($curriculum))->response()->getData(true)['data'],
            $response->json('data')
        );
    }

    /** @test */
    public function it_calculates_min_price(): void
    {
        $minCost = 10;
        $curriculum = $this->createCurriculaForInstitution($this->createInstitution(), 1, [
            'learning_options' => [
                ['cost' => 200],
                ['cost' => $minCost],
                ['cost' => 100],
            ]
        ])->first();

        $response = $this->json($this->method, route('curricula.show', $curriculum->id));
        $response->assertOk();
        $this->assertEquals(
            "от $minCost ₽",
            $response->json('data.cost')
        );
    }

    /** @test */
    public function it_responds_with_404_if_curriculum_is_not_published(): void
    {
        $curriculum = $this->createCurriculaForInstitution(
            $this->createInstitution(),
            1,
            ['is_published' => false]
        )->first();

        $response = $this->json($this->method, route('curricula.show', $curriculum->id));
        $response->assertNotFound();
    }
}
