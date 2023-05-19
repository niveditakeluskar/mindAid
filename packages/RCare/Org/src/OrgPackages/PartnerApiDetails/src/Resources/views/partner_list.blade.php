@extends('Theme::layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">

@endsection

@section('main-content')

<div class="breadcrusmb">

  <div class="row">
    <div class="col-md-11">
     <h4 class="card-title mb-3">Partner</h4>
   </div>
   <div class="col-md-1">
     <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addPartner"> Add Partner</a>  
   </div>
 </div>
 <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>
<div id="msg"></div>
<div id="success"></div>
<div class="row mb-4">
  <div class="col-md-12 mb-4">
    <div class="card text-left">
      <div class="card-body">
       <!--  <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>    -->            
       @include('Theme::layouts.flash-message')
       <div class="table-responsive">
        <table id="PartnerList" class="display table table-striped table-bordered" style="width:100%">
          <thead>
            <tr>
              <th width="45px">Sr No.</th>
              <th width="60px">Partner Name</th>      
              <th width="100px">Address</th>  
               <th width="50px">Email</th>  
               <th width="50px">Contact Person</th>  
              <th width="50px">Last Modified By</th>
              <th width="50px">Last Modified On</th>                 
              <th width="65px">Action</th>
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

<!-- Add Partner -->

<div class="modal fade" id="add_partner_modal" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title" id="modelHeading1">Add Partner</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
         <div class="modal-body">
            <form action="{{ route('create_partner') }}" method="POST" name="AddPartnerForm" id="AddPartnerForm">
               {{ csrf_field() }}
               <div class="form-group">
                  <div class="row">
                     <div class="col-md-12  form-group mb-3">            
                        <label for="drug_name">Partner Name <span class='error'>*</span></label>
                        @text("name", ["placeholder" => "Enter Name"])
                     </div>                     
                      <div class="col-md-6 form-group mb-3">
                        <label for="email"><span class="error">*</span> Email</label>
                        @email("email", ["id" => "email", "class" => " "])
                      </div>
                      <div class="col-md-6  form-group mb-3">            
                        <label for="phone">Phone<span class='error'>*</span></label>
                        @phone("phone", ["id"=> "phone"])
                     </div>
                     <div class="col-md-12  form-group mb-3">            
                        <label for="drug_reaction">Address1<span class='error'>*</span></label>
                        @text("add1",["placeholder" => "Enter Address1"])
                     </div>
                    <div class="col-md-12  form-group mb-3">            
                        <label for="add2">Address2</label>
                        @text("add2",["placeholder" => "Enter Address2"])
                     </div>
                    
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="city">City<span class="error">*</span></label>
                          @text("city", ["id" => "city", "class" => "capitalize"])
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="state">State<span class="error">*</span></label>
                          @selectstate("state", request()->input("state"))
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="zipcode">Zip Code<span class="error">*</span></label>
                          @text("zip", ["id" => "zip"])
                        </div>
                      </div>
               
                       <div class="col-md-12  form-group mb-3">            
                        <label for="phone">Contact Person<span class='error'>*</span></label>
                        @text("contact_person", ["id"=> "contact_person"])
                     </div>
                      <div class="col-md-6  form-group mb-3">            
                        <label for="contact_person_phone">Contact Person phone</label>                        
                        @phone("contact_person_phone", ["id"=> "contact_person_phone"])
                     </div>
                      <div class="col-md-6  form-group mb-3">            
                        <label for="contact_person_email">Contact Person Email</label>
                        @email("contact_person_email", ["id"=> "contact_person_email"])
                     </div>

                     <div class="col-md-5  form-group mb-3">            
                        <label for="partner_devices">Devices</label>
                        @selectdevices("devices[]", ["id"=> "devices_0"])
                     </div>

                     <div class="col-md-5  form-group mb-3">            
                        <label for="partner_device_name">Partner Devices Name</label>
                        @text("partner_device_name[]", ["id"=> "partner_device_name_0"])
                     </div>

                     <div class="col-md-1" style="margin-top:29px"> 
                        <i class="plus-icons i-Add" id="addnewdevice" name="addnewdevice"></i>
                     </div>  

                     <div class="col-md-12 pt-1" id="append_devices"></div>

                     

                       <hr>
                       <div  class="col-md-12 form-group mb-3">                             
                          <h4 class="modal-title" id="modelHeading1">Partner Api Configuration</h4>
                        </div>
                      
                       <br><br>
                     
                       <div class="col-md-6  form-group mb-3">            
                        <label for="url">Url</label>
                        @text("url[]", ["id"=> "url_0"])  
                     </div>

                     <div class="col-md-6  form-group mb-3">            
                        <label for="Username">Username</label>
                        @text("username[]", ["id"=> "username_0"])
                     </div>

                     <div class="col-md-6  form-group mb-3">            
                        <label for="password">Password</label>
                        @text("password[]", ["id"=> "password_0"])
                     </div>

                     <div class="col-md-6 form-group mb-3"> 
                        <label for="status">Status</label> 
                        <select id="status_0" name="status[]" class="custom-select show-tick" >                            
                           <option value="1" selected>Active</option>
                           <option value="0">Inactive</option>                        
                        </select>                         
                     </div>

                     <div class="col-md-6  form-group mb-3">              
                        <label for="env">Env</label>
                        <select id="env_0" name="env[]" class="custom-select show-tick" >                            
                           <option value="development" selected>Development</option>
                           <option value="staging">Staging</option>   
                           <option value="production">Production</option>                       
                        </select> 
                     </div>

                     <div class="col-md-1" style="margin-top:29px"> 
                        <i class="plus-icons i-Add" id="addnewpartnerapi" name="addnewpartnerapi"></i>
                     </div>

                     <div class="col-md-12 pt-1" id="append_partnerapi"></div>


                  </div>
                   
               </div>
               <div class="card-footer">
                  <div class="mc-footer">
                     <div class="row">
                        <div class="col-lg-12 text-right">
                           <button type="submit"  class="btn  btn-primary m-1">Add Partner</button>
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


<!-- Edit medication -->
<div class="modal fade" id="edit_partner_modal" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title" id="modelHeading1">Edit Partner</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
         <div class="modal-body">
            <form action="{{ route('update_partner') }}" method="POST" name="editPartnerForm" id="editPartnerForm">
               {{ csrf_field() }}
               <input type="hidden" name="id" id="id">
               <div class="form-group">
                  <div class="row">
                     <div class="col-md-12  form-group mb-3">            
                        <label for="drug_name">Partner Name <span class='error'>*</span></label>
                        @text("name", ["placeholder" => "Enter Name"])
                     </div>                     
                      <div class="col-md-6 form-group mb-3">
                        <label for="email"><span class="error">*</span> Email</label>
                        @email("email", ["id" => "email", "class" => " "])
                      </div>
                      <div class="col-md-6  form-group mb-3">            
                        <label for="phone">Phone<span class='error'>*</span></label>
                        @phone("phone", ["id"=> "phone"])
                     </div>
                     <div class="col-md-12  form-group mb-3">            
                        <label for="drug_reaction">Address1<span class='error'>*</span></label>
                        @text("add1",["placeholder" => "Enter Address1"])
                     </div>
                    <div class="col-md-12  form-group mb-3">            
                        <label for="add2">Address2</label>
                        @text("add2",["placeholder" => "Enter Address2"])
                     </div>
                    
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="city">City<span class="error">*</span></label>
                          @text("city", ["id" => "city", "class" => "capitalize"])
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="state">State<span class="error">*</span></label>
                          @selectstate("state", request()->input("state"))
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="zipcode">Zip Code<span class="error">*</span></label>
                          @text("zip", ["id" => "zip"])
                        </div>
                      </div>
               
                       <div class="col-md-12  form-group mb-3">            
                        <label for="phone">Contact Person<span class='error'>*</span></label>
                        @text("contact_person", ["id"=> "contact_person"])
                     </div>
                      <div class="col-md-6  form-group mb-3">            
                        <label for="contact_person_phone">Contact Person phone</label>                        
                        @phone("contact_person_phone", ["id"=> "contact_person_phone"])
                     </div>
                      <div class="col-md-6  form-group mb-3">            
                        <label for="contact_person_email">Contact Person Email</label>
                        @email("contact_person_email", ["id"=> "contact_person_email"])
                     </div>

                     <div  id="append_devicediv" style="display:none;"><br></div>


                     <div  class="row" id="editdevicediv" style="display:none">
                   
                   
                     <div class="row" id="p1" style="margin-left:10px">
                        <div class="col-md-5  form-group mb-3 ml-2" >            
                           <label for="partner_devices">Devices</label>
                           @selectdevices("devices[]", ["id"=> "editdevices"])
                        </div>

                        <div class="col-md-5 form-group mb-3 ml-3">            
                           <label for="partner_device_name">Partner Devices Name</label>
                           @text("partner_device_name[]", ["id"=> "editpartner_device_name"])
                        </div>

                       
                           <i class="plus-icons i-Add addicon" id="addicon" name="addicon" style="display:none;margin-top:35px"></i>                                                     
                           <i class="remove-icons i-Remove removeicon" id="removeicon" name="removeicon" style="margin-top:29px"></i>
                      
                        </div>  
                  

                     </div>

                     

                     
                    
                        <div  class="col-md-12 form-group mb-3">                             
                           <h4 class="modal-title" id="modelHeading1">Partner Api Configuration</h4>
                        </div>

                        <div  class="row" id="append_partnerapidiv" style="display:none;"><br></div>
                        
                        <br><br>
                  <div class="row" id="editpartnerapidiv" style="display:none;">

                     <div class="row" id="p2" style="margin-left:10px">
                        <div class="col-md-6  form-group mb-3 ml-3">            
                           <label for="url">Url</label>
                           @text("url[]", ["id"=> "editurl"])  
                        </div>

                        <div class="col-md-4 form-group mb-3 mr-3">            
                           <label for="Username">Username</label>
                           @text("username[]", ["id"=> "editusername"])
                        </div>

                        <div class="col-md-6 form-group mb-3  ml-3">            
                           <label for="password">Password</label>
                           @text("password[]", ["id"=> "editpassword"])
                        </div>

                        <div class="col-md-4 form-group mb-3  mr-3"> 
                           <label for="status">Status</label> 
                           <select id="status" name="editstatus[]" class="custom-select show-tick" >                            
                              <option value="1" selected>Active</option>
                              <option value="0">Inactive</option>                        
                           </select>                         
                        </div>

                        <div class="col-md-6  form-group mb-3  ml-3">              
                           <label for="env">Env</label>
                           <select id="editenv" name="env[]" class="custom-select show-tick" >                            
                              <option value="development" selected>Development</option>
                              <option value="staging">Staging</option>   
                              <option value="production">Production</option>                       
                           </select> 
                        </div>

                           <!-- <div class="col-md-1" style="margin-top:29px">   -->
                           <i class="plus-icons i-Add" id="addiconpartnerapi" name="addiconpartnerapi" style="display:none;margin-top:29px"></i>
                           <i class="remove-icons i-Remove" id="removeiconpartnerapi" name="removeiconpartnerapi" style="margin-top:29px" ></i>
                     </div>

                  </div>

                     
                     
                  </div>
               </div>

               <div class="card-footer">
                  <div class="mc-footer">
                     <div class="row">
                        <div class="col-lg-12 text-right">
                           <button type="submit"  class="btn  btn-primary m-1">Update Partner</button>
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
<script type="text/javascript">
 var renderPartnerTable =  function() {
  var columns= [
    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
    {data: 'name', name: 'name'},
    {data: 'add1', name: 'add1'},
    {data: 'email', name: 'email'},
    {data: 'contact_person', name: 'contact_person'},
    {data: 'users',
        mRender: function(data, type, full, meta){
        if(data!='' && data!='NULL' && data!=undefined){
          l_name = data['l_name'];
        if(data['l_name'] == null && data['f_name'] == null){
          l_name = '';
          return '';
        }
        else
        {

          return data['f_name'] + ' ' + l_name;
        }
        } else { 
            return ''; 
        }    
        },orderable: false
        }, 
    {data: 'updated_at',name:'updated_at'},
    {data: 'action', name: 'action', orderable: false, searchable: false},
    ];
  var table = util.renderDataTable('PartnerList', "{{ route('partner_list') }}", columns, "{{ asset('') }}");   
 };


$(document).ready(function() {
  partner.init();
  renderPartnerTable();
  util.getToDoListData(0, {{getPageModuleName()}});

  var parameterscnt = 1;
  var apiparametercnt = 1;
  var len = $('#editdevicediv').length;//
  len = len+1;
  var newlen = $('#editpartnerapidiv').length;
  

  $('#addnewdevice').click(function () {
	// debugger;
	parameterscnt++;
	$('#append_devices').append('<div class="btn_remove row" id="btn_removedevice_' + parameterscnt + '">\
	<div class="col-md-5 form-group mb-3">\@selectdevices("devices[]", [ "class" => "select2","id"=> "devices_'+parameterscnt+'"])</div>\
   <div class="col-md-5 form-group mb-3">\@text("partner_device_name[]", ["id"=> "partner_device_name_'+parameterscnt+'"])</div>\<div class="invalid-feedback"></div>\
	<i class="col-md-1 remove-icons i-Remove float-right mb-3" id="remove_parameter_' + parameterscnt + '" title="Remove Goals"></i>\
	<div class="error_msg" style="display:none;color:red">Please Enter device details</div></div>');  
 
		
});

$(document).on("click", ".remove-icons", function () {
   alert($(this).id);
        var button_id = $(this).closest('div').attr('id');
        alert("removebuttonid: " + button_id);
        console.log("removebuttonid: " + button_id);
        $('#' + button_id).remove();
});




$('#addnewpartnerapi').click(function () {
	// debugger;
	apiparametercnt++;
	$('#append_partnerapi').append('<div class="btn_remove row" id="btn_removepartnerapi_' + apiparametercnt + '">\<div class="col-md-6 form-group mb-3">\<label for="url">Url</label>\@text("url[]", ["id"=> "url_0"])</div>\
   <div class="col-md-6 form-group mb-3">\<label for="Username">Username</label>@text("username[]", ["id"=> "username_'+apiparametercnt+'"])</div>\<div class="col-md-6 form-group mb-3">\<label for="password">Password</label>\@text("password[]", ["id"=> "password_'+apiparametercnt+'"])</div>\<div class="col-md-6 form-group mb-3">\<label for="status">Status</label>\<select id="status_'+apiparametercnt+'" name="status[]" class="custom-select show-tick" >\<option value="1" selected>Active</option>\<option value="0">Inactive</option></select></div>\<div class="col-md-6 form-group mb-3">\<label for="env">Env</label>\<select id="env_'+apiparametercnt+'" name="env[]" class="custom-select show-tick" >\<option value="development" selected>Development</option>\<option value="staging">Staging</option>\<option value="production">Production</option>\</select>\</div>\
   <div class="invalid-feedback"></div>\<i class="col-md-1 remove-icons i-Remove float-right mt-4" id="remove_parameter_' + apiparametercnt + '" title="Remove Goals"></i>\<div class="error_msg" style="display:none;color:red">Please Enter device details</div></div>');
			
});


// $(document).on("click", "#addicon_0", function () {
//    // alert("hi");
//    len++;
//    $('#append_devicediv').append('<div class="btn_editremove row" id="btn_editremovedevice_' + len + '">\<div class="col-md-5 form-group mb-3 ml-4">\@selectdevices("editdevices[]", [ "class" => "select2","id"=> "editdevices_'+len+'"])</div>\<div class="col-md-5 form-group mb-3">\@text("editpartner_device_name[]", ["id"=> "editpartner_device_name_'+len+'"])</div>\<div class="invalid-feedback"></div>\
//    <i class="col-md-1 remove-icons i-Remove float-right mb-3" id="remove_editparameter_' + len + '" title="Remove Goals"></i>\
//    <div class="error_msg" style="display:none;color:red">Please Enter device details</div></div>');  
// });




// $(document).on("click", "#removeicon_1", function () {
   // alert("hbbhbjm");
  

// $(this).parent('div').remove();
   // $('#editpartner_device_name_1').remove();
   // // $('#editdevices_1').remove();
   // if($('#editdevices_1').hasClass("select2-hidden-accessible")){
   //          $('#editdevices_1').select2('destroy');  
   //      }

   //    //   $('label[for=Parent]').remove();
// });    

// $(document).on("click", "#addiconpartnerapi_0", function () {
//    // alert("hi");  
//    newlen++;
//    $('#append_partnerapidiv').append('<div class="btn_remove row" id="btn_editremovepartnerapi_' + newlen + '">\<div class="col-md-5 form-group mb-3 ml-4">\<label for="url">Url</label>\@text("editurl[]", ["id"=> "url_'+newlen+'"])</div>\
//    <div class="col-md-5 form-group mb-3 mr-3">\<label for="Username">Username</label>@text("editusername[]", ["id"=> "username_'+newlen+'"])</div>\<div class="col-md-5 form-group mb-3 ml-4">\<label for="password">Password</label>\@text("editpassword[]", ["id"=> "password_'+newlen+'"])</div>\<div class="col-md-5 form-group mb-3 mr-3">\<label for="status">Status</label>\<select id="status_'+newlen+'" name="status[]" class="custom-select show-tick" >\<option value="1" selected>Active</option>\<option value="0">Inactive</option></select></div>\<div class="col-md-5 form-group mb-3 ml-4">\<label for="env">Env</label>\<select id="env_'+newlen+'" name="env[]" class="custom-select show-tick" >\<option value="development" selected>Development</option>\<option value="staging">Staging</option>\<option value="production">Production</option>\</select>\</div>\
//    <div class="invalid-feedback"></div>\<i class="col-md-1 remove-icons i-Remove float-right mt-4" id="remove_parameter_' + newlen + '" title="Remove Goals"></i>\<div class="error_msg" style="display:none;color:red">Please Enter device details</div></div>');
           
// });






});    
</script>

  

@endsection

