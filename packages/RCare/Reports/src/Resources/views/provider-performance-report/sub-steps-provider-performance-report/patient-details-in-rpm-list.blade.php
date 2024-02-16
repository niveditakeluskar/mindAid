@extends('Theme::layouts_2.to-do-master')
@section('page-css')
  
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Provider Performance Patients Details In RPM</h4>
        </div>
    </div>
    <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card text-left">   
            <div class="card-body">              
                <div class="form-row">
                   <h6 class="card-title mb-3"> Practice : </h6>&nbsp;&nbsp;&nbsp;<?php echo $practicename[0]->name;?>
                    <input type="hidden" name="practiceid" id="practiceid" value="{{$practiceid}}">
                    <input type="hidden" name="providerid" id="providerid" value="{{$providerid}}">     
                </div>                
            </div>
        </div>
    </div>
     <div class="col-md-6 mb-4">
        <div class="card text-left">   
            <div class="card-body">              
                <div class="form-row">                
                   <h6 class="card-title mb-3">Provider : </h6>&nbsp;&nbsp;&nbsp;<?php if($providername != ''){ echo $providername[0]->name; } ?>  
                </div>                
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
                    <table id="in_rpm_patient_list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="35px">Sr No.</th>
                            <th width="57px">EMR/EHR ID</th>
                            <th width="150px">Patient</th>
                            <th width="97px">DOB</th>   
                              <th width="97px">Email</th>   
                              <th width="35px">Action</th>
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
       var getEnrolledPatientList = function(practice = null,provider = null) {
            var columns =  [
                                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                                {data: null,
                                     mRender: function(data, type, full, meta){
                                        practice_emr = full['pppracticeemr'];
                                        if(full['pppracticeemr'] == null){
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
                                {data: 'pdob', type: 'date-dd-mmm-yyyy', name: 'pdob', "render":function (value) {
                                    if (value === null) return "";
                                        return moment(value).format('MM-DD-YYYY');
                                    }
                                },
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        email = full['pemail'];
                                        if(full['pemail'] == null){
                                            email = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                             return email;
                                        }
                                    },
                                    orderable: true
                                },
                                {data: 'editPatient', name: 'editPatient'},
                            ];
            
           // debugger;
            if(practice=='')
            {
                practice=null;
            }
             if(provider=='')
            {
                provider=null;
            }
                var url1 = "/reports/get-data-patient-details-in-rpm/"+practice+"/"+provider;
              //  console.log(url);
                var table1 = util.renderDataTable('in_rpm_patient_list', url1, columns, "{{ asset('') }}");
              
        }
        $(document).ready(function() {
           var practiceid=$('#practiceid').val();
           var providerid=$('#providerid').val();
           getEnrolledPatientList(practiceid,providerid);
            // $("[name='practices']").on("change", function () {
            //      util.updatePhysicianList(parseInt($(this).val()), $("#physician"))
            // });
            //    $("[name='modules']").val(3).attr("selected", "selected").change();

            
            util.getToDoListData(0, {{getPageModuleName()}});
          //  $(".patient-div").hide(); // to hide patient search select
        
        }); 


$('#enrolledccmsearchbutton').click(function(){
   
    var practice=$('#practicesid').val();
    console.log(practice+" test");
     getEnrolledPatientList(practice);
   
  
});


 
    </script>

@endsection
