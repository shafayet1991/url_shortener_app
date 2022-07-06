<template>
    <div>
        <h4 class="text-center">Add URL</h4>
        <div class="row">
            <div class="col-md-12">
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
                        <button type="submit" class="btn btn-primary" v-if="genUrlBtn">Generate Short URL</button>< /br>
                        <button type="submit" class="btn btn-primary" v-if="genUrlBtn" @click="resetForm">Reset</button>
                    </div>
                </form>
                <div class="form-group mt-2">
                    <button type="submit" class="btn btn-primary" v-if="copyUrlBtn" @click="copyUrl">Copy URL</button>
                    <button type="submit" class="btn btn-primary" v-if="copyUrlBtn" @click="resetForm">Reset</button>
                </div>
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
            },
            copyUrlBtn: false,
            genUrlBtn: true,
            copyTextString: 'Copy Text To Clipboard',
        }
    },
    methods: {
        //Validating and formatting given url
        getUrl() {
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
                        this.url.newUrl = response.data;
                        if(this.url.newUrl != "" || this.url.newUrl != null) {
                            this.copyUrlBtn = true;
                            this.genUrlBtn = false;
                        } else{
                            this.copyUrlBtn = false;
                            this.genUrlBtn = true;
                        }
                    })
                    .catch(function (error) {
                        console.error(error);
                    });
            })
        },
        copyUrl() {
            /* Copy the new generated url */
            navigator.clipboard.writeText(this.url.newUrl);

            /* Alert the copied text */
            alert("Copied the text: " + this.url.newUrl);
        },
        resetForm() {
            this.copyUrlBtn= false;
            this.genUrlBtn = true;
            this.url.newUrl = null;
            this.url.currentUrl = null;
        }
    },
    created() {
    /*this.getRandom();
    this.genHash();*/
    /*console.log("random generator::"+this.getRandom());
    console.log("random generator::"+this.genHash());*/
    }
}
</script>

<style scoped>
.tooltip {
    position: relative;
    display: inline-block;
}

.tooltip .tooltiptext {
    visibility: hidden;
    width: 140px;
    background-color: #555;
    color: #fff;
    text-align: center;
    border-radius: 6px;
    padding: 5px;
    position: absolute;
    z-index: 1;
    bottom: 150%;
    left: 50%;
    margin-left: -75px;
    opacity: 0;
    transition: opacity 0.3s;
}

.tooltip .tooltiptext::after {
    content: "";
    position: absolute;
    top: 100%;
    left: 50%;
    margin-left: -5px;
    border-width: 5px;
    border-style: solid;
    border-color: #555 transparent transparent transparent;
}

.tooltip:hover .tooltiptext {
    visibility: visible;
    opacity: 1;
}
</style>
