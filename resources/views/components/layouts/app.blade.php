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
    <body>
        <header>
            @if(auth()->user()->role == 'patient')
                @include('components.layouts.partials.patient-navigation')
            @else
                @include('components.layouts.partials.medecin-navigation')
            @endif
        </header>
        <main>
            {{ $slot  }}
        </main>
        <div id="container">

        </div>
    </body>
</html>
