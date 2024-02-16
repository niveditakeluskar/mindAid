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
                <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addMenu"> Add Menu</a>  
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
                                <th>No</th>
                                <th>Menu Name</th>
                                <th>Menu URL</th>
                                <th>Module</th>
                                <th>Components</th>
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
    <div class="modal fade" id="menuModel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="menuModelHeading"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="" method="POST" id="menuForm" name="menuForm" class="form-horizontal">
                    <div class="modal-body">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" id="id">
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
                                    <label for="name" class="control-label">Module</label>
                                    <select name="service_id" id="service_id" class="form-control  " onchange="get_components(this.value);">
                                        @foreach($service as $services)   
                                            <option value="{{ $services->id }}">{{ $services->module }}</option>    
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="name" class="control-label">Components</label>
                                    <select name="component_id" id="components_id" class="form-control  ">
                                        @foreach($component as $components)   
                                            <option value="{{ $components->id }}">{{ $components->components }}</option>    
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
                                    @text("icon", ["id" => "icon", "class" => "form-control  ", "placeholder" => "Enter Menu Icon"])
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
                                    <span id="button_div"></span>
                                        <!-- <button type="submit" class="btn  btn-primary m-1" id="saveBtn" value="create">Save changes</button> -->
                                        <button type="button" class="btn btn-outline-secondary m-1" style="float:right" data-dismiss="modal">Close</button>
                                    </span>
                                </div>
                            </div>    
                        </div>
                    </div>  
                </form>      
            </div>
        </div>
    </div>
    <!-- <div class="modal fade" id="ajaxModel" aria-hidden="true">
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
                                <label for="name" class="control-label">Menu Name</label>
                                @text("menu", ["id" => "menu", "class" => "form-control  ", "placeholder" => "Enter Menu name"])
                            </div>
                            
                            <div class="col-md-6 form-group mb-3">
                                <label for="name" class="control-label">Menu URL</label>
                                @text("menu_url", ["id" => "menu_url", "class" => "form-control  ", "placeholder" => "Enter Menu URL"])
                            </div>
                            
                            <div class="col-md-6 form-group mb-3">
                            <label for="name" class="control-label">Module</label>
                            <select name="service_id" id="service_id" class="form-control  " onchange="get_components(this.value);">
                                @foreach($service as $services)   
                                    <option value="{{ $services->id }}">{{ $services->module }}</option>    
                                @endforeach
                            </select>
                            </div>
                            <div class="col-md-6 form-group mb-3">
                            <label for="name" class="control-label">Components</label>
                            <select name="component_id" id="components_id" class="form-control  ">
                                @foreach($component as $components)   
                                    <option value="{{ $components->id }}">{{ $components->components }}</option>    
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
                                                <label for="service_name">Menu name</label>
                                                @text("menu", ["id" => "menu", "class" => "form-control m", "placeholder" => "Enter Menu name"])
                                            </div>
                                            <div class="col-md-6 form-group mb-3">
                                                <label for="service_name">Menu URL</label>
                                                <!- -div class="input-group-prepend">
                                                    <span class="input-group-text">http://rcaredev.d-insights.global/</span>
                                                </div- ->
                                                @text("menu_url", ["id" => "menu_url", "class" => "form-control  ", "placeholder" => "Enter Menu URL"])
                                            </div>
                                            <div class="col-md-6  form-group mb-3">
                                                <label for="icon">Icon</label>
                                                <input type="text" name="icon" class="form-control  ">
                                            </div>    
                                            <div class="col-md-6 form-group mb-3">
                                                <label for="loginuser" class="">Module</label>
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
                                                <label for="loginuser" class="">Components</label>
                                                <div class="">
                                                    <select name="component_id"  id ="component_id" class="form-control  ">
                                                        
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
                                            
                                            <!- -div class="col-md-12">
                                                <div class="card-header">
                                                    <button type="button" id="add_menu" class="btn btn-primary">Add Menu</button>
                                                    <button type="button" class="btn btn-danger" style="float:right" data-dismiss="modal">Close</button>
                                                </div>    
                                            </div- ->
                                            
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
    </div> -->
@endsection
@section('page-js')

    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    <script type="text/javascript">
        var columns= [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'menu', name: 'menu'},
            {data: 'menu_url', name: 'menu_url'},
            {data: 'module.module', name: 'module.module'},
            {data: 'components.components', name: 'components.components'},
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
        var table = util.renderDataTable('menu_list', "{{ route('org_menu_list') }}", columns, "{{ asset('') }}");   
    </script>
    
    <script>
        $(document).ready(function() {
            orgmenus.init();
            form.ajaxForm(
                "menuForm",
                orgmenus.onResult,
                orgmenus.onSubmit,
                orgmenus.onErrors
            );
            form.evaluateRules("MenuAddRequest");
        });
    </script>
@endsection
