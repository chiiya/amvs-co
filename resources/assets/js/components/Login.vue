<template>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <form>
                        <p class="error" v-if="error">{{ errorMessage }}</p>
                        <input type="email" placeholder="Email" v-model="email" v-on:keyup.enter="submit" required autofocus>
                        <input type="password" placeholder="Password" v-model="password" v-on:keyup.enter="submit" required>
                        <input type="submit" value="Login" v-on:click="submit">
                        <p>
                            <a href="#" v-on:click="display('signup')">Or create a new account.</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
</template>

<script>
    export default {
        props: ['display'],
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
            this.$http.post('/login', data, {
                headers: {
                    'Content-Type': 'application/json'
                }
            }).then((response) => {
                console.log(response);
                window.location.replace('/dashboard');
            }, (response) => {
                this.error = true;
                this.errorMessage = "Wrong email/password.";
            });
        }
    }

}
</script>
