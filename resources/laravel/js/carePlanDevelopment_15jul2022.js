
const URL_POPULATE = "/ccm/ajax/populateCarePlanDevelopmentForm";
var baseURL = window.location.origin + '/';
var patient_id = $("input[name='patient_id']").val();
var sPageURL = window.location.pathname;
parts = sPageURL.split("/"),
	module = parts[parts.length - 2];

var imagingcount = 0;
var inc_symptoms = 0;
var review_inc_symptoms = 0;
var inc_goals = 0;
var review_inc_goals = 0;
var inc_tasks = 0;
var review_inc_tasks = 0;
var healthdatacount = 0;
var labcount = 0;

var familyColumns = [
	{ data: 'DT_RowIndex', name: 'DT_RowIndex' },
	{
		data: null, mRender: function (data, type, full, meta) {
			if (data != '' && data != 'NULL' && data != undefined) {
				fname = full['fname'];
				if (full['lname'] == null && fname == null) {
					return '';
				}
				else {
					return fname + ' ' + full['lname'];
				}
			} else {
				return '';
			}
		}, orderable: false
	},
	{ data: 'age', name: 'age' },
	{ data: 'address', name: 'address' },
	{
		data: 'users',
		mRender: function (data, type, full, meta) {
			if (data != '' && data != 'NULL' && data != undefined) {
				l_name = data['l_name'];
				if (data['l_name'] == null) {
					l_name = '';
				}
				return data['f_name'] + ' ' + l_name;
			} else {
				return '';
			}
		}, orderable: false
	},
	{ data: 'updated_at', name: 'updated_at' },
	{
		data: null, mRender: function (data, type, full, meta) {
			if (data != '' && data != 'NULL' && data != undefined) {
				if (full['review'] == '1') {
					return "Yes";
				} else {
					return "No";
				}
				if (full['review'] == null) {
					return "No";
				}
			}
		},
		orderable: true
	},
	{ data: 'action', name: 'action', orderable: false, searchable: false }
];

var dmeOtherMedicalSuppliesServiceColumns = [
	{ data: 'DT_RowIndex', name: 'DT_RowIndex' },
	{ data: 'type', name: 'type' },
	{ data: 'purpose', name: 'purpose' },
	{ data: 'specify', name: 'specify' },
	{ data: 'brand', name: 'brand' },
	{
		data: 'users',
		mRender: function (data, type, full, meta) {
			if (data != '' && data != 'NULL' && data != undefined) {
				l_name = data['l_name'];
				if (data['l_name'] == null) {
					l_name = '';
				}
				return data['f_name'] + ' ' + l_name;
			} else {
				return '';
			}
		}, orderable: false
	},
	{ data: 'updated_at', name: 'updated_at' },
	{ data: 'action', name: 'action', orderable: false, searchable: false }
];

var homeDialysisTherapySosialServicesColumns = [
	{ data: 'DT_RowIndex', name: 'DT_RowIndex' },
	{ data: 'type', name: 'type' },
	{ data: 'purpose', name: 'purpose' },
	{ data: 'specify', name: 'specify' },
	{ data: 'brand', name: 'brand' },
	{ data: 'frequency', name: 'frequency' },
	{
		data: 'service_start_date', name: 'service_start_date', "render": function (value) {
			if (value === null || value == undefined || value == "") return "";
			return moment(value).format('MM-DD-YYYY');
		}
	},
	{
		data: 'service_end_date', name: 'service_end_date', "render": function (value) {
			if (value === null || value == undefined || value == "") return "";
			return moment(value).format('MM-DD-YYYY');
		}
	},
	{
		data: 'users',
		mRender: function (data, type, full, meta) {
			if (data != '' && data != 'NULL' && data != undefined) {
				l_name = data['l_name'];
				if (data['l_name'] == null) {
					l_name = '';
				}
				return data['f_name'] + ' ' + l_name;
			} else {
				return '';
			}
		}, orderable: false
	},
	{ data: 'updated_at', name: 'updated_at' },
	{ data: 'action', name: 'action', orderable: false, searchable: false }
];

var labColumns = [
	{ data: 'DT_RowIndex', name: 'DT_RowIndex' },
	{
		data: null, mRender: function (data, type, full, meta) {
			if (data != '' && data != 'NULL' && data != undefined) {
				var description = data['description'];
				if (data['description'] == null) {
					description = '';
				}
				return description;
			}
		},
		orderable: true
	},
	{
		data: null, mRender: function (data, type, full, meta) {
			if (data != '' && data != 'NULL' && data != undefined) {
				var lab_date = data['lab_date'];
				if (data['lab_date'] == null || data['lab_date'] == 'null' || data['lab_date'] == '') {
					lab_date = '';
				} else {
					//return util.viewsDateFormat(lab_date);
					return moment(lab_date).format('MM-DD-YYYY');
				}
				//  return moment(lab_date).format('MM-DD-YYYY');;           
			}
		},
		orderable: true
	},
	{ data: 'labparameter', name: 'labparameter' },
	{
		data: null, mRender: function (data, type, full, meta) {
			if (data != '' && data != 'NULL' && data != undefined) {
				var notes = data['notes'];
				if (data['notes'] == null) {
					notes = '';
				} else {
					return notes;
				}
				//  return moment(lab_date).format('MM-DD-YYYY');;           
			}
		},
		orderable: true
	},
	{ data: 'action', name: 'action' }
];
/*
var imagingColumns = [
	{ data: 'DT_RowIndex', name: 'DT_RowIndex' },
	{
		data: null, mRender: function (data, type, full, meta) {
			if (data != '' && data != 'NULL' && data != undefined) {
				var imaging = data['imaging_details'];
				if (data['imaging_details'] == null) {
					imaging = '';
				}
				return imaging;
			}
		},
		orderable: true
	},
	{
		data: null, mRender: function (data, type, full, meta) {
			if (data != '' && data != 'NULL' && data != undefined) {
				var imaging_date = data['imaging_date'];
				if (data['imaging_date'] == null || data['imaging_date'] == 'null' || data['imaging_date'] == '') {
					imaging_date = '';
				} else {
					//return util.viewsDateFormat(lab_date);
					return moment(imaging_date).format('MM-DD-YYYY');
				}
				//  return moment(lab_date).format('MM-DD-YYYY');;           
			}
		},
		orderable: true
	},

	{
		data: null, mRender: function (data, type, full, meta) {
			if (data != '' && data != 'NULL' && data != undefined) {
				var comment = data['comment'];
				if (data['comment'] == null) {
					comment = '';
				} else {
					return comment;
				}
				//  return moment(lab_date).format('MM-DD-YYYY');;           
			}
		},
		orderable: true
	},
	{ data: 'action', name: 'action' }
];
*/


var vitalColumns = [
	{ data: 'DT_RowIndex', name: 'DT_RowIndex' },

	{
		data: null, mRender: function (data, type, full, meta) {
			if (data != '' && data != 'NULL' && data != undefined) {

				if (data['rec_date'] == null || data['rec_date'] == 'null' || data['rec_date'] == '') {
					var rec_date = '';
				} else {
					var rec_date = data['rec_date'];
				}
				return moment(rec_date).format('MM-DD-YYYY');
				//  return moment(lab_date).format('MM-DD-YYYY');;           
			}
		},
		orderable: true
	},
	{
		data: null, mRender: function (data, type, full, meta) {
			if (data != '' && data != 'NULL' && data != undefined) {

				if (data['height'] == null) {
					var height = '';
				} else {
					var height = data['height'];
				}
				return height;
			}
		},
		orderable: true
	},
	{
		data: null, mRender: function (data, type, full, meta) {
			if (data != '' && data != 'NULL' && data != undefined) {

				if (data['weight'] == null) {
					var weight = '';
				} else {
					var weight = data['weight'];
				}
				return weight;

			}
		},
		orderable: true
	},
	{
		data: null, mRender: function (data, type, full, meta) {
			if (data != '' && data != 'NULL' && data != undefined) {

				if (data['bmi'] == null) {
					var bmi = '';
				} else {
					var bmi = data['bmi'];
				}
				return bmi;

			}
		},
		orderable: true
	}, {
		data: null, mRender: function (data, type, full, meta) {
			if (data != '' && data != 'NULL' && data != undefined) {

				if (data['bp'] == null) {
					var bp = '';
				} else {
					var bp = data['bp'];
				}
				return bp;

			}
		},
		orderable: true
	},
	{
		data: null, mRender: function (data, type, full, meta) {
			if (data != '' && data != 'NULL' && data != undefined) {

				if (data['diastolic'] == null) {
					var diastolic = '';
				} else {
					var diastolic = data['diastolic'];
				}
				return diastolic;

			}
		},
		orderable: true
	},
	{
		data: null, mRender: function (data, type, full, meta) {
			if (data != '' && data != 'NULL' && data != undefined) {

				if (data['o2'] == null) {
					var o2 = '';
				} else {
					var o2 = data['o2'];
				}
				return o2;

			}
		},
		orderable: true
	},
	{
		data: null, mRender: function (data, type, full, meta) {
			if (data != '' && data != 'NULL' && data != undefined) {

				if (data['pulse_rate'] == null) {
					var pulse_rate = '';
				} else {
					var pulse_rate = data['pulse_rate'];
				}
				return pulse_rate;

			}
		},
		orderable: true
	},
	{
		data: null, mRender: function (data, type, full, meta) {
			if (data != '' && data != 'NULL' && data != undefined) {

				if (data['oxygen'] == null) {
					var oxygen = '';
				} else {
					var oxygen = data['oxygen'];
				}
				return oxygen;

			}
		},
		orderable: true
	},
	{
		data: null, mRender: function (data, type, full, meta) {
			if (data != '' && data != 'NULL' && data != undefined) {

				if (data['notes'] == null) {
					var notes = '';
				} else {
					var notes = data['notes'];
				}
				return notes;

			}
		},
		orderable: true
	}/*,
	{ data: 'action', name: 'action' }*/
];

var medicationColumns = [
	{ data: 'DT_RowIndex', name: 'DT_RowIndex' },
	{ data: 'name', name: 'name' },
	{ data: 'description', name: 'description' },
	{ data: 'purpose', name: 'purpose' },
	{ data: 'dosage', name: 'dosage' },
	{ data: 'strength', name: 'strength' },
	{ data: 'frequency', name: 'frequency' },
	{ data: 'route', name: 'route' },
	{ data: 'duration', name: 'duration' },
	{ data: 'pharmacy_name', name: 'pharmacy_name' },
	{ data: 'pharmacy_phone_no', name: 'pharmacy_phone_no' },
	{ data: 'users', name: 'users' },
	{ data: 'updated_at', name: 'updated_at' },
	{
		data: 'review', name: 'review',
		mRender: function (data, type, full, meta) {
			if (data != '' && data != 'NULL' && data != undefined) {
				return 'YES';
			} else {
				return 'NO';
			}
		}, orderable: false
	},
	{ data: 'action', name: 'action', orderable: false, searchable: false }
];

var allergiesColumns = [
	{ data: 'DT_RowIndex', name: 'DT_RowIndex' },
	{ data: 'specify', name: 'specify' },
	{ data: 'type_of_reactions', name: 'type_of_reactions' },
	{ data: 'severity', name: 'severity' },
	{ data: 'course_of_treatment', name: 'course_of_treatment' },
	{ data: 'allergy_status', name: 'allergy_status' },
	{
		data: 'users',
		mRender: function (data, type, full, meta) {
			if (data != '' && data != 'NULL' && data != undefined) {
				l_name = data['l_name'];
				if (data['l_name'] == null) {
					l_name = '';
				}
				return data['f_name'] + ' ' + l_name;
			} else {
				return '';
			}
		}, orderable: false
	},
	{ data: 'updated_at', name: 'updated_at' },
	{
		data: null,
		mRender: function (data, type, full, meta) {
			if (data != '' && data != 'NULL' && data != undefined) {
				if (full['review'] == '1') {
					return "Yes";
				} else {
					return "No";
				}
				if (full['review'] == null) {
					return "No";
				}
			}
		}, orderable: true
	},
	{ data: 'action', name: 'action', orderable: false, searchable: false }
];

var specilistColumns = [
	{ data: 'DT_RowIndex', name: 'DT_RowIndex' },
	{
		data: 'practice.name',
		mRender: function (data, type, full, meta) {
			if (data != '' && data != 'NULL' && data != undefined) {
				return data;
			} else {
				return 'Other';
			}
		}, orderable: false
	},
	{ data: 'provider.name', name: 'provider.name' },
	{ data: 'specility.speciality', name: 'specility.speciality' },
	{ data: 'provider_subtype.sub_provider_type', name: 'provider_subtype.sub_provider_type' },
	{ data: 'phone_no', name: 'phone_no' },
	{
		data: 'last_visit_date', name: 'last_visit_date', "render": function (value) {
			if (value === null) return "";
			return moment(value).format('MM-DD-YYYY');
		}
	},
	{
		data: 'users',
		mRender: function (data, type, full, meta) {
			if (data != '' && data != 'NULL' && data != undefined) {
				l_name = data['l_name'];
				if (data['l_name'] == null) {
					l_name = '';
				}
				return data['f_name'] + ' ' + l_name;
			} else {
				return '';
			}
		}, orderable: false
	},
	{ data: 'updated_at', name: 'updated_at' },
	{ data: 'action', name: 'action', orderable: false, searchable: false }
];
/**
 * Populate the form of the given patient
 *
 * 
 */
// var populateForm = function (data, url) {
var populateForm = function (id, url) {
	$("#preloader").css("display", "block");
	$.get(url, id,
		//data,
		function (result) {
			var module_id = $("form[name='part_of_research_study_form'] input[name='module_id']").val();
			var component_id = $("form[name='part_of_research_study_form'] input[name='component_id']").val();
			for (var key in result) {
				form.dynamicFormPopulate(key, result[key]);

				if ((key == 'diagnosis_code_form' || key == 'care_plan_form' || key == 'review_diagnosis_code_form') && result[key] != "") {

					if (result[key].static.hasOwnProperty('diagnosis')) {
						// alert("if");
						var code = result[key].static['code'];

						var diagnosis = result[key].static['diagnosis'];
						$("form[name='" + key + "'] #diagnosis_condition").val(diagnosis);
						$("form[name='" + key + "'] #diagnosis_code option:selected").val(code);

						if (diagnosis != null) {
							util.selectDiagnosisCode(diagnosis, $("form[name='" + key + "'] #diagnosis_code"), code);
						} else {
							util.selectDiagnosisCode(parseInt(diagnosis), $("form[name='" + key + "'] #diagnosis_code"));
						}
					}
					if (result[key].static.hasOwnProperty('diagnosis_id')) {
						// alert("else");
						var code = result[key].static['code'];
						// console.log('code');
						// console.log(code);
						var diagnosis = result[key].static['diagnosis_id'];
						$("form[name='" + key + "'] #diagnosis_condition").val(diagnosis);
						$("form[name='" + key + "'] #diagnosis_code option:selected").val(code);

						if (diagnosis != null) {
							util.selectDiagnosisCode(diagnosis, $("form[name='" + key + "'] #diagnosis_code"), code);
						} else {
							util.selectDiagnosisCode(parseInt(diagnosis), $("form[name='" + key + "'] #diagnosis_code"));
						}



					}

					if (result[key].static['symptoms'] != null) {
						var symptoms = JSON.parse(result[key].static['symptoms']);
						var symptomsCount = symptoms.length;
						if (symptomsCount != 0) {
							var inc_symptoms = 0;
							for (var symptom in symptoms) {
								if ($.trim(symptoms[symptom]) == "blank" || symptoms[symptom] == "" || symptoms[symptom] == null || symptoms[symptom] == "no symptoms") {
								}
								else {
									if (inc_symptoms == 0) {
										$("form[name='" + key + "'] #symptoms_0").val(symptoms[symptom]);
										$("form[name='" + key + "'] #symptoms_0").prop("disabled", true);
									} else {
										$("form[name='" + key + "'] #append_symptoms").append('<div class=" row btn_remove removesymptoms" id="btn_removesymptoms_' + inc_symptoms + '"><input type="text" class="form-control col-md-10 symptoms"  name ="symptoms[]" id ="symptoms_' + inc_symptoms + '" value="' + symptoms[symptom] + '" placeholder ="Enter Symptoms" disabled="disabled" ><div class="invalid-feedback"></div><i class="col-md-1 remove-icons i-Remove float-right mb-3" id="remove_symptoms_' + inc_symptoms + '" title="Remove Symptom" style="display:none"></i></div>');
										$("form[name='" + key + "'] #symptoms_" + inc_symptoms).val(symptoms[symptom]);
									}
									inc_symptoms++;
								}

							}
						} else {
							$("form[name='" + key + "'] #symptoms_0").val(symptoms[symptom]);
							$("form[name='" + key + "'] #symptoms_0").prop("disabled", true);
						}
					}
					if (result[key].static['goals'] != null) {
						var goals = JSON.parse(result[key].static['goals']);
						var goalcount = goals.length;
						if (goalcount != 0) {
							var inc_goals = 0;
							for (var goal in goals) {
								if ($.trim(goals[goal]) == "blank" || goals[goal] == "" || goals[goal] == null || goals[goal] == "no goals") {
								}
								else {
									if (inc_goals == 0) {
										$("form[name='" + key + "'] #goals_0").val(goals[goal]);
										$("form[name='" + key + "'] #goals_0").prop("disabled", "disabled");
									} else {
										$("form[name='" + key + "'] #append_goals").append('<div class="row btn_remove removegoals" id="btn_removegoals_' + inc_goals + '"><input type="text" class="form-control col-md-10 goals" name ="goals[]" id ="goals_' + inc_goals + '" value="' + goals[goal] + '" placeholder ="Enter Goals" disabled="disabled" ><div class="invalid-feedback"></div><i class="col-md-1 remove-icons i-Remove float-right mb-3" id="remove_goals_' + inc_goals + '" title="Remove Goals" style="display:none"></i></div>');
										$("form[name='" + key + "'] #goals_" + inc_goals).val(goals[goal]);
									}
									inc_goals++;
								}
							}
						} else {
							$("form[name='" + key + "'] #goals_0").val(goals[goal]);
							$("form[name='" + key + "'] #goals_0").prop("disabled", "disabled");
						}
					}
					if (result[key].static['tasks'] != null) {
						var tasks = JSON.parse(result[key].static['tasks']);
						var taskscount = tasks.length;
						if (taskscount != 0) {
							var inc_tasks = 0;
							for (var task in tasks) {
								if ($.trim(tasks[task]) == "blank" || tasks[task] == "" || tasks[task] == null || tasks[task] == "no tasks") {
								}
								else {
									if (inc_tasks == 0) {
										$("form[name='" + key + "'] #tasks_0").val(tasks[task]);
										$("form[name='" + key + "'] #tasks_0").prop("disabled", true);
										$("form[name='" + key + "'] #tasks_0" + inc_tasks).each(function () {

											$(this).height($(this).prop('scrollHeight'));
										});



									} else {
										$("form[name='" + key + "'] #append_tasks").append('<div class="row btn_remove removetasks" id="btn_removetasks_' + inc_tasks + '"><textarea class="mb-1 col-md-10 form-control tasks"  disabled="disabled"  name ="tasks[]" id ="tasks_' + inc_tasks + '" placeholder ="Enter tasks">' + tasks[task] + '</textarea><div class="invalid-feedback"></div><i class="col-md-1 remove-icons i-Remove float-right mb-3" id="remove_tasks_' + inc_tasks + '" title="Remove Tasks" style="display:none"></i></div>');
										$("form[name='" + key + "'] #tasks_" + inc_tasks).val(tasks[task])

										$("form[name='" + key + "'] #tasks_" + inc_tasks).each(function () {

											$(this).height($(this).prop('scrollHeight'));
										});


									}
									inc_tasks++;
								}
							}
						} else {
							$("form[name='" + key + "'] #tasks_0").val(tasks[task]);
							$("form[name='" + key + "'] #tasks_0").prop("disabled", true);
							$("form[name='" + key + "'] #tasks_0" + inc_tasks).each(function () {

								$(this).height($(this).prop('scrollHeight'));
							});


						}
					}

					if (result[key].static['comments'] != null) {
						var comments = result[key].static['comments'];
						$("form[name='" + key + "'] #diagnosis_comments").val(comments);
					}
				}

				if (key == 'number_tracking_labs_form') {
					//debugger;
					if (result[key].static['lab'] != null) {
						var labs = JSON.parse(result[key].static['lab']);
						$('#editform').val('edit');

						// labcount = labs.length;

						for (var lab in labs) {
							var inc_lab = labcount;
							// console.log(inc_lab + " lab count");

							if (inc_lab == 0) {
								$('#lab').val(labs[lab]);
								$("#labdate").attr('name', 'labdate[' + labs[lab] + '][]');
								$('#oldlab').val(labs[lab]);

							} else {
								var labElement = $('#lab').clone().prop('id', 'lab' + inc_lab);
								var labdate = $('#labdate').clone().prop('id', 'labdate' + inc_lab);
								//$('#append_labs').append('<div class="col-md-12 form-group mb-3 btn_remove" id="btn_removelabs_' + inc_lab + '"><label>Labs<span class="error">*</span> :</label><br/><div id="l' + inc_lab + '"><div class="invalid-feedback"></div></div><i class="col-md-1 remove-icons i-Remove float-right mb-3" id="remove_lebs_' + inc_lab + '" title="Remove Labs"></i><div class="row mb-3" id="append_labs_params_lab' + inc_lab + '"></div><hr/></div> ');
								$('#append_labs').append('<div class="col-md-12 form-group mb-3 btn_remove" id="btn_removelabs_' + inc_lab + '"><label>Labs<span class="error">*</span> :</label><span class="form-row"><div id="l' + inc_lab + '" class="col-md-4"></div><label>Date<span class="error">*</span> :</label><div id="date' + inc_lab + '" class="col-md-4"></div><div class="invalid-feedback"></div><i class="col-md-1 remove-icons i-Remove mb-3" id="remove_lebs_' + inc_lab + '" title="Remove Labs"></i></span><div class="form-row mb-3" id="append_labs_params_lab' + inc_lab + '"></div><hr/></div>');
								$('#l' + inc_lab).html(labElement);
								$('#date' + inc_lab).html(labdate);
								$('#l' + inc_lab).attr(("onChange", "addLabparam(this);"));
								$('#lab' + inc_lab).val(labs[lab]);
								$("#labdate" + inc_lab).attr('name', 'labdate[' + labs[lab] + '][]');
							}

							var lab_details = result["number_tracking_labs_details"].dynamic['lab'];
							var filteredLabs = $(lab_details).filter(function (idx) {
								return lab_details[idx].lab_test_id == labs[lab];
							});
							var labParam = "", i = 0;
							for (var labDetails in filteredLabs) {
								var high = "", normal = "", low = "", notes, test_not_performed = "", positive = "", negative = "";


								if (filteredLabs[labDetails]['lab_test_parameter_id'] != undefined) {

									if (inc_lab == 0) {
										var fulllabdate = filteredLabs[labDetails]['lab_date'];
										if (fulllabdate != '' && fulllabdate != null) {
											var res = fulllabdate.split(" ");
											$('#labdate').val(res[0]);
											$('#labdateexist').val(res[0]);
										}
										if (filteredLabs[labDetails]['lab_date'] == '' || filteredLabs[labDetails]['lab_date'] == null) {
											$('#olddate').val(filteredLabs[labDetails]['rec_date']);
										}
										else {
											$('#olddate').val(filteredLabs[labDetails]['lab_date']);
										}

									}
									else {
										$('#labdate' + inc_lab).val(filteredLabs[labDetails]['lab_date']);
										$('#labdateexist').val(filteredLabs[labDetails]['lab_date']);

									}
									notes = filteredLabs[labDetails]['notes'];

									if (labs[lab] != '0') {
										positive = ""; negative = ""; test_not_performed = "";
										if (filteredLabs[labDetails]['reading'] == 'high') {
											high = "selected";
										} else if (filteredLabs[labDetails]['reading'] == 'normal') {
											normal = "selected";
										} else if (filteredLabs[labDetails]['reading'] == 'low') {
											low = "selected";
										} else if (filteredLabs[labDetails]['reading'] == 'positive') {
											positive = "selected";
										} else if (filteredLabs[labDetails]['reading'] == 'negative') {
											negative = "selected";
										} else if (filteredLabs[labDetails]['reading'] == 'test_not_performed') {
											test_not_performed = "selected";
										}

										if (filteredLabs[labDetails]['parameter'] == "COVID-19") {

											labParam = labParam + "<div class='col-md-6 mb-3'>";
											labParam = labParam + "<label>" + filteredLabs[labDetails]['parameter'] + "</label>";
											labParam = labParam + "<input type='hidden' name='lab_test_id[" + labs[lab] + "][]'  value='" + labs[lab] + "'>";
											labParam = labParam + "<input type='hidden' name='lab_params_id[" + labs[lab] + "][]' value='" + filteredLabs[labDetails]['lab_test_parameter_id'] + "'>";
											labParam = labParam + "<div class='form-row'><div class='col-md-5'><select class='forms-element form-control mr-1 pl-3' name='reading[" + labs[lab] + "][]'><option value=''>Select Reading</option><option value='positive' " + positive + ">Positive</option><option value='negative' " + negative + ">Negative</option></select><div class='invalid-feedback'></div></div>";
											// labParam = labParam + "<div class='col-md-6'><input type='text' class='forms-element form-control' name='high_val[" + labs[lab] + "][]' value='" + filteredLabs[labDetails]['high_val'] + "'><div class='invalid-feedback'></div></div></div>";
											labParam = labParam + "</div></div>";
										}
										else {
											labParam = labParam + "<div class='col-md-6 mb-3'>";
											labParam = labParam + "<label>" + filteredLabs[labDetails]['parameter'] + "</label>";
											labParam = labParam + "<input type='hidden' name='lab_test_id[" + labs[lab] + "][]'  value='" + labs[lab] + "'>";
											labParam = labParam + "<input type='hidden' name='lab_params_id[" + labs[lab] + "][]' value='" + filteredLabs[labDetails]['lab_test_parameter_id'] + "'>";
											labParam = labParam + "<div class='form-row'><div class='col-md-5'><select class='forms-element form-control mr-1 pl-3' name='reading[" + labs[lab] + "][]'><option value=''>Select Reading</option><option value='high' " + high + ">High</option><option value='normal' " + normal + ">Normal</option><option value='low' " + low + ">Low</option><option value='test_not_performed' " + test_not_performed + ">Test not performed</option></select><div class='invalid-feedback'></div></div>";
											labParam = labParam + "<div class='col-md-6'><input type='text' class='forms-element form-control' name='high_val[" + labs[lab] + "][]' value='" + filteredLabs[labDetails]['high_val'] + "'><div class='invalid-feedback'></div></div></div>";
											labParam = labParam + "</div>";
										}

									}
								}
								i++;
							}
							labParam = labParam + '<div class="col-md-12 mb-3"><label>Notes:</label><textarea class="forms-element form-control" name="notes[' + labs[lab] + ']">' + notes + '</textarea><div class="invalid-feedback"></div></div>';
							if (inc_lab == 0) {
								$('#append_labs_params_lab').html(labParam);
							} else {
								$('#append_labs_params_lab' + inc_lab).html(labParam);
							}
							labcount++;
							//inc_lab++;
						}
					}
				}

				if ((key == 'provider_specialists_form' || key == 'review_provider_specialists_form') && result[key] != "") {
					var practice_id = result[key].static.practice_id;
					var provider_id = result[key].static.provider_id;
					var specialist_id = result[key].static.specialist_id;
					var provider_subtype_id = result[key].static.provider_subtype_id;
					var phone_no = result[key].static.phone_no;
					var last_visit_date = result[key].static.last_visit_date;
					var address = result[key].static.address;


					$("form[name='" + key + "'] #practice_name").hide();
					$("form[name='" + key + "'] #providers_name").hide();

					$("form[name='" + key + "'] #practices_div").removeClass('col-md-3');
					$("form[name='" + key + "'] #providers_div").removeClass('col-md-3');
					$("form[name='" + key + "'] #practices_div").addClass('col-md-6');
					$("form[name='" + key + "'] #providers_div").addClass('col-md-6');


					if (practice_id != null || practice_id != '' || practice_id == 0) {
						util.updatePhysicianList(
							practice_id,
							$("form[name='" + key + "'] #provider_id"),
							provider_id
						);

					}
					if (provider_id != null) {
						$("form[name='" + key + "'] #provider_id").val(provider_id);

					}
					if (specialist_id != null) {
						$("form[name='" + key + "'] #specialist").val(specialist_id);

					}
					if (provider_subtype_id != null) {
						$("form[name='" + key + "'] #credential").val(provider_subtype_id);

					}
					if (phone_no) {
						$("form[name='" + key + "'] #phone_no").val(phone_no);

					}
					if (last_visit_date != null) {
						var dateAr = last_visit_date.split('-');
						var newDate = dateAr[2] + '-' + dateAr[0] + '-' + dateAr[1];

						$("form[name='" + key + "'] input[name='last_visit_date']").val(newDate);
						//alert(newDate);
						//$("form[name='" + key + "'] #last_visit_date").val(last_visit_date);

					}
					if (address != null) {
						$("form[name='" + key + "'] #address").val(address);

					}
				}

				if ((key == 'provider_pcp_form' || key == 'review_provider_pcp_form') && result[key] != "") {
					var practice_id = result[key].static.practice_id;
					var provider_id = result[key].static.provider_id;

					var last_visit_date = result[key].static.last_visit_date;
					if (last_visit_date != null) {
						var dateAr = last_visit_date.split('-');
						var newDate = dateAr[2] + '-' + dateAr[0] + '-' + dateAr[1];
						$("form[name='" + key + "'] input[name='last_visit_date']").val(newDate);
					}

					if (practice_id != '') {
						//util.updatePhysicianList(
						util.updatePcpPhysicianList( //added by priya 25feb2021 for remove other option
							practice_id,
							$("form[name='" + key + "'] #provider_id"),
							provider_id
						);
					}
				}

				if ((key == 'provider_dentist_form' || key == 'review_provider_dentist_form') && result[key] != "") {
					var practice_id = result[key].static.practice_id;
					var provider_id = result[key].static.provider_id;
					var last_visit_date = result[key].static.last_visit_date;
					if (last_visit_date != null) {
						var dateAr = last_visit_date.split('-');
						var newDate = dateAr[2] + '-' + dateAr[0] + '-' + dateAr[1];
						$("form[name='" + key + "'] input[name='last_visit_date']").val(newDate);
					}
					if (practice_id != '' || practice_id == 0) {
						util.updatePhysicianList(
							practice_id,
							$("form[name='" + key + "'] #provider_id"),
							provider_id
						);
					}
				}
				if ((key == 'provider_vision_form' || key == 'review_provider_vision_form') && result[key] != "") {
					var practice_id = result[key].static.practice_id;
					var provider_id = result[key].static.provider_id;
					var last_visit_date = result[key].static.last_visit_date;
					if (last_visit_date != null) {
						var dateAr = last_visit_date.split('-');
						var newDate = dateAr[2] + '-' + dateAr[0] + '-' + dateAr[1];
						$("form[name='" + key + "'] input[name='last_visit_date']").val(newDate);
					}
					if (practice_id != '' || practice_id == 0) {
						util.updatePhysicianList(
							practice_id,
							$("form[name='" + key + "'] #provider_id"),
							provider_id
						);

					}
				}

				if (key == 'review_do_you_live_with_anyone_form' && result[key] != "") {
					var relational_status = result[key].static.relational_status;
					var live_fname = result[key].static.fname;
					var live_lname = result[key].static.lname;
					var live_relationship = result[key].static.relationship;
					var live_additional_notes = result[key].static.additional_notes;
					if (relational_status != null && relational_status == 0) {
						$("form[name='review_do_you_live_with_anyone_form'] #relational_status_no").prop("checked", true);
					} else {
						$("form[name='review_do_you_live_with_anyone_form'] #relational_status_yes").prop("checked", true);
						$("form[name='review_do_you_live_with_anyone_form'] #relational_status_no").prop("disabled", true);
					}

					if (live_fname != null && relational_status == 1) {
						$("form[name='review_do_you_live_with_anyone_form'] #review_live_fname_0").val(live_fname);
					}
					if (live_lname != null && relational_status == 1) {
						$("form[name='review_do_you_live_with_anyone_form'] #review_live_lname_0").val(live_lname);
					}
					if (live_relationship != null && relational_status == 1) {
						$("form[name='review_do_you_live_with_anyone_form'] #review_live_relationship_0").val(live_relationship);
					}
					if (live_additional_notes != null && relational_status == 1) {
						$("form[name='review_do_you_live_with_anyone_form'] #review_live_additional_notes_0").val(live_additional_notes);
					}
				} else if (key == 'review_do_you_live_with_anyone_form' && result[key] == "") {
					$("form[name='review_do_you_live_with_anyone_form'] #relational_status_yes").prop("checked", false);
					$("form[name='review_do_you_live_with_anyone_form'] #relational_status_no").prop("checked", false);
					$("form[name='review_do_you_live_with_anyone_form'] #relational_status_no").prop("disabled", false);

				}

				if ((key == 'sibling_form' || key == 'children_form' || key == 'grandchildren_form') && result[key] != "") {
					var relational_status = result[key].static.relational_status;
					var sibling_fname = result[key].static.fname;
					var sibling_lname = result[key].static.lname;
					var sibling_age = result[key].static.age;
					var sibling_location = result[key].static.address;
					var sibling_additional_notes = result[key].static.additional_notes;

					if (relational_status != null && relational_status == 0) {
						$("form[name='" + key + "'] #relational_status_no").prop("checked", true);
					} else {
						$("form[name='" + key + "'] #relational_status_yes").prop("checked", true);
						$("form[name='" + key + "'] #relational_status_no").prop("disabled", true);
					}
					if (sibling_fname != null && relational_status == 1) {
						$("form[name='" + key + "'] #review_sibling_fname").val(sibling_fname);
					}
					if (sibling_lname != null && relational_status == 1) {
						$("form[name='" + key + "'] #review_sibling_lname").val(sibling_lname);
					}
					if (sibling_age != null && relational_status == 1) {
						$("form[name='" + key + "'] #review_sibling_age").val(sibling_age);
					}
					if (sibling_location != null && relational_status == 1) {
						$("form[name='" + key + "'] #review_sibling_address").val(sibling_location);
					}
					if (sibling_additional_notes != null && relational_status == 1) {
						$("form[name='" + key + "'] #review_sibling_additional_notes").val(sibling_additional_notes);
					}
				} else if ((key == 'sibling_form' || key == 'children_form' || key == 'grandchildren_form') && result[key] == "") {
					$("form[name='" + key + "'] #relational_status_yes").prop("checked", false);
					$("form[name='" + key + "'] #relational_status_no").prop("checked", false);
					$("form[name='" + key + "'] #relational_status_no").prop("disabled", false);

				}

				if (key == 'review_pets_form' && result[key] != "") {
					var pet_status = result[key].static.pet_status;
					var pet_name = result[key].static.pet_name;
					var pet_type = result[key].static.pet_type;
					var pet_notes = result[key].static.notes;
					if (pet_status != null && pet_status == 0) {
						$("form[name='review_pets_form'] #pet_status_no").prop("checked", true);
					} else {
						$("form[name='review_pets_form'] #pet_status_yes").prop("checked", true);
						$("form[name='review_pets_form'] #pet_status_no").prop("disabled", true);
					}
					if (pet_name != null && pet_status == 1) {
						$("form[name='review_pets_form'] #review_pet_name").val(pet_name);
					} if (pet_type != null && pet_status == 1) {
						$("form[name='review_pets_form'] #review_pet_type").val(pet_type);
					} if (pet_notes != null && pet_status == 1) {
						$("form[name='review_pets_form'] #review_pet_notes").val(pet_notes);
					}
				} else if (key == 'review_pets_form' && result[key] == '') {
					$("form[name='review_pets_form'] #pet_status_yes").prop("checked", false);
					$("form[name='review_pets_form'] #pet_status_no").prop("checked", false);
					$("form[name='review_pets_form'] #pet_status_no").prop("disabled", false);
				}

				if (key == 'review_hobbies_form' && result[key] != "") {
					var hobbies_status = result[key].static.hobbies_status;
					var hobbies_description = result[key].static.hobbies_name;
					var hobbies_location = result[key].static.location;
					var hobbies_frequency = result[key].static.frequency;
					var hobbies_with_whom = result[key].static.with_whom;
					var hobbies_notes = result[key].static.notes;
					if (hobbies_status != null && hobbies_status == 0) {
						$("form[name='review_hobbies_form'] #hobbies_status_no").prop("checked", true);
					} else {
						$("form[name='review_hobbies_form'] #hobbies_status_yes").prop("checked", true);
						$("form[name='review_hobbies_form'] #hobbies_status_no").prop("disabled", true);
					}
					if (hobbies_description != null && hobbies_status == 1) {
						$("form[name='review_hobbies_form'] #review_hobbie_description").val(hobbies_description);
					}
					if (hobbies_location != null && hobbies_status == 1) {
						$("form[name='review_hobbies_form'] #review_hobbie_location").val(hobbies_location);
					}
					if (hobbies_frequency != null && hobbies_status == 1) {
						$("form[name='review_hobbies_form'] #review_hobbie_frequency").val(hobbies_frequency);
					}
					if (hobbies_with_whom != null && hobbies_status == 1) {
						$("form[name='review_hobbies_form'] #review_hobbie_with_whom").val(hobbies_with_whom);
					}
					if (hobbies_notes != null && hobbies_status == 1) {
						$("form[name='review_hobbies_form'] #review_hobbie_notes").val(hobbies_notes);
					}

				} else if (key == 'review_hobbies_form' && result[key] == "") {
					$("form[name='review_hobbies_form'] #hobbies_status_yes").prop("checked", false);
					$("form[name='review_hobbies_form'] #hobbies_status_no").prop("checked", false);
					$("form[name='review_hobbies_form'] #hobbies_status_no").prop("disabled", false);
				}

				if (key == 'review_travel_form' && result[key] != "") {
					var travel_status = result[key].static.travel_status;
					var travel_location = result[key].static.location;
					var travel_travel_type = result[key].static.travel_type;
					var travel_frequency = result[key].static.frequency;
					var travel_with_whom = result[key].static.with_whom;
					var travel_upcoming_tips = result[key].static.upcoming_tips;
					var travel_notes = result[key].static.notes;
					if (travel_status != null && travel_status == 0) {
						$("form[name='review_travel_form'] #travel_status_no").prop("checked", true);
					} else {
						$("form[name='review_travel_form'] #travel_status_yes").prop("checked", true);
						$("form[name='review_travel_form'] #travel_status_no").prop("disabled", true);
					}
					if (travel_location != null && travel_status == 1) {
						$("form[name='review_travel_form'] #review_travel_location").val(travel_location);
					} if (travel_travel_type != null && travel_status == 1) {
						$("form[name='review_travel_form'] #review_travel_travel_type").val(travel_travel_type);
					} if (travel_frequency != null && travel_status == 1) {
						$("form[name='review_travel_form'] #review_travel_frequency").val(travel_frequency);
					} if (travel_with_whom != null && travel_status == 1) {
						$("form[name='review_travel_form'] #review_travel_with_whom").val(travel_with_whom);
					} if (travel_upcoming_tips != null && travel_status == 1) {
						$("form[name='review_travel_form'] #review_travel_upcoming_tips").val(travel_upcoming_tips);
					} if (travel_notes != null && travel_status == 1) {
						$("form[name='review_travel_form'] #review_travel_notes").val(travel_notes);
					}
				} else if (key == 'review_travel_form' && result[key] == "") {
					$("form[name='review_travel_form'] #travel_status_yes").prop("checked", false);
					$("form[name='review_travel_form'] #travel_status_no").prop("checked", false);
					$("form[name='review_travel_form'] #travel_status_no").prop("disabled", false);
				}

				if (key == 'number_tracking_imaging_form') {
					if (result[key].static['imaging'] != null) {
						var imagingDetails = JSON.parse(result[key].static['imaging']);
						var imagingDate = JSON.parse(result[key].static['imaging_date']);
						var comment = JSON.parse(result[key].static['comment']);
						// imagingcount = 0;
						for (var imaging in imagingDetails) {


							inc_imaging = imagingcount;
							if (imagingcount == 0) {
								$('#imaging_0').val(imagingDetails[imaging]);
								$('#imaging_date').val(imagingDate[imaging]);

							} else {
								$('#append_imaging').append('<div class="row btn_remove" id="btn_removeimaging_' + inc_imaging + '"><div class="col-md-4"><input type="text" class="form-control" name="imaging[]" id="imaging_' + imagingcount + '" placeholder ="Enter Imaging" value="' + imagingDetails[imaging] + '"><div class="invalid-feedback"></div></div><div class="col-md-4"><input type="date" class="form-control" name="imaging_date[]" id="imaging_date' + imagingcount + '"placeholder ="Enter Imaging Date" value="' + imagingDate[imaging] + '"><div class="invalid-feedback"></div></div><i class="col-md-1 remove-icons i-Remove float-right mb-3" id="remove_imaging_' + imagingcount + '" title="Remove Imaging"></i></div>');
							}
							$('#imaging_comment').val(comment[imaging]);
							imagingcount++;
						}
					}
				}

				if (key == 'number_tracking_healthdata_form') {
					if (result[key].static['healthdata'] != null) {
						var healthDetails = JSON.parse(result[key].static['healthdata']);
						var healthDates = JSON.parse(result[key].static['health_date']);
						var comment = JSON.parse(result[key].static['comment']);
						// imagingcount = 0;
						for (var healthdata in healthDetails) {
							//console.log("imaging" + imaging + "=====  " + comment[healthdata]);
							inc_healthdata = healthdatacount;
							if (healthdatacount == 0) {
								$('#healthdata_0').val(healthDetails[healthdata]);
								$('#health_date').val(healthDates[healthdata]);
								//$('#comment').val(comment[healthdata]);
							} else {
								$('#append_healthdata').append('<div class="row btn_remove" id="btn_removehealthdata_' + inc_healthdata + '"><div class="col-md-4"><input type="text" class="form-control"  name="health_data[]" id ="healthdata_' + inc_healthdata + '" placeholder ="Enter Health Data" value="' + healthDetails[healthdata] + '"><div class="invalid-feedback"></div></div><div class="col-md-4"><input type ="date" class="form-control" name="health_date[]" id="healthdate' + inc_healthdata + '" placeholder ="Enter Health Data Date" value="' + healthDates[healthdata] + '"><div class="invalid-feedback"></div></div><i class="col-md-1 remove-icons i-Remove float-right mb-3" id="remove_healthdata_' + inc_healthdata + '" title="Remove health data"></i></div>');

							}
							$('#healthdata_comment').val(comment[healthdata]);
							healthdatacount++;
						}
					}
				}
				if (key == 'call_close_form') {
					if (result[key] != '') {
						var date_enrolled = result[key].static['q2_datetime'];
						if (date_enrolled != undefined) {
							var a = date_enrolled.split(" ")[0];
							var day = a.split("-");
							$mydate = day[2] + '-' + day[0] + '-' + day[1];
							document.getElementById("next_month_call_date").value = $mydate;
						}
					}
				}
			}
			$("form[name='part_of_research_study_form'] input[name='module_id']").val(module_id);
			$("form[name='part_of_research_study_form'] input[name='component_id']").val(component_id);
			$("form[name='personal_notes_form'] input[name='module_id']").val(module_id);
			$("form[name='personal_notes_form'] input[name='component_id']").val(component_id);
			// $("#basix-info-address").val(address);
			$("form[name='patient_threshold_form'] input[name='module_id']").val(module_id);
			$("form[name='patient_threshold_form'] input[name='component_id']").val(component_id);

			$("#preloader").css("display", "none");
		}
	).fail(function (result) {
		console.error("Population Error:", result);
	});
};

