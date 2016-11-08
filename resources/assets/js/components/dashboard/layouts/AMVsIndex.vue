<template>
    <section class="dashboard__index dashboard-container is-right">
        <h3>AMV Overview</h3>
        <router-link 
            to="/dashboard/amvs/create" 
            class="create button button--square button--primary z-depth-1">
            New AMV
        </router-link>
        <loading></loading>
        <div class="clearfix"></div>
        <amv v-for="amv in amvs" :amv="amv"></amv>
    </section>
</template>

<script>
    import AmvCard from '../modules/AmvCard.vue';
    import LoadingSpinner from '../modules/LoadingSpinner.vue'
    import { setNav } from '../../../util/functions';

    export default {

        components: {
            amv: AmvCard,
            loading: LoadingSpinner
        },

        beforeMount: function() {
            // Set Breadcrumbs
            this.$store.commit('SET_PARENT', {
                title: 'AMVs',
                path: '/dashboard/amvs'
            });
        },

        mounted: function() {
            setNav(1);
        },

        computed: {
            /**
             * List of all amvs by this user.
             * @returns {Array}
             */
            amvs() {
                return this.$store.getters.amvs;
            },

            /**
             * Current loading status (loading is true if data is being loaded asynchronously)
             * @returns {Boolean}
             */
            loading() {
                return this.$store.state.loading;
            }
        }
    }
</script>