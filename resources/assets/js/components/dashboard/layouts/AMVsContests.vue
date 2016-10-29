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
                        <button id="submit" v-bind:disabled="saveButtonDisabled"
                            class="button button--square z-depth-1"
                            v-bind:class="buttonClasses" 
                            @click="submit">
                            {{ saveButtonStatus }}
                        </button>
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
    import LoadingSaveButton from '../modules/LoadingSaveButton.vue';

    export default {
        data() {
            return {
                contestList: [],
                updatedAMV: {},
                saveButtonDisabled: false,
                saveButtonStatus: 'Save',
                cancelButtonStatus: 'Cancel',
                submitErrors: []
            }
        },

        props: ['user', 'display', 'amv'],

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

        components: {
            contest: ContestSelect,
            newcontest: NewContestSelect,
            save: LoadingSaveButton
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
                this.saveButtonDisabled = true;
                this.saveButtonStatus = 'Saving...';

                this.$http.put(`/amv/${this.amv.id}/awards`, this.updatedAMV, {
                    headers: {
                        'Content-Type': 'application/json'
                    }
                }).then((response) => {
                    this.saveButtonStatus = 'Saved';
                    this.cancelButtonStatus = 'Back';
                }, (response) => {
                    this.saveButtonStatus = 'Failed';
                    if (typeof response.body === 'object') {
                        for (let key in response.body) {
                            this.submitErrors.push(response.body[key][0]);
                        }
                    } else {
                        this.submitErrors.push("Server Error. Please try again later.");
                    }
                });
            },
            /**
            * Gets called from child components. If a user input change has been made, the Save button
            * will be reset, so that the user can save additional changes. 
            */
            notifyChange() {
                if (this.saveButtonDisabled) {
                    this.saveButtonDisabled = false;
                    this.saveButtonStatus = 'Save';
                    this.cancelButtonStatus = 'Cancel';
                }
            }
        },
    }
</script>