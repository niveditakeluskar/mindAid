/**
 * Invoked after the form has been submitted
 */
 
//   var onPatientOrderForm = function (formObj, fields, response) {
//    console.log(response.data+" test add respose");
//     if (response.status == 200) {
    
//         console.log(response.data+" test add respose");
//     }
//     else
//     {
//          console.log(response.data+" respose");
//     }
// };

var init = function () {
	$("[name='practices']").on("change", function () {
        $(".patient-div").show();
		util.updatePatientList(parseInt($(this).val()), $("#patient"));
    });

    $("[name='patient_id']").on("change", function () {
        getPatientList($(this).val());
	});

	



      // form.ajaxForm("patient_order_form", onPatientOrderForm, function () {       
      //                var devicename=$('#device_id option:selected').text();
      //                 $('#devicename').val(devicename);
      //                   return true;
      //               });
};





window.enrollPatient = {
	init: init
};