@extends('layouts.master')
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
                           
                            <div class="card-title mb-3"> New Compliance</div>
                            <form id="comp-form">
                                @csrf
                                <div class="row">
                                   <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Select Condition</label>
                                        <select class="form-control" name="condition" id="condition">
                                            <option value="">Select</option>
                                            <option value="CBC">CBC</option>
                                            <option value="2">CMP</option>
                                            <option value="3">Blood Pressure - Systolic 2</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="picker1">Select Sub-Condition</label>
                            <select class="form-control" name="sub_condition" id="otherType" style="display:none;">
                                            <option value="">Select</option>
                                            <option value="1">WBC</option>
                                            <option value="2">RBC</option>
                                            <option value="3">Hematocrit</option>
                                            <option value="4">Platelets</option>
                                            <option value="5">MCV</option>
                           </select>
                                    </div>
                                    <div class="col-md-6 form-group mb-3">
                                       <label class="radio radio-outline-success">
                                          <input type="radio" name="radio" value="120/80mmHg" formControlName="radio">
                                            <span>Male</span>
                                            <span class="checkmark"></span>
                                       </label>
                                       <label class="radio radio-outline-success">
                                          <input type="radio" name="radio" value="110/70mmHg" formControlName="radio">
                                            <span>Femal</span>
                                            <span class="checkmark"></span>
                                       </label>
                                       <label class="radio radio-outline-success">
                                          <input type="radio" name="radio" value="125/85mmHg" formControlName="radio">
                                            <span>Other</span>
                                            <span class="checkmark"></span>
                                       </label>
                                    </div>

                               
                                    <div class="col-md-6 form-group mb-3">
                                        <label for="Range">Range </label>
                                        <input type="text" name="Range" class="form-control" id="it" placeholder="">
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="Lower">Lower </label>
                                        <input type="number" name="lower" class="form-control" id="lastName1" placeholder="">
                                    </div>

                                   <div class="col-md-6 form-group mb-3">
                                        <label for="Higer">Higer </label>
                                        <input type="number" name="higer" class="form-control" id="lastName1" placeholder="Enter your last name">
                                    </div>

                                    <div class="col-md-6 form-group mb-3">
                                        <label for="notes">Note </label>
                                         <textarea class="form-control" aria-label="With textarea" name="notes"></textarea>
                                    </div>

                                  

                                    

                                  

                                    

                                    <div class="col-md-12">
                                         <button class="btn btn-primary float-right" id="save-comp">Submit</button>
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
<script type="text/javascript">
   $(document).ready(function(){ 
      $('#save-comp').click(function(){
         alert('hi');
         $.ajax({
            type: 'post',
           
            url: '/org/SaveComp',
            data: $('#comp-form').serialize(), // getting filed value in serialize form
            success: function(response){
              
              alert('Created successfully!');

              document.getElementById("comp-form").reset();


            }
         });
          // to prevent refreshing the whole page page
            return false;
      });
  
      });


   $('#condition').change(function(){
   selection = $(this).val();    
   switch(selection)
   { 
       case 'CBC':
           $('#otherType').show();
           break;
       default:
           $('#otherType').hide();
           break;
   }
});

   $(document).ready(function(){
    $("input[type=radio]").click(function(){
       $("#it").val(this.value);
    }); 
});


    
</script>


@endsection

@section('bottom-js')
<script src="{{asset('assets/js/form.basic.script.js')}}"></script>


@endsection
