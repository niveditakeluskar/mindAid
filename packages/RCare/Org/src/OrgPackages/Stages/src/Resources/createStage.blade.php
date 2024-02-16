@extends('Theme::layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">


@endsection

@section('main-content')

<div class="breadcrusmb">

  <div class="row">
                <div class="col-md-11">
                   <h4 class="card-title mb-3">Stages</h4>
                </div>
                 <div class="col-md-1">
                 <a class="" href="javascript:void(0)" id="addMenu"><i class="add-icons i-Tag-4" data-toggle="tooltip" data-placement="top" title="Add Menu"></i>&nbsp;<i class="plus-icons i-Add" data-toggle="tooltip" data-placement="top" title="Add Menu"></i></a> 
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
                    <table id="menu_list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                        <th width="50px">Sr No.</th>
                        <th>Menu Name</th>
                        <th>Module</th>
                        <th>Sub Module</th>
                        <th>Menu URL</th>
                        <th>Parent</th>
                        <th>Icon</th>
                        
                        <th>Sequence</th>
                        <th>Operation</th>
                        <th width="50px">Action</th>
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
            <form action="{{ route('updateMenu')}}" method="POST" id="menuForm" name="menuForm" class="form-horizontal">
            <div class="modal-body">
       
                {{ csrf_field() }}
                <input type="hidden" name="menu_id" id="menu_id">
                    <div class="form-group">
                    <div class="row">    
                        
                        <div class="col-md-6 form-group mb-3">
                             <label for="name" class="control-label"><span class="error">*</span> Menu Name</label>
                            @text("menu", ["id" => "menu", "class" => "form-control capital-first ", "placeholder" => "Enter Menu name"])
                        </div>
                        
                        <div class="col-md-6 form-group mb-3">
                            <label for="name" class="control-label"><span class="error">*</span> Menu URL</label>
                            @text("menu_url", ["id" => "menu_url", "class" => "form-control  ", "placeholder" => "Enter Menu URL"])
                        </div>
                        
                        <div class="col-md-6 form-group mb-3">
                        <label for="name" class="control-label"><span class="error">*</span> Module</label>
                        <select name="service_id" id="service_id" class="form-control  " onchange="get_components(this.value);">
                            @foreach($service as $services)   
                                <option value="{{ $services->id }}">{{ $services->module }}</option>    
                            @endforeach
                        </select>
                         </div>
                         <div class="col-md-6 form-group mb-3">
                        <label for="name" class="control-label"><span class="error">*</span> Sub Module</label>
                        <select name="component_id" id="components_id" class="form-control  ">
                            @foreach($component as $components)   
                                <option value="{{ $components->id }}">{{ $components->components }}</option>    
                            @endforeach
                        </select>
                         </div>
                         <div class="col-md-6 form-group mb-3">
                        <label for="name" class="control-label"><span class="error">*</span> Parent</label>
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
                        <label for="sequence" class="control-label"><span class="error">*</span> Sequence</label>
                            <input type="text" name="sequence" id="sequence" class="form-control  " id="sequence">
                        </div>
                        <div class="col-md-6 form-group mb-3">
                        <label for="operation" class="control-label"><span class="error">*</span> Operation</label>
                            <select name="operation" class="form-control " id="operation">
                                <option value=''>Select Operation</option>
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
   



<div class="modal fade" id="ajaxModel1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading1"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id ="create_stage" method="POST" name="create_stage" action="{{route('create_stage')}}">
            <div class="modal-body">
            <div class="alert alert-danger" style="display:none"></div>
                                 {{ csrf_field() }}
                                 <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 form-group mb-3">
                                            <label><span class="error">*</span> Stage name</label>
                                            @text("description", ["id" => "stage", "class" => "form-control capital-first m", "placeholder" => "Enter Stage name"])
                                        </div>
                                        <div class="col-md-6 form-group mb-3">
                                            <label><span class="error">*</span> Stage URL</label>
                                            <!--div class="input-group-prepend">
                                                <span class="input-group-text">http://rcaredev.d-insights.global/</span>
                                            </div-->
                                            @text("stage_url", ["id" => "stage_url", "class" => "form-control  ", "placeholder" => "Enter Stage URL"])
                                        </div>
                                        <div class="col-md-6  form-group mb-3">
                                             <label for="icon">Icon</label>
                                            <input type="text" name="icon" class="form-control  ">
                                        </div>    
                                        <div class="col-md-6 form-group mb-3">
                                            <label class=""><span class="error">*</span> Module</label>
                                            <div class="">
                                                <select name="service_id" class="form-control  " onchange="get_component(this.value);">
                                                    <option value="">None<option>
                                                    @foreach($service as $services)   
                                                    <option value="{{ $services->id }}">{{ $services->module }}</option>    
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div> 
                                        <div class="col-md-6 form-group mb-3">
                                            <label for="loginuser" class=""><span class="error">*</span> Sub Module</label>
                                            <div class="">
                                                <select name="component_id"  id ="component_id" class="form-control  ">
                                                    
                                                </select>
                                            </div>
                                        </div>   
                                        <div class="col-md-6 form-group mb-3">
                                            <label for="loginuser" class=""><span class="error">*</span> Parent</label>
                                            <div class="">
                                                <select name="parent" id="parent" class="form-control">
                                                    <option value="0">None<option>
                                                    @foreach($menus as $values)   
                                                    <option value="{{ $values->id }}">{{ $values->menu }}</option>    
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 form-group mb-3">
                                            <label for="loginuser" class=""><span class="error">*</span> Sequence</label>
                                            <input type="number" name="sequence" class="form-control  " id="sequence">
                                        </div> 
                                        <div class="col-md-6 form-group mb-3">
                                            <label for="operation" class="control-label"><span class="error">*</span> Operation</label>
                                            <select name="operation" class="form-control " id="operation">
                                                <option>Select Operation</option>
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
                            <button type="submit" id="add_menu" class="btn  btn-primary m-1">Add Stage</button>
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
    <script src="{{asset(mix('assets/js/jquery.validate.min.js'))}}"></script>
<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script type="text/javascript">
    
    var columns= [
        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'menu', name: 'menu', render: function(data, type, full, meta){
                            var name = "<span class='capital-first'>" + data + "</span>";
                            return name
                        }
                    },
                    {data: 'module.module', name: 'module.module', render: function(data, type, full, meta){
                            var name = "<span class='capital-first'>" + data + "</span>";
                            return name
                        }
                    },
                    {data: 'components.components', name: 'components.components', render: function(data, type, full, meta){
                            var name = "<span class='capital-first'>" + data + "</span>";
                            return name
                        }
                    },
                    {data: 'menu_url', name: 'menu_url'},
                    {data: 'mnu.menu', name: 'mnu.menu', render: function(data, type, full, meta){
                            var name = "<span class='capital-first'>" + data + "</span>";
                            return name
                        }
                    },
                  
                    {data: 'icon', name: 'icon'},
                   
                    {data: 'sequence', name: 'sequence'},
                    {data: 'operations', "searchable": false, "orderable":false, "render": function (data, type, row) {
                    if (row.operation === 'c') { return 'Create';}
                    else if(row.operation === 'r' ){ return 'Read'; }
                    else if(row.operation === 'u' ){ return 'Update'; }    
                    else { return 'Delete';}}},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
            ];
    var table = util.renderDataTable('menu_list', "{{ route('org_menu_list') }}", columns, "{{ asset('') }}");   

    $('body').on('click', '.editMenu', function () {
            var id = $(this).data('id');
            $.get("ajax/editMenu" +'/' + id +'/edit', function (data) {
                $('#modelHeading').html("Edit Menu");
                $('#saveBtn').val("edit-user");
                $('#ajaxModel').modal('show');
                $('#menu_id').val(data.id);
                $('#menu').val(data.menu);
                $('#menu_url').val(data.menu_url);
                $('#service_id').val(data.module_id);
                $('#components_id').val(data.component_id);
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
                url: "ajax/deleteMenu"+'/'+menu_id+'/delete',
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
                $('#ajaxModel1').modal('show');
            });


            $("form[name='createmenu']").validate({
                rules: {
                    menu: "required",
                    menu_url: "required",
                    service_id: "required",
                    component_id: "required",
                    parent: "required",
                    sequence: "required",
                    operation: "required"
                },
                messages: {
                    menu: "Please enter Menu Name",
                    menu_url: "Please enter Menu Url",
                    service_id: "Please select an item!", 
                    component_id: "Please select an item!",
                    parent: "Please select an item!",
                    sequence: "Please select an item!",
                    operation: "Please select an item!"

                },
                submitHandler: function(form) {
                     form.submit();
                 }
 
            });

            $("form[name='menuForm']").validate({
                rules: {
                    menu: "required",
                    menu_url: "required",
                    service_id: "required",
                    component_id: "required",
                    parent: "required",
                    sequence: "required",
                    operation: "required"
                },
                messages: {
                    menu: "Please enter Menu Name",
                    menu_url: "Please enter Menu Url",
                    service_id: "Please select an item!", 
                    component_id: "Please select an item!",
                    parent: "Please select an item!",
                    sequence: "Please select an item!",
                    operation: "Please select an item!"

                },
                submitHandler: function(form) {
                     form.submit();
                 }
 
            });


        $(document).ready(function() { 
            if(window.location.href == 'http://rcaredev.d-insights.global/menu#ajaxModel1'){
                $('#saveBtn').val("create-product");
                $('#product_id').val('');
                $('#productForm').trigger("reset");
                $('#modelHeading1').html("Add Menu");
                $('#ajaxModel1').modal('show');
             }
         });

         function get_component(val){
        $.ajax({
            type: 'post',
            url: 'GetComponent',
            data: {
                _token: '{!! csrf_token() !!}',
                module:val
            },
            success: function (response) {
                document.getElementById("component_id").innerHTML=response; 
            }
        });
    }
    function get_components(val){
        $.ajax({
            type: 'post',
            url: 'GetComponent',
            data: {
                _token: '{!! csrf_token() !!}',
                module:val
            },
            success: function (response) {
                document.getElementById("components_id").innerHTML=response; 
            }
        });
    }


    $('body').on('click', '.deleteMenu', function () {
            
            var menu_id = $(this).data("id");
            var checkstr = confirm("Are You sure want to delete !");
            if(checkstr == true){

            $.ajax({
                type: "POST",
                url: "ajax/deleteMenu"+'/'+menu_id+'/delete',
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



</script>
@endsection
