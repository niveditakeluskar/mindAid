{{ csrf_field() }}
<!-- start Solid Bar -->
<style>
    .patientstatusscrolldiv {
        /* height:500px; */
        overflow: auto;
        overflow-x: hidden;
        margin-right: 5%;
    }
</style>
<div class="patientstatusscrolldiv">
    <div class="col-lg-12 mb-1">
        <label class=" "><strong>Patient UID:</strong></label>
        <span><?php if (isset($patient) && $patient != "") {
                    echo $patient[0]->id;
                } ?></span>
    </div>
    <div class="col-lg-12 mb-1">
        <label class=" "><strong>Practice Documents:</strong></label>
        <span>
            <?php
            $i = 0;
            $docCount = count($documents);
            foreach ($documents as $index => $docs) {
                $i++;
                if (isset($docs->doc_content) && $docs->doc_content != "") {
                    $content = $docs->doc_content;
            ?>
                    <a target="_blank" href="<?php echo asset('practice-provider-documents/') . '/' . $content ?>">
                        <!-- <a href="/practice-provider-documents/<?php //secho $docs->doc_content; 
                                                                    ?>" target="_blank"> -->
                        <?php
                        echo ($docs->doc_content);
                        echo ($docCount === $i) ? '.' : ', ';
                        ?>
                    </a>
            <?php
                }
            }
            ?>
        </span>
    </div>
    <div class="col-lg-12 mb-1">
        <label class=" "><strong>Previous Month Total Time Spent:</strong></label>
        <span><?php if (isset($previousEllapsedTime) && $previousEllapsedTime != "") {
                    echo $previousEllapsedTime;
                } ?></span>
    </div>
    <div class="col-lg-12 mb-1">
        <label class=" "><strong>Current Month Total Time Elapsed:</strong></label>
        <span class="last_time_spend"><?php if (isset($currentEllapsedTime) && $currentEllapsedTime != "") {
                                            echo $currentEllapsedTime;
                                        } ?></span>
    </div>
    <div class="col-lg-12 mb-1">
        <label class=" "><strong>Non Billable time:</strong></label>
        <span class="non_billabel_last_time_spend"><?php if (isset($non_billable_time[0]->totaltime) && $non_billable_time[0]->totaltime != "") {
                                                        echo $non_billable_time[0]->totaltime;
                                                    } ?></span>
    </div>
    <div class="col-lg-12 mb-1">
        <label class=" "><strong>Date Care Plan Finalized:</strong></label>
        <span class="finalized_date">
            <?php
            if (isset($patientdiagnosislastmodified->updated_at)) {
                $d = str_replace("-", "/", $patientdiagnosislastmodified->updated_at);
                echo date('F, d Y', strtotime(date("Y-m-d H:i:s", strtotime($d)))) . " ";
            } else {
                echo "00:00:00" . " ";
            }
            ?>
        </span>
        <label class=" "><strong>Care Plan Last Modified By:</strong></label>
        <span>
            <?php
            if (isset($patientdiagnosislastmodified['users_created_by']->f_name)) {
                echo $patientdiagnosislastmodified['users_created_by']->f_name . "  " . $patientdiagnosislastmodified['users_created_by']->l_name;
            } else {
                echo " ";
            }
            ?>
        </span>
    </div>
    <?php if (isset($chronicCondition) && $chronicCondition != "") { ?>
        <div class="col-lg-12 mb-1 table-responsive">
            <table class="display table table-striped table-bordered" style="width:100%; border: 1px solid #00000029;">
                <thead>
                    <tr>
                        <th>Condition</th>
                        <th style="width: 60px;">Code</th>
                        <th>Updated on</th>
                        <!-- <th>Review Date</th>   -->
                        <th>Updated by</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($chronicCondition as $chr) { ?>
                        <tr>
                            <td><?php echo $chr->condition; ?></td>
                            <td><?php echo $chr->code; ?></td>
                            <td><?php echo $chr->update_date; ?></td>
                            <!-- <td>?php echo $chr->review_date; ?></td> -->
                            <td><?php echo $chr->userfname . "  " . $chr->userlname ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
    <div class="col-lg-12 mb-1">
        <label class=" mb-1"><strong>RPM Device(s):</strong></label>
        <span>
            <?php
            if (isset($patient_assign_device) && $patient_assign_device != " ") {
                echo ($patient_assign_device);
            }
            ?>
        </span>
    </div>
    <div class="col-lg-12 mb-1">
        <label class=" mb-1"><strong>Date Device Education Completed:</strong></label>
        <span>
            <?php
            if (isset($device_education_training) && $device_education_training != "" && $device_education_training != NULL) {
                echo ($device_education_training);
            }
            ?>
        </span>
    </div>
    <div class="col-lg-12 mb-1">
        <label class=" mb-1"><strong>Medications / Vaccines:</strong></label>
        <span>
            <?php if (isset($medication) && $medication != "") { ?>
                <table>
                    <tr>
                        <?php
                        $i = 1;
                        $j = 1;
                        $k = 0;
                        foreach ($medication as $medication) {
                            $t = $j * 4;
                            if ($i == $t - $k) {
                                $j++;
                                $k++;
                        ?>
                    </tr>
                    <tr>
                    <?php } ?>
                    <td>
                        <?php
                            echo "<li><span>" . $medication->name . ' (' . $medication->dosage . ' ' . $medication->frequency . ')' . "</span></li>";
                        ?>
                    </td>
                <?php $i++;
                        } ?>
                </table>
            <?php } ?>
        </span>
    </div>
    <div class="col-lg-12 mb-1">
        <label class=" "><strong>Date of Last Contact:</strong></label>
        <?php
        if (isset($lastContactDate) && $lastContactDate != "") {
            if (isset($lastContactDate[0]->rec_date)) {
                $d = str_replace("-", "/", $lastContactDate[0]->rec_date);
                echo date('F, d Y', strtotime(date("Y-m-d H:i:s", strtotime($d))));
            }
        }
        ?>
    </div>
    <div class="col-lg-12 mb-1">
        <label class=" "><strong>Personal Notes:</strong></label>
        <span>
            <?php
            if (isset($personal_notes['static']['personal_notes'])) {
                echo $personal_notes['static']['personal_notes'];
            }
            ?>
            <a href="javascript:void(0)" type="button" class="btn btn-info btn-sm" style="background-color:#27a7de;border:none;" id="view-more-personal-notes">View More</a>
        </span>
    </div>
    <div class="view_personal_notes" style="display:none">
        <?php if (isset($all_personal_notes) && $all_personal_notes != "") { ?>
            <div class="col-lg-12 mb-1 table-responsive">
                <table class="display table table-striped table-bordered" style="width:100%; border: 1px solid #00000029;">
                    <thead>
                        <tr>
                            <th>Sr.No.</th>
                            <th>Description</th>
                            <th>Record Date</th>
                            <th>Last Modified By</th>
                            <th>Last Modified On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $m = 0; ?>
                        <?php foreach ($all_personal_notes as $notes) { ?>
                            <tr>
                                <td class="personal_notes_<?php echo $m ?>"><?php echo $m; ?></td>
                                <td class="personal_notes_<?php echo $m ?>"><?php echo $notes->personal_notes; ?></td>
                                <td class="personal_notes_<?php echo $m ?>"><?php echo $notes->rec_date; ?></td>
                                <td class="personal_notes_<?php echo $m ?>"><?php echo $notes->update_date; ?></td>
                                <td class="personal_notes_<?php echo $m ?>"><?php echo $notes->userfname . "  " . $notes->userlname ?></td>
                            </tr>
                        <?php $m++;
                        } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
    </div>
    <div class="col-lg-12 mb-1">
        <label class=" "><strong>Part of Research Study:</strong></label>
        <span>
            <?php
            if (isset($research_study['static']['part_of_research_study'])) {
                echo $research_study['static']['part_of_research_study'];
            }
            ?>
            <a href="javascript:void(0)" type="button" class="btn btn-info btn-sm" style="background-color:#27a7de;border:none;" id="view-more-part-of-research-study">View More</a>
        </span>
    </div>
    <div class="view_more_research_study" style="display:none;">
        <?php if (isset($all_research_study) && $all_research_study != "") { ?>
            <div class="col-lg-12 mb-1 table-responsive">
                <table class="display table table-striped table-bordered" style="width:100%; border: 1px solid #00000029;">
                    <thead>
                        <tr>
                            <th>Sr.No.</th>
                            <th>Description</th>
                            <th>Record Date</th>
                            <th>Last Modified By</th>
                            <th>Last Modified On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $n = 0; ?>
                        <?php foreach ($all_research_study as $research_study) { ?>
                            <tr>
                                <td class="research_study_<?php echo $n ?>"><?php echo $n; ?></td>
                                <td class="research_study_<?php echo $n ?>"><?php echo $research_study->part_of_research_study; ?></td>
                                <td class="research_study_<?php echo $n ?>"><?php echo $research_study->rec_date; ?></td>
                                <td class="research_study_<?php echo $n ?>"><?php echo $research_study->update_date; ?></td>
                                <td class="research_study_<?php echo $n ?>"><?php echo $research_study->userfname . "  " . $research_study->userlname ?></td>
                            </tr>
                        <?php $n++;
                        } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
    </div>
    <div class="col-lg-12 mb-1">
        <?php
        if (isset($questionnaire_status->template_id) && $questionnaire_status->template_id != "") {
            echo "<strong> Social Determinate of Health (SODH) Questionnaire </strong><br>";
            $patient_questionnaire = json_decode($questionnaire_status->template);
            $no = 1;
            foreach ($patient_questionnaire as $key => $value) { ?>
                <label class=" "><?php echo $no . '. '; ?> {{str_replace('_', ' ', $key)}}: {{$value}}</label><br>
        <?php $no++;
            }
            echo "<label>Current Monthly Notes: " . $questionnaire_status->monthly_notes . "</label>";
        }
        ?>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.ps__rail-y').hide();
        $("#view-more-personal-notes").click(function() {
            var check = $('.view_personal_notes').is(":visible"); // The same works with show
            // $('.view_personal_notes').is(":hidden");   // The same works with hidden
            if (check == false) {
                $(".view_personal_notes").css("display", "block");
            } else {
                $(".view_personal_notes").css("display", "none");
            }

            $('.personal_notes_0').hide();
        });
        $("#view-more-part-of-research-study").click(function() {
            var check = $('.view_more_research_study').is(":visible"); // The same works with show
            if (check == false) {
                $(".view_more_research_study").css("display", "block");
            } else {
                $(".view_more_research_study").css("display", "none");
            }
            $('.research_study_0').hide();
        });
    });
</script>