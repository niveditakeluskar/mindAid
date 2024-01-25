<?php 
	$taskArray = []; $goalArray = []; $symptomsArray = []; $allComments="";
?>
<style>
	.scrolldiv {  
		height:200px;  
		overflow:scroll;
		overflow-x:hidden;
		padding-top: 1%;  
	}  
</style>

<div class="scrolldiv" style="margin: 0px -30px 0px -23px">
	<div>
		<h4>Diagnosis Codes:</h4>    
		@if(count($patientDiagnosisDetails)>0)  
			<table class="tbl">
				<thead>
					<tr>
						<th width="30%">Date</th>
						<th width="20%">Condition</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($patientDiagnosisDetails as $record)
						<?php                                               
							$tasks = json_decode($record->tasks, true); 
							if(!empty($tasks) && $tasks[0]!='blank') { 
								$taskArray = array_merge($taskArray, $tasks);
							}
							$goals = json_decode($record->goals, true); 
							if(!empty($goals) && $goals[0]!='blank') { 
								$goalArray = array_merge($goalArray, $goals);
							}
							$symptoms = json_decode($record->symptoms, true); 
							if(!empty($symptoms) && $symptoms[0]!='blank') { 
								$symptomsArray = array_merge($symptomsArray, $symptoms);
							}
							$comments = $record->comments; 
							if(!empty($comments) && $comments[0] != null && $comments != '') { 
								$allComments   = $allComments.$comments.'<br/>';
							}
						?>
						<tr>
							<td> 
								<?php
									$date = strtotime($record->date);
									echo date("m-d-Y", $date);
								?>
							</td>  
							<td> 
								{{ $record->condition }} ({{ $record->code }}) 
							</td>
						</tr>
					@endforeach 
				</tbody>
			</table> 
		@endif
		{{--
		@if(count($PatientDiagnosis)>0)
			<table class="tbl">
				<thead>
					<tr>
						<th width="30%">Date</th>
						<th width="20%">Condition</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($PatientDiagnosis as $rec)
						<tr>
							<td> 
								<?php 
									$str1 = $rec->date;
									$conditiondate = explode(" ",$str1);
									echo  $str1;
								?>
							</td>  
							<td> 
								{{ $rec->condition }} ({{ $rec->code }}) 
							</td>
						</tr>
					@endforeach       
				</tbody>
			</table>
		@endif
		--}}
	</div>
	<div class="custom-separator"></div>
	<!--  -->
	@if($subModule == "care-plan-development")
		<div class="row">
			<label class="col-md-2" id="ID1"><h4>Prognosis and Expected Outcome:</h4></label>
			<div class="col-md-10" id="ID2"> </div>
		</div>
		<div class="custom-separator"></div>
	@endif
	<div>
		<h4>Treatment Goals:</h4>
		<div>
			<?php $i=1;?>
			<ol>
				@if(count($goalArray)>0)
					@foreach($goalArray as $key => $goal)
						<li style="font-size: 13px;text-align: left;">    
							<?php 
								print_r(htmlentities($goal))
							?>
						</li>
					@endforeach   
				@endif
			</ol>
		</div>
	</div>
	<div class="custom-separator"></div>
	<!--  -->
	<div>
		<h4>Treatment Goals Status:</h4>
		<div> </div>
	</div>
	<div class="custom-separator"></div>
	<!--  -->
	<div>
		<h4>Symptoms:</h4>
		<div>
			<?php $i=1;?> 
			<ol>
				@if(count($symptomsArray)>0)
					@foreach($symptomsArray as $key => $symptom)
						<li style="font-size: 13px;text-align: left;">    
							<?php 
								print_r(htmlentities($symptom))
							?>
						</li>
					@endforeach   
				@endif
			</ol>                
		</div>
	</div>
	<div class="custom-separator"></div>
	<!--  -->
