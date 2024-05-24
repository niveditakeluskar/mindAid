@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
    <div class="breadcrusmb">
        <div class="row">
            <div class="col-md-11">
                <h4 class="card-title mb-3">Test Users</h4>
            </div>
        </div>
    </div>
    <form action="{{ route('createUsers') }}" method="POST" name="test_form">
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
                    @email("email", ["id" => "email_add", "class" => "form-control form-control-rounded"])
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
@endsection

@section('page-js')
    <script src="{{asset('assets/js/js/app.js')}}"></script>
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
                    $('#email_update').val(data.email);
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
        });

    </script>

@endsection