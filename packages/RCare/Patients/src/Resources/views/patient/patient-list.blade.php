@extends('Theme::layouts_2.to-do-master')

@section('page-css')
    <link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')


<!-- include('Rpm::patient.filter-practice-patient') -->

<div class="breadcrusmb">

  <div class="row">
		<div class="col-md-11">
		   <h4 class="card-title mb-3">Patients</h4>
		</div>
		 <div class="col-md-1">
         <a class="" href="javascript:void(0)" id="addPatient"><i class="add-icons i-Administrator" data-toggle="tooltip" data-placement="top" title="Add Patient"></i>
         <i class="plus-icons i-Add" data-toggle="tooltip" data-placement="top" title="Add Patient"></i></a>  
		</div>
</div>
   <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>
<!-- include('Rpm::patient.filter-practice-patient') -->
 <div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
           <!--  <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>    -->            
                @include('Theme::layouts.flash-message')
                <div class="table-responsive">
                    <table id="patient-list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Sr No.</th>
                            <th>Patient</th>
                            <th>DOB</th>
                            <th>Email</th> 
                            <th>Phone No. </th>
                            <th>Clinic</th>
                            <th>Total Questionnaire Responses</th>
                            <th>Pending Questionnaire Responses</th>
                            <th>Surgeries</th>
                            <th>Action</th> 
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add medication -->
<div class="modal fade" id="add_patient_modal" aria-hidden="true">
  <div class="modal-dialog ">
    <div class="modal-content">
        <div class="modal-header">
                <h4 class="modal-title" id="modelHeading1">Add patient</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <form action="{{route('ajax.patient.registration')}}" method="POST" name ="patient_registration_form"  id="patient_registration_form">
         {{ csrf_field() }}
        <div class="modal-body">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-12 form-group mb-3">
                        <label for="practicename">Clinic</label>
                        @selectpracticespcp("practice_id", ["id" => "practices"]) 
                        <!-- selectpractices -->
                    </div>
                    <div class="col-md-4 form-group mb-3" >
                        <label for="username"><span class="error">*</span> First Name</label>
                        @text("fname", ["id" => "fname", "class" => "capital-first "])
                    </div>
                    <div class="col-md-4 form-group mb-3"> 
                        <label for="username"><span class="error">*</span> Last Name</label>
                        @text("mname", ["id" => "mname","class" => "capital-first"])
                    </div>
                    <div class="col-md-4 form-group mb-3">
                        <label for="username"><span class="error"></span> Middle Name</label>
                        @text("mname", ["id" => "mname","class" => "capital-first"])
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group mb-3" ><label><span class="error">*</span>Date of Birth</label>
                        @date("dob")
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label>Gender</label>
                        @select("Gender", "gender", [ 0 => "Male", 1 => "Female"])
                    </div>
                </div>
                <div class="row">
                    <div class="col-6 form-group mb-3">
                        <label for="city">City</label>
                        @text("city", ["id" => "city", "class" => "capitalize"])
                    </div>
                    <div class="col-6 form-group mb-3">
                        <label for="state">State</label>
                        @selectstate("state", request()->input("state"))
                    </div>
                    <div class="col-6 form-group mb-3">
                        <label for="zipcode">zipcode</label>
                        @text("Zipcode", ["id" => "zipcode", "class" => "capitalize"])
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label for="email"> Email Address</label>
                        @email("email", ["id" => "email", "class" => " "])
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group mb-3">
                        <label>Country Code</label>
                        @selectcountrycode("country_code", ["id" => "country_code"]) 
                    </div> 
                    <div class="col-md-6 form-group mb-3">
                        <label for="password"><span class="error">*</span> Contact Number</label>
                            @phone("mob", ["id"=> "number"])
                    </div>
                    <!--div class="col-md-4 form-group mb-3" >
                        <label>Surgery Date</label>
                        @date("surgery_date")
                    </div-->
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="addr1">Address</label>
                            @text("address", ["id" => "address"])
                        </div>
                    </div>
                </div>    
            </div>    
            <div class="card-footer">
                <div class="mc-footer">
                <div class="row">
                    <div class="col-lg-12 text-right">
                        <button type="submit"  class="btn  btn-primary m-1">Save</button>
                        <button type="button" class="btn btn-outline-secondary m-1" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
                </div>
            </div>
        </div>
        </form>
    </div>
  </div>
