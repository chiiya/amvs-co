<template>
    <section class="dashboard__contests dashboard-container is-right">
        <loading></loading>
        <div class="clearfix"></div>
        <div class="dashboard__contests" v-if="!loading">
            <p v-if="participatingEmpty" class="info">You're not participating in any contests right now. Head over to the <a href="#">Contest</a> section to see available contests that you can sign up for.</p>
            <p v-if="creatorEmpty" class="info">If you wish to create your own contest, you can apply for a <a href="#">creator position here</a>.</p>

            <div class="row">
            <div v-if="!creatorEmpty">
                <h3>Contests You've Created</h3>
                <div class="clearfix"></div>
                <div v-for="contest in contests.creator" class="col-xs-12 col-lg-6">
                    <div class="card dashboard__card card--blue">
                        <div class="card-content">
                            <h2>{{ contest.name }}</h2>
                            <p>{{ contest.year }}</p>
                            <div class="clearfix"></div>
                            <router-link
                                :to="'/dashboard/contests/' + contest.id"
                                class="button button--square button--white button--transparent">
                                Details
                                <i class="material-icons high">description</i>
                            </router-link>
                            <router-link
                                :to="'/dashboard/contests/' + contest.id + '/edit'" 
                                class="button button--square button--white button--transparent">
                                Edit
                                <i class="material-icons high">edit</i>
                            </router-link>
                            <router-link
                                :to="'/dashboard/contests/' + contest.id + '/entries'" 
                                class="button button--square button--white button--transparent">
                                Entries
                                <i class="material-icons high">movie</i>
                            </router-link>
                            <a class="button button--square button--white button--transparent">
                                Delete
                                <i class="material-icons low">close</i>  
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            </div>

            <div class="row">
            <div v-if="!adminEmpty">
                <h3>Contests: Admin</h3>
                <div class="clearfix"></div>
                <div v-for="contest in contests.admin" class="col-xs-12 col-lg-6">
                    <div class="card dashboard__card card--info">
                        <div class="card-content">
                            <h2>{{ contest.name }}</h2>
                            <p>{{ contest.year }}</p>
                            <div class="clearfix"></div>
                            <router-link
                                :to="'/dashboard/contests/' + contest.id"
                                class="button button--square button--info button--transparent">
                                Details
                                <i class="material-icons high">description</i>
                            </router-link>
                            <router-link
                                :to="'/dashboard/contests/' + contest.id + '/edit'" 
                                class="button button--square button--info button--transparent">
                                Edit
                                <i class="material-icons high">edit</i>
                            </router-link>
                            <router-link
                                :to="'/dashboard/contests/' + contest.id + '/entries'" 
                                class="button button--square button--info button--transparent">
                                Entries
                                <i class="material-icons high">movie</i>
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
            </div>

            <div class="row">
            <div v-if="!judgeEmpty">
                <h3>Contests: Judge</h3>
                <div class="clearfix"></div>
                <div v-for="contest in contests.judge" class="col-xs-12 col-lg-6">
                    <div class="card dashboard__card card--info">
                        <div class="card-content">
                            <h2>{{ contest.name }}</h2>
                            <p>{{ contest.year }}</p>
                            <div class="clearfix"></div>
                            <router-link
                                :to="'/dashboard/contests/' + contest.id"
                                class="button button--square button--info button--transparent">
                                Details
                                <i class="material-icons high">description</i>
                            </router-link>
                            <router-link
                                :to="'/dashboard/contests/' + contest.id + '/entries'" 
                                class="button button--square button--info button--transparent">
                                Entries
                                <i class="material-icons high">movie</i>
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
            </div>

            <div class="row">
            <div v-if="!participatingEmpty">
                <h3>Contests: Participating</h3>
                <div class="clearfix"></div>
                <div v-for="contest in contests.participant" class="col-xs-12 col-lg-6">
                    <div class="card dashboard__card card--info">
                        <div class="card-content">
                            <h2>{{ contest.name }}</h2>
                            <p>{{ contest.year }}</p>
                            <div class="clearfix"></div>
                            <router-link
                                :to="'/dashboard/contests/' + contest.id"
                                class="button button--square button--info button--transparent">
                                Details
                                <i class="material-icons high">description</i>
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </section>
</template>

<script>
    import LoadingSpinner from '../modules/LoadingSpinner.vue'
    import { setNav } from '../../../util/functions';

    export default {

        components: {
            loading: LoadingSpinner
        },

        beforeMount: function() {
            // Set Breadcrumbs
            this.$store.commit('SET_PARENT', {
                title: 'Contests',
                path: '/dashboard/contests'
            });
        },

        mounted: function() {
            this.loadData();
            setNav(2);
        },

        computed: {
            /**
             * List of all contests the user is participating in.
             * @returns {Object}
             */
            contests() {
                return this.$store.state.userContests;
            },

            /**
             * Current loading status (loading is true if data is being loaded asynchronously)
             * @returns {Boolean}
             */
            loading() {
                return this.$store.state.loading;
            },

            participatingEmpty() {
                return this.contests.participant && this.contests.participant.length === 0;
            },

            judgeEmpty() {
                return this.contests.judge && this.contests.judge.length === 0;
            },

            adminEmpty() {
                return this.contests.admin && this.contests.admin.length === 0;
            },

            creatorEmpty() {
                return this.contests.creator && this.contests.creator.length === 0;
            }
        },

        methods: {
            loadData() {
                this.$store.dispatch('FETCH_USER_CONTESTS')
            }
        }
    }
</script>