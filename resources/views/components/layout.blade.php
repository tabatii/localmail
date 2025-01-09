@use('Tabatii\LocalMail\Facades\LocalMail')
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ "LocalMail - {$title}" }}</title>
        {!! LocalMail::css() !!}
    </head>
    <body>
        <div class="flex text-slate-800">
            <div
                class="fixed lg:static top-0 -start-96 bottom-0 z-50 shrink-0 border-e border-indigo-100 duration-500 max-w-full w-96"
                x-bind:class="{ '!start-0': open }"
                x-data="{ open: false }"
            >
                <div class="fixed start-4 bottom-4 lg:!hidden">
                    <button
                        type="button"
                        class="inline-flex justify-center items-center text-white bg-indigo-500 hover:bg-indigo-600 rounded-full duration-200 size-12"
                        x-on:click="open = !open"
                    >
                        <x-localmail::icons.bars-solid class="size-4" />
                    </button>
                </div>
                <div
                    class="fixed inset-0 lg:!hidden bg-black/50 invisible opacity-0 duration-500"
                    x-bind:class="{ 'invisible opacity-0': !open }"
                    x-on:click="open = false"
                ></div>
                <div class="relative bg-white">
                    <livewire:localmail.sidebar />
                </div>
            </div>
            <div class="grow w-full lg:w-[calc(100%-24rem)]">{{ $slot }}</div>
        </div>
    </body>
</html>
