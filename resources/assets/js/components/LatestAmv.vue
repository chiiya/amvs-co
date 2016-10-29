<template>
    <header>
        <div class="clear"></div>
        <div class="container" v-cloak>
            <template v-if="latestExists()">

            <p>{{ userName + "'s" }} latest AMV:</p>
            <h1>{{ latest.title }}</h1>
        
            <ul>
                <li>{{ latest.genre }}</li>
                <li>{{ latest.date }}</li>
            </ul>
            <a class="button icon play" v-bind:href="'/user/' + userName + '/' + latest.url"><span>Watch Now</span></a>
            </template>
            <template v-else>
                <h1>{{ userName }}</h1>
                <p>No AMVs uploaded yet for this user.</p>
            </template>
        </div>
    </header>
</template>

<script>
    export default {
        data() {
            return {
                userName: '',
                latest : {}
            }
        },

        props: ['user', 'amvs'],
        watch: {
  	        user: function (val) {
    	        this.userName = val.name;
            },
            amvs: function(val) {
                this.latest = val[val.length - 1];
                if (typeof this.latest !== 'undefined' && this.latest.bg) {
                    var bg = `linear-gradient(rgba(0, 0, 0, 0.5),rgba(0, 0, 0, 0.5)),url(/img/bgs/${this.latest.bg}) bottom right / cover no-repeat`;
                    document.querySelector('header').style.background = bg;
                } else if (typeof this.latest == 'undefined') {
                    var header = document.querySelector('header');
                    header.style.backgroundImage = 'linear-gradient(rgba(0, 0, 0, 0.5),rgba(0, 0, 0, 0.5)),url(/img/bgs/default.jpg)';
                    header.style.backgroundSize = 'cover';
                }
            }
        },
        methods: {
            latestExists() {
                /* Check if latest is defined AND is not an empty object */
                return typeof this.latest !== 'undefined' && !(Object.keys(this.latest).length === 0 && this.latest.constructor === Object);
            }
        }
    }
</script>