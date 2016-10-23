<template>
    <section class="dashboard__form is-right">
        <h3>Create a new AMV</h3>
        <div class="row">
            <form class="col-xs-12">
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <label for="title">Title</label>
                        <input id="title" placeholder="AMV Title" type="text" required>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <label for="music">Music</label>
                        <input id="music" placeholder="Music used in the AMV" type="text" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <label for="animes">Animes</label>
                        <input id="animes" placeholder="Animes used" type="text" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <label for="description">Description</label>
                        <textarea id="description" placeholder="Description" @input="resizeTextarea()"></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <label for="video">Video Link</label>
                        <input id="video" placeholder="Youtube / Vimeo Link" type="text">
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <label for="download">Download Link</label>
                        <input id="download" placeholder="Google Drive Download Link" type="text">
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <label>Genres</label>
                        <multiselect 
                            :value="genres" 
                            :options="genreList"
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
                            <input type="checkbox" class="filled-in" id="published" />
                            <label for="published">Published</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <label>Poster</label>
                        <p>Recommended: 488x642px</p>
                        <label for="poster" class="button button--square button--input z-depth-1">
                            <i class="material-icons">cloud_upload</i>
                            File {{ poster }}
                        </label>
                        <input type="file" id="poster" @change="showFile('poster', $event)">
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <label>Background</label>
                        <p>Recommended: 1920x900px</p>
                        <label for="bg" class="button button--square button--input z-depth-1">
                            <i class="material-icons">cloud_upload</i>
                            File {{ bg }}
                        </label>
                        <input type="file" id="bg" @change="showFile('bg', $event)">
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <input id="submit" type="submit" value="Create" 
                            class="button button--square button--primary large z-depth-1">
                        <input id="cancel" type="submit" value="Cancel" 
                            @click="display('index')" class="button button--square button--input large z-depth-1">
                </div>
            </form>
        </div>
    </section>
</template>

<script>
    import Multiselect from 'vue-multiselect/lib/Multiselect.vue';

    export default {
        data() {
            return {
                genres: null,
                poster: '',
                bg: '',
                genreList: []
            }
        },

        props: ['user', 'display'],

        components: {
            Multiselect
        },

        mounted: function () {
            this.loadGenres();
        },

        methods: {
            loadGenres() {
                this.$http.get('/api/genres').then((response) => {
                    this.genreList = response.body;
                }, (response) => {
                    console.log("Couldn't load genres.");
                });
            },

            // Automatically expand (or reduce) textarea on user input.
            resizeTextarea() {
                const textarea = event.currentTarget;
                textarea.style.height = "";
                textarea.style.height = textarea.scrollHeight + "px";
            },

            // Get the submitted filepath, and strip out path. 'C:\Pictures\foo.png' -> 'foo.png'
            showFile(type, event) {
                this[type] = ' | ' + event.currentTarget.value.replace(/^.*?([^\\\/]*)$/, '$1');
            },

            updateSelected(value) {
                this.genres = value;
            }
        }
        
    }
</script>