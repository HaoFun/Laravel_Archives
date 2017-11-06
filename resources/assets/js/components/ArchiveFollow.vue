<template>
    <div class="panel panel-default">
        <div class="panel-heading text-center">
            <h3 v-text="followers_count"></h3>
            <span >關注數</span>
        </div>
        <div class="panel-body text-center" v-if="this.check">
            <button class="btn btn-block"
                    v-text="text"
                    :class="[followed? 'btn-danger':'btn-success']"
                    @click="follow">
            </button>
            <a id="write" href="#editor" class="btn btn-default btn-block block">撰寫回覆</a>
        </div>
    </div>
</template>

<script>
    export default {
        props:['archive','check'],
        mounted() {
            if(this.check)
            {
                axios.post('/api/archive/follower',{'archive':this.archive}).then(response =>{
                    this.followed = response.data.followed;
                    this.followers_count = response.data.followers_count;
                });
            }
        },
        data() {
            return {
                followed:false,
                followers_count:0
            }
        },
        computed:{
            text() {
                return this.followed ? '取消關注':'關注文章';
            }
        },
        methods:{
            follow(){
                if(this.check)
                {
                    axios.post('/api/archive/follow',{'archive':this.archive}).then(response =>{
                        this.followed = response.data.followed;
                        this.followers_count = response.data.followers_count;
                    });
                }
            }
        }
    }
</script>
