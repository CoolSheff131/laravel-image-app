<template>
  <div class="w-25">
      <input v-model="title" type="text" class="form-control" placeholder="title">
      <div ref="dropzone" class="btn d-block p-5 bg-dark text-center text-light">
        DROPZONE
      </div>
      <input @click.prevent="store" type="submit" class="btn btn-primary">
      <div>
          <vue-editor useCustomImageHandler @image-removed="handleImageRemoved" @image-added="handleImageAdded" v-model="content"></vue-editor>
      </div>
      <div>
          <div v-if="post">
              <h4>{{post.title}}</h4>
              <div v-for="image in post.images" v-bind:key="image.id">
                <img :src="image.preview_url" >
                <img :src="image.url" >
              </div>
              <div class="ql-editor" v-html="post.content"></div>
          </div>
      </div>
  </div>
</template>

<script>
import Dropzone from 'dropzone'
import {VueEditor} from 'vue2-editor'

export default {
    name: 'Index',

    data(){
        return {
            dropzone: null,
            title: null,
            post: null,
            content: null,
            imagesIdForDelete:[],
            imagesUrlsForDelete:[],
        }
    },

    components:{
        VueEditor
    },

    mounted(){
        this.dropzone = new Dropzone(this.$refs.dropzone,{
            url: '1234',
            autoProcessQueue: false,
            addRemoveLinks: true,
        })
        this.dropzone.on('removedfile',file=>{
            this.imagesIdForDelete.push(file.id)
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
            this.imageIdsForDelete.forEach(idForDelete => {
                data.append('image_ids_for_delete[]', idForDelete)
            })
            this.imagesUrlsForDelete.forEach(urlForDelete => {
                data.append('image_urls_for_delete[]', urlForDelete)
            })
            data.append('title',this.title)
            data.append('content',this.content)
            this.title = ''
            this.content = ''
            axios.post('/api/posts',data )
        },

        getPost(){
            axios.get('/api/posts')
            .then(res=>{
                this.post = res.data.data

                this.title = res.data.title
                this.content = res.data.content
                this.post.images.forEach(image=>{
                    let file = {id: image.id, name: image.name, size: image.size}
                    this.dropzone.displayExistingFile(file,image.url)

                })
            })
        },
        handleImageAdded(file, Editor, cursosLocation, resetUploader){
            const formData = new FormData()
            formData.append('image',file)
            axios.post('/api/posts/images',formData)
            .then(result=>{
                const url = result.data.url;
                Editor.insertEmbed(cursosLocation, 'image',url)
                resetUploader()
            })
        },

        handleImageRemoved(url){
            this.imagesUrlsForDelete.push(url)
        }
    }
}
</script>

<style>
.dz-success-mark,
.dz-error-mark {
    display: none;
}
</style>