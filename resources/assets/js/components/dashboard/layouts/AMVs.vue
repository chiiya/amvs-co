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
            <component :is="currentView" :amv-id="id" :display="display">
            </component>
        </transition>
        </keep-alive>
    </div>
</template>

<script>
    import AMVsIndex from './AMVsIndex.vue'
    import AMVsCreate from'./AMVsCreate.vue'
    import AMVsContests from './AMVsContests.vue'
    import AMVsEdit from './AMVsEdit.vue'

    export default {
        data() {
            return {
                currentView: 'index',
                id: 1
            }
        },

        components: {
            index: AMVsIndex,
            create: AMVsCreate,
            contests: AMVsContests,
            edit: AMVsEdit
        },

        methods: {
            /**
            * Sets the component currently displayed (Index/Create/Edit/Contests)
            * @params {String: component, Object: AMV for contests and edit}
            */
            display(view, id) {
                this.currentView = view;
                if (id) this.id = id;
            }
        }

        
    }
</script>