</div> 


<!-- Edit medication -->
<div class="modal fade" id="edit_patient_modal" aria-hidden="true">
    <div class="modal-dialog modal-l">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading1">Surgery</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{ route('save.patient.surgeryData') }}" method="POST" name="surgeriesPatientForm" id="surgeriesPatientForm">
                   {{ csrf_field() }} 
                    <input type="hidden" name="id" id="id"> 
                    <input type="hidden" name="patient_id" id="patient_id">
						<div class="form-group">
							<div class="row">
                            <div class="col-md-6  form-group mb-3">            
                                <label for="name">Surgery Name</label><span class='error'>*</span>
                                @selectsurgery("surgery_id[]",["id" =>"surgery_name","placeholder" => "Enter Surgery Name"])
                            </div>
                            <div class="col-md-6  form-group mb-3">            
                                <label for="description">Date<span class='error'>*</span></label>
                                @date("surgery_date[]", ["placeholder" => "Enter Date"])
                            </div>     
                            </div>
                            <div class="col-md-12 pt-1" id="append_surgeries"></div>
                                <i class="plus-icons i-Add"  class="btn btn-sprimary float-left"  onclick="additionalSurgery(this)" title="Add Surgeries"></i>
                            </div>
            </div>    
                        <div class="card-footer"> 
                            <div class="mc-footer">
                                <div class="row">
                                    <div class="col-lg-12 text-right">
                                        <button type="submit" class="btn  btn-primary m-1">Save</button>
                                        <button type="button" class="btn btn-outline-secondary m-1" data-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>
						<div class="row">
						<div class="col-lg-12 ">
                        <div class="table-responsive" style="margin:10px;">
                            <table id="surgeries-list" class="display table table-striped table-bordered" style="width:95%">
                                <thead>
                                    <tr>
                                        <th>Sr No.</th>
                                        <th>Surgery</th>
                                        <th>Date</th> 
                                    </tr>
                                </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>surgery1</td>
                                    <th>12/29/2024</td>    
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>surgery2</td>
                                    <th>12/29/2024</td>     
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>surgery3</td>
                                    <th>12/29/2024</td>     
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>surgery4</td>
                                    <th>12/29/2024</td>      
                                </tr>
                            </tbody>
                            </table>
                        </div>
						</div>
						</div>
					</div>
                </form>
            </div>
        </div>
    </div>
</div> 

@endsection 

