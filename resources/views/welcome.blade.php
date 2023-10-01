<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Google Sheets</title>
        <link rel="stylesheet" href="/public/css/all.css">
        <script>
            (function (){
                window.Laravel = {
                    csrfToken: '{{ csrf_token() }}'
                }
            })();
        </script>
    </head>
    <body class="antialiased">
        <div id="app">
            <mainapp :user="false"></mainapp>
        </div>
    </body>
    <script src="{{mix('/js/app.js')}}"></script>
</html>
