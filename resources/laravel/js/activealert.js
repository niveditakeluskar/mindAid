// const URL_POPULATE = "/ajax/populate-vital-alerts";
var parameterscnt = 0;
var newoperation = 0;  
var i =0;



var onRPMCMNotesMainForm = function (formObj, fields, response) {   
    // console.log("response" + response.status); 
    // console.log("responsedata" + response);    
    
    
    if (response.status == 200 && $.trim(response.data)=='') {
       
        $("#rpm_cm_form")[0].reset();         
        getAlertHistoryList();
        $("#success").show();
        $('#rpm_cm_modal').modal('hide');
        var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Care Manager Notes Added Successfully!</strong></div>';
        $("#success").html(txt);
        var scrollPos = $(".main-content").offset().top;
        $(window).scrollTop(scrollPos);
        setTimeout(function () {
            $("#success").hide();
        }, 3000);
    }
    // else if(response.status == 200 &&  $.trim(response.data)=='already-exsits'){
    //     $("#add_scheduler_form")[0].reset();
       
    //     getSchedulerlisting();
    //     $("#success").show();
    //     $('#rpm_cm_modal').modal('hide'); 
    //     var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Scheduler Already Exsits for the given Services,Date,Day and Time!</strong></div>';
    //     $("#success").html(txt);
    //     var scrollPos = $(".main-content").offset().top;
    //     $(window).scrollTop(scrollPos);
    //     setTimeout(function () {
    //         $("#success").hide();
    //     }, 3000);
    // } 

}

// var onEditSchedulerMainForm = function (formObj, fields, response) {
//     if (response.status == 200) {
//         $("#edit_scheduler_form")[0].reset();
//         getSchedulerlisting();
//         $("#success").show();
//         $('#edit_scheduler_modal').modal('hide');
//         var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Scheduler Updated Successfully!</strong></div>';
//         $("#success").html(txt);
//         var scrollPos = $(".main-content").offset().top;
//         $(window).scrollTop(scrollPos);
//         setTimeout(function () {
//             $("#success").hide();
//         }, 3000);
//     }
// };




var init = function () {

console.log("call js");
   

    form.ajaxForm("rpm_cm_form", onRPMCMNotesMainForm, function () { 
        // var checkboxescount = $('input[type="checkbox"]:checked').length; 
        // if(checkboxescount == 0)
        // {
        //     $("#services-error").show();
        //     var txt = 'Please select atleast one service'; 
        //     $("#services-error").html(txt);  
        //     return false;   
        // }
        // else{
        //     $("#services-error").hide();  
        //     return true;         
        // }
      return true;
        
    });

    //  form.ajaxForm("edit_scheduler_form", onEditSchedulerMainForm, function () {
    //     return true;
    // });

    // $('body').on('click', '#scheduler-list .editScheduler', function () {
    //     $("#edit_scheduler_form")[0].reset();
    //     $('#edit_scheduler_modal').modal('show');
    //     var sPageURL = window.location.pathname;
    //     var parts = sPageURL.split("/"); 
    //     var id = $(this).data('id');
    //     var data = "";
    //     var formpopulateurl = URL_POPULATE + "/" + id;
    //     populateForm(data, formpopulateurl); 
    // });     

    
    
   

    
};

window.activealert={  
    init: init
}