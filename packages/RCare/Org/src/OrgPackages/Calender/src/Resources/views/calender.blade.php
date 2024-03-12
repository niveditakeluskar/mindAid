<!-- @section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/styles/external-css/fullcalnendar.main.css')}}">
@endsection -->

    <style type="text/css">

        .card {
            color: black;
        }
        .form-popup {
          display: none;
          position: fixed;
          /*bottom: 0;
          right: 15px;
          border: 3px solid #f1f1f1;
          z-index: 9;*/
        }

        .sidebarcalender .handle{
            top: 250px;
        }

        .sidebarcalender .sidebarcalender-body{
            max-height: calc(100vh - 100px);
        }

        .sidebarcalender{
            top:10px;
        }
        .icons{
            color: red;
            margin-left: 10px;
        }
    </style>
<div class="container">
     <div class="alert alert-success" id="success-alert" style="display: none;">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <strong>Task Added successfully! </strong><span id="text"></span>
              </div>
    <input type="hidden" id="patient_id" name="patientId" value="{{$patient_id}}">
    <input type="hidden" id="module_id" name="moduleId" value="{{$module_id}}">
    <div id='todo-calendar1'></div>
    <!-- Start popup dialog box -->
    <div class="modal fade" id="event_entry_modal" aria-hidden="true">
        <div class="modal-dialog modal-lg" style="width: 635px;">
             <div class="modal-content">
                <div class="modal-header"> 
                    <h4 class="modal-title" id="modelHeading1"><span class='form_type'></span>Add Task</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                 <form method="POST" enctype="multipart/form-data" name="AddEventForm" id="AddEventForm" action="javascript:void(0)" >
                    <div class="modal-body"> 
                        <input type="hidden" name="id" id="eventtask_id">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4 form-group mb-3 ">
                                   <label for="practicename">Practice<span class="error">*</span> </label>
                                    @selectpractices("practice_id",["id" => "todopractices"])
                                </div>
                                <div class="col-md-4 form-group mb-3 ">
                                    <label for="patient">Patient<span class="error">*</span> </label>
                                      @selectpatient("patient", ["class" => "select2","id" => "todopatient"]) 
                                </div>
                                <div class="col-md-4 form-group mb-3">
                                 <label for="module">Module Name<span class="error">*</span> </label>
                                        <select id="todomodules" class="custom-select show-tick" name="modules">
                                            <option value="">select module</option>
                                            <option value="3">CCM</option>
                                            <option value="2">RPM</option>  
                                        </select> 
                                </div>
                                <div class="col-md-4 form-group mb-3">
                                  <label for="submodule_id" class="">Sub Module<span class="error">*</span> </label>
                                    @select("Sub Module", "submodule_id", [], ["id" => "todosubmodule_id", "class"=>"sub-module"])
                                </div> 
                                <div class="col-md-4 form-group mb-3">
                                    <div class="form-group">
                                    <label for="event_start_date">Event start<span class="error">*</span> </label>
                                       @date('event_start_date',["id" => "event_start_date"])
                                    </div>
                                </div>
                                <div class="col-md-4 form-group mb-3">
                                    <label for="event_end_date">Event end<span class="error">*</span> </label>
                                    @date('event_end_date',["id" => "event_end_date"])
                                </div> 
                            
                                <div class="col-md-12 form-group mb-3">
                                    <label for="event_name">Task Event<span class="error">*</span> </label>
                                      @text("event_name",["id" => "event_name"])
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="momenttime" id="momenttime">
                    </div>
                    <div class="card-footer">
                        <div class="mc-footer">
                            <div class="row">
                                <div class="col-lg-12 text-right"> 
                                    <button type="submit"  class="btn  btn-primary m-1" id ="upload-doc-btn">Save</button>
                                    <button type="button" class="btn btn-outline-secondary m-1" data-dismiss="modal">Cancel</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
             </div>
        </div>
    </div>
    <!-- End popup dialog box -->
</div>
    <!-- <script src="{{  asset('assets/js/vendor/calendar/fullcalendar.min.js')}}"></script> -->
