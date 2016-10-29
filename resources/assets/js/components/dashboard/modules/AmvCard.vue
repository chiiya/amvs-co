<template>
    <div class="col-xs-12 col-lg-6">
        <div class="card dashboard__card horizontal">
            <div class="card-image">
                <img v-bind:src="amv.poster">
            </div>
            <div class="card-stacked">
                <div class="card-content">
                    <h2><a v-bind:href="'/user/' + user.name + '/' + amv.url">{{ amv.title }}</a></h2>
                    <p>{{ amv.date }}</p>
                    <p>{{ genres }}</p>
                </div>
                <div class="card-action">
                    <a class="button button--square button--info button--transparent">
                        Edit
                        <i class="material-icons high">edit</i>
                    </a>
                    <a class="button button--square button--info button--transparent"
                        @click="display('contests', amv)">
                        Contests
                        <i class="material-icons high">star</i>
                    </a>
                    <delete v-on:delete="deleteAmv" :url="'/amvs/'+amv.id"></delete>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import LoadingDeleteButton from './LoadingDeleteButton.vue';

    export default {

        props: ['amv', 'user', 'display'],

        computed: {
            /**
            * Concatenated genres of the AMV, separated by dash
            * @returns {String}
            */
            genres: function() {
                return this.amv.genres.map(elem => elem.name).join(' - ');
            }
        },

        components: { delete: LoadingDeleteButton },

        methods: {
            deleteAmv: function() {
                console.log("AMV deleted!");
            }
        }
    }
</script>