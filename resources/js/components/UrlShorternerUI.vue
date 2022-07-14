<template>
    <div>
        <h4 class="text-center">Add URL</h4>
        <div class="row">
            <div class="col-md-12">
                <div v-bind:class="url.alertMessageClass" class="alert alert-dismissible fade show" v-if="url.showAlert" role="alert">
                    {{url.responseMessage}}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <form @submit.prevent="addUrl">
                    <div class="form-group">
                        <label>URL</label>
                        <input type="text" class="form-control" v-model="url.currentUrl">
                    </div>
                    <div class="form-group">
                        <label>New Url</label>
                        <input type="url" class="form-control" disabled v-model="url.newUrl">
                    </div>
                    <div class="form-group mt-2">
                        <button type="submit" class="btn btn-primary mr-2" v-if="genUrlBtn">Generate Short URL</button>
                        <button class="btn btn-primary" v-if="genUrlBtn" @click="resetForm">Reset</button>
                    </div>
                </form>
                <div class="form-group mt-2">
                    <button class="btn btn-primary mr-2" v-if="copyUrlBtn" @click="copyUrl">Copy URL</button>
                    <button class="btn btn-warning" v-if="copyUrlBtn" @click="resetForm">Reset</button>
                </div>
            </div>
        </div>
    </div>
    <div class="loading" v-if="loader">Loading&#8230;</div>
</template>

<script>
export default {
    data() {
        return {
            url: {
                currentUrl: '',
                newUrl: '',
                alertMessageClass: '',
                showAlert: '',
                responseMessage: ''
            },
            copyUrlBtn: false,
            genUrlBtn: true,
            copyTextString: 'Copy Text To Clipboard',
            loader: false,
        }
    },
    methods: {
        //Validating and formatting given url
        getUrl() {
            this.loader = true;
            let url = this.url.currentUrl;
            let protocol_ok = url.startsWith("http://") || url.startsWith("https://") || url.startsWith("ftp://");
            if(!protocol_ok){
                this.url.newUrl = "http://"+url;
                return this.url.newUrl;
            }else{
                return url;
            }
        },

        //Generating random 6 digit alphanumaric
        getRandom() {
            let text = "";
            let possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
            for (var i = 0; i < 6; i++)
                text += possible.charAt(Math.floor(Math.random() * possible.length));
            return text;
        },
        addUrl() {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.post('/api/urls/shorten', {
                    shortLink: window.location.origin + "/SL/" + this.getRandom(),
                    url: this.getUrl()
                }).then(response => {
                        this.loader = false;
                        if(response.data.status == 200){
                            if(this.url.newUrl != "" || this.url.newUrl != null) {
                                this.copyUrlBtn = true;
                                this.genUrlBtn = false;
                                this.url.newUrl = response.data.data.newUrl;
                                this.url.responseMessage = response.data.data.status;
                                this.url.alertMessageClass = 'alert-success';
                                this.url.showAlert = true;
                            } else{
                                this.copyUrlBtn = false;
                                this.genUrlBtn = true;
                            }
                        } else if(response.data.status == 202){
                            this.url.newUrl = response.data.data.newUrl;
                            this.url.responseMessage = response.data.data.status;
                            this.url.alertMessageClass = 'alert-warning';
                            this.url.showAlert = true;
                            this.copyUrlBtn = false;
                            this.genUrlBtn = true;
                        } else if(response.data.status == 203){
                            this.url.newUrl = response.data.data.newUrl;
                            this.url.responseMessage = response.data.data.status;
                            this.url.alertMessageClass = 'alert-danger';
                            this.url.showAlert = true;
                            this.copyUrlBtn = true;
                            this.genUrlBtn = false;
                        } else{
                            this.url.alertMessageClass = 'alert-danger';
                            this.url.responseMessage = "Try Again";
                            this.url.showAlert = true;
                            this.copyUrlBtn = false;
                            this.genUrlBtn = true;
                            this.loader = false;
                        }
                    })
                    .catch(function (error) {
                        console.error(error);
                        this.url.alertMessageClass = 'alert-danger';
                        this.url.responseMessage = "Try Again";
                        this.url.showAlert = true;
                        this.copyUrlBtn = false;
                        this.genUrlBtn = true;
                        this.loader = false;
                    });
            })
        },
        copyUrl() {
            /* Copy the new generated url */
            navigator.clipboard.writeText(this.url.newUrl);
            this.url.alertMessageClass = 'alert-info';
            this.url.responseMessage = "Url Copied Successfully"
            this.url.showAlert = true;
            this.copyUrlBtn = true;
            this.genUrlBtn = false;
        },
        resetForm() {
            this.copyUrlBtn= false;
            this.genUrlBtn = true;
            this.url.newUrl = null;
            this.url.currentUrl = null;
            this.loader = false,
            this.url.alertMessageClass = 'alert-info';
            this.url.responseMessage = "Form reset Successful!"
        }
    },
    created() {
    }
}
</script>

<style scoped>
/* Absolute Center Spinner */
.loading {
    position: fixed;
    z-index: 999;
    height: 2em;
    width: 2em;
    overflow: show;
    margin: auto;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
}

/* Transparent Overlay */
.loading:before {
    content: '';
    display: block;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(rgba(20, 20, 20,.8), rgba(0, 0, 0, .8));

    background: -webkit-radial-gradient(rgba(20, 20, 20,.8), rgba(0, 0, 0,.8));
}

/* :not(:required) hides these rules from IE9 and below */
.loading:not(:required) {
    /* hide "loading..." text */
    font: 0/0 a;
    color: transparent;
    text-shadow: none;
    background-color: transparent;
    border: 0;
}

.loading:not(:required):after {
    content: '';
    display: block;
    font-size: 10px;
    width: 1em;
    height: 1em;
    margin-top: -0.5em;
    -webkit-animation: spinner 150ms infinite linear;
    -moz-animation: spinner 150ms infinite linear;
    -ms-animation: spinner 150ms infinite linear;
    -o-animation: spinner 150ms infinite linear;
    animation: spinner 150ms infinite linear;
    border-radius: 0.5em;
    -webkit-box-shadow: rgba(255,255,255, 0.75) 1.5em 0 0 0, rgba(255,255,255, 0.75) 1.1em 1.1em 0 0, rgba(255,255,255, 0.75) 0 1.5em 0 0, rgba(255,255,255, 0.75) -1.1em 1.1em 0 0, rgba(255,255,255, 0.75) -1.5em 0 0 0, rgba(255,255,255, 0.75) -1.1em -1.1em 0 0, rgba(255,255,255, 0.75) 0 -1.5em 0 0, rgba(255,255,255, 0.75) 1.1em -1.1em 0 0;
    box-shadow: rgba(255,255,255, 0.75) 1.5em 0 0 0, rgba(255,255,255, 0.75) 1.1em 1.1em 0 0, rgba(255,255,255, 0.75) 0 1.5em 0 0, rgba(255,255,255, 0.75) -1.1em 1.1em 0 0, rgba(255,255,255, 0.75) -1.5em 0 0 0, rgba(255,255,255, 0.75) -1.1em -1.1em 0 0, rgba(255,255,255, 0.75) 0 -1.5em 0 0, rgba(255,255,255, 0.75) 1.1em -1.1em 0 0;
}

/* Animation */

@-webkit-keyframes spinner {
    0% {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
    }
    100% {
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}
@-moz-keyframes spinner {
    0% {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
    }
    100% {
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}
@-o-keyframes spinner {
    0% {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
    }
    100% {
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}
@keyframes spinner {
    0% {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
    }
    100% {
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}
.mr-2{
    margin-right: 20px;
}
</style>
