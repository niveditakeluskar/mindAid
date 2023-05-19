@extends('Theme::layouts.master')
@section('page-css')
<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection
@section('main-content')
<div class="breadcrusmb">
   <div class="row">
      <div class="col-md-11">
         <h4 class="card-title mb-3">RPM Vitals Protocol</h4>
      </div>
      <div class="col-md-1">
         <a class="" href="javascript:void(0)" id="UploadDocument"><i class="add-icons i-Administrator" data-toggle="tooltip" data-placement="top" title="Upload Document"></i><i class="plus-icons i-Add" data-toggle="tooltip" data-placement="top" title="Upload Document"></i></a>  
      </div>
   </div>
   <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>
<div id="msg"></div>
<div class="alert alert-success alert-block" style="display: none">
   <button type="button" class="close" data-dismiss="alert">×</button>
   <strong>You have successfully upload file.</strong>
</div>
<div class="row mb-4">
   <div class="col-md-12 mb-4">
      <div class="card text-left">
         <div class="card-body">
            <!--  <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>    -->            
            @include('Theme::layouts.flash-message')
            <div class="table-responsive">
               <table id="vitals-document-list" class="display table table-striped table-bordered" style="width:100%">
                  <thead>
                     <tr>
                        <th width="30px">Sr No.</th>
                        <th width="50px">Device</th>
                        <th width="80px">Document Name</th>
                        <th width="80px">Last Modified By</th>
                        <th width="80px">Last Modified On</th>
                        <th width="80px">Action</th>
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
<div class="modal fade" id="rpm_protocol_modal" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title" id="modelHeading1">Upload RPM Vitals Protocol Document</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
         </div>
         <div class="modal-body">
            <form method="POST" id="rpmprotocol_form" form="rpmprotocol_form" enctype="multipart/form-data">
               {{ csrf_field() }}
               <div class="form-group">
                  <div class="row">
                     <div class="col-md-7 ">
                        <div class="form-group">
                           <label for="device">Select Device<span class="error">*</span></label>
                           @selectDevice("device",["id"=>"device"])
                        </div>
                     </div>
                  </div>
                  <br>
                  <div class="row">
                     <div class="col-md-7 form-group">
                        <!-- <label for="practice_id">Upload File<span class="error">*</label> -->
                        <!-- <input type="file" name="file" class="form-control"> -->
                        <div _ngcontent-fcp-c8="" class="custom-file">
                           <input _ngcontent-fcp-c8="" class="custom-file-input form-control" id="file"  name="file" type="file">
                           <div class="invalid-feedback"></div>
                           <label _ngcontent-fcp-c8="" aria-describedby="inputGroupFileAddon02" class="custom-file-label" for="inputGroupFile02">Choose file</label>
                        </div>
                        (.pdf )
                     </div>
                     <div class="col-md-5 progress" style="display: none">
                        <div class="progress-bar"></div>
                     </div>
                  </div>
               </div>
               <div class="card-footer">
                  <div class="mc-footer">
                     <div class="row">
                        <div class="col-lg-12 text-right">
                           <button type="button" class="btn btn-success" onclick="uploadfile()">Upload</button>
                           <button type="button" id="resetbutton" class="btn btn-primary" onclick="resetform()">Reset</button>
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
$( document ).ready(function() {  
    rpmprotocol.init();
});
</script>
@endsection

