<!doctype html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>AnimeMusicVideo Catalogue</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="css/normalize.min.css">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <link rel="stylesheet" href="css/app.css">

</head>

<body class="start">
  <div id="app">
    <header>
      <div class="clear"></div>
      <div class="container">
        <h1>AMVS.CO</h1>
        <h2>Anime Music Video Catalogue</h2>
      </div>
    </header>
    <div class="form">
      <ul>
        <li><a href="#" v-on:click="display('login')">Login</a></li>
        <li><a href="#" v-on:click="display('signup')">Signup</a></li>
      </ul>
      <keep-alive>
        <transition name="fade"
          enter-active-class="animated fadeIn"
          leave-activeclass="animated fadeOut"
          mode="out-in">
        <component :is="currentView" :display.sync="display">
        </component>
        </transition>
      </keep-alive>
    </div>
  </div>
  <script src="/js/app.js"></script>
  <script src="/js/home.js"></script>
</body>

</html>