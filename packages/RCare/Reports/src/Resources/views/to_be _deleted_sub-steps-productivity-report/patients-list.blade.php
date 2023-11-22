@extends('Theme::layouts_2.to-do-master')
@section('page-css')
  
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Total Patient's Details</h4>
        </div>
    </div>
    <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">   
            <div class="card-body">
                <form id="total_patient_details_form" name="total_patient_details_form"  action ="">
                 @csrf
                <div class="form-row">
                    <div class="col-md-2 form-group mb-3">
                        <label for="practicename">Practice Name</label>
                        @selectpracticeswithAll("practices", ["id" => "practices","class" => "select2"])
                    </div>
                  
                 <!--  <div class="col-md-2 form-group mb-3">
                        <label for="provider">Provider</label>
                        @selectpracticesphysician("provider",["id" => "physician"])
                    </div>
                  <div class="col-md-3 form-group mb-3">
                        <label for="patient">Patient Name</label>
                        @select("Patient", "patient_id", [], ["id" => "patient"])
                    </div> 
                    <div class="col-md-2 form-group mb-3">
                        <label for="module">Module</label>
                        @selectOrgModule("modules",["id" => "modules"])
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <label for="month">Date</label>
                        @date('date',["id" => "date"])
                       
                    </div>
                     <div class="col-md-2 form-group mb-2"> 
                          <select id="timeoption" class="custom-select show-tick" style="margin-top: 23px;">
                            <option value=">">Greater than</option>
                             <option value="<" selected>Less than</option>
                              <option value="=">Equal to</option>
                          </select>                         
                    </div>
                    <div class="col-md-1 form-group mb-3">                      
                        <label for="time">Time</label>
                               @text("time", ["id" => "time", "placeholder" => "hh:mm:ss"])
                    </div> -->
                    <div class="col-md-1 form-group mb-3">
                       <button type="button" id="search-patients" class="btn btn-primary mt-4">Search</button>
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
                    <table id="total_patients_list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="35px">Sr No.</th>
                            <th width="57px">EMR/EHR ID</th>
                            <th width="150px">Patient</th>
                            <th width="97px">DOB</th>
                            <th width="84px">Registered Date</th>
                            <th width="84px">Practice</th>                           
                            <th width="84px">Provider</th>         
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
       var getProductivityPatientsList = function(practice=null,caremanager=null,fromdate=null,todate=null) {
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
                    orderable: true
                },
                {data: null, mRender: function(data, type, full, meta){
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
                orderable: true
                },
                // {data: null, type: 'date-dd-mmm-yyyy', "render":function (value) {
                //     if (value === null) return "";
                //         return util.viewsDateFormat(value);
                //     }
                // },
                {data: 'pdob', type: 'date-dd-mmm-yyyy', name: 'pdob', "render":function (value) {
                                    if (value === null) return "";
                                         return moment(value).format('MM-DD-YYYY');
                                    }
                 },
                {data: 'registered_date',type: 'date-dd-mm-yyyy', name: 'registered_date',"render":function (value) {
                    if (value === null) return "";
                         return moment(value).format('MM-DD-YYYY');
                    }
                },     
               {data:null,
                    mRender: function(data, type, full, meta){
                        name = full['practice'];
                        if(full['practice'] == null){
                            name = '';
                        }
                        
                        if(data!='' && data!='NULL' && data!=undefined){
                            return name;
                        }
                    },
                    orderable: true
                },
                {data:null,
                    mRender: function(data, type, full, meta){
                        name = full['provider'];
                        if(full['provider'] == null){
                            name = '';
                        }
                        if(data!='' && data!='NULL' && data!=undefined){
                            return name;
                        }
                    },
                    orderable: true
                }
              
            ];
            
            if(practice==''){practice=null;} 
            if(fromdate==''){ fromdate=null; }
            if(todate=='')  { todate=null; }
            if(caremanager==''){ caremanager=null; }
           var url ="/reports/productivity-patients/"+practice+'/'+caremanager+'/'+fromdate+'/'+todate;
          //  console.log(url);
            var table1 = util.renderDataTable('total_patients_list', url, columns, "{{ asset('') }}");
              
        }
        $(document).ready(function() {
           getProductivityPatientsList();
            util.getToDoListData(0, {{getPageModuleName()}});
          //  $(".patient-div").hide(); // to hide patient search select   
        }); 


$('#search-patients').click(function(){
    var practice=$('#practices').val();
    var caremanager =$('#caremanager_id').val();
    var fromdate =$('#fromdate').val();
    var todate =$('#todate').val();
     // alert(practice);
     getProductivityPatientsList(practice,caremanager,fromdate,todate);
});


 
    </script>

@endsection
