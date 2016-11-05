<template>
    <div class="is-right dashboard__overview">
        <p v-show="!loading">Hello {{ name }}. Nothing new! Start following users to receive updates on new AMVs.</p>
        <loading></loading>
    </div>
</template>

<script>
    import LoadingSpinner from '../modules/LoadingSpinner.vue'

    export default {
        computed: {
            name() {
                return this.$store.state.user.name;
            },
            loading() {
                return this.$store.state.loading;
            }
        },

        beforeMount: function() {
            this.$store.commit('SET_PARENT', {
                title: 'Overview',
                path: '/dashboard'
            });
            const navelements = document.getElementsByClassName('elem');
            for (let i=0; i<navelements.length; i++) {
                navelements[i].classList.remove('active');
            }
            navelements[0].classList.add('active');
        },

        components: {
            loading: LoadingSpinner
        }
    }
</script>