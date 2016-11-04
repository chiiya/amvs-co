<template>
    <section class="dashboard__index dashboard-container is-right">
        <h3>AMV Overview</h3>
        <a class="button button--square button--primary z-depth-1" @click="display('create')">New AMV</a>
        <div v-show="loading" class="preloader-wrapper active">
            <div class="spinner-layer spinner-blue-only">
                <div class="circle-clipper left">
                    <div class="circle"></div>
                </div>
                <div class="gap-patch">
                    <div class="circle"></div>
                </div>
                <div class="circle-clipper right">
                    <div class="circle"></div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <amv v-for="amv in amvs" :amv="amv" :display="display"></amv>
    </section>
</template>

<script>
    import AmvCard from '../modules/AmvCard.vue';

    export default {
        props: ['display'],

        components: {
            amv: AmvCard
        },

        computed: {
            amvs() {
                return this.$store.state.amvs;
            },
            loading() {
                return this.$store.state.loading;
            }
        }
    }
</script>