function nextMove(id) {
	//alert('here');
	$('#' + id).click()
}

/**
 * Add a Allergies via Ajax request
 */

//Family Data
var onFamilyData = function (formObj, fields, response) {
	var form_name = fields.values.form_name;
	if (response.status == 200) {
		var patient_id = $("input[name='patient_id']").val();
		var module_id = $("input[name='module_id']").val();
		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
		if (!form_name.includes("review_")) {
			$("#" + form_name)[0].reset();
		}
		CompletedCheck();
		populateformdata();
		util.getPatientDetails(patient_id, module_id);
		if (form_name == 'family_patient_data_form' || form_name == 'review_family_patient_data_form') {
			var address = $("form[name='" + form_name + "'] #patient_address").val();
			$("#basix-info-address").val(address);
		}
		var scrollPos = $(".main-content").offset().top;
		$(window).scrollTop(scrollPos);
		$("form[name='" + form_name + "'] .alert-success").show();
		$("form[name='" + form_name + "'] .alert-danger").hide();
		setTimeout(function () {
			$('.alert-success').fadeOut();
		}, 3000);
		var timer_paused = $("form[name='" + form_name + "'] input[name='end_time']").val();
		$("#timer_start").val(timer_paused);
	} else {
		$("form[name='" + form_name + "'] .alert-success").hide();
		$("form[name='" + form_name + "'] .alert-danger").show();
	}
};

// var onPatientDataFamily = function (formObj, fields, response) {
// 	if (response.status == 200) {
// 		var patient_id = $("input[name='patient_id']").val();
// 		var module_id = $("input[name='module_id']").val();
// 		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 		$("#family_patient_data_form")[0].reset();
// 		$("form[name='family_patient_data_form'] .alert-success").show();
// 		$("form[name='family_patient_data_form'] .alert-danger").hide();
// 		var scrollPos = $(".main-content").offset().top;
// 		CompletedCheck();
// 		populateformdata();
// 		util.getPatientDetails(patient_id, module_id);
// 		//util.getPatientDetailsModel(patient_id, module_id);
// 		var address = $("form[name='family_patient_data_form'] #patient_address").val();
// 		$("#basix-info-address").val(address);
// 		$(window).scrollTop(scrollPos);
// 		setTimeout(function () {
// 			$('.alert-success').fadeOut();
// 		}, 3000);
// 		var timer_paused = $("form[name='family_patient_data_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 	} else {
// 		$("form[name='family_patient_data_form'] .alert-success").hide();
// 		$("form[name='family_patient_data_form'] .alert-danger").show();
// 	}
// };

// var onSpouseFamily = function (formObj, fields, response) {
// 	//console.log(response);
// 	if (response.status == 200) {
// 		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 		$("#family_spouse_form")[0].reset();
// 		$("form[name='family_spouse_form'] .alert-success").show();
// 		$("form[name='family_spouse_form'] .alert-danger").hide();
// 		var scrollPos = $(".main-content").offset().top;
// 		CompletedCheck();
// 		populateformdata();
// 		$(window).scrollTop(scrollPos);
// 		setTimeout(function () {
// 			$('.alert-success').fadeOut('fast');
// 		}, 3000);
// 		var timer_paused = $("form[name='family_spouse_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 	} else {
// 		$("form[name='family_spouse_form'] .alert-success").hide();
// 		$("form[name='family_spouse_form'] .alert-danger").show();
// 	}
// };

// var onEmergencyContactFamily = function (formObj, fields, response) {
// 	//console.log(response);
// 	if (response.status == 200) {
// 		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 		$("#family_emergency_contact_form")[0].reset();
// 		$("form[name='family_emergency_contact_form'] .alert-success").show();
// 		$("form[name='family_emergency_contact_form'] .alert-danger").hide();
// 		var scrollPos = $(".main-content").offset().top;
// 		CompletedCheck();
// 		populateformdata();
// 		$(window).scrollTop(scrollPos);
// 		setTimeout(function () {
// 			$('.alert').fadeOut('alert-success');
// 		}, 3000);
// 		var timer_paused = $("form[name='family_emergency_contact_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 		// $("#timer_end").val(timer_paused);
// 	}
// 	else {
// 		$("form[name='family_emergency_contact_form'] .alert-success").hide();
// 		$("form[name='family_emergency_contact_form'] .alert-danger").show();
// 	}
// };

// var onCareGiverFamily = function (formObj, fields, response) {
// 	//console.log(response);
// 	if (response.status == 200) {
// 		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 		$("#family_care_giver_form")[0].reset();
// 		$("form[name='family_care_giver_form'] .alert-success").show();
// 		$("form[name='family_care_giver_form'] .alert-danger").hide();
// 		var scrollPos = $(".main-content").offset().top;
// 		CompletedCheck();
// 		populateformdata();
// 		$(window).scrollTop(scrollPos);
// 		//goToNextStep("call_step_1_id");
// 		setTimeout(function () {
// 			$('.alert-success').fadeOut('fast');//goToNextStep("drug-icon-pill"); 
// 		}, 3000);
// 		var timer_paused = $("form[name='family_care_giver_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 		// $("#timer_end").val(timer_paused);
// 	} else {
// 		$("form[name='family_care_giver_form'] .alert-success").hide();
// 		$("form[name='family_care_giver_form'] .alert-danger").show();
// 	}
// };

// Allergies

var onAllergy = function (formObj, fields, response) {
	var form_name = fields.values.form_name;
	if (response.status == 200) {
		$("#" + form_name)[0].reset();
		$("form[name='" + form_name + "'] #id").val('');
		var allergy_type = $("form[name='" + form_name + "'] input[name='allergy_type']").val();
		var id = $("#patient_id").val();
		var patient_id = $("input[name='patient_id']").val();
		var module_id = $("input[name='module_id']").val();
		util.getPatientCareplanNotes(patient_id, module_id);
		util.refreshAllergyCountCheckbox(id, allergy_type, form_name);
		if (module == 'care-plan-development') {
			util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
			if (!form_name.includes("review_")) {
				$("#review_" + form_name)[0].reset();
				$("form[name='review_" + form_name + "'] #id").val('');
			}
			if (form_name == 'allergy_food_form' || form_name == 'review_allergy_food_form') {
				renderFoodTable();
			} else if (form_name == 'allergy_drug_form' || form_name == 'review_allergy_drug_form') {
				renderdrugTable();
			} else if (form_name == 'allergy_enviromental_form' || form_name == 'review_allergy_enviromental_form') {
				renderEnviromentalTable();
			} else if (form_name == 'allergy_insect_form' || form_name == 'review_allergy_insect_form') {
				renderinsectTable();
			} else if (form_name == 'allergy_latex_form' || form_name == 'review_allergy_latex_form') {
				renderLatexTable();
			} else if (form_name == 'allergy_pet_related_form' || form_name == 'review_allergy_pet_related_form') {
				renderPetRelatedTable();
			} else if (form_name == 'allergy_other_allergy_form' || form_name == 'review_allergy_other_allergy_form') {
				renderAllergyOtherTable();
			}
			CompletedCheck();
		} else {
			util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
			if (form_name == 'allergy_food_form') {
				carePlanDevelopment.renderFoodTableData();
			} else if (form_name == 'allergy_drug_form') {
				carePlanDevelopment.renderdrugTableData();
			} else if (form_name == 'allergy_enviromental_form') {
				carePlanDevelopment.renderEnviromentalTableData();
			} else if (form_name == 'allergy_insect_form') {
				carePlanDevelopment.renderinsectTableData();
			} else if (form_name == 'allergy_latex_form') {
				carePlanDevelopment.renderLatexTableData();
			} else if (form_name == 'allergy_pet_related_form') {
				carePlanDevelopment.renderPetRelatedTableData();
			} else if (form_name == 'allergy_other_allergy_form') {
				carePlanDevelopment.renderAllergyOtherTableData();
			}
		}
		$("form[name ='" + form_name + "']")[0].reset();
		$("form[name='" + form_name + "'] input[name='specify']").prop("disabled", false);
		$("form[name='" + form_name + "'] input[name='type_of_reactions']").prop("disabled", false);
		$("form[name='" + form_name + "'] input[name='severity']").prop("disabled", false);
		$("form[name='" + form_name + "'] input[name='course_of_treatment']").prop("disabled", false);
		$("form[name='" + form_name + "'] textarea[name='notes']").prop("disabled", false);
		var scrollPos = $(".main-content").offset().top;
		$(window).scrollTop(scrollPos);
		$("form[name='" + form_name + "'] .alert-success").show();
		$("form[name='" + form_name + "'] .alert-danger").hide();
		setTimeout(function () {
			$('.alert-success').fadeOut('fast');//goToNextStep("drug-icon-pill"); 
		}, 3000);
		var timer_paused = $("form[name='" + form_name + "'] input[name='end_time']").val();
		$("#timer_start").val(timer_paused);
		// $("#timer_end").val(timer_paused);
	} else {
		$("form[name='" + form_name + "'] .alert-success").hide();
		$("form[name='" + form_name + "'] .alert-danger").show();
	}
};

// var onFoodAllergy = function (formObj, fields, response) {
// 	if (response.status == 200) {
// 		$("form[name='allergy_food_form'] .alert-danger").hide();
// 		$("#allergy_food_form")[0].reset();
// 		$('form[name="allergy_food_form"] #id').val('');
// 		$("form[name='allergy_food_form'] .alert-success").show();
// 		var scrollPos = $(".main-content").offset().top;
// 		var allergy_type = $('form[name="allergy_food_form"] input[name="allergy_type"]').val();
// 		var id = $("#patient_id").val();
// 		var patient_id = $("input[name='patient_id']").val();
//     	var module_id = $("input[name='module_id']").val();
// 		util.getPatientCareplanNotes(patient_id, module_id);
// 		util.refreshAllergyCountCheckbox(id, allergy_type, 'allergy_food_form');
// 		if (module == 'care-plan-development') {
// 			util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 			$("#review_allergy_food_form")[0].reset();
// 			$('form[name="review_allergy_food_form"] #id').val('');
// 			renderFoodTable();
// 			CompletedCheck();
// 		} else {
// 			util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
// 			carePlanDevelopment.renderFoodTableData();
// 		}
// 		$("form[name ='allergy_food_form']")[0].reset();
// 		$("form[name='allergy_food_form'] input[name='specify']").prop("disabled", false);
// 		$("form[name='allergy_food_form'] input[name='type_of_reactions']").prop("disabled", false);
// 		$("form[name='allergy_food_form'] input[name='severity']").prop("disabled", false);
// 		$("form[name='allergy_food_form'] input[name='course_of_treatment']").prop("disabled", false);
// 		$("form[name='allergy_food_form'] textarea[name='notes']").prop("disabled", false);
// 		$(window).scrollTop(scrollPos);
// 		setTimeout(function () {
// 			$('.alert-success').fadeOut('fast');//goToNextStep("drug-icon-pill"); 
// 		}, 3000);
// 		var timer_paused = $("form[name='allergy_food_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 		// $("#timer_end").val(timer_paused);
// 	} else {
// 		$("form[name='allergy_food_form'] .alert-success").hide();
// 		$("form[name='allergy_food_form'] .alert-danger").show();
// 	}
// };

// var onDrugAllergy = function (formObj, fields, response) {
// 	if (response.status == 200) {
// 		$("form[name='allergy_drug_form'] .alert-danger").hide();
// 		$("#allergy_drug_form")[0].reset();
// 		$('form[name="allergy_drug_form"] #id').val('');
// 		var allergy_type = $('form[name="allergy_drug_form"] input[name="allergy_type"]').val();
// 		var id = $("#patient_id").val();
// 		util.refreshAllergyCountCheckbox(id, allergy_type, 'allergy_drug_form');
// 		renderdrugTable();
// 		var patient_id = $("input[name='patient_id']").val();
//     	var module_id = $("input[name='module_id']").val();
// 		util.getPatientCareplanNotes(patient_id, module_id);
// 		if (module == 'care-plan-development') {
// 			util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 			$("#review_allergy_drug_form")[0].reset();
// 			$('form[name="review_allergy_drug_form"] #id').val('');

// 			CompletedCheck();
// 			renderdrugTable();
// 		} else {
// 			util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
// 			carePlanDevelopment.renderdrugTableData();
// 		}

// 		$("form[name ='allergy_drug_form']")[0].reset();
// 		$("form[name='allergy_drug_form'] input[name='specify']").prop("disabled", false);
// 		$("form[name='allergy_drug_form'] input[name='type_of_reactions']").prop("disabled", false);
// 		$("form[name='allergy_drug_form'] input[name='severity']").prop("disabled", false);
// 		$("form[name='allergy_drug_form'] input[name='course_of_treatment']").prop("disabled", false);
// 		$("form[name='allergy_drug_form'] textarea[name='notes']").prop("disabled", false);
// 		$("form[name='allergy_drug_form'] .alert-success").show();
// 		var scrollPos = $(".main-content").offset().top;
// 		$(window).scrollTop(scrollPos);
// 		setTimeout(function () {
// 				$('.alert-success').fadeOut('fast');
// 		}, 3000);
// 		var timer_paused = $("form[name='allergy_drug_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 	} else {
// 		$("form[name='allergy_drug_form'] .alert-success").hide();
// 		$("form[name='allergy_drug_form'] .alert-danger").show();
// 	}
// };

// var onEnviromentalAllergy = function (formObj, fields, response) {
// 	if (response.status == 200) {
// 		$("form[name='allergy_enviromental_form'] .alert-danger").hide();
// 		$("#allergy_enviromental_form")[0].reset();
// 		$('form[name="allergy_enviromental_form"] #id').val('');
// 		$("form[name='allergy_enviromental_form'] .alert-success").show();
// 		var scrollPos = $(".main-content").offset().top;
// 		var allergy_type = $('form[name="allergy_enviromental_form"] input[name="allergy_type"]').val();
// 		var id = $("#patient_id").val();
// 		util.refreshAllergyCountCheckbox(id, allergy_type, 'allergy_enviromental_form');
// 		renderEnviromentalTable();
// 		var patient_id = $("input[name='patient_id']").val();
//     	var module_id = $("input[name='module_id']").val();
// 		util.getPatientCareplanNotes(patient_id, module_id);
// 		if (module == 'care-plan-development') {
// 			util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 			$("#review_allergy_enviromental_form")[0].reset();
// 			$('form[name="review_allergy_enviromental_form"] #id').val('');
// 			renderEnviromentalTable();
// 			CompletedCheck();
// 		} else {
// 			util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
// 			carePlanDevelopment.renderEnviromentalTableData();
// 		}
// 		$("form[name ='allergy_enviromental_form']")[0].reset();
// 		$("form[name='allergy_enviromental_form'] input[name='specify']").prop("disabled", false);
// 		$("form[name='allergy_enviromental_form'] input[name='type_of_reactions']").prop("disabled", false);
// 		$("form[name='allergy_enviromental_form'] input[name='severity']").prop("disabled", false);
// 		$("form[name='allergy_enviromental_form'] input[name='course_of_treatment']").prop("disabled", false);
// 		$("form[name='allergy_enviromental_form'] textarea[name='notes']").prop("disabled", false);
// 		$(window).scrollTop(scrollPos);
// 		setTimeout(function () {
// 			$('.alert-success').fadeOut('fast'); //goToNextStep("drug-icon-pill"); 
// 		}, 3000);
// 		var timer_paused = $("form[name='allergy_enviromental_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 	} else {
// 		$("form[name='allergy_enviromental_form'] .alert-success").hide();
// 		$("form[name='allergy_enviromental_form'] .alert-danger").show();
// 	}
// };

// var onInsectAllergy = function (formObj, fields, response) {
// 	if (response.status == 200) {
// 		$("form[name='allergy_insect_form'] .alert-danger").hide();
// 		$("#allergy_insect_form")[0].reset();
// 		$('form[name="allergy_insect_form"] #id').val('');
// 		$("form[name='allergy_insect_form'] .alert-success").show();
// 		var allergy_type = $('form[name="allergy_insect_form"] input[name="allergy_type"]').val();
// 		var id = $("#patient_id").val();
// 		util.refreshAllergyCountCheckbox(id, allergy_type, 'allergy_insect_form');
// 		renderinsectTable();
// 		var patient_id = $("input[name='patient_id']").val();
//     	var module_id = $("input[name='module_id']").val();
// 		util.getPatientCareplanNotes(patient_id, module_id);
// 		if (module == 'care-plan-development') {
// 			util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 			$("#review_allergy_insect_form")[0].reset();
// 			$('form[name="review_allergy_insect_form"] #id').val('');
// 			renderinsectTable();
// 			CompletedCheck();

// 		} else {
// 			util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
// 			carePlanDevelopment.renderinsectTableData();
// 		}
// 		var scrollPos = $(".main-content").offset().top;
// 		$(window).scrollTop(scrollPos);
// 		$("form[name ='allergy_insect_form']")[0].reset();
// 		$("form[name='allergy_insect_form'] input[name='specify']").prop("disabled", false);
// 		$("form[name='allergy_insect_form'] input[name='type_of_reactions']").prop("disabled", false);
// 		$("form[name='allergy_insect_form'] input[name='severity']").prop("disabled", false);
// 		$("form[name='allergy_insect_form'] input[name='course_of_treatment']").prop("disabled", false);
// 		$("form[name='allergy_insect_form'] textarea[name='notes']").prop("disabled", false);
// 		setTimeout(function () {
// 			$('.alert-success').fadeOut('fast');//goToNextStep("drug-icon-pill"); 
// 		}, 3000);
// 		var timer_paused = $("form[name='allergy_insect_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 	} else {
// 		$("form[name='allergy_insect_form'] .alert-success").hide();
// 		$("form[name='allergy_insect_form'] .alert-danger").show();
// 	}
// };

// var onLatexAllergy = function (formObj, fields, response) {
// 	if (response.status == 200) {
// 		$("form[name='allergy_latex_form'] .alert-danger").hide();
// 		$("#allergy_latex_form")[0].reset();
// 		$("form[name='allergy_latex_form'] .alert-success").show();
// 		$('form[name="allergy_latex_form"] #id').val('');
// 		var scrollPos = $(".main-content").offset().top;
// 		var allergy_type = $('form[name="allergy_latex_form"] input[name="allergy_type"]').val();
// 		var id = $("#patient_id").val();
// 		util.refreshAllergyCountCheckbox(id, allergy_type, 'allergy_latex_form');
// 		renderLatexTable();
// 		var patient_id = $("input[name='patient_id']").val();
//     	var module_id = $("input[name='module_id']").val();
// 		util.getPatientCareplanNotes(patient_id, module_id);
// 		if (module == 'care-plan-development') {
// 			util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 			$("#review_allergy_latex_form")[0].reset();
// 			$('form[name="review_allergy_latex_form"] #id').val('');
// 			renderLatexTable();

// 			CompletedCheck();
// 		} else {
// 			util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
// 			carePlanDevelopment.renderLatexTableData();
// 		}
// 		$("form[name ='allergy_latex_form']")[0].reset();
// 		$("form[name='allergy_latex_form'] input[name='specify']").prop("disabled", false);
// 		$("form[name='allergy_latex_form'] input[name='type_of_reactions']").prop("disabled", false);
// 		$("form[name='allergy_latex_form'] input[name='severity']").prop("disabled", false);
// 		$("form[name='allergy_latex_form'] input[name='course_of_treatment']").prop("disabled", false);
// 		$("form[name='allergy_latex_form'] textarea[name='notes']").prop("disabled", false);
// 		$(window).scrollTop(scrollPos);
// 		setTimeout(function () {
// 			$('.alert-success').fadeOut('fast');//goToNextStep("drug-icon-pill"); 
// 		}, 3000);
// 		var timer_paused = $("form[name='allergy_latex_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 	} else {
// 		$("form[name='allergy_latex_form'] .alert-success").hide();
// 		$("form[name='allergy_latex_form'] .alert-danger").show();
// 	}
// };

// var onPetRelatedAllergy = function (formObj, fields, response) {
// 	if (response.status == 200) {
// 		$("#allergy_pet_related_form")[0].reset();
// 		$("form[name='allergy_pet_related_form'] .alert-success").show();
// 		$("form[name='allergy_pet_related_form'] .alert-danger").hide();
// 		$('form[name="allergy_pet_related_form"] #id').val('');
// 		var scrollPos = $(".main-content").offset().top;
// 		var allergy_type = $('form[name="allergy_pet_related_form"] input[name="allergy_type"]').val();
// 		var id = $("#patient_id").val();
// 		util.refreshAllergyCountCheckbox(id, allergy_type, 'allergy_pet_related_form');
// 		renderPetRelatedTable();
// 		var patient_id = $("input[name='patient_id']").val();
//     	var module_id = $("input[name='module_id']").val();
// 		util.getPatientCareplanNotes(patient_id, module_id);
// 		if (module == 'care-plan-development') {
// 			util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 			$("#review_allergy_pet_related_form")[0].reset();
// 			$('form[name="review_allergy_pet_related_form"] #id').val('');
// 			renderPetRelatedTable();
// 			CompletedCheck();
// 		} else {
// 			util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
// 			carePlanDevelopment.renderPetRelatedTableData();

// 		}
// 		$("form[name ='allergy_pet_related_form']")[0].reset();
// 		$("form[name='allergy_pet_related_form'] input[name='specify']").prop("disabled", false);
// 		$("form[name='allergy_pet_related_form'] input[name='type_of_reactions']").prop("disabled", false);
// 		$("form[name='allergy_pet_related_form'] input[name='severity']").prop("disabled", false);
// 		$("form[name='allergy_pet_related_form'] input[name='course_of_treatment']").prop("disabled", false);
// 		$("form[name='allergy_pet_related_form'] textarea[name='notes']").prop("disabled", false);
// 		$("#allergy_pet_related_form")[0].reset();
// 		$(window).scrollTop(scrollPos);
// 		setTimeout(function () {
// 			$('.alert-success').fadeOut('fast');//goToNextStep("drug-icon-pill"); 
// 		}, 3000);
// 		var timer_paused = $("form[name='allergy_pet_related_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 	} else {
// 		$("form[name='allergy_pet_related_form'] .alert-success").hide();
// 		$("form[name='allergy_pet_related_form'] .alert-danger").show();
// 	}
// };

// var onOtherAllergy = function (formObj, fields, response) {
// 	if (response.status == 200) {
// 		$("form[name='allergy_other_allergy_form'] .alert-danger").hide();
// 		$("#allergy_other_allergy_form")[0].reset();
// 		$('form[name="allergy_other_allergy_form"] #id').val('');
// 		var allergy_type = $('form[name="allergy_other_allergy_form"] input[name="allergy_type"]').val();
// 		var id = $("#patient_id").val();
// 		util.refreshAllergyCountCheckbox(id, allergy_type, 'allergy_other_allergy_form');
// 		renderAllergyOtherTable();
// 		var patient_id = $("input[name='patient_id']").val();
//     	var module_id = $("input[name='module_id']").val();
// 		util.getPatientCareplanNotes(patient_id, module_id);
// 		var scrollPos = $(".main-content").offset().top;
// 		if (module == 'care-plan-development') {
// 			util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 			renderAllergyOtherTable();
// 			$("#review_allergy_other_allergy_form")[0].reset();
// 			$('form[name="review_allergy_other_allergy_form"] #id').val('');
// 			CompletedCheck();

// 		} else {
// 			util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
// 			carePlanDevelopment.renderAllergyOtherTableData();

// 		}
// 		$("form[name='allergy_other_allergy_form'] .alert-success").show();
// 		$(window).scrollTop(scrollPos);

// 		$("form[name='allergy_other_allergy_form'] .alert-success").show();
// 		$("form[name ='allergy_other_allergy_form']")[0].reset();
// 		$("form[name='allergy_other_allergy_form'] input[name='specify']").prop("disabled", false);
// 		$("form[name='allergy_other_allergy_form'] input[name='type_of_reactions']").prop("disabled", false);
// 		$("form[name='allergy_other_allergy_form'] input[name='severity']").prop("disabled", false);
// 		$("form[name='allergy_other_allergy_form'] input[name='course_of_treatment']").prop("disabled", false);
// 		$("form[name='allergy_other_allergy_form'] textarea[name='notes']").prop("disabled", false);
// 		setTimeout(function () {
// 			$('.alert-success').fadeOut('fast');
// 		}, 3000);
// 		var timer_paused = $("form[name='allergy_other_allergy_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 	} else {
// 		$("form[name='allergy_other_allergy_form'] .alert-success").hide();
// 		$("form[name='allergy_other_allergy_form'] .alert-danger").show();
// 	}
// };

//patient services

var onServices = function (formObj, fields, response) {
	var form_name = fields.values.form_name;
	if (response.status == 200) {
		$("#" + form_name)[0].reset();
		var patient_id = $("input[name='patient_id']").val();
		var module_id = $("input[name='module_id']").val();
		util.getPatientCareplanNotes(patient_id, module_id);
		if (module == 'care-plan-development') {
			util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
			if (!form_name.includes("review_")) {
				$("#review_" + form_name)[0].reset();
				$("form[name='review_" + form_name + "'] #id").val('');
			}
			if (form_name == 'service_dme_form' || form_name == 'review_service_dme_form') {
				renderDMEServicesTable();
			} else if (form_name == 'service_home_health_form' || form_name == 'review_service_home_health_form') {
				renderHomeHealthServicesTable();
			} else if (form_name == 'service_dialysis_form' || form_name == 'review_service_dialysis_form') {
				renderDialysiServicesTable();
			} else if (form_name == 'service_therapy_form' || form_name == 'review_service_therapy_form') {
				renderTherapyServicesTable();
			} else if (form_name == 'service_social_form' || form_name == 'review_service_social_form') {
				renderSocialServicesTable();
			} else if (form_name == 'service_medical_supplies_form' || form_name == 'review_service_medical_supplies_form') {
				renderMedicalSuppliesServicesTable();
			} else if (form_name == 'service_other_health_form' || form_name == 'review_service_other_health_form') {
				renderOtherHealthServicesTable();
			}
			CompletedCheck();
		} else {
			util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
			if (form_name == 'service_dme_form') {
				carePlanDevelopment.renderDMEServicesTableData();
			} else if (form_name == 'service_home_health_form') {
				carePlanDevelopment.renderHomeHealthServicesTableData();
			} else if (form_name == 'service_dialysis_form') {
				carePlanDevelopment.renderDialysiServicesTableData();
			} else if (form_name == 'service_therapy_form') {
				carePlanDevelopment.renderTherapyServicesTableData();
			} else if (form_name == 'service_social_form') {
				carePlanDevelopment.renderSocialServicesTableData();
			} else if (form_name == 'service_medical_supplies_form') {
				carePlanDevelopment.renderMedicalSuppliesServicesTableData();
			} else if (form_name == 'service_other_health_form') {
				carePlanDevelopment.renderOtherHealthServicesTableData();
			}
		}
		var scrollPos = $(".main-content").offset().top;
		$(window).scrollTop(scrollPos);
		$("form[name='" + form_name + "'] .alert-success").show();
		$("form[name='" + form_name + "'] .alert-danger").hide();
		setTimeout(function () {
			$('.alert').fadeOut('fast');//goToNextStep("drug-icon-pill"); 
		}, 3000);
		var timer_paused = $("form[name='" + form_name + "'] input[name='end_time']").val();
		$("#timer_start").val(timer_paused);
	} else {
		$("form[name='" + form_name + "'] .alert-success").hide();
		$("form[name='" + form_name + "'] .alert-danger").show();
	}
};

