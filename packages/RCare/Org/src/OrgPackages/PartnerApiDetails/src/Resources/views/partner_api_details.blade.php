@extends('Theme::layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">

@endsection

@section('main-content')
<div class="breadcrusmb">

  <div class="row">
    <div class="col-md-11">
     <h4 class="card-title mb-3">Partner API Details</h4>
   </div>
   <div class="col-md-1">
     <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addPartnerapidetails"> Add PartnerDetails</a>    
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
        <table id="PartnerApiList" class="display table table-striped table-bordered" style="width:100%">
          <thead>
            <tr>
              <th width="45px">Sr No.</th>
              <th width="60px">Tag Name</th>      
              <th width="100px">Path</th>  
               <th width="50px">Request</th>  
               <th width="50px">Response</th>
               <th width="50px">Method</th>  
               <th width="50px">Description</th>
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

<!-- Add Partner api details -->

<div class="modal fade" id="add_partnerapidetails_modal" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title" id="modelHeading1">Add Partner Api Details</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
         <div class="modal-body">
            <form action="{{ route('create-partner-api') }}" method="POST" name="AddPartnerApiDetailsForm" id="AddPartnerApiDetailsForm">
               {{ csrf_field() }}
               <div class="form-group">
                  <div class="row">
                     
                  <div class="col-md-12  form-group mb-3">            
                        <label for="tag">Tag Name <span class='error'>*</span></label>
                        @text("tag_name", ["placeholder" => "Enter tag name"])
                     </div> 

                     <div class="col-md-12  form-group mb-3">            
                        <label for="path">Path Name <span class='error'>*</span></label>
                        @text("path", ["placeholder" => "Enter path name"])
                     </div> 

                     <div class="col-md-12  form-group mb-3">            
                        <label for="request">Request<span class='error'>*</span></label>
                        @text("api_request", ["placeholder" => "Enter Name"])
                     </div>
                     
                     <div class="col-md-12  form-group mb-3">            
                        <label for="response">Response<span class='error'>*</span></label>
                        @text("api_response", ["placeholder" => "Enter response"])
                     </div>

                     <div class="col-md-12  form-group mb-3">            
                        <label for="method">Method<span class='error'>*</span></label>
                        @text("method", ["placeholder" => "Enter Method"])
                     </div>
                     
                      <div class="col-md-12 form-group mb-3">
                        <label for="description">Description<span class="error">*</span></label>
                        @text("description", ["placeholder" => "Enter Description"])
                      </div>  
                        
                  </div>                       
               </div>
               <div class="card-footer">
                  <div class="mc-footer">
                     <div class="row">
                        <div class="col-lg-12 text-right">
                           <button type="submit"  class="btn  btn-primary m-1">Add PartnerApiDetails</button>
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


<!--edit partner api details-->

<div class="modal fade" id="edit_partnerapidetails_modal" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title" id="modelHeading1">Edit Partner Api Details</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
         <div class="modal-body">
            <form action="{{ route('update-partner-api') }}" method="POST" name="EditPartnerApiDetailsForm" id="EditPartnerApiDetailsForm">
               {{ csrf_field() }}
               <div class="form-group">
                  <div class="row">
                     
                  <input type="hidden" name="id"/>
                  <div class="col-md-12  form-group mb-3">            
                        <label for="tag">Tag Name <span class='error'>*</span></label>
                        @text("tag_name", ["placeholder" => "Enter tag name"])
                     </div> 

                     <div class="col-md-12  form-group mb-3">            
                        <label for="path">Path Name <span class='error'>*</span></label>
                        @text("path", ["placeholder" => "Enter path name"])
                     </div> 

                     <div class="col-md-12  form-group mb-3">            
                        <label for="request">Request<span class='error'>*</span></label>
                        @text("api_request", ["placeholder" => "Enter Name"])
                     </div>
                     
                     <div class="col-md-12  form-group mb-3">            
                        <label for="response">Response<span class='error'>*</span></label>
                        @text("api_response", ["placeholder" => "Enter response"])
                     </div>

                     <div class="col-md-12  form-group mb-3">            
                        <label for="method">Method<span class='error'>*</span></label>
                        @text("method", ["placeholder" => "Enter Method"])
                     </div>
                     
                      <div class="col-md-12 form-group mb-3">
                        <label for="description">Description<span class="error">*</span></label>
                        @text("description", ["placeholder" => "Enter Description"])
                      </div>  
                        
                  </div>                       
               </div>
               <div class="card-footer">
                  <div class="mc-footer">
                     <div class="row">
                        <div class="col-lg-12 text-right">
                           <button type="submit"  class="btn  btn-primary m-1">Update</button>
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

  var renderPartnerApiDetailsTable =  function() {
    
  var columns= [
    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
    {data: 'tag_name', name: 'tag_name'},
    {data: 'path', name: 'path'},
    {data: 'api_request', name: 'api_request'},
    {data: 'api_response', name: 'api_response'},
    {data: 'method', name: 'method'},
    {data: 'description', name: 'description'},
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
  var table = util.renderDataTable('PartnerApiList', "{{ route('partner_api_list') }}", columns, "{{ asset('') }}");   
 };


 
$(document).ready(function() {

   partnerapidetails.init();
   renderPartnerApiDetailsTable();
   util.getToDoListData(0, {{getPageModuleName()}});


});

</script>
@endsection