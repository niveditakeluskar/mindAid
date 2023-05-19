/**
 * Administration Page Controller
 * Created by David Ludwig
 *
 * This module controls all functionality on the administration tab of the site,
 * including modals, and Ajax requests.
 */

// General Definitions ---------------------------------------------------------

// URLs
const URL_DATA_FETCH = "/ajax/data_admin_fetch";

// Store the employees and practices in this structure
var adminData = {
    "employees": {},
    "practices": {},
    "titles":    {}
};

// Store the filters
var filters = {
    "employees": {
        "checkboxes": {
            "active":   true,
            "inactive": true
        },
        "select": {
            "practice": "",
            "title":    ""
        }
    },
    "practices": {
        "checkboxes": {
            "active":   true,
            "inactive": true
        }
    }
};

// Frontend Manipulation -------------------------------------------------------

/**
 * Clear the employese table
 */
var clearEmployees = function () {
    $("#table-employees tbody").html("");
};

/**
 * Clear the practices table
 */
var clearPractices = function () {
    $("#table-practices tbody").html("");
};

/**
 * Display the employees in the table
 */
var displayEmployees = function() {
    var columns = ["renova_id", "esig", "is_manager", "is_admin", "is_active"];
    var labels  = ["ID", "eSignature", "Manager", "Admin", "Active"]
    var practice = adminData.practices[filters.employees.select.practice];
    if (practice) {
        columns.unshift("practice");
        labels.unshift("Practice");
        Object.values(adminData.employees).forEach((employee) => {
            employee.practice = practice.name;
        });
    }
    table("employees").setColumns(columns, labels).setData(adminData.employees);
};

/**
 * Display the selected employee's practices
 *
 * @param {Integer} employeeId
 */
var displayEmployeesPractices = function (employeeId) {
    var employee = adminData.employees[employeeId];
    var rows = [];
    for (let i = 0; i < employee.practices.length; i++) {
        let id = employee.practices[i];
        let practice = adminData.practices[id];
        let row = $(`<tr data-practice="${id}" onclick="$(this).prev().click();">`);
        $("<th scope='row'>").text(practice.number).appendTo(row);
        $("<td>").text(practice.name).appendTo(row);
        $("<td>").text(locale.activeInactive(practice.is_active)).appendTo(row);
        rows.push($(`<input type="checkbox" class="check-table-select" name="unlink[]" value="${id}">`)); // This allows the row to be selected
        rows.push(row);
    }
    $("#table-edit-employee-practices tbody").html(rows);
}

/**
 * Display the practices in the table
 *
 * @param {String} search
 */
var displayPractices = function() {
    table("practices").setData(adminData.practices);
};

var displayEmployeeTitles = function(id) {
    let elem = $(`#${id}`);
    form.selectOtherReset(elem);
    form.selectOtherSetOptions(elem, adminData.titles);
};

/**
 * Populate the dropdown box in the employee-edit modal with eligable practices
 */
var displayEmployeeEligablePractices = function(employeeId) {
    var practices = adminData.practices;
    var linkedPractices = adminData.employees[employeeId].practices;
    var select = $("#practice-link-select");
    select.html("");
    for (let id in practices) {
        if (!linkedPractices.includes(parseInt(id))) {
            $("<option>").text(`${practices[id].number}: ${practices[id].name}`)
                .val(id)
                .appendTo(select);
        }
    }
    select.selectpicker("refresh");
};

var displayManagers = function(selectFieldId, excludeId, selected = null) {
    var employees = adminData.employees;
    var select = $(`#${selectFieldId}`);
    select.html("");
    $("<option>").text("None")
        .val("")
        .appendTo(select);
    for (let id in employees) {
        if ((employees[id].is_manager || employees[id].is_admin) && id != excludeId) {
            $("<option>").text(employees[id].esig)
                .val(id)
                .appendTo(select);
        }
    }
    select.selectpicker("refresh");
    select.selectpicker("val", selected);
};

// Ajax GET Requests -----------------------------------------------------------

var applyDataFilters = function() {
    filters.employees.checkboxes.active   = $("#employee-filter-active").prop("checked");
    filters.employees.checkboxes.inactive = $("#employee-filter-inactive").prop("checked");
    filters.employees.select.title        = $("#filter-title-select").val();
    filters.employees.select.practice     = $("#filter-practice-select").val();
    filters.practices.checkboxes.active   = $("#practice-filter-active").prop("checked");
    filters.practices.checkboxes.inactive = $("#practice-filter-inactive").prop("checked");
};

/**
 * Get the employees and practices
 *
 * @param {Function} callback
 */
