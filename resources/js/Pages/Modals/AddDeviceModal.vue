<template>
    <div class="modal fade" :class="{ 'show': isOpen }" >
		<div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Devices</h4>
                <button type="button" class="close" @click="closeModal">×</button>
            </div>
            <div class="modal-body">
                <loading-spinner :isLoading="isLoading"></loading-spinner>
                <form  method="post" name="patient_add_device_form" id="patient_add_device_form" @submit.prevent="submitadddeviceForm">
                        <input type="hidden" name="patient_id" id="patient_id" :value="patientId" />
                        <input type="hidden" name="uid" :value="patientId">
                        <input type="hidden" name="start_time" :value="'00:00:00'" >
                        <input type="hidden" name="end_time" :value="'00:00:00'">
                        <input type="hidden" name="module_id" id="module_id" :value="moduleId" />
                        <input type="hidden" name="component_id" :value="componentId" />
                        <input type="hidden" name="id">
                        <input type="hidden" name="mail_content" id="mail_content" :value="mail_content">
                        <input type="hidden" name="timearr[form_start_time]" class="timearr form_start_time" :value="adddevicetime">
                     
                        <input type="hidden" name="stage_id" v-model="emailStageId" />
                        <div class="alert alert-success" id="device-alert" v-if="showSuccessAlert">
                            <button type="button" class="close" data-dismiss="alert">x</button>
                            <strong>Additional Device data saved successfully! </strong><span id="text"></span>
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group mb-3 ">
                                <!--label for="module">Se</label-->
                                <select name="add_replace_device" id="add_replace_device" class="custom-select show-tick select2" v-model="addReplaceDevice" @change="handleDeviceChange" required>
                                    <option value="1">Additional device</option>
                                    <option value="2">Replace device</option>
                                </select>
                                <div class="invalid-feedback"  v-if="formErrors.add_replace_device" style="display: block;">{{formErrors.add_replace_device[0] }}</div>
                            </div>
                            <div class="col-md-6 form-group mb-3 ">
                                <label for="sel1">Select Device:</label>
                                <div class="wrapMulDropDevices">
                               <!--      <button type="button" id="multiDropDevice" name="multiDropDevice" class="multiDropDevice form-control col-md-12">Select Device<i style="float:right;" class="icon ion-android-arrow-dropdown"></i></button> -->
                                    <ul ref="deviceList" style="list-style-type: none;">

                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-6 form-group mb-3 ">
                                <label for="sel1">Email Template:</label>
                                <select id="email_title"  class="custom-select show-tick" name="call_not_answer_template_id"
                                  v-model="selectedEmail" @change="textScript(selectedEmail)" required>
                                  <option value="0">Select Template</option>
                                <option  v-for="item in emailOptions"  :key="item.id" :value="item.id">
                                 {{ item.content_title }} </option>
                                </select>
                <div class="invalid-feedback"  v-if="formErrors.call_not_answer_template_id" style="display: block;">{{formErrors.call_not_answer_template_id[0] }}</div>
                            </div>
                            <div class="col-md-6 form-group mb-3 " style="display:none">
                                <input type="text" class="form-control" name="email_from" id="email_from">
                            </div>
                            <div class="col-md-6 form-group mb-3 " style="display:none">
                                <input type="text" class="form-control" name="email_sub" id="email_sub">
                            </div>
                            <div class="col-md-12 form-group mb-3">
                                <label><b>Content</b><span class="error">*</span></label>
                                <textarea name="text_msg" class="form-control" v-model="editorData" id="email_title_area" style="padding: 5px;width: 47em;min-height: 5em;overflow: auto;height: 87px;}"></textarea>
                                <div class="invalid-feedback"  v-if="formErrors.text_msg" style="display: block;">{{formErrors.text_msg[0] }}</div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary float-right submit-patient-add_device">Submit</button>
                            <button type="button" class="btn btn-default float-left" data-dismiss="modal" @click="closeModal">Close</button>
                        </div>
                    </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" @click="closeModal">Close</button>
            </div>
        </div>
		</div>
    </div>
</template>

<script>
import {
    reactive,
    ref,
    onBeforeMount,
    onMounted,
    // Add other common imports if needed
} from '../commonImports';
import LayoutComponent from '../LayoutComponent.vue'; // Import your layout component
import axios from 'axios';
import { getCurrentInstance, watchEffect,nextTick } from 'vue';

