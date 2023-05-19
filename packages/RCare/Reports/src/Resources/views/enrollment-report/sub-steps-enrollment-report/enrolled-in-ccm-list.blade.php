@extends('Theme::layouts_2.to-do-master')
@section('page-css')
  
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Enrolled In CCM Details</h4>
        </div>
    </div>
    <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">   
            <div class="card-body">
                <form id="enrolled_form" name="enrolled_form"  action ="">
                @csrf
                <div class="form-row">
                     <div class="col-md-2 form-group mb-3">
                        <label for="practicename">Practice Name</label>
                        @selectpracticeswithAll("practices", ["id" => "practicesid","class" => "select2"])
                    </div>
                    
                    <div class="col-md-2 form-group mb-3">  
                          <label for="activedeactivestatus">Status</label> 
                          <select id="activedeactivestatus" name="activedeactivestatus" class="custom-select show-tick" >
                            <option value="" selected>All (Active,Suspended)</option> 
                            <option value="1">Active</option>
                            <option value="0">Suspended</option>
                          </select>                          
                    </div>
                      
                    <div class="col-md-1 form-group mb-3">
                       <button type="button" id="enrolledccmsearchbutton" class="btn btn-primary mt-4">Search</button>
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
                    <table id="enrolled_in_ccm_patient_list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="35px">Sr No.</th>
                            <th width="57px">EMR/EHR ID</th>
                            <th width="150px">Patient</th>
                            <th width="97px">DOB</th>
                            <th width="84px">Registered Date</th>
                            <th width="84px">Practice</th>                           
                            <th width="84px">Provider</th>   
                            <th width="84px">Status</th>      
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
       var getEnrolledPatientList = function(practice = null,activedeactivestatus =null) {
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
                                // {data: 'pscreatedat',type: 'date-dd-mm-yyyy', name: 'pscreatedat',"render":function (value) {
                                //     if (value === null) return "";
                                //         return util.viewsDateFormat(value);
                                //     }
                                // },
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
                                },
                                 {data: null,  
                                  mRender: function(data, type, full, meta){
                                    status = full['pstatus'];
                                      if(full['pstatus'] == 1){
                                          status = 'Active';
                                      } 
                                      if(full['pstatus'] == 0){
                                          status = 'Suspended'; 
                                      }
                                      if(data!='' && data!='NULL' && data!=undefined){
                                        return status;
                                      }
                                    },
                                    orderable: true, searchable: false
                                }
                            ];
            
           // debugger;
            if(practice=='')
            {
                practice=null;
            }
           if(activedeactivestatus==''){activedeactivestatus=null;}

                var url1 = "/reports/enrolledInCCM/search/"+practice+'/'+activedeactivestatus;
              //  console.log(url);
                var table1 = util.renderDataTable('enrolled_in_ccm_patient_list', url1, columns, "{{ asset('') }}");
              
        }
        $(document).ready(function() {
          
           getEnrolledPatientList();
            // $("[name='practices']").on("change", function () {
            //      util.updatePhysicianList(parseInt($(this).val()), $("#physician"))
            // });
            //    $("[name='modules']").val(3).attr("selected", "selected").change();

            
            util.getToDoListData(0, {{getPageModuleName()}});
          //  $(".patient-div").hide(); // to hide patient search select
        
           
        }); 


$('#enrolledccmsearchbutton').click(function(){
   
    var practice=$('#practicesid').val();
    var activedeactivestatus = $('#activedeactivestatus').val();
    // console.log(practice+" test");
     getEnrolledPatientList(practice,activedeactivestatus);
   
  
});


 
    </script>

@endsection
