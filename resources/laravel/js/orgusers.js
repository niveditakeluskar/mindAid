/**
* Test-Form Javascript
*/
const URL_POPULATE = "/org/ajax/user_populate";
const URL_SAVE = "/org/create-users"; //create_user_user
const URL_UPDATE = "/org/ajax/updateUser";
const URL_REPORT_TO = "/org/ajax/report-to";
const URL_LINKED_PRACTICES = "/org/ajax/linked-practices";
const URL_LINK_PRACTICES = "/org/ajax/link-practices";

/**
 * Invoked when the form is submitted
 *
 * @return {Boolean}
 */
/*var onSubmit = function () {
    // $('#user_modal').modal('hide');
   // renderUserTable();
    return true;
};
*/
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
    // $("form[name='user_form']").attr("action", URL_SAVE);
    return true;
};

/**
 * Invoked after the form has been submitted
 */
var onResult = function (form, fields, response, error) {
    if (error) {
        console.log(error);
    }
    else {
        console.log("check");
    }
    // if (response.status == 200) {
    //     renderUserTable();
    //     var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>User Saved Successfully!</strong></div>';
    //     $("#add-user-msg").html(txt);
    //     // alert("User Saved Successfully");
    //     notify.success("User Saved Successfully");
    //     $('#user_form').trigger("reset");
    //     $('#user_modal').modal('hide');

    // } else {
    //     notify.danger("Save Failed: Unknown Error");
    // }
};
/**
 * Populate the form of the given patient
 *
 * @param {Integer} patientId
 */

var populateForm = function (id) {
    // alert(id);
    if (!id)
        return;
    $.get(
        URL_POPULATE,
        { id: id },
        function (data) {
            // debugger;
            // console.log(data);
            $("#edit-f-name").val(data.static.f_name);
            $("#edit-l-name").val(data.static.l_name);
            $("#edit-email").val(data.static.email);
            $("#edit-emp-id").val(data.static.emp_id);
            $("#edit-exten").val(data.static.extension);
            $("#edit-office_id").val(data.static.office_id);
            $("#edit-number").val(data.static.number);
            $("#edit_country_code").val(data.static.country_code);

            $("#edit-emp-status").val(data.static.status);
            //   $("#edit-emp-category_id").val(data.static.category_id);
            $("#hidden-profile-img").val(data.static.profile_img);
            $("#edit-roles").val(data.static.role);
            $("#edit-reports").val(data.static.report_to);
            if(data.static.mfa_status==1){
                $("#mfa_status_sms").prop( "checked", true );
            }
            if(data.static.mfa_status==0){
                $("#mfa_status_config").prop( "checked", true );
            }


            var resultcount = 0;
            if (data.user_details != null) {
                var resultJSON = data.user_details;
                // var responsibility_details = resultJSON.responsibility_id;
                for (var result in resultJSON) {
                    console.log(result + "result" + "=====  " + resultJSON[result].responsibility_id);
                    inc_result = resultcount;
                    document.getElementById("edit_responsibility_" + resultJSON[result].responsibility_id).checked = true;
                    var x = $('.wrapMulDrop ul li input[type="checkbox"]:checked').length;
                    if (x != "") {
                        $('.multiDrop').html(x + " " + "selected");
                    } else if (x < 1) {
                        $('.multiDrop').html('Select responsibility<i style="float:right;" class="icon ion-android-arrow-dropdown"></i>');
                    }
                }

            }
            var image = data.static.profile_img;
            if (image != "" && image != null && image != undefined) {
                var img = "<img src='/images/usersRcare/" + image + "' width='100' height='100' id='user-profile-img'>";
            } else {
                var img = "<img src='" + window.location.origin + "/assets/images/faces/avatar.png'  width='100' height='100' id='user-profile-img'>";
            }
            $("#insertedImages").html(img);
            form.dynamicFormPopulate("user_form", data);
            form.evaluateRules("user_form");
        }
    ).fail(function (result) {
        console.error("Population Error:", result);
    });
};
/**
 * Set an user's name
 */
// var onUserDetailsUpdate = function (formObj, fields, response) {
//     if (response.status == 200) {
//         data[response.data.id] = response.data;
//         renderUserTable();
//         var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>User details updated successfully!</strong></div>';
//         $("#edit-user-msg").html(txt);
//         // alert("User details updated successfully!");
//         notify.success("User details updated successfully!");
//     } else {
//         console.error(response);
//         notify.danger("An error occurred while changing the user's details!");
//     }
// };
/**
 * Set an user's password
 */
