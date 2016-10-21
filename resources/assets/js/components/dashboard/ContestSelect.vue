<template>
    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <label>Contest</label>
            <multiselect 
                :value="selected" 
                :options="contestList" 
                :searchable="true"
                :custom-label="nameWithYear" 
                @input="updateSelected"
                :close-on-select="true" 
                :clear-on-select="false" 
                placeholder="Select one" 
                label="name" 
                track-by="name">
            </multiselect>
        </div>
        <div v-if="selected" class="col-xs-12 col-sm-6">
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
                selected: null
            }
        },

        props: {
            contest: {
                type: null,
                default: null
            },
            contestList: Array,
            add: {
                type: Function,
                default: null
            },
            remove: {
                type: Function,
                default: null
            }
            
        },

        components: {
            Multiselect
        },

        mounted: function () {
            if (this.contest) {
                this.selected = this.contest;
            }
        },

        methods: {
            updateSelected (value) {
                // If selected is null and value isn't, we're creating a new contest entry
                if (!this.contest && !this.selected && value) {
                    this.add(value);
                } 
                // If new value is null, we're removing an existing contest entry
                else if (this.selected && !value) {
                    this.remove(this.selected);
                }
                this.selected = null;
            },
            nameWithYear ({ name, year }) {
                return `${name} ${year}`
            }
        }
    }
</script>