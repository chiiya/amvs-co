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

<style lang="sass">
  @import "../../sass/base/_variables.scss";

  div.form {
        -webkit-backface-visibility: hidden;
        -moz-backface-visibility: hidden;
        position: relative;
        overflow: hidden !important;
        z-index:1;
    }
  
  p.error {
    color: red;
  }
</style>