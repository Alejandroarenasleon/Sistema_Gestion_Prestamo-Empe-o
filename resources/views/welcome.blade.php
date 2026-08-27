<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="font-sans antialiased dark:bg-black dark:text-white/50">
        <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
            <div class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#FF2D20] selection:text-white">
                <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                    <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                        <div class="flex lg:justify-center lg:col-start-2">
                            <svg class="h-12 w-auto text-white lg:h-16 lg:text-[#FF2D20]" viewBox="0 0 60 65" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M54.6 11.2L40.8 0 27.3 11.2 13.7 0 0 11.2V27l13.6-11.3 13.5 11.3 13.6-11.3L54.6 14V11.2zM27.3 26.6L13.7 15.3l-13.6 11.3v15.4l13.6-11.3 13.5 11.3 13.6-11.3 13.8 11.3V26.6l-13.7 11.4-13.6-11.4z" fill="currentColor"/>
                            </svg>
                        </div>
                    </header>

                    <main class="mt-6">
                        <div class="flex justify-center">
                            <p class="text-lg">Welcome to Laravel</p>
                        </div>
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>
