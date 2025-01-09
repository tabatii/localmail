<?php

namespace Tabatii\LocalMail\Livewire;

use Tabatii\LocalMail\Models\Message as MessageModel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Carbon\Carbon;

#[Layout('localmail::components.layout')]
class Message extends Component
{
    #[Locked]
    public $id;

    #[Locked]
    public $recipient_id;

    public function mount()
    {
        $this->recipient_id = $this->message->recipient_id;
        if (is_null($this->message->read_at)) {
            $this->message->touch('read_at');
        }
    }

    #[Computed]
    public function message()
    {
        return MessageModel::where('id', $this->id)->with('recipient')->firstOrFail();
    }

    public function getHeaderName(string $header)
    {
        return trim(str($header)->explode(':',2)->first());
    }

    public function getHeaderValue(string $header)
    {
        $value = trim(str($header)->explode(':',2)->last());
        try {
            return Carbon::createFromFormat(\DateTimeInterface::RFC2822, $value)->toDayDateTimeString();
        } catch (\Carbon\Exceptions\InvalidFormatException $e) {
            return $value;
        }
    }

    public function delete()
    {
        if ($this->message->delete()) {
            $this->redirectRoute('localmail.recipient', $this->recipient_id, navigate: true);
        }
    }

    public function render()
    {
        return view('localmail::livewire.message')->title($this->message->subject ?: 'Message');
    }
}
