const URL_POPULATE = "/rpm/ajax/populate-vital-alerts";
var parameterscnt = 0;
var newoperation = 0;  
var i =0;


var populateForm = function (data, url) {

	$.get(
		url, 
		data,
		function (result) {
			console.log(result);
            // console.log(result.rpm_cm_form[0].notes);
            var len = result.rpm_cm_form.length;
            // console.log(len);
            if(len>0){
                var a = result.rpm_cm_form[0].notes;
                $("#notes").val(a); 
            }else{
                $("#notes").val(""); 
            }
         

			for (var key in result) {
				form.dynamicFormPopulate(key, result[key]);
				

			}
		}
	).fail(function (result) {
		console.error("Population Error:", result);
	});

};



var onRPMCMNotesMainForm = function (formObj, fields, response) {   
    // console.log("response" + response.status); 
    // console.log("responsedata" + response);    
    

    
    if (response.status == 200 && $.trim(response.data)=='') {
     
        $("#rpm_cm_form")[0].reset();         
        getAlertHistoryList();
        $("#success").show();
        $('#rpm_cm_modal').modal('hide');
        var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Care Manager Notes Updated Successfully!</strong></div>';
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

   //alert("IN init");

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


    $('#alert-history-list tbody').on('click','.activealertpatientstatus',function(){
        //if($(this).is(":checked")){
            var table = $('#alert-history-list').DataTable();
            var rowdata = table.row($(this).parents('tr')).data();
            //alert("rowdata".rowdata);
            // console.log("inside activealertclick"); 
            // console.log(rowdata);  
            var pfname = rowdata.pfname;
            var plname = rowdata.plname;
            var unit   = rowdata.unit;
            var patientname = pfname+" "+plname;
            var rpmid = rowdata.tempid;
            var pid = rowdata.pid;
            var csseffdate = rowdata.csseffdate;
            var reading = rowdata.reading;
            var unit = rowdata.unit;
            var urlmm="/rpm/monthly-monitoring/"+pid;
            $('#gotomm').html('<a href="'+urlmm+'"><u>Go To Monthly Monitoring</u></a>');
            
            if(unit=='%'){
                var vital = 'Oxygen';
            }
            else if(unit=='mm[Hg]'){
               var vital = 'Blood Pressure';
            }
            else if(unit=='beats/minute'){
               var vital =  'Heartrate';
            } 
            else if(unit=='mg/dl'){
                // alert('glucose');
                var vital =  'Glucose';
            }
            else if(unit=='lbs'){
                var vital =  'Weight';
            }
            else if(unit=='degrees F'){
                var vital =  'Temperature';
            }
            else{
                var vital = 'Spirometer';
            }

           
            $("#rpm_cm_modal").modal('show');
            $("#patientname").text(patientname);
            $("#patientvital").text(vital);     
            $("#patient_id").val(pid); 
            $("#vital").val(vital); 
            $("#rpm_observation_id").val(rpmid);
            $("#notes").val();  
               


            if(vital=='Blood Pressure'){
                const r = reading.split("/");
                var newreading = r.join(" - ");
                // console.log(r);
                // console.log(newreading);
                var reading = newreading;

            }

            if(vital=='Spirometer'){
                const r = reading.split("/");
                var newreading = r.join(" - ");
                // console.log(r);
                // console.log(newreading);
                var reading = newreading;

            }

            console.log(vital);
            var data = "";
            var formpopulateurl = URL_POPULATE + "/" + pid + "/" + vital + "/" + csseffdate + "/" + reading;
            // var a = decodeURI(formpopulateurl);
            // console.log(formpopulateurl);
            // console.log(a);
            populateForm(data, formpopulateurl); 


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

window.alerthistory={  
    init: init
}