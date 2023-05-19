<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
  
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.js"></script>
  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
</head>
<body>
  
<div class="container">
    <h1>Calendar</h1>
    <br>
    <input type="hidden" id="patient_id" name="patientId" value="{{$patient_id}}">
    <input type="hidden" id="module_id" name="moduleId" value="{{$module_id}}">
    <div id='calendar'></div>
</div>
   
<script>
	var patientId =$("#patient_id").val();
	var moduleId = $("#module_id").val();
    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
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
        // eventRender: function(event, element) {
        //      $(element).tooltip({title:event.title});
        //      $(element).tooltip({Patient:event.Patient});
        //      $(element).tooltip({assignby:event.assignby});
        // }, 
        eventMouseover: function(calEvent, jsEvent) {
            var tooltip = '<div class="tooltipevent" style="width:auto;border-style: solid;border-color: #2cb8e; background:#fff;color:#2cb8e;position:absolute;z-index:10001;">' 
            +'Assign By :' +calEvent.assignby +'<br>'
            //+'Title :' +calEvent.title +'<br>'
            +'Patient Name :' +calEvent.patient + '<br>'
            +'Time :' +calEvent.start.format("MM-DD-YYYY hh:mm a") 
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
        events:'/org/getcaldata/'+patientId+'/'+moduleId+'/cal',
        //events:'/org/getcaldata/446932220/3'
        selectable: true, 
        selectHelper: true,       
    });
    
</script>
  
</body>
</html>