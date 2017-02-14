<template>
    <section class="dashboard__form is-right">
        <loading></loading>
        <p v-if="!loading && !authorized" class="info">Unauthorized. Access was denied because you are not the owner of this AMV. Please make sure the URL you entered is correct, or <router-link to="/dashboard/amvs">go back to your AMV Overview</router-link>.</p>
        <h3 v-if="!loading && authorized">Edit AMV Awards: {{ updatedAMV.title }} </h3>
        <div v-show="!loading && authorized" class="row">
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
    import LoadingSpinner from '../modules/LoadingSpinner.vue';
    import { setNav } from '../../../util/functions';

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
                authorized: true
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
            /**
             * List of available contests, from Vuex.
             * @returns {Array}
             */
            contests() {
                return this.$store.state.contests;
            },
            /**
             * Current loading status (loading is true if data is being loaded asynchronously)
             * @returns {String}
             */
            loading () {
                return this.$store.state.loading;
            },
            /**
             * Currently displayed AMV object, fetched via route ID parameter
             * @returns {Object}
             */
            amv() {
                return this.$store.state.amvs[this.$route.params.id];
            },
            /**
             * Only show errors when the attempt to save just failed.
             * @returns {Boolean}
             */
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
            // Set Breadcrumbs
            this.$store.commit('SET_PARENT', {
                title: 'AMVs',
                path: '/dashboard/amvs'
            });
        },

        mounted: function () {
            setNav(1);
            // Only fetch contests from API if they aren't already stored in Vuex
            if (!this.contests.length > 0) this.loadContests();
            // Fetch AMV from Vuex. If not present in Vuex an API call gets made
            this.$store.dispatch('FETCH_AMV', this.$route.params.id)
                .then((response) => {
                    // this.updatedAMV = response would create a copy by reference! All changes to updatedAMV
                    // would cascade down to the store object as well.
                    // JSON.parse(JSON.stringify) in order to create a new copy of the AMV, not by reference.
                    // Needs to be done so that the user changes can be disregarded once he clicks 'Cancel'
                    this.updatedAMV = JSON.parse(JSON.stringify(response));
                })
                .catch((error) => {
                    if (error.status === 404 || error.status === 401) this.authorized = false;
                    this.submitErrors.push(error.body)
                });
        },

        methods: {
           /**
            * Makes an API call to fetch available contests
            */
            loadContests() {
                this.$store.dispatch('FETCH_CONTESTS');
            },

           /**
            * Adds a new entry to the contests array. Gets called from the NewContestSelect component.
            * @param {Object contest}
            */
            add(contest) {
                // Initiate award object with all necessary elements so that the user can store & delete it later
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
            * @param {Object award}
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
                // Reset errors.
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
                    // If award already has an ID, perform PUT update
                    if (current.hasOwnProperty('id')) {

                        // Only make call to API if the award has been changed.
                        if (oldAMV.awards[i].award !== current.award) {
                            this.$store.dispatch('PATCH_AWARD', current)
                            .then(() => {
                                processed++;
                                if (processed === max) {
                                    this.notifySuccess();
                                }
                            })
                            .catch((error) => {
                                this.pushError(error);
                            })

                        } else {
                            processed++;
                            if (processed === max) {
                                this.notifySuccess();
                            }
                        }
                    
                    // Else perform POST to store new award
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
                                this.pushError(error);
                            });
                    }
                }
            },

            /**
             * Validate the user input.
             * Check if any awards are duplicates, since there is a unique index on (contest_id, amv_id, award)
             */
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

            /**
             * Once all awards have been successfully updated with the API, notify user
             */
            notifySuccess() {
                this.saveButtonStatus = 'Saved';
                this.cancelButtonStatus = 'Back';
            },

            /**
             * If any errors occured during saving, push onto submitErrors
             * @param {String error}
             */
            pushError(error) {
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

            /**
             * When user clicks on 'Cancel', take him back to AMV overview page
             */
            goBack() {
                this.$router.push('/dashboard/amvs');
            }
        },
    }
</script>