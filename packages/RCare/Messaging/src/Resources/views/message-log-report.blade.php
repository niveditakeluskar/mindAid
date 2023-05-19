@extends('Theme::layouts_2.to-do-master')
@section('page-css')
@section('page-title')
Message Log
@endsection    
@endsection
@section('main-content')
<div class="breadcrusmb">
    <div class="row">
        <div class="col-md-11">
            <h4 class="card-title mb-3">Message Log</h4> 
        </div>
    </div>
    <!-- ss -->             
</div>
<?php //echo messageResponse(); ?>
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
                        @selectcaremanagerwithAll("caremanagerid", ["class" => "select2","id" => "caremanagerid", "placeholder" => "Select Care Manager"])
                    </div>
                    <div class="col-md-2 form-group mb-3">
                        
                        <label for="module">Module Name</label>
                            <select id="modules" class="custom-select show-tick" name="modules">
                            <option value="0">None</option>
                            <option value="3" selected>CCM</option>
                            <option value="2">RPM</option>
                            </select> 
                    
                    </div>
                    <div class="col-md-2 form-group mb-2">
                        <label for="date">From Date</label>
                        @date('date',["id" => "fromdate"])
                                               
                    </div>
                     <div class="col-md-2 form-group mb-3">
                        <label for="date">To Date</label>
                        @date('date',["id" => "todate"])
                                              
                    </div>
                    <div class="col-md-2 form-group mb-3">  
                          <label for="status">Status</label> 
                          <select id="status" name="status" class="custom-select show-tick" >
                            <option value="0" selected>All</option> 
                            <option value="accepted">Accepted</option>
                            <option value="queued">Queued</option>
                            <option value="sending">Sending</option>
                            <option value="sent">Sent</option> 
                            <option value="failed">Failed</option> 
                            <option value="delivered">Delivered</option> 
                            <option value="undelivered">Undelivered</option> 
                            <option value="receiving">Receiving</option> 
                            <option value="received">Received</option> 
                          </select>                          
                    </div>
                    <div class="col-md-1 form-group mb-3">
                       <button type="button" class="btn btn-primary mt-4 ml-3" id="message">Search</button>
                    </div>
                    <div class="col-md-1 form-group mb-3">
                       <button type="button" class="btn btn-primary mt-4 ml-1" id="resetbutton">Reset</button>
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
                    <table id="Activities-list" class="display table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th width="">Sr No.</th>                            
                            <th width="">Date</th>
                            <th width="">Practice</th>
                            <th width="">Assigned Care Manager</th>
                            <th width="">Message Send By</th>
                            <th width="">Patient</th>
                            <th width="">DOB</th>
                            <th width="">Module</th>
                            <th width="">Direction</th>
                            <th width="">From</th>
                            <th width="">To</th>
                            <th width="">Status</th>  
                            <th width="">Action</th>    
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


