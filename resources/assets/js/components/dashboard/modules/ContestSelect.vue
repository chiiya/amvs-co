<template>
    <div  class="row">
        <div class="col-xs-12 col-sm-5">
            <label for="contest">Contest</label>
            <input id="contest" v-model="name" disabled type="text">
        </div>
        <div class="col-xs-12 col-sm-5">
            <label for="award">Award / Placement</label>
            <input id="award" v-model="contest.award"
                placeholder="Contest Award or Placement" type="text">
        </div>
        <div class="col-xs-12 col-sm-2">
            <label class="delete">Delete</label>
            <a class="button button--square button--danger button--transparent" 
                @click="deleteContest" v-bind:disabled="buttonDisabled" v-bind:class="buttonClasses">
                {{ buttonStatus }}
                <i v-if="!buttonDisabled" class="material-icons">close</i>  
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
                buttonStatus: 'Delete',
                /**
                * Disabled status of the delete button.
                * @type {Boolean}
                */
                buttonDisabled: false
            }
        },

        props: {
            /**
            * Contest shown in current row.
            * @type {Object}
            */
            contest: {
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
            * Parent property that checks the disabled status of the Save button.
            * @type {Boolean}
            */
            disabled: {
                type: Boolean
            },
            /**
            * Parent method to notify parent instance that a user input has been made.
            * Fires if disabled is true. 
            * @type {Function}
            */
            notifyChange: {
                type: Function
            },
            /**
            * ID of the AMV for the current contest. Necessary for delete-URL.
            * @type {Function}
            */
            amv_id: {
                type: Number
            }
        },

        computed: {
            /**
            * Concatenate contest name and year. Will be shown in the disabled contest input field.
            * @returns {String} 
            */
            name: function() {
                return this.contest.name + ' ' + this.contest.year;
            },
            /**
            * Possible delete button classes, depending on the value of buttonStatus
            * @returns {Object}
            */
            buttonClasses: function() {
                return {
                    'button--loading': this.buttonStatus === 'Deleting...',
                    'button--error': this.buttonStatus === 'Failed'
                }
            }
        },

        methods: {
            /**
            * Delete the current contest award. Gets called when user clicks on delete button.
            * Performs DELETE request and updates button status accordingly. Once successful,
            * the contest gets removed from the parent amvs.contests instance and thus from the view.
            */
            deleteContest() {
                this.buttonDisabled = true;
                this.buttonStatus = 'Deleting...';

                this.$http.delete('/amv/' + this.amv_id + '/awards/' + this.contest.award_id, {
                    headers: {
                        'Content-Type': 'application/json'
                    }
                }).then((response) => {
                    this.remove(this.contest);
                }, (response) => {
                    this.buttonStatus = 'Failed';
                });
                
            }
        },

        watch: {
            /**
            * Watches the award input field for changes. If a change (user input) is detected,
            * if the parent Save button is currently disabled, the parent component will be 
            * notified that a change has been made.
            */
            'contest.award': function() {
                if (this.disabled) this.notifyChange();
            }
        }
    }
</script>