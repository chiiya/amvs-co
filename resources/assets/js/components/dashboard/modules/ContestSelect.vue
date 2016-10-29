<template>
    <div  class="row">
        <div class="col-xs-12 col-sm-5">
            <label for="contest">Contest</label>
            <input id="contest" v-model="name" disabled type="text">
        </div>
        <div class="col-xs-12 col-sm-5">
            <label for="award">Award / Placement</label>
            <input id="award" v-model="contest.award" v-on:input="notifyChange" 
                placeholder="Contest Award or Placement" type="text">
        </div>
        <div class="col-xs-12 col-sm-2">
            <label class="delete">Delete</label>
            <delete v-on:remove="deleteContest" :url="'/amv/' + amv_id + '/awards/' + contest.award_id"></delete>
        </div>
    </div>
</template>

<script>
    import LoadingDeleteButton from './LoadingDeleteButton.vue';

    export default {

        data() {
            return {
                award: ''
            }
        },

        mounted: function () {
            this.award = this.contest.award;
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
                return `${this.contest name} ${this.contest.year}`;
            }
        },

        components: { delete: LoadingDeleteButton },

        methods: {
            /**
            * Delete the current contest award. Gets called when user clicks on delete button.
            * Performs DELETE request and updates button status accordingly. Once successful,
            * the contest gets removed from the parent amvs.contests instance and thus from the view.
            */
            deleteContest() {
                this.remove(this.contest);
            }
        }
    }
</script>