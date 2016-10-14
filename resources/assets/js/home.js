import Signup from './components/Signup.vue';
import SignupComplete from './components/SignupComplete.vue';
import Login from './components/Login.vue';

const vm = new Vue({
  el: '#app',
  data: {
    currentView: 'login',
  },
  components: {
    signup: Signup,
    login: Login,
  },
  methods: {
    display(view) {
      this.currentView = view;
    },
  }
})