var fetchEmployeesAndPractices = function(filters, callback) {
    $("#employee-filter-button, #employee-add-button").prop("disabled", true);
    $("#practice-filter-button, #practice-add-button").prop("disabled", true);
    $.get(URL_DATA_FETCH, filters, function(data) {
        adminData = data;
        $("#employee-filter-button, #employee-add-button").prop("disabled", false);
        $("#practice-filter-button, #practice-add-button").prop("disabled", false);
        console.log(adminData);
        if (callback)
            callback();
    }).fail(function(error) {
        console.error("Data fetch error:", error);
    });
};

// Ajax POST Responses ---------------------------------------------------------

/**
 * Add an employee via Ajax request
 *
 * @param {String} formName The "name" attribute of the form to submit
 */
var onEmployeeAdd = function(formObj, fields, response) {
    if (response.status == 200) {
        adminData.employees[response.data.id] = response.data;
        if (adminData.titles.indexOf(response.data.title) == -1) {
            adminData.titles.push(response.data.title);
            adminData.titles.sort();
        }
        displayEmployees();
        displayManagers("manager-add");
        displayEmployeeTitles("employee-add-title");
        form.selectOtherReset($("#employee-add-title"));
        notify.success("Employee added successfully!");
        $("#employee-add-fname").val("");
        $("#employee-add-mname").val("");
        $("#employee-add-lname").val("");
        $("#employee-add-renova-id").val("");
        $("#employee-add-email").val("");
        $("#employee-add-password").val("");
        $("#employee-add-repassword").val("");
        $("#employee-add-is-admin, #employee-add-is-manager").prop("checked", false);
    } else {
        console.error(response);
        notify.danger("Unable to add employee!");
    }
};

/**
 * Set an employee's password via Ajax request
 *
 * @param {String} formName The "name" attribute of the form to submit
 */
var onEmployeeSetPassword = function(form, fields, response) {
    if (response.status == 200) {
        notify.success("Employee password changed successfully!");
    } else {
        console.error(response);
        notify.danger("Unable to set employee's password!");
    }
    $("#employee-edit-password").val("");
    $("#employee-edit-repassword").val("");
};

/**
 * Set an employee's status via Ajax request
 *
 * @param {String} formName The "name" attribute of the form to submit
 */
var onEmployeeSetStatus = function (form, fields, response) {
    if (response.status == 200) {
        adminData.employees[response.data.id] = response.data;
        displayEmployees();
        notify.success("Employee status updated successfully!");
    } else if (response.status == 401) {
        notify.error("You cannot change your own status!");
    } else {
        console.error(response);
        notify.error("Unable to set employee status!");
    }
};

/**
 * Set an employee's status via Ajax request
 */
var onEmployeeSetTitle = function (formObj, fields, response) {
    if (response.status == 200) {
        adminData.employees[response.data.id] = response.data;
        displayEmployees();
        if (adminData.titles.indexOf(response.data.title) == -1) {
            adminData.titles.push(response.data.title);
            adminData.titles.sort();
        }
        displayEmployeeTitles();
        form.selectOtherSetValue($("#employee-edit-title"), response.data.title);
        notify.success("Employee title updated successfully!");
    } else {
        console.error(response);
        notify.danger("An error occurred while changing the employee's title!");
    }
};

/**
 * Set an employee's name
 */
var onEmployeeSetName = function (formObj, fields, response) {
    if (response.status == 200) {
        adminData.employees[response.data.id] = response.data;
        displayEmployees();
        notify.success("Employee name updated successfully!");
    } else {
        console.error(response);
        notify.danger("An error occurred while changing the employee's name!");
    }
};

/**
 * Set an employee's authority level
 */
var onEmployeeSetAuthority = function (formObj, fields, response) {
    if (response.status == 200) {
        adminData.employees[response.data.id] = response.data;
        displayEmployees();
        notify.success("Employee authority updated successfully!");
    } else {
        console.error(response);
        notify.danger("An error occurred while changing the employee's authority!");
    }
};

/**
 * Link a practice to the currently selected employee
 */
var onEmployeePracticeLink = function(form, fields, response) {
    if (response.status == 200) {
        adminData.employees[response.data.id] = response.data;
        displayEmployeesPractices(response.data.id);
        displayEmployeeEligablePractices(response.data.id);
        notify.success("Practice was linked to the employee successfully!");
    } else {
        console.error(response);
        notify.danger("An error occurred while linking the practice to the employee!");
    }

};

/**
 * Unlink the selected practices from the currently selected employee
 */
