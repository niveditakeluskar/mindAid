@extends('Theme::layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">

@endsection

@section('main-content')

<div class="breadcrusmb">

  <div class="row">
    <div class="col-md-11">
     <h4 class="card-title mb-3">Medications</h4>
   </div>
   <div class="col-md-1">
     <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addMedication"> Add Medication</a>  
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
        <table id="MedicationList" class="display table table-striped table-bordered" style="width:100%">
          <thead>
            <tr>
              <th width="45px">Sr No.</th>
              <th width="235px">Medication</th>      
              <th width="50px">Drug Reaction</th>  
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

<!-- Add medication -->
<div class="modal fade" id="add_medication_modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
                <h4 class="modal-title" id="modelHeading1">Add medication</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
    
      <div class="modal-body">
      <form action="{{ route('create_org_medication') }}" method="POST" name="AddMedicationForm" id="AddMedicationForm">
         {{ csrf_field() }}

          <div class="form-group">
                        <div class="row">
                    <div class="col-md-12  form-group mb-3">            
                     <label for="drug_name">Drug <span class='error'>*</span></label>
                     @text("description", ["placeholder" => "Enter Drug"])
                   </div>
                    <div class="col-md-12  form-group mb-3">            
                     <label for="drug_reaction">Drug Reaction</label>
                     <!-- <span class='error'>*</span> -->
                     @text("drug_reaction",["placeholder" => "Enter Drug Reaction"])
                   </div>     
                  </div>
                       </div>    
      <div class="card-footer">
                <div class="mc-footer">
                    <div class="row">
                        <div class="col-lg-12 text-right">
                            <button type="submit"  class="btn  btn-primary m-1">Add Medication</button>
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
<div class="modal fade" id="edit_medication_modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
                <h4 class="modal-title" id="modelHeading1">Edit medication</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
    
      <div class="modal-body">
      <form action="{{ route('update_org_medication') }}" method="POST" name="editMedicationForm" id="editMedicationForm">
         {{ csrf_field() }}
       <input type="hidden" name="id" id="id">
          <div class="form-group">
                        <div class="row">
                    <div class="col-md-12  form-group mb-3">            
                     <label for="drug_name">Drug<span class='error'>*</span></label>
                     @text("description", ["placeholder" => "Enter Drug"])
                   </div>
                    <div class="col-md-12  form-group mb-3">            
                     <label for="drug_reaction">Drug Reaction</label>
                     <!-- <span class='error'>*</span> -->
                     @text("drug_reaction",["placeholder" => "Enter Drug Reaction"])
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
<script type="text/javascript">
 var renderMedicationTable =  function() {
  var columns= [
    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
    {data: 'description', name: 'description'},
    {data: 'drug_reaction', name: 'drug_reaction'},
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
  var table = util.renderDataTable('MedicationList', "{{ route('org_medication_list') }}", columns, "{{ asset('') }}");   
 };


$(document).ready(function() {
  medication.init();
  renderMedicationTable();
  util.getToDoListData(0, {{getPageModuleName()}});
});
</script>

  

@endsection

