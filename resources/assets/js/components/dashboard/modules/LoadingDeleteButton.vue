<template>
    <a class="button button--square button--danger button--transparent"
       @click="remove" v-bind:disabled="deleteButtonDisabled" v-bind:class="deleteButtonClasses">
       {{ deleteButtonStatus }}
       <i v-if="!deleteButtonDisabled" class="material-icons">close</i>  
    </a>
</template>

<script>

    export default {

        data() {
            return {
                /**
                * String value of the delete button.
                * @type {String}
                */
                deleteButtonStatus: 'Delete',
                /**
                * Disabled status of the delete button.
                * @type {Boolean}
                */
                deleteButtonDisabled: false
            }
        },

        props: ['url'],

        computed: {
            /**
            * Possible delete button classes, depending on the value of deleteButtonStatus
            * @returns {Object}
            */
            deleteButtonClasses: function() {
                return {
                    'button--loading': this.deleteButtonStatus === 'Deleting...',
                    'button--error': this.deleteButtonStatus === 'Failed'
                }
            } 
        },

        methods: {
            /**
            * Delete a resource by its URL. Once successfull, emits an event caught by parent instance.
            */
            remove: function() {
                this.deleteButtonDisabled = true;
                this.deleteButtonStatus = 'Deleting...';

                this.$http.delete(this.url, {
                    headers: {
                        'Content-Type': 'application/json'
                    }
                }).then((response) => {
                    this.$emit('remove');
                }, (response) => {
                    this.deleteButtonStatus = 'Failed';
                });
            }
        }
    }

</script>