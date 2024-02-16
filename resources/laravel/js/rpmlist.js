/**
 * Invoked after the form has been submitted
 */
 


var init = function () {
	$("[name='practices']").on("change", function () {
        $(".patient-div").show();
		util.getRpmPatientList(parseInt($(this).val()), $("#patient_id"));
    });

    $("[name='patient_id']").on("change", function () {
        getPatientList($(this).val());
	});

	
};





window.rpmlist = {
	init: init
};