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
            <component :is="currentView" :user="user" :amvs="amvs"
                :loading="loading" :amv="amv" :add-amv="addAmv" :display.sync="display">
            </component>
        </transition>
        </keep-alive>
    </div>
</template>

<script>
    import AMVsIndex from './AMVsIndex.vue';
    import AMVsCreate from './AMVsCreate.vue';
    import AMVsContests from './AMVsContests.vue';

    export default {
        data() {
            return {
                currentView: 'index',
                amv: {}
            }
        },

        props: ['user', 'amvs', 'loading', 'addAmv'],

        components: {
            index: AMVsIndex,
            create: AMVsCreate,
            contests: AMVsContests
        },

        methods: {
            /**
            * Sets the component currently displayed (Index/Create/Edit/Contests)
            * @params {String: component, Object: AMV for contests and edit}
            */
            display(view, amv) {
                this.currentView = view;
                if (amv) this.amv = amv;
            }
        }

        
    }
</script>