var onUserUpdatePassword = function (formObj, fields, response) {
    if (response.status == 200) {
        data[response.data.id] = response.data;
        // alert("User password updated successfully!");
        renderUserTable();
        var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>User password updated successfully!</strong></div>';
        $("#edit-user-password-msg").html(txt);
        // notify.success("User password updated successfully!");
    } else {
        console.error(response);
        notify.danger("An error occurred while changing the user's password!");
    }
};
/**
 * Set an user's practice link
 */
var onUserLinkPractice = function (formObj, fields, response) {
    if (response.status == 200) {
        // console.log(response.data);
        data[response.data.id] = response.data;
        renderPracticeTable(response.data.user_id);
        renderUserTable();
        // alert("User practice linked successfully!");
        var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>User practice updated successfully!</strong></div>';
        $("#edit-user-practice-msg").html(txt);
        // notify.success("User practice linked successfully!");
    } else {
        console.error(response);
        notify.danger("An error occurred while linking the user's practice!");
    }
};

// var listData = function () {
//     var columns = [
//         { data: 'DT_RowIndex', name: 'DT_RowIndex' },
//         { data: 'f_name', name: 'f_name' },
//         { data: 'l_name', name: 'l_name' },
//         { data: 'email', name: 'email' },
//         { data: 'action', name: 'action', orderable: false, searchable: false },
//     ]
//     var table = util.renderDataTable('usersList', "{{ route('org_users_list') }}", columns, "{{ asset('') }}");

// };


var onAddUser = function (formObj, fields, response) {
    if (response.status == 200) {
        renderUserTable();
        $("#success").show();
        var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>User Added Successfully!</strong></div>';
        $("#success").html(txt);
        // $("#success").html('<strong>User Saved Successfully!</strong>');        
        $('#user_form').trigger("reset");
        $('#user_modal').modal('hide');
        var scrollPos = $(".main-content").offset().top;
        $(window).scrollTop(scrollPos);
        setTimeout(function () { $("#success").hide(); }, 5000);
    }

};



var onUpdateUser = function (formObj, fields, response) {
    if (response.status == 200) {
        renderUserTable();
        $("#success").show();
        var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>User Updated Successfully!</strong></div>';
        $("#success").html(txt);
        $('#user_details').trigger("reset");
        $('#edit_user_modal').modal('hide');
        var scrollPos = $(".main-content").offset().top;
        $(window).scrollTop(scrollPos);
        setTimeout(function () { $("#success").hide(); }, 5000);
    } else {
        if (response.data.errors.emp_id) {
            $("#edit-emp-id").addClass("is-invalid");
            $("#edit-emp-id").next(".invalid-feedback").html(response.data.errors.emp_id);
        } else {
            $("#edit-emp-id").removeClass("is-invalid");
            $("#edit-emp-id").next(".invalid-feedback").html('');
        }
        if (response.data.errors.f_name) {
            $("#edit-f-name").addClass("is-invalid");
            $("#edit-f-name").next(".invalid-feedback").html(response.data.errors.f_name);
        } else {
            $("#edit-f-name").removeClass("is-invalid");
            $("#edit-f-name").next(".invalid-feedback").html('');
        }
        if (response.data.errors.l_name) {
            $("#edit-l-name").addClass("is-invalid");
            $("#edit-l-name").next(".invalid-feedback").html(response.data.errors.l_name);
        } else {
            $("#edit-l-name").removeClass("is-invalid");
            $("#edit-l-name").next(".invalid-feedback").html('');
        }
        if (response.data.errors.extension) {
            $("#edit-exten").addClass("is-invalid");
            $("#edit-exten").next(".invalid-feedback").html(response.data.errors.extension);
        } else {
            $("#edit-exten").removeClass("is-invalid");
            $("#edit-exten").next(".invalid-feedback").html('');
        }
        if (response.data.errors.email) {
            $("#edit-email").addClass("is-invalid");
            $("#edit-email").next(".invalid-feedback").html(response.data.errors.email);
        } else {
            $("#edit-email").removeClass("is-invalid");
            $("#edit-email").next(".invalid-feedback").html('');
        }
        if (response.data.errors.role) {
            $("#edit-roles").addClass("is-invalid");
            $("#edit-roles").next(".invalid-feedback").html(response.data.errors.role);
        } else {
            $("#edit-roles").removeClass("is-invalid");
            $("#edit-roles").next(".invalid-feedback").html('');
        }
        if (response.data.errors.status) {
            $("#edit-emp-status").addClass("is-invalid");
            $("#edit-emp-status").next(".invalid-feedback").html(response.data.errors.status);
        } else {
            $("#edit-emp-status").removeClass("is-invalid");
            $("#edit-emp-status").next(".invalid-feedback").html('');
        }
        if (response.data.errors.report_to) {
            $("#edit-reports").addClass("is-invalid");
            $("#edit-reports").next(".invalid-feedback").html(response.data.errors.report_to);
        } else {
            $("#edit-reports").removeClass("is-invalid");
            $("#edit-reports").next(".invalid-feedback").html('');
        }
    }

};

