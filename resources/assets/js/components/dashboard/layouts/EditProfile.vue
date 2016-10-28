<template>
    <div>
    <header class="dashboard__title valign-wrapper">
            <a href="#" class="breadcrumb">Edit Profile</a>
    </header>
    <section class="dashboard__form form--profile is-right">
        <div class="row">
            <div class="col-xs-12">
                <h3>General Information</h3>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <label for="location">Location</label>
                        <input id="location" v-model="userObject.location" placeholder="Location" type="text">
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <label for="studio">Studio</label>
                        <input id="studio" v-model="userObject.studio" placeholder="AMV Studio" type="text">
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <label for="website">Website</label>
                        <input id="website" v-model="userObject.website" placeholder="Personal Website" type="text">
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <label>Avatar</label>
                        <img v-bind:src="avatar" />
                        <div class="img--description">
                            <p>Maximum size: 150 KB</p>
                            <label for="avatar" class="button button--square button--input z-depth-1">
                                <i class="material-icons">cloud_upload</i>
                                Upload Avatar
                            </label>
                            <input type="file" id="avatar" @change="showImage($event)">
                        </div>
                    </div>
                </div>
                <h3>Account Information</h3>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <label for="email">Email</label>
                        <input id="email" v-model="userObject.email" placeholder="New Email" type="text" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <label for="password">New Password</label>
                        <input id="password" v-model="password" placeholder="New Password (Optional)" type="password">
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <label for="confirm">Confirm Password</label>
                        <input id="confirm" v-model="confirmPassword" placeholder="Confirm Password" type="password">
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <button id="submit" v-bind:disabled="saveButtonDisabled"
                            class="button button--square z-depth-1"
                            v-bind:class="saveButtonClasses"
                            @click="submit">
                            {{ saveButtonStatus }}</button>
                        <button id="cancel" @click="display('overview')" 
                            class="button button--square button--transparent button--primary">
                            {{ cancelButtonStatus }}</button>
                    </div>
                </div>
                <div v-if="showErrors" class="row">
                    <div class="col-xs-12">
                        <p v-for="error in validationErrors" class="error">
                            {{ error }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </div>
</template>

<script>

    export default {
        data() {
            return {
                userObject: {
                    location: '',
                    studio: '',
                    website: '',
                    avatar: '',
                    email: ''
                },
                saveButtonDisabled: false,
                saveButtonStatus: 'Save',
                cancelButtonStatus: 'Cancel',
                validationErrors: [],
                password: '',
                confirmPassword: ''
            }
        },

        props: ['user', 'display', 'updateAvatar'],

        computed: {
            /**
            * Get the avatar URL. In case the user doesn't have an avatar, show default one.
            * @returns {String}
            */
            avatar: function() {
                return this.user.avatar || '/img/avatars/default.jpg';
            },
            /**
            * Possible Save button classes, depending on the value of saveButtonStatus
            * @returns {Object}
            */
            saveButtonClasses: function() {
                return {
                    'button--primary': this.saveButtonStatus === 'Save' || this.saveButtonStatus === 'Saving...',
                    'button--loading': this.saveButtonStatus === 'Saving...',
                    'button--success': this.saveButtonStatus === 'Saved',
                    'button--error': this.saveButtonStatus === 'Failed'
                }
            },
            showErrors: function() {
                return this.validationErrors.length > 0 && this.saveButtonStatus === 'Failed';
            }
        },

        mounted: function() {
            this.userObject = this.user;
            this.baywatch(['user.location', 'user.studio', 'user.website', 'user.email',
                'password'], this.notifyChange.bind(this));
        },

        methods: {
            submit: function() {
                this.saveButtonDisabled = true;
                this.saveButtonStatus = 'Saving...';

                const elAvatar = document.getElementById('avatar');
                const files = elAvatar.files;
                const formData = new FormData();

                formData.append('location', this.user.location);
                formData.append('studio', this.user.studio);
                formData.append('website', this.user.website);

                // Check whether email field is empty
                if (this.user.email.length === 0) {
                    this.saveButtonStatus = 'Failed';
                    this.pushError("You must specify an email address.");
                    return;
                }
                formData.append('email', this.user.email);

                // Check whether a new password has been entered and if they match
                if (this.password.length > 0) {
                    if (this.password !== this.confirmPassword) {
                        this.saveButtonStatus = 'Failed';
                        this.pushError("Your passwords didn't match.");
                        return;
                    }
                    formData.append('password', this.password);
                }

                // Check if avatar file has been selected
                if (files.length) {
                    if (!this.validateImage(files[0])) {
                        this.saveButtonStatus = 'Failed';
                        return;
                    }
                    formData.append('avatar', files[0]);
                }

                // Laravel bug: multipart/form-data needs to be POST. Specify custom method PUT.
                formData.append('_method', 'PUT');
                this.$http.post('/users/' + this.user.id, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Accept': 'application/json'
                    }
                }).then((response) => {
                    this.updateAvatar(response.body.avatar);
                    this.saveButtonStatus = 'Saved';
                    this.cancelButtonStatus = 'Back';
                }, (response) => {
                    this.saveButtonStatus = 'Failed';
                    for (let key in response.body) {
                        this.pushError(response.body[key][0]);
                    }
                });
            },
            validateImage: function(file) {
                if (!(/\.(png|jpeg|jpg)$/i).test(file.name)) {
                    this.pushError("Must be a valid image file (.PNG, .JPG or .JPEG allowed).");
                    return false;
                }
                if (!(file.size/1024 < 150)) {
                    this.pushError("Image must be smaller than 150KB.");
                    return false;
                }
                return true;
            },
            baywatch: function(props, watcher) {
                var iterator = function(prop) {
                    this.$watch(prop, watcher);
                };
                props.forEach(iterator, this);
            },
            notifyChange: function() {
                if (this.saveButtonDisabled) {
                    this.saveButtonDisabled = false;
                    this.saveButtonStatus = 'Save';
                    this.cancelButtonStatus = 'Cancel';
                }
            },
            showImage: function(event) {
                const elAvatar = document.getElementById('avatar');
                const files = elAvatar.files;
                let vm = this;
                
                if (files && files[0]) {
                    const reader = new FileReader();
                    reader.onload = function() {
                        vm.userObject.avatar = reader.result;
                    }
                    reader.readAsDataURL(files[0]);
                    this.notifyChange();
                }
            },
            pushError: function(error) {
                if (this.validationErrors.indexOf(error) === -1) {
                    this.validationErrors.push(error);
                }
            }
        }
    }
</script>