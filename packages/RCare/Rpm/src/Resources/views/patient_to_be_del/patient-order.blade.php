@extends('Theme::layouts_2.to-do-master')
@section('page-css')
<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection
@section('main-content')
<div class="breadcrusmb">
   <div class="row">
      <div class="col-md-11">
         <h4 class="card-title mb-3">Patient Order</h4>
      </div>
   </div>
</div>
<div class="separator-breadcrumb border-top"></div>
<div class="row mb-4">
   <div class="col-md-12 mb-4">
      <div id="success"></div>
      <div class="card">
         <div class="card-body" id="hobby">
            <div id="error_msg"></div>
            <form action="http://rcareproto2.d-insights.global/rpm/patientorder" method="post" name ="patient_order_form"  id="patient_order_form">
            @csrf
            <div class="alert alert-success" id="success-alert" style="display: none;">
               <button type="button" class="close" data-dismiss="alert">x</button>
               <strong> Patient Order added successfully! </strong><span id="text"></span>
            </div>
            <input type="hidden" name="devicename" id="devicename">
            <div class="row">
               <div class="col-6">
                  <div class="form-group">
                     <label>Order Id<span class="error">*</span></label>
                      <input type="text" name="orderid" id="orderid" class="form-control" >     
                  </div>
               </div>
               <div class="col-6">
                  <div class="form-group">
                     <label>Patient Name<span class="error">*</span></label>
                     @selectallpatient("patient_id",["id"=>"patient_id"])
                  </div>
               </div>
               <div class="col-6">
                  <div class="form-group">            
                     <label>Device Name <span class='error'>*</span></label> 
                     @selectDevice("device_id",["id"=>"device_id"])   
                  </div>
               </div>
               <div class="col-6">
                  <div class="form-group">            
                     <label>Partner MRN<span class='error'>*</span></label>                     
                     <input type="text" name="partner_mrn" id="partner_mrn" class="form-control" >     
                  </div>
               </div>
                <div class="col-6">
                  <div class="form-group">            
                     <label>HUB<span class='error'>*</span></label>                     
                     <input type="text" name="hub" id="hub" class="form-control" >     
                  </div>
               </div>
               <div class="col-6">
                  <div class="form-group">            
                     <label>Carrier Name<span class='error'>*</span></label>                     
                     <input type="text" name="carrier_name" id="carrier_name" class="form-control" >     
                  </div>
               </div>
                <div class="col-6">
                  <div class="form-group">            
                     <label>Tracking No<span class='error'>*</span></label>                     
                     <input type="text" name="tracking_no" id="tracking_no" class="form-control" >     
                  </div>
               </div>
               
               <div class="col-6">
                  <div class="col-md-12 forms-element">
                     <label for="step-1_new_dignosis" class="mr-3 mb-4"><b>Shipped:</b> <span class="error">*</span></label><br>
                     <div class="mr-3 d-inline-flex align-self-center">
                        <label for="shippedyes" class="radio radio-primary mr-3">
                        <input type="radio" name="shipped" id="shippedyes" value="Y" formControlName="radio"
                           >
                        <span>Yes</span>
                        <span class="checkmark"></span>
                        </label>
                        <label for="shippedno" class="radio radio-primary mr-3">
                        <input type="radio" name="shipped" id="shippedno"  value="N" formControlName="radio"
                           >
                        <span>No</span>
                        <span class="checkmark"></span>
                        </label>
                     </div>
                  </div>
                  <div class="invalid-feedback"></div>
               </div>
            </div>
            <div class="row">
               <div class="col-12 text-right form-group mb-4">
                  <button type="submit" class="btn btn-primary">Submit</button>
               </div>
            </div>
            </form>
         </div>
      </div>
   </div>
</div>
<div id="app"></div>
@endsection
@section('page-js')
<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script src="{{asset('assets/js/tooltip.script.js')}}"></script>
<script>
   $(document).ready(function() {
     
      util.getToDoListData(0, {{getPageModuleName()}});
    //  CompletedCheck();

   });


 $("#patient_order_form").submit(function(e) {
    e.preventDefault();
 var patient_id=$('#patient_id option:selected').val();
    var devicename=$('#device_id option:selected').text();
    var deviceid=$('#device_id option:selected').val();
    var partner_mrn=$('#partner_mrn').val();
     var orderid=$('#orderid').val();
    var shipped=$('input[name="shipped"]').val();
     var hub=$('#hub').val();
      var carrier_name=$('#carrier_name').val();
      var tracking_no=$('#tracking_no').val();
 
console.log(patient_id+"  "+partner_mrn+" "+ shipped+" "+orderid+" "+tracking_no+" "+carrier_name);

     var data='{"order_id": "'+orderid+'", "MSP_MRN": "'+partner_mrn+'","REN_MRN":"'+patient_id+'" ,"Devices": { "ID": "'+deviceid+'","name": "'+devicename+'"}, "Hub": "'+hub+'","shipping_details": { "shipped": "'+shipped+'","carrier-name": "'+carrier_name+'","tracking_no":"'+tracking_no+'"},"recd_status": "Y"}';

 console.log(data);

    $.ajaxSetup({
       headers: {
         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
       }
     });
     $.ajax({
       type: 'post',
       url: '/rpm/get-patient-order',
       data: {'data':data},
       dataType: "json",
       success: function (response) {
       
         },    
     });
 })


   var CompletedCheck = function () {

  


   //var data=

  // $.ajaxSetup({
  //   headers: {
  //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  //   }
  // });
  // $.ajax({
  //   type: 'post',
  //   url: '/ccm/CheckCompletedCheckbox',
  //   data: 'patient_id=' + patient_id,
  //   success: function (response) {
    
  //     },    
  // });
}
</script>
@endsection