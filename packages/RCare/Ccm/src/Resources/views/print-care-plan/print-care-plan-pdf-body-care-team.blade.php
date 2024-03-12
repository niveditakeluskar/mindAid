<div style="background-color: #1474a0;color: white;margin-top:10px;">
<h3>CARE TEAM :</h3>                    
</div>
<div class="row">
	<label class="col-md-2" id="ID11"><h4>Specialist Details</h4></label>
	<div class="col-md-12" id="ID12"> 
	<?php //echo "<pre>"; dd($patientProviders);?>                          
		<table class="tbl">
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
				@if(isset($patientProviders))
					@foreach($patientProviders as $key => $value) 
						<tr>
							<td>{{$value->provider_name}}</td>
							<td>{{isset($value->practice_name)?$value->practice_name:'Other'}}</td>
							<td>{{$value->address}}</td>  
							<td>{{$value->phone_no}}</td>
							<td>
								<?php 
									$timestamp= $value->last_visit_date;
									$splitTimeStamp = explode(" ",$timestamp);
								?>
								{{isset($splitTimeStamp)?$splitTimeStamp[0]:''}}
							</td>
						</tr>
					@endforeach 
				@endif
				@if(isset($PatientSpecialistProvider))
					@foreach ($PatientSpecialistProvider as $key => $value)
						<tr>
							<td>{{$value->provider_name}}</td>
							<td>{{isset($value->practice_name)?$value->practice_name:'Other'}}</td>
							<td>{{$value->address}}</td>  
							<td>{{$value->phone_no}}</td>
							<td>
								<?php 
									$timestamp= $value->last_visit_date;
									$splitTimeStamp = explode(" ",$timestamp);
								?>
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
						<td>
							<?php 
								$timestamp= $PatientDentistProvider[0]->last_visit_date;
								$splitTimeStamp = explode(" ",$timestamp);
							?>
							{{isset($splitTimeStamp)?$splitTimeStamp[0]:''}}
						</td>
					</tr>                               
				@endif
				@if(isset($PatientVisionProvider) && !empty($PatientVisionProvider))   
				<?php echo "<pre/>"; print_r($PatientVisionProvider);  ?>
					<tr>
						<td>{{isset($PatientVisionProvider[0]->provider_name)?$PatientVisionProvider[0]->provider_name:''}}</td>
						<td>{{isset($PatientVisionProvider[0]->practice_name)?$PatientVisionProvider[0]->practice_name:'Other'}}</td>
						<td>{{isset($PatientVisionProvider[0]->address)?$PatientVisionProvider[0]->address:''}}</td>
						<td>{{isset($PatientVisionProvider[0]->phone_no)?$PatientVisionProvider[0]->phone_no:''}}</td>
						<td>
							<?php 
								$timestamp= $PatientVisionProvider[0]->last_visit_date;
								$splitTimeStamp = explode(" ",$timestamp);
							?>
							{{isset($splitTimeStamp)?$splitTimeStamp[0]:''}}
						</td>
					</tr>                               
				@endif 
			</tbody> 
		</table>
	</div>
</div>
<div class="custom-separator"></div>
<div class="row">
	<label class="col-md-2" id="ID11"><h4>Details of Care Giver</h4></label>
	<table class="table-responsive" width = 100%>                          
		<tr> 
			<td>
				<b> Name : </b> {{empty($PatientCareGiver->fname) ? '' : $PatientCareGiver->fname}} {{empty($PatientCareGiver->lname) ? '' : $PatientCareGiver->lname}}
			</td>
			<td colspan=2> 
				<b> Phone No. : </b> {{empty($PatientCareGiver->mobile) ? '' : $PatientCareGiver->mobile}} 
			</td>
		</tr>
		<tr> 
			<td> 
				<b> Relationship to Patient :</b> {{empty($PatientCareGiver->relationship) ? '' : $PatientCareGiver->relationship}}  
			</td>
			<td> 
				<b> Email Id : </b> {{empty($PatientCareGiver->email) ? '' : $PatientCareGiver->email}} 
			</td>
		</tr>
		<tr>
			<td>
				<b> Address : </b> {{empty($PatientCareGiver->address) ? '' : $PatientCareGiver->address}} 
			</td> 
		</tr>
	</table>
</div>
<div class="custom-separator"></div>
<div class="row">
	<label class="col-md-2" id="ID11"><h4>Details of Emergency Contact</h4></label>
	<table class="table-responsive" width = 100%>                          
		<tr> 
			<td>
				<b> Name : </b> {{empty($PatientEmergencyContact->fname) ? '' : $PatientEmergencyContact->fname}} 
				{{empty($PatientEmergencyContact->lname) ? '' : $PatientEmergencyContact->lname}}
			</td>
		</tr>  
		<tr>
			<td>
				<b> Phone No. : </b> {{empty($PatientEmergencyContact->mobile) ? '' : $PatientEmergencyContact->mobile}}
			</td>
		</tr>
		<tr>
			<td>
				<b> Email Id : </b>  {{empty($PatientEmergencyContact->email) ? '' : $PatientEmergencyContact->email}} 
			</td>
		</tr>
		<tr>
			<td>
				<b> Address : </b>  {{empty($PatientEmergencyContact->address) ? '' : $PatientEmergencyContact->address}} 
			</td>
		</tr>
	</table>
	<!-- </div>
	</div>
	</div> -->
</div>
<!-- <br> -->
<!-- <div class="custom-separator"></div> -->
<hr />
<!-- <br> -->
<div style="float: right; margin-top:2em;">
	<div>
		Signature:
		<b>
			{{ isset( $electronic_sign[0]->users_created_by->f_name) ? $electronic_sign[0]->users_created_by->f_name . ' ' . $electronic_sign[0]->users_created_by->l_name : '' }} 
			{{-- isset($electronic_sign[0]->f_name)? $electronic_sign[0]->f_name.' '.$electronic_sign[0]->l_name:' ' --}} 
			<!-- <br> -->
		<br/>
	</div>
	<div>
		<?php  
			echo "Date: ".date('m-d-Y');
		?>
	</div>
</div>  
<div style="float: none; clear:both;"> </div>
<div id="footer">
	<div class="form-row" style="background-color:#1474a0;height: 10px;">&nbsp;</div>
	 <div class="form-row" style="background-color:#27a8de;height: 20px;">&nbsp;</div> 
</div>
