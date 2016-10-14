<template>
    <div class="profile">
    <header>
        <div class="clear"></div>
        <div class="container" v-cloak>
            <p>My latest AMV:</p>
            <h1>Affinity</h1>
            <ul>
                <li>Drama</li>
                <li>Sep 2016</li>
            </ul>
            <a class="button icon play" href="#"><span>Watch Now</span></a>
        </div>
    </header>

    <main>
        <div class="container boxbg">
            <div class="clear"></div>
            <div class="catalogue">
                <h2 class="border">AMV Catalogue</h2>
                <div class="row active-with-click">
                    <div class="col-md-4 col-sm-6 col-xs-12" v-cloak v-for="amv in amvs">
                        <article class="material-card Blue">
                            <h2>
                                <span><a href="#">Affinity</a></span>
                                <strong>
                                <i class="fa fa-fw fa-star"></i>
                                Drama - Sep 2016
                            </strong>
                            </h2>
                            <div class="mc-content">
                                <div class="img-container">
                                    <img class="img-responsive" src="/img/poster/affinity.png">
                                </div>
                                <div class="mc-description">
                                    <p>
                                        <strong>Anime(s):</strong> LUL <br />
                                        <strong>Awards:</strong>
                                    </p>
                                    <ul>
                                        <li v-for="award in amv.awards">LEL</li>
                                    </ul>
                                </div>
                            </div>
                            <a class="mc-btn-action" v-on:click="animateCard">
                                <i class="fa fa-bars"></i>
                            </a>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </main>
    </div>
</template>

<script>
    import moment from 'moment';
    export default {
        data() {
            return {
                amvs: []
            }
        },
        mounted: function() {
            this.getAMVs();
        },
        methods: {
            getAMVs: function() {
                console.log("Getting AMVS...");
                this.$http.get('/api/amvs?user=1').then((response) => {
                    console.log(response.body);
                    this.amvs = response.body;
                    this.formatDateAndURL();
                }, (response) => {
                    console.log("Couldn't load AMVs");
                });
            },
            formatDateAndURL: function() {
                for (var i=0; i<this.amvs.length; i++) {
                    var date = new Date(this.amvs[i].created_at);
                    this.amvs[i].date = moment(date).format('MMM YYYY');
                    this.amvs[i].url = this.amvs[i].title.replace(" ", "_").toLowerCase();
                }
            },
            animateCard: function(event) {
                var card = event.currentTarget.parentElement;
                var icon = event.currentTarget.getElementsByTagName('i');
                icon[0].classList.add('fa-spin-fast');

                if (card.classList.contains('mc-active')) {
                    card.classList.remove('mc-active');

                    window.setTimeout(function() {
                        icon[0].classList.remove('fa-arrow-left');
                        icon[0].classList.remove('fa-spin-fast');
                        icon[0].classList.add('fa-bars');
                    }, 400);
                } else {
                    card.classList.add('mc-active');
                    window.setTimeout(function() {
                        icon[0].classList.remove('fa-bars');
                        icon[0].classList.remove('fa-spin-fast');
                        icon[0].classList.add('fa-arrow-left');
                    }, 400);
                }
            }
        }
    }
</script>


<style lang="sass">
@import "../../sass/base/_variables.scss";
body {
    background-color: $color-bg;
}
    header {
    height: 90%;
    min-height: 600px;
    background-image: url(/img/bgs/affinity_bg.jpg);
    background-size: cover;
    background-repeat: no-repeat;
    background-position: bottom right;
    .container {
        margin-top: 10%;
    }
    }
    .boxbg {
    min-height: 500px;
    background-color: $color-bg;
    border-radius: 0.5rem;
    margin-top: -70px;
}

@media (max-width: 800px) {
    .boxbg {
        border-radius: 0px;
    }
    .container {
        width: 100%;
    }
    header .container {
        margin: 30px;
    }
}
.catalogue {
    margin: 15px 30px;
}
</style>