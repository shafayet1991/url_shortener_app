<template>
    <div>
        <h4 class="text-center">Add URL</h4>
        <div class="row">
            <div class="col-md-12">
                <form @submit.prevent="addUrl">
                    <div class="form-group">
                        <label>URL</label>
                        <input type="url" class="form-control" v-model="url.currentUrl">
                    </div>
                    <div class="form-group">
                        <label>New Url</label>
                        <input type="url" class="form-control" disabled v-model="url.newUrl">
                    </div>
                    <button type="submit" class="btn btn-primary">Add URL</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            url: {
                currentUrl: '',
                newUrl: ''
            }
        }
    },
    methods: {
        addUrl() {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('/api/urls/shorten', {
                    shortLink: this.url.newUrl,
                    url: this.url.currentUrl
                }).then(response => {
                        //this.$router.push({name: 'urls'})
                        console.log(response);
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
        }
    },
}
</script>

<style scoped>

</style>
