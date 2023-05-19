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
                        @selectpracticeswithAll("practices", ["id" => "practicesid","class" => "select2"])
                    </div>
                  
                
                    <div class="col-md-1 form-group mb-3">
                       <button type="button" id="totalpatientssearchbutton" class="btn btn-primary mt-4">Search</button>
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
<div id="app">
</div>  

@endsection

@section('page-js')
    <script src="{{asset('assets/js/vendor/datatables.min.js')}}"></script>
    <script src="{{asset('assets/js/datatables.script.js')}}"></script>
    <script src="{{asset('assets/js/tooltip.script.js')}}"></script>
   

 <script type="text/javascript">
       var getTotalPatientList = function(practice = null) {
            var columns =  [
                                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                                {data: null,
                                     mRender: function(data, type, full, meta){
                                        practice_emr = full['pracpracticeemr'];
                                        if(full['pracpracticeemr'] == null){
                                            practice_emr = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return practice_emr;
                                        }
                                    },
                                    orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    m_Name = full['pmname'];
                                    if(full['pmname'] == null){
                                        m_Name = '';
                                    }
                                    if(data!='' && data!='NULL' && data!=undefined){
                                        if(full['pprofileimg']=='' || full['pprofileimg']==null) {
                                            return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['pfname']+' '+m_Name+' '+full['plname'];
                                        } else {
                                            return ["<img src='"+full['pprofileimg']+"' width='40px' height='25px' class='user-image' />"]+' '+full['pfname']+' '+m_Name+' '+full['plname'];
                                        }
                                        // return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname'];
                                    }
                                },
                                orderable: true
                                },
                               
                                // {data:null,
                                //     mRender: function(data, type, full, meta){
                                //         dob = full['pdob'];
                                //         if(full['pdob'] == null){
                                //             dob = '';
                                //         }
                                //         if(data!='' && data!='NULL' && data!=undefined){
                                //              return util.viewsDateFormat(dob);
                                //         }
                                //     },
                                //     orderable: true
                                // },
                                // {data: 'pscreatedat',type: 'date-dd-mm-yyyy', name: 'pscreatedat',"render":function (value) {
                                //     if (value === null) return "";
                                //         return util.viewsDateFormat(value);
                                //     }  
                                // },  
                                {data: 'pdob', type: 'date-dd-mmm-yyyy', name: 'pdob', "render":function (value) {
                                    if (value === null) return "";
                                        return moment(value).format('MM-DD-YYYY');
                                    }
                                },
                                {data: 'pscreatedat', type: 'date-dd-mmm-yyyy', name: 'pscreatedat', "render":function (value) {
                                    if (value === null) return "";
                                        return moment(value).format('MM-DD-YYYY');
                                    }
                                }, 
                               {data:null,
                                    mRender: function(data, type, full, meta){
                                        name = full['prapracticename'];
                                        if(full['prapracticename'] == null){
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
                                        name = full['prprovidername'];
                                        if(full['prprovidername'] == null){
                                            name = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return name;
                                        }
                                    },
                                    orderable: true 
                                }
                              
                            ];
            
           // debugger;
            if(practice=='')
            {
                practice=null;
            }
                var url1 = "/reports/totalpatients/search/"+practice;
              //  console.log(url);
                var table1 = util.renderDataTable('total_patients_list', url1, columns, "{{ asset('') }}");
              
        }
        $(document).ready(function() {
          
           getTotalPatientList(); 
            util.getToDoListData(0, {{getPageModuleName()}});
          //  $(".patient-div").hide(); // to hide patient search select
        
           
        }); 


$('#totalpatientssearchbutton').click(function(){
   
    var practice=$('#practicesid').val();
    console.log(practice+" test");
     getTotalPatientList(practice);
   
  
});


 
    </script>

@endsection
