<div class="col-md-4 form-group mb-3">
    <label for="height">Height (in)<!-- <span class="error">*</span> --> :</label>
    @text("height", ["id" => "height", "onchange"=>"carePlanDevelopment.onChangeNumberTrackingVitalsWeightOrHeight();"])
</div>
<div class="col-md-4 form-group mb-3">
    <label for="weight">Weight (lbs)<!-- <span class="error">*</span> --> :</label>
    @text("weight", ["id" => "weight", "onchange"=>"carePlanDevelopment.onChangeNumberTrackingVitalsWeightOrHeight();"])
</div>
<div class="col-md-4 form-group mb-3">
    <label for="bmi">BMI<!-- <span class="error">*</span> --> :</label>
    @text("bmi", ["id" => "bmi","readonly"=>"readonly"])
</div>
<div class="col-md-4 form-group mb-3">
    <label for="bp">Blood Pressure<!-- <span class="error">*</span> --> :</label>
    <div class="form-row col-md-12 form-group">
        <span class="col-md-5">
            @text("bp", ["id" => "bp","placeholder"=>"Systolic"])
        </span>
        <span class="mt-1 pl-2 pr-2"> / </span>
        <span class="col-md-6">
            @text("diastolic", ["id" => "diastolic","placeholder"=>"Diastolic"])
        </span>
    </div>
</div>
<div class="col-md-4 form-group mb-3">
    <label for="o2">O2 Saturation<!-- <span class="error">*</span> --> :</label>
    @text("o2", ["id" => "o2"])
</div>
<div class="col-md-2 form-group mb-3">
    <label for="pulse_rate">Pulse Rate<!-- <span class="error">*</span>  -->:</label>
    @text("pulse_rate", ["id" => "pulse_rate"])
</div>
<div class="col-md-2 form-group mb-3">
    <label for="pain_level">Pain Level<!-- <span class="error">*</span>  -->:</label>
    <select name="pain_level" id="pain_level" class="custom-select show-tick">
        <option value="0">0</option>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
        <option value="6">6</option>
        <option value="7">7</option>
        <option value="8">8</option>
        <option value="9">9</option>
        <option value="10">10</option>
    </select>
</div>
<div class="col-md-12 form-group mb-3">
    <div class="mr-3 d-inline-flex align-self-center">
        <label class="radio radio-primary mr-3">
            <input type="radio" id="yes" name="oxygen" value="1" formControlName="radio">
            <span>Room Air</span>
            <span class="checkmark"></span>
        </label>
        <label class="radio radio-primary mr-3">
            <input type="radio" id="no" name="oxygen" value="0" formControlName="radio">
            <span>Supplemental Oxygen</span>
            <span class="checkmark"></span>
        </label>
    </div>
</div>
<div class="col-md-12 mr-3 mb-3" id="Supplemental_notes_div" style="display:none">
    <label>Notes</label>
    <textarea class="form-control forms-element" name="notes"></textarea>
    <div class="invalid-feedback"></div>
</div>
<script>
    function editVitals(id) {
        var time = document.getElementById("page_landing_times").value;
        $(".timearr").val(time);
        url = `/ccm/get-patient-vital-by-id/${id}/patient-vital`;
        $("#preloader").css("display", "block");
        $.get(url, id,
            function(result) {
                $("#preloader").css("display", "none");
                for (var key in result) {
                    if (key == 'number_tracking_vitals_form') {
                        if (result[key].static['id'] != null) {
                            var height = (result[key].static['height']);
                            $("#number_tracking_vitals_form [name='height']").val(height);
                            var weight = (result[key].static['weight']);
                            $("#number_tracking_vitals_form [name='weight']").val(weight);
                            var bmi = (result[key].static['bmi']);
                            $("#number_tracking_vitals_form [name='bmi']").val(bmi);
                            var bp = (result[key].static['bp']);
                            $("#number_tracking_vitals_form [name='bp']").val(bp);
                            var o2 = (result[key].static['o2']);
                            $("#number_tracking_vitals_form [name='o2']").val(o2);
                            var pulse_rate = (result[key].static['pulse_rate']);
                            $("#number_tracking_vitals_form [name='pulse_rate']").val(pulse_rate);
                            var pain_level = (result[key].static['pain_level']);
                            $("#number_tracking_vitals_form [name='pain_level']").val(pain_level);
                            var oxygen = (result[key].static['oxygen']);
                            if (oxygen == 1) {
                                $("#number_tracking_vitals_form #yes").prop("checked", true);
                            } else if (oxygen == 0) {
                                $("#number_tracking_vitals_form #no").prop("checked", true);
                                var notes = (result[key].static['notes']);
                                $("#number_tracking_vitals_form [name='notes']").val(notes);
                            }
                            var id = result[key].static['id'];
                            $("#number_tracking_vitals_form [name='id']").val(id);
                        }
                    }
                }
                var mainContent = $(".main-content");
                if (mainContent.length) {
                    var scrollPos = mainContent.offset().top;
                    if (scrollPos > 0) {
                        $(window).scrollTop(scrollPos);
                    } else {
                        console.log("Element has no top offset");
                    }
                } else {
                    console.log("Element not found");
                }
            }
        ).fail(function(result) {
            console.error("Population Error:", result);
            $("#preloader").css("display", "none");
        });
    }

    function deleteVitals(id) {
        if (
            window.confirm("Are you sure you want to delete this Vitals?")
        ) {
            var patient_id = $("#number_tracking_vitals_form [name='patient_id']").val();
            var module_id = $("#number_tracking_vitals_form [name='module_id']").val();
            const data = {
                id: id,
                patientid: patient_id,
                module_id: module_id,
                component_id: $("#number_tracking_vitals_form [name='component_id']").val(),
                start_time: $("#number_tracking_vitals_form [name='start_time']").val(),
                end_time: $("#number_tracking_vitals_form [name='end_time']").val(),
                stage_id: $("#number_tracking_vitals_form [name='stage_id']").val(),
                step_id: $("#number_tracking_vitals_form [name='step_id']").val(),
                form_name: $("#number_tracking_vitals_form [name='form_name']").val(),
                billable: $("#number_tracking_vitals_form [name='billable']").val(),
                "timearr[form_start_time]": $("#number_tracking_vitals_form [name='timearr[form_start_time]']").val(),
            };
            $.ajax({
                type: 'POST',
                url: "/ccm/delete-patient-vital-by-id",
                data: data,
                success: function(response) {
                    console.log("response", response);
                    // window.carePlanDevelopment.onNumberTrackingImaging(response);
                    window.util.getPatientCareplanNotes(patient_id, module_id);
                    util.updateTimer($("input[name='patient_id']").val(), $("input[name='billable']").val(), $("input[name='module_id']").val());
                    // window.carePlanDevelopment.CompletedCheck();
                    window.carePlanDevelopment.renderVitalTable();
                    var scrollPos = $(".main-content").offset().top;
                    $(window).scrollTop(scrollPos);
                    setTimeout(function() {
                        $('.alert').fadeOut('fast');
                    }, 3000);
                    var timer_paused = $("form[name='number_tracking_vitals_form'] input[name='end_time']").val();
                    $("#timer_start").val(timer_paused);
                    $(".form_start_time").val(response.form_start_time);
                    $("form[name='number_tracking_vitals_form']")[0].reset();
                    $("#append_imaging").html("");
                }
            });
        }
    }
</script>