<!-- <div> -->
	<div >
		<h4>Medications:</h4>
		<!-- if(count($PatientMedication1)>0) -->
		@if(is_array($PatientMedication1) && count($PatientMedication1) > 0)
			<div>
				<table class="tbl">
						<thead> 
							<tr> 
								<th width="22%">Date</th>
								<th width="22%">Name</th>
								<th width="22%">Description</th>
								<th width="15%">Purpose</th>
								<th width="15%">Dosage</th>
								<th width="15%">Route</th>
								<th width="15%">Frequency</th>
								<th width="15%">Pharmacy Name</th>
								<th width="15%">Pharmacy Phone No</th>
							</tr>
						</thead>
						<tbody> 
							@foreach ($PatientMedication1 as $key => $value)  
							<tr>
							<td> 
							<?php
							$med_date="";   
							if(($value->date!= 'null')){
							$str = $value->date;
							$med_date = explode(" ",$str);
							echo date('m-d-Y',strtotime($med_date[0]));   
							} else{ 
							echo $med_date;       
							}
							?>
							</td>
							<td>
							<?php $med_description = "";
							if($value->name=='null' &&$value->name == null){
								echo "";
							}else{
								echo $value->name;
							} ?>
							</td> 
							<td>{{ ($value->description =='null') ? '' : $value->description  }}</td> 
							<td>{{ ($value->purpose =='null') ? '' : $value->purpose  }}</td>
							<td>{{ ($value->dosage =='null') ? '' : $value->dosage  }}</td>
							<td>{{ ($value->route =='null') ? '' : $value->route  }}</td>
							<td>{{ ($value->frequency =='null') ? '' : $value->frequency  }}</td> 
							<td>{{ ($value->pharmacy_name =='null') ? '' : $value->pharmacy_name  }}</td>
							<td>{{ ($value->pharmacy_phone_no =='null') ? '' : $value->pharmacy_phone_no  }}</td> 
							</tr>
							@endforeach	
						</tbody>
				</table> 
			</div>
		@endif
	</div>
	<div class="custom-separator"></div>
	<!--  -->
	<div>
		<h4>Tasks:</h4>
		<ol>
			@if(count($taskArray)>0)
				@foreach($taskArray as $key => $task)
					<li style="font-size: 13px;text-align: left;">    
						<?php 
							print_r(htmlentities($task))
						?>
					</li>
				@endforeach   
			@endif
		</ol>
	</div> 
	<div class="custom-separator"></div>
	<!--  -->
	<div>
		<h4>Allergies:</h4>
		@if(count($PatientAllergy1)>0)
			<div>
				@foreach ($PatientAllergy1 as $key => $value)
					<b>Date</b> :
					{{empty($value['displaydate'])?'':date("m-d-Y", strtotime($value['displaydate']))}}
						<table class="tbl" >
							<thead>
								<tr> 
									<th width="25%">Name</th>
									<th width="25%">Specify</th>
									<th width="25%">Reaction</th>
									<th width="25%">Severity</th>
									<th width="25%">Treatment</th>
									<th width="25%">Allergy Status</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($value['date'] as $key => $val) 
									<tr>
										<td>{{ucfirst($val['allergy_type'])}}</td> 
										<td>{{$val['specify']}}</td>
										<td>{{$val['type_of_reactions']}}</td>
										<td>{{$val['severity']}}</td>
										<td>{{$val['course_of_treatment']}}</td> 
										<td>{{$val['allergy_status']}}</td> 
									</tr>
								@endforeach
							</tbody>
						</table>
					<!-- <br>  -->
				@endforeach 
			</div>
		@endif
	</div> 
	<div class="custom-separator"></div>
	<!--  
	<div>
		<h4>Comments:</h4>
		@if($allComments != "" && $allComments != null && !empty($allComments)) 
			<?php // print_r($allComments);?>  
		@endif
	</div>
	<div class="custom-separator"></div> -->
	<!--  -->
	<div>
	<h4>Services:</h4>  
		@if(count($patient_services1)>0)
			@foreach ($patient_services1 as $key => $value)
				<b>Date</b> : {{empty($value->displaydate)?'':date("m-d-Y", strtotime($value->displaydate))}}
				<table class="tbl" >
					<thead>
						<tr> 
							<th width="10%">Name</th>
							<th width="10%">Purpose</th>
							<th width="8%">Specify</th>
							<th width="8%">Brand</th>
							<th width="10%">Freq.</th>                                      
							<th width="25%">Start Date</th>
							<th width="25%">End Date</th>
							<th width="10%">Notes</th>
						</tr>
					</thead>
					<tbody>
						<?php //print_r($value->dateval); ?>
						@foreach($value->dateval as $key => $val) 							
							<tr>
								<td>
								{{$val->type}}</td> 
								<td>{{$val->purpose}}</td>
								<td>{{$val->specify}}</td>
								<td>{{$val->brand}}</td> 
								<td>{{$val->frequency}}</td>    
								<td>
								{{(isset($val->service_start_dt) && $val->service_start_dt!='01-01-1970'? $val->service_start_dt:'')}}
									
								</td>
								<td>
								{{(isset($val->service_end_dt) && $val->service_end_dt!='01-01-1970'? $val->service_end_dt:'')}}
									
								</td>
								<td>{{$val->notes}}</td>  
							</tr>
						@endforeach
						
					</tbody>
				</table>
			<!-- <br>  -->
			@endforeach
		@endif
	</div>
	<div class="custom-separator"></div> 
	<div>
		<h4>Labs:</h4>
		@if(count($patientLabDetails)>0)
			@foreach($patientLabDetails as $labInfoKey => $labInfo)
			<?php $j=0; ?> 
				@foreach($labInfo as $key => $lab)
					<?php 
						$lab_name = $lab['lab_name'];
						if(!empty($lab_name)){
						echo "Lab: <b>".$lab_name."</b> ";
						if($lab['lab_date_exist']=='1') { 
							echo "Lab Date";
						} else {
							echo "CPD Date";
						}
						$lab_date  = strtotime($lab['lab_date']);
						echo ": <b>".date("m-d-Y", $lab_date)."</b>";
						$j++;
						( sizeof($lab) == $j) ? $lab_style_border="" : $lab_style_border="border-bottom:1px dashed #dee2e6; margin-bottom:20px;";
					}else if($lab_name=='null' && $lab_name==null){
						echo "Lab: <b>Other</b> ";
						if($lab['lab_date_exist']=='1') { 
							echo "Lab Date";
						} else {
							echo "CPD Date";
						}
						$lab_date  = strtotime($lab['lab_date']);
						echo ": <b>".date("m-d-Y", $lab_date)."</b>";
						$j++;
						( sizeof($lab) == $j) ? $lab_style_border="" : $lab_style_border="border-bottom:1px dashed #dee2e6; margin-bottom:20px;";
					}else{
						$lab_style_border="";
					}?>
					<div class="" style="{{ $lab_style_border }}">
						<table class="tbl" class="table table-responsive" style="margin-bottom:15px;">
							<thead>
								<tr>
									<th width="20%">Parameter</th>
									<th width="20%">Reading</th>
									<th width="20%">Value</th>
								</tr>
							</thead>
							<tbody>

								@foreach ($lab['lab_details'] as $key => $val) 
									<?php //if($key!='0'){?>
									<tr>
										<td>{{empty($key)?'':$key}}</td> 
										<td>{{empty($val['reading'])?'':$val['reading']}}</td>
										<td>{{empty($val['high_val'])?'':$val['high_val']}}</td> 
									</tr>
									<?php //}?>
								@endforeach
								@if(!empty($lab['notes']))
								<tr>
									<td colspan="3"><b> Notes : </b> {{ empty($lab['notes'])?'':$lab['notes']}} </td>
								</tr>
								@endif
							</tbody>
						</table>
					</div>
				@endforeach 
			@endforeach
		@endif
