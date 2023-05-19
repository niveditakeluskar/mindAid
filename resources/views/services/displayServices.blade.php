@extends('layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection
<body>
@section('main-content')
<div class="container">
    <h1>Service Management</h1>
    <div class="row">
        <div class="col-md-1">
            <a class="btn btn-success btn-sm" href="javascript:void(0)" id="addService"> Add Service</a>
        </div>
    </div>
    <br>
    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
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
       
                <form action="{{ route('updateServices')}}" method="POST" id="serviceForm" name="serviceForm" class="form-horizontal">
                {{ csrf_field() }}
                <input type="hidden" name="service_id" id="service_id">
                    <div class="form-group">
                        <label for="name" class="col-sm-4 control-label">Service Name</label>
                        @text("service_name", ["id" => "service_name", "class" => "form-control form-control-rounded"])
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
               <form action="{{ route('createServices') }}" method="POST">
                                 {{ csrf_field() }}
                                 <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 form-group mb-3">
                                            <label for="service_name">Service name</label>
                                            @text("service_name", ["id" => "service_name", "class" => "form-control form-control-rounded", "placeholder" => "Enter service name"])
                                        </div>

                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary">Add Service</button>
                                        </div>
                                    </div>
                                </div>
                          
                        
                </form>
            </div>
        </div>
    </div>
</div>   
</body>
@endsection 
@section('page-js')  
    
    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>

    <script type="text/javascript">
    $(function () {
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('fetchServices') }}",
            columns: [  
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'service_name', name: 'service_name'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        $('body').on('click', '.editServices', function () {
        var id = $(this).data('id');
        $.get("/ajax/rCare/editServices" +'/' + id +'/edit', function (data) {
            $('#modelHeading').html("Edit Product");
            $('#saveBtn').val("edit-user");
            $('#ajaxModel').modal('show');
            $('#service_id').val(data.id);
            $('#service_name').val(data.service_name);
        })
    });

    $('body').on('click', '.deleteServices', function () {
        
        var service_id = $(this).data("id");
        confirm("Are You sure want to delete !");
    
        $.ajax({
            type: "POST",
            url: "/ajax/rCare/deleteServices"+'/'+service_id+'/delete',
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
        
    $('#addService ').click(function () {
            $('#saveBtn').val("create-product");
            $('#product_id').val('');
            $('#productForm').trigger("reset");
            $('#modelHeading1').html("Add User");
            $('#ajaxModel1').modal('show');
        });
    });
    </script>
@endsection