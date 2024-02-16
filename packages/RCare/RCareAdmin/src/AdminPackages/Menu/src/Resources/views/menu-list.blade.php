@extends('Theme::layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">


@endsection

@section('main-content')

<div class="breadcrusmb">

  <div class="row">
                <div class="col-md-11">
                   <h4 class="card-title mb-3">Menus</h4>
                </div>
                 <div class="col-md-1">
                 <a class=" " href="javascript:void(0)" id="addMenu"> <i class="add-icons i-Tag-4" data-toggle="tooltip" data-placement="top" title="Add Menu"></i><i class="plus-icons i-Add" data-toggle="tooltip" data-placement="top" title="Add Menu"></i></a> 
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
                    <table id="menu_list" class="display table table-striped table-bordered capital" style="width:100%">
                    <thead>
                        <tr>
                        <th>No</th>
                        <th>Menu Name</th>
                        <th>Menu URL</th>
                        <th>Service</th>
                        <th>Icon</th>
                        <th>Parent</th>
                        <th>Sequence</th>
                        <th>Operation</th>
                        <th width="100px">Action</th>
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
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('update_menu')}}" method="POST" id="menuForm" name="menuForm" class="form-horizontal">
            <div class="modal-body">
       
                {{ csrf_field() }}
                <input type="hidden" name="menu_id" id="menu_id">
                    <div class="form-group">
                    <div class="row">    
                        
                        <div class="col-md-6 form-group mb-3">
                             <label for="name" class="control-label">Menu Name</label>
                            @text("menu", ["id" => "menu", "class" => "form-control  ", "placeholder" => "Enter Menu name"])
                        </div>
                        
                        <div class="col-md-6 form-group mb-3">
                            <label for="name" class="control-label">Menu URL</label>
                            @text("menu_url", ["id" => "menu_url", "class" => "form-control  ", "placeholder" => "Enter Menu URL"])
                        </div>
                        
                        <div class="col-md-6 form-group mb-3">
                        <label for="name" class="control-label">Services</label>
                        <select name="service_id" id="service_id" class="form-control  ">
                            @foreach($service as $services)   
                                <option value="{{ $services->id }}">{{ $services->service_name }}</option>    
                            @endforeach
                        </select>
                         </div>
                         <div class="col-md-6 form-group mb-3">
                        <label for="name" class="control-label">Parent</label>
                        <select name="parent" id="parent" class="form-control  ">
                            <option value="0">None</option>
                            @foreach($menus as $value)   
                                <option value="{{ $value->id }}">{{ $value->menu }}</option>    
                            @endforeach
                        </select>
                        </div>
                        <div class="col-md-6 form-group mb-3">
                        <label for="icon" class=" control-label">Menu Icon</label>
                            <input type="text" name="icon" class="form-control  " id="icon">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                        <label for="sequence" class="control-label">Sequence</label>
                            <input type="text" name="sequence" class="form-control  " id="sequence">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                        <label for="operation" class="control-label">Operation</label>
                            <select name="operation" class="form-control " id="operation">
                                <option value='c'>Create</option>
                                <option value='r'>Read</option>
                                <option value='u'>Update</option>
                                <option value='d'>Delete</option>
                            </select>    
                        </div>
                        
                    </div>    
                    </div>
      
                       
               
            </div>
                    <div class="card-footer">
                        <div class="mc-footer">
                            <div class="row">
                                <div class="col-lg-12 text-right">
                                    <button type="submit" class="btn  btn-primary m-1" id="saveBtn" value="create">Save changes</button>
                                    <button type="button" class="btn btn-outline-secondary m-1" style="float:right" data-dismiss="modal">Close</button>
                                </div>
                            </div>    
                        </div>
                    </div>  
                </form>      
        </div>
    </div>
</div>
   
