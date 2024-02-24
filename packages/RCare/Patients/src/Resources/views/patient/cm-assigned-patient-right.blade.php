<style>
.tableFixHead   { overflow-y: auto; height: 400px; } 
.tableFixHead thead th { position: sticky; top: 0; }
table  { border-collapse: collapse; width: 100%; }
th, td { padding: 8px 16px; } 
th     { background:#eee; }
</style>
<div class="row">
    <div class="col-lg-12 mb-3">
    <form id="cm_assign_patient_form" name="cm_assign_patient_form"  action ="">
                @csrf
                    <div class="form-row">
                        <!-- <div class="col-md-3 form-group mb-3">  
                            <label for="practicegrp">{{config('global.practice_group')}}</label>
                             @selectgrppractices("practicesgrp", ["class" => "select2","id" => "practicesgrp"]) 
                        </div> -->
                        <div class="col-md-3 form-group mb-3">
                            <label for="practicename">Practice</label>                  
                            @selectworklistpractices("practices1", ["id" => "practices1", "class" => "select2"])   
                            <div id="errorPractice" style="display: none; color: red;">Please select a practice.</div>
                        </div>
                        <div class="col-md-3 form-group mb-6">
                            <label for="practicename">Patient Name</label>
                            @selectassignpatient("Patient",["id" => "patient1", "class" => "select2"])
                        </div>
                        <!-- <div class="col-md-2 form-group mb-2">
                            <label for="date">From Date</label>
                            @date('date',["id" => "fromdate"])          
                        </div>
                        <div class="col-md-2 form-group mb-3">
                            <label for="date">To Date</label>
                            @date('date',["id" => "todate"])                     
                        </div> -->
                        <div class="col-md-1">
                            <button type="button" id="searchbutton1" class="btn btn-primary mt-4">Search</button>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-primary mt-4" id="reset1">Reset</button>
                        </div>
                    </div>
                </form>
        
        <!-- table -->
            <div class="col-md-12">
                <div class="table-responsive">
                  <table id="patientassignlist" class="display table table-striped table-bordered" style="width:100%; border: 1px solid #00000029; ">
                    <thead>
                        <tr>
                            <th>Sr No.</th>                        
                            <th>Practice</th> 
                            <th>Patient</th>
                            <th>DOB</th>
                            <th>Action</th>
                        </tr> 
                    </thead>
                    <?php $i = 1; $url=''; ?> 
                    <tbody>
                        <?php //if(!empty($prev_topics) || isset($prev_topics) || $prev_topics!=''){ $i=1; 
                        if (empty($query)) { ?>
                        <tr><td></td>
                            <td></td>
                            <td style='text-align:center'> Not Assigned any patient!!!</td>
                            <td></td>
                            <td></td>
                        </tr>
                    <?php }else{ 
                        foreach($query as $key => $value){ ?>
                        <tr>
                        <td><?php  echo $i++;?></td>
                        <td><?php  echo $value->practice;?></td>
                        <?php
                         isset($value->fname)?$fname = $value->fname:$fname='';
                         isset($value->lname)?$lname = $value->lname:$lname='';
                         ?>
                         <?php
                            //dd($value);
                             $module_name     = strtolower(str_replace(' ', '-', $value->module));
                             $components_name1 = "monthly-monitoring";
                             $components_name2 = "care-plan-development";
                             $patient_id = $value->id;
                
                             $url1 = "/".$module_name."/".$components_name1."/".$patient_id;
                             $url2 = "/".$module_name."/".$components_name2."/".$patient_id;
                        ?> 
                        <td>
                            <!-- <a href="{{ $url }}" > -->
                            <?php echo $fname.' '.$lname;?></td>
                        <td><?php  $dob= $value->dob;
                                if($dob=='null' ||$dob==null){
                                    echo "";
                                }else{
                                    echo date('m-d-Y',strtotime($value->dob));
                                }?>
                        </td> 
                        <td>
                            <a href="{{ $url1 }}" ><button type="button" id="mmbtn" class="btn btn-primary">MM</button></a>
                            <a href="{{ $url2 }}" ><button type="button" id="cpdbtn" class="btn btn-primary">CPD</button></a>
                        </td>
                        </tr> 
                    <?php } }?>
                    </tbody>
                  </table>
                </div> 
            </div>
        <!-- end table -->
        <input type="hidden" id="count_patient" value="{{$i-1}}">
    </div> 
</div>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.js" integrity="sha512-3CuraBvy05nIgcoXjVN33mACRyI89ydVHg7y/HMN9wcTVbHeur0SeBzweSd/rxySapO7Tmfu68+JlKkLTnDFNg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    $( document ).ready(function() { 
        
        $("[name='practices1']").on("change", function () { 
            var practiceId =$(this).val();
            if(practiceId =='' || practiceId=='0'){
                var  practiceId= null;
                // util.getCmAssignPatientList(parseInt(practiceId), $("#patient1"));
            }else if(practiceId!=''){
                getCmAssignPatientList(parseInt(practiceId),$("#patient1")); 
            }else { 
                // var  practiceId= null;
                // util.getCmAssignPatientList(parseInt(practiceId), $("#patient1"));
            }
        });
    });

    // alert("working");
    // util.getAssignPatientListData(0,0);
        
    $('#searchbutton1').click(function(){
        var practice=$('#practices1').val();
        var patient=$('#patient1').val();

        if ((practice === "" || practice === null) && (patient === "" || patient === null)) {
            $("#errorPractice").show();
            setTimeout(function() {
                $("#errorPractice").hide(); 
            }, 4000); 
        } else {
            $("#errorPractice").hide(); 
        }
        getAssignPatientListData(practice,patient);   
    });

    $("#reset1").click(function(){   
        $('#practices1').val('').trigger('change');
        $('#patient1').val('').trigger('change'); 
        getAssignPatientListData(null,null);   
    });

    var getAssignPatientListData = function (practice,patient) { //debugger;
        if (practice == '') {
            practice = 0;
        }
        if (patient == '') {
            patient = 0;
        }
        axios({
            method: "GET",
            url: `/patients/cm-assignpatient/${practice}/${patient}/cmassignpatient`
        }).then(function (response) {
            // console.log(response.data);
            $("#patientassignlist").html(response.data);
            //alert();
            // $('.badge').html($('#count_patient').val());
        }).catch(function (error) {
            console.error(error, error.response);
        });
    };

    var getCmAssignPatientList = function (practiceId, selectElement, selectedPatients = null) {
        selectElement.html($("<option value=''>").html("Select Patient"));
        // if (!practiceId) {
        //     return;
        // }

        axios({
            method: "GET",
            //url: `/patients/ajax/practice/${practiceId}/${moduleId}/patient`
            url: `/patients/ajax/assignpatientlist/${practiceId}/assignpatientlist`,
        }).then(function (response) {
            Object.values(response.data).forEach(function (patient) {
                var mname;
                if ((patient.mname != "") && (patient.mname != null) && (patient.mname != undefined)) {
                    mname = patient.mname;
                } else {
                    mname = "";
                }
                $("<option>").val(patient.id).html(patient.fname + " " + mname + " " + patient.lname + ", DOB: " + moment(patient.dob).format('MM-DD-YYYY')).appendTo(selectElement);
                // $("<option>").val(patient.id).html(patient.fname + " " + mname + " " +patient.lname + ", DOB: " + viewsDateFormat(patient.dob)).appendTo(selectElement);
            });
            if (selectedPatients) {
                selectElement.val(selectedPatients);
            }
        }).catch(function (error) {
            console.error(error, error.response);
        });
    };
</script>