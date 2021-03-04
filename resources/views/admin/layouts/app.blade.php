<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="images/favicon.ico" type="image/ico" />

    <title>Test GSI! | </title>

    <!-- Bootstrap -->
    <link href="{{ asset('css/app.css')}}" rel="stylesheet">

    <!-- Additional styles -->
    @stack('additional-css')
    <!-- Additional script -->
    @stack('additional-js-head')
</head>

<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col">
                @include('layouts._sidebar')
            </div>

            <!-- top navigation -->
            <div class="top_nav">
                <nav class="nav_menu justify-content-between">
                    <div class="nav toggle">
                        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                    </div>
                </nav>
            </div>
            <!-- /top navigation -->

            <!-- page content -->
            <div class="right_col" role="main">
                @yield('content')
            </div>
            <!-- /page content -->

            <!-- footer content -->
            <footer>
                <div class="pull-right">
                    Gentelella - Bootstrap Admin Template by <a href="https://colorlib.com">Colorlib</a>
                </div>
                <div class="clearfix"></div>
            </footer>
            <!-- /footer content -->

            @if($alert = Session::get('alert'))
                <input type="hidden" name="alert[type]" value="@if(isset($alert['type'])){{ $alert['type'] }}@endif">
                <input type="hidden" name="alert[message]" value="@if(isset($alert['message'])){{ $alert['message'] }}@endif">
                <input type="hidden" name="alert[timer]" value="@if(isset($alert['timer'])){{ $alert['timer'] }}@endif">
                <input type="hidden" name="alert[is_html]" value="@if(isset($alert['is_html'])){{ $alert['is_html'] }}@endif">
            @endif
        </div>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('additional-js-end')
</body>

</html>
