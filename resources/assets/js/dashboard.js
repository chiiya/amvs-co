import Overview from './components/dashboard/layouts/Overview.vue';
import AMVs from './components/dashboard/layouts/AMVs.vue';
import EditProfile from './components/dashboard/layouts/EditProfile.vue';

const vm = new Vue({
    el: '#app',
    data: {
        currentView: 'overview',
        user: {},
        amvs: [],
        months: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        loading: true
    },

    components: {
        overview: Overview,
        amvs: AMVs,
        profile: EditProfile
    },

    mounted: function () {
        this.$nextTick(function () {
            this.loadData();
        })
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
            } else {
                
            }  
        },
        loadData() {
            let id = document.querySelector("meta[name='user']").getAttribute('id');
            this.$http.get(`/users/${id}`).then((response) => {
                this.user = response.body;
                this.$http.get('/api/amvs?user='+this.user.id).then((response) => {
                    this.loading = false;
                    this.amvs = response.body;
                    this.formatDate();
                }, (response) => {
                    console.log("Couldn't load AMVs");
                });
            }, (response) => {
                console.log("Couldn't load user info.");
            });
        },
        updateAvatar(path) {
            this.user.avatar = path;
            const avatars = document.getElementsByClassName('avatar');
            for (let i=0; i<avatars.length; i++) {
                avatars[i].src = path;
            }
        },
        formatDate() {
            for (let i = 0; i < this.amvs.length; i++) {
                const date = new Date(this.amvs[i].created_at);
                this.amvs[i].date = this.months[date.getMonth()] + ' ' + date.getFullYear();
            }
        },
        addAmv(amv) {
            const date = new Date(amv.created_at);
            amv.date = this.months[date.getMonth()] + ' ' + date.getFullYear();
            this.amvs.push(amv);
        }
    }
})