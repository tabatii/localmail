<?php

namespace Tests\Feature;

use Tests\TestCase;

class LocalMailTest extends TestCase
{
    public function test_sending_emails(): void
    {
        $this->assertDatabaseCount('recipients', $this->emails_count);
        $this->assertDatabaseCount('messages', $this->emails_count);
    }
}
