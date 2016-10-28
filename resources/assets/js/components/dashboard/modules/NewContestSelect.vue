<template>
    <div class="row">
        <div class="col-xs-12 col-sm-5">
            <label>New Contest</label>
            <multiselect 
                :value="selected" 
                :options="contestList" 
                :searchable="true"
                :custom-label="nameWithYear"
                :reset-after="true" 
                @input="add"
                :close-on-select="true" 
                placeholder="Select one" 
                label="name" 
                track-by="name">
            </multiselect>
        </div>
        <div v-if="selected" class="col-xs-12 col-sm-5">
            <label for="award">Award / Placement</label>
            <input id="award" v-model="selected.award" 
                placeholder="Contest Award or Placement" type="text">
        </div>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect/lib/Multiselect.vue';

    export default {
        data () {
            return {
                /**
                * Current selected option from multiselect.
                * @type {Object} gets assigned the entire award object
                */
                selected: null
            }
        },

        props: {
            /**
            * List with all contests, passed from parent.
            * @type {Array}
            */
            contestList: {
                type: Array
            },
            /**
            * Parent method to add the newly selected option to amv.contests
            * @type {Function}
            */
            add: {
                type: Function,
                default: null
            }
        },

        components: {
            Multiselect
        },

        methods: {
            /**
            * Concatenates contest name and year so that both get displayed in the multiselect dropdown.
            * @returns {String}
            */
            nameWithYear ({ name, year }) {
                return `${name} ${year}`
            }
        }
    }
</script>