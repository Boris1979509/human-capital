<?php

namespace Tests\Feature\Institution;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InstitutionAutocompleteTest extends TestCase
{
    use RefreshDatabase;

    private string $endpoint;
    private string $method = "get";

    protected function setUp(): void
    {
        parent::setUp();
        $this->endpoint = route('institutions.autocomplete');
    }

    /** @test */
    public function it_filters_institution_by_name(): void
    {
        $fooFull = $this->createInstitution(['full_name' => 'foo', 'short_name'=>'qqq']);
        $fooShort = $this->createInstitution(['short_name' => 'foo', 'full_name'=>'qqq']);

        $barFull = $this->createInstitution(['full_name' => 'bar', 'short_name'=>'qqq']);
        $barShort = $this->createInstitution(['short_name' => 'bar', 'full_name'=>'qqq']);

        $this->assertDatabaseCount('institutions', 4);

        $response = $this->json($this->method, $this->endpoint);
        $response->assertOk();
        $this->assertCount(4, $response->json('data'));

        $response = $this->json($this->method, $this->endpoint, ['name' => 'o']);
        $response->assertOk();
        $this->assertCount(2, $response->json('data'));
        $this->assertEquals(
            collect([$fooFull, $fooShort])->pluck('id'),
            collect($response->json('data'))->pluck('id')
        );

        $response = $this->json($this->method, $this->endpoint, ['name' => 'f']);
        $response->assertOk();
        $this->assertCount(2, $response->json('data'));
        $this->assertEquals(
            collect([$fooFull, $fooShort])->pluck('id'),
            collect($response->json('data'))->pluck('id')
        );

        $response = $this->json($this->method, $this->endpoint, ['name' => 'A']);
        $response->assertOk();
        $this->assertCount(2, $response->json('data'));
        $this->assertEquals(
            collect([$barFull, $barShort])->pluck('id'),
            collect($response->json('data'))->pluck('id')
        );

        $response = $this->json($this->method, $this->endpoint, ['name' => 'R']);
        $response->assertOk();
        $this->assertCount(2, $response->json('data'));
        $this->assertEquals(
            collect([$barFull, $barShort])->pluck('id'),
            collect($response->json('data'))->pluck('id')
        );
    }
}