// var onDialysisServices = function (formObj, fields, response) {
// 	if (response.status == 200) {
// 		$("#service_dme_form")[0].reset();
// 		$("#service_home_health_form")[0].reset();
// 		$("form[name='service_dialysis_form']")[0].reset();
// 		$("#service_therapy_form")[0].reset();
// 		$("#service_social_form")[0].reset();
// 		$("#service_medical_supplies_form")[0].reset();
// 		$("#service_other_health_form")[0].reset();
// 		var patient_id = $("input[name='patient_id']").val();
//     	var module_id = $("input[name='module_id']").val();
// 		util.getPatientCareplanNotes(patient_id, module_id);
// 		if (module == 'care-plan-development') {
// 			util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 			$("#review_service_dme_form")[0].reset();
// 			$("#review_service_home_health_form")[0].reset();
// 			$("form[name='review_service_dialysis_form']")[0].reset();
// 			$("#review_service_medical_supplies_form")[0].reset();
// 			$("#review_service_other_health_form")[0].reset();
// 			$("#review_service_social_form")[0].reset();
// 			$("#review_service_therapy_form")[0].reset();
// 			renderDialysiServicesTable();
// 			CompletedCheck();
// 		} else {
// 			util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
// 			carePlanDevelopment.renderDialysiServicesTableData();
// 		}
// 		$("form[name='service_dialysis_form'] .alert-success").show();
// 		$("form[name='service_dialysis_form'] .alert-danger").hide();
// 		var scrollPos = $(".main-content").offset().top;
// 		$(window).scrollTop(scrollPos);
// 		setTimeout(function () {
// 			$('.alert').fadeOut('fast');//goToNextStep("drug-icon-pill"); 
// 		}, 3000);
// 		var timer_paused = $("form[name='service_dialysis_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 	} else {
// 		if (response.data.errors.from_whom) {
// 			$("form[name='service_dialysis_form'] #services_homehealth_from_whom").addClass("is-invalid");
// 			$("form[name='service_dialysis_form'] #services_homehealth_from_whom").next(".invalid-feedback").html('The from whom field is required');
// 		} if (response.data.errors.purpose) {
// 			$("form[name='service_dialysis_form'] #services_homehealth_purpose").addClass("is-invalid");
// 			$("form[name='service_dialysis_form'] #services_homehealth_purpose").next(".invalid-feedback").html(response.data.errors.purpose);
// 		} if (response.data.errors.specify) {
// 			$("form[name='service_dialysis_form'] #services_homehealth_specify").addClass("is-invalid");
// 			$("form[name='service_dialysis_form'] #services_homehealth_specify").next(".invalid-feedback").html(response.data.errors.specify);
// 		} if (response.data.errors.frequency) {
// 			$("form[name='service_dialysis_form'] #services_homehealth_frequency").addClass("is-invalid");
// 			$("form[name='service_dialysis_form'] #services_homehealth_frequency").next(".invalid-feedback").html('The frequency field is required');
// 		} if (response.data.errors.duration) {
// 			$("form[name='service_dialysis_form'] #services_homehealth_duration").addClass("is-invalid");
// 			$("form[name='service_dialysis_form'] #services_homehealth_duration").next(".invalid-feedback").html('The duration field is required');
// 		}
// 		if (response.data.errors.from_where) {
// 			$("form[name='service_dialysis_form'] #services_homehealth_from_where").addClass("is-invalid");
// 			$("form[name='service_dialysis_form'] #services_homehealth_from_where").next(".invalid-feedback").html('The from where field is required');
// 		}
// 		$("form[name='service_dialysis_form'] .alert-success").hide();
// 		$("form[name='service_dialysis_form'] .alert-danger").show();
// 	}
// };

// var onOtherServices = function (formObj, fields, response) {
// 	if (response.status == 200) {
// 		$("#service_dme_form")[0].reset();
// 		$("#service_home_health_form")[0].reset();
// 		$("form[name='service_dialysis_form']")[0].reset();
// 		$("#service_therapy_form")[0].reset();
// 		$("#service_social_form")[0].reset();
// 		$("#service_medical_supplies_form")[0].reset();
// 		$("#service_other_health_form")[0].reset();
// 		var patient_id = $("input[name='patient_id']").val();
//     	var module_id = $("input[name='module_id']").val();
// 		util.getPatientCareplanNotes(patient_id, module_id);
// 		if (module == 'care-plan-development') {
// 			util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 			$("#review_service_dme_form")[0].reset();
// 			$("#review_service_home_health_form")[0].reset();
// 			$("form[name='review_service_dialysis_form']")[0].reset();
// 			$("#review_service_medical_supplies_form")[0].reset();
// 			$("#review_service_other_health_form")[0].reset();
// 			$("#review_service_social_form")[0].reset();
// 			$("#review_service_therapy_form")[0].reset();
// 			renderOtherHealthServicesTable();
// 			CompletedCheck();
// 		} else {
// 			util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
// 			carePlanDevelopment.renderOtherHealthServicesTableData();
// 		}
// 		$("form[name='service_other_health_form'] .alert-success").show();
// 		$("form[name='service_other_health_form'] .alert-danger").hide();
// 		var scrollPos = $(".main-content").offset().top;
// 		$(window).scrollTop(scrollPos);
// 		setTimeout(function () {
// 			$('.alert').fadeOut('fast');//goToNextStep("drug-icon-pill"); 
// 		}, 3000);
// 		var timer_paused = $("form[name='service_other_health_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 	} else {
// 		if (response.data.errors.brand) {
// 			$("form[name='service_other_health_form'] #services_brand").addClass("is-invalid");
// 			$("form[name='service_other_health_form'] #services_brand").next(".invalid-feedback").html('The brand field is required');
// 		}
// 		if (response.data.errors.purpose) {
// 			$("form[name='service_other_health_form'] #services_purpose").addClass("is-invalid");
// 			$("form[name='service_other_health_form'] #services_purpose").next(".invalid-feedback").html('The purpose field is required');
// 		}
// 		if (response.data.errors.specify) {
// 			$("form[name='service_other_health_form'] #services_specify").addClass("is-invalid");
// 			$("form[name='service_other_health_form'] #services_specify").next(".invalid-feedback").html('The specify field is required');
// 		}
// 		if (response.data.errors.type) {
// 			$("form[name='service_other_health_form'] #services_type").addClass("is-invalid");
// 			$("form[name='service_other_health_form'] #services_type").next(".invalid-feedback").html('The type field is required');
// 		}
// 		$("form[name='service_other_health_form'] .alert-success").hide();
// 		$("form[name='service_other_health_form'] .alert-danger").show();
// 	}
// };

// var onSocialServices = function (formObj, fields, response) {
// 	if (response.status == 200) {
// 		$("#service_dme_form")[0].reset();
// 		$("#service_home_health_form")[0].reset();
// 		$("form[name='service_dialysis_form']")[0].reset();
// 		$("#service_therapy_form")[0].reset();
// 		$("#service_social_form")[0].reset();
// 		$("#service_medical_supplies_form")[0].reset();
// 		$("#service_other_health_form")[0].reset();
// 		var patient_id = $("input[name='patient_id']").val();
//     	var module_id = $("input[name='module_id']").val();
// 		util.getPatientCareplanNotes(patient_id, module_id);
// 		if (module == 'care-plan-development') {
// 			util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 			$("#review_service_dme_form")[0].reset();
// 			$("#review_service_home_health_form")[0].reset();
// 			$("form[name='review_service_dialysis_form']")[0].reset();
// 			$("#review_service_medical_supplies_form")[0].reset();
// 			$("#review_service_other_health_form")[0].reset();
// 			$("#review_service_social_form")[0].reset();
// 			$("#review_service_therapy_form")[0].reset();
// 			renderSocialServicesTable();
// 			CompletedCheck();
// 		} else {
// 			util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
// 			carePlanDevelopment.renderSocialServicesTableData();
// 		}
// 		$("form[name='service_social_form'] .alert-danger").hide();
// 		$("form[name='service_social_form'] .alert-success").show();
// 		var scrollPos = $(".main-content").offset().top;
// 		$(window).scrollTop(scrollPos);
// 		setTimeout(function () {
// 			$('.alert').fadeOut('fast');//goToNextStep("drug-icon-pill"); 
// 		}, 3000);
// 		var timer_paused = $("form[name='service_social_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 	} else {
// 		if (response.data.errors.from_whom) {
// 			$("form[name='service_social_form'] #services_homehealth_from_whom").addClass("is-invalid");
// 			$("form[name='service_social_form'] #services_homehealth_from_whom").next(".invalid-feedback").html('The from whom field is required');
// 		} if (response.data.errors.purpose) {
// 			$("form[name='service_social_form'] #services_homehealth_purpose").addClass("is-invalid");
// 			$("form[name='service_social_form'] #services_homehealth_purpose").next(".invalid-feedback").html(response.data.errors.purpose);
// 		} if (response.data.errors.specify) {
// 			$("form[name='service_social_form'] #services_homehealth_specify").addClass("is-invalid");
// 			$("form[name='service_social_form'] #services_homehealth_specify").next(".invalid-feedback").html(response.data.errors.specify);
// 		} if (response.data.errors.frequency) {
// 			$("form[name='service_social_form'] #services_homehealth_frequency").addClass("is-invalid");
// 			$("form[name='service_social_form'] #services_homehealth_frequency").next(".invalid-feedback").html('The frequency field is required');
// 		} if (response.data.errors.duration) {
// 			$("form[name='service_social_form'] #services_homehealth_duration").addClass("is-invalid");
// 			$("form[name='service_social_form'] #services_homehealth_duration").next(".invalid-feedback").html('The duration field is required');
// 		}
// 		if (response.data.errors.from_where) {
// 			$("form[name='service_social_form'] #services_homehealth_from_where").addClass("is-invalid");
// 			$("form[name='service_social_form'] #services_homehealth_from_where").next(".invalid-feedback").html('The from where field is required');
// 		}
// 		$("form[name='service_social_form'] .alert-success").hide();
// 		$("form[name='service_social_form'] .alert-danger").show();
// 	}
// };

// var onTherapyServices = function (formObj, fields, response) {
// 	if (response.status == 200) {
// 		$("#service_therapy_form #id").val('');
// 		$("#service_dme_form")[0].reset();
// 		$("#service_home_health_form")[0].reset();
// 		$("form[name='service_dialysis_form']")[0].reset();
// 		$("#service_therapy_form")[0].reset();
// 		$("#service_social_form")[0].reset();
// 		$("#service_medical_supplies_form")[0].reset();
// 		$("#service_other_health_form")[0].reset();
// 		var patient_id = $("input[name='patient_id']").val();
//     	var module_id = $("input[name='module_id']").val();
// 		util.getPatientCareplanNotes(patient_id, module_id);
// 		if (module == 'care-plan-development') {
// 			util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 			$("#review_service_dme_form")[0].reset();
// 			$("#review_service_home_health_form")[0].reset();
// 			$("form[name='review_service_dialysis_form']")[0].reset();
// 			$("#review_service_medical_supplies_form")[0].reset();
// 			$("#review_service_other_health_form")[0].reset();
// 			$("#review_service_social_form")[0].reset();
// 			$("#review_service_therapy_form")[0].reset();
// 			renderTherapyServicesTable();
// 			CompletedCheck();
// 		} else {
// 			util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
// 			carePlanDevelopment.renderTherapyServicesTableData();
// 		}
// 		$("form[name='service_therapy_form'] .alert-success").show();
// 		$("form[name='service_therapy_form'] .alert-danger").hide();
// 		var scrollPos = $(".main-content").offset().top;
// 		$(window).scrollTop(scrollPos);
// 		setTimeout(function () {
// 			$('.alert').fadeOut('fast');//goToNextStep("drug-icon-pill"); 
// 		}, 3000);
// 		var timer_paused = $("form[name='service_therapy_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 	} else {
// 		if (response.data.errors.from_whom) {
// 			$("form[name='service_therapy_form'] #services_homehealth_from_whom").addClass("is-invalid");
// 			$("form[name='service_therapy_form'] #services_homehealth_from_whom").next(".invalid-feedback").html('The from whom field is required');
// 		} if (response.data.errors.purpose) {
// 			$("form[name='service_therapy_form'] #services_homehealth_purpose").addClass("is-invalid");
// 			$("form[name='service_therapy_form'] #services_homehealth_purpose").next(".invalid-feedback").html(response.data.errors.purpose);
// 		} if (response.data.errors.specify) {
// 			$("form[name='service_therapy_form'] #services_homehealth_specify").addClass("is-invalid");
// 			$("form[name='service_therapy_form'] #services_homehealth_specify").next(".invalid-feedback").html(response.data.errors.specify);
// 		} if (response.data.errors.frequency) {
// 			$("form[name='service_therapy_form'] #services_homehealth_frequency").addClass("is-invalid");
// 			$("form[name='service_therapy_form'] #services_homehealth_frequency").next(".invalid-feedback").html('The frequency field is required');
// 		} if (response.data.errors.duration) {
// 			$("form[name='service_therapy_form'] #services_homehealth_duration").addClass("is-invalid");
// 			$("form[name='service_therapy_form'] #services_homehealth_duration").next(".invalid-feedback").html('The duration field is required');
// 		}
// 		if (response.data.errors.from_where) {
// 			$("form[name='service_therapy_form'] #services_homehealth_from_where").addClass("is-invalid");
// 			$("form[name='service_therapy_form'] #services_homehealth_from_where").next(".invalid-feedback").html('The from where field is required');
// 		}
// 		$("form[name='service_therapy_form'] .alert-success").hide();
// 		$("form[name='service_therapy_form'] .alert-danger").show();
// 	}
// };

// var onDmeServices = function (formObj, fields, response) {
// 	//console.log(response);
// 	if (response.status == 200) {
// 		$("#service_dme_form")[0].reset();
// 		$("#service_home_health_form")[0].reset();
// 		$("form[name='service_dialysis_form']")[0].reset();
// 		$("#service_therapy_form")[0].reset();
// 		$("#service_social_form")[0].reset();
// 		$("#service_medical_supplies_form")[0].reset();
// 		$("#service_other_health_form")[0].reset();
// 		var patient_id = $("input[name='patient_id']").val();
//     	var module_id = $("input[name='module_id']").val();
// 		util.getPatientCareplanNotes(patient_id, module_id);
// 		if (module == 'care-plan-development') {
// 			util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 			$("#review_service_dme_form")[0].reset();
// 			$("#review_service_home_health_form")[0].reset();
// 			$("form[name='review_service_dialysis_form']")[0].reset();
// 			$("#review_service_medical_supplies_form")[0].reset();
// 			$("#review_service_other_health_form")[0].reset();
// 			$("#review_service_social_form")[0].reset();
// 			$("#review_service_therapy_form")[0].reset();
// 			renderDMEServicesTable();
// 			CompletedCheck();
// 		} else {
// 			util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
// 			renderDMEServicesTableData();
// 		}
// 		$("form[name='service_dme_form'] .alert-success").show();
// 		$("form[name='service_dme_form'] .alert-danger").hide();
// 		var scrollPos = $(".main-content").offset().top;
// 		$(window).scrollTop(scrollPos);
// 		setTimeout(function () {
// 			$('.alert').fadeOut('fast');//goToNextStep("drug-icon-pill"); 
// 		}, 3000);
// 		var timer_paused = $("form[name='service_dme_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 	} else {
// 		if (response.data.errors.brand) {
// 			$("#services_brand").addClass("is-invalid");
// 			$("#services_brand").next(".invalid-feedback").html('The brand field is required');
// 		}
// 		if (response.data.errors.purpose) {
// 			$("#services_purpose").addClass("is-invalid");
// 			$("#services_purpose").next(".invalid-feedback").html('The purpose field is required');
// 		}
// 		if (response.data.errors.specify) {
// 			$("#services_specify").addClass("is-invalid");
// 			$("#services_specify").next(".invalid-feedback").html('The specify field is required');
// 		}
// 		if (response.data.errors.type) {
// 			$("#services_type").addClass("is-invalid");
// 			$("#services_type").next(".invalid-feedback").html('The type field is required');
// 		}
// 		$("form[name='service_dme_form'] .alert-success").hide();
// 		$("form[name='service_dme_form'] .alert-danger").show();
// 	}

// };

// var onMedicalSuppliesServices = function (formObj, fields, response) {
// 	if (response.status == 200) {
// 		$("#service_dme_form")[0].reset();
// 		$("#service_home_health_form")[0].reset();
// 		$("form[name='service_dialysis_form']")[0].reset();
// 		$("#service_therapy_form")[0].reset();
// 		$("#service_social_form")[0].reset();
// 		$("#service_medical_supplies_form")[0].reset();
// 		$("#service_other_health_form")[0].reset();
// 		var patient_id = $("input[name='patient_id']").val();
//     	var module_id = $("input[name='module_id']").val();
// 		util.getPatientCareplanNotes(patient_id, module_id);
// 		if (module == 'care-plan-development') {
// 			util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 			$("#review_service_dme_form")[0].reset();
// 			$("#review_service_home_health_form")[0].reset();
// 			$("form[name='review_service_dialysis_form']")[0].reset();
// 			$("#review_service_medical_supplies_form")[0].reset();
// 			$("#review_service_other_health_form")[0].reset();
// 			$("#review_service_social_form")[0].reset();
// 			$("#review_service_therapy_form")[0].reset();
// 			renderMedicalSuppliesServicesTable();
// 			CompletedCheck();
// 		} else {
// 			util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
// 			carePlanDevelopment.renderMedicalSuppliesServicesTableData();
// 		}
// 		$("form[name='service_medical_supplies_form'] .alert-success").show();
// 		$("form[name='service_medical_supplies_form'] .alert-danger").hide();
// 		var scrollPos = $(".main-content").offset().top;
// 		$(window).scrollTop(scrollPos);
// 		setTimeout(function () {
// 			$('.alert').fadeOut('fast');//goToNextStep("drug-icon-pill"); 
// 		}, 3000);
// 		var timer_paused = $("form[name='service_medical_supplies_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 	} else {
// 		if (response.data.errors.brand) {
// 			$("form[name='service_medical_supplies_form'] #services_brand").addClass("is-invalid");
// 			$("form[name='service_medical_supplies_form'] #services_brand").next(".invalid-feedback").html('The brand field is required');
// 		}
// 		if (response.data.errors.purpose) {
// 			$("form[name='service_medical_supplies_form'] #services_purpose").addClass("is-invalid");
// 			$("form[name='service_medical_supplies_form'] #services_purpose").next(".invalid-feedback").html('The purpose field is required');
// 		}
// 		if (response.data.errors.specify) {
// 			$("form[name='service_medical_supplies_form'] #services_specify").addClass("is-invalid");
// 			$("form[name='service_medical_supplies_form'] #services_specify").next(".invalid-feedback").html('The specify field is required');
// 		}
// 		if (response.data.errors.type) {
// 			$("form[name='service_medical_supplies_form'] #services_type").addClass("is-invalid");
// 			$("form[name='service_medical_supplies_form'] #services_type").next(".invalid-feedback").html('The type field is required');
// 		}
// 		$("form[name='service_medical_supplies_form'] .alert-success").hide();
// 		$("form[name='service_medical_supplies_form'] .alert-danger").show();
// 	}
// };

// var onHomeHealthServices = function (formObj, fields, response) {
// 	//console.log(response);
// 	if (response.status == 200) {
// 		$("#service_dme_form")[0].reset();
// 		$("#service_home_health_form")[0].reset();
// 		$("form[name='service_dialysis_form']")[0].reset();
// 		$("#service_therapy_form")[0].reset();
// 		$("#service_social_form")[0].reset();
// 		$("#service_medical_supplies_form")[0].reset();
// 		$("#service_other_health_form")[0].reset();
// 		var patient_id = $("input[name='patient_id']").val();
//     	var module_id = $("input[name='module_id']").val();
// 		util.getPatientCareplanNotes(patient_id, module_id);
// 		if (module == 'care-plan-development') {
// 			util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 			$("#review_service_dme_form")[0].reset();
// 			$("#review_service_home_health_form")[0].reset();
// 			$("form[name='review_service_dialysis_form']")[0].reset();
// 			$("#review_service_medical_supplies_form")[0].reset();
// 			$("#review_service_other_health_form")[0].reset();
// 			$("#review_service_social_form")[0].reset();
// 			$("#review_service_therapy_form")[0].reset();
// 			renderHomeHealthServicesTable();
// 			CompletedCheck();
// 		} else {
// 			util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
// 			carePlanDevelopment.renderHomeHealthServicesTableData();
// 		}
// 		$("form[name='service_home_health_form'] .alert-success").show();
// 		$("form[name='service_home_health_form'] .alert-danger").hide();
// 		var scrollPos = $(".main-content").offset().top;
// 		$(window).scrollTop(scrollPos);
// 		setTimeout(function () {
// 			$('.alert').fadeOut('fast');//goToNextStep("drug-icon-pill"); 
// 		}, 3000);
// 		var timer_paused = $("form[name='service_home_health_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 	} else {
// 		if (response.data.errors.from_whom) {
// 			$("#services_homehealth_from_whom").addClass("is-invalid");
// 			$("#services_homehealth_from_whom").next(".invalid-feedback").html('The from whom field is required');
// 		} if (response.data.errors.purpose) {
// 			$("#services_homehealth_purpose").addClass("is-invalid");
// 			$("#services_homehealth_purpose").next(".invalid-feedback").html(response.data.errors.purpose);
// 		} if (response.data.errors.specify) {
// 			$("#services_homehealth_specify").addClass("is-invalid");
// 			$("#services_homehealth_specify").next(".invalid-feedback").html(response.data.errors.specify);
// 		} if (response.data.errors.frequency) {
// 			$("#services_homehealth_frequency").addClass("is-invalid");
// 			$("#services_homehealth_frequency").next(".invalid-feedback").html('The frequency field is required');
// 		} if (response.data.errors.duration) {
// 			$("#services_homehealth_duration").addClass("is-invalid");
// 			$("#services_homehealth_duration").next(".invalid-feedback").html('The duration field is required');
// 		}
// 		if (response.data.errors.from_where) {
// 			$("#services_homehealth_from_where").addClass("is-invalid");
// 			$("#services_homehealth_from_where").next(".invalid-feedback").html('The from where field is required');
// 		}
// 		$("form[name='service_home_health_form'] .alert-success").hide();
// 		$("form[name='service_home_health_form'] .alert-danger").show();
// 	}
// };

//Provider

var onProvider = function (formObj, fields, response) {
	var form_name = fields.values.form_name;
	if (response.status == 200) {
		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
		$("form[name='" + form_name + "'] .alert-success").show();
		$("form[name='" + form_name + "'] .alert-danger").hide();

		if (form_name == "provider_pcp_form") {
			var pcp_provider = $("form[name='provider_pcp_form'] #provider_id option:selected").text();
			$("#model-provider").text(pcp_provider);
		}
		if ($("form[name='" + form_name + "'] #provider_id").val() == 0) {
			$("form[name='" + form_name + "'] #practices_div").removeClass('col-md-4');
			$("form[name='" + form_name + "'] #providers_div").removeClass('col-md-4');
			$("form[name='" + form_name + "'] #providers_name").hide();
			$("form[name='" + form_name + "'] #practices_div").addClass('col-md-6');
			$("form[name='" + form_name + "'] #providers_div").addClass('col-md-6');
		}
		if ($("form[name='" + form_name + "'] #practices").val() == 0) {
			$("form[name='" + form_name + "'] #practices_div").removeClass('col-md-3');
			$("form[name='" + form_name + "'] #providers_div").removeClass('col-md-3');
			$("form[name='" + form_name + "'] #prac_name").hide();
			$("form[name='" + form_name + "'] #practices_div").addClass('col-md-6');
			$("form[name='" + form_name + "'] #providers_div").addClass('col-md-6');
			util.updatePracticeList("form[name='" + form_name + "'] #practices");
		}
		var practice_id = $("form[name='" + form_name + "'] #practices option:selected").val();
		util.updateCpdProviderList(practice_id, "form[name='" + form_name + "'] #provider_id");
		var scrollPos = $(".main-content").offset().top;
		$("#" + form_name)[0].reset();
		CompletedCheck();
		populateformdata();
		$(window).scrollTop(scrollPos);
		setTimeout(function () {
			$('.alert').fadeOut('fast');
			//goToNextStep("drug-icon-pill"); 
		}, 3000);
		var timer_paused = $("form[name='" + form_name + "'] input[name='end_time']").val();
		$("#timer_start").val(timer_paused);
		// $("#timer_end").val(timer_paused);
		if (form_name == 'provider_specialists_form' || form_name == 'review_provider_specialists_form') {
			renderOtherProviderSpecialistTable();
		}
		if (module == 'care-plan-development') {
			$("form[name='" + form_name + "'] input[name='id']").val('');
		}
	} else {
		// console.log("errormsg");
		$("form[name='" + form_name + "'] .alert-success").hide();
		$("form[name='" + form_name + "'] .alert-danger").show();
	}
};

// var onPcpProvider = function (formObj, fields, response) {
// 	// console.log(response.status+"test");
// 	if (response.status == 200) {
// 		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 		$("form[name='provider_pcp_form'] .alert-success").show();
// 		$("form[name='provider_pcp_form'] .alert-danger").hide();
// 		if ($('form[name="provider_pcp_form"] #provider_id').val() == 0) {
// 			$("form[name='provider_pcp_form'] #practices_div").removeClass('col-md-4');
// 			$("form[name='provider_pcp_form'] #providers_div").removeClass('col-md-4');
// 			$("form[name='provider_pcp_form'] #providers_name").hide();
// 			$("form[name='provider_pcp_form'] #practices_div").addClass('col-md-6');
// 			$("form[name='provider_pcp_form'] #providers_div").addClass('col-md-6');
// 		}
// 		var scrollPos = $(".main-content").offset().top;
// 		$("#provider_pcp_form")[0].reset();
// 		CompletedCheck();
// 		populateformdata();
// 		$(window).scrollTop(scrollPos);
// 		setTimeout(function () {
// 			$('.alert').fadeOut('fast');
// 			//goToNextStep("drug-icon-pill"); 
// 		}, 3000);
// 		var timer_paused = $("form[name='provider_pcp_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 		// $("#timer_end").val(timer_paused);
// 	} else {
// 		// console.log("errormsg");
// 		$("form[name='provider_pcp_form'] .alert-success").hide();
// 		$("form[name='provider_pcp_form'] .alert-danger").show();
// 	}
// };

// var onSpecialistsProvider = function (formObj, fields, response) {
// 	if (response.status == 200) {
// 		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 		$("form[name='provider_specialists_form'] .alert-success").show();
// 		$("form[name='provider_specialists_form'] .alert-danger").hide();
// 		renderOtherProviderSpecialistTable();
// 		if ($('form[name="provider_specialists_form"] #provider_id').val() == 0) { 
// 			$("form[name='provider_specialists_form'] #practices_div").removeClass('col-md-3');
// 			$("form[name='provider_specialists_form'] #providers_div").removeClass('col-md-3');
// 			$("form[name='provider_specialists_form'] #providers_name").hide();
// 			$("form[name='provider_specialists_form'] #practices_div").addClass('col-md-6');
// 			$("form[name='provider_specialists_form'] #providers_div").addClass('col-md-6');
// 			var practice_id = $("form[name='provider_specialists_form'] #practices option:selected").val();
// 			util.updateCpdProviderList(practice_id, "form[name='provider_specialists_form'] #provider_id");
// 		}
// 		if ($('form[name="provider_specialists_form"] #practices').val() == 0) { 
// 			$("form[name='provider_specialists_form'] #practices_div").removeClass('col-md-3');
// 			$("form[name='provider_specialists_form'] #providers_div").removeClass('col-md-3');
// 			$("form[name='provider_specialists_form'] #prac_name").hide();
// 			$("form[name='provider_specialists_form'] #practices_div").addClass('col-md-6');
// 			$("form[name='provider_specialists_form'] #providers_div").addClass('col-md-6');
// 			util.updatePracticeList("form[name='provider_specialists_form'] #practices");
// 		}
// 		var scrollPos = $(".main-content").offset().top;
// 		$("form[name='provider_specialists_form']")[0].reset();
// 		$("form[name='provider_specialists_form'] input[name='id']").val('');
// 		if (module == 'care-plan-development') {
// 			$("form[name='review_provider_specialists_form']")[0].reset();
// 			$("form[name='review_provider_specialists_form'] input[name='id']").val('');
// 		}
// 		CompletedCheck();
// 		$(window).scrollTop(scrollPos);
// 		setTimeout(function () {
// 			$('.alert-success').fadeOut('fast');//goToNextStep("drug-icon-pill"); 
// 		}, 3000);
// 		var timer_paused = $("form[name='provider_specialists_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 		// $("#timer_end").val(timer_paused);
// 	} else {
// 		$("form[name='provider_specialists_form'] .alert-success").hide();
// 		$("form[name='provider_specialists_form'] .alert-danger").show();
// 	}
// };

// var onVisionProvider = function (formObj, fields, response) {
// 	//console.log(response);
// 	if (response.status == 200) {
// 		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 		$("form[name='provider_vision_form'] .alert-success").show();
// 		$("form[name='provider_vision_form'] .alert-danger").hide();
// 		if ($('form[name="provider_vision_form"] #provider_id').val() == 0) {
// 			$("form[name='provider_vision_form'] #practices_div").removeClass('col-md-3');
// 			$("form[name='provider_vision_form'] #providers_div").removeClass('col-md-3');
// 			$("form[name='provider_vision_form'] #providers_name").hide();
// 			$("form[name='provider_vision_form'] #practices_div").addClass('col-md-6');
// 			$("form[name='provider_vision_form'] #providers_div").addClass('col-md-6');
// 			var practice_id = $("form[name='provider_vision_form'] #practices option:selected").val();
// 			util.updateCpdProviderList(practice_id, "form[name='provider_vision_form'] #provider_id");
// 		}
// 		if ($('form[name="provider_vision_form"] #practices').val() == 0) { 
// 			$("form[name='provider_vision_form'] #practices_div").removeClass('col-md-3');
// 			$("form[name='provider_vision_form'] #providers_div").removeClass('col-md-3');
// 			$("form[name='provider_vision_form'] #prac_name").hide();
// 			$("form[name='provider_vision_form'] #practices_div").addClass('col-md-6');
// 			$("form[name='provider_vision_form'] #providers_div").addClass('col-md-6');
// 			util.updatePracticeList("form[name='provider_vision_form'] #practices");
// 		}
// 		var scrollPos = $(".main-content").offset().top;
// 		$("form[name='provider_vision_form']")[0].reset();
// 		CompletedCheck(); 
// 		populateformdata();
// 		$(window).scrollTop(scrollPos); 
// 		setTimeout(function () {
// 			$('.alert').fadeOut('fast');
// 		}, 3000);
// 		var timer_paused = $("form[name='provider_vision_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 		// $("#timer_end").val(timer_paused);
// 	} else {
// 		$("form[name='provider_vision_form'] .alert-success").hide();
// 		$("form[name='provider_vision_form'] .alert-danger").show();
// 	}
// };

// var onDentistProvider = function (formObj, fields, response) {
// 	//console.log(response);
// 	if (response.status == 200) {
// 		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 		$("form[name='provider_dentist_form'] .alert-success").show();
// 		$("form[name='provider_dentist_form'] .alert-danger").hide();
// 		if ($('form[name="provider_dentist_form"] #provider_id').val() == 0) {
// 			$("form[name='provider_dentist_form'] #practices_div").removeClass('col-md-3');
// 			$("form[name='provider_dentist_form'] #providers_div").removeClass('col-md-3');
// 			$("form[name='provider_dentist_form'] #providers_name").hide();
// 			$("form[name='provider_dentist_form'] #practices_div").addClass('col-md-6');
// 			$("form[name='provider_dentist_form'] #providers_div").addClass('col-md-6');
// 			var practice_id = $("form[name='provider_dentist_form] #practices option:selected").val();
// 			util.updateCpdProviderList(practice_id, "form[name='provider_dentist_form'] #provider_id");
// 		}
// 		if ($('form[name="provider_dentist_form"] #practices').val() == 0) { 
// 			$("form[name='provider_dentist_form'] #practices_div").removeClass('col-md-3');
// 			$("form[name='provider_dentist_form'] #providers_div").removeClass('col-md-3');
// 			$("form[name='provider_dentist_form'] #prac_name").hide();
// 			$("form[name='provider_dentist_form'] #practices_div").addClass('col-md-6');
// 			$("form[name='provider_dentist_form'] #providers_div").addClass('col-md-6');
// 			util.updatePracticeList("form[name='provider_dentist_form'] #practices");
// 		}
// 		var scrollPos = $(".main-content").offset().top;
// 		$("#provider_dentist_form")[0].reset();
// 		CompletedCheck();
// 		populateformdata();
// 		$(window).scrollTop(scrollPos);
// 		setTimeout(function () {
// 			$('.alert').fadeOut('fast');
// 		}, 3000);
// 		var timer_paused = $("form[name='provider_dentist_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 		// $("#timer_end").val(timer_paused);
// 	} else {
// 		$("form[name='provider_dentist_form'] .alert-success").hide();
// 		$("form[name='provider_dentist_form'] .alert-danger").show();
// 	}
// };

//Review provider
// var onReviewPcpProvider = function (formObj, fields, response) {
// 	//console.log(response);
// 	if (response.status == 200) {
// 		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 		$("form[name='review_provider_pcp_form'] .alert-success").show();
// 		$("form[name='review_provider_pcp_form'] .alert-danger").hide();
// 		if ($('form[name="review_provider_pcp_form"] #provider_id').val() == 0) {
// 			$("form[name='review_provider_pcp_form'] #practices_div").removeClass('col-md-4');
// 			$("form[name='review_provider_pcp_form'] #providers_div").removeClass('col-md-4');
// 			$("form[name='review_provider_pcp_form'] #providers_name").hide();
// 			$("form[name='review_provider_pcp_form'] #practices_div").addClass('col-md-6');
// 			$("form[name='review_provider_pcp_form'] #providers_div").addClass('col-md-6');
// 			var practice_id = $("form[name='review_provider_pcp_form'] #practices option:selected").val();
// 			util.updateCpdProviderList(practice_id, "form[name='review_provider_pcp_form'] #provider_id");
// 		}
// 		var scrollPos = $(".main-content").offset().top;
// 		$("#review_provider_pcp_form")[0].reset();
// 		CompletedCheck();
// 		populateformdata();
// 		$(window).scrollTop(scrollPos);
// 		setTimeout(function () {
// 			$('.alert').fadeOut('fast');
// 		}, 3000);
// 		var timer_paused = $("form[name='review_provider_pcp_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 		// $("#timer_end").val(timer_paused);
// 	} else {
// 		$("form[name='review_provider_pcp_form'] .alert-success").hide();
// 		$("form[name='review_provider_pcp_form'] .alert-danger").show();
// 	}
// };

// var onReviewSpecialistsProvider = function (formObj, fields, response) {
// 	//console.log(response);
// 	if (response.status == 200) {
// 		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 		//renderOtherProviderSpecialistReviewTable();
// 		renderOtherProviderSpecialistTable();
// 		$("form[name='provider_specialists_form']")[0].reset();
// 		$("form[name='review_provider_specialists_form']")[0].reset();
// 		$("form[name='provider_specialists_form'] input[name='id']").val('');
// 		$("form[name='review_provider_specialists_form'] input[name='id']").val('');
// 		$("#providers_name").hide();
// 		$("form[name='review_provider_specialists_form'] .alert-success").show();
// 		$("form[name='review_provider_specialists_form'] .alert-danger").hide();
// 		if ($('form[name="review_provider_specialists_form"] #provider_id').val() == 0) {
// 			$("form[name='review_provider_specialists_form'] #practices_div").removeClass('col-md-3');
// 			$("form[name='review_provider_specialists_form'] #providers_div").removeClass('col-md-3');
// 			$("form[name='review_provider_specialists_form'] #providers_name").hide();
// 			$("form[name='review_provider_specialists_form'] #practices_div").addClass('col-md-6');
// 			$("form[name='review_provider_specialists_form'] #providers_div").addClass('col-md-6');
// 			var practice_id = $("form[name='review_provider_specialists_form'] #practices option:selected").val();
// 			util.updateCpdProviderList(practice_id, "form[name='review_provider_pcp_form'] #provider_id");
// 		}

