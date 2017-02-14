<template>
    <section class="dashboard__form is-right">
        <loading></loading>
        <p v-if="!loading && !authorized" class="info">Unauthorized. Access was denied because you are not the owner of this AMV. Please make sure the URL you entered is correct, or <router-link to="/dashboard/amvs">go back to your AMV Overview</router-link>.</p>
        <h3 v-if="!loading && authorized">Edit AMV: {{ updatedAMV.title }}</h3>
        <div class="row" v-show="!loading && authorized">
            <div class="col-xs-12">
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <label for="title">Title</label>
                        <input id="title" v-model="updatedAMV.title" placeholder="AMV Title" type="text" required>
                        <p v-if="showError('title')" class="error"> {{ errors.title }}</p>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <label for="music">Music</label>
                        <input id="music" v-model="updatedAMV.music" placeholder="Music used in the AMV" type="text" required>
                        <p v-if="showError('music')" class="error"> {{ errors.music }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <label for="animes">Anime</label>
                        <input id="animes" v-model="updatedAMV.animes" placeholder="List all anime sources" type="text" required>
                        <p v-if="showError('animes')" class="error"> {{ errors.animes }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <label for="description">Description</label>
                        <textarea id="description" v-model="updatedAMV.description" placeholder="Description" @input="resizeTextarea()"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <label for="video">Video Link</label>
                        <input id="video" v-model="updatedAMV.video" placeholder="Youtube / Vimeo Link" type="text">
                        <p v-if="showError('video')" class="error"> {{ errors.video }}</p>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <label for="download">Download Link</label>
                        <input id="download" v-model="updatedAMV.download" placeholder="Google Drive Download Link" type="text">
                        <p v-if="showError('download')" class="error"> {{ errors.download }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <label>Genres</label>
                        <multiselect 
                            :value="updatedAMV.genres" 
                            :options="genres"
                            :multiple="true" 
                            @input="updateSelected"
                            :close-on-select="false" 
                            :clear-on-select="false" 
                            placeholder="Choose genres" 
                            label="name" 
                            track-by="name">
                        </multiselect>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                            <label>Publishment Status</label>
                            <p class="publishment">Unpublished entries aren't listed, but can be sent in to contests</p>
                            <input type="checkbox" v-model="updatedAMV.published" class="filled-in" id="published" />
                            <label for="published">Published</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <label>Poster</label>
                        <img v-if="updatedAMV.poster" v-bind:src="updatedAMV.poster" class="preview" />
                        <p>Recommended: 1:1.5 width:height ratio, max 500KB</p>
                        <label for="poster" class="button button--square button--input z-depth-1">
                            <i class="material-icons">cloud_upload</i>
                            File {{ poster }}
                        </label>
                        <input type="file" id="poster" @change="showFile('poster', $event)">
                        <p v-if="showError('poster')" class="error"> {{ errors.poster }}</p>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <label>Background</label>
                        <img v-if="updatedAMV.bg" v-bind:src="updatedAMV.bg" class="preview" />
                        <p>Recommended: 1920x900px</p>
                        <label for="bg" class="button button--square button--input z-depth-1">
                            <i class="material-icons">cloud_upload</i>
                            File {{ bg }}
                        </label>
                        <input type="file" id="bg" @change="showFile('bg', $event)">
                        <p v-if="showError('bg')" class="error"> {{ errors.bg }}</p>
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
                <div v-if="submitErrors.length > 0" class="row">
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
    import Multiselect from 'vue-multiselect/lib/Multiselect.vue';
    import { matchYoutubeUrl, matchVimeoUrl, matchDriveUrl, setNav } from '../../../util/functions';
    import LoadingSpinner from '../modules/LoadingSpinner.vue'

    export default {
        data() {
            return {
                updatedAMV: {
                    genres: []
                },
                errors: {
                    title: '',
                    music: '',
                    animes: '',
                    video: '',
                    download: '',
                    bg: '',
                    poster: ''
                },
                submitErrors: [],
                authorized: true,
                poster: '',
                bg: '',
                saveButtonDisabled: false,
                saveButtonStatus: 'Save',
                cancelButtonStatus: 'Cancel'
            }
        },

        computed: {
           /**
            * Possible Save button classes, depending on the value of saveButtonStatus
            * @returns {Object}
            */
            saveButtonClasses() {
                return {
                    'button--primary': this.saveButtonStatus === 'Save' || this.saveButtonStatus === 'Saving...',
                    'button--loading': this.saveButtonStatus === 'Saving...',
                    'button--success': this.saveButtonStatus === 'Saved',
                    'button--error': this.saveButtonStatus === 'Failed'
                }
            },

            /**
             * Get a list of all available genres from Vuex
             * @returns {Array}
             */
            genres() {
                return this.$store.state.genres;
            },

            /**
             * Get the currently displayed AMV data by route ID parameter
             * @returns {Object}
             */
            amv() {
                return this.$store.state.amvs[this.$route.params.id];
            },

            /**
             * Current loading status (loading is true if data is being loaded asynchronously)
             * @returns {Boolean}
             */
            loading() {
                return this.$store.state.loading;
            }      
        },

        components: {
            Multiselect,
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
                // Fetch AMV from Vuex. If not present in Vuex an API call gets made
                this.$store.dispatch('FETCH_AMV', this.$route.params.id)
                    .then((response) => {
                        // this.updatedAMV = response would create a copy by reference! All changes to updatedAMV
                        // would cascade down to the store object as well.
                        // JSON.parse(JSON.stringify) in order to create a new copy of the AMV, not by reference.
                        // Needs to be done so that the user changes can be disregarded once he clicks 'Cancel'
                        this.updatedAMV = JSON.parse(JSON.stringify(response));
                        this.$nextTick(function() {
                            this.initialize();
                        });
                        
                    })
                    .catch((error) => {
                        if (error.status === 404 || error.status === 401) this.authorized = false;
                        this.submitErrors.push(error.body)
                    });
        },

        methods: {
            initialize() {
                // Only load genres from API if they aren't already stored in Vuex
                if (!this.genres.length > 0) this.loadGenres();
                // Watch input fields for user input
                this.baywatch(['updatedAMV.title', 'updatedAMV.music', 'updatedAMV.animes', 'updatedAMV.video', 'updatedAMV.download', 'updatedAMV.genres', 'updatedAMV.published'],this.notifyChange.bind(this));

                // On initial load, make the description textarea fit the description text.
                this.resizeTextarea();  
            },
           /**
            * Load a list of available genres to use for the multiselect
            */
            loadGenres() {
                this.$store.dispatch('FETCH_GENRES');
            },

           /**
            * Automaticaly expand (or reduce) the description textarea on user input
            */
            resizeTextarea() {
                let textarea = document.getElementById('description');
                    textarea.style.height = "";
                    textarea.style.height = textarea.scrollHeight + "px";
            },

           /**
            * Get the submitted filepath, and strip out the path. 'C:\Pictures\foo.png' -> 'foo.png'
            * @param {String type: 'poster'/'bg'}
            * @param {Object event: DOM node that fired the Listener}
            */
            showFile(type, event) {
                this[type] = ' | ' + event.currentTarget.value.replace(/^.*?([^\\\/]*)$/, '$1');
                this.notifyChange();
            },

           /**
            * Update the AMV genres with values from multiselect
            * @param {Array value: list of genre objects}
            */
            updateSelected(value) {
                this.updatedAMV.genres = value;
            },

           /**
            * Submit the form and create new AMV entry
            */
            submit() {
                // Reset errors
                this.submitErrors = [];
                for (let key in this.errors) {
                    this.errors[key] = '';
                }

                this.saveButtonDisabled = true;
                this.saveButtonStatus = 'Saving...';
                const formData = new FormData();

                const posterFiles = document.getElementById('poster').files;
                const bgFiles = document.getElementById('bg').files;

                // Validate form
                if (!this.validateForm(posterFiles, bgFiles)) {
                    this.saveButtonStatus = 'Failed';
                    $('html, body').animate({
                        scrollTop: $('.error:visible:first').offset().top
                    }, 1000);
                    return;
                }

                formData.append('title', this.updatedAMV.title);
                formData.append('music', this.updatedAMV.music);
                formData.append('animes', this.updatedAMV.animes);
                formData.append('description', this.updatedAMV.description);
                formData.append('video', this.updatedAMV.video);
                formData.append('videoHost', this.updatedAMV.videoHost);
                formData.append('videoCode', this.updatedAMV.videoCode);
                formData.append('download', this.updatedAMV.download);
                formData.append('driveId', this.updatedAMV.driveId);
                formData.append('published', this.updatedAMV.published);
                
                // If genres are specified, push all genre IDs onto new array and append
                if (this.updatedAMV.genres.length > 0) {
                    let genreArray = [];
                    for (let i=0; i<this.updatedAMV.genres.length; i++) {
                        genreArray.push(this.updatedAMV.genres[i].id);
                    }
                    formData.append('genres', JSON.stringify(genreArray));
                }

                if (posterFiles && posterFiles[0]) {
                    formData.append('poster', posterFiles[0]);
                }
                if (bgFiles && bgFiles[0]) {
                    formData.append('bg', bgFiles[0]);
                }

                // Laravel bug: multipart/form-data needs to be POST. Specify custom method PUT.
                formData.append('_method', 'PUT')

                this.$store.dispatch('PATCH_AMV', {
                        id: this.updatedAMV.id,
                        data: formData
                })
                    .then((response) => {
                        this.updatedAMV.poster = response.poster;
                        this.updatedAMV.bg = response.bg;
                        this.$store.commit('UPDATE_AMV', this.updatedAMV);
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
                            this.submitErrors.push(error.status + ": Server Error. Please try again later. Maybe you used a duplicate title?");
                        }
                        
                    });
            },

           /**
            * Validates the input form
            * @param {Array posterFiles: poster image submitted by user}
            * @param {Array bgFiles: background image submitted by user}
            * @returns {Boolean: Form is valid or not}
            */
            validateForm(posterFiles, bgFiles) {
                let valid = true;

                // Check if title is present
                if (!this.updatedAMV.title.length > 0) {
                    this.errors.title = "Please specify a title for your AMV.";
                    valid = false;
                }
                // Check if anime list is present
                if (!this.updatedAMV.animes.length > 0) {
                    this.errors.animes = "Please list the anime sources used in the AMV.";
                    valid = false;
                }
                // Check if music has been specified
                if (!this.updatedAMV.music.length > 0) {
                    this.errors.music = "Please specify the music used in the AMV.";
                    valid = false;
                }
                // Match input video URL to either Youtube or Vimeo and extract video ID
                if (this.updatedAMV.video != null && this.updatedAMV.video !== '' &&
                    this.updatedAMV.video.length > 0) {
                    const youtube = matchYoutubeUrl(this.updatedAMV.video);
                    const vimeo = matchVimeoUrl(this.updatedAMV.video);

                    if (!youtube && !vimeo) {
                        this.errors.video = "Your video URL is not valid. Please use a valid Youtube or Vimeo URL.";
                        valid = false;
                    } else if (youtube) {
                        this.updatedAMV.videoHost = 'Youtube';
                        this.updatedAMV.videoCode = youtube;
                    } else if (vimeo) {
                        this.updatedAMV.videoHost = 'Vimeo';
                        this.updatedAMV.videoCode = vimeo;
                    }
                }
                // Match input download URL to Google Drive and extract file ID
                if (this.updatedAMV.download != null && this.updatedAMV.dowload !== '' && 
                    this.updatedAMV.download.length > 0) {
                    const drive = matchDriveUrl(this.updatedAMV.download);
                    if (!drive) {
                        this.errors.download = "Your download URL is not valid. Please use a valid Google Drive shared file URL.";
                        valid = false;
                    }
                    this.updatedAMV.driveId = drive;
                }
                // Check if poster is a valid image file and no larger than 500 KB
                if (posterFiles && posterFiles[0]) {
                    if (!(/\.(png|jpeg|jpg)$/i).test(posterFiles[0].name)) {
                        this.errors.poster = "Must be a valid image file (.PNG, .JPG or .JPEG allowed).";
                        valid = false;
                    }
                    if (posterFiles[0].size/1024 > 500) {
                        this.errors.poster = "Image must be smaller than 500KB.";
                        valid = false;
                    }
                }
                // Check if background is a valid image file and no larger than 500 KB
                if (bgFiles && bgFiles[0]) {
                    if (!(/\.(png|jpeg|jpg)$/i).test(bgFiles[0].name)) {
                        this.errors.bg = "Must be a valid image file (.PNG, .JPG or .JPEG allowed).";
                        valid = false;
                    }
                    if (bgFiles.size/1024 > 500) {
                        this.errors.bg = "Image must be smaller than 500KB.";
                        valid = false;
                    }
                }

                return valid;
            },

           /**
            * Checks whether any errors should be displayed to the user.
            * @param {String field: Input field that may display error}
            * @returns {Boolean}
            */
            showError(field) {
                return this.errors[field].length > 0 && this.saveButtonStatus === 'Failed';
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
             * When the user clicks on 'Cancel'/'Back', go back to AMV overview page
             */
            goBack() {
                this.$router.push('/dashboard/amvs');
            }
        }
    }
</script>