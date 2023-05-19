<div class="card">       
	<div class ="card-body" style="margin: -45px -45px -0px -45px"> 
		<div class="form-row header" style="background-color:#27a8de;height: 100px;">
			 <!--<div class="form-row" margin-top="20px" >-->
				<!--<?php 
					//if(isset($patient_providers[0]->practice['logo'])){ 
					//	$logo = $patient_providers[0]->practice['logo'];?>-->
						<!-- <img src="" alt="" height="50px" width="150px" style="padding-top: 20px;padding-left: 17px;"> )-->
				<!--<?php 
					//}
				?>-->
			<!--</div> -->
			<!-- <label id="cpid">Care Plan</label> -->
			<div style="background-color:#1474a0;color:white;margin-top:20px;">
			  <h2 style="text-align:center;padding:15px;">Care Plan</h2>                    
		    </div>
		</div>
		<div class="form-row" style="background-color:#1474a0;height: 20px;">
		</div>
	</div>
	 <div class="form-row" style="margin: -30px -30px -0px -30px" > 
		<div style="background-color: #1474a0;color: white;margin-top:50px;">
			<h3>Patient Info :</h3>                    
		</div>
		<table width = 100% >
			<tr class="col1">
				<td colspan="2">
					<b>Name :  </b>
					{{empty($patient[0]->fname) ? '' : $patient[0]->fname}} {{empty($patient[0]->lname) ? '' : $patient[0]->lname}} 
					(<?php
						if(isset($patient_demographics[0])) {
							if($patient_demographics[0]->gender == '1') {
								echo 'Female';
							} else if($patient_demographics[0]->gender == '0') {
								echo 'Male';
							} else {
								echo '';
							}
						}
					?>)
				</td>
				<td>
					<b>DOB :</b> 
					{{date("m-d-Y", strtotime($patient[0]->dob))}} ({{ empty($patient[0]->dob) ? '' : age($patient[0]->dob)}})
				</td>
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
			<tr class="col2">
				<td colspan="2">
					<b>Mobile :</b> 
					{{$patient[0]->mob}}
				</td>
				<td>
					<b>Phone No. :  </b>
					<?php 
						if(isset($patient[0]->home_number) && $patient[0]->home_number!=''){
							echo $patient[0]->home_number; 
						}
					?>
				</td>
			</tr>
			<tr class="col3">
				<td colspan="2">
					<b>PCP :</b>
					<?php 
						if((isset($patient_providers[0]->provider))) {
							echo $patient_providers[0]->provider['name']; 
						} else if(isset($patient_providers[1]->provider)) {
							echo $patient_providersusers[1]->provider['name']; 
						} else {
							echo " "; 
						}
					?>
				</td>
				<td colspan="2">
					<b>Practice:</b>
					<?php 
						if((isset($patient_providers[0]->practice))) {
							echo $patient_providers[0]->practice['name']; 
						} else if(isset($patient_providers[1]->practice)) {
							echo $patient_providersusers[1]->practice['name']; 
						} else{
							echo " "; 
						}
					?>
				</td>
			</tr>
			<tr class="col4">
				<td colspan="2">
					<b>Practice Phone No. :</b>
					<?php 
						if((isset($patient_providers[0]->practice))) {
							echo $patient_providers[0]->practice['phone']; 
						} else if(isset($patient_providers[1]->practice)) {
							echo $patient_providersusers[1]->practice['phone']; 
						} else {
							echo " "; 
						}
					?>
				<td>
					<b>EMR No:</b> {{$patient[0]->emr}} 
					<?php
					if(isset($patient_providers[0])) {
						echo $patient_providers[0]->practice_emr;      
					}
					?>
				</td>
			</tr>
			<tr class="col5"> 
				<td colspan="2">
					<b>Care Manager :</b> 
					<?php //dd($caremanager); ?>
					{{empty($caremanager[0]->f_name) ? '' : $caremanager[0]->f_name." ".$caremanager[0]->l_name}}
				</td>
				<td>
					<b>Services : </b>
					<?php 
						if(isset($patient[0]->PatientServices)) {
							$enrollin = "";
							foreach($patient[0]->PatientServices as $patient_service) {
								if(isset($services)) { 
									foreach($services as $service) {
										if($service['id'] == $patient_service->module_id) {
											$enrollin = $enrollin . $service['module'].", ";                                                    
										}
									}
								} 
							} 
							$enrollin=rtrim($enrollin, ', ');
							echo $enrollin;       
						}
					?>
				</td>
			</tr>
			<tr class="col5"> 
				<td colspan="2"><b>Enrolled On : </b>
					<?php
						$enroll_date="";
						$module_id = getPageModuleName();                                    
						if(isset($patient_enroll_date[0]->date_enrolled)) {
							$str = $patient_enroll_date[0]->date_enrolled;
							$enroll_date = explode(" ",$str); 
							echo $enroll_date[0];   
						} else { 
							echo $enroll_date;   
						}
					?>
				</td> 
				<td>
					<b>Total Time Spent : </b>
					<?php echo (isset($last_time_spend) && ($last_time_spend!='0')) ? $last_time_spend : '00:00:00'; ?>
				</td>
			</tr>
		</table>
	</div>
</div>
<div style="background-color: #1474a0;color: white;margin: 10px -30px 0px -30px">
	<h3>PROBLEMS :</h3>                    
</div>