<?php

namespace Tabatii\LocalMail\Livewire;

use Tabatii\LocalMail\Models\Recipient as RecipientModel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Pipeline;
use Tabatii\LocalMail\Models\Message;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('localmail::components.layout')]
class Recipient extends Component
{
    #[Locked]
    public $id;

    #[Url(as: 'message', except: '')]
    public $search = '';

    #[Computed]
    public function recipient()
    {
        return RecipientModel::findOrFail($this->id);
    }

    #[Computed]
    public function messages()
    {
        return Pipeline::send(Message::query())
            ->through([
                function (Builder $query, \Closure $next) {
                    $query->where('recipient_id', $this->recipient->id);
                    return $next($query);
                },
                function (Builder $query, \Closure $next) {
                    if (filled($this->search)) {
                        $query->where('subject', 'like', "%{$this->search}%");
                    }
                    return $next($query);
                },
            ])
            ->thenReturn()
            ->latest('id')
            ->paginate(50)
            ->withQueryString();
    }

    #[On('recipient-read')]
    public function refresh()
    {
        //
    }

    public function readAll()
    {
        if ($this->recipient->messages()->unread()->update(['read_at' => now()])) {
            $this->dispatch('message-read');
        }
    }

    public function deleteAll()
    {
        if ($this->recipient->messages()->delete()) {
            $this->dispatch('message-deleted');
        }
    }

    public function read(Message $message)
    {
        if ($message->touch('read_at')) {
            $this->dispatch('message-read');
        }
    }

    public function delete(Message $message)
    {
        if ($message->delete()) {
            $this->dispatch('message-deleted');
        }
    }

    public function render()
    {
        return view('localmail::livewire.recipient')->title($this->recipient->address);
    }
}
