<div class="row mb-4">
    <div class="col-md-12">
        <div class="card" >
            <div class="card-header" >
                <div class="form-row">
                    <span class="col-md-4">
                        <h4>Patient Device Reading</h4>
                    </span>
                    <span class="col-md-8" style="padding-right:30%">
                        @selectDevice('device_name',['id'=>'device_name'])
                    </span>
                </div>  
            </div>
            <?php 
            $module_id    = getPageModuleName();
            $submodule_id = getPageSubModuleName();

            // $stage_id = getFormStageId(getPageModuleName(), getPageSubModuleName(), 'daily-review'); 
            ?>
            <div class="card-body"> 
                <div class="row"> 
                    <div class="col-md-12" id ="time_add_success_msg"></div>
                    <div class="col-md-12 steps_section">
                    <div class="title">
                        <input type="hidden" id="patient_id" value="<?php echo $patient[0]->id; ?>">
                        <input type="hidden" id="module_id" name="module_id" value="{{$module_id}}">
                        <input type="hidden" id= "component_id" name="sub_module_id" value="{{$submodule_id}}">
                        <input type="hidden" name="stage_id" value="0">
                        <input type="hidden" name="step_id" value="0"> 
                        <h5 class="text-center">Today's Readings</h5>
                    </div>
                    <div class="row justify-content-center">
                        <div class="card  rounded">
                            <div class="card-body device_box">
                                <span id="today" class="day"></span>
                                <span id="today_unit" class="unit"></span>
                            </div>                               
                        </div>                        
                    </div>
                    </div>                   
                </div>
                <!-- <div class="col-md-6 steps_section" id="select-activity-2" style=""></div> -->
                <div class="align-self-start pt-3">
                    <div class="d-flex justify-content-center">
                            <div class="mr-2" style="">
                                <div class="card">
                                    <div class="card-header device_header">7 Days Ago</div>
                                    <div class="card-body device_box">
                                        <span id ="seven_day" class="day"></span>
                                            <span id="seven_unit" class="unit"></span>
                                          <input class="checkbox" type="checkbox" value="" id="flexCheckDefault">
                                    </div>
                                </div>
                            </div>
                            <div class="mr-2" style="">
                                <div class="card">
                                    <div class="card-header device_header">6 Days Ago</div>
                                    <div class="card-body device_box">
                                        <span id="six_day" class="day"></span>
                                        <span id="six_unit" class="unit"></span>
                                          <input class="checkbox" type="checkbox" value="" id="flexCheckDefault">
                                    </div>
                                </div>
                            </div>
                            <div class="mr-2" style="">
                                <div class="card">
                                    <div class="card-header device_header">5 Days Ago</div>
                                    <div class="card-body device_box">
                                        <span id="five_day" class="day"></span>
                                        <span id="five_unit" class="unit"></span>
                                          <input class="checkbox" type="checkbox" value="" id="flexCheckDefault">
                                    </div>
                                </div>
                            </div>
                            <div class="mr-2" style="">
                                <div class="card">
                                    <div class="card-header device_header">4 Days Ago</div>
                                    <div class="card-body device_box">
                                          <span id="four_day" class="day"></span>
                                          <span id="four_unit" class="unit"></span>
                                          <input class="checkbox" type="checkbox" value="" id="flexCheckDefault">
                                    </div>
                                </div>
                            </div>
                            <div class="mr-2" style="">
                                <div class="card">
                                    <div class="card-header device_header">3 Days Ago</div>
                                    <div class="card-body device_box">
                                        <span id="three_day" class="day"></span>
                                        <span id="three_unit" class="unit"></span>
                                          <input class="checkbox" type="checkbox" value="" id="flexCheckDefault">
                                    </div>
                                </div>
                            </div>
                            <div class="mr-2" style="">
                                <div class="card">
                                    <div class="card-header device_header">2 Days Ago</div>
                                    <div class="card-body device_box">
                                        <span id="two_day" class="day"></span>
                                        <span id="two_unit" class="unit"></span>
                                          <input class="checkbox" type="checkbox" value="" id="flexCheckDefault">
                                    </div>
                                </div>  
                            </div>
                            <div class=" rounded" style="">
                                <div class="card">
                                    <div class="card-header device_header">Yesterday</div>
                                    <div class="card-body device_box">
                                        <span id="yester_day" class="day"></span>
                                        <span id="yester_day_unit" class="unit"></span>
                                        <input class="checkbox" type="checkbox" value="" id="flexCheckDefault">
                                    </div>                               
                                </div>  
                            </div>
                    </div>
                </div>
                <!-- <div class="col-md-6 steps_section" id="select-activity-2" style=""></div> -->
                <div class="align-self-start pt-3">
                    <div class="row justify-content-center">
                        <div class="col-md-4 text-center">
                            <div class="form-group">
                                <button type="button" class="btn btn-primary btn-lg" id="textbtn">Text Patient</button>
                            </div> 
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="form-group">
                                <button type="button" class="btn btn-primary btn-lg">Message Care Manager</button>
                            </div> 
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="form-group">
                                <button type="button" onclick="addTimeFunction()" class="btn btn-primary btn-lg">View Observations</button>
                            </div> 
                        </div>
                    </div>
                </div>

                <div id="textcard" style="display: none;width: 79%;">
               
                 @include('Rpm::daily-review.text')
                 </div>
                <!-- <div class="col-md-6 steps_section" id="select-activity-2" style=""></div> -->
                <div class="row justify-content-center">
                    <div class="col-md-10">
                        <div class="form-group">
                            <label>Care Manager Notes</label> 
                                <textarea name ="personal_notes" class="form-control forms-element personal_notes_class"><?php if(isset($personal_notes['static']['personal_notes'])) { echo $personal_notes['static']['personal_notes']; }?></textarea>
                            <div class="invalid-feedback"></div>
                        </div> 
                    </div>
                </div>
       
            <div class="row mb-4" id="daily-review-table" style="display: none;">
                <div class="col-md-12 mb-4" id="test3">                  
                    <div class="card text-left" id="test2">
                        <div class="card-body"> 
                        <div class="row">
                         <div class="col-md-2 form-group mb-3">
                        <label for="month">From Month & Year</label>
                        @month('monthly',["id" => "monthly"]) 
                        </div>
                        <div class="col-md-2 form-group mb-3">
                            <label for="month">To Month & Year</label>
                            @month('monthlyto',["id" => "monthlyto"])
                        </div>
                        <div class="row col-md-2 mb-2">
                        <div class="col-md-5">
                            <button type="button" class="btn btn-primary mt-4" id="month-search">Search</button>
                        </div>
                        <div class="col-md-5">
                            <button type="button" class="btn btn-primary mt-4" id="month-reset">Reset</button>
                        </div>
                    </div>
                        </div>
                        <div class="daily-review-table">
                        <button type="button" id="tableclose" class="close">×</button>  
                        </div>       
                            @include('Theme::layouts.flash-message')
                            <div class="table-responsive">
                                <table id="monthlyreviewlist" class="display table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th width="35px">Sr No.</th>
                                        <th width="35px">View details</th> 
                                        <th width="205px">Patient</th>
                                        <th width="97px">DOB</th>
                                        <th width="97px">Clinic</th>
                                        <th>Provider</th>                            
                                        <th>Care Manager</th>
                                        <th>Device</th>
                                        <th>Range</th>
                                        <th>Reading</th>
                                        <th>TimeStamp</th>                                       
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

          

            </div>
            <div class="card-footer"> 
                <div class="mc-footer">
                    <button type="submit" class="btn  btn-primary m-1 float-right" >Save</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style type="text/css">
