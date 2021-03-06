<template>
    <section class="dashboard__form form--profile is-right">
        <loading></loading>
        <div class="row" v-show="!loading">
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
                            <input type="file" id="avatar" @change="showImage">
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
                        <button id="cancel" @click="goBack" 
                            class="button button--square button--transparent button--primary">
                            {{ cancelButtonStatus }}</button>
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
    </section>
</template>

<script>
    import LoadingSpinner from '../modules/LoadingSpinner.vue'
    import { setNav } from '../../../util/functions';

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
                submitErrors: [],
                password: '',
                confirmPassword: ''
            }
        },

        computed: {
           /**
            * Get the avatar URL. In case the user doesn't have an avatar, show default one.
            * @returns {String}
            */
            avatar() {
                return this.userObject.avatar || '/img/avatars/default.jpg'
            },

            /**
             * Current loading status (loading is true if data is being loaded asynchronously)
             * @returns {Boolean}
             */
            loading() {
                return this.$store.state.loading;
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

            /**
             * Only show errors when the attempt to save just failed.
             * @returns {Boolean}
             */ 
            showErrors: function() {
                return this.submitErrors.length > 0 && this.saveButtonStatus === 'Failed';
            }
        },

        components: {
            loading: LoadingSpinner
        },

        mounted: function() {
            // Set Breadcrumbs
            this.$store.commit('SET_PARENT', {
                title: 'Edit Profile',
                path: '/dashboard/profile'
            });
            setNav(-1);

            // Fetch current user from Vuex or API
            this.$store.dispatch('FETCH_USER')
                .then((response) => {
                    // this.userObject = response would create a copy by reference! All changes to userObject
                    // would cascade down to the store object as well.
                    // JSON.parse(JSON.stringify) in order to create a new copy of the user, not by reference.
                    // Needs to be done so that the user changes can be disregarded once he clicks 'Cancel'
                    this.userObject = JSON.parse(JSON.stringify(response));
                });
            
            // Watch all input field for user input
            this.baywatch(['userObject.location', 'userObject.studio', 'userObject.website', 
                'userObject.email', 'password'], this.notifyChange.bind(this));
        },

        methods: {
            /**
             * Submit the form and update the user
             */
            submit() {
                // Reset errors
                this.submitErrors = [];

                this.saveButtonDisabled = true;
                this.saveButtonStatus = 'Saving...';

                const elAvatar = document.getElementById('avatar');
                const files = elAvatar.files;
                const formData = new FormData();

                formData.append('location', this.userObject.location);
                formData.append('studio', this.userObject.studio);
                formData.append('website', this.userObject.website);

                // Check whether email field is empty
                if (this.userObject.email.length === 0) {
                    this.saveButtonStatus = 'Failed';
                    this.pushError("You must specify an email address.");
                    return;
                }
                formData.append('email', this.userObject.email);

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

                this.$store.dispatch('PATCH_USER', {
                        id: this.userObject.id,
                        data: formData
                })
                .then((response) => {
                    this.userObject.avatar = response.avatar;
                    this.$store.commit('SET_USER', this.userObject);
                    this.updateAvatar(response.avatar);
                    this.saveButtonStatus = 'Saved';
                    this.cancelButtonStatus = 'Back';
                })
                .catch((error) => {
                    this.saveButtonStatus = 'Failed';
                    if (typeof error.body === 'object') {
                        for (let key in error.body) {
                            this.submitErrors.push(error.body[key][0]);
                        }
                    } else {
                        this.submitErrors.push(error.status + ": Server Error. Please try again later.");
                    }
                });

            },

            /**
             * Validates the avatar image
             * @param {Object file: avatar file submitted by user}
             * @returns {Boolean: file is valid or not}
             */
            validateImage(file) {
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

            /**
             * Watches an array of properties for changes
             * @param {Array props: properties to be watched}
             * @param {Function watcher: method to be executed once a change has been detected}
             */
            baywatch: function(props, watcher) {
                var iterator = function(prop) {
                    this.$watch(prop, watcher);
                };
                props.forEach(iterator, this);
            },

            /**
             * Once a change to one of the input fields has been made, reset buttons so that the user can
             * save additional changes
             */
            notifyChange: function() {
                if (this.saveButtonDisabled) {
                    this.saveButtonDisabled = false;
                    this.saveButtonStatus = 'Save';
                    this.cancelButtonStatus = 'Cancel';
                }
            },

            /**
             * Show a preview of the avatar image submitted by the user
             */
            showImage() {
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

            /**
             * Push a new error onto the submitErrors array
             * @param {String error}
             */
            pushError: function(error) {
                if (this.submitErrors.indexOf(error) === -1) {
                    this.submitErrors.push(error);
                }
            },

            /**
             * Once the new avatar has been uploaded, update all instances on the current page as well
             * @param {String path: URL of the new avatar}
             */
            updateAvatar: function(path) {
                const avatars = document.getElementsByClassName('avatar');
                for (let i=0; i<avatars.length; i++) {
                    avatars[i].src = path;
                }
            },

            /**
             * When the user clicks on 'Back'/'Cancel', return to previous page
             */
            goBack() {
                this.$router.go(-1);
            }
        }
    }
</script>