// 		if ($('form[name="review_provider_specialists_form"] #practices').val() == 0) { 
// 			$("form[name='review_provider_specialists_form'] #practices_div").removeClass('col-md-3');
// 			$("form[name='review_provider_specialists_form'] #providers_div").removeClass('col-md-3');
// 			$("form[name='review_provider_specialists_form'] #prac_name").hide();
// 			$("form[name='review_provider_specialists_form'] #practices_div").addClass('col-md-6');
// 			$("form[name='review_provider_specialists_form'] #providers_div").addClass('col-md-6');
// 			util.updatePracticeList("form[name='review_provider_specialists_form'] #practices");
// 		}
// 		var scrollPos = $(".main-content").offset().top;
// 		CompletedCheck();
// 		$(window).scrollTop(scrollPos);
// 		setTimeout(function () {
// 			$('.alert').fadeOut('fast');
// 		}, 3000);
// 		var timer_paused = $("form[name='review_provider_specialists_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 		// $("#timer_end").val(timer_paused);
// 	} else {
// 		$("form[name='review_provider_specialists_form'] .alert-success").hide();
// 		$("form[name='review_provider_specialists_form'] .alert-danger").show();
// 	}
// };

// var onReviewVisionProvider = function (formObj, fields, response) {
// 	//console.log(response);
// 	if (response.status == 200) {
// 		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 		$("form[name='review_provider_vision_form'] .alert-success").show();
// 		$("form[name='review_provider_vision_form'] .alert-danger").hide();
// 		if ($('form[name="review_provider_vision_form"] #provider_id').val() == 0) {
// 			$("form[name='review_provider_vision_form'] #practices_div").removeClass('col-md-3');
// 			$("form[name='review_provider_vision_form'] #providers_div").removeClass('col-md-3');
// 			$("form[name='review_provider_vision_form'] #providers_name").hide();
// 			$("form[name='review_provider_vision_form'] #practices_div").addClass('col-md-6');
// 			$("form[name='review_provider_vision_form'] #providers_div").addClass('col-md-6');
// 			var practice_id = $("form[name='review_provider_vision_form'] #practices option:selected").val();
// 			util.updateCpdProviderList(practice_id, "form[name='review_provider_vision_form'] #provider_id");
// 		}
// 		if ($('form[name="review_provider_vision_form"] #practices').val() == 0) { 
// 			$("form[name='review_provider_vision_form'] #practices_div").removeClass('col-md-3');
// 			$("form[name='review_provider_vision_form'] #providers_div").removeClass('col-md-3');
// 			$("form[name='review_provider_vision_form'] #prac_name").hide();
// 			$("form[name='review_provider_vision_form'] #practices_div").addClass('col-md-6');
// 			$("form[name='review_provider_vision_form'] #providers_div").addClass('col-md-6');
// 			util.updatePracticeList("form[name='review_provider_vision_form'] #practices");
// 		}
// 		var scrollPos = $(".main-content").offset().top;
// 		$("#review_provider_vision_form")[0].reset();
// 		CompletedCheck();
// 		populateformdata();
// 		$(window).scrollTop(scrollPos);
// 		setTimeout(function () {
// 			$('.alert').fadeOut('fast');
// 		}, 3000);
// 		var timer_paused = $("form[name='review_provider_vision_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 		// $("#timer_end").val(timer_paused);
// 	} else {
// 		$("form[name='review_provider_vision_form'] .alert-success").hide();
// 		$("form[name='review_provider_vision_form'] .alert-danger").show();
// 	}
// };

// var onReviewDentistProvider = function (formObj, fields, response) {
// 	//console.log(response);
// 	if (response.status == 200) {
// 		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 		$("form[name='review_provider_dentist_form'] .alert-success").show();
// 		$("form[name='review_provider_dentist_form'] .alert-danger").hide();
// 		if ($('form[name="review_provider_dentist_form"] #provider_id').val() == 0) {
// 			$("form[name='review_provider_dentist_form'] #practices_div").removeClass('col-md-3');
// 			$("form[name='review_provider_dentist_form'] #providers_div").removeClass('col-md-3');
// 			$("form[name='review_provider_dentist_form'] #providers_name").hide();
// 			$("form[name='review_provider_dentist_form'] #practices_div").addClass('col-md-6');
// 			$("form[name='review_provider_dentist_form'] #providers_div").addClass('col-md-6');
// 			var practice_id = $("form[name='review_provider_dentist_form'] #practices option:selected").val();
// 			util.updateCpdProviderList(practice_id, "form[name='review_provider_dentist_form'] #provider_id");
// 		}
// 		if ($('form[name="review_provider_dentist_form"] #practices').val() == 0) { 
// 			$("form[name='review_provider_dentist_form'] #practices_div").removeClass('col-md-3');
// 			$("form[name='review_provider_dentist_form'] #providers_div").removeClass('col-md-3');
// 			$("form[name='review_provider_dentist_form'] #prac_name").hide();
// 			$("form[name='review_provider_dentist_form'] #practices_div").addClass('col-md-6');
// 			$("form[name='review_provider_dentist_form'] #providers_div").addClass('col-md-6');
// 			util.updatePracticeList("form[name='review_provider_dentist_form'] #practices");
// 		}
// 		var scrollPos = $(".main-content").offset().top;
// 		$("#review_provider_dentist_form")[0].reset();
// 		CompletedCheck();
// 		populateformdata();
// 		$(window).scrollTop(scrollPos);
// 		setTimeout(function () {
// 		$('.alert').fadeOut('fast');
// 		}, 3000);
// 		var timer_paused = $("form[name='review_provider_dentist_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 		// $("#timer_end").val(timer_paused);
// 	} else {
// 		$("form[name='review_provider_dentist_form'] .alert-success").hide();
// 		$("form[name='review_provider_dentist_form'] .alert-danger").show(); 
// 	}
// };


// numberTracking

var onNumberTrackingVital = function (formObj, fields, response) {
	if (response.status == 200 && ($.trim(response.data) == '' || $.trim(response.data) == 'null')) {
		var patient_id = $("input[name='patient_id']").val();
		var module_id = $("input[name='module_id']").val();
		util.getPatientCareplanNotes(patient_id, module_id);
		if (module == 'care-plan-development') {
			util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
			CompletedCheck();
			renderVitalTable();
		} else {
			util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
			var table = $('#callwrap-list');
			table.DataTable().ajax.reload();
			var year = (new Date).getFullYear();
			var month = (new Date).getMonth() + 1; //add +1 for current mnth
			util.getPatientPreviousMonthNotes(patient_id, module_id, month, year);
			util.getPatientCareplanNotes(patient_id, module_id);
			carePlanDevelopment.renderVitalTable();
		}
		var scrollPos = $(".main-content").offset().top;
		$(window).scrollTop(scrollPos);
		$("form[name='number_tracking_vitals_form'] .alert-success").show();
		$("form[name='number_tracking_vitals_form'] .alert-danger").hide();
		setTimeout(function () {
			$('.alert').fadeOut('fast');
		}, 3000);
		var timer_paused = $("form[name='number_tracking_vitals_form'] input[name='end_time']").val();
		$("#timer_start").val(timer_paused);
	} else if (response.status == 200 && ($.trim(response.data) == 'false')) {
		$("form[name='number_tracking_vitals_form'] .alert-success").hide();
		var txt = '<div class="alert alert-danger alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Please fill atleast 1 field!</strong></div>';
		setTimeout(function () {
			$('.alert').fadeOut('fast');
		}, 3000);
		$("#alert-danger").html(txt);
		$("#alert-danger").show();
	}
};

// var onNumberTrackingLab = function (formObj, fields, response) {
// 	if (response.status == 200) {
// 		labcount = 0;
// 		$('#append_labs').html('');
// 		$('#append_labs_params_lab').html('');
// 		$("form[name='number_tracking_labs_form']")[0].reset();
// 		var patient_id = $("input[name='patient_id']").val();
// 		var module_id = $("input[name='module_id']").val();
// 		util.getPatientCareplanNotes(patient_id, module_id);
// 		if (module == 'care-plan-development') {
// 			util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 			CompletedCheck();
// 			renderLabsTable();
// 		} else {
// 			util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
// 			var table = $('#callwrap-list');
// 			table.DataTable().ajax.reload();
// 			var year = (new Date).getFullYear();
// 			var month = (new Date).getMonth() + 1; //add +1 for current mnth
// 			var module_id = $("input[name='module_id']").val();
// 			util.getPatientPreviousMonthNotes(patient_id, module_id, month, year);
// 			// util.getPatientCareplanNotes(patient_id, module_id);
// 			carePlanDevelopment.renderLabsTable();
// 		}
// 		labcount = 0;
// 		var scrollPos = $(".main-content").offset().top;
// 		$(window).scrollTop(scrollPos);
// 		$("form[name='number_tracking_labs_form'] .alert-danger").hide();
// 		$("form[name='number_tracking_labs_form'] .alert-success").show();
// 		setTimeout(function () {
// 			$('.alert').fadeOut('fast');
// 		}, 3000);
// 		var timer_paused = $("form[name='number_tracking_labs_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 	} else {
// 		$("form[name='number_tracking_labs_form'] .alert-success").hide();
// 		$("form[name='number_tracking_labs_form'] .alert-danger").show();
// 	}
// };
var onNumberTrackingLab = function (formObj, fields, response) {
	if (response.status == 200) {
		labcount = 0;
		$('#append_labs').html('');
		$('#append_labs_params_lab').html('');
		$("form[name='number_tracking_labs_form']")[0].reset();
		$("form[name='number_tracking_labs_form'] .alert-success").show();
		$("form[name='number_tracking_labs_form'] .alert-danger").hide();
		var patient_id = $("input[name='patient_id']").val();
		var module_id = $("input[name='module_id']").val();
		util.getPatientCareplanNotes(patient_id, module_id);
		if (module == 'care-plan-development') {
			util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
			CompletedCheck();
			renderLabsTable();
		} else {
			util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
			var table = $('#callwrap-list');
			table.DataTable().ajax.reload();
			var year = (new Date).getFullYear();
			var month = (new Date).getMonth() + 1; //add +1 for current mnth
			var module_id = $("input[name='module_id']").val();
			util.getPatientPreviousMonthNotes(patient_id, module_id, month, year);
			util.getPatientCareplanNotes(patient_id, module_id);
			carePlanDevelopment.renderLabsTable();
		}
		labcount = 0;
		var scrollPos = $(".main-content").offset().top;
		$(window).scrollTop(scrollPos);
		setTimeout(function () {
			$('.alert').fadeOut('fast');
		}, 3000);
		var timer_paused = $("form[name='number_tracking_labs_form'] input[name='end_time']").val();
		$("#timer_start").val(timer_paused);
	} else {
		$("form[name='number_tracking_labs_form'] .alert-success").hide();
		$("form[name='number_tracking_labs_form'] .alert-danger").show();
	}
};

var onNumberTrackingImaging = function (formObj, fields, response) {
	if (response.status == 200) {
		$("form[name='number_tracking_imaging_form'] .alert-success").show();
		$("form[name='number_tracking_imaging_form'] .alert-danger").hide();

		var patient_id = $("input[name='patient_id']").val();
		var module_id = $("input[name='module_id']").val();
		util.getPatientCareplanNotes(patient_id, module_id);
		if (module == 'care-plan-development') {
			util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
			CompletedCheck();
			//renderImagingTable();
		} else {
			util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
			var module_id = $("input[name='module_id']").val();
			// util.getPatientCareplanNotes(patient_id, module_id);
			var year = (new Date).getFullYear();
			var month = (new Date).getMonth() + 1; //add +1 for current mnth
			util.getPatientPreviousMonthNotes(patient_id, module_id, month, year);
			var table = $('#callwrap-list');
			table.DataTable().ajax.reload();

			//carePlanDevelopment.renderImagingTable();
		}
		var scrollPos = $(".main-content").offset().top;
		$(window).scrollTop(scrollPos);
		setTimeout(function () {
			$('.alert').fadeOut('fast');
		}, 3000);
		var timer_paused = $("form[name='number_tracking_imaging_form'] input[name='end_time']").val();
		$("#timer_start").val(timer_paused);
	} else {
		$("form[name='number_tracking_imaging_form'] .alert-success").hide();
		$("form[name='number_tracking_imaging_form'] .alert-danger").show();
	}
};

var onNumberTrackingHealthdata = function (formObj, fields, response) {
	if (response.status == 200) {
		$("form[name='number_tracking_healthdata_form'] .alert-success").show();
		$("form[name='number_tracking_healthdata_form'] .alert-danger").hide();

		var patient_id = $("input[name='patient_id']").val();
		var module_id = $("input[name='module_id']").val();
		util.getPatientCareplanNotes(patient_id, module_id);
		if (module == 'care-plan-development') {
			util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
			CompletedCheck();
		} else {
			util.updateTimer($("input[name='patient_id']").val(), 1, $("input[name='module_id']").val());
			var module_id = $("input[name='module_id']").val();
			util.getPatientCareplanNotes(patient_id, module_id);
			var year = (new Date).getFullYear();
			var month = (new Date).getMonth() + 1; //add +1 for current mnth
			util.getPatientPreviousMonthNotes(patient_id, module_id, month, year);
			var table = $('#callwrap-list');
			table.DataTable().ajax.reload();
		}
		var scrollPos = $(".main-content").offset().top;
		$(window).scrollTop(scrollPos);
		setTimeout(function () {
			$('.alert').fadeOut('fast');
		}, 3000);
		var timer_paused = $("form[name='number_tracking_healthdata_form'] input[name='end_time']").val();
		$("#timer_start").val(timer_paused);
	} else {
		$("form[name='number_tracking_healthdata_form'] .alert-success").hide();
		$("form[name='number_tracking_healthdata_form'] .alert-danger").show();
	}
};

// Review-patient-Data
// var onReviewPatientDataFamily = function (formObj, fields, response) {
// 	//console.log(response);
// 	if (response.status == 200) {
// 		var patient_id = $("input[name='patient_id']").val();
// 		var module_id = $("input[name='module_id']").val();
// 		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 		$("form[name='review_family_patient_data_form'] .alert-success").show();
// 		$("form[name='review_family_patient_data_form'] .alert-danger").hide();
// 		var scrollPos = $(".main-content").offset().top;
// 		$(window).scrollTop(scrollPos);
// 		populateformdata();
// 		CompletedCheck();
// 		util.getPatientDetails(patient_id, module_id);
// 		//util.getPatientDetailsModel(patient_id, module_id);
// 		setTimeout(function () {
// 			$('.alert').fadeOut('slow');
// 		}, 3000);
// 		var timer_paused = $("form[name='review_family_patient_data_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 		// $("#timer_end").val(timer_paused);
// 	} else {
// 		$("form[name='review_family_patient_data_form'] .alert-success").hide();
// 		$("form[name='review_family_patient_data_form'] .alert-danger").show();
// 	}
// };

// var onReviewSpouseFamily = function (formObj, fields, response) {
// 	//console.log(response);
// 	if (response.status == 200) {
// 		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 		// $("#review_family_spouse_form")[0].reset();
// 		$("form[name='review_family_spouse_form'] .alert-success").show();
// 		$("form[name='review_family_spouse_form'] .alert-danger").hide();
// 		var scrollPos = $(".main-content").offset().top;
// 		$(window).scrollTop(scrollPos);
// 		populateformdata();
// 		CompletedCheck();
// 		setTimeout(function () {
// 			$('.alert').fadeOut('fast');
// 		}, 3000);
// 		var timer_paused = $("form[name='review_family_spouse_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 		// $("#timer_end").val(timer_paused);
// 	} else {
// 		$("form[name='review_family_spouse_form'] .alert-success").hide();
// 		$("form[name='review_family_spouse_form'] .alert-danger").show();
// 	}
// };

// var onReviewEmergencyContactFamily = function (formObj, fields, response) {
// 	//console.log(response);
// 	if (response.status == 200) {
// 		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 		// $("#review_family_emergency_contact_form")[0].reset();
// 		$("form[name='review_family_emergency_contact_form'] .alert-success").show();
// 		$("form[name='review_family_emergency_contact_form'] .alert-danger").hide();
// 		var scrollPos = $(".main-content").offset().top;
// 		$(window).scrollTop(scrollPos);
// 		populateformdata();
// 		CompletedCheck();
// 		setTimeout(function () {
// 		}, 3000);
// 		var timer_paused = $("form[name='review_family_emergency_contact_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 		// $("#timer_end").val(timer_paused);
// 	} else {
// 		$("form[name='review_family_emergency_contact_form'] .alert-success").hide();
// 		$("form[name='review_family_emergency_contact_form'] .alert-danger").show();
// 	}
// };

// var onReviewCareGiverFamily = function (formObj, fields, response) {
// 	if (response.status == 200) {
// 		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 		$("form[name='review_family_care_giver_form'] .alert-success").show();
// 		$("form[name='review_family_care_giver_form'] .alert-danger").hide();
// 		var scrollPos = $(".main-content").offset().top;

// 		$(window).scrollTop(scrollPos);
// 		populateformdata();
// 		CompletedCheck();
// 		setTimeout(function () {
// 			$('.alert').fadeOut('fast');
// 		}, 3000);
// 		var timer_paused = $("form[name='review_family_care_giver_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 		// $("#timer_end").val(timer_paused);
// 	} else {
// 		$("form[name='review_family_care_giver_form'] .alert-success").hide();
// 		$("form[name='review_family_care_giver_form'] .alert-danger").show();
// 	}
// };

//review-medication

// var onReviewMedication = function (formObj, fields, response) {
// 	//console.log(response);
// 	if (response.status == 200) {
// 		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 		renderMedicationsTable();
// 		// renderMedicationsReviewTable();
// 		$("#review_medications_form")[0].reset();
// 		$("#medications_form")[0].reset();
// 		$("form[name='review_medications_form'] #med_name").hide();
// 		$("form[name='review_medications_form'] .alert-success").show();
// 		$("form[name='review_medications_form'] .alert-danger").hide();
// 		var scrollPos = $(".main-content").offset().top;
// 		$(window).scrollTop(scrollPos);
// 		CompletedCheck();
// 		util.updateMedicationList($("form[name='review_medications_form'] #medication_med_id"));
// 		util.updateMedicationList($("form[name='medications_form'] #medication_med_id"));
// 		setTimeout(function () {
// 			$('.alert').fadeOut('fast');
// 		}, 3000);
// 		var timer_paused = $("form[name='review_medications_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 		// $("#timer_end").val(timer_paused);
// 	} else {
// 		$("form[name='review_medications_form'] .alert-success").hide();
// 		$("form[name='review_medications_form'] .alert-danger").show();
// 	}
// };

var onPatientRelationshipData = function (formObj, fields, response) {
	var form_name = fields.values.form_name;
	if (response.status == 200) {
		var relational_status_yes = 0;
		if ($("form[name='" + form_name + "'] #relational_status_yes").prop("checked")) {
			relational_status_yes = 1;
		}
		$("#" + form_name)[0].reset();
		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
		var relativeval = $("form[name='" + form_name + "'] #review_live_relationship_0").val();
		if (relativeval == '0') {
			util.updateRelationshipList("form[name='" + form_name + "'] #review_live_relationship_0");
		}
		$("form[name='" + form_name + "'] #relatnname_0").hide();
		if (form_name == 'review_do_you_live_with_anyone_form') {
			renderLiveWithTable();
		} else if (form_name == 'grandchildren_form') {
			renderGrandchildrenTable();
		} else if (form_name == 'children_form') {
			renderChildrenTable();
		} else if (form_name == 'sibling_form') {
			renderSiblingTable();
		}

		var radioValue = $("form[name='" + form_name + "'] input[name='relational_status']:checked").val();
		if (radioValue == 1) {
			$("form[name='" + form_name + "'] #relational_status_no").prop("disabled", true);
		}
		if (relational_status_yes == 1) {
			$("form[name='" + form_name + "'] #relational_status_yes").prop("checked", true);
		}
		// populateformdata();
		var scrollPos = $(".main-content").offset().top;
		$(window).scrollTop(scrollPos);
		$("form[name='" + form_name + "'] .alert-success").show();
		$("form[name='" + form_name + "'] .alert-danger").hide();
		CompletedCheck();
		setTimeout(function () {
			$('.alert').fadeOut('fast');
		}, 3000);
		var timer_paused = $("form[name='" + form_name + "'] input[name='end_time']").val();
		$("#timer_start").val(timer_paused);
		// $("#timer_end").val(timer_paused);
	} else {
		$("form[name='" + form_name + "'] .alert-success").hide();
		$("form[name='" + form_name + "'] .alert-danger").show();
	}
};

// var onReviewGrandchildren = function (formObj, fields, response) {
// 	//console.log(response);
// 	if (response.status == 200) {
// 		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 		$("form[name='grandchildren_form'] input[name='id']").val('');
// 		$("form[name='grandchildren_form'] #review_sibling_fname").val('');
// 		$("form[name='grandchildren_form'] #review_sibling_lname").val('');
// 		$("form[name='grandchildren_form'] #review_sibling_age").val('');
// 		$("form[name='grandchildren_form'] #review_sibling_address").val('');
// 		$("form[name='grandchildren_form'] #review_sibling_additional_notes").val('');
// 		// $("#grandchildren_form")[0].reset();
// 		$("form[name='grandchildren_form'] .alert-success").show();
// 		$("form[name='grandchildren_form'] .alert-danger").hide();
// 		var scrollPos = $(".main-content").offset().top;
// 		$(window).scrollTop(scrollPos);
// 		CompletedCheck();
// 		renderGrandchildrenTable();
// 		var radioValue = $("form[name='grandchildren_form'] input[name='relational_status']:checked").val();
// 		if (radioValue == 1) {
// 			$("form[name='grandchildren_form'] #relational_status_no").prop("disabled", true);
// 		}
// 		setTimeout(function () {
// 			$('.alert').fadeOut('fast');
// 		}, 3000);
// 		var timer_paused = $("form[name='grandchildren_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 		// $("#timer_end").val(timer_paused);
// 	} else {
// 		$("form[name='grandchildren_form'] .alert-success").hide();
// 		$("form[name='grandchildren_form'] .alert-danger").show();
// 	}
// };

// var onReviewChildren = function (formObj, fields, response) {
// 	//console.log(response);
// 	if (response.status == 200) {
// 		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 		$("form[name='children_form'] input[name='id']").val('');
// 		$("form[name='children_form'] #review_sibling_fname").val('');
// 		$("form[name='children_form'] #review_sibling_lname").val('');
// 		$("form[name='children_form'] #review_sibling_age").val('');
// 		$("form[name='children_form'] #review_sibling_address").val('');
// 		$("form[name='children_form'] #review_sibling_additional_notes").val('');
// 		$("form[name='children_form'] .alert-success").show();
// 		var scrollPos = $(".main-content").offset().top;
// 		$(window).scrollTop(scrollPos);
// 		CompletedCheck();
// 		renderChildrenTable();
// 		var radioValue = $("form[name='children_form'] input[name='relational_status']:checked").val();
// 		if (radioValue == 1) {
// 			$("form[name='children_form'] #relational_status_no").prop("disabled", true);
// 		}
// 		setTimeout(function () {
// 			$('.alert').fadeOut('fast');
// 		}, 3000);
// 		var timer_paused = $("form[name='children_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 		// $("#timer_end").val(timer_paused);
// 	} else {
// 		$("form[name='children_form'] .alert-success").hide();
// 		$("form[name='children_form'] .alert-danger").show();
// 	}
// };

// var onReviewSibling = function (formObj, fields, response) {
// 	//console.log(response);
// 	if (response.status == 200) {
// 		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 		$("form[name='sibling_form'] input[name='id']").val('');
// 		$("form[name='sibling_form'] #review_sibling_fname").val('');
// 		$("form[name='sibling_form'] #review_sibling_lname").val('');
// 		$("form[name='sibling_form'] #review_sibling_age").val('');
// 		$("form[name='sibling_form'] #review_sibling_address").val('');
// 		$("form[name='sibling_form'] #review_sibling_additional_notes").val('');
// 		$("form[name='sibling_form'] .alert-success").show();
// 		$("form[name='sibling_form'] .alert-danger").hide();
// 		var scrollPos = $(".main-content").offset().top;
// 		$(window).scrollTop(scrollPos);
// 		CompletedCheck();
// 		renderSiblingTable();
// 		var radioValue = $("form[name='sibling_form'] input[name='relational_status']:checked").val();
// 		if (radioValue == 1) {
// 			$("form[name='sibling_form'] #relational_status_no").prop("disabled", true);
// 		}
// 		setTimeout(function () {
// 			$('.alert').fadeOut('fast');
// 		}, 3000);
// 		var timer_paused = $("form[name='sibling_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 		// $("#timer_end").val(timer_paused);
// 	} else {
// 		$("form[name='sibling_form'] .alert-success").hide();
// 		$("form[name='sibling_form'] .alert-danger").show();
// 	}
// };

//review- allergy

// var onReviewFoodAllergy = function (formObj, fields, response) {
// 	//console.log(response);
// 	if (response.status == 200) {
// 		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 		$("#review_allergy_food_form")[0].reset();
// 		$("#allergy_food_form")[0].reset();
// 		$('form[name="allergy_food_form"] #id').val('');
// 		$('form[name="review_allergy_food_form"] #id').val('');
// 		$("form[name='review_allergy_food_form'] .alert-success").show();
// 		$("form[name='review_allergy_food_form'] .alert-danger").hide();
// 		var scrollPos = $(".main-content").offset().top;
// 		$(window).scrollTop(scrollPos);
// 		var allergy_type = $('form[name="review_allergy_food_form"] input[name="allergy_type"]').val();
// 		var id = $("#patient_id").val();
// 		util.refreshAllergyCountCheckbox(id, allergy_type, 'review_allergy_food_form');
// 		renderFoodTable();
// 		CompletedCheck();
// 		setTimeout(function () {
// 			$('.alert').fadeOut('fast');
// 		}, 3000);
// 		var timer_paused = $("form[name='review_allergy_food_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 		// $("#timer_end").val(timer_paused);
// 	} else {
// 		$("form[name='review_allergy_food_form'] .alert-success").hide();
// 		$("form[name='review_allergy_food_form'] .alert-danger").show();
// 	}
// };

// var onReviewDrugAllergy = function (formObj, fields, response) {
// 	//console.log(response);
// 	if (response.status == 200) {
// 		////refreshdrugcountcheckbox();
// 		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 		$("#review_allergy_drug_form")[0].reset();
// 		$("#allergy_drug_form")[0].reset();
// 		$('form[name="allergy_drug_form"] #id').val('');
// 		$('form[name="review_allergy_drug_form"] #id').val('');
// 		$("form[name='review_allergy_drug_form'] .alert-success").show();
// 		$("form[name='review_allergy_drug_form'] .alert-danger").hide();
// 		var scrollPos = $(".main-content").offset().top;
// 		$(window).scrollTop(scrollPos);
// 		var allergy_type = $('form[name="review_allergy_drug_form"] input[name="allergy_type"]').val();
// 		var id = $("#patient_id").val();
// 		util.refreshAllergyCountCheckbox(id, allergy_type, 'review_allergy_drug_form');
// 		renderdrugTable();
// 		CompletedCheck();
// 		setTimeout(function () {
// 			$('.alert').fadeOut('fast');
// 		}, 3000);
// 		var timer_paused = $("form[name='review_allergy_drug_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 		// $("#timer_end").val(timer_paused);
// 	} else {
// 		$("form[name='review_allergy_drug_form'] .alert-success").hide();
// 		$("form[name='review_allergy_drug_form'] .alert-danger").show();
// 	}
// };

// var onReviewEnviromentalAllergy = function (formObj, fields, response) {
// 	//console.log(response);
// 	if (response.status == 200) {
// 		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 		$("#review_allergy_enviromental_form")[0].reset();
// 		$("#allergy_enviromental_form")[0].reset();
// 		$('form[name="allergy_enviromental_form"] #id').val('');
// 		$('form[name="review_allergy_enviromental_form"] #id').val('');
// 		$("form[name='review_allergy_enviromental_form'] .alert-success").show();
// 		$("form[name='review_allergy_enviromental_form'] .alert-danger").hide();
// 		var scrollPos = $(".main-content").offset().top;
// 		$(window).scrollTop(scrollPos);
// 		var allergy_type = $('form[name="review_allergy_enviromental_form"] input[name="allergy_type"]').val();
// 		var id = $("#patient_id").val();
// 		util.refreshAllergyCountCheckbox(id, allergy_type, 'review_allergy_enviromental_form');
// 		renderEnviromentalTable();
// 		CompletedCheck();
// 		setTimeout(function () {
// 			$('.alert').fadeOut('fast');
// 		}, 3000);
// 		var timer_paused = $("form[name='review_allergy_enviromental_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 		// $("#timer_end").val(timer_paused);
// 	} else {
// 		$("form[name='review_allergy_enviromental_form'] .alert-success").hide();
// 		$("form[name='review_allergy_enviromental_form'] .alert-danger").show();
// 	}
// };

// var onReviewInsectAllergy = function (formObj, fields, response) {
// 	//console.log(response);
// 	if (response.status == 200) {
// 		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 		$("#review_allergy_insect_form")[0].reset();
// 		$("#allergy_insect_form")[0].reset();
// 		$('form[name="allergy_insect_form"] #id').val('');
// 		$('form[name="review_allergy_insect_form"] #id').val('');
// 		$("form[name='review_allergy_insect_form'] .alert-success").show();
// 		$("form[name='review_allergy_insect_form'] .alert-danger").hide();
// 		var scrollPos = $(".main-content").offset().top;
// 		$(window).scrollTop(scrollPos);
// 		CompletedCheck();
// 		var allergy_type = $('form[name="review_allergy_insect_form"] input[name="allergy_type"]').val();
// 		var id = $("#patient_id").val();
// 		util.refreshAllergyCountCheckbox(id, allergy_type, 'review_allergy_insect_form');
// 		renderinsectTable();
// 		setTimeout(function () {
// 			$('.alert').fadeOut('fast');
// 		}, 3000);
// 		var timer_paused = $("form[name='review_allergy_insect_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 		// $("#timer_end").val(timer_paused);
// 	} else {
// 		$("form[name='review_allergy_insect_form'] .alert-success").hide();
// 		$("form[name='review_allergy_insect_form'] .alert-danger").show();
// 	}
// };

// var onReviewLatexAllergy = function (formObj, fields, response) {
// 	//console.log(response);
// 	if (response.status == 200) {
// 		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 		$("#review_allergy_latex_form")[0].reset();
// 		$("#allergy_latex_form")[0].reset();
// 		$('form[name="allergy_latex_form"] #id').val('');
// 		$('form[name="review_allergy_latex_form"] #id').val('');
// 		$("form[name='review_allergy_latex_form'] .alert-success").show();
// 		$("form[name='review_allergy_latex_form'] .alert-danger").hide();
// 		var scrollPos = $(".main-content").offset().top;
// 		$(window).scrollTop(scrollPos);
// 		CompletedCheck();
// 		var allergy_type = $('form[name="review_allergy_latex_form"] input[name="allergy_type"]').val();
// 		var id = $("#patient_id").val();
// 		util.refreshAllergyCountCheckbox(id, allergy_type, 'review_allergy_latex_form');
// 		renderLatexTable();
// 		setTimeout(function () {
// 			$('.alert').fadeOut('fast');
// 		}, 3000);
// 		var timer_paused = $("form[name='review_allergy_latex_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 		// $("#timer_end").val(timer_paused);
// 	} else {
// 		$("form[name='review_allergy_latex_form'] .alert-success").hide();
// 		$("form[name='review_allergy_latex_form'] .alert-danger").show();
// 	}
// };

// var onReviewPetRelatedAllergy = function (formObj, fields, response) {
// 	//console.log(response);
// 	if (response.status == 200) {
// 		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 		$("#review_allergy_pet_related_form")[0].reset();
// 		$("#allergy_pet_related_form")[0].reset();
// 		$('form[name="allergy_pet_related_form"] #id').val('');
// 		$('form[name="review_allergy_pet_related_form"] #id').val('');
// 		$("form[name='review_allergy_pet_related_form'] .alert-success").show();
// 		$("form[name='review_allergy_pet_related_form'] .alert-danger").hide();
// 		var scrollPos = $(".main-content").offset().top;
// 		$(window).scrollTop(scrollPos);
// 		CompletedCheck();
// 		var allergy_type = $('form[name="review_allergy_pet_related_form"] input[name="allergy_type"]').val();
// 		var id = $("#patient_id").val();
// 		util.refreshAllergyCountCheckbox(id, allergy_type, 'review_allergy_pet_related_form');
// 		renderPetRelatedTable();
// 		setTimeout(function () {
// 			$('.alert').fadeOut('fast');
// 		}, 3000);
// 		var timer_paused = $("form[name='review_allergy_pet_related_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 	} else {
// 		$("form[name='review_allergy_pet_related_form'] .alert-success").hide();
// 		$("form[name='review_allergy_pet_related_form'] .alert-danger").show();
// 	}
// };

// var onReviewOtherAllergy = function (formObj, fields, response) {
// 	//console.log(response);
// 	if (response.status == 200) {
// 		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 		$("#review_allergy_other_allergy_form")[0].reset();
// 		$("#allergy_other_allergy_form")[0].reset();
// 		$('form[name="allergy_other_allergy_form"] #id').val('');
// 		$('form[name="review_allergy_other_allergy_form"] #id').val('');
// 		$("form[name='review_allergy_other_allergy_form'] .alert-success").show();
// 		$("form[name='review_allergy_other_allergy_form'] .alert-danger").hide();
// 		renderAllergyOtherTable();
// 		CompletedCheck();
// 		var scrollPos = $(".main-content").offset().top;
// 		$(window).scrollTop(scrollPos);
// 		var allergy_type = $('form[name="review_allergy_other_allergy_form"] input[name="allergy_type"]').val();
// 		var id = $("#patient_id").val();
// 		util.refreshAllergyCountCheckbox(id, allergy_type, 'review_allergy_other_allergy_form');
// 		setTimeout(function () {
// 			$('.alert').fadeOut('fast');
// 		}, 3000);
// 		var timer_paused = $("form[name='review_allergy_other_allergy_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 	} else {
// 		$("form[name='review_allergy_other_allergy_form'] .alert-success").hide();
// 		$("form[name='review_allergy_other_allergy_form'] .alert-danger").show();
// 	}
// };

//Review Services

// var onReviewDialysisServices = function (formObj, fields, response) {
// 	//console.log(response);
// 	if (response.status == 200) {
// 		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 		$("#service_dme_form")[0].reset();
// 		$("#service_home_health_form")[0].reset();
// 		$("form[name='service_dialysis_form']")[0].reset();
// 		$("#service_therapy_form")[0].reset();
// 		$("#service_social_form")[0].reset();
// 		$("#service_medical_supplies_form")[0].reset();
// 		$("#service_other_health_form")[0].reset();

// 		$("#review_service_dme_form")[0].reset();
// 		$("#review_service_home_health_form")[0].reset();
// 		$("form[name='review_service_dialysis_form']")[0].reset();
// 		$("#review_service_medical_supplies_form")[0].reset();
// 		$("#review_service_other_health_form")[0].reset();
// 		$("#review_service_social_form")[0].reset();
// 		$("#review_service_therapy_form")[0].reset();

// 		$("form[name='review_service_dialysis_form'] .alert-success").show();
// 		$("form[name='review_service_dialysis_form'] .alert-danger").hide();
// 		renderDialysiServicesTable();
// 		var scrollPos = $(".main-content").offset().top;
// 		$(window).scrollTop(scrollPos);
// 		CompletedCheck();
// 		setTimeout(function () {
// 			$('.alert').fadeOut('fast');
// 		}, 3000);
// 		var timer_paused = $("form[name='review_service_dialysis_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 	} else {
// 		$("form[name='review_service_dialysis_form'] .alert-success").hide();
// 		$("form[name='review_service_dialysis_form'] .alert-danger").show();
// 	}
// };

// var onReviewOtherServices = function (formObj, fields, response) {
// 	//console.log(response);
// 	if (response.status == 200) {
// 		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 		$("#service_dme_form")[0].reset();
// 		$("#service_home_health_form")[0].reset();
// 		$("form[name='service_dialysis_form']")[0].reset();
// 		$("#service_therapy_form")[0].reset();
// 		$("#service_social_form")[0].reset();
// 		$("#service_medical_supplies_form")[0].reset();
// 		$("#service_other_health_form")[0].reset();

