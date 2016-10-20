<template>
    <section>
        <h3 class="is-right">AMV Overview</h3>
        <div v-show="loading" class="preloader-wrapper active is-right">
            <div class="spinner-layer spinner-blue-only">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div>
                <div class="gap-patch">
                    <div class="circle"></div>
                </div>
                <div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
        </div>
        <amv v-for="amv in amvs" :amv="amv" :user="user"></amv>
    </section>
</template>

<script>
    import moment from 'moment';
    import DashAmvCard from './DashAmvCard.vue';

    export default {
        data() {
            return {
                loading: true,
                amvs: [],
            }
        },

        props: ['user'],

        components: {
            amv: DashAmvCard
        },

        mounted: function () {
            this.$nextTick(function () {
                this.loadData();
            })
        },

        watch: {
  	        user: function (val) {
                this.loadData();
            },
        },

        methods: {
            loadData() {
                this.$http.get('/api/amvs?user='+this.user.id).then((response) => {
                    this.loading = false;
                    this.amvs = response.body;
                    this.formatDate();
                }, (response) => {
                    console.log("Couldn't load AMVs");
                });
            },
            formatDate() {
                for (var i = 0; i < this.amvs.length; i++) {
                    var date = new Date(this.amvs[i].created_at);
                    this.amvs[i].date = moment(date).format('MMM YYYY');
                }
            }
        }
        
    }
</script>