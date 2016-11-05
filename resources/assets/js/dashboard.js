import store from './store'
import router from './router'
import vueSmoothScroll from 'vue-smoothscroll'

Vue.use(vueSmoothScroll);

router.beforeEach((to, from, next) => {
  const navelements = document.getElementsByClassName('elem');
  for (let i=0; i<navelements.length; i++) {
      navelements[i].classList.remove('active');
  }
  let active = -1;
  if (to.path === '/dashboard') {
    active = 0;
  } else if (to.path === '/dashboard/profile') {
    active = -1;
  } else {
    active = 1;
  }
  if (active > -1) {
    navelements[active].classList.add('active');
  }
  next();
});

const vm = new Vue({
    store,
    router,
    el: '#app',

    computed: {
        parent() {
            return this.$store.state.parent;
        },
        overviewClass() {
            return {
                'active': this.$store.state.overviewClass
            }
        },
        amvClass() {
            return {
                'active': this.$store.state.amvClass
            }
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
            const id = document.querySelector("meta[name='user']").getAttribute('id');
            this.$store.dispatch('FETCH_USER');
            this.$store.dispatch('FETCH_AMVS', { id });
        }
    }
})