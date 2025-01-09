<?php

namespace Tests\Feature\Routes;

use Tabatii\LocalMail\Models\Recipient;
use Tests\TestCase;

class RecipientTest extends TestCase
{
    public function test_the_recipient_page_returns_a_successful_response(): void
    {
        $recipient = Recipient::first();
        $response = $this->get(route('localmail.recipient', $recipient->id));
        $response->assertStatus(200);
        $response->assertSeeLivewire(\Tabatii\LocalMail\Livewire\Sidebar::class);
        $response->assertSeeLivewire(\Tabatii\LocalMail\Livewire\Recipient::class);
    }
}
