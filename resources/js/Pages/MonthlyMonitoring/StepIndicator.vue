<template>
  <ul class="gsi-step-indicator triangle gsi-style-1 gsi-transition">
    <li v-for="(step, index) in steps" :key="index" :class="{ current: activeStep === index }">
      <a href="#0" @click="changeStep(index)">
        <span class="desc"><span>{{ step }}</span></span>
      </a>
    </li>
  </ul>
</template>

<script>
export default {
  props: ['steps', 'activeStep'],
  methods: {
    changeStep(index) {
      this.$emit('step-change', index);
    }
  }
}
</script>
