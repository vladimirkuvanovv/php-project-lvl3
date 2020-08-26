<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Faker\Generator;
use Faker\Provider\Address;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DomainsControllerTest extends TestCase
{
    use DatabaseTransactions, DatabaseMigrations;

    protected $id;
    protected $domain = 'https://www.w3schools.com';

    protected function setUp(): void
    {
        parent::setUp();

        $this->id = DB::table('domains')->insertGetId([
            'name'       => $this->domain,
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
        $response = $this->post(route('domains.store'), ['domain' => $this->domain]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseHas('domains', ['name' => $this->domain]);
    }

    public function testShow()
    {
        $response = $this->get(route('domains.show', $this->id));

        $response->assertOk();
    }
}
