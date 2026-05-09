<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body  class="bg-gray-50">
        {{ $slot }}
    </body>
</html>
