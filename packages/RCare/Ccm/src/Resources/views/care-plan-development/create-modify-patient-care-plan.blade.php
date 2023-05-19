<!DOCTYPE html>
<html>
<head>
<style type="text/css">

body{
    font-family:verdana;    
}
table, th {
 /* border: 0px solid black; */
 text-align:justify ;
 /*text-align:left;*/
 /*padding-left:3px;*/ 
}
table,td{
vertical-align: middle;
 
text-align:justify;
}

/*table, tr{ 
    margin-right: 10px; 
}
*/

#cpecialisttbl {
  /*font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;*/
  border-collapse: collapse;
  width: 100%;
}

#cpecialisttbl td, #cpecialisttbl th {
  border: 1px solid #ddd;
   text-align: center;
  /*padding: 8px;*/
}

#cpecialisttbl tr:nth-child(even){background-color: #f2f2f2;}

#cpecialisttbl tr:hover {background-color: #ddd;}

#cpecialisttbl th {
 padding-top:2px;
  padding-bottom: 2px;
  text-align: center;
  background-color: #1474a0;
  color: white;

}
b{
    color:#000033;

}
h4
{
   background-color:#cdebf9;
   color:#000033;
   padding-top: 8px;
    padding-bottom: 8px;
    padding-left: 7px;
    /*width: 330px;*/
}
h3
{
   padding-top: 5px;
    padding-bottom: 5px;
    padding-left: 7px;
    color:white;
}
#cpid
{
    float: right;
    color: white;
    font-size: 36px;
    font-family: -webkit-pictograph;   
    margin-top: -11px;
    margin-right: 10px;
}
</style>
    <title>CARE PLAN</title>

</head>
<body>

