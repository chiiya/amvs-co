<template>
    <section class="dashboard__form is-right">
        <h3>Create a new AMV</h3>
        <div class="row">
            <div class="col-xs-12">
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <label for="title">Title</label>
                        <input id="title" v-model="amvObject.title" placeholder="AMV Title" type="text" required>
                        <p v-if="showError('title')" class="error"> {{ errors.title }}</p>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <label for="music">Music</label>
                        <input id="music" v-model="amvObject.music" placeholder="Music used in the AMV" type="text" required>
                        <p v-if="showError('music')" class="error"> {{ errors.music }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <label for="animes">Anime</label>
                        <input id="animes" v-model="amvObject.animes" placeholder="List all anime sources" type="text" required>
                        <p v-if="showError('animes')" class="error"> {{ errors.animes }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <label for="description">Description</label>
                        <textarea id="description" v-model="amvObject.description" placeholder="Description" @input="resizeTextarea()"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <label for="video">Video Link</label>
                        <input id="video" v-model="amvObject.video" placeholder="Youtube / Vimeo Link" type="text">
                        <p v-if="showError('video')" class="error"> {{ errors.video }}</p>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <label for="download">Download Link</label>
                        <input id="download" v-model="amvObject.download" placeholder="Google Drive Download Link" type="text">
                        <p v-if="showError('download')" class="error"> {{ errors.download }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <label>Genres</label>
                        <multiselect 
                            :value="amvObject.genres" 
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
                            <input type="checkbox" v-model="amvObject.published" class="filled-in" id="published" />
                            <label for="published">Published</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <label>Poster</label>
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
                        <button id="cancel" @click="display('index')" 
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
    import { matchYoutubeUrl, matchVimeoUrl, matchDriveUrl } from '../../../util/functions';

    export default {
        data() {
            return {
                amvObject: {
                    title: '',
                    music: '',
                    animes: '',
                    video: '',
                    videoHost: '',
                    videoCode: '',
                    download: '',
                    driveId: '',
                    description: '',
                    published: false,
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
                poster: '',
                bg: '',
                saveButtonDisabled: false,
                saveButtonStatus: 'Save',
                cancelButtonStatus: 'Cancel'
            }
        },

        props: ['display'],

        computed: {
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
            genres() {
                return this.$store.state.genres;
            }            
        },

        components: {
            Multiselect
        },

        mounted: function () {
            this.loadGenres();
            this.baywatch(['amvObject.title', 'amvObject.music', 'amvObject.animes', 'amvObject.video',
                'amvObject.download', 'amvObject.genres', 'amvObject.published'
                ], this.notifyChange.bind(this));
        },

        methods: {
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
                const textarea = event.currentTarget;
                textarea.style.height = "";
                textarea.style.height = textarea.scrollHeight + "px";
            },

            /**
            * Get the submitted filepath, and strip out the path. 'C:\Pictures\foo.png' -> 'foo.png'
            * @params {String: poster/bg, Object}
            */
            showFile(type, event) {
                this[type] = ' | ' + event.currentTarget.value.replace(/^.*?([^\\\/]*)$/, '$1');
                this.notifyChange();
            },

            /**
            * Update the AMV genres with values from multiselect
            * @params {Array: list of genre objects}
            */
            updateSelected(value) {
                this.amvObject.genres = value;
            },

            /**
            * Submit the form and create new AMV entry
            */
            submit: function() {
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
                    window.scrollTo(0, 0);
                    return;
                }

                formData.append('title', this.amvObject.title);
                formData.append('music', this.amvObject.music);
                formData.append('animes', this.amvObject.animes);
                formData.append('description', this.amvObject.description);
                formData.append('video', this.amvObject.video);
                formData.append('videoHost', this.amvObject.videoHost);
                formData.append('videoCode', this.amvObject.videoCode);
                formData.append('download', this.amvObject.download);
                formData.append('driveId', this.amvObject.driveId);
                formData.append('published', this.amvObject.published);
                
                // If genres are specified, push all genre IDs onto new array and append
                if (this.amvObject.genres.length > 0) {
                    let genreArray = [];
                    for (let i=0; i<this.amvObject.genres.length; i++) {
                        genreArray.push(this.amvObject.genres[i].id);
                    }
                    formData.append('genres', JSON.stringify(genreArray));
                }

                if (posterFiles && posterFiles[0]) {
                    formData.append('poster', posterFiles[0]);
                }
                if (bgFiles && bgFiles[0]) {
                    formData.append('bg', bgFiles[0]);
                }

                this.$store.dispatch('STORE_AMV', formData)
                    .then(() => {
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
                            this.submitErrors.push("Server Error. Please try again later.");
                        }
                        
                    });
            },

            /**
            * Validates the input form
            * @returns {Boolean} Form is valid or not
            */
            validateForm: function(posterFiles, bgFiles) {
                let valid = true;

                // Check if title is present, and then if that title has already been used in one of the user's AMVs.
                if (!this.amvObject.title.length > 0) {
                    this.errors.title = "Please specify a title for your AMV.";
                    valid = false;
                } else {
                    for (let i=0; i<this.$store.state.amvs.length; i++) {
                        if (this.$store.state.amvs[i].title.indexOf(this.amvObject.title) > -1) {
                            this.errors.title = "You already used this title for another AMV. Your title needs to be unique among your AMVs.";
                            valid = false;
                        }
                    }
                }
                // Check if anime list is present
                if (!this.amvObject.animes.length > 0) {
                    this.errors.animes = "Please list the anime sources used in the AMV.";
                    valid = false;
                }
                // Check if music has been specified
                if (!this.amvObject.music.length > 0) {
                    this.errors.music = "Please specify the music used in the AMV.";
                    valid = false;
                }
                // Match input video URL to either Youtube or Vimeo and extract video ID
                if (this.amvObject.video != null &&  this.amvObject.video.length > 0) {
                    const youtube = matchYoutubeUrl(this.amvObject.video);
                    const vimeo = matchVimeoUrl(this.amvObject.video);

                    if (!youtube && !vimeo) {
                        this.errors.video = "Your video URL is not valid. Please use a valid Youtube or Vimeo URL.";
                        valid = false;
                    } else if (youtube) {
                        this.amvObject.videoHost = 'Youtube';
                        this.amvObject.videoCode = youtube;
                    } else if (vimeo) {
                        this.amvObject.videoHost = 'Vimeo';
                        this.amvObject.videoCode = vimeo;
                    }
                }
                // Match input download URL to Google Drive and extract file ID
                if (this.amvObject.download != null && this.amvObject.download.length > 0) {
                    const drive = matchDriveUrl(this.amvObject.download);
                    if (!drive) {
                        this.errors.download = "Your download URL is not valid. Please use a valid Google Drive shared file URL.";
                        valid = false;
                    }
                    this.amvObject.driveId = drive;
                }
                // Check if poster is a valid image file and no larger than 500 KB
                if (posterFiles && posterFiles[0]) {
                    if (!(/\.(png|jpeg|jpg)$/i).test(posterFiles[0].name)) {
                        this.errors.poster = "Must be a valid image file (.PNG, .JPG or .JPEG allowed).";
                        valid = false;
                    }
                    if (posterFiles[0].size/1024 > 500) {
                        this.errors.poster("Image must be smaller than 500KB.");
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
                        this.errors.bg("Image must be smaller than 500KB.");
                        valid = false;
                    }
                }

                return valid;
            },

            /**
            * Checks whether any errors should be displayed to the user.
            * @params {String: Input field that may display error}
            * @returns {Boolean}
            */
            showError: function(field) {
                return this.errors[field].length > 0 && this.saveButtonStatus === 'Failed';
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
            }
        }
        
    }
</script>