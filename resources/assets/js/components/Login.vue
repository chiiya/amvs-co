<template>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <form>
                        <p class="error" v-if="error">{{ errorMessage }}</p>
                        <input type="email" placeholder="Email" v-model="email" v-on:keyup.enter="submit" required>
                        <input type="password" placeholder="Password" v-model="password" v-on:keyup.enter="submit" required>
                        <input type="submit" value="Login" v-on:click="submit">
                        <p>
                            <router-link to="signup">Or create a new account.</router-link>
                        </p>
                    </form>
                </div>
            </div>
        </div>
</template>

<script>
    export default {
    data() {
        return {
            password: '',
            email: '',

            error: false,
            errorMessage: ''
        }
    }, 
    methods: {
        submit(e) {
            e.preventDefault();
            let data = {};

            data.email = this.email;
            data.password = this.password;
            console.log(Laravel.csrfToken);
            this.$http.post('/api/auth/token', data, {
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            }).then((response) => {
                this.error = true;
                this.errorMessage = "Logged in!";
            }, (response) => {
                this.error = true;
                this.errorMessage = "Wrong email/password.";
            });
        }
    }

}
</script>
