 /**
 * Test-Form Javascript
 */
const URL_POPULATE = "/admin/ajax/role_populate";
const URL_SAVE     = "/admin/ajax/role_save"; //create_user_role
const URL_UPDATE   = "update-user-role";

/**
 * Invoked when the form is submitted
 *
 * @return {Boolean}
 */
var onSubmit = function () {
    // $('#role_modal').modal('hide');
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
            } catch (e) {
                console.error("Field Error:", field, fields.fields);
            }
        }
    }
    $("form[name='create_stage']").attr("action", URL_SAVE);
    return true;
};

/**
 * Invoked after the form has been submitted
 */
var onResult = function (form, fields, response, error) {
    if (error)
        console.log(error);
    if (response.status == 200) {
        notify.success("Role Saved Successfully");
        $('#role_modal').modal('hide');
    } else {
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
        {role_id: id},
        function(data) {
            console.log(data);
            form.dynamicFormPopulate("create_stage", data);
            form.evaluateRules("create_stage");
        }
    ).fail(function(result) {
        console.error("Population Error:", result);
    });
};

var onAddstageCode = function (formObj, fields, response) {
    if (response.status == 200) {
        renderStageCodeTable();
        $("#success").show();
        var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">� </button><strong>Step Added Successfully!</strong></div>';
        $("#success").html(txt);
        // $("#success").html('<strong>User Saved Successfully!</strong>');        
        $('#create_stage_code').trigger("reset");
        $('#addStageCodeModel').modal('hide');
        var scrollPos = $(".main-content").offset().top;
        $(window).scrollTop(scrollPos);
        setTimeout(function () { $("#success").hide(); }, 5000);
    }

};

var onUpdatestageCode = function (formObj, fields, response) {
    //console.log("add user success"+response);
    if (response.status == 200) {
        renderStageCodeTable();
        $("#success").show();
        var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">� </button><strong>Step Updated Successfully!</strong></div>';
        $("#success").html(txt);
        $('#editstagecodeForm').trigger("reset");
        $('#editStageCodeModel').modal('hide');
        var scrollPos = $(".main-content").offset().top;
        $(window).scrollTop(scrollPos);
        setTimeout(function () { $("#success").hide(); }, 5000);

    }

};
    $('#addStageCodeBtn, .addmenus').click(function () {
            $('#saveBtn').val("create-product");
            $('#product_id').val('');
            $('#productForm').trigger("reset");
            $('#addStageCodeHeading').html("Add Step");
            $('#addStageCodeModel').modal('show');
    });

    $('body').on('click', '.editStageCode', function () {
        var id = $(this).data('id');
        $.get("ajax/editStageCode" +'/' + id +'/edit', function (data) {
            $('#modelHeading').html("Edit Step");
            $('#saveBtn').val("edit-stage");
            $('#editStageCodeModel').modal('show');
            // console.log(data);
            $('#stage_code_id').val(data[0].id);
            $('#edit_module').val(data[0].stage.module_id);
            $('#edit-stage-code').val(data[0].description);
            $('#edit-sequence').val(data[0].sequence);
            util.updateSubModuleList(parseInt(data[0].stage.module_id), $("#edit_sub_module"), parseInt(data[0].stage.submodule_id));
            util.updateStageList(parseInt(data[0].stage.submodule_id), $("#edit_stages"), parseInt(data[0].stage_id));
        })
    });
    
   $('body').on('click', '.deleteStageCode', function () {
        var stage_id = $(this).data("id");
        var checkstr = confirm("Are You sure want to chnage status !");
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        if(checkstr == true){
            $.ajax({
                type: "POST",
                url: "ajax/deleteStageCode"+'/'+stage_id+'/delete',
                data: { "id": stage_id },
                success: function (data) {
                    renderStageCodeTable();
                    $("#msg").show();
                        var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">x</button><strong>'+data.success+'</strong></div>';
                    $("#msg").html(txt);
                    var scrollPos = $(".main-content").offset().top;
                    $(window).scrollTop(scrollPos);
                    setTimeout(function () { $("#msg").hide(); }, 5000);

                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        } else {
            return false;
        }
    });


    $(".module").on("change", function () {
        util.updateSubModuleList(parseInt($(this).val()), $(".sub_module"));
    });

    $(".sub_module").on("change", function () {
        util.updateStageList(parseInt($(this).val()), $(".stage"));
    });

    

/**
 * Initialize the form
 */
var init = function (){
    form.ajaxForm("create_stage_code", onAddstageCode, function () {
        //  form.ajaxForm("user_details", onUpdateUser, function(){});
        return true;
    });
    form.ajaxForm("editstagecodeForm", onUpdatestageCode, function () {
        //  form.ajaxForm("user_details", onUpdateUser, function(){});
        return true;
    });

};

/**
 * Export the module
 */
window.stageCode = {
    init: init,
    onErrors: onErrors,
    onResult: onResult,
    onSubmit: onSubmit
};
