<template>
  <div class="tsf-content">
    <div v-for="(content, index) in contents" :key="index" :class="{ 'active': activeStep === index }">
      {{ content }}
    </div>
  </div>
</template>

<script>
export default {
  props: ['contents', 'activeStep']
}
</script>
