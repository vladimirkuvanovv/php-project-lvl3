<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Faker\Factory;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DomainsControllerTest extends TestCase
{
    protected $id;
    protected $url;

    protected function setUp(): void
    {
        parent::setUp();

        $faker = Factory::create();
        $this->url = $faker->url;

        $this->id = DB::table('domains')->insertGetId([
            'name'       => $this->url,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    public function testIndex()
    {
        $response = $this->get(route('domains.index'));
        $response->assertOk();
    }

    public function testStore()
    {
        $response = $this->post(route('domains.store'), ['url' => $this->url]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseHas('domains', ['name' => $this->url]);
    }

    public function testShow()
    {
        $response = $this->get(route('domains.show', $this->id));

        $response->assertOk();
    }
}
