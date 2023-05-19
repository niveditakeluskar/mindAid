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
            resetControl: document.getElementById('reset')
        };

    var runClock;

    function displayTime() {
        $stopwatch.container.innerHTML = moment().hour(H).minute(M).second(counter++).format('HH:mm:ss');
    }

    function resetDisplayTimeToZero() {
        $stopwatch.container.innerHTML = moment().hour(H).minute(M).second(counterZeroSet++).format('HH:mm:ss');
    }

    function startWatch() {
        $("#start").hide();
        $("#pause").show();
        var end_time = $("#timer_start").val(); //timer_start updates when time is saved to db
        if(end_time != null && end_time != "" && end_time != undefined){
            $(".last_time_spend").html(end_time);
        }
        runClock = setInterval(displayTime, 1000);
    }

    function pauseWatch() {
        $("#start").show();
        $("#pause").hide();
        var end_time = $("#timer_start").val(); //timer_start updates when time is saved to db
        if(end_time != null && end_time != "" && end_time != undefined){
            $(".last_time_spend").html(end_time);
        }
        clearInterval(runClock);
    }

    function stopWatch() {
        if (confirm('Are you sure you want to stop timer?')) {
            $("#display-val").html($stopwatch.container.innerHTML);
            clearInterval(runClock);
            interval=0;
            $("#start").show();
            $("#pause").hide();
            $("#stop").show();
        }
    }

    function resetWatch() {
        clearInterval(runClock);
        interval=0;
        runClock = setInterval(resetDisplayTimeToZero, 1000);
    }

    return {
        startClock: startWatch,
        pauseClock: pauseWatch,
        stopClock: stopWatch,
        resetClock: resetWatch,
        $start: $stopwatch.startControl,
        $pause: $stopwatch.pauseControl,
        $stop: $stopwatch.stopControl,
        $reset: $stopwatch.resetControl
    };
})();

AppStopwatch.$start.addEventListener('click', AppStopwatch.startClock, false);
AppStopwatch.$pause.addEventListener('click', AppStopwatch.pauseClock, false);
AppStopwatch.$stop.addEventListener('click', AppStopwatch.stopClock, false);
AppStopwatch.$reset.addEventListener('click', AppStopwatch.resetClock, false);