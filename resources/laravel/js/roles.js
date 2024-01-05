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
    $("form[name='role_form']").attr("action", URL_SAVE);
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
            form.dynamicFormPopulate("role_form", data);
            form.evaluateRules("role_form");
        }
    ).fail(function(result) {
        console.error("Population Error:", result);
    });
};

var onAddRole = function (formObj, fields, response) {
    if (response.status == 200) {
        renderRolesTable();
        $("#success").show();
        var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Role Added Successfully!</strong></div>';
        $("#success").html(txt);
        $('#add_role_modal').modal('hide');     
        $('#addUsersroleForm').trigger("reset");
        var scrollPos = $(".main-content").offset().top;
        $(window).scrollTop(scrollPos); 
        setTimeout(function () { $("#success").hide(); }, 5000);
    }

};

var onUpdateRole = function (formObj, fields, response) {
    //console.log("add user success"+response);
    if (response.status == 200) {
        renderRolesTable();
        $("#success").show();
        var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Role Updated Successfully!</strong></div>';
        $("#success").html(txt);
        $('#edit_role_modal').modal('hide');
        $('#editroleForm').trigger("reset");
        var scrollPos = $(".main-content").offset().top;
        $(window).scrollTop(scrollPos);
        setTimeout(function () { $("#success").hide(); }, 5000);

    }

};

/**
 * Initialize the form
 */
var init = function (){
    form.ajaxForm("addUsersroleForm", onAddRole, function () {
        //  form.ajaxForm("user_details", onUpdateUser, function(){});
        return true;
    });
    form.ajaxForm("editroleForm", onUpdateRole, function () {
        //  form.ajaxForm("user_details", onUpdateUser, function(){});
        return true;
    });
    // $(document).ready(function() { 
    //     if(window.location.href == 'http://rcareprototype.d-insights.global/admin/roles#role_modal'){
    //    var url = 'ajax/role_save';
    //     $("#role_form").attr("action",URL_SAVE);
    //     $(".is-invalid").removeClass("is-invalid");
    //     $('.invalid-feedback').html("");
    //     $("#button_div").html('<button type="submit" class="btn  btn-primary m-1" id="add-role">Add Role</button>');
    //     $('#role_modal_heading').html("Add Role");
    //     $('#saveBtn').val("create-role");
    //     $('#role_name').val('');
    //     $('#role_form').trigger("reset");
    //     $('#role_modal').modal('show');
    //    }
    // }); 

    $('#addRole').click(function () {
        //$('#modelHeading1').html("Add Role");
        $('#saveBtn').val("create-product");
        $('#product_id').val('');
        $('#productForm').trigger("reset");
        // $('#modelHeading').html("Add Role");
        $('#add_role_modal').modal('show');
    });

    $('body').on('click', '.editroles', function () {
        var id = $(this).data('id');
        $.get("ajax/edituserRoles" +'/' +id+'/edit', function (data) {
          //$('#modelHeading').html("Edit Roles");
          $('#saveBtn').val("edit-user");
          $('#edit_role_modal').modal('show');
          $('#id').val(data.id);
          $('#role_name').val(data.role_name);
          $('#level').val(data.level);

        })
    });
    
    $('body').on('click', '.change_role_active', function () {
        var id = $(this).data('id');
        if (confirm("Are you sure you want to Deactivate this Role")) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: '/org/changeRoleStatus/' + id,
                data: { "id": id },
                success: function (response) {
                    renderRolesTable();
                    $("#success").show();
                    var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Role Deactivated Successfully!</strong></div>';
                    $("#success").html(txt);
                    var scrollPos = $(".main-content").offset().top;
                    $(window).scrollTop(scrollPos);
                    setTimeout(function () {
                        $("#success").hide();
                    }, 5000);
                }
            });
        } else { return false; }
    });

    $('body').on('click', '.change_role_deactive', function () {
        var id = $(this).data('id');
        if (confirm("Are you sure you want to Activate this Role")) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: '/org/changeRoleStatus/' + id,
                // data: {"_token": "{{ csrf_token() }}","id": id},
                data: { "id": id },
                success: function (response) {
                    renderRolesTable();
                    $("#success").show();
                    var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Role  Activated Successfully!</strong></div>';
                    $("#success").html(txt);
                    var scrollPos = $(".main-content").offset().top;
                    $(window).scrollTop(scrollPos);
                    setTimeout(function () {
                        $("#success").hide();
                    }, 5000);
                }
            });
        } else { return false; }
    });
};

/**
 * Export the module
 */
window.roles = {
    init: init,
    onErrors: onErrors,
    onResult: onResult,
    onSubmit: onSubmit
};
