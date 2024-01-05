@extends('Theme::layouts_2.to-do-master')
@section('page-css')
<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection
@section('main-content')
<div class="breadcrusmb">
   <div class="row">
      <div class="col-md-11">
         <h4 class="card-title mb-3">Partner Registration</h4>
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
            <form action="{{ route('add.partner.form') }}" method="post" name ="partner_form"  id="partner_form">
            @csrf
            <div class="alert alert-success" id="success-alert" style="display: none;">
               <button type="button" class="close" data-dismiss="alert">x</button>
               <strong> Partner added successfully! </strong><span id="text"></span>
            </div>
          
            <div class="row">
               <div class="col-6">
                  <div class="form-group">
                     <label>Partner Name<span class="error">*</span></label>
                      @text("partner_name")                         
                  </div>
               </div>
             
              
               <div class="col-6">
                  <div class="form-group">            
                     <label>Location<span class='error'>*</span></label> 
                     @text("location")    
                  </div>
               </div>
                <div class="col-6">
                  <div class="form-group">            
                     <label>Phone No.<span class='error'>*</span></label>                     
                  @phone("phone") 
                  </div>
               </div>
               <div class="col-6">
                  <div class="form-group">            
                     <label>Email<span class='error'>*</span></label>                     
                    @email("email")
                  </div>
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
<script>
   $(document).ready(function() {
     
      util.getToDoListData(0, {{getPageModuleName()}});   

      form.ajaxForm("partner_form", onPartnerForm, function () {   
        return true;
  });

   });




var onPartnerForm = function (formObj, fields, response) {
  //console.log(response);
  if (response.status == 200) {
    $("form[name='partner_form']")[0].reset();
      $("form[name='partner_form'] .alert-success").show();
      var scrollPos = $(".main-content").offset().top;
      $(window).scrollTop(scrollPos);
    //goToNextStep("call_step_1_id");
    setTimeout(function () {
      $('.alert').fadeOut('fast');//goToNextStep("drug-icon-pill"); 
    }, 3000);
  }
}

</script>
@endsection