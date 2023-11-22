@extends('Theme::layouts_2.to-do-master')
@section('page-css')
    
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Monthly Billable Report</h4>
        </div>
    </div>
    <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>
@include('Reports::monthly-billing-filter')
 <div class="row mb-4">
    <div class="col-md-12 mb-4">
        <div class="card text-rightleft">
            <div class="card-body">
                <div class="alert alert-success" id="success-alert" style="display: none;">
                  <button type="button" class="close" data-dismiss="alert">x</button>
                  <strong>Call data saved successfully! </strong><span id="text"></span>
               </div>
                @include('Theme::layouts.flash-message')
                <div class="table-responsive">
                    <table id="patient-list" class="display table table-striped table-bordered">
                    <thead>
                        <tr>
                        <th width="35px">Sr No.</th>
                        <th width="100px">Provider</th>
                        <th width="10px">EMR</th>
                        <th width="225px">Patient First Name</th>
                        <th width="225px">Patient Last Name</th>
                        <th width="97px">DOB</th>
                        <th width="97px">DOS</th>
                            <!--th width="220px">Conditions</th>                           
                            <th width="150px" >Practice</th>
                            <th width="100px">Provider</th>
                            <th width="75px">Minutes Spent</th-->
                            <th width="100px">CPT Code</th>
                            <th width="50px">Units</th>
                            <th width="100px">Diagnosis</th>
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

     $(document).ready(function () {
    // var tdate = new Date();
    // var twoDigitMonth = ((tdate.getMonth().length + 1) === 1) ? (tdate.getMonth() + 1) : '0' + (tdate.getMonth() + 1);
    // var yyyy = tdate.getFullYear(); //yields year
    // var currentDate = yyyy + "-" + twoDigitMonth;
    // $('#monthly').val(currentDate);
    // $('#monthlyto').val(currentDate);

    // alert(currentDate); 

    function getMonth(date) {
  var month = date.getMonth() + 1;
  return month < 10 ? '0' + month : '' + month; // ('' + month) for string result
}
var c_month = (new Date().getMonth() + 1).toString().padStart(2, "0");
var c_year = new Date().getFullYear();
var current_MonthYear = c_year+'-'+c_month;
$("#monthly").val(current_MonthYear);
$("#monthlyto").val(current_MonthYear);

  });
  
        var getMonthlyBillingPatientList = function(practice = null,provider=null,modules=null,monthly=null,monthlyto=null) {
            var columns =  [
                                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        provider_name = full['prprovidername'];
                                        if(full['prprovidername'] == null){
                                            provider_name = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return provider_name;
                                        }
                                    },
                                    orderable: true
                                },
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
                                    
                                    if(data!='' && data!='NULL' && data!=undefined){
                                        if(full['pprofileimg']=='' || full['pprofileimg']==null) {
                                            return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['pfname'];
                                        } else {
                                            return ["<img src='"+full['pprofileimg']+"' width='40px' height='25px' class='user-image' />"]+' '+full['pfname'];
                                        }
                                        // return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+full['lname'];
                                    }
                                },
                                orderable: true
                                },
                                {data: null, mRender: function(data, type, full, meta){
                                    
                                    if(data!='' && data!='NULL' && data!=undefined){
                                       
                                            return full['plname'];
                                        
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
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        dob = full['pdob'];
                                        if(full['pdob'] == null){
                                            dob = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return  moment(dob).format('MM-DD-YYYY');
                                        }
                                    },
                                    orderable: true
                                },
                                {data: 'ccsrecdate',type: 'date-dd-mm-yyyy', name: 'ccsrecdate',"render":function (value) {
                                    if (value === null) return "";
                                        return util.viewsDateFormat(value);
                                    }
                                },  
                                // {data:null,
                                //     mRender: function(data, type, full, meta){
                                //         rec_date = full['rec_date'];
                                //         if(full['rec_date'] == null){
                                //             rec_date = '';
                                //         }
                                //         if(data!=' ' && data!='NULL' && data!=undefined && data!=null){
                                //              //return util.viewsDateFormat(rec_date);
                                //              if (rec_date === null) return "";
                                //         return util.viewsDateFormat(value);
                                //     }
                                       
                                //     },
                                //     orderable: false
                                // },                              
                                /*{data:null,
                                    mRender: function(data, type, full, meta){
                                        condition = full['pdcondition'];
                                        if(full['pdcondition'] == null){
                                            condition = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return condition;
                                        }
                                    },
                                    orderable: true
                                },
                               
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        practice_name = full['pracpracticename'];
                                        if(full['pracpracticename'] == null){ 
                                            practice_name = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return practice_name;
                                        }
                                    },
                                    orderable: true
                                },

                                

                                 
                                {data: 'billingcode', name: 'billingcode', orderable: true},*/
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        billingcode = full['billingcode'];
                                        if(full['billingcode'] == 000){ 
                                            billingcode = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return billingcode;
                                        }
                                    },
                                    orderable: true
                                },

                               /* {data:null,
                                    mRender: function(data, type, full, meta){
                                        cptcode = '';
                                        if((full['ptrtotaltime'] >= '00:20:00') &&(full['ptrtotaltime'] < '00:40:00')){
                                            cptcode = '99490';
                                        }
                                        if((full['ptrtotaltime'] >= '00:40:00') &&(full['ptrtotaltime'] < '00:60:00')){
                                            cptcode = '99490'+'<br>'+'G2058';
                                        }
                                        if((full['ptrtotaltime'] >= '00:60:00') &&(full['ptrtotaltime'] < '01:30:00')){
                                            cptcode = '99490'+'<br>'+'G2058';
                                        }
                                        if(full['ptrtotaltime'] >= '01:30:00'){
                                            cptcode = '99487'+'<br>'+'99489';
                                        }

                                        if(data!='' && data!='NULL' && data!=undefined){ 
                                            return cptcode;
                                        } 
                                    },
                                    orderable: true
                                },*/

                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        unit = '';
                                        if((full['ptrtotaltime'] >= '00:20:00') &&(full['ptrtotaltime'] < '00:40:00')){
                                            unit = '1';
                                        }
                                        if((full['ptrtotaltime'] >= '00:40:00') &&(full['ptrtotaltime'] < '00:60:00')){
                                            unit = '1';
                                        }
                                        if((full['ptrtotaltime'] >= '00:60:00') &&(full['ptrtotaltime'] < '01:30:00')){
                                            unit = '2';
                                        }
                                        if(full['ptrtotaltime'] >= '01:30:00'){ 
                                            unit = '1';
                                        }

                                        if(data!='' && data!='NULL' && data!=undefined){ 
                                            return unit;
                                        } 
                                    },
                                    orderable: true 
                                },
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        daignosis = full['pdcode'];
                                        if(full['pdcode'] == null){
                                            condition = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return daignosis;
                                        }
                                    },
                                    orderable: true
                                },
                               



                            ];
                if(practice=='')
                {
                    practice=null;
                }
                if(provider=='')
                {
                    provider=null;
                }
                if(monthly=='')
                {
                    monthly=null;
                }
                if(monthlyto=='')
                {
                    monthlyto=null;
                }
                 var url = "/reports/monthly-billing-report/search/"+practice+"/"+provider+"/"+modules+"/"+monthly+"/"+monthlyto;      
                 console.log(url);          
                var table1 = util.renderDataTable('patient-list', url, columns, "{{ asset('') }}"); 

        }
        $(document).ready(function() {

           
            var practice = $('#practices').val();
           // alert(practice);
          //  report.init();
          getMonthlyBillingPatientList();
        //   getMonthlyBillingPatientList(practice,provider,modules,monthly,monthlyto); 
      
            util.getToDoListData(0, {{getPageModuleName()}});
            // $(".patient-div").hide(); // to hide patient search select

            $("[name='practices']").on("change", function () {
                 util.updatePhysicianListWithoutOther(parseInt($(this).val()), $("#physician"))
            });

            $("[name='physician']").on("change", function () {
                 // util.updatePhysicianList(parseInt($(this).val()), $("#physician"))
            });

            $("[name='modules']").val(3).attr("selected", "selected").change();
            
            $("[name='modules']").on("change", function () {
                // util.updatePatientList(parseInt($(this).val()), {{ getPageModuleName() }}, $("#patient"));
            });

            $("[name='monthly']").on("change", function (){    
            });

            $("[name='monthlyto']").on("change", function (){    
            });
            var modules = $('#modules').val();
            // alert("start");
            // alert(modules);
             
        }); 

        $("#month-search").click(function(){
            //modified by -ashvini 26th oct 2020
            var practice = $('#practices').val();
            // alert(practice);
            var provider = $('#physician').val();
            //alert(provider);
            var modules = $('#modules').val();
            //  alert(modules);
            var monthly = $('#monthly').val();
            var monthlyto = $('#monthlyto').val();
            if(monthlyto < monthly)
            {
                $('#monthlyto').addClass("is-invalid");
                $('#monthlyto').next(".invalid-feedback").html("Please select to-month properly .");
                $('#monthly').addClass("is-invalid");
                $('#monthly').next(".invalid-feedback").html("Please select from-month properly .");   
            } 
            
            else{ 
                $('#monthlyto').removeClass("is-invalid");
                $('#monthlyto').removeClass("invalid-feedback");
                $('#monthly').removeClass("is-invalid");
                $('#monthly').removeClass("invalid-feedback");
                getMonthlyBillingPatientList(practice,provider,modules,monthly,monthlyto); 
            }
    
        });

        $("#month-reset").click(function(){
                $('#monthlyto').removeClass("is-invalid");
                $('#monthlyto').removeClass("invalid-feedback");
                $('#monthly').removeClass("is-invalid");
                $('#monthly').removeClass("invalid-feedback"); 
            function getMonth(date) {
            var month = date.getMonth() + 1;
            return month < 10 ? '0' + month : '' + month; // ('' + month) for string result
            }
            var c_month = (new Date().getMonth() + 1).toString().padStart(2, "0");
            var c_year = new Date().getFullYear();
            var current_MonthYear = c_year+'-'+c_month;
            $("#monthly").val(current_MonthYear);
            $("#monthlyto").val(current_MonthYear);
                var practice = null
                var modules = 3;
                var provider = null;
                var monthly =  $("#monthly").val();
                var monthlyto = $("#monthlyto").val(); 


               
        
        $('#practices').val('').trigger('change');
        // var practice = $('#practices').val();
        //     alert(practice);  
       $('#physician').val('').trigger('change'); 
   
        $('#modules').val('3').trigger('change'); 
       
        getMonthlyBillingPatientList(practice,provider,modules,monthly,monthlyto);
        });

        
        </script>
@endsection         