@section('page-js')
    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    <script src="{{asset('assets/js/tooltip.script.js')}}"></script>
    <script type="text/javascript">
        var surgerycount = 0;
        function additionalSurgery(formsObj) {
            var formName = $(formsObj).closest(":has(form)").find('form').attr('name');
            surgerycount++;
            $("form[name='" + formName + "'] #append_surgeries").append('<div class="row btn_remove mb-2" id="btn_removeSuregery_' + surgerycount + '"><div class="col-md-5">@selectsurgery("surgery_id[]",["id" =>"surgery_name_' + surgerycount + '" ,"placeholder" => "Enter Surgery Name"])<div class="invalid-feedback"></div></div><div class="col-md-5"><input type="date" class="form-control" name="surgery_date[]" id="surgerydate' + surgerycount + '"placeholder ="Enter Imaging"><div class="invalid-feedback"></div></div><i class="col-md-1 remove-icons i-Remove float-right mb-3" id="remove_imaging_' + surgerycount + '" title="Remove Surgery"></i></div>');
            $("form[name='" + formName + "'] #surgery_date" + surgerycount).val('');
        }

        var columns = [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'fname',name: 'fname',
                            mRender: function(data, type, full, meta){
                                if(data!='' && data!='NULL' && data!=undefined){
                                    if(full['mname'] == null){
                                        return full['fname']+' '+full['lname'];
                                    }else{
                                        return full['fname']+' '+full['mname']+' '+full['lname'];
                                    }

                                }
                            },
                            orderable: false
                        },
                        {data: 'dob', type: 'date-dd-mmm-yyyy', name: 'dob',
                             "render":function (value) {
                            if (value === null) return "";
                             return moment(value).format('MM-DD-YYYY');
                            } 
                        },
                        {data: 'email', name: 'email'},
                        {data: 'mob', name: 'mob'},
                        {data: 'name',name:'name',
                            mRender: function(data, type, full, meta){
                                if(data!='' && data!='NULL' && data!=undefined){
                                        return full['name'];
                                    }
                                       return 'Practice Test';
                                }
                        },
                       
                        {data: 'total_ques_resp', name:'total_ques_resp',
                            mRender: function(data, type, full, meta){
                                if(data!='' && data!='NULL' && data!=undefined){
                                        return full['total_ques_resp'];
                                    }
                                       return '2';
                                }
                        },
                        {data: 'pending_ques_resp', name:'pending_ques_resp',
                            mRender: function(data, type, full, meta){
                                if(data!='' && data!='NULL' && data!=undefined){
                                        return full['pending_ques_resp'];
                                    }
                                       return '5';
                                }
                        },
                        {data: 'add', name: 'add', orderable: false, searchable: false},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ]
        var table = util.renderDataTable('patient-list', "{{ route('patients_list') }}", columns, "{{ asset('') }}");  
        $(document).ready(function() {
            $("#addPatient").click(function() {
            $("#patient_registration_form")[0].reset(),
            $(".invalid-feedback").text(""),
            $(".form-control").removeClass("is-invalid"),
            $("#add_patient_modal").modal("show")
            });
            $('#country_code option:contains(United States (US) +1)').attr('selected', 'selected');
            $('body').on('click', '.editPatient', function () {
                var id = $(this).data('id');
                // alert(id);
                $("#patient_id").val(id);
                // $(".invalid-feedback").text("");
                // $(".form-control").removeClass("is-invalid");
                // //	alert(id);	
                $('#edit_patient_modal').modal('show');
                // var url = URL_POPULATE + "/" + id + "/populate";
                // populateForm(id, url);
            });



            $("#surgeriesPatientForm").on('submit', function(e) {
                e.preventDefault(); // Prevent the form from submitting via the browser
                $.ajax({
                    type: 'POST',
                    url: "{{ route('save.patient.surgeryData') }}",
                    data: new FormData(this),
                    contentType: false, 
                    processData: false,
                    success: function(response) { 
                        if (response.success) {
                            $('#edit_patient_modal').modal('hide');
                            table.ajax.reload(); // Reload the DataTable to reflect new data
                            toastr.success('Patient surgeries data added successfully.');
                        } else { 
                            toastr.error('Failed to add patient. Please try again.');
                        }
                    },
                    error: function(error) {
                        toastr.error('Something went wrong. Please try again later.');
                    }
                });
            });
     

            $('#patient_registration_form').on('submit', function(e) {
                e.preventDefault(); // Prevent the form from submitting via the browser
                $.ajax({
                    type: 'POST',
                    url: "{{ route('ajax.patient.registration') }}",
                    data: new FormData(this),
                    contentType: false, 
                    processData: false,
                    success: function(response) { 
                        if (response.success) {
                            $('#add_patient_modal').modal('hide');
                            table.ajax.reload(); // Reload the DataTable to reflect new data
                            toastr.success('Patient added successfully.');
                        } else { 
                            toastr.error('Failed to add patient. Please try again.');
                        }
                    },
                    error: function(error) {
                        toastr.error('Something went wrong. Please try again later.');
                    }
                });
            });
        }); 
    </script>
@endsection