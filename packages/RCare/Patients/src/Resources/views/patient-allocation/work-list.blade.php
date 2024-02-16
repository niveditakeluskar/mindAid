@extends('Theme::layouts_2.to-do-master')
@section('page-css') 
    <!-- <link rel="stylesheet" href="{{-- asset('assets/styles/vendor/datatables.min.css') --}}"> -->
@endsection
@section('main-content')
    <div class="breadcrusmb"> 
        <div class="row" style="margin-top:10px">
            <div class="col-md-8"> 
            <h4 class="card-title mb-3">Work List</h4>  
            </div>
            <div class="form-group col-md-4"> 
            </div>
        </div>           
    </div>
    <div class="separator-breadcrumb border-top"></div>
    <div id='success'></div>
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card text-left">   
                <div class="card-body">
                    <form id="" name="" method="post" action ="">
                        @csrf
                        <div class="form-row">
                            <div class="col-md-6 form-group mb-6">
                                <label for="practicename">Practice Name</label>
                                @selectworklistpractices("practices", ["id" => "practices", "class" => "select2"])
                            </div>
                            <div class="col-md-6 form-group mb-6">
                                <label for="practicename">Patient Name</label>
                                @selectallworklistccmpatient("Patient",["id" => "patient", "class" => "select2"])
                            </div>
                        </div> 
                        <div class="form-row">
                            <div class="col-md-3 form-group mb-3"> 
                                <select id="timeoption"  class="custom-select show-tick" style="margin-top: 23px;">
                                    <option value="2">Greater than</option>
                                    <option value="1" selected>Less than</option>
                                    <option value="3">Equal to</option>
                                    <option value="4">All</option>
                                </select>                          
                            </div>
                            <div class="col-md-3 form-group mb-3">                        
                                <label for="time">Time Spent</label>
                                @text("time", ["id" => "time", "placeholder" => "hh:mm:ss"])                       
                            </div>
                            <div class="col-md-3 form-group mb-3">
                                <label for="module">Module</label>
                                <select name="modules" id="modules" class="custom-select show-tick">
                                    <option value="3" selected>CCM</option>
                                    <option value="2">RPM</option>   
                                </select> 
                            </div> 
                            <div class="col-md-3 form-group mb-3">
                                <label for="activedeactivestatus">Patient Status</label> 
                                <select id="activedeactivestatus" name="activedeactivestatus" class="custom-select show-tick" >
                                    <option value="" selected>All (Active,Suspended,Deactivated,Deceased)</option> 
                                    <option value="1">Active</option>
                                    <option value="0">Suspended</option>                           
                                    <option value="2">Deactivated</option>
                                    <option value="3">Deceased</option>
                                </select>                          
                            </div>
                            <div class="col-md-3 form-group mb-3">
                                <button type="button" class="btn btn-primary mt-4" id="month-search">Search</button>
                                <button type="button" class="btn btn-primary mt-4" id="reset">Reset</button>
                            </div>
                            <div class="col-md-8 form-group">
                                <h4 style="float: right;">
                                    <label float-right="">Total Minutes Spent</label>
                                    <label for="programs" class="cmtotaltimespent" data-toggle="tooltip" data-placement="right" title="" data-original-title="Total minutes spent/Total No. of patients" style="margin-left: 2px;margin-top: 10px;font-size: 16px;"></label>
                                </h4>
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
                                    <th>EMR No.</th>
                                    <th>Patient Name</th>
                                    <th>DOB</th>
                                    <th>Practice</th>
                                    <th width="120px">Last contact Date</th>
                                    <th width="115px">Total Time Spent</th>
                                    <th>Action</th>
                                    <th width="35px">Patient Status</th>
                                   <!-- <th width="35px">Add'l Act</th> -->
                                    <th width="35px">Call Score</th>
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
    <!--start modal-->
    <div id="add-activities" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content--> 
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Activity</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form action="{{route('save.patient.activity')}}" method="post" name ="add_activity_form"  id="add_activity_form">
                    @csrf
                    <div class="modal-body">
                        <div id="msg"></div>                           
                        <input type="hidden" name='timer_on' id='timestart'>
                        <input type='hidden' name='practice_id' id='practice_id'>
                        <input type="hidden" name="module_id" id="module_id">
                        <input type="hidden" name="component_id" value="19">
                        <input type="hidden" name="stage_id" value="0"> 
                        <input type="hidden" name="step_id" value="0"> 
                        <input type="hidden" name="patient_id" id="patient_id">    
                        <input type="hidden" name="activity_name" id="activity_name">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-12 form-group mb-3">
                                    <label for="activitytype">Activity<span class='error'>*</span></label>
                                    @selectGroupedPatientActivites("activity_id",["id" => "activity_id", "class" => "form-control"])
                                    @hidden('activity',['id'=>'activity'])
                                    <div id="activity_div" style='color: #f44336 !important;font-size: 80%;'></div>
                                </div> 
                            </div>
                        </div> 
                        <div class="row" id="datediv" style="display: none;">
                            <div class="col-md-12">
                                <div class="col-md-12 form-group mb-3">
                                    <label for="activitydate">Date <span class='error'>*</span></label>
                                    @date('davice_traning_date',['id'=>'davice_traning_date'])
                                </div>
                            </div>
                        </div>
                        <div class="row">                                    
                            <div class="col-md-12">
                                <div class="col-md-12 form-group mb-3">
                                    <label for="activitytime">Time <span class='error'>*</span></label>
                                    @timetext('net_time',['id'=>'net_time'])
                                    @hidden("timer_type",["id"=>"timer_type"])
                                </div>
                            </div>
                        </div>
                        <div class="row" id="notes">
                            <div class="col-md-12">
                                <div class="col-md-12 form-group">
                                    <label for="activity">Notes</label>
                                    <textarea class="form-control" name="notes" id="comments"></textarea>
                                    <div id="notes_div" class="invalid-feedback"></div>
                                </div>
                            </div>
                        </div>                            
                    </div>         
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary float-right" id="patient_activity_submit">Submit</button>
                        <button type="button" class="btn btn-default float-left" data-dismiss="modal">Close</button>
                    </div>
                </form>
            </div><!-- Modal content end-->
        </div>
    </div><!--end modal-->
