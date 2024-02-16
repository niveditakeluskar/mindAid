<div class="row">

	<div class="col-lg-12 mb-3">
   <div class="mb-3"><b style="text-align:center;">Calling: Initial Research Followup</b></div>
   <div class="form-row" style="color:#000;">
            <div class="form-group col-md-12 ">
               <label for="" class="" style="color:#000;">Office Visit: Did you have any (other) office visits during the month, including with any of your specialists?</label>
               
            </div>
            <div class="col-md-6">
						<label class="radio radio-primary col-md-4 float-left">
							<input type="radio" id="role1" class="office_visit" name="office_visit" value="1" formControlName="radio">
							<span>Yes</span>
							<span class="checkmark"></span>
						</label>
						<label class="radio radio-primary col-md-4 float-left">
							<input type="radio" id="role2" class="office_visit" name="office_visit" value="0" formControlName="radio" >
							<span>No</span>
							<span class="checkmark"></span>
						</label>
					</div>
         </div>
   <div id="patient_reply">
            <div class="form-row mb-3">
                  <div class="col-md-12">
                     <textarea class="form-control" style="width:100%;" placeholder="Enter Patient's Comments here"></textarea>
                  </div>
            </div>
            <div class="row">
               <div class="col-lg-12 text-right">
                  <button type="submit" data-toggle="modal" data-target="#modal-office-visit-questionnaire" class="btn  btn-primary m-1">Go To Office Visit Module</button>
               </div>
            </div>
         </div>
		<!-- <div class="card">
			<div class="card-body">
				<div class="card-title mb-3"><b>Calling: Initial Research Followup</b></div>
				<div class="form-row">
					<div class="form-group col-md-12">
						<label for="">Office Visit: Did you have any (other) office visits during the month, including with any ofyour specialists?</label>
						<div class="col-md-6">
						<label class="radio radio-primary col-md-4 float-left">
							<input type="radio" id="role1" class="office_visit" name="office_visit" value="1" formControlName="radio">
							<span>Yes</span>
							<span class="checkmark"></span>
						</label>
						<label class="radio radio-primary col-md-4 float-left">
							<input type="radio" id="role2" class="office_visit" name="office_visit" value="0" formControlName="radio" >
							<span>No</span>
							<span class="checkmark"></span>
						</label>
					</div>
            </div>
         </div>
         <div id="patient_reply">
            <div class="form-row mb-3">
                  <div class="col-md-12">
                     <textarea class="form-control" style="width:100%;" placeholder="Enter Patient's Comments here"></textarea>
                  </div>
            </div>
            <div class="row">
               <div class="col-lg-12 text-right">
                  <button type="submit" data-toggle="modal" data-target="#modal-office-visit-questionnaire" class="btn  btn-primary m-1">Go To Office Visit Module</button>
               </div>
            </div>
         </div>
		</div>
	</div> -->
		
		<!-- button -->
		
</div>	
</div>

