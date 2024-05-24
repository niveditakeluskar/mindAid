const URL_GROUP_THRESHOLS_POPULATE = '/org/ajax/group_threshold_populate/populate';
var populateForm = function (url) {
    //alert("url"+url);
    $.get(
        url,
        function (result) {
            // console.log(result);

            for (var key in result) {
                form.dynamicFormPopulate(key, result[key]);
                // var providerdata = result[key].static.providerdata;
                // console.log(providerdata);


            }
        }
    ).fail(function (result) {
        console.error("Population Error:", result);
    });

};

var onAddthreshold = function (formObj, fields, response) {
    if (response.status == 200) {
        $("#threshold-success").show();
        var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Group Threshold added successfully!</strong></div>';
        $("#threshold-success").html(txt);
        //var scrollPos = $(".main-content").offset().top;
        //$(window).scrollTop(scrollPos);
        //goToNextStep("call_step_1_id");
        setTimeout(function () {
            $("#threshold-success").hide();
        }, 3000);
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
    form.ajaxForm("group_threshold_form", onAddthreshold, function () {
        return true;
    });

    $(document).ready(function () {
        var url = URL_GROUP_THRESHOLS_POPULATE;
        //alert(url);
        populateForm(url);
    });
};

window.threshold = {
    init: init,
    onResult: onResult
};