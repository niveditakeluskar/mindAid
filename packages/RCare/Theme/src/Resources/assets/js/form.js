/**
 * Ajax Form Automation Utility
 * Created by David Ludwig
 *
 * This little library will completely automate your Ajax forms, from submission
 * to displaying errors on your fields. By simply calling `ajaxSubmit` with your
 * form name (the `name` attribute), this library will automatically find all of
 * your inputs within the form, the URL and HTTP method will be fetched from the
 * attributes of the form as if you were to use a normal form, submitted, and it
 * will automatically display the error messages from the response using the div
 * just after the input with the class of `invalid-feedback`.
 */

const createHash = require("hash-generator");

const HASH_LENGTH = 10;
var counter = 0;
/**
 * Store any dynamic form templates
 */
var formTemplates      = {};
var formAutofillRules  = {};
var formAutofillFields = {   
    /* "current_date" : function(){
        var now = new Date();
        var day = ("0" + now.getDate()).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);
        return `${now.getUTCFullYear()}-${month}-${day}`;
    } */
};

var formAutofillCasting = {
    "years_difference": function (value) {
        return Math.abs(util.timeDifference(value).getFullYear() - 1970);
    },
    "days_difference": function (value) {
        var day = 1000 * 60 * 60 * 24;
        var date = new Date(value).getTime()
        var today = Date.now();
        return Math.round(Math.abs((date - today) / day));
    },
    "business_days_difference_initial": function (value) {
        var date = $("#initial_contact_required").val();
       
        var iWeeks, iDateDiff, iAdjust = 0;
        var dDate1 = new Date(date);
        var dDate2 = new Date(value);
         
          if (dDate2 < dDate1) return 0;                 // error code if dates transposed
         
          var iWeekday1 = dDate1.getDay();                // day of week
          var iWeekday2 = dDate2.getDay();
         
          iWeekday1 = (iWeekday1 == 0) ? 7 : iWeekday1;   // change Sunday from 0 to 7
          iWeekday2 = (iWeekday2 == 0) ? 7 : iWeekday2;
         
          if ((iWeekday1 > 5) && (iWeekday2 > 5)) iAdjust = 1;  // adjustment if both days on weekend
         
          iWeekday1 = (iWeekday1 > 5) ? 5 : iWeekday1;    // only count weekdays
          iWeekday2 = (iWeekday2 > 5) ? 5 : iWeekday2;
         
          // calculate differnece in weeks (1000mS * 60sec * 60min * 24hrs * 7 days = 604800000)
          iWeeks = Math.floor((dDate2.getTime() - dDate1.getTime()) / 604800000)
         
          if (iWeekday1 <= iWeekday2) {
            iDateDiff = (iWeeks * 5) + (iWeekday2 - iWeekday1)
          } 
          else {
            iDateDiff = ((iWeeks + 1) * 5) - (iWeekday1 - iWeekday2)
          }
         
          iDateDiff -= iAdjust ;
          var days = iDateDiff + 1;
          /* if(days>=3)
          {
            alert("STOP! Does not qualify for TCM. The process/program is stopped.");
          } */
          return days;
          //alert("STOP! Does not qualify for TCM. The process/program is stopped.");
    },

    "business_days_difference_second": function (value) {
        var date = $("#second_contact_required").val();
       
        var iWeeks, iDateDiff, iAdjust = 0;
        var dDate1 = new Date(date);
        var dDate2 = new Date(value);
         
          if (dDate2 < dDate1) return 0;                 // error code if dates transposed
         
          var iWeekday1 = dDate1.getDay();                // day of week
          var iWeekday2 = dDate2.getDay();
         
          iWeekday1 = (iWeekday1 == 0) ? 7 : iWeekday1;   // change Sunday from 0 to 7
          iWeekday2 = (iWeekday2 == 0) ? 7 : iWeekday2;
         
          if ((iWeekday1 > 5) && (iWeekday2 > 5)) iAdjust = 1;  // adjustment if both days on weekend
         
          iWeekday1 = (iWeekday1 > 5) ? 5 : iWeekday1;    // only count weekdays
          iWeekday2 = (iWeekday2 > 5) ? 5 : iWeekday2;
         
          // calculate differnece in weeks (1000mS * 60sec * 60min * 24hrs * 7 days = 604800000)
          iWeeks = Math.floor((dDate2.getTime() - dDate1.getTime()) / 604800000)
         
          if (iWeekday1 <= iWeekday2) {
            iDateDiff = (iWeeks * 5) + (iWeekday2 - iWeekday1)
          } 
          else {
            iDateDiff = ((iWeeks + 1) * 5) - (iWeekday1 - iWeekday2)
          }
         
          iDateDiff -= iAdjust ;
          var days = iDateDiff + 1;
          /* if(days>=3)
          {
            alert("STOP! Does not qualify for TCM. The process/program is stopped.");
          } */
          return days;
    } 
};