<div class="card">       
    <div class="card-body"> 
        <div class="form-row" style="background-color:#27a8de;height: 100px;">
                 <div class="form-row" margin-top="20px">
                    <img src="http://rcareproto2.d-insights.global/assets/images/logo.png" alt="" height="40px" width="150px" style="padding-top: 30px;padding-left: 17px;">
                 </div>
                <label id="cpid">Care Plan</label>
             </div>
              <div class="form-row" style="background-color:#1474a0;height: 10px;">
              </div>

        <div class="form-row" style="padding-top: 15px">
            <input type="hidden" name="hidden_id" id="hidden_id" value="{{$patient[0]->id}}">
            <input type="hidden" name="page_module_id" id="page_module_id" value="{{ getPageModuleName() }}">
            <!-- <input type="hidden" name="practice_id" id="practice_id" value="{{-- $patient[0]->practice_id --}}"> -->
           
           <table width = 100%>
            <tr class="col1">
               
                <td width="35%"><b>Name :</b>{{empty($patient[0]->fname) ? '' : $patient[0]->fname}} {{empty($patient[0]->lname) ? '' : $patient[0]->lname}}
                  </td>
                <td><b>Gender :</b>
                    <?php
                    if(isset($patient_demographics[0])){
                        if($patient_demographics[0]->gender == '1') {
                            echo 'Female';
                        } else if($patient_demographics[0]->gender == '0') {
                            echo 'Male';
                        }else {
                            echo '';
                        }
                    }?></td>
                    <td rowspan='5' width="10%">
                    <?php
                    $m_id = getPageModuleName();
                    $c_id = getPageSubModuleName();
                        $default_img_path = asset('assets/images/faces/avatar.png');
                        $path             = $patient[0]->profile_img;
                    ?>
                    @if(isset($patient[0]->profile_img) && ($patient[0]->profile_img != ""))
                        {{-- @if(file_exists($path))) --}}
                            <img src="{{ asset($path) }}" class='user-image' style="width: 100px;" />
                            {{-- @else --}}
                            <!-- <img src="{{-- $default_img_path --}}" class='user-image' style="width: 60px;" /> -->
                            {{-- @endif --}}
                        @else
                            <img src="{{ $default_img_path }}" class='user-image' style="width: 100px;" />
                    @endif
                </td>
                </tr>
                <tr>
                <td><b>EMR No.:</b> {{$patient[0]->emr}} 
                    <?php
                        if(isset($patient_providers[0])){
                            echo $patient_providers[0]->practice_emr;      
                        }
                    ?>
                </td>
                <td><b>Email :</b> {{$patient[0]->email}}</td>
            </tr>
           

            <tr class="col2">
                
                <td><b>DOB :</b> {{$patient[0]->dob}}</td>
                 <td><b>Age :</b> {{ empty($patient[0]->dob) ? '' : age($patient[0]->dob)}}</td>
               

            </tr>
            <tr>
                 <td><b>Enrolled On : </b><?php
                    $enroll_date="";
                        $module_id = getPageModuleName();                                    
                        if(isset($patient_enroll_date[0]->date_enrolled)){
                           $str = $patient_enroll_date[0]->date_enrolled; 
                            $enroll_date = date("m-d-Y", strtotime($str)); 
                            echo $enroll_date;   
                    }?></td>
                
                <td><b>Mobile :</b> {{$patient[0]->mob}}<?php if(isset($patient[0]->home_number) && $patient[0]->home_number!=''){echo ' | '.$patient[0]->home_number; }?></td>
                
               
                
            </tr>
            <tr colspan='2'><td><b>Total Time Spent : </b><?php echo (isset($last_time_spend) && ($last_time_spend!='0')) ? $last_time_spend : '00:00:00'; ?></td>
                 <td><b>Services : </b><?php if(isset($patient[0]->PatientServices)){
                        $enrollin = "";
                        foreach($patient[0]->PatientServices as $patient_service){
                            if(isset($services)){ 
                                foreach($services as $service){
                                    if($service['id'] == $patient_service->module_id){
                                        $enrollin = $enrollin . $service['module'].", ";                                                    
                                    }
                                }
                            } 
                        } 
                        $enrollin=rtrim($enrollin, ', ');
                        echo $enrollin;       
                    }?>
                </td>

            </tr>
           <!--
            
          
            <tr>
           
                <th>Status :</th>
                <td>
                    <?php 
                    if(isset($patient_enroll_date[0]->date_enrolled)){
                        $date = $patient_enroll_date[0]->date_enrolled;
                        if(date("m", strtotime($date)) == date("m")) {
                            echo "New";
                        } else {
                            echo "Existing";
                        }
                    }
                    ?>
                </td>
                
                
                    
                
                
                <th>Time :</th>
                <td>  </td>
            
            </tr>
        -->
        </table>
        </div>
    </div>
