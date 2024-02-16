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
/* text-align:left; */
}

/* table, tr{ 
    margin-right: 10px; 
} */
/* 
table, th, td {
  border: 1px solid black;
} */
#cpecialisttbl {
  /*font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;*/
  border-collapse: collapse;
  width: 100%;
}

#cpecialisttbl td, #cpecialisttbl th {
  border: 1px solid #ddd;
   text-align: left;
  padding: 6px;
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
.header {
    top: 0px;
}
</style>
    <title>CARE PLAN</title>

</head>
<body>

<div class="card">       
    <div class="card-body" style="margin: -45px -45px -0px -45px"> 
        <div class="form-row header" style="background-color:#27a8de;height: 100px;">
                 <div class="form-row" margin-top="20px">
                  <?php if(isset($patient_providers[0]->practice['logo'])){?>
                    <img src="http://rcareproto2.d-insights.global/PracticeLogo/{{$patient_providers[0]->practice['logo']}}" alt="" height="50px" width="150px" style="padding-top: 20px;padding-left: 17px;">
                  <?php } ?>
                 </div>
                <label id="cpid">Care Plan</label>
             </div>
              <div class="form-row" style="background-color:#1474a0;height: 10px;">
              </div>
</div>
        <div class="form-row" style="padding-top: 15px">
            <input type="hidden" name="hidden_id" id="hidden_id" value="{{$patient[0]->id}}">
            <input type="hidden" name="page_module_id" id="page_module_id" value="{{ getPageModuleName() }}">
            <!-- <input type="hidden" name="practice_id" id="practice_id" value="{{-- $patient[0]->practice_id --}}"> -->
            <div style="background-color: #1474a0;color: white;margin-top:10px;">
                             <h3>Patient Info :</h3>                    
                     </div>
           <table width = 100%>
            <tr class="col1">
               
                <td colspan="2"><b>Name :  </b>{{empty($patient[0]->fname) ? '' : $patient[0]->fname}} {{empty($patient[0]->lname) ? '' : $patient[0]->lname}} (
                <?php
                    if(isset($patient_demographics[0])){
                        if($patient_demographics[0]->gender == '1') {
                            echo 'Female';
                        } else if($patient_demographics[0]->gender == '0') {
                            echo 'Male';
                        }else {
                            echo '';
                        }
                    }?> )  


                  </td>

                  <td><b>DOB :</b> {{date("m-d-Y", strtotime($patient[0]->dob))}} ( {{ empty($patient[0]->dob) ? '' : age($patient[0]->dob)}} )</td> 
                <!-- <td><b>Gender :</b>
                    ?php
                    if(isset($patient_demographics[0])){
                        if($patient_demographics[0]->gender == '1') {
                            echo 'Female';
                        } else if($patient_demographics[0]->gender == '0') {
                            echo 'Male';
                        }else {
                            echo '';
                        }
                    }?></td>

                    <td><b>DOB :</b> {{date("m-d-Y", strtotime($patient[0]->dob))}}</td> -->
                    


                    <td rowspan="2" width="2%">
                    <?php
                    $m_id = getPageModuleName();
                    $c_id = getPageSubModuleName();
                        $default_img_path = asset('assets/images/faces/avatar.png');
                        $path             = $patient[0]->profile_img;
                    ?>
                    @if(isset($patient[0]->profile_img) && ($patient[0]->profile_img != ""))
                        {{-- @if(file_exists($path))) --}}
                            <img src="{{ asset($path) }}" class='user-image' style="width: 50px;" />
                            {{-- @else --}}
                            <!-- <img src="{{-- $default_img_path --}}" class='user-image' style="width: 60px;" /> -->
                            {{-- @endif --}}
                        @else
                            <img src="{{ $default_img_path }}" class='user-image' style="width: 50px;" />
                    @endif
                </td>
                </tr>

                <!-- <tr>
                 <td><b>DOB :</b> {{date("m-d-Y", strtotime($patient[0]->dob))}}</td>
                <td><b>Email :</b> {{$patient[0]->email}}</td>
            </tr> -->

            <tr class="col2">
            <!-- <td><b>Age :</b> {{ empty($patient[0]->dob) ? '' : age($patient[0]->dob)}}</td> -->
            <td colspan="2"><b>Mobile :</b> {{$patient[0]->mob}}</td>
            <td><b>Phone No. :  </b><?php if(isset($patient[0]->home_number) && $patient[0]->home_number!=''){echo $patient[0]->home_number; }?></td>
            </tr> 

            <!-- <tr class="col3">
           
            </tr> -->

           

            <tr class="col3">
            <td colspan="2">
           <b>PCP :</b> 
            

          <!-- {{empty($patient_providers[0]->provider['name']) ? '' : $patient_providers[0]->provider['name']}}</td> -->

            <?php if((isset($patient_providers[0]->provider))) {
              echo $patient_providers[0]->provider['name']; 
           }
           else if(isset($patient_providers[1]->provider)) {
            echo $patient_providersusers[1]->provider['name']; 
           }
           else{
               echo " "; 
           }
           ?>
            </td>

            <td colspan="2">
           <b>Practice :</b>
          <!-- {{empty($patient_providers[0]->practice['name']) ? '' : $patient_providers[0]->practice['name']}}</td> -->

          <?php if((isset($patient_providers[0]->practice))) {
              echo $patient_providers[0]->practice['name']; 
           }
           else if(isset($patient_providers[1]->practice)) {
            echo $patient_providersusers[1]->practice['name']; 
           }
           else{
               echo " "; 
           }
           ?>

          <!-- ?php if((count($patient_providers)>1) &&(isset($patient_providers[1]->practice))) {
              echo $patient_providers[1]->practice['name']; 
           }
           else if((count($patient_providers)==1) &&(isset($patient_providers[0]->practice))) {
            echo $patient_providersusers[0]->practice['name']; 
           }
           else{
               echo " "; 
           }
           ?> -->

          
          </tr> 
          
          <tr class="col4">
          <td colspan="2">
           <b>Practice Phone No. :</b>
           <!-- {{empty($patient_providers[0]->practice['outgoing_phone_number']) ? '' : $patient_providers[0]->practice['outgoing_phone_number']}}</td> -->
           
           <?php if((isset($patient_providers[0]->practice))) {
              echo $patient_providers[0]->practice['phone']; 
           }
           else if(isset($patient_providers[1]->practice)) {
            echo $patient_providersusers[1]->practice['phone']; 
           }
           else{
               echo " "; 
           }
           ?>

           <!-- ?php if((count($patient_providers)>1) &&(isset($patient_providers[1]->practice))) {
              echo $patient_providers[1]->practice['outgoing_phone_number']; 
           }
           else if((count($patient_providers)==1) &&(isset($patient_providers[0]->practice))) {
            echo $patient_providers[0]->practice['outgoing_phone_number']; 
           }
           else{
               echo " "; 
           }
           ?> -->
             

            <td>
            <b>EMR No:</b> {{$patient[0]->emr}} 
            <?php
                        if(isset($patient_providers[0])){
                            echo $patient_providers[0]->practice_emr;      
                        }
                    ?>
                </td>
          </tr>
          

          <tr class="col5"> 
          <td colspan="2">
           <b>Care Manager's Name :</b> 
            {{empty($caremanager[0]->f_name) ? '' : $caremanager[0]->f_name." ".$caremanager[0]->l_name}}
           <!-- ?php if((count($patient_providersusers)>1) &&(isset($patient_providersusers[1]->users))) {
              echo $patient_providersusers[1]->users['f_name']." ".$patient_providersusers[1]->users['l_name']; 
           }
           else if((count($patient_providersusers)==1) &&(isset($patient_providersusers[0]->users))) {
            echo $patient_providersusers[0]->users['f_name']." ".$patient_providersusers[0]->users['l_name']; 
           }
           else{
               echo " "; 
           }
           ?> -->
          </td>

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

          <tr class="col5"> 
          <td colspan="2"><b>Enrolled On : </b>
          <?php
        
                    $enroll_date="";
                        $module_id = getPageModuleName();                                    
                        if(isset($patient_enroll_date[0]->date_enrolled)){
                           $str = $patient_enroll_date[0]->date_enrolled;
                            // $enroll_date = date("m-d-Y", strtotime($str)); 
                            $enroll_date = explode(" ",$str); 
                            echo $enroll_date[0];   
                           }
                           else{ 
                            echo $enroll_date;   
                           }
                    
                    
                    ?></td> 
          <td><b>Total Time Spent : </b><?php echo (isset($last_time_spend) && ($last_time_spend!='0')) ? $last_time_spend : '00:00:00'; ?>
          </tr>

           

            <!-- <tr class="col2">
                
               
                 <td><b>Age :</b> {{ empty($patient[0]->dob) ? '' : age($patient[0]->dob)}}</td>
               <td><b>Enrolled On : </b>
               /*?php
                    $enroll_date="";
                        $module_id = getPageModuleName();                                    
                        if(isset($patient_enroll_date[0]->date_enrolled)){
                           $str = $patient_enroll_date[0]->date_enrolled; 
                            $enroll_date = date("m-d-Y", strtotime($str)); 
                            echo $enroll_date;   
                    }?></td>

            </tr> -->
            <!-- <tr>
                 
                <td><b>Total Time Spent : </b>/*?php echo (isset($last_time_spend) && ($last_time_spend!='0')) ? $last_time_spend : '00:00:00'; ?>
                <td><b>Mobile :</b> {{$patient[0]->mob}}?php if(isset($patient[0]->home_number) && $patient[0]->home_number!=''){echo ' | '.$patient[0]->home_number; }?></td>
                
               
                
            </tr> -->
            <!-- <tr colspan='2'></td>
                 

            </tr> -->
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
        <!-- <hr> -->
       
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

                    <div class="row">
                    <h4>Diagnosis Codes:</h4>
                    @if(count($PatientDiagnosis)>0)
                    
                    <table id="cpecialisttbl">
                                <thead>
                                    <tr>
                                        <th width="20%">Date</th>
                                        <th width="20%">Condition</th> 
                                       
                                      
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($PatientDiagnosis as $rec)
                                    <tr>
                                      <td> 
                                      <?php 
                                        $str1 = $rec->date;
                                        // $conditiondate = date("m-d-Y", strtotime($str1));
                                        $conditiondate = explode(" ",$str1); 
                                     
                                        echo $conditiondate[0];
                                        ?>
                                     </td>  
                                      <td> {{ $rec->condition }} ({{ $rec->code }}) </td>
                                    </tr>
                                   @endforeach       
                                </tbody>
                       </table>
                       @endif
                 
                    </div>
                    
                    <hr>
                    <div class="row">
                        <label class="col-md-2" id="ID1"><h4>Prognosis and Expected Outcome:</h4></label>
                        <div class="col-md-10" id="ID2">
                      
                        </div>
                    </div>
                      <hr>
                    <div class="row">
                        <label class="col-md-2" id="ID5"><h4>Treatment Goals:</h4></label>
                       
                        <div class="col-md-10" id="ID6">
                            <?php $i=1;?>
                           <ol>
                            @if(count($PatientDiag)>0)
                              @foreach ($PatientDiag as $key => $value)
                                  <?php  
                                  $goalsData = json_decode($value->goals, true);  
                                  //  if(!empty($data))
                                  if(!empty($goalsData) || $goalsData[0]!='blank') {  
                                  //  echo  $i++;   
                                  ?> 
                                  <li>                  
                                      @foreach($goalsData as $key => $goals)
                                          <?php print_r(htmlentities($goals))?>
                                      <br/>
                                  </li>
                                  @endforeach   
                                  <?php 
                                  }
                                  ?>
                              @endforeach
                            @endif     
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
                               <?php  $data = json_decode($value->symptoms, true);  
                                //  if(!empty($data))
                                if($data[0]!='blank'){?> 
                              <li>                  
                                @foreach ($data as $sym)
                                  <?php print_r(htmlentities($sym))?>
                                @endforeach        
                                <br/>
                              </li>
                             <?php }?>
                            @endforeach 
                            </ol>                
                        </div>
                    </div>
                  
                    
                    <hr>
                   
                    <div>
                    <div class="row">
                    <label class="col-md-2" id="ID11"><h4>Medications:</h4></label>
                    <div class="col-md-10"id="ID12">

                       <table id="cpecialisttbl">
                                <thead> 
                                    <tr> 
                                        <th width="20%">Date</th>
                                        <th width="20%">Name</th>
                                        <th width="20%">Purpose</th>
                                        <th width="20%">Dosage</th>
                                        <th width="20%">Route</th>
                                        <th width="20%">Frequency</th>
                                    </tr>
                                </thead>
                                <tbody> 
                                    
                                    
                                @foreach ($PatientMedication1 as $key => $value)  
                                      <tr>
                                      <!-- <td>{{ ($value->date =='null') ? '' : date('m-d-Y',strtotime($value->date))  }}</td> -->
                                      <td> <?php
                                        $diagdate="";
                                                                               
                                            if(($value->date!= 'null')){
                                            $str = $value->date;
                                                // $enroll_date = date("m-d-Y", strtotime($str)); 
                                                $diagdate = explode(" ",$str); 
                                                echo $diagdate[0];   
                                            }
                                            else{ 
                                                echo $diagdate;       
                                            }
                                        
                                        
                                        ?></td>
                                      <td>{{ ($value->description =='null') ? '' : $value->description  }}</td> 
                                      <td>{{ ($value->purpose =='null') ? '' : $value->purpose  }}</td>
                                      <td>{{ ($value->dosage =='null') ? '' : $value->dosage  }}</td>
                                      <td>{{ ($value->route =='null') ? '' : $value->route  }}</td>
                                      <td>{{ ($value->frequency =='null') ? '' : $value->frequency  }}</td> 
                                    </tr>
                                    @endforeach   
                                   
                                        
                                </tbody>
                       </table> 
                    </div>
                    </div>
                    </div> 
                  
                   <hr>
                    <div class="row">
                        <label class="col-md-2" id="ID7"><h4>Tasks:</h4></label>
                        <div class="col-md-10" id="ID8">
                            <?php $i=1;?>
                           <ol>
                            @foreach ($PatientDiag as $key => $value)  
                               
                                <?php  $data = json_decode($value->tasks, true);   
                                //  if(!empty($data))
                                if($data[0]!='blank')
                                { 
                             // echo  $i++;                     
                                ?>  
                                 <li>                     
                            @foreach ($data as $sym)
                             <?php print_r(htmlentities($sym))?>
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
                    <label class="col-md-2" id="ID11"><h4>Allergies:</h4></label>
                    <div class="col-md-10"id="ID12">
                   
                       @foreach ($PatientAllergy1 as $key => $value)
                    <b>Date</b> :
                    {{empty($value['displaydate'])?'':date("m-d-Y", strtotime($value['displaydate']))}}
                    <!-- ?php
                     $disp = $value['displaydate'];
                     echo $disp; 
                    ?> -->
                                 
                      <table id="cpecialisttbl">
                                <thead>
                                    <tr> 
                                        <th width="20%">Name</th>
                                        <th width="20%">Specify</th>
                                        <th width="20%">Reaction</th>
                                        <th width="20%">Severity</th>
                                        <th width="20%">Treatment</th>
                                        <th width="20%">Allergy Status</th>  
                                      
                                    </tr>
                                </thead>
                                <tbody> 
                                    
                                    
                                @foreach ($value['date'] as $key => $val) 
                                      <tr>
                                      <td>{{$val['allergy_type']}}</td> 
                                      <td>{{$val['specify']}}</td>
                                      <td>{{$val['type_of_reactions']}}</td>
                                      <td>{{$val['severity']}}</td>
                                      <td>{{$val['course_of_treatment']}}</td> 
                                      <td>{{$val['allergy_status']}}</td> 
                                    </tr>
                                    @endforeach   
                                   
                                        
                                </tbody>
                       </table>
                       <br> 
                       @endforeach 
                    </div>
                    </div> 
                    <hr>
                    <div class="row">
                        <label class="col-md-2" id="ID15"><h4>Comments:</h4></label>
                        <div class="col-md-10" id="ID16">
                            <?php $i=1;?>
                            @foreach ($patient_cmnt as $key => $value)   
                              <?php  //$data = json_decode($value->comments, true);   
                              if($value->comments!=null || $value->comments!='')
                              {?> {{$value->comments}}<br/>
                              <?php } ?>                          
                            @endforeach
                        </div>
                    </div>
                    <hr> 
             <div class="row">

                    <label class="col-md-2" id="ID15"><h4>Services:</h4></label>
                   
                    @foreach ($patient_services1 as $key => $value)
                    <b>Date</b> : {{empty($value->displaydate)?'':date("m-d-Y", strtotime($value->displaydate))}}
                   
                                 
                      <table id="cpecialisttbl">
                                <thead>
                                    <tr> 
                                        <th width="10%">Name</th>
                                        <th width="10%">Purpose</th>
                                        <th width="10%">Specify</th>
                                        <th width="10%">Brand</th>
                                        <th width="10%">Frequency</th>                                      
                                        <th width="15%">Start Date</th>
                                        <th width="15%">End Date</th>
                                        <th width="10%">Notes</th>
                                      
                                    </tr>
                                </thead>
                                <tbody> 
                                    
                                    
                                @foreach ($value->date as $key => $val) 
                                      <tr>
                                      <td>{{$val->type}}</td> 
                                      <td>{{$val->purpose}}</td>
                                      <td>{{$val->specify}}</td>
                                      <td>{{$val->brand}}</td> 
                                      <td>{{$val->frequency}}</td>    
                                      <td> <?php $timestamp= $val->service_start_date;
                                      $splitTimeStamp = explode(" ",$timestamp);$s_date = $splitTimeStamp[0];
                                      isset($s_date) && $s_date!='01-01-1970'?print_r($s_date):''; ?></td>
                                      <td><?php $timestamp= $val->service_end_date;
                                      $splitTimeStamp = explode(" ",$timestamp);$e_date = $splitTimeStamp[0]; 
                                      isset($e_date) && $e_date!='01-01-1970'?print_r($e_date):''; ?> </td>
                                      <td>{{$val->notes}}</td>  
                                    </tr>
                                    @endforeach   
                                   
                                        
                                </tbody>
                       </table>
                       <br> 
                       @endforeach 
                 
             </div>

             
      

                    <div class="row">
                        <label class="col-md-2" id="ID15"><h4>Vitals Data:</h4></label>
                        <div class="col-md-10" id="ID16">
                            @foreach ($patient_vitals as $rec)
                            @if(!empty($rec->weight||$rec->bmi||$rec->bp||$rec->o2||$rec->height||$rec->pulse_rate))
                           <table width = 100%>
                            <tr class="vital_data">
                              <td><b>Date: </b>
                                <?php $date = explode(" ", $rec->date); ?>
                                {{$date[0]}}  
                              </td>
                            </tr>
                                <tr class="vital_data">
                                  
                                <td><b>Height:</b> {{ $rec->height }}</td>
                                <td><b>Weight:</b> {{ $rec->weight }}</td>
                                <td><b>BMI:</b> {{$rec->bmi}}</td>
                                <td><b>BP:</b> {{$rec->bp}} / {{$rec->diastolic}}</td>
                                <td><b>O2:</b> {{$rec->o2}}</td>
                                <td><b>Pulse Rate:</b> {{$rec->pulse_rate}}</td>
                                </tr>
                            </table>
                            @endif
                            <?php $oxygen = $rec->oxygen;
                            if($oxygen=='1'){?>
                              <b><?php echo "Room Air";}else{?></b>
                              <b><?php echo "Supplemental Oxygen";?></b>
                              <?php }?>
                            <?php $notes = $rec->notes; 
                            if($notes!='null' && $notes!=null && $notes!=''){?>
                            <b>Notes : </b><?php echo $notes; }?>
                            
                            @endforeach 
                        </div>
                    </div>
                    <hr>  
                    <div class="row">

<label class="col-md-2" id="ID15"><h4>Labs:</h4></label>
 
@foreach ($patient_lab1 as $key => $value) 

Lab : <b>{{ empty($value->description)?'': $value->description }} </b>&nbsp;&nbsp;&nbsp;
    <?php if($value->lab_date_exist=='1'){ echo "Lab Date";}else {echo "CPD Date";} ?> :
    <b>
    <?php
    $labdate=$value->displaydate;
    $labs = explode(" ",$labdate);
    $labs1 = $labs[0];
    $labs2 = explode("-",$labs1);
    $mylabsdate= $labs2[1]."-".$labs2[2]."-".$labs2[0] ;   
    
    ?>
    {{ empty($mylabsdate)?'':$mylabsdate  }} 
    </b>  
  <table id="cpecialisttbl">
            <thead>
                <tr><!--  <th width="20%"> Date</th> -->
                    <th width="20%">Parameter</th>
                    <th width="20%">Reading</th>
                    <th width="20%">Value</th>
                  <!--   <th width="20%">Notes</th> -->
                </tr>
            </thead>
            <tbody>  
                
                
            @foreach ($value->date as $key => $val) 
                  <tr>
                <!--   <td></td> -->
                  <td>{{$val->parameter}}</td> 
                  <td>{{$val->reading}}</td>
                  <td>{{$val->high_val}}</td> 
                 <!--  <td>{{$val->notes}}</td>  -->
                </tr>
                @endforeach    
               
                    
            </tbody>
   </table>
   <br> 
  <b> Note : </b> {{ empty($value->notes)?'':$value->notes}} 
  <br>  
   <hr>
   @endforeach 

</div>

                   

                    <hr>
                     <div clas="row">
                     <label class="col-md-2" id="ID16"><h4>Health Data </h4></label>
                     <table id="cpecialisttbl">
                                <thead>
                                    <tr>
                                        <th width="10%">Date</th>
                                        <th width="30%">Health Data</th>
                                    </tr> 
                                </thead> 
                                <tbody>
                                
                                    @foreach ($patient_healthdata as $health_data)
                                   
                                    <tr>
                                      <td> 
                                      <?php $healthdate=explode(" ", $health_data->updated_at);
                                      if($health_data->health_date != ''){
                                        // echo date("m-d-Y", strtotime($health_data->health_date));
                                        $h1 = explode(" ",$health_data->health_date);
                                        $h2= $h1[0];
                                        $h3 = explode("-",$h2);
                                        $myhealthdate= $h3[1]."-".$h3[2]."-".$h3[0] ; 
                                        echo $myhealthdate;  
                                    }else{

                                    
                                  ?> 
                                  {{empty($health_data->updated_at)?'':$healthdate[0]}}
                                    <?php  echo '(CPD Date)'; } ?>
                                         
                                     </td>  
                                      <td> {{$health_data->health_data}} </td>
                                    </tr>
                                   @endforeach       
                                </tbody>
                       </table>
                     </div> 
                    
                     <hr>

                    <div clas="row">
                     <label class="col-md-2" id="ID16"><h4>Imaging Data </h4></label>
                     <table id="cpecialisttbl">
                                <thead>
                                    <tr>
                                        <th width="10%">Date</th>
                                        <th width="30%">Imaging Data</th>
                                       
                                      
                                    </tr> 
                                </thead> 
                                <tbody>
                                    @foreach ($patient_imaging as $health)
                                    <tr>
                                      <td> 
                                      <?php $imagdate=explode(" ", $health->updated_at);
                                      if($health->imaging_date != ''){
                                        $healthimgdate = $health->imaging_date;
                                        $d = explode(" ",$healthimgdate);  
                                        echo $d[0]; 
                                      }else{
                                      ?> 
                                      {{empty($health->updated_at)?'':$imagdate[0]}}
                                      <?php  echo '(CPD Date)'; } ?>
                                     </td>  
                                      <td> {{$health->imaging_details}} </td>  
                                    </tr>
                                   @endforeach       
                                </tbody>
                       </table>
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
                                        <th width="20%">Provider Name</th>
                                        <th width="20%">Practice</th>
                                        <th width="20%">Address</th>
                                        <th width="20%">Phone No</th>
                                        <th width="20%">Last Visit Date</th>
                                    </tr>
                                </thead> 
                                <tbody>
                                  @if(isset($PatientSpecialistProvider))
                                  @foreach ($PatientSpecialistProvider as $key => $value)
                                  <tr>
                                    <?php //dd($value->practice_name);?>
                                      <td>{{$value->provider_name}}</td>
                                      <td>{{isset($value->practice_name)?$value->practice_name:'Other'}}</td>
                                      <td>{{$value->address}}</td>  
                                      <td>{{$value->phone_no}}</td>
                                      <td><?php $timestamp= $value->last_visit_date;
                                      $splitTimeStamp = explode(" ",$timestamp);?>
                                      {{isset($splitTimeStamp)?$splitTimeStamp[0]:''}}
                                    </td>
                                  </tr>
                                  @endforeach 
                                @endif
                                @if(isset($PatientDentistProvider) && !empty($PatientDentistProvider))  
                                  <tr>
                                      <td>{{isset($PatientDentistProvider[0]->provider_name)?$PatientDentistProvider[0]->provider_name:''}}</td>
                                      <td>{{isset($PatientDentistProvider[0]->practice_name)?$PatientDentistProvider[0]->practice_name:'Other'}}</td>
                                      <td>{{isset($PatientDentistProvider[0]->address)?$PatientDentistProvider[0]->address:''}}</td>
                                      <td>{{isset($PatientDentistProvider[0]->phone_no)?$PatientDentistProvider[0]->phone_no:''}}</td>
                                      <td><?php $timestamp= $PatientDentistProvider[0]->last_visit_date;
                                      $splitTimeStamp = explode(" ",$timestamp);?>
                                      {{isset($splitTimeStamp)?$splitTimeStamp[0]:''}}
                                    </td>
                                  </tr>                               
                                @endif
                                @if(isset($PatientVisionProvider) && !empty($PatientVisionProvider))   
                                  <tr>
                                    <td>{{isset($PatientVisionProvider[0]->provider_name)?$PatientVisionProvider[0]->provider_name:''}}</td>
                                    <td>{{isset($PatientVisionProvider[0]->practice_name)?$PatientVisionProvider[0]->practice_name:'Other'}}</td>
                                    <td>{{isset($PatientVisionProvider[0]->address)?$PatientVisionProvider[0]->address:''}}</td>
                                    <td>{{isset($PatientVisionProvider[0]->phone_no)?$PatientVisionProvider[0]->phone_no:''}}</td>
                                    <td><?php $timestamp= $PatientVisionProvider[0]->last_visit_date;
                                    $splitTimeStamp = explode(" ",$timestamp);?>
                                    {{isset($splitTimeStamp)?$splitTimeStamp[0]:''}}
                                  </td>
                                  </tr>                               
                                @endif 
                                </tbody> 
                            </table>
                            
                        </div>
                    </div>
                    
                     
                    
                    <div class="row">
                        <label class="col-md-2" id="ID11"><h4>Details of Care Giver</h4></label>
                        
                    </div>
                     <div class="row">
                     <table class="table-responsive" width = 100%>                          
                     <tr> 
                     <td><b> Name : </b> {{empty($PatientCareGiver->fname) ? '' : $PatientCareGiver->fname}} {{empty($PatientCareGiver->lname) ? '' : $PatientCareGiver->lname}}
                      </td>
                      <td colspan=2> <b> Phone No. : </b> {{empty($PatientCareGiver->mobile) ? '' : $PatientCareGiver->mobile}} </td>
                      </tr>
                    
                    <tr> 
                     <td> <b> Relationship to Patient :</b> {{empty($PatientCareGiver->relationship) ? '' : $PatientCareGiver->relationship}}  </td>
                     <td> <b> Email Id : </b> {{empty($PatientCareGiver->email) ? '' : $PatientCareGiver->email}} </td></tr>
                     <tr><td><b> Address : </b> {{empty($PatientCareGiver->address) ? '' : $PatientCareGiver->address}} </td> </tr>

                
                        </table>
                    </div>
                   
                   
                   <div class="row">
                        <label class="col-md-2" id="ID11"><h4>Details of Emergency Contact</h4></label>
                        
                    </div>
                     <div class="row">
                     <table class="table-responsive" width = 100%>                          
                     <tr> 
                     <td><b> Name : </b> {{empty($PatientEmergencyContact->fname) ? '' : $PatientEmergencyContact->fname}} 
                     {{empty($PatientEmergencyContact->lname) ? '' : $PatientEmergencyContact->lname}}
                      </td>
                      </tr>  
                       <tr><td><b> Phone No. : </b> {{empty($PatientEmergencyContact->mobile) ? '' : $PatientEmergencyContact->mobile}}</td></tr>
                      <tr><td> <b> Email Id : </b>  {{empty($PatientEmergencyContact->email) ? '' : $PatientEmergencyContact->email}} </td></tr>
                      <tr><td><b> Address : </b>  {{empty($PatientEmergencyContact->address) ? '' : $PatientEmergencyContact->address}} </td> </tr>
                        </table>
                    </div>
                   
                </div>
           <!--  </div> -->
        </div>
    </div>
    <br>
<hr>
<br>

<div style="float: right;">

  Signature - 
  <b>{{ isset($electronic_sign[0]->f_name)? $electronic_sign[0]->f_name.' '.$electronic_sign[0]->l_name:' ' }} <br></b>
  
    <?php  echo "Date :".date('m-d-Y');?>
    </div>  
    <div style="float: right;">
       
    </div>  
    <br>  
    <br>
    <br>
    <br><br>
       <br>
    <br><br>
   
      <div style="margin: -45px -45px -0px -45px">
 <div class="form-row" style="background-color:#1474a0;height: 10px;">
              </div>
     <div class="form-row" style="background-color:#27a8de;height: 80px;">                
             </div>
           </div>
</body>
</html>
