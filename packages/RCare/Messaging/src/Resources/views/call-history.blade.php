<?php 
    $ch = getCallHistory($patient_id);
?>
<div class="container mt-5 mb-5">
	<div class="row">
		<div class="col-md-7" id="history_list">
			<h4>Call History</h4>
			<ul class="timeline">
			<?php foreach($ch as $callhistory){  ?>
				<li>
					<?php 
						if($callhistory->phone_no == "received"){
							echo "<h5> Incoming Response (".$callhistory->created_at.")</h5>";
							echo  "<b>SMS: </b>".$callhistory->text_msg;
						} else{
					    if($callhistory->call_status == "1"){ ?>
						    <h5><?php echo 'Call Answered ('.$callhistory->created_at.')';?></h5>
						      
                            <table>
                              	<tr>
                              		<th>Call Continue Status</th>
                              		 <?php if($callhistory->call_continue_status==0){  ?>
                              		<th>Call Follow-up date</th>
                              		<th>Call Follow-up Time</th>
                              		<?php } ?>

                              	</tr>
                              	
                              	<tr>
                              	    <td><?php if($callhistory->call_continue_status==0){ echo "No";}else{ echo "Yes";}?></td>
                              	    <?php if($callhistory->call_continue_status==0){  ?>
						      	    <td>{{str_replace('00:00:00','',$callhistory->ccm_answer_followup_date)}}</td>
                              		<td>{{$callhistory->ccm_answer_followup_time}} </td>
                              	    <?php } ?>
                              	</tr>
                             
                               
                              </table>

						    <?php }else{ ?> <h5><?php echo 'Call Not Answered ('.$callhistory->created_at.')';?></h5>

                                 <table>
                              	<tr>
                              		
                              		<th>Call Follow-up date</th>
                              		

                              	</tr>
                              	
                              	<tr>
                              	    
						      	    
									<td>{{str_replace('00:00:00','',$callhistory->ccm_call_followup_date)}}</td>
                              		
                              	</tr>
                                </table>
						     <?php  } ?>
						
							 
							
						<b>
							<?php if($callhistory->call_status == "1"){ ?>
								 Call Script:
							<?php 
						        }else{ 
						              if($callhistory->voice_mail == "1"){ ?> 
						              	  Left Voice Mail : 
						              <?php }else if($callhistory->voice_mail == "2"){ ?>
						              	     No Voice Mail
						              <?php }else{ ?>
                                             SMS Sent
						             <?php }
						             } ?>
                            
						</b>
					       {{$callhistory->text_msg}}
                        


				</li>
			<?php } }
			
			?>
			</ul>
			<div class="d-flex justify-content-center">
               
            </div>   
		</div>
	</div>
</div>

									 