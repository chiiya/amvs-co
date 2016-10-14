<template>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <article class="material-card Blue">
            <h2>
                <span><a v-bind:href="'/user/' + user.name + '/' + amv.url">{{ amv.title }}</a></span>
                <strong>
                    <i class="fa fa-fw fa-star"></i>
                    {{ amv.genre}} - {{ amv.date }}
                </strong>
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
                    <a class="button button-small" v-bind:href="'/user/' + user.name + '/' + amv.url"><i class="fa fa-fw fa-play-circle-o"></i> Watch Now</span></a>
                </div>
            </div>
            <a class="mc-btn-action" v-on:click="animateCard">
                <i class="fa fa-bars"></i>
            </a>
        </article>
    </div>
</template>

<script>
    export default {
        props: ['user', 'amv'],
        methods: {
            animateCard(event) {
                var card = event.currentTarget.parentElement;
                var icon = event.currentTarget.getElementsByTagName('i');
                icon[0].classList.add('fa-spin-fast');

                if (card.classList.contains('mc-active')) {
                    card.classList.remove('mc-active');

                    window.setTimeout(function () {
                        icon[0].classList.remove('fa-arrow-left');
                        icon[0].classList.remove('fa-spin-fast');
                        icon[0].classList.add('fa-bars');
                    }, 400);
                } else {
                    card.classList.add('mc-active');
                    window.setTimeout(function () {
                        icon[0].classList.remove('fa-bars');
                        icon[0].classList.remove('fa-spin-fast');
                        icon[0].classList.add('fa-arrow-left');
                    }, 400);
                }
            }
        }
    }
</script>