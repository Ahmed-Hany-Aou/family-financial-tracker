<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     */
    public function createApplication(): Application
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();
        
        // Set up test configuration
        config([
            'hypersender.instance_id' => env('HYPERSENDER_INSTANCE_ID', 'test-instance'),
            'hypersender.api_token' => env('HYPERSENDER_API_TOKEN', 'test-token'),
            'hypersender.my_phone' => env('MY_WHATSAPP_NUMBER', '201234567890')
        ]);
    }
}