<div class="modal fade" id="add_menu_model" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading1"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id ="createMenu" method="POST" name="createmenu" action="{{ route('createMenu') }}">
            <div class="modal-body">
            <div class="alert alert-danger" style="display:none"></div>
                                 {{ csrf_field() }}
                                 <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 form-group mb-3">
                                            <label for="service_name">Menu name</label>
                                            @text("menu", ["id" => "menu", "class" => "form-control m", "placeholder" => "Enter Menu name"])
                                        </div>
                                        <div class="col-md-6 form-group mb-3">
                                            <label for="service_name">Menu URL</label>
                                            <!--div class="input-group-prepend">
                                                <span class="input-group-text">http://rcaredev.d-insights.global/</span>
                                            </div-->
                                            @text("menu_url", ["id" => "menu_url", "class" => "form-control  ", "placeholder" => "Enter Menu URL"])
                                        </div>
                                        <div class="col-md-6  form-group mb-3">
                                             <label for="icon">Icon</label>
                                            <input type="text" name="icon" class="form-control  ">
                                        </div>    
                                        <div class="col-md-6 form-group mb-3">
                                            <label for="loginuser" class="">Services</label>
                                            <div class="">
                                                <select name="service_id" class="form-control  ">
                                                    <option value="">None<option>
                                                    @foreach($service as $services)   
                                                    <option value="{{ $services->id }}">{{ $services->service_name }}</option>    
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>    
                                        <div class="col-md-6 form-group mb-3">
                                            <label for="loginuser" class="">Parent</label>
                                            <div class="">
                                                <select name="parent" class="form-control">
                                                    <option value="0">None<option>
                                                    @foreach($menus as $values)   
                                                    <option value="{{ $values->id }}">{{ $values->menu }}</option>    
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 form-group mb-3">
                                            <label for="loginuser" class="">Sequence</label>
                                            <input type="number" name="sequence" class="form-control  " id="sequence">
                                        </div> 
                                        <div class="col-md-6 form-group mb-3">
                        <label for="operation" class="control-label">Operation</label>
                            <select name="operation" class="form-control " id="operation">
                                <option value='c'>Create</option>
                                <option value='r'>Read</option>
                                <option value='u'>Update</option>
                                <option value='d'>Delete</option>
                            </select>    
                        </div>
                                        
                                        <!--div class="col-md-12">
                                            <div class="card-header">
                                                <button type="button" id="add_menu" class="btn btn-primary">Add Menu</button>
                                                <button type="button" class="btn btn-danger" style="float:right" data-dismiss="modal">Close</button>
                                            </div>    
                                        </div-->
                                        
                                    </div>
                                </div>
                                
               
            </div>
            <div class="card-footer">
                <div class="mc-footer">
                    <div class="row">
                        <div class="col-lg-12 text-right">
                            <button type="submit" id="add_menu" class="btn  btn-primary m-1">Add Menu</button>
                            <button type="button" class="btn btn-outline-secondary m-1" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </form> 
        </div>
    </div>
</div>

@endsection 
@section('page-js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="{{asset('assets/js/tooltip.script.js')}}"></script>
<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script type="text/javascript">
    
    var columns= [
        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'menu', name: 'menu'},
                    {data: 'menu_url', name: 'menu_url'},
                    {data: 'services.service_name', name: 'services.service_name'},
                    {data: 'icon', name: 'icon'},
                    {data: 'mnu.menu', name: 'mnu.menu'},
                    {data: 'sequence', name: 'sequence'},
                    {data: 'operations', "searchable": false, "orderable":false, "render": function (data, type, row) {
                    if (row.operation === 'c') { return 'Create';}
                    else if(row.operation === 'r' ){ return 'Read'; }
                    else if(row.operation === 'u' ){ return 'Update'; }    
                    else { return 'Delete';}}},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
            ];
    var table = util.renderDataTable('menu_list', "{{ route('fetchMenu') }}", columns, "{{ asset('') }}");   

    $('body').on('click', '.editMenu', function () {
            var id = $(this).data('id');
            $.get("ajax/rCare/editMenu" +'/' + id +'/edit', function (data) {
                $('#modelHeading').html("Edit Menu");
                $('#saveBtn').val("edit-user");
                $('#ajaxModel').modal('show');
                $('#menu_id').val(data.id);
                $('#menu').val(data.menu);
                $('#menu_url').val(data.menu_url);
                $('#service_id').val(data.service_id);
                $('#icon').val(data.icon);
                $('#parent').val(data.parent);
                $('#sequence').val(data.sequence);
                $('#operation').val(data.operation);

            })
        });

        $('body').on('click', '.deleteMenu', function () {
            
            var menu_id = $(this).data("id");
            var checkstr = confirm("Are You sure want to delete !");
            if(checkstr == true){

            $.ajax({
                type: "POST",
                url: "ajax/rCare/deleteMenu"+'/'+ menu_id +'/delete',
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
            }else{
    return false;
    }

        });

        $('#addMenu, .addmenus').click(function () {
                $('#saveBtn').val("create-product");
                $('#product_id').val('');
                $('#productForm').trigger("reset");
                $('#modelHeading1').html("Add Menu");
                $('#add_menu_model').modal('show');
            });

            $("form[name='createmenu']").validate({
                rules: {
                    menu: "required",
                    menu_url: "required",
                    service_id: "required"
                },
                messages: {
                    menu: "Please enter Menu Name",
                    menu_url: "Please enter Menu Url",
                    service_id: "Please select an item!" 

                },
                submitHandler: function(form) {
                     form.submit();
                 }
 
            });

        $(document).ready(function() { 
            if(window.location.href == 'http://rcareprototype.d-insights.global/admin/menu#add_menu_model'){
                $('#saveBtn').val("create-product");
                $('#product_id').val('');
                $('#productForm').trigger("reset");
                $('#modelHeading1').html("Add Menu");
                $('#add_menu_model').modal('show');
             }
         });

    </script>
@endsection
