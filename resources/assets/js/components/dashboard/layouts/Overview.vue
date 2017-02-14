<template>
    <div class="is-right dashboard__overview">
        <p v-show="!loading" class="info">Hello {{ name }}. Nothing new! Start following users to receive updates on new AMVs.</p>
        <loading></loading>
    </div>
</template>

<script>
    import LoadingSpinner from '../modules/LoadingSpinner.vue'
    import { setNav } from '../../../util/functions';

    export default {
        computed: {
            /**
             * Get the currently authenticated user name
             * @returns {String}
             */
            name() {
                return this.$store.state.user.name;
            },

            /**
             * Current loading status (loading is true if data is being loaded asynchronously)
             * @returns {Boolean}
             */
            loading() {
                return this.$store.state.loading;
            }
        },

        beforeMount: function() {
            // Set Breadcrumbs
            this.$store.commit('SET_PARENT', {
                title: 'Overview',
                path: '/dashboard'
            });
        },

        mounted: function() {
            setNav(0);
        },

        components: {
            loading: LoadingSpinner
        }
    }
</script>