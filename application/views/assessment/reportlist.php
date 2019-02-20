<?php
/* Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
    This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
    LICENSE file in the root directory of this source tree.*/
?>

<?php
$isSchoolAssessment = $group_assessment_id == 0 ? true : false;
$isSubAssessment = $assessment_id > 0 && $group_assessment_id > 0 ? true : false;
$isGroupAssessment = $assessment_id == 0 ? true : false;
$isSelfReview = !empty($assessment['subAssessmentType']) && $assessment['subAssessmentType'] == 1 ? 1 : '0';
$allow_publish = 1;
$isAdmin = 0;
if (!empty($user))
    $isAdmin = in_array("create_assessment", $user['capabilities']);
if ($isSubAssessment) {
    $teacherExtPerc = explode(',', $assessment['perc']);
    $teacherExtPerc = $teacherExtPerc[1];
    $teacherIntPerc = $teacherExtPerc[0];
}
?>
<h1 class="page-title">
    Reports for <?php echo $isSubAssessment ? $assessment['usernameByRole'][3] . ' - ' : '';
echo $assessment['client_name']; ?>
</h1>
<div class="clr"></div>
<div id="reportsListWrapper" data-assessmentorgroupassessmentid="<?php echo $isGroupAssessment ? $group_assessment_id : $assessment_id; ?>" data-assesmenttypeid="<?php echo $assessment['assessment_type_id']; ?>" class="">
    <div class="ylwRibbonHldr">
        <div class="tabitemsHldr"></div>
    </div>
    <div class="subTabWorkspace pad26">	
        <?php
        if (count($reports) == 0) {
            ?>
            <h3>No reports found</h3>
            <?php } else if ($isSchoolAssessment && (($assessment['statusByRole'][3] != 1 && $assessment['subAssessmentType'] == 1) && ($assessment['statusByRole'][4] != 1))) {
            ?>
            <h3>School Profile or External review form still not submitted</h3>
            <?php } else if ($isSubAssessment && (($assessment['statusByRole'][4] != 1 || $teacherExtPerc != '100') && $assessment['isTchrInfoFilled'] != 1)) {
            ?>
            <h3>Teacher/Student info form or External review form still not submitted</h3>
            <?php } else if ($assessment['assessment_type_id'] == 4 && isset($assessment['isTchrInfoFilled']) && $assessment['isTchrInfoFilled'] != 1) {
            ?>
            <h3>Student info form still not submitted</h3>
            <?php
        } else {
            $isPublished = $reports[0]['isPublished'];
            ?>

            <?php
            if ($isSchoolAssessment && (($assessment['statusByRole'][3] != 1 && $assessment['subAssessmentType'] == 1) && ($assessment['statusByRole'][4] != 1))) {
                $allow_publish = 0;
            }
            ?>
            <?php if ($isPublished != 1) { ?>
                <div class="row report_row pb10">
                    <div class="col-sm-3">
                        <b>Report Validity: </b>
                    </div>
                    <div class="col-sm-2">
                        <select class="valid_years">
                            <option value="0">0</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select> <b>Years</b>
                    </div>
                    <div class="col-sm-2">
                        <select class="valid_months">
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
                            <option value="11">11</option>
                        </select> <b>Months</b>
                    </div>
                    <?php
                    if ($isSchoolAssessment && $assessment_id && $assessment['assessment_type_id'] == 1) {
                        ?>
                        <div class="col-sm-2">
                            <b>Language:</b>
                        </div>
                        <div class="col-sm-3">
                            <select name="lang" id="lang_id">
                                <option value="">Select Language</option>

                                <?php foreach ($diagnosticsLanguage as $data) { ?>
                                    <option value="<?php echo $data['language_id']; ?>"><?php echo $data['language_words']; ?></option>

                                <?php } ?>
                            </select>
                        </div>

                        <?php
                    }
                    ?>

                </div>
            <?php } ?>


            <?php
            if ($isPublished == 1 && $assessment['assessment_type_id'] == 1) {
                ?>
                <div class="row report_row pb10">
                    <div class="col-sm-2">
                        <b>Language:</b>
                    </div>
                    <div class="col-sm-3">
                        <select name="lang" id="lang_id">
                            <option value="">Select Language</option>

                            <?php foreach ($diagnosticsLanguage as $data) { ?>
                                <option value="<?php echo $data['language_id']; ?>"><?php echo $data['language_words']; ?></option>

                            <?php } ?>
                        </select>
                    </div>
                </div>
                <?php
            }
            ?>
            <div id="reportsList" class="form-stmnt">
                <?php
                    foreach ($reports as $report) {
                        if ($report['report_id'] == 8 || $report['report_id'] == 10)
                            continue;
                        $aid = $assessment_id == 0 ? $report['assessment_id'] : $assessment_id;
                        $subAssessmentType = !empty($assessment['subAssessmentType']) ? $assessment['subAssessmentType'] : 0;
                        if (($isSchoolAssessment && $subAssessmentType == 1 && $report['report_id'] != 1) || ($isSchoolAssessment && $report['report_id'] == 3 && $numKpas < 2) || ($isSchoolAssessment && !empty($user) && in_array("take_external_assessment", $user['capabilities']) && !(in_array("view_published_own_school_reports", $user['capabilities'])) && in_array($report['report_id'], array(3))))
//show aqs report card only in case of self review
                            continue;
                        $dId = empty($report['diagnostic_id']) ? 0 : $report['diagnostic_id'];
                        if ($report['report_id'] == 1) {
                            $url = createUrl(array("controller" => "customreport", "action" => "generateAqsReport", "assessment_id" => $aid, "report_id" => $report['report_id'], "group_assessment_id" => $report['group_assessment_id'], "diagnostic_id" => $dId, "assessor_id" => $assessor_id));
                            $url_compar_round = createUrl(array("controller" => "report", "action" => "reportRound2", "assessment_id" => $aid, "report_id" => $report['report_id'], "group_assessment_id" => $report['group_assessment_id'], "diagnostic_id" => $assessment['diagnostic_id'], "client_id" => $assessment['client_id']));
                        } else if ($report['report_id'] == 2 || $report['report_id'] == 6) {

                            $url = createUrl(array("controller" => "pdf", "action" => "pdf", "assessment_id" => $aid, "report_id" => $report['report_id'], "group_assessment_id" => $report['group_assessment_id'], "diagnostic_id" => $dId, "assessor_id" => $assessor_id));

                            $url_compar_round = createUrl(array("controller" => "report", "action" => "reportRound2", "assessment_id" => $aid, "report_id" => $report['report_id'], "group_assessment_id" => $report['group_assessment_id'], "diagnostic_id" => $assessment['diagnostic_id'], "client_id" => $assessment['client_id']));
                        } else {
                            $url = createUrl(array("controller" => "report", "action" => "report", "assessment_id" => $aid, "report_id" => $report['report_id'], "group_assessment_id" => $report['group_assessment_id'], "diagnostic_id" => $dId, "assessor_id" => $assessor_id));

                            $url_compar_round = createUrl(array("controller" => "report", "action" => "reportRound2", "assessment_id" => $aid, "report_id" => $report['report_id'], "group_assessment_id" => $report['group_assessment_id'], "diagnostic_id" => $assessment['diagnostic_id'], "client_id" => $assessment['client_id']));
                        }
                        ?>
                        <div class="row report_row pb10 pt10" id="report_row_<?php echo $report['report_id']; ?>">

                                <div class="col-sm-10">
                                <?php print strtolower($report['report_name']) == 'aqs report' ? 'AQS Report Card' : $report['report_name'] . ($assessment_id == 0 && $report['group_assessment_id'] == 0 ? ' - ' . $report['user_names'][0] : ''); ?>
                                </div>
            <?php if ($isPublished == 1) { ?>
                                <div class="col-sm-2">
                                    <a target="_blank" class="btn btn-primary" id="aqsReportView" rel="<?php echo $url; ?>" data-reportid="<?php echo $report['report_id']; ?>" href="#">View Report</a>
                                </div>
                            <?php } else {
                                ?>

                                <div class="col-sm-2">

                                    <input type="button" data-url="<?php echo $url; ?>" class="btn btn-secondary form-control generate_report school-report" data-reportid="<?php echo $report['report_id']; ?>" value="Generate Report" />
                                </div>

                            <?php } ?>

                        </div>

                        <?php
                    }
                ?>
            </div>

            <?php
            if ($assessment['aqs_status'] != 1 && $allow_publish == 0) {
                echo"<br><div style='width:100%;text-align:left;'>Note: AQS Profile Data is Incomplete.</div>";
            }
            ?>
            <?php if (!$isSelfReview && !empty($user) && in_array("generate_submitted_asmt_reports", $user['capabilities'])) { ?>	
                <?php if ($isPublished != 1 && ($assessment_id > 0 || ($assessment['allStatusFilled'] && $assessment['allTchrInfoFilled']))) { ?>
                    <?php if ($allow_publish && ($isSchoolAssessment || $isGroupAssessment)) { ?>
                        <div class="row report_row pb10 pt10">
                            <div class="col-sm-10">
                                <?php if ($isSchoolAssessment && $assessment['isAssessorKeyNotesApproved'] != 1 && $numKpas > 1) { ?>
                                    <div style="width: auto;" class="chkHldr"><input type="checkbox" id="keyNotesAccepted"><label class="chkF checkbox"><span>I approve all the assessor key recommendations</span></label></div>
                                <?php } else { ?>
                                    &nbsp;
                                <?php } ?>
                            </div>
                            <div class="col-sm-2">
                                <input type="button" class="btn btn-primary form-control publish_report vtip" title="Click to publish all reports." value="Publish" />
                            </div>
                        </div>
                        <div class="row pt10">
                            <div class="col-sm-12">
                                <b>NOTE:-</b> <i>Once you publish the reports you would not be able to edit anything (including review and assessor keynotes).</i>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else if ($isPublished == 1) { ?>
                    <div class="row pt20">
                        <div class="col-sm-9">
                            <b>Published on:</b> <?php echo substr($reports[0]['publishDate'], 0, 7); ?>
                        </div>
                        <div class="col-sm-3">
                            <b>Valid till:</b> <?php echo substr($reports[0]['valid_until'], 0, 7); ?>
                        </div>
                    </div>
                <?php } ?>

            <?php } ?>	
        <?php } ?>
    </div>
</div>