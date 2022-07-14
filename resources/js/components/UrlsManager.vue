<template>
    <div>
        <h4 class="text-center">All Urls</h4><br/>
        <table class="table table-bordered table-responsive">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">URL</th>
                    <th scope="col">New Short URL</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Updated At</th>
<!--                    <th scope="col">Actions</th>-->
                </tr>
            </thead>

            <tbody>
            <tr v-for="url in urls" :key="url.id" scope="row">
                <td>{{ url.id }}</td>
                <td>{{ url.old_url }}</td>
                <td>{{ url.new_url }}</td>
                <td>{{ url.created_at }}</td>
                <td>{{ url.updated_at }}</td>
<!--                <td>
                    <div class="btn-group" role="group">
                        <button class="btn btn-danger" @click="deleteUrl(url.id)">Delete</button>
                    </div>
                </td>-->
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    name: "Urls",
    data() {
        return {
            urls: [],
            hideHead: false,
        }
    },
    methods: {
        getAllUrl() {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.get('/api/urls/getUrl')
                    .then(response => {
                        this.urls = response.data;
                        console.log(response.data);
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
        }
    },
    created() {
        this.getAllUrl()
    },
    beforeRouteEnter(to, from, next) {
        if (!window.Laravel.isLoggedin) {
            window.location.href = "/";
        }
        next();
    }
}
</script>

<style scoped>
td{
    word-break: break-word;
}
td:last-child, td:first-child{
    word-break: normal;
}
</style>
