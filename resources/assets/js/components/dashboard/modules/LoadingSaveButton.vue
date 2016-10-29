<template>
    <button id="submit" v-bind:disabled="saveButtonDisabled"
        class="button button--square z-depth-1"
        v-bind:class="buttonClasses" 
        @click="submit">
        {{ saveButtonStatus }}
    </button>
</template>

<script>
    export default {
        props: {
            /**
            * String value of the save button.
            * @type {String}
            */
            saveButtonStatus: {
                type: String,
                default: 'Save'
            },
            /**
            * Disabled status of the save button.
            * @type {Boolean}
            */
            saveButtonDisabled: {
                type: Boolean,
                default: false
            }
        },

        computed: {
            /**
            * Possible Save button classes, depending on the value of saveButtonStatus
            * @returns {Object}
            */
            buttonClasses: function() {
                return {
                    'button--primary': this.saveButtonStatus === 'Save' || this.saveButtonStatus === 'Saving...',
                    'button--loading': this.saveButtonStatus === 'Saving...',
                    'button--success': this.saveButtonStatus === 'Saved',
                    'button--error': this.saveButtonStatus === 'Failed'
                }
            }
        },

        methods: {
            submit: function() {
                this.$emit('submit');
            }
        }
    }
</script>