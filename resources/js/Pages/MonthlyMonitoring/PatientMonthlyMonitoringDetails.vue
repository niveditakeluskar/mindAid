<template>
   <div class="row mb-4">
      <div class="col-md-12">
         <div class="card">
            <div class="card-body">
               <div style="width: 9%; float: left;">
                  <div v-for="(tab, index) in tabs" :key="index" @click="changeTab(index)" :class="{ active: activeTab === index }" class="tab">
                     {{ tab }}
                  </div>
               </div>
               <div style="width: 90%; float: right;">
                  <component :is="selectedComponent" v-bind="componentProps" :patientId="patientId" :moduleId="moduleId" :componentId="componentId" :stageid="stageid"></component>
               </div>
            </div>
         </div>
      </div>
   </div>
</template>

<script>
   import axios from 'axios';
   import Preparation from './SubSteps/Preparation.vue';
   import Call from './SubSteps/Call.vue';
   import FollowUp from './SubSteps/FollowUp.vue';
   import Text from './SubSteps/Text.vue';
   
   export default {
      props: {
         patientId: Number,
         moduleId: Number,
         componentId: Number,
         stageid:Number,
      },
      components: {
         Preparation,
         Call,
         FollowUp,
         Text,
      },
      data() {
         return {
            activeTab: 0,
            tabs: ['Preparation', 'Review RPM', 'Call', 'Follow-up', 'Text'],
            componentProps: {}, 
            // selectedCallAnswerdContentScript: null,
            // selectedCallNotAnswerdContentScript: null,
            // callStatus: null,
            // voiceMailAction: null,
         };
      },
      computed: {
         selectedComponent() {
            switch (this.activeTab) {
               case 0:
                  return 'Preparation';
               case 1:
                  return 'SubStepConditionReview';
               case 2:
                  return 'Call';
               case 3:
                  return 'FollowUp';
               case 4:
                  return 'Text';
               default:
                  return 'Preparation'; // Default component if activeTab is not in the range
            }
         },
      },
      mounted() {
         console.log('Patient Monthly Monitoring Details Component mounted.');
         // this.initCareManagerDataTable();
      },
      methods: {
         changeTab(index) {
            this.activeTab = index;
            this.updatePropsForComponent();
         },
         // async initCareManagerDataTable() {
         //    // const columns = [
         //    //    { title: 'Sr. No.', data: 'DT_RowIndex', name: 'DT_RowIndex' },
         //    //    { title: 'Task', data: 'task_notes', name: 'task_notes' },
         //    //    { title: 'Category', data: 'task', name: 'task' },
         //    //    {
         //    //       title: 'Notes', data: 'notes', name: 'notes', render: function (data, type, full, meta) {
         //    //          if (data != '' && data != 'NULL' && data != undefined) {
         //    //             return full['notes'] + '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' + full['id'] + '" data-original-title="Edit" class="editfollowupnotes" title="Edit"><i class=" editform i-Pen-4"></i></a> ';
         //    //          } else { return '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' + full['id'] + '" data-original-title="Edit" class="editfollowupnotes" title="Edit"><i class=" editform i-Pen-4"></i></a>'; }
         //    //       }
         //    //    },
         //    //    {
         //    //       title: 'Date Scheduled', data: 'tt', type: 'date-dd-mm-yyyy',
         //    //       "render": function (value) {
         //    //          if (value === null) return "";
         //    //          return value;
         //    //          // return util.viewsDateFormat(value);
         //    //       }
         //    //    },
         //    //    {
         //    //       title: 'Task Time', data: 'task_time', name: 'task_time',
         //    //       render: function (data, type, full, meta) {
         //    //          if (data != '' && data != 'NULL' && data != undefined) {
         //    //             return full['task_time'];
         //    //          } else {
         //    //             return '';
         //    //          }
         //    //       }
         //    //    },
         //    //    { title: 'Mark as Complete', data: 'action', name: 'action', orderable: false, searchable: false },
         //    //    // {
         //    //    // 	data: 'Task Completed Date', type: 'date-dd-mm-yyyy h:i:s', name: 'task_completed_at', "render": function (value) {
         //    //    // 		if (value === null) return "";
         //    //    // 		return value;
         //    //    // 		// return util.viewsDateFormat(value);
         //    //    // 	}
         //    //    // },
         //    //    {
         //    //       title: 'Created By', data: null,
         //    //       render: function (data, type, full, meta) {
         //    //          if (data != '' && data != 'NULL' && data != undefined) {
         //    //             return full['f_name'] + ' ' + full['l_name'];
         //    //          } else {
         //    //             return '';
         //    //          }
         //    //       }
         //    //    },
         //    // ];
         //    // const dataTableElement = document.getElementById('task-list');
         //    // // var url = `/ccm/patient-followup-task/${this.patientId}/${this.moduleId}/followuplist`;
         //    // if (dataTableElement) {
         //    //    // util.renderDataTable('task-list', url, columns, "{{ asset('') }}");
         //    //    this.drawTable(dataTableElement, columns, dataTable.value);
         //    // } else {
         //    //    console.error('DataTables library not loaded or initialized properly');
         //    // }
         // },
         updatePropsForComponent() {
            this.componentProps = {
               patientId: this.patientId,
               moduleId: this.moduleId,
               componentId: this.componentId,
               stageid: this.stageid,
            };
         },
      },
   };
</script>
