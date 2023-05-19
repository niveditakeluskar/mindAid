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
            <a class="" href="javascript:void(0)" id="addMenu"><i class="add-icons i-Tag-4" data-toggle="tooltip" data-placement="top" title="Add Stage"></i>&nbsp;<i class="plus-icons i-Add" data-toggle="tooltip" data-placement="top" title="Add Stage"></i></a> 
        </div>
    </div>
    <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>
<div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
                <div id="success"></div>
                <div id="msg"></div>
                <!--  <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>    -->            
                @include('Theme::layouts.flash-message')
                <div class="table-responsive">
                    <table id="stage_list" class="display table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th width="50px">Sr No.</th>
                                <th>Stage Name</th>
                                <th>Module</th>
                                <th>Sub Module</th>
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
            <form action="{{ route('updateStage')}}" method="POST" id="menuForm" name="menuForm" class="form-horizontal">
                <div class="modal-body">

                    {{ csrf_field() }}
                    <input type="hidden" name="stage_id" id="stage_id">
                    <div class="form-group">
                        <div class="row">    

                            <div class="col-md-6 form-group mb-3">
                                <label for="name" class="control-label"><span class="error">*</span> Stage Name</label>
                                @text("description", ["id" => "description", "class" => "form-control capital-first ", "placeholder" => "Enter Menu name"])
                            </div>                        
                            <div class="col-md-6 form-group mb-3">
                                <label for="name" class="control-label"><span class="error">*</span> Module</label>
                                @selectOrgModule("service_id",["id"=>"edit_service_id", "class"=>"module"])
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label for="name" class="control-label"><span class="error">*</span> Sub Module</label>
                                @select("Sub Module", "component_id", [], ["id" => "edit_component_id", "class"=>"sub-module"])
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
                                <label class=""><span class="error">*</span> Module</label>
                                @selectOrgModule("service_id",["id"=>"service_id", "class"=>"module"]) 
                                <!-- selectMasterModule -->
                            </div> 
                            <div class="col-md-6 form-group mb-3">
                                <label for="loginuser" class=""><span class="error">*</span> Sub Module</label>
                                @select("Sub Module", "component_id", [], ["id" => "component_id", "class"=>"sub-module"])
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
var renderStageTable = function() {
    var columns= [
    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
    {data: 'description', name: 'description', render: function(data, type, full, meta){
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
    var table = util.renderDataTable('stage_list', "{{ route('stageList') }}", columns, "{{ asset('') }}");   
}

$(document).ready(function() { 
    renderStageTable();
    stage.init();
    util.getToDoListData(0, {{getPageModuleName()}});
});
</script>
@endsection