// 		$("#review_service_dme_form")[0].reset();
// 		$("#review_service_home_health_form")[0].reset();
// 		$("form[name='review_service_dialysis_form']")[0].reset();
// 		$("#review_service_medical_supplies_form")[0].reset();
// 		$("#review_service_other_health_form")[0].reset();
// 		$("#review_service_social_form")[0].reset();
// 		$("#review_service_therapy_form")[0].reset();

// 		$("form[name='review_service_other_health_form'] .alert-success").show();
// 		$("form[name='review_service_other_health_form'] .alert-danger").hide();
// 		renderOtherHealthServicesTable();
// 		CompletedCheck();
// 		var scrollPos = $(".main-content").offset().top;
// 		$(window).scrollTop(scrollPos);
// 		setTimeout(function () {
// 			$('.alert').fadeOut('fast');
// 		}, 3000);
// 		var timer_paused = $("form[name='review_service_other_health_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 	} else {
// 		$("form[name='review_service_other_health_form'] .alert-success").hide();
// 		$("form[name='review_service_other_health_form'] .alert-danger").show();
// 	}
// };

// var onReviewSocialServices = function (formObj, fields, response) {
// 	//console.log(response);
// 	if (response.status == 200) {
// 		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 		$("#service_dme_form")[0].reset();
// 		$("#service_home_health_form")[0].reset();
// 		$("form[name='service_dialysis_form']")[0].reset();
// 		$("#service_therapy_form")[0].reset();
// 		$("#service_social_form")[0].reset();
// 		$("#service_medical_supplies_form")[0].reset();
// 		$("#service_other_health_form")[0].reset();

// 		$("#review_service_dme_form")[0].reset();
// 		$("#review_service_home_health_form")[0].reset();
// 		$("form[name='review_service_dialysis_form']")[0].reset();
// 		$("#review_service_medical_supplies_form")[0].reset();
// 		$("#review_service_other_health_form")[0].reset();
// 		$("#review_service_social_form")[0].reset();
// 		$("#review_service_therapy_form")[0].reset();

// 		$("form[name='review_service_social_form'] .alert-success").show();
// 		$("form[name='review_service_social_form'] .alert-danger").hide();
// 		renderSocialServicesTable();
// 		var scrollPos = $(".main-content").offset().top;
// 		$(window).scrollTop(scrollPos);
// 		CompletedCheck();
// 		setTimeout(function () {
// 			$('.alert').fadeOut('fast');
// 		}, 3000);
// 		var timer_paused = $("form[name='review_service_social_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 		// $("#timer_end").val(timer_paused);
// 	} else {
// 		$("form[name='review_service_social_form'] .alert-success").hide();
// 		$("form[name='review_service_social_form'] .alert-danger").show();
// 	}
// };

// var onReviewTherapyServices = function (formObj, fields, response) {
// 	//console.log(response);
// 	if (response.status == 200) {
// 		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 		$("#service_dme_form")[0].reset();
// 		$("#service_home_health_form")[0].reset();
// 		$("form[name='service_dialysis_form']")[0].reset();
// 		$("#service_therapy_form")[0].reset();
// 		$("#service_social_form")[0].reset();
// 		$("#service_medical_supplies_form")[0].reset();
// 		$("#service_other_health_form")[0].reset();

// 		$("#review_service_dme_form")[0].reset();
// 		$("#review_service_home_health_form")[0].reset();
// 		$("form[name='review_service_dialysis_form']")[0].reset();
// 		$("#review_service_medical_supplies_form")[0].reset();
// 		$("#review_service_other_health_form")[0].reset();
// 		$("#review_service_social_form")[0].reset();
// 		$("#review_service_therapy_form")[0].reset();

// 		$("form[name='review_service_therapy_form'] .alert-success").show();
// 		$("form[name='review_service_therapy_form'] .alert-danger").hide();
// 		renderTherapyServicesTable();
// 		var scrollPos = $(".main-content").offset().top;
// 		$(window).scrollTop(scrollPos);
// 		CompletedCheck();
// 		setTimeout(function () {
// 			$('.alert').fadeOut('fast');
// 		}, 3000);
// 		var timer_paused = $("form[name='review_service_therapy_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 	} else {
// 		$("form[name='review_service_therapy_form'] .alert-success").hide();
// 		$("form[name='review_service_therapy_form'] .alert-danger").show();
// 	}
// };

// var onReviewDmeServices = function (formObj, fields, response) {
// 	//console.log(response);
// 	if (response.status == 200) {
// 		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 		$("#service_dme_form")[0].reset();
// 		$("#service_home_health_form")[0].reset();
// 		$("form[name='service_dialysis_form']")[0].reset();
// 		$("#service_therapy_form")[0].reset();
// 		$("#service_social_form")[0].reset();
// 		$("#service_medical_supplies_form")[0].reset();
// 		$("#service_other_health_form")[0].reset();

// 		$("#review_service_dme_form")[0].reset();
// 		$("#review_service_home_health_form")[0].reset();
// 		$("form[name='review_service_dialysis_form']")[0].reset();
// 		$("#review_service_medical_supplies_form")[0].reset();
// 		$("#review_service_other_health_form")[0].reset();
// 		$("#review_service_social_form")[0].reset();
// 		$("#review_service_therapy_form")[0].reset();

// 		$("form[name='review_service_dme_form'] .alert-success").show();
// 		$("form[name='review_service_dme_form'] .alert-danger").hide();
// 		renderDMEReviewServicesTable();
// 		renderDMEServicesTable();
// 		var scrollPos = $(".main-content").offset().top;
// 		$(window).scrollTop(scrollPos);
// 		CompletedCheck();
// 		setTimeout(function () {
// 			$('.alert').fadeOut('fast');
// 		}, 3000);
// 		var timer_paused = $("form[name='review_service_dme_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 		// $("#timer_end").val(timer_paused);
// 	} else {
// 		$("form[name='review_service_dme_form'] .alert-success").hide();
// 		$("form[name='review_service_dme_form'] .alert-danger").show();
// 	}
// };

// var onReviewMedicalSuppliesServices = function (formObj, fields, response) {
// 	//console.log(response);
// 	if (response.status == 200) {
// 		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 		$("#service_dme_form")[0].reset();
// 		$("#service_home_health_form")[0].reset();
// 		$("form[name='service_dialysis_form']")[0].reset();
// 		$("#service_therapy_form")[0].reset();
// 		$("#service_social_form")[0].reset();
// 		$("#service_medical_supplies_form")[0].reset();
// 		$("#service_other_health_form")[0].reset();

// 		$("#review_service_dme_form")[0].reset();
// 		$("#review_service_home_health_form")[0].reset();
// 		$("form[name='review_service_dialysis_form']")[0].reset();
// 		$("#review_service_medical_supplies_form")[0].reset();
// 		$("#review_service_other_health_form")[0].reset();
// 		$("#review_service_social_form")[0].reset();
// 		$("#review_service_therapy_form")[0].reset();
// 		$("form[name='review_service_medical_supplies_form'] .alert-success").show();
// 		$("form[name='review_service_medical_supplies_form'] .alert-danger").hide();
// 		renderMedicalSuppliesServicesTable();
// 		CompletedCheck();
// 		var scrollPos = $(".main-content").offset().top;
// 		$(window).scrollTop(scrollPos);
// 		setTimeout(function () {
// 			$('.alert').fadeOut('fast');
// 		}, 3000);
// 		var timer_paused = $("form[name='review_service_medical_supplies_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 	} else {
// 		$("form[name='review_service_medical_supplies_form'] .alert-success").hide();
// 		$("form[name='review_service_medical_supplies_form'] .alert-danger").show();
// 	}
// };

// var onReviewHomeHealthServices = function (formObj, fields, response) {
// 	//console.log(response);
// 	if (response.status == 200) {
// 		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 		$("#service_dme_form")[0].reset();
// 		$("#service_home_health_form")[0].reset();
// 		$("form[name='service_dialysis_form']")[0].reset();
// 		$("#service_therapy_form")[0].reset();
// 		$("#service_social_form")[0].reset();
// 		$("#service_medical_supplies_form")[0].reset();
// 		$("#service_other_health_form")[0].reset();

// 		$("#review_service_dme_form")[0].reset();
// 		$("#review_service_home_health_form")[0].reset();
// 		$("form[name='review_service_dialysis_form']")[0].reset();
// 		$("#review_service_medical_supplies_form")[0].reset();
// 		$("#review_service_other_health_form")[0].reset();
// 		$("#review_service_social_form")[0].reset();
// 		$("#review_service_therapy_form")[0].reset();

// 		$("form[name='review_service_home_health_form'] .alert-success").show();
// 		$("form[name='review_service_home_health_form'] .alert-danger").hide();
// 		renderHomeHealthServicesTable();
// 		CompletedCheck();
// 		var scrollPos = $(".main-content").offset().top;
// 		$(window).scrollTop(scrollPos);
// 		setTimeout(function () {
// 			$('.alert').fadeOut('fast');
// 		}, 3000);
// 		var timer_paused = $("form[name='review_service_home_health_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 	} else {
// 		$("form[name='review_service_home_health_form'] .alert-success").hide();
// 		$("form[name='review_service_home_health_form'] .alert-danger").show();
// 	}
// };

//MEdication

var onMedication = function (formObj, fields, response) {
	var form_name = fields.values.form_name;
	if (response.status == 200) {
		var module_id = $("input[name='module_id']").val();
		util.updateTimer(patient_id, $("input[name='billable']").val(), module_id);
		// var patient_id = $("#patient_id").val();
		util.getPatientCareplanNotes(patient_id, module_id);
		util.getPatientStatus(patient_id, module_id);
		$("#" + form_name)[0].reset();
		$("form[name='" + form_name + "'] #med_name").hide();
		var scrollPos = $(".main-content").offset().top;
		$(window).scrollTop(scrollPos);
		$("form[name='" + form_name + "'] .alert-success").show();
		$("form[name='" + form_name + "'] .alert-danger").hide();
		util.updateMedicationList($("form[name='" + form_name + "'] #medication_med_id"));
		if (module == 'care-plan-development') {
			CompletedCheck();
			renderMedicationsTable();
		} else {
			renderMedicationsTableData();
		}
		setTimeout(function () {
			$('.alert').fadeOut('fast');
		}, 3000);
		var timer_paused = $("form[name='" + form_name + "'] input[name='end_time']").val();
		$("#timer_start").val(timer_paused);
		// $("#timer_end").val(timer_paused);
	} else {
		$("form[name='" + form_name + "'] .alert-success").hide();
		$("form[name='" + form_name + "'] .alert-danger").show();
	}
};

//diagnosis
var onDiagnosis = function (formObj, fields, response) {
	var form_name = fields.values.form_name;
	if (response.status == 200) {
		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
		// var patient_id = $("#patient_id").val();
		var module_id = $("input[name='module_id']").val();
		var year = (new Date).getFullYear();
		var month = (new Date).getMonth() + 1; //add +1 for current mnth
		util.getPatientStatus(patient_id, module_id);
		util.getPatientCareplanNotes(patient_id, module_id);
		util.getPatientPreviousMonthNotes(patient_id, module_id, month, year);
		$("form[name='" + form_name + "']")[0].reset();
		$("form[name='" + form_name + "'] #append_symptoms").html("");
		$("form[name='" + form_name + "'] #append_goals").html("");
		$("form[name='" + form_name + "'] #append_tasks").html("");

		// $("form[name='"+form_name+"'] #support").val('');
		// $("form[name='"+form_name+"'] #symptoms_0").val('');
		// $("form[name='"+form_name+"'] #goals_0").val('');
		// $("form[name='"+form_name+"'] #tasks_0").val('');
		// $("form[name='"+form_name+"'] input[name='comments']").val('');
		// $("form[name='"+form_name+"'] input[name='id']").val('');

		// $('form[name=""+form_name+""]  #new_code').val('');
		$('.emaillist').removeClass("col-md-3").addClass("col-md-6");
		$('.otherlist').hide();
		inc_symptoms = 0;
		inc_goals = 0;
		inc_tasks = 0;
		if (module == 'care-plan-development') {
			CompletedCheck();
			renderDiagnosisTable();
		} else {
			renderDiagnosisTableData();
		}
		var scrollPos = $(".main-content").offset().top;
		$(window).scrollTop(scrollPos);
		$("form[name='" + form_name + "'] .alert-danger").hide();
		$("form[name='" + form_name + "'] .alert-success").show();
		$("form[name='" + form_name + "'] #enable_diagnosis_button").hide();
		setTimeout(function () {
			$('.alert').fadeOut('fast');
		}, 5000);
		var timer_paused = $("form[name='" + form_name + "'] input[name='end_time']").val();
		$("#timer_start").val(timer_paused);
		// $("#timer_end").val(timer_paused);
	} else {
		$("form[name='" + form_name + "'] .alert-success").hide();
		$("form[name='" + form_name + "'] .alert-danger").show();
	}
};

//diagnosis
// var onReviewDiagnosis = function (formObj, fields, response) {
// 	if (response.status == 200) {
// 		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 		var patient_id = $("#patient_id").val();
// 		var module_id = $("input[name='module_id']").val();
// 		util.getPatientStatus(patient_id, module_id);
// 		util.getPatientCareplanNotes(patient_id, module_id);
// 		var year = (new Date).getFullYear();
// 		var month = (new Date).getMonth() + 1; //add +1 for current mnth
// 		util.getPatientPreviousMonthNotes(patient_id, module_id, month, year);
// 		$("form[name='review_diagnosis_code_form']")[0].reset();
// 		$("form[name='diagnosis_code_form'] input[name='id']").val('');
// 		$("form[name='review_diagnosis_code_form'] input[name='id']").val('');
// 		$("form[name='diagnosis_code_form']")[0].reset();
// 		renderDiagnosisTable();
// 		CompletedCheck();
// 		review_inc_tasks = 0;
// 		review_inc_symptoms = 0;
// 		review_inc_goals = 0;

// 		$('form[name="review_diagnosis_code_form"] #append_symptoms').html("");
// 		$('form[name="review_diagnosis_code_form"] #append_goals').html("");
// 		$('form[name="review_diagnosis_code_form"] #append_tasks').html("");

// 		$('form[name="review_diagnosis_code_form"] #support').val('');
// 		$('form[name="review_diagnosis_code_form"] #symptoms_0').val('');
// 		$('form[name="review_diagnosis_code_form"] #goals_0').val('');
// 		$("form[name='review_diagnosis_code_form'] #tasks_0").val('');
// 		$("form[name='review_diagnosis_code_form'] input[name='comments']").val('');
// 		$("#new_code").val('');
// 		$('.emaillist').removeClass("col-md-3").addClass("col-md-6");
// 		$('.otherlist').hide();
// 		$("form[name='review_diagnosis_code_form'] .alert-success").show();
// 		$("form[name='review_diagnosis_code_form'] .alert-danger").hide();
// 		var scrollPos = $(".main-content").offset().top;
// 		$(window).scrollTop(scrollPos);
// 		setTimeout(function () {
// 			$('.alert').fadeOut('fast');
// 		}, 5000);
// 		var timer_paused = $("form[name='review_diagnosis_code_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 		// $("#timer_end").val(timer_paused);
// 	} else {
// 		$("form[name='review_diagnosis_code_form'] .alert-success").hide();
// 		$("form[name='review_diagnosis_code_form'] .alert-danger").show();
// 	}
// };

//review-pet

var onOtherPersonalData = function (formObj, fields, response) {
	var form_name = fields.values.form_name;
	var status_for = "";
	var status_yes = 0;
	if (response.status == 200) {
		util.updateTimer(patient_id, $("input[name='billable']").val(), $("input[name='module_id']").val());
		var scrollPos = $(".main-content").offset().top;
		$(window).scrollTop(scrollPos);
		$("form[name='" + form_name + "'] .alert-success").show();
		$("form[name='" + form_name + "'] .alert-danger").hide();
		if (form_name == "review_pets_form") {
			status_for = "pet_status";
			renderPetTable();
		} else if (form_name == "review_travel_form") {
			status_for = "travel_status";
			renderTravelTable();
		} else if (form_name == "review_hobbies_form") {
			status_for = "hobbies_status";
			renderHobbiesTable();
		}
		var radioValue = $("form[name='" + form_name + "'] input[name='" + status_for + "']:checked").val();
		$("#" + form_name)[0].reset();
		if (radioValue == 1) {
			$("form[name='" + form_name + "'] #" + status_for + "_no").prop("disabled", true);
			$("form[name='" + form_name + "'] #" + status_for + "_yes").prop("checked", true);
		}
		setTimeout(function () {
			$('.alert').fadeOut('fast');
		}, 3000);
		var timer_paused = $("form[name='" + form_name + "'] input[name='end_time']").val();
		$("#timer_start").val(timer_paused);
		// $("#timer_end").val(timer_paused);
	} else {
		$("form[name='" + form_name + "'] .alert-success").hide();
		$("form[name='" + form_name + "'] .alert-danger").show();
	}
};

// var onReviewpet = function (formObj, fields, response) {
// 	if (response.status == 200) {
// 		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 		$("form[name='review_pets_form'] input[name='id']").val('');
// 		$("#review_pet_name").val('');
// 		$("#review_pet_type").val('');
// 		$("#review_pet_notes").val('');
// 		$("form[name='review_pets_form'] .alert-success").show();
// 		$("form[name='review_pets_form'] .alert-danger").hide();
// 		var scrollPos = $(".main-content").offset().top;
// 		$(window).scrollTop(scrollPos);
// 		renderPetTable();
// 		var radioValue = $("form[name='review_pets_form'] input[name='pet_status']:checked").val();
// 		if (radioValue == 1) {
// 			$("form[name='review_pets_form'] #pet_status_no").prop("disabled", true);
// 		}
// 		setTimeout(function () {
// 			$('.alert').fadeOut('fast');
// 		}, 3000);
// 		var timer_paused = $("form[name='review_pets_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 		// $("#timer_end").val(timer_paused);
// 	} else {
// 		$("form[name='review_pets_form'] .alert-success").hide();
// 		$("form[name='review_pets_form'] .alert-danger").show();
// 	}
// };

//Travel
// var onReviewTravel = function (formObj, fields, response) {
// 	if (response.status == 200) {
// 		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 		$("form[name='review_travel_form'] input[name='id']").val('');
// 		$("#review_travel_location").val('');
// 		$("#review_travel_travel_type").val('');
// 		$("#review_travel_frequency").val('');
// 		$('#review_travel_with_whom').val('');
// 		$('#review_travel_upcoming_tips').val('');
// 		$('#review_travel_notes').val('');
// 		$("form[name='review_travel_form'] .alert-success").show();
// 		$("form[name='review_travel_form'] .alert-danger").hide();
// 		var scrollPos = $(".main-content").offset().top;
// 		$(window).scrollTop(scrollPos);
// 		renderTravelTable();
// 		var radioValue = $("form[name='review_travel_form'] input[name='travel_status']:checked").val();
// 		if (radioValue == 1) {
// 			$("form[name='review_travel_form'] #travel_status_no").prop("disabled", true);
// 		}
// 		setTimeout(function () {
// 			$('.alert').fadeOut('fast');
// 		}, 3000);
// 		var timer_paused = $("form[name='review_travel_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 		// $("#timer_end").val(timer_paused);
// 	} else {
// 		$("form[name='review_travel_form'] .alert-success").hide();
// 		$("form[name='review_travel_form'] .alert-danger").show();
// 	}
// };

//Review Hobies
// var onReviewHobies = function (formObj, fields, response) {
// 	if (response.status == 200) {
// 		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
// 		$("form[name='review_hobbies_form'] input[name='id']").val('');
// 		$("#review_hobbie_description").val('');
// 		$("#review_hobbie_location").val('');
// 		$("#review_hobbie_frequency").val('');
// 		$('#review_hobbie_with_whom').val('');
// 		$('#review_hobbie_notes').val('');
// 		$("form[name='review_hobbies_form'] .alert-success").show();
// 		$("form[name='review_hobbies_form'] .alert-danger").hide();
// 		var scrollPos = $(".main-content").offset().top;
// 		$(window).scrollTop(scrollPos);
// 		renderHobbiesTable();
// 		var radioValue = $("form[name='review_hobbies_form'] input[name='hobbies_status']:checked").val();
// 		if (radioValue == 1) {
// 			$("form[name='review_hobbies_form'] #hobbies_status_no").prop("disabled", true);
// 		}
// 		setTimeout(function () {
// 			$('.alert').fadeOut('fast');
// 		}, 3000);
// 		var timer_paused = $("form[name='review_hobbies_form'] input[name='end_time']").val();
// 		$("#timer_start").val(timer_paused);
// 		// $("#timer_end").val(timer_paused);
// 	} else {
// 		$("form[name='review_hobbies_form'] .alert-success").hide();
// 		$("form[name='review_hobbies_form'] .alert-danger").show();
// 	}
// };


var onFirstReview = function (formObj, fields, response) {
	if (response.status == 200) {
		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
		$("form[name='patient_first_review'] .alert-success").show();
		var scrollPos = $(".main-content").offset().top;
		$(window).scrollTop(scrollPos);
		setTimeout(function () { $("form[name='patient_first_review'] .alert").fadeOut(); }, 3000);
		var timer_paused = $("form[name='patient_first_review'] input[name='end_time']").val();
		$("#timer_start").val(timer_paused);
		// $("#timer_end").val(timer_paused);
	}
};

var onHomeService = function (formObj, fields, response) {
	if (response.status == 200) {
		util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
		if ($("form[name='homeservice_form'] input[name$='therapist_come_home_care']:checked").val() == 0) {
			$("form[name='homeservice_form'] #success-alert").show();
			$("form[name='homeservice_form'] #eligibility-alert").hide();
			$("form[name='homeservice_form'] #schedule-alert").hide();
			$("form[name='homeservice_form'] #home_services_no_div").hide();
			var scrollPos = $(".main-content").offset().top;
			util.getToDoListData($("form[name='homeservice_form'] input[name='patient_id']").val(), $("form[name='homeservice_form'] input[name='moduleId']").val());
			$(window).scrollTop(scrollPos);
			setTimeout(function () {
				$("form[name='homeservice_form'] #success-alert").fadeOut();
				goToNextStep("review-patient-tab");

			}, 3000);
		} else {
			var atLeastOneIsChecked = false;
			$("form[name='homeservice_form'] input:checkbox").each(function () {
				if ($(this).is(':checked')) {
					atLeastOneIsChecked = true;
					$("form[name='homeservice_form'] #resons_error").hide();
					var home_services_end = $("form[name='homeservice_form'] input[name$='home_service_ends']:checked").val();
					if (home_services_end == 1) {
						$("form[name='homeservice_form'] #success-alert").hide();
						$("form[name='homeservice_form'] #eligibility-alert").hide();
						$("form[name='homeservice_form'] #home_services_no_div").show();
						$("form[name='homeservice_form'] #schedule-alert").hide();
						var scrollPos = $(".main-content").offset().top;
						util.getToDoListData($("form[name='homeservice_form'] input[name='patient_id']").val(), $("form[name='homeservice_form'] input[name='moduleId']").val());
						$(window).scrollTop(scrollPos);
						setTimeout(function () {
							$('#some_services_no_div').fadeOut();
							goToNextStep("patient-datap-tab");
						}, 3000);
					} else {
						$("form[name='homeservice_form'] #schedule-alert").show();
						$("form[name='homeservice_form'] #eligibility-alert").hide();
						$("form[name='homeservice_form'] #home_services_no_div").hide();
						$("form[name='homeservice_form'] #success-alert").hide();
						var scrollPos = $(".main-content").offset().top;
						util.getToDoListData($("form[name='homeservice_form'] input[name='patient_id']").val(), $("form[name='homeservice_form'] input[name='moduleId']").val());
						$(window).scrollTop(scrollPos);
						setTimeout(function () {
							$('#schedule-alert').fadeOut();
							goToNextStep("patient-datap-tab");
						}, 3000);
					}
					//return false;
				} else {
					$("form[name='homeservice_form'] #resons_error").text('Please choose at least one checkbox');
				}
			});
			var checked_home_services_end = $("form[name='homeservice_form'] input[name='home_service_ends']:checked").val();
			if (checked_home_services_end == 0) {
				$("#time-container").val(AppStopwatch.pauseClock);
			} else {
				$("#time-container").val(AppStopwatch.pauseClock);
			}
		}
		var timer_paused = $("form[name='homeservice_form'] input[name='end_time']").val();
		$("#timer_start").val(timer_paused);
		// $("#timer_end").val(timer_paused);
	}
};

var onResult = function (form, fields, response, error) {
	if (error) {
	}
	else {
		window.location.href = response.data.redirect;
	}
};

var goToNextStep = function (id) {
	setTimeout(function () {
		$('#' + id).click();
	}, 5000);
}

function editlabsformnew(labdate, patientid, labid, labexist) {
	// console.log("labdate:"+labdate+ "patientid:" +patientid+ "labid:" +labid+ "labdateexist:"+labexist);
	$("#editform").val("");
	$("#olddate").val("");
	$("#oldlab").val("");
	$("#labdateexist").val("");
	$("#labdate").val("");
	labcount = 0;
	$('#append_labs').html('');
	$('#append_labs_params_lab').html('');
	$("form[name='number_tracking_labs_form']")[0].reset();
	var labdate = labdate;
	var id = patientid;
	var labid = labid;
	var labdatexist = labexist;
	url = '/ccm/care-plan-development-populateLabs/' + id + '/' + labdate + '/' + labid + '/' + labdatexist;
	// console.log(url);
	populateForm(id, url);
}

function deleteLabs(labdate, patientid, labid, labexist) {
	if (confirm("Are you sure you want to Delete this lab")) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'POST',
			url: '/ccm/delete-lab',
			// data: {"_token": "{{ csrf_token() }}","id": id},
			data: { "labdate": labdate, "patientid": patientid, "labid": labid, "labdateexist": labexist },
			success: function (response) {
				renderLabsTable(patientid);
				var table = $('#callwrap-list');
				table.DataTable().ajax.reload();
				var year = (new Date).getFullYear();
				var month = (new Date).getMonth() + 1; //add +1 for current mnth
				var module_id = $("input[name='module_id']").val();
				util.getPatientPreviousMonthNotes(patient_id, module_id, month, year);
				util.getPatientCareplanNotes(patient_id, module_id);
				$("#msgsccess").show();
				var txt = '<div class="alert alert-success alert-block " style="margin-left: 1.1em;margin-right: 1.1em;"><button type="button" class="close" data-dismiss="alert">× </button><strong>Lab Deleted Successfully!</strong></div>';
				$("#msgsccess").html(txt);
				//goToNextStep("call_step_1_id");
				setTimeout(function () {
					$("#msgsccess").hide();
				}, 3000);
			}
		});
	} else {
		return false;
	}
}

//Medication Edit and delete
function deleteMedications(id, formsObj) {
	var formName = $(formsObj).closest(":has(form)").find('form').attr('name');
	var module_id = $("form[name='" + formName + "'] input[name='module_id']").val();
	var component_id = $("form[name='" + formName + "'] input[name='component_id']").val();
	var patient_id = $("form[name='" + formName + "'] input[name='patient_id']").val();
	var stage_id = $("form[name='" + formName + "'] input[name='stage_id']").val();
	var step_id = $("form[name='" + formName + "'] input[name='step_id']").val();
	var form_name = $("form[name='" + formName + "'] input[name='form_name']").val();
	var timer_start = $("#timer_start").val();
	var timer_paused = $("#time-container").text();
	var billable = $("form[name='" + formName + "'] input[name='billable']").val();

	if (confirm("Are you sure you want to delete this Medication?")) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'post',
			url: '/ccm/delete-medications_patient-by-id',
			// data: {"_token": "{{ csrf_token() }}",
			data: 'id=' + id +
				'&start_time=' + timer_start +
				'&end_time=' + timer_paused +
				'&module_id=' + module_id +
				'&component_id=' + component_id +
				'&patient_id=' + patient_id +
				'&stage_id=' + stage_id +
				'&step_id=' + step_id +
				'&form_name=' + form_name +
				'&billable=' + billable
			,
			success: function (response) {
				// alert(module);
				if (module == 'care-plan-development') {
					CompletedCheck();
					renderMedicationsTable();
				} else {
					renderMedicationsTableData();
				}
				util.updateTimer($("input[name='patient_id']").val(), billable, $("input[name='module_id']").val());
				$("#time-container").val(AppStopwatch.pauseClock);
				$("#timer_start").val(timer_paused);
				$("#timer_end").val(timer_paused);
				$("#time-container").val(AppStopwatch.startClock);
			},
		});
	} else {
		return false;
	}
}
function editMedications(id) {
	url = '/ccm/get-all-medications_patient-by-id/' + id + '/medicationspatient';
	populateForm(id, url);

}

//Allergies Edit and delete
function deleteAllergies(id, type, patient_id, formsObj) {
	var formName = $(formsObj).closest(":has(form)").find('form').attr('name');
	var module_id = $("form[name='" + formName + "'] input[name='module_id']").val();
	var component_id = $("form[name='" + formName + "'] input[name='component_id']").val();
	var patient_id = $("form[name='" + formName + "'] input[name='patient_id']").val();
	var stage_id = $("form[name='" + formName + "'] input[name='stage_id']").val();
	var step_id = $("form[name='" + formName + "'] input[name='step_id']").val();
	var form_name = $("form[name='" + formName + "'] input[name='form_name']").val();
	var timer_start = $("#timer_start").val();
	var timer_paused = $("#time-container").text();
	var billable = $("form[name='" + formName + "'] input[name='billable']").val();
	var result = confirm("Are you sure you want to delete this allergy?");
	if (result) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'post',
			url: '/ccm/delete-allergies',
			data: 'id=' + id +
				'&start_time=' + timer_start +
				'&end_time=' + timer_paused +
				'&module_id=' + module_id +
				'&component_id=' + component_id +
				'&patient_id=' + patient_id +
				'&stage_id=' + stage_id +
				'&step_id=' + step_id +
				'&form_name=' + form_name +
				'&billable=' + billable
			,
			success: function (response) {
				if (type == 'food') {
					util.refreshAllergyCountCheckbox(patient_id, type, formName);
					if (module == 'care-plan-development') {
						CompletedCheck();
						renderfoodTable();
					} else {
						renderFoodTableData();
					}
				} else if (type == 'drug') {
					util.refreshAllergyCountCheckbox(patient_id, type, formName);
					if (module == 'care-plan-development') {
						CompletedCheck();
						renderdrugTable();
					} else {
						renderdrugTableData();
					}
				} else if (type == 'enviromental') {
					util.refreshAllergyCountCheckbox(patient_id, type, formName);
					if (module == 'care-plan-development') {
						CompletedCheck();
						renderEnviromentalTable();
					} else {
						renderEnviromentalTableData();
					}
				} else if (type == 'insect') {
					util.refreshAllergyCountCheckbox(patient_id, type, formName);
					if (module == 'care-plan-development') {
						CompletedCheck();
						renderinsectTable();
					} else {
						renderinsectTableData();
					}
				} else if (type == 'latex') {
					util.refreshAllergyCountCheckbox(patient_id, type, formName);
					if (module == 'care-plan-development') {
						CompletedCheck();
						renderLatexTable();
					} else {
						renderLatexTableData();
					}
				} else if (type == 'petrelated') {
					util.refreshAllergyCountCheckbox(patient_id, type, formName);
					if (module == 'care-plan-development') {
						CompletedCheck();
						renderPetRelatedTable();
					} else {
						renderPetRelatedTableData();
					}
				} else if (type == 'other') {
					util.refreshAllergyCountCheckbox(patient_id, type, formName);
					if (module == 'care-plan-development') {
						CompletedCheck();
						renderAllergyOtherTable();
					} else {
						renderAllergyOtherTableData();
					}
				}
				util.updateTimer($("input[name='patient_id']").val(), billable, $("input[name='module_id']").val());
				$("#time-container").val(AppStopwatch.pauseClock);
				$("#timer_start").val(timer_paused);
				$("#timer_end").val(timer_paused);
				$("#time-container").val(AppStopwatch.startClock);
			},
		});
	} else {
		return false;
	}
}

function editAllergy(id, type, formsObj) {
	var formName = $(formsObj).closest(":has(form)").find('form').attr('name');
	var type = $("form[name='" + formName + "'] input[name='allergy_type']").val();
	var count = $('#' + type + 'count').val();

	if (count == 0) {
		$("form[name='" + formName + "'] input[name='type_of_reactions']").val("");
		$("form[name='" + formName + "'] input[name='type_of_reactions']").val("");
		$("form[name='" + formName + "'] input[name='severity']").val("");
		$("form[name='" + formName + "'] input[name='course_of_treatment']").val("");
		$("form[name='" + formName + "'] textarea[name='notes']").val("");

		$("form[name='" + formName + "'] input[name='specify']").attr("disabled", 'disabled');
		$("form[name='" + formName + "'] input[name='type_of_reactions']").prop("disabled", true);
		$("form[name='" + formName + "'] input[name='severity']").prop("disabled", true);
		$("form[name='" + formName + "'] input[name='course_of_treatment']").prop("disabled", true);
		$("form[name='" + formName + "'] textarea[name='notes']").prop("disabled", true);
	}
	else {
		// $(")[0].reset();  
		$("form[name='" + formName + "'] input[name='specify']").prop("disabled", false);
		$("form[name='" + formName + "'] input[name='type_of_reactions']").prop("disabled", false);
		$("form[name='" + formName + "'] input[name='severity']").prop("disabled", false);
		$("form[name='" + formName + "'] input[name='course_of_treatment']").prop("disabled", false);
		$("form[name='" + formName + "'] textarea[name='notes']").prop("disabled", false);
	}
	url = '/ccm/get-allergies-other/' + id + '/' + type + '/allergiespatient';
	populateForm(id, url);
}

//Services Edit and Delete
function editService(id) {
	url = '/ccm/get-services/' + id + '/servicespatient';
	populateForm(id, url);
}

function deleteServices(id, formsObj) {
	var formName = $(formsObj).closest(":has(form)").find('form').attr('name');
	var module_id = $("form[name='" + formName + "'] input[name='module_id']").val();
	var component_id = $("form[name='" + formName + "'] input[name='component_id']").val();
	var patient_id = $("form[name='" + formName + "'] input[name='patient_id']").val();
	var stage_id = $("form[name='" + formName + "'] input[name='stage_id']").val();
	var step_id = $("form[name='" + formName + "'] input[name='step_id']").val();
	var form_name = $("form[name='" + formName + "'] input[name='form_name']").val();
	var timer_start = $("#timer_start").val();
	var timer_paused = $("#time-container").text();
	var billable = $("form[name='" + formName + "'] input[name='billable']").val();

	var result = confirm("Are you sure you want to delete this Service ?");
	if (result) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'post',
			url: '/ccm/delete-services',
			data: 'id=' + id +
				'&start_time=' + timer_start +
				'&end_time=' + timer_paused +
				'&module_id=' + module_id +
				'&component_id=' + component_id +
				'&patient_id=' + patient_id +
				'&stage_id=' + stage_id +
				'&step_id=' + step_id +
				'&form_name=' + form_name +
				'&billable=' + billable
			,
			success: function (response) {
				if (module == 'care-plan-development') {
					CompletedCheck();
					renderDMEServicesTable();
					renderDialysiServicesTable();
					renderHomeHealthServicesTable();
					renderOtherHealthServicesTable();
					renderMedicalSuppliesServicesTable();
					renderSocialServicesTable();
					renderTherapyServicesTable();
				} else {
					renderDMEServicesTableData();
					renderDialysiServicesTableData();
					renderHomeHealthServicesTableData();
					renderOtherHealthServicesTableData();
					renderMedicalSuppliesServicesTableData();
					renderSocialServicesTableData();
					renderTherapyServicesTableData();
				}
				util.updateTimer($("input[name='patient_id']").val(), billable, $("input[name='module_id']").val());
				$("#time-container").val(AppStopwatch.pauseClock);
				$("#timer_start").val(timer_paused);
				$("#timer_end").val(timer_paused);
				$("#time-container").val(AppStopwatch.startClock);
			},
		});
	}
}




//edit and delete diagnosis

