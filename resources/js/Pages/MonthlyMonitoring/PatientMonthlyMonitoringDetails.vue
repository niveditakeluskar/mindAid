<template>
   <LayoutComponent>
      <div class="row mb-4">
         <div class="col-md-12">
            <div class="card">
               <div class="card-body">
                  <div class="tm" style="float: left; width: 10%;">
                        <div v-for="(tab, index) in tabs" :key="index" @click="changeTab(index)" :class="{ active: activeTab === index }" class="tm-section">
                           <div class="tm-tab">{{ tab }}</div>
                        </div>
                  </div>
                  <div style="width: 90%; float: right;">
                     <component :is="selectedComponent" v-bind="componentProps"></component>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </LayoutComponent>
</template>

<script>
   import axios from 'axios';
   import LayoutComponent from '../LayoutComponent.vue'; // Import your layout component
   import Preparation from './SubSteps/Preparation.vue';
   import Call from './SubSteps/Call.vue';
   import FollowUp from './SubSteps/FollowUp.vue';
   import Text from './SubSteps/Text.vue';
   
   export default {
      props: {
         patientId: Number,
         moduleId: Number,
         componentId: Number
      },
      components: {
         LayoutComponent,
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
               componentId: this.componentId
            };
         },
      },
   };
</script>
<style>
.tm{
  margin-top:20px;
  position:relative;
  
}

.tm:before{
  position:absolute;
  content:'';
  width:5px;
  height:calc(83% + 0px);
background: #edeff0;
/*background: -moz-linear-gradient(left, rgba(138,145,150,1) 0%, rgba(122,130,136,1) 60%, rgba(98,105,109,1) 100%);
background: -webkit-linear-gradient(left, rgba(138,145,150,1) 0%,rgba(122,130,136,1) 60%,rgba(98,105,109,1) 100%);
background: linear-gradient(to right, rgba(138,145,150,1) 0%,rgba(122,130,136,1) 60%,rgba(98,105,109,1) 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#8a9196', endColorstr='#62696d',GradientType=1 );*/
  left:14px;
  top:5px;
  border-radius:4px;
}
.tm-section{
  padding-left:35px;
  display:block;
  position:relative;
  margin-bottom:20px;
  counter-increment:inc;
}

.tm-tab{
  margin-bottom:15px;
  /*padding:2px 15px;
  background:linear-gradient(#74cae3, #5bc0de 60%, #4ab9db);*/
  position:relative;
  display:inline-block;
  border-radius:20px;
  /*border:1px solid #17191B;*/
  color:#2c3f4c;
}
.tm-section:before{
  content:'';
  position:absolute;
  /*width:30px;*/
  height:1px;
  background-color:#444950;
  top:12px;
  left:20px;
}

.tm
{
    counter-reset:inc;
}
.tm-section:after{
  content:counter(inc);
  position:absolute;
  width:20px;
  height:20px;
  background:#edeff0;/*linear-gradient(to bottom, rgba(138,145,150,1) 0%,rgba(122,130,136,1) 60%,rgba(112,120,125,1) 100%);*/
  top:2px;
  left:7px;
  /*border:1px solid #17191B;*/
  border-radius:100%;
  z-index: 2;
  text-align: center;
}
.tm .active .tm-tab{
   color:#27a8de;
}
.tm div.active:after{
   background:#27a8de;
   color:white;
}

</style>
