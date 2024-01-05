@extends('Theme::layouts.master')
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
                    @include('Theme::layouts.flash-message')	
                    <div class="table-responsive">
                        <table id="usersRolesList" class="display table table-striped table-bordered capital" style="width:100%">
                            <thead>
                                <tr>
                                    <th width="40px">Sr No.</th>
                                    <th>Roles Name</th>                  
                                    <th width="40px">Action</th>
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


    <div class="modal fade" id="edit-role" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading1">Edit Role</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('updateUserRole') }}" method="POST" id="usersroleForm" name="usersroleForm" class="form-horizontal">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12  form-group mb-3 ">
                                    <label for="rolename">Role Name</label>
                                    <input id="role_name" name ="role_name" class="form-control  " type="text" required> 
                                    <span class="text-danger">{{ $errors->first('role_name') }}</span>
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
    </div>
    <div class="modal fade" id="add-role" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modelHeading1">Add Role</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('UsersrolesCreate') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12  form-group mb-3 ">
                                    <label for="rolename">Role Name</label>
                                    <input id="role_name" name ="role_name" class="form-control  " type="text" required> 
                                    <span class="text-danger">{{ $errors->first('role_name') }}</span>
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
    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>

    <script type="text/javascript">
        $(function () {
            // var table = $('#usersRolesList').DataTable({
            //     processing: true,
            //     serverSide: true,
            //     ajax: "{{ route('usersRolesList') }}",
            //     columns: [
            //         {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            //         {data: 'role_name', name: 'role_name'},
            //         {data: 'action', name: 'action', orderable: false, searchable: false},
            //     ]
            // });

            var columns= [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'role_name', name: 'role_name'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ];
            var table = util.renderDataTable('usersRolesList', "{{ route('users_roles_list') }}", columns, "{{ asset('') }}");

            $('body').on('click', '.editroles', function () {
                var id = $(this).data('id');
                $.get("/ajax/rCare/edituserRoles" +'/' +id+'/edit', function (data) {
                    //  $('#modelHeading').html("Edit Roles");
                    $('#saveBtn').val("edit-user");
                    $('#edit-role').modal('show');
                    $('#id').val(data.id);
                    $('#role_name').val(data.role_name);
                })
            });

            $('#addUser').click(function () {
                //  $('#modelHeading1').html("Add Role");
                $('#saveBtn').val("create-product");
                $('#product_id').val('');
                $('#productForm').trigger("reset");
                // $('#modelHeading').html("Add Role");
                $('#add-role').modal('show');
            });

        //submit form ajax
            $("#role_add").click(function () {
              var role_name = $("#role_name").val();
              $.ajax({
                  type: "POST",
                  url: "{{ route('UsersrolesCreate') }}",
                  data: $('#UsersrolesCreate').serialize(),
                  success: function () {
                      alert('form was submitted');
                  }
              });
            });
        });
    </script> 
@endsection