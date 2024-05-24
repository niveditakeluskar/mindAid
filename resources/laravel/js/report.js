/**
 * Invoked after the form has been submitted
 */
var onMonthlyReport = function (formObj, fields, response) {
  if (response.status == 200) {
    getMonthlyPatientList();

  }
};

var onDailyReport = function (formObj, fields, response) {
  if (response.status == 200) {
    console.log("success");

    // getMonthlyPatientList('search');
    //window.location.href = 'ccm/daily-report/patients-search';
  }
};

var onResult = function (form, fields, response, error) {
  if (error) {
  }
  else {
    //console.log(response.data.redirect+"*&*&*&");
    window.location.href = response.data.redirect;
  }
};






var init = function () {


  getMonthlyPatientList();
  //getDailyPatientList();
  console.log("test");
  form.ajaxForm("report_form", onMonthlyReport, function () {
    return true;
  });

  //  form.ajaxForm("daily_report_form", onDailyReport, function () {
  //   return true;
  // });

};

window.report = {
  init: init,
  onResult: onResult
  // getCallScriptsById              : getCallScriptsById,
  // getCallScripts                  : getCallScripts,
};
