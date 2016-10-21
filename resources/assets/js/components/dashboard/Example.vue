<template>
    <div class="row is-right">
        <div class="col-xs-12 col-sm-6">
            <label>Contest</label>
            <multiselect 
                :value="selected" 
                :options="contestList" 
                :searchable="true"
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
                selected: null,
                contestList : [
                    {
                        name: "Contest 1",
                        year: 2016
                    },
                    {
                        name: "Contest 2",
                        year: 2015
                    },
                    {
                        name: "Contest 3",
                        year: 2016
                    }
                ]
            }
        },

        components: {
            Multiselect
        },

        methods: {
            updateSelected (value) {
                this.selected = value;
            },
            nameWithYear ({ name, year }) {
                return `${name} ${year}`
            }
        }
    }
</script>