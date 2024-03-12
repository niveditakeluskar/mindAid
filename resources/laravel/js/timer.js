/**
 * Invoked after the form has been submitted
 */
var AppStopwatch = (function () {
    // var counter = 1,
    // var interval = 1,
    var counter = S, counterZeroSet = 0,
        $stopwatch = {
            el: document.getElementById('stopwatch'),
            container: document.getElementById('time-container'),
            startControl: document.getElementById('start'),
            pauseControl: document.getElementById('pause'),
            stopControl: document.getElementById('stop'),
            resetControl: document.getElementById('reset'),
            resetTickingTimeControl: document.getElementById('resetTickingTime')
        };

    var runClock;
	
	function getTimeDiff(start, end) {
	  return end-start;
	  //return moment.duration(moment(end, "HH:mm:ss a").diff(moment(start, "HH:mm:ss a")));
	}
	
	function getTimeSum(start, end) {
	  return end + start;
	  //return moment.duration(moment(end, "HH:mm:ss a").diff(moment(start, "HH:mm:ss a")));
	}
	
	function displayTime() {
		var start_time =  moment($("#page_landing_time").val(), 'hh:mm:ss'); //.format('HH:mm:ss');
		var pt = moment.duration($("#patient_time").val()).asMilliseconds(); //moment($("#patient_time").val());
		var now = moment(); 
		var timediff = getTimeDiff(start_time, now); // moment().diff(start_time, 'minutes');	
		
		var addpt = getTimeSum(parseInt(timediff), parseInt(pt));
		//console.log("start_time "+start_time+" pt "+pt+" now "+now+" timediff "+timediff+" addpt "+addpt+" formatted val"+ moment.duration(addpt, "milliseconds").format("hh:mm:ss"));
		
		var pauseplaydiff = moment.duration($("#pauseplaydiff").val()).asMilliseconds();  //moment($("#pauseplaydiff").val(), 'hh:mm:ss');
		
		var addptminuspauseplaydiff = getTimeDiff(parseInt(pauseplaydiff), parseInt(addpt));
		
		//console.log("start_time "+start_time+" pt "+pt+" now "+now+" timediff "+timediff+ " timediffformatted "+ moment.duration(timediff, "milliseconds").format("hh:mm:ss")+ " addpt "+addpt + " addptformatted "+ moment.duration(addpt, "milliseconds").format("hh:mm:ss")+" pauseplaydiff "+pauseplaydiff + " addptminuspauseplaydiff "+addptminuspauseplaydiff+  " final display time "+moment.duration(addptminuspauseplaydiff, "milliseconds").format("hh:mm:ss"));
		
		var displaytime = moment.duration(addptminuspauseplaydiff, "milliseconds").format("hh:mm:ss");
		hour = '00';
		min = '00';
		sec = '00';
		var displaytimearr = displaytime.split(':');
		//console.log(displaytimearr.length);
		if(displaytimearr.length==3){
			hour = displaytimearr[0];
			min = displaytimearr[1];
			sec = displaytimearr[2];
		}
		else if(displaytimearr.length==2){
			min = displaytimearr[0];
			sec = displaytimearr[1];
		}else{
			sec = displaytimearr[0];
		}
		
		
		$stopwatch.container.innerHTML = hour+":"+min+":"+sec;
	}

    /*function displayTime() {
		
		var start_time =  moment($("#page_landing_time").val(), 'hh:mm:ss'); //.format('HH:mm:ss');
		var pt = moment($("#patient_time").val(), 'HH:mm:ss');
		var patient_time = moment.duration(pt.format("HH:mm:ss")).asMinutes(); // comment
		//$("#patient_time").val(); // moment($("#patient_time").val(), 'HH:mm:ss');
		
        // $stopwatch.container.innerHTML = moment().hour(H).minute(M).second(counter++).format('HH:mm:ss');
		var now = moment(); //.format('HH:mm:ss');
	
	    var timediff = getTimeDiff(start_time, now); // moment().diff(start_time, 'minutes');
		//console.log("start_time "+start_time+" pt "+pt+" patient_time "+patient_time+ " now "+now+" timediff "+timediff);
		var pauseplaydiff = moment($("#pauseplaydiff").val(), 'hh:mm:ss');
		
		var totalpatienttime = getTimeSum(pt , timediff);
		console.log("totalpatienttime "+moment.duration(totalpatienttime, "milliseconds").format("HH:mm:ss"));
		
		//var totalpatienttime = timediff.add(patient_time, 'minutes');
		var totalpatienttimeformatted = moment.duration(totalpatienttime, "milliseconds").format("HH:mm:ss");
		
		//var totaltimeminuspauseplaydiff = getTimeDiff(moment(pauseplaydiff,'hh:mm:ss'), moment(totalpatienttimeformatted, 'hh:mm:ss'));
		var totaltimeminuspauseplaydiff = getTimeDiff(totalpatienttime, pauseplaydiff);
		console.log("totaltimeminuspauseplaydiff "+moment.duration(totaltimeminuspauseplaydiff, "milliseconds").format("HH:mm:ss"));
		//console.log("totalpatienttime "+totalpatienttime+" totalpatienttimeformatted"+totalpatienttimeformatted+" pauseplaydiff "+ pauseplaydiff+" totaltimeminuspauseplaydiff "+totaltimeminuspauseplaydiff);
		
		
		
		
		//var mom = moment(totalpatienttime,'HHmmss');
		//console.log("totalpatienttime"+moment(timediff).format('HH:mm:ss'));
		//var mom = moment(totalpatienttime, 'HHmmss');
		//console.log(mom.hours());
		//console.log(totaltimeminuspauseplaydiff);
		//console.log("timediff "+timediff+ " patient_time "+patient_time+ " totalpatienttime ");
		
		//console.log(now.format('HH:mm:ss') + " starttime " + start_time.format('HH:mm:ss')+ " timediff "+ timediff + " totalpatienttime "+totalpatienttime);
		var hour = (`${totaltimeminuspauseplaydiff.hours()}`<10? `0`+`${totaltimeminuspauseplaydiff.hours()}`:`${totaltimeminuspauseplaydiff.hours()}`);
		var mins = (`${totalpatienttime.minutes()}`<10? `0`+`${totalpatienttime.minutes()}`:`${totaltimeminuspauseplaydiff.minutes()}`);
		var secs = (`${totalpatienttime.seconds()}`<10? `0`+`${totalpatienttime.seconds()}`:`${totalpatienttime.seconds()}`);
		
		//console.log(hour+':'+mins+':'+secs);
		//console.log(`${totalpatienttime.hours()}:${totalpatienttime.minutes()}:${totalpatienttime.seconds()}`);
		//console.log(totalpatienttime.format('HH:mm:ss'));
		
		//console.log(now.format('HH:mm:ss') + " - starttime:" + start_time.format('HH:mm:ss') + " - pt: "+ patient_time+ "timediff: "+moment(timediff).format('HH:mm:ss')+ " totaltime: "+moment(addpatienttime).format('HH:mm:ss'));
		$stopwatch.container.innerHTML = hour+':'+mins+':'+secs;
    }
*/
    function resetDisplayTimeToZero() { 
        $stopwatch.container.innerHTML = moment().hour(H).minute(M).second(counterZeroSet++).format('HH:mm:ss');
    }
	
    function startWatch() {
		if($("#pause").is(":hidden")){
		var pausetime = $("#pause_time").val();
			
		var playtime = "0";
		var pauseplaydiff = $("#pauseplaydiff").val();
		
		//pauseplaydiffmin = moment.duration(pauseplaydiff.format("HH:mm:ss")).asMinutes();
		
		if(pausetime!='0'){
			console.log("pausetime1 "+pausetime);
			pausetime = moment(pausetime);
			playtime = moment();
			console.log("pausetime2 "+pausetime);
			//console.log("pausetime"+pausetime+" playtime "+playtime);
			pauseplaydiff_new = getTimeDiff( pausetime, playtime);
			$("#play_time").val(playtime);
			console.log("pausetime"+pausetime+" playtime "+playtime+ " pauseplaydiff_new "+pauseplaydiff_new+ " pauseplaydiff "+pauseplaydiff);
			pauseplaydiffsum = getTimeSum(parseInt(pauseplaydiff_new), parseInt(pauseplaydiff));
			console.log("pausetime"+pausetime+" playtime "+playtime+ " pauseplaydiff_new "+pauseplaydiff_new+ " pauseplaydiff "+pauseplaydiff+" pauseplaydiffsum "+pauseplaydiffsum );
			$("#pauseplaydiff").val(pauseplaydiffsum);
			
			/*
			var hour = (`${pauseplaydiff.hours()}`<10? `0`+`${pauseplaydiff.hours()}`:`${pauseplaydiff.hours()}`);
			var mins = (`${pauseplaydiff.minutes()}`<10? `0`+`${pauseplaydiff.minutes()}`:`${pauseplaydiff.minutes()}`);
			var secs = (`${pauseplaydiff.seconds()}`<10? `0`+`${pauseplaydiff.seconds()}`:`${pauseplaydiff.seconds()}`);			
			*/
		}
		
        $("#start").hide();
        $("#pause").show();    
		}		
        runClock = setInterval(displayTime, 1000);
    }

    function pauseWatch() {
		if($("#start").is(":hidden")){
			var pausetime = '0';
			pausetime = moment();
			$("#pause_time").val(pausetime);
			$("#start").show();
			$("#pause").hide();
			clearInterval(runClock);
		}
    }

    function stopWatch() {
       // if (confirm('Are you sure you want to stop timer?')) {
            $("#display-val").html($stopwatch.container.innerHTML);
			var pausetime = '0';
			pausetime = moment();
			$("#pause_time").val(pausetime);
            clearInterval(runClock);
            interval = 0;
            $("#start").show();
            $("#pause").hide();
            $("#stop").hide();
       // }
    }

    function resetWatch() {
        clearInterval(runClock);
        interval = 0;
        runClock = setInterval(resetDisplayTimeToZero, 1000);
    }

    function resetTickingTimeWatch() {	
        interval = 0;
		counter = S;        
        runClock = setInterval(displayTime, 1000);		
    }

    return {
        startClock: startWatch,
        pauseClock: pauseWatch,
        stopClock: stopWatch,
        resetClock: resetWatch,
        resetTickingTimeClock: resetTickingTimeWatch,
        $start: $stopwatch.startControl,
        $pause: $stopwatch.pauseControl,
        $stop: $stopwatch.stopControl,
        $reset: $stopwatch.resetControl,
        $resetTickingTime: $stopwatch.resetTickingTimeControl
    };
})();