/**
 * Initialize the form
 */
var init = function () {

    // form.ajaxForm("user_details", onUserDetailsUpdate);
    form.ajaxForm("change_password", onUserUpdatePassword);
    form.ajaxForm("edit_practice", onUserLinkPractice);

    form.ajaxForm("user_form", onAddUser, function () {
        //  form.ajaxForm("user_details", onUpdateUser, function(){});
        return true;
    });

    $('#edit-name-btn').on('click', function () {

        form.ajaxSubmit("user_details", onUpdateUser, function () { });
    });

    $("#roles").change(function () {
        // console.log($(this).val());
        var role_id = $(this).val();
        $.get(URL_REPORT_TO,
            { role_id: role_id },
            function (data) {
                var model = $('#reports');
                model.empty();
                model.append("<option>Select Report To</option>");
                $.each(data, function (index, element) {
                    model.append("<option value='" + element.id + "'>" + element.f_name + " " + element.l_name + "</option>");
                });
            }
        );
    });

    $("#edit-roles").change(function () {
        // console.log($(this).val());
        var role_id = $(this).val();
        $.get(URL_REPORT_TO,
            { role_id: role_id },
            function (data) {
                var model = $('#edit-reports');
                model.empty();
                model.append("<option>Select Report To</option>");
                $.each(data, function (index, element) {
                    model.append("<option value='" + element.id + "'>" + element.f_name + " " + element.l_name + "</option>");
                });
            }
        );
    });

    $('#addUser').click(function () {
        $("#user_form").attr("action", URL_SAVE);
        $(".is-invalid").removeClass("is-invalid");
        $('.invalid-feedback').html("");
        $("#button_div").html('<button type="submit" class="btn btn-primary m-1" id="add-user" ">Add User</button>');
        $('#user_modal_heading').html("Add User");
        $('#saveBtn').val("create-user");
        $('#user_form').trigger("reset");
        $('form[name="user_form"]')[0].reset();
        $('#user_modal').modal('show');
    });

    $('body').on('click', '.edit', function () {
        $(".is-invalid").removeClass("is-invalid");
        $('.invalid-feedback').html("");
        $('form[name="user_form"]')[0].reset();
        $('.multiDrop').html('Select responsibility<i style="float:right;" class="icon ion-android-arrow-dropdown"></i>');
        var id = $(this).data('id');
        $("[name='id']").val(id); // form cleared, so sets id again :)
        populateForm(id);
        renderPracticeTable(id);
        $('#edit_user_modal').modal('show');
    });

    $('body').on('click', '.change_userstatus_active', function () {
        var id = $(this).data('id');
        var title =$(this).attr('data-original-title');
        
        if (confirm("Are you sure you want to "+ title +" this User")) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: '/org/changeUserStatus/' + id,
                // data: {"_token": "{{ csrf_token() }}","id": id},
                data: { "id": id },
                success: function (response) {
                    renderUserTable();
                    $("#success").show();
                    var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>User '+ response +' Successfully!</strong></div>';
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
/*
    $('body').on('click', '.change_userstatus_deactive', function () {
        var id = $(this).data('id');
        if (confirm("Are you sure you want to Activate this User")) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: '/org/changeUserStatus/' + id,
                // data: {"_token": "{{ csrf_token() }}","id": id},
                data: { "id": id },
                success: function (response) {
                    renderUserTable();
                    $("#success").show();
                    var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>User  Activated Successfully!</strong></div>';
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
*/
     $('body').on('click', '.change_userstatus_block', function () {
        var id = $(this).data('id');
        var title =$(this).attr('data-original-title');
    
        if (confirm("Are you sure you want to "+ title +" this User")) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: 'post',
                url: '/org/changeBlockUnblockStatus/' + id,
                // data: {"_token": "{{ csrf_token() }}","id": id},
                data: { "id": id },
                success: function (response) {
               
                    renderUserTable();
                    $("#success").show();
                    var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>User'+ title +' Successfully!</strong></div>';
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
window.orgusers = {
    init: init,
    //onErrors: onErrors,
    //onResult: onResult,
    // onSubmit: onSubmit
};