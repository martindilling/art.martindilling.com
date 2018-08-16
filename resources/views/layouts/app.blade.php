<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <script src="https://gumroad.com/js/gumroad-embed.js"></script>
    {{--<script src="{{ mix('js/app.js') }}" defer></script>--}}
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet"
          type="text/css"
          href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.css"/>
    <script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.1.0/cookieconsent.min.js"></script>
    <script>
        window.addEventListener("load", function () {
            window.cookieconsent.initialise({
                "palette": {
                    "popup": {
                        "background": "#ffffff"
                    },
                    "button": {
                        "background": "#6574cd"
                    }
                },
                "content": {
                    "message": "This website require cookies for the Gumroad shop widgets to work."
                }
            })
        });
    </script>
</head>
<body class="flex h-full font-sans antialiased text-black leading-tight bg-grey-lighter">
<div id="app" class="flex-1 block md:flex flex-col border-t-4 border-indigo-lighter">
    <div class="flex-1 flex flex-col pb-4">
        @yield('body')
    </div>
    <div class="mb-6 text-xs text-grey text-center">
        &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}
    </div>
</div>
</body>
</html>
