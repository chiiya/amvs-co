import Vuex from 'vuex'
import api from '../services/api'


const store = new Vuex.Store({
    state: {
        /**
         * Currently authenticated user
         */
        user: {},
        /**
         * List of all AMVs by the current user
         */
        amvs: {},
        /**
         * List of all available AMV genres
         */
        genres: [],
        /**
         * List of all available contests
         */
        contests: [],
        /**
         * Shorthand for all months. Necessary for reformatting AMV creation date
         */
        months: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        /**
         * Currently fetching data from the API?
         */
        loading: false,
        /**
         * Currently displayed parent component (for breadcrumbs)
         */
        parent: {
            title: 'Overview',
            path: ''
        },
        failure: ''
    },

    actions: {
        /**
         * Fetch a user. First check if the user is already stored in Vuex, if that is the case
         * the simply return it. Otherwise make a call to the API and then store in Vuex.
         */
        FETCH_USER: ({commit, state}) => {
            const isEmpty = Object.keys(state.user).length === 0
            if (isEmpty) commit('SET_LOADING', { val: true });
            return !isEmpty
                ? Promise.resolve(state.user)
                : api.getUser()
                .then((response) => {
                    commit('SET_LOADING', { val: false });
                    commit('SET_USER', response);
                    return Promise.resolve(response);
                })
                .catch((error) => {
                    commit('SET_LOADING', { val: false });
                    commit('FAILURE', error)
                });
        },
        /**
         * Fetch all AMVs of a user by the user's ID. Once done, store in Vuex
         */
        FETCH_AMVS: ({commit}, user) => {
            commit('SET_LOADING', { val: true });
            return api.getAMVs(user.id)
                .then((response) => {
                    commit('SET_LOADING', { val: false });
                    commit('SET_AMVS', response)
                })
                .catch((error) => {
                    commit('SET_LOADING', { val: false });
                    commit('FAILURE', error)
                });
        },

        FETCH_AMV: ({commit, state}, id) => {
            if (!state.amvs[id]) commit('SET_LOADING', { val: true });
            return state.amvs[id]
                ? Promise.resolve(state.amvs[id])
                : api.getAMV(id)
                    .then((response) => {
                        commit('SET_LOADING', { val: false });
                        return Promise.resolve(response);
                    })
                    .catch((error) => {
                        commit('SET_LOADING', { val: false });
                        commit('FAILURE', error)
                    });
        },
        /**
         * Fetch all AMV genres from the API. Once done, store in Vuex
         */
        FETCH_GENRES: ({commit}) => {
            commit('SET_LOADING', { val: true });
            return api.getGenres()
                .then((response) => {
                    commit('SET_LOADING', { val: false });
                    commit('SET_GENRES', response)
                })
                .catch((error) => {
                    commit('SET_LOADING', { val: false });
                    commit('FAILURE', error)
                });
        },

        /**
         * Fetch all available contests. Once done, store in Vuex
         */
        FETCH_CONTESTS: ({commit}) => {
            commit('SET_LOADING', { val: true });
            return api.getContests()
                .then((response) => {
                    commit('SET_LOADING', { val: false });
                    commit('SET_CONTESTS', response)
                })
                .catch((error) => {
                    commit('SET_LOADING', { val: false });
                    commit('FAILURE', error)
                });
        },

        PATCH_USER: ({commit}, payload) => {
            return api.updateUser(payload.id, payload.data)
                .then((response) => Promise.resolve(response))
                .catch((error) => Promise.reject(error));
        },

        /**
         * Store a new award in database and Vuex
         */
        STORE_AWARD: ({commit}, award) => {
            return api.storeAward(award)
                .then((response) => Promise.resolve(response))
                .catch((error) => Promise.reject(error));
        },

        /**
         * Update an existing award in database
         */
        PATCH_AWARD: ({commit}, award) => {
            return api.updateAward(award)
                .then((response) => {
                    commit('UPDATE_AWARD', response)
                })
                .catch((error) => Promise.reject(error));
        },

        /**
         * Delete an existing award from database and/or Vuex
         */
        DESTROY_AWARD: ({commit}, award) => {
            return api.deleteAward(award.id)
                .then((response) => {
                    commit('DELETE_AWARD', award);
                })
                .catch((error) => Promise.reject(error));
        },

        /**
         * Store a new AMV in database and Vuex
         */
        STORE_AMV: ({commit}, amv) => {
            return api.storeAMV(amv)
                .then((response) => {
                    commit('ADD_AMV', response);
                })
                .catch((error) => Promise.reject(error));
        },

        /**
         * Update an existing AMV in database and Vuex
         */
        PATCH_AMV: ({commit}, payload) => {
            return api.updateAMV(payload.id, payload.data)
                .then((response) => Promise.resolve(response))
                .catch((error) => Promise.reject(error));
        },

        /**
         * Delete an existing AMV from database and/or Vuex
         */
        DESTROY_AMV: ({commit}, id) => {
            return api.deleteAMV(id)
                .then((response) => {
                    commit('DELETE_AMV', id);
                })
                .catch((error) => Promise.reject(error));
        }

    },

    mutations: {
        FAILURE: (state, error) => {
            state.failure = error.body;
        },

        SET_USER: (state, user) => {
            for (let key in user) {
                if (user[key] === 'null') user[key] = '';
            }
            state.user = user;
        },

        SET_AMVS: (state, amvs) => {
            amvs.forEach(amv => {
                for (let key in amv) {
                    if (amv[key] === 'null') amv[key] = '';
                }
                Vue.set(state.amvs, amv.id, amv);
            });
        },

        SET_GENRES: (state, genres) => {
            state.genres = genres;
        },

        SET_CONTESTS: (state, contests) => {
            state.contests = contests;
        },

        SET_LOADING: (state, {val}) => {
            state.loading = val;
        },

        ADD_AMV: (state, amv) => {
            const date = new Date(amv.created_at);
            amv.date = state.months[date.getMonth()] + ' ' + date.getFullYear();
            for (let key in amv) {
                if (amv[key] === 'null') amv[key] = '';
            }
            Vue.set(state.amvs, amv.id, amv);
        },

        UPDATE_AMV: (state, amv) => {
            state.amvs[amv.id] = amv;
        },

        DELETE_AMV: (state, id) => {
            Vue.delete(state.amvs, id);
        },

        ADD_AWARD: (state, award) => {
            state.amvs[award.amv_id].awards.push(award);
        },

        UPDATE_AWARD: (state, award) => {
            const index = state.amvs[award.amv_id].awards.findIndex(x => x.id === award.id);
            if (index > -1) {
                state.amvs[award.amv_id].awards[index] = award;
            }
        },

        DELETE_AWARD: (state, award) => {
            const index = state.amvs[award.amv_id].awards.findIndex(x => x.id === award.id);
            if (index > -1) {
                state.amvs[award.amv_id].awards.splice(index, 1);
            }
        },

        SET_PARENT: (state, parent) => {
            state.parent = parent;
        }
    },

    getters: {
        amvs: state => {
            return Object.keys(state.amvs).map(key => state.amvs[key]);
        }
    }
})

export default store