function editPatientDignosis(id, formsObj) {
	var formName = $(formsObj).closest(":has(form)").find('form').attr('name');
	// alert(formName);
	inc_symptoms = 0;
	review_inc_symptoms = 0;
	inc_goals = 0;
	review_inc_goals = 0;
	inc_tasks = 0;
	review_inc_tasks = 0;
	//	console.log("test click");
	$("form[name='" + formName + "']  #editdiagnoid").val(id);
	$("form[name='" + formName + "'] #enable_diagnosis_button").show();
	$("form[name='" + formName + "'] #disable_diagnosis_button").show();
	$("form[name='" + formName + "'] #hiddenenablebutton").val("");
	$("form[name='" + formName + "'] #diagnosis_code").val();
	$("form[name='" + formName + "'] #append_symptoms").html("");
	$("form[name='" + formName + "'] #append_goals").html("");
	$("form[name='" + formName + "'] #append_tasks").html("");
	$("form[name='" + formName + "'] #support").val("");
	$("form[name='" + formName + "'] #symptoms_0").val("");
	$("form[name='" + formName + "'] #goals_0").val("");
	$("form[name='" + formName + "'] #tasks_0").val("");
	$("form[name='" + formName + "'] #diagnosis_code_form textarea[name='comments']").val('');
	$("form[name='" + formName + "'] #diagnosis_code_form textarea[name='comments']").val('');
	$("form[name='" + formName + "'] #diagnosis_condition").val(id);
	var condition_name = $("form[name='" + formName + "'] #diagnosis_condition option:selected").text();
	$("form[name='" + formName + "'] input[name='condition']").val(condition_name);
	$("form[name='" + formName + "']  #append_symptoms_icons  ").hide();
	$("form[name='" + formName + "']  #append_goals_icons  ").hide();
	$("form[name='" + formName + "']  #append_tasks_icons  ").hide();
	var sPageURL = window.location.pathname;
	parts = sPageURL.split("/"),
	patientId = parts[parts.length - 1];
	patientDignosisFormPopulateURL = '/ccm/diagnosis-select/' + id + '/' + patientId;
	populateForm(patientId, patientDignosisFormPopulateURL);

	$("form[name='" + formName + "'] #save_care_plan_form").prop("disabled", false);
}

function enableDiagnosisbutton(formsObj) {
	// alert("calling from patientdetails frm cpdjs");  
	var formName = $(formsObj).closest(":has(form)").find('form').attr('name');
	// alert(formName);
	$("form[name='" + formName + "'] #hiddenenablebutton").val(1);
	$("form[name='" + formName + "'] #symptoms_0").prop("disabled", false);
	$("form[name='" + formName + "'] #goals_0").prop("disabled", false);
	$("form[name='" + formName + "'] #tasks_0").prop("disabled", false);

	$("form[name='" + formName + "']  .symptoms ").prop("disabled", false);
	$("form[name='" + formName + "']  .goals ").prop("disabled", false);
	$("form[name='" + formName + "']  .tasks  ").prop("disabled", false);

	$("form[name='" + formName + "']  #append_symptoms_icons").show();
	$("form[name='" + formName + "']  #append_goals_icons").show();
	$("form[name='" + formName + "']  #append_tasks_icons").show();
	// debugger;
	$("form[name='" + formName + "']  #append_goals").find('i').css('display', 'block');
	$("form[name='" + formName + "']  #append_symptoms").find('i').css('display', 'block');
	$("form[name='" + formName + "']  #append_tasks").find('i').css('display', 'block');



}

function disableDiagnosisbutton(formsObj){
	var formName = $(formsObj).closest(":has(form)").find('form').attr('name');
	// alert(formName);
	var id= $("form[name='" + formName + "']  #editdiagnoid").val();
	// $("form[name='" + formName + "']")[0].reset();
	$("form[name='" + formName + "'] #append_symptoms").empty();
	$("form[name='" + formName + "'] #append_goals").empty();
	$("form[name='" + formName + "'] #append_tasks").empty();  
	
	var sPageURL = window.location.pathname;
	parts = sPageURL.split("/"),
	patientId = parts[parts.length - 1];
	debugger;
	patientDignosisFormPopulateURL = '/ccm/diagnosis-select/' + id + '/' + patientId;
	// debugger;
	populateForm(patientId, patientDignosisFormPopulateURL);

}



function deletePatientDignosis(id, formsObj) {
	var formName = $(formsObj).closest(":has(form)").find('form').attr('name');
	var module_id = $("form[name='" + formName + "'] input[name='module_id']").val();
	var component_id = $("form[name='" + formName + "'] input[name='component_id']").val();
	var patient_id = $("form[name='" + formName + "'] input[name='patient_id']").val();
	var stage_id = $("form[name='" + formName + "'] input[name='stage_id']").val();
	var step_id = $("form[name='" + formName + "'] input[name='step_id']").val();
	var form_name = $("form[name='" + formName + "'] input[name='form_name']").val();
	var timer_start = $("#timer_start").val();
	var timer_paused = $("#time-container").text();
	var billable = $("form[name='" + formName + "'] input[name='billable']").val();
	var result = confirm("Are you sure you want to delete the Condition");
	if (result) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'post',
			url: '/ccm/delete-care-plan',
			data: 'id=' + id +
				'&start_time=' + timer_start +
				'&end_time=' + timer_paused +
				'&module_id=' + module_id +
				'&component_id=' + component_id +
				'&patient_id=' + patient_id +
				'&stage_id=' + stage_id +
				'&step_id=' + step_id +
				'&form_name=' + form_name +
				'&billable=' + billable
			,
			success: function (response) {
				if (module == 'care-plan-development') {
					CompletedCheck();
					renderDiagnosisTable();
					var patient_id = $("input[name='patient_id']").val();
					var module_id = $("input[name='module_id']").val();
					util.getPatientCareplanNotes(patient_id, module_id);
					util.getPatientStatus(patient_id, module_id);
				} else {
					renderDiagnosisTableData();
					var patient_id = $("input[name='patient_id']").val();
					var module_id = $("input[name='module_id']").val();
					util.getPatientCareplanNotes(patient_id, module_id);
					util.getPatientStatus(patient_id, module_id);
				}
				util.updateTimer($("input[name='patient_id']").val(), billable, $("input[name='module_id']").val());
				$("#time-container").val(AppStopwatch.pauseClock);
				$("#timer_start").val(timer_paused);
				$("#timer_end").val(timer_paused);
				$("#time-container").val(AppStopwatch.startClock);

			},
		});
	}
}

// personal(Sibling) Data edit and Delete
function editSiblingData(id, relation, formsObj) {
	var formName = $(formsObj).closest(":has(form)").find('form').attr('name');
	// alert(formName);
	$("form[name='" + formName + "']")[0].reset();
	$("form[name='" + formName + "']")[0].reset();
	$("form[name='" + formName + "']")[0].reset();
	$("form[name='" + formName + "']")[0].reset();
	url = '/ccm/get-all-family_patient-by-id/' + id + '/' + relation + '/familypatient';
	populateForm(id, url);
}

function deleteSiblingData(id, formsObj) {
	var formName = $(formsObj).closest(":has(form)").find('form').attr('name');
	var module_id = $("form[name='" + formName + "'] input[name='module_id']").val();
	var component_id = $("form[name='" + formName + "'] input[name='component_id']").val();
	var patient_id = $("form[name='" + formName + "'] input[name='patient_id']").val();
	var stage_id = $("form[name='" + formName + "'] input[name='stage_id']").val();
	var step_id = $("form[name='" + formName + "'] input[name='step_id']").val();
	var form_name = $("form[name='" + formName + "'] input[name='form_name']").val();
	var tab_name = $("form[name='" + formName + "'] input[name='tab_name']").val();
	var timer_start = $("#timer_start").val();
	var timer_paused = $("#time-container").text();
	var billable = $("form[name='" + formName + "'] input[name='billable']").val();
	if (confirm("Are you sure you want to delete this data?")) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'post',
			url: '/ccm/get-delete-family_patient-by-id/' + id + '/familypatient',
			data: 'id=' + id +
				'&start_time=' + timer_start +
				'&end_time=' + timer_paused +
				'&module_id=' + module_id +
				'&component_id=' + component_id +
				'&patient_id=' + patient_id +
				'&stage_id=' + stage_id +
				'&step_id=' + step_id +
				'&tab_name=' + tab_name +
				'&form_name=' + form_name +
				'&billable=' + billable
			,
			success: function (response) {
				util.updateTimer($("input[name='patient_id']").val(), billable, $("input[name='module_id']").val());
				$("#time-container").val(AppStopwatch.pauseClock);
				$("#timer_start").val(timer_paused);
				$("#timer_end").val(timer_paused);
				$("#time-container").val(AppStopwatch.startClock);

				if ($.trim(response) == 'live-with') {
					$("form[name='" + formName + "'] #relational_status_yes").prop("checked", false);
					$("form[name='" + formName + "'] #relational_status_no").prop("checked", false);
					$("form[name='" + formName + "'] #relational_status_no").prop("disabled", false);
					// renderLiveWithTable();
				}
				if ($.trim(response) == 'sibling') {
					$("form[name='" + formName + "'] #relational_status_yes").prop("checked", false);
					$("form[name='" + formName + "'] #relational_status_no").prop("checked", false);
					$("form[name='" + formName + "'] #relational_status_no").prop("disabled", false);
					// renderSiblingTable();
				}
				if ($.trim(response) == 'children') {
					$("form[name='" + formName + "'] #relational_status_yes").prop("checked", false);
					$("form[name='" + formName + "'] #relational_status_no").prop("checked", false);
					$("form[name='" + formName + "'] #relational_status_no").prop("disabled", false);
					// renderChildrenTable();
				}
				if ($.trim(response) == 'grandchildren') {
					$("form[name='" + formName + "'] #relational_status_yes").prop("checked", false);
					$("form[name='" + formName + "'] #relational_status_no").prop("checked", false);
					$("form[name='" + formName + "'] #relational_status_no").prop("disabled", false);
					// renderGrandchildrenTable();
				}
				renderSiblingTable();
				renderGrandchildrenTable();
				renderChildrenTable();
				renderLiveWithTable();
			},
		});
	} else {
		return false;
	}
}

//edit & delete Provider(specialist)
function editSpecialistProviderPatient(id) {
	url = '/ccm/get-all-proivder-specialist_patient-by-id/' + id + '/providerspecialistpatient';
	populateForm(id, url);
}

function deleteSpecialistProviderPatient(id, formsObj) {
	var formName = $(formsObj).closest(":has(form)").find('form').attr('name');
	var module_id = $("form[name='" + formName + "'] input[name='module_id']").val();
	var component_id = $("form[name='" + formName + "'] input[name='component_id']").val();
	var patient_id = $("form[name='" + formName + "'] input[name='patient_id']").val();
	var stage_id = $("form[name='" + formName + "'] input[name='stage_id']").val();
	var step_id = $("form[name='" + formName + "'] input[name='step_id']").val();
	var form_name = $("form[name='" + formName + "'] input[name='form_name']").val();
	var timer_start = $("#timer_start").val();
	var timer_paused = $("#time-container").text();
	var billable = $("form[name='" + formName + "'] input[name='billable']").val();
	if (confirm("Are you sure you want to delete this Specialist Provider Data?")) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'post',
			url: '/ccm/get-delete-proivder-specialist_patient-by-id/' + id + '/providerspecialistpatient',
			data: 'id=' + id +
				'&start_time=' + timer_start +
				'&end_time=' + timer_paused +
				'&module_id=' + module_id +
				'&component_id=' + component_id +
				'&patient_id=' + patient_id +
				'&stage_id=' + stage_id +
				'&step_id=' + step_id +
				'&form_name=' + form_name +
				'&billable=' + billable
			,
			success: function (response) {
				if (module == 'care-plan-development') {
					CompletedCheck();
					renderOtherProviderSpecialistTable();
				} else {
					renderOtherProviderSpecialistTableData();
				}
				util.updateTimer($("input[name='patient_id']").val(), billable, $("input[name='module_id']").val());
				$("#time-container").val(AppStopwatch.pauseClock);
				$("#timer_start").val(timer_paused);
				$("#timer_end").val(timer_paused);
				$("#time-container").val(AppStopwatch.startClock);
			},
		});
	} else {
		return false;
	}
}

//Hobbies data delete and edit
function editHobbiesData(id, formsObj) {
	var formName = $(formsObj).closest(":has(form)").find('form').attr('name');
	$("form[name='" + formName + "']")[0].reset();
	url = '/ccm/get-all-hobbie_patient-by-id/' + id + '/hobbiepatient';
	populateForm(id, url);
}

function deleteHobbiesData(id, formsObj) {
	var formName = $(formsObj).closest(":has(form)").find('form').attr('name');
	var module_id = $("form[name='" + formName + "'] input[name='module_id']").val();
	var component_id = $("form[name='" + formName + "'] input[name='component_id']").val();
	var patient_id = $("form[name='" + formName + "'] input[name='patient_id']").val();
	var stage_id = $("form[name='" + formName + "'] input[name='stage_id']").val();
	var step_id = $("form[name='" + formName + "'] input[name='step_id']").val();
	var form_name = $("form[name='" + formName + "'] input[name='form_name']").val();
	var hobbies_status = $("form[name='" + formName + "'] input[name='hobbies_status']").val();
	var timer_start = $("#timer_start").val();
	var timer_paused = $("#time-container").text();
	var billable = $("form[name='" + formName + "'] input[name='billable']").val();
	if (confirm("Are you sure you want to delete this data?")) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'post',
			url: '/ccm/get-delete-hobbie_patient-by-id/' + id + '/hobbiepatient',
			data: 'id=' + id +
				'&start_time=' + timer_start +
				'&end_time=' + timer_paused +
				'&module_id=' + module_id +
				'&component_id=' + component_id +
				'&patient_id=' + patient_id +
				'&stage_id=' + stage_id +
				'&step_id=' + step_id +
				'&hobbies_status=' + hobbies_status +
				'&form_name=' + form_name +
				'&billable=' + billable
			,
			success: function (response) {
				util.updateTimer($("input[name='patient_id']").val(), billable, $("input[name='module_id']").val());
				$("#time-container").val(AppStopwatch.pauseClock);
				$("#timer_start").val(timer_paused);
				$("#timer_end").val(timer_paused);
				$("#time-container").val(AppStopwatch.startClock);

				if ($.trim(response) == 'nothing') {
					$("form[name='" + formName + "'] #hobbies_status_yes").prop("checked", false);
					$("form[name='" + formName + "'] #hobbies_status_no").prop("checked", false);
					$("form[name='" + formName + "'] #hobbies_status_no").prop("disabled", false);
					renderHobbiesTable();
				}
				renderHobbiesTable();
			},
		});
	} else {
		return false;
	}
}

//pet data delete and edit
function editPetData(id, formsObj) {
	var formName = $(formsObj).closest(":has(form)").find('form').attr('name');
	$("form[name='" + formName + "']")[0].reset();
	url = '/ccm/get-all-pet_patient-by-id/' + id + '/petpatient';
	populateForm(id, url);
}

function deletePetData(id, formsObj) {
	var formName = $(formsObj).closest(":has(form)").find('form').attr('name');
	var module_id = $("form[name='" + formName + "'] input[name='module_id']").val();
	var component_id = $("form[name='" + formName + "'] input[name='component_id']").val();
	var patient_id = $("form[name='" + formName + "'] input[name='patient_id']").val();
	var stage_id = $("form[name='" + formName + "'] input[name='stage_id']").val();
	var step_id = $("form[name='" + formName + "'] input[name='step_id']").val();
	var form_name = $("form[name='" + formName + "'] input[name='form_name']").val();
	var pet_status = $("form[name='" + formName + "'] input[name='pet_status']").val();
	var timer_start = $("#timer_start").val();
	var timer_paused = $("#time-container").text();
	var billable = $("form[name='" + formName + "'] input[name='billable']").val();
	if (confirm("Are you sure you want to delete this data?")) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'post',
			url: '/ccm/get-delete-pet_patient-by-id/' + id + '/petpatient',
			data: 'id=' + id +
				'&start_time=' + timer_start +
				'&end_time=' + timer_paused +
				'&module_id=' + module_id +
				'&component_id=' + component_id +
				'&patient_id=' + patient_id +
				'&stage_id=' + stage_id +
				'&step_id=' + step_id +
				'&pet_status=' + pet_status +
				'&form_name=' + form_name +
				'&billable=' + billable
			,
			success: function (response) {
				util.updateTimer($("input[name='patient_id']").val(), billable, $("input[name='module_id']").val());
				$("#time-container").val(AppStopwatch.pauseClock);
				$("#timer_start").val(timer_paused);
				$("#timer_end").val(timer_paused);
				$("#time-container").val(AppStopwatch.startClock);

				if ($.trim(response) == 'nothing') {
					$("form[name='" + formName + "'] #pet_status_yes").prop("checked", false);
					$("form[name='" + formName + "'] #pet_status_no").prop("checked", false);
					$("form[name='" + formName + "'] #pet_status_no").prop("disabled", false);
					renderPetTable();
				}
				renderPetTable();
			},
		});
	} else {
		return false;
	}
}

//Travel Data edit and delete

function editTravelData(id, formsObj) {
	var formName = $(formsObj).closest(":has(form)").find('form').attr('name');
	$("form[name='" + formName + "']")[0].reset();
	url = '/ccm/get-all-travel_patient-by-id/' + id + '/travelpatient';
	populateForm(id, url);
}

function deleteTraveltData(id, formsObj) {
	var formName = $(formsObj).closest(":has(form)").find('form').attr('name');
	var module_id = $("form[name='" + formName + "'] input[name='module_id']").val();
	var component_id = $("form[name='" + formName + "'] input[name='component_id']").val();
	var patient_id = $("form[name='" + formName + "'] input[name='patient_id']").val();
	var stage_id = $("form[name='" + formName + "'] input[name='stage_id']").val();
	var step_id = $("form[name='" + formName + "'] input[name='step_id']").val();
	var form_name = $("form[name='" + formName + "'] input[name='form_name']").val();
	var travel_status = $("form[name='" + formName + "'] input[name='travel_status']").val();
	var timer_start = $("#timer_start").val();
	var timer_paused = $("#time-container").text();
	var billable = $("form[name='" + formName + "'] input[name='billable']").val();
	if (confirm("Are you sure you want to delete this data?")) {
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});
		$.ajax({
			type: 'post',
			url: '/ccm/get-delete-travel_patient-by-id/' + id + '/travelpatient',
			data: 'id=' + id +
				'&start_time=' + timer_start +
				'&end_time=' + timer_paused +
				'&module_id=' + module_id +
				'&component_id=' + component_id +
				'&patient_id=' + patient_id +
				'&stage_id=' + stage_id +
				'&step_id=' + step_id +
				'&travel_status=' + travel_status +
				'&form_name=' + form_name +
				'&billable=' + billable
			,
			success: function (response) {
				util.updateTimer($("input[name='patient_id']").val(), billable, $("input[name='module_id']").val());
				$("#time-container").val(AppStopwatch.pauseClock);
				$("#timer_start").val(timer_paused);
				$("#timer_end").val(timer_paused);
				$("#time-container").val(AppStopwatch.startClock);

				if ($.trim(response) == 'nothing') {
					$("form[name=" + formName + "] #hobbies_status_yes").prop("checked", false);
					$("form[name=" + formName + "] #hobbies_status_no").prop("checked", false);
					$("form[name=" + formName + "] #hobbies_status_no").prop("disabled", false);
					renderTravelTable();
				}
				renderTravelTable();
			},
		});
	} else {
		return false;
	}
}

$("#review_live_relationship_0").on("change", function () {
	$relationship = $('#review_live_relationship_0').val();
	if ($relationship == '0') {
		$("#relatnname_0").css('display', 'block');
	}
	else {
		$("#relatnname_0").css('display', 'none');
	}
});


var CompletedCheck = function () {
	var sPageURL = window.location.pathname;
	parts = sPageURL.split("/"),
		id = parts[parts.length - 1];
	var patient_id = id;

	var familyspouse_step_id = $('form[name="family_spouse_form"] input[name=step_id]').val();
	var familyemergency_contact_step_id = $('form[name="family_emergency_contact_form"] input[name=step_id]').val();
	var familycare_giver_step_id = $('form[name="family_care_giver_form"] input[name=step_id]').val();

	var family_array = [familyspouse_step_id, familyemergency_contact_step_id, familycare_giver_step_id];

	var provider_pcp = $('form[name="provider_pcp_form"] input[name=step_id]').val();
	var provider_specialists = $('form[name="provider_specialists_form"] input[name=step_id]').val();
	var provider_vision = $('form[name="provider_vision_form"] input[name=step_id]').val();
	var provider_dentist = $('form[name="provider_dentist_form"] input[name=step_id]').val();

	var provider_array = [provider_pcp, provider_specialists, provider_vision, provider_dentist];

	var diagnosis_step_id = $('form[name="diagnosis_code_form"] input[name=step_id]').val();

	var diagnosis_array = [diagnosis_step_id];

	var allergy_food = $("form[name='allergy_food_form'] input[name=step_id]").val();

	var allergy_drug = $("form[name='allergy_drug_form'] input[name=step_id]").val();
	var allergy_enviromental = $("form[name='allergy_enviromental_form'] input[name=step_id]").val();
	var allergy_insect = $("form[name='allergy_insect_form'] input[name=step_id]").val();
	var allergy_latex = $("form[name='allergy_latex_form'] input[name=step_id]").val();
	var allergy_pet_related = $("form[name='allergy_pet_related_form'] input[name=step_id]").val();
	var allergy_other_allergy = $("form[name='allergy_other_allergy_form'] input[name=step_id]").val();
	var allergy_array = [allergy_food, allergy_drug, allergy_enviromental, allergy_insect, allergy_latex, allergy_pet_related, allergy_other_allergy];

	var medication_step_id = $("form[name='medications_form'] input[name=step_id]").val();
	var medication_array = [medication_step_id];

	var service_dme = $("form[name='service_dme_form'] input[name=step_id]").val();
	var service_home_health = $("form[name='service_home_health_form'] input[name=step_id]").val();
	var service_dialysis = $("form[name='service_dialysis_form'] input[name=step_id]").val();
	var service_medical_supplies = $("form[name='service_medical_supplies_form'] input[name=step_id]").val();
	var service_other_health = $("form[name='service_other_health_form'] input[name=step_id]").val();
	var service_social = $("form[name='service_social_form'] input[name=step_id]").val();
	var service_therapy = $("form[name='service_therapy_form'] input[name=step_id]").val();
	var services_array = [service_dme, service_home_health, service_dialysis, service_medical_supplies, service_other_health, service_social, service_therapy];

	var number_tracking_vitals = $("form[name='number_tracking_vitals_form'] input[name=step_id]").val();
	var number_tracking_labs = $("form[name='number_tracking_labs_form'] input[name=step_id]").val();
	var number_tracking_imaging = $("form[name='number_tracking_imaging_form'] input[name=step_id]").val();
	var number_tracking_healthdata = $("form[name='number_tracking_healthdata_form'] input[name=step_id]").val();
	var number_tracking_array = [number_tracking_vitals, number_tracking_labs, number_tracking_imaging, number_tracking_healthdata];

	// var finaldata = [{'0': family_array},{'1':provider_array},{'2':diagnosis_array},{'3':allergy_array},{'4':medication_array},{'5':services_array},{'6':number_tracking_array}];
	var finaldata = [family_array, provider_array, diagnosis_array, allergy_array, medication_array, services_array, number_tracking_array];



	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajax({
		type: 'POST',
		type: 'post',
		url: '/ccm/CheckCompletedCheckbox',
		data: { 'finaldata': finaldata, 'patient_id': patient_id },
		success: function (response) {
			//   console.log(response);
			for (var i = 0; i < response.length; i++) {
				if ($.trim(response[i]) == '0') {
					$('#familycheck').prop('checked', true);
				}
				else if ($.trim(response[i]) == '1') {
					$('#providercheck').prop('checked', true);
				}
				else if ($.trim(response[i]) == '2') {
					$('#diagnosischeck').prop('checked', true);
				}
				else if ($.trim(response[i]) == '3') {
					$('#allergycheck').prop('checked', true);
				}
				else if ($.trim(response[i]) == '4') {
					$('#medicationscheck').prop('checked', true);
				}
				else if ($.trim(response[i]) == '5') {
					$('#healthcarecheck').prop('checked', true);
				}
				else if ($.trim(response[i]) == '6') {
					$('#numbers_trackingcheck').prop('checked', true);
				}
			}
		},
	});
}

var populateformdata = function () {
	var sPageURL = window.location.pathname;
	parts = sPageURL.split("/"),
		id = parts[parts.length - 1];
	var patientId = id;
	var formpopulateurl = URL_POPULATE + "/" + patientId;
	populateForm(id, formpopulateurl);
}

function saveFinalize() {
	var id = $("input[name='patient_id']").val();
	var billabel = $("input[name='bill_practice']").val();
	if ($("input[name='finalize_cpd']").is(':checked')) {
		var finalize = 1;
	} else {
		var finalize = 0;
	}
	$.ajax({
		type: 'post',
		url: '/ccm/finalize-cpd',
		data: { "id": id, "finalize": finalize, "billabel": billabel },
		success: function (response) {
			//alert(response);
			$("input[name='billable']").val(response.trim());
		},
	});
}

var renderOtherProviderSpecialistTableData = function () {
	var url = baseURL + "ccm/care-plan-development-provider-specilist-list/" + patient_id;
	var table = util.renderDataTable('Specialists-list', url, specilistColumns, baseURL);
	return table;
};

var renderOtherProviderSpecialistTable = function () {
	var table = renderOtherProviderSpecialistTableData();
	// util.copyDataFromOneDataTableToAnother(table, 'Specialists-list', 'specialists-review-list', baseURL);
	var url = baseURL + "ccm/care-plan-development-provider-specilist-list/" + patient_id;
	var table = util.renderDataTable('specialists-review-list', url, specilistColumns, baseURL);
	//  return table;
};


/*var renderOtherProviderSpecialistTable = function () {
		// var url = baseURL+"ccm/care-plan-development-provider-specilist-list/"+patient_id;
		// var table = util.renderDataTable('Specialists-list', url, specilistColumns, baseURL);
		// util.copyDataFromOneDataTableToAnother(table, 'Specialists-list', 'Specialists-Review-list', baseURL);
		var url = baseURL+"ccm/care-plan-development-provider-specilist-list/"+patient_id;
		  var table = util.renderDataTable('Specialists-list', url, specilistColumns, baseURL, 1, 'specialists-review-list');
		//util.copyDataFromOneDataTableToAnother(table, 'Specialists-list', 'Specialists-Review-list', baseURL);
}
*/
var renderLiveWithTable = function () {
	var columns = [
		{ data: 'DT_RowIndex', name: 'DT_RowIndex' },
		{
			data: null, mRender: function (data, type, full, meta) {
				if (data != '' && data != 'NULL' && data != undefined) {
					fname = full['fname'];
					if (full['lname'] == null && fname == null) {
						return '';
					}
					else {
						return fname + ' ' + full['lname'];
					}
				} else {
					return '';
				}
			}, orderable: false
		},
		{ data: 'relationship', name: 'relationship' },
		{
			data: 'users',
			mRender: function (data, type, full, meta) {
				if (data != '' && data != 'NULL' && data != undefined) {
					l_name = data['l_name'];
					if (data['l_name'] == null) {
						l_name = '';
					}
					return data['f_name'] + ' ' + l_name;
				} else {
					return '';
				}
			}, orderable: false
		},
		{ data: 'updated_at', name: 'updated_at' },
		{
			data: null, mRender: function (data, type, full, meta) {
				if (data != '' && data != 'NULL' && data != undefined) {
					if (full['review'] == '1') {
						return "Yes";
					} else {
						return "No";
					}
					if (full['review'] == null) {
						return "No";
					}
				}
			},
			orderable: true
		},
		{ data: 'action', name: 'action', orderable: false, searchable: false }
	];

	var table = util.renderDataTable('live-with-list', baseURL + "ccm/care-plan-development-siblinglist/" + patient_id + "/live-with", columns, baseURL);
}

var renderPetTable = function () {
	var columns = [
		{ data: 'DT_RowIndex', name: 'DT_RowIndex' },
		{ data: 'pet_name', name: 'pet_name' },
		{ data: 'pet_type', name: 'pet_type' },
		{
			data: 'users',
			mRender: function (data, type, full, meta) {
				if (data != '' && data != 'NULL' && data != undefined) {
					l_name = data['l_name'];
					if (data['l_name'] == null) {
						l_name = '';
					}
					return data['f_name'] + ' ' + l_name;
				} else {
					return '';
				}
			}, orderable: false
		},
		{ data: 'updated_at', name: 'updated_at' },

		{ data: 'action', name: 'action', orderable: false, searchable: false }
	];
	var table = util.renderDataTable('pet-list', baseURL + "ccm/care-plan-development-review-petlist/" + patient_id, columns, baseURL);
}

var renderHobbiesTable = function () {
	var columns = [
		{ data: 'DT_RowIndex', name: 'DT_RowIndex' },
		{ data: 'hobbies_name', name: 'hobbies_name' },
		{ data: 'location', name: 'location' },
		{ data: 'frequency', name: 'frequency' },
		{ data: 'with_whom', name: 'with_whom' },
		{
			data: 'users',
			mRender: function (data, type, full, meta) {
				if (data != '' && data != 'NULL' && data != undefined) {
					l_name = data['l_name'];
					if (data['l_name'] == null) {
						l_name = '';
					}
					return data['f_name'] + ' ' + l_name;
				} else {
					return '';
				}
			}, orderable: false
		},
		{ data: 'updated_at', name: 'updated_at' },

		{ data: 'action', name: 'action', orderable: false, searchable: false }
	];
	var table = util.renderDataTable('hobbies-list', baseURL + "ccm/care-plan-development-review-hobbieslist/" + patient_id, columns, baseURL);
};

var renderTravelTable = function () {
	var columns = [
		{ data: 'DT_RowIndex', name: 'DT_RowIndex' },
		{ data: 'location', name: 'location' },
		{ data: 'travel_type', name: 'travel_type' },
		{ data: 'frequency', name: 'frequency' },
		{ data: 'with_whom', name: 'with_whom' },
		{ data: 'upcoming_tips', name: 'upcoming_tips' },
		{
			data: 'users',
			mRender: function (data, type, full, meta) {
				if (data != '' && data != 'NULL' && data != undefined) {
					l_name = data['l_name'];
					if (data['l_name'] == null) {
						l_name = '';
					}
					return data['f_name'] + ' ' + l_name;
				} else {
					return '';
				}
			}, orderable: false
		},
		{ data: 'updated_at', name: 'updated_at' },
		{ data: 'action', name: 'action', orderable: false, searchable: false }
	];
	var table = util.renderDataTable('travel-list', baseURL + "ccm/care-plan-development-review-travellist/" + patient_id, columns, baseURL);
};

var renderSiblingTable = function () {
	var table = util.renderDataTable('sibling-list', baseURL + "ccm/care-plan-development-siblinglist/" + patient_id + "/sibling", familyColumns, baseURL);
}

var renderGrandchildrenTable = function () {
	var table = util.renderDataTable('grandchildren-list', baseURL + "ccm/care-plan-development-siblinglist/" + patient_id + "/grandchildren", familyColumns, baseURL);
};

var renderChildrenTable = function () {
	var table = util.renderDataTable('children-list', baseURL + "ccm/care-plan-development-siblinglist/" + patient_id + "/children", familyColumns, baseURL);
};

var renderLabsTable = function () {
	util.renderDataTable('labs-list', baseURL + "ccm/care-plan-development-labs-labslist/" + patient_id, labColumns, baseURL);
}

/*var renderImagingTable = function () {
	//alert("check");
	//console.log(baseURL + "ccm/care-plan-development-imaging-imaginglist/" + patient_id);
	util.renderDataTable('imaging-list', baseURL + "ccm/care-plan-development-imaging-imaginglist/" + patient_id, imagingColumns, baseURL);
}*/
var renderVitalTable = function () {
	util.renderDataTable('vital-list', baseURL + "ccm/care-plan-development-vital-vitallist/" + patient_id, vitalColumns, baseURL);
}


var renderMedicationsTableData = function () {
	var url = baseURL + "ccm/care-plan-development-medications-medicationslist/" + patient_id;
	var table1 = util.renderDataTable('Medication-list', url, medicationColumns, baseURL);
	return table1;
}

var renderMedicationsTable = function () {
	var table1 = renderMedicationsTableData();
	util.copyDataFromOneDataTableToAnother(table1, 'Medication-list', 'MedicationReview-list', baseURL);
}

//patient-data, review-patient-data ALLERGIES start here
var refreshdrugcountcheckbox = function () {
	var drugscnt = $("#drugcountsdiv").text();
	if (drugscnt == 0) {
		$("#Nodrug").removeAttr('disabled', 'disabled');
	} else {
		$("#Nodrug").attr('disabled', 'disabled');
	}
}

var renderdrugTableData = function () {
	var allergytype = $('form[name="allergy_drug_form"] input[name="allergy_type"]').val();
	var url = baseURL + "ccm/allergies/" + patient_id + "/" + allergytype;
	var table = util.renderDataTable('drug-list', url, allergiesColumns, baseURL);
	return table;
};

var renderdrugTable = function () {
	var table = renderdrugTableData();
	util.copyDataFromOneDataTableToAnother(table, 'drug-list', 'review-drug-list', baseURL);
};

var refreshfoodcountcheckbox = function () {
	var foodcnt = $("#foodcountdiv").text();
	if (foodcnt == 0) {
		$("#Nofood").removeAttr('disabled', 'disabled');
	} else {
		$("#Nofood").attr('disabled', 'disabled');
	}
}

var renderFoodTableData = function () {
	var allergytype = $('form[name="allergy_food_form"] input[name="allergy_type"]').val();
	var url = baseURL + "ccm/allergies/" + patient_id + "/" + allergytype;
	var table = util.renderDataTable('food-list', url, allergiesColumns, baseURL);
	return table;
};

var renderFoodTable = function () {
	var table = renderFoodTableData();
	util.copyDataFromOneDataTableToAnother(table, 'food-list', 'review-food-list', baseURL);
};

var refreshenivornmentcountcheckbox = function () {
	var envcnt = $("#environmentcountdiv").text();
	if (envcnt == 0) {
		$("#Noenv").removeAttr('disabled', 'disabled');
	} else {
		$("#Noenv").attr('disabled', 'disabled');
	}
}

var renderEnviromentalTableData = function () {
	var allergytype = $('form[name="allergy_enviromental_form"] input[name="allergy_type"]').val();
	var url = baseURL + "ccm/allergies/" + patient_id + "/" + allergytype;
	var table = util.renderDataTable('enviromental-list', url, allergiesColumns, baseURL);
	return table;
};

var renderEnviromentalTable = function () {
	var table = renderEnviromentalTableData();
	util.copyDataFromOneDataTableToAnother(table, 'enviromental-list', 'review-enviromental-list', baseURL);
};

var refreshlatexcountcheckbox = function () {
	var latexcnt = $("#latexcountdiv").text();
	if (latexcnt == 0) {
		$("#Nolatex").removeAttr('disabled', 'disabled');
	} else {
		$("#Nolatex").attr('disabled', 'disabled');
	}
}

var renderLatexTableData = function () {
	var allergytype = $('form[name="allergy_latex_form"] input[name="allergy_type"]').val();
	var url = baseURL + "ccm/allergies/" + patient_id + "/" + allergytype;
	var table = util.renderDataTable('latex-list', url, allergiesColumns, baseURL);
	return table;
};

var renderLatexTable = function () {
	var table = renderLatexTableData();
	util.copyDataFromOneDataTableToAnother(table, 'latex-list', 'review-latex-list', baseURL);
};

var refreshpetcountcheckbox = function () {
	var petcnt = $("#petcountdiv").text();
	if (petcnt == 0) {
		$("#Nopet").removeAttr('disabled', 'disabled');
	} else {
		$("#Nopet").attr('disabled', 'disabled');
	}
}

