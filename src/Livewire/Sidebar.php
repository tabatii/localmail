<?php

namespace Tabatii\LocalMail\Livewire;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Pipeline;
use Tabatii\LocalMail\Models\Recipient;
use Tabatii\LocalMail\Models\Message;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;
use Livewire\Component;

class Sidebar extends Component
{
    #[Url(as: 'recipient', except: '')]
    public $search = '';

    #[Locked]
    public ?int $recipient_id = null;

    #[Locked]
    public ?int $message_id = null;

    #[Locked]
    public int $limit = 40;

    public function mount()
    {
        if (request()->routeIs('localmail.recipient')) {
            $this->recipient_id = request()->route('id');
        }
        if (request()->routeIs('localmail.message')) {
            $this->message_id = request()->route('id');
        }
    }

    #[Computed]
    public function recipients()
    {
        return Pipeline::send(Recipient::query())
            ->through([
                function (Builder $query, \Closure $next) {
                    $query->withCount(['messages' => fn (Builder $query) => $query->unread()]);
                    return $next($query);
                },
                function (Builder $query, \Closure $next) {
                    if (filled($this->search)) {
                        $query->where('address', 'like', "%{$this->search}%");
                    }
                    return $next($query);
                },
            ])
            ->thenReturn()
            ->latest('updated_at')
            ->latest('id')
            ->paginate($this->limit);
    }

    #[On('message-read')]
    #[On('message-deleted')]
    public function refresh()
    {
        //
    }

    #[On('load-more')]
    public function loadMore()
    {
        if ($this->recipients->hasMorePages()) {
            $this->limit = $this->limit + 20;
            unset($this->recipients);
        }
    }

    public function readAll()
    {
        if (Message::unread()->update(['read_at' => now()])) {
            $this->dispatch('recipient-read');
        }
    }

    public function deleteAll()
    {
        if (Recipient::query()->delete()) {
            $this->redirectRoute('localmail.dashboard', navigate: true);
        }
    }

    public function read(Recipient $recipient)
    {
        if ($recipient->messages()->unread()->update(['read_at' => now()])) {
            $this->dispatch('recipient-read');
        }
    }

    public function delete(Recipient $recipient)
    {
        $is_recipient_active = $recipient->id === $this->recipient_id;
        $is_message_active = $recipient->messages()->where('id', $this->message_id)->exists();
        if ($recipient->delete()) {
            if ($is_recipient_active || $is_message_active) {
                $this->redirectRoute('localmail.dashboard', navigate: true);
                return;
            }
            $this->dispatch('recipient-deleted');
        }
    }

    public function render()
    {
        return view('localmail::livewire.sidebar');
    }
}
