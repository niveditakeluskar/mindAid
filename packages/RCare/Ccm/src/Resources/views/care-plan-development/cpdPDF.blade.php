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
</style>
	<title>CARE PLAN</title>
</head>
<body>
<div class="card">
	<div class="card-body">             
        <div class="form-row">
            <input type="hidden" name="hidden_id" id="hidden_id" value="{{$patient[0]->id}}">
            <input type="hidden" name="page_module_id" id="page_module_id" value="{{ getPageModuleName() }}">
            <!-- <input type="hidden" name="practice_id" id="practice_id" value="{{-- $patient[0]->practice_id --}}"> -->
           
           <table width = 100%>
		 	<tr class="col1">
		 		<!-- <th></th> -->
		 		<td rowspan='4' width="10%">
		 			<?php
                    $m_id = getPageModuleName();
                    $c_id = getPageSubModuleName();
                        $default_img_path = asset('assets/images/faces/avatar.png');
                        $path             = $patient[0]->profile_img;
                    ?>
			    	@if(isset($patient[0]->profile_img) && ($patient[0]->profile_img != ""))
                       	{{-- @if(file_exists($path))) --}}
                            <img src="{{ asset($path) }}" class='user-image' style="width: 60px;" />
                            {{-- @else --}}
                            <!-- <img src="{{-- $default_img_path --}}" class='user-image' style="width: 60px;" /> -->
                            {{-- @endif --}}
                    	@else
                            <img src="{{ $default_img_path }}" class='user-image' style="width: 60px;" />
                    @endif
            	</td>
            	<!-- <td></td> -->
            	<td width="35%"><b>Name:</b> {{$patient[0]->fname}} {{$patient[0]->lname}}</td>
			    <td><b>Gender:</b>
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
			    <td><b>EMR No.:</b> {{$patient[0]->emr}} 
                    <?php
                        if(isset($patient_providers[0])){
                            echo $patient_providers[0]->practice_emr;      
                        }
                    ?>
                </td>
				<!--
				<td><b>: </b></td>
				-->

		  	</tr>

		  	<tr class="col2">
			    <td><b>Email:</b> {{$patient[0]->email}}</td>
			    <td><b>DOB:</b> {{$patient[0]->dob}}</td>
			    <td><b>Services: </b><?php if(isset($patient[0]->PatientServices)){
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
	                }?></td>

		  	</tr>
			<tr>
				
				<td><b>Mobile:</b> {{$patient[0]->mob}}<?php if(isset($patient[0]->home_number) && $patient[0]->home_number!=''){echo ' | '.$patient[0]->home_number; }?></td>
				 <td><b>Age :</b> {{ empty($patient[0]->dob) ? '' : age($patient[0]->dob)}}</td>
				<td><b>Enrolled On: </b><?php
                    $enroll_date="";
                        $module_id = getPageModuleName();                                    
                        if(isset($patient_enroll_date[0]->date_enrolled)){
                           $str = $patient_enroll_date[0]->date_enrolled; 
                            $enroll_date = date("m-d-Y", strtotime($str)); 
                            echo $enroll_date;   
                    }?></td>
			    
			</tr>
			<tr colspan='2'><td><b>Total Time Spent:</b><?php echo (isset($last_time_spend) && ($last_time_spend!='0')) ? $last_time_spend : '00:00:00'; ?></td></tr>
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
		            <h3 class="card-title mb-3" id="ID">Care Plan</h3>
		            <div class="row">
						<label class="col-md-2" id="ID1"><h4>Diagnosis Code:</h4></label>
						<div class="col-md-10" id="ID2">	
							@foreach ($PatientDiagnosis as $rec)
									{{ $rec->condition }} ({{ $rec->code }} ),
							@endforeach
						</div>
					</div>
					<hr>
					<div class="row">
						<label class="col-md-2" id="ID3"><h4>Symptoms:</h4></label>
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
						<label class="col-md-2" id="ID5"><h4>Goals:</h4></label>
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
						<label class="col-md-2" id="ID7"><h4>Tasks:</h4></label>
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
						<label class="col-md-2" id="ID9"><h4>Medications:</h4></label>
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
						<label class="col-md-2" id="ID11"><h4>Allergies:</h4></label>
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
						<label class="col-md-2" id="ID13"><h4>Support:</h4></label>
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
					<div class="row">
						<label class="col-md-2" id="ID15"><h4>Comments:</h4></label>
						<div class="col-md-10" id="ID16">
							<?php $i=1;?>
		                   
							@foreach ($PatientDiag as $key => $value)		
		                        <?php  $data = json_decode($value->comments, true);   
		                        if($value->comments!=null || $value->comments!='')
		                        {
		                      echo  $i++;                     
		                        ?> )                       
		                   {{$value->comments}},
							      <br/>
							     <?php } ?>
							    
							      @endforeach
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
