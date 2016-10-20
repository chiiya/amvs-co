<template>
    <div class="col-xs-12">
        <div class="card horizontal is-right">
            <div class="card-image">
                <img v-bind:src="'/img/poster/' + amv.poster">
            </div>
            <div class="card-stacked">
                <div class="card-content">
                    <h2><a v-bind:href="'/user/' + user.name + '/' + amv.url">{{ amv.title }}</a></h2>
                    <p>{{ genres }} / {{ amv.date }}</p>
                    <p>Anime: {{ amv. animes }}</p>
                </div>
                <div class="card-action">
                    <a href="#"><i class="material-icons">edit</i> Edit</a>
                    <a href="#"><i class="material-icons">delete</i> Delete</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['amv', 'user'],

        computed: {
            genres: function() {
                return this.amv.genres.map(function(elem) { return elem.name; }).join(' - ');
            }
        }
    }
</script>