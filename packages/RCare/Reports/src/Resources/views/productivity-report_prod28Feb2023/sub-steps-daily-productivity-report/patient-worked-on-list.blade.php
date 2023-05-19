@extends('Theme::layouts_2.to-do-master')
@section('page-css')
  
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Daily Worked On Patient's Details</h4>
        </div>
    </div>
    <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">   
            <div class="card-body">
                <form id="workedon_patient_details_form" name="workedon_patient_details_form"  action ="">
                @csrf
                <div class="form-row">
                     <div class="col-md-2 form-group mb-3">
                        <label for="practicename">Practice Name</label>
                        @selectpracticeswithAll("practices", ["id" => "practices_id","class" => "select2"])
                    </div>
                    <div class="col-md-1 form-group mb-3">
                       <button type="button" id="search-patient-workedon" class="btn btn-primary mt-4">Search</button>
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
                    <table id="Productivity-daily-Patient-Worked-On-List" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="35px">Sr No.</th>
                            <th width="57px">EMR/EHR ID</th>
                            <th width="150px">Patient</th>
                            <th width="97px">DOB</th>
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
<div id="app">
</div>

@endsection

@section('page-js')

<script type="text/javascript">
// Productivity practice billable patient
var getProductivityPatientWorkedOnList = function(practice=null,caremanager=null,modules=null,fromdate=null,todate=null) {
    var columns =  [
        { data: 'DT_RowIndex', name: 'DT_RowIndex'},
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
        { data: null, 
            mRender: function(data, type, full, meta){
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
                }
            },
            orderable: true
        },
        { data: 'dob', type: 'date-dd-mmm-yyyy', name: 'dob', 
            "render":function (value) {
                if (value === null) return "";
                return util.viewsDateFormat(value);
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
    var url ="/reports/productivity-daily-patient-worked-on/"+practice+'/'+caremanager+'/'+modules+'/'+fromdate+'/'+todate;
    // console.log(url +'workedon_patient_details_form');
    util.renderDataTable('Productivity-daily-Patient-Worked-On-List', url, columns, "{{ asset('') }}");
}

$(document).ready(function(){
    // getProductivitycounts();
    getProductivityPatientWorkedOnList();
    util.getToDoListData(0, {{getPageModuleName()}});
  //  $(".patient-div").hide(); // to hide patient search select
}); 

$('#search-patient-workedon').click(function(){
    var practice_id = $('#practices_id').val();
    if(practice_id==''){
        var practice='null';
    }else{
        var practice= $('#practices_id').val();
    }
    var module_id =$('#modules').val();
      if(module_id==''){
        var modules ='null';
      }else{
        var modules=$('#modules').val(); 
      }
      var caremanager = "null";
      var fromdate ="null"; 
      var todate ="null";  
    getProductivityPatientWorkedOnList(practice,caremanager,modules,fromdate,todate);
});

</script>


@endsection
