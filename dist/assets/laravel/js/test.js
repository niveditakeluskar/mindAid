/**
 * Invoked after the form has been submitted
 */
 
var onResult = function (form, fields, response, error) {	
	// var myJSON = JSON.stringify(response);	
	window.location.href = response.data.redirect;
};

var init = function () {
	console.log('i am hit');
	form.ajaxForm("test_form", onResult, undefined, function () {
		notify.danger("Invalid information provided");
		return true;
	});
};

window.test = {
	init: init,
    onResult: onResult
};