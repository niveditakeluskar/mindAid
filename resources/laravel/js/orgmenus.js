/**
* Test-Form Javascript
*/
const URL_POPULATE = "/org/ajax/menu_populate";
const URL_SAVE = "/org/ajax/menu_save"; //create_user_menu
const URL_UPDATE = "/org/ajax/updateMenu";

/**
 * Invoked when the form is submitted
 *
 * @return {Boolean}
 */
var onSubmit = function () {
    // $('#menuModel').modal('hide');
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
    $("form[name='menuForm']").attr("action", URL_SAVE);
    return true;
};

/**
 * Invoked after the form has been submitted
 */
var onResult = function (form, fields, response, error) {
    if (error)
        console.log(error);
    if (response.status == 200) {
        notify.success("Menu Saved Successfully");
        $('#menuModel').modal('hide');
    } else {
        notify.danger("Save Failed: Unknown Error");
    }
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
        { org_menu_id: id },
        function (data) {
            console.log(data);
            form.dynamicFormPopulate("menuForm", data);
            form.evaluateRules("menuForm");
        }
    ).fail(function (result) {
        console.error("Population Error:", result);
    });
};

var onAddMenu = function (formObj, fields, response) {
    if (response.status == 200) {
        renderMenuTable();
        $("#success").show();
        var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Menu Added Successfully!</strong></div>';
        $("#success").html(txt);
        $("form[name='createmenu']")[0].reset();
        $('#ajaxModel1').modal('hide');
        var scrollPos = $(".main-content").offset().top;
        $(window).scrollTop(scrollPos);
        setTimeout(function () { $("#success").hide(); }, 5000);
    }

};

var onUpdateMenu = function (formObj, fields, response) {
    //console.log("add user success"+response);
    if (response.status == 200) {
        renderMenuTable();
        $("#success").show();
        var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Menu Updated Successfully!</strong></div>';
        $("#success").html(txt);
        $("form[name='menuForm']")[0].reset();
        $('#ajaxModel').modal('hide');
        var scrollPos = $(".main-content").offset().top;
        $(window).scrollTop(scrollPos);
        setTimeout(function () { $("#success").hide(); }, 5000);

    }

};
$('#addMenu, .addmenus').click(function () {
    $('#saveBtn').val("create-product");
    $('#product_id').val('');
    $("form[name='createmenu']")[0].reset();
    $('#modelHeading1').html("Add Menu");
    $('#ajaxModel1').modal('show');
});


$('body').on('click', '.editMenu', function () {
    var id = $(this).data('id');
    $.get("ajax/editMenu" + '/' + id + '/edit', function (data) {
        $('#modelHeading').html("Edit Menu");
        $('#saveBtn').val("edit-user");
        $('#ajaxModel').modal('show');
        $('#menu_id').val(data.id); 
        $('#menu').val(data.menu);
        $('#menu_url').val(data.menu_url);
        $('#service_id').val(data.module_id);
        $('#components_id').val(data.component_id);
        $('#icon').val(data.icon);
        $('#parent').val(data.parent);
        $('#sequence').val(data.sequence);
        $('#operation').val(data.operation);
        $('#status').val(data.status);

    })
});


//  $('body').on('click', '.deleteMenu', function () {

//     var menu_id = $(this).data("id");
//     var checkstr = confirm("Are You sure want to delete!");
//     $.ajaxSetup({
//             headers: {
//                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//             }
//     }); 
//     if(checkstr == true){
//         $.ajax({
//             type: "POST",
//             url: "ajax/deleteMenu"+'/'+menu_id+'/delete',
//             data: {"id":menu_id},
//             success: function (response) {
//                 renderMenuTable();
//                 $("#success").show();
//                 var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Menu Deleted Successfully!</strong></div>';
//                 $("#success").html(txt);
//                 var scrollPos = $(".main-content").offset().top;
//                 $(window).scrollTop(scrollPos);
//                 setTimeout(function () {
//                     $("#success").hide();
//                 }, 5000);
//             }
//         });
//     }else{
//         return false;
//     }
// });


$('body').on('click', '.change_status_active', function () {
    var id = $(this).data('id');
    if (confirm("Are you sure you want to Deactivate this Menu")) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'post',
            url: '/org/menuStatus/' + id,
            // data: {"_token": "{{ csrf_token() }}","id": id},
            data: { "id": id },
            success: function (response) {
                renderMenuTable();
                $("#success").show();
                var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Menu Deactivated Successfully!</strong></div>';
                $("#success").html(txt);
                var scrollPos = $(".main-content").offset().top;
                $(window).scrollTop(scrollPos);
                //goToNextStep("call_step_1_id");
                setTimeout(function () {
                    $("#success").hide();
                }, 3000);
            }
        });
    } else { return false; }
});

$('body').on('click', '.change_status_deactive', function () {
    var id = $(this).data('id');

    if (confirm("Are you sure you want to Activate this Menu")) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            type: 'post',
            url: '/org/menuStatus/' + id,
            // data: {"_token": "{{ csrf_token() }}","id": id},
            data: { "id": id },
            success: function (response) {
                renderMenuTable();
                $("#msg").show();
                var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Menu Activated Successfully!</strong></div>';
                $("#msg").html(txt);
                var scrollPos = $(".main-content").offset().top;
                $(window).scrollTop(scrollPos);
                //goToNextStep("call_step_1_id");
                setTimeout(function () {
                    $("#msg").hide();
                }, 3000);
            }
        });
    } else { return false; }
});

/**
 * Initialize the form
 */
var init = function () {
    renderMenuTable();
    form.ajaxForm("createmenu", onAddMenu, function () {
        return true;
    });

    form.ajaxForm("menuForm", onUpdateMenu, function () {
        return true;
    });
    // $("#add-menu").click(function () {
    //        $("form[name='menuForm']").attr("action", URL_SAVE);
    //        $("form[name='menuForm']").submit();
    //    });

    // $("#edit-menu").click(function () {
    //        $("form[name='menuForm']").attr("action", URL_UPDATE);
    //        $("form[name='menuForm']").submit();
    //    });



};

/**
 * Export the module
 */
window.orgmenus = {
    init: init,
    onErrors: onErrors,
    onResult: onResult,
    onSubmit: onSubmit
};
