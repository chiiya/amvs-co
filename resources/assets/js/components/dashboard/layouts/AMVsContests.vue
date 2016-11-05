<template>
    <section class="dashboard__form is-right">
        <h3>Edit AMV Awards: {{ updatedAMV.title }} </h3>
        <loading></loading>
        <div v-show="!loading" class="row">
            <div class="col-xs-12">
                <transition-group name="contest-list" tag="div" mode="out-in">
                    <contest 
                        v-for="award in updatedAMV.awards" 
                        :award="award"
                        :key="award"
                        :remove="remove"
                        :notify-change="notifyChange"
                        class="contest-list-item">
                    </contest>
               </transition-group>
                <div class="contest-list-item">
                    <newcontest 
                        :contest-list="contests"
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
                        <button id="cancel" @click="goBack" 
                            class="button button--square button--transparent button--primary">
                            {{ cancelButtonStatus }} </button>
                    </div>
                </div>
                <div v-if="showErrors" class="row">
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
    import LoadingSpinner from '../modules/LoadingSpinner.vue'

    export default {
        data() {
            return {
                updatedAMV: {
                    id: '',
                    awards: []
                },
                saveButtonDisabled: false,
                saveButtonStatus: 'Save',
                cancelButtonStatus: 'Cancel',
                submitErrors: [],
                successCount: 0,
                loading: false
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
            },
            contests() {
                return this.$store.state.contests;
            },
            loading () {
                return this.$store.state.loading;
            },
            amv() {
                return this.$store.state.amvs[this.$route.params.id];
            },
            showErrors: function() {
                return this.submitErrors.length > 0 && this.saveButtonStatus === 'Failed';
            }

        },

        components: {
            contest: ContestSelect,
            newcontest: NewContestSelect,
            loading: LoadingSpinner
        },

        beforeMount: function() {
            this.$store.commit('SET_PARENT', {
                title: 'AMVs',
                path: '/dashboard/amvs'
            });
        },

        mounted: function () {
            if (!this.contests.length > 0) this.loadContests();
            this.$store.dispatch('FETCH_AMV', this.$route.params.id)
                .then((response) => {
                    this.updatedAMV = JSON.parse(JSON.stringify(response));
                });
        },

        methods: {
            /**
            * Loads a list of all available contests.
            */
            loadContests() {
                this.$store.dispatch('FETCH_CONTESTS');
            },
            /**
            * Adds a new entry to the contests array. Gets called from the NewContestSelect component.
            */
            add(contest) {
                const award = {
                    award: '',
                    amv_id: this.updatedAMV.id,
                    contest_id: contest.id,
                    contest: contest
                }
                this.updatedAMV.awards.push(award);
                this.notifyChange();
            },
            /**
            * Removes a contest entry from the contests array. Gets called once the ContestSelect
            * component has performed a successful delete.
            */
            remove(award) {
                var index = this.updatedAMV.awards.indexOf(award);
                 
                if (index>=0) {
                    this.updatedAMV.awards.splice(index, 1);
                }
            },
            /**
            * Updates the entire updatedAMV.awards array. Gets called when user clicks on Save button.
            * Performs PUT request and updates button status accordingly.
            */
            submit() {
                this.submitErrors = [];
                this.saveButtonDisabled = true;
                this.saveButtonStatus = 'Saving...';
                if (!this.validate()) {
                    this.saveButtonStatus = 'Failed';
                    this.submitErrors.push("No duplicate awards allowed.");
                    return;
                } 
                let processed = 0;
                const max = this.updatedAMV.awards.length;
                const oldAMV = this.amv;
                for (let i=0; i<max; i++) {
                    let current = this.updatedAMV.awards[i];
                    if (current.hasOwnProperty('id')) {
                        if (oldAMV.awards[i].award !== current.award) {
                            this.$store.dispatch('PATCH_AWARD', current)
                            .then(() => {
                                processed++;
                                if (processed === max) {
                                    this.notifySuccess();
                                }
                            })
                            .catch((error) => {
                                this.showErrors(error);
                            })
                        } else {
                            
                            processed++;
                            if (processed === max) {
                                this.notifySuccess();
                            }
                        }
                    } else {
                        this.$store.dispatch('STORE_AWARD', current)
                            .then((response) => {
                                Vue.set(current, 'id', response.id);
                                this.$store.commit('ADD_AWARD', current);
                                processed++;
                                if (processed === max) {
                                    this.notifySuccess();
                                }
                            })
                            .catch((error) => {
                                this.showErrors(error);
                            });
                    }
                }
            },

            validate() {
                let tmpArr = [];
                for(let obj in this.updatedAMV.awards) {
                    const contest = this.updatedAMV.awards[obj].contest_id + ' | ' + this.updatedAMV.awards[obj].award;
                    if(tmpArr.indexOf(contest) < 0){ 
                        tmpArr.push(contest);
                    } else {
                        return false;
                    }
                }
                return true
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
            },

            notifySuccess() {
                this.saveButtonStatus = 'Saved';
                this.cancelButtonStatus = 'Back';
            },

            showErrors(error) {
                if (typeof error.status === 'undefined') return;
                this.saveButtonStatus = 'Failed';
                if (typeof error.body === 'object') {
                    for (let key in error.body) {
                        this.submitErrors.push(error.body[key][0]);
                    }
                } else {
                    this.submitErrors.push(error.status + ": Server Error. Please try again later.");
                }
            },
            goBack() {
                this.$router.push('/dashboard/amvs');
            }
        },
    }
</script>