<div class="custom-separator"></div>
	<!--  -->
	<div>
		<h4>Vitals Data:</h4>
		@if(count($patient_vitals)>0)
			<?php $i=0;?>
			@foreach ($patient_vitals as $key => $rec)
			<?php $i++;?>
				@if(!empty($rec->weight && $rec->bmi && $rec->bp && $rec->o2 && $rec->height && $rec->pulse_rate))
					<?php 
					( sizeof($patient_vitals) == $i) ? $style_border="" : $style_border="border-bottom:1px dashed #dee2e6; margin-bottom:20px;";
					?>
					<div class="" style="{{ $style_border }}">
						<table class="table-borderless table" style="margin-bottom:10px; width:100%;">
							<tr class="vital_data">
								<td colspan="6">
									<b>Date: </b>
									<?php 
										$date = explode(" ", $rec->date); 
										$vital_date  = strtotime($date[0]);
										echo date("m-d-Y", $vital_date);
									?>
								</td>
							</tr>
							<tr class="vital_data">
								<td colspan="2"><b>Height:</b> {{ $rec->height }}</td>
								<td colspan="2"><b>Weight:</b> {{ $rec->weight }}</td>
								<td colspan="2"><b>BMI:</b> {{$rec->bmi}}</td>
							</tr>
							<tr class="vital_data">
								<td colspan="2"><b>BP:</b> {{$rec->bp}} / {{$rec->diastolic}}</td>
								<td colspan="2"><b>O2:</b> {{$rec->o2}}</td>
								<td colspan="2"><b>Pulse Rate:</b> {{$rec->pulse_rate}}</td>
							</tr>
							<tr>
								<td colspan="3">
									<?php 
										$oxygen = $rec->oxygen;
										if($oxygen=='1') {
									?>
											<b><?php echo "Room Air";}else{?></b>
											<b><?php echo "Supplemental Oxygen";?></b>
									<?php 
										}
									?>
									<?php 
										$notes = $rec->notes; 
										if($notes!='null' && $notes!=null && $notes!='') {
									?>
											<b>Notes : </b>
									<?php 
											echo $notes; 
										}
									?>
								</td>
								<td colspan="3"><b>Pain Level:</b> {{$rec->pain_level}}</td>
							</tr>
						</table>
					</div>
				@endif
			@endforeach 
		@endif
	</div>
	<div class="custom-separator"></div>  
	<!--  -->
	<div class="custom-separator"></div>
	<!--  -->
	<div>
		<h4>Imaging Data </h4>
		@if(count($patient_imaging)>0)
			<table class="tbl">
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
								<?php 
									$imagdate=explode(" ", $health->updated_at);
									if($health->imaging_date != '') {
										$healthimgdate = $health->imaging_date;
										$d = explode(" ",$healthimgdate);  
										echo $d[0]; 
									} else {
								?> 
										{{empty($health->updated_at)?'':$imagdate[0]}}
								<?php  
										echo '(CPD Date)'; 
									} 
								?>
							</td>  
							<td> {{$health->imaging_details}} </td>  
						</tr>
					@endforeach       
				</tbody>
			</table>
		@endif
	</div>
	<div class="custom-separator"></div>
	<!--  -->
	<div>
		<h4>Health Data</h4>
		@if(count($patient_healthdata)>0)
			<table class="tbl">
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
								<?php 
									$healthdate=explode(" ", $health_data->updated_at);
									if($health_data->health_date != ''){
										// echo date("m-d-Y", strtotime($health_data->health_date));
										$h1 = explode(" ",$health_data->health_date);
										$h2= $h1[0];
										$h3 = explode("-",$h2);
										$myhealthdate= $h3[1]."-".$h3[2]."-".$h3[0] ; 
										echo $myhealthdate;  
									} else {
								?> 
										{{empty($health_data->updated_at)?'':$healthdate[0]}}
								<?php  
										echo '(CPD Date)'; 
									} 
								?>
							</td>  
							<td> {{$health_data->health_data}} </td>
						</tr>
					@endforeach       
				</tbody>
			</table>
		@endif
	</div>
	<div class="custom-separator"></div>
	<!-- <div class="custom-separator"></div> -->
	<hr />
	<!--  -->
</div>