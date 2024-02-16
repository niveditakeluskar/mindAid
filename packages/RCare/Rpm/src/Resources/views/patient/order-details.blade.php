@extends('Theme::layouts.master')
@section('page-css')
<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
<style type="text/css">
   input:not(.userListBox){
background:transparent !important;
border:none !important;
outline:none !important;
padding: 0px 0px 0px 0px !important;
}
.form-group label{
   font-size: 13px;
}
input{
   margin-left: 7px;
}

ul li{
  display: inline;
}

</style>
@endsection
@section('main-content')
<div class="breadcrusmb">
   <div class="row">
      <div class="col-md-4">
         <h4 class="card-title mb-3">Order Details</h4>
      </div>
      <!-- <div class="col-md-8">
         <h4 id="orderno"></h4>
         </div> -->
   </div>
</div>
<div class="separator-breadcrumb border-top"></div>
<div class="row mb-4">
   <div class="col-md-12 mb-4">
      <div id="success"></div>
      <!-- <div class="card">
         <div class="card-body" id="hobby"> -->
            <div id="error_msg"></div>
            <form  name ="order_details"  id="order_details">
               @csrf
               <div class="alert alert-success" id="success-alert" style="display: none;">
                  <button type="button" class="close" data-dismiss="alert">x</button>
                  <strong>
                     Device Order added successfully! <br> Order No. is :
                     <div id="orderno"></div>
                  </strong>
                  <span id="text"></span>
               </div>
               <div id="error_msg"></div>
               <input type="hidden" name="devicename" id="devicename">               
               
               <div class="card">
                    <div class="card-header"> 
                    Patient Details
                  </div>
                  <div class="card-body" id="hobby">                 
                     <div class="row">
                        <div class="col-md-3 form-group">                
                          <label class="form-group"> Patient Name :</label>                          
                          @text("patientname", ["id" => "patientname","disabled"=>"disabled"])  
                        </div>
                                    
                        <div class="col-md-3 form-group">
                           <label class="form-group">Phone :</label>
                           @text("pphone", ["id" => "pphone","disabled"=>"disabled"]) 
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              <label for="add_1">Address :</label>
                              @text("paddress", ["id" => "paddress","disabled"=>"disabled"])
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              <label for="pcity">City :</label>
                              @text("pcity", ["id" => "pcity","disabled"=>"disabled"])
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              <label for="pcity">Zip :</label>
                              @text("pzip", ["id" => "pzip","disabled"=>"disabled"])
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              <label for="pstate">State : </label>           
                              @text("statename", ["id" => "statename","disabled"=>"disabled"])
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              <label for="emr">Email : </label>
                              @text("pemail", ["id" => "pemail","disabled"=>"disabled"])
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              <label for="emr">Group Code : </label>
                              @text("group_code", ["id" => "group_code","disabled"=>"disabled"])
                           </div>
                        </div>
                         <div class="col-md-3">
                           <div class="form-group">
                              <label for="emr">Action Plan : </label>
                              @text("action_plan", ["id" => "action_plan","disabled"=>"disabled"])
                           </div>
                        </div>
                         <div class="col-md-3">
                           <div class="form-group">
                              <label for="emr">User Id : </label>
                              @text("userid", ["id" => "userid","disabled"=>"disabled"])
                           </div>
                        </div>                       
                     </div>
                  </div>
               </div>
               <br>
                <div class="card">                    
                  <div class="card-body" id="hobby"> 
                  <div class="form-group">
                      <label for="emr">Devices : </label>  
                       @text("Devices", ["id" => "Devices","disabled"=>"disabled","style"=>"width:100%"])  
                       </div>            
                  </div>
                </div>
                <br>
               <!-- Responsible party -->
               <div class="card">
                  <div class="card-header"> 
                     Responsible Party 
                  </div>
                  <div class="card-body">
                     <div class="row">
                        <div class="col-md-3">
                           <div class="form-group">
                              <label>First Name :</label>
                              @text("respname", ["id" => "respname","disabled"=>"disabled"])
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              <label>Last Name :</label>
                              @text("resplastname", ["id" => "resplastname","disabled"=>"disabled"])
                           </div>
                        </div>   
                         <div class="col-md-3">
                           <div class="form-group">
                              <label>Phone :</label>
                              @text("respphone", ["id" => "respphone","disabled"=>"disabled"])
                           </div>
                        </div>   
                         <div class="col-md-3">
                           <div class="form-group">
                              <label>DOB :</label>
                               @date("dob", ["id"=>"dob","disabled"=>"disabled"])   
                            
                           </div>
                        </div>   
                         <div class="col-md-3">
                           <div class="form-group">
                              <label>Gender :</label>
                              @text("gender", ["id" => "gender","disabled"=>"disabled"])
                           </div>
                        </div>   
                         <div class="col-md-3">
                           <div class="form-group">
                              <label>Address :</label>
                              @text("respaddress", ["id" => "respaddress","disabled"=>"disabled"])
                           </div>
                        </div>  
                        <div class="col-md-3">
                           <div class="form-group">
                              <label>Zip :</label>
                              @text("respzip", ["id" => "respzip","disabled"=>"disabled"])
                           </div>
                        </div>  
                         <div class="col-md-3">
                           <div class="form-group">
                              <label>State :</label>
                              @text("respstate", ["id" => "respstate","disabled"=>"disabled"])
                           </div>
                        </div>      
                          <div class="col-md-3">
                           <div class="form-group">
                              <label>City :</label>
                              @text("respcustcity", ["id" => "respcustcity","disabled"=>"disabled"])
                           </div>
                        </div>  
                         <div class="col-md-3">
                           <div class="form-group">
                              <label>Email :</label>
                              @text("respemail", ["id" => "respemail","disabled"=>"disabled"])
                           </div>
                        </div>                   
                     </div>
                  </div>
               </div>
               <br>
                <!-- billing details -->
               <div class="card">
                  <div class="card-header"> 
                      Billing Details 
                  </div>
                  <div class="card-body">
                     <div class="row">
                        <div class="col-md-3">
                           <div class="form-group">
                              <label>First Name :</label>
                              @text("billingname", ["id" => "billingname","disabled"=>"disabled"])
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              <label>Last Name :</label>
                              @text("billinglastname", ["id" => "billinglastname","disabled"=>"disabled"])
                           </div>
                        </div>   
                         <div class="col-md-3">
                           <div class="form-group">
                              <label>Phone :</label>
                              @text("billingphone", ["id" => "billingphone","disabled"=>"disabled"])
                           </div>
                        </div>   
                         <div class="col-md-3">
                           <div class="form-group">
                              <label>Address :</label>
                              @text("billingaddress", ["id" => "billingaddress","disabled"=>"disabled"])
                           </div>
                        </div>  
                           <div class="col-md-3">
                           <div class="form-group">
                              <label>City :</label>
                              @text("billingcustcity", ["id" => "billingcustcity","disabled"=>"disabled"])
                           </div>
                        </div> 
                         <div class="col-md-3">
                           <div class="form-group">
                              <label>State :</label>
                              @text("billingstate", ["id" => "billingstate","disabled"=>"disabled"])
                           </div>
                        </div>  
                        <div class="col-md-3">
                           <div class="form-group">
                              <label>Zip :</label>
                              @text("billingzip", ["id" => "billingzip","disabled"=>"disabled"])
                           </div>
                        </div>  
                         <div class="col-md-3">
                           <div class="form-group">
                              <label>Email :</label>
                              @text("billingemail", ["id" => "billingemail","disabled"=>"disabled"])
                           </div>
                        </div>                   
                     </div>
                  </div>
               </div>
               <br>
                <!-- Shipping details -->
               <div class="card">
                  <div class="card-header"> 
                      Shipping Details 
                  </div>
                  <div class="card-body">
                     <div class="row">
                        <div class="col-md-3">
                           <div class="form-group">
                              <label>First Name :</label>
                              @text("shippingname", ["id" => "shippingname","disabled"=>"disabled"])
                           </div>
                        </div>
                        <div class="col-md-3">
                           <div class="form-group">
                              <label>Last Name :</label>
                              @text("shippinglastname", ["id" => "shippinglastname","disabled"=>"disabled"])
                           </div>
                        </div>   
                         <div class="col-md-3">
                           <div class="form-group">
                              <label>Phone :</label>
                              @text("shippingphone", ["id" => "shippingphone","disabled"=>"disabled"])
                           </div>
                        </div>   
                         <div class="col-md-3">
                           <div class="form-group">
                              <label>Address :</label>
                              @text("shippingaddress", ["id" => "shippingaddress","disabled"=>"disabled"])
                           </div>
                        </div>  
                            <div class="col-md-3">
                           <div class="form-group">
                              <label>Email :</label>
                              @text("shippingemail", ["id" => "shippingemail","disabled"=>"disabled"])
                           </div>
                        </div>  
                          <div class="col-md-3">
                           <div class="form-group">
                              <label>City :</label>
                              @text("shippingcustcity", ["id" => "shippingcity","disabled"=>"disabled"])
                           </div>
                        </div> 
                         <div class="col-md-3">
                           <div class="form-group">
                              <label>State :</label>
                              @text("shippingstate", ["id" => "shippingstate","disabled"=>"disabled"])
                           </div>
                        </div>   
                        
                        <div class="col-md-3">
                           <div class="form-group">
                              <label>Zip :</label>
                              @text("shippingzip", ["id" => "shippingzip","disabled"=>"disabled"])
                           </div>
                        </div> 
                         <div class="col-md-5">
                           <div class="form-group">
                              <label>Shipping Method :</label>
                              @text("shippingoption", ["id" => "shippingoption","disabled"=>"disabled"])
                           </div>
                        </div>                   
                     </div>
                  </div>
               </div>              
               <br>   
            </form>
      <!--    </div>
      </div> -->
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
          
          $('input').removeClass('form-control');
            var sPageURL = window.location.pathname;
            parts = sPageURL.split("/"),
               id = parts[parts.length - 1];
            var orderid = id;
            if ($.isNumeric(orderid)) {
               var data = "";
               var formpopulateurl = "/rpm/populate-order-details/" + orderid;               
               populateForm(data, formpopulateurl);
            }
   
   }); 
   
  
   
   var populateForm = function (data, url) {      
     $.get(
        url,
         data,
          function (result) {           
            for (var key in result) {     
            // console.log(key);         
              form.dynamicFormPopulate(key, result[key]);
               if(key=="order_details")
               {
               	 var devicedata=result[key].static.devicename;    
                    var fname=result[key].static.pfname;  
                     var lname=result[key].static.plastname;    
                     $('#patientname').val(fname+" "+lname); 
               	 	
               	 	$('#Devices').val(devicedata);
                
               //	 console.log(finaldata+"devicedata");
               	 
               	 
               	 
               }
            
          }
       }
   ).fail(function (result) {
   console.error("Population Error:", result);
   });
   
   };
   
   
  
 
</script>
@endsection