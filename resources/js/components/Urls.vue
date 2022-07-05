<template>
    <template>
        <div>
            <h4 class="text-center">All Urls</h4><br/>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Author</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="book in books" :key="book.id">
                    <td>{{ book.id }}</td>
                    <td>{{ book.name }}</td>
                    <td>{{ book.author }}</td>
                    <td>{{ book.created_at }}</td>
                    <td>{{ book.updated_at }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <router-link :to="{name: 'editbook', params: { id: book.id }}" class="btn btn-primary">Edit
                            </router-link>
                            <button class="btn btn-danger" @click="deleteBook(book.id)">Delete</button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>

            <button type="button" class="btn btn-info" @click="this.$router.push('/books/add')">Add Book</button>
        </div>
    </template>
</template>

<script>
export default {
    name: "Urls",
    data() {
        return {
            books: []
        }
    },
    created() {
        this.$axios.get('/sanctum/csrf-cookie').then(response => {
            this.$axios.get('/api/books')
                .then(response => {
                    this.books = response.data;
                })
                .catch(function (error) {
                    console.error(error);
                });
        })
    },
}
</script>

<style scoped>

</style>