var renderPetRelatedTableData = function () {
	var allergytype = $('form[name="allergy_pet_related_form"] input[name="allergy_type"]').val();
	var url = baseURL + "ccm/allergies/" + patient_id + "/" + allergytype;
	var table = util.renderDataTable('pet-related-list', url, allergiesColumns, baseURL);
	return table;
};

var renderPetRelatedTable = function () {
	var table = renderPetRelatedTableData();
	util.copyDataFromOneDataTableToAnother(table, 'pet-related-list', 'review-pet-related-list', baseURL);
};

var refreshinsectcountcheckbox = function () {
	var insectcnt = $("#insectcountdiv").text();
	if (insectcnt == 0) {
		$("#Noinsect").removeAttr('disabled', 'disabled');
	} else {
		$("#Noinsect").attr('disabled', 'disabled');
	}
}

var renderinsectTableData = function () {
	var allergytype = $('form[name="allergy_insect_form"] input[name="allergy_type"]').val();
	var url = baseURL + "ccm/allergies/" + patient_id + "/" + allergytype;
	var table = util.renderDataTable('insect-list', url, allergiesColumns, baseURL);
	return table;
};

var renderinsectTable = function () {
	var table = renderinsectTableData();
	util.copyDataFromOneDataTableToAnother(table, 'insect-list', 'review-insect-list', baseURL);
};

var refreshothercountcheckbox = function () {
	var othercnt = $("#othercountsdiv").text();
	if (othercnt == 0) {
		$("#Noother").removeAttr('disabled', 'disabled');
	} else {
		$("#Noother").attr('disabled', 'disabled');
	}
}

var renderAllergyOtherTableData = function () {
	var allergytype = $('form[name="allergy_other_allergy_form"] input[name="allergy_type"]').val();
	var url = baseURL + "ccm/allergies/" + patient_id + "/" + allergytype;
	var table = util.renderDataTable('Other-list', url, allergiesColumns, baseURL);
	return table;
};

var renderAllergyOtherTable = function () {
	var table = renderAllergyOtherTableData();
	util.copyDataFromOneDataTableToAnother(table, 'Other-list', 'Review-Other-list', baseURL);
};
//patient-data, review-patient-data ALLERGIES ends here

//patient-data, review-patient-data SERVICES starts here
var renderDMEServicesTableData = function () {
	var servicetype = $('form[name="service_dme_form"] .hid').val();
	var url = baseURL + "ccm/care-plan-development-services-list/" + patient_id + '/' + servicetype;
	var table1 = util.renderDataTable('dme-services-list', url, dmeOtherMedicalSuppliesServiceColumns, baseURL);
	return table1;
}

var renderDMEServicesTable = function () {
	var table = renderDMEServicesTableData();
	util.copyDataFromOneDataTableToAnother(table, 'dme-services-list', 'dme-review-services-list', baseURL);
}

var renderHomeHealthServicesTableData = function () {
	var servicetype = $('form[name="service_home_health_form"] .hid').val();
	var url = baseURL + "ccm/care-plan-development-services-list/" + patient_id + '/' + servicetype;
	var table1 = util.renderDataTable('Home-Health-Services-list', url, homeDialysisTherapySosialServicesColumns, baseURL);
	return table1;
}

var renderHomeHealthServicesTable = function () {
	var table = renderHomeHealthServicesTableData();
	util.copyDataFromOneDataTableToAnother(table, 'Home-Health-Services-list', 'home-health-review-services-list', baseURL);
}

var renderDialysiServicesTableData = function () {
	var servicetype = $('form[name="service_dialysis_form"] .hid').val();
	var url = baseURL + "ccm/care-plan-development-services-list/" + patient_id + '/' + servicetype;
	var table1 = util.renderDataTable('dialysis-services-list', url, homeDialysisTherapySosialServicesColumns, baseURL);
	return table1;
}

var renderDialysiServicesTable = function () {
	var table = renderDialysiServicesTableData();
	util.copyDataFromOneDataTableToAnother(table, 'dialysis-services-list', 'dialysis-review-services-list', baseURL);
}

var renderTherapyServicesTableData = function () {
	var servicetype = $('form[name="service_therapy_form"] .hid').val();
	var url = baseURL + "ccm/care-plan-development-services-list/" + patient_id + '/' + servicetype;
	var table1 = util.renderDataTable('therapy-services-list', url, homeDialysisTherapySosialServicesColumns, baseURL);
	return table1;
}

var renderTherapyServicesTable = function () {
	var table = renderTherapyServicesTableData();
	util.copyDataFromOneDataTableToAnother(table, 'therapy-services-list', 'therapy-review-services-list', baseURL);
}

var renderSocialServicesTableData = function () {
	var servicetype = $('form[name="service_social_form"] .hid').val();
	var url = baseURL + "ccm/care-plan-development-services-list/" + patient_id + '/' + servicetype;
	var table1 = util.renderDataTable('social-services-list', url, homeDialysisTherapySosialServicesColumns, baseURL);
	return table1;
}

var renderSocialServicesTable = function () {
	var table = renderSocialServicesTableData();
	util.copyDataFromOneDataTableToAnother(table, 'social-services-list', 'social-review-services-list', baseURL);
}

var renderMedicalSuppliesServicesTableData = function () {
	var servicetype = $('form[name="service_medical_supplies_form"] .hid').val();
	var url = baseURL + "ccm/care-plan-development-services-list/" + patient_id + '/' + servicetype;
	var table1 = util.renderDataTable('medical-supplies-services-list', url, dmeOtherMedicalSuppliesServiceColumns, baseURL);
	return table1;
}

var renderMedicalSuppliesServicesTable = function () {
	var table = renderMedicalSuppliesServicesTableData();
	util.copyDataFromOneDataTableToAnother(table, 'medical-supplies-services-list', 'medical-supplies-review-services-list', baseURL);
}

var renderOtherHealthServicesTableData = function () {
	var servicetype = $('form[name="service_other_health_form"] .hid').val();
	var url = baseURL + "ccm/care-plan-development-services-list/" + patient_id + '/' + servicetype;
	var table1 = util.renderDataTable('other-services-list', url, dmeOtherMedicalSuppliesServiceColumns, baseURL);
	return table1;
}

var renderOtherHealthServicesTable = function () {
	var table = renderOtherHealthServicesTableData();
	util.copyDataFromOneDataTableToAnother(table, 'other-services-list', 'other-health-review-services-list', baseURL);
}
//patient-data, review-patient-data SERVICES ends here

var getLabParamsOnLabChange = function (selectObject) {
	$("#editform").val("");
	$("#olddate").val("");
	$("#oldlab").val("");
	$("#labdateexist").val("");
	$("#labdate").val("");
	var lab = selectObject.value; //$( this ).val();
	$.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	});
	$.ajax({
		type: 'post',
		url: '/ccm/lab-param',
		data: 'lab=' + lab,
		success: function (response) {
			$("#append_labs_params_lab").html(response);
			$("#labdate").attr('name', 'labdate[' + lab + '][]');
		},
	});
}

var selectMedicationOther = function (selectObject) {
	//var med_id = $("#medication_med_id").val();
	var med_id = selectObject;
	//alert(selectObject);
	if (med_id == 'other') {
		$(".med_id").removeClass("col-md-6").addClass("col-md-4");
		$(".description").removeClass("col-md-6").addClass("col-md-4");
		$("#med_name").show();
		$("#medication_description").val("");
		$("#medication_purpose").val("");
		$("#medication_strength").val("");
		$("#medication_dosage").val("");
		$("#medication_route").val("");
		$("#medication_frequency").val("");
		$("#duration").val("");
		$("#medication_drug_reaction").val("");
		$("#medication_pharmacogenetic_test").val("");
	} else {
		$(".med_id").removeClass("col-md-4").addClass("col-md-6");
		$(".description").removeClass("col-md-4").addClass("col-md-6");
		$("#med_name").hide();
		$("#medication_description").val("");
		$("#medication_purpose").val("");
		$("#medication_strength").val("");
		$("#medication_dosage").val("");
		$("#medication_route").val("");
		$("#medication_frequency").val("");
		$("#duration").val("");
		$("#medication_drug_reaction").val("");
		$("#medication_pharmacogenetic_test").val("");
		var sPageURL = window.location.pathname;
		var parts = sPageURL.split("/");
		var patientId = parts[parts.length - 1];
		url = '/ccm/get-selected-medications_patient-by-id/' + patientId + '/' + med_id + '/selectedmedicationspatient';
		populateForm(patientId, url);
	}
}

function clearConditionForm(formsObj) {
	var formName = $(formsObj).closest(":has(form)").find('form').attr('name');
	var condition_name = $("form[name='" + formName + "'] #diagnosis_condition option:selected").text();
	$("form[name='" + formName + "'] input[name='condition']").val(condition_name);
	$("form[name='" + formName + "'] #diagnosis_code").val("");
	$("form[name='" + formName + "'] #append_symptoms").html("");
	$("form[name='" + formName + "'] #append_goals").html("");
	$("form[name='" + formName + "'] #append_tasks").html("");
	$("form[name='" + formName + "'] #symptoms_0").val("");
	$("form[name='" + formName + "'] #goals_0").val("");
	$("form[name='" + formName + "'] #tasks_0").val("");
	$("form[name='" + formName + "'] #support").val("");
	$("form[name='" + formName + "'] textarea[name='comments']").val("");
	$("form[name='" + formName + "'] textarea[name='comments']").text('');

}

function changeCondition(formsObj) {
	var formName = $(formsObj).closest(":has(form)").find('form').attr('name');
	//$("form[name='" + formName + "'] #diagnosis_condition option:selected").val('');
	inc_tasks = 0;
	inc_symptoms = 0;
	inc_goals = 0;
	var editid = $("form[name='" + formName + "'] #editdiagnoid").val();
	// alert(editid); 

	var sPageURL = window.location.pathname;
	parts = sPageURL.split("/");
	patientId = parts[parts.length - 1];


	var id = $("form[name='" + formName + "'] #diagnosis_condition option:selected").val();
	// alert(id);
	var condition_name = $("form[name='" + formName + "'] #diagnosis_condition option:selected").text();

	var code = $("form[name='" + formName + "'] #diagnosis_code").val();
	// alert(code);


	util.getDiagnosisIdfromPatientdiagnosisid(editid, condition_name, code,formsObj, patientId);
	$("form[name='" + formName + "'] input[name='condition']").val(condition_name);
	$("form[name='" + formName + "'] #diagnosis_code").val("");
	$("form[name='" + formName + "'] #append_symptoms").html("");
	$("form[name='" + formName + "'] #append_goals").html("");
	$("form[name='" + formName + "'] #append_tasks").html("");
	$("form[name='" + formName + "'] #symptoms_0").val("");
	$("form[name='" + formName + "'] #goals_0").val("");
	$("form[name='" + formName + "'] #tasks_0").val("");
	$("form[name='" + formName + "'] #support").val("");
	$("form[name='" + formName + "'] textarea[name='comments']").val("");
	$("form[name='" + formName + "'] textarea[name='comments']").text('');
	// debugger;
	DiagnosisFormPopulateURL = '/ccm/get-all-code-by-id/' + id + '/' + patientId + '/diagnosis';
	populateForm(patientId, DiagnosisFormPopulateURL);

	if (id == null || id == '' || id == "") {
		$("form[name='" + formName + "'] #save_care_plan_form").prop("disabled", true);
	} else {
		$("form[name='" + formName + "'] #save_care_plan_form").prop("disabled", false);
	}

}

function changeCode(formsObj) {
	var formName = $(formsObj).closest(":has(form)").find('form').attr('name');
	var condition = $("form[name='" + formName + "'] #diagnosis_condition option:selected").val();
	changeConfirmation = confirm("Are you sure you want to change the code?");
	if (condition != '') {
		if (changeConfirmation) {
			// Proceed as planned
			selected_val = $("form[name='" + formName + "'] #diagnosis_code option:selected").val();
			if (selected_val == 0) {
				$('.emaillist').removeClass("col-md-6").addClass("col-md-3");
				$('.otherlist').show();
			} else {
				$('.emaillist').removeClass("col-md-3").addClass("col-md-6");
				$('.otherlist').hide();
			}
		} else {
			$(this).val();
		}
	} else {
		alert('please selecte condition!');
		$("form[name='" + formName + "'] #diagnosis_code").val('');
	}
}

//var inc_goals = 0;
function additionalgoals(formsObj) {
	var formName = $(formsObj).closest(":has(form)").find('form').attr('name');
	inc_goals++;
	$("form[name='" + formName + "'] #append_goals").append('<div class="row btn_remove removegoals" id="btn_removegoals_' + inc_goals + '"><input type="text" class="form-control col-md-10" name ="goals[]" id ="goals_' + inc_goals + '" placeholder ="Enter Goals"><div class="invalid-feedback"></div><i class="col-md-1 remove-icons i-Remove float-right mb-3" id="remove_goals_' + inc_goals + '" title="Remove Goals"></i></div>');

}

//	var inc_symptoms = 0;
function additionalsymptoms(formsObj) {
	var formName = $(formsObj).closest(":has(form)").find('form').attr('name');
	inc_symptoms++;
	$("form[name='" + formName + "'] #append_symptoms").append('<div class=" row btn_remove removesymptoms" id="btn_removesymptoms_' + inc_symptoms + '"><input type="text" class="form-control col-md-10"  name ="symptoms[]" id ="symptoms_' + inc_symptoms + '"  placeholder ="Enter Symptoms"><div class="invalid-feedback"></div><i class="col-md-1 remove-icons i-Remove float-right mb-3" id="remove_symptoms_' + inc_symptoms + '" title="Remove Symptom"></i></div>');

}

//	var inc_tasks = 0;
function additionaltasks(formsObj) {
	var formName = $(formsObj).closest(":has(form)").find('form').attr('name');
	inc_tasks++;
	$("form[name='" + formName + "'] #append_tasks").append('<div class="row btn_remove newremovetasks" id="btn_removetasks_' + inc_tasks + '"><textarea class="mb-1 col-md-10 form-control" name ="tasks[]" id ="tasks_' + inc_tasks + '" placeholder ="Enter tasks"></textarea><div class="invalid-feedback"></div><i class="col-md-1 remove-icons i-Remove float-right mb-3" id="remove_tasks_' + inc_tasks + '" title="Remove Tasks"></i></div>');

}

function additionalimaging(formsObj) {
	var formName = $(formsObj).closest(":has(form)").find('form').attr('name');
	imagingcount++;
	$("form[name='" + formName + "'] #append_imaging").append('<div class="row btn_remove" id="btn_removeimaging_' + imagingcount + '"><div class="col-md-4"><input type="text" class="form-control" name="imaging[]" id="imaging_' + imagingcount + '" placeholder ="Enter Imaging"><div class="invalid-feedback"></div></div><div class="col-md-4"><input type="date" class="form-control" name="imaging_date[]" id="imagingdate' + imagingcount + '"placeholder ="Enter Imaging"><div class="invalid-feedback"></div></div><i class="col-md-1 remove-icons i-Remove float-right mb-3" id="remove_imaging_' + imagingcount + '" title="Remove Imaging"></i></div>');
	$("form[name='" + formName + "'] #imaging_date" + imagingcount).val('');
}

function additionalhealthdata(formsObj) {
	var formName = $(formsObj).closest(":has(form)").find('form').attr('name');
	healthdatacount++;
	$("form[name='" + formName + "'] #append_healthdata").append('<div class="row btn_remove" id="btn_removehealthdata_' + healthdatacount + '"><div class="col-md-4"><input type="text" class="form-control" name ="health_data[]" id ="healthdata_' + healthdatacount + '" placeholder ="Enter Health Data"><div class="invalid-feedback"></div></div><div class="col-md-4"><input type="date" class="form-control" name="health_date[]" id="healthdate' + healthdatacount + '"  placeholder ="Enter Health Data Date"><div class="invalid-feedback"></div></div><i class="col-md-1 remove-icons i-Remove float-right mb-3" id="remove_healthdata_' + healthdatacount + '" title="Remove healthdata"></i></div>');
	$("form[name='" + formName + "'] #health_date" + healthdatacount).val('');
}

var onChangeNumberTrackingVitalsWeightOrHeight = function () {
	var height = $("#number_tracking_vitals_form #height").val();
	var weight = $("#number_tracking_vitals_form #weight").val();
	$("#bmi").trigger("blur");
	if (height != null && weight != null && height != "" && weight != "") {
		util.updateBmi(weight, height, "#bmi");
	} else {
		$("#bmi").val(" ");
	}
}

function onProviderChange(formsObj) {
	var formName = $(formsObj).closest(":has(form)").find('form').attr('name');
	var provider_id = $("form[name='" + formName + "'] #provider_id").val();
	if (provider_id == '0') {
		$("form[name='" + formName + "'] #providers_div").removeClass("col-md-6").addClass("col-md-3");
		// $("form[name='" + formName + "'] #practices_div").removeClass("col-md-6").addClass("col-md-4");
		$("form[name='" + formName + "'] #providers_name").show();
	} else {
		$("form[name='" + formName + "'] #providers_div").removeClass("col-md-3").addClass("col-md-6");
		// $("form[name='" + formName + "'] #practices_div").removeClass("col-md-4").addClass("col-md-6");
		$("form[name='" + formName + "'] #pro_name").val("");
		$("form[name='" + formName + "'] #providers_name").hide();
	}
}

function onPracticeChange(formsObj) {
	var formName = $(formsObj).closest(":has(form)").find('form').attr('name');
	var practice_id = $("form[name='" + formName + "'] #practices").val();
	if (practice_id == '0') {
		$("form[name='" + formName + "'] #practices_div").removeClass("col-md-6").addClass("col-md-3");
		$("form[name='" + formName + "'] #practice_name").show();
		var practice_id = $("form[name='" + formName + "'] #practices option:selected").val();
		//util.updateCpdProviderList(practice_id, "form[name='" + formName + "'] #provider_id");
	} else {
		// $("form[name='" + formName + "'] #providers_div").removeClass("col-md-4").addClass("col-md-6");
		$("form[name='" + formName + "'] #practices_div").removeClass("col-md-3").addClass("col-md-6");
		$("form[name='" + formName + "'] #prac_name").val("");
		$("form[name='" + formName + "'] #practice_name").hide();
		var practice_id = $("form[name='" + formName + "'] #practices option:selected").val();
		//util.updateCpdProviderList(practice_id, "form[name='" + formName + "'] #provider_id");
	}
}

var renderDiagnosisTableData = function () {
	var diagnosisColumns = [
		{ data: 'DT_RowIndex', name: 'DT_RowIndex' },
		{ data: 'code', name: 'code' },
		{ data: 'condition', name: 'condition' },
		{
			data: null,
			render: function (data, type, full, meta) {
				if (data != '' && data != 'NULL' && data != undefined) {
					if (full['users'] == 'null' || full['users'] == null) {
						return "";
					} else {
						if ((full.users.f_name == '' || full.users.f_name == null) && (full.users.l_name == '' || full.users.l_name == null)) {
							return "";
						} else {
							return full.users.f_name + ' ' + full.users.l_name;
						}
					}
				}
			}
		},
		{ data: 'updated_at', name: 'updated_at' },
		{
			data: null,
			mRender: function (data, type, full, meta) {
				if (data != '' && data != 'NULL' && data != undefined) { 
					if (full['review'] == '1') {						
						return "Yes";  
					} else {	
						return "No";						
					}
					
					if (full['review'] == null) { 	
						return  "No";
					}
				}
			}, orderable: true
		},

		
		{
			data: null,
			mRender: function (data, type, full, meta) {
				return full['review_date'];
			},orderable: true
		},

		{
			data: null,
			mRender: function (data, type, full, meta) {
				return full['update_date'];
			}, orderable: true
		},


		

		{ data: 'action', name: 'action', orderable: false, searchable: false },
	];
	var table = util.renderDataTable('diagnosis-list', baseURL + "ccm/care-plan-development-diagnosis-diagnosislist/" + patient_id, diagnosisColumns, baseURL);
	return table;
};

var renderDiagnosisTable = function () {
	var table = renderDiagnosisTableData();
	util.copyDataFromOneDataTableToAnother(table, 'diagnosis-list', 'review-diagnosis-list', baseURL);
}

$('#myIconTab li a').on('click', function () {
	var sPageURL = window.location.pathname;
	parts = sPageURL.split("/"),
		id = parts[parts.length - 1];
	var patientId = id;



	if ($(this).is("#call-close-tab")) {

		/*if ($('#parentdiv').is(':empty')) {
			var p = $("#tempdiv").html();
			$("#ignore").html(p);
			$("#tempdiv").html('');
		}
		else {
			var p = $("#parentdiv").html();
			$("#ignore").html(p);
			$("#parentdiv").html('');
		}*/

		if ($("#tempdiv").html().trim().length > 0) {
			var p = $("#tempdiv").html();
			$("#ignore").html(p);
			$("#tempdiv").html('');
		}
		else if ($("#parentdiv").html().trim().length > 0) {
			var p = $("#parentdiv").html();
			$("#ignore").html(p);
			$("#parentdiv").html('');
		}


		var formpopulateurl = URL_POPULATE + "/" + patientId;
		populateForm(patientId, formpopulateurl);


	}
	else if ($(this).is("#call-tab")) {
		/*if ($('#parentdiv').is(':empty')) {
			var p = $("#ignore").html();
			$("#tempdiv").html(p);
			$("#ignore").html('');
		}
		else {
			var p = $("#parentdiv").html();
			$("#tempdiv").html(p);
			$("#parentdiv").html('');
		}
		*/

		if ($("#ignore").html().trim().length > 0) {
			var p = $("#ignore").html();
			$("#tempdiv").html(p);
			$("#ignore").html('');
		}
		else if ($("#parentdiv").html().trim().length > 0) {
			var p = $("#parentdiv").html();
			$("#tempdiv").html(p);
			$("#parentdiv").html('');
		}
		var formpopulateurl = URL_POPULATE + "/" + patientId;
		populateForm(patientId, formpopulateurl);

	}

});

var copyPreviousMonthDataToThisMonth  = function(patient_id, module_id) {
    if (!patient_id) {
        return;
    }
    if (!module_id) {
        return;
    }
    axios({
        method: "GET",
        url: `/ccm/ajax/copy-previous-month-data-to-this-month/${patient_id}/${module_id}/previous-month-data`,
    }).then(function (response) {
		// console.log(response);
		if (module == 'care-plan-development') {
			carePlanDevelopment.callCPDInitFunctions();
		} else {
			ccmMonthlyMonitoring.callMonthllyMonitoringInitFunctions();
		}
    }).catch(function (error) {
        console.error(error, error.response);
    });
}

var callCPDInitFunctions = function () {
	var patient_id = $("input[name='patient_id']").val();
	var module_id = $("input[name='module_id']").val();
	var year = (new Date).getFullYear();
	var month = (new Date).getMonth() + 1; //add +1 for current mnth
	//console.log("patient_id" + patient_id + " module_id" + module_id + "year" + year + "month" + month);
	var allergy_type = $('form[name="allergy_drug_form"] input[name="allergy_type"]').val();
	var review_allergy_type = $('form[name="review_allergy_drug_form"] input[name="allergy_type"]').val();
	var id = $("#patient_id").val();
	util.getToDoListData($("#hidden_id").val(), $("#page_module_id").val());
	util.getPatientDetails(patient_id, module_id);
	//util.getPatientDetailsModel(patient_id, module_id);
	
	util.refreshAllergyCountCheckbox(id, allergy_type, 'allergy_drug_form');
	util.refreshAllergyCountCheckbox(id, review_allergy_type, 'review_allergy_drug_form');
	util.getPatientCurrentMonthNotes(patient_id, module_id);
	util.getPatientPreviousMonthNotes(patient_id, module_id, month, year);
	util.getPatientCareplanNotes(patient_id, module_id);
	util.getPatientStatus(patient_id, module_id);
	util.gatCaretoolData(patient_id, module_id);

	CompletedCheck();
	renderSiblingTable();
	renderGrandchildrenTable();
	renderChildrenTable();
	renderLiveWithTable();
	renderDiagnosisTable();
	renderPetTable();
	renderHobbiesTable();
	renderTravelTable();
	renderOtherProviderSpecialistTable();
	renderDMEServicesTable();
	renderDialysiServicesTable();
	renderHomeHealthServicesTable();
	renderOtherHealthServicesTable();
	renderMedicalSuppliesServicesTable();
	renderSocialServicesTable();
	renderTherapyServicesTable();


	renderLabsTable();
	renderVitalTable();
	//renderImagingTable();

	renderMedicationsTable();
	renderdrugTable();
	renderEnviromentalTable();
	renderFoodTable();
	renderinsectTable();
	renderLatexTable();
	renderPetRelatedTable();
	renderAllergyOtherTable();
	// renderDiagnosisTable();
	refreshdrugcountcheckbox();
	populateformdata();

	$("#home_service_yes_div").hide();
	$("#next_month_call_div").hide();
	$("#parentdiv").hide();
	$("form[name='callstatus_form'] #notAnswer").hide();
	$("form[name='callstatus_form'] #callAnswer").hide();
	$("form[name='callstatus_form'] #call-save-button").html('<button type="submit" class="btn  btn-primary m-1">Next</button>');
	
}


