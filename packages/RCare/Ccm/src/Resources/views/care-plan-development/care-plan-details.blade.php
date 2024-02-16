<style>
	.stopwatch{
	display:none;
}
</style>
<div class="row mb-4" id="select-activity-5">
    <div class="col-md-12">
        <div class="card">    
            <div class="card-body">
            <h4 class="card-title mb-3" id="ID">Care Plan</h4>
            <div class="row">
				<label class="col-md-2" id="ID1">Diagnosis Code:</label>
				<div class="col-md-10" id="ID2">	
					@foreach ($PatientDiagnosis as $rec)
							{{ $rec->condition }} ({{ $rec->code }} ),
					@endforeach
				</div>
			</div>
			<hr>
			<div class="row">
				<label class="col-md-2" id="ID3">Symptoms:</label>
				<div class="col-md-10" id="ID4">
               <?php $i=1;?>
                   
					@foreach ($PatientDiag as $key => $value)		
                        <?php  $data = json_decode($value->symptoms, true);  
                       
                         if(!empty($data))
                        {  
                      echo  $i++;  

                        ?> )                       
                 @foreach ($data as $sym)
                  <?php print_r($sym)?>,

					 @endforeach		
					   
					     <br/>
					 <?php }?>
					      @endforeach					
				</div>
			</div>
			<hr>
			<div class="row">
				<label class="col-md-2" id="ID5">Goals:</label>
				<div class="col-md-10" id="ID6">
					   <?php $i=1;?>
                   
					@foreach ($PatientDiag as $key => $value)		
                        <?php  $data = json_decode($value->goals, true); 
                         if(!empty($data))
                        {   
                      echo  $i++;                     
                        ?> )                       
                    @foreach ($data as $sym)
					 <?php print_r($sym)?>,
					     @endforeach
					     <br/>
					 <?php } ?>
					      @endforeach		
					
				</div>
			</div>
			<hr>
			<div class="row">
				<label class="col-md-2" id="ID7">Tasks:</label>
				<div class="col-md-10" id="ID8">
					<?php $i=1;?>
                   
					@foreach ($PatientDiag as $key => $value)		
                        <?php  $data = json_decode($value->tasks, true);   
                         if(!empty($data))
                        { 
                      echo  $i++;                     
                        ?> )                       
                    @foreach ($data as $sym)
					 <?php print_r($sym)?>,
					     @endforeach
					     <br/>
					 <?php } ?>
					      @endforeach		
				</div>
			</div>
			<hr>
			<div class="row">
				<label class="col-md-2" id="ID9">Medications:</label>
				<div class="col-md-10" id="ID10">
				<?php //print_r($PatientMedication[0]->medication['description']);?>
				<?php $i=1;?>
					@foreach ($PatientMedication as $key => $value)	
					 {{$i++}})
					 <?php if($value->medication['description']!='' || $value->medication['description']!=null){?>  
					Name:{{$value->medication['description']}},
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
					<br/>
					 @endforeach		
					
					
				</div>
			</div>
			<hr>
			<div class="row">
				<label class="col-md-2" id="ID11">Allergies:</label>
				<div class="col-md-10"id="ID12">
					<?php $i=1;?>
					@foreach ($PatientAllergy as $Aller_rec)
					<?php if (!empty($Aller_rec)) { ?>
					{{$i++}})
					<?php } if($Aller_rec->allergy_type!='' || $Aller_rec->allergy_type!=null){?>
						Allergy Type:{{ $Aller_rec->allergy_type }},
					<?php } if($Aller_rec->type_of_reactions!='' || $Aller_rec->type_of_reactions!=null){?>
						Type of Reaction:{{ $Aller_rec->type_of_reactions }},
                  <?php } if($Aller_rec->specify!='' || $Aller_rec->specify!=null){?>
						specify:{{ $Aller_rec->specify }}.
                    <?php } ?>
                    <br/>
					@endforeach
					<label id="Allergies"></label>
				</div>
			</div>
			<hr>
			<div class="row">
				<label class="col-md-2" id="ID13">Support:</label>
				<div class="col-md-10" id="ID14"> 
					   <?php $i=1;?>
                   
					@foreach ($PatientDiag as $key => $value)		
                        <?php  
                        if($value->support!='' || $value->support!=null)
                        { 
                      echo  $i++;                     
                        ?> )                       
                   {{$value->support}}
					     <br/>
					       <?php } ?>
					      @endforeach	
				</div>
			</div>
			<hr>
			
		</div>
    </div>
