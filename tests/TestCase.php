<?php

namespace Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tabatii\LocalMail\Mail\Fake;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase, WithWorkbench;

    protected $enablesPackageDiscoveries = true;

    protected $emails_count = 3;

    protected function setUp(): void
    {
        parent::setUp();
        for ($i = 0; $i < $this->emails_count; $i++) {
            $this->artisan('localmail:fake');
        }
    }
}
