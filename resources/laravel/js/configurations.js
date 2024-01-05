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

// var listData = function() {
//     var columns= [
//         {data: 'DT_RowIndex', name: 'DT_RowIndex'},
//         {data: 'role_name', name: 'role_name'},
//         {data: 'action', name: 'action', orderable: false, searchable: false},
//     ];
//     var table = util.renderDataTable('usersRolesList', "users_roles_list", columns);
// };

/**
 * Initialize the form
 */
var init = function () {

	$("#add-role").click(function () {
        $("form[name='role_form']").attr("action", URL_SAVE);
        $("form[name='role_form']").submit();
    });

	$("#edit-role").click(function () {
        $("form[name='role_form']").attr("action", URL_UPDATE);
        $("form[name='role_form']").submit();
    });

    $('#addrole, .addrole').click(function () {
        // var url = 'ajax/role_save';
        $("#role_form").attr("action",URL_SAVE);
        $(".is-invalid").removeClass("is-invalid");
        $('.invalid-feedback').html("");
        $("#button_div").html('<button type="submit" class="btn  btn-primary m-1" id="add-role">Add Role</button>');
        $('#role_modal_heading').html("Add Role");
        $('#saveBtn').val("create-role");
        $('#role_name').val('');
        $('#role_form').trigger("reset");
        $('#role_modal').modal('show');
    });
    
    $('body').on('click', '.edit', function () {
        // var url = 'update-user-role';
        $("#role_form").attr("action",URL_UPDATE);
        $(".is-invalid").removeClass("is-invalid");
        $('.invalid-feedback').html("");
        $("#button_div").html('<button type="submit" class="btn  btn-primary m-1" id="edit-role">Save Changes</button>');
        var id = $(this).data('id');
        $('form[name="role_form"]')[0].reset();
		$("[name='id']").val(id); // form cleared, so sets id again :)
        populateForm(id);
        $('#role_modal_heading').html("Edit Role");
        $('#saveBtn').val("create-role");
        $('#role_modal').modal('show');
    });
    $('body').on('click', '.change-status', function (){
        console.log('changed status');
        var confirm = confirm("Are you sure you want to Deactive this user?");
        if (confirm == true) {
            alert('yessss');
        } 
    });

    $(document).ready(function() { 
        if(window.location.href == 'http://rcareprototype.d-insights.global/admin/roles#role_modal'){
       var url = 'ajax/role_save';
        $("#role_form").attr("action",URL_SAVE);
        $(".is-invalid").removeClass("is-invalid");
        $('.invalid-feedback').html("");
        $("#button_div").html('<button type="submit" class="btn  btn-primary m-1" id="add-role">Add Role</button>');
        $('#role_modal_heading').html("Add Role");
        $('#saveBtn').val("create-role");
        $('#role_name').val('');
        $('#role_form').trigger("reset");
        $('#role_modal').modal('show');
       }
    }); 



};

/**
 * Export the module
 */
window.configurations = {
    init: init,
    onErrors: onErrors,
    onResult: onResult,
    onSubmit: onSubmit
};