</div> 

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                   <!--  <h3 class="card-title mb-3" id="ID">Care Plan</h3> -->
                    <div style="background-color: #1474a0;color: white;margin-top:10px;">
                             <h3>PROBLEMS :</h3>                    
                     </div>
                  <!--   <div class="row">       -->               
                      <h4>Diagnosis Codes:</h4>   
                      <ol>              
                        <?php $i=0; ?>
                            @foreach ($PatientDiagnosis as $rec)
                            <li>                           
                                {{ $rec->condition }} ({{ $rec->code }}                          
                           </li>
                            @endforeach
                        </ol>
                        <br>
                   <!--  </div> -->
                    <hr>
                    <div class="row">
                        <label class="col-md-2" id="ID1"><h4>Diagnosis and Expected Outcome:</h4></label>
                        <div class="col-md-10" id="ID2">
                      
                        </div>
                    </div>
                      <hr>
                    <div class="row">
                        <label class="col-md-2" id="ID5"><h4>Treatment Goals:</h4></label>
                        <div class="col-md-10" id="ID6">
                               <?php $i=1;?>
                           <ol>
                            @foreach ($PatientDiag as $key => $value) 
                            <li>      
                           <?php  $data = json_decode($value->goals, true); 
                                 if(!empty($data))
                                {   
                            //  echo  $i++;                     
                                ?>                   
                            @foreach ($data as $sym)
                             <?php print_r($sym)?>
                                 @endforeach
                                 <br/>
                             <?php } ?>
                         </li>
                                  @endforeach       
                            </ol>
                        </div>
                    </div>
                     <hr>
                    <div class="row">
                        <label class="col-md-2" id="ID5"><h4>Treatment Goals Status:</h4></label>
                        <div class="col-md-10" id="ID6">
                                 
                            
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <label class="col-md-2" id="ID3"><h4>Symptoms:</h4></label>
                        <div class="col-md-10" id="ID4">
                            <?php $i=1;?> <ol>                  
                            @foreach ($PatientDiag as $key => $value)
                            <li>       
                               <?php  $data = json_decode($value->symptoms, true);  
                                 if(!empty($data))
                                {  
                            //  echo  $i++;  

                                ?>                   
                            @foreach ($data as $sym)
                            <?php print_r($sym)?>

                             @endforeach        
                               
                                 <br/>
                             <?php }?>
                         </li>
                            @endforeach 
                            </ol>                
                        </div>
                    </div>
                  
                   
                    <hr>
                    <div class="row">
                        <label class="col-md-2" id="ID9"><h4>Medications:</h4></label>
                        <div class="col-md-10" id="ID10">
                            <ol>
                        <?php //print_r($PatientMedication[0]->medication['description']);?>
                        <?php $i=1;?>
                            @foreach ($PatientMedication as $key => $value) 
                             <li>
                             <?php if($value->medication['description']!='' || $value->medication['description']!=null){?>  
                                {{$value->medication['description']}}
                            <?php }  if($value->description!='' || $value->description!=null){?>
                            Description:{{$value->description}},
                            <?php }  if($value->purpose!='' || $value->purpose!=null){?>
                            Purpose:{{$value->purpose}},
                            <?php }  if($value->dosage!='' || $value->dosage!=null){?>
                            Dosage:{{$value->dosage}},
                            <?php }  if($value->stregth!='' || $value->stregth!=null){?>
                            Stregth:{{$value->stregth}},
                            <?php }  if($value->frequency!='' || $value->frequency!=null){?>
                            Frequency:{{$value->frequency}},
                            <?php }  if($value->route!='' || $value->route!=null){?>
                            Route:{{$value->route}},
                            <?php }  if($value->drug_reaction!='' || $value->drug_reaction!=null){?>
                            Drug Reaction :{{$value->drug_reaction}},
                           <?php }  if($value->pharmacogenetic_test!='' || $value->pharmacogenetic_test!=null){?>
                            Pharmacogenetic test :{{$value->pharmacogenetic_test}}.
                        <?php } ?>
                           
                        </li>
                             @endforeach        
                            </ol>
                            
                        </div>
                    </div>
                    
                   <hr>
                    <div class="row">
                        <label class="col-md-2" id="ID7"><h4>Tasks:</h4></label>
                        <div class="col-md-10" id="ID8">
                            <?php $i=1;?>
                           <ol>
                            @foreach ($PatientDiag as $key => $value)  
                            <li>     
                                <?php  //$data = json_decode($value->tasks, true);   
                                 if(!empty($data))
                                { 
                             // echo  $i++;                     
                                ?>                       
                            @foreach ($data as $sym)
                             <?php print_r($sym)?>
                                 @endforeach
                                 <br/>
                             <?php } ?>
                         </li>
                                  @endforeach    
                                  </ol>   
                        </div>
                    </div> 
                   
                    <div class="row">
                        <label class="col-md-2" id="ID11"><h4>Allergies:</h4></label>
                        <div class="col-md-10"id="ID12">
                            <?php $i=1;?>
                            <ol>
                            @foreach ($PatientAllergy as $Aller_rec)
                            <li>
                            <?php if (!empty($Aller_rec)) { ?>
                          
                            <?php } if($Aller_rec->allergy_type!='' || $Aller_rec->allergy_type!=null){?>
                                Allergy Type:{{ $Aller_rec->allergy_type }},
                            <?php } if($Aller_rec->type_of_reactions!='' || $Aller_rec->type_of_reactions!=null){?>
                                Type of Reaction:{{ $Aller_rec->type_of_reactions }},
                          <?php } if($Aller_rec->specify!='' || $Aller_rec->specify!=null){?>
                                {{ $Aller_rec->specify }}
                            <?php } ?>
                           </li>
                            @endforeach
                        </ol>
                            <label id="Allergies"></label>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <label class="col-md-2" id="ID13"><h4>Supports:</h4></label>
                        <div class="col-md-10" id="ID14"> 
                               <?php $i=1;?>
                           <ol>
                            @foreach ($PatientDiag as $key => $value) 
                            <li>      
                                <?php  
                                if($value->support!='' || $value->support!=null)
                                { 
                            //  echo  $i++;                     
                                ?>                       
                           {{$value->support}}
                                 <br/>
                                   <?php } ?>
                               </li>
                                  @endforeach   
                              </ol>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <label class="col-md-2" id="ID15"><h4>Comments:</h4></label>
                        <div class="col-md-10" id="ID16">
                            <?php $i=1;?>
                           <ol>
                            @foreach ($PatientDiag as $key => $value)   
                            <li>    
                                <?php  $data = json_decode($value->comments, true);   
                                if($value->comments!=null || $value->comments!='')
                                {
                           //   echo  $i++;                     
                                ?>                        
                           {{$value->comments}}
                                  <br/>
                                 <?php } ?>
                             </li>
                                
                                  @endforeach
                              </ol>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <label class="col-md-2" id="ID15"><h4>Vitals Data:</h4></label>
                        <div class="col-md-10" id="ID16">
                            @foreach ($patient_vitals as $rec)
                           <table width = 100%>
                                <tr class="vital_data">
                                <td><b>Height:</b> {{ $patient_vitals[0]->height }}</td>
                                <td><b>Weight:</b> {{ $patient_vitals[0]->weight }}</td>
                                <td><b>BMI:</b> {{$patient_vitals[0]->bmi}}</td>
                                <td><b>BP:</b> {{$patient_vitals[0]->bp}} / {{$patient_vitals[0]->diastolic}}</td>
                                <td><b>O2:</b> {{$patient_vitals[0]->o2}}</td>
                                <td><b>Pulse Rate:</b> {{$patient_vitals[0]->pulse_rate}}</td>
                                </tr>
                            </table>
                            @endforeach
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <label class="col-md-2" id="ID15"><h4>Labs </h4></label>
                    @foreach ($patient_lab_test_name as $recs => $value)   
                    <?php  
                                if($value->description!='' || $value->description!=null)
                                { 
                                               
                                ?>                  
                           <b>{{$value->description}}</b>
                                 <br/>
                            <?php }  $counter=1; ?>         
                            <table width = 100%>
                                <tr class="vital_data">  
                        @foreach ($patient_lab as $rec)
                            <?php if($value->description ==  $rec->description){ ?>        
                            
                                <td><b>{{$rec->parameter}} : </b>{{$rec->high_val}} ({{$rec->reading}})</td>
                                <!--td width=30%><b>Notes :</b> </td-->
                                
                                <?php   
                                echo "\n"; 
                                if ($counter >= 3){
                                    echo '</tr><tr>';   
                                    $counter=0;     
                                }
                                $counter++;
                            }
                                ?>        
                        @endforeach
                        </tr>
                        </table>
                        <?php $n = 1; ?>
                        @foreach ($patient_lab as $notes)

                        <?php if($value->description ==  $notes->description && $n == 1){  echo "<br>"; ?>
            
                            
                        <?php echo "<hr>"; $n++;}   ?>   
                        @endforeach

                    @endforeach      
                    </div>

                     
             <div style="background-color: #1474a0;color: white;margin-top:10px;">
                             <h3>CARE TEAM :</h3>                    
                     </div>                  
                     
                    <div class="row">
                        <label class="col-md-2" id="ID11"><h4>Specialist Details</h4></label>
                        <div class="col-md-12" id="ID12">                           
                            
                            <table id="cpecialisttbl">
                                <thead>
                                    <tr>
                                        <th width="20%">Specialist Name</th>
                                        <th width="20%">Practice</th>
                                        <th width="20%">Address</th>
                                        <th width="20%">Phone No</th>
                                        <th width="20%">Date of Last </th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($PatientSpecialistProvider as $key => $value)
                                    <tr>
                                        <td>{{$value->provider['name']}}</td>
                                        <td>{{$value->practice['name']}}</td>
                                        <td>{{$value->address}}</td>
                                        <td>{{$value->phone_no}}</td>
                                        <td>{{$value->last_visit_date}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                   
                    <div class="row">
                        <label class="col-md-2" id="ID11"><h4>Details of Care Giver</h4></label>
                        
                    </div>
                     <div class="row">
                     <table class="table-responsive" width = 100%>                          
                     <tr> <td><b> Name : </b> {{empty($PatientCareGiver->fname) ? '' : $PatientCareGiver->fname}} {{empty($PatientCareGiver->lname) ? '' : $PatientCareGiver->lname}}
                      </td>
                      <td><b> Address : </b> {{empty($PatientCareGiver->address) ? '' : $PatientCareGiver->address}} </td> </tr>
                    <tr> <td>  <b> Phone No. : </b> {{empty($PatientCareGiver->mobile) ? '' : $PatientCareGiver->mobile}} </td>
                     <td> <b> Relationship to Patient :</b> {{empty($PatientCareGiver->relationship) ? '' : $PatientCareGiver->relationship}}  </td></tr>
                     <tr> <td> <b> Email Id : </b> {{empty($PatientCareGiver->email) ? '' : $PatientCareGiver->email}} </td></tr>
                        </table>
                    </div>
                   
                   <div class="row">
                        <label class="col-md-2" id="ID11"><h4>Details of Emergency Contact</h4></label>
                        
                    </div>
                     <div class="row">
                     <table class="table-responsive" width = 100%>                          
                     <tr> <td><b> Name : </b> {{empty($PatientEmergencyContact->fname) ? '' : $PatientEmergencyContact->fname}} {{empty($PatientEmergencyContact->lname) ? '' : $PatientEmergencyContact->lname}}
                      </td>
                      <td><b> Address : </b>  {{empty($PatientEmergencyContact->address) ? '' : $PatientEmergencyContact->address}} </td> </tr>
                    <tr> <td>  <b> Phone No. : </b> {{empty($PatientEmergencyContact->mobile) ? '' : $PatientEmergencyContact->mobile}}</td>
                     <td> <b> Relationship to Patient : </b> {{empty($PatientEmergencyContact->relationship) ? '' : $PatientEmergencyContact->fname}} </td></tr>
                     <tr> <td> <b> Email Id : </b>  {{empty($PatientEmergencyContact->email) ? '' : $PatientEmergencyContact->email}} </td></tr>
                        </table>
                    </div>
                   
                   
                </div>
            </div>
        </div>
    </div>
    <br>
<hr>
<br>
   
<div style="float: right;">
    Electronic Care Manager Signature
    </div>
    <br>
    <br>
    <br>
    <br><br>
    
   
 <div class="form-row" style="background-color:#1474a0;height: 10px;">
              </div>
     <div class="form-row" style="background-color:#27a8de;height: 80px;">                
             </div>
</body>
</html>
