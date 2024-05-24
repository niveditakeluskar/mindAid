 /**
 * Test-Form Javascript
 */
const URL_POPULATE = "/ajax/test_populate";
const URL_SAVE     = "/ajax/test_save";

/**
 * Invoked when the form is submitted
 *
 * @return {Boolean}
 */
var onSubmit = function () {
    $(".tab-error").removeClass("tab-error");
    return true;
};

/**
 * Invoked when errors in the form are detected
 */
var onErrors = function (form, fields, response) {
    if (response.data.errors) {
        for (var field in response.data.errors) {
            try {
                let id = fields.fields[field].parents("[role='tabpanel']").attr("id");
                $(`#${id}-tab`).addClass("tab-error");
            } catch (e) {
                console.error("Field Error:", field, fields.fields);
            }
        }
    }
    $("form[name='test_form']").attr("action", URL_SAVE);
    return true;
};

/**
 * Invoked after the form has been submitted
 */
var onResult = function (form, fields, response, error) {
    if (error)
        console.log(error);
    if (response.status == 200) {
        // $("form[name='test_form']").attr("action", URL_SAVE);
        notify.success("Test Saved Successfully");
        $("#form-id").val(response.data.form);
        setTimeout(function () {
            if (confirm("Would you like to print now?")) {
                onPrint(null, true, response.data.form);
            }
        }, 1);
    } else {
        // $("form[name='test_form']").attr("action", URL_SAVE);
        notify.danger("Save Failed: Unknown Error");
    }
};
/**
 * Populate the form of the given patient
 *
 * @param {Integer} patientId
 */
var populateForm = function(id) {
    // alert(id);
    if (!id)
        return;
    $.get(
        URL_POPULATE,
        {test_id: id},
        function(data) {
            console.log(data);
            form.dynamicFormPopulate("test_form", data);
            form.evaluateRules("test_form");
        }
    ).fail(function(result) {
        console.error("Population Error:", result);
    });
};

/**
 * Initialize the form
 */
var init = function () {
	$("[name='user']").change(function() {
		var id = $(this).val(); //current user id
		$('form[name="test_form"]')[0].reset(); //clear previous record
		$("#form-id").val('');
		$("[name='user']").val(id); // form cleared, so sets id again :)
        populateForm(id); //populate form according to new user id
    });
	$("#test-submit").click(function () {
        // $("form[name='test_form']").attr("action", URL_SAVE);
        $("form[name='test_form']").submit();
    });
};

/**
 * Export the module
 */
window.test = {
    init: init,
    onErrors: onErrors,
    onResult: onResult,
    onSubmit: onSubmit
};
