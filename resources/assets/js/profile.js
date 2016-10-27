import AMVCard from './components/AMVCard.vue';

const vm = new Vue({
    el: '#app',
    data: {
        user: {},
        amvs: [],
        months: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]
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
            for (let i=0; i < this.amvs.length; i++) {
                const date = new Date(this.amvs[i].created_at);
                this.amvs[i].date = this.months[date.getMonth()] + ' ' + date.getFullYear();
            }
        }
    }
})