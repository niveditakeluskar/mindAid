@extends('Theme::layouts.master')
@section('page-css')
    <link rel="stylesheet" href="{{asset('assets/styles/vendor/datatables.min.css')}}">
@endsection

@section('main-content')

<div class="breadcrusmb">

    <div class="row">
    <div class="col-md-11">
       <h4 class="card-title mb-3">Responsibilities</h4>
    </div>
     <div class="col-md-1">
     <a href="javascript:void(0)" id="addResponsibility" class="addResponsibility"><i class="add-icons i-Administrator" data-toggle="tooltip" data-placement="top" title="Add Responsibility"></i><i class="plus-icons i-Add" data-toggle="tooltip" data-placement="top" title="Add Responsibility"></i></a>  
    </div>
    </div>
   <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>
 <div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
                <div class="alert alert-success" id="success-alert" style="display: none;">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <strong> <span id='text'></span> </strong>
                </div>
                <div id="success"></div>
           <!--  <a class="btn btn-success btn-sm " href="javascript:void(0)" id="addUser"> Add Role</a>    -->            
                @include('Theme::layouts.flash-message')
                <div class="table-responsive">
                    <table id="responsibility-list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="30px">Sr No.</th>
                            <th width="50px">Responsibility Name</th>
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
<div class="modal fade" id="add_responsibility_modal" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h4 class="modal-title" id="modelHeading1">Add Responsibility</h4>
<button type="button" class="close" data-dismiss="modal">&times;</button>
</div>

<div class="modal-body">
    <form action="{{ route("ajax.save.responsibility")}}" method="post" name ="main_responsibility_form"  id="main_responsibility_form">
        @csrf
        @include('Responsibility::responsibility')
    </form>
</div>
</div>
</div>
</div> 

<!-- edit -->
<div class="modal fade" id="edit_responsibility_modal" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h4 class="modal-title" id="modelHeading1">Edit Responsibility</h4>
<button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
    <form action="{{ route("ajax.save.responsibility")}}" method="post" name ="main_edit_responsibility_form"  id="main_edit_responsibility_form">
        @csrf
        <input type="hidden" name="id" id="responsibility_hid">
         @include('Responsibility::responsibility')
    </form> 
</div>

</div>
</div>
</div>

@endsection 

@section('page-js')
    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    <script src="{{asset('assets/js/tooltip.script.js')}}"></script>
    <script type="text/javascript">
     var getresponsibilitylisting =  function() {
        var columns = [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                        {data: 'responsibility',name: 'responsibility'},
                        // {data: 'address',name: 'address'},
                        // {data: 'phone',name: 'phone'},
                        {data: 'users',
                            mRender: function(data, type, full, meta){
                            if(data!='' && data!='NULL' && data!=undefined){
                              l_name = data['l_name'];
                            if(data['l_name'] == null && data['f_name'] == null){
                              l_name = '';
                              return '';
                            }
                            else
                            {

                              return data['f_name'] + ' ' + l_name;
                            }
                            } else { 
                                return ''; 
                            }    
                            },orderable: false
                            }, 
                        {data: 'updated_at',name:'updated_at'},
                        {data: 'action', name: 'action', orderable: false, searchable: false},
                    ]
    var table = util.renderDataTable('responsibility-list', "{{ route('responsibility_list') }}", columns, "{{ asset('') }}"); 
    }; 

$(document).ready(function() {
    responsibility.init();
    util.getToDoListData(0, {{getPageModuleName()}});

});
$(".module_id").on("change", function () {
     // alert("test"+$(this).val());
    util.updateSubModuleList(parseInt($(this).val()), ".component_id"); 
});
    </script>
@endsection