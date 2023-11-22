@extends('Theme::layouts.master')
@section('page-css')

<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">


@endsection

@section('main-content')

<div class="breadcrusmb">

  <div class="row">
                <div class="col-md-11">
                   <h4 class="card-title mb-3">Steps</h4>
                </div>
                 <div class="col-md-1">
                 <a class="" href="javascript:void(0)" id="addStageCodeBtn"><i class="add-icons i-Professor" data-toggle="tooltip" data-placement="top" title="Add Step" style="margin-right:-7px"></i>&nbsp;<i class="plus-icons i-Add" data-toggle="tooltip" data-placement="top" title="Add Step"></i></a> 
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
                    <table id="stage_code_list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                        <th>Sr No.</th>
                        <th width='20px'>Step</th>
                        <th>Stage</th>
                        <th>Component</th>
                        <th>Module</th>
                        <th>Sequence</th>
                        <th>Last Modified By</th>
                        <th>Last Modified On</th>
                        <th>Action</th>
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


<div class="modal fade" id="editStageCodeModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('updateStageCode')}}" method="POST" id="editstagecodeForm" name="editstagecodeForm" class="form-horizontal">
                <div class="modal-body">
        
                    {{ csrf_field() }}
                    <input type="hidden" name="stage_code_id" id="stage_code_id">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 form-group mb-3">
                                <label class=""><span class="error">*</span> Module</label>
                                @selectMasterModule("module",["id"=>"edit_module", "class"=>"module"])
                            </div> 
                            <div class="col-md-6 form-group mb-3">
                                <label for="loginuser" class=""><span class="error">*</span> Sub Module</label>
                                @select("Sub Module", "sub_module", [], ["id" =>"edit_sub_module", "class"=>"sub_module"])
                            </div>   
                            <div class="col-md-6 form-group mb-3">
                                <label><span class="error">*</span> Stage</label>
                                @select("Stage", "stages", [], ["id" =>"edit_stages", "class"=>"stage"])
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label><span class="error">*</span> Step name</label>
                                @text("description", ["id" => "edit-stage-code", "class" => "capital-first m", "placeholder" => "Enter Stage Code"])
                            </div>
                            <div class="col-md-6 form-group mb-3">
                                <label><span class="error">*</span> Step Sequence</label>
                                @number("sequence", ["id" => "edit-sequence", "class" => "m", "placeholder" => "Enter Sequence"])
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
   
<div class="modal fade" id="addStageCodeModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="addStageCodeHeading"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id ="create_stage_code" method="POST" name="create_stage_code" action="{{route('create_stage_code')}}">
            <div class="modal-body">
            <div class="alert alert-danger" style="display:none"></div>
                                 {{ csrf_field() }}
                                 <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-6 form-group mb-3">
                                            <label class=""><span class="error">*</span> Module</label>
                                            @selectMasterModule("module",["id"=>"module", "class"=>"module"])
                                        </div> 
                                        <div class="col-md-6 form-group mb-3">
                                            <label for="loginuser" class=""><span class="error">*</span> Sub Module</label>
                                            @select("Sub Module", "sub_module", [], ["id" => "sub_module", "class"=>"sub_module"])
                                        </div>   
                                        <div class="col-md-6 form-group mb-3">
                                            <label><span class="error">*</span> Stage</label>
                                            @select("Stage", "stages", [], ["id" => "stages", "class"=>"stage"])
                                        </div>
                                        <div class="col-md-6 form-group mb-3">
                                            <label><span class="error">*</span> Step name</label>
                                            @text("description", ["id" => "stage", "class" => "capital-first m", "placeholder" => "Enter Stage Code"])
                                        </div>
                                        <div class="col-md-6 form-group mb-3">
                                            <label><span class="error">*</span> Step Sequence</label>
                                            @number("sequence", ["id" => "sequence", "class" => "m", "placeholder" => "Enter Sequence"])
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
                            <button type="submit" id="add_menu" class="btn  btn-primary m-1">Add Step</button>
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
<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>
<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script src="{{asset(mix('assets/js/laravel/stageCode.js'))}}"></script>
<script type="text/javascript">
    var renderStageCodeTable = function() {
        var columns= [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'description', name: 'description', render: function(data, type, full, meta){
                    var name = "<span class='capital-first'>" + data + "</span>";
                    return name
                }
            },
            {data: 'stage.description', name: 'stage.description', render: function(data, type, full, meta){
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
            {data: 'sequence', name: 'sequence'},
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
        var table = util.renderDataTable('stage_code_list', "{{ route('stage-codes') }}", columns, "{{ asset('') }}");   
    }; 
    $(document).ready(function() { 
        renderStageCodeTable();
        stageCode.init();
        util.getToDoListData(0, {{getPageModuleName()}});
    });
</script>
@endsection