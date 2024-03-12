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
            <a class="" href="javascript:void(0)" id="addMenu"><i class="add-icons i-Tag-4" data-toggle="tooltip" data-placement="top" title="Add Menu"></i>&nbsp;<i class="plus-icons i-Add" data-toggle="tooltip" data-placement="top" title="Add Menu"></i></a> 
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
                                <th>Last Modified By</th>
                                <th>Last Modified On</th>
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
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="name" class="control-label"><span class="error">*</span> Sub Module</label>
                                <select name="component_id" id="components_id" class="form-control">
                                    <option value='0'>None</option>
                                    @foreach($component as $components)   
                                    <option value="{{ $components->id }}">{{ $components->components }}</option>    
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="name" class="control-label"><span class="error">*</span>Parent</label>
                                <select name="parent" id="parent" class="form-control">
                                    <option value="">Select Parent</option>
                                        <option value="0">None</option>
                                        @foreach($menus as $value)   
                                        <option value="{{ $value->id }}">{{ $value->menu }}</option>    
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="icon" class=" control-label"><span class="error">*</span> Menu Icon</label>
                                    @text("icon", ["id" => "icon", "class" => "", "placeholder" => "Enter Menu Icon"])
                                    <!-- <input type="text" name="icon" class="form-control  " id="icon"> -->
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="sequence" class="control-label"><span class="error">*</span> Sequence</label>
                                    <input type="text" name="sequence" id="sequence" class="form-control  " id="sequence">
                                      <div class="invalid-feedback"></div>
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
                                      <div class="invalid-feedback"></div> 
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="status" class="control-label"><span class="error">*</span> Status</label>
                                    <select name="status" class="form-control " id="status">
                                        <option value=''>Select Status</option>
                                        <option value='0'>InActive</option>
                                        <option value='1'>Active</option>
                                        <option value='2'>Only Developer Access</option>
                                    </select>    
                                      <div class="invalid-feedback"></div>
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
                <form id ="createMenu" method="POST" name="createmenu" action="{{route('create_menu')}}">
                    <div class="modal-body">
                        <div class="alert alert-danger" style="display:none"></div>
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 form-group mb-3">
                                    <label for="service_name"><span class="error">*</span> Menu name</label>
                                    @text("menu", ["id" => "menu", "class" => "form-control capital-first m", "placeholder" => "Enter Menu name"])
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="service_name"><span class="error">*</span> Menu URL</label>
<!--div class="input-group-prepend">
<span class="input-group-text">http://rcaredev.d-insights.global/</span>
</div-->
        @text("menu_url", ["id" => "menu_url", "class" => "form-control  ", "placeholder" => "Enter Menu URL"])
        </div>
        <div class="col-md-6  form-group mb-3">
            <label for="icon"><span class="error">*</span> Menu Icon</label>
            @text("icon", ["id" => "icon", "class" => "", "placeholder" => "Enter Menu Icon"])
            <!-- <input type="text" name="icon" class="form-control  "> -->
        </div>    
        <div class="col-md-6 form-group mb-3">
            <label for="loginuser" class=""><span class="error">*</span> Module</label>
            <div class="">
                <select name="service_id" class="form-control  " onchange="get_component(this.value);">
                    <option value="">None<option>
                        @foreach($service as $services)   
                        <option value="{{ $services->id }}">{{ $services->module }}</option>    
                        @endforeach
                    </select>
                      <div class="invalid-feedback"></div>
                </div>
            </div> 
            <div class="col-md-6 form-group mb-3">
                <label for="loginuser" class=""><span class="error">*</span> Sub Module</label>
                <div class="">
                    <select name="component_id"  id ="component_id" class="form-control  ">
                    </select>
                      <div class="invalid-feedback"></div>
                </div>
            </div>   
            <div class="col-md-6 form-group mb-3">
                <label for="loginuser" class=""><span class="error">*</span> Parent</label>
                <div class="">
                    <select name="parent" id="parent" class="form-control">
                        <option value="">Select Parent<option>
                            <option value="0">None<option>
                                @foreach($menus as $values)   
                                <option value="{{ $values->id }}">{{ $values->menu }}</option>    
                                @endforeach
                            </select>
                              <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="col-md-6 form-group mb-3">
                        <label for="loginuser" class=""><span class="error">*</span> Sequence</label>
                        <input type="number" name="sequence" class="form-control  " id="sequence">
                          <div class="invalid-feedback"></div>
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
                          <div class="invalid-feedback"></div>  
                    </div>
                    <div class="col-md-6 form-group mb-3">
                                            <label for="status" class="control-label"><span class="error">*</span> Status</label>
                                            <select name="status" class="form-control " id="status">
                                                <option value=''>Select Status</option>
                                                <option value='0'>InActive</option>
                                                <option value='1'>Active</option>
                                                <option value='2'>Only Developer Access</option>
                                            </select>    
                                              <div class="invalid-feedback"></div>
                                        </div>

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
<!--Andy22Nov21-->
<!-- <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script> -->
    <script src="{{asset(mix('assets/js/jquery.validate.min.js'))}}"></script>
<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script> 
<script type="text/javascript">

var renderMenuTable = function(){
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
    var table = util.renderDataTable('menu_list', "{{ route('org_menu_list') }}", columns, "{{ asset('') }}");   
}

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

$(document).ready(function() { 
    renderMenuTable();
    orgmenus.init();
    util.getToDoListData(0, {{getPageModuleName()}});
});
</script>
@endsection