// General Form Utilities --------------------------------------------------------------------------

/**
 * Get the options available in a select box
 *
 * @param  {jQuery Object} field
 * @return {Array<String>}
 */
var getSelectOptions = function(field) {
    var result = [];
    field.find("option").each(function() {
        var value = $(this).attr("value");
        if (value == undefined)
            value = $(this).text();
        result.push(value);
    });
    return result;
};



// Ajax Utilities ----------------------------------------------------------------------------------

/**
 * Handle errors from an Ajax request by displaying the error messages at the fields
 *
 * @param {jQuery Object} form
 * @param {JSON Object}   fields
 * @param {Object}        response
 */
var onAjaxErrors = function (form, fields, response) {
    console.log("Errors occurred");

    var errors = response.data.errors;
    var fieldNames = Object.keys(errors);
    for (let i = 0; i < fieldNames.length; i++) {
        try {
            let field = fields.fields[fieldNames[i]]; //, form.find(`[name="${fieldNames[i]}"]`);
            if (!field)
                return;
            if (field.attr("data-feedback")) {
                $(`[data-feedback-area="${field.attr("data-feedback")}"]`).html(errors[fieldNames[i]]);
            } else
                field.next(".invalid-feedback").html(errors[fieldNames[i]]);
            field.addClass("is-invalid");
        } catch (e) {
            console.error(`Ajax error reporting: for field ${fieldNames[i]}`, e);
        }
    }
}

/**
 * Locate the fields within the form
 *
 * @param  {String}      formName The name attribute of the form
 * @return {JSON Object}
 */
var getFields = function (formName) {
    var form = $(`form[name="${formName}"]`);
    var result = {
        fields: {},
        values: {}
    };
    $(form).find("input, select, textarea").each(function (index) {
        let type = $(this).attr("type");
        let name = $(this).attr("name");
        if (name) {
            // Split array names into each part
            let nameParts = name.split(/\[\s*\]|\[([^\]]*)\]/);
            let key = nameParts[0];
            let field = nameParts[0];
            let scope = result.values;
            for (let i = 1; i < nameParts.length; i += 2) {
                if (!nameParts[i]) {
                    if (!Array.isArray(scope[key]))
                        scope[key] = [];
                    scope = scope[key];
                    key = scope.length;
                } else {
                    if (typeof scope[key] != "object" || Array.isArray())
                        scope[key] = {};
                    scope = scope[key];
                    key = nameParts[i];
                }
                field += `.${key}`;
            }
            if (type == "checkbox") {
                // Usually unchecked checkboxes are not sent; however, we wish to send unchecked
                // checkboxes.
                scope[key] = $(this).is(":checked");
            } else if (type == "radio") {
                // If it's not checked, and it's not specified what it already is
                // e.g. if the first radio in the group is checked, and the second is not,
                // don't override the `checked value` with null
                if (!$(this).is(":checked") && scope[key] === undefined)
                    scope[key] = null;
                else if ($(this).is(":checked"))
                    scope[key] = $(this).val();
            } else
                scope[key] = $(this).val();
            result.fields[field] = $(this);
        }
    });
    return result;
};

/**
 * Submit the given form using Ajax. The HTTP method and Url will be retrieved
 * from the form's attributes, so don't forget to specify.
 *
 * @param {String}             formName The name attribute of the form
 * @param {Function|Undefined} onResult Function invoked after received result
 * @param {Function|Undefined} onErrors Function invoked if errors occurred
 */
