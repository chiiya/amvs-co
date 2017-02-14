<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title') - AMVs.CO</title>

    @section('meta')
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">    
    <meta name="csrf_token" content="{{ csrf_token() }}">
    @show
    
    <!-- Styles -->
    @section('styles')
    <link rel="stylesheet" href="/css/app.css">
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    @show

</head>
<body class="{{ $viewName }}">
    @include('modules.navigation')
    @yield('content')

    <!-- Scripts -->
    @section('scripts')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.8/js/materialize.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".button-collapse").sideNav();
        });
    </script>
    @show
</body>
</html>