</div>
</div>
           <table style="width:100%">
           	<tr>
           		<th style="width:15%">Profile</th>
           		<th style="width:15%">Personal Info</th>
           		<th style="width:15%">Contact</th>
           		<th style="width:15%">EMR & Enroll Date</th>
           		<th style="width:15%">Enroll In</th>
           		<th style="width:15%">Total Time</th>
           	</tr>
           	<tr>
           		<td>
					<?php
                    $m_id = getPageModuleName();
                    $c_id = getPageSubModuleName();
                        $default_img_path = asset('assets/images/faces/avatar.png');
                        $path             = $patient[0]->profile_img;
                    ?>
			    	@if(isset($patient[0]->profile_img) && ($patient[0]->profile_img != ""))
                       	{{-- @if(file_exists($path))) --}}
                            <img src="{{ $path }}" class='user-image' style="width: 60px;" />
                            {{-- @else --}}
                            <!-- <img src="{{-- $default_img_path --}}" class='user-image' style="width: 60px;" /> -->
                            {{-- @endif --}}
                    	@else
                            <img src="{{ $default_img_path }}" class='user-image' style="width: 60px;" />
                    @endif
				</td>
           		<td>{{$patient[0]->fname}} {{$patient[0]->lname}}</td>
           		<td>
					{{$patient[0]->mob}}<?php if(isset($patient[0]->home_number) && $patient[0]->home_number!=''){echo ' | '.$patient[0]->home_number; }?>
				</td>
           		<td>
           			{{$patient[0]->emr}} 
                    <?php
                        if(isset($patient_providers[0])){
                            echo $patient_providers[0]->practice_emr;      
                        }
                    ?>
                </td> 
           		<td>
           			<?php if(isset($patient[0]->PatientServices)){
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
           		<td><?php echo (isset($last_time_spend) && ($last_time_spend!='0')) ? $last_time_spend : '00:00:00'; ?></td>
           	</tr>
           	<tr>
           		<td>
           			<?php
                        if(isset($patient_demographics[0])){
                            if($patient_demographics[0]->gender == '1') {
                                echo 'Female';
                            } else if($patient_demographics[0]->gender == '0') {
                                echo 'Male';
                            }else {
                                echo '';
                            }
                        }
                    	?><br>
                   	({{ empty($patient[0]->dob) ? '' : age($patient[0]->dob)}})
                </td>
           		<td>{{$patient[0]->dob}}</td>
           		<td>{{$patient[0]->email}}</td>
           		<td>
           			{{ empty($patient[0]->created_date) ? '' : $patient[0]->created_date }}
			    	<?php
                    $enroll_date="";
                        $module_id = getPageModuleName();                                    
                        if(isset($patient_enroll_date[0]->date_enrolled)){
                           $str = $patient_enroll_date[0]->date_enrolled; 
                            $enroll_date = date("m-d-Y", strtotime($str)); 
                            echo $enroll_date;   
                        }?>
				</td>
           		<td><?php 
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
           		<td></td>
           	</tr>
           </table>

<!-- <script src="http://www.openjs.com/scripts/events/keyboard_shortcuts/shortcut.js"></script> -->
<script src="{{asset(mix('assets/js/external-js/keyboard_shortcuts-shortcut.js'))}}"></script>
 	<script  type="text/javascript">
        shortcut.add("ctrl+p", function() {
       	// alert('Printing Screen');
        var myDiv = document.getElementById("ID").innerHTML;
        var myDiv1 = document.getElementById("ID1").innerHTML;
        var myDiv2 = document.getElementById("ID2").innerHTML;
        var myDiv3 = document.getElementById("ID3").innerHTML;
        var myDiv4 = document.getElementById("ID4").innerHTML;
        var myDiv5 = document.getElementById("ID5").innerHTML;
        var myDiv6 = document.getElementById("ID6").innerHTML;
        var myDiv7 = document.getElementById("ID7").innerHTML;
        var myDiv8 = document.getElementById("ID8").innerHTML;
        var myDiv9 = document.getElementById("ID9").innerHTML;
        var myDiv10 = document.getElementById("ID10").innerHTML;
        var myDiv11 = document.getElementById("ID11").innerHTML;
        var myDiv12 = document.getElementById("ID12").innerHTML;
        var myDiv13 = document.getElementById("ID13").innerHTML;
        var myDiv14 = document.getElementById("ID14").innerHTML;
        var myDiv15 = document.getElementById("ID15").innerHTML;
        var myDiv16 = document.getElementById("ID16").innerHTML;
        
            var oldPage = document.body.innerHTML;
            document.body.innerHTML = 
              "<html><head><title>Care Plan</title></head><body>" + 
              myDiv + myDiv1 + myDiv2 + myDiv3 + myDiv4 + myDiv5 + myDiv6 + myDiv7 + myDiv8 + myDiv9 + myDiv10 + myDiv11 + myDiv12 + myDiv13 + myDiv14 + myDiv15 + myDiv16 +"</body>";
            window.print();
            document.body.innerHTML = oldPage;
            });

    </script>
