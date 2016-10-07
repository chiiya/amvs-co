<template>
  <div class="home">
    <header>
      <div class="clear"></div>
      <div class="container">
        <h1>AMVS.CO</h1>
        <h2>Anime Music Video Catalogue</h2>
      </div>
    </header>
    <div class="form">
      <transition 
        name="fade"
        enter-active-class="animated fadeIn"
      leave-activeclass="animated fadeOut"
        mode="out-in">
    <router-view></router-view>
      </transition>
    </div>
  </div>
</template>

<script>
</script>


<style lang="sass">
  @import "../../sass/base/_variables.scss";

  div.form {
        -webkit-backface-visibility: hidden;
        -moz-backface-visibility: hidden;
        position: relative;
        overflow: hidden !important;
        z-index:1;
    }
  
  body {
    background-color: #100b0f;
  }
  
  header {
    .container {
      margin: 0 auto;
      text-align: center;
    }
  }
  
  a {
    text-align: center;
    display: inline-block;
    margin-top: 15px;
    color: darken($color-primary, 30%);
    &:hover,
    &:active,
    &:focus,
    &:visited {
      text-decoration: none;
      color: darken($color-primary, 15%);
    }
  }
  
  header {
    .container {
      margin-top: 5%;
    }
  }
  
  .row {
    margin-bottom: 5%;
  }
  
  p {
    color: $color-text-light;
  }

  p.error {
    color: red;
  }
</style>