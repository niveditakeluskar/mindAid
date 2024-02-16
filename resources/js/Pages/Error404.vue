<template>
   <center>
    <h1>Not Found!</h1>
    <p>
      <a href="/">Go home?</a>
    </p>
  </center>
    
</template>

<script>
  export default {
    mounted: function() {
      console.log('Mounted lost');
    }
  }
</script>
<style scoped>

  center {
    margin: 15vw;
  }

  h1 {
    color: var(--border);
    font-size: 2em;
  }

</style>