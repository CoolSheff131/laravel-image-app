<template>
  <div class="w-25">
      <input v-model="title" type="text" class="form-control" placeholder="title">
      <div ref="dropzone" class="btn d-block p-5 bg-dark text-center text-light">
        DROPZONE
      </div>
      <input @click.prevent="store" type="submit" class="btn btn-primary">
      <div>
          <div v-if="post">
              <h4>{{post.title}}</h4>
              <div v-for="image in post.images" v-bind:key="image.id">
                <img :src="image.preview_url" >
                <img :src="image.url" >
              </div>
          </div>
      </div>
  </div>
</template>

<script>
import Dropzone from 'dropzone'

export default {
    name: 'Index',

    data(){
        return {
            dropzone: null,
            title: null,
            post: null,
        }
    },
    mounted(){
        this.dropzone = new Dropzone(this.$refs.dropzone,{
            url: '1234',
            autoProcessQueue: false,
            addRemoveLinks: true,
        })
        this.getPost()
    },

    methods:{
        store(){
            const data = new FormData()
            const files = this.dropzone.getAcceptedFiles()
            files.forEach((file)=>{
                data.append('images[]',file)
                this.dropzone.removeFile(file)
            })
            data.append('title',this.title)
            this.title = ''
            axios.post('/api/posts',data )
        },

        getPost(){
            axios.get('/api/posts')
            .then(res=>{
                this.post = res.data.data
            })
        }
    }
}
</script>

<style>
.dropzone{
    background: #000;
}
</style>