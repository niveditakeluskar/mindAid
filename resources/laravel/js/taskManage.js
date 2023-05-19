var onUserPatients = function (formObj, fields, response) {
    if (response.status == 200) {
        $("form[name='user_patients_form'] .alert").show();
		$("#practices").val("").trigger('change');
		$("#caremanager").val("").trigger('change');
        var dataSet = [];
        if ($.fn.dataTable.isDataTable('#patient-list')) {
            $('#patient-list').DataTable({
                "destroy": true,
                "processing": true,
                "data": dataSet
            });
        } else {
            $('#patient-list').DataTable({
                "processing": true,
                "data": dataSet
            });
        }
    }
};




var onResult = function (form, fields, response, error) {

    if (error) {
    }
    else {
        window.location.href = response.data.redirect;
    }
};



var init = function () {

    form.ajaxForm("user_patients_form", onUserPatients, function () {

        return true;
    });

};

window.taskManage = {
    init: init,
    onResult: onResult
}; 