/**
 * Invoked after the form has been submitted
 */
 
var AppStopwatch = (function () {
    var counter = 0,
        $stopwatch = {
            el: document.getElementById('stopwatch'),
            container: document.getElementById('time-container'),
            startControl: document.getElementById('start'),
            pauseControl: document.getElementById('pause'),
            stopControl: document.getElementById('stop')
        };

    var runClock;

    function displayTime() {
        var timestamp = new Date(0,0,0,0,0,0);
        var interval = 1;
        timestamp = new Date(timestamp.getTime() + interval*1000);
        $stopwatch.container.innerHTML = moment().hour(0).minute(0).second(counter++).format('HH : mm : ss');
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
        $("#display-val").html($stopwatch.container.innerHTML);
        clearInterval(runClock);
        counter=0;
        $("#start").show();
        $("#pause").hide();
        $("#stop").hide();
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

window.onload=function(){
    AppStopwatch.$start.addEventListener('click', AppStopwatch.startClock, false);
    AppStopwatch.$pause.addEventListener('click', AppStopwatch.pauseClock, false);
    AppStopwatch.$stop.addEventListener('click', AppStopwatch.stopClock, false);
}