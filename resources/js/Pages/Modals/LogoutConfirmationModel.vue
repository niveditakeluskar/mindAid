<!-- ModalForm.vue -->
<template>
  <div class="modal fade" :class="{ 'show': isOpen }">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Logout Alert</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <!-- <label>Do you want to logout?</label> -->
          <label>You are inactive on screen since few minutes. Do you really want to logout?</label>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary float-right" id="logout_yes" data-dismiss="modal"
            @click.prevent="logout">Yes</button>
          <button type="button" class="btn btn-default float-left" id="logout_no" data-dismiss="modal"
            @click.prevent="closeLogoutModal">No</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref } from 'vue';

export default {
  setup() {

    let isOpen = ref(false);

    let openModal = () => {
      isOpen.value = true;
    };

    let closeLogoutModal = () => {
      isOpen.value = false;
    };

    let logout = () => {
      window.location.href = '/logout';
      isOpen.value = false;
    };

    return {
      isOpen,
      openModal,
      closeLogoutModal,
      logout,
    };
  },
};
</script>