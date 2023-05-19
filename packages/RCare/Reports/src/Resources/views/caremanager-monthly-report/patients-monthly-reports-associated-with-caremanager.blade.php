@extends('Theme::layouts_2.to-do-master')
@section('page-css')
@section('page-title')
Care Manager Monthly Billable Report
@endsection    
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Care Manager Monthly Billable Report</h4> 
        </div>
    </div>
    <!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card text-left">   
            <div class="card-body">
                
                <form id="report_form" name="report_form" method="post" action ="">
                @csrf
                <div class="form-row">
                    <div class="col-md-2 form-group mb-3">
                        <label for="practicegrp">{{config('global.practice_group')}}</label>
                         @selectgrppractices("practicesgrp", ["class" => "select2","id" => "practicesgrp"]) 
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <label for="practicename">Practice</label>
                          @selectGroupedPractices("practices",["id" => "practices", "class" => "form-control select2"])                         
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <label for="caremanagername">Care Manger</label>
                        @selectorguser("caremanagerid", ["class" => "select2","id" => "caremanagerid", "placeholder" => "Select Care Manager"])
                        <!-- selectcaremanagerwithAll -->
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <label for="module">Module Name</label>
                            <select id="modules" class="custom-select show-tick" name="modules">
                            <option value="0">None</option>
                            <option value="3" selected>CCM</option>
                            <option value="2">RPM</option>
                            </select> 
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <label for="month">From Month & Year</label>
                        @month('monthlyto',["id" => "monthlyto"])
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        <label for="month">To Month & Year</label>
                        @month('monthly',["id" => "monthly"])
                    </div>
                    <div class="col-md-2 form-group mb-3">  
                          <label for="activedeactivestatus">Patient Status</label> 
                          <select id="activedeactivestatus" name="activedeactivestatus" class="custom-select show-tick" >
                            <option value="" selected>All (Active,Suspended,Deactivated,Deceased)</option> 
                            <option value="1">Active</option>
                            <option value="0">Suspended</option>
                            <option value="2" >Deactivated</option>                           
                            <option value="3" >Deceased</option>
                          </select>                          
                    </div>
                    <div class="col-md-1 form-group mb-3">
                       <button type="button" class="btn btn-primary mt-4 ml-3" id="month-search">Search</button>
                    </div>
                    <div class="col-md-1 form-group mb-3">
                       <button type="button" class="btn btn-primary mt-4 ml-1" id="reset-btn">Reset</button>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
 
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
                    <table id="patient-list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="35px">Sr No.</th>
                            <th width="">Practice</th>
                            <th width="">Care Manager</th>
                            <th width="">Provider </th>
                            <th>EMR No.</th>
                            <th>Patient Name</th>
                            <th>DOB</th>
                            <th>Last contact Date</th>
                            <th>Billable</th>
                            <th>Status</th>
                            <th>Total Time Spent</th>
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
    var tdate = new Date();
    var twoDigitMonth = ((tdate.getMonth().length + 1) === 1) ? (tdate.getMonth() + 1) : '0' + (tdate.getMonth() + 1);
    var yyyy = tdate.getFullYear(); //yields year
    var currentDate = yyyy + "-" + twoDigitMonth;
    $('#monthly').val(currentDate);
    $('#monthlyto').val(currentDate);
  });
        var getCareManagerMonthlyPatientList = function(practicesgrp = null, practice = null,caremanager = null,modules=null,monthly=null,monthlyto=null,activedeactivestatus=null) {
            var columns =  [
                {data: 'DT_RowIndex', name: 'DT_RowIndex'}, 
                {data:null,
                                    mRender: function(data, type, full, meta){
                                        practice_name = full['pracpracticename'];
                                        if(full['pracpracticename'] == null){
                                            provider_name = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return practice_name;
                                        }
                                    },
                                    orderable: true
                                },
                                { data: null, 
                    mRender: function(data, type, full, meta){
                        if(data!='' && data!='NULL' && data!=undefined){
                            if(full['f_name']==null || full['l_name']==null) {
                                return '';
                            } else {
                                return  full['f_name']+' '+full['l_name']; 
                            }
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

                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        emr = full['pppracticeemr'];
                                        if(full['pppracticeemr'] == null){
                                            provider_name = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return emr;
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
                                {data: 'pdob', type: 'date-dd-mmm-yyyy', name: 'pdob', "render":function (value) {
                                    if (value === null) return "";
                                        return moment(value).format('MM-DD-YYYY');
                                    }
                                },
                                {data: 'ccsrecdate', type: 'date-dd-mmm-yyyy', name: 'ccsrecdate', "render":function (value) {
                                    if (value === null) return "";
                                        return moment(value).format('MM-DD-YYYY');
                                    }
                                },
                // {data:null,
                //                     mRender: function(data, type, full, meta){
                //                         last_date = full['ccsrecdate'];
                //                         if(full['ccsrecdate'] == null){
                //                             last_date = '';
                //                         }
                //                         if(data!='' && data!='NULL' && data!=undefined){
                //                             return last_date;
                //                         }
                //                     },
                //                     orderable: true
                //                 },
                {data:null,
                                    mRender: function(data, type, full, meta){
                                        bilabel = full['ptrtotaltime'];
                                        if(full['ptrtotaltime'] > '00:20:00'){
                                            bilabel = 'yes';
                                        }else{
                                            bilabel = 'no';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return bilabel;
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
                                      if(full['pstatus'] == 2){ 
                                          status = 'Deactived';
                                      } 
                                      if(full['pstatus'] == 3){ 
                                          status = 'Deceased';
                                      }
                                      if(data!='' && data!='NULL' && data!=undefined){
                                        return status;
                                      }
                                    },
                                    orderable: true, searchable: false
                                },

                                {data: 'ptrtotaltime', name: 'ptrtotaltime',
                    mRender: function(data, type, full, meta){
                        if(data!='' && data!='NULL' && data!=undefined){
                            return '<span id="'+full["id"]+'">'+full["ptrtotaltime"]+'</span>';
                        }else{ return '';}
                    },
                    orderable: true
                }                
                ];
                if(practicesgrp=='')
                {
                    practicesgrp=null;
                } 
                if(practice=='')
                {
                    practice=null;
                }
                if(caremanager=='')
                {
                    caremanager=null;
                }
                if(modules==''){
                    modules=null;
                }
                if(monthly=='')
                {
                    monthly=null;
                }
                if(monthlyto=='')
                {
                    monthlyto=null; 
                }

                if(activedeactivestatus==''){activedeactivestatus=null;}
                 var url = "/reports/care-manager-monthly-report/search/"+practicesgrp+"/"+practice+"/"+caremanager+"/"+modules+"/"+monthly+"/"+monthlyto+'/'+activedeactivestatus;             
                var table1 = util.renderDataTable('patient-list', url, columns, "{{ asset('') }}"); 
        } 
        $(document).ready(function() { 
         
            util.getToDoListData(0, {{getPageModuleName()}});

            function getMonth(date) {
            var month = date.getMonth() + 1;
            return month < 10 ? '0' + month : '' + month; // ('' + month) for string result
            }
            var c_month = (new Date().getMonth() + 1).toString().padStart(2, "0");
            var c_year = new Date().getFullYear();
            var current_MonthYear = c_year+'-'+c_month;
            $("#monthlyto").val(current_MonthYear);
            $("#monthly").val(current_MonthYear);
            // $(".patient-div").hide(); // to hide patient search select

            $("[name='modules']").val(3).attr("selected", "selected").change();
            $("[name='modules']").on("change", function () {
            });
            $("[name='monthlyto']").on("change", function (){    
            });
            $("[name='monthly']").on("change", function (){    
            });

            $("[name='practicesgrp']").on("change", function () { 
                var practicegrp_id = $(this).val(); 

                if(practicegrp_id==0){
                    //getCareManagerMonthlyPatientList();  
                }
                if(practicegrp_id!=''){
                    util.getPracticelistaccordingtopracticegrp(parseInt(practicegrp_id),$("#practices")); 
                }
                else{
                      util.updatePracticeListWithoutOther(001, $("#practices")) ;
                }   
            });

           
        });




        $("#month-search").click(function(){
            var caremanager = $('#caremanagerid').val();
            var modules = $('#modules').val();
            var monthlyto = $('#monthlyto').val();
            var monthly = $('#monthly').val();
            var practices =$('#practices').val();
            var practicesgrp =$('#practicesgrp').val();
            var activedeactivestatus =$('#activedeactivestatus').val();
          //  alert(practicesgrp);
            if(monthlyto!= '' && monthly!= '' && monthlyto > monthly)
             { 
             alert("Please ensure that the To Date is greater than or equal to the From Date.");
             return false;
             }
             else
             {
                getCareManagerMonthlyPatientList(practicesgrp,practices,caremanager,modules,monthly,monthlyto,activedeactivestatus);  
            }
            
        });

        $("#reset-btn").click(function(){ 
            $('#caremanagerid').val('').trigger('change');
            $('#practices').val('').trigger('change');
            $('#practicesgrp').val('').trigger('change');
            var c_month = (new Date().getMonth() + 1).toString().padStart(2, "0");
            var c_year = new Date().getFullYear();
            var current_MonthYear = c_year+'-'+c_month;
            $("#monthlyto").val(current_MonthYear);
            $("#monthly").val(current_MonthYear);
            $("[name='modules']").val(3).attr("selected", "selected").change(); 
            $('#activedeactivestatus').val('').attr("selected","selected").change();
                }); 
            $("[name='caremanagerid']").on("change", function () { 
        });
  </script>      

@endsection    