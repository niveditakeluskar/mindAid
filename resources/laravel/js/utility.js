
/**
 * Filter the given data by searching the given keys for the given value
 *
 * @param  {JSON Object}  data   The given data object to search through
 * @param  {JSON Object}  fields What indices of the object to search through
 * @param  {String}       search The value(s) to search for in the data
 * @return {JSON Object}
 */
var pause_stop_flag = 0;
var pause_next_stop_flag = 0;
var baseURL = window.location.origin + '/';
var sPageURL = window.location.pathname;
parts = sPageURL.split("/"),
    module = parts[parts.length - 2];
var filterValues = function (data, fields, search = "") {
    search = search.toLowerCase().trim();
    let filtered = {};
    let keywords;
    if (search) {
        let re = /\s+/;
        keywords = search.split(re);
        for (let id in data) {
            let include = 1;
            for (var j = 0; j < keywords.length && include; j++) {
                try {
                    for (var k = 0; k < fields.length && !data[id][fields[k]].toLowerCase().includes(keywords[j]); k++) { }
                    include &= k != fields.length;
                } catch (e) {
                    include = 0;
                }
            }
            if (include)
                filtered[id] = data[id];
        }
    } else
        return data;
    return filtered
};

/**
 * Determine if the given argument is an Array
 *
 * @param  {Any}     obj
 * @return {Boolean}
 */
var isArray = function (obj) {
    return Object.prototype.toString.call(obj) === '[object Array]';
};

/**
 * Set the active tab
 *
 * @param {String} tabName
 */
var setActiveTab = function (tabGroup, tab) {
    $(`#tabs-${tabGroup}-${tab}-tab`).tab("show");
};

/**
 * Update the list of physicians to select from
 *
 * @param {Integer}       practiceId
 * @param {jQuery Object} selectElement
 * @param {Integer}       selectedPhysician
 */

//refresh allergies count
var refreshAllergyCountCheckbox = function (id, allergy_type, form_name) {

    axios({
        method: "GET",
        url: `/ccm/care-plan-development-allergies-allergylist/${id}/${allergy_type}/count_allergy`,
    }).then(function (response) {

        var allergiescnt = response.data;
        // console.log("allergycount"+allergiescnt+" "+allergy_type+ " "+form_name );      


        $('form[name="' + form_name + '"] #' + allergy_type + 'count').val(allergiescnt);
        if (allergiescnt == 0) {
            $('form[name="' + form_name + '"] .noallergiescheckbox').prop("disabled", false);

        } else {
            $('form[name="' + form_name + '"] .noallergiescheckbox').prop("disabled", true);


        }
    }).catch(function (error) {
        console.error(error, error.response);
    });
}

//ashwini 16th july 2022
var getDistinctDiagnosisCountForBubble = function (patientId) {

    if (!patientId) {
        return;
    }
    axios({
        method: "GET",
        url: `/org/ajax/diagnosis/${patientId}/patientdiagnosiscountforbubble`,
    }).then(function (response) {

        // console.log(response);
        var diagcount = response.data[0].count;
        // alert(diagcount);  

        $('.reviewcareplanbuttoncount').html(diagcount);



    }).catch(function (error) {
        console.error(error, error.response);
    });

}


//ashwini 
var getDiagnosisCount = function (patientId) {
    if (!patientId) {
        return;
    }
    axios({
        method: "GET",
        url: `/org/ajax/diagnosis/${patientId}/patientdiagnosiscount`,
    }).then(function (response) {
        // console.log(response);
        var diagcount = response.data;
        if (diagcount > 0) {
            $('.reviewcareplanbutton').show();
            $('.createcareplanbutton').hide();
            // $('#createcareplanbuttoncount').hide();
            $('#reviewcareplanbuttoncount').show();
        } else {
            $('.createcareplanbutton').show();
            $('.reviewcareplanbutton').hide();
            // $('#createcareplanbuttoncount').show();
            $('#reviewcareplanbuttoncount').show();
        }

    }).catch(function (error) {
        console.error(error, error.response);
    });

}

//ashwini
var getDiagnosisIdfromPatientdiagnosisid = function (editid, condition_name, code, formsObj, patientId) {
    if (!editid) {
        return;
    }
    axios({
        method: "GET",
        url: `/org/ajax/diagnosis/${editid}/${patientId}/${condition_name}/${code}/editpatientdiagnosisId`,
    }).then(function (response) {

        // alert("condition change");
        var count = response.data;
        console.log(count);
        // var datatrim = d.trim();


        // console.log(datatrim);
        // console.log(condition_name);

        if (count > 0) {
            // alert("if");
            var formName = $(formsObj).closest(":has(form)").find('form').attr('name');


            $("form[name='" + formName + "'] #hiddenenablebutton").val(0);
            $("form[name='" + formName + "'] #symptoms_0").prop("disabled", true);
            $("form[name='" + formName + "'] #goals_0").prop("disabled", true);
            $("form[name='" + formName + "'] #tasks_0").prop("disabled", true);

            $("form[name='" + formName + "']  .symptoms ").prop("disabled", true);
            $("form[name='" + formName + "']  .goals ").prop("disabled", true);
            $("form[name='" + formName + "']  .tasks  ").prop("disabled", true);

            $("form[name='" + formName + "']  #append_symptoms_icons  ").hide();
            $("form[name='" + formName + "']  #append_goals_icons  ").hide();
            $("form[name='" + formName + "']  #append_tasks_icons  ").hide();

            $("form[name='" + formName + "']  .removegoals  ").hide();
            $("form[name='" + formName + "']  .removesymptoms  ").hide();
            $("form[name='" + formName + "']  .removetasks  ").hide();


        } else {
            // alert("else");  
            var formName = $(formsObj).closest(":has(form)").find('form').attr('name');

            // alert(formName);
            $("form[name='" + formName + "'] #hiddenenablebutton").val(1);
            $("form[name='" + formName + "'] #symptoms_0").prop("disabled", false);
            $("form[name='" + formName + "'] #symptoms_0").prop("disabled", false);
            $("form[name='" + formName + "'] #goals_0").prop("disabled", false);
            $("form[name='" + formName + "'] #tasks_0").prop("disabled", false);

            $("form[name='" + formName + "']  .symptoms ").prop("disabled", false);
            $("form[name='" + formName + "']  .goals ").prop("disabled", false);
            $("form[name='" + formName + "']  .tasks  ").prop("disabled", false);

            $("form[name='" + formName + "']  #append_symptoms_icons  ").show();
            $("form[name='" + formName + "']  #append_goals_icons  ").show();
            $("form[name='" + formName + "']  #append_tasks_icons  ").show();

            $("form[name='" + formName + "']  .removegoals  ").show();
            $("form[name='" + formName + "']  .removesymptoms  ").show();
            $("form[name='" + formName + "']  .removetasks  ").show();

        }


        // return  response.data;
    }).catch(function (error) {
        console.error(error, error.response);
    });
}

var getModuleId = function (name) {
    if (!name) {
        return;
    }
    axios({
        method: "GET",
        url: `/org/ajax/modules/${name}/moduleId`,
    }).then(function (response) {
        $("#getModuleIdFromDB").val(response.data);
        // console.log(response);
        // return  response.data;
    }).catch(function (error) {
        console.error(error, error.response);
    });
}

