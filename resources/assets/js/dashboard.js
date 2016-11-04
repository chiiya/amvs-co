import store from './store'
import vueSmoothScroll from 'vue-smoothscroll'
import Overview from './components/dashboard/layouts/Overview.vue'
import EditProfile from './components/dashboard/layouts/EditProfile.vue'
import AMVs from './components/dashboard/layouts/AMVs.vue'

Vue.use(vueSmoothScroll);

const vm = new Vue({
    store, 
    el: '#app',
    data: {
        currentView: 'overview'
    },

    components: {
        overview: Overview,
        profile: EditProfile,
        amvs: AMVs
    },

    mounted: function () {
        this.loadData();
    },

    methods: {
        display(view, event) {
            this.currentView = view;
            const navelements = document.getElementsByClassName('elem');
            for (let i=0; i<navelements.length; i++) {
                    navelements[i].classList.remove('active');
                }
            if (event) {
                const target = event.currentTarget;
                target.classList.add('active');
            }
        },

        /**
         * Load user and AMV data and store it in Vuex
         */
        loadData() {
            const id = document.querySelector("meta[name='user']").getAttribute('id');
            this.$store.dispatch('FETCH_USER', { id });
            this.$store.dispatch('FETCH_AMVS', { id });
        }
    }
})