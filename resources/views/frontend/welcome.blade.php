<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $shared['title'] }}</title>


        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
        <link rel="shortcut icon" type="image/x-icon" href="{{ url('assets/images/'.$shared['logo'])}}" />
        <style>
            body, html {
                height: 100%;
                margin: 0;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .center-container {
                text-align: center;
            }

        </style>
  
    </head>
    <body class="antialiased text-align: center">
    <img src="{{ url('assets/images/'.$shared['logo'])}}" style="width: 755px !important; height:676px !important; " >

    </body>
</html>
