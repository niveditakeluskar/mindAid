@extends('Theme::layouts.master')
@section('before-css')
 <link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.css')}}">
 <link rel="stylesheet" href="{{asset('assets/styles/vendor/pickadate/classic.date.css')}}">


@endsection

@section('main-content')

   <div class="breadcrumb">
                <h1>Basic</h1>
                <ul>
                    <li><a href="">Form</a></li>
                    <li>Basic</li>
                </ul>
            </div>

            <div class="separator-breadcrumb border-top"></div>

            <div class="row">

                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="card-title mb-3">License</div>
                            <div class="alert alert-success d-none" id="msg_div">
                                  <span id="res_message"></span>
                          </div>
                     <form action="{{ route('updateLicense', $row->org_id) }}" method="POST">
                             {{ csrf_field() }}
                                <div class="row">
                                                 
                                    <div class="col-md-6 form-group mb-3">
                                                 <label for="license_model">Model</label>
                                                        @selectslicensemodel("license_model", ["id" => "validationCustomUsername", "class" => "form-control form-control-rounded"])
                                                 <span class="text-danger">{{ $errors->first('license_model') }}</span>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                                 <label for="subscription_in_months">Subscription in Month</label>
                                                        @text("subscription_in_months", ["id" => "subscription_in_months", "class" => "form-control form-control-rounded"])
                                                 <span class="text-danger">{{ $errors->first('subscription_in_months') }}</span>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                                 <label for="start_date">Start Date</label>
                                                        @date("start_date", ["id" => "picker2", "class" => "form-control form-control-rounded", "placeholder" =>"mm/dd/yyyy"])
                                                 <span class="text-danger">{{ $errors->first('start_date') }}</span>
                                                
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                                 <label for="end_date">End Date</label>
                                                        @date("end_date", ["id" => "picker3", "class" => "form-control form-control-rounded", "placeholder" =>"mm/dd/yyyy"])
                                                         <span class="text-danger">{{ $errors->first('start_date') }}</span>
                                                
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                                 <label for="Status">Status</label>
                                                        @selectactivestatus("status",["id" => "status", "class" => "form-control form-control-rounded"])
                                                 <span class="text-danger">{{ $errors->first('status') }}</span>
                                    </div>
                                </div>


                                   <div class="row">
                                   <div class="col-md-4">
                                  
                                   </div>
                                   <div class="col-md-6">
                          
                                    </div>
                                    <div class="col-md-2">
                                         <button type="submit"  class="btn btn-primary btn-block btn-rounded mt-3">Add</button>
                                   </div>
                          <!--  <div class="col-md-6">
                          <button class="btn btn-primary btn-block btn-rounded mt-3">Cancel</button>
                        </div> -->
                </div>
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

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/additional-methods.min.js"></script>

 
 
<!-- <script>
   if ($("#license_submit").length > 0) {
    $("#license_submit").validate({
      
    rules: {
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
        required: "Please enter subscription in months",
        maxlength: "Your last name maxlength should be 50 characters long."
    
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

         
    },
    submitHandler: function(form) {
     $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      $('#send_form').html('Sending..');
      $.ajax({
        url: '{{ url('updateLicense, +<?$id; ?>')}}' ,
        type: "POST",
        data: $('#license_susbmit').serialize(),
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
 -->

@endsection

@section('bottom-js')
<script src="{{asset('assets/js/form.basic.script.js')}}"></script>

@endsection
