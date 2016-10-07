<!doctype html>
<html class="home">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>Allaire AMV Catalogue</title>
    <meta name="description" content="A catalogue of all my AMVs">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="apple-touch-icon.png">

    <link rel="stylesheet" href="/css/normalize.min.css">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/app.css">

</head>
<body>
  <div id="app">
    <router-link to="/allaire">Allaire Profile</router-link>
    <router-view></router-view>
  </div>
  
  <script>
    window.Laravel = { csrfToken: '{{ csrf_token() }}' };
</script>
  <script src="/js/app.js"></script>
</body>
</html>