var onEmployeePracticeUnlink = function(form, fields, response, error) {
    if (response.status == 200) {
        adminData.employees[response.data.id] = response.data;
        displayEmployeesPractices(response.data.id);
        displayEmployeeEligablePractices(response.data.id);
        notify.success("Selected practices were unlinked from the employee successfully!");
    } else {
        console.error(response);
        notify.danger("An error occurred while unlinking practices from the employee!");
    }
};

/**
 * Update the employee when the manager has been changed
 */
var onEmployeeAssignManager = function(form, fields, response, error) {
    if (response.status == 200) {
        adminData.employees[response.data.id] = response.data;
        notify.success("The manager was assigned successfully!");
    } else {
        console.error(response);
        notify.danger("An error occurred while assigning a manager to an employee!")
    }
};

/**
 * Add a practice via Ajax request
 */
var onPracticeAdd = function(formObj, fields, response) {
    if (response.status == 200) {
        adminData.practices[response.data.id] = response.data;
        displayPractices();
        notify.success("Practice added successfully!");
        $("#practice-add-name").val("");
        $("#practice-add-number").val("");
        form.dynamicFormClear("physicians");
    }
};

/**
 * Set an practice's status via Ajax request
 */
var onPracticeSetStatus = function(form, fields, response) {
    if (response.status == 200) {
        adminData.practices[response.data.id] = response.data;
        displayPractices();
        notify.success("Practice status updated successfully!");
    } else {
        console.error(response);
        notify.danger("An error occurred while updating the practice status!");
    }
};

/**
 * Set an practice's status via Ajax request
 */
var onPracticeSetPhysicians = function(formObj, fields, response) {
    if (response.status == 200) {
        adminData.practices[response.data.id] = response.data;
        notify.success("Practice physicians successfully updated!");
    } else {
        console.error(response);
        notify.danger("An error occurred while trying to update the physicians for the practice!");
    }
};

// Event Handlers --------------------------------------------------------------

/**
 * Populate the employee-edit modal when an edit request has been made
 *
 * @param {Event Object}
 */
var onOpenEmployeeEdit = function (row) {
    var modal = $("#modal-employee-edit");
    var employee = adminData.employees[row.id];
    modal.find(".modal-title").text(`Edit Employee: ${employee.esig}`);
    modal.find("[data-value='employee_id']").val(employee.id);
    modal.find(".is-invalid").removeClass("is-invalid");
    modal.find("#print-employee").attr("href", `/administration/printing/employee/${employee.id}`);
    $("#employee-edit-password, #employee-edit-repassword").val("");
    $("#employee-edit-is-manager").prop("checked", employee.is_manager);
    $("#employee-edit-is-admin").prop("checked", employee.is_admin);
    $("#employee-edit-fname").val(employee.fname);
    $("#employee-edit-mname").val(employee.mname);
    $("#employee-edit-lname").val(employee.lname);
    displayEmployeeEligablePractices(employee.id);
    displayEmployeesPractices(employee.id);
    displayManagers("manager-assign-select", employee.id, employee.manager_id);
    displayEmployeeTitles("employee-edit-title");
    form.selectOtherSetValue($("#employee-edit-title"), employee.title);
    modal.modal("show");
};

/**
 * Populate the employee-filter modal when a filter edit request has been made
 *
 * @param {Event Object}
 */
var onOpenEmployeeFilter = function (event) {
    var modal = $(this);

    // Checkboxes
    var checkboxes = filters.employees.checkboxes;
    Object.keys(checkboxes).forEach(function (property) {
        let elem = modal.find(`[name="${property}"]`).prop("checked", checkboxes[property]);
    });

    var practiceSelect = $("#filter-practice-select");
    practiceSelect.html("<option value=''>None</option>");
    for (let id in adminData.practices) {
        let practice = adminData.practices[id].name;
        let option = $("<option>");
        option.val(id).html(practice);
        practiceSelect.append(option);
    }
    practiceSelect.selectpicker("refresh");
    practiceSelect.selectpicker("val", filters.employees.select.practice);

    var titleSelect = $("#filter-title-select");
    titleSelect.html("<option value=''>None</option>");
    adminData.titles.sort();
    adminData.titles.forEach(function (title) {
        let option = $("<option>");
        option.val(title).html(title);
        titleSelect.append(option);
    });
    titleSelect.selectpicker("refresh");
    titleSelect.selectpicker("val", filters.employees.select.title);
};

/**
 * Populate the practice-filter modal when a filter edit request has been made
 *
 * @param {Event Object}
 */
