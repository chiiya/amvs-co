import store from './store'
import router from './router'

const vm = new Vue({
    store,
    router,
    el: '#app',

    computed: {
        /**
         * Breadcrumbs
         */
        parent() {
            return this.$store.state.parent;
        }
    },

    mounted: function () {
        this.loadData();
    },

    methods: {
        /**
         * Load user and AMV data and store it in Vuex
         */
        loadData() {
            this.$store.dispatch('FETCH_USER')
                .then((response) => {
                    this.$store.dispatch('FETCH_AMVS', response);
                });
        }
    }
})