<template>
    <section class="dashboard__form is-right">
        <h3>Edit AMV Contests: {{ amv.title }} </h3>
        <div class="row">
            <div class="col-xs-12">
                <transition-group name="contest-list" tag="div" mode="out-in">
                    <contest 
                        v-for="contest in updatedAMV.contests" 
                        :contest="contest"
                        :amv_id="amv.id" 
                        :key="contest"
                        :remove="remove"
                        :disabled="buttonDisabled" 
                        :notify-change="notifyChange"
                        class="contest-list-item">
                    </contest>
               </transition-group>
                <div class="contest-list-item">
                    <newcontest 
                        :contest-list="contestList"
                        :add="add">
                    </newcontest>
                <div class="row">
                    <div class="col-xs-12">
                        <button id="submit" v-bind:disabled="buttonDisabled"
                            class="button button--square z-depth-1"
                            v-bind:class="buttonClasses"
                            @click="submit">
                            {{ buttonStatus }}</button>
                        <button id="cancel" @click="display('index')" 
                            class="button button--square button--transparent button--primary">
                            {{ cancelButtonStatus }} </button>
                    </div>
                </div>
                <div v-if="submitErrors.length > 0" class="row">
                    <div class="col-xs-12">
                        <p v-for="error in submitErrors" class="error">
                            {{ error }}
                        </p>
                        <div> {{ htmlError }}</div>
                    </div>
                </div>
            </div>
             
            </div>
        </div>
    </section>
</template>

<script>
    import ContestSelect from '../modules/ContestSelect.vue';
    import NewContestSelect from '../modules/NewContestSelect.vue';

    export default {
        data() {
            return {
                contestList: [],
                updatedAMV: {},
                buttonDisabled: false,
                buttonStatus: 'Save',
                cancelButtonStatus: 'Cancel',
                submitErrors: []
            }
        },

        props: ['user', 'display', 'amv'],

        computed: {
            /**
            * Possible Save button classes, depending on the value of buttonStatus
            * @returns {Object}
            */
            buttonClasses: function() {
                return {
                    'button--primary': this.buttonStatus === 'Save' || this.buttonStatus === 'Saving...',
                    'button--loading': this.buttonStatus === 'Saving...',
                    'button--success': this.buttonStatus === 'Saved',
                    'button--error': this.buttonStatus === 'Failed'
                }
            }
        },

        components: {
            contest: ContestSelect,
            newcontest: NewContestSelect
        },

        mounted: function () {
            this.loadContests();
            this.updatedAMV = this.amv;
        },

        methods: {
            /**
            * Loads a list of all available contests.
            */
            loadContests() {
                this.$http.get('/api/contests').then((response) => {
                    this.contestList = response.body;
                }, (response) => {
                    console.log("Couldn't load contests.");
                });
            },
            /**
            * Adds a new entry to the contests array. Gets called from the NewContestSelect component.
            */
            add(contest) {
                this.updatedAMV.contests.push(contest);
                this.notifyChange();
            },
            /**
            * Removes a contest entry from the contests array. Gets called once the ContestSelect
            * component has performed a successful delete.
            */
            remove(contest) {
                var index = this.updatedAMV.contests.indexOf(contest);
                 
                if (index>=0) {
                    this.updatedAMV.contests.splice(index, 1);
                }
            },
            /**
            * Updates the entire amv.contests array. Gets called when user clicks on Save button.
            * Performs PUT request and updates button status accordingly.
            */
            submit() {
                this.buttonDisabled = true;
                this.buttonStatus = 'Saving...';

                this.$http.put('/amv/' + this.amv.id + '/awards', this.updatedAMV, {
                    headers: {
                        'Content-Type': 'application/json'
                    }
                }).then((response) => {
                    this.buttonStatus = 'Saved';
                    this.cancelButtonStatus = 'Back';
                }, (response) => {
                    this.buttonStatus = 'Failed';
                    if (typeof response.body === 'object') {
                        for (let key in response.body) {
                            this.submitErrors.push(response.body[key][0]);
                        }
                    } else {
                        this.submitErrors.push("Server Error. Please try again later.");
                        this.htmlError = response.body;
                    }
                });
            },
            /**
            * Gets called from child components. If a user input change has been made, the Save button
            * will be reset, so that the user can save additional changes. 
            */
            notifyChange() {
                this.buttonDisabled = false;
                this.buttonStatus = 'Save';
                this.cancelButtonStatus = 'Cancel';
            }
        },
    }
</script>