var init = function () {
	//util.redirectToWorklistPage();
	copyPreviousMonthDataToThisMonth($("#hidden_id").val(),$("#page_module_id").val());
	$('form[name="personal_notes_form"] .submit-personal-notes').on('click', function (e) {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("form[name='personal_notes_form'] input[name='start_time']").val(timer_start);
		$("form[name='personal_notes_form'] input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		form.ajaxSubmit('personal_notes_form', patientEnrollment.onPersonalNotes);
	});

	$('form[name="part_of_research_study_form"] .submit-part-of-research-study').on('click', function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("form[name='part_of_research_study_form'] input[name='start_time']").val(timer_start);
		$("form[name='part_of_research_study_form'] [name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		form.ajaxSubmit('part_of_research_study_form', patientEnrollment.onPartOfResearchStudy);
	});

	$('form[name="patient_threshold_form"] .submit-patient-threshold').on('click', function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("form[name='patient_threshold_form'] input[name='start_time']").val(timer_start);
		$("form[name='patient_threshold_form'] [name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		form.ajaxSubmit('patient_threshold_form', patientEnrollment.onPatientThreshold);
	});


	// form.ajaxForm("family_patient_data_form", onPatientDataFamily, function () {
	form.ajaxForm("family_patient_data_form", onFamilyData, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	// form.ajaxForm("family_spouse_form", onSpouseFamily, function () {
	form.ajaxForm("family_spouse_form", onFamilyData, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	// form.ajaxForm("family_emergency_contact_form", onEmergencyContactFamily, function () {
	form.ajaxForm("family_emergency_contact_form", onFamilyData, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	// form.ajaxForm("family_care_giver_form", onCareGiverFamily, function () {
	form.ajaxForm("family_care_giver_form", onFamilyData, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	//Allergies     	
	form.ajaxForm("allergy_food_form", onAllergy, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	form.ajaxForm("allergy_drug_form", onAllergy, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	form.ajaxForm("allergy_enviromental_form", onAllergy, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	form.ajaxForm("allergy_insect_form", onAllergy, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	form.ajaxForm("allergy_latex_form", onAllergy, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	form.ajaxForm("allergy_pet_related_form", onAllergy, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	form.ajaxForm("allergy_other_allergy_form", onAllergy, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	//services 
	form.ajaxForm("service_dialysis_form", onServices, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	// form.ajaxForm("service_social_form", onSocialServices, function () {
	form.ajaxForm("service_social_form", onServices, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	// form.ajaxForm("service_home_health_form", onHomeHealthServices, function () {
	form.ajaxForm("service_home_health_form", onServices, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	// form.ajaxForm("service_dme_form", onDmeServices, function () {
	form.ajaxForm("service_dme_form", onServices, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	// form.ajaxForm("service_medical_supplies_form", onMedicalSuppliesServices, function () {
	form.ajaxForm("service_medical_supplies_form", onServices, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	// form.ajaxForm("service_other_health_form", onOtherServices, function () {
	form.ajaxForm("service_other_health_form", onServices, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	// form.ajaxForm("service_therapy_form", onTherapyServices, function () {
	form.ajaxForm("service_therapy_form", onServices, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	//Number Tracking Vital
	form.ajaxForm("number_tracking_vitals_form", onNumberTrackingVital, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	form.ajaxForm("number_tracking_labs_form", onNumberTrackingLab, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	form.ajaxForm("number_tracking_imaging_form", onNumberTrackingImaging, function () {
		//alert("radha")
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	form.ajaxForm("number_tracking_healthdata_form", onNumberTrackingHealthdata, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	//provider
	form.ajaxForm("provider_pcp_form", onProvider, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	form.ajaxForm("provider_specialists_form", onProvider, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	form.ajaxForm("provider_vision_form", onProvider, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	form.ajaxForm("provider_dentist_form", onProvider, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	//Review Provider
	form.ajaxForm("review_provider_pcp_form", onProvider, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	form.ajaxForm("review_provider_specialists_form", onProvider, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	form.ajaxForm("review_provider_vision_form", onProvider, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	form.ajaxForm("review_provider_dentist_form", onProvider, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	//Review patient data
	// form.ajaxForm("review_family_patient_data_form", onReviewPatientDataFamily, function () {onFamilyData
	form.ajaxForm("review_family_patient_data_form", onFamilyData, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	// form.ajaxForm("review_family_spouse_form", onReviewSpouseFamily, function () {
	form.ajaxForm("review_family_spouse_form", onFamilyData, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	// form.ajaxForm("review_family_emergency_contact_form", onReviewEmergencyContactFamily, function () {
	form.ajaxForm("review_family_emergency_contact_form", onFamilyData, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	// form.ajaxForm("review_family_care_giver_form", onReviewCareGiverFamily, function () {
	form.ajaxForm("review_family_care_giver_form", onFamilyData, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	form.ajaxForm("review_do_you_live_with_anyone_form", onPatientRelationshipData, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	form.ajaxForm("grandchildren_form", onPatientRelationshipData, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	form.ajaxForm("children_form", onPatientRelationshipData, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	form.ajaxForm("sibling_form", onPatientRelationshipData, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	//Reeview Allergies     	
	form.ajaxForm("review_allergy_food_form", onAllergy, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	form.ajaxForm("review_allergy_drug_form", onAllergy, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	form.ajaxForm("review_allergy_enviromental_form", onAllergy, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	form.ajaxForm("review_allergy_insect_form", onAllergy, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	form.ajaxForm("review_allergy_latex_form", onAllergy, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	form.ajaxForm("review_allergy_pet_related_form", onAllergy, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	form.ajaxForm("review_allergy_other_allergy_form", onAllergy, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	//review_services
	// form.ajaxForm("review_service_dialysis_form", onReviewDialysisServices, function () {
	form.ajaxForm("review_service_dialysis_form", onServices, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	// form.ajaxForm("review_service_social_form", onReviewSocialServices, function () {
	form.ajaxForm("review_service_social_form", onServices, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	// form.ajaxForm("review_service_home_health_form", onReviewHomeHealthServices, function () {
	form.ajaxForm("review_service_home_health_form", onServices, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	// form.ajaxForm("review_service_dme_form", onReviewDmeServices, function () {
	form.ajaxForm("review_service_dme_form", onServices, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	// form.ajaxForm("review_service_medical_supplies_form", onReviewMedicalSuppliesServices, function () {
	form.ajaxForm("review_service_medical_supplies_form", onServices, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	// form.ajaxForm("review_service_other_health_form", onReviewOtherServices, function () {
	form.ajaxForm("review_service_other_health_form", onServices, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	// form.ajaxForm("review_service_therapy_form", onReviewTherapyServices, function () {
	form.ajaxForm("review_service_therapy_form", onServices, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	//dignosis
	form.ajaxForm("diagnosis_code_form", onDiagnosis, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});
	// review dignosis
	form.ajaxForm("review_diagnosis_code_form", onDiagnosis, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});
	//Medication

	form.ajaxForm("medications_form", onMedication, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});
	//review medication

	form.ajaxForm("review_medications_form", onMedication, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	//review Hobies
	// form.ajaxForm("review_hobbies_form", onReviewHobies, function () {
	form.ajaxForm("review_hobbies_form", onOtherPersonalData, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	//review Pet
	// form.ajaxForm("review_pets_form", onReviewpet, function () {
	form.ajaxForm("review_pets_form", onOtherPersonalData, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	//review travel
	// form.ajaxForm("review_travel_form", onReviewTravel, function () {
	form.ajaxForm("review_travel_form", onOtherPersonalData, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});


	//callstatus
	form.ajaxForm("hippa_form", ccmMonthlyMonitoring.onCallHippa, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	form.ajaxForm("patient_first_review", onFirstReview, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	form.ajaxForm("homeservice_form", onHomeService, function () {
		var count = 0;
		$("form[name='homeservice_form'] input:checkbox").each(function () {
			if ($(this).is(':checked')) {
				count++;
			}
		});
		var therapistComeHomeCare = $('form[name="homeservice_form"] input[name="therapist_come_home_care"]:checked').val();
		if ((count <= 0) && ((therapistComeHomeCare == "1") || (therapistComeHomeCare == 1))) {
			if ($('form[name="homeservice_form"] input[name="verification"]').is(':checked')) {
				$("form[name='homeservice_form'] input[name='verification']").removeClass("is-invalid");
				$("form[name='homeservice_form'] #verification-invalid-feedback").html('');
				$("form[name='homeservice_form'] #verification-invalid-feedback").hide();
			} else {
				$("form[name='homeservice_form'] input[name='verification']").addClass("is-invalid");
				$("form[name='homeservice_form'] #verification-invalid-feedback").html('The verification field is required.');
				$("form[name='homeservice_form'] #verification-invalid-feedback").show();
			}

			if ((therapistComeHomeCare == "1") || (therapistComeHomeCare == 1) || (therapistComeHomeCare == "0") || (therapistComeHomeCare == 0)) {
				$("form[name='homeservice_form'] input[name='therapist_come_home_care']").removeClass("is-invalid");
				$("form[name='homeservice_form'] input[name='therapist_come_home_care']").next(".invalid-feedback").html('');
			} else {
				$("form[name='homeservice_form'] input[name='therapist_come_home_care']").addClass("is-invalid");
				$("form[name='homeservice_form'] input[name='therapist_come_home_care']").next(".invalid-feedback").html('The therapist come home care field is required.');
			}

			if ((therapistComeHomeCare == "1") || therapistComeHomeCare == 1) {
				if (count <= 0) {
					$("form[name='homeservice_form'] #resons_error").text('Please select reasons that the nurse or therapist comes for home-visit.');
				} else {
					$("form[name='homeservice_form'] #resons_error").text('');
				}

				var reasonForVisit = $('form[name="homeservice_form"] textarea[name="reason_for_visit"]').val();
				if ((reasonForVisit == "") || (reasonForVisit == null) || (reasonForVisit == undefined) || (reasonForVisit.trim().length < 0)) {
					$('form[name="homeservice_form"] textarea[name="reason_for_visit"]').addClass("is-invalid");
					$('form[name="homeservice_form"] textarea[name="reason_for_visit"]').next(".invalid-feedback").html('The reason for visit field is required, when therapist come home care answered yes.');
				} else {
					$('form[name="homeservice_form"] textarea[name="reason_for_visit"]').removeClass("is-invalid");
					$('form[name="homeservice_form"] textarea[name="reason_for_visit"]').next(".invalid-feedback").html('');
				}

				var homeServiceEnds = $('form[name="homeservice_form"] input[name="home_service_ends"]:checked').val();
				if ((homeServiceEnds == "1") || (homeServiceEnds == 1) || (homeServiceEnds == "0") || (homeServiceEnds == 0)) {
					$("form[name='homeservice_form'] input[name='home_service_ends']").removeClass("is-invalid");
					$("form[name='homeservice_form'] #home_service_ends_error").html('');
					$("form[name='homeservice_form'] #home_service_ends_error").hide();
				} else {
					$("form[name='homeservice_form'] input[name='home_service_ends']").addClass("is-invalid");
					$("form[name='homeservice_form'] #home_service_ends_error").html('The home service ends field is required when therapist come home care is 1.');
					$("form[name='homeservice_form'] #home_service_ends_error").show();
				}
				if (homeServiceEnds == "0" || homeServiceEnds == 0) {
					var followUpDate = $('form[name="homeservice_form"] input[name="follow_up_date"]').val();
					if ((followUpDate == "") || (followUpDate == null) || (followUpDate == undefined) || (followUpDate.trim().length < 0)) {
						$('form[name="homeservice_form"] input[name="follow_up_date"]').addClass("is-invalid");
						$('form[name="homeservice_form"] input[name="follow_up_date"]').next(".invalid-feedback").html('The follow up date field is required when "Do you know when the Home Service ends?" answered as no.');
					} else {
						$('form[name="homeservice_form"] input[name="follow_up_date"]').removeClass("is-invalid");
						$('form[name="homeservice_form"] input[name="follow_up_date"]').next(".invalid-feedback").html('');
					}
				} else if (homeServiceEnds == "1" || homeServiceEnds == 1) {
					var serviceEndDate = $('form[name="homeservice_form"] input[name="service_end_date"]').val();
					if ((serviceEndDate == "") || (serviceEndDate == null) || (serviceEndDate == undefined) || (serviceEndDate.trim().length < 0)) {
						$('form[name="homeservice_form"] input[name="service_end_date"]').addClass("is-invalid");
						$('form[name="homeservice_form"] input[name="service_end_date"]').next(".invalid-feedback").html('The home service end date field is required when "Do you know when the Home Service ends?" answered as yes.');
					} else {
						$('form[name="homeservice_form"] input[name="service_end_date"]').removeClass("is-invalid");
						$('form[name="homeservice_form"] input[name="service_end_date"]').next(".invalid-feedback").html('');
					}
				}
			}
			return false;
		} else {
			$("#time-container").val(AppStopwatch.pauseClock);
			var timer_start = $("#timer_start").val();
			var timer_paused = $("#time-container").text();
			$("input[name='start_time']").val(timer_start);
			$("input[name='end_time']").val(timer_paused);
			// $("#timer_start").val(timer_paused);
			$("#timer_end").val(timer_paused);
			$("#time-container").val(AppStopwatch.startClock);
			$("form[name='homeservice_form'] #resons_error").text('');
			return true;
		}

	});

	form.ajaxForm("callstatus_form", ccmMonthlyMonitoring.onCallStatus, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	$("form[name='callstatus_form'] input[name='call_continue_status']").click(function () {
		var call_continue_status = $(this).val();
		if (call_continue_status == '0') {
			$("#schedule_call_ans_next_call").show();
		} else {
			$("#answer_followup_date").val('');//reset form
			$("#schedule_call_ans_next_call").hide();
		}
	});

	$("form[name='callstatus_form'] input[name='answer_followup_time']").change(function () {

		// var call_continue_status = $(this).val();
		var inputEle = document.getElementById('answer_followup_time');

		var timeSplit = inputEle.value.split(':'),
			hours,
			minutes,
			meridian;
		hours = timeSplit[0];
		minutes = timeSplit[1];
		if (hours > 12) {
			meridian = 'PM';
			hours -= 12;
		} else if (hours < 12) {
			meridian = 'AM';
			if (hours == 0) {
				hours = 12;
			}
		} else {
			meridian = 'PM';
		}
		var answer_followup_time = hours + ':' + minutes + ' ' + meridian;
		$("#hourtime").val(answer_followup_time);

	});

	//call.call.call-status-change
	$("form[name='callstatus_form'] input[name='call_status']").click(function () {
		var checked_call_option = $("form[name='callstatus_form'] input[name$='call_status']:checked").val();
		// console.log("checked_call_option=>"+checked_call_option+"<=");
		if (checked_call_option == 1 || checked_call_option == "1") {
			$('.invalid-feedback').html('');
			$("form[name='callstatus_form'] #notAnswer").hide();
			$("#answer").prop('selectedIndex', 0);//reset value
			$("#contact_number").prop('selectedIndex', 0);//reset value
			// $("#ccm_content_title").prop('selectedIndex', 0);//reset value
			// $("#ccm_content_area").val('');//reset value
			$("#call_followup_date").val('');//reset value  
			$("form[name='callstatus_form'] #callAnswer").show();
			$("form[name='callstatus_form'] #call_scripts_select option:last").attr("selected", "selected").change();
			$("form[name='callstatus_form'] #call-save-button").html('<button type="submit" class="btn  btn-primary m-1" id="save-callstatus">Next</button>');
			$("form[name='callstatus_form'] #call_action_script").val($("form[name='callstatus_form'] input[name='call_action_script'] option:selected").text());
			util.getCallScriptsById($("form[name='callstatus_form'] #call_scripts_select option:selected").val(), '.call_answer_template', "form[name='callstatus_form'] input[name='template_type_id']", "form[name='callstatus_form'] input[name='content_title']");
		} else if (checked_call_option == 2 || checked_call_option == "2") {
			$('.invalid-feedback').html('');
			$("form[name='callstatus_form'] #callAnswer").hide();
			// $("#call_scripts_select").prop('selectedIndex', 0);//reset value
			$("#role1").prop('checked', false);//reset value
			$("#role2").prop('checked', false);//reset value
			$("#answer_followup_date").val('');//reset value
			$("form[name='callstatus_form'] #notAnswer").show();
			$("form[name='callstatus_form'] #ccm_content_title option:last").attr("selected", "selected").change();
			$("form[name='callstatus_form'] #call-save-button").html('<button type="submit" class="btn btn-primary m-1 call_status_submit" id="save_schedule_call">Schedule Call</button>');
			$("form[name='callstatus_form'] #call_action_script").val($("form[name='callstatus_form'] input[name='content_title'] option:selected").text());
			util.getCallScriptsById($("form[name='callstatus_form'] #ccm_content_title option:selected").val(), '#ccm_content_area', "form[name='callstatus_form'] input[name='template_type_id']", "form[name='callstatus_form'] input[name='content_title']");
		}
	});

	form.ajaxForm("call_close_form", ccmMonthlyMonitoring.onCallClose, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	form.ajaxForm("callwrapup_form", ccmMonthlyMonitoring.onCallWrapUp, function () {
		$("#time-container").val(AppStopwatch.pauseClock);
		var timer_start = $("#timer_start").val();
		var timer_paused = $("#time-container").text();
		$("input[name='start_time']").val(timer_start);
		$("input[name='end_time']").val(timer_paused);
		// $("#timer_start").val(timer_paused);
		$("#timer_end").val(timer_paused);
		$("#time-container").val(AppStopwatch.startClock);
		return true;
	});

	$("form[name='callstatus_form'] input[name='call_status']").click(function () {
		var checked_call_option = $("form[name='callstatus_form'] input[name$='call_status']:checked").val();
		if (checked_call_option == '1') {
			$('.invalid-feedback').html('');
			$("form[name='callstatus_form'] #notAnswer").hide();
			$("form[name='callstatus_form'] #callAnswer").show();
			$("form[name='callstatus_form'] #call_scripts_select option:last").attr("selected", "selected").change();
			$("form[name='callstatus_form'] #call-save-button").html('<button type="submit" class="btn  btn-primary m-1" id="save-callstatus">Next</button>');
			$("form[name='callstatus_form'] #call_action_script").val($("form[name='callstatus_form'] input[name='call_action_script'] option:selected").text());
			util.getCallScriptsById($("form[name='callstatus_form'] #call_scripts_select").val(), '.call_answer_template');
			$("form[name='callstatus_form'] input[name='content_title']").val($("form[name='callstatus_form'] #call_scripts_select option:selected").text());
		} else if (checked_call_option == '2') {
			$('.invalid-feedback').html('');
			$("form[name='callstatus_form'] #notAnswer").show();
			$("form[name='callstatus_form'] #callAnswer").hide();
			$("form[name='callstatus_form'] #content_title option:last").attr("selected", "selected").change();
			$("form[name='callstatus_form'] #call-save-button").html('<button type="submit" class="btn btn-primary m-1 call_status_submit" id="save_schedule_call">Schedule Call</button>');
			$("form[name='callstatus_form'] #call_action_script").val($("form[name='callstatus_form'] input[name='content_title'] option:selected").text());
			util.getCallScriptsById($("form[name='callstatus_form'] #ccm_content_title").val(), '#ccm_content_area');
			$("form[name='callstatus_form'] input[name='content_title']").val($("form[name='callstatus_form'] #ccm_content_title option:selected").text());
		}
	});

	$("form[name='callstatus_form'] #call_scripts_select").change(function () {
		util.getCallScriptsById($(this).val(), '.call_answer_template');
		$("form[name='callstatus_form'] input[name='content_title']").val($("form[name='callstatus_form'] #call_scripts_select option:selected").text());
	});

	$("form[name='callstatus_form'] #ccm_content_title").change(function () {
		util.getCallScriptsById($(this).val(), '#ccm_content_area');
		$("form[name='callstatus_form'] input[name='content_title']").val($("form[name='callstatus_form'] #ccm_content_title option:selected").text());
	});

	//non-eligible script
	$("form[name='homeservice_form'] #non-eligible-script option:last").attr("selected", "selected").change();
	util.getCallScriptsById($("form[name='homeservice_form'] #non-eligible-script").val(), '.non-eligible-script-container', "form[name='homeservice_form'] input[name='template_type_id']", "form[name='homeservice_form'] input[name='content_title']");

	$("input[name='call_status']").click(function () {
		var checked_call_option = $("input[name$='call_status']:checked").val();
		if (checked_call_option == "1") {
			$(".invalid-feedback").html("");
			$("#CcmNotAnswer").hide();
			$("#CcmCallAnswer").show();
			$("#save-callstatus").show();
			$("#ccm_call_scripts_select option:last")
				.attr("selected", "selected")
				.change();
		} else if (checked_call_option == "2") {
			$(".invalid-feedback").html("");
			$("#CcmNotAnswer").show();
			$("#CcmCallAnswer").hide();
			$("#save-callstatus").hide();
			$("#ccm_content_title option:last")
				.attr("selected", "selected")
				.change();
		}
	});

	$("form[name='homeservice_form'] input[name='therapist_come_home_care']").click(function () {
		var call_next_month_data = $(this).val();
		if (call_next_month_data == '1') {
			$("#home_service_yes_div").show();
		} else {
			$("form[name='homeservice_form'] input[type='checkbox']").prop("checked", false);
			$("form[name='homeservice_form'] textarea").val('');
			$("form[name='homeservice_form'] input[name='service_end_date']").val('');
			$("form[name='homeservice_form'] input[name='follow_up_date']").val('');
			$("#dateend").prop('checked', false);
			$("#datef").prop('checked', false);
			$("#home_service_yes_div").hide();
		}
	});

	$("input[name='therapist_come_home_care']").click(function () {
		var therapist = $(this).val();
		if (therapist == "1") {
			$("#applicableboxes_reason").show();
		} else {
			$("form[name='homeservice_form'] input[type='checkbox']").prop("checked", false);
			$("form[name='homeservice_form'] textarea").val('');
			$("form[name='homeservice_form'] input[name='service_end_date']").val('');
			$("form[name='homeservice_form'] input[name='follow_up_date']").val('');
			$("#dateend").prop('checked', false);
			$("#datef").prop('checked', false);
			$("#applicableboxes_reason").hide();
		}
	});

	$('input[name="home_service_ends"]').click(function () {
		var homeservice_data = $("input[name$='home_service_ends']:checked").val();
		if (homeservice_data == '1') {
			$("#1_box").css('display', 'block');
			$("form[name='homeservice_form'] input[name='follow_up_date']").val('');//reset values
			$("form[name='homeservice_form'] input[name='service_end_date']").val('');//reset value
			$("#2_box").css('display', 'none');
		} else if (homeservice_data == '0') {
			$("#2_box").css('display', 'block');
			$("form[name='homeservice_form'] input[name='service_end_date']").val('');//reset value
			$("form[name='homeservice_form'] input[name='follow_up_date']").val('');//reset values
			$("#1_box").css('display', 'none');
			$("form[name='homeservice_form'] #home_services_no_div").hide(); //hide non-eligible script
			$("form[name='homeservice_form'] #eligibility-alert").hide(); //hide non-eligible script alert
		}
	});

	$("form[name='call_close_form'] input[name='query2']").click(function () {
		var call_next_month_data = $(this).val();
		if (call_next_month_data == '1' || call_next_month_data == '0') {
			$("#next_month_call_div").show();
		} else {
			$("#next_month_call_date").val('');//reset form value
			$("#next_month_call_time").val('');//reset form value
			$("form[name='call_close_form'] textarea[name='q2_notes']").val('');//reset form value
			$("#next_month_call_div").hide();
		}
	});

	$("form[name='number_tracking_vitals_form'] input[name='oxygen']").click(function () {
		var oxygen = $(this).val();
		if (oxygen == '0') {
			$("#Supplemental_notes_div").show();
		} else {
			$("form[name='number_tracking_vitals_form'] textarea[name='notes']").val('');//reset form value
			$("#Supplemental_notes_div").hide();
		}
	});

	$('body').on('click', '#diagnosis-codes-click_id', function () {
		inc_symptoms = 0;
		review_inc_symptoms = 0;
		inc_goals = 0;
		review_inc_goals = 0;
		inc_tasks = 0;
		review_inc_tasks = 0;
		$("form[name='diagnosis_code_form'] #diagnosis_hiden_id").val("");
		$("form[name='diagnosis_code_form'] #diagnosis_condition").val("");
		$("form[name='diagnosis_code_form'] #diagnosis_code").val("");
		$("form[name='diagnosis_code_form'] #append_symptoms").html("");
		$("form[name='diagnosis_code_form'] #append_goals").html("");
		$("form[name='diagnosis_code_form'] #append_tasks").html("");
		$("form[name='diagnosis_code_form'] #support").val('');
		$("form[name='diagnosis_code_form'] #symptoms_0").val('');
		$("form[name='diagnosis_code_form'] #goals_0").val('');
		$("form[name='diagnosis_code_form'] #tasks_0").val('');
		$("form[name='diagnosis_code_form'] textarea[name='comments']").val('');
		$("form[name='diagnosis_code_form'] #support").val('');
		$("form[name='diagnosis_code_form']")[0].reset();
		$("form[name='diagnosis_code_form']").trigger("reset");

		var id = $("form[name='diagnosis_code_form'] #diagnosis_condition option:selected").val();
		util.selectDiagnosisCode(parseInt(id), $("form[name='diagnosis_code_form'] #diagnosis_code"));
	});

	$('body').on('click', '#open_codes_info_for_medical', function () {
		inc_symptoms = 0;
		review_inc_symptoms = 0;
		inc_goals = 0;
		review_inc_goals = 0;
		inc_tasks = 0;
		review_inc_tasks = 0;
		$("form[name='review_diagnosis_code_form'] #diagnosis_hiden_id").val("");
		$("form[name='review_diagnosis_code_form'] #diagnosis_condition").val("");
		$("form[name='review_diagnosis_code_form'] #diagnosis_code").val("");
		$("form[name='review_diagnosis_code_form'] #append_symptoms").html("");
		$("form[name='review_diagnosis_code_form'] #append_goals").html("");
		$("form[name='review_diagnosis_code_form'] #append_tasks").html("");
		$("form[name='review_diagnosis_code_form'] #support").val('');
		$("form[name='review_diagnosis_code_form'] #symptoms_0").val('');
		$("form[name='review_diagnosis_code_form'] #goals_0").val('');
		$("form[name='review_diagnosis_code_form'] #tasks_0").val('');
		$("form[name='review_diagnosis_code_form'] textarea[name='comments']").val('');
		$("form[name='review_diagnosis_code_form'] #support").val('');
		$("form[name='review_diagnosis_code_form']")[0].reset();
		$("form[name='review_diagnosis_code_form']").trigger("reset");

		var id = $("form[name='review_diagnosis_code_form'] #diagnosis_condition option:selected").val();
		util.selectDiagnosisCode(parseInt(id), $("form[name='review_diagnosis_code_form'] #diagnosis_code"));
	});

	$('body').on('click', '#click_services_id', function () {
		$("#service_dme_form")[0].reset();
		$("#service_home_health_form")[0].reset();
		$("form[name='service_dialysis_form']")[0].reset();
		$("#service_therapy_form")[0].reset();
		$("#service_social_form")[0].reset();
		$("#service_medical_supplies_form")[0].reset();
		$("#service_other_health_form")[0].reset();

		$("form[name='service_dme_form'] #id").val('');
		$("form[name='service_home_health_form'] #id").val('');
		$("form[name='service_dialysis_form'] #id").val('');
		$("form[name='service_therapy_form'] #id").val('');
		$("form[name='service_social_form'] #id").val('');
		$("form[name='service_medical_supplies_form'] #id").val('');
		$("form[name='service_other_health_form'] #id").val('');

	});

	$('body').on('click', '.allergiesclick', function () {
		$("#review_allergy_food_form")[0].reset();
		$("#review_allergy_drug_form")[0].reset();
		$("#review_allergy_enviromental_form")[0].reset();
		$("#review_allergy_insect_form")[0].reset();
		$("#review_allergy_latex_form")[0].reset();
		$("#review_allergy_pet_related_form")[0].reset();
		$("#review_allergy_other_allergy_form")[0].reset();

		$("#allergy_food_form")[0].reset();
		$("#allergy_drug_form")[0].reset();
		$("#allergy_enviromental_form")[0].reset();
		$("#allergy_insect_form")[0].reset();
		$("#allergy_latex_form")[0].reset();
		$("#allergy_pet_related_form")[0].reset();
		$("#allergy_other_allergy_form")[0].reset();

		$("form[name='review_allergy_food_form'] #id").val('');
		$("form[name='review_allergy_drug_form'] #id").val('');
		$("form[name='review_allergy_enviromental_form'] #id").val('');
		$("form[name='review_allergy_insect_form'] #id").val('');
		$("form[name='review_allergy_latex_form'] #id").val('');
		$("form[name='review_allergy_pet_related_form'] #id").val('');
		$("form[name='review_allergy_other_allergy_form'] #id").val('');
		$("form[name='allergy_food_form'] #id").val('');
		$("form[name='allergy_drug_form'] #id").val('');
		$("form[name='allergy_enviromental_form'] #id").val('');
		$("form[name='allergy_insect_form'] #id").val('');
		$("form[name='allergy_latex_form'] #id").val('');
		$("form[name='allergy_pet_related_form'] #id").val('');
		$("form[name='allergy_other_allergy_form'] #id").val('');
	});

	$('body').on('click', '#open_health_services', function () {
		$("#review_service_dme_form")[0].reset();
		$("#review_service_home_health_form")[0].reset();
		$("form[name='review_service_dialysis_form']")[0].reset();
		$("#review_service_medical_supplies_form")[0].reset();
		$("#review_service_other_health_form")[0].reset();
		$("#review_service_social_form")[0].reset();
		$("#review_service_therapy_form")[0].reset();


		$("form[name='review_service_dme_form'] #id").val('');
		$("form[name='review_service_home_health_form'] #id").val('');
		$("form[name='review_service_dialysis_form'] #id").val('');
		$("form[name='review_service_medical_supplies_form'] #id").val('');
		$("form[name='review_service_other_health_form'] #id").val('');
		$("form[name='review_service_social_form'] #id").val('');
		$("form[name='review_service_therapy_form'] #id").val('');
	});

	$('.patient_data_allergies_tab').click(function (e) {
		var target = $(e.target).attr("href") // activated tab  
		var form = $(target).find("form").attr('name');
		var allergy_type = $("form[name=" + form + "] input[name='allergy_type']").val();
		var id = $("#patient_id").val();
		util.refreshAllergyCountCheckbox(id, allergy_type, form);
	});

	$('.review_data_allergy_tab').click(function (e) {
		var target = $(e.target).attr("href") // activated tab  
		var form = $(target).find("form").attr('name');
		var allergy_type = $("form[name=" + form + "] input[name='allergy_type']").val();
		var id = $("#patient_id").val();
		util.refreshAllergyCountCheckbox(id, allergy_type, form);
	});

	$(document).on("click", ".remove-icons", function () {
		var button_id = $(this).closest('div').attr('id');
		$('#' + button_id).remove();
	});

	$('form[name="review_do_you_live_with_anyone_form"] #review_live_fname_0').on('blur', function () {
		if ($('#review_live_fname_0').val() != '') {
			$("form[name='review_do_you_live_with_anyone_form'] input[name='relational_status'][value='1']").prop('checked', true);
		} else {
			$("form[name='review_do_you_live_with_anyone_form'] input[name='relational_status'][value='0']").prop('checked', false);
		}
	});

	$('form[name="sibling_form"] #review_sibling_fname').on('blur', function () {
		if ($('#review_sibling_fname').val() != '') {
			$("form[name='sibling_form'] input[name='relational_status'][value='1']").prop('checked', true);
		} else {
			$("form[name='sibling_form'] input[name='relational_status'][value='0']").prop('checked', false);
		}
	});

	$('form[name="children_form"] #review_sibling_fname').on('blur', function () {
		if ($('#review_sibling_fname').val() != '') {
			$("form[name='children_form'] input[name='relational_status'][value='1']").prop('checked', true);
		} else {
			$("form[name='children_form'] input[name='relational_status'][value='0']").prop('checked', false);
		}
	});

	$('form[name="grandchildren_form"] #review_sibling_fname').on('blur', function () {
		if ($('#review_sibling_fname').val() != '') {
			$("form[name='grandchildren_form'] input[name='relational_status'][value='1']").prop('checked', true);
		} else {
			$("form[name='grandchildren_form'] input[name='relational_status'][value='0']").prop('checked', false);
		}
	});

	$('form[name="review_pets_form"] #review_pet_name').on('blur', function () {
		if ($('#review_pet_name').val() != '') {
			$("form[name='review_pets_form'] input[name='pet_status'][value='1']").prop('checked', true);
		} else {
			$("form[name='review_pets_form'] input[name='pet_status'][value='0']").prop('checked', false);
		}
	});

	$('form[name="review_hobbies_form"] #review_hobbie_description').on('blur', function () {
		if ($('#review_hobbie_description').val() != '') {
			$("form[name='review_hobbies_form'] input[name='hobbies_status'][value='1']").prop('checked', true);
		} else {
			$("form[name='review_hobbies_form'] input[name='hobbies_status'][value='0']").prop('checked', false);
		}
	});

	$('form[name="review_travel_form"] #review_travel_location').on('blur', function () {
		if ($('#review_travel_location').val() != '') {
			$("form[name='review_travel_form'] input[name='travel_status'][value='1']").prop('checked', true);
		} else {
			$("form[name='review_travel_form'] input[name='travel_status'][value='0']").prop('checked', false);
		}
	});

	//Provider
	$('form[name="provider_specialists_form"] #practices').on('change', function () {
		util.updatePhysicianList(parseInt($(this).val()), $("form[name='provider_specialists_form'] #provider_id"));
	});

	$('form[name="provider_pcp_form"] #practices').on('change', function () {
		util.updatePcpPhysicianList(parseInt($(this).val()), $("form[name='provider_pcp_form'] #provider_id")); //added by priya 25feb2021 for remove other option
	});

	$('form[name="provider_dentist_form"] #practices').on('change', function () {
		util.updatePhysicianList(parseInt($(this).val()), $("form[name='provider_dentist_form'] #provider_id"));
	});

	$('form[name="provider_vision_form"] #practices').on('change', function () {
		util.updatePhysicianList(parseInt($(this).val()), $("form[name='provider_vision_form'] #provider_id"));
	});

	//review Provider
	$('form[name="review_provider_pcp_form"] #practices').on('change', function () {
		util.updatePcpPhysicianList(parseInt($(this).val()), $("form[name='review_provider_pcp_form'] #provider_id")); //added by priya 25feb2021 for remove other option
	});

	$('form[name="review_provider_specialists_form"] #practices').on('change', function () {
		util.updatePhysicianList(parseInt($(this).val()), $("form[name='review_provider_specialists_form'] #provider_id"));
	});

	$('form[name="review_provider_dentist_form"] #practices').on('change', function () {
		util.updatePhysicianList(parseInt($(this).val()), $("form[name='review_provider_dentist_form'] #provider_id"));
	});

	$('form[name="review_provider_vision_form"] #practices').on('change', function () {
		util.updatePhysicianList(parseInt($(this).val()), $("form[name='review_provider_vision_form'] #provider_id"));
	});

	$("form[name='callstatus_form'] #voice_scripts_select").change(function () {
		util.getCallScriptsById($(this).val(), '.voice_mail_template', "form[name='callstatus_form'] input[name='template_type_id']", "form[name='callstatus_form'] input[name='content_title']");
	});

	$('#answer').change(function () {
		if (this.value == 3) {
			$("#txt-msg").show();
		} else {
			$("#txt-msg").hide();
		}
	});

	$("#diagnosis-codes-click_id").click(function () {
		$("form[name='diagnosis_code_form'] input[name='condition']").val("");
		$("form[name='diagnosis_code_form'] #diagnosis_code").val("");
		$("form[name='diagnosis_code_form'] #append_symptoms").html("");
		$("form[name='diagnosis_code_form'] #append_goals").html("");
		$('form[name="diagnosis_code_form"] #append_tasks').html("");
		$('form[name="diagnosis_code_form"] #support').val('');
		$('form[name="diagnosis_code_form"] #symptoms_0').val('');
		$("form[name='diagnosis_code_form'] #goals_0").val("");
		$("form[name='diagnosis_code_form'] #tasks_0").val("");
		$("form[name='diagnosis_code_form'] #support").val("");
		$("form[name='diagnosis_code_form'] textarea[name='comments']").val("");
		$("form[name='diagnosis_code_form'] textarea[name='comments']").text('');
		$("form[name='diagnosis_code_form'] input[name='id']").val('');
		$('#append_symptoms').html("");
		$('#append_goals').html("");
		$('#append_tasks').html("");
		$('#support').val('');
		$('#symptoms_0').val('');
		$('#goals_0').val('');
		$("#tasks_0").val('');
		$("input[name='comments']").val('');
		$("form[name='diagnosis_code_form'] .alert-success").hide();
		$("form[name='diagnosis_code_form'] .alert-danger").hide();
		inc_symptoms = 0;
		inc_goals = 0;
		inc_tasks = 0;
		$('.emaillist').removeClass("col-md-3").addClass("col-md-6");
		$(".otherlist").hide();
		renderDiagnosisTable();
	});

	$("#open_codes_info_for_medical").click(function () {
		$("form[name='review_diagnosis_code_form'] input[name='condition']").val("");
		$("form[name='review_diagnosis_code_form'] #diagnosis_code").val("");
		$("form[name='review_diagnosis_code_form'] #append_symptoms").html("");
		$("form[name='review_diagnosis_code_form'] #append_goals").html("");
		$('form[name="review_diagnosis_code_form"] #append_tasks').html("");
		$('form[name="review_diagnosis_code_form"] #support').val('');
		$('form[name="review_diagnosis_code_form"] #symptoms_0').val('');
		$("form[name='review_diagnosis_code_form'] #goals_0").val("");
		$("form[name='review_diagnosis_code_form'] #tasks_0").val("");
		$("form[name='review_diagnosis_code_form'] #support").val("");
		$("form[name='review_diagnosis_code_form'] textarea[name='comments']").val("");
		$("form[name='review_diagnosis_code_form'] textarea[name='comments']").text('');
		$("form[name='review_diagnosis_code_form'] input[name='id']").val('');
		$('#append_symptoms').html("");
		$('#append_goals').html("");
		$('#append_tasks').html("");
		$('#support').val('');
		$('#symptoms_0').val('');
		$('#goals_0').val('');
		$("#tasks_0").val('');
		$("input[name='comments']").val('');
		$("form[name='review_diagnosis_code_form'] .alert-success").hide();
		$("form[name='review_diagnosis_code_form'] .alert-danger").hide();
		inc_symptoms = 0;
		inc_goals = 0;
		inc_tasks = 0;
		$('.emaillist').removeClass("col-md-3").addClass("col-md-6");
		$('.otherlist').hide();
		renderDiagnosisTable();
	});

	$("#open_live_info").click(function () {
		$("form[name='review_do_you_live_with_anyone_form'] input[name='id']").val('');
		$("#review_live_fname_0").val('');
		$("#review_live_lname_0").val('');
		$("#review_live_relationship_0").val('');
		$("#review_live_additional_notes_0").val('');
		renderLiveWithTable();
	});

	$("#open_sibiling_info").click(function () {
		$("form[name='sibling_form'] input[name='tab_name']").val('sibling');
		$("form[name='sibling_form'] input[name='id']").val('');
		$("#review_sibling_fname").val('');
		$("#review_sibling_lname").val('');
		$("#review_sibling_age").val('');
		$("#review_sibling_address").val('');
		$("#review_sibling_additional_notes").val('');
		renderSiblingTable();
	});

	$("#open_children_info").click(function () {
		$("form[name='children_form'] input[name='tab_name']").val('children');
		$("form[name='children_form'] input[name='id']").val('');
		$("form[name='children_form'] #review_sibling_fname").val('');
		$("form[name='children_form'] #review_sibling_lname").val('');
		$("form[name='children_form'] #review_sibling_age").val('');
		$("form[name='children_form'] #review_sibling_address").val('');
		$("form[name='children_form'] #review_sibling_additional_notes").val('');
		renderChildrenTable();
	});

	$("#open_grandchildren_info").click(function () {
		$("form[name='grandchildren_form'] input[name='tab_name']").val('grandchildren');
		$("form[name='grandchildren_form'] input[name='id']").val('');
		$("form[name='grandchildren_form'] #review_sibling_fname").val('');
		$("form[name='grandchildren_form'] #review_sibling_lname").val('');
		$("form[name='grandchildren_form'] #review_sibling_age").val('');
		$("form[name='grandchildren_form'] #review_sibling_address").val('');
		$("form[name='grandchildren_form'] #review_sibling_additional_notes").val('');
		renderGrandchildrenTable();
	});

	$("#open_pets").click(function () {
		$("form[name='review_pets_form'] input[name='id']").val('');
		$("#review_pet_name").val('');
		$("#review_pet_type").val('');
		$("#review_pet_notes").val('');
		renderPetTable();
	});

	$("#open_hobbies").click(function () {
		$("form[name='review_hobbies_form'] input[name='id']").val('');
		$("#review_hobbie_description").val('');
		$("#review_hobbie_location").val('');
		$("#review_hobbie_frequency").val('');
		$('#review_hobbie_with_whom').val('');
		$('#review_hobbie_notes').val('');
		renderHobbiesTable();
	});

	$("#open_travel").click(function () {
		$("form[name='review_travel_form'] input[name='id']").val('');
		$("#review_travel_location").val('');
		$("#review_travel_travel_type").val('');
		$("#review_travel_frequency").val('');
		$('#review_travel_with_whom').val('');
		$('#review_travel_upcoming_tips').val('');
		$('#review_travel_notes').val('');
		renderTravelTable();
	});

	$("#open_medications").click(function () {
		$("#review_medications_form")[0].reset();
		$("#medications_form")[0].reset();
		renderMedicationsTable();
		// renderMedicationsReviewTable();
	});
};

window.carePlanDevelopment = {
	init: init,
	onResult: onResult,

	renderOtherProviderSpecialistTable: renderOtherProviderSpecialistTable,
	renderdrugTableData: renderdrugTableData,
	renderFoodTableData: renderFoodTableData,
	renderEnviromentalTableData: renderEnviromentalTableData,
	renderinsectTableData: renderinsectTableData,
	renderLatexTableData: renderLatexTableData,
	renderPetRelatedTableData: renderPetRelatedTableData,
	renderAllergyOtherTableData: renderAllergyOtherTableData,
	renderDMEServicesTableData: renderDMEServicesTableData,
	renderHomeHealthServicesTableData: renderHomeHealthServicesTableData,
	renderDialysiServicesTableData: renderDialysiServicesTableData,
	renderTherapyServicesTableData: renderTherapyServicesTableData,
	renderSocialServicesTableData: renderSocialServicesTableData,
	renderMedicalSuppliesServicesTableData: renderMedicalSuppliesServicesTableData,
	renderOtherHealthServicesTableData: renderOtherHealthServicesTableData,
	renderMedicationsTableData: renderMedicationsTableData,
	renderLabsTable: renderLabsTable,
	//renderImagingTable: renderImagingTable,
	renderVitalTable: renderVitalTable,
	renderDiagnosisTableData: renderDiagnosisTableData,

	getLabParamsOnLabChange: getLabParamsOnLabChange,
	onChangeNumberTrackingVitalsWeightOrHeight: onChangeNumberTrackingVitalsWeightOrHeight,

	onAllergy: onAllergy,
	// onDrugAllergy: onDrugAllergy,
	// onFoodAllergy: onFoodAllergy,
	// onEnviromentalAllergy: onEnviromentalAllergy,
	// onInsectAllergy: onInsectAllergy,
	// onLatexAllergy: onLatexAllergy,
	// onPetRelatedAllergy: onPetRelatedAllergy,
	// onOtherAllergy: onOtherAllergy,

	onMedication: onMedication,

	editlabsformnew: editlabsformnew,
	deleteLabs: deleteLabs,
	deleteMedications: deleteMedications,
	editMedications: editMedications,
	selectMedicationOther: selectMedicationOther,
	deleteAllergies: deleteAllergies,
	editAllergy: editAllergy,
	editService: editService,
	deleteServices: deleteServices,
	onDiagnosis: onDiagnosis,
	editPatientDignosis: editPatientDignosis,
	deletePatientDignosis: deletePatientDignosis,
	changeCondition: changeCondition,
	changeCode: changeCode,
	additionalgoals: additionalgoals,
	additionaltasks: additionaltasks,
	additionalsymptoms: additionalsymptoms,

	onServices: onServices,
	// onDialysisServices: onDialysisServices,
	// onSocialServices: onSocialServices,
	// onHomeHealthServices: onHomeHealthServices,
	// onDmeServices: onDmeServices,
	// onMedicalSuppliesServices: onMedicalSuppliesServices,
	// onOtherServices: onOtherServices,
	// onTherapyServices: onTherapyServices,

	onNumberTrackingVital: onNumberTrackingVital,
	onNumberTrackingLab: onNumberTrackingLab,
	onNumberTrackingImaging: onNumberTrackingImaging,
	onNumberTrackingHealthdata: onNumberTrackingHealthdata,
	additionalimaging: additionalimaging,
	additionalhealthdata: additionalhealthdata,

	onPatientRelationshipData: onPatientRelationshipData,
	// onReviewLiveWith: onReviewLiveWith,
	// onReviewGrandchildren: onReviewGrandchildren,
	// onReviewChildren: onReviewChildren,
	// onReviewSibling: onReviewSibling,

	onFamilyData: onFamilyData,
	// onReviewCareGiverFamily: onReviewCareGiverFamily,
	// onReviewEmergencyContactFamily: onReviewEmergencyContactFamily,
	// onReviewSpouseFamily: onReviewSpouseFamily,
	// onReviewPatientDataFamily: onReviewPatientDataFamily,

	editSiblingData: editSiblingData,
	deleteSiblingData: deleteSiblingData,
	editSpecialistProviderPatient: editSpecialistProviderPatient,
	deleteSpecialistProviderPatient: deleteSpecialistProviderPatient,
	onProviderChange: onProviderChange,
	onPracticeChange: onPracticeChange,
	editHobbiesData: editHobbiesData,
	deleteHobbiesData: deleteHobbiesData,
	editPetData: editPetData,
	deletePetData: deletePetData,
	editTravelData: editTravelData,
	deleteTraveltData: deleteTraveltData,
	enableDiagnosisbutton: enableDiagnosisbutton,
	disableDiagnosisbutton:disableDiagnosisbutton,
	clearConditionForm: clearConditionForm,
    copyPreviousMonthDataToThisMonth:copyPreviousMonthDataToThisMonth,
	callCPDInitFunctions: callCPDInitFunctions

	// onReviewHobies: onReviewHobies,
	// onReviewpet: onReviewpet,
	// onReviewTravel: onReviewTravel
};