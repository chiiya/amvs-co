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
    @show

</head>
<body class="{{ $viewName }}">
    @include('modules.navigation')
    @yield('content')

    <!-- Scripts -->
    @section('scripts')
    <script src="/js/app.js"></script>
    @show
</body>
</html>
