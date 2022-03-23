<template>
  <div class="w-25">
      <input v-model="title" type="text" class="form-control" placeholder="title">
      <div ref="dropzone" class="btn d-block p-5 bg-dark text-center text-light">
        DROPZONE
      </div>
      <input @click.prevent="store" type="submit" class="btn btn-primary">
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
        }
    },
    mounted(){
        this.dropzone = new Dropzone(this.$refs.dropzone,{
            url: '1234',
            autoProcessQueue: false,
        })
        console.log(this.dropzone);
    },

    methods:{
        store(){
            const images = new FormData()
            const files = this.dropzone.getAcceptedFiles()
            files.forEach((file)=>{
                images.append('images[]',file)
            })
            axios.post('/api/posts',images )
            
        }
    }
}
</script>

<style>
.dropzone{
    background: #000;
}
</style>