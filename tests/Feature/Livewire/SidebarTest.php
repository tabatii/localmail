<?php

namespace Tests\Feature\Livewire;

use Tabatii\LocalMail\Models\Recipient;
use Tabatii\LocalMail\Models\Message;
use Livewire\Livewire;
use Tests\TestCase;

class SidebarTest extends TestCase
{
    public function test_all_recipients_can_be_read(): void
    {
        $livewire = Livewire::test(\Tabatii\LocalMail\Livewire\Sidebar::class);
        $livewire->call('readAll');
        $livewire->assertDispatched('recipient-read');
        $this->assertEquals(Message::unread()->count(), 0);
    }

    public function test_all_recipient_can_be_deleted(): void
    {
        $livewire = Livewire::test(\Tabatii\LocalMail\Livewire\Sidebar::class);
        $livewire->call('deleteAll');
        $livewire->assertRedirect(route('localmail.dashboard'));
        $this->assertEquals(Recipient::count(), 0);
    }

    public function test_recipient_can_be_read(): void
    {
        $recipient = Recipient::first();
        $livewire = Livewire::test(\Tabatii\LocalMail\Livewire\Sidebar::class);
        $livewire->call('read', $recipient->id);
        $livewire->assertDispatched('recipient-read');
        $this->assertEquals(Message::where('recipient_id', $recipient->id)->unread()->count(), 0);
    }

    public function test_recipient_can_be_deleted_from_dashboard_page(): void
    {
        $recipient = Recipient::first();
        $livewire = Livewire::test(\Tabatii\LocalMail\Livewire\Sidebar::class);
        $livewire->call('delete', $recipient->id);
        $livewire->assertDispatched('recipient-deleted');
        $this->assertEquals(Recipient::where('id', $recipient->id)->count(), 0);
    }

    public function test_recipient_can_be_deleted_from_current_recipient_page(): void
    {
        $recipient = Recipient::first();
        $livewire = Livewire::test(\Tabatii\LocalMail\Livewire\Sidebar::class, ['recipient_id' => $recipient->id]);
        $livewire->call('delete', $recipient->id);
        $livewire->assertRedirect(route('localmail.dashboard'));
        $this->assertEquals(Recipient::where('id', $recipient->id)->count(), 0);
    }

    public function test_recipient_can_be_deleted_from_current_message_page(): void
    {
        $recipient = Recipient::first();
        $livewire = Livewire::test(\Tabatii\LocalMail\Livewire\Sidebar::class, ['message_id' => $recipient->messages()->value('id')]);
        $livewire->call('delete', $recipient->id);
        $livewire->assertRedirect(route('localmail.dashboard'));
        $this->assertEquals(Recipient::where('id', $recipient->id)->count(), 0);
    }
}
