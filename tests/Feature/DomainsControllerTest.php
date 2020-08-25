<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class DomainsControllerTest extends TestCase
{
    use DatabaseTransactions, DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testIndex()
    {
        $response = $this->get(route('domains.index'));
        $response->assertOk();
    }

    public function testStore()
    {
        $data = ['domain' => 'https://www.w3schools.com'];
        $response = $this->post(route('domains.store'), $data);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseHas('domains', ['name' => $data['domain']]);
    }


}