export default {
    props: {
        patientId: Number,
        moduleId: Number,
        componentId: Number,
    },
    data() {
        return {
            isOpen: false,
            adddevicetime: null
        };
    },
    components: {
        LayoutComponent,
    },
    methods: {
        openModal() {
            this.isOpen = true;
            this.adddevicetime = document.getElementById('page_landing_times').value;
            document.body.classList.add('modal-open');
        },
        closeModal() {
            this.isOpen = false;
            document.body.classList.remove('modal-open');
        },
    },
    setup(props) {
        const emailStageId = ref('');
        const adddevicetime = ref(null);
        const isSaveButtonDisabled = ref(true);
        const addReplaceDevice = ref('');
        const formErrors = ref({});
        const showSuccessAlert = ref(false);
        const isLoading = ref(false);
        const token = ref('');
        const editorData = ref(null);
        const selectedEmail = ref('');
        const textScripts = ref('');
        const mail_content =ref('');
        const selectedCode = ref('');
        const showAlert = ref(false);
        const loading = ref(false);
        let emailOptions = ref([]);
        let codeOptions = ref([]);
        let selectedMedication = ref('');
       
        const selectedEditDiagnosId = ref('');
        const selectedcondition = ref('');
 
        const submitadddeviceForm = async () => {
            isLoading.value = true;
            let myForm = document.getElementById('patient_add_device_form');
            let formData = new FormData(myForm);
            axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
            try {
                const response = await axios.post('/ccm/additional-device-email', formData);
                if (response && response.status == 200) {
                    showSuccessAlert.value = true;
                    alert("Saved Successfully");
                    updateTimer(props.patientId, '1', props.moduleId); 
                    $(".form_start_time").val(response.data.form_start_time);
                    document.getElementById("patient_add_device_form").reset();
                   setTimeout(() => {
                        showSuccessAlert.value = false;
                        adddevicetime.value = document.getElementById('page_landing_times').value;
                    }, 3000); 
                }
                isLoading.value = false;
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    console.log( error.response);
                    formErrors.value = error.response.data.errors;
                } else {
                    alert("oopz! something went wrong. Try again or Contact Administrator");
                    console.error('Error submitting form:', error);
                }
                isLoading.value = false;
            }
            // this.closeModal();
        };

    

   
        let fetchEmail = async () => {
            try {
                await new Promise((resolve) => setTimeout(resolve, 2000)); // Simulating a 2-second delay
                const response = await fetch(`/patients/getcontent-template`);
                if (!response.ok) {
                    throw new Error('Failed to fetch email list');
                }
                const emailData = await response.json();
                const emailArray = Object.entries(emailData).map(([id, content_title]) => ({ id, content_title }));
                if (emailArray.length > 0) { // Check if emailArray is not empty
                    emailOptions.value = emailArray;
                    selectedEmail.value = emailArray[emailArray.length - 1].id;
                    textScript(selectedEmail.value);
                }
            } catch (error) {
                console.error('Error fetching email list:', error);
            }
        };

        let textScript = async(id) => {
            await axios.get(`/ccm/get-call-scripts-by-id/${id}/${props.patientId}/call-script`)
            .then(response => {
               textScripts.value = response.data.finaldata;
               const data = `${editorData.value.getData()}${textScripts.value}`;
               editorData.value.setData(data);
            //    textScripts = this.textScripts.replace(/(<([^>]+)>)/ig, '');
            //    textScripts = this.textScripts.replace(/&nbsp;/g, ' ');
            //    textScripts = this.textScripts.replace(/&amp;/g, '&');
            })
            .catch(error => {
               console.error('Error fetching data:', error);
            });
        };

        const handleDeviceChange = async () => {
      try {
        isLoading.value = true;
        token.value = document.querySelector('meta[name="csrf-token"]').content;
        const response = await fetch('/patients/getdevicevl', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: new URLSearchParams({
            patientid: props.patientId, // Assuming props.patientId is available
            add_replace_device: addReplaceDevice.value,
            _token: token.value,
          }),
        });

        isLoading.value = false;

        if (response.ok) {
          const responseData = await response.json();

          // Access the ref and update its content
          const deviceList = document.querySelector('.wrapMulDropDevices ul');
          if (deviceList) {
            // Clear the existing content
            deviceList.innerHTML = '';

            // Loop through responseData and append input fields to the ul
            responseData.forEach(device => {
              const li = document.createElement('li');
              li.innerHTML = `
                <label class="forms-element checkbox checkbox-outline-primary">
                  <input class="ckbox"
                         name="device_ids[${device.id}]"
                         id="${device.device_name}"
                         value="${device.id}"
                         type="checkbox">
                  <span>${device.device_name}</span><span class="checkmark"></span>
                </label>
              `;
               // Attach the click event handler after creating the element
                const checkbox = li.querySelector('input');
                checkbox.addEventListener('click', (event) => getDevice(event.target));

              deviceList.appendChild(li);
            });
          } else {
            console.error('Target ul element not found');
          }
        } else {
          console.error('Failed to fetch device data');
        }
      } catch (error) {
        console.error('Error:', error);
      }
    };

    const getDevice = (checkbox) => {
        if (editorData.value && checkbox.checked) {
            const y = checkbox.id;
            const data = `${editorData.value.getData()}<li>${y}</li>`;
            editorData.value.setData(data);
        } else if (editorData.value) {
            const text = editorData.value.getData().replace(`<li>${checkbox.id}</li>`, '').trim();
            editorData.value.setData(text);
        }
        mail_content.value =`${editorData.value.getData()}`;
    };

    let getStageID = async () => {
            try {
                let templateName = 'Email';
                let response = await axios.get(`/get_stage_id/${props.moduleId}/${props.componentId}/${templateName}`);
                emailStageId.value = response.data.stageID;
            } catch (error) {
                throw new Error('Failed to fetch Patient Data stageID');
            }
        };

        onMounted(async () => {
            fetchEmail();
            getStageID();
            try {
                adddevicetime.value = document.getElementById('page_landing_times').value;
            } catch (error) {
                console.error('Error on page load:', error);
            } 
            // Wait for CKEditor to be ready
        await new Promise((resolve) => {
            CKEDITOR.on('instanceReady', function (event) {
                if (event.editor.name === 'email_title_area') {
                    editorData.value = event.editor;
                    resolve();
                }
            });
            CKEDITOR.replace('email_title_area');
        });
        });

        return {
            emailStageId,
            isSaveButtonDisabled,
            loading,
            emailOptions,
            formErrors,
            isLoading,
            showSuccessAlert,
            addReplaceDevice,
            handleDeviceChange,
            selectedEmail,
            adddevicetime,
            token,
            editorData,
            mail_content,
      getDevice,
      submitadddeviceForm,
      textScript,
      textScripts
          };
    }

};
</script>