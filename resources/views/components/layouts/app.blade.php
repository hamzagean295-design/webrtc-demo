<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <title>LaraConsult</title>
    </head>
    <body>
        <header>
            @if(auth()->user()->role == 'patient')
                @include('components.layouts.partials.patient-navigation')
            @else
                @include('components.layouts.partials.medecin-navigation')
            @endif
        </header>
        {{ $slot  }}
    </body>
</html>
