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
                    <p>{{ genres }}</p>
                </div>
                <div class="card-action">
                    <a class="button button--square button--info button--transparent">
                        Edit
                        <i class="material-icons high">edit</i>
                    </a>
                    <a class="button button--square button--info button--transparent"
                        @click="display('contests', amv)">
                        Contests
                        <i class="material-icons high">star</i>
                    </a>
                    <a class="button button--square button--danger button--transparent"
                        @click="deleteAMV" v-bind:disabled="deleteButtonDisabled" v-bind:class="deleteButtonClasses">
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
        }

        props: ['amv', 'user', 'display'],

        computed: {
            /**
            * Concatenated genres of the AMV, separated by dash
            * @returns {String}
            */
            genres: function() {
                return this.amv.genres.map(function(elem) {
                        return elem.name; 
                    }).join(' - ');
            },
            /**
            * Possible delete button classes, depending on the value of deleteButtonStatus
            * @returns {Object}
            */
            deleteButtonClasses: function() {
                return {
                    'button--loading': this.deleteButtonStatus === 'Deleting...',
                    'button--error': this.deleteButtonStatus === 'Failed'
                }
            }  
        },

        methods: {
            deleteAMV: function() {
                this.buttonDisabled = true;
                this.buttonStatus = 'Deleting...';

                this.$http.delete('/amvs/' + this.amv.id, {
                    headers: {
                        'Content-Type': 'application/json'
                    }
                }).then((response) => {
                    this.remove(this.contest);
                }, (response) => {
                    this.buttonStatus = 'Failed';
                });
            }
        }
    }
</script>