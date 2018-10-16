<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <script src="{{ mix('js/app.js') }}" defer></script>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body class="flex h-full font-sans antialiased text-black leading-tight bg-grey-lighter">
<div id="app" class="flex-1 block md:flex flex-col border-t-4 border-indigo-lighter">
    <div class="flex-1 flex flex-col pb-4">
        @yield('body')
    </div>
    <div class="border-t cursor-default mb-6 pt-2 text-center text-grey text-xs">
        &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}
    </div>
</div>
</body>
</html>
