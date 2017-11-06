<template>
    <div  v-if="this.check">
        <div class="user-statics col-md-12" >
            <div class="statics-item　col-md-4 col-sm-4 col-xs-4">
                <div class="statics-text">文章</div>
                <div class="statics-count" v-text="this.archives_count"></div>
            </div>
            <div class="statics-item col-md-4 col-sm-4 col-xs-4">
                <div class="statics-text">回覆</div>
                <div class="statics-count" v-text="this.answers_count"></div>
            </div>
            <div class="statics-item col-md-4 col-sm-4 col-xs-4">
                <div class="statics-text">關注</div>
                <div class="statics-count" v-text="this.followers_count"></div>
            </div>
        </div>
        <button class="btn btn-block"
                v-text="text"
                :class="[followed? 'btn-danger':'btn-success']"
                @click="follow">
        </button>
        <a id="write" href="#editor" class="btn btn-default btn-block block">發送訊息</a>
    </div>
</template>

<script>
    export default {
        props:['user','check'],
        mounted() {
            if(this.check)
            {
                axios.post('/api/user/followers',{'user':this.user}).then(response =>{
                    this.followed = response.data.followed;
                    this.archives_count = response.data.archives_count;
                    this.answers_count = response.data.answers_count;
                    this.followers_count = response.data.followers_count;
                });
            }
        },
        data() {
            return {
                followed:false,
                archives_count:0,
                answers_count:0,
                followers_count:0
            }
        },
        computed:{
            text() {
                return this.followed ? '取消關注':'關注作者';
            }
        },
        methods:{
            follow(){
                if(this.check)
                {
                    axios.post('/api/user/follow',{'user':this.user}).then(response =>{
                        this.followed = response.data.followed;
                        this.archives_count = response.data.archives_count;
                        this.answers_count = response.data.answers_count;
                        this.followers_count = response.data.followers_count;
                    });
                }
            }
        }
    }
</script>
