
/**
 * Filter the given data by searching the given keys for the given value
 *
 * @param  {JSON Object}  data   The given data object to search through
 * @param  {JSON Object}  fields What indices of the object to search through
 * @param  {String}       search The value(s) to search for in the data
 * @return {JSON Object}
 */
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
var refreshAllergyCountCheckbox = function(id,allergy_type,form_name){      
    axios({
          method: "GET",
          url: `/ccm/care-plan-development-allergies-allergylist/${id}/${allergy_type}/count_allergy`,
      }).then(function (response) {
       
        var allergiescnt = response.data;
        // console.log("allergycount"+allergiescnt+" "+allergy_type);      

        
        $('form[name="'+form_name+'"] #'+allergy_type+'count').val(allergiescnt);  
        if (allergiescnt==0) {
            $('form[name="'+form_name+'"] .noallergiescheck').removeAttr('disabled', 'disabled');
         
        } else {

            $('form[name="'+form_name+'"] .noallergiescheck').attr('disabled', 'disabled');  
         

        } 
    }).catch(function (error) {
        console.error(error, error.response);
    });  
}

// var getModuleId = function(name){
//    $.ajax({
//         method: "GET", 
//         url: `/org/ajax/modules/${name}/moduleId`,  
//         success:function(response) {
//           return response; 
//         }  
//     }); 
// }
var getModuleId = function(name){
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
//var renderDataTable = function (tabid, url, columnData, assetBaseUrl, copyflag , copyTo) {
var renderDataTable = function (tabid, url, columnData, assetBaseUrl, copyflag=0 , copyTo="") {
    console.log("tabid="+tabid+"  url="+url+"   columnData==>"+columnData+"   assetBaseUrl==>"+assetBaseUrl+" copyflag==>"+copyflag+"  copyTo==>"+copyTo);
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
                text: '<img src="' + assetBaseUrl + copy_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="Copy">',
            },
            {
                extend: 'excelHtml5',
                text: '<img src="' + assetBaseUrl + excel_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="Excel">',
                titleAttr: 'Excel'
            },
            {
                extend: 'csvHtml5',
                text: '<img src="' + assetBaseUrl + csv_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="CSV">',
                titleAttr: 'CSV',
                fieldSeparator: '\|',
            },
            {
                extend: 'pdfHtml5',
                text: '<img src="' + assetBaseUrl + pdf_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="PDF">',
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
        "drawCallback": function( settings ) {
            if(copyflag == 1){
                //alert( copyTo + ' DataTables has redrawn the table' );
                t2content = $('#' + tabid +' tbody').html();
                //alert(t2content);
                $('#' + copyTo + ' tbody').html(t2content);
                
                table2 = util.renderRawDataDataTable(copyTo, assetBaseUrl);
                //copydata();


            }
        }
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
                text: '<img src="' + assetBaseUrl + copy_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="Copy">',
            },
            {
                extend: 'excelHtml5',
                text: '<img src="' + assetBaseUrl + excel_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="Excel">',
                titleAttr: 'Excel'
            },
            {
                extend: 'csvHtml5',
                text: '<img src="' + assetBaseUrl + csv_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="CSV">',
                titleAttr: 'CSV',
                fieldSeparator: '\|',
            },
            {
                extend: 'pdfHtml5',
                text: '<img src="' + assetBaseUrl + pdf_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="PDF">',
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

var renderRawDataDataTable = function(tabid,  assetBaseUrl){
    var copy_img = "assets/images/copy_icon.png";
    var excel_img = "assets/images/excel_icon.png";
    var pdf_img = "assets/images/pdf_icon.png";
    var csv_img = "assets/images/csv_icon.png";
    var table = $('#' + tabid).DataTable({
        "dom": '<"float-right"B><"float-right"f><"float-left"r><"clearfix">t<"float-left"i><"float-right"p><"clearfix">',
        buttons: [
            {
                extend: 'copyHtml5',
                text: '<img src="' + assetBaseUrl + copy_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="Copy">',
            },
            {
                extend: 'excelHtml5',
                text: '<img src="' + assetBaseUrl + excel_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="Excel">',
                titleAttr: 'Excel'
            },
            {
                extend: 'csvHtml5',
                text: '<img src="' + assetBaseUrl + csv_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="CSV">',
                titleAttr: 'CSV',
                fieldSeparator: '\|',
            },
            {
                extend: 'pdfHtml5',
                text: '<img src="' + assetBaseUrl + pdf_img + '" width="20" alt="" data-toggle="tooltip" data-placement="top" title="PDF">',
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
    table.on( 'draw', function () {
        if ( table.data().count() != 0 ) {  
            t2content = $('#' + copyFromTableId +' tbody').html();
            $('#' + copyToTableId + ' tbody').html(t2content);
            table2 = util.renderRawDataDataTable(copyToTableId, baseURL);
        }
    });
};
/**
 * Update the list of patients to select from
 *
 * @param {Integer}       practiceId
 * @param {jQuery Object} selectElement
 * @param {Integer}       selectedPatients
 */
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

var getRpmPatientList = function (practiceId, selectElement, selectedPatients = null) {
    // if (!practiceId) {
    //     return;
    // }

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

/**
 * Update the list of patients to select from
 *
 * @param {Integer}       callScriptId
 * @param {jQuery Object} selectElement
 * @param {Integer}       selectedCallScript
 */
var getCallScriptsById = function (callScriptId, selectElement = null, selectTemplateTypeIdElement = null, selectTemplateTitleElement = null, emailSubject = null) {
    if (callScriptId != 'undefined' && callScriptId != null && callScriptId != "") {
        var uid = $("[name='patient_id']").val();
        //console.log('testing data ff ' + uid);
        axios({
            method: "GET",
            url: `/ccm/get-call-scripts-by-id/${callScriptId}/${uid}/call-script`,
        }).then(function (response) {
            //console.log(response.data);
            //console.log(response.data.finaldata);
            if (response.data[0].hasOwnProperty('content')) {
                //var script = jQuery.parseJSON(response.data[0].content).message;
                var script = response.data.finaldata;
                var script = script.replace(/(<([^>]+)>)/ig, '');
                //console.log("message script" + mscript);
                var subject = jQuery.parseJSON(response.data[0].content).subject;
                // var subject = subject.replace(/(<([^>]+)>)/ig, '');
                if (selectElement) {
                    var pra = "<?php echo $provider_data['practice_name']; ?>";
                    // console.log(pra + 'we finally gating this');
                    // $(selectElement).html($(script).text());
                    $(selectElement).text(script);
                }
                if (emailSubject) {
                    $(emailSubject).val(subject);
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
            $(".last_time_spend").html(response.data);
        } else {
            $(".non_billabel_last_time_spend").html(response.data);
        }
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
    selectElement.html($("<option>").html("Select Stage Code"));
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

var updatePracticeListWithoutOther = function (caremanager, selectElement, selectedPractice = null) {
    //  alert(practiceId);
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

var logTimeManually = function (timerStart, timerEnd, patientId, moduleId, subModuleId, stageId, billable, uId) {
    // alert('calling');
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
            uId: uId
        }
    }).then(function (response) {
        if (JSON.stringify(response.data) != "" && JSON.stringify(response.data) != null && JSON.stringify(response.data) != undefined) {
            $("#timer_start").val(response.data);
            $("#timer_end").val(response.data);
            $(".last_time_spend").html(response.data);
            alert("Time Logged successfully.");
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

var updateCpdProviderList = function (practiceid , selectElement, selectedProvider ) {
    //alert(practiceid);
//var updateCpdProviderList = function (practiceid = null, selectElement, selectedProvider = null) {//comment by anand
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
            alert(selectElementid);
            var x = $('#editappendul_'+selectElementid+' li input[type="checkbox"]:checked').length;
            alert(x);  
            if (x != "") {
                $('#editappendcandy_' +selectElementid).html(x + " Practices" + " " + "selected");
            } else if (x < 1) {
                $('#editappendcandy_' +selectElementid).html('Select Practices<i style="float:right;" class="icon ion-android-arrow-dropdown"></i>');
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

// $('form[name="personal_notes_form"]').on('submit', function (e) { e.preventDefault(); form.ajaxSubmit('personal_notes_form', onPersonalNotes); });
// $('form[name="part_of_research_study_form"]').on('submit', function (e) { e.preventDefault(); form.ajaxSubmit('part_of_research_study_form', onPartOfResearchStudy); });
// Export the module
window.util = {
    getPatientPreviousMonthCalender: getPatientPreviousMonthCalender,  
    validateAndGenerateUid: validateAndGenerateUid,
    getCallScriptsById: getCallScriptsById,
    getPatientStatus: getPatientStatus,
    getRelationBuild: getRelationBuild,
    //getRelationshipRadioStatus      : getRelationshipRadioStatus,
    getPatientRelationshipBuilding: getPatientRelationshipBuilding,
    getPatientCurrentMonthNotes: getPatientCurrentMonthNotes,
    getPatientPreviousMonthNotes: getPatientPreviousMonthNotes,
    getPatientCareplanNotes: getPatientCareplanNotes,
    gatCaretoolData: gatCaretoolData,
    updatePracticeListWithoutOther: updatePracticeListWithoutOther,
    // getQuestionnaireScript          : getQuestionnaireScript,
    // getCallScripts                  : getCallScripts,
    updateTimer: updateTimer,
    updateFinalizeDate: updateFinalizeDate,
    updatePatientList: updatePatientList,
    getPatientList: getPatientList,
    renderDataTable: renderDataTable,
    renderFixedColumnDataTable: renderFixedColumnDataTable,
    copyDataFromOneDataTableToAnother: copyDataFromOneDataTableToAnother,
    dateValue: dateValue,
    filterValues: filterValues,
    selectDiagnosisCode: selectDiagnosisCode,
    updatePhysicianList: updatePhysicianList,
    updatePcpPhysicianList: updatePcpPhysicianList,
    updateCaremanagerList: updateCaremanagerList,
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
    updateBmi: updateBmi,
    getPatientNetTimeBasedOnModule: getPatientNetTimeBasedOnModule,
    updateMedicationList: updateMedicationList,
    updateCpdProviderList: updateCpdProviderList,
    updateRelationshipList: updateRelationshipList,
    viewsDateFormatWithTime: viewsDateFormatWithTime,
    updatePhysicianListWithoutOther: updatePhysicianListWithoutOther,
    getPracticelistaccordingtopracticegrp: getPracticelistaccordingtopracticegrp,
    getRpmPatientList: getRpmPatientList,
    getactivityPracticelistaccordingtopracticegrp: getactivityPracticelistaccordingtopracticegrp,
    getnewactivityPracticelistaccordingtopracticegrp: getnewactivityPracticelistaccordingtopracticegrp,
    getappendPracticelistaccordingtopracticegrp: getappendPracticelistaccordingtopracticegrp,
    geteditappendPracticelistaccordingtopracticegrp: geteditappendPracticelistaccordingtopracticegrp,
    geteditPracticelistaccordingtopracticegrp: geteditPracticelistaccordingtopracticegrp,
    getRpmProviderPatientList : getRpmProviderPatientList,
    renderRawDataDataTable : renderRawDataDataTable,
    refreshAllergyCountCheckbox : refreshAllergyCountCheckbox,
    getModuleId:getModuleId
};