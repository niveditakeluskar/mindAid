<template>
    <div class="modal fade" :class="{ 'show': isOpen }" >
	<div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Patient Details</h4>
                <button type="button" class="close" @click="closeModal">×</button>
            </div>
            <div class="modal-body" style="padding-top:10px;">
             
                <div class="separator-breadcrumb border-top"></div>
                <div class="row">
                    <div class="col-md-12">
                      
                            <AgGridTable :rowData="passRowDataCA" :columnDefs="columnDefsCA"/>
                 
                    </div>
                </div>
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
        ref,AgGridTable
    } from '../commonImports';
    import axios from 'axios';

    export default {
        components: {
        AgGridTable,
    },
    setup() {
            const isOpen = ref(false);
            const passRowDataCA = ref([]);
         

            const openModal = async (parampracticeid,pararmProvider,paramGroupid,paramNull,pararmFromdate, paramTodate) => {
                callExternalFunctionWithParams(parampracticeid,pararmProvider,paramGroupid,paramNull,pararmFromdate, paramTodate)
                isOpen.value = true;
                document.body.classList.add('modal-open');
              
            };
       
            const closeModal = () => {
                isOpen.value = false;
                document.body.classList.remove('modal-open');
            };

            let columnDefsCA = ref([
    {
        headerName: 'Sr. No.',
        valueGetter: 'node.rowIndex + 1',     flex: 1
      },
{ headerName: "Patient Name",field:"patient_name"},
{ headerName: "DOB",field:"pdob" },
{ headerName: "Practice Name",field:"practicename" },
{ headerName: "Caremanager Name",field:"caremanager" },
{ headerName: "Call Record Date",field:"call_record_date" },
{ headerName: "Call Answered or Not Answered status",field:"call_continue_status" },
{ headerName: "No Additional Services Provided",field:"no_additional_response",flex: 2 },
{ headerName: "Authorized CM Only-Renewal RX transmission to the Pharmacy",field:"authorized_response_one" },
{ headerName: "Authorized CM Only-New RX transmission to the Pharmacy",field:"authorized_response_two" },
{ headerName: "Mailed Documents-Health Education Material",field:"mailed_response_one" },
{ headerName: "Mailed Documents-Letters",field:"mailed_response_two" },
{ headerName: "Mailed Documents-Care Plans",field:"mailed_response_three" },
{ headerName: "Mailed Documents-Lab/Test Results",field:"mailed_response_four" },
 { headerName: "Mailed Documents-Resource Support Material",field:"mailed_response_five" },
 { headerName: "Medication Support-Medication Renewal Research",field:"medication_response_one" },

 { headerName: "Medication Support-Medication Change Request",field:"medication_response_two" },

 { headerName: "Medication Support-Medication Sample Request",field:"medication_response_three" },

 { headerName: "Medication Support-Medication Prior Authorization",field:"medication_response_four" },

 { headerName: "Medication Support-Medication Cost Assistance",field:"medication_response_five" },

 { headerName: "Referral/Order Support-Social Services",field:"referral_response_one" },

 { headerName: "Referral/Order Support-Home Health Physical Therapy",field:"referral_response_two" },

 { headerName: "Referral/Order Support-Home Health Skilled Nursing",field:"referral_response_three" },

 { headerName: "Referral/Order Support-Home Health Occupational Therapy",field:"referral_response_four" },

 { headerName: "Referral/Order Support-DME",field:"referral_response_five" },

 { headerName: "Referral/Order Support-Specialist",field:"referral_response_six" },

 { headerName: "Referral/Order Support-Mental Health",field:"referral_response_seven" },

 { headerName: "Referral/Order Support-Hospice",field:"referral_response_eight" },

 { headerName: "Referral/Order Support-Oxygen",field:"referral_response_nine" },

 { headerName: "Referral/Order Support-Medical Supplies",field:"referral_response_ten" },

 { headerName: "Resource Support-Hospice",field:"resource_response_one" },

 { headerName: "Resource Support-Mental Health",field:"resource_response_two" },

 { headerName: "Resource Support-Community and Social Groups",field:"resource_response_three" },

 { headerName: "Resource Support-Veterinary Care Assistance",field:"resource_response_four" },

 { headerName: "Resource Support-Food",field:"resource_response_five" },

 { headerName: "Resource Support-Housing",field:"resource_response_six" },

 { headerName: "Resource Support-Transportation",field:"resource_response_seven" },

 { headerName: "Resource Support-Utilities",field:"resource_response_eight" },

 { headerName: "Resource Support-ADLs",field:"resource_response_nine" },

 { headerName: "Resource Support-Home Health",field:"resource_response_ten" },

 { headerName: "Resource Support-Housekeeping and errands",field:"resource_response_eleven" },

 { headerName: "Routine Response-Interaction with Office Staff",field:"routine_response_one" },

 { headerName: "Routine Response-Vision or Dental appointment scheduled",field:"routine_response_two" },

 { headerName: "Routine Response-Interaction with PCP",field:"routine_response_three" },

 { headerName: "Routine Response-PCP appointment scheduled",field:"routine_response_four" },

 { headerName: "Routine Response-Specialist appointment scheduled",field:"routine_response_five" },

 { headerName: "Routine Response-AWV appointment scheduled",field:"routine_response_six" },

 { headerName: "Routine Response-Prior Authorization for Labs or Diagnostics",field:"routine_response_seven" },

 { headerName: "Routine Response-Medical Records Request",field:"routine_response_eight" },

 { headerName: "Urgent/Emergent Response-Patient told to call 911",field:"urgent_response_one" },

 { headerName: "Urgent/Emergent Response-Care Manager called 911",field:"urgent_response_two" },

{ headerName: "Urgent/Emergent Response-Patient instructed to go to Urgent Care or ED",field:"urgent_response_three" },

 { headerName: "Urgent/Emergent Response-Same Day appointment scheduled",field:"urgent_response_four" },

 { headerName: "Urgent/Emergent Response-Interaction with Office Staff",field:"urgent_response_five" },

 { headerName: "Urgent/Emergent Response-Interaction with PCP",field:"urgent_response_six" },

 { headerName: "Urgent/Emergent Response-Next Day appointment scheduled",field:"urgent_response_seven" },

 { headerName: "Verbal Education/Review with Patient-Health Education Material",field:"verbal_response_one" },

 { headerName: "Verbal Education/Review with Patient-Lab/Test Results",field:"verbal_response_two" },

 { headerName: "Verbal Education/Review with Patient-Resource Support Material",field:"verbal_response_three" },

 { headerName: "Verbal Education/Review with Patient-RPM Device Education",field:"verbal_response_four" },

 { headerName: "Veterans Services-Resource Support",field:"veterans_response_one" },

 { headerName: "Veterans Services-Referral Support",field:"veterans_response_two" },

 { headerName: "Veterans Services-Scheduled appointment with VA",field:"veterans_response_three" },

 { headerName: "Veterans Services-Mailed VA forms",field:"veterans_response_four" },

 { headerName: "Veterans Services-Mailed Resource Material",field:"veterans_response_five" },

 { headerName: "Veterans Services-Mailed Health Education Material",field:"veterans_response_six" },

 { headerName: "Veterans Services-Research on behalf of the patient",field:"veterans_response_seven" },

 { headerName: "Veterans Services-Medical Records Request",field:"veterans_response_eight" },
     ]);

            const callExternalFunctionWithParams = async (parampracticeid,pararmProvider,paramGroupid,paramNull,pararmFromdate, paramTodate) => {
                    try {
      const responseGet = await fetch(`/reports/callActivityServiceListSearch/${parampracticeid}/${pararmProvider}/${paramGroupid}/${paramNull}/${pararmFromdate}/${paramTodate}`);
         const newData = await responseGet.json();     
         const data = newData.data;
      passRowDataCA.value = data;
    
        console.log('rowData:', passRowDataCA.value);
        console.log('newData value:', data);
  
       } catch (error) {
         console.error('Error fetching user filters:', error);
       }
                };
          
            return {
                columnDefsCA,
                callExternalFunctionWithParams,
                isOpen,
                openModal,
                closeModal,
                passRowDataCA
               
            };
        },
    };
</script>