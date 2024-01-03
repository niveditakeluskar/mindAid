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
                        </tr> 
                    </thead>
                    <tbody>
                        <?php //if(!empty($prev_topics) || isset($prev_topics) || $prev_topics!=''){ $i=1; 
                        if($query==0){ ?>
                        <tr><td></td>
                            <td style='text-align:center'> Not Assigned any patient!!!</td>
                            <td></td>
                        </tr>
                    <?php }else{$i=1;
                        foreach($query as $key => $value){?>
                        <tr>
                        <td><?php  echo $i++;?></td>
                        <td><?php  echo $value->practice;?></td>
                        <td>
                        <?php
                         isset($value->fname)?$fname = $value->fname:$fname='';
                         isset($value->lname)?$lname = $value->lname:$lname='';
                         ?>
                         <?php echo $fname.' '.$lname;?></td>
                        <td><?php  $dob= $value->dob;
                                if($dob=='null' ||$dob==null){
                                    echo "";
                                }else{
                                    echo date('m-d-Y',strtotime($value->dob));
                                }?>
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
<script>
    $( document ).ready(function() { 

        $("[name='practices1']").on("change", function () { 
            var practiceId =$(this).val();
            if(practiceId =='' || practiceId=='0'){
                var  practiceId= null;
                // util.getCmAssignPatientList(parseInt(practiceId), $("#patient1"));
            }else if(practiceId!=''){
                util.getCmAssignPatientList(parseInt(practiceId),$("#patient1")); 
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
        util.getAssignPatientListData(practice,patient);   
    });

    $("#reset1").click(function(){   
        $('#practices1').val('').trigger('change');
        $('#patient1').val('').trigger('change'); 
        util.getAssignPatientListData(null,null);   
    });
</script>