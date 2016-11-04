export default {
    /**
     * Resource: User
     * GET
     */

    getUser(id) {
        return Vue.http.get(`/api/users/${id}`)
            .then((response) => Promise.resolve(response.body))
            .catch((error) => Promise.reject(error));
    },

    /**
     * Resource: AMV
     * GET, POST, PUT, DELETE
     */

    getAMVs(user) {
        return Vue.http.get(`/api/amvs?user=${user}`)
            .then((response) => Promise.resolve(response.body))
            .catch((error) => Promise.reject(error));
    },
    
    storeAMV(amv) {
        return Vue.http.post('/api/amvs', amv, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Accept': 'application/json'
                }
            })
            .then((response) => Promise.resolve(response.body))
            .catch((error) => Promise.reject(error));
    },

    updateAMV(id, data) {
        return Vue.http.post(`/api/amvs/${id}`, data, {
                headers: {
                    'Content-Type': 'multipart/form-data',
                    'Accept': 'application/json'
                }
        })
        .then((response) => Promise.resolve(response.body))
        .catch((error) => Promise.reject(error));
    },

    deleteAMV(id) {
        return Vue.http.delete(`/api/amvs/${id}`)
            .then((response) => Promise.resolve(response.body))
            .catch((error) => Promise.reject(error));
    },

    /**
     * Resource: Genre
     * GET
     */
    getGenres() {
        return Vue.http.get('/api/genres')
            .then((response) => Promise.resolve(response.body))
            .catch((error) => Promise.reject(error));
    },

    /**
     * Resource: Contest
     * GET
     */

    getContests() {
        return Vue.http.get('/api/contests')
            .then((response) => Promise.resolve(response.body))
            .catch((error) => Promise.reject(error));
    },

    /**
     * Resource: Award
     * POST, PUT, DELETE
     */

    storeAward(award) {
        return Vue.http.post(`/api/awards`, award, {
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then((response) => Promise.resolve(response.body))
        .catch((error) => Promise.reject(error));
    },

    updateAward(award) {
        return Vue.http.put(`/api/awards/${award.id}`, award, {
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then((response) => Promise.resolve(response.body))
            .catch((error) => Promise.reject(error));
    },

    deleteAward(id) {
        return Vue.http.delete(`/api/awards/${id}`)
            .then((response) => Promise.resolve(response.body))
            .catch((error) => Promise.reject(error));
    }
}