@endsection
@section('page-js')
    <script src="{{asset('assets/js/tooltip.script.js')}}"></script>
    <script src="{{asset(mix('assets/js/laravel/worklist.js'))}}"></script>
    <script src="{{asset(mix('assets/js/laravel/ccmcpdcommonJS.js'))}}"></script>
    <script type="text/javascript">
        var deactiveDataInGreyColor=function() {
            $('tr').each(function() {
                $(this).find('td i').attr('class');       
                if( $(this).find('td i').attr('class')=='i-Closee i-Close') {                                  
                    $(this).css('color', '#ccc');
                }
            });
        }

        var getPatientList = function(practice_id = null,patient_id = null,module_id = null,timeoption=null,time=null,activedeactivestatus=null) {
            var columns =  [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    { data:null,
                        mRender: function(data, type, full, meta) {
                            emr = full['pppracticeemr'];
                            if(full['pppracticeemr'] == null){
                                provider_name = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return emr;
                            }
                        }, orderable: true
                    },
                    { data: 'pfname', name: 'pfname',
                        mRender: function(data, type, full, meta) {
                            m_Name = full['pmname'];
                            if(full['pmname'] == null) {   
                                m_Name = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined) {
                                if(full['pprofile_img']=='' || full['pprofile_img']==null) {
                                    return  ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['pfname']+' '+m_Name+' '+full['plname'];
                                } else {
                                    return ["<img src='"+full['profile_img']+"' width='40px' height='25px' class='user-image' />"]+' '+full['pfname']+' '+m_Name+' '+full['plname'];
                                }
                            }
                        }, orderable: true 
                    },
                    { data: 'pdob', type: 'date-mmm-dd-yyyy', name: 'pdob', "render":function (value) {
                        if (value === null) return "";
                            return moment(value).format('MM-DD-YYYY'); 
                        } 
                    },
                    { data:null,
                        mRender: function(data, type, full, meta) {
                            practice_name = full['pracpracticename'];
                            if(full['pracpracticename'] == null) {
                                provider_name = '';
                            }
                            if(data!='' && data!='NULL' && data!=undefined) {
                                return practice_name;
                            }
                        }, orderable: true
                    },
                    { data: 'csslastdate',name:'csslastdate',
                        "render":function (value) {
                            if (value === null) return "";
                            return moment(value).format('MM-DD-YYYY'); 
                        }
                    },
                    { data: 'ptrtotaltime', name: 'ptrtotaltime',
                        mRender: function(data, type, full, meta){
                        // $(row).css('color', 'red');
                        if(data!='' && data!='NULL' && data!=undefined){
                            return full["ptrtotaltime"];   
                        } else {
                            return '';
                        }
                        }, orderable: true
                    },
                    { data: 'action', name: 'action', orderable: true, searchable: false },
                    { data: 'activedeactive', name: 'activedeactive', 
                        mRender: function(data, type, full, meta) {
                            fromdate = moment(full['pfromdate']).format('MM-DD-YYYY'); 
                            todate = moment(full['ptodate']).format('MM-DD-YYYY');
                            if(full['ptodate'] == null){
                                date = '';
                            } 
                            if(full['pfromdate'] == null){ 
                                date = '';
                            } else { 
                                if(full['pstatus']=='0') { 
                                    date ="("+todate+")"; 
                                }  
                                if(full['pstatus']=='2') {
                                    date ="("+fromdate+")"; 
                                }
                            }
                            if(data!='' && data!='NULL' && data!=undefined){
                                return full['activedeactive']+" "+date;
                            }
                        }, orderable: true, searchable: false
                    },
                   /* { data: 'addaction', name: 'addaction', orderable: true, searchable: false}, */
                    
                    { data: 'pssscore', name: 'pssscore',
                        mRender: function(data, type, full, meta){
                        // $(row).css('color', 'red');
                        if(data!='' && data!='NULL' && data!=undefined){
                            return full["pssscore"];   
                        } else {
                            return '';
                        }
                        }, orderable: true
                    }, 
                    
            ]//,
            if(practice_id=='') { 
                practice_id=null;
            }
            if(patient_id=='') {
                patient_id=null;
            }
            if(module_id=='') {
                module_id=null;
            }  
            if(time=='') {
                time='00:20:00';
            }
            if(time=='00:00:00') {
                time='00:00:00';
            }
            if(timeoption=='') {
                timeoption=1;
            }
            if(activedeactivestatus=='') { 
                activedeactivestatus =null ;  
            }
            var url = "/patients/worklist/"+practice_id+"/"+patient_id+"/"+module_id+"/"+timeoption+"/"+time+"/"+activedeactivestatus;
            var table = util.renderDataTable('patient-list', url, columns, "{{ asset('') }}");
        }

        var savefilters = function(practices,patient,modules,timeoption,time,activedeactivestatus) {
            if(practices=='') {
                practices=null;
            }
            if(patient=='') {
                patient=null;
            }
            if(modules=='') {
                modules=null;
            }  
            if(time=='') {
                time='00:20:00';
            }
            if(time=='00:00:00') {
                time='00:00:00';
            }
            if(timeoption=='') {
                timeoption=1;
            }
            if(activedeactivestatus=='') {
                activedeactivestatus = null;  
            }    
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type:'POST',
                url:'/patients/worklist/saveuser-filters/'+practices+'/'+patient+'/'+modules+'/'+timeoption+'/'+time+'/'+activedeactivestatus,
                data: {
                    'practices' : practices, 
                    'patient' : patient, 
                    'modules': modules,
                    'timeoption' : timeoption, 
                    'time' : time, 
                    'activedeactivestatus': activedeactivestatus
                },
                success:function(data) {
                    // console.log(data);  
                } 
            }); 
        }

        var filters = function() { 
            $.ajax({   
                type:'GET',
                url:"{{route('user.filters')}}",
                success:function(data) {
                    var data = JSON.parse(data);  
                    var d = (data + '').length ;
                    if(d >0) {
                        var newpractice = data.practice;
                        var newpatient = data.patient;
                        var newtimeoption = data.timeoption;
                        var newtime = data.time;
                        var patientstatus = data.patientstatus;
                        if(newpractice=='null'){
                            util.getPatientList(newpractice,$("#patient"));  
                        } else {
                            $('#practices').val(newpractice).trigger('change');   
                        }
                        setTimeout(function() {    
                            if(newpatient=='null'){
                            } else{
                                $('#patient').val(newpatient).trigger('change');  
                            }
                        }, 5000); 
                        $('#timeoption').val(newtimeoption).trigger('change'); 
                        $('#time').val(newtime);
                        $('#activedeactivestatus').val(patientstatus); 
                    } else {     
                        $('#time').val('00:20:00');  
                        $('#timeoption').val('1').trigger('change');
                        $('#activedeactivestatus').val('').trigger('change');  
                    } 
                    var role = '<?php echo $roleid; ?>';
                    if(role!=2) {
                        getPatientList(newpractice,newpatient,3,newtimeoption,newtime,patientstatus);
                    }
                }
            });
        }

        function formatDate() {
            var d = new Date(),
            month = '' + (d.getMonth() + 1),
            day = '' + d.getDate(),
            year = d.getFullYear();
            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;
            return [year,month, day].join('-');
        }

        var currentdate = formatDate();  
        var date = new Date(); 
        var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);         
        var getmnth=("0" +(date.getMonth() + 1)).slice(-2);
        var firstDayWithSlashes = date.getFullYear()+ '-' + getmnth + '-' +('0' +(firstDay.getDate())).slice(-2);

        $(document).ready(function() {
            $('#davice_traning_date').val(currentdate); 
            worklist.init();
            filters();
            var table = $('#patient-list').DataTable();
            util.getToDoListData(0, {{getPageModuleName()}});
            util.getAssignPatientListData(0,0);
            $(".patient-div").hide();
            $("[name='practices']").on("change", function() {
                var practice_id = $(this).val();  
                if(practice_id==0){
                    // getPatientList();  
                }
                if(practice_id!=''){
                    util.getPatientList(parseInt(practice_id),$("#patient"));
                } else{
                    // getPatientList();    
                }
            });
        });

        $('#timeoption').change(function() {
            $checkvalue=$('#timeoption').val();
            if($checkvalue=='4') {
                $('#time').val('00:00:00'); 
                $('#time').prop( "disabled", true);
            } else {
                $('#time').val('00:20:00');    
                $('#time').prop( "disabled", false);
            }
        });

        //move to js file
        $("#month-search").click(function(){ 
            $('#time').removeClass("is-invalid");
            $('#time').removeClass("invalid-feedback");
            var practices = $('#practices').val();
            var modules = $('#modules').val();//.trigger('change');
            var patient = $('#patient').val();
            var timeoption = $('#timeoption').val();
            var time = $('#time').val();
            var activedeactivestatus = $('#activedeactivestatus').val();
            if(time == '00:00:00') {
                time = '00:00:00';
            }
            var time_regex = new RegExp(/(?:[01]\d|2[0123]):(?:[012345]\d):(?:[012345]\d)/);
            if(time!="") {
                if(time.length!=8) {
                    $('#time').addClass("is-invalid"); 
                    $('#time').next(".invalid-feedback").html("Please enter time in HH:MM:SS format.");
                } else {
                    if(time_regex.test(time)) { 
                        $('#time').removeClass("is-invalid"); 
                        $('#time').removeClass("invalid-feedback");     
                        getPatientList(practices,patient,modules,timeoption,time,activedeactivestatus);
                        savefilters(practices,patient,modules,timeoption,time,activedeactivestatus);
                    } else {
                        $('#time').addClass("is-invalid");
                        $('#time').next(".invalid-feedback").html("Please enter time in HH:MM:SS format.");
                    }
                }
            } else {  
                $('#time').removeClass("is-invalid"); 
                $('#time').removeClass("invalid-feedback");     
                getPatientList(practices,patient,modules,timeoption,time,activedeactivestatus);
                savefilters(practices,patient,modules,timeoption,time,activedeactivestatus);  
            }
            setTimeout(function() {
                deactiveDataInGreyColor();   
                //do something special
            }, 5000);
        });

        $("#reset").click(function(){
            $('#time').removeClass("is-invalid"); 
            $('#time').removeClass("invalid-feedback");
            var practices = null
            var modules = $('#modules').val('3').trigger('change');
            var patient = null;
            var timeoption = null;
            var time = null;
            var activedeactivestatus = null;
            $('#time').val('00:20:00');
            $('#practices').val('').trigger('change');
            $('#patient').val('').trigger('change');
            $('#timeoption').val('1').trigger('change'); 
            $('#activedeactivestatus').val('').trigger('change'); 
        }); 

        $('table').on('draw.dt', function() {
            $('[data-toggle="tooltip"]').tooltip();  
        }); 

        var time = "<?php $time='00:00:00'; echo ($time!='0') ? $time : '00:00:00'; ?>";
        var splitTime = time.split(":");
        const H = splitTime[0];
        const M = splitTime[1];
        const S = splitTime[2]; 
    </script>
@endsection