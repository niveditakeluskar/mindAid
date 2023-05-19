const URL_POPULATE = "/org/ajax/populateSchedulerForm";
var parameterscnt = 0;
var newoperation = 0;  
var i =0;

var populateForm = function (data, url) {
    $.get( url, data, function (result) {
       
    //   console.log(result);  
      for (var key in result) { 
        form.dynamicFormPopulate(key, result[key]); 
        var parameters = result[key].static['paramdata'];
        $("#scheduler_id").val(result[key].static.id);  
        var act = result[key].static['activity_id'];
        var checkboxvalues = parameters[0].modules;
        var operation = result[key].static['operation'];
        console.log(operation);
        if(operation == 'add'){
            newoperation = 1;
            console.log('if');
        }
        else{
            newoperation = 2;
            console.log('else');
        }
        for(var checkbox in checkboxvalues){       
            $("#modules_"+checkboxvalues[checkbox]).prop('checked',true);     
        }  
        // console.log(parameters[0]['activity']['activity']); 
         
        $("#editactivity").val(act).trigger('change');
        
        $("#operation").select2().val(newoperation).trigger('change');     
        
        
      } 
           
    } 

    
    ).fail(function (result) {
        console.error("Population Error:", result);
    });
}; 

var onSchedulerMainForm = function (formObj, fields, response) {
    // console.log("response" + response.status); 
    // console.log("responsedata" + response);    
    if (response.status == 200 && $.trim(response.data)=='') {
        $("#add_scheduler_form")[0].reset(); 
        // if ($('#activity').hasClass("select2-hidden-accessible")) {
        //     $('#activity').select2('destroy'); 
        // }
        // if ($('#day_of_execution').hasClass("select2-hidden-accessible")) {
        //     $('#day_of_execution').select2('destroy'); 
        // }
        // $("#activity").val('').trigger('change');
        // $("#day_of_execution").val('1').trigger('change'); 

        $("#activity").select2().val('').trigger('change');
        $("#day_of_execution").select2().val('1').trigger('change'); 

        getSchedulerlisting();
        $("#success").show();
        $('#add_scheduler_modal').modal('hide');
        var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Scheduler Added Successfully!</strong></div>';
        $("#success").html(txt);
        var scrollPos = $(".main-content").offset().top;
        $(window).scrollTop(scrollPos);
        setTimeout(function () {
            $("#success").hide();
        }, 3000);
    }
    else if(response.status == 200 &&  $.trim(response.data)=='already-exsits'){
        $("#add_scheduler_form")[0].reset();
        // if ($('#activity').hasClass("select2-hidden-accessible")) {
        //     $('#activity').select2('destroy'); 
        // }
        // if ($('#day_of_execution').hasClass("select2-hidden-accessible")) {
        //     $('#day_of_execution').select2('destroy'); 
        // }
        $("#activity").select2().val('').trigger('change');
        $("#day_of_execution").select2().val('1').trigger('change');   
        getSchedulerlisting();
        $("#success").show();
        $('#add_scheduler_modal').modal('hide'); 
        var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Scheduler Already Exsits for the given Services,Date,Day and Time!</strong></div>';
        $("#success").html(txt);
        var scrollPos = $(".main-content").offset().top;
        $(window).scrollTop(scrollPos);
        setTimeout(function () {
            $("#success").hide();
        }, 3000);
    } 

}

var onEditSchedulerMainForm = function (formObj, fields, response) {
    if (response.status == 200) {
        $("#edit_scheduler_form")[0].reset();
        getSchedulerlisting();
        $("#success").show();
        $('#edit_scheduler_modal').modal('hide');
        var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Scheduler Updated Successfully!</strong></div>';
        $("#success").html(txt);
        var scrollPos = $(".main-content").offset().top;
        $(window).scrollTop(scrollPos);
        setTimeout(function () {
            $("#success").hide();
        }, 3000);
    }
};




var init = function () {
    $('#addScheduler').click(function () {
        $('#add_scheduler_modal').modal('show');
    });

    form.ajaxForm("add_scheduler_form", onSchedulerMainForm, function () { 
        var checkboxescount = $('input[type="checkbox"]:checked').length; 
        // alert(checkboxescount);


        var checked1 = $("form[name='add_scheduler_form'] #services_CCM").prop("checked");
        var checked2 = $("form[name='add_scheduler_form'] #services_TCM").prop("checked");
        var checked3 = $("form[name='add_scheduler_form'] #services_AWV").prop("checked");
        var checked4 = $("form[name='add_scheduler_form'] #services_RPM").prop("checked");

        if ((checked1 == true || checked2 == true || checked3 == true || checked4 == true)){
            $("#services-error").css("display", "none");  
            // $("#scheduleevent").prop('disabled', false);
            return true;     
        }else{
            $("#services-error").show();
            var txt = 'Please select atleast one service'; 
            $("#services-error").html(txt);  
            setTimeout(function () { $('form[name="add_scheduler_form"]').find(":submit").attr("disabled", false) }, 3000);  
        }




        // if(checkboxescount == 0)
        // {
        //     $("#services-error").show();
        //     var txt = 'Please select atleast one service'; 
        //     $("#services-error").html(txt);  
        //     setTimeout(function () { $('form[name="add_scheduler_form"]').find(":submit").attr("disabled", false) }, 3000);
        //     // return false;   
        // }
        // else{
        //     $("#services-error").css("display", "none");  
        //     $("#scheduleevent").prop('disabled', false);  
        //     return true;   
               
        // }
      
        
    });

     form.ajaxForm("edit_scheduler_form", onEditSchedulerMainForm, function () {
        return true;
    });

    $('body').on('click', '#scheduler-list .editScheduler', function () {
        $("#edit_scheduler_form")[0].reset();
        $('#edit_scheduler_modal').modal('show');
        var sPageURL = window.location.pathname;
        var parts = sPageURL.split("/"); 
        var id = $(this).data('id');
        var data = "";
        var formpopulateurl = URL_POPULATE + "/" + id;
        populateForm(data, formpopulateurl); 
    });     

    

    $('body').on('click', '.change_scheduler_status_active', function () {
        var id = $(this).data('id');
        if (confirm("Are you sure you want to deactivate this Scheduler")) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: '/org/schedulerStatus/' + id,
                // data: {"_token": "{{ csrf_token() }}","id": id},
                data: { "id": id },
                success: function (response) {
                    getSchedulerlisting();
                    $("#success").show();
                    var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Scheduler Deactivated Successfully!</strong></div>';
                    $("#success").html(txt);
                    var scrollPos = $(".main-content").offset().top;
                    $(window).scrollTop(scrollPos);
                    //goToNextStep("call_step_1_id");
                    setTimeout(function () {
                        $("#success").hide();
                    }, 3000);
                }
            });
        } else { 
            return false; 
        }
    }); 

    $('body').on('click', '.change_scheduler_status_deactive', function () {
        var id = $(this).data('id');
        if (confirm("Are you sure you want to activate this Scheduler")) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: '/org/schedulerStatus/' + id,
                // data: {"_token": "{{ csrf_token() }}","id": id},
                data: { "id": id },
                success: function (response) {
                    getSchedulerlisting();
                    $("#success").show();
                    var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Scheduler Activated Successfully!</strong></div>';
                    $("#success").html(txt);
                    var scrollPos = $(".main-content").offset().top;
                    $(window).scrollTop(scrollPos);
                    //goToNextStep("call_step_1_id");
                    setTimeout(function () {
                        $("#success").hide();
                    }, 3000);
                }
            });
        } else { 
            return false; 
        }
    });
};

window.scheduler={
    init: init
}