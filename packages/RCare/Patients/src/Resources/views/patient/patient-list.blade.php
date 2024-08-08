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
         <a class="" href="javascript:void(0)" id="addMedication"><i class="add-icons i-Administrator" data-toggle="tooltip" data-placement="top" title="Add Patient"></i>
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
                            <th>practice</th>
                            <th>Surgery Date</th>
                            <th>Total Questionnaire Responses</th>
                            <th>Pending Questionnaire Responses</th>
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
<div class="modal fade" id="add_medication_modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
                <h4 class="modal-title" id="modelHeading1">Add patient</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <form action="{{ route('create_org_medication') }}" method="POST" name="AddMedicationForm" id="AddMedicationForm">
         {{ csrf_field() }}
        <div class="modal-body">
            <div class="form-group">
                <div class="row">
                    <div class="col-md-12 form-group mb-3">
                        <label for="practicename">Practice Name</label>
                        @selectpracticespcp("practices", ["id" => "practices"]) 
                        <!-- selectpractices -->
                    </div>
                    <div class="col-md-4 form-group mb-3" >
                        <label for="username"><span class="error">*</span> First Name</label>
                        @text("f_name", ["id" => "txtName", "class" => "capital-first "])
                    </div>
                    <div class="col-md-4 form-group mb-3">
                        <label for="username"><span class="error">*</span> Last Name</label>
                        @text("m_name", ["id" => "m_name","class" => "capital-first"])
                    </div>
                    <div class="col-md-4 form-group mb-3">
                        <label for="username"><span class="error">*</span> Last Name</label>
                        @text("l_name", ["id" => "l_name","class" => "capital-first"])
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group mb-3" >
                        <label>Date of Birth</label>
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
                        @text("state", ["id" => "state", "class" => "capitalize"])
                    </div>
                    <div class="col-6 form-group mb-3">
                        <label for="zipcode">zipcode</label>
                        @text("Zipcode", ["id" => "zipcode", "class" => "capitalize"])
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label for="email"><span class="error">*</span> Email Address</label>
                        @email("email", ["id" => "email", "class" => " "])
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 form-group mb-3">
                        <label>Country Code</label>
                        @selectcountrycode("country_code", ["id" => "country_code"]) 
                    </div> 
                    <div class="col-md-4 form-group mb-3">
                        <label for="password"><span class="error">*</span> Contact Number (MFA)</label>
                            @phone("number", ["id"=> "number"])
                    </div>
                    <div class="col-md-4 form-group mb-3" >
                        <label>Surgery Date</label>
                        @date("surgery_date")
                    </div>
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
<div class="modal fade" id="edit_medication_modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
                <h4 class="modal-title" id="modelHeading1">Edit Surgery</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
    
      <div class="modal-body">
      <form action="{{ route('update_org_medication') }}" method="POST" name="editMedicationForm" id="editMedicationForm">
         {{ csrf_field() }}
       <input type="hidden" name="id" id="id"> 
          <div class="form-group">
          <div class="row">
            <div class="col-md-12  form-group mb-3">            
                <label for="code">Code <span class='error'>*</span></label>
                @text("code", ["placeholder" => "Enter Code"])
              </div>
              <div class="col-md-12  form-group mb-3">            
                <label for="name">Name</label>
                <!-- <span class='error'>*</span> -->
                @text("name",["placeholder" => "Enter Surgery Name"])
              </div>
              <div class="col-md-12  form-group mb-3">            
                <label for="description">Description <span class='error'>*</span></label>
                @text("description", ["placeholder" => "Enter Description"])
              </div>
              
              <div class="col-md-12  form-group mb-3">            
                <label for="category">Category <span class='error'>*</span></label>
                @text("category", ["placeholder" => "Enter Category"])
              </div>
              
              <div class="col-md-12  form-group mb-3">            
                <label for="sub_category">Sub category <span class='error'>*</span></label>
                @text("sub_category", ["placeholder" => "Enter Sub category"])
              </div>
              <div class="col-md-12  form-group mb-3">            
                <label for="duration">Duration</label>
                <!-- <span class='error'>*</span> -->
                @text("duration",["placeholder" => "Enter Duration"])
              </div>     
            </div>
          </div>
          </div>    
          <div class="card-footer">
              <div class="mc-footer">
                  <div class="row">
                      <div class="col-lg-12 text-right">
                          <button type="submit"  class="btn  btn-primary m-1">Update Medication</button>
                          <button type="button" class="btn btn-outline-secondary m-1" data-dismiss="modal">Cancel</button>
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
        var columns = [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'fname',name: 'fname',
                            mRender: function(data, type, full, meta){
                                if(data!='' && data!='NULL' && data!=undefined){
                                    if(full['mname']== null){
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
                        {data: 'surgery_date', type: 'date-dd-mmm-yyyy', name:'surgery_date',
                             "render":function (value) {
                            if (value === null) return "08-25-2024";
                             return moment(value).format('MM-DD-YYYY');
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
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ]
        var table = util.renderDataTable('patient-list', "{{ route('patients_list') }}", columns, "{{ asset('') }}");  
        $(document).ready(function() {

        });
    </script>
@endsection