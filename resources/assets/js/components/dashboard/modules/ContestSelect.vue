<template>
    <div  class="row">
        <div class="col-xs-12 col-sm-5">
            <label for="contest">Contest</label>
            <input id="contest" v-model="name" disabled type="text">
        </div>
        <div class="col-xs-12 col-sm-5">
            <label for="award">Award / Placement</label>
            <input id="award" v-model="award.award" v-on:input="notifyChange" 
                placeholder="Contest Award or Placement" type="text">
        </div>
        <div class="col-xs-12 col-sm-2">
            <label class="delete">Delete</label>
            <a class="button button--square button--danger button--transparent"
                @click="deleteAward" v-bind:disabled="deleteButtonDisabled" v-bind:class="deleteButtonClasses">
                {{ deleteButtonStatus }}
                <i v-if="!deleteButtonDisabled" class="material-icons">close</i>  
            </a>
        </div>
    </div>
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

        props: {
            /**
            * Contest shown in current row.
            * @type {Object}
            */
            award: {
                type: Object,
                default: null
            },
            /**
            * Parent method to remove current contest from amv.contests
            * @type {Function}
            */
            remove: {
                type: Function,
                default: null
            },
            /**
            * Parent method to notify parent instance that a user input has been made.
            * Fires if disabled is true. 
            * @type {Function}
            */
            notifyChange: {
                type: Function
            }
        },

        computed: {
            /**
            * Concatenate contest name and year. Will be shown in the disabled contest input field.
            * @returns {String} 
            */
            name: function() {
                return `${this.award.contest.name} ${this.award.contest.year}`;
            },
            /**
            * Checks if the current contest award is local or not (i.e. has not been saved to the server yet).
            * @returns {Boolean}
            */
            isLocal: function() {
                return (!this.award.hasOwnProperty('id'));
            },
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
            * Delete the current contest award. Gets called when user clicks on delete button.
            * Performs DELETE request and updates button status accordingly. Once successful,
            * the contest gets removed from the parent amvs.contests instance and thus from the view.
            */
            deleteAward() {
                this.deleteButtonDisabled = true;
                this.deleteButtonStatus = 'Deleting...';
                if (this.isLocal) {
                    this.remove(this.award);
                    return;
                }
                this.$store.dispatch('DESTROY_AWARD', this.award)
                    .then(() => {
                        this.remove(this.award);
                    })
                    .catch((error) => {
                        this.deleteButtonStatus = 'Failed';
                    });
            }
        }
    }
</script>