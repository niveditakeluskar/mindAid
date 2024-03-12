<div class="card-body">
    <?php
        $module_id    = getPageModuleName();
    ?>
    <input type="hidden" name="patient_id" value="{{$patient_id}}" />
    <input type="hidden" name="module_id" value="{{ $module_id }}" />
    <div class="col-12">
        <div class="table-responsive">
            <table id="patient-list" class="display table table-striped table-bordered" style=" border: 1px solid #00000029; width: 60%;">
                <thead>
                    <tr>											
                      <th></th>
                      <th>Add/Update</th>
                      <th>Completed</th> 
                  </tr>
              </thead>
              <tbody>
                <tbody>
                    <tr>
                        <td class="patient_data">Family</td>
                        <td><i id="click_id" type="button" class="editform i-Pen-4 click_id" data-toggle="modal" data-target="#myModal" target="family"></i></td>
                        <td><label class="checkbox"><input type="checkbox" id="familycheck"><span></span><span class="checkmark"></span></label></td>
                    </tr>
                    <tr>
                        <td class="patient_data">Provider</td>
                        <td><i id="click_id" type="button" class="editform i-Pen-4 click_id specialists_data" data-toggle="modal" data-target="#myModal" target="provider-information"></i></td>
                        <td><label class="checkbox"><input type="checkbox" id="providercheck"><span></span><span class="checkmark"></span></label></td>
                    </tr>
                    <tr>
                        <td class="patient_data">Diagnosis Codes</td>
                        <td><i id="diagnosis-codes-click_id" type="button" class="editform i-Pen-4 click_id" data-toggle="modal" data-target="#myModal" target="diagnosis-codes"></i></td>
                        <td><label class="checkbox"><input type="checkbox" id="diagnosischeck"><span></span><span class="checkmark" ></span></label></td>
                    </tr>
                    <tr>
                        <td class="patient_data">Allergies</td>
                        <td><i id="click_allergies_id" type="button" class="editform i-Pen-4 click_id allergiesclick" data-toggle="modal" data-target="#myModal" target="allergy-information"></i></td>
                        <td><label class="checkbox"><input type="checkbox" id="allergycheck"><span></span><span class="checkmark"></span></label></td>
                    </tr>
                    <tr>
                        <td class="patient_data">Medications</td>
                        <td><i id="click_id" type="button" class="editform i-Pen-4 click_id medications" data-toggle="modal" data-target="#myModal" target="medications"></i></td>
                        <td><label class="checkbox"><input type="checkbox" id="medicationscheck"><span></span><span class="checkmark"></span></label></td>
                    </tr>
                    <tr>
                        <td class="patient_data">Services</td>
                        <td><i id="click_services_id" type="button" class="editform i-Pen-4 click_id" data-toggle="modal" data-target="#myModal" target="healthcare-services"></i></td>
                        <td><label class="checkbox"><input type="checkbox" id="healthcarecheck"><span></span><span class="checkmark"></span></label></td>
                    </tr>
                    <tr>
                        <td class="patient_data">Vitals and Health Data</td>
                        <td><i id="click_id" type="button" class="editform i-Pen-4 click_id vitals_and_health_data" data-toggle="modal" data-target="#myModal" target="numbers-tracking"></i></td>
                        <td><label class="checkbox"><input type="checkbox" id="numbers_trackingcheck"><span></span><span class="checkmark"></span></label></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="container">
  <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-lg">
        <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                   @include('Patients::components.patient-Ajaxbasic-info')
                    <!-- TEXT-2 --> 
                    <div class="row mb-4"> 
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body "> 
                                    <div class="row">
                                        <div class="col-md-12">  
                                            <div class="form-group pcpPatientData" id="family" name="family" style="display:none">
                                                @include('Ccm::care-plan-development.care-plan-development-sub-steps.patient-data-sub-steps.family')
                                            </div>
                                            <div class="form-group pcpPatientData" id="provider-information" name="provider_information" style="display:none" >
                                              @include('Ccm::care-plan-development.care-plan-development-sub-steps.patient-data-sub-steps.provider') 
                                            </div>
                                            <div class="form-group pcpPatientData" id="diagnosis-codes" name="diagnosis_codes" style="display:none">
                                            @include('Ccm::care-plan-development.care-plan-development-sub-steps.patient-data-sub-steps.diagnosis-codes')
                                            </div>
                                            <div class="form-group pcpPatientData" id="allergy-information" name="allergy_information" style="display:none">
                                                @include('Ccm::care-plan-development.care-plan-development-sub-steps.patient-data-sub-steps.allergy')
                                            </div>
                                            <div class="form-group pcpPatientData" id="medications" name="medications" style="display:none">
                                                @include('Ccm::care-plan-development.care-plan-development-sub-steps.patient-data-sub-steps.medications') 
                                            </div>
                                            <div class="form-group pcpPatientData" id="healthcare-services" name="healthcare_services" style="display:none">
                                                @include('Ccm::care-plan-development.care-plan-development-sub-steps.patient-data-sub-steps.service')
                                            </div>
                                            <div class="form-group pcpPatientData" id="numbers-tracking" name="numbers_tracking" style="display:none">
                                                @include('Ccm::care-plan-development.care-plan-development-sub-steps.patient-data-sub-steps.number-tracking')
                                            </div>
                                        </div>        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>



