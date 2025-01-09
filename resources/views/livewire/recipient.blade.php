<div>
    <div class="flex justify-between items-center gap-2 border-b border-indigo-100 h-20 px-4" x-data="{ open: false }">
        <div class="grow sm:grow-0 sm:shrink-0">
            <div class="relative sm:w-96 h-9" x-on:click="open = true" x-on:click.outside="open = false">
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
        <div class="shrink-0" x-bind:class="{ 'hidden sm:block': open }">
            {{ $this->messages->onEachSide(1)->links('localmail::components.pagination') }}
        </div>
    </div>
    <div class="overflow-auto h-[calc(100vh-5rem)] p-4 space-y-2">
        <ul class="flex flex-wrap gap-2">
            <li>
                <a
                    href="{{ route('localmail.dashboard') }}"
                    class="inline-flex justify-center items-center bg-indigo-50 hover:bg-indigo-100 rounded-full duration-200 size-9"
                    wire:navigate
                >
                    <x-localmail::icons.arrow-left-solid class="size-4" />
                </a>
            </li>
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
        @forelse ($this->messages as $message)
            <div class="flex flex-wrap justify-between">
                <div class="w-[calc(100%-2.25rem)] h-9 pe-2">
                    <a
                        href="{{ route('localmail.message', $message->id) }}"
                        class="inline-flex items-center gap-1 grow bg-indigo-50 hover:bg-indigo-100 rounded-full duration-200 size-full px-3"
                        wire:navigate
                    >
                        @if (is_null($message->read_at))
                            <div class="inline-flex justify-center items-center shrink-0 bg-indigo-500 rounded-full size-3"></div>
                        @endif
                        <div @class(['hidden sm:block shrink-0 truncate w-52 pe-8', 'text-indigo-500 font-medium' => is_null($message->read_at)])>
                            <span>{{ $message->from_name ?: $message->from_address }}</span>
                        </div>
                        <div @class(['truncate', 'text-indigo-500 font-medium' => is_null($message->read_at)])>
                            <span>{{ $message->subject ?: 'No subject' }}</span>
                        </div>
                    </a>
                </div>
                <div
                    class="relative size-9"
                    x-data="{ open: false }"
                    x-on:click.outside="open = false"
                    x-on:message-read.window="open = false"
                    x-on:message-deleted.window="open = false"
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
                            @if (is_null($message->read_at))
                                <li>
                                    <a
                                        href="#read"
                                        class="block hover:text-indigo-500 hover:bg-indigo-50 rounded duration-200 px-3 py-1"
                                        wire:click.prevent="read('{{ $message->id }}')"
                                    >
                                        <span>Mark as read</span>
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a
                                    href="#delete"
                                    class="block hover:text-red-500 hover:bg-red-50 rounded duration-200 px-3 py-1"
                                    wire:click.prevent="delete('{{ $message->id }}')"
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
        <p class="text-center font-medium py-1.5">No Messages Found</p>
        @endforelse
    </div>
</div>
