/**
 * 
 */
const URL_POPULATE = "/org/ajax/populateLabsForm";
var parameterscnt = 0;

var populateForm = function (data, url) {
    $.get( url, data, function (result) {
        //  console.log(result);
        $('#append_parameter').html("");
        for (var key in result) {
            form.dynamicFormPopulate(key, result[key]);
            var parameters = result[key].static['paramdata'];
            var paramCount = parameters.length;
            console.log(parameters + "param  : " + paramCount);
            $('form[name="main_edit_labs_form"] input[name="lab"]').val(result[key].static['description']);
            for (parameterscnt = 0; parameterscnt < paramCount; parameterscnt++) {
                console.log("data: " + parameters[parameterscnt]['parameter']);
                if (parameterscnt == 0) {
                    $('#parameter_0').val(parameters[parameterscnt]['parameter']);
                } else {
                    $('#append_parameter').append('<div class=" row btn_remove" id="btn_removeparam_' + parameterscnt + '"><input type="text" class="form-control col-md-11" name ="parameters[]" id ="parameter_' + parameterscnt + '" value="' + parameters[parameterscnt]['parameter'] + '" placeholder ="Enter Parameter"><div class="invalid-feedback"></div><i class="col-md-1 remove-icons i-Remove float-right mb-3" id="remove_parameter_' + parameterscnt + '" title="Remove Parameter"></i><div class="error_msg" style="display:none;color:red">Please Enter Parameter</div></div>');
                }
                // parameterscnt++;
            }
            // console.log(symptoms+"******count: "+symptomsCount);
            // updateBmi()
        }
    }
    ).fail(function (result) {
        console.error("Population Error:", result);
    });
};

var onEditLabsMainForm = function (formObj, fields, response) {
    if (response.status == 200) {
        $("#main_edit_labs_form")[0].reset();
        getlablisting();
        $("#success").show();
        // $("form[name='main_labs_form'] .alert").show();
        $('#edit_lab_modal').modal('hide');
        var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Lab Updated Successfully!</strong></div>';
        $("#success").html(txt);
        var scrollPos = $(".main-content").offset().top;
        $(window).scrollTop(scrollPos);
        //goToNextStep("call_step_1_id");
        setTimeout(function () {
            $("#success").hide();
        }, 3000);
    }
};

var onLabsMainForm = function (formObj, fields, response) {
    console.log("response" + response.data);
    if (response.status == 200) {
        $("#main_labs_form")[0].reset();
        getlablisting();
        $("#success").show();
        //$("form[name='main_labs_form'] .alert").show();
        $('#add_lab_modal').modal('hide');
        var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Lab Added Successfully!</strong></div>';
        $("#success").html(txt);
        var scrollPos = $(".main-content").offset().top;
        $(window).scrollTop(scrollPos);
        //goToNextStep("call_step_1_id");
        setTimeout(function () {
            $("#success").hide();
        }, 3000);
    }
};

var onResult = function (form, fields, response, error) {
    if (error) {
    } else {
    //window.location.href = response.data.redirect;
    }
};


var init = function () {
    form.ajaxForm("main_edit_labs_form", onEditLabsMainForm, function () {
        return true;
    });

    form.ajaxForm("main_labs_form", onLabsMainForm, function () {
        return true;
    });

    $('body').on('click', '#labs-list .editLab', function () {
        $("#main_edit_labs_form")[0].reset();
        addparameterscnt = 0;
        $('#additionalparameteradd').html("");
        $('#additionalparameter').html("");
        $('#parameter').val("");
        $('#edit_lab_modal').modal('show');
        var sPageURL = window.location.pathname;
        var parts = sPageURL.split("/");
        // var id = parts[parts.length - 1];
        // var patientId = $(this).data(id);
        var id = $(this).data('id');
        var data = "";
        var formpopulateurl = URL_POPULATE + "/" + id;
        populateForm(data, formpopulateurl);
    });

    // $('body').on('click', '.medstatus', function () {
    //  //  alert("test"+);
    //      var id = $(this).data('id');    
    //      var url = "labStatus/" + id;     
    //      // alert("test"+id);        

    //        $.ajax({
    //             type:'get',
    //             url:url, 
    //             id:"id="+id,             
    //             success:function(data) {                
    //              getlablisting();
    //             }
    //          });
    //  });

    $('#description').change(function () {
        var desc = $(this).val();
        //  alert(desc);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            data: { 'description': desc },
            type: 'POST',
            url: '/org/existlab',
            success: function (data) {
            //alert("test");
                console.log(data);
                if ($.trim(data) == "yes") {
                    // alert("This lab name already exist !");
                    $('#description').addClass("is-invalid");
                    $('#description').next(".invalid-feedback").html("This lab name already exist.");
                    // Submit this form without doing the ajax call again
                } else {
                    $('#description').removeClass("is-invalid");
                }
            }
        });
    });

    $('body').on('click', '.change_lab_status_active', function () {
        var id = $(this).data('id');
        if (confirm("Are you sure you want to deactivate this Lab")) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: '/org/labStatus/' + id,
                // data: {"_token": "{{ csrf_token() }}","id": id},
                data: { "id": id },
                success: function (response) {
                    getlablisting();
                    $("#success").show();
                    var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Lab Deactivated Successfully!</strong></div>';
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

    $('body').on('click', '.change_lab_status_deactive', function () {
        var id = $(this).data('id');
        if (confirm("Are you sure you want to activate this Lab")) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
                }
            });
            $.ajax({
                type: 'post',
                url: '/org/labStatus/' + id,
                // data: {"_token": "{{ csrf_token() }}","id": id},
                data: { "id": id },
                success: function (response) {
                    getlablisting();
                    $("#success").show();
                    var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Lab  Activated Successfully!</strong></div>';
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

    // edit parameter
    $(document).on("click", ".remove-icons", function () {
        var button_id = $(this).closest('div').attr('id');
        console.log("removebuttonid: " + button_id);
        $('#' + button_id).remove();
    });

    $('#additionalparameter').click(function () {
        // debugger;
        parameterscnt++;
        $('#append_parameter').append('<div class="btn_remove row" id="btn_removeparam_' + parameterscnt + '"><input type="text" class="col-md-11 form-control" name ="parameters[]" id ="parameter_' + parameterscnt + '" placeholder ="Enter Parameter"><div class="invalid-feedback"></div><i class="col-md-1 remove-icons i-Remove float-right mb-3" id="remove_parameter_' + parameterscnt + '" title="Remove Goals"></i><div class="error_msg" style="display:none;color:red">Please Enter parameter</div></div>');
    });

    // add parameter
    // $(document).on("click", ".remove-icons", function () {
    //     var button_id = $(this).closest('div').attr('id');
    //     console.log("removebuttonid: " + button_id);
    //     $('#' + button_id).remove();
    // });

    var addparameterscnt = 0;
    $('#additionalparameteradd').click(function () {
        addparameterscnt++;
        $('#append_parameteradd').append('<div class="btn_remove row" id="btn_removeparam1_' + addparameterscnt + '"><input type="text" class="col-md-11 form-control" name ="addparameters[]" id ="addparameter_' + addparameterscnt + '" placeholder ="Enter Parameter"><div class="invalid-feedback"></div><i class="col-md-1 remove-icons i-Remove float-right mb-3" id="remove_parameter1_' + addparameterscnt + '" title="Remove Goals"></i><div class="error_msg" style="display:none;color:red">Please Enter parameter</div></div>');
    });
};

window.labs = {
    init: init
    //onResult: onResult
};