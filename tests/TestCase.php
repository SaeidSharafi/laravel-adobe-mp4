<?php

namespace Tests;

use App\Traits\FeatureTestTrait;
use Faker\Factory as Faker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use FeatureTestTrait;
    use RefreshDatabase;

    protected $faker;


    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Faker::create();

        Artisan::call('db:seed TestSeeder');
    }

    /**
     * Rolls back migrations
     */
    protected function tearDown(): void
    {
        //Artisan::call('migrate:reset');

        parent::tearDown();
    }
}