var updatePhysicianList = function (practiceId, selectElement, selectedPhysician = null) {
    // alert(practiceId);
    //selectElement.html($("<option value=''>").html("Select Physician"));
    $(selectElement)
        .empty()
        .append('<option value="">Select Provider</option><option value="0">Other</option>');
    if (practiceId == null || isNaN(practiceId)) {
        return;
    }

    axios({
        method: "GET",
        url: `/org/ajax/practice/${practiceId}/Providerphysicians`,
        // url: `/org/ajax/practice/${practiceId}/physicians`,
    }).then(function (response) {

        Object.values(response.data).forEach(function (physician) {
            $("<option>").val(physician.id).html(physician.name).appendTo(selectElement);
        });
        if (selectedPhysician) {
            selectElement.val(selectedPhysician);
        }
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var updatePhysicianListWithoutOther = function (practiceId, selectElement, selectedPhysician = null) {
    //modified by -ashvini 2ndnov2020
    //  alert(practiceId);
    //selectElement.html($("<option value=''>").html("Select Physician"));
    $(selectElement)
        .empty()
        .append('<option value="">Select Provider</option>');
    if (!practiceId) {
        practiceId = null;

    }
    if (isNaN(practiceId)) {
        practiceId = null;
    }
    axios({
        method: "GET",
        url: `/org/ajax/practice/${practiceId}/physicians`,
    }).then(function (response) {
        $("<option>").val('0').html('None').appendTo(selectElement);
        Object.values(response.data).forEach(function (physician) {
            $("<option>").val(physician.id).html(physician.name + " " + "(" + physician.count + ")").appendTo(selectElement);
        });
        if (selectedPhysician) {
            selectElement.val(selectedPhysician);
        }
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var updatePhysicianProviderListWithoutOther = function (practiceId, selectElement, selectedPhysician = null) {
    //modified by -ashvini 2ndjune2021
    //  alert(practiceId);
    //selectElement.html($("<option value=''>").html("Select Physician"));
    $(selectElement)
        .empty()
        .append('<option value="">Select Provider</option>');
    if (!practiceId) {
        practiceId = null;

    }
    if (isNaN(practiceId)) {
        practiceId = null;
    }
    axios({
        method: "GET",
        url: `/org/ajax/practice/provider/${practiceId}/physicians`,
    }).then(function (response) {
        // $("<option>").val('0').html('None').appendTo(selectElement);
        Object.values(response.data).forEach(function (physician) {
            $("<option>").val(physician.id).html(physician.name + " " + "(" + physician.count + ")").appendTo(selectElement);
        });
        if (selectedPhysician) {
            selectElement.val(selectedPhysician);
        }
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var updatePcpPhysicianList = function (practiceId, selectElement, selectedPhysician = null) {
    $(selectElement)
        .empty()
        .append('<option value="">Select Primary Care Provider(PCP)</option>');
    if (!practiceId) {
        return;
    }
    axios({
        method: "GET",
        url: `/org/ajax/provider/list/${practiceId}/Pcpphysicians`,
    }).then(function (response) {

        Object.values(response.data).forEach(function (physician) {
            $("<option>").val(physician.id).html(physician.name).appendTo(selectElement);
        });
        if (selectedPhysician) {
            selectElement.val(selectedPhysician);
        }
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var updateProviderPracticeListfunction = function (typeId, selectElement, selectedPhysician = null) {
    selectElement.html($("<option value=''>").html("Select practices"));
    if (!typeId) {
        return;
    }
    axios({
        method: "GET",
        url: `ccm/carePlanDevelopment/${typeId}/practice`,
    }).then(function (response) {
        Object.values(response.data).forEach(function (practice) {
            $("<option>").val(practice.id).html(practice.name).appendTo(selectElement);
        });
        if (selectedPractices) {
            selectElement.val(selectedPractices);
        }
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var selectDiagnosisCode = function (conditionId, selectElement, selectedDiagnosis = null) {
    $(selectElement)
        .empty()
        .append('<option value="">Select Code</option><option value="0">Other</option>');
    if (!conditionId) {
        return;
    }
    axios({
        method: "GET",
        url: `/ccm/get_diagnosis_all_codes/${conditionId}/get_diagnosis_all_codes`,
    }).then(function (response) {
        var code;
        Object.values(response.data).forEach(function (diagnosis) {
            if (diagnosis.code != "" && diagnosis.code != null && diagnosis.code != undefined) {
                code = diagnosis.code.toUpperCase();
            } else {
                code = diagnosis.code;
            }
            $("<option>").val(diagnosis.code).html(code).appendTo(selectElement);
        });
        if (selectedDiagnosis) {
            selectElement.val(selectedDiagnosis);
        }
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

//providers
// var updateProviderList = function(practiceId, selectElement, selectedPhysician = null) {
//     selectElement.html($("<option>").html("Select Providers"));
//     if (!practiceId) {
//         return;
//     }
//     axios({
//         method: "GET",
//         url: `ccm/carePlanDevelopment/${practice_id}/provider`,
//     }).then(function (response) {
//         Object.values(response.data).forEach(function(provider) {
//             $("<option>").val(provider.id).html(provider.name).appendTo(selectElement);
//         });
//         if (selectedProvider) {
//             selectElement.val(selectedProvider);
//         }
//     }).catch(function (error) {
//         console.error(error, error.response);
//     });
// };

/* Dropdown for team leader and checking the user type in dashboard */
var checkAdmin = function () {
    axios({
        method: "GET",
        url: `/ajax/dashboard/checkAdmin`,
    }).then(function (response) {
        //console.log(response.data);
    }).catch(function (error) {
        console.error(error, error.response);
    });
}

var check = function () {
    var id = $("#hiddenid").val();
    $("#teamleader_id").hide();
    var title_id = $("#identify").val();

    if (title_id == '3') {
        $("#teamleader_id_text").css('display', 'block');
        if ($("#teamleader_id_text").is(":visible")) {
            axios({
                method: "GET",
                url: `/ajax/dashboard/${id}/fetchTeamLeader`,
            }).then(function (response) {
                var drop = $("#caremanager_id");
                drop.empty();
                drop.append($("<option></option>").attr("value", '').text("Please Select"));
                Object.values(response.data).forEach(function (careData) {
                    drop.append($("<option></option>").attr("value", careData.firstname).text(careData.firstname + " " + careData.lastname));
                });
            }).catch(function (error) {
                console.error(error, error.response);
            });
        }
    } else {
        $("#selectd").css('display', 'block');
    }
}

var teamLeader = function (id) {
    axios({
        method: "GET",
        url: `/ajax/dashboard/${id}/fetchTeamLeader`,
    }).then(function (response) {
        var drop = $("#caremanager_id");
        drop.empty();
        drop.append($("<option></option>").attr("value", '').text("Please Select"));
        Object.values(response.data).forEach(function (careData) {
            // alert(careData.firstname);
            drop.append($("<option></option>").attr("value", careData.firstname).text(careData.firstname + " " + careData.lastname));
        });
    }).catch(function (error) {
        console.error(error, error.response);
    });
}

/* Dropdown for team leader and checking the user type in dashboard */

/* get the count of patients whose initial contact date is due today */
var countInitialContactDueToday = function () {
    //debugger;
    axios({
        method: "GET",
        url: `/ajax/tcmdashboard/patientInitialContactDueToday`,
    }).then(function (response) {
        $("#initial-contact-due-today").html(response.data);
        //alert(response.data);
    }).catch(function (error) {
        console.error(error, error.response);
    });
};
/* get the count of patients whose initial contact date is due today */

/* get the count of patients whose second contact date is due today */
var countSecondContactDueToday = function () {
    //debugger;
    axios({
        method: "GET",
        url: `/ajax/tcmdashboard/patientSecondContactDueToday`,
    }).then(function (response) {
        $("#second-contact-due-today").html(response.data);
        //alert(response.data);
    }).catch(function (error) {
        console.error(error, error.response);
    });
};
/* get the count of patients whose second contact date is due today */

/* get the count of patients whose face-to-face visit is due this week */
var countFacetoFaceVisitDueThisWeek = function () {
    axios({
        method: "GET",
        url: `/ajax/tcmdashboard/patientFacetoFaceVisitDueThisWeek`,
    }).then(function (response) {
        $("#face-to-face-visit-due-this-week").html(response.data);
        //alert(response.data);
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

/* get the count of patients whose face-to-face visit is due this week */
var patientCount = function () {
    //debugger;
    axios({
        method: "GET",
        url: `/ajax/dashboard/patientCount`,
    }).then(function (response) {
        $('#patient-count').html(response.data);
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var newlyAssignedCount = function () {
    axios({
        method: "GET",
        url: `/ajax/dashboard/newlyAssignedCount`,
    }).then(function (response) {
        $('#newly-assigned').html(response.data);
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var inProgressCount = function () {
    axios({
        method: "GET",
        url: `/ajax/dashboard/inProgressCount`,
    }).then(function (response) {
        $('#status-count').html(response.data);
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var nonBillableCount = function (empId) {
    axios({
        method: "GET",
        url: `/ajax/dashboard/nonBillableCount`,
    }).then(function (response) {
        $('#non-billable').html(response.data);
    }).catch(function (error) {
        console.error(error, error.response);
    });
}

var billableCount = function () {

    axios({
        method: "GET",
        url: `/ajax/tcmdashboard/billableCount`,
    }).then(function (response) {
        $("#billable").html(response.data);
        //alert(response.data);
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var readmissionCount = function () {

    axios({
        method: "GET",
        url: `/ajax/tcmdashboard/readmissionCount`,
    }).then(function (response) {
        $("#readmission").html(response.data);
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

/* get the max count of patient addl contact attempt for a specific tcm_form_id */
var countAttempt = function (tcmId) {
    //debugger;
    if (!tcmId) {
        return (false);
    }
    axios({
        method: "GET",
        url: `/ajax/tcm/${tcmId}/patientAttempt`,
    }).then(function (response) {
        $("#count_attempt").val(response.data);
        //return response.data;
        //hiddenElement.val(response.data);
        //alert(response.data);
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

/* assign id and labels for patient contact attempts */
var dynamicFieldCnt = function (count) {
    var cnt = parseInt(count);
    //var cnt = parseInt($("#count_attempt").val());
    //alert(counter);
    //alert(cnt);
    //var nextItem = cnt;
    /* for(var i = 0; i < counter; i++)
    {

    } */

    decOrdinal = decimalToOrdinal(cnt);
    decWord = ordinalInWord(cnt);

    $("#patient_attempt_" + cnt).html(decWord + " Contact Required on:");
    $("#addl_contact_attempt_" + cnt).html(decOrdinal + " Attempt")


    //$("#count_attempt").val(cnt);

};


/* Convert decimal to ordinal for eg: 1st, 2nd and so on --  added by Deborah on 06-07-2019 */
function decimalToOrdinal(i) {
    var j = i % 10,
        k = i % 100;
    if (j == 1 && k != 11) {
        return i + "st";
    }
    if (j == 2 && k != 12) {
        return i + "nd";
    }
    if (j == 3 && k != 13) {
        return i + "rd";
    }
    return i + "th";
}
/* Convert decimal to ordinal for eg: 1st, 2nd and so on */


/* Convert decimal to ordinal words for eg: First, Second and so on -- added by Deborah on 06-07-2019 */
function ordinalInWord(cardinal) {
    var ordinals = ['Zeroth', 'First', 'Second', 'Third', 'Fourth', 'Fifth', 'Sixth',
        'Seventh', 'Eighth', 'Ninth', 'Tenth', 'Eleventh', 'Twelveth', 'Thirteenth',
        'Fourteenth', 'Fifteenth', 'Sixteenth', 'Seventeenth', 'Eighteenth', 'Nineteenth',
        'Twentieth'/* and so on up to "twentieth" */];
    var tens = {
        20: 'twenty',
        30: 'thirty',
        40: 'forty' /* and so on */
    };
    var ordinalTens = {
        30: 'thirtieth',
        40: 'fortieth',
        50: 'fiftieth'
    };

    if (cardinal <= 20) {
        return ordinals[cardinal];
    }

    if (cardinal % 10 === 0) {
        return ordinalTens[cardinal];
    }

    return tens[cardinal - (cardinal % 10)] + ordinals[cardinal % 10];
}
/* Convert decimal to ordinal words for eg: First, Second and so on */


/* TCM Charts */

/* Donut Chart */

/* Initial Contact Due Today Status */
var initialContactVariables = function () {

    axios({
        method: "GET",
        url: `/ajax/tcmdashboard/initialContactChart`,
    }).then(function (response) {
        //$("#hdnInitial").val(response.data);
        var donutVariables = response.data.count;
        //alert(response.data.count[0]);
        var ctx = $("#dashboard-chart-initial-contact-status").get(0).getContext("2d");
        var donut = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: response.data.labels,
                datasets: [{
                    data: [20, 30],
                    //data: [response.data.count[0], response.data.count[1]],
                    //data: response.data.count,
                    backgroundColor: ['#4e73df', '#1cc88a'],
                    hoverBackgroundColor: ['#2e59d9', '#17a673'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: false
                },
                cutoutPercentage: 20,
            },
        });

    }).catch(function (error) {
        console.error(error, error.response);
    });
};

/* Second Contact Due Today Status */
var secondContactVariables = function () {

    axios({
        method: "GET",
        url: `/ajax/tcmdashboard/secondContactChart`,
    }).then(function (response) {
        //$("#hdnInitial").val(response.data);
        var donutVariables = response.data.count;
        //alert(response.data.count[0]);
        var ctx = $("#dashboard-chart-second-contact-status").get(0).getContext("2d");
        var donut = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: response.data.labels,
                datasets: [{
                    data: [50, 30],
                    //data: [response.data.count[0], response.data.count[1]],
                    //data: response.data.count,
                    backgroundColor: ['#4e73df', '#1cc88a'],
                    hoverBackgroundColor: ['#2e59d9', '#17a673'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: false
                },
                cutoutPercentage: 20,
            },
        });

    }).catch(function (error) {
        console.error(error, error.response);
    });
};

/* TCM Patients */
var tcmPatientVariables = function () {

    axios({
        method: "GET",
        url: `/ajax/tcmdashboard/tcmPatientStatusChart`,
    }).then(function (response) {
        //$("#hdnInitial").val(response.data);
        var donutVariables = response.data.count;
        //alert(response.data.count[0]);
        var ctx = $("#dashboard-chart-tcm-patients-status").get(0).getContext("2d");
        var donut = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: response.data.labels,
                datasets: [{
                    //data : [20, 30, 50],
                    //data: [[response.data.count[0], response.data.count[1]],
                    data: response.data.count,
                    backgroundColor: ['#4e73df', '#1cc88a', '#F0B27A'],
                    hoverBackgroundColor: ['#2e59d9', '#17a673', '#BA4A00'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: false
                },
                cutoutPercentage: 20,
            },
        });

    }).catch(function (error) {
        console.error(error, error.response);
    });
};

/* Donut Chart */

/* Bar Graph */
var taskDueTodayVariables = function () {

    axios({
        method: "GET",
        url: `/ajax/tcmdashboard/tcmTaskDueTodayChart`,
    }).then(function (response) {
        var ctx = $("#dashboard-chart-tasks-due-today").get(0).getContext("2d");
        var donut = new Chart(ctx, {
            type: "bar",
            data: {
                labels: response.data.labels,
                datasets: [
                    {
                        data: response.data.count,
                        // data : [[response.data.count[0],[response.data.count[1],[response.data.count[2]],
                        backgroundColor: ['rgba(255, 206, 86, 0.2)', 'rgba(255, 206, 86, 0.2)', 'rgba(255, 206, 86, 0.2)'],
                        borderColor: ['rgba(255, 206, 86, 0.2)', 'rgba(255, 206, 86, 0.2)', 'rgba(255, 206, 86, 0.2)'],
                        borderWidth: 1
                    }
                ],
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
                //onClick: graphClickEvent
            }
        });

    }).catch(function (error) {
        console.error(error, error.response);
    });
};

/* function graphClickEvent(event, array){
     if(array[0]){
     alert("HI");
     }
 } */

var faceToFaceDueThisWeekVariables = function () {

    axios({
        method: "GET",
        url: `/ajax/tcmdashboard/tcmfaceToFaceDueThisWeekChart`,
    }).then(function (response) {
        var ctx = $("#dashboard-chart-face-to-face-due-this-week").get(0).getContext("2d");
        var donut = new Chart(ctx, {
            type: "bar",
            data: {
                labels: response.data.labels,
                datasets: [
                    {
                        data: response.data.count,
                        //data : [20, 10, 30, 15, 10, 2, 1],
                        backgroundColor: ['rgba(255, 206, 86, 0.2)', 'rgba(255, 206, 86, 0.2)', 'rgba(255, 206, 86, 0.2)'],
                        borderColor: ['rgba(255, 206, 86, 0.2)', 'rgba(255, 206, 86, 0.2)', 'rgba(255, 206, 86, 0.2)'],
                        borderWidth: 1
                    }
                ],
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
                // onClick: graphClickEvent
            }
        });

    }).catch(function (error) {
        console.error(error, error.response);
    });
};
/* Bar Graph */

/* TCM Charts */

var patientCount = function (empId) {
    //debugger;
    axios({
        method: "GET",
        url: `/ajax/dashboard/${empId}/patientCount`,
    }).then(function (response) {
        $('#patient-count').html(response.data);
    }).catch(function (error) {
        console.error(error, error.response);
    });
}

var viewsDateFormat = function (date) {
    var dt = new Date(date);
    var day = dt.getDate() < 10 ? '0' + (dt.getDate()) : dt.getDate();
    var month = dt.getMonth() < 9 ? '0' + (dt.getMonth() + 1) : dt.getMonth() + 1;
    var year = dt.getFullYear();
    return `${month}-${day}-${dt.getFullYear()}`;
    // var res = date.split("-");
    // return res[1]+"-"+res[2]+"-"+res[0];
};

var viewsDateFormatWithTime = function (date) {
    var dt = new Date(date);
    //console.log(date + ' test date');
    var day = dt.getDate() < 10 ? '0' + (dt.getDate()) : dt.getDate();
    var month = dt.getMonth() < 9 ? '0' + (dt.getMonth() + 1) : dt.getMonth() + 1;
    var year = dt.getFullYear();
    var time = dt.getTime();
    var dateString = '';

    var h = dt.getHours();
    var m = dt.getMinutes();
    var s = dt.getSeconds();

    if (h < 10) h = '0' + h;
    if (m < 10) m = '0' + m;
    if (s < 10) s = '0' + s;

    dateString = h + ':' + m + ':' + s;
    return `${month}-${day}-${dt.getFullYear()} ${dateString}`;
};


/**
 * Format the given date into mm/dd/YYYY
 *
 * @param  {Date}   date
 * @return {String}
 */
var formatDate = function (date) {
    var day = date.getDate() < 10 ? '0' + (date.getDate()) : date.getDate();
    var month = date.getMonth() < 9 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1;
    return `${month}/${day}/${date.getFullYear()}`;
};

/**
 * Format the given date to work with inputs Y-m-d
 *
 * @param  {Date}   date
 * @return {String}
 */
var dateValue = function (date) {
    var day = date.getDate() < 10 ? '0' + (date.getDate()) : date.getDate();
    var month = date.getMonth() < 9 ? '0' + (date.getMonth() + 1) : date.getMonth() + 1;
    return `${date.getFullYear()}-${month}-${day}`;
};

var timeDifference = function (date) {
    var timeV = new Date(date);
    var DifMs = Date.now() - timeV.getTime();
    return new Date(DifMs); // miliseconds from epoch
};

var dateFormatisEmpty = function (date) {
    var dt = new Date(date);
    var day = dt.getDate() == NaN ? '00' : dt.getDate();
    var month = dt.getMonth() == NaN ? '00' : dt.getMonth();
    var year = date.getFullYear();
    return `${month}/${day}/${dt.getFullYear()}`;
};

var age = function (date) {
    return Math.abs(timeDifference(date).getFullYear() - 1970) || "";

    /* days = Math.floor(date / (1000 * 60 * 60 * 24));
    ageYears = Math.floor(days / 365);
    ageMonths = Math.floor((days % 365) / 31);
    ageDays = days - (ageYears * 365) - (ageMonths * 31);
    ageText = "";
    if (ageYears > 0) {
        ageText = ageText + ageYears + " year";
        if (ageYears > 1) ageText = ageText + "s"; 
    }
    if (ageMonths > 0) {
        if (ageYears > 0) {
            if (ageDays > 0) ageText = ageText + ", "; 
            else if (ageDays == 0) ageText = ageText + " and ";
        }
        ageText = ageText + ageMonths + " month";
        if (ageMonths > 1) ageText = ageText + "s";
    }
    if (ageDays > 0) {
        if ((ageYears > 0) || (ageMonths > 0)) ageText = ageText + " and ";
        ageText = ageText + ageDays + " day";
        if (ageDays > 1) ageText = ageText + "s";
    }
    
    return ageText;  */

};

var updateTodaysDate = function (id, action) {
    var today = 0;
    try {
        var now = new Date();
        var day = ("0" + now.getDate()).slice(-2);
        var month = ("0" + (now.getMonth() + 1)).slice(-2);
        var today = `${now.getFullYear()}-${month}-${day}`;
        if (action != 'return') {
            if ($("#" + id).val() == "") {
                $("#" + id).val(today);
            }
        } else {
            return today;
        }
    }
    catch (e) {
        console.warn(e)
    }
    //var today = (month) + "/" + (day) + "/" + now.getFullYear();
};

$(document).ready(function () {
    $(document).on("click", "[data-change-tab]", function () {
        setActiveTab(
            $(this).attr("data-tab-group"),
            $(this).attr("data-change-tab")
        );
    });
    $("[name='dob'], #dob").blur(function () {
        $("#age").val(util.age($(this).val()));
    });

});



/// added on 29-06-21 Dhanashree


var renderDataTableOrder = function (tabid, url, columnData, assetBaseUrl, copyflag = "0", copyTo = '') {

    var copy_img = "assets/images/copy_icon.png";
    var excel_img = "assets/images/excel_icon.png";
    var pdf_img = "assets/images/pdf_icon.png";
    var csv_img = "assets/images/csv_icon.png";
    var table = $('#' + tabid).DataTable({
        "dom": '<"float-right"B><"float-right"f><"float-left"r><"clearfix">t<"float-left"i><"float-right"p><"clearfix">',
        // dom     : '<"float-right"B><"clearfix"><"navbar-text"><"float-left"lr><"float-right"f><"clearfix">t<"float-left"i><"float-right"p><"clearfix">',
        // dom     : '<"navbar-text"><"float-left"lr><"float-right"f><"float-right"B><"clearfix">t<"float-left"i><"float-right"p><"clearfix">',
        buttons: [
            {
                extend: 'copyHtml5',
                text: '<img src="' + baseURL + copy_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="Copy">',
            },
            {
                extend: 'excelHtml5',
                text: '<img src="' + baseURL + excel_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="Excel">',
                titleAttr: 'Excel'
            },
            {
                extend: 'csvHtml5',
                text: '<img src="' + baseURL + csv_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="CSV">',
                titleAttr: 'CSV',
                fieldSeparator: '\|',
            },
            {
                extend: 'pdfHtml5',
                text: '<img src="' + baseURL + pdf_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="PDF">',
                titleAttr: 'PDF'
            }
        ],
        processing: true,
        // serverSide : true,
        destroy: true,
        // sScrollX: 1500,
        // scrollY: true, 

        // scrollH: true,     
        // scrollCollapse: true,        
        ajax: url,
        columns: columnData,
        "Language": {
            search: "_INPUT_",
            // "search":'<a class="btn searchBtn" id="searchBtn"><i class="i-Search-on-Cloud"></i></a>',
            "searchPlaceholder": "Search records",
            "EmptyTable": "No Data Found"
        },
        "columnDefs": [{
            "targets": '_all',
            "defaultContent": ""
        }]/*,
        "drawCallback": function( settings ) {
            if(copyflag == 1){
                //alert( copyTo + ' DataTables has redrawn the table' );
                t2content = $('#' + tabid +' tbody').html();
                alert(t2content);
               // console.log(t2content);
                $('#' + copyTo + ' tbody').html(t2content);
                //alert(copyTo);

                

                table2 = util.renderRawDataDataTable(copyTo, assetBaseUrl);
               //table2 = util.renderRawDataDataTableAnand(copyTo, assetBaseUrl,columnData);
                //copydata();


            }
        }*/
    });



    return table;
}

///

var rendernewDataTable = function (tabid, url, columnData, assetBaseUrl, copyflag = "0", copyTo = '') {
    var copy_img = "assets/images/copy_icon.png";
    var excel_img = "assets/images/excel_icon.png";
    var pdf_img = "assets/images/pdf_icon.png";
    var csv_img = "assets/images/csv_icon.png";
    var table = $('#' + tabid).DataTable({
        "dom": '<"float-right"B><"float-right"f><"float-left"r><"clearfix">t<"float-left"i><"float-right"p><"clearfix">',
        // dom     : '<"float-right"B><"clearfix"><"navbar-text"><"float-left"lr><"float-right"f><"clearfix">t<"float-left"i><"float-right"p><"clearfix">',
        // dom     : '<"navbar-text"><"float-left"lr><"float-right"f><"float-right"B><"clearfix">t<"float-left"i><"float-right"p><"clearfix">',
        buttons: [
            {
                extend: 'copyHtml5',
                text: '<img src="' + baseURL + copy_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="Copy">',
            },
            // {
            //     extend: 'excelHtml5',
            //     text: '<img src="' + baseURL + excel_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="Excel">',
            //     titleAttr: 'Excel'
            // },
            // {
            //     extend: 'csvHtml5',
            //     text: '<img src="' + baseURL + csv_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="CSV">',
            //     titleAttr: 'CSV',
            //     fieldSeparator: '\|',
            // },
            {
                extend: 'pdfHtml5',
                text: '<img src="' + baseURL + pdf_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="PDF">',
                titleAttr: 'PDF'
            },

            {
                extend: 'excelHtml5',
                text: '<i class="fa fa-file-excel-o"></i>',
                titleAttr: 'Excel',
                "oSelectorOpts": { filter: 'applied', order: 'index', page: 'all' },
                "sFileName": "report.xls",
                action: function (e, dt, button, config) {
                    console.log(dt + "checkdata excel");
                    exportTableToCSV.apply(dt, [$('#' + tabid), 'export.xls']);

                }

            },
            {
                extend: 'csvHtml5',
                text: '<i class="fa fa-file-text-o"></i>',
                titleAttr: 'CSV',
                exportOptions: {
                    columns: ':visible',
                    modifer: {
                        page: 'all',
                        search: 'none'
                    }
                },
                action: function (e, dt, button, config) {
                    console.log(dt + "checkdata csv");
                    exportTableToCSV.apply(dt, [$('#' + tabid), 'export.csv']);

                }

            }

        ],
        processing: true,
        serverSide: false,
        destroy: true,
        //    sScrollX: true,
        // scrollY: 120, 
        // scrollH: true,     
        // scrollCollapse: true,        
        ajax: url,
        columns: columnData,
        "Language": {
            search: "_INPUT_",
            // "search":'<a class="btn searchBtn" id="searchBtn"><i class="i-Search-on-Cloud"></i></a>',
            "searchPlaceholder": "Search records",
            "EmptyTable": "No Data Found"
        },
        "columnDefs": [{
            "targets": '_all',
            "defaultContent": ""
        }]/*,
        "drawCallback": function( settings ) {
            if(copyflag == 1){
                //alert( copyTo + ' DataTables has redrawn the table' );
                t2content = $('#' + tabid +' tbody').html();
                alert(t2content);
               // console.log(t2content);
                $('#' + copyTo + ' tbody').html(t2content);
                //alert(copyTo);

                

                table2 = util.renderRawDataDataTable(copyTo, baseURL);
               //table2 = util.renderRawDataDataTableAnand(copyTo, baseURL,columnData);
                //copydata();


            }
        }*/
    });

    return table;
}

function exportTableToCSV($table, filename) {

    //rescato los títulos y las filas
    var $Tabla_Nueva = $table.find('tr:has(td,th)');
    // elimino la tabla interior.
    var Tabla_Nueva2 = $Tabla_Nueva.filter(function () {
        return (this.childElementCount != 1);
    });

    var $rows = Tabla_Nueva2,

        // var $rows = $('#dailyreviewlist').DataTable().rows.data(),//changes by ashvini

        // Temporary delimiter characters unlikely to be typed by keyboard
        // This is to avoid accidentally splitting the actual contents
        tmpColDelim = String.fromCharCode(11), // vertical tab character
        tmpRowDelim = String.fromCharCode(0), // null character

        // Solo Dios Sabe por que puse esta linea
        colDelim = (filename.indexOf("xls") != -1) ? '"\t"' : '","',
        rowDelim = '"\r\n"',


        // Grab text from table into CSV formatted string
        csv = '"' + $rows.map(function (i, row) {
            var $row = $(row);
            var $cols = $row.find('td:not(.hidden),th:not(.hidden)');

            return $cols.map(function (j, col) {
                var $col = $(col);
                var text = $col.text().replace(/\./g, '');
                return text.replace('"', '""'); // escape double quotes

            }).get().join(tmpColDelim);
            csv = csv + '"\r\n"' + 'fin ' + '"\r\n"';
        }).get().join(tmpRowDelim)
            .split(tmpRowDelim).join(rowDelim)
            .split(tmpColDelim).join(colDelim) + '"';


    download_csv(csv, filename);


}

function download_csv(csv, filename) {
    var csvFile;
    var downloadLink;

    // CSV FILE
    csvFile = new Blob([csv], { type: "text/csv" });

    // Download link
    downloadLink = document.createElement("a");

    // File name
    downloadLink.download = filename;

    // We have to create a link to the file
    downloadLink.href = window.URL.createObjectURL(csvFile);

    // Make sure that the link is not displayed
    downloadLink.style.display = "none";

    // Add the link to your DOM
    document.body.appendChild(downloadLink);

    // Lanzamos
    downloadLink.click();
}

var renderDataTable = function (tabid, url, columnData, assetBaseUrl, copyflag = "0", copyTo = '', directExport = null) {
    // console.log("tabid="+tabid+"  url="+url+"   columnData==>"+columnData);
    var copy_img = "assets/images/copy_icon.png";
    var excel_img = "assets/images/excel_icon.png";
    var pdf_img = "assets/images/pdf_icon.png";
    var csv_img = "assets/images/csv_icon.png";
    var table = $('#' + tabid).DataTable({
        "dom": '<"float-right"B><"float-right"f><"float-left"r><"clearfix">t<"float-left"i><"float-right"p><"clearfix">',
        // dom     : '<"float-right"B><"clearfix"><"navbar-text"><"float-left"lr><"float-right"f><"clearfix">t<"float-left"i><"float-right"p><"clearfix">',
        // dom     : '<"navbar-text"><"float-left"lr><"float-right"f><"float-right"B><"clearfix">t<"float-left"i><"float-right"p><"clearfix">',
        buttons: [
            {
                extend: 'copyHtml5',
                text: '<img src="' + baseURL + copy_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="Copy">',
            },
            {
                extend: 'excelHtml5',
                text: '<img src="' + baseURL + excel_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="Excel">',
                titleAttr: 'Excel'
            },
            {
                extend: 'csvHtml5',
                text: '<img src="' + baseURL + csv_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="CSV">',
                titleAttr: 'CSV',
                fieldSeparator: '\|',
            },
            {
                extend: 'pdfHtml5',
                text: '<img src="' + baseURL + pdf_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="PDF">',
                titleAttr: 'PDF'
            }

        ],
        processing: true,
        // serverSide : true,
        destroy: true,
        //    sScrollX: true,
        // scrollY: 120, 
        // scrollH: true,     
        // scrollCollapse: true,        
        ajax: url,
        columns: columnData,
        "Language": {
            search: "_INPUT_",
            // "search":'<a class="btn searchBtn" id="searchBtn"><i class="i-Search-on-Cloud"></i></a>',
            "searchPlaceholder": "Search records",
            "EmptyTable": "No Data Found"
        },
        "columnDefs": [{
            "targets": '_all',
            "defaultContent": ""
        }],
        "fnInitComplete": function (oSettings, json) {
            console.log(directExport + "checkval");
            if (directExport == "1") {
                table.button('.buttons-excel').trigger();
                setTimeout(function () {
                    window.close();
                }, 1500);
            }
        }
        /*,
        "drawCallback": function( settings ) {
            if(copyflag == 1){
                //alert( copyTo + ' DataTables has redrawn the table' );
                t2content = $('#' + tabid +' tbody').html();
                alert(t2content);
               // console.log(t2content);
                $('#' + copyTo + ' tbody').html(t2content);
                //alert(copyTo);

                

                table2 = util.renderRawDataDataTable(copyTo, baseURL);
               //table2 = util.renderRawDataDataTableAnand(copyTo, baseURL,columnData);
                //copydata();


            }
        }*/
    });

    return table;
}

var renderDataTable_pdf = function (tabid, url, columnData, assetBaseUrl, copyflag = "0", copyTo = '') {


    var copy_img = "assets/images/copy_icon.png";
    var excel_img = "assets/images/excel_icon.png";
    var pdf_img = "assets/images/pdf_icon.png";
    var csv_img = "assets/images/csv_icon.png";
    var table = $('#' + tabid).DataTable({
        "dom": '<"float-right"B><"float-right"f><"float-left"r><"clearfix">t<"float-left"i><"float-right"p><"clearfix">',
        // dom     : '<"float-right"B><"clearfix"><"navbar-text"><"float-left"lr><"float-right"f><"clearfix">t<"float-left"i><"float-right"p><"clearfix">',
        // dom     : '<"navbar-text"><"float-left"lr><"float-right"f><"float-right"B><"clearfix">t<"float-left"i><"float-right"p><"clearfix">',
        buttons: [
            {
                extend: 'copyHtml5',
                text: '<img src="' + baseURL + copy_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="Copy">',
            },
            {
                extend: 'excelHtml5',
                text: '<img src="' + baseURL + excel_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="Excel">',
                titleAttr: 'Excel'
            },
            {
                extend: 'csvHtml5',
                text: '<img src="' + baseURL + csv_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="CSV">',
                titleAttr: 'CSV',
                fieldSeparator: '\|',
            },
            {
                extend: 'pdfHtml5',
                text: '<img src="' + baseURL + pdf_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="PDF">',
                titleAttr: 'PDF'
            }
        ],
        processing: true,
        destroy: true,
        paging: false,
        ajax: url,
        columns: columnData,
        "Language": {
            search: "_INPUT_",
            "searchPlaceholder": "Search records",
            "EmptyTable": "No Data Found"
        },
        "columnDefs": [{
            "targets": '_all',
            "defaultContent": ""
        }]
    });

    return table;
}

var renderFixedColumnDataTable = function (tabid, url, columnData, assetBaseUrl) {
    //console.log("tabid="+tabid+"  url="+url+"   columnData==>"+columnData);
    var copy_img = "assets/images/copy_icon.png";
    var excel_img = "assets/images/excel_icon.png";
    var pdf_img = "assets/images/pdf_icon.png";
    var csv_img = "assets/images/csv_icon.png";
    var table = $('#' + tabid).DataTable({
        "dom": '<"float-right"B><"float-right"f><"float-left"r><"clearfix">t<"float-left"i><"float-right"p><"clearfix">',
        // dom     : '<"float-right"B><"clearfix"><"navbar-text"><"float-left"lr><"float-right"f><"clearfix">t<"float-left"i><"float-right"p><"clearfix">',
        // dom     : '<"navbar-text"><"float-left"lr><"float-right"f><"float-right"B><"clearfix">t<"float-left"i><"float-right"p><"clearfix">',
        buttons: [
            {
                extend: 'copyHtml5',
                text: '<img src="' + baseURL + copy_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="Copy">',
            },
            {
                extend: 'excelHtml5',
                text: '<img src="' + baseURL + excel_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="Excel">',
                titleAttr: 'Excel'
            },
            {
                extend: 'csvHtml5',
                text: '<img src="' + baseURL + csv_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="CSV">',
                titleAttr: 'CSV',
                fieldSeparator: '\|',
            },
            {
                extend: 'pdfHtml5',
                text: '<img src="' + baseURL + pdf_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="PDF">',
                titleAttr: 'PDF'
            }
        ],
        processing: true,
        // serverSide : true,
        destroy: true,
        ajax: url,
        columns: columnData,
        "Language": {
            search: "_INPUT_",
            // "search":'<a class="btn searchBtn" id="searchBtn"><i class="i-Search-on-Cloud"></i></a>',
            "searchPlaceholder": "Search records",
            "EmptyTable": "No Data Found"
        },
        "columnDefs": [{
            "targets": '_all',
            "defaultContent": ""
        }],
        scrollX: true,
        scrollCollapse: true,
        paging: false,
        fixedColumns: {
            leftColumns: 3
        }
    });


    // $($.fn.dataTable.tables(true)).DataTable().columns.adjust(); //table UI
    // $('#container').css('display', 'block');
    // table.columns.adjust().draw();
    // return table;
}

var renderRawDataDataTable = function (tabid, assetBaseUrl) {
    var copy_img = "assets/images/copy_icon.png";
    var excel_img = "assets/images/excel_icon.png";
    var pdf_img = "assets/images/pdf_icon.png";
    var csv_img = "assets/images/csv_icon.png";
    var table = $('#' + tabid).DataTable({
        "dom": '<"float-right"B><"float-right"f><"float-left"r><"clearfix">t<"float-left"i><"float-right"p><"clearfix">',
        buttons: [
            {
                extend: 'copyHtml5',
                text: '<img src="' + baseURL + copy_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="Copy">',
            },
            {
                extend: 'excelHtml5',
                text: '<img src="' + baseURL + excel_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="Excel">',
                titleAttr: 'Excel'
            },
            {
                extend: 'csvHtml5',
                text: '<img src="' + baseURL + csv_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="CSV">',
                titleAttr: 'CSV',
                fieldSeparator: '\|',
            },
            {
                extend: 'pdfHtml5',
                text: '<img src="' + baseURL + pdf_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="PDF">',
                titleAttr: 'PDF'
            }
        ],
        processing: true,
        // serverSide : true,
        destroy: true,
        sScrollX: true,
        // scrollY: 120, 
        // scrollH: true,     
        // scrollCollapse: true,        
        //columns: columnData,
        "Language": {
            search: "_INPUT_",
            // "search":'<a class="btn searchBtn" id="searchBtn"><i class="i-Search-on-Cloud"></i></a>',
            "searchPlaceholder": "Search records",
            "EmptyTable": "No Data Found"
        },
        "columnDefs": [{
            "targets": '_all',
            "defaultContent": ""
        }]
    });
    return table;
}

var copyDataFromOneDataTableToAnother = function (table, copyFromTableId, copyToTableId, baseURL) {
    $('#' + copyToTableId).DataTable().clear().destroy();
    table.on('draw', function () {
        if (table.data().count() != 0) {
            t2content = $('#' + copyFromTableId + ' tbody').html();
            $('#' + copyToTableId + ' tbody').html(t2content);
            table2 = util.renderRawDataDataTable(copyToTableId, baseURL);
        }
    });

    // var fromTableId = $('#' + copyFromTableId).DataTable();
    // var toTableId = $('#' + copyToTableId).DataTable();
    // var aTrs = fromTableId.rows().select().data();
    // // var aTrs = oTableSource.rows('.row_selected').data();  
    // for ( var i=0 ; i<aTrs.length ; i++ ) {  
    //     toTableId.row.add(aTrs[i]).draw();
    // }  


    // var row = $('#' + copyFromTableId + ' tbody').clone();
    //  $('#' + copyToTableId + ' tbody').append(row);
};
/**
 * Update the list of patients to select from
 *
 * @param {Integer}       practiceId
 * @param {jQuery Object} selectElement
 * @param {Integer}       selectedPatients
 */

var updatePartner = function (practiceId, moduleId, selectElement, selectedPatients = null) {
    //debugger;
    if (isNaN(practiceId)) {
        practiceId = null;
    }
    if (isNaN(moduleId)) {
        return;
    }
    axios({
        method: "GET",
        url: `/patients/ajax/practice/partner/${practiceId}/patient`
        // url: `/patients/ajax/patient/${practiceId}/patient`,
    }).then(function (response) {
        count = (response.data).length;
        console.log("updateprtner");
        console.log(response.data[0].partner_id);
        var partnerid = response.data[0].partner_id;
        $("form[name='device_order_form'] #partnerid").val(partnerid);

        // selectElement.html($("<option value=''>").html("Select Patient (" + count + ")"));
        // Object.values(response.data).forEach(function (patient) {
        //     var mname;
        //     if ((patient.mname != "") && (patient.mname != null) && (patient.mname != undefined)) {
        //         mname = patient.mname;
        //         $("<option>").val(patient.id).html(patient.fname + " " + patient.mname + " " + patient.lname + ", DOB: " + moment(patient.dob).format('MM-DD-YYYY')).appendTo(selectElement);
        //         // $("<option>").val(patient.id).html(patient.fname + " " + patient.mname + " " + patient.lname + ", DOB: " + viewsDateFormat(patient.dob)).appendTo(selectElement);
        //     } else {
        //         $("<option>").val(patient.id).html(patient.fname + " " + patient.lname + ", DOB: " + moment(patient.dob).format('MM-DD-YYYY')).appendTo(selectElement);
        //         // $("<option>").val(patient.id).html(patient.fname + " " + patient.lname + ", DOB: " + viewsDateFormat(patient.dob)).appendTo(selectElement);
        //         mname = "";
        //     }

        // });
        // if (selectedPatients) {
        //     selectElement.val(selectedPatients);
        // }


    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var updatePatientList = function (practiceId, moduleId, selectElement, selectedPatients = null) {
    //debugger;
    if (isNaN(practiceId)) {
        practiceId = null;
    }
    if (isNaN(moduleId)) {
        return;
    }
    axios({
        method: "GET",
        url: `/patients/ajax/practice/${practiceId}/${moduleId}/patient`
        // url: `/patients/ajax/patient/${practiceId}/patient`,
    }).then(function (response) {
        count = (response.data).length;
        selectElement.html($("<option value=''>").html("Select Patient (" + count + ")"));
        Object.values(response.data).forEach(function (patient) {
            var mname;
            if ((patient.mname != "") && (patient.mname != null) && (patient.mname != undefined)) {
                mname = patient.mname;
                $("<option>").val(patient.id).html(patient.fname + " " + patient.mname + " " + patient.lname + ", DOB: " + moment(patient.dob).format('MM-DD-YYYY')).appendTo(selectElement);
                // $("<option>").val(patient.id).html(patient.fname + " " + patient.mname + " " + patient.lname + ", DOB: " + viewsDateFormat(patient.dob)).appendTo(selectElement);
            } else {
                $("<option>").val(patient.id).html(patient.fname + " " + patient.lname + ", DOB: " + moment(patient.dob).format('MM-DD-YYYY')).appendTo(selectElement);
                // $("<option>").val(patient.id).html(patient.fname + " " + patient.lname + ", DOB: " + viewsDateFormat(patient.dob)).appendTo(selectElement);
                mname = "";
            }

        });
        if (selectedPatients) {
            selectElement.val(selectedPatients);
        }
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var updatePartnerDevice = function (partnerid, selectElement, selectPartnerDevice = null) {
    // if (!practiceId) {
    //     return;
    // }
    // alert("In Util");
    axios({
        method: "GET",
        url: `/patients/ajax/${partnerid}/practice/practiceId/moduleId/patient`,
    }).then(function (response) {
        console.log(response.data);
        count = (response.data).length;
        console.log(count);
        // selectElement.html($("<option value=''>").html("Select Partner Devices (" + count + ")"));
        selectElement.html($("<option value=''>").html("Select Partner Devices"));
        Object.values(response.data).forEach(function (PartnerDevices) {
            console.log(PartnerDevices);
            $("<option>").val(PartnerDevices.id).html(PartnerDevices.device_name).appendTo(selectElement);
            // $("<option>").val(patient.id).html(patient.fname + " " + mname + " " +patient.lname + ", DOB: " + viewsDateFormat(patient.dob)).appendTo(selectElement);
        });
        if (selectPartnerDevice) {
            selectElement.val(selectPartnerDevice);
        }
    }).catch(function (error) {
        console.error(error, error.response);
    });
};


var updatePatientListAssignedDevice = function (practiceId, moduleId, selectElement, selectedPatients = null) {
    //debugger;
    if (isNaN(practiceId)) {
        practiceId = null;
    }
    if (isNaN(moduleId)) {
        return;
    }
    axios({
        method: "GET",
        url: `/patients/ajax/practice/${practiceId}/${moduleId}/assign-patient`
        // url: `/patients/ajax/patient/${practiceId}/patient`,
    }).then(function (response) {
        count = (response.data).length;
        selectElement.html($("<option value=''>").html("Select Patient (" + count + ")"));
        Object.values(response.data).forEach(function (patient) {
            var mname;
            if ((patient.mname != "") && (patient.mname != null) && (patient.mname != undefined)) {
                mname = patient.mname;
                var fname = patient.fname;
                fname = fname.toLowerCase().replace(/\b[a-z]/g, function (letter) {
                    return letter.toUpperCase();
                });
                var mnamee = patient.mname;
                mnamee = mnamee.toLowerCase().replace(/\b[a-z]/g, function (letter) {
                    return letter.toUpperCase();
                });
                var lname = patient.lname;
                lname = lname.toLowerCase().replace(/\b[a-z]/g, function (letter) {
                    return letter.toUpperCase();
                });

                $("<option>").val(patient.id).html(fname + " " + mnamee + " " + lname + ", DOB: " + moment(patient.dob).format('MM-DD-YYYY')).appendTo(selectElement);
                // $("<option>").val(patient.id).html(patient.fname + " " + patient.mname + " " + patient.lname + ", DOB: " + viewsDateFormat(patient.dob)).appendTo(selectElement);
            } else {
                var fname = patient.fname;
                fname = fname.toLowerCase().replace(/\b[a-z]/g, function (letter) {
                    return letter.toUpperCase();
                });
                var lname = patient.lname;
                lname = lname.toLowerCase().replace(/\b[a-z]/g, function (letter) {
                    return letter.toUpperCase();
                });

                $("<option>").val(patient.id).html(fname + " " + lname + ", DOB: " + moment(patient.dob).format('MM-DD-YYYY')).appendTo(selectElement);
                // $("<option>").val(patient.id).html(patient.fname + " " + patient.lname + ", DOB: " + viewsDateFormat(patient.dob)).appendTo(selectElement);
                mname = "";
            }

        });
        if (selectedPatients) {
            selectElement.val(selectedPatients);
        }
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var getRpmPatientList = function (practiceId, selectElement, selectedPatients = null) {
    // if (!practiceId) {
    //     return;
    // }
    //alert("In Util");
    axios({
        method: "GET",
        url: `/patients/ajax/rpmpatientlist/${practiceId}/patientlist`,
    }).then(function (response) {
        count = (response.data).length;
        selectElement.html($("<option value=''>").html("Select Patient (" + count + ")"));
        Object.values(response.data).forEach(function (patient) {
            var mname;
            if ((patient.mname != "") && (patient.mname != null) && (patient.mname != undefined)) {
                mname = patient.mname;
            } else {
                mname = "";
            }
            $("<option>").val(patient.id).html(patient.fname + " " + mname + " " + patient.lname + ", DOB: " + moment(patient.dob).format('MM-DD-YYYY')).appendTo(selectElement);
            // $("<option>").val(patient.id).html(patient.fname + " " + mname + " " +patient.lname + ", DOB: " + viewsDateFormat(patient.dob)).appendTo(selectElement);
        });
        if (selectedPatients) {
            selectElement.val(selectedPatients);
        }
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var getRpmProviderPatientList = function (providerId, selectElement, selectedPatients = null) {


    // if (!practiceId) {
    //     return;
    // }

    axios({
        method: "GET",

        url: `/patients/ajax/rpmproviderpatientlist/${providerId}/providerpatientlist`,
    }).then(function (response) {
        count = (response.data).length;
        selectElement.html($("<option value=''>").html("Select Patient (" + count + ")"));
        Object.values(response.data).forEach(function (patient) {
            var mname;
            if ((patient.mname != "") && (patient.mname != null) && (patient.mname != undefined)) {
                mname = patient.mname;
            } else {
                mname = "";
            }
            $("<option>").val(patient.id).html(patient.fname + " " + mname + " " + patient.lname + ", DOB: " + moment(patient.dob).format('MM-DD-YYYY')).appendTo(selectElement);
            // $("<option>").val(patient.id).html(patient.fname + " " + mname + " " +patient.lname + ", DOB: " + viewsDateFormat(patient.dob)).appendTo(selectElement);
        });
        if (selectedPatients) {
            selectElement.val(selectedPatients);
        }
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var getPatientList = function (practiceId, selectElement, selectedPatients = null) {
    selectElement.html($("<option value=''>").html("Select Patient"));
    // if (!practiceId) {
    //     return;
    // }

    axios({
        method: "GET",
        //url: `/patients/ajax/practice/${practiceId}/${moduleId}/patient`
        url: `/patients/ajax/patientlist/${practiceId}/patientlist`,
    }).then(function (response) {
        Object.values(response.data).forEach(function (patient) {
            var mname;
            if ((patient.mname != "") && (patient.mname != null) && (patient.mname != undefined)) {
                mname = patient.mname;
            } else {
                mname = "";
            }
            $("<option>").val(patient.id).html(patient.fname + " " + mname + " " + patient.lname + ", DOB: " + moment(patient.dob).format('MM-DD-YYYY')).appendTo(selectElement);
            // $("<option>").val(patient.id).html(patient.fname + " " + mname + " " +patient.lname + ", DOB: " + viewsDateFormat(patient.dob)).appendTo(selectElement);
        });
        if (selectedPatients) {
            selectElement.val(selectedPatients);
        }
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

/**
 * Update the list of call ccript to select from
 *
 * @param {Integer}       callStatus
 * @param {jQuery Object} selectElement
 * @param {Integer}       selectedPatients
 */
var getCallScripts = function (callStatus, selectElement, selectedPatients = null) {
    if (callStatus == 1) {
        selectElement.html($("<option>").html("Select Call Script"));
        axios({
            method: "GET",
            url: `/ccm/get-call-scripts`,
        }).then(function (response) {
            Object.values(response.data).forEach(function (callScripts) {
                $("<option>").val(callScripts.id).html(callScripts.content_title).appendTo(selectElement);
            });
            if (selectedPatients) {
                selectElement.val(selectedPatients);
            }
        }).catch(function (error) {
            console.error(error, error.response);
        });
    } else {
        alert('not answered')
        selectElement.html($("<option>").html("Select Templatesssss"));
        // $.ajax({
        //     type: 'Get',
        //     url: '/ccm/fetchContentForMonthlyMonitoring',
        //     data: {template_type_id:template_type_id },
        //     success: function (response) {
        //          document.getElementById("content_title").innerHTML=response; 
        //     }
        // }); 
        axios({
            method: "GET",
            url: `/ccm/fetchContentForMonthlyMonitoring`,
        }).then(function (response) {
            // alert(jQuery.parseJSON(response.data));
            Object.values(response.data).forEach(function (fetchContent) {
                $("<option>").val(fetchContent.id).html(fetchContent.content_title).appendTo(selectElement);
            });
            if (selectedPatients) {
                selectElement.val(selectedPatients);
            }
        }).catch(function (error) {
            console.error(error, error.response);
        });
    }
    //
};

var updateCaremanagerList = function (practiceId, selectElement, selectedMedication = null) {
    $(selectElement)
        .empty()
        .append('<option value="">Select Care Manager</option>');
    axios({
        method: "GET",
        url: `/task-management/ajax/caremanager/${practiceId}/caremanagerlist`
    }).then(function (response) {
        Object.values(response.data).forEach(function (caremanager) {
            $("<option>").val(caremanager.id).html(caremanager.f_name + " " + caremanager.l_name).appendTo(selectElement);
        });
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var updateDocsOtherList = function (selectElement, selectdocument = null) {
    //debugger;
    $(selectElement)
        .empty()
        .append('<option value="">Select Document Type</option> <option value="0">Other</option>');
    axios({
        method: "GET",
        url: `/org/ajax/document/list`
    }).then(function (response) {
        Object.values(response.data).forEach(function (document) {
            $("<option>").val(document.doc_type).html(document.doc_type).appendTo(selectElement);
        });
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

/**
 * Update the list of patients to select from
 *
 * @param {Integer}       callScriptId
 * @param {jQuery Object} selectElement
 * @param {Integer}       selectedCallScript
 */
var getCallScriptsById = function (callScriptId, selectElement = null, selectTemplateTypeIdElement = null, selectTemplateTitleElement = null, emailSubject = null) {
    //    console.log(callScriptId,selectElement,selectTemplateTypeIdElement,selectTemplateTitleElement);
    //let str = "How are you doing today?";

    if (callScriptId != 'undefined' && callScriptId != null && callScriptId != "") {
        //console.log('testing data ff ' + uid);
        if ($("[name='patient_id']").length) {
            var uid = $("[name='patient_id']").val();
            //alert(uid);
            if (uid != 'undefined' && uid != null && uid != "") {
                axios({
                    method: "GET",
                    url: `/ccm/get-call-scripts-by-id/${callScriptId}/${uid}/call-script`,
                }).then(function (response) {
                    if (response.data[0].hasOwnProperty('content')) {
                        var script = response.data.finaldata;
                        var script = script.replace(/(<([^>]+)>)/ig, '');
                        var script = script.replace(/&nbsp;/g, ' ');
                        var script = script.replace(/&amp;/g, '&');
                        var subject = jQuery.parseJSON(response.data[0].content).subject;
                        if (selectElement) {
                            var pra = "<?php echo $provider_data['practice_name']; ?>";
                            $(selectElement).text(script);
                        }
                        if (emailSubject) {
                        }
                    }
                    if (response.data[0].hasOwnProperty('template_type_id')) {
                        var templateTypeId = jQuery.parseJSON(response.data[0].template_type_id);
                        // console.log("templateTypeId" + templateTypeId);
                        if (selectTemplateTypeIdElement) {
                            $(selectTemplateTypeIdElement).val(templateTypeId);
                        }
                    }
                    if (response.data[0].hasOwnProperty('content_title')) {
                        var contentTitle = response.data[0].content_title;
                        // console.log("contentTitle" + contentTitle);
                        if (selectTemplateTitleElement) {
                            $(selectTemplateTitleElement).val(contentTitle);
                        }
                    }
                }).catch(function (error) {
                    console.error(error, error.response);
                });
            }
        } else {
            return;
        }
    } else {
        if (selectElement) {
            $(selectElement).html("");
        }
        if (emailSubject) {
            $(emailSubject).val("");
        }
        if (selectTemplateTypeIdElement) {
            $(selectTemplateTypeIdElement).val("");
        }
        if (selectTemplateTitleElement) {
            $(selectTemplateTitleElement).val("");
        }
    }
};

var getEmailScriptsBYId = function (callScriptId, selectElement = null, selectTemplateTypeIdElement = null, selectTemplateTitleElement = null, emailSubject = null, emailFrom = null) {
    if (callScriptId != 'undefined' && callScriptId != null && callScriptId != "") {
        var uid = $("[name='patient_id']").val();
        if (uid != 'undefined' && uid != null && uid != "") {
            axios({
                method: "GET",
                url: `/ccm/get-call-scripts-by-id/${callScriptId}/${uid}/call-script`,
            }).then(function (response) {
                if (response.data[0].hasOwnProperty('content')) {
                    var script = response.data.finaldata;
                    var script = script.replace(/(<([^>]+)>)/ig, '');
                    var subject = jQuery.parseJSON(response.data[0].content).subject;
                    var from = jQuery.parseJSON(response.data[0].content).from;
                    // console.log(from + 'we finally gating this ' + subject);
                    $("#email_from").val(from);
                    $("#email_sub").val(subject);
                    if (selectElement) {
                        var pra = "<?php echo $provider_data['practice_name']; ?>";
                        $(selectElement).text(script);
                    }

                }
                if (response.data[0].hasOwnProperty('template_type_id')) {
                    var templateTypeId = jQuery.parseJSON(response.data[0].template_type_id);
                    if (selectTemplateTypeIdElement) {
                        $(selectTemplateTypeIdElement).val(templateTypeId);
                    }
                }
                if (response.data[0].hasOwnProperty('content_title')) {
                    var contentTitle = response.data[0].content_title;
                    //console.log("contentTitle" + contentTitle);
                    if (selectTemplateTitleElement) {
                        $(selectTemplateTitleElement).val(contentTitle);
                    }
                }
            }).catch(function (error) {
                console.error(error, error.response);
            });
        }
    } else {
        if (selectElement) {
            $(selectElement).html("");
        }
        if (emailSubject) {
            $(emailSubject).val("");
        }
        if (selectTemplateTypeIdElement) {
            $(selectTemplateTypeIdElement).val("");
        }
        if (selectTemplateTitleElement) {
            $(selectTemplateTitleElement).val("");
        }
    }
};
/**
 * Update the list of patients to select from
 *
 * @param {Integer}       callScriptId
 * @param {jQuery Object} selectElement
 * @param {Integer}       selectedCallScript
 */
// var getQuestionnaireScript = function(moduleId, subModuleId, stageId, stepId, selectElement = null) {
//     axios({
//         method: "GET",
//         url: `/org/get-dynamic-template/${moduleId}/${subModuleId}/${stageId}/${stepId}/questionnaire-template`,
//     }).then(function (response) {
//         var template_type_id = JSON.stringify(response.data.template_type_id);
//         var question_data    = JSON.stringify(response.data.question);
//         var $q_arr           = JSON.stringify(question_data.question.q);
//         console.log("template_type_id=>"+template_type_id);
//         console.log("question_data=>"+question_data);
//         console.log("q_arr=>"+q_arr);
//         // if (response.data[0].hasOwnProperty('content')) { 
//         //     var script         = jQuery.parseJSON(response.data[0].content).message;
//         //     if (selectElement) {
//         //         $(selectElement).html($(script).text());
//         //     }
//         // }
//     }).catch(function (error) {
//         console.error(error, error.response);
//     });
// };

function stepWizard(cl) {
    tsf1 = $('.' + cl).tsfWizard({
        stepEffect: 'basic',
        stepStyle: 'style12',
        navPosition: 'left',
        stepTransition: true,
        showButtons: true,
        showStepNum: true,
        height: '300px',
        onBeforeNextButtonClick: function (e, validation) {
            // console.log('onBeforeNextButtonClick');
            // console.log(validation);
            //for return please write below code
            //  e.preventDefault();
        },
        onAfterNextButtonClick: function (e, from, to, validation) {
            //console.log('onAfterNextButtonClick');
        },
        onBeforePrevButtonClick: function (e, from, to) {
            // console.log('onBeforePrevButtonClick');
            // console.log('from ' + from + ' to ' + to);
            //  e.preventDefault();
        },
        onAfterPrevButtonClick: function (e, from, to) {
            //console.log('onAfterPrevButtonClick');
            //console.log('validation ' + from + ' to ' + to);
        },
        onBeforeFinishButtonClick: function (e, validation) {
            //console.log('onBeforeFinishButtonClick');
            //console.log('validation ' + validation);
            //e.preventDefault();
        },
        onAfterFinishButtonClick: function (e, validation) {
            //console.log('onAfterFinishButtonClick');
            //console.log('validation ' + validation);
        }
    });
}

function stepWizardHorizontal(cl) {
    tsf1 = $('#' + cl).tsfWizard({
        stepEffect: 'basic',
        stepStyle: 'style12',
        navPosition: 'top',
        stepTransition: true,
        showButtons: true,
        showStepNum: true,
        height: '300px',
        onBeforeNextButtonClick: function (e, validation) {
            // console.log('onBeforeNextButtonClick');
            //console.log(validation);
            //for return please write below code
            //  e.preventDefault();
        },
        onAfterNextButtonClick: function (e, from, to, validation) {
            //console.log('onAfterNextButtonClick');
        },
        onBeforePrevButtonClick: function (e, from, to) {
            //console.log('onBeforePrevButtonClick');
            //console.log('from ' + from + ' to ' + to);
            //  e.preventDefault();
        },
        onAfterPrevButtonClick: function (e, from, to) {
            //console.log('onAfterPrevButtonClick');
            //console.log('validation ' + from + ' to ' + to);
        },
        onBeforeFinishButtonClick: function (e, validation) {
            //console.log('onBeforeFinishButtonClick');
            //console.log('validation ' + validation);
            //e.preventDefault();
        },
        onAfterFinishButtonClick: function (e, validation) {
            //console.log('onAfterFinishButtonClick');
            //console.log('validation ' + validation);
        }
    });

}

/**
 * Update the list of sub modules to select from
 *
 * @param {Integer}       moduleId
 * @param {jQuery Object} selectElement
 * @param {Integer}       selectedSubModules
 */
var updateSubModuleList = function (moduleId, selectElement, selectedSubModules = null) {
    $(selectElement)
        .empty()
        .append('<option value="">Select Modules</option> <option value="0">None</option>');
    // selectElement.html($("<option>").html("Select Sub Module"));
    if (!moduleId) {
        return;
    }
    axios({
        method: "GET",
        url: `/org/ajax/sub-module/${moduleId}/sub-module`,
    }).then(function (response) {
        Object.values(response.data).forEach(function (subModules) {
            $("<option>").val(subModules.id).html(subModules.components).appendTo(selectElement);
        });

        if (selectedSubModules) {
            selectElement.val(selectedSubModules);
        }

    }).catch(function (error) {
        console.error(error, error.response);
    });
};

/**
 * Update the list of stages to select from
 *
 * @param {Integer}       subModuleId
 * @param {jQuery Object} selectElement
 * @param {Integer}       selectedStages
 */
var updateStageList = function (subModuleId, selectElement, selectedStages = null) {
    selectElement.html($("<option>").html("Select Stage"));
    if (!subModuleId) {
        return;
    }
    axios({
        method: "GET",
        url: `/org/ajax/stages/${subModuleId}/stages`,
    }).then(function (response) {
        Object.values(response.data).forEach(function (stages) {
            $("<option>").val(stages.id).html(stages.description).appendTo(selectElement);
        });
        if (selectedStages) {
            selectElement.val(selectedStages);
        }
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

/**
 * Update the list of stage codes to select from
 *
 * @param {Integer}       stageId
 * @param {jQuery Object} selectElement
 * @param {Integer}       selectedStageCodes
 */
var updateTimer = function (patientID, billable, moduleId) {
    axios({
        method: "GET",
        url: `/system/get-updated-time/${patientID}/${billable}/${moduleId}/total-time`,
    }).then(function (response) {
        if (billable == 1) {
            $(".last_time_spend").html(response.data['billable_time']);
        } else {
            $(".non_billabel_last_time_spend").html(response.data['non_billable_time']);
        }
        $("#time-containers").html(response.data['total_time']);
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var updateFinalizeDate = function (patientID, moduleId) {
    axios({
        method: "GET",
        url: `/system/get-finalize-date/${patientID}/${moduleId}/finalize-date`,
    }).then(function (response) {

        $(".finalized_date").html(response.data);

    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var updateStageCodeList = function (stageId, selectElement, selectedStageCodes = null) {
    selectElement.html($("<option>").html("Select Step"));
    if (!stageId) {
        return;
    }
    axios({
        method: "GET",
        url: `/org/ajax/stage_codes/${stageId}/stage_codes`,
    }).then(function (response) {
        $("<option>").val("0").html("None").appendTo(selectElement);
        Object.values(response.data).forEach(function (stages) {
            $("<option>").val(stages.id).html(stages.description).appendTo(selectElement);
        });
        if (selectedStageCodes) {
            selectElement.val(selectedStageCodes);
        }
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var updateTemplateList = function (moduleId, stepId, type, selectElement) {
    $("#template-list").html("");
    axios({
        method: "GET",
        url: `/org/get-template/${moduleId}/${stepId}/${type}/stepWise`,
    }).then(function (response) {
        Object.values(response.data).forEach(function (stages) {
            // $("<option>").val(stages.id).html(stages.description).appendTo(selectElement);
            $("#template-list").append('<li><label for="' + stages.id + '" class="checkbox checkbox-primary mr-3"><input type="checkbox" name="template[copy][' + stages.id + ']" id="' + stages.id + '" value="1"  formControlName="checkbox" /><span>' + stages.content_title + '</span><span class="checkmark"></span></label></li>');
        });
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

// /**
//  * Update the list of patients to select from
//  *
//  * @param {Integer}       template
//  * @param {jQuery Object} selectElement
//  * @param {Integer}       selectedTemplate
//  */
// var getTemplates = function(template, selectElement, selectedTemplate= null) {
//     axios({
//         method: "GET",
//         url: `/ccm/get-templates`,
//     }).then(function (response) {
//         Object.values(response.data).forEach(function(callScripts) {
//             $("<option>").val(callScripts.id).html(callScripts.content_title).appendTo(selectElement);
//         });
//         if (selectedTemplate) {
//             selectElement.val(selectedTemplate);
//         }
//     }).catch(function (error) {
//         console.error(error, error.response);
//     });
// };
var gatCaretoolData = function (patientId, moduleId) {
    axios({
        method: "GET",
        url: `/patients/patient-caretool/${patientId}/${moduleId}/caretool`,
    }).then(function (response) {
        //console.log(response.data);
        $("#patientCaretoolData").html(response.data);
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var updateTemplateLists = function (module, subModules, selectElement, templateId) {
    selectElement.html($("<option>").html("Select Template"));
    axios({
        method: "GET",
        url: `/org/ajax/template/${module}/${subModules}/${templateId}/list`,
    }).then(function (response) {
        Object.values(response.data).forEach(function (template) {
            $("<option>").val(template.id).html(template.content_title).appendTo(selectElement);
        });
    }).catch(function (error) {
        console.error(error, error.response);
    });
};


var updatePracticeListWithoutOther = function (caremanager, selectElement, selectedPractice = null) {
    //  alert(caremanager);  
    //selectElement.html($("<option value=''>").html("Select Physician"));
    $(selectElement)
        .empty()
        .append('<option value="">Select Practice</option>');
    if (!caremanager) {
        return;
    }
    axios({
        method: "GET",
        url: `/org/ajax/caremanager/${caremanager}/practice`,
    }).then(function (response) {
        $("<option>").val('0').html('None').appendTo(selectElement);
        Object.values(response.data).forEach(function (practice) {
            $("<option>").val(practice.id).html(practice.name + " " + practice.location + " " + "(" + practice.count + ")").appendTo(selectElement);
        });
        if (selectedPractice) {
            selectElement.val(selectedPractice);
        }
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var updatePracticeListOnEmr = function (emr, selectElement, selectedPractice = null) {
    // alert(emr);  
    //selectElement.html($("<option value=''>").html("Select Physician"));
    $(selectElement)
        .empty()
        .append('<option value="">Select Practice</option>');
    if (!emr) {
        return;
    }
    axios({
        method: "GET",
        url: `/patients/ajax/practicelist/${emr}/practicelist`,
    }).then(function (response) {
        $("<option>").val('0').html('None').appendTo(selectElement);
        Object.values(response.data).forEach(function (practice) {
            $("<option>").val(practice.id).html(practice.name + " " + practice.location + " " + "(" + practice.count + ")").appendTo(selectElement);
        });
        if (selectedPractice) {
            selectElement.val(selectedPractice);
        }
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var getEmrOnPractice = function (practiceid, selectElement, patientid = null, selectedPractice = null) {
    // alert(emr);  
    //selectElement.html($("<option value=''>").html("Select Physician"));
    $(selectElement)
        .empty()
        .append('<option value="">Select EMR</option>');
    if (!practiceid) {
        return;
    }
    axios({
        method: "GET",
        url: `/patients/ajax/emrlist/${practiceid}/${patientid}`,
    }).then(function (response) {
        // $("<option>").val('0').html('None').appendTo(selectElement);
        Object.values(response.data).forEach(function (emr) {
            $("<option>").val(emr.practice_emr).html(emr.practice_emr).appendTo(selectElement);
        });
        if (selectedPractice) {
            selectElement.val(selectedPractice);
        }
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var updatePatientListOnEmr = function (emr, practiceId, module_id, selectElement, selectedPatients = null) {
    // alert(emr);  
    if (isNaN(practiceId)) {
        practiceId = null;
    }
    if (isNaN(module_id)) {
        return;
    }
    $(selectElement)
        .empty()
        .append('<option value="">Select Patient</option>');
    if (!emr) {
        return;
    }
    axios({
        method: "GET",
        url: `/patients/ajax/patientlist/${emr}/${practiceId}/${module_id}/patientlist`,
    }).then(function (response) {
        count = (response.data).length;
        selectElement.html($("<option value=''>").html("Select Patient (" + count + ")"));
        Object.values(response.data).forEach(function (patient) {
            var mname;
            if ((patient.mname != "") && (patient.mname != null) && (patient.mname != undefined)) {
                mname = patient.mname;
            } else {
                mname = "";
            }
            $("<option>").val(patient.id).html(patient.fname + " " + mname + " " + patient.lname + ", DOB: " + moment(patient.dob).format('MM-DD-YYYY')).appendTo(selectElement);
            // $("<option>").val(patient.id).html(patient.fname + " " + mname + " " +patient.lname + ", DOB: " + viewsDateFormat(patient.dob)).appendTo(selectElement);
        });
        if (selectedPatients) {
            selectElement.val(selectedPatients);
        }
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var getPatientRelationshipBuilding = function (patientId) {
    axios({
        method: "GET",
        url: `/ccm/patient-relationship-building/${patientId}/patient_relationship_building`,
    }).then(function (response) {
        var data = $.trim(response.data);
        $("#patient-relationship-building").html(data);
        // $("textarea[name='patient_relationship_building']").text(data);
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var getRelationBuild = function (patientId) {
    axios({
        method: "GET",
        url: `/ccm/patient-relationship-building/${patientId}/relation_patient_relationship_building`,
    }).then(function (response) {
        var data = $.trim(response.data);
        $("#patient_build").html(data);
        // $("textarea[name='patient_relationship_building']").text(data);
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var getPatientStatus = function (patientId, moduleId) {
    axios({
        method: "GET",
        url: `/patients/patient-status/${patientId}/${moduleId}/status`,
    }).then(function (response) {
        //console.log(response.data);
        $("#status_blockcontent").html(response.data);
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

// var getPatientDetailsModel = function (patientId, moduleId) {
//     axios({
//         method: "GET", 
//         url: `/patients/patient_details_model/${patientId}/${moduleId}/patient_details_model`,
//     }).then(function (response){
//         // console.log(response.data + 'patient-Ajaxdetails');
//         $("#patient-Ajaxdetails-model").html(response.data);
//     }).catch(function (error) {
//         console.error(error, error.response);
//     }); 
// }; 

var getPatientEnrollModule = function (patientId, selmoduleId) {
    axios({
        method: "GET",
        url: `/patients/patient-module/${patientId}/patient-module`,
    }).then(function (response) {
        $('.enrolledservice_modules').html('');
        enr = response.data;
        count_enroll = enr.length;
        for (var i = 0; i < count_enroll; i++) {
            $('.enrolledservice_modules').append(`<option value="${response.data[i].module_id}">${response.data[i].module.module}</option>`);
        }
        $("#enrolledservice_modules").val(selmoduleId).trigger('change');
        // $("#patient-Ajaxdetails-model").html(response.data); 
    }).catch(function (error) {
        console.error(error, error.response);
    });
};


var getPatientDetails = function (patientId, moduleId) {
    axios({
        method: "GET",
        url: `/patients/patient-details/${patientId}/${moduleId}/patient-details`,
    }).then(function (response) {
        // ptient enrolleed module in change status
        $('.enrolledservice_modules').html('');
        enr = response.data.patient_services.length;
        // alert(response.data.patient_services[0].module.module); 


        for (i = 0; i < enr; i++) {
            // alert(i); 
            // var module_name = response.data.patient[0].patient_services[i].module.module;
            var module_id = response.data.patient_services[i].module_id;
            $('.enrolledservice_modules').append(`<option value="${module_id}">${response.data.patient_services[i].module.module}</option>`);
        }
        // 
        count_enroll_Services = response.data.patient_services.length;
        var enroll_services = [];
        var enroll_services_status = [];
        for (i = 0; i < count_enroll_Services; i++) {
            enroll_services_status = response.data.patient_services[i].status;
            if (enroll_services_status == 0) {
                patient_enroll_services_status = '<i class="i-Closee i-Close" id="isuspended" data-toggle="tooltip" data-placement="top" data-original-title="Suspended"></i>';
            } else if (enroll_services_status == 1) {
                patient_enroll_services_status = '<i class="i-Yess i-Yes" id="iactive" data-toggle="tooltip" data-placement="top" data-original-title="Activate"></i>';
            } else if (enroll_services_status == 2) {
                patient_enroll_services_status = '<i class="i-Closee i-Close" id="ideactive" data-toggle="tooltip" data-placement="top" data-original-title="Deactivate"></i>';
            } else if (enroll_services_status == 3) {
                patient_enroll_services_status = '<i class="i-Closee i-Close" id="ideceased" data-toggle="tooltip" data-placement="top" data-original-title="Deceased"></i>';
            }
            enroll_services[i] = response.data.patient_services[i].module.module + "-" + patient_enroll_services_status;
            if (response.data.patient_services[i].module.module == 'RPM') {
                $("#add_patient_devices").show();
            }
        }
        console.log(enroll_services + 'enroll_services')
        $(".patient_enroll_services").html(enroll_services.toString());

        //dropdown for change modules in Change Patient Status priya 14 04 2023


        //changes for devices button ashwini mali 02 02 2023
        /*var devicesdata = enroll_services[0];
        var devicesdata1 = enroll_services[1];
        if(devicesdata == 'RPM'){ 
              $("#add_patient_devices").show();
        }else{
            if(devicesdata1 == 'RPM'){
                $("#add_patient_devices").show();
            }else{
                $("#add_patient_devices").hide(); 
            }

        }*/

        //end

        //patient profile picture
        var path = response.data.patient[0].profile_img;
        var default_img_path = window.location.origin + '/assets/images/faces/avatar.png';
        var img = "";
        if (path) {
            img = "<img src='" + path + "' class='user-image' style='width: 60px;' />";
        } else {
            img = "<img src='" + default_img_path + "' class='user-image' style='width: 60px;' />";
        }
        $(".patient_img").html(img);

        //second column
        var fname = '';
        if (response.data.patient[0].fname != '') {
            var fname = response.data.patient[0].fname;
        }
        var lname = '';
        if (response.data.patient[0].lname != '') {
            var lname = response.data.patient[0].lname;
        }
        $(".patient_name").text(fname + ' ' + lname);

        if (response.data.gender == "1") {
            var gender = "Female /";
        } else if (response.data.gender == "2") {
            var gender = "Male /";
        } else {
            var gender = "";
        }
        var mmddyydob = moment(response.data.patient[0].dob).format('MM-DD-YYYY');
        $(".patient_gender").text(gender + '(' + response.data.age + ')');
        $(".patient_dob").text(mmddyydob);

        if (response.data.patient[0].fin_number != '') {
            var fin_number = response.data.patient[0].fin_number;
        }
        $(".patient_fin_number").text(fin_number);

        if (response.data.military_status == "0") {
            var military_status = "Veteran Service - Yes";
        } else if (response.data.military_status == "1") {
            var military_status = "Veteran Service - No";
        } else {
            var military_status = "Veteran Service - Unknown";
        }
        $(".patient_vateran_service").html(military_status);

        //third column
        var home_num = '';
        var mob = '';
        if (response.data.patient[0].home_number != '' && response.data.patient[0].home_number != null) {
            var home_num = response.data.patient[0].home_number;
        }
        if (response.data.patient[0].mob != '' && response.data.patient[0].mob != null) {
            var mob = response.data.patient[0].mob;
        }
        $(".patient_contact_num").text(mob + '|' + home_num);

        if (response.data.consent_to_text == "1") {
            consent_to_text = "Consent to text - Yes";
        } else {
            consent_to_text = "Consent to text - No";
        }
        $(".patient_concent_to_text").html(consent_to_text);

        var add1 = ''; var add2 = ''; var city = ''; var state = ''; var zipcode = '';
        if (response.data.add_1) {
            var add1 = response.data.add_1;
        }
        if (response.data.add_2) {
            var add2 = ', ' + response.data.add_2;
        }
        if (response.data.city) {
            var city = ', ' + response.data.city;
        }
        if (response.data.state) {
            var state = ', ' + response.data.state;
        };
        if (response.data.zipcode) {
            var zipcode = ', ' + response.data.zipcode;
        };
        $(".patient_address").text(add1 + add2 + state + city + zipcode);

        //forth column
        var practice = '';
        if (response.data.practice_name != '') {
            practice = response.data.practice_name;
        }
        $(".patient_practice").text(practice);

        var provider = '';
        if (response.data.provider_name != '') {
            provider = response.data.provider_name;
        }
        $(".patient_provider").html(provider);

        var practice_emr = '';
        if (response.data.practice_emr != '') {
            practice_emr = response.data.practice_emr;
        }
        $(".patient_practice_emr").html(practice_emr);

        var assignCM = '';
        if (response.data.caremanager_name != '') {
            assignCM = response.data.caremanager_name;
        }
        $(".patient_assign_cm").text(assignCM);

        //fift column
        if (response.data.allreadydevice == 0) {
            $(".patient_add_device").hide();
        } else {
            $(".patient_add_device").show();
        }
        $("#vateran_service_title").html(military_status);

        var patient_status = '';
        var fromDate = '';
        var toDate = '';
        var statusDate;
        if (response.data.suspended_to != '') {
            toDate = response.data.suspended_to;
        }
        if (response.data.suspended_from != '') {
            fromDate = response.data.suspended_from;
        }

        if (response.data.hasOwnProperty("patient_enroll_date") && response.data.patient_enroll_date.length > 0) {
            if (response.data.patient_enroll_date[0].status == 1) {
                patient_status = 'Active';
                statusDate = "";
                // $('#serviceActiveId').html('<a href="javascript:void(0)" data-toggle="modal" style="margin-left: 15px;" class="ActiveDeactiveClass" data-target="#active-deactive" onClick=ccmcpdcommonJS.onActiveDeactiveClick(' + patientId + ',1)  id="active"><i class="i-Yess i-Yes" id="iactive" data-toggle="tooltip" data-placement="top" data-original-title="Activate"></i></a>');
                // $('.model-serviceActiveId').html('<a href="javascript:void(0)" data-toggle="modal" style="margin-left: 15px;" class="ActiveDeactiveClass" data-target="#active-deactive" onClick=ccmcpdcommonJS.onActiveDeactiveClick(' + patientId + ',1)  id="active"><i class="i-Yess i-Yes" id="iactive" data-toggle="tooltip" data-placement="top" data-original-title="Activate"></i></a>');
                $('.patient_service_active_id').html('<a href="javascript:void(0)" data-toggle="modal" style="margin-left: 15px;" class="ActiveDeactiveClass" data-target="#active-deactive" onClick=ccmcpdcommonJS.onActiveDeactiveClick(' + patientId + ',1)  id="active"><i class="i-Yess i-Yes" id="iactive" data-toggle="tooltip" data-placement="top" data-original-title="Activate"></i></a>');
            } else if (response.data.patient_enroll_date[0].status == 0) {
                patient_status = 'Suspended';
                statusDate = 'From' + fromDate + 'To' + toDate;
                // $('#serviceActiveId').html('<a href="javascript:void(0)" data-toggle="modal" style="margin-left: 15px;" class="ActiveDeactiveClass" data-target="#active-deactive"  onClick=ccmcpdcommonJS.onActiveDeactiveClick(' + patientId + ',0) id="suspend"><i class="i-Closee i-Close" id="isuspended" data-toggle="tooltip" data-placement="top" data-original-title="Suspended"></i></a>');
                // $('.model-serviceActiveId').html('<a href="javascript:void(0)" data-toggle="modal" style="margin-left: 15px;" class="ActiveDeactiveClass" data-target="#active-deactive"  onClick=ccmcpdcommonJS.onActiveDeactiveClick(' + patientId + ',0) id="suspend"><i class="i-Closee i-Close" id="isuspended" data-toggle="tooltip" data-placement="top" data-original-title="Suspended"></i></a>');
                $('.patient_service_active_id').html('<a href="javascript:void(0)" data-toggle="modal" style="margin-left: 15px;" class="ActiveDeactiveClass" data-target="#active-deactive"  onClick=ccmcpdcommonJS.onActiveDeactiveClick(' + patientId + ',0) id="suspend"><i class="i-Closee i-Close" id="isuspended" data-toggle="tooltip" data-placement="top" data-original-title="Suspended"></i></a>');
            } else if (response.data.patient_enroll_date[0].status == 2) {
                patient_status = 'Deactivated';
                statusDate = fromDate;
                // $('#serviceActiveId').html('<a href="javascript:void(0)" data-toggle="modal" style="margin-left: 15px;" class="ActiveDeactiveClass" data-target="#active-deactive" onClick=ccmcpdcommonJS.onActiveDeactiveClick(' + patientId + ',2) id="deactive"><i class="i-Closee i-Close" id="ideactive" data-toggle="tooltip" data-placement="top" data-original-title="Deactivate"></i></a>');
                // $('.model-serviceActiveId').html('<a href="javascript:void(0)" data-toggle="modal" style="margin-left: 15px;" class="ActiveDeactiveClass" data-target="#active-deactive" onClick=ccmcpdcommonJS.onActiveDeactiveClick(' + patientId + ',2) id="deactive"><i class="i-Closee i-Close" id="ideactive" data-toggle="tooltip" data-placement="top" data-original-title="Deactivate"></i></a>');
                $('.patient_service_active_id').html('<a href="javascript:void(0)" data-toggle="modal" style="margin-left: 15px;" class="ActiveDeactiveClass" data-target="#active-deactive" onClick=ccmcpdcommonJS.onActiveDeactiveClick(' + patientId + ',2) id="deactive"><i class="i-Closee i-Close" id="ideactive" data-toggle="tooltip" data-placement="top" data-original-title="Deactivate"></i></a>');
            } else if (response.data.patient_enroll_date[0].status == 3) {
                patient_status = 'Deceased';
                // $('#serviceActiveId').html('<a href="javascript:void(0)" data-toggle="modal" style="margin-left: 15px;" class="ActiveDeactiveClass"data-target="#active-deactive" onClick=ccmcpdcommonJS.onActiveDeactiveClick(' + patientId + ',3) id="deceased" ><i class="i-Closee i-Close" id="ideceased" data-toggle="tooltip" data-placement="top" data-original-title="Deceased"></i></a>');
                // $('.model-serviceActiveId').html('<a href="javascript:void(0)" data-toggle="modal" style="margin-left: 15px;" class="ActiveDeactiveClass"data-target="#active-deactive" onClick=ccmcpdcommonJS.onActiveDeactiveClick(' + patientId + ',3) id="deceased" ><i class="i-Closee i-Close" id="ideceased" data-toggle="tooltip" data-placement="top" data-original-title="Deceased"></i></a>');
                $('.patient_service_active_id').html('<a href="javascript:void(0)" data-toggle="modal" style="margin-left: 15px;" class="ActiveDeactiveClass"data-target="#active-deactive" onClick=ccmcpdcommonJS.onActiveDeactiveClick(' + patientId + ',3) id="deceased" ><i class="i-Closee i-Close" id="ideceased" data-toggle="tooltip" data-placement="top" data-original-title="Deceased"></i></a>');
            }
            // $("#pservice_status").html(patient_status);
            // $(".model-pservice_status").html(patient_status);
            $(".patient_service_status").html(patient_status);
        }
        $(".patient_status_date").text(statusDate);

        var enroll_date = '';
        if (response.data.date_enrolled != '') {
            enroll_date = response.data.date_enrolled;
        } else {
            enroll_date = '';
        }
        $(".patient_enroll_date").text(enroll_date);

        var device_code = '';
        if (response.data.device_code != '') {
            device_code = response.data.device_code;
        }
        $(".patient_device_code").text(device_code)

        var device_status = '';
        if (response.data.device_status != '') {
            shipping_status = response.data.device_status;
            if (shipping_status == 1) {
                device_status = 'Dispatched';
            } else if (shipping_status == 2) {
                device_status = 'Delivered';
            } else if (shipping_status == 3) {
                device_status = 'Pending';
            } else {
                device_status = '';
            }
        }

        $(".device_delivery_status").text(device_status);

        if (response.data.billable_time != '') {
            $("#btime").html(response.data.billable_time)
        }
        if (response.data.non_billabel_time != '') {
            $("#nbtime").html(response.data.non_billabel_time)
        }
        if (response.data.systolichigh != '') {
            var systolichigh = response.data.systolichigh;
        } else {
            var systolichigh = '';
        }
        $("#systolichigh").val(systolichigh);
        if (response.data.systoliclow != '') {
            var systoliclow = response.data.systoliclow;
        } else {
            var systoliclow = '';
        }
        $("#systoliclow").val(systoliclow);
        if (response.data.diastolichigh != '') {
            var diastolichigh = response.data.diastolichigh;
        } else {
            var diastolichigh = '';
        }
        $("#diastolichigh").val(diastolichigh);
        if (response.data.diastoliclow != '') {
            var diastoliclow = response.data.diastoliclow;
        } else {
            var diastoliclow = '';
        }
        $("#diastoliclow").val(diastoliclow);
        if (response.data.bpmhigh != '') {
            var bpmhigh = response.data.bpmhigh;
        } else {
            var bpmhigh = '';
        }
        $("#bpmhigh").val(bpmhigh);
        if (response.data.bpmlow != '') {
            var bpmlow = response.data.bpmlow;
        } else {
            var bpmlow = '';
        }
        $("#bpmlow").val(bpmlow);
        if (response.data.oxsathigh != '') {
            var oxsathigh = response.data.oxsathigh;
        } else {
            var oxsathigh = '';
        }
        $("#oxsathigh").val(oxsathigh);
        if (response.data.oxsatlow != '') {
            var oxsatlow = response.data.oxsatlow;
        } else {
            var oxsatlow = '';
        }
        $("#oxsatlow").val(oxsatlow);
        if (response.data.glucosehigh != '') {
            var glucosehigh = response.data.glucosehigh;
        } else {
            var glucosehigh = '';
        }
        $("#glucosehigh").val(glucosehigh);
        if (response.data.glucoselow != '') {
            var glucoselow = response.data.glucoselow;
        } else {
            var glucoselow = '';
        }
        $("#glucoselow").val(glucoselow);
        if (response.data.temperaturehigh != '') {
            var temperaturehigh = response.data.temperaturehigh;
        } else {
            var temperaturehigh = '';
        }
        $("#temperaturehigh").val(temperaturehigh);
        if (response.data.temperaturelow != '') {
            var temperaturelow = response.data.temperaturelow;
        } else {
            var temperaturelow = '';
        }
        $("#temperaturelow").val(temperaturelow);
        if (response.data.weighthigh != '') {
            var weighthigh = response.data.weighthigh;
        } else {
            var weighthigh = '';
        }
        $("#weighthigh").val(weighthigh);
        if (response.data.weightlow != '') {
            var weightlow = response.data.weightlow;
        } else {
            var weightlow = '';
        }
        $("#weightlow").val(weightlow);
        if (response.data.spirometerfevhigh != '') {
            var spirometerfevhigh = response.data.spirometerfevhigh;
        } else {
            var spirometerfevhigh = '';
        }
        $("#spirometerfevhigh").val(spirometerfevhigh);
        if (response.data.spirometerfevlow != '') {
            var spirometerfevlow = response.data.spirometerfevlow;
        } else {
            var spirometerfevlow = '';
        }
        $("#spirometerfevlow").val(spirometerfevlow);
        if (response.data.spirometerpefhigh != '') {
            var spirometerpefhigh = response.data.spirometerpefhigh;
        } else {
            var spirometerpefhigh = '';
        }
        $("#spirometerpefhigh").val(spirometerpefhigh);
        if (response.data.spirometerpeflow != '') {
            var spirometerpeflow = response.data.spirometerpeflow;
        } else {
            var spirometerpeflow = '';
        }
        $("#spirometerpeflow").val(spirometerpeflow);

        var personal_notes = '';
        if (response.data.personal_notes != '') {
            var personal_notes = response.data.personal_notes['static']['personal_notes'];
        }
        $("textarea#personal_notes").val(personal_notes);
        var research_study = '';
        if (response.data.research_study != '') {
            var research_study = response.data.research_study['static']['research_study'];
        }
        $("textarea#part_of_research_study").val(research_study);

        if ($('#contact_number').length) { // to  check id is exist on page(enrollment screen not  sending sms so not exist on screen)
            if (response.data.patient[0].mob != '' && response.data.patient[0].mob != null && (response.data.patient[0].primary_cell_phone == "1")) {
                var mob = response.data.patient[0].mob;
                var country_code = response.data.patient[0].country_code;
                $('#contact_number').append(`<option value="${country_code}${mob}">${mob} (P)</option>`);
            }
            if (response.data.patient[0].home_number != '' && response.data.patient[0].home_number != null && (response.data.patient[0].secondary_cell_phone == "1")) {
                var home_num = response.data.patient[0].home_number;
                var secondary_country_code = response.data.patient[0].secondary_country_code;
                $('#contact_number').append(`<option value="${secondary_country_code}${home_num}">${home_num} (S)</option>`);
            }
            if ((response.data.patient[0].primary_cell_phone == "0") && (response.data.patient[0].secondary_cell_phone == "0")) {
                var txt = "<div class='alert alert-warning mt-2 ml-2'>Patient Cell Number is Unavailable. Text Message cannot be sent to this patient.</div> <input type='hidden' name='error' value='Patient Cell Number is Unavailable. Text Message cannot be sent to this patient.'>";
                $('#patient_cell_number_unavailable').html(txt);
            }
        }

        if (response.data.patient_enroll_date[0].finalize_cpd == 0 && response.data.billable == 0 && response.data.enroll_in_rpm == 0 && module == 'care-plan-development') {
            $('input[name="billable"]').val(0);
        } else {
            $('input[name="billable"]').val(1);
        }

    }).catch(function (error) {
        console.error(error, error.response);
    });
};

// var getRelationshipRadioStatus = function(patientId, moduleId) {
//     axios({
//         method: "GET",
//         url: `/ccm/populateCarePlanDevelopmentrelationship/${patientId}/radiostatus`,
//     }).then(function (response) { 
//         //console.log(response.data);
//         $("#status_blockcontent").html(response.data);
//     }).catch(function (error) {
//         console.error(error, error.response);
//     });
// };

var getPatientCurrentMonthNotes = function (patientId, moduleId) {
    axios({
        method: "GET",
        url: `/ccm/current-month-status/${patientId}/${moduleId}/currentstatus`,
    }).then(function (response) {
        // console.log(response.data);
        $("#currentMonthData").html(response.data);
        // $("#condition_requirnment").val(response.data.condition_requirnment_notes);
        // $("#nov_notes").val(response.data.nov_notes); 
        // $("#nd_notes").val(response.data.nd_notes);
        // $("#med_added_or_discon_notes").val(response.data.med_added_or_discon_notes);
        // $("#report_requirnment_notes").val(response.data.report_requirnment_notes);
        // $("#ctd_notes").val(response.data.ctd_notes);
        //$("#anything_else").val(response.data.anything_else);
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var getPatientPreviousMonthNotes = function (patientId, moduleId, month, year) {
    // console.log("month");
    // console.log(month);
    axios({
        method: "GET",
        url: `/ccm/previous-month-status/${patientId}/${moduleId}/${month}/${year}/previousstatus`,
    }).then(function (response) {
        //console.log(response.data);
        $("#previousMonthData").html(response.data);
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var getPatientCareplanNotes = function (patientId, moduleId) {
    axios({
        method: "GET",
        url: `/ccm/careplan-status/${patientId}/${moduleId}/careplanstatus`,
    }).then(function (response) {
        //console.log(response.data);
        $("#careplan_blockcontent").html(response.data);
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var validateAndGenerateUid = function (fName, lName, dob, mName) {
    if ((fName != 'undefined' && fName != null && fName != "") && (lName != 'undefined' && lName != null && lName != "") && (dob != 'undefined' && dob != null && dob != "")) {
        axios({
            method: "POST",
            url: `/patients/patient-uid/validate`,
            data: {
                fName: fName,
                lName: lName,
                dob: dob,
                mName: mName
            }
        }).then(function (response) {
            //console.log("test " + response.data);
            if (response.data.error != undefined) {
                // console.log(response.data);
                $('form[name="patient_registration_form"] input[name="lname"]').addClass("is-invalid");
                $('form[name="patient_registration_form"] input[name="fname"]').addClass("is-invalid");
                $('form[name="patient_registration_form"] input[name="dob"]').addClass("is-invalid");
                $('form[name="patient_registration_form"] input[name="mname"]').addClass("is-invalid");
                $('form[name="patient_registration_form"]  #uid').next(".invalid-feedback").html(response.data.error);
            } else {
                $('form[name="patient_registration_form"] #uid').val(response.data.uid);
                $('form[name="patient_registration_form"] input[name="lname"]').removeClass("is-invalid");
                $('form[name="patient_registration_form"] input[name="fname"]').removeClass("is-invalid");
                $('form[name="patient_registration_form"] input[name="dob"]').removeClass("is-invalid");
                $('form[name="patient_registration_form"] input[name="mname"]').removeClass("is-invalid");
                $('form[name="patient_registration_form"]  #uid').next(".invalid-feedback").html('');
            }
        }).catch(function (error) {
            console.error(error, error.response);
        });
    }
};

var getFollowupListData = function (patientId, moduleId) {
    axios({
        method: "GET",
        url: `/ccm/patient-followup-task/${patientId}/${moduleId}/followuplist`,
    }).then(function (response) {
        $("#no_follow_up_list").html(response.data);
        $("#follow_up_list").html(response.data);
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var getToDoListData = function (patientId, moduleId) {
    axios({
        method: "GET",
        url: `/task-management/patient-to-do/${patientId}/${moduleId}/list`,
    }).then(function (response) {

        // console.log(response.data);
        $("#toDoList").html(response.data);
        //alert();
        $('.badge').html($('#count_todo').val());
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var getToDoListCalendarData = function (patient_id, module_id) {
    if (patient_id == '') {
        patient_id = 0;
    }
    if (module_id == '') {
        module_id = 0;
    }
    axios({
        method: "GET",
        url: `/org/calender/${patient_id}/${module_id}/cal`,
    }).then(function (response) {
        // console.log(response.data);
        $("#todo-calendar").html(response.data);
        //alert();
    }).catch(function (error) {
        console.error(error, error.response);
    });
};
// var getDataCalender = function (patientId, moduleId){
//   axios({
//         method: "GET",
//         url: `/getcaldata/${patientId}/${moduleId}/cal`,
//     }).then(function (response) {
//         // console.log(response.data);
//         $("#Calender").html(response.data);
//         //alert();
//         $('.badge').html($('#count_cal').val());
//     }).catch(function (error) {
//         console.error(error, error.response);
//     });  
// }
var logPauseTime = function (timerStart, patientId, moduleId, subModuleId, stageId, billable, uId, stepId, formName) {
    axios({
        method: "POST",
        url: `/system/log-time/time`,
        data: {
            timerStart: '00:00:00',
            timerEnd: '00:00:00',
            patientId: patientId,
            moduleId: moduleId,
            subModuleId: subModuleId,
            stageId: stageId,
            billable: billable,
            uId: uId,
            stepId: stepId,
            formName: formName,
            form_start_time: timerStart,
            pause_start_time: timerStart
        }
    }).then(function (response) {
        $("#timer_runing_status").val(0);
        $('.form_start_time').val(response.data.form_start_time);
        $("form").find(":submit").attr("disabled", false);
        $("form").find(":button").attr("disabled", false);
        pause_next_stop_flag = 0;
        setTimeout(function () {
            pause_stop_flag = 0;
            if (pause_next_stop_flag == 0) {
                updateTimeEveryMinutes(patientId, moduleId, response.data.form_start_time);
            }
        }, 60000);
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var logTimeManually = function (timerStart, timerEnd, patientId, moduleId, subModuleId, stageId, billable, uId, stepId, formName) {
    // alert('calling');
    var form_start_time = $('.form_start_time').val();
    pause_stop_flag = 1;
    pause_next_stop_flag = 1;
    $("#timer_runing_status").val(1);
    axios({
        method: "POST",
        url: `/system/log-time/time`,
        data: {
            timerStart: timerStart,
            timerEnd: timerEnd,
            patientId: patientId,
            moduleId: moduleId,
            subModuleId: subModuleId,
            stageId: stageId,
            billable: billable,
            uId: uId,
            stepId: stepId,
            formName: formName,
            form_start_time: form_start_time
        }
    }).then(function (response) {
        if (JSON.stringify(response.data.end_time) != "" && JSON.stringify(response.data.end_time) != null && JSON.stringify(response.data.end_time) != undefined) {
            $("#timer_start").val(response.data.end_time);
            $("#timer_end").val(response.data.end_time);
            updateTimer(patientId, billable, moduleId);
            $("form").find(":submit").attr("disabled", true);
            $("form").find(":button").attr("disabled", true);
            //$(".last_time_spend").html(response.data.end_time);
            $('.form_start_time').val(response.data.form_start_time);
            alert("Timer paused and Time Logged successfully.");
        } else {
            alert("Unable to log time, please try after some time.");
        }

    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var updateBmi = function (weight, height, selectElement) {
    var result = 0;
    try {
        // let weight = $("[name='vital_weight']").val();
        // let height = $("[name='vital_height']").val();
        result = weight / Math.pow(height, 2) * 703;
    } catch (e) {
        console.warn(e);
    }
    if (selectElement) {
        $(selectElement).val(result.toFixed(1));
    }
    // $("#bmi").val(result.toFixed(1));
};

var getPatientNetTimeBasedOnModule = function (patientId, moduleId, selectElement) {
    axios({
        method: "GET",
        url: `/system/patient-net-time/${patientId}/${moduleId}/time`,
    }).then(function (response) {
        var time = $.trim(response.data);
        if (selectElement) {
            $(selectElement).val(time);
        }
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

/**
 * Update the list of Medication to select from
 *
 * @param {Integer}       Medication
 * @param {jQuery Object} selectElement
 * @param {Integer}       selectedMedication
 */
var updateMedicationList = function (selectElement, selectedMedication = null) {
    $(selectElement)
        .empty()
        .append('<option value="">Select Medication</option> <option value="other">Other</option> <option value="0">None</option>');
    axios({
        method: "GET",
        url: `/org/ajax/medication/list`
    }).then(function (response) {
        Object.values(response.data).forEach(function (medication) {
            $("<option>").val(medication.id).html(medication.description).appendTo(selectElement);
        });
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var updateCpdProviderList = function (practiceid = null, selectElement, selectedProvider = null) {//comment by anand
    $(selectElement)
        .empty()
        .append('<option value="">Select Provider</option><option value="0">Other</option>');
    axios({
        method: "get",
        // data:{'practiceid':practiceid},
        url: `/org/ajax/provider/list/${practiceid}`,
    }).then(function (response) {
        Object.values(response.data).forEach(function (provider) {
            // console.log(provider.name+"test providername");
            $("<option>").val(provider.id).html(provider.name).appendTo(selectElement);
            // if(selectedProvider == provider.name){
            //     $("<option selected>").val(provider.id).html(provider.name).appendTo(selectElement);
            // } else {
            //     $("<option>").val(provider.id).html(provider.name).appendTo(selectElement); 
            // }
        });
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var updatePracticeList = function (selectElement, selectedPractice = null) {
    $(selectElement)
        .empty()
        .append('<option value="">Select Practice</option><option value="0">Other</option>');
    axios({
        method: "GET",
        url: `/org/ajax/practice/all-list`,
    }).then(function (response) {
        Object.values(response.data).forEach(function (practice) {
            // console.log(practice.name+"test practicename");
            $("<option>").val(practice.id).html(practice.name).appendTo(selectElement);
        });
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var updateRelationshipList = function (selectElement, selectedProvider = null) {
    $(selectElement)
        .empty()
        .append('<option value="">Select Relationship</option><option value="0">Other</option>');
    axios({
        method: "GET",
        url: `/patients/ajax/relationship/list`
    }).then(function (response) {
        Object.values(response.data).forEach(function (relative) {
            // console.log(relative.relationship + "test providername");
            $("<option>").val(relative.relationship).html(relative.relationship).appendTo(selectElement);

        });
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var getPracticelistaccordingtopracticegrp = function (practicegrpId, selectElement, selectedPractice = null) {
    // alert(practicegrpId);
    // console.log(selectElement);
    // selectElement.html($("<option value=''>").html("Select Physician"));
    $(selectElement)
        .empty()
        .append('<option value="">Select Practices</option>');
    if (!practicegrpId) {
        practicegrpId = null;

    }
    if (isNaN(practicegrpId)) {
        practicegrpId = null;
    }
    axios({
        method: "GET",
        url: `/org/ajax/practicegrp/${practicegrpId}/practice`,
    }).then(function (response) {
        $("<option>").val('0').html('None').appendTo(selectElement);
        Object.values(response.data).forEach(function (practice) {
            $("<option>").val(practice.id).html(practice.name + " " + "(" + practice.count + ")").appendTo(selectElement);
        });
        if (selectedPractice) {
            selectElement.val(selectedPractice);
        }
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var pateintdevicecode = function (id, selectElement, selecteddevice = null) {
    $(selectElement)
        .empty()
        .append('<option value="">Select Device</option>');
    if (!id) {
        id = null;

    }
    if (isNaN(id)) {
        id = null;
    }
    axios({
        method: "GET",
        url: `/reports/ajax/patientdevice/${id}/pateintdevice`,
    }).then(function (response) {
        console.log(response);
        // $("<option>").val('0').html('None').appendTo(selectElement);
        Object.values(response.data).forEach(function (pateint) {
            $("<option>").val(pateint.id).html(pateint.device_code).appendTo(selectElement);
        });
        if (selecteddevice) {
            selectElement.val(selecteddevice);
        }
    }).catch(function (error) {
        console.error(error, error.response);
    });
}

var getactivityPracticelistaccordingtopracticegrp = function (practicegrpId, selectElement, selectedPractice = null) {
    // alert(practicegrpId);
    // console.log(selectElement);

    $('#utilulpract')
        .empty()
        .append('<li value="">Select Practices</li>');
    if (!practicegrpId) {
        practicegrpId = null;

    }
    if (isNaN(practicegrpId)) {
        practicegrpId = null;
    }
    axios({
        method: "GET",
        url: `/org/ajax/practicegrp/${practicegrpId}/practice`,
    }).then(function (response) {
        // $("<option>").val('0').html('None').appendTo(selectElement);
        Object.values(response.data).forEach(function (practice) {

            $("<li>").val(practice.id).html("<input type='checkbox' value='' name ='activity[0][practices][" + practicegrpId + "][" + practice.id + "]' />" + practice.name + " " + "(" + practice.count + ")").appendTo($('#utilulpract'));
        });
        // $('.multiDrop').on('click', function (event) { 
        //     // alert("1"); 
        // event.stopPropagation();  
        // $(this).next('ul').slideToggle();
        // });
        // $(document).on('click', function () {
        //     if (!$(event.target).closest('.wrapMulDrop').length) {
        //         $('.wrapMulDrop ul').slideUp();
        //     }
        // });

        $('.wrapMulDrop ul li input[type="checkbox"]').on('change', function () {
            // alert("2");
            var x = $('.wrapMulDrop ul li input[type="checkbox"]:checked').length;
            if (x != "") {
                $('.multiDrop').html(x + "template" + " " + "selected");
            } else if (x < 1) {
                $('.multiDrop').html('Select Practices<i style="float:right;" class="icon ion-android-arrow-dropdown"></i>');
            }
        });
        // if (selectedPractice) {
        //     selectElement.val(selectedPractice);
        // }
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var getnewactivityPracticelistaccordingtopracticegrp = function (practicegrpId, selectElement, formCountId, selectElementid, selectedPractice = null) {
    // alert(practicegrpId); 
    // console.log(formCountId, selectElementid);

    formCountId = formCountId == 0 ? 0 : formCountId - 1;
    selectElementid = selectElementid == 0 ? 0 : selectElementid - 1;
    // console.log(formCountId, selectElement);
    $('#utilulpract_' + formCountId + '_' + selectElementid)
        .empty()
        .append('<li value="">Select Practices</li>');
    if (!practicegrpId) {
        practicegrpId = null;

    }
    if (isNaN(practicegrpId)) {
        practicegrpId = null;
    }
    axios({
        method: "GET",
        url: `/org/ajax/practicegrp/${practicegrpId}/practice`,
    }).then(function (response) {
        // $("<option>").val('0').html('None').appendTo(selectElement);
        Object.values(response.data).forEach(function (practice) {

            $("<li>").val(practice.id).html("<input type='checkbox' value='' name ='activity[" + formCountId + "][practices][" + practicegrpId + "][" + practice.id + "]' />" + practice.name + " " + "(" + practice.count + ")").appendTo($('#utilulpract_' + formCountId + '_' + selectElementid));
        });
        // $('#candy_'+formCountId+'_'+selectElementid).on('click', function (event) { 
        //     // alert("1"); 
        // event.stopPropagation();  
        // $(this).next('ul').slideToggle();
        // });

        // $(document).on('click', function () {
        //     if (!$(event.target).closest('#utilulpract_'+formCountId+'_'+selectElementid).length) {
        //         $('#utilulpract_'+formCountId+'_'+selectElementid+' ul').slideUp();
        //     }
        // });  

        $('#utilulpract_' + formCountId + '_' + selectElementid + ' ul li input[type="checkbox"]').on('change', function () {
            // alert("2");
            var x = $('#utilulpract_' + formCountId + '_' + selectElementid + ' ul li input[type="checkbox"]:checked').length;
            if (x != "") {
                $('#candy_' + formCountId + '_' + selectElementid).html(x + "template" + " " + "selected");
            } else if (x < 1) {
                $('#candy_' + formCountId + '_' + selectElementid).html('Select Practices<i style="float:right;" class="icon ion-android-arrow-dropdown"></i>');
            }
        });
        // if (selectedPractice) {
        //     selectElement.val(selectedPractice);
        // }
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var getappendPracticelistaccordingtopracticegrp = function (practicegrpId, selectElement, formCountId, selectElementid, selectedPractice = null) {
    // alert(practicegrpId); 
    // console.log(formCountId, selectElementid);
    // formCountId = formCountId-1;
    // selectElementid = selectElementid-1;
    // console.log(formCountId,selectElement);    
    $('#appendutilulpract_' + formCountId + '_' + selectElementid)
        .empty()
        .append('<li value="">Select Practices</li>');
    if (!practicegrpId) {
        practicegrpId = null;

    }
    if (isNaN(practicegrpId)) {
        practicegrpId = null;
    }
    axios({
        method: "GET",
        url: `/org/ajax/practicegrp/${practicegrpId}/practice`,
    }).then(function (response) {
        // $("<option>").val('0').html('None').appendTo(selectElement);
        Object.values(response.data).forEach(function (practice) {

            $("<li>").val(practice.id).html("<input type='checkbox' value='' name ='activity[" + formCountId + "][practices][" + practicegrpId + "][" + practice.id + "]' />" + practice.name + " " + "(" + practice.count + ")").appendTo($('#appendutilulpract_' + formCountId + '_' + selectElementid));
        });

        // $('#appendnewcandy_'+formCountId+'_'+selectElementid).on('click', function (event) { 
        //     // alert("1"); 
        // event.stopPropagation();  
        // $(this).next('ul').slideToggle();
        // });

        // $(document).on('click', function () {
        //     if (!$(event.target).closest('.wrapMulDrop').length) {
        //         $('.wrapMulDrop ul').slideUp();  
        //     }
        // });       

        $('.wrapMulDrop ul li input[type="checkbox"]').on('change', function () {
            // alert("2");
            var x = $('.wrapMulDrop ul li input[type="checkbox"]:checked').length;
            if (x != "") {
                $('#appendnewcandy_' + formCountId + '_' + selectElementid).html(x + "template" + " " + "selected");
            } else if (x < 1) {
                $('#appendnewcandy_' + formCountId + '_' + selectElementid).html('Select Practices<i style="float:right;" class="icon ion-android-arrow-dropdown"></i>');
            }
        });
        // if (selectedPractice) {
        //     selectElement.val(selectedPractice);
        // }
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var geteditappendPracticelistaccordingtopracticegrp = function (practicegrpId, selectElement, selectElementid, formlength, selectedPractice = null) {
    // alert(practicegrpId); 
    // console.log(formCountId,selectElementid);    
    // formCountId = formCountId-1;
    // selectElementid = selectElementid-1;
    // console.log(formCountId,selectElement);    


    $('#editappendul_' + selectElementid)
        .empty()
        .append('<li value="">Select Practices</li>');
    if (!practicegrpId) {
        practicegrpId = null;

    }
    if (isNaN(practicegrpId)) {
        practicegrpId = null;
    }
    axios({
        method: "GET",
        url: `/org/ajax/practicegrp/${practicegrpId}/practice`,
    }).then(function (response) {
        // $("<option>").val('0').html('None').appendTo(selectElement);
        Object.values(response.data).forEach(function (practice) {

            // $("<li>").val(practice.id).html("<input type='checkbox' value='' name ='editappendpracticeswithorg["+selectElementid+"][practices]["+practicegrpId+"][" +practice.id+"]' />"+practice.name + " " + "(" + practice.count + ")").appendTo($('#editappendul_'+selectElementid));
            //  $("<li>").val(practice.id).html("<input type='checkbox' value='' name ='activitypractice"+"["+"editappendpracticesnew_"+selectElementid+"]][practices]["+practicegrpId+"][" +practice.id+"]' />"+practice.name + " " + "(" + practice.count + ")").appendTo($('#editappendul_'+selectElementid));
            $("<li>").val(practice.id).html("<input type='checkbox'  value='' name ='editActivity[" + (formlength) + "]" + "[" + "editappendpracticesnew_" + formlength + "]][practices][" + practicegrpId + "][" + practice.id + "]' />" + practice.name + " " + "(" + practice.count + ")").appendTo($('#editappendul_' + selectElementid));

        });

        // $('#appendnewcandy_'+formCountId+'_'+selectElementid).on('click', function (event) { 
        //     // alert("1"); 
        // event.stopPropagation();  
        // $(this).next('ul').slideToggle();
        // });

        // $(document).on('click', function () {
        //     if (!$(event.target).closest('.wrapMulDrop').length) {
        //         $('.wrapMulDrop ul').slideUp();  
        //     }
        // });       

        $('.wrapMulDrop ul li input[type="checkbox"]').on('change', function () {
            // alert(selectElementid);
            var x = $('#editappendul_' + selectElementid + ' li input[type="checkbox"]:checked').length;
            alert(x);
            if (x != "") {
                $('#editappendcandy_' + selectElementid).html(x + " Practices" + " " + "selected");
            } else if (x < 1) {
                $('#editappendcandy_' + selectElementid).html('Select Practices<i style="float:right;" class="icon ion-android-arrow-dropdown"></i>');
            }
        });
        // if (selectedPractice) {
        //     selectElement.val(selectedPractice);
        // }
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var geteditPracticelistaccordingtopracticegrp = function (practicegrpId, selectElement, selectElementid, selectedPractice = null) {
    // alert(practicegrpId); 
    // console.log(formCountId,selectElementid);    
    // formCountId = formCountId-1;
    // selectElementid = selectElementid-1;
    // console.log(formCountId,selectElement);    


    $('#editul_' + selectElementid)
        .empty()
        .append('<li value="">Select Practices</li>');
    if (!practicegrpId) {
        practicegrpId = null;

    }
    if (isNaN(practicegrpId)) {
        practicegrpId = null;
    }
    axios({
        method: "GET",
        url: `/org/ajax/practicegrp/${practicegrpId}/practice`,
    }).then(function (response) {
        // $("<option>").val('0').html('None').appendTo(selectElement);
        Object.values(response.data).forEach(function (practice) {

            // $("<li>").val(practice.id).html("<input type='checkbox' value='' name ='practicesnew["+selectElementid+"][practices]["+practicegrpId+"][" +practice.id+"]' />"+practice.name + " " + "(" + practice.count + ")").appendTo($('#editul_'+selectElementid));
            // $("<li>").val(practice.id).html("<input type='checkbox' value='' name ='activitypractice"+"["+"practicesnew_"+selectElementid+"]][practices]["+practicegrpId+"][" +practice.id+"]' />"+practice.name + " " + "(" + practice.count + ")").appendTo($('#editul_'+selectElementid));
            $("<li>").val(practice.id).html("<input type='checkbox' value='' name ='editActivity[" + (selectElementid) + "]" + "[" + "practicesnew_" + selectElementid + "][practices][" + practicegrpId + "][" + practice.id + "]' />" + practice.name + " " + "(" + practice.count + ")").appendTo($('#editul_' + selectElementid));
        });

        // $('#appendnewcandy_'+formCountId+'_'+selectElementid).on('click', function (event) { 
        //     // alert("1"); 
        // event.stopPropagation();  
        // $(this).next('ul').slideToggle();
        // });



        // $(document).on('click', function () {
        //     if (!$(event.target).closest('.wrapMulDrop').length) {
        //         $('.wrapMulDrop ul').slideUp();  
        //     }
        // });       

        $('.wrapMulDrop ul li input[type="checkbox"]').on('change', function () {
            // alert("2");
            var x = $('.wrapMulDrop ul li input[type="checkbox"]:checked').length;
            if (x != "") {
                $('.editappendcandy').html(x + "template" + " " + "selected");
            } else if (x < 1) {
                $('.editappendcandy').html('Select Practices<i style="float:right;" class="icon ion-android-arrow-dropdown"></i>');
            }
        });
        // if (selectedPractice) {
        //     selectElement.val(selectedPractice);  
        // }
    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var getPatientPreviousMonthCalender = function (patientId, moduleId) {

    if (patientId != undefined && patientId != '') {
        axios({
            method: "GET",
            url: `/ccm/previous-month-calendar/${patientId}/${moduleId}/previousstatus`,
        }).then(function (response) {
            // console.log(response.data.created_at, "helllo");
            $("#regi_mnth").val(response.data.created_at);
            // var patientdata = response.data.created_at;
            // return patientdata;

        }).catch(function (error) {
            console.error(error, error.response);
        });
    }

};

const getCircularReplacer = () => {
    //this function  is to get data from circular JSON
    //use this function as follows
    //console.log('Specialists-list'  +  JSON.stringify(table, getCircularReplacer()));
    const seen = new WeakSet();
    return (key, value) => {
        if (typeof value === "object" && value !== null) {
            if (seen.has(value)) {
                return;
            }
            seen.add(value);
        }
        return value;
    };
};
// var onPersonalNotes = function (formObj, fields, response) {
//     if (response.status == 200) {
//         // $("#personal_notes_form")[0].reset();
//         $("#personal-notes").modal('hide');
//         var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>personal Notes Inserted Successfully!</strong></div>';
//         $("#success").html(txt);
//         $("#success").show();
//         setTimeout(function () {
//             $("#success").hide();
//         }, 3000);

//         var patient_id = $("#personal_notes_form input[name='patient_id']").val();
//         var module_id = $("#personal_notes_form input[name='module_id']").val();
//         util.getPatientStatus(patient_id, module_id);
//     }
// };

// var onPartOfResearchStudy = function (formObj, fields, response) {
//     if (response.status == 200) {
//         // $("#personal_notes_form")[0].reset();
//         $("#part-of-research-study").modal('hide');
//         var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Part Of Research Study Inserted Successfully!</strong></div>';
//         $("#success").html(txt);
//         $("#success").show();
//         setTimeout(function () {
//             $("#success").hide();
//         }, 3000);
//         var patient_id = $("#part_of_research_study_form input[name='patient_id']").val();
//         var module_id = $("#part_of_research_study_form input[name='module_id']").val();
//         util.getPatientStatus(patient_id, module_id);
//     }
// };

var updateBillableNonBillableAndTickingTimer = function (patientID, moduleId, totaltimeonly = null) {

    axios({
        method: "GET",
        url: `/system/get-total-billable-nonbillable-time/${patientID}/${moduleId}/total-time`,
    }).then(function (response) {
        var data = response.data;
        var time = data['total_time'];
        var splitTime = time.split(":");
        H = splitTime[0];
        M = splitTime[1];
        S = splitTime[2];
        $("#time-container").val(AppStopwatch.resetTickingTimeClock);
        if (totaltimeonly == null) {
            $("#start").hide();
            $("#pause").show();

            $("#timer_start").val(time);
            $("#page_landing_time").val(moment(Date.now()).format('HH:mm:ss'));
            $("#patient_time").val(time);
            $("#pause_time").val('0');
            $("#play_time").val('0');
            $("#pauseplaydiff").val('0');


            var billableTime = data['billable_time'];
            $(".last_time_spend").html(billableTime);

            var nonBillableTime = data['non_billable_time'];
            $(".non_billabel_last_time_spend").html(nonBillableTime);
        }

    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var totalTimeSpentByCM = function () {
    axios({
        method: "GET",
        url: `/patients/getCMtotaltime`,
    }).then(function (response) {
        var data = JSON.stringify(response.data);
        if (data == "null" || data == "") {
            var totalpatients = "00";
            var totaltime = "00";
        }
        else {
            var totalpatients = response.data[0].totalpatients;
            var totaltime = response.data.minutes;
        }
        var finaldata = " : " + totaltime + " / " + totalpatients;
        $(".cmtotaltimespent").html(finaldata);
        //console.log(response.data[0].totalpatients+" checkdata "+finaldata+"testdata"+data);

    }).catch(function (error) {
        console.error(error, error.response);
    });
};

var totalTimeSpent = function (patient_id, module_id, stage_id) {
    axios({
        method: "GET",
        url: `/ccm/getSpentTime/${patient_id}/${module_id}/${stage_id}/spent_total-time`,
    }).then(function (response) {
        var data = JSON.stringify(response.data);
        if (response.data[0].net_time == "null" || response.data[0].net_time == "" || response.data[0].net_time == null) {
            var totaltime = "00:00:00";
        }
        else {
            var totaltime = response.data[0].net_time;
        }
        var finaldata = "Total call Preparation time : " + totaltime;
        $("#prep_time_spent").html(finaldata);
        // console.log(response.data.net_time+" checkPREP TIME "+finaldata+"testdata"+data);

    }).catch(function (error) {
        console.error(error, error.response);
    });
};

// var getChartAjax = function(patient_id,deviceid,month,year) {
//     axios({
//         method: "GET", 
//         url :'/rpm/graphreadingnew-chart/'+ patient_id +'/'+ deviceid +'/'+month+'/'+year+'/graphchart',
//     }).then(function (response) {
//         //alert(JSON.stringify(response.data) +'graph');
//         var arraydate = JSON.stringify(response.data.uniArray);
//         var patientarraydatetime = JSON.parse(arraydate);
//         var arrayreading = JSON.stringify(response.data.arrayreading);
//         var reading=JSON.parse(arrayreading);
//         var label = JSON.stringify(response.data.label).replace(/\"/g, "");

//         var arrayreading1 = JSON.stringify(response.data.arrayreading1);
//         var reading1 = JSON.parse(arrayreading1);
//         var label1 = JSON.stringify(response.data.label1).replace(/\"/g, "");

//         var arrayreading_min =JSON.stringify(response.data.arrayreading_min);
//         var reading_min = JSON.parse(arrayreading_min); 
//         var arrayreading_max =JSON.stringify(response.data.arrayreading_max);
//         var reading_max = JSON.parse(arrayreading_max);

//         var arrayreading_min1 =JSON.stringify(response.data.arrayreading_min1);
//         var reading_min1 = JSON.parse(arrayreading_min1); 
//         var arrayreading_max1 =JSON.stringify(response.data.arrayreading_max1);
//         var reading_max1 = JSON.parse(arrayreading_max1);

//         var chart_text =JSON.stringify(response.data.chart_text);
//         var chart_name = JSON.parse(chart_text); 
//         var subtitle1 =  "<b>" + label +"</b>"+" - [Min:"+ reading_min +" ]/[Max: "+ reading_max +"]" ;
//         if(label1!=''){
//             var subtitle2 =  "<b>" + label1 +"</b>"+" - [Min:"+ reading_min1 +" ]/[Max: "+ reading_max1 +"]" ;
//         }else{
//             var subtitle2 =  ' ';
//         }
//         Highcharts.chart('container1', {
//           chart: {
//             type: 'spline',
//             events: {
//               load: function() {
//                 this.series.forEach(function(s) {
//                   s.update({
//                     showInLegend: s.points.length
//                   });
//                 });
//               }
//             }
//           },
//           xAxis: {
//             //type: 'datetime',
//             categories: patientarraydatetime,
//             crosshair: true,
//             index:1,
//             gridLineWidth: 1,
//           },
//           title: {
//             text: chart_name
//           },
//           subtitle: {
//             text: subtitle1+ ' '+subtitle2             
//           },
//           yAxis: {
//                 title: {
//                   text: 'Readings' //'Wind speed (m/s)'
//                 },
//                 min:0,
//                 minorGridLineWidth: 0,
//                 // gridLineWidth: 1,
//                 // tickInterval: 1,
//                 alternateGridColor: null, 
//                 plotBands: [
//                     { 
//                         from:reading_min,
//                         to:reading_max,
//                         color: 'rgba(68, 170, 213, 0.1)',

//                     },
//                     { 
//                         from:reading_min1, 
//                         to:reading_max1,
//                         color: 'rgba(243, 248, 157, 1)',//'rgba(269, 70, 213, 0.1)',

//                     }
//                 ]
//             },
//             tooltip: {
//                 shared: true,
//                 crosshairs: true 
//             },
//             plotOptions: {
//                 spline: {
//                     lineWidth: 4,
//                     states: {
//                         hover: { 
//                           lineWidth: 5
//                         }
//                     },
//                     marker: {
//                         enabled: true
//                     },
//                 } 
//             },
//             series: [{
//                         name: label,
//                         data: reading
//                     }, 
//                     {
//                         name: label1,
//                         data: reading1
//             }],
//             navigation: {
//                 menuItemStyle: {  
//                     fontSize: '10px'
//                 }
//             }
//         });
//     });
// }

// function getSpirometerChartAjax(patient_id,deviceid,month,year) {
//     axios({
//         method: "GET",
//         url :'/rpm/graphreadingnew-chart/'+ patient_id +'/'+ deviceid +'/'+month+'/'+year+'/graphchart',
//     }).then(function (response) {   
//      // alert(JSON.stringify(response.data) +'graph');

//         var arraydate = JSON.stringify(response.data.uniArray);
//         var patientarraydatetime = JSON.parse(arraydate);
//         var arrayreading = JSON.stringify(response.data.arrayreading);
//         var reading=JSON.parse(arrayreading);
//         var label = JSON.stringify(response.data.label).replace(/\"/g, "");

//         var arrayreading1 = JSON.stringify(response.data.arrayreading1).replace(/\"/g, "");
//         var reading1 = JSON.parse(arrayreading1);
//         var label1 = JSON.stringify(response.data.label1).replace(/\"/g, "");

//         var arrayreading_min =JSON.stringify(response.data.arrayreading_min);
//         var reading_min = JSON.parse(arrayreading_min); 
//         var arrayreading_max =JSON.stringify(response.data.arrayreading_max);
//         var reading_max = JSON.parse(arrayreading_max);

//         var arrayreading_min1 =JSON.stringify(response.data.arrayreading_min1);
//         var reading_min1 = JSON.parse(arrayreading_min1); 
//         var arrayreading_max1 =JSON.stringify(response.data.arrayreading_max1);
//         var reading_max1 = JSON.parse(arrayreading_max1);

//         var chart_text =JSON.stringify(response.data.chart_text);
//         var chart_name = JSON.parse(chart_text);
//         var subtitle1 = "<b>"+ label +"</b>" +" - [Min:"+ reading_min +" ]/[Max: "+ reading_max +"]";
//         var subtitle2 = "<b>"+ label1 +"</b>"+" - [Min:"+ reading_min1 +" ]/[Max: "+ reading_max1 +"]";
//         Highcharts.chart('container1', {
//             chart: {
//                 zoomType: 'xy'
//             },
//             title: {
//                 text: chart_name
//             },
//             subtitle: {
//                 text: subtitle1+ ' '+subtitle2             
//             },
//             xAxis: [{
//                 categories: patientarraydatetime,
//                 crosshair: true,
//                 index:1,
//                 gridLineWidth: 1,
//             }],
//             yAxis: [{ // Primary yAxis

//             }, { // Secondary yAxis
//                 gridLineWidth: 1,
//                 title: {
//                     text: label,
//                     style: {
//                         color: Highcharts.getOptions().colors[0]
//                     }
//                 },
//                 /*labels: {
//                     format: label,
//                     style: {
//                         color: Highcharts.getOptions().colors[0]
//                     }
//                 },*/
//                 plotBands: [{ 
//                       from:reading_min,
//                       to:reading_max,
//                       color: 'rgba(68, 170, 213, 0.1)',

//                 /*label:{
//                             text: label1,
//                             align: 'right',

//                             style: {
//                               color: '#606060',

//                             },

//                         }*/
//                 }]
//                 // top:'50%',
//                 // height:'50%'
//             }, { // Tertiary yAxis
//                 gridLineWidth: 0,
//                 title: {
//                     text: label1 ,

//                     style: {
//                         color: Highcharts.getOptions().colors[1]
//                     }
//                 },/*
//                 labels: {
//                     format: label1,
//                     style: {
//                         color: Highcharts.getOptions().colors[1]
//                     }
//                 },*/
//                 plotBands: [{ 
//                       from: reading_min1,
//                       to: reading_max1,
//                       color:'rgba(243, 248, 157, 1)',
//                       /*label: {
//                                 text: label1,
//                                 align: 'right',
//                                 style: {
//                                   color: '#606060',
//                                 },

//                             }*/
//                     }],
//                 opposite: true,
//                 // top:'50%',
//                 // height:'50%'
//             }],
//             tooltip: {
//                 shared: true
//             },
//             // legend: {
//             //     layout: 'vertical',
//             //     //align: 'left',
//             //    // x: 80,
//             //     verticalAlign: 'top',
//             //     //y: 55,
//             //     //floating: true,
//             //    // backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
//             // },
//             plotOptions: {
//             spline: {
//               lineWidth: 4,
//               states: {
//                 hover: { 
//                   lineWidth: 5
//                 }
//               },
//               marker: {
//                 enabled: true
//               },

//               // pointInterval: 3600000, // one hour
//               // pointStart: patientarraydatetime//Date.UTC(2018, 1, 13, 0, 0, 0)
//             } 
//             },
//             series: [{
//                 name: label,
//                 type: 'spline',
//                 yAxis: 1, 
//                 data: reading,
//                 tooltip: {
//                     valueSuffix: ' L/min'
//                 }

//             }, {
//                 name: label1,
//                 type: 'spline',
//                 yAxis: 2,
//                 data: reading1, 
//                 tooltip: {
//                     valueSuffix: ' L'
//                 }

//             }]
//         });

//     });
// }

var getChartAjax = function (patient_id, deviceid, month, year) {
    $.ajax({
        url: '/rpm/graphreadingnew-chart/' + patient_id + '/' + deviceid + '/' + month + '/' + year + '/graphchart',
        type: 'GET',
        async: true,
        dataType: "json",
        success: function (response) {
            /*axios({
                method: "GET", 
                url :'/rpm/graphreadingnew-chart/'+ patient_id +'/'+ deviceid +'/'+month+'/'+year+'/graphchart',
                async: true,
                dataType: "json",
            }).then(function (response) {*/
            getChartOnclick(response, "container1", deviceid);

        }
    });
}

var getChartOnclick = function (data, id, deviceid) {

    var patientarraydatetime = JSON.parse(JSON.stringify(data.uniArray));
    var reading = JSON.parse(JSON.stringify(data.arrayreading));
    var label = JSON.parse(JSON.stringify(data.label));
    var reading1 = JSON.parse(JSON.stringify(data.arrayreading1));
    var label1 = JSON.parse(JSON.stringify(data.label1));

    var arrayreading_min = JSON.stringify(data.arrayreading_min);
    var reading_min = JSON.parse(arrayreading_min);
    var arrayreading_max = JSON.stringify(data.arrayreading_max);
    var reading_max = JSON.parse(arrayreading_max);

    var arrayreading_min1 = JSON.stringify(data.arrayreading_min1);
    var reading_min1 = JSON.parse(arrayreading_min1);
    var arrayreading_max1 = JSON.stringify(data.arrayreading_max1);
    var reading_max1 = JSON.parse(arrayreading_max1);
    var title_name = JSON.parse(JSON.stringify(data.title_name));

    if (deviceid == 3) {
        var subtitle2 = " / <b>" + label1 + "</b>" + " - [Max:" + reading_max1 + " ]/[Min: " + reading_min1 + "]";
    } else {
        var subtitle2 = "";
    }
    //alert("entering..");
    var subtitle1 = "<b>" + label + "</b>" + " - [Min:" + reading_min + " ]/[Max: " + reading_max + "]";

    // Highcharts.setOptions({
    //     time: {
    //         timezone: 'America/Chicago'
    //     }
    // });

    let chart = Highcharts.chart(id, {
        chart: {
            type: 'spline',
            events: {
                load: function () {
                    this.series.forEach(function (s) {
                        s.update({
                            showInLegend: s.points.length
                        });
                    });
                }
            }
        },
        xAxis: {
            //type: 'datetime',
            labels: {
                rotation: -90,
                step: 1,
                padding: 0,
                style: {
                    fontSize: '8px'
                }
            },
            categories: patientarraydatetime,
            crosshair: true,  //extra
            index: 1,//extra
            gridLineWidth: 1,
        },
        // time: {
        //     timezone: 'America/Chicago'
        // },
        title: {
            text: title_name
        },
        subtitle: {
            text: subtitle1 + ' ' + subtitle2
        },
        yAxis: {
            title: {
                text: 'Readings' //'Wind speed (m/s)'
            },
            min: 0,
            minorGridLineWidth: 0,

            alternateGridColor: null,
            plotBands: [
                {
                    from: reading_min,
                    to: reading_max,
                    color: 'rgba(68, 170, 213, 0.1)',

                },
                {
                    from: reading_min1,
                    to: reading_max1,
                    color: 'rgba(243, 248, 157, 1)',//'rgba(269, 70, 213, 0.1)',

                }
            ]
        },
        tooltip: {
            shared: true,
            crosshairs: true
        },
        plotOptions: {
            spline: {
                lineWidth: 4,
                states: {
                    hover: {
                        lineWidth: 5
                    }
                },
                marker: {
                    enabled: true
                },
            }
        },
        series: [{
            name: label,
            data: reading
        },
        {
            name: label1,
            data: reading1
        }],
        navigation: {
            menuItemStyle: {
                fontSize: '10px'
            }
        }
    });
    let btn = document.getElementById("btnFullScreen")

    btn.addEventListener('click', function () {
        Highcharts.FullScreen = function (container) {
            this.init(container.parentNode); // main div of the chart
        };

        Highcharts.FullScreen.prototype = {
            init: function (container) {
                if (container.requestFullscreen) {
                    container.requestFullscreen();
                } else if (container.mozRequestFullScreen) {
                    container.mozRequestFullScreen();
                } else if (container.webkitRequestFullscreen) {
                    container.webkitRequestFullscreen();
                } else if (container.msRequestFullscreen) {
                    container.msRequestFullscreen();
                }
            }
        };
        chart.fullscreen = new Highcharts.FullScreen(chart.container);
    })

}

var getSpirometerChartAjax = function (patient_id, deviceid, month, year) {
    $.ajax({
        url: '/rpm/graphreadingnew-chart/' + patient_id + '/' + deviceid + '/' + month + '/' + year + '/graphchart',
        type: 'GET',
        async: true,
        dataType: "json",
        success: function (response) {
            getSpirometerChartOnclick(response, "container1", deviceid);
        }
    });
}

var getSpirometerChartOnclick = function (data, id, deviceid) {

    var patientarraydatetime = JSON.parse(JSON.stringify(data.uniArray));
    var arrayreading = JSON.stringify(data.arrayreading);
    var reading = JSON.parse(arrayreading);
    var label = JSON.stringify(data.label).replace(/\"/g, "");
    var arrayreading1 = JSON.stringify(data.arrayreading1).replace(/\"/g, "");
    var reading1 = JSON.parse(arrayreading1);
    var label1 = JSON.stringify(data.label1).replace(/\"/g, "");

    var arrayreading_min = JSON.stringify(data.arrayreading_min);
    var reading_min = JSON.parse(arrayreading_min);
    var arrayreading_max = JSON.stringify(data.arrayreading_max);
    var reading_max = JSON.parse(arrayreading_max);

    var arrayreading_min1 = JSON.stringify(data.arrayreading_min1);
    var reading_min1 = JSON.parse(arrayreading_min1);
    var arrayreading_max1 = JSON.stringify(data.arrayreading_max1);
    var reading_max1 = JSON.parse(arrayreading_max1);

    var chart_name = JSON.parse(JSON.stringify(data.title_name));



    Highcharts.chart(id, {
        chart: {
            zoomType: 'xy'
        },
        title: {
            text: chart_name
        },
        subtitle: {
            text: "<b>" + label + "</b>" + " - [Max:" + reading_max + " ]/[Min: " + reading_min + "]" + " / <b>" + label1 + "</b>" + " - [Max:" + reading_max1 + " ]/[Min: " + reading_min1 + "]"
        },
        xAxis: [{
            categories: patientarraydatetime,
            crosshair: true,
            index: 1,
            gridLineWidth: 1,
        }],
        yAxis: [{ // Primary yAxis

        }, { // Secondary yAxis
            gridLineWidth: 1,
            title: {
                text: label,
                style: {
                    color: Highcharts.getOptions().colors[0]
                }
            },

            plotBands: [{
                from: reading_min,
                to: reading_max,
                color: 'rgba(68, 170, 213, 0.1)',


            }]

        }, { // Tertiary yAxis
            gridLineWidth: 0,
            title: {
                text: label1,

                style: {
                    color: Highcharts.getOptions().colors[1]
                }
            },
            plotBands: [{
                from: reading_min1,
                to: reading_max1,
                color: 'rgba(243, 248, 157, 1)',

            }],
            opposite: true,

        }],
        tooltip: {
            shared: true
        },

        plotOptions: {
            spline: {
                lineWidth: 4,
                states: {
                    hover: {
                        lineWidth: 5
                    }
                },
                marker: {
                    enabled: true
                },


            }
        },
        series: [{
            name: label,
            type: 'spline',
            yAxis: 1,
            data: reading,
            tooltip: {
                valueSuffix: ' L/min'
            }

        }, {
            name: label1,
            type: 'spline',
            yAxis: 2,
            data: reading1,
            tooltip: {
                valueSuffix: ' L'
            }

        }]
    });

}

var redirectToWorklistPage = function () {
    var interval_id = 0;
    document.addEventListener("visibilitychange", function () {
        var timediff = 0;
        if (document.hidden) {
            var start_time = moment();
            var refreshIntervalId = setInterval(function () {
                var patientScreenTimeout = $("#patientScreenTimeout").val();
                if (document.hidden) {
                    interval_id++;
                    var now = moment();
                    var timediff = now - start_time;
                    console.log("inactive for " + interval_id + " now" + now + " starttime" + start_time + " " + timediff + " " + timediff / 60000 + " sec");
                    if (timediff / 60000 >= patientScreenTimeout) {
                        console.log("tab was inactive for 20 mins");
                        var baseURL = window.location.origin + '/';
                        window.open(baseURL + "patients/worklist", '_self');
                        clearInterval(refreshIntervalId);
                        interval_id = 0;

                    }
                }
                else {
                    if (timediff / 60000 >= patientScreenTimeout) {
                        console.log("tab was inactive for 20 mins");
                        var baseURL = window.location.origin + '/';
                        window.open(baseURL + "patients/worklist", '_self');
                        clearInterval(refreshIntervalId);
                        interval_id = 0;

                    } else {
                        interval_id = 0;
                    }
                }
            }, 5000);
        }
    });
}

var setLandingTime = function () {
    axios({
        method: "GET",
        url: `/system/get-landing-time`,
    }).then(function (response) {
        var data = response.data;
        var landing_time = data['landing_time'];
        $("#page_landing_times").val(landing_time);
        $(".form_start_time").val(landing_time);
    }).catch(function (error) {
        console.error(error, error.response);
    });
}

var updateTimeEveryMinutes = function (patientID, moduleId, starttime) {
    axios({
        method: "GET",
        url: `/system/get-total-time/${patientID}/${moduleId}/${starttime}/total-time`,
    }).then(function (response) {
        if (patientID != 0) {
            if (pause_stop_flag == 0) {
                var data = response.data;
                var fial_time = data['total_time'];
                $("#time-containers").html(fial_time);
                $(".message-notification").html('');
                $(".message-notification").append(data['count']);
                $("#ajax-message-history").html('');
                $("#ajax-message-history").append(data['history']);
                var now = new Date();
                var timeToNextTick = (60 - now.getSeconds()) * 1000 - now.getMilliseconds();
                setTimeout(function () {
                    if ($(".form_start_time").val() == "undefined" || ($(".form_start_time").val() == '')) {
                        var start_time = $("#page_landing_times").val();
                    } else {
                        var start_time = $(".form_start_time").val();
                    }
                    updateTimeEveryMinutes(patientID, moduleId, start_time);
                }, 60000);
            }
        } else {
            var data = response.data;
            $(".message-notification").html('');
            $(".message-notification").append(data['count']);
            setTimeout(function () {
                updateTimeEveryMinutes(0, 0, 0);
            }, 60000);
        }
    }).catch(function (error) {
        console.error(error, error.response);
    });
}

var getSessionLogoutTimeWithPopupTime = function () {
    var data;
    axios({
        method: "GET",
        url: `/system/get-session-logout-time-with-popup-time`,
    }).then(function (response) {
        var data = response.data;
        var logoutPopupTime = data.logoutpoptime;
        var sessionTimeout = data.session_timeout;
        var sessionTimeoutInSeconds = sessionTimeout * 60;
        var showPopupTime = sessionTimeoutInSeconds - logoutPopupTime;
        // sessionStorage.setItem("logoutPopupTime", logoutPopupTime);
        // sessionStorage.setItem("sessionTimeout", sessionTimeout);
        localStorage.setItem("idleTime", 0);
        // sessionStorage.setItem("sessionTimeoutInSeconds", sessionTimeoutInSeconds);
        localStorage.setItem("sessionTimeoutInSeconds", sessionTimeoutInSeconds); //changes by ashvini
        // sessionStorage.setItem("showPopupTime", showPopupTime);
        localStorage.setItem("showPopupTime", showPopupTime); //changes by ashvini
        const dt = new Date();
        // let time = d.getTime() / 1000;
        localStorage.setItem("systemDate", dt);

        // var sessionTimeoutInMilliseconds = sessionTimeout*60000;
        // var logoutPopupTimeInMilliseconds = logoutPopupTime*60;
        // var showPopupTimeInMilliseconds  = sessionTimeoutInMilliseconds - logoutPopupTimeInMilliseconds;
        // sessionStorage.setItem("sessionTimeoutInMilliseconds", sessionTimeoutInMilliseconds);
        // sessionStorage.setItem("showPopupTimeInMilliseconds", showPopupTimeInMilliseconds);


    }).catch(function (error) {
        console.error(error, error.response);
    });

    // return $.ajax({
    //     url: `/system/get-session-logout-time-with-popup-time`,
    //     type: 'GET'
    //     // ,
    //     // success: function (response) {
    //     //     response.data;
    //     // }
    // });
}

$('.sub-item-name').click(function () {
    // alert(this.textContent.trim());
});
// $('form[name="personal_notes_form"]').on('submit', function (e) { e.preventDefault(); form.ajaxSubmit('personal_notes_form', onPersonalNotes); });
// $('form[name="part_of_research_study_form"]').on('submit', function (e) { e.preventDefault(); form.ajaxSubmit('part_of_research_study_form', onPartOfResearchStudy); });
// Export the module

var displayLoader = function () {
    $("#preloader").css('display', 'block');
}

window.util = {
    redirectToWorklistPage: redirectToWorklistPage,
    getPatientPreviousMonthCalender: getPatientPreviousMonthCalender,
    validateAndGenerateUid: validateAndGenerateUid,
    getCallScriptsById: getCallScriptsById,
    getEmailScriptsBYId: getEmailScriptsBYId,
    getPatientStatus: getPatientStatus,
    getPatientDetails: getPatientDetails,
    setLandingTime: setLandingTime,
    updateTimeEveryMinutes: updateTimeEveryMinutes,
    // getPatientDetailsModel:getPatientDetailsModel,
    getRelationBuild: getRelationBuild,
    //getRelationshipRadioStatus      : getRelationshipRadioStatus,
    getPatientRelationshipBuilding: getPatientRelationshipBuilding,
    getPatientCurrentMonthNotes: getPatientCurrentMonthNotes,
    getPatientPreviousMonthNotes: getPatientPreviousMonthNotes,
    getPatientCareplanNotes: getPatientCareplanNotes,
    gatCaretoolData: gatCaretoolData,
    updatePracticeListWithoutOther: updatePracticeListWithoutOther,
    updatePracticeListOnEmr: updatePracticeListOnEmr,
    updatePatientListOnEmr: updatePatientListOnEmr,
    // getQuestionnaireScript          : getQuestionnaireScript,
    // getCallScripts                  : getCallScripts,
    updateTimer: updateTimer,
    updateFinalizeDate: updateFinalizeDate,
    updatePatientList: updatePatientList,
    updatePatientListAssignedDevice: updatePatientListAssignedDevice,
    getPatientList: getPatientList,
    renderDataTableOrder: renderDataTableOrder,
    renderDataTable: renderDataTable,
    renderDataTable_pdf: renderDataTable_pdf,
    renderFixedColumnDataTable: renderFixedColumnDataTable,
    copyDataFromOneDataTableToAnother: copyDataFromOneDataTableToAnother,
    dateValue: dateValue,
    filterValues: filterValues,
    selectDiagnosisCode: selectDiagnosisCode,
    updatePhysicianList: updatePhysicianList,
    updatePcpPhysicianList: updatePcpPhysicianList,
    updateCaremanagerList: updateCaremanagerList,
    updateDocsOtherList: updateDocsOtherList,
    updateProviderPracticeListfunction: updateProviderPracticeListfunction,
    // updateProviderList              : updateProviderList,
    formatDate: formatDate,
    isArray: isArray,
    setActiveTab: setActiveTab,
    timeDifference: timeDifference,
    age: age,
    updateTodaysDate: updateTodaysDate,
    countAttempt: countAttempt,
    dynamicFieldCnt: dynamicFieldCnt,
    patientCount: patientCount,
    teamLeader: teamLeader,
    checkAdmin: checkAdmin,
    check: check,
    newlyAssignedCount: newlyAssignedCount,
    inProgressCount: inProgressCount,
    countInitialContactDueToday: countInitialContactDueToday,
    countSecondContactDueToday: countSecondContactDueToday,
    countFacetoFaceVisitDueThisWeek: countFacetoFaceVisitDueThisWeek,
    nonBillableCount: nonBillableCount,
    billableCount: billableCount,
    readmissionCount: readmissionCount,
    initialContactVariables: initialContactVariables,
    secondContactVariables: secondContactVariables,
    tcmPatientVariables: tcmPatientVariables,
    taskDueTodayVariables: taskDueTodayVariables,
    faceToFaceDueThisWeekVariables: faceToFaceDueThisWeekVariables,
    stepWizard: stepWizard,
    stepWizardHorizontal: stepWizardHorizontal,
    updateSubModuleList: updateSubModuleList,
    updateStageList: updateStageList,
    updateStageCodeList: updateStageCodeList,
    getToDoListData: getToDoListData,
    getFollowupListData: getFollowupListData,
    //lineChartVariables          : lineChartVariables,
    //businessDays                : businessDays,
    viewsDateFormat: viewsDateFormat,
    logTimeManually: logTimeManually,
    logPauseTime: logPauseTime,
    updateBmi: updateBmi,
    getPatientNetTimeBasedOnModule: getPatientNetTimeBasedOnModule,
    updateMedicationList: updateMedicationList,
    updateCpdProviderList: updateCpdProviderList,
    updatePracticeList: updatePracticeList,
    updateRelationshipList: updateRelationshipList,
    viewsDateFormatWithTime: viewsDateFormatWithTime,
    updatePhysicianListWithoutOther: updatePhysicianListWithoutOther,
    getPracticelistaccordingtopracticegrp: getPracticelistaccordingtopracticegrp,
    getRpmPatientList: getRpmPatientList,
    pateintdevicecode: pateintdevicecode,
    getactivityPracticelistaccordingtopracticegrp: getactivityPracticelistaccordingtopracticegrp,
    getnewactivityPracticelistaccordingtopracticegrp: getnewactivityPracticelistaccordingtopracticegrp,
    getappendPracticelistaccordingtopracticegrp: getappendPracticelistaccordingtopracticegrp,
    geteditappendPracticelistaccordingtopracticegrp: geteditappendPracticelistaccordingtopracticegrp,
    geteditPracticelistaccordingtopracticegrp: geteditPracticelistaccordingtopracticegrp,
    getRpmProviderPatientList: getRpmProviderPatientList,
    renderRawDataDataTable: renderRawDataDataTable,
    refreshAllergyCountCheckbox: refreshAllergyCountCheckbox,
    getModuleId: getModuleId,
    updatePhysicianProviderListWithoutOther: updatePhysicianProviderListWithoutOther,
    updateBillableNonBillableAndTickingTimer: updateBillableNonBillableAndTickingTimer,
    rendernewDataTable: rendernewDataTable,
    totalTimeSpentByCM: totalTimeSpentByCM,
    totalTimeSpent: totalTimeSpent,
    getEmrOnPractice: getEmrOnPractice,
    getChartAjax: getChartAjax,
    getSpirometerChartAjax: getSpirometerChartAjax,
    getChartOnclick: getChartOnclick,
    getSpirometerChartOnclick: getSpirometerChartOnclick,
    updatePartner: updatePartner,
    getSessionLogoutTimeWithPopupTime: getSessionLogoutTimeWithPopupTime,
    getDiagnosisIdfromPatientdiagnosisid: getDiagnosisIdfromPatientdiagnosisid,
    getDiagnosisCount: getDiagnosisCount,
    getDistinctDiagnosisCountForBubble: getDistinctDiagnosisCountForBubble,
    displayLoader: displayLoader,
    updatePartnerDevice: updatePartnerDevice,
    getPatientEnrollModule: getPatientEnrollModule,
    getToDoListCalendarData: getToDoListCalendarData,
    updateTemplateList: updateTemplateList,
    updateTemplateLists: updateTemplateLists

};