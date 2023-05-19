@extends('Theme::layouts.master')
@section('page-css')
<link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')

<div class="breadcrusmb">

  <div class="row">
    <div class="col-md-11">
     <h4 class="card-title mb-3">Labs</h4>
 </div>
 <div class="col-md-1">
   <a class="" href="javascript:void(0)" id="addLabs"><i class="add-icons i-Administrator" data-toggle="tooltip" data-placement="top" title="Add Labs"></i><i class="plus-icons i-Add" data-toggle="tooltip" data-placement="top" title="Add Labs"></i></a>  
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
                <table id="labs-list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="30px">Sr No.</th>
                            <th width="50px">Labs</th>
                            <th width="50px">Last Modified By</th>
                            <th width="50px">Last Modified On</th>
                            <th width="40px">Action</th>
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

<!-- add lab -->
<div class="modal fade" id="add_lab_modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="modelHeading1">Add Lab</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
         <form action="{{ route('submit_lab')}}" method="post" name ="main_labs_form"  id="main_labs_form">
            @csrf
            <div class="alert alert-success" id="success-alert" style="display: none;">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <strong> Lab Added successfully! </strong><span id="text"></span>
            </div>
            @include('Labs::labs')
        </form>
    </div>

</div>
</div>
</div> 


<div class="modal fade" id="edit_lab_modal" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="modelHeading1">Edit Lab</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
         <form action="{{ route('submit_lab')}}"  method="POST" name ="main_edit_labs_form"  id="main_edit_labs_form">
            @csrf
            <div class="alert alert-success" id="success-alert" style="display: none;">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <strong> Lab Updated successfully! </strong><span id="text"></span>
            </div>
            <input type="hidden" name="id" value="">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-10">
                        <div class="form-group">
                            <label>Lab <span class="error">*</span></label>
                            @text("description",["name" => "lab","id"=>"description"])
                        </div>
                    </div>  
                </div>
            </div>  
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-11">
                        <div class="form-group">
                            <label>Parameters <span class='error'>*</span></label>
                            @text("parameters[]", ["placeholder" => "Enter Parameter", "id" => "parameter_0" ,"class"=>"col-md-10"])
                        </div>
                    </div>
                    <div class="col-md-1" style="float: right;margin-top: -36px; margin-right: 16%;">
                        <div >
                            <i class="plus-icons i-Add"  class="btn btn-sprimary float-left"  id="additionalparameter" title="Add Parameter"></i>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-10">

                <div class="col-md-12 form-group mb-3" id="append_parameter"></div>
            </div>


            <div class="row">
                <!-- <div class="col-6 text-left form-group"><a href="http://awvprod.d-insights.global" class="btn btn-primary">Cancel</a></div>  -->
                <div class="col-12 text-right form-group mb-4">
                    <button type="submit" class="btn btn-primary">Submit</button>
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

    var getlablisting =  function() {
        var columns = [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'description',name: 'description'},
            {data: 'users',
                mRender: function(data, type, full, meta){
                    if(data!='' && data!='NULL' && data!=undefined){
                        l_name = data['l_name'];
                        if(data['l_name'] == null && data['f_name'] == null){
                            l_name = '';
                            return '';
                        } else {
                            return data['f_name'] + ' ' + l_name;
                        }
                    } else { 
                        return ''; 
                    }    
                },orderable: false
            }, 
            {data: 'updated_at',name:'updated_at'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ];
        var table = util.renderDataTable('labs-list', "{{ route('labs_list') }}", columns, "{{ asset('') }}");  
    };

    $(document).ready(function() {
        getlablisting();
        labs.init();
        util.getToDoListData(0, {{getPageModuleName()}});
    });

    $('#addLabs').click(function() {    
        $('#add_lab_modal').modal('show');
    });
</script>
@endsection