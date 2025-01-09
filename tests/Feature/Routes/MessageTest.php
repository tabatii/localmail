<?php

namespace Tests\Feature\Routes;

use Tabatii\LocalMail\Models\Message;
use Tests\TestCase;

class MessageTest extends TestCase
{
    public function test_the_message_page_returns_a_successful_response(): void
    {
        $message = Message::first();
        $response = $this->get(route('localmail.message', $message->id));
        $response->assertStatus(200);
        $response->assertSeeLivewire(\Tabatii\LocalMail\Livewire\Sidebar::class);
        $response->assertSeeLivewire(\Tabatii\LocalMail\Livewire\Message::class);
    }
}
