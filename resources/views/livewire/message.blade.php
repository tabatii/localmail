<div>
    <div class="flex justify-between items-center gap-2 border-b border-indigo-100 h-20 px-4">
        <div class="shrink-0 w-full">
            <div class="flex flex-wrap">
                <div
                    class="relative size-12"
                    x-data="{ open: false }"
                    x-on:click.outside="open = false"
                    x-on:iframe-clicked.window="open = false"
                >
                    <a
                        href="#mail"
                        class="inline-flex justify-center items-center bg-indigo-50 hover:bg-indigo-100 rounded-full duration-200 size-full"
                        x-on:click.prevent="open = !open"
                    >
                        <x-localmail::icons.envelope-regular class="size-4" />
                    </a>
                    <div class="absolute top-full -start-2 sm:start-0 z-10" x-show="open" x-cloak>
                        <div class="bg-white text-sm border border-indigo-100 rounded-lg shadow w-80 sm:w-[600px] p-4 mt-1">
                            @foreach ($this->message->headers as $header)
                                <div class="flex flex-wrap">
                                    <div class="w-28 font-medium">{{ $this->getHeaderName($header) }}:</div>
                                    <div class="w-[calc(100%-7rem)] text-ellipsis overflow-hidden">{{ $this->getHeaderValue($header) }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="w-[calc(100%-3rem)] ps-2">
                    <div class="font-semibold truncate">{{ $this->message->from_name }}</div>
                    <div class="text-indigo-300 truncate">{{ $this->message->from_address }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="overflow-auto h-[calc(100vh-5rem)] p-4 space-y-2">
        <div class="flex flex-col gap-6 h-full">
            <ul class="flex flex-wrap gap-2">
                <li>
                    <a
                        href="{{ route('localmail.recipient', $this->message->recipient_id) }}"
                        class="inline-flex justify-center items-center bg-indigo-50 hover:bg-indigo-100 rounded-full duration-200 size-9"
                        wire:navigate
                    >
                        <x-localmail::icons.arrow-left-solid class="size-4" />
                    </a>
                </li>
                <li>
                    <a
                        href="#delete"
                        class="inline-flex justify-center items-center bg-indigo-50 hover:bg-indigo-100 rounded-full duration-200 size-9"
                        wire:click.prevent="delete"
                        wire:confirm="Are you sure ?"
                    >
                        <x-localmail::icons.trash-can-regular class="size-4" />
                    </a>
                </li>
            </ul>
            <div class="shrink-0">
                <h1 class="text-3xl mb-1">{{ $this->message->subject ?: 'No subject' }}</h1>
                <div class="text-indigo-300 text-sm">{{ $this->message->created_at->toDayDateTimeString() }} ({{ $this->message->created_at->since() }})</div>
            </div>
            <div @class(['shrink-0 space-y-2', 'hidden' => empty($this->message->attachments)])>
                <div class="flex flex-wrap gap-2">
                    @foreach ($this->message->attachments as $attachment)
                        <div class="inline-flex items-center gap-1 text-white text-sm bg-indigo-500 rounded px-2 py-1">
                            <x-localmail::icons.file-lines-regular class="size-4" />
                            <div>{{ $attachment['name'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="grow shrink-0">
                <iframe
                    class="!overflow-hidden w-full h-0"
                    sandbox="allow-same-origin"
                    scrolling="no"
                    x-data="{ body: @js($this->message->html_body) }"
                    x-init="$el.src = URL.createObjectURL(new Blob([body], { type: 'text/html' }))"
                    x-on:load="() => {
                        $el.style.height = `${$el.contentWindow.document.documentElement.scrollHeight+16}px`
                        $el.contentWindow.document.addEventListener('click', () => $dispatch('iframe-clicked'))
                    }"
                ></iframe>
            </div>
        </div>
    </div>
</div>
