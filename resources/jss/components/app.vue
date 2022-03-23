<template>
  <div id="app-container">
    
    <router-view></router-view>
  </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
<style scoped>
.app-container{
    min-height:100%;
}
</style>
