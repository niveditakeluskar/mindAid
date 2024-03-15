<script setup>
const props = defineProps({
  show: Boolean
})
</script>

<template>
  <Transition name="VeteranService">
    <div v-if="show" class="modal-mask">
      <div class="modal-container">
        <div class="modal-header"> 
          <slot name="header">default header Vetran</slot>
        </div>
        <div class="modal-body">   
          <slot name="body">default body privetran</slot>

          <!-- Vatran Service -->
<div id="vateran-service" class="modal fade" role="dialog">  
        <div class="modal-dialog modal-lg">

       
            <div class="modal-content">
                <div class="modal-header">
                <h4 class="modal-title" id="vateran_service_title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body"> 
                        <div class="modal-footer">
                            <!--button type="button" class="btn btn-primary float-right submit-vateran-service">Submit</button-->
                            <button type="button" class="btn btn-default float-left" data-dismiss="modal">Close</button>
                        </div>
                </div>
            </div>
        </div>
    </div>
<!-- Vatran Service --> 

        </div>

        <div class="modal-footer">
          <slot name="footer">
            default footer
            <button
              class="modal-default-button"
              @click="$emit('close')"
            >OK</button>
          </slot>
        </div>
      </div>
    </div>
  </Transition>
</template>

<style>
.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  transition: opacity 0.3s ease;
}

.modal-container {
  width: 300px;
  margin: auto;
  padding: 20px 30px;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
  transition: all 0.3s ease;
}

.modal-header h3 {
  margin-top: 0;
  color: #42b983;
}

/* .modal-body {
  margin: 20px 0;
} */

.modal-default-button {
  float: right;
}

/*
 * The following styles are auto-applied to elements with
 * transition="modal" when their visibility is toggled
 * by Vue.js.
 *
 * You can easily play with the modal transition by editing
 * these styles.
 */

.modal-enter-from {
  opacity: 0;
}
 
.modal-leave-to {
  opacity: 0;
}

.modal-enter-from .modal-container,
.modal-leave-to .modal-container {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}
</style>