<!--Modal-->
<div class="modal fade" id="modal-office-visit-questionnaire" tabindex="-1" role="dialog" aria-labelledby="modal-office-visit-questionnaire" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content"> 
            <div class="modal-header">
               <h5 class="modal-title" id="modal-office-visit-questionnaire"> Office Visit Questionnaire </h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="modal-body">
               <div class="container-fluid">
               <div class="row mb-4 hidden" id="office_visit_questionnaire" >
                  <div class="col-md-12 mb-4">
                     <div class="card text-left">
                     <div class="card-header">
                     <p style="color:#3f829a">
                        I’d like to help you prepare for your upcoming office visit with Dr. Ben. I am
                        going to help you organize your questions and when we are done, I am going to put a note in
                        Dr. Ben files so he will be prepared for your visit as well. So, let’s get started:
                     </p>
                     </div>
                        <div class="card-body">

                           <div class="">
                              <div class="row">
                                 <div class="col-md-12">
                                    <p class="fontBlack">1. What was the primary reason this appointment was scheduled ?</p>
                                 </div>
                                 <div class="col-md-8">
                                    <textarea class="form-control" style="width:100%;" placeholder="Enter Patient's Comments here"></textarea>
                                 </div>
                              </div>
                              <br>
                              <div class="row">
                                 <div class="col-md-12">
                                    <p class="fontBlack">2. What questions and issues do you want to talk about with your doctor during your visit ?</p>
                                 </div>
                                 <div class="col-md-8">
                                    <textarea class="form-control" style="width:100%;" placeholder="Enter Patient's Comments here"></textarea>
                                 </div>
                              </div>
                              <br>
                              <div class="row">
                                 <div class="col-md-8">
                                    <p class="fontBlack">3. Do you have any questions about any of the medications that you are taking that you would 
                                       like to discuss with your doctor ?
                                    </p>
                                 </div>
                                 <div class="col-md-8">
                                    <textarea class="form-control" style="width:100%;" placeholder="Enter Patient's Comments here"></textarea>
                                 </div>
                              </div>
                              <br>
                              <div class="row">
                                 <div class="col-md-12">
                                    <p class="fontBlack">4. Do you have any questions regarding any lab results, radiology, or any other test results 
                                       that you would like to discuss ?
                                    </p>
                                 </div>
                                 <div class="col-md-8">
                                    <textarea class="form-control" style="width:100%;" placeholder="Enter Patient's Comments here"></textarea>
                                 </div>
                              </div>
                              <br>
                              <div class="row">
                                 <div class="col-md-12">
                                    <p class="fontBlack">5. Do you have any upcoming surgeries, procedures, labs, radiology appointments, or other
                                       tests scheduled that you have any questions about ?
                                    </p>
                                 </div>
                                 <div class="col-md-8">
                                    <textarea class="form-control" style="width:100%;" placeholder="Enter Patient's Comments here"></textarea>
                                 </div>
                              </div>
                              <br>
                              <div class="row">
                                 <div class="col-md-12">
                                    <p class="fontBlack">6. Do you have any medical records from any of your specialists that need to be sent to your 
                                       doctor before your visit?
                                    </p>
                                 </div>
                                 <div class="col-md-8">
                                    <textarea class="form-control" style="width:100%;" placeholder="Enter Patient's Comments here"></textarea>
                                 </div>
                              </div>
                              <br>
                              <div class="row">
                                 <div class="col-md-12">
                                    <p class="fontBlack font-weight-bold">Great, I will send you a summary of our discussion so you can have it with you for your visit. I
                                    will also be putting a note in the doctor’s file as well.
                                    </p>
                                 </div>
                              </div>
                              <div class="card-footer">
                                 <div class="mc-footer">
                                       <div class="row">
                                          <div class="col-lg-12 text-right">
                                             <button type="submit" class="btn  btn-primary m-1 office-visit-save" >Save</button>
                                          </div>
                                       </div>
                                 </div>
                              </div>

                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
          </div>
      </div>
   </div>
</div>

@section('page-js')
<script type="text/javascript">
   $(function () {
       var table = $('#mailList').DataTable({
        // processing: true,
         //serverSide: true,
   
     });
	 $(".office_visit").on("click", function(){
		 if($(this).val()==1){
			$("#office_visit_questionnaire").css("display","block");
		 }
	 })
   
   });
   
</script> 
<script>
$(document).ready(function(){ 
   $("#patient_reply").hide();
   timer.start();
   $(".sw-btn-prev").attr('disabled', true);
   $(".sw-btn-next").attr('disabled', true);
   $("input[name$='office_visit']").click(function() {
      var office_visit = $(this).val();
      if(office_visit == '1') {
         $("#patient_reply").show();
      } else {
         $("#patient_reply").hide();
      }
   }); 
   $('.office-visit-save').click(function() {
      // data_1  = $("#data_1").value();
      $('#modal-office-visit-questionnaire').modal('hide');
   });
});
</script>
@endsection	