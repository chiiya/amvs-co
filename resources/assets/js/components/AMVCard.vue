<template>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <article class="material-card Blue">
            <h2>
                <span><a v-bind:href="'/user/' + user.name + '/' + amv.url">{{ amv.title }}</a></span>
                <span>{{ genres}}</span>
                <span>{{ amv.date }}</span>
            </h2>
            <div class="mc-content">
                <div class="img-container">
                    <img v-if="amv.poster" class="img-responsive" v-bind:src="'/img/poster/' + amv.poster">
                    <img v-else class="img-responsive" src="/img/poster/placeholder.jpg">
                </div>
                <div class="mc-description">
                    <p>
                        <strong>Anime(s):</strong> {{ amv.animes }} <br />
                        <strong>Awards:</strong>
                    </p>
                    <ul>
                        <li v-for="contest in amv.contests">{{ contest.name + ' ' + contest.year + ' ' + contest.award }}</li>
                    </ul>
                    <a class="button button--rounded button--transparent" v-bind:href="'/user/' + user.name + '/' + amv.url"><i class="fa fa-fw fa-play-circle-o"></i> Watch Now</span></a>
                </div>
            </div>
            <a class="mc-btn-action" v-on:click="animateCard">
                <i class="material-icons card-menu">menu</i>
            </a>
        </article>
    </div>
</template>

<script>
    export default {
        props: ['user', 'amv'],

        computed: {
            genres: function() {
                if (this.amv.genres) {
                    return this.amv.genres.map(function(elem) { return elem.name; }).join(' - ');
                } else return "";
            }
        },

        methods: {
            animateCard(event) {
                var card = event.currentTarget.parentElement;
                var icon = event.currentTarget.querySelector('i.card-menu');
                console.log(icon);
                icon.classList.add('fa-spin-fast');

                if (card.classList.contains('mc-active')) {
                    card.classList.remove('mc-active');

                    window.setTimeout(function () {
                        icon.classList.remove('fa-spin-fast');
                        icon.innerHTML = "menu";
                    }, 400);
                } else {
                    card.classList.add('mc-active');
                    window.setTimeout(function () {
                        icon.classList.remove('fa-spin-fast');
                        icon.innerHTML = "arrow_back";
                    }, 400);
                }
            }
        }
    }
</script>