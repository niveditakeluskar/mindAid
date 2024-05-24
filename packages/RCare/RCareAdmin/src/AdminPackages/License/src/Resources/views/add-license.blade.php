@extends('Theme::layouts.master')
@section('page-css')
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.css')}}">
<link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.date.css')}}">


@endsection

@section('main-content')

<div class="breadcrumb">
  <h1>License</h1>
                <!-- <ul>
                    <li><a href="">Form</a></li>
                    <li>Basic</li>

                  </ul> -->
                </div>

                <div class="separator-breadcrumb border-top"></div>

                <div class="row">

                  <div class="col-md-12">
                    <div class="card mb-4">
                      <div class="card-body">
                        @include('Theme::layouts.flash-message')
                       <!--  <div class="card-title mb-3">License</div> -->


                       <div class="alert alert-success d-none" id="msg_div">
                        <span id="res_message"></span>
                      </div>
                     <!--  id="license_submit" -->
                      <form  action="{{ route('create_license') }}" method="POST">
                       {{ csrf_field() }}
                       <div class="row">

                         <div class="col-lg-4 mb-3">
                          <div class="card">
                           <div class="card-body">
                             <div class="card-title mb-3">Organization Name</div>
                             <div class="form-row ">
                              <div class="form-group col-md-12">
                               <label for=""></label> 
                               

                      @foreach ($users as $user)
                              <div class="timeline-badge">
                                       <center>  <img class="badge-img" src="{{asset ($user->logo_img)}}" style="border-radius: 100px; width: 50%">
                                   
                                     <p class="m-0 text-24">{{ $user->name }}</p>
                                    <p class="text-muted m-0">#{{ $user->uid }}</p></center>
                             </div>
                       @endforeach
                             </div>
                           </div>
                           <div class="form-row">
                            <div class="form-group col-md-12">

                            </div>
                          </div> 
                          <div class="form-row ">
                            <div class="form-group col-md-6">

                            </div>
                          </div>                                       
                        </div>
                        <!-- end::form -->
                      </div>
                    </div>  
                    <div class="col-lg-8 mb-3">
                      <div class="card">                                
                       <!--begin::form-->                                
                       <div class="card-body">
                        <div class="card-title mb-3">License Details</div>
                        <div class="form-row ">

                           <input  type="hidden" id="org_id" name ="org_id" class="form-control"  value="{{ $user->id }}"> 
                          <div class="form-group col-md-6">
                           <label for="license_model">Model</label>
                           @text("license_model", ["id" => "license_model", "class" => "form-control", "value" => "SAAS", "readonly"])
                         </div>
                         <div class="form-group col-md-6">
                           <label for="license_model">module</label>
                           @selectmodules("service_id", ["id" => "service_id", "class" => "form-control", "required" ])
                         </div>
                       </div>
                       <div class="card-title mb-3"></div>
                       <div class="form-row mb-4">
                        <div class="form-group col-md-4">
                         <label for="subscription_in_months">Subscription in Month</label>
                         @text("subscription_in_months", ["id" => "number_of_months", "class" => "form-control  "])
                       </div>

                       <div class="form-group col-md-4">
                        <label for="start_date">Start Date</label>
                        <div class='' id='datetimepicker1'>



                          @text("start_date", ["id" => "start_date", "class" => "form-control  ", "placeholder" =>"mm/dd/yyyy"])
                          
                        </div>
                      </div>

                      <div class="form-group col-md-4">
                        <label for="end_date">End Date</label>
                        <div class='' id='datetimepicker2'>
                          @text("end_date", ["id" => "end_date", "class" => "form-control  ", "placeholder" =>"mm/dd/yyyy", "readonly"])
                          

                        </div>
                      </div>

                      <div class="form-group col-md-4">
                       <label for="Status">Status</label>
                       @selectactivestatus("status",["id" => "status", "class" => "form-control  "])
                       
                     </div>
                   </div>

                 </div>

                 <!-- end::form -->
                  </div>
                </div>                               


                           <div class="col-lg-12 mb-3">
                           <div class="card">
                           <div class="card-header ">
                           <div class="col-lg-12 text-right">
                           <!--  id="send_form" -->
                           <button type="submit"  class="btn  btn-primary m-1">Submit</button>
                             <a href="{{ route('addorg') }}" class="btn btn-outline-secondary m-1">Cancel</a> 
               </div>
            </div>								
            </div>
						</div><!-- 
                                   <div class="row">
                                   <div class="col-md-4">
                                   No of weeks: <input type="text" id="week_number" />
  Start date: <input type="text" id="start_date" />
  End date:<input type="text" id="end_week_return" /> -->
                                  <!--  </div>
                                   <div class="col-md-6">
                          
                                    </div>
                                    <div class="col-md-2">
                                         <button type="submit" id="send_form" class="btn btn-primary btn-block btn-rounded mt-3">Add</button>
                                       </div> -->
                          <!--  <div class="col-md-6">
                          <button class="btn btn-primary btn-block btn-rounded mt-3">Cancel</button>
                        </div> -->
                        <!--</div> -->



                      </div>
                    </form>
                  </div>
                </div>
              </div>

            </div>
          
           
         

          @endsection
    
          @section('page-js')


          <script src="{{asset('assets/js/vendor/pickadate/picker.js')}}"></script>
          <script src="{{asset('assets/js/vendor/pickadate/picker.date.js')}}"></script>


          <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

          <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">

          <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>  
          <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>


