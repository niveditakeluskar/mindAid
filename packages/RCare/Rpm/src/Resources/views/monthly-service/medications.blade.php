<div class="row mb-4" id="medications">
   <div class="col-md-12 mb-4">
      <div class="success" id="success"></div>
      <div class="card-body">
         <div class="row mb-4"> 
            <div class="col-md-12  mb-4"> 
               <ul class="nav nav-pills" id="myPillTab" role="tablist"> 
                  <li class="nav-item">   
                     <!-- <a class="nav-link active" id="medication-icon-pill" data-toggle="pill" href="#medication" role="tab" aria-controls="medication" aria-selected="true"><i class="nav-icon color-icon-2 i-Home1 mr-1"></i>MEDICATION</a> -->
                  </li>
               </ul>
               <div class="tab-content" id="myPillTabContent">
                  <div class="tab-pane fade show active" id="medication" role="tabpanel" aria-labelledby="medication-icon-pill">
                    <div class="card mb-4">
                        <div class="card-header mb-3">MEDICATION</div> 
                          <form id="rpm_medications_form" name="rpm_medications_form" action="{{route("rpm.medication.savedetails")}}" method="post">
                              <div class="card-body"> 
                                <div class="alert alert-success" id="success-alert" style="display: none;">
                                    <button type="button" class="close" data-dismiss="alert">x</button>
                                    <strong> Medication data saved successfully! </strong><span id="text"></span>
                                </div> 
                                <div class="form-row col-md-12">
                                    @include('Theme::layouts.flash-message')
                                    @csrf
                                    <?php 
                                        $module_id    = getPageModuleName();
                                        $submodule_id = getPageSubModuleName(); 
                                        $stage_id     = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'Medication');
                                        // $step_id      = getFormStepId($module_id, $submodule_id, $stage_id, '');
                                    ?> 
                                    <input type="hidden" name="patient_id" value="{{$patient[0]->id}}" />
                                    <input type="hidden" name="uid" value="{{$patient[0]->id}}">
                                    <input type="hidden" name="start_time" value="00:00:00">
                                    <input type="hidden" name="end_time" value="00:00:00">
                                    <input type="hidden" name="module_id" value="{{ $module_id }}" />
                                    <input type="hidden" name="component_id" value="{{ $submodule_id }}" />
                                    <input type="hidden" name="stage_id" value="{{$stage_id}}" />
                                    <input type="hidden" name="step_id" value="">
                                    <input type="hidden" name="id" id="id"> 
                                    @include('Patients::components.medication')
                                </div> 
                              </div>
                            <div class="card-footer">
                              <div class="mc-footer">
                                <div class="row">
                                  <div class="col-lg-12 text-right">
                                      <button type="submit" class="btn btn-primary float-right" id="">Save</button>
                                  </div>
                                </div>
                              </div>
                            </div>
                          <div class="alert alert-danger" id="danger-alert" style="display: none;">
                              <button type="button" class="close" data-dismiss="alert">x</button>
                              <strong> Please Fill All Mandatory Fields! </strong><span id="text"></span>
                          </div>   
                          </form> 
                    </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<div class="separator-breadcrumb border-top"></div>
<div class="row">
    <div class="col-12">
        <div class="table-responsive">
            <table id="Medication-list" class="display table table-striped table-bordered" style="width:100%; border: 1px solid #00000029;">
                <thead>
                    <tr> 
                        <th>Sr</th>
                        <th>Name</th>
                        <!-- <th>Description</th> -->
                        <th>Purpose</th>
                        <th>Dosage</th>
                        <th>Strength</th>
                        <th>Frequency</th>
                        <th>Route</th>
                        <th>Duration</th>
                        <th>Last Modified By</th>
                        <th>Last Modified On</th>
                        <!-- <th>Reviewed Data</th> -->
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody> 
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
  // rpmMonthlyServices.init();    
});



var renderMedicationsTable =  function() {
  // alert("medica"); 
  var columns = [
  {data: 'DT_RowIndex', name: 'DT_RowIndex'},
  {data: 'medication.description', name: 'medication.description'},
  {data: 'purpose', name: 'purpose'},
  {data: 'dosage', name: 'dosage'},
  {data: 'strength', name: 'strength'},
  {data: 'frequency', name: 'frequency'},
  {data: 'route', name: 'route'},
  {data: 'duration', name: 'duration'},
  {data: 'users',
      mRender: function(data, type, full, meta){
        if(data!='' && data!='NULL' && data!=undefined){
          l_name = data['l_name'];
        if(data['l_name'] == null){
          l_name = '';
        }
          return data['f_name'] + ' ' + l_name;
        } else { 
            return ''; 
        }    
      },orderable: false
  }, 
  {data:'updated_at',name:'updated_at'},
  {data: 'action', name: 'action', orderable: false, searchable: false}
  ];
  var table = util.renderDataTable('Medication-list', "{{ route('rpm_medication_details',[$patient[0]->id]) }}", columns, "{{ asset('') }}"); 
};
</script>