@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">


@endsection

@section('main-content')

<div class="breadcrusmb">

  <div class="row">
                <div class="col-md-11">
                   <h4 class="card-title mb-3">Role</h4>
                </div>
                 <div class="col-md-1">
                 <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>  
                </div>
              </div>
   <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>

 <div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
           <!--  <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>    -->            
                @include('layouts.flash-message')
                <div class="table-responsive">
                    <table id="usersRolesList" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Roles Name</th>                  
                            <th width="250px">Action</th>
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
     

<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
                <form action="{{ route('updateUserRole') }}" method="POST" id="usersroleForm" name="usersroleForm" class="form-horizontal">
                {{ csrf_field() }}
                <input type="hidden" name="id" id="id">
            <div class="form-group">
                <div class="row">

                    <div class="col-md-12">
                      <label for="role_name">Role Name</label>
                      <input id="role_name" name ="role_name" class="form-control form-control-rounded" type="text"> 
                       <span class="text-danger">{{ $errors->first('role_name') }}</span>
                   


                </div>
                    
                    </div>
                </div>
                  
                   
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                             <button type="submit" class="btn btn-primary btn-block btn-rounded mt-3" id="saveBtn" value="create">Save changes
                            </button>
                            </div>
                              
                    </div>
                </div>
    
                </form>
            </div>
        </div>
    </div>
</div>
  

  <div class="modal fade" id="ajaxModel1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading1"></h4>
            </div>
            <div class="modal-body">
               <form action="{{ route('UsersrolesCreate') }}" method="POST">
                 {{ csrf_field() }}
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-12">
                          <label for="rolename">Role Name</label>
                          <input id="role_name" name ="role_name" class="form-control form-control-rounded" type="text"> 
                           <span class="text-danger">{{ $errors->first('role_name') }}</span>
                       
                            
                        
                        </div>
                    </div>
                </div>
               
               
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                         <button type="submit" class="btn btn-primary btn-block btn-rounded mt-3">Add</button>
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

 <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    
    <script type="text/javascript">
  $(function () {
      var table = $('#usersRolesList').DataTable({
        processing: true,
        serverSide: true,

        ajax: "{{ route('usersRolesList') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'role_name', name: 'role_name'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

      $('body').on('click', '.editroles', function () {
      var id = $(this).data('id');
      $.get("/ajax/rCare/edituserRoles" +'/' +id+'/edit', function (data) {
          $('#modelHeading').html("Edit Roles");
          $('#saveBtn').val("edit-user");
          $('#ajaxModel').modal('show');
           $('#id').val(data.id);
          $('#role_name').val(data.role_name);
          
        
          
      })
   });

      $('#addUser').click(function () {
        $('#modelHeading1').html("Add Role");
        $('#saveBtn').val("create-product");
        $('#product_id').val('');
        $('#productForm').trigger("reset");
       // $('#modelHeading').html("Add Role");
        $('#ajaxModel1').modal('show');
    });


    /*  $('body').on('click', '.deleteRoles', function () {
     
     var id = $(this).data("id");
     confirm("Are You sure want to delete !");
   
     $.ajax({
         type: "POST",
         url: "/ajax/rCare/deleteRole"+'/'+ id +'/delete',
         data: {
        "_token": "{{ csrf_token() }}"
        },
         success: function (data) {
             table.draw();
         },
         error: function (data) {
             console.log('Error:', data);
         }
     });
 });
    */
 


  });

</script> 

@endsection

 