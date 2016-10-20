<template>
    <div>
        <header class="dashboard__title valign-wrapper">
            <h2 class="valign is-right">My AMVs</h2>
        </header>
        <transition 
            name="fade" 
            enter-active-class="animated fadeIn" 
            leave-activeclass="animated fadeOut" 
            mode="out-in"
        >
            <component :is="currentView" :user="user" :amv="amv">
            </component>
        </transition>
    </div>
</template>

<script>
    import DashboardAMVsIndex from './DashboardAMVsIndex.vue';

    export default {
        data() {
            return {
                currentView: 'index',
                amv: {}
            }
        },

        props: ['user'],

        components: {
            index: DashboardAMVsIndex
        },

        methods: {
            display(view) {
                this.currentView = view;
            }
        }

        
    }
</script>