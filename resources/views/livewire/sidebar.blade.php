<div>
    <div class="flex justify-between items-center gap-2 border-b border-indigo-100 h-20 px-4">
        <div class="shrink-0 w-full">
            <div class="flex gap-2">
                <div class="shrink-0 lg:hidden size-9">
                    <button
                        type="button"
                        class="inline-flex justify-center items-center bg-indigo-50 hover:bg-indigo-100 rounded-full duration-200 size-full"
                        x-on:click="open = false"
                    >
                        <x-localmail::icons.bars-solid class="size-4" />
                    </button>
                </div>
                <div class="shrink-0 size-9">
                    <a
                        href="{{ config('localmail.home_url', '/') }}"
                        class="inline-flex justify-center items-center bg-indigo-50 hover:bg-indigo-100 rounded-full duration-200 size-full"
                        wire:navigate
                    >
                        <x-localmail::icons.house-solid class="size-4" />
                    </a>
                </div>
                <div class="relative grow h-9">
                    <input
                        type="text"
                        class="border border-indigo-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-full size-full pe-8"
                        wire:model.live="search"
                        placeholder="Search..."
                    />
                    <div class="absolute top-2.5 end-2.5 text-indigo-200 cursor-text pointer-events-none">
                        <x-localmail::icons.magnifying-glass-solid class="size-4" />
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="overflow-auto h-[calc(100vh-5rem)] p-4 space-y-2">
        <ul class="flex flex-wrap gap-2">
            <li>
                <a
                    href="#readAll"
                    class="inline-flex justify-center items-center gap-1 bg-indigo-50 hover:bg-indigo-100 rounded-full duration-200 h-9 px-3"
                    wire:click.prevent="readAll"
                >
                    <x-localmail::icons.envelope-open-regular class="size-4" />
                    <div>Mark all as read</div>
                </a>
            </li>
            <li>
                <a
                    href="#deleteAll"
                    class="inline-flex justify-center items-center gap-1 bg-indigo-50 hover:bg-indigo-100 rounded-full duration-200 h-9 px-3"
                    wire:click.prevent="deleteAll"
                    wire:confirm="Are you sure ?"
                >
                    <x-localmail::icons.envelope-open-regular class="size-4" />
                    <div>Delete all</div>
                </a>
            </li>
        </ul>
        @forelse ($this->recipients as $recipient)
            <div class="flex flex-wrap justify-between">
                <div class="w-[calc(100%-2.25rem)] h-9 pe-2">
                    <a
                        href="{{ route('localmail.recipient', $recipient->id) }}"
                        class="inline-flex items-center gap-1 grow bg-indigo-50 hover:bg-indigo-100 rounded-full duration-200 size-full px-3"
                        wire:navigate
                    >
                        @if ($recipient->messages_count > 0)
                            <div class="inline-flex justify-center items-center shrink-0 bg-indigo-500 rounded-full size-3"></div>
                        @endif
                        <div @class(['truncate', 'text-indigo-500 font-medium' => $recipient->messages_count > 0])>{{ $recipient->address }}</div>
                    </a>
                </div>
                <div
                    class="relative size-9"
                    x-data="{ open: false }"
                    x-on:click.outside="open = false"
                    x-on:recipient-read.window="open = false"
                    x-on:recipient-deleted.window="open = false"
                    x-on:iframe-clicked.window="open = false"
                >
                    <button
                        type="button"
                        class="inline-flex justify-center items-center bg-indigo-50 hover:bg-indigo-100 rounded-full duration-200 size-full"
                        x-on:click="open = !open"
                    >
                        <x-localmail::icons.ellipsis-vertical-solid class="size-4" />
                    </button>
                    <div class="absolute top-full end-0 z-10" x-show="open" x-cloak>
                        <ul class="bg-white border border-indigo-100 rounded-lg shadow min-w-40 p-1 mt-1">
                            @if ($recipient->messages_count > 0)
                                <li>
                                    <a
                                        href="#read"
                                        class="block hover:text-indigo-500 hover:bg-indigo-50 rounded duration-200 px-3 py-1"
                                        wire:click.prevent="read('{{ $recipient->id }}')"
                                    >
                                        <span>Mark as read</span>
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a
                                    href="#delete"
                                    class="block hover:text-red-500 hover:bg-red-50 rounded duration-200 px-3 py-1"
                                    wire:click.prevent="delete('{{ $recipient->id }}')"
                                    wire:confirm="Are you sure ?"
                                >
                                    <span>Delete</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center font-medium py-1.5">No Recipients Found</p>
        @endforelse
        @if ($this->recipients->hasMorePages())
            <div x-intersect="$wire.dispatchSelf('load-more')"></div>
        @endif
    </div>
</div>
