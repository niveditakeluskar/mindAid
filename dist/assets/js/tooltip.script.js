$(document).ready(function () {
    $(function () {
        $('[data-toggle="tooltip"]').tooltip().each(function(){
            var pos  = $(this).attr('data-placement');
            if(pos != undefined &&  pos != "") {
                $(this).data('bs.tooltip').config.placement = pos; //'top';// For dynamic position
                $(this).data('bs.tooltip').config.boundary= pos; //'top'; // Used for tooltip boundary range
            }
        });
    });
    $('#manual').on('click', function () {
        $(this).tooltip('toggle')
    });
});