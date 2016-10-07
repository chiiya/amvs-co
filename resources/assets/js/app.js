
require('./bootstrap');
import Router from 'vue-router';
import App from './components/App.vue';
import Signup from './components/Signup.vue';
import Login from './components/Login.vue';

Vue.use(Router);
Vue.component('app', App);
Vue.component('signup', Signup);
Vue.component('login', Login);

const UserPosts = { template: '<div>Posts</div>' }
const UserProfile = { template: '<div>Profile</div>' }

// Register app routes
const routes = [
  { path: '/', component: App,
      children: [
        {
          path: '',
          redirect: '/signup'
        },
        {
          path: '/signup',
          component: Signup
        },
        { path: '/login', 
          component: Login 
        }
      ]
    },
  { path: '/allaire', component: App},
  { path: '*', redirect: '/' }
]

var router = new Router({
  mode: 'history',
  routes
});

const app = new Vue({
  router
}).$mount('#app')
