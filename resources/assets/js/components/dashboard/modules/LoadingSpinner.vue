<template>
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
</template>

<script>
    export default {
        computed: {
            /**
             * Current loading status (loading is true if data is being loaded asynchronously)
             * @returns {Boolean}
             */
            loading() {
                return this.$store.state.loading;
            }
        }
    }
</script>