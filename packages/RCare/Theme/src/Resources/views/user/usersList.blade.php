@extends('Theme::layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
<div class="breadcrusmb">

  <div class="row">
                <div class="col-md-11">
                   <h4 class="card-title mb-3">Users</h4>
                </div>
                 <div class="col-md-1">
              <a class="btn btn-success btn-sm" href="javascript:void(0)" id="addUser"> Add User</a>
                </div>
              </div>
   <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>
			
 <div class="row mb-4">
	<div class="col-md-12 mb-4">
		<div class="card text-left">			
			<div class="card-body">	
                @include('Theme::layouts.flash-message')			
				<div class="table-responsive">
					<table id="usersList" class="display table table-striped table-bordered" style="width:100%">
					<thead>
						<tr>
							<th>No</th>
							<th>First Name</th>
							<th>Last Name</th>
							<th>Email</th>							
							<th width="200px">Action</th>
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
                <form action="{{ route('updateUser') }}" method="POST" id="userForm" name="userForm" class="form-horizontal">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" id="id">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="username">First name</label>
                                @text("f_name", ["id" => "f_name", "class" => "form-control form-control-rounded"])
                                <span class="text-danger">{{ $errors->first('f_name') }}</span>
                           
                                <label for="username">Last name</label>
                                @text("l_name", ["id" => "l_name", "class" => "form-control form-control-rounded"])
                                <span class="text-danger">{{ $errors->first('l_name') }}</span>

                                <label for="email">Email address</label>
                                @email("email", ["id" => "email", "class" => "form-control form-control-rounded"])
                                <span class="text-danger">{{ $errors->first('email') }}</span>

                                <label for="Status">Status</label>
                                @selectactivestatus("status",["id" => "status", "class" => "form-control form-control-rounded"])

                            </div>
                        </div>
                    </div>
                  
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary btn-block btn-rounded mt-3" id="saveBtn" value="create">Save changes</button>
                            </div>
                            <div class="col-md-6">
                              <button class="btn btn-primary btn-block btn-rounded mt-3">Cancel</button>
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
                    <form action="{{ route('createUsers') }}" method="POST">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="username">First name</label>
                                    @text("f_name", ["id" => "fname", "class" => "form-control form-control-rounded"])
                                    <span class="text-danger">{{ $errors->first('f_name') }}</span>

                                    <label for="username">Last name</label>
                                    @text("l_name", ["id" => "lname", "class" => "form-control form-control-rounded"])
                                    <span class="text-danger">{{ $errors->first('l_name') }}</span>

                                    <label for="email">Email address</label>
                                    @email("email", ["id" => "email", "class" => "form-control form-control-rounded"])
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="password">Password</label>
                                    @password("password", ["id" => "password", "class" => "form-control form-control-rounded"])
                                    <span class="text-danger">{{ $errors->first('password') }}</span>

                                    <label for="repassword">Confim password</label>
                                    @password("password_confirmation", ["id" => "repassword", "class" => "form-control form-control-rounded"])
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary btn-block btn-rounded mt-3">Add</button>
                                </div>
                                <div class="col-md-6">
                                    <button class="btn btn-primary btn-block btn-rounded mt-3">Cancel</button>
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
        $(function () {
            var table = $('#usersList').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('userList') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'f_name', name: 'f_name'},
                    {data: 'l_name', name: 'l_name'},
                    {data: 'email', name: 'email'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            $('body').on('click', '.edit', function () {
                var id = $(this).data('id');
                $.get("/ajax/rCare/editUser" +'/' + id +'/edit', function (data) {
                    $('#modelHeading').html("Edit Product");
                    $('#saveBtn').val("edit-user");
                    $('#ajaxModel').modal('show');
                    $('#id').val(data.id);
                    $('#l_name').val(data.l_name);
                    $('#f_name').val(data.f_name);
                    $('#email').val(data.email);
                    $('#status').val(data.status);
                })
            });

            $('#addUser').click(function () {
                $('#saveBtn').val("create-product");
                $('#product_id').val('');
                $('#productForm').trigger("reset");
                $('#modelHeading1').html("Add User");
                $('#ajaxModel1').modal('show');
            });


            /*  $('body').on('click', '.deleteUser', function () {

            var id = $(this).data("id");
            confirm("Are You sure want to delete !");

            $.ajax({
            type: "POST",
            url: "/ajax/rCare/deleteUser"+'/'+id+'/delete',
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
            });*/

        });

    </script>

@endsection

 
