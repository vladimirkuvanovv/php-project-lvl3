<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Faker\Factory;

class DomainCheckControllerTest extends TestCase
{
    protected $id;
    protected $domain;

    protected function setUp(): void
    {
        parent::setUp();

        $faker = Factory::create();
        $this->domain = $faker->url;

        $this->id = DB::table('domains')->insertGetId([
            'name'       => $this->domain,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testStore()
    {
        $expected = [
            'domain_id' => $this->id,
            'status_code' => 200,
            'keywords' => 'test keywords',
            'h1' => 'test h1',
            'description' => 'test description',
        ];

        $html = file_get_contents(__DIR__ . '/../fixtures/fixture.html');

        Http::fake([$this->domain => Http::response($html, 200)]);

        $response = $this->post(route('check', ['id' => $this->id]));

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseHas('domain_checks', $expected);
    }
}