.checkbox {
position: absolute;
    right: 1px;
    top: 37px;
}

.device_box{
    min-height:38px;
    min-width: 130px;
}
.device_header{
    text-align: center;
    font-size: revert;
    font-weight: 400; 
}

.day{
    font-weight: bold;
    font-size: medium;
}
.unit{
    font-weight: bold;
    font-size: medium;  
}
</style>
<script type="text/javascript">
$(document).ready(function(){ 
    
    var patient_id = $('#patient_id').val();    
    var device_id = $('#device_name option[value="<?php echo $deviceid?>"').attr('selected', 'selected').val();
    dailyReadingFunction(patient_id,device_id);
    getMonthlyReviewPatientList(patient_id);

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

$.ajaxSetup({    
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

 var getMonthlyReviewPatientList = function(patientid,monthly=null,monthlyto=null) {
            var columns = [
                               { data: 'DT_RowIndex', name: 'DT_RowIndex'},
                               {data: 'action', name: 'action'}, 
                                { data: 'pfname',name: 'pfname',
                                    mRender: function(data, type, full, meta){
                                        m_Name = full['pmname'];
                                            if(full['pmname'] == null){
                                                m_Name = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return ["<img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['pfname']+' '+m_Name+' '+full['plname'];
                                        }
                                    },
                                    orderable: false
                                },
                                { data: 'pdob', type: 'date-dd-mmm-yyyy', name: 'pdob',
                                    "render":function (value) {
                                    if (value === null) return "";
                                        return moment(value).format('MM-DD-YYYY');
                                    }
                                },
                         
                                { data: 'practicename',name: 'practicename',
                                    mRender: function(data, type, full, meta){
                                        practice_name = full['practicename'];
                                            if(full['practicename'] == null){
                                                practice_name = '';
                                        }
                                        else{
                                            return practice_name;
                                        }
                                         
                                    },
                                    orderable: false
                                },
                                { data: 'providername',name: 'providername',
                                    mRender: function(data, type, full, meta){
                                        providername = full['providername'];
                                            if(full['providername'] == null){
                                                providername = '';
                                        }
                                        else{
                                            return providername;
                                        }
                                         
                                    },
                                    orderable: false
                                },
                                { data: 'caremanagerfname',name: 'caremanagerfname',
                                    mRender: function(data, type, full, meta){
                                        caremanagerfname = full['caremanagerfname'];
                                            if(full['caremanagerfname'] == null){
                                                caremanagerfname = '';
                                        }
                                        else{
                                            return full['caremanagerfname']+' '+full['caremanagerlname']; 
                                        }
                                        
                                    },
                                    orderable: false
                                },
                                { data: 'unit',name: 'unit',
                                    mRender: function(data, type, full, meta){
                                        r_unit = full['unit'];
                                            if(full['unit'] == null){
                                                r_unit = '';
                                            }
                                            else{
                                                if(r_unit == '%'){
                                                    return 'Pulse Oximeter';
                                                }
                                                else if(r_unit == 'beats/minute'){
                                                    return 'Blood Pressure Cuff';
                                                }
                                                else{
                                                    //mmHg
                                                    return 'Spirometer';
                                                }
                                            }
                                        
                                    },
                                    orderable: false
                                },

                                { data: 'fname',name: 'fname',
                                    mRender: function(data, type, full, meta){
                                        m_Name = full['mname'];
                                            if(full['mname'] == null){
                                                m_Name = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return 0;
                                        }
                                    },
                                    orderable: false
                                },
                                { data: 'reading',name: 'reading',
                                    mRender: function(data, type, full, meta){
                                        m_reading = full['reading'];
                                        m_unit = full['unit'];
                                            if(full['reading'] == null){
                                                m_reading = '';
                                        }
                                        else{
                                            return m_reading+' '+m_unit;
                                        }
                                        // if(data!='' && data!='NULL' && data!=undefined){
                                        //     return 0;
                                        // }
                                    },
                                    orderable: false
                                },  

                                // {data: 'csseffdate',type:'dd-mm-yyyy h:i:s', name:'csseffdate',"render":function (value) {
                                //     if (value === null) return "";
                                //         return util.viewsDateFormat(value);  
                                //     }
                                // },  

                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        totaltime = full['csseffdate'];  
                                        if(full['csseffdate'] == null){
                                            totaltime = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return totaltime;
                                        }
                                    },
                                    orderable: true   
                                }
                        ];    
          
                if(monthly=='')
                 {
                     monthly=null;
                 }
                 if(monthlyto=='')
                 { 
                     monthlyto=null;
                }
                var url ="/rpm/monthly-review-list/"+patientid+"/"+monthly+"/"+monthlyto;  
                console.log(url);
                var table1 = util.renderDataTable('monthlyreviewlist', url, columns, "{{ asset('') }}"); 
            
        } 

        function addTimeFunction(){

           $('#daily-review-table').show();
        }
         $('#textbtn').click(function(){
           $('#textcard').show();
        });
       
        $('.close').click(function(){
           var id=$(this).parent().attr('class');
            $('#'+id).hide();
        });
        
        $('#text_template_id').change(function(){
             var deviceid=$(this).val();
            var patient_id = $('#patient_id').val();           
            $.ajax({
                url: '/ccm/get-call-scripts-by-id/'+deviceid+'/'+patient_id+"/call-script",
                type: 'get',
                // data: form_data,
                success: function(data) {   
                  $('#templatearea_sms').html(data[0].content_title);                
                    
                },
            });
        });

          $('#monthlyreviewlist tbody').on('click', 'td a', function () {     
            var data_id=  $(this).attr('id');  
            // alert(data_id);      
            var res = data_id.split("/");
            var patient_id=res[0];
            var fromdate=res[1];
            var unittable=res[2];
            

            var tr  = $(this).closest('tr'),
            row = $('#monthlyreviewlist').DataTable().row(tr);
            if (row.child.isShown()) {                 
            destroyChild(row);
            tr.removeClass('shown');
            $(this).find('i').removeClass('i-Remove');
            $(this).find('i').addClass('i-Add');
                
                // $(this).attr("class","http://i.imgur.com/SD7Dz.png");
            }
            else
            {                      
                tr.addClass('shown');
                //$(this).attr("src","https://i.imgur.com/d4ICC.png");
                $(this).find('i').removeClass('i-Add');
                $(this).find('i').addClass('i-Remove');
                
                var reviewchild=' <div class="table-responsive">';
                reviewchild = reviewchild + '<table id="ReviewMonthly-Child-list'+patient_id+'" class="display table table-striped table-bordered reviewdailychildtable" style="width:100%">';
                reviewchild = reviewchild + '<thead>';
                reviewchild = reviewchild + '<tr>';
                reviewchild = reviewchild + '<th width="35px">Sr No.</th>';  
                reviewchild = reviewchild + '<th width="35px">Patient</th>';                         
                reviewchild = reviewchild + '<th width="205px">DOB</th>';
                reviewchild = reviewchild + '<th width="205px">Clinic</th>';
                reviewchild = reviewchild + '<th width="97px">Provider</th>';
                reviewchild = reviewchild + '<th width="97px">CareManager</th>'; 
                reviewchild = reviewchild + '<th width="97px">Device</th>';
                reviewchild = reviewchild + '<th width="97px">Range</th>'; 
                reviewchild = reviewchild + '<th width="97px">Reading</th>';  
                reviewchild = reviewchild + '<th width="97px">Timestamp</th>';
                reviewchild = reviewchild +  '</tr></thead><tbody></tbody> </table></div>';
                row.child( reviewchild ).show();
                getReviewMonthlyChildList(row,row.data().FindField1,patient_id,fromdate,unittable);
            }
        });


  var getReviewMonthlyChildList = function(row=null,$Find1=null,patient_id=null,fromdate=null,unittable=null)
                {
                   var columns =  [   
                                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                                { data: 'pfname',name: 'pfname',
                                    mRender: function(data, type, full, meta){
                                        m_Name = full['pmname'];
                                            if(full['pmname'] == null){
                                                m_Name = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return ["<a href='#'><img src={{asset('assets/images/faces/avatar.png')}} width='50px' class='user-image' />"]+' '+full['pfname']+' '+m_Name+' '+full['plname']+'</a>';
                                        }
                                    },
                                    orderable: false
                                },
                                { data: 'pdob', type: 'date-dd-mmm-yyyy', name: 'pdob',
                                    "render":function (value) {
                                    if (value === null) return "";
                                        return moment(value).format('MM-DD-YYYY');
                                    }
                                },
                         
                                { data: 'practicename',name: 'practicename',
                                    mRender: function(data, type, full, meta){
                                        practice_name = full['practicename'];
                                            if(full['practicename'] == null){
                                                practice_name = '';
                                        }
                                        else{
                                            return practice_name;
                                        }
                                         
                                    },
                                    orderable: false
                                },
                                { data: 'providername',name: 'providername',
                                    mRender: function(data, type, full, meta){
                                        providername = full['providername'];
                                            if(full['providername'] == null){
                                                providername = '';
                                        }
                                        else{
                                            return providername;
                                        }
                                         
                                    },
                                    orderable: false
                                },
                                { data: 'caremanagerfname',name: 'caremanagerfname',
                                    mRender: function(data, type, full, meta){
                                        caremanagerfname = full['caremanagerfname'];
                                            if(full['caremanagerfname'] == null){
                                                caremanagerfname = '';
                                        }
                                        else{
                                            return full['caremanagerfname']+' '+full['caremanagerlname']; 
                                        }
                                        
                                    },
                                    orderable: false
                                },
                                { data: 'unit',name: 'unit',
                                    mRender: function(data, type, full, meta){
                                        r_unit = full['unit'];
                                            if(full['unit'] == null){
                                                r_unit = '';
                                            }
                                            else{
                                                if(r_unit == '%'){
                                                    return 'Pulse Oximeter';
                                                }
                                                else if(r_unit == 'beats/minute'){
                                                    return 'Blood Pressure Cuff';
                                                }
                                                else{
                                                    //mmHg
                                                    return 'Spirometer';
                                                }
                                            }
                                        
                                    },
                                    orderable: false
                                },

                                { data: 'fname',name: 'fname',
                                    mRender: function(data, type, full, meta){
                                        m_Name = full['mname'];
                                            if(full['mname'] == null){
                                                m_Name = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return 0;
                                        }
                                    },
                                    orderable: false
                                },
                                { data: 'reading',name: 'reading',  
                                    mRender: function(data, type, full, meta){
                                        m_reading = full['reading'];
                                        m_unit = full['unit'];
                                            if(full['reading'] == null){
                                                m_reading = '';
                                        }
                                        else{
                                            return m_reading+' '+m_unit;
                                        }
                                        // if(data!='' && data!='NULL' && data!=undefined){
                                        //     return 0;
                                        // }
                                    },
                                    orderable: false  
                                },  

                                {data:null,
                                    mRender: function(data, type, full, meta){
                                        totaltime = full['csseffdate'];  
                                        if(full['csseffdate'] == null){
                                            totaltime = '';
                                        }
                                        if(data!='' && data!='NULL' && data!=undefined){
                                            return totaltime;
                                        }
                                    },
                                    orderable: true   
                                }
                  ]               
                
                    var childurl = "/rpm/monthly-review-list-details/"+patient_id+'/'+fromdate+'/'+unittable;  
                   
                    var childtableid='ReviewMonthly-Child-list'+patient_id;
                    childtable = util.renderDataTable(childtableid, childurl, columns, "{{ asset('') }}");

                }


                 function destroyChild(row) {
            var tabledestroy = $("childtable", row.child());
            tabledestroy.detach();
            tabledestroy.DataTable().destroy();
            // And then hide the row
            row.child.hide();
            }


// commented by radha 15 may 2021 (changed in requirement .they don't want to add time)
// function addTimeFunction1(){
//     var patient_id = $("#patient_id").val();
//     var component_id = $("#component_id").val();
//     $.ajax({
//         url: '/rpm/daily-reading-record-time',
//         type: 'Post',
//         data: {
//                 patient_id: patient_id,
//                 component_id :  component_id  
//             },
//         success: function(response) {
//             if($.trim(response)==200){
//                 var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Time Added Successfully!</strong></div>';
//                 $("#time_add_success_msg").html(txt);
//                 var scrollPos = $(".main-content").offset().top;
//                 $(window).scrollTop(scrollPos);
//                 setTimeout(function () {
//                     $("#time_add_success_msg").hide();
//                 }, 3000);
//             }
//         },
//     });
// }



function dailyReadingFunction(patient_id,device_id){   

    $.ajax({
        url: '/rpm/daily-device-reading/'+patient_id+'/'+device_id,
        type: 'get',
        // data: form_data,
        success: function(data) {
            console.log(data.reading[0] + "testingggg" +data.unit[0]);
             var today ="N/A";
              var today_unit="";
            if(data.reading[0]!='' && data.unit[0]!=''){
               today = data.reading[0]; 
                today_unit= data.unit[0];                
            }
           if(data.reading[1]!='' && data.unit[1]!=''){
                var yester_day = data.reading[1];
                var yester_day_unit= data.unit[1];
            }if(data.reading[2]!='' && data.unit[2]!=''){
                var two_day = data.reading[2];
                var two_unit= data.unit[2];
            }if(data.reading[3]!='' && data.unit[3]!=''){
                var three_day = data.reading[3];
                var three_unit= data.unit[3];
            }if(data.reading[4]!='' && data.unit[4]!=''){
                var four_day = data.reading[4];
                var four_unit= data.unit[4];
            }if(data.reading[5]!='' && data.unit[5]!=''){  
                var five_day = data.reading[5];
                var five_unit= data.unit[5];
            }if(data.reading[6]!='' && data.unit[6]!=''){
                var six_day = data.reading[6];
                var six_unit= data.unit[6];
            }if(data.reading[7]!='' && data.unit[7]!=''){
                var seven_day = data.reading[7];
                var seven_unit= data.unit[7];
            }
                      
             if(today=='N/A'){
                $("#today").text(today);  
                $("#today_unit").text('');              
             }else{
                $("#today").text(today);
                $("#today_unit").text(today_unit);
             } 
             if(yester_day=='N/A'){
                $("#yester_day").text(yester_day);                
                $("#yester_day_unit").text('');    
             }else{
                $("#yester_day").text(yester_day);
                $("#yester_day_unit").text(yester_day_unit);   
             }
             if(two_day=='N/A'){
                $("#two_day").text(two_day);                
                $("#two_unit").text('');    
             }else{
                $("#two_day").text(two_day);
                $("#two_unit").text(two_unit);   
             }
             if(three_day=='N/A'){
                $("#three_day").text(three_day);                
                $("#three_unit").text('');
             }else{
                $("#three_day").text(three_day);
                $("#three_unit").text(three_unit);   
             }
             if(four_day=='N/A'){
                $("#four_day").text(four_day);                
                $("#four_unit").text('');  
             }else{
                $("#four_day").text(four_day);
                $("#four_unit").text(four_unit);   
             }
             if(five_day=='N/A'){
                $("#five_day").text(five_day);                
                $("#five_unit").text('');  
             }else{
                $("#five_day").text(five_day);
                $("#five_unit").text(five_unit);   
             }
             if(six_day=='N/A'){
                $("#six_day").text(six_day);                
                $("#six_unit").text('');    
             }else{
                $("#six_day").text(six_day);
                $("#six_unit").text(six_unit);   
             }
             if(seven_day=='N/A'){
                $("#seven_day").text(seven_day);                
                $("#seven_unit").text(''); 
             }else{
                $("#seven_day").text(seven_day);
                $("#seven_unit").text(seven_unit);   
             }

        },
    });
}

        $("#device_name").change(function (){
            //alert($(this).val());
            var patient_id = $('#patient_id').val();
            var device_id = $(this).val();
            dailyReadingFunction(patient_id,device_id);
        });

          $("#month-search").click(function(){   
                var patient_id = $('#patient_id').val();              
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
                    getMonthlyReviewPatientList(patient_id,monthly,monthlyto); 
                }
        
            });

           $("#month-reset").click(function(){
             var patient_id = $('#patient_id').val(); 
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
                
                var monthly =  $("#monthly").val();
                var monthlyto = $("#monthlyto").val();               
       
        getMonthlyReviewPatientList(patient_id,monthly,monthlyto);
        });


</script>