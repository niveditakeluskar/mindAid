@extends('Theme::layouts.master')
@section('page-css')
    <link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')

<div class="breadcrusmb">

  <div class="row">
                <div class="col-md-11">
                   <h4 class="card-title mb-3">Services</h4>
                </div>
                 <div class="col-md-1">
                 <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addService"> Add Service</a>  
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
                    <table id="service-list" class="display table table-striped table-bordered capital" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Service Name</th>                  
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
     

<div class="modal fade" id="edit_service_model" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="edit_service_Heading"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{ route('updateServices')}}" method="POST" id="serviceForm" name="serviceForm" class="form-horizontal">
                {{ csrf_field() }}
                <input type="hidden" name="service_id" id="service_id">
            <div class="form-group">
                <div class="row">

                    <div class="col-md-12  form-group mb-3">
                            <label for="service_name">Service name</label>
                                @text("service_name", ["id" => "service_name", "class" => "form-control form-control"])   
                               
                            </div>
                    
                    </div>
                </div>
                  
                   
                    <div class="card-footer">
                        <div class="mc-footer">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit" class="btn  btn-primary m-1" id="saveBtn" >Save changes</button>
                                    <button type="button" class="btn btn-outline-secondary m-1" data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                   <!--  <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                            <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes
                            </button>
                            </div>
                              
                    </div>
                </div> -->
    
                </form>
            </div>
        </div>
    </div>
</div>
  

  <div class="modal fade" id="add_service_model" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="service_model"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
            <form action="{{ route('create-services') }}" method="POST">
                 {{ csrf_field() }}
                <div class="form-group">
                    <div class="row">

                        <div class="col-md-12  form-group mb-3">
                            <label for="service_name">Service name</label>
                                @text("service_name", ["id" => "service_name", "class" => "form-control form-control", "placeholder" => "Enter service name"])   
                               
                            </div>
                       
                    </div>
                </div>
               
               
                <!-- <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                         <button type="submit" class="btn btn-primary btn-block btn-rounded mt-3">Add Service</button>
                        </div>
                         
                </div>
            </div> -->
              <div class="card-footer">
                        <div class="mc-footer">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit" class="btn  btn-primary m-1">Add Service</button>
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

        var columns = [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'service_name', name: 'service_name'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
    var table = util.renderDataTable('service-list', "{{ route('fetch_services') }}", columns, "{{ asset('') }}");  
          
    

        $('body').on('click', '.editServices', function () {
        var id = $(this).data('id');
        $.get("/ajax/rCare/editServices" +'/' + id +'/edit', function (data) {
            $('#edit_service_Heading').html("Edit Service");
            $('#saveBtn').val("edit-user");
            $('#edit_service_model').modal('show');
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
            $('#service_model').html("Add Service");
            $('#add_service_model').modal('show');
        });
    
    </script>
@endsection

