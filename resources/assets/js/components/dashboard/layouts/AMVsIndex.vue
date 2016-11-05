<template>
    <section class="dashboard__index dashboard-container is-right">
        <h3>AMV Overview</h3>
        <router-link 
            to="/dashboard/amvs/create" 
            class="button button--square button--primary z-depth-1">
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

    export default {

        components: {
            amv: AmvCard,
            loading: LoadingSpinner
        },

        beforeMount: function() {
            this.$store.commit('SET_PARENT', {
                title: 'AMVs',
                path: '/dashboard/amvs'
            });
        },

        computed: {
            amvs() {
                return this.$store.getters.amvs;
            },
            loading() {
                return this.$store.state.loading;
            }
        }
    }
</script>