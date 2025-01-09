@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between gap-4">
        <div>
            <p class="text-sm leading-5">
                @if ($paginator->firstItem())
                    <span class="font-medium">{{ $paginator->firstItem() }}</span><span>-</span><span class="font-medium">{{ $paginator->lastItem() }}</span>
                @else
                    {{ $paginator->count() }}
                @endif
                {!! __('of') !!}
                <span class="font-medium">{{ $paginator->total() }}</span>
            </p>
        </div>

        <div class="flex justify-between flex-1 gap-2">
            @if ($paginator->onFirstPage())
                <span class="inline-flex justify-center items-center text-indigo-200 bg-indigo-50 rounded-full cursor-default size-9">
                    <x-localmail::icons.angle-left-solid class="size-4" />
                </span>
            @else
                <a
                    href="{{ $paginator->previousPageUrl() }}"
                    class="inline-flex justify-center items-center bg-indigo-50 hover:bg-indigo-100 rounded-full duration-200 size-9"
                    wire:navigate
                >
                    <x-localmail::icons.angle-left-solid class="size-4" />
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a
                    href="{{ $paginator->nextPageUrl() }}"
                    class="inline-flex justify-center items-center bg-indigo-50 hover:bg-indigo-100 rounded-full duration-200 size-9"
                    wire:navigate
                >
                    <x-localmail::icons.angle-right-solid class="size-4" />
                </a>
            @else
                <span class="inline-flex justify-center items-center text-indigo-200 bg-indigo-50 rounded-full cursor-default size-9">
                    <x-localmail::icons.angle-right-solid class="size-4" />
                </span>
            @endif
        </div>
    </nav>
@endif
