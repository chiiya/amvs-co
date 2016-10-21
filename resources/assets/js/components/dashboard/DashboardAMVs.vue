<template>
    <div>
        <header class="dashboard__title valign-wrapper">
            <a href="#" class="breadcrumb" @click="display('index')">My AMVs</a>
            <a href="#" class="breadcrumb">{{ currentView }}</a>
        </header>
        <keep-alive>
        <transition 
            name="fade" 
            enter-active-class="animated fadeIn" 
            leave-activeclass="animated fadeOut" 
            mode="out-in"
        >
            <component :is="currentView" :user="user" :amv="amv" :display.sync="display">
            </component>
        </transition>
        </keep-alive>
    </div>
</template>

<script>
    import DashboardAMVsIndex from './DashboardAMVsIndex.vue';
    import DashboardAMVsCreate from './DashboardAMVsCreate.vue';
    import DashboardAMVsContests from './DashboardAMVsContests.vue';

    export default {
        data() {
            return {
                currentView: 'index',
                amv: {}
            }
        },

        props: ['user'],

        components: {
            index: DashboardAMVsIndex,
            create: DashboardAMVsCreate,
            contests: DashboardAMVsContests
        },

        methods: {
            display(view, amv) {
                this.currentView = view;
                if (amv) this.amv = amv;
            },
            capitalize(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            }
        }

        
    }
</script>