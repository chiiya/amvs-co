<template>
    <div>
        <header class="dashboard__title valign-wrapper">
            <h2 class="valign is-right">Dashboard</h2>
        </header>
        <p class="is-right" v-show="!loading">Hello {{ name }}. Nothing new! Start following users to receive updates on new AMVs.</p>
        <loading class="is-right"></loading>
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
        components: {
            loading: LoadingSpinner
        }
    }
</script>