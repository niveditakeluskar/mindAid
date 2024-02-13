<template>
    <div class="overlay" :class="{ 'open': isOpen }" @click="closeModal"></div>
    <div class="modal fade" :class="{ 'open': isOpen }"> <!-- :style="{ display: isOpen ? 'block' : 'none' }"> -->
        <div class="modal-content followup-modal-content">
            <form name="followup_task_edit_notes" id="followup_task_edit_notes" @submit.prevent="submitFormModal">
            <div class="modal-header">
                <h4 class="modal-title">Modify Followup Task</h4>
                <button type="button" class="close" @click="closeModal">×</button>
            </div>
            <div class="modal-body" style="padding-top:10px;" id="followup_task_edit_notes">
                <div id="followUpAlert"></div>
                <loading-spinner :isLoading="isLoading"></loading-spinner>
             
                    <input type="hidden" name="patient_id" id="patientid" :value=" patientId"/>
                    <input type="hidden" name="uid" id="patientid" :value="patientId">
                    <input type="hidden" name="start_time" value="00:00:00">
                    <input type="hidden" name="end_time" value="00:00:00">
                    <input type="hidden" name="module_id" :value="moduleId" />
                    <input type="hidden" name="component_id" :value="componentId" />
                    <input type="hidden" name="form_name" value="followup_task_edit_notes" />
                    <input type="hidden" name="stage_id" :value="stageId" />
                    <input type="hidden" name="id" id="hiden_idhiden_id" />
                    <input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time" :value="followupTime">
                    <p><b>Task : </b><span id="task_notes">{{ task_notes }}</span></p>
                    <p><b>Category : </b><span id="category">{{ category }}</span> </p>
                    <p><input type="date" name="task_date" id="task_date_val" v-model="task_date"/> </p>
                    <textarea id="notes" name="notes" class="forms-element form-control" v-model="notes"></textarea>
                    <div class="form-group col-md-12 mt-2">
                        <label class="forms-element checkbox checkbox-outline-primary">
                            <input type="checkbox" id="status_flag" name="status_flag" :value="status_flag" :checked="status_flag == 1"><span>Mark as
                                completed</span><span class="checkmark"></span>
                        </label>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-default float-left" data-dismiss="modal" @click="closeModal">Close</button>
            </div>
        </form>
        </div>
    </div>
</template>
<script>
import { reactive, ref, onBeforeMount, onMounted,watch } from '../commonImports';
import axios from 'axios';
export default {
    props: {
        moduleId: Number,
        componentId: Number,
        stageId:Number,
        patientId: Number
    },
    setup(props) {
        let followupTime = ref(null);
        const notes = ref('');
        const task_notes = ref('');
        const category = ref('');
        const task_date = ref('');
        const status_flag = ref(0);
        const Deactivations = ref([]);
        const isOpen = ref(false);
        const isLoading = ref(false);
        const formErrors = ref({});

        const stageId = ref(props.stageId); // Ensure reactivity
        // Watch for changes to the prop and update the ref accordingly
        watch(() => props.stageId, (newValue) => {
            stageId.value = newValue;
        });
        // Format the date function
const formatDate = (dateString) => {
    // Split the date string and rearrange it to match ISO format (YYYY-MM-DD)
    const dateParts = dateString.split(' ')[0].split('-');
    return `${dateParts[2]}-${dateParts[0]}-${dateParts[1]}`;
};

        const openModal = (param1, param2) => {
            isOpen.value = true;
            followupTime.value = document.getElementById('page_landing_times').value;
            if ($.isNumeric(param1) == true) {
                let patientId = param2;
                axios({
                    method: "GET",
                    url: `/ccm/getFollowupListData-edit/${param1}/${patientId}/followupnotespopulate`,
                }).then(function (response) {
                    const firstItem = response.data.followup_task_edit_notes[0];
                    if (firstItem) {
                         task_notes.value = firstItem.task_notes;
                         category.value = firstItem.select_task_category;
                         task_date.value = formatDate(firstItem.task_date); // Format the date
                         notes.value = firstItem.notes;
                         status_flag.value = firstItem.status_flag;
                    
                    } else {
                        // Handle the case when the array is empty
                        console.error('Empty response array');
                    }

                }).catch(function (error) {
                    console.error(error, error.response);
                });

            } else {
                closeModal();
            }

            
        };

        onMounted(async () => {
			try {
				followupTime.value = document.getElementById('page_landing_times').value;
                console.log(followupTime,"time followup");
			} catch (error) {
				console.error('Error on page load:', error);
			}
		});

        const closeModal = () => {
            isOpen.value = false;
        };



        const submitFormModal = async () => {
            isLoading.value = true;
            let myForm = document.getElementById('followup_task_edit_notes');
            let formData = new FormData(myForm);
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
            try {
                const response = await axios.post('/ccm/saveFollowupListData-edit', formData);
                if (response && response.status == 200) {
                    document.getElementById("followup_task_edit_notes").reset();
                    $('#followUpAlert').html('<div class="alert alert-success"> Data Saved Successfully </div>');
					updateTimer(props.patientId, '1', props.moduleId);
                    $(".form_start_time").val(response.data.form_start_time);
					followupTime.value = response.data.form_start_time;
					setTimeout(function () {
                      $('#followUpAlert').html('');
                                    }, 3000);
                }
                setTimeout(function () {
                    closeModal();
                }, 3000); // Close the modal after 3 seconds (3000 milliseconds)
                isLoading.value = false;
            } catch (error) {
                isLoading.value = false;
                if (error.response && error.response.status === 422) {
                    formErrors.value = error.response.data.errors;
                    console.log(error.response.data.errors);
                    setTimeout(function () {
						formErrors.value = {};
                }, 3000);
                } else {
                    $('#followUpAlert').html('<div class="alert alert-danger">Error: ' + error + '</div>');
                    console.error('Error submitting form:', error);
                    setTimeout(function () {
                      $('#followUpAlert').html('');
                                    }, 3000);
                }
            }
            // this.closeModal();
        };

        return {
            followupTime,
            submitFormModal,
            status_flag,
            notes,
            task_notes,
            category,
            task_date,
            isOpen,
            openModal,
            closeModal,
            Deactivations,
            isLoading,
            formErrors,
            stageId,
        };
    },
};

</script>
<style>  /* Modal styles */
  .modal {
      display: none;
      position: fixed;
      background-color: white;
      z-index: 1000;
      margin: 2%;
      opacity: 0;
      transition: opacity 0.3s ease;
  }

  /* Style the overlay */
  .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 999;
      display: none;
  }

  /* Show the overlay and modal when modal is open */
  .modal.open {
      display: block;
      opacity: 1;
  }

  .overlay.open {
      display: block;
  }

  .followup-modal-content {
      overflow-y: auto !important;
      height: 500px !important;
  }
</style>