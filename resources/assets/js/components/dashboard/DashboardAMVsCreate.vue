<template>
    <section>
        <h3 class="is-right">Create new AMV</h3>
        <div class="row">
            <form class="col-xs-12">
                <div class="row">
                    <div class="input-field col-xs-12 col-sm-6">
                        <input id="title" type="text" class="validate">
                        <label for="title">Title</label>
                    </div>
                    <div class="input-field col-xs-12 col-sm-6">
                        <input id="music" type="text" class="validate">
                        <label for="music">Music</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col-xs-12">
                        <input id="animes" type="text" class="validate">
                        <label for="animes">Animes</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col-xs-12">
                        <input id="description" type="text" class="validate materialize-textarea">
                        <label for="description">Description</label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col-xs-12 col-sm-6">
                        <input id="video" placeholder="Youtube / Vimeo Link" type="text" class="validate">
                        <label for="video">Video Link</label>
                    </div>
                    <div class="input-field col-xs-12 col-sm-6">
                        <input id="download" placeholder="Google Drive Download Link" type="text" class="validate">
                        <label for="download">Download Link</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <select multiple>
                            <option value="" disabled selected>Choose a genre</option>
                            <option v-for="genre in genres" v-bind:value="genre.value">
                                {{ genre.name }}
                            </option>
                        </select>
                        <label>Genre</label>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <p>Publication status. Unpublished entries aren't listed, but can be sent in to contests</p>
                        <p>
                            <input type="checkbox" class="filled-in" id="published" />
                            <label for="published">Published</label>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="file-field input-field col-xs-12 col-sm-6">
                        <div class="btn">
                            <span>File</span>
                            <input type="file" id="poster">
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" placeholder="Poster (Recommended: 488x642px)" type="text">
                        </div>
                    </div>
                    <div class="file-field input-field col-xs-12 col-sm-6">
                        <div class="btn">
                            <span>File</span>
                            <input type="file" id="bg">
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" placeholder="Background Image (Recommended: 1920x900px)" type="text">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</template>

<script>
    import DashAmvCard from './DashAmvCard.vue';

    export default {
        data() {
            return {
                amv: {}
            }
        },

        props: ['user'],

        mounted: function () {
                this.loadGenres();
            });
        },

        methods: {
            loadGenres() {
                this.$http.get('/api/amvs?user='+this.user.id).then((response) => {
                    this.loading = false;
                    this.amvs = response.body;
                    this.formatDate();
                }, (response) => {
                    console.log("Couldn't load AMVs");
                });
            }
        }
        
    }
</script>