<template>
    <div class="userbar">
        <div class="container">
        <ul v-cloak>
            <li>
                <img v-bind:src="user.avatar || '/img/avatars/default.jpg'">
                 {{ user.name }}
            </li>
            <li>
                <h3>Location</h3>
                <p>{{ user.location || '-' }}</p>
            </li>
            <li>
                <h3>Studio</h3>
                <p>{{ user.studio || '-'}}</p>
            </li>
            <li>
                <h3>Website</h3>
                <p v-if="hasWebsite" ><a v-bind:href="user.website">Visit Website</a></p>
                <p v-else>-</p>
            </li>
        </ul>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                user: {}
            }
        },
        props: ['userObj'],

        computed: {
            hasWebsite: function() {
                /* Check if website is defined AND is a non-empty string */
                return (typeof this.user.website !== 'undefined') && (typeof this.user.website !== 'null')
                    && (typeof this.user.website === 'string') && (this.user.website.length > 0);
            }
        },

        watch: {
            userObj: function (val) {
    	        this.user = val;
            }
        }
    }
</script>