<!-- <script src="{{asset('assets/js/vendor/pickadate/picker.js')}}"></script>
<script src="{{asset('assets/js/vendor/pickadate/picker.date.js')}}"></script>

  

  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>




  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>

    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script> -->




    <script type="text/javascript">

        $(document).ready(function() {
            util.getToDoListData(0, {{getPageModuleName()}});
        });


     if ($("#license_submit").length > 0) {
      $("#license_submit").validate({

        rules: {

          service_id: {
            required: true,
           },

          subscription_in_months: {
            required: true,
            
          },

          license_model: {
            required: true,
            
          },

          start_date: {
            required: true,
            
          },


          end_date: {
            required: true,

          },   

          status: {
            required: true,

          },  
        },
        messages: {

          subscription_in_months: {
            required: "Please enter name maxlength should be 50 characters long."

          },
          license_model: {
            required: "Please select license",

          },

          start_date: {
            required: "Please select start date",

          },

          end_date: {
            required: "Please select end date",

          },

          status: {
            required: "Please select status",

          },

          service_id:
          {
            required: "Please select module",

          },


        },
        submitHandler: function(form) {
         $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
         $('#send_form').html('Sending..');
         $.ajax({
          url: '{{ url('createLicense')}}' ,
          type: "POST",
          data: $('#license_submit').serialize(),
          success: function( response ) {
            $('#send_form').html('Submit');
            $('#res_message').show();
            $('#res_message').html(response.msg);
            $('#msg_div').removeClass('d-none');

            document.getElementById("license_submit").reset(); 
            setTimeout(function(){
              $('#res_message').hide();
              $('#msg_div').hide();
            },20000);
          }
        });
       }
     })
    }


  </script>

  <script type="text/javascript">
    jQuery(function ($) {
      $('#start_date').datepicker({
        format: "dd/mm/yyyy"
      });

      $('#end_date').datepicker({
        format: "dd/mm/yyyy"
      });

      $('#number_of_months, #start_date').change(function () {
        var months = +$('#number_of_months').val() || 0,
        date = $('#start_date').datepicker('getDate');
        if (months && date) {
          date.setMonth(date.getMonth()+months);
          $('#end_date').datepicker('setDate', date);
        } else {
          $('#end_date').val('');
        }
      })
    });
  </script>


  @endsection

  @section('bottom-js')
  <script src="{{asset('assets/js/form.basic.script.js')}}"></script>
  <script src="{{asset('assets/js/modal.script.js')}}"></script>

      <script type="text/javascript">
             $("document").ready(function(){
    setTimeout(function(){
       $("div.alert").remove();
    }, 5000 ); // 5 secs

});
          </script>
  @endsection
