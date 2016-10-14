<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <form>
                    <p class="error" v-if="error">{{ errorMessage }}</p>
                    <input type="text" placeholder="Username" v-model="username" v-on:keyup.enter="submit" required>
                    <input type="password" placeholder="Password" v-model="password" v-on:keyup.enter="submit" required>
                    <input type="password" placeholder="Confirm Password" v-model="confirmPassword" v-on:keyup.enter="submit" required>
                    <input type="email" placeholder="Email" v-model="email" v-on:keyup.enter="submit" required>
                    <input type="submit" value="Submit" v-on:click="submit">
                    <p><a href="#" v-on:click="display('login')">Already have an account? Login now.</a></p>
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
            username: '',
            password: '',
            confirmPassword: '',
            email: '',

            error: false,
            errorMessage: ''
        }
    }, 
    methods: {
        submit(e) {
            e.preventDefault();
            let data = {};

            if (this.password != this.confirmPassword) {
                this.error = true;
                this.errorMessage = "Your passwords didn't match.";
            } else {
                this.error = false;
            }

            data.name = this.username;
            data.password = this.password;
            data.email = this.email;

            this.$http.post('/api/users', data).then((response) => {
                this.currentView = "signup-successful";
            }, (response) => {
                this.error = true;
                this.errorMessage = "Couldn't submit form."
            });
        }
    }

}
</script>