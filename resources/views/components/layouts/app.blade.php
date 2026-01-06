<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="user-id" content="{{ Auth::id() }}">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <title>LaraConsult {{ Auth::check() ? auth()->user()->role : '' }} </title>
    </head>
    <body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
    <header class="bg-white shadow-md dark:bg-gray-800">
        @include('components.layouts.partials.shared-navigation')
    </header>

    <!-- Page Content -->
    <main>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Notification Container -->
    <div id="container" class="fixed top-5 right-1 z-50 w-full max-w-sm space-y-4">
        <!-- Notifications will be dynamically injected here -->
    </div>
</body>
</html>
