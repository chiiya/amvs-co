import DashboardOverview from './components/dashboard/DashboardOverview.vue';
import DashboardAMVs from './components/dashboard/DashboardAMVs.vue';

const vm = new Vue({
    el: '#app',
    data: {
        currentView: 'overview',
        user: {}
    },

    components: {
        overview: DashboardOverview,
        amvs: DashboardAMVs
    },

    mounted: function () {
        this.$nextTick(function () {
            this.loadData();
        })
    },

    methods: {
        display(view, event) {
            this.currentView = view;
            const target = event.currentTarget;
            const navelements = document.getElementsByClassName('elem');
            for (let i=0; i<navelements.length; i++) {
                navelements[i].classList.remove('active');
            }
            target.classList.add('active');
        },
        loadData() {
            this.$http.get('/api/users/' + document.querySelector("meta[name='user']").getAttribute('id')).then((response) => {
                this.user = response.body;
            }, (response) => {
                console.log("Couldn't load user info.");
            });
        }
    }
})