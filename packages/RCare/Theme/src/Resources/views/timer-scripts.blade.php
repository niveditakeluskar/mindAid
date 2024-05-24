@extends('Theme::layouts.master')

@section('main-content')
<style>
    .demo-div {
        border:1px solid; 
        border-radius:5px;
        padding: 10px;
    }
</style>
<div class="breadcrusmb">
	<div class="row">
		<div class="col-md-11">
			<h4 class="card-title mb-3">Timer</h4>
		</div>
	</div>
	<!-- ss -->             
</div>
<div class="separator-breadcrumb border-top"></div>


<div class="mb-4 demo-div">
    <div class="stopwatch" id="stopwatch">
        <div id="time-container" class="container"></div>
        <button class="button" id="start"><img src="{{asset("assets/images/btn-play.jpeg")}}" class='user-image' style=" width: 34px;" /></button>
        <button class="button" id="pause"><img src="{{asset("assets/images/btn-pause.svg")}}" class='user-image' style=" width: 34px;" /></button>
        <button class="button" id="stop"><img src="{{asset("assets/images/btn-stop.png")}}" class='user-image' style=" width: 34px;" /></button>
        <div>Value when Clock Stopped<div id="display-val"></div></div>
        <div><button class="button" onclick="stopRunningClock()">Click Button to stop Clock and get time Alert</button></div>
    </div>
</div>

@endsection

@section('page-js')
<script type="text/javascript">
    // var duration = moment.duration({
    //     'seconds': 30,
    //     'hour': 1,
    //     'minutes': 10
    // });
    //     var time = '00:02:12';
    //     var splitTime = time.split(":");
    //     var h = splitTime[0];
    //     var m = splitTime[1];
    //     var s = splitTime[2];
    //     var duration = moment.duration({
    //         'seconds': s,
    //         'hour': h,
    //         'minutes': m
    //     });

    // var timestamp = new Date(0,0,0,2,10,30);
    // var interval = 1;
    // setInterval(function () {
    //     // timestamp = new Date(timestamp.getTime() + interval*1000);
    //     duration = moment.duration(duration.asSeconds() + interval, 'seconds');
    //     //.asSeconds() 
    //     // $('.countdown').text(Math.round(duration.asHours()) + 'h:' + Math.round(duration.asMinutes()) + 'm:' + Math.round(duration.asSeconds()) + 's'); //.seconds() 

    //    // $('.countdown1').text(duration.days() + 'd:' + duration.hours() + 'h:' + duration.minutes() + 'm:' + duration.seconds() + 's');
    //    // $('.countdown2').text(timestamp.getDay()+'d:'+timestamp.getHours()+'h:'+timestamp.getMinutes()+'m:'+timestamp.getSeconds()+'s');
        
    // }, 1000);

    var time = '00:02:12';
        var splitTime = time.split(":");
        var h = splitTime[0];
        var m = splitTime[1];
        var s = splitTime[2];
    var AppStopwatch = (function () {
        var counter = s,
            $stopwatch = {
                el: document.getElementById('stopwatch'),
                container: document.getElementById('time-container'),
                startControl: document.getElementById('start'),
                pauseControl: document.getElementById('pause'),
                stopControl: document.getElementById('stop')
            };

        var runClock;

        function displayTime() {
            // var timestamp = new Date(0,0,0,0,0,0);
            // var interval = 1;
            // timestamp = new Date(timestamp.getTime() + interval*1000);
            // var counter = counter + s;
            $stopwatch.container.innerHTML = moment().hour(h).minute(m).second(counter++).format('HH : mm : ss');
        }

        function startWatch() {
            $("#start").hide();
            $("#pause").show();
            runClock = setInterval(displayTime, 1000);
        }

        function pauseWatch() {
            $("#start").show();
            $("#pause").hide();
            clearInterval(runClock);
        }

        function stopWatch() {
            
            alert(counter);
            $("#display-val").html($stopwatch.container.innerHTML);
            clearInterval(runClock);
            counter=0;
            // $("#start").show();
        }

        return {
            startClock: startWatch,
            pauseClock: pauseWatch,
            stopClock: stopWatch,
            $start: $stopwatch.startControl,
            $pause: $stopwatch.pauseControl,
            $stop: $stopwatch.stopControl
        };
    })();

    AppStopwatch.$start.addEventListener('click', AppStopwatch.startClock, false);
    AppStopwatch.$pause.addEventListener('click', AppStopwatch.pauseClock, false);
    AppStopwatch.$stop.addEventListener('click', AppStopwatch.stopClock, false);
    $( document ).ready(function() {
        $("#start").hide();
        $("#pause").show();
        $("#time-container").val(AppStopwatch.startClock);
    });

    function stopRunningClock() {
        console.log("in stop running clock");
        // AppStopwatch.$stop.addEventListener('click', AppStopwatch.stopClock, false);
        $("#time-container").val(AppStopwatch.stopClock);
        $("#start").show();
        $("#pause").hide();
    }

</script>
@endsection