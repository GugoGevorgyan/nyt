<?php

declare(strict_types=1);

namespace Tests\Feature;

use Src\Models\Client\Client;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Class ExampleTest
 * @package Tests\Feature
 */
class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicGetInit(): void
    {
        $user = factory(Client::class)->make();

        $response = $this->actingAs($user)->get('/init');

        $response->assertStatus(200);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicGetInitCoin(): void
    {
        $user = factory(Client::class)->make();

        $response = $this->actingAs($user)->post('/init_coin');

        $response->assertStatus(200);
    }
}
