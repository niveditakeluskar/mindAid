<!-- LoadingSpinner.vue -->
<template>
    <div class="overlay" v-if="isLoading">
      <div class="spinner"></div>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      isLoading: {
        type: Boolean,
        required: true,
      },
    },
  };
  </script>
  
  <style scoped>
  .overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.8); /* Semi-transparent white overlay */
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000; /* Adjust z-index to make sure it's on top of other elements */
  }
  
  .spinner {
    border: 8px solid #3498db; /* Spinner color */
    border-radius: 50%;
    border-top: 8px solid #f3f3f3; /* Background color */
    width: 50px; /* Spinner size */
    height: 50px; /* Spinner size */
    animation: spin 1s linear infinite; /* Spinner animation */
  }
  
  @keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
  }
  </style>
  