<?php

namespace Tests\Feature\Livewire;

use Tabatii\LocalMail\Models\Recipient;
use Tabatii\LocalMail\Models\Message;
use Livewire\Livewire;
use Tests\TestCase;

class RecipientTest extends TestCase
{
    public function test_all_messages_can_be_read(): void
    {
        $recipient = Recipient::first();
        $livewire = Livewire::test(\Tabatii\LocalMail\Livewire\Recipient::class, ['id' => $recipient->id]);
        $livewire->call('readAll');
        $livewire->assertDispatched('message-read');
        $this->assertEquals(Message::where('recipient_id', $recipient->id)->unread()->count(), 0);
    }

    public function test_all_messages_can_be_deleted(): void
    {
        $recipient = Recipient::first();
        $livewire = Livewire::test(\Tabatii\LocalMail\Livewire\Recipient::class, ['id' => $recipient->id]);
        $livewire->call('deleteAll');
        $livewire->assertDispatched('message-deleted');
        $this->assertEquals(Message::where('recipient_id', $recipient->id)->count(), 0);
    }

    public function test_message_can_be_read(): void
    {
        $recipient = Recipient::first();
        $message = Message::where('recipient_id', $recipient->id)->first();
        $livewire = Livewire::test(\Tabatii\LocalMail\Livewire\Recipient::class, ['id' => $recipient->id]);
        $livewire->call('read', $message->id);
        $livewire->assertDispatched('message-read');
        $this->assertEquals(Message::where('id', $message->id)->unread()->count(), 0);
    }

    public function test_message_can_be_deleted(): void
    {
        $recipient = Recipient::first();
        $message = Message::where('recipient_id', $recipient->id)->first();
        $livewire = Livewire::test(\Tabatii\LocalMail\Livewire\Recipient::class, ['id' => $recipient->id]);
        $livewire->call('delete', $message->id);
        $livewire->assertDispatched('message-deleted');
        $this->assertEquals(Message::where('id', $message->id)->count(), 0);
    }
}
