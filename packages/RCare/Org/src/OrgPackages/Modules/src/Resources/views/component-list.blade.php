@extends('Theme::layouts.master')
@section('page-css')
<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-10">
            <h4 class="card-title mb-3">Sub Modules</h4>
        </div>
        <div class="col-md-2">
            <a class="float-right" href="javascript:void(0)" id="addcompModule"><i class="add-icons i-ID-3" data-toggle="tooltip" data-placement="top" title="Add Sub Module"></i>&nbsp;<i class="plus-icons i-Add" data-toggle="tooltip" data-placement="top" title="Add Sub Module"></i></a> 
        </div>
    </div>
</div>
<div class="separator-breadcrumb border-top"></div>


<div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
                <div class="alert alert-success" id="success-alert" style="display: none;">
                  <button type="button" class="close" data-dismiss="alert">x</button>
                  <strong>Sub Module saved successfully! </strong><span id="text"></span>
                </div>
                <div id="msg"></div>
                <div id="msgActive"></div>         
                @include('Theme::layouts.flash-message')
                <div class="table-responsive">
                    <table id="component_list" class="display datatable table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th width="45px">Sr No.</th>
                                <th>Modules </th> 
                                <th>Sub Modules</th>   
                                <th>Last Modified By</th>
                                <th>Last Modified On</th>              
                                <th width="65px">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="add_comp_module_modal" aria-hidden="true">
  <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading1"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('add_component') }}" name="add_comp_module_form" id="add_comp_module_form" method="POST">
                <div class="modal-body">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12  form-group mb-3 ">
                                <label for="rolename"><span class="error">*</span> Module Name</label>
                                @selectMasterModule("module_id",["id"=>"module_id", "class"=>""])  
                            </div>
                            <div class="col-md-12  form-group mb-3 ">
                                <label for="rolename"><span class="error">*</span> Sub Module Name</label>
                                @text("components", ["id" => "components", "placeholder" => "Enter Sub Module", "class" => "capital-first"])

                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="mc-footer">
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <button type="submit"  class="btn  btn-primary m-1" >Add Sub Module</button>
                                <button type="button" class="btn btn-outline-secondary m-1" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div> 


<div class="modal fade" id="edit_comp_module_modal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading1">Edit Sub Module</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('update_component') }}" method="POST" id="edit_comp_module_form" name="edit_comp_module_form" class="form-horizontal">
                <div class="modal-body">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" id="compid">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12  form-group mb-3 ">
                                <label for="rolename"><span class="error">*</span> Module Name</label>
                                @selectMasterModule("module_id",["id"=>"edit_comp_module_id", "class"=>""]) 
                            </div>
                            <div class="col-md-12  form-group mb-3 ">
                                <label for="rolename"><span class="error">*</span> Sub Module Name</label>
                                @text("components", ["id" => "comp", "placeholder" => "Enter Sub Module", "class" => "capital-first"])
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="mc-footer">
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <button type="submit"  class="btn  btn-primary m-1">Save Changes</button>
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
<script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
<script src="{{asset('assets/js/datatables.script.js')}}"></script>
<script src="{{asset(mix('assets/js/jquery.validate.min.js'))}}"></script>
<script src="{{asset('assets/js/external-js/additional-methods.min.js')}}"></script>

<script type="text/javascript">
var renderSubModulesTable = function() {
    var columns= [
    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
    {data: 'module.module', name: 'module.module'},
    {data: 'components', name: 'components'},                    
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
    var table = util.renderDataTable('component_list', "{{ route('component_list') }}", columns, "{{ asset('') }}");
};

$(document).ready(function() {
    renderSubModulesTable();
    modules.init();
    util.getToDoListData(0, {{getPageModuleName()}});
});

</script>
@endsection
