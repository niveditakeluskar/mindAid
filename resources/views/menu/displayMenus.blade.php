@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection
@section('main-content')
<div class="container">
    <h1>Menu Management</h1>
    <div class="row">
        <div class="col-md-1">
            <a class="btn btn-success btn-sm" href="javascript:void(0)" id="addMenu"> Add Menu</a>
        </div>
    </div>
    <br>
    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Menu Name</th>
                <th>Menu URL</th>
                <th>Service</th>
                <th width="100px">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
       
                <form action="{{ route('updateMenu')}}" method="POST" id="menuForm" name="menuForm" class="form-horizontal">
                {{ csrf_field() }}
                <input type="hidden" name="menu_id" id="menu_id">
                    <div class="form-group">
                        <label for="name" class="col-sm-4 control-label">Menu Name</label>
                        <div class="col-sm-6">
                            @text("menu", ["id" => "menu", "class" => "form-control form-control-rounded", "placeholder" => "Enter Menu name"])
                        </div>
                        <label for="name" class="col-sm-4 control-label">Menu URL</label>
                        <div class="col-sm-6">
                            @text("menu_url", ["id" => "menu_url", "class" => "form-control form-control-rounded", "placeholder" => "Enter Menu URL"])
                        </div>
                        <label for="name" class="col-sm-4 control-label">Services</label>
                        <div class="col-md-6">
                            @selectservices("services", ["id" => "services", "class" => "form-control form-control-rounded"])
                        </div>
        
                    </div>
      
                    <div class="col-sm-offset-2 col-sm-10">
                     <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes
                     </button>
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
               <form action="{{ route('createMenu') }}" method="POST">
                                 {{ csrf_field() }}
                                 <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 form-group mb-3">
                                            <label for="service_name">Menu name</label>
                                            @text("menu", ["id" => "menu", "class" => "form-control form-control-rounded", "placeholder" => "Enter Menu name"])
                                    
                                        </div>

                                        <div class="col-md-6 form-group mb-3">
                                            <label for="service_name">Menu URL</label>
                                            @text("menu_url", ["id" => "menu_url", "class" => "form-control form-control-rounded", "placeholder" => "Enter Menu URL"])
                                        </div>

                                    
                                        <div class="form-group row">
                                            <label for="loginuser" class="col-md-4 col-form-label text-md-right">Services</label>
                                            <div class="col-md-6">
                                            @selectservices("services", ["id" => "services", "class" => "form-control form-control-rounded"])
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary">Add Menu</button>
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
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('fetchMenu') }}",
                columns: [   
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'menu', name: 'menu'},
                    {data: 'menu_url', name: 'menu_url'},
                    {data: 'service_id', name: 'service_id'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });

            $('body').on('click', '.editMenu', function () {
            var id = $(this).data('id');
            $.get("/ajax/rCare/editMenu" +'/' + id +'/edit', function (data) {
                $('#modelHeading').html("Edit Menu");
                $('#saveBtn').val("edit-user");
                $('#ajaxModel').modal('show');
                $('#menu_id').val(data.id);
                $('#menu').val(data.menu);
                $('#menu_url').val(data.menu_url);
                $('#service_id').val(data.service_id);

            })
        });

        $('body').on('click', '.deleteMenu', function () {
            
            var menu_id = $(this).data("id");
            confirm("Are You sure want to delete !");
        
            $.ajax({
                type: "POST",
                url: "/ajax/rCare/deleteMenu"+'/'+menu_id+'/delete',
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

        $('#addMenu ').click(function () {
                $('#saveBtn').val("create-product");
                $('#product_id').val('');
                $('#productForm').trigger("reset");
                $('#modelHeading1').html("Add User");
                $('#ajaxModel1').modal('show');
            });

        });
    </script>
@endsection