<style>
.scrolldiv {   

    height:200px;
    overflow:scroll;
    overflow-x:hidden;
    margin-left: 5%;
    padding: 1%; 
     }  
</style>

<div class="scrolldiv">
<div class="row mt-2">

  <div class="col-lg-12 mb-4">
    <label class=" mb-1"><strong>Diagnosis Codes123:</strong></label>
    <!-- <span>Hypertension (I10), COPD (J44.9), Diabetes (E11.9), Depression (F32.9)</span> -->
    <p><?php if(isset($PatientDiagnosis) && $PatientDiagnosis != "") { 
      $i=0;
      foreach ($PatientDiagnosis as $PatientDiagnosis) {
        if($i!=0){echo ",";}
        echo $PatientDiagnosis->condition .' ('.$PatientDiagnosis->code .')';
        $i++;
      }
    }?></p>
  </div>            
  <hr>

  <div class="col-lg-12 mb-1">
    <label class=" mb-1"><strong>Symptoms:</strong></label>
    <?php $i=1;?> <ol>                  
    @foreach ($PatientDiag as $key => $value)
    <li>       
      <?php  $data = json_decode($value->symptoms, true);  
      if(!empty($data)){?>                   
      @foreach ($data as $sym)
      <?php print_r($sym)?>
      @endforeach        
      <br/>
      <?php }?>
    </li>
    @endforeach </ol>                   
  </div>
  <hr>

  <div class="col-lg-12 mb-1">
    <label class=" mb-1"><strong>Goals:</strong></label>
    <ol>
      @foreach ($PatientDiag as $key => $value) 
      <li>      
        <?php  $data = json_decode($value->goals, true); 
        if(!empty($data)){?>                   
        @foreach ($data as $sym)
        <?php print_r($sym)?>
        @endforeach
        <br/>
        <?php } ?>
      </li>
      @endforeach       
    </ol>      
  </div>
  <hr>

  <div class="col-lg-12 mb-1">
    <label class="mb-1"><strong>Tasks:</strong></label>
    <ol>
      @foreach ($PatientDiag as $key => $value)  
      <li>     
        <?php  
        $data = json_decode($value->tasks, true);   
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
    <hr>

    <div class="col-lg-12 mb-1">
      <label class=" mb-1"><strong>Medications:</strong></label> <br>
      <ol>
        @foreach ($PatientMedication as $key => $value)
        <li> 
          <span>  
            <?php if(isset($value->med_description) || $value->med_description != "" || $value->med_description==null || $value->med_description=='null'){?>
            <strong> {{$value->med_description }} </strong>
            <?php }?>
          </span><br>
          <span>
            <?php if(isset($value->description) || $value->description != "" || $value->description==null || $value->description=='null') {?>
            <strong>Description: </strong>{{$value->description}}
            <?php }?>

            <?php if(isset($value->purpose) || $value->purpose != "" || $value->purpose==null || $value->purpose=='null') {?>
            <strong>Purpose: </strong>{{$value->purpose}}
            <?php }?>

            <?php if(isset($value->dosage) || $value->dosage != "" || $value->dosage==null || $value->dosage=='null') {?>
            <strong>Dosage: </strong>{{$value->dosage}}
            <?php }?>

            <?php if(isset($value->strength) || $value->strength != "" || $value->strength==null || $value->strength=='null') {?>
            <strong>Strenght: </strong>{{$value->strength}}
            <?php }?>

            <?php if(isset($value->frequency) || $value->frequency != "" || $value->frequency==null || $value->frequency=='null') {?>
            <strong>Frequency: </strong>{{$value->frequency}}
            <?php }?>

            <?php if(isset($value->route) || $value->route != "" || $value->route==null || $value->route=='null') {?>
            <strong>Route: </strong>{{$value->route}}
            <?php }?>

            <?php if(isset($value->drug_reaction) || $value->drug_reaction != "" || $value->drug_reaction==null || $value->drug_reaction=='null') {?>
            <strong>Drug Reaction: </strong>{{$value->drug_reaction}}
            <?php }?>

            <?php if(isset($value->pharmacogenetic_test) || $value->pharmacogenetic_test != "" || $value->pharmacogenetic_test==null || $value->pharmacogenetic_test=='null') {?>
            <strong>Pharmacogenetic Test: </strong>{{$value->pharmacogenetic_test}}
            <?php }?>
          </span> 
        </li>
        @endforeach
      </ol>
    </div>
    <hr>
 
    <div class="col-lg-12 mb-1">
      <label class="mb-1"><strong>Allergies:</strong></label>
      <ol>
        @foreach ($PatientAllergy as $Aller_rec) 
        <li> 
          <?php if($Aller_rec->allergy_type!='' || $Aller_rec->allergy_type!=null){?>,
          <strong>Allergy Type:</strong>{{ $Aller_rec->allergy_type }},
          <?php } if($Aller_rec->allergy_status!='' || $Aller_rec->allergy_status!=null){?>
          <strong>Allergy Status:</strong>{{ $Aller_rec->allergy_status}},
          <?php } if($Aller_rec->type_of_reactions!='' || $Aller_rec->type_of_reactions!=null){?>
          <strong>Type of Reaction:</strong>{{ $Aller_rec->type_of_reactions }},
          <?php } if($Aller_rec->specify!='' || $Aller_rec->specify!=null){?>
          <strong> Specify: </strong>{{ $Aller_rec->specify }}
          <?php } ?>
        </li>
        @endforeach
      </ol>
    </div>
    <hr>

    <div class="col-lg-12 mb-1">
      <label class="mb-1"><strong>Vitals Data:</strong></label>
      @foreach ($patient_vitals as $rec)
      @if(!empty($rec->weight||$rec->bmi||$rec->bp||$rec->o2||$rec->height||$rec->pulse_rate))
      <table width = 100%>
        <tr class="vital_data"><td><b>Date: </b><?php $date = date("m-d-Y", strtotime($rec->date));?> {{$date}}</td></tr>
        <tr class="vital_data">
          <td><b>Weight:</b> {{ $rec->weight }}</td>
          <td><b>BMI:</b> {{$rec->bmi}}</td>
          <td><b>BP:</b> {{$rec->bp}} / {{$rec->diastolic}}</td>
          <td><b>O2:</b> {{$rec->o2}}</td>
          <td><b>Height:</b> {{ $rec->height }}</td>
          <td><b>Pulse Rate:</b> {{$rec->pulse_rate}}</td>
        </tr>
        <br>
      </table> 
      @endif
       <div class="col-lg-12 mb-1">
        <?php if(isset($rec->oxygen) && $rec->oxygen ==1) {?>
          <label class="mb-1"><strong><?php echo "Room Air"; ?></strong></label> 
        <?php }?>
        <?php if(isset($rec->oxygen) && $rec->oxygen ==0) {?>
          <label class="mb-1"><strong><?php echo "Supplemental Oxygen"; ?></strong></label> 
        <?php }?>
    </div> 
    <div class="col-lg-12 mb-1">
      <?php if(isset($rec->oxygen) && $rec->oxygen ==0) {?>
        <label class="mb-1"><strong>Notes: </strong></label>  
      <!-- <label class="mb-1"><strong>Notes: </strong></label>  -->
      <?php if(isset($rec->notes) && $rec->notes != "") { 
       echo $rec->notes;
     } }?>
    </div>
      @endforeach
    </div>
   <hr>

   <div class="col-lg-12 mb-1 mt-3">
    <label class="mb-1"><strong>Labs Data: </strong></label><br>
    @foreach ($patient_lab_test_name as $recs => $value)   
    <?php  
    if($value->description!='' || $value->description!=null){?>                  
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
      }?>        
      @endforeach
    </tr>
  </table>
  <?php $n = 1; ?>
  @foreach ($patient_lab as $notes)
  <?php if($value->description ==  $notes->description && $n == 1){  echo "<br>"; ?>
  <?php echo "<hr>"; $n++;}   ?>   
  @endforeach
  @endforeach 
  <!-- <hr>      -->
  </div>
  <div class="col-lg-12 mb-1">
    <label class="mb-1"><strong>Imaging :</strong></label>       
    <ol>
      @foreach ($patient_imaging as $key => $value)  
      <?php  
      if($value->imaging_details!='' || $value->imaging_details!=null){?> 
      <li>                      
        {{$value->imaging_details}}   
      </li>                              
      <?php } ?>
      @endforeach   
    </ol>
  </div>
  <hr>

<div class="col-lg-12 mb-1">
  <label class="mb-1"><strong>Comments:</strong></label>
  <ol>
    @foreach ($patient_cmnt as $key => $value)   
    <?php if($value->comments!=null || $value->comments!=''){?>   
    <li>                      
      {{$value->comments}}  
    </li>                                
    <?php }?>
    @endforeach
  </ol>
</div>
<hr>

</div> 

<div style="float: right;">
  <!--     Electronic Care Manager Signature -->
</div>
<br>
<br>
<br>
<br><br>


<div class="form-row" style= "height: 10px;">
</div>
<div class="form-row" style="height: 80px;">                
</div>
</div>
<br>