var ajaxSubmit = function (formName, onResult, onErrors) {
    var form = $(`form[name="${formName}"]`);
    var action = form.attr("action") || window.location.href;
    var method = String(form.attr("method") || "GET").toUpperCase();
    var enabledFields = form.find("input:enabled, select:enabled, button:enabled");

    // Remove any error messages
    form.find(".is-invalid").removeClass("is-invalid");
    form.find(".invalid-feedback").html("");

    // Disable the fields while the submission goes through
    enabledFields.prop("disabled", true);

    // Gather the input fields
    var fields = getFields(formName);
    console.log(fields);

    // Because Axios doesn't allow sending a body with a GET request...
    if (method == "GET") {
        data   = undefined;
        params = fields.values
    } else {
        data   = fields.values;
        params = undefined;
    }

    // Send the Ajax request
    axios({
        method: method,
        url: action,
        data: data,
        params: params
    }).then(function (response) {
        console.log(response);
        enabledFields.prop("disabled", false);
        if (!onResult || (onResult && onResult(form, fields, response)))
            if (response.data && response.data.redirect)
                window.location = response.data.redirect;
    }).catch(function (error) {
        console.error(error, error.response);
        enabledFields.prop("disabled", false);
        if (error.response.data.errors && (!onErrors || onErrors(form, fields, error.response)))
            onAjaxErrors(form, fields, error.response);
        if (onResult)
            onResult(form, fields, error.response, error);
    });
};

/**
 * Register the given form name to be an Ajax form
 *
 * @param {String}             name       The `name` attribute of the form
 * @param {Function|Undefined} onResult   Optional callback function that's invoked after submission
 * @param {Function|Undefined} onSubmit   Optional callback function that's invoked before submission
 * @param {Function|Undefined} onErrors   Optional callback function that's invoked if errors occur
 */
var ajaxForm = function (name, onResult, onSubmit, onErrors) {
    $(`form[name="${name}"]`).submit(function () {
        if (!onSubmit || onSubmit(name))
            ajaxSubmit(name, onResult, onErrors);
        return false;
    });
};

// Select Field Utilities --------------------------------------------------------------------------

/**
 * Set the options for a select other box
 *
 * @param {jQuery Object}     field
 * @param {Array|JSON Object} value
 * @return {Undefined}
 */
var selectOtherSetOptions = function (field, options) {
    options = options || [];
    var children = field.children();
    for (var i = 1; i < children.length - 1; i++) {
        $(children[i]).remove();
    }

    let first = $(children[0]);
    if (Array.isArray(options)) {
        options.forEach(function (value) {
            let option = $("<option>");
            option.val(value).html(value);
            first.after(option);
        });
    } else {
        Object.keys(options).forEach(function (key) {
            let option = $("<option>");
            option.val(key).html(options[key]);
            first.after(option);
        })
    }
};

/**
 * Set the current value for the given select other box
 *
 * @param {jQuery Object} field
 * @param {String}        value
 * @return {Undefined}
 */
var selectOtherSetValue = function (field, value) {
    console.log("The title is", value);
    dynamicFormPopulateSelectOther(field, value);
};

/**
 * Reset the given select other box
 *
 * @param {jQuery Object} field
 * @return {Undefined}
 */
var selectOtherReset = function(field) {
    field.children().first().prop("selected", true);
    field.children().last().val("");
    field.parents().find(".edit-option").val("");
    field.trigger("change");
};

// Dynamic Form Utilities --------------------------------------------------------------------------

/**
 * Parse and save any form templates that are defined
 */
var parseFormTemplates = function () {
    $("#dynamic-templates > [data-dynamic-template-id]").each(function () {
        formTemplates[$(this).attr("data-dynamic-template-id")] = $(this).clone();
        $(this).remove();
    });
};

/**
 * Get the dynamic form area root element from the given name
 *
 * @param {String}         name
 * @return {jQuery Object}
 */
var dynamicForm = function (group) {
    return $(`[data-dynamic-area="${group}"]`);
};

/**
 * Add a dynamic form row
 *
 * @param {String}      group      The group to add the template to
 * @param {String}      templateId The template to add
 * @param {JSON Object} attributes The values for the given template
 */
