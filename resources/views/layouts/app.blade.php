<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Test GSI</title>

        <!-- Bootstrap -->
        <link href="{{ asset('css/app.css')}}" rel="stylesheet">

        <!-- Additional styles -->
        @stack('additional-css')
        <!-- Additional script -->
        @stack('additional-js-head')

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        @yield('content')

        @if($alert = Session::get('alert'))
            <input type="hidden" name="alert[type]" value="@if(isset($alert['type'])){{ $alert['type'] }}@endif">
            <input type="hidden" name="alert[message]" value="@if(isset($alert['message'])){{ $alert['message'] }}@endif">
            <input type="hidden" name="alert[timer]" value="@if(isset($alert['timer'])){{ $alert['timer'] }}@endif">
            <input type="hidden" name="alert[is_html]" value="@if(isset($alert['is_html'])){{ $alert['is_html'] }}@endif">
        @endif
    </body>

    <!-- jQuery -->
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('additional-js-end')
</html>
