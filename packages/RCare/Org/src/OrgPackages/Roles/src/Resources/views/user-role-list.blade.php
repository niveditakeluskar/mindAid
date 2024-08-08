@extends('Theme::layouts_2.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">

@endsection

@section('main-content')

<div class="breadcrusmb">

  <div class="row">
    <div class="col-md-11">
     <h4 class="card-title mb-3">Roles</h4>
   </div>
   <div class="col-md-1">
     <a class="" href="javascript:void(0)" id="addRole"><i class="add-icons i-Business-Mens" data-toggle="tooltip" data-placement="top" title="Add Role"></i><i class="plus-icons i-Add" data-toggle="tooltip" data-placement="top" title="Add Role"></i></a>   
   </div>
 </div>
 <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>
<div id="success"></div> 
<div class="row mb-4">
  <div class="col-md-12 mb-4">
    <div class="card text-left">
      <div class="card-body">
       <!--  <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>    -->            
       @include('Theme::layouts.flash-message')
       <div class="table-responsive">
        <table id="usersRolesList" class="display table table-striped table-bordered" style="width:100%">
          <thead>
            <tr>
              <th>Sr No.</th>
              <th>Roles Name</th>      
              <th>Roles Level</th>  
              <th>Last Modified By</th>  
              <th>Last Modified On</th>                   
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


<div class="modal fade" id="edit_role_modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
     <div class="modal-header">
      <h4 class="modal-title" id="modelHeading1">Edit Role</h4>
      <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
      <form action="{{ route('update_org_role') }}" method="POST" id="editroleForm" name="editroleForm" class="form-horizontal">
        {{ csrf_field() }}
        <input type="hidden" name="id" id="id">
        <div class="form-group">

          <div class="row">
            <div class="col-md-12  form-group mb-3 ">
             <label for="rolename"><span class="error">*</span> Role Name</label>
             @text("role_name", ["id" => "role_name", "placeholder" => "Enter Role", "class" => "capital-first" ])
           </div>
           <div class="col-md-12  form-group mb-3 ">
             <label for="rolename"><span class="error">*</span> Role Level</label>
             @number("level", ["id" => "level", "placeholder" => "Select Level", "min" => "0"])
           </div>

         </div>

       </div>

       <div class="card-footer">
        <div class="mc-footer">
          <div class="row">
            <div class="col-lg-12 text-right">
              <button type="submit"  class="btn  btn-primary m-1">Save Changes</button>
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


<div class="modal fade" id="add_role_modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="modelHeading1">Add Role</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
       <form action="{{ route('create_org_role') }}" method="POST"  id="addUsersroleForm" name="addUsersroleForm">
         {{ csrf_field() }}

         <div class="form-group">
          <div class="row">
            <div class="col-md-12  form-group mb-3 ">
             <label for="rolename"><span class="error">*</span> Role Name</label>
             @text("role_name", ["id" => "role_name", "placeholder" => "Enter Role", "class" => "capital-first" ])
           </div>
           <div class="col-md-12  form-group mb-3 ">
             <label for="rolename"><span class="error">*</span> Role Level</label>
             @number("level", ["id" => "level", "placeholder" => "Select Level", "min" => "0" ])
           </div>     
         </div>
       </div>



       <div class="card-footer">
        <div class="mc-footer">
          <div class="row">
            <div class="col-lg-12 text-right">
              <button type="submit"  class="btn  btn-primary m-1">Add Role</button>
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
var renderRolesTable = function() {
  var columns= [
    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
    {data: 'role_name', name: 'role_name', render: function(data, type, full, meta){
      var name = "<span class='capital-first'>" + data + "</span>";
      return name
      }
    },
    {data: 'level', name: 'level'},
    {data: 'users', mRender: function(data, type, full, meta){
        if(data!='' && data!='NULL' && data!=undefined){
            if(data['l_name'] == null && data['f_name'] == null){
                return '';
            }else{
                return data['f_name'] + ' ' + data['l_name'];
            }
        }else { return '';}    
    },orderable: false},
    {data: 'updated_at', name: 'updated_at'},
    {data: 'action', name: 'action', orderable: false, searchable: false},
  ];
  var table = util.renderDataTable('usersRolesList', "{{ route('org_roles_list') }}", columns, "{{ asset('') }}");   
}

  $(document).ready(function() {
    renderRolesTable();
    roles.init();
    util.getToDoListData(0, {{getPageModuleName()}});
  });
  </script>
  @endsection

