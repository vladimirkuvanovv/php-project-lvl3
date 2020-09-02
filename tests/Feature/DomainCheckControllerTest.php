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
        $response = $this->post(route('check', ['id' => $this->id]));

        $response->assertStatus(302);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseHas('domain_checks', ['domain_id' => $this->id]);
    }

    public function testClientRequest()
    {
        Http::fake();

        $response = Http::post(route('check', ['id' => $this->id]));
        $this->assertSame(200, $response->status());
    }
}
