const URL_POPULATE = "/org/ajax/reportscheduler_populate";

// $('.sub-item-name').click(function(){
//     //alert(this.textContent.trim());
//     alert("working");
//     if((this.textContent.trim()) == "Report Scheduler"){
//         alert("knkjklk");

//         var element = document.getElementById('practice-icon-tab');
//         element.classList.remove('active');

//         var element = document.getElementById('practice-group-icon-tab');
//         element.classList.add('active');

//         var element = document.getElementById('practice');
//         element.classList.remove('show' , 'active');

//         var element = document.getElementById('practice-group');
//         element.classList.add('show' , 'active');   
//     }
// });

var populateForm = function (id, url) {
    $.get(
        url,
        id,
        function (result) {
            //console.log(result);
            for (var key in result) {
                form.dynamicFormPopulate(key, result[key]);
                // alert(result[key][0]['report_id']);
                if (result[key][0]['report_id'] != null) {
                    var report_id = result[key][0]['report_id'];
                    $("#report_id").val(report_id);
                    $("#report_format").val(result[key][0]['report_format']);
                    var frequency = $("#frequency").val(result[key][0]['frequency']);
                    //alert(JSON.stringify(result[key][0]['frequency']));
					var freq = result[key][0]['frequency'];
                    if(freq == 'daily'){
                        $("#days").hide();
                        $("#months").hide();
                        $("#week").hide();
                    }else if(freq == 'weekly'){
                        $("#days").hide();
                        $("#months").hide();
						$("#week").show();

                    } else if(freq == 'monthly'){
						$("#days").show();
                        $("#months").hide();
						$("#week").hide();
					}else if(freq == 'yearly'){
						$("#days").show();
                        $("#months").show();
						$("#week").hide();
					}
                    // $("#day_of_execution option").val(result[key][0]['day_of_execution'])
                    $('select[name="day_of_execution"] option[value="'+result[key][0]['day_of_execution']+'"]').attr("selected","selected");
                    $("#date_of_execution").val(result[key][0]['date_of_execution']);
                    $("#month_of_execution").val(result[key][0]['month_of_execution']);
                 	$("#year_of_execution").val(result[key][0]['year_of_execution']);
                 	$("#week_of_execution").val(result[key][0]['week_of_execution']);
                    //alert(result[key].static['time_of_execution'] +'time_of_execution');
                 	$("#report_time_of_execution").val(result[key][0]['stime_of_execution']); 
                 	$("#report_comments").val(result[key][0]['comments']);
                 	//alert(result[key].static['comments']);
                    var substr = JSON.parse(result[key][0]['user_id']);


                    //disable reportdropdown and frequency

                    $("#report_id").prop('disabled', true);
                    $("#frequency").prop('disabled', true);



                    for(var i=0; i< substr.length; i++) {
                      document.getElementById("users_" + substr[i]).checked = true;
		                    var x = $('.wrapMulDrop ul li input[type="checkbox"]:checked').length;
		                    if (x != "") {
		                        $('.multiDrop').html(x + " " + "selected");
		                    } else if (x < 1) {
		                        $('.multiDrop').html('Select Users<i style="float:right;" class="icon ion-android-arrow-dropdown"></i>');
		                    }
                    }
                      
                    
                }
            }
        }

    ).fail(function (result) {
        console.error("Population Error:", result);
    });

};

var onAddReportScheduler = function (formObj, fields, response) {  

    


	var selected_options = $('.wrapMulDrop ul li input[type="checkbox"]:checked').length;
    if(selected_options == 0){
        $("#user-required").html('The users feild is required.');
    }
    if (response.status == 200) {
        $("#success").show();
        $("#user-required").html('');
        getReportSchedulerlisting();
        if ($("#modelHeading1").text() == 'Add Report Scheduler') {
            var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Reports Added Successfully!</strong></div>';
        } else {
            var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Reports Updated Successfully!</strong></div>';
        }
        $("#AddReportSchedulerForm")[0].reset();
        if($('#user-required').hasClass("select2-hidden-accessible")){
            $('#user-required').select2('destroy');  
        }

        $("#multiDrop").text('Select Users');


        //$('#add_reportsmaster_modal').modal('hide'); 
        $("#success").html(txt);
        var scrollPos = $(".main-content").offset().top;
        $(window).scrollTop(scrollPos);
        setTimeout(function () {
            $("#success").hide();
        }, 3000);
        $('#add_report_modal').modal('hide');
    }   
};