var dynamicFormAdd = function (group, attributes = {}, hash = null) {
	
    var area = $(`[data-dynamic-area="${group}"]`);
    if (area.length == 0)
        return;
    var templateName = area.attr("data-dynamic-template");
    var templateElem = formTemplates[templateName];
    var template = templateElem.clone();
    var hash = hash || createHash(HASH_LENGTH);
    //var counter = 0;
    $(`[data-dynamic-area="${group}"]`).trigger("dynamic-area-add", [group, template]);
    template.find("[name]").each(function () {
        let name = $(this).attr("name");
        let type = this.tagName.toLowerCase();
        $(this).attr("name", `${group}[${hash}][${name}]`);
        if ($(this).attr("data-feedback")) { // Check if invalid feedback is specified
            let feedback = template.find(`[data-feedback-area="${$(this).attr("data-feedback")}"]`);
            if (feedback) {
                let feedbackName = `${group}_${hash}_${feedback.attr("data-feedback-area")}`;
                feedback.attr("data-feedback-area", feedbackName);
                $(this).attr("data-feedback", feedbackName);
            }
        }
        if (attributes[name]) { // Check if attributes are supplied for this field
            dynamicFormPopulateField($(this), attributes[name]);
        }
    });
	
	if(group == 'tcm_patient_contact_addl_attempt'){
		dynVal = $("#dynamic_area_count").val();
        counter = parseInt(dynVal)+1;
		template.find('#patient_attempt').attr('id', 'patient_attempt_'+counter);
		template.find('#nextAttemptstarts .custom-control-label').attr('id', 'addl_contact_attempt_'+counter);
		template.find('.removebtn').attr('id', 'addl_contact_attempt_remove'+counter);
		template.find('#addl_contact_attempt_date').attr('id', 'addl_contact_attempt_date'+counter);
		todaysDate = util.updateTodaysDate('addl_contact_attempt_date'+counter, 'return'); 
		template.find('#addl_contact_attempt_date'+counter).attr('value', todaysDate);
		$(`[data-dynamic-area="${group}"]`).append(template);
		template.find("input[data-inputmask]").inputmask();
		$(`[data-dynamic-area="${group}"]`).trigger("dynamic-area-added", [group, template]);
		
		if(counter>=4){		
			prevId = parseInt(counter)-1;
			
			$("#addl_contact_attempt_remove"+prevId).css('display','none');
		}
        $("#dynamic_area_count").val(counter);
        var cntval = util.dynamicFieldCnt(counter);              
    }else{
		$(`[data-dynamic-area="${group}"]`).append(template);
		template.find("input[data-inputmask]").inputmask();
		$(`[data-dynamic-area="${group}"]`).trigger("dynamic-area-added", [group, template]);
		
	}
    return template;
};

/**
 * Remove a template element
 *
 * @param {jQuery Object} elem Element that triggered the event
 */
var dynamicFormRemove = function (elem) {	
    var parent = elem.parents("[data-dynamic-area]");
	
	if(parent.attr("data-dynamic-area") == 'tcm_patient_contact_addl_attempt'){
		
		dynVal = $("#dynamic_area_count").val();
		decreasedCnt = parseInt(dynVal)-1;
		 $("#dynamic_area_count").val(decreasedCnt);
		 $("#addl_contact_attempt_remove"+decreasedCnt).css('display','block');
	}
    parent.trigger("dynamic-area-remove", [parent.attr("data-dynamic-area"), elem]);
    elem.detach();
    parent.trigger("dynamic-area-removed", [parent.attr("data-dynamic-area"), elem]);

};

/**
 * Clear all entries in a dynamic form area
 */
var dynamicFormClear = function(group) {
    $(`[data-dynamic-area="${group}"]`).trigger("dynamic-area-clear", [group]);
    $(`[data-dynamic-area="${group}"]`).html("");
    $(`[data-dynamic-area="${group}"]`).trigger("dynamic-area-cleared", [group]);
};

/**
 * Populate a given field object
 *
 * @param {jQuery Object} field
 * @param {Any}           value
 */
var dynamicFormPopulateField = function(field, value) {
    let type = field[0].tagName.toLowerCase();
    if (type == "input") {
        let subType = ($(field).attr("type") || "").toLowerCase();
        if (subType) {
            subType = subType.toLowerCase();
        }
        if (subType == "radio") {
            dynamicFormPopulateRadio(field, value);
        } else if (subType == "checkbox") {
            dynamicFormPopulateCheckbox(field, value);
        } else {
            field.val(value);
        }
    } else if (type == "select") {
        dynamicFormPopulateSelect(field, value);
    } else if (type == "textarea") {
        field.val(value);
    }
};

/**
 * Populate a radio button (check/uncheck it basically)
 *
 * @param {jQuery Object} field
 * @param {Any}           value
 */
var dynamicFormPopulateRadio = function(field, value) {
    field.prop("checked", false);
    field.parent("label.btn").removeClass("active");
    field.each(function () {
        if ($(this).val() == String(value)) {
            $(this).prop("checked", true);
            $(this).parent("label.btn").addClass("active");
        }
    });
};

