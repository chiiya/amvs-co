import AMVCard from './components/AMVCard.vue';
import moment from 'moment';

const vm = new Vue({
    el: '#app',
    data: {
        user: {},
        amvs: []
    },

    components: {
        amvcard: AMVCard
    },

    mounted: function () {
        this.$nextTick(function () {
            this.loadData();
        })
    },

    methods: {
        loadData() {
            this.$http.get('/api/users/' + document.querySelector("meta[name='user']").getAttribute('id')).then((response) => {
                this.user = response.body;

                this.$http.get('/api/amvs?user='+this.user.id).then((response) => {
                    this.amvs = response.body;
                    this.formatDate();
                }, (response) => {
                    console.log("Couldn't load AMVs");
                });

            }, (response) => {
                console.log("Couldn't load user info.");
            });
        },
        formatDate() {
            for (var i = 0; i < this.amvs.length; i++) {
                var date = new Date(this.amvs[i].created_at);
                this.amvs[i].date = moment(date).format('MMM YYYY');
            }
        }
    }
})