$('body').on('click', '.editReportScheduler', function () {
    // $("form[name='AddReportSchedulerForm'] #report_name").removeClass('is-invalid');
    // $("form[name='AddReportSchedulerForm'] #report_name").next('.invalid-feedback').html('');
    $("#AddReportSchedulerForm")[0].reset();
    $("#modelHeading1").text('Edit Report Scheduler');
    $('#add_report_modal').modal('show');
    $('.multiDrop').html('Select Users<i style="float:right;" class="icon ion-android-arrow-dropdown"></i>');
    var id = $(this).data('id');
    $("#id").val(id); 
    var data = "";
    var formpopulateurl = URL_POPULATE + "/" + id + "/populate";
    populateForm(data, formpopulateurl);
});


$('#addReportScheduler').click(function () { 
    // debugger;
    // $("form[name='AddReportSchedulerForm'] #report_name").removeClass('is-invalid');
    // $("form[name='AddReportSchedulerForm'] #report_name").next('.invalid-feedback').html('');
    $("#modelHeading1").text('Add Report Scheduler');
    $("#AddReportSchedulerForm")[0].reset();  
    $('.multiDrop').html('Select Users<i style="float:right;" class="icon ion-android-arrow-dropdown"></i>');
    $("#week_of_execution").select2("val", "1");    
  

    $('#day_of_execution').prop('selectedIndex',0);
    $('#month_of_execution').prop('selectedIndex',0);

    $('#add_report_modal').modal('show');
    $("#report_id").prop('disabled', false);  
    $("#frequency").prop('disabled', false);
    $('#id').val("");
});

$('body').on('click', '.change_reportscheduler_status_active', function () {
    var id = $(this).data('id');
    //alert(id);
    if (confirm("Are you sure you want to Deactivate this Report")) {
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
                getReportSchedulerlisting();
                $("#success").show();
                var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong> Report Deactivated Successfully!</strong></div>';
                $("#success").html(txt);
                setTimeout(function () {
                    $("#success").hide();
                }, 3000);
            }
        });
    } else { return false; }
});




$('body').on('click', '.change_reportscheduler_status_deactive', function () {
    var id = $(this).data('id');
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $.ajax({
        type: 'post',
        url: '/org/reportscheduler-samefrequency-check/' + id,
        // data: {"_token": "{{ csrf_token() }}","id": id},
        data: { "id": id },
        success: function (response) {
           
            // alert(response);
            if(response==0){

                if (confirm("Are you sure you want to Activate this Report")) {
       
                    $.ajax({
                        type: 'post',
                        url: '/org/schedulerStatus/' + id,
                        // data: {"_token": "{{ csrf_token() }}","id": id},
                        data: { "id": id },
                        success: function (response) {
                            getReportSchedulerlisting();
                            $("#success").show();
                            var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong> Report Activated Successfully!</strong></div>';
                            $("#success").html(txt);
                            setTimeout(function () {
                                $("#success").hide();
                            }, 3000);
                        }
                    });
                } else { return false; }


            }else{  

                if (confirm("Are you sure you want to Activate this Report? This will inturn deactive other reporttype with same frequency! ")) {
       
                    $.ajax({
                        type: 'post',
                        url: '/org/reportscheduler-deactive-old/' + id,
                        // data: {"_token": "{{ csrf_token() }}","id": id},
                        data: { "id": id },
                        success: function (response) {
                            getReportSchedulerlisting();
                            $("#success").show();
                            var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong> Report Activated Successfully!</strong></div>';
                            $("#success").html(txt);
                            setTimeout(function () {
                                $("#success").hide();
                            }, 3000);
                        }
                    });
                } else { return false; }


            }

        }
    });





    



});

var init = function () {
    form.ajaxForm("AddReportSchedulerForm", onAddReportScheduler, function () {
        return true;
    });

};




window.reportScheduler = {
    init: init
    //onResult: onResult
};