/**
 * Populate a checkbox (check/uncheck it basically)
 *
 * @param {jQuery Object} field
 * @param {Any}           value
 */
var dynamicFormPopulateCheckbox = function(field, value) {
    field.prop("checked", Boolean(value));
};

var dynamicFormPopulateSelectOther = function(field, value) {
    if (value == null || value == "") {
        field.children().first().prop("selected", true);
        field.trigger("change");
        return;
    }
    var option = field.children().filter(function () {
        return this.value == value;
    });
    if (option.length == 0) {
        field.find(".editable").val(value);
    }
    field.val(value);
    field.trigger("change");
};

/**
 * Populate a select box
 *
 * @param {jQuery Object} field
 * @param {Any}           value
 */
var dynamicFormPopulateSelect = function(field, value) {
    if (field.hasClass("selectpicker"))
        field.selectpicker("val", value);
    else if (field.hasClass("select-other"))
        dynamicFormPopulateSelectOther(field, value);
    else
        field.val(value);
};

/**
 * Populate the given form
 *
 * @param {JSON Object} data
 */
var dynamicFormPopulate = function(formName, data) {
    var form = $(`form[name="${formName}"]`);
    for (var key in data.static) {
        let field = form.find(`[name="${key}"]`);
        if (field.length == 0) {
            console.warn(`Dynamic Form Populate: No field named "${key}"`);
            continue;
        }
        dynamicFormPopulateField(field, data.static[key]);
    }
    $("[data-dynamic-area]").each(function() {
        dynamicFormClear($(this).attr("data-dynamic-area"));
    });
    for (var group in data.dynamic) {
        for (var hash in data.dynamic[group]) {
            dynamicFormAdd(group, data.dynamic[group][hash], hash);
        }
    }
};

/**
 * Check if the given dynamic form has an entry
 */
var dynamicFormFindEntry = function(formName, group, data) {
    if (!Array.isArray(data))
        data = [data];
    var area = $(`form[name='${formName}']`).find(`[data-dynamic-area='${group}']`);
    var rows = area.children();
    for (var i = 0; i < rows.length; i++) {
        let row = $(rows[i]);
        let result = true;
        for (var j = 0; j < data.length && result; j++) {
            let field = data[j];
            let elem = row.find(`[name$="[${field.name}]"]`);
            result = evaluateElement(elem, field);
        }
        if (result)
            return row;
    }
    return null;
};

// Autofill Utilities ------------------------------------------------------------------------------

var evaluateElement = function(elem, requirement) {
    try {
        var result = true;
        if (requirement.checked !== undefined) {
            result &= elem.prop("checked") == requirement.checked;
        }
        let value = elem.val();
        if (requirement.convert) {
            value = formAutofillCasting[requirement.convert](value);
        }
        if (requirement.value !== undefined) {
            if (typeof(requirement.value) == "object") {
                let cValue     = requirement.value[0];
                let comparator = requirement.value[1];
                if (value === NaN || value === undefined || value === null)
                    return false;
                // shut up, it's safe here
                result = eval(`value ${comparator} cValue`);
            } else {
                result &= value == requirement.value;
            }
        }
        return result;
    } catch(e) {
        return false;
    }
}

var evaluateRule = function(formName, ruleName) {
    var form = $(`form[name="${formName}"]`);
    var rule = formAutofillRules[formName][ruleName];
    var result = false;
    for (var i = 0; i < rule.requirements.length && !result; i++) {
        result = true;
        for (var j = 0; j < rule.requirements[i].length && result; j++) {
            let field = rule.requirements[i][j];
            let ids = typeof field.id == "string" ? [field.id] : field.id;
            let lResult = false;
            let matched = 0;
            let minMatches = field.min_matches || 0;
            for (let j = 0; j < ids.length && (!lResult || matched < minMatches); j++) {
                let element = form.find(`#${ids[j]}`);
                if(evaluateElement(element, field))
                {
                    matched++;
                    lResult |= true;
                }
            }
            result = lResult && matched >= minMatches;
        }
    }
    console.log("Rule Result:", result);
    var action = (result ? rule.satisfied : rule.unsatisfied) || [];
    action.forEach(function(field) {
        let element = form.find(`#${field.id}`);
        if (field.checked !== undefined) {
            // Update the labels for radio buttons
            if (element.get(0).type && element.get(0).type.toLowerCase() == "radio") {
                element.parent("label").button("toggle");
            } else {
                element.prop("checked", field.checked);
            }
        }
        if (field.value !== undefined) {
            element.val(field.value);
        }
    });
    // Add dynamic fields
    for (var area in rule.dynamic) {
        for (let name in rule.dynamic[area]) {
            let fields = rule.dynamic[area][name];
            let row = dynamicFormFindEntry(formName, name, fields);
            if (result && row == null) {
                let data = {}
                for (let i in fields) {
                    data[fields[i].name] = fields[i].value || fields[i].checked
                }
                dynamicFormAdd(name, data);
            }
            else if (!result && row) {
                row.remove();
            }
        }
    }
};

