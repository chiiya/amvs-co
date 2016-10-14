<!doctype html>
<html class="home">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>{{ $user->name }} - AMV Catalogue</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <meta name="user" id="{{ $user->id }}">

    <link rel="stylesheet" href="/css/normalize.min.css">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/app.css">

</head>

<body class="home" id="home">
<div id="app">
<latestamv :amvs="amvs" :user="user"></latestamv>
@verbatim
<main>
    <div class="container boxbg">
        <div class="clear"></div>
        <div class="catalogue">
            <h2 class="border">AMV Catalogue</h2>
            <div class="row active-with-click">
                <amvcard v-for="amv in amvs" :amv="amv" :user="user"></amvcard>
            </div>
        </div>
    </div>
</main>
</div>

@endverbatim

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment.min.js"></script>
<script>
    window.Laravel = { csrfToken: '{{ csrf_token() }}' };
</script>
<script src="/js/app.js"></script>
<script src="/js/profile.js"></script>
</body>

</html>