<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading">Text Message</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
                <div class="modal-body">
                <p id="viewmessage"></p>
                </div>
                <div class="card-footer">
                    <div class="mc-footer">
                        <div class="row">
                            <div class="col-lg-12 text-right">
                                <button type="button" class="btn btn-outline-secondary m-1" style="float:right" data-dismiss="modal">Close</button>
                            </div>
                        </div>    
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
   
    <script type="text/javascript">
        
       var getAdditionalActivitiesList = function(practicesgrp=null,practice = null,caremanager=null,modules=null,fromdate1=null,todate1=null,status=null) {
            var columns =  [   
                                {data: 'DT_RowIndex', name: 'DT_RowIndex'},       
                                {data: 'messagedate', type: 'date-dd-mmm-yyyy', name: 'messagedate',"render":function (value) {
                                    if (value === null) return "";
                                    return moment(value).format('MM-DD-YYYY hh:mm:ss');
                                    }
                                },
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        name = full['practicename'];
                                        if(full['practicename'] == null){
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
                                        lname = full['u1lname'];
                                        if(full['u1lname'] == null){
                                            lname = '';
                                        }

                                        if(full['u1fname'] == null){
                                           return '';
                                        }

                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return full['u1fname']+" "+lname;
                                        }
                                       
                                    },
                                    orderable: true
                                },  

                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        lname = full['ulname'];
                                        if(full['ulname'] == null){
                                            lname = '';
                                        }

                                        if(full['ufname'] == null){
                                            m_Name = full['mname'];
                                            l_name = full['lname'];
                                            if(full['mname'] == null){
                                                m_Name = '';
                                            }
                                            if(full['lname'] == null){
                                                l_name = '';
                                            }
                                            return full['fname']+' '+m_Name+' '+l_name;
                                        }

                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return full['ufname']+" "+lname;
                                        }
                                       
                                    },
                                    orderable: true
                                },                      
                                {data: null, mRender: function(data, type, full, meta){
                                    m_Name = full['mname'];
                                    l_name = full['lname'];
                                    if(full['mname'] == null){
                                        m_Name = '';
                                    }
                                    if(full['lname'] == null){
                                        l_name = '';
                                    }

                                    if(data!='' && data!='NULL' && data!=undefined){
                                        if(full['profile_image']=='' || full['profile_image']==null) {
                                            return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+l_name;
                                        } 
                                        else 
                                        {
                                            return ["<img src='"+full['profile_image']+"' width='40px' height='25px' class='user-image' />"]+' '+full['fname']+' '+m_Name+' '+l_name;
                                        }                                       
                                    }
                                },
                                orderable: true
                                },
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        dob = full['dob'];
                                        if(full['dob'] == null){
                                            dob = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                             return moment(dob).format('MM-DD-YYYY');      
                                        }
                                    },
                                    orderable: true
                                },
                               
                                {data: 'modulename', name: 'modulename'},
                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        if(full['status'] == 'received'){
                                            return 'Incoming';
                                        }else{
                                            return 'Outgoing';
                                        }
                                    
                                    },
                                    orderable: true
                                },
                                {data: 'fphone', name: 'fphone'},
                                {data: 'tophone', name: 'tophone'}, 
                                {data: 'status', name: 'status'}, 
                                {data: 'action', name: 'action'},
                            
                            ];
            
             // debugger;
             if(practicesgrp==''){practicesgrp=null;}
             if(practice==''){practice=null;}                        
             if(caremanager==''){ caremanager=null; }
             if(modules=='')  { modules=null; }
             if(fromdate1==''){ fromdate1=null; }
             if(todate1=='')  { todate1=null; }
             if(status==''){status=null;}

         var url = "/messaging/message-log/search/"+practicesgrp+"/"+practice+'/'+caremanager+'/'+modules+'/'+fromdate1+'/'+todate1+'/'+status;
         table1 = util.renderDataTable('Activities-list', url, columns, "{{ asset('') }}");
              
        }

        function formatDate() {
        var d = new Date(),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();

        if (month.length < 2) 
            month = '0' + month;
        if (day.length < 2) 
            day = '0' + day;

        return [year,month, day].join('-');
        }

         var currentdate = formatDate();  
            var date = new Date(); 

           var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);         
           var getmnth=("0" +(date.getMonth() + 1)).slice(-2);
           var firstDayWithSlashes = date.getFullYear()+ '-' + getmnth + '-' +('0' +(firstDay.getDate())).slice(-2);
        

        $(document).ready(function() {                
            
            $('#fromdate').val(firstDayWithSlashes);                      
            $('#todate').val(currentdate);
            util.getToDoListData(0, {{getPageModuleName()}});

            $("[name='practicesgrp']").on("change", function () { 
                var practicegrp_id = $(this).val(); 
                if(practicegrp_id==0){
                   // getPatientData();  
                }
                if(practicegrp_id!=''){
                    util.getPracticelistaccordingtopracticegrp(parseInt(practicegrp_id),$("#practices")); 
                }
                else{
                     util.updatePracticeListWithoutOther(001, $("#practices")) ;
                }   
            });
    

             
           
        }); 

   
    


     $('#resetbutton').click(function(){
            $('#practicesgrp').val('').trigger('change');
            $('#practices').val('').trigger('change');
           
            $('#modules').val(3);
            $('#caremanagerid').val('').trigger('change');
             
            
            var practicesgrp =$('#practicesgrp').val();
            var practice=$('#practices').val();
            var caremanagerid=$('#caremanagerid').val();
            var moduleid=$('#modules').val(); 
            var fromdate1=$('#fromdate').val();
            var todate1=$('#todate').val();
            var status = $('#status').val();
            $('#fromdate').val(firstDayWithSlashes);                      
            $('#todate').val(currentdate); 
            $('#status').val('0');
        
      
            
            getAdditionalActivitiesList(practicesgrp,practice,caremanagerid,moduleid,fromdate1,todate1,status);

  });

$('#message').click(function(){
   
   
    var practicesgrp =$('#practicesgrp').val(); 
    var practice=$('#practices').val();     
    var caremanager=$('#caremanagerid').val();
    var moduleid=$('#modules').val();    
    var fromdate1=$('#fromdate').val();
    var todate1=$('#todate').val();   
    var status = $('#status').val();      
    var eDate = new Date(todate1);
           var sDate = new Date(fromdate1);
           if(fromdate1!= '' && todate1!= '' && sDate> eDate)
             {
             alert("Please ensure that the To Date is greater than or equal to the From Date.");
             return false;
             }
             else
             {
            getAdditionalActivitiesList(practicesgrp,practice,caremanager,moduleid,fromdate1,todate1,status);
             }
  });
   
         function viewSendMessage(messageid){
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
            $.ajax({
                    type: 'post',
                    url: '/messaging/view-message',
                    data: 'id=' + messageid,
                    success: function(response) {
                       $("#viewmessage").html(response);
                       $("#ajaxModel").modal('show');
                    },
                });
         }

         function reSend(messageid){
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
            });
            $.ajax({
                    type: 'post',
                    url: '/messaging/resend-message',
                    data: 'id=' + messageid,
                    success: function(response) {
                       $("#viewmessage").html(response);
                    },
                });
         }
    
 
    </script>
@endsection 