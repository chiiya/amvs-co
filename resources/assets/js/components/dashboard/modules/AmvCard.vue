<template>
    <div class="col-xs-12 col-lg-6">
        <div class="card dashboard__card horizontal">
            <div class="card-image">
                <img v-bind:src="amv.poster">
            </div>
            <div class="card-stacked">
                <div class="card-content">
                    <h2><a v-bind:href="'/user/' + user.name + '/' + amv.url">{{ amv.title }}</a></h2>
                    <p>{{ amv.date }}</p>
                    <div class="labels">
                    <span v-for="genre in amv.genres"><span class="label label-primary">{{ genre.name }}</span> &nbsp;</span>
                    </div>
                    <p>{{ animes }}</p>
                </div>
                <div class="card-action">
                    <router-link
                        :to="'/dashboard/amvs/' + amv.id" 
                        class="button button--square button--info button--transparent">
                        Edit
                        <i class="material-icons high">edit</i>
                    </router-link>
                    <router-link
                        :to="'/dashboard/amvs/' + amv.id + '/contests'"
                        class="button button--square button--info button--transparent">
                        Contests
                        <i class="material-icons high">star</i>
                    </router-link>
                    <a class="button button--square button--danger button--transparent"
                        @click="deleteAmv" v-bind:disabled="deleteButtonDisabled" v-bind:class="deleteButtonClasses">
                        {{ deleteButtonStatus }}
                        <i v-if="!deleteButtonDisabled" class="material-icons">close</i>  
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        data() {
            return {
                /**
                * String value of the delete button.
                * @type {String}
                */
                deleteButtonStatus: 'Delete',
                /**
                * Disabled status of the delete button.
                * @type {Boolean}
                */
                deleteButtonDisabled: false
            }
        },

        props: ['amv'],

        computed: {
            /**
            * Truncate anime sources if necessary
            * @returns {String}
            */
            animes() {
                if (this.amv.animes.length > 40) {
                    return this.amv.animes.substring(0, 40) + '...';
                }
                return this.amv.animes;
            },
            user() {
                return this.$store.state.user;
            },
            /**
            * Possible delete button classes, depending on the value of deleteButtonStatus
            * @returns {Object}
            */
            deleteButtonClasses() {
                return {
                    'button--loading': this.deleteButtonStatus === 'Deleting...',
                    'button--error': this.deleteButtonStatus === 'Failed'
                }
            }
        },

        methods: {
            /**
            * Delete currently displayed AMV
            */
            deleteAmv() {
                this.deleteButtonDisabled = true;
                this.deleteButtonStatus = 'Deleting...';
                this.$store.dispatch('DESTROY_AMV', this.amv.id)
                    .then((response) => {
                        this.deleteButtonStatus = 'Deleted';
                    })
                    .catch((error) => {
                        this.deleteButtonStatus = 'Failed';
                    });
            }
        }
    }
</script>