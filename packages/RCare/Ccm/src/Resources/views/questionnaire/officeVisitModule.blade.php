@extends('Theme::layouts.master')
@section('page-css')
<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection
@section('main-content')
<div class="breadcrusmb">
   <div class="row">
      <div class="col-md-10">
         <h4 class="card-title mb-3">Office Visit Module</h4>
      </div>
      <!-- <div class="col-md-1">
         <a class="float-right " href="questionnaire" ><i class="plus-icons i-Add" data-toggle="tooltip" data-placement="top" title="Add Questionnaire Template"></i></a>  
      </div> -->
   </div>
   <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>
<div class="row mb-4">
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
                                <button type="submit" class="btn  btn-primary m-1" >Save</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
         </div>
      </div>
   </div>
</div>
@endsection
@section('page-js')
<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script type="text/javascript">
   $(function () {
       var table = $('#mailList').DataTable({
        // processing: true,
         //serverSide: true,
   
     });
   
   });
   
</script> 
@endsection