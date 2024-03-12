@extends('Theme::layouts_2.to-do-master')
@section('page-css')
    <link rel="stylesheet" href="{{asset('assets/js/vendor/jquery-datetimepicker/jquery.datetimepicker.css')}}">
     <link rel="stylesheet" href="{{asset('assets/js/vendor/jquery-datetimepicker/jquery.datetimepicker.min.css')}}">
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Daily Billable Report</h4>
        </div>
    </div>
    <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">   
            <div class="card-body">
                <form id="daily_report_form" name="daily_report_form"  action ="">
                @csrf
                <div class="form-row">
                    <div class="col-md-3 form-group mb-3">
                        <label for="practicename">Practice Name</label>
                        @selectpractices("practices", ["id" => "practices"])
                    </div>
                    <div class="col-md-3 form-group mb-3">
                        <label for="provider">Provider Name</label>
                        @selectpracticesphysician("provider",["id" => "physician"])
                    </div>
                    <!-- <div class="col-md-3 form-group mb-3">
                        <label for="patient">Patient Name</label>
                        @select("Patient", "patient_id", [], ["id" => "patient"])
                    </div> -->
                    <div class="col-md-2 form-group mb-3">
                        <label for="module">Module Name</label>
                        @selectOrgModule("modules",["id" => "modules"])
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <label for="month">date</label>
                        @date('date',["id" => "date"])
                        <!-- @select("Patient", "patient_id", [], ["id" => "patient"]) -->
                    </div>
                    <div class="col-md-1 form-group mb-3">
                        <label for="month">Time</label>
                        @time('time',["id" => "time"])
                        <!-- @select("Patient", "patient_id", [], ["id" => "patient"]) -->
                    </div>
                    <div class="col-md-1 form-group mb-3">
                       <button type="button" id="searchbutton" class="btn btn-primary mt-4">Search</button>
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
                    <table id="patient-list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="35px">Sr No.</th>
                            <th width="10px">EMR/EHR ID</th>
                            <th width="205px">Patient</th>
                            <th width="97px">DOB</th>
                            <th width="97px">Date of service</th>
                            <th width="220px">Conditions</th>                           
                            <th >Provider</th>
                            <th width="75px">Minutes Spent</th>
                            
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
    <script src="{{asset('assets/js/vendor/jquery-datetimepicker/jquery.datetimepicker.full.min.js')}}"></script>
      <script src="{{asset('assets/js/vendor/jquery-datetimepicker/jquery.datetimepicker.js')}}"></script>
        <script src="{{asset('assets/js/vendor/jquery-datetimepicker/jquery.datetimepicker.min.js')}}"></script>
          <script src="{{asset('assets/js/vendor/jquery-datetimepicker/jquery.datetimepicker.full.js')}}"></script>
    <script type="text/javascript">
       var getDailyPatientList = function(practice = null,provider=null,modules=null,date=null,time=null) {
            var columns =  [
                                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                                {data: null,
                                     mRender: function(data, type, full, meta){
                                        practice_emr = full['practice_emr'];
                                        if(full['practice_emr'] == null){
                                            practice_emr = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return practice_emr;
                                        }
                                    },
                                    orderable: false
                                },
                                {data: 'fname',name: 'fname', mRender: function(data, type, full, meta){
                                    m_Name = full['mname'];
                                    if(full['mname'] == null){
                                        m_Name = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                        if(full['profile_img']=='' || full['profile_img']==null) {
                                            return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname'];
                                        } else {
                                            return ["<img src='"+full['profile_img']+"' width='40px' height='25px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname'];
                                        }
                                        // return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname'];
                                    }
                                },
                                orderable: false
                                },
                                {data: 'dob', type: 'date-dd-mmm-yyyy', name: 'dob', "render":function (value) {
                                    if (value === null) return "";
                                        return util.viewsDateFormat(value);
                                    }
                                },
                                {data: 'rec_date',type: 'date-dd-mm-yyyy', name: 'rec_date', "render":function (value) {
                                    if (value === null) return "";
                                        return util.viewsDateFormat(value);
                                    }
                                },                                
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        condition = full['condition'];
                                        if(full['condition'] == null){
                                            condition = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return condition;
                                        }
                                    },
                                    orderable: false
                                },
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        provider_name = full['provider_name'];
                                        if(full['provider_name'] == null){
                                            provider_name = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return provider_name;
                                        }
                                    },
                                    orderable: false
                                },

                                {data: 'totaltime', name: 'totaltime', orderable: false}
                            ];
            
           // debugger;
            if(practice=='')
            {
                practice=null;
            } if(provider=='')
            {
                provider=null;
            }
             if(modules=='')
            {
                modules=null;
            } if(date=='')
            {
                date=null;
            } if(time=='')
            {
                time=null;
            }
				var url = "/ccm/daily-report/search/"+practice+'/'+provider+'/'+modules+'/'+date+'/'+time;
                console.log(url);
				var table1 = util.renderDataTable('patient-list', url, columns, "{{ asset('') }}");
              
        }
        $(document).ready(function() {
			getDailyPatientList();
            $("[name='practices']").on("change", function () {
                 util.updatePhysicianList(parseInt($(this).val()), $("#physician"))
            });
               $("[name='modules']").val(3).attr("selected", "selected").change();

            
            util.getToDoListData(0, {{getPageModuleName()}});
          //  $(".patient-div").hide(); // to hide patient search select
        
           
        }); 


$('#searchbutton').click(function(){
    var practice=$('#practices').val();
     var provider=$('#physician').val();
     var modules=$('#modules').val();
      var date=$('#date').val();
       var time=$('#time').val();
      
 getDailyPatientList(practice,provider,modules,date,time);
  
});


 
    </script>
@endsection