var onOpenPracticeFilter = function(event) {
    var modal = $(this);

    // Checkboxes
    var checkboxes = filters.practices.checkboxes;
    console.log(checkboxes);
    Object.keys(checkboxes).forEach(function(property) {
        modal.find(`[name="${property}"]`).prop("checked", checkboxes[property]);
    });
};

/**
 * Populate the employee-add modal when an add request has been made
 *
 * @param {Event Object}
 */
var onOpenEmployeeAdd = function (event) {
    displayManagers("manager-add");
    displayEmployeeTitles("employee-add-title");
    form.selectOtherReset($("#employee-add-title"));
};

/**
 * Populate the practice-edit modal when an edit request has been made
 *
 * @param {Event Object}
 */
var onOpenPracticeEdit = function (row) {
    var modal = $("#modal-practice-edit");
    var practice = adminData.practices[row.id];
    modal.find(".modal-title").text(`Edit Practice: ${practice.number}`);
    modal.find("[data-value='practice_id']").val(practice.id);
    modal.find("#practice-edit-status")
        .removeClass("is-invalid")
        .val(practice.is_active).change();
    modal.find("#print-practice").attr("href", `/administration/printing/practice/${practice.id}`);
    form.dynamicFormClear("edit_physicians");
    practice.physicians.forEach(function(physician) {
        form.dynamicFormAdd("edit_physicians", {"name": physician.name});
    });
    modal.modal("show");
};

// Generic Functions -----------------------------------------------------------

/**
 * Refresh the employees and practices from the database and display the results
 */
var updateEmployeesAndPractices = function () {
    var filterList = {};
    for (var section in filters) {
        filterList[section] = {}
        for (type in filters[section]) {
            for (name in filters[section][type]) {
                filterList[section][name] = filters[section][type][name];
            }
        }
    }
    clearEmployees();
    clearPractices();
    table("employees").setData({}).setLoading(true);
    table("practices").setData({}).setLoading(true);
    fetchEmployeesAndPractices(filterList, function () {
        table("employees").setLoading(false);
        table("practices").setLoading(false);
        displayEmployees();
        displayPractices();
    });
};

// Initialization --------------------------------------------------------------

/**
 * Initialize the administrator page and register event listeners
 */
var init = function() {
    // Register modal event handlers
    $("#modal-employee-add").on("show.bs.modal", onOpenEmployeeAdd);
    $("#modal-employee-filter").on("show.bs.modal", onOpenEmployeeFilter);
    $("#modal-practice-filter").on("show.bs.modal", onOpenPracticeFilter);

    // Register input event handlers
    $("#filter-employees").keyup(function () {
        table("employees").setKeywordFilter($(this).val());
    });
    $("#filter-practices").keyup(function () {
        table("practices").setKeywordFilter($(this).val());
    });

    // Open the modals on edit
    table("employees").click(onOpenEmployeeEdit);
    table("practices").click(onOpenPracticeEdit);

    // Register Ajax forms
    form.ajaxForm("employee_add", onEmployeeAdd);
    form.ajaxForm("employee_edit_password", onEmployeeSetPassword);
    form.ajaxForm("employee_edit_status", onEmployeeSetStatus);
    form.ajaxForm("employee_edit_title", onEmployeeSetTitle);
    form.ajaxForm("employee_edit_name", onEmployeeSetName);
    form.ajaxForm("employee_edit_auth", onEmployeeSetAuthority);
    form.ajaxForm("employee_edit_practice_link", onEmployeePracticeLink,
        function () {
            return Boolean($("#practice-link-select").val()); // Don't allow to submit if no practice is selected
        });
    form.ajaxForm("employee_edit_practice_unlink", onEmployeePracticeUnlink,
        function () {
            return $("[name='unlink[]']:checked").length > 0; // Don't allow to submit if no practices are selected
        });
    form.ajaxForm("employee_assign_manager", onEmployeeAssignManager);
    form.ajaxForm("practice_add", onPracticeAdd);
    form.ajaxForm("practice_edit_status", onPracticeSetStatus);
    form.ajaxForm("practice_edit_physicians", onPracticeSetPhysicians);

    // Handle filter modals
    $("[name='employee_filter'], [name='practice_filter']").submit(function() {
        applyDataFilters();
        $("#modal-employee-filter, #modal-practice-filter").modal("hide");
        updateEmployeesAndPractices();
        return false;
    });

    // Fetch the list of employees and practices and display them
    updateEmployeesAndPractices();
};

// Module Export ---------------------------------------------------------------

// Export the module functions
window.admin = {
    init: init
};
