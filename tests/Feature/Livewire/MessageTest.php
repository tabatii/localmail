<?php

namespace Tests\Feature\Livewire;

use Tabatii\LocalMail\Models\Message;
use Livewire\Livewire;
use Tests\TestCase;

class MessageTest extends TestCase
{
    public function test_message_can_be_deleted(): void
    {
        $message = Message::first();
        $livewire = Livewire::test(\Tabatii\LocalMail\Livewire\Message::class, ['id' => $message->id]);
        $livewire->call('delete');
        $livewire->assertRedirect(route('localmail.recipient', $message->recipient_id));
        $this->assertEquals(Message::where('id', $message->id)->count(), 0);
    }
}
