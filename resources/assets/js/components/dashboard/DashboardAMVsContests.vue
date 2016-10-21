<template>
    <section class="is-right">
        <h3>Edit Contests: {{ amv.title }} </h3>
        <div class="row">
            <form class="col-xs-12">
                <contest 
                    v-for="contest in amv.contests" 
                    :contest="contest" 
                    :contest-list="contestList"
                    :remove="remove"></contest>
                <newcontest 
                    :contest-list="contestList"
                    :add="add"
                    :remove="remove"></newcontest>
            </form>
        </div>
    </section>
</template>

<script>
    import ContestSelect from './ContestSelect.vue';
    import NewContestSelect from './NewContestSelect.vue';

    export default {
        data() {
            return {
                contestList: [],
                updatedAMV: {}
            }
        },

        props: ['user', 'display', 'amv'],

        components: {
            contest: ContestSelect,
            newcontest: NewContestSelect
        },

        mounted: function () {
            this.loadContests();
            this.updatedAMV = this.amv;
        },

        methods: {
            loadContests() {
                this.$http.get('/api/contests').then((response) => {
                    this.contestList = response.body;
                }, (response) => {
                    console.log("Couldn't load contests.");
                });
            },
            add(contest) {
                this.updatedAMV.contests.push(contest);
            },
            remove(contest) {
                var index = this.updatedAMV.contests.indexOf(contest);
                console.log(index);                   
                if (index>=0) {
                    this.updatedAMV.contests.splice(index, 1);
                }
            }
        }
        
    }
</script>