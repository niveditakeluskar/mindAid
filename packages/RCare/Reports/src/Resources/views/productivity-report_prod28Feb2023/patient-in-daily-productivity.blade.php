@extends('Theme::layouts_2.to-do-master')
@section('page-title')
    Patient In Daily Productivity Report 
@endsection
@section('page-css')
    
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Patient In Daily Productivity Report</h4>   
        </div>
    </div>
    <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>
<!-- add filter here -->
 <div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-rightleft"> 
            <div class="card-body">
              <input type="hidden" id="p_id" value="<?php echo $practices;?>">
              <input type="hidden" id="c_id" value="<?php echo $caremanager; ?>">
              <input type="hidden" id="f_id" value="<?php echo $fromdate; ?>">
              <input type="hidden" id="t_id" value="<?php echo $todate; ?>">
              <input type="hidden" id="m_id" value="<?php echo $module_id; ?>">
              <input type="hidden" id="p_status" value="<?php echo $pstatus; ?>">
                <div class="table-responsive">
                    <table id="patient-productivity-list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                        <th width="35px">Sr No.</th>
                            <th width="10px">Patient</th>
                            <th width="10px">Practice</th>
                            <th width="10px">Provider</th>
                            <th width='10px'>Caremanager Name</th>
                            <th width="75px">Minutes Spent</th>
                            <th width="75px">Billable</th>
                            <th width="75px">Status</th>
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
     
var getPatientProductivityList = function(practices=null,care_manager=null,f_date=null,t_date=null,module_id=null,pstatus=null) {
var columns =  [
    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
    // {data: null, mRender: function(data, type, full, meta){
    //     m_Name = full['mname'];
    //     if(full['mname'] == null){ 
    //         m_Name = '';
    //     }
    //     if(data!='' && data!='NULL' && data!=undefined){
    //         if(full['profile_img']=='' || full['profile_img']==null) {
    //             return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname'];
    //         } else {
    //             return ["<img src='"+full['profile_img']+"' width='40px' height='25px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname'];
    //         }
    //         // return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname'];
    //     }
    // },
    // orderable: false
    // },

    {data: null, mRender: function(data, type, full, meta){
        
        if(data!='' && data!='NULL' && data!=undefined){
            if(full['profile_img']=='' || full['profile_img']==null) {
                return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['patient_name'];
            } else {
                return ["<img src='"+full['profile_img']+"' width='40px' height='25px' class='user-image' />"]+' '+full['patient_name'];
            }
            // return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname'];
        }
    },
    orderable: false
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
    },
    {data:null,
        mRender: function(data, type, full, meta){
            name = full['caremanager'];
            if(full['caremanager'] == null){
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
            totaltime = full['totaltime'];
            if(full['totaltime'] == null){
                totaltime = '';
            }
            if(data!='' && data!='NULL' && data!=undefined){
                return parseFloat(totaltime).toFixed(2);

            }
        },
        orderable: true
    },

    {data:null,
        mRender: function(data, type, full, meta){
            billable = full['billable'];
            if(full['billable'] == null){
                billable = '';
                
            }if(data!='' && data!='NULL' && data!=undefined){
                return billable;
            }
        },
        orderable: true
    },  
    
//     {data: null, 
//   mRender: function(data, type, full, meta){
//     status = full['pstatus'];
//       if(full['pstatus'] == 1){
//           status = 'Active';
//       }
//       if(full['pstatus'] == 0){
//           status = 'Suspended'; 
//       }
//       if(full['pstatus'] == 2){ 
//           status = 'Deactived';
//       }
//       if(full['pstatus'] == 3){ 
//           status = 'Deceased'; 
//       }
//       if(data!='' && data!='NULL' && data!=undefined){
//         return status;
//       }
//     },
//     orderable: true, searchable: false
// },

{data:null,
        mRender: function(data, type, full, meta){
            psmodule = full['psmodule'];
            if(full['psmodule'] == null){
                psmodule = '';
                
            }if(data!='' && data!='NULL' && data!=undefined){
                return psmodule;
            }
        },
        orderable: true
    }, 

];
// var url = "/reports/patient-in-daily-productivity-report-search/"+ f_date + "/" +t_date+"/"+care_manager+"/"+practices+"/"+module_id+"/"+pstatus;
var url = "/reports/patient-in-daily-productivity-report-search/"+ f_date + "/" +t_date+"/"+care_manager+"/"+practices+"/"+pstatus;
// //var url ='';
// console.log(url +'Productivity patient-productivity-list');
table1 = util.renderDataTable('patient-productivity-list', url, columns, "{{ asset('') }}");

}

 $(document).ready(function() {
  var p_id =$("#p_id").val();
  var c_id =$("#c_id").val();
  var f_id =$("#f_id").val();
  var t_id =$("#t_id").val();
  var m_id =$("#m_id").val();
  var pstatus = $("#p_status").val();
  //getPatientProductivityList(p_id,c_id,f_id,t_id,pstatus);
  getPatientProductivityList(p_id,c_id,f_id,t_id,m_id,pstatus);  
            
    
}); 

   
// $('#month-search').click(function(){
//   //debugger
//     $('#time').removeClass("is-invalid");
//     var practice=$('#practices').val();
//     var fromdate1=$('#fromdate').val();
//     var todate1=$('#todate').val();
//     var care_manager_id=$('#care_manager_id').val();
//     getPatientProductivityList(practice,care_manager_id,fromdate1,todate1);
  
// });
 
</script>
@endsection