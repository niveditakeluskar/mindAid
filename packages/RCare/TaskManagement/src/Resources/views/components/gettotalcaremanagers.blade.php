@extends('Theme::layouts_2.to-do-master')
@section('page-title')
    Care Manager - 
@endsection
@section('page-css')
  
@endsection 
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Care Manager</h4>
        </div>
    </div>
    <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>

<div class="row">
    <div class="card mb-4 col-md-12">  
        <div class="form-group" id="caremanager" name="caremanager" > 
            <div class="card-body">
                @include('Theme::layouts.flash-message')
                <div class="form-row">
                    <div class="col-md-6 form-group">  
                        <label for="practicename">Practice</label>                        
                        @selectpracticespcp("practices", ["class" => "select2","id" => "practicesthree"])
                        <!-- selectpracticeswithoutNone -->
                    </div>
                    <div class="col-md-1 form-group mb-3">
                        <button type="button" id="searchbuttonthree" class="btn btn-primary mt-4">Search</button>
                    </div>
                </div>
            </div>
        </div>                                         
    </div>        
</div>
<div class="row">
    <div class="card col-md-12">  
        <div class="form-group" id="caremanager" name="caremanager" > 
            <div class="card-body">
                <div class="table-responsive">
                    <table id="cm-list" class="display table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th width="35px">Sr No.</th>
                                <th width="97px">Employee Id</th>                  
                                <th width="205px">Care Manager Name</th>
                                <th width="97px">Email</th>
                                <th width="97px">No of Patients</th>
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
<div id="app">
</div>
@endsection

@section('page-js')
    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    <script src="{{asset('assets/js/tooltip.script.js')}}"></script>
    <script src="{{asset(mix('assets/js/laravel/taskManage.js'))}}"></script>
    <script type="text/javascript">
        var getCMlist=function(practice=null) {
            var columns =  [
                { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                { data: 'emp_id', name: 'emp_id'},
                { data: null, 
                    mRender: function(data, type, full, meta){
                        m_Name ="";
                       
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['profile_img']=='' || full['profile_img']==null) {
                                return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['f_name']+' '+m_Name+' '+full['l_name'];
                            } else {
                                return ["<img src='"+full['profile_img']+"' width='40px' height='25px' class='user-image' />"]+' '+full['f_name']+' '+m_Name+' '+full['l_name'];
                            }
                        }
                    },
                    orderable: true
                },
                { data:null,
                    mRender: function(data, type, full, meta){
                        email = full['email'];
                        if(full['email'] == null){
                            email = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            return email;
                        }
                    },
                    orderable: true
                },
                { data:null,
                    mRender: function(data, type, full, meta){
                        name = full['count'];
                        if(full['count'] == null){
                            name = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            return name;
                        }
                    },
                    orderable: true
                },
            ];
            // debugger;
            if(practice==''){practice=null;} 
            // var url = "/patients/patients-assignment/search/"+practice+'/'+provider+'/'+time+'/'+care_manager_id+'/'+timeoption;
            //var url = "/patients/patients-assignment/nonassignedpatients/"+practice;
            var url ="/task-management/patients-assignment/cmlist/"+practice;
            // console.log(url);
            util.renderDataTable('cm-list', url, columns, "{{ asset('') }}"); 
        }

        $(document).ready(function() {
            taskManage.init(); 
            getCMlist();
        });
        $('#searchbuttonthree').click(function(){
            var practice=$('#practicesthree').val();
            getCMlist(practice); 
        });
    </script>
    @endsection 