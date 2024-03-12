@extends('Theme::layouts_2.to-do-master')
@section('page-css')
  
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Care Managers List</h4>
        </div>
    </div>
    <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">   
            <div class="card-body">
                <form id="productivity_caremanager_report_form" name="productivity_caremanager_report_form"  action ="">
                @csrf 
                <div class="form-row">
                     <div class="col-md-2 form-group mb-3">
                        <label for="caremanagersname">Care Manager Name</label>
                        @selectcaremanagerwithAll("caremanager_id", ["id" => "caremanager_id", "placeholder" => "Select Care Manager","class" => "select2"])                    
                    </div>
<!--                     <div class="col-md-2 form-group mb-3">
                        <label for="practicename">Caremanager Name</label>
                        @selectpractices("practices", ["id" => "practicesid","class" => "select2"])
                    </div -->

                    <div class="col-md-1 form-group mb-3">
                       <button type="button" id="search-caremanager" class="btn btn-primary mt-4">Search</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-left">
            <div class="card-body">
                @include('Theme::layouts.flash-message')
                <div class="table-responsive">
                    <table id="Productivity-CM-List" class="display table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                            <th>Sr No.</th>
                            <th>User</th>
                            <th>Email</th>  
                            <th>Employee Id</th>
                            <th>Report To</th>
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
<div id="app"></div>
@endsection

@section('page-js')
    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    <script src="{{asset('assets/js/tooltip.script.js')}}"></script>
    <script type="text/javascript">
// Productivity practice billable patient
var getProductivityCMList = function(caremanager=null) {
       var columns =  [
        {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'profile_img',
                mRender: function(data, type, full, meta){
                //console.log(full['f_name']);
                l_Name = full['l_name'];
                f_Name =full['f_name'];
                if(full['l_name'] == null){
                    l_Name = '';
                }
                if(full['f_name'] == null){
                    f_Name = '';
                }
                if(data!='' && data!='NULL' && data!=undefined){
                        return ["<img src={{ URL::to('/') }}/images/usersRcare/" + data + " width='40px' class='user-image' />"]+' '+f_Name+' '+l_Name;
                }else { 
                        return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+f_Name+' '+l_Name;
                }

                },
                orderable: true
            },
            {data: 'email', name: 'email'},
            {data: 'emp_id', name: 'emp_id'},
            {data: 'report_to_users',   
                render: function(data, type, full, meta){
                    reports_to = full['report_to_users'];
                    if(data!='' && data!='NULL' && data!=undefined){
                        return full['report_to_users']; 
                    } else {
                        return "-";
                    }
                }
            },
    ];
    if(caremanager=='')
    {
        caremanager=null;
    }
    var url ="/reports/productivity-caremanager/"+caremanager;
    util.renderDataTable('Productivity-CM-List', url, columns, "{{ asset('') }}");
}

$(document).ready(function() {
    // getProductivitycounts();
    getProductivityCMList();
    util.getToDoListData(0, {{getPageModuleName()}});
  //  $(".patient-div").hide(); // to hide patient search select
}); 


$('#search-caremanager').click(function(){
    var caremanager_id=$('#caremanager_id').val();
    if (caremanager_id==''){
        caremanager ='null';
    }else{
        caremanager=$('#caremanager_id').val();
    }
    getProductivityCMList(caremanager);
});
</script>
@endsection