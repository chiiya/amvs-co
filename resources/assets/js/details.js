const vm = new Vue({
    el: '#app',

    data: {
        error: '',
        like: {}
    },

    methods: {

        like() {
            document.querySelector(".amv__like--unfilled").style.display="none";
            document.querySelector(".amv__like--filled").style.display="block";
            const data = {
                amv_id: document.head.querySelector("[name=amv]").id
            };
            this.$http.post('/api/likes', data, {
                headers: {
                    'Content-Type': 'application/json'
                }
            }).then((response) => {
                this.like = response.body;
            });
        },

        unlike() {
            const filledDiv = document.querySelector(".amv__like--filled");
            filledDiv.style.display="none";
            const likeId = this.like.id || filledDiv.getAttribute('likeid');
            document.querySelector(".amv__like--unfilled").style.display="block";

            this.$http.delete(`/api/likes/${likeId}`);
        }

    }
})