<script>


    var patient_id = $("input[name='patient_id']").val();
        if (patient_id==''){
            patient_id = 0;
        }
     var getflyout = function (){ 
            util.getToDoListData(patient_id, {{ getPageModuleName() }});
            util.getToDoListCalendarData(0, {{ getPageModuleName() }});
        } 

    $(document).ready(function() {  
       
        var patient_id = $("input[name='patient_id']").val();
        if (patient_id==''){
            patient_id = 0;
        }
        var module_id = $("input[name='module_id']").val();
        if(module_id == ''){
            module_id = 0;
        }

          
        var calendar =  $('#todo-calendar1').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'listMonth,month,agendaWeek,agendaDay'
                    //right: 'month,agendaWeek,agendaDay'
            },
            eventLimit:true,
            //defaultView: 'agendaDay',
            defaultView: 'listMonth',
            //defaultView: 'listWeek',
            views: {
                timeGridMonth: {
                    eventLimit: 1// adjust to 6 only for timeGridWeek/timeGridDay
                }
            },
            editable: true,
            droppable: true,
            navLinks: true,
            navLinkDayClick: 'agendaDay',

            select: function(start, end) {
               //  alert(moment(start).format('YYYY-MM-DD') +'moment');
                //alert(start);
                var validateday = moment(start).format('ddd');
                var momenttime1 = moment(start).format("HH:mm:ss");  //alert(momenttime);
                var momentstartdate = moment(start).format('YYYY-MM-DD');
                var momentenddate = moment(end).format('YYYY-MM-DD');
            
                $('#event_start_date').val(momentstartdate);
                $('#event_end_date').val(momentenddate);
                $('#momenttime').val(momenttime1);

                if(validateday == 'Sat' || validateday == 'Sun'){
                  alert("Please assign task in weeks day.!")
                } else{
                 $('#event_entry_modal').modal('show');
                }
            },

            eventMouseover: function(calEvent, jsEvent) {
                var tooltip = '<div class="tooltipevent" style="width:auto;border-style: solid;border-color: #2cb8e; background:#fff;color:#2cb8e;position:absolute;z-index:10001;">' 
                // +'Assign By :' +calEvent.assignby +'<br>'
                +'Title :' +calEvent.title +'<br>'
                // +'Patient Name :' +calEvent.patient + '<br>'
                 +'Time :' +calEvent.start.format("MM-DD-YYYY hh:mm a")+'<br>' 
                // +'module Id:' +calEvent.module_id +'<br>'
                // +'patient Id:' +calEvent.patient_id +'<br>'
                + '</div>';


                $("body").append(tooltip); 
                $(this).mouseover(function(e) {
                    $(this).css('z-index', 10000);
                    $('.tooltipevent').fadeIn('500');
                    $('.tooltipevent').fadeTo('10', 1.9);
                }).mousemove(function(e) {
                    $('.tooltipevent').css('top', e.pageY + 10);
                    $('.tooltipevent').css('left', e.pageX + 20);
                });
            },
            eventMouseout: function(calEvent, jsEvent) {
                $(this).css('z-index', 8);
                $('.tooltipevent').remove();
            },
            timezone: "local",
            // events:'/org/getcaldata',
            events:'/org/getcaldata/'+patient_id+'/'+module_id+'/cal',
            //events:'/org/getcaldata/446932220/3'
            eventRender: function(event, element) {
                if(event.icon){          
                    element.find(".fc-list-item-title").append("<i class='"+event.icon+" icons'></i>");
                }
            },
            eventDrop: function(calEvent) {
                alert(calEvent.title + " was dropped on " + calEvent.start.toISOString());
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $calday = calEvent.start.format("YYYY-MM-DD hh:mm a"); //09-01-2022 11:00 am 09-20-2022 12:00 am
               // alert($calday);
                function padTo2Digits(num) {
                  return num.toString().padStart(2, '0');
                }
                function formatDate(date) {
                    return (
                        [
                          date.getFullYear(),
                          padTo2Digits(date.getMonth()), //padTo2Digits(date.getMonth() + 1),
                          padTo2Digits(date.getDate()),
                        ].join('-') +
                        ' ' +
                        [
                          padTo2Digits(date.getHours()),
                          padTo2Digits(date.getMinutes()),
                          padTo2Digits(date.getSeconds()),
                        ].join(':')
                    );
                }
                $today = formatDate(new Date()); //2022-09-17 14:06:50 YYYY-MM-DD
                //alert($today); 
                //console.log(formatDate(new Date()));
                //console.log(calEvent.status_flag)

                if($calday < $today){
                    alert('reschedule the date today or next');
                } 
                if( calEvent.status_flag == '1'){
                    alert('Completed task not able to reschedule');
                } 
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                            url: '/org/updatecal', 
                            type: 'POST',
                            data: { 'scheduledtime': calEvent.start.format("MM-DD-YYYY HH:MM:SS"), 'cal_id' : calEvent.id },
                            success: function(event,delta,revertFunc)
                            {
                                getflyout();
                                console.log(event);
                                alert(event);
                            }

                });

                    // if (!confirm("Are you sure about this change?")) {
                    //   info.revert();
                    // }
            },
            selectable: true, 
            selectHelper: true,
        });

        $("[name='practice_id']").on("change", function () {
                    //console.log($(this).val()+"test"+{{ getPageModuleName() }});
                    if($(this).val()=='0' || $(this).val()==''){  
                        //getToDoListReport('0');
                        util.updatePatientList(parseInt(''), {{ getPageModuleName() }}, $("#todopatient"));
                    } else {     
                        util.updatePatientList(parseInt($(this).val()), {{ getPageModuleName() }}, $("#todopatient"));
                            
                    }
                        //util.updatePatientList(parseInt($(this).val()), $("#patient"));
        });

        $("[name='modules']").on("change", function () {
                util.updateSubModuleList(parseInt($(this).val()), $("#todosubmodule_id"))
        });

        $('#AddEventForm').submit(function(e) {

                e.preventDefault();
                var formName = $(`form[name="AddEventForm"]`);
                formName.find(".is-invalid").removeClass("is-invalid");
                formName.find(".invalid-feedback").html("");
                var formData = new FormData(this);

                var practices = $("#todopractices").val();
                var patient = $("#todopatient").val();
                var modules = $("#todomodules").val();
                var component = $("#todosubmodule_id").val();
                var event_name=$("#event_name").val();
                var event_start_date=$("#event_start_date").val();
                var event_end_date=$("#event_end_date").val();
                var event_time = $("#momenttime").val();
                
               //alert(component)
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                     url:"/org/addcal",
                     type:"POST",
                     data: formData, 
                     cache:false,
                     contentType: false,
                     processData: false,
                     // data: {
                     //        'practices':practices,
                     //        'patient':patient,
                     //        'modules':modules,
                     //        'component':component,
                     //        'event_name':event_name,
                     //        'event_start_date':event_start_date,
                     //        'event_end_date':event_end_date
                     //    },

                     success:function(data){ 
                        //dd(data);
                        // this.reset();
                         getflyout();
                        $("#AddEventForm")[0].reset();
                        $("#eventtask_id").val("");
                        $("#event_entry_modal").modal('toggle');
                        //$("#docspreloader").css("display","none");
                        //util.updateDocsOtherList($("form[name='AddDocumentForm'] #doc_type"));
                        $("#success-alert").show();
                        $("#success-alert strong").text(data);
                        setTimeout(function () { $("#success-alert").hide(); }, 5000);
                    },
                    error: function(response){ 
                      var fields = form.getFields('AddEventForm');
                      var errors = response.responseJSON.errors;
                      var fieldNames = Object.keys(errors);

                      for (let i = 0; i < fieldNames.length; i++) { 
                          try {
                              let field = fields.fields[fieldNames[i]];
                              if (!field)
                                  return;
                              if (field.attr("data-feedback")) {
                                  $(`[data-feedback-area="${field.attr("data-feedback")}"]`).html(errors[fieldNames[i]]);
                              } else {
                                  if (field.next(".invalid-feedback").length > 0) {
                                      field.next(".invalid-feedback").html(errors[fieldNames[i]]);
                                  } else {
                                      field.closest(".forms-element").next().html(errors[fieldNames[i]]);
                                      field.closest(".forms-element").next().css("display", "block");
                                  }
                              }
                              field.addClass("is-invalid");
                          } catch (e) {
                              console.error(`Ajax error reporting: for field ${fieldNames[i]}`, e);
                          }
                      }
                      alert(response);
                    }
                });    
                return false;
        });
    });
    
</script>