AppStopwatch.$start.addEventListener('click', AppStopwatch.startClock, false);
AppStopwatch.$pause.addEventListener('click', AppStopwatch.pauseClock, false);
AppStopwatch.$stop.addEventListener('click', AppStopwatch.stopClock, false);
AppStopwatch.$reset.addEventListener('click', AppStopwatch.resetClock, false);
AppStopwatch.$resetTickingTime.addEventListener('click', AppStopwatch.resetTickingTimeClock, false);


$( document ).ready(function() {
	$("#page_landing_time").val(moment(Date.now()).format('HH:mm:ss'));
	$("#patient_time").val($("#timer_start").val());
	/*
	$("#pause").click(function(){
		// $("#time-container").val(AppStopwatch.pauseClock);
        var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		var module_id = 3;
		var component_id = 19;
		var patient_id = 706138193;
		var uid = 706138193;

        $("#timer_start").val(timer_paused);
		// $("#time-container").val(AppStopwatch.startClock);

		axios({
			method: "POST",
			url: `/system/log-timer-button-action/time`,
			data: {
				timer_on: timer_start,
        		timer_off: timer_paused,
        		module_id: module_id,
        		component_id: component_id,
        		patient_id: patient_id,
        		uid: uid,
        		action_taken: "paused"
			}
		}).then(function (response) {
			if (JSON.stringify(response.data) != "" && JSON.stringify(response.data) != null && JSON.stringify(response.data) != undefined) {
				//alert("Time Paused.");
			} else {
				//alert("Unable to log time, please try after some time.");
			}
	
		}).catch(function (error) {
			console.error(error, error.response);
		});
	});
	$("#stop").click(function(){
		// $("#time-container").val(AppStopwatch.pauseClock);
        var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		var module_id = 3;
		var component_id = 19;
		var patient_id = 706138193;
		var uid = 706138193;

        $("#timer_start").val(timer_paused);
		// $("#time-container").val(AppStopwatch.startClock);

		axios({
			method: "POST",
			url: `/system/log-timer-button-action/time`,
			data: {
				timer_on: timer_start,
        		timer_off: timer_paused,
        		module_id: module_id,
        		component_id: component_id,
        		patient_id: patient_id,
        		uid: uid,
        		action_taken: "stoped"
			}
		}).then(function (response) {
			if (JSON.stringify(response.data) != "" && JSON.stringify(response.data) != null && JSON.stringify(response.data) != undefined) {
				alert("Time Stoped.");
			} else {
				alert("Unable to log time, please try after some time.");
			}
	
		}).catch(function (error) {
			console.error(error, error.response);
		});
	});
	$("#start").click(function(){
		// $("#time-container").val(AppStopwatch.pauseClock);
        var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		var module_id = 3;
		var component_id = 19;
		var patient_id = 706138193;
		var uid = 706138193;

        $("#timer_start").val(timer_paused);
		// $("#time-container").val(AppStopwatch.startClock);

		axios({
			method: "POST",
			url: `/system/log-timer-button-action/time`,
			data: {
				timer_on: timer_start,
        		timer_off: timer_paused,
        		module_id: module_id,
        		component_id: component_id,
        		patient_id: patient_id,
        		uid: uid,
        		action_taken: "started"
			}
		}).then(function (response) {
			if (JSON.stringify(response.data) != "" && JSON.stringify(response.data) != null && JSON.stringify(response.data) != undefined) {
				alert("Time Started.");
			} else {
				alert("Unable to log time, please try after some time.");
			}
	
		}).catch(function (error) {
			console.error(error, error.response);
		});
	});
	*/
});
