import Router from 'vue-router'

Vue.use(Router);

import Overview from '../components/dashboard/layouts/Overview.vue'
import EditProfile from '../components/dashboard/layouts/EditProfile.vue'
import AMVsIndex from '../components/dashboard/layouts/AMVsIndex.vue'
import AMVsCreate from '../components/dashboard/layouts/AMVsCreate.vue'
import AMVsEdit from '../components/dashboard/layouts/AMVsEdit.vue'
import AMVsContests from '../components/dashboard/layouts/AMVsContests.vue'
import Contests from '../components/dashboard/layouts/Contests.vue'

export default new Router({
  mode: 'history',
  routes: [
    { path: '/dashboard', component: Overview },
    { path: '/dashboard/amvs', component: AMVsIndex },
    { path: '/dashboard/amvs/create', component: AMVsCreate },
    { path: '/dashboard/amvs/:id(\\d+)', component: AMVsEdit },
    { path: '/dashboard/amvs/:id(\\d+)/contests', component: AMVsContests },
    { path: '/dashboard/contests', component: Contests },
    { path: '/dashboard/profile', component: EditProfile },
    { path: '*', redirect: '/dashboard' }
  ]
});