/**
 * Evaluate the entire list of rules
 */
var evaluateRules = function(formName) {
    for (var ruleName in formAutofillRules[formName])
        evaluateRule(formName, ruleName);
};

/**
 * Add the autofill rule to the form
 */
var addAutofillRule = function(form, name, rule) {
    // Start keeping track of the form
    if (!formAutofillFields[form]) {
        formAutofillFields[form] = {};
        formAutofillRules[form]  = {};
    }
    rule.requirements = Array.isArray(rule.requirements[0]) ? rule.requirements : [rule.requirements];
    rule.requirements.forEach((group) => {
        group.forEach((field) => {
            if (field.id) {
                let ids = typeof field.id == "string" ? [field.id] : field.id;
                ids.forEach((id) => {
                    if (!formAutofillFields[form][id]) {
                        formAutofillFields[form][id] = [];
                        let elem = $(`#${id}`);
                        let changeEvent = function() {
                            formAutofillFields[form][id].forEach(function(name) {
                                evaluateRule(form, name);
                            });
                        };
                        if (elem.attr("type") == "checkbox" || elem.attr("type") == "radio") {
                            elem.change(changeEvent);
                        } else {
                            elem.blur(changeEvent);
                        }
                    }
                    formAutofillFields[form][id].push(name);
                });
            }
            else if (field.dynamic_id) {
                // Dynamic fields here
            }
        });
    });
    formAutofillRules[form][name] = rule;
};

/**
 * Add the list of autofill rules to the form
 *
 * @param {String} form The name of the form
 */
var addAutofillRules = function(form, rules) {
    for (var rule in rules) {
        addAutofillRule(form, rule, rules[rule]);
    }
};

/**
 * Register event listeners
 */
$(document).ready(function () {
    $("input[data-inputmask]").inputmask();

    // Adjust the "other" value in the select menus
    $(".edit-option").each(function () {
        $(this).parents(".select-other-containter").find(".editable").val($(this).val());
    });

    $(".select-other .editable").each(function() {
        $(this).val("");
    });

    // Register text change event listeners
    $(document).on("keyup", ".edit-option", function () {
        $(this).parents(".select-other-container").find(".editable").val($(this).val());
    });

    // Register template form actions
    $(document).on("click", "[data-dynamic-action]", function () {
        var action = $(this).attr("data-dynamic-action")
        if (action == "remove")
            dynamicFormRemove($(this).parents("[data-dynamic-template-id]"));
        else if (action == "add")
            dynamicFormAdd($(this).attr("data-dynamic-group"));
    });

    $(document).on("change", ".select-other", function () {
        var edit = $(this).parent(".select-other-container").find(".edit-option");
        if ($("option:selected", this).attr("class") == "editable") {
            edit.val($(this).val()).show();
            edit.focus();
        } else {
            edit.hide();
        }
    });

    // Parse any existing templates
    parseFormTemplates();
});

// Export the form module
window.form = {
    ajaxForm: ajaxForm,
    ajaxSubmit: ajaxSubmit,
    getFields: getFields,
    getSelectOptions: getSelectOptions,
    selectOtherSetOptions: selectOtherSetOptions,
    selectOtherSetValue: selectOtherSetValue,
    selectOtherReset: selectOtherReset,
    dynamicForm: dynamicForm,
    dynamicFormAdd: dynamicFormAdd,
    dynamicFormClear: dynamicFormClear,
    dynamicFormFindEntry: dynamicFormFindEntry,
    dynamicFormPopulate: dynamicFormPopulate,
    dynamicFormRemove: dynamicFormRemove,
    addAutofillRules: addAutofillRules,
    evaluateRules: evaluateRules,
}
