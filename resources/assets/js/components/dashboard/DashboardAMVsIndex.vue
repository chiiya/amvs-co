<template>
    <section class="dashboard__index dashboard-container">
        <h3 class="is-right">AMV Overview</h3>
        <a class="button button--square button--primary z-depth-1" @click="display('create')">New AMV</a>
        <div v-show="loading" class="preloader-wrapper active is-right">
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
        <amv v-for="amv in amvs" :amv="amv" :user="user" :display="display"></amv>
    </section>
</template>

<script>
    import DashAmvCard from './DashAmvCard.vue';

    export default {
        props: ['user', 'display', 'amvs', 'loading'],

        components: {
            amv: DashAmvCard
        }
    }
</script>