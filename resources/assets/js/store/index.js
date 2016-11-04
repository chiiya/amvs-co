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
        amvs: [],
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
        failure: ''
    },

    actions: {
        /**
         * Fetch a user by its ID from the API. Once done, store in Vuex
         */
        FETCH_USER: ({commit, state}, {id}) => {
            commit('SET_LOADING', { val: true });
            return api.getUser(id)
                .then((response) => {
                    commit('SET_LOADING', { val: false });
                    commit('SET_USER', response);
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
            for (let i=0; i<amvs.length; i++) {
                for (let key in amvs[i]) {
                    if (amvs[i][key] === 'null') amvs[i][key] = '';
                }
            }
            state.amvs = amvs;
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
            state.amvs.push(amv);
        },

        UPDATE_AMV: (state, amv) => {
            const index = state.amvs.findIndex(x => x.id === amv.id);
            state.amvs[index] = amv;
        },

        DELETE_AMV: (state, id) => {
            const index = state.amvs.findIndex(x => x.id === id);
            if (index > -1) {
                state.amvs.splice(index, 1);
            }
        },

        ADD_AWARD: (state, award) => {
            const amv = state.amvs.find(x => x.id === award.amv_id);
            if (!amv) return;
            amv.awards.push(award);
        },

        UPDATE_AWARD: (state, award) => {
            const amv = state.amvs.find(x => x.id === award.amv_id);
            if (amv && amv.awards && amv.awards.length > 0) {
                const index = amv.awards.findIndex(x => x.id === award.id);
                if (index > -1) {
                    amv.awards[index] = award;
                }
            }
        },

        DELETE_AWARD: (state, award) => {
            const amv = state.amvs.find(x => x.id === award.amv_id);
            if (amv && amv.awards && amv.awards.length > 0) {
                const index = amv.awards.findIndex(x => x.id === award.id);
                if (index > -1) {
                    amv.awards.splice(index, 1);
                }
            }
        }
    },

    getters: {
        avatar: state => {
            return state.user.avatar || '/img/avatars/default.jpg';
        }
    }
})

export default store