<?php
/* Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
    This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
    LICENSE file in the root directory of this source tree.*/
?>

<?php
$externalClient = explode(',', $assessment['user_ids']);
$externalClient = end($externalClient);
$externalClientKpa = isset($assignedKpas[$externalClient]) ? $assignedKpas[$externalClient] : array();
$allAssessors = array($externalClient);
$subRolesT = $assessment['subroles'];
$subRolesT = !empty($subRolesT) ? explode(',', $subRolesT) : '';
$i = 1;
if (!empty($subRolesT))
    foreach ($subRolesT as $subRoleT) {
        $i++;
        $subRoleT = explode('_', $subRoleT);
        $memberT = $subRoleT[1];
        array_push($allAssessors, $memberT);
    }
$hideDiagnostics = explode(',', $hideDiagnostics['hidediagnostics']);
$externalRevPerc = null;
$internalRevPerc = null;
$rev = explode(',', $assessment['percCompletes']);
if (count($rev) > 1) {
    $externalRevPerc = $rev[1];
    $internalRevPerc = $rev[0];
} else
    $externalRevPerc = $internalRevPerc = $assessment['percCompletes'];
$isEditable = ($disabled !== 0) ? $disabled : ( ($externalRevPerc > 0) ? 'disabled' : '');

if (count($reviewNotifications)) {
    $notifications = $reviewNotifications;
}
$active = (isset($step2) && $step2 == 1) ? 1 : 0;
$numKpas = 0;
$assessor_id = '';
?>
<?php if (empty($editStatus)) { ?>
    <a id="addUserBtn" class="btn btn-primary pull-right execUrl vtip fixonmodal" title="Click to add a user." href="?controller=user&action=createUser&ispop=1">Add user</a>
<?php } ?>
<h1 class="page-title">
    <?php if ($isPop == 0) { ?>
        <a href="<?php
        $args = array("controller" => "assessment", "action" => "assessment");
        $args["filter"] = 1;
        echo createUrl($args);
        ?>">
            <i class="fa fa-chevron-circle-left vtip" title="Back"></i>
            Manage MyReviews
        </a> &mdash;
    <?php } ?>	
    Edit Review - <?php echo $assessment['client_name']; ?>
</h1>
<div class="clr"></div>

<div class="">

    <div class="ylwRibbonHldr">
        <?php if (empty($editStatus)) { ?>
        <?php } ?>
        <div class="tabitemsHldr pad0 clearfix" >            
            <?php if (isset($review_type) && $review_type == 1) { ?>
                <ul class="yellowTab nav nav-tabs ">          
                    <li class="item <?php echo ($active == 0) ? 'active' : ''; ?>" id="collaborative-step1"><a href="#ctreateSchoolAssessment-step1" data-toggle="tab" class="vtip" title="Create School Review">Step 1</a></li>
                    <li class="item edit-assessment <?php echo ($active == 1) ? 'active' : ''; ?>" id="collaborative-step2" style=" display: <?php echo(isset($review_type) & $review_type == 1) ? '' : 'none'; ?> "><a href="#ctreateSchoolAssessment-step2" data-toggle="tab" class="vtip" title="Add Collaborative details" id="step2" onclick="getStep2('<?php echo $assessment_id; ?>', '<?php echo $editStatus; ?>')" >Step 2</a></li>				
                </ul>
            <?php } ?>
        </div>
    </div>

    <div id="ctreateSchoolAssessment-step1" role="tabpanel" class="tab-pane fade in" style=" display: <?php echo (isset($step2) && $step2 == 1) ? 'none' : 'block'; ?>">
        <div class="subTabWorkspace pad26">
            <form method="post" id="edit_school_assessment_form" action="">
                <div class="form-stmnt">

                    <input type="hidden" name="client_id" value="<?php echo $assessment['client_id']; ?>" />
                    <div class="boxBody">

                        <dl class="fldList">
                            <dt>Review Type<span class="astric">*</span>:</dt>
                            <dd><div class="row"><div class="col-sm-6">
                                        <select class="form-control " name="review_type" id="review_type" required disabled readonly >
                                            <option value=""> - Select School - </option>
                                            <option value="0" <?php echo (isset($assessment['iscollebrative']) && $assessment['iscollebrative'] == 0 ? 'selected="selected"' : '') ?> >Validated School Review </option>
                                            <option value="1" <?php echo (isset($assessment['iscollebrative']) && $assessment['iscollebrative'] == 1 ? 'selected="selected"' : '') ?>>Collaborative School Review</option>

                                        </select>
                                    </div></div></dd>
                        </dl>
                        <dl class="fldList">
                            <dt>School<span class="astric">*</span>:</dt>
                            <dd><div class="row"><div class="col-sm-6">
                                        <input type="hidden" name="assessment_id" id="assessment_id" value="<?php echo $assessment_id; ?>" />
                                        <select class="form-control internal_client_id" name="client_id" required disabled readonly>
                                            <option value=""> - Select School - </option>
                                            <?php
                                            foreach ($clients as $client)
                                                print $assessment['client_id'] == $client['client_id'] ? "<option selected=\"selected\" value=\"" . $client['client_id'] . "\">" . $client['client_name'] . ($client['city'] != "" ? ", " . $client['city'] : '') . "</option>\n" : "<option value=\"" . $client['client_id'] . "\">" . $client['client_name'] . ($client['city'] != "" ? ", " . $client['city'] : '') . "</option>\n";
                                            ?>
                                        </select>
                                    </div></div></dd>
                        </dl>

                        <dl class="fldList">
                            <dt>Internal Reviewer<span class="astric">*</span>:</dt>
                            <dd><div class="row"><div class="col-sm-6">
                                        <select class="form-control internal_assessor_id" name="internal_assessor_id" required <?php echo ($internalRevPerc > 0 || $externalRevPerc > 0) ? 'disabled' : '' ?> >
                                            <option value=""> - Select Internal Reviewer - </option>
                                        </select>
                                    </div></div></dd>
                        </dl>

                        <dl class="fldList">
                            <dt>Review Criteria:</dt>
                            <dd><div class="row"><div class="col-sm-6">
                                        <input type="text" class="form-control" value="<?php echo $assessment['review_criteria']; ?>" name="review_criteria"  maxlength="100"/>
                                    </div></div></dd>
                        </dl>

                        <!---external team-->
                        <div class="clr" style="margin-top:20px;"></div>
                        <div class="boxBody">									
                            <p><b>External Review team:</b><span class="astric">*</span></p>
                            <div class="tableHldr teamsInfoHldr school_team team_table noShadow">
                                <?php if (!$disabled) { ?>
                                    <a href="javascript:void(0)" class="extteamAddRow"><i class="fa fa-plus"></i></a>
                                <?php } ?>
                                <table class='table customTbl'>
                                    <thead>
                                        <tr><th style="width:5%">Sr. No.</th><th style="width:35%">School</th><th style="width:25%">External Reviewer Role</th><th style="width:25%">External Reviewer Team member</th><th style="width:5%;"></th></tr>
                                    </thead>
                                    <tbody>
                                        <tr class='team_row'><td class='s_no'>1</td>
                                            <td><select class="form-control external_client_id" id="external_client_id" required <?php echo $isEditable ?>>
                                                    <option value=""> - Select School - </option>
                                                    <?php
                                                    foreach ($clients as $client)
                                                        echo "<option value=\"" . $client['client_id'] . "\"" . ($assessment['external_client'] == $client['client_id'] ? "selected=selected" : '') . ">" . $client['client_name'] . ($client['city'] != "" ? ", " . $client['city'] : '') . "</option>\n";
                                                    ?>
                                                </select></td>
                                            <td>Mentor</td>	
                                            <td><select class="form-control external_assessor_id" name="external_assessor_id" id="lead_assessor" required  <?php echo $isEditable; ?>>
                                                    <option value=""> - Select Member - </option>
                                                    <?php
                                                    foreach ($externalAssessors as $index => $ext) {
                                                        if ($externalClient == $ext['user_id'] || !in_array($ext['user_id'], $allAssessors))
                                                            echo "<option value=\"" . $ext['user_id'] . "\"" . ($externalClient == $ext['user_id'] ? 'selected=selected' : '') . ">" . $ext['name'] . "</option>";
                                                    }
                                                    ?>
                                                </select></td>
                                            <td></td>	
                                        </tr>
                                        <?php
                                        ?>
                                        <?php
                                        $subRoles = $assessment['subroles'];
                                        $subRoles = !empty($subRoles) ? explode(',', $subRoles) : '';
                                        $sn = 2;
                                        if (!empty($subRoles))
                                            foreach ($subRoles as $subRole) {
                                                $rowTeam = explode('_', $subRole);
                                                $teamExternalClientId = $rowTeam[0];
                                                $teamExternalMemberId = $rowTeam[1];
                                                $teamExternalRoleId = $rowTeam[2];
                                                $isFilled = $rowTeam[3];
                                                $disabled = '';
                                                if ($isFilled == 1) {
                                                    $disabled = 'disabled=""';
                                                }
                                                $row = '<tr class="team_row">
										<td class="s_no">' . $sn . '</td>';
                                                $row .= '<td><select class="form-control team_external_client_id" id="team_external_client_id' . $sn . '" required name="externalReviewTeam[clientId][]" ' . $disabled . '>
																	<option value=""> - Select School - </option>';
                                                foreach ($clients as $client)
                                                    $row .= "<option value=\"" . $client['client_id'] . "\"" . ($teamExternalClientId == $client['client_id'] ? 'selected=selected' : '') . ">" . $client['client_name'] . ($client['city'] != "" ? ", " . $client['city'] : '') . "</option>\n";

                                                $row .= '</select></td>';

                                                $row .= '<td><select class="form-control " name="externalReviewTeam[role][]" required ' . $disabled . '>
											<option value=""> - Select Role - </option>
											';
                                                foreach ($externalReviewRoles as $externalReviewer)
                                                    $row .= $externalReviewer['sub_role_id'] == '1' ? '' : "<option value=\"" . $externalReviewer['sub_role_id'] . "\"" . ($externalReviewer['sub_role_id'] == $teamExternalRoleId ? 'selected=selected' : '') . ">" . $externalReviewer['sub_role_name'] . "</option>";

                                                $row .= '</select></td>
										<td><select class="form-control team_external_assessor_id" name="externalReviewTeam[member][]" id="team_external_assessor_id' . $sn . '" required ' . $disabled . '>
											<option value=""> - Select Member - </option>';
                                                foreach ($externalAssessorsTeam[$sn - 2] as $index => $ext) {

                                                    if ($teamExternalMemberId == $ext['user_id'] || !in_array($ext['user_id'], $allAssessors))
                                                        $row .= "<option value=\"" . $ext['user_id'] . "\"" . ($teamExternalMemberId == $ext['user_id'] ? 'selected=selected' : '') . ">" . $ext['name'] . "</option>";
                                                }


                                                $row .= '</select></td>
										<td>';
                                                if ($disabled == '') {
                                                    $row .= '<a href="javascript:void(0)" class="delete_row"><i class="fa fa-times"></i></a>';
                                                }
                                                $row .= '</td>
									</tr>';
                                                echo $row;
                                                $sn++;
                                            }
                                        ?>										
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!--external team ends-->
                        <div class="boxBody">
                            <div class="clr" style="margin-top:20px;"></div>
                            <p><b>Facilitator:</b></p>
                            <div class="tableHldr teamsInfoHldr school_team facilitator_table noShadow">
                                <?php if (!$disabled) { ?>
                                    <a href="javascript:void(0)" class="extteamAddRow"><i class="fa fa-plus"></i></a>
                                <?php } ?>
                                <table class='table customTbl'>
                                    <thead>
                                        <tr><th style="width:5%">Sr. No.</th><th style="width:35%">School</th><th style="width:25%">Facilitator Role</th><th style="width:25%">Facilitator Team member</th><th style="width:5%;"></th></tr>	
                                    </thead>
                                    <tbody>
                                        <tr class='facilitator_row'><td class='s_no'>1</td>
                                            <td><select class="form-control facilitator_client_id" name="facilitator_client_id" id="facilitator_client_id"  <?php //echo $disabled    ?>>
                                                    <option value=""> - Select School - </option>
                                                    <?php
                                                    foreach ($clients as $client)
                                                        print isset($assessment['f_client_id']) && $assessment['f_client_id'] == $client['client_id'] ? "<option selected='selected' value=\"" . $client['client_id'] . "\">" . $client['client_name'] . "</option>\n" : "<option value=\"" . $client['client_id'] . "\">" . $client['client_name'] . "</option>\n";
                                                    ?>
                                                </select></td>
                                            <td>Mentor</td>
                                            <td><select class="form-control facilitator_id" name="facilitator_id"  <?php //echo $disabled  ?>>
                                                    <option value=""> - Select Facilitator - </option>

                                                </select></td>
                                            <td></td>	
                                        </tr>	
                                        <?php
                                        $subRoles = $assessment['subroles'];
                                        $subRoles = !empty($subRoles) ? explode(',', $subRoles) : '';
                                        $sn = 2;
                                        if (!empty($facilitators)) {
                                            foreach ($facilitators as $data) {
                                                $facilitatorClientId = $data['client_id'];
                                                $facilitatorMemberId = $data['user_id'];
                                                $facilitatorRoleId = $data['sub_role_id'];
                                                if ($facilitatorRoleId > 2) {
                                                    $row = '<tr class="facilitator_row"><td class="s_no">' . $sn . '</td>';

                                                    $row .= '<td><select class="form-control team_facilitator_client_id" id="team_facilitator_client_id' . $sn . '" required name="facilitatorReviewTeam[clientId][]" ' . $disabled . '>
                                                                                                                <option value=""> - Select School - </option>';
                                                    foreach ($clients as $client)
                                                        $row .= "<option value=\"" . $client['client_id'] . "\"" . ($facilitatorClientId == $client['client_id'] ? 'selected=selected' : '') . ">" . $client['client_name'] . ($client['city'] != "" ? ", " . $client['city'] : '') . "</option>\n";

                                                    $row .= '</select></td>';

                                                    $row .= '<td><select class="form-control " name="facilitatorReviewTeam[role][]" ><option value=""> - Select Role - </option>';
                                                    foreach ($externalReviewRoles as $externalReviewer)
                                                        $row .= $externalReviewer['sub_role_id'] == '1' || $externalReviewer['sub_role_id'] == '2' ? '' : "<option value=\"" . $externalReviewer['sub_role_id'] . "\"" . ($externalReviewer['sub_role_id'] == $facilitatorRoleId ? 'selected=selected' : '') . ">" . $externalReviewer['sub_role_name'] . "</option>";

                                                    $row .= '</select></td>
                                                                                                                 <td><select class="form-control team_external_facilitator_id" name="facilitatorReviewTeam[member][]" id="team_external_facilitator_id' . $sn . '" required ' . $disabled . '>
                                                                                                                 <option value=""> - Select Member - </option>';
                                                    foreach ($facilitatorTeam[$facilitatorMemberId] as $index => $ext) {

                                                        if ($facilitatorRoleId == $ext['user_id'] || !in_array($ext['user_id'], $allAssessors))
                                                            $row .= "<option value=\"" . $ext['user_id'] . "\"" . ($facilitatorMemberId == $ext['user_id'] ? 'selected=selected' : '') . ">" . $ext['name'] . "</option>";
                                                    }
                                                    $row .= '</select></td><td>';
                                                    if ($disabled == '') {
                                                        $row .= '<a href="javascript:void(0)" class="delete_row"><i class="fa fa-times"></i></a>';
                                                    }
                                                    $row .= '</td></tr>';
                                                    echo $row;
                                                    $sn++;
                                                }
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="boxBody">									
                            <p><b>School preferred dates for AQS:</b></p>
                            <div class="tableHldr noShadow">
                                <table class='table customTbl'>
                                    <tbody>
                                        <tr class='team_row'>
                                            <td class="trans" style="width:45%">
                                                <label>Start Date</label><span class="astric">*</span>:</p>
                                                <div class="inpFld">
                                                    <div class="input-group aqsDate aqs_sdate">
                                                        <input autocomplete="off" id="aqsf_school_aqs_pref_start_date"  type="text" class="form-control external_assessor_id" placeholder="DD-MM-YYYY" name="school_aqs_pref_start_date" value="<?php echo empty($assessment['school_aqs_pref_start_date']) ? '' : $assessment['school_aqs_pref_start_date']; ?>" readonly="readonly">
                                                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>

                                                    </div>
                                                </div>
                                            </td>
                                            <td style="width:45%">
                                                <label>End Date</label><span class="astric">*</span>:</p>
                                                <div class="inpFld">
                                                    <div class="input-group aqsDate aqs_edate">
                                                        <input autocomplete="off" id="aqsf_school_aqs_pref_end_date" type="text" class="form-control external_assessor_id" placeholder="DD-MM-YYYY" name="school_aqs_pref_end_date" value="<?php echo empty($assessment['school_aqs_pref_end_date']) ? '' : $assessment['school_aqs_pref_end_date']; ?>" readonly="readonly">
                                                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </td>
                                    </tbody>            
                                </table>

                            </div>

                        </div>
                        <dl class="fldList">
                            <dt>Diagnostic<span class="astric">*</span>:</dt>
                            <dd><div class="row"><div class="col-sm-6">
                                        <select class="form-control" name="diagnostic_id" id="diagnostic_id" required <?php echo ($internalRevPerc > 0 || $externalRevPerc > 0) ? 'disabled' : '' ?>>
                                            <option value=""> - Select Diagnostic - </option>
                                            <?php
                                            foreach ($diagnostics as $diagnostic)
                                                print $assessment['diagnostic_id'] == $diagnostic['diagnostic_id'] ? "<option selected='selected' value=\"" . $diagnostic['diagnostic_id'] . "\">" . $diagnostic['name'] . "</option>\n" : (in_array($diagnostic['diagnostic_id'], $hideDiagnostics) ? '' : "<option value=\"" . $diagnostic['diagnostic_id'] . "\">" . $diagnostic['name'] . "</option>\n");
                                            ?>
                                        </select>
                                    </div></div></dd>
                        </dl>
                        <dl class="fldList">
                            <dt>Preferred Language<span class="astric">*</span>:</dt>
                            <dd><div class="row"><div class="col-sm-6">
                                        <select class="form-control" name="diagnostic_lang" id="diagnostic_lang_id" required>
                                            <option  value=""> - Select Diagnostic Language - </option>
                                            <?php
                                            foreach ($languages as $language)
                                                print $assessment['language_id'] == $language['language_id'] ? "<option selected='selected' value=\"" . $language['language_id'] . "\">" . $language['language_words'] . "</option>\n" : "<option value=\"" . $language['language_id'] . "\">" . $language['language_words'] . "</option>\n";
                                            ?>
                                        </select>
                                    </div></div></dd>
                        </dl>
                        <input type="hidden" name="tier_id" value="3"/>
                        <input type="hidden" name="award_scheme_name" value="1"/>
                        <dl class="fldList">
                            <dt>Review Round <span class="astric">*</span>:</dt>
                            <dd><div class="row"><div class="col-sm-6">
                                        <select class="form-control" name="aqs_round" required>
                                            <option value=""> - Select AQS Round - </option>
                                            <?php
                                            foreach ($aqsRounds as $aqsRound) {
                                                if (in_array($aqsRound['aqs_round'], $unusedRounds)) {
                                                    $disabled = "";
                                                } else {
                                                    $disabled = 'disabled="disabled"';
                                                }
                                                if ($aqsRound['aqs_round'] == $assessment['aqs_round']) {
                                                    echo "<option value=\"" . $aqsRound['aqs_round'] . "\" selected=\"selected\" " . $disabled . ">" . $aqsRound['aqs_round'] . "</option>\n";
                                                } else {
                                                    echo "<option value=\"" . $aqsRound['aqs_round'] . "\" " . $disabled . ">" . $aqsRound['aqs_round'] . "</option>\n";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div></div></dd>
                        </dl>
                        <?php
                        if ($editStatus != 1) {
                            ?>

                            <div class="text-right"><input type="submit" title="Click to edit review."  value="Update Review" class="btn btn-primary vtip"></div>

                        <?php } ?>

                    </div>
                    <div class="ajaxMsg" id="createresource"></div>
                    <input type="hidden" class="isAjaxRequest" name="isAjaxRequest" value="<?php echo $ajaxRequest; ?>" />

                </div>
            </form>
        </div>
    </div>
    <div id="ctreateSchoolAssessment-step2" role="tabpanel" class="tab-pane fade in" style=" display: <?php echo (isset($step2) && $step2 == 1) ? 'block' : 'none'; ?>;">
        <div class="subTabWorkspace pad26">
            <form id="edit-review-kpa">
                <div class="form-stmnt" id="kpa-step2">

                    <div class="boxBody">									
                        <p><b>External Review team</b></p>
                        <div class="tableHldr teamsInfoHldr school_team team_table noShadow">

                            <table class='table customTbl'>
                                <thead>
                                    <tr><th style="width:5%">Sr. No.</th><th style="width:35%">School</th><th style="width:25%">External Reviewer Role</th><th style="width:25%">External Reviewer Team member</th><th style="width:5%;">KPAs</th></tr>
                                </thead>
                                <tbody>
                                    <tr class='team_row'><td class='s_no'>1</td>
                                        <td>
                                            <?php
                                            foreach ($clients as $client) {
                                                if ($assessment['external_client'] == $client['client_id']) {
                                                    echo $client['client_name'] . ($client['city'] != "" ? ", " . $client['city'] : '');
                                                }
                                            }
                                            ?>
                                            </select></td>
                                        <td>Mentor</td>	
                                        <td>
<?php
foreach ($externalAssessors as $index => $ext) {
    if ($externalClient == $ext['user_id']) {
        echo ' <input type="hidden" name="external_assessor_id" id="lead_assessor" value=' . $ext['user_id'] . ' >';
        echo $ext['name'];
    }
}
?>
                                            </select></td>
                                        <?php
                                        $row1 = '<td><select multiple="multiple" class="form-control team_kpa_id" id="team_kpa_id' . 1 . '"  name="team_kpa_id[' . $externalClient . '][]" ' . '>';

                                        foreach ($assessmentKpas as $kpas) {
                                            $numKpas++;
                                            $row1 .= "<option value=\"" . $kpas['kpa_id'] . "\"" . (!empty($kpas) && in_array($kpas['kpa_id'], $externalClientKpa) ? 'selected=selected' : '') . ">" . $kpas['kpa_name'] . "</option>\n";
                                        }

                                        $row1 .= '</select></td>';
                                        echo $row1;
                                        echo "</td>";
                                        ?>

                                    </tr>

                                    <?php
                                    echo ' <input type="hidden" name="assessment_id"  value=' . $assessment['assessment_id'] . ' >';
                                    echo ' <input type="hidden" name="num_kpa"  value=' . $numKpas . ' >';
                                    $subRoles = $assessment['subroles'];
                                    $subRoles = !empty($subRoles) ? explode(',', $subRoles) : '';
                                    //print_r($subRoles);
                                    $sn = 2;
                                    if (!empty($subRoles))
                                        foreach ($subRoles as $subRole) {
                                            $rowTeam = explode('_', $subRole);
                                            $teamExternalClientId = $rowTeam[0];
                                            $teamExternalMemberId = $rowTeam[1];
                                            $teamExternalRoleId = $rowTeam[2];
                                            $row = '<tr class="team_row">
										<td class="s_no">' . $sn . '</td>';
                                            $row .= '<td>';

                                            foreach ($clients as $client) {
                                                if ($teamExternalClientId == $client['client_id'])
                                                    $row .= $client['client_name'] . ($client['city'] != "" ? ", " . $client['city'] : '');
                                            }

                                            $row .= '</td>';

                                            $row .= '<td>';
                                            foreach ($externalReviewRoles as $externalReviewer) {
                                                if ($externalReviewer['sub_role_id'] != '1' && $externalReviewer['sub_role_id'] == $teamExternalRoleId)
                                                    $row .= $externalReviewer['sub_role_name'];
                                            }

                                            $row .= '</td>
										<td>';
                                            foreach ($externalAssessorsTeam[$sn - 2] as $index => $ext) {

                                                if ($teamExternalMemberId == $ext['user_id']) {
                                                    $row .= $ext['name'];
                                                    $assessor_id = $ext['user_id'];
                                                }
                                            }


                                            $row .= '</select></td>';

                                            $row .= '<td><select multiple="multiple" class="form-control team_kpa_id" id="team_kpa_id' . $sn . '"  name="team_kpa_id[' . $teamExternalMemberId . '][]" ' . '>';

                                            foreach ($assessmentKpas as $kpas)
                                                $row .= "<option value=\"" . $kpas['kpa_id'] . "\"" . (!empty($assignedKpas[$assessor_id]) && in_array($kpas['kpa_id'], $assignedKpas[$assessor_id]) ? 'selected=selected' : '') . ">" . $kpas['kpa_name'] . "</option>\n";

                                            $row .= '</select></td>';
                                            $row .= '</tr>';

                                            echo $row;
                                            $sn++;
                                        }
                                    ?>										
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <?php if ($assessmentStatus != 1) { ?>
                    <div class="text-right"><input type="submit" title="Click to assign KPAs"  value="Assign KPA" id="edit-kpa" class="btn btn-primary vtip"></div>
<?php } ?>
                <div class="ajaxMsg" id="createresource"></div>
                <input type="hidden" class="isAjaxRequest" name="isAjaxRequest" value="<?php echo $ajaxRequest; ?>" />
                <input type="hidden" class="isNewReview" name="isNewReview" value="<?php echo $isNewReview; ?>" />
                <input type="hidden" class="editStatus" name="editStatus" value="<?php echo $editStatus; ?>" />
                <input type="hidden" class="assessmentRating" name="assessmentRating" value="<?php echo $assessmentRating; ?>" />
                <input type="hidden" class="assessmentRatingKpa" name="assessmentRatingKpa" value="<?php echo $assessmentRatingKpa; ?>" />
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {

        loadAssesorListForEditAssessment($("#edit_school_assessment_form"), "internal", '<?php echo $assessment['user_ids'] ?>', '<?php echo $editStatus ?>');
        getExternalAssesorListForAssessment($("#edit_school_assessment_form"));
        loadFacilitatorListForEditAssessment($("#edit_school_assessment_form"), 'facilitator', '<?php echo $assessment['facilitator_id'] ?>');
        var date = new Date();
        var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
        $('.aqs_sdate').datetimepicker({format: 'DD-MM-YYYY', useCurrent: false, pickTime: false, minDate: today});
        $('.aqs_edate').datetimepicker({format: 'DD-MM-YYYY', useCurrent: false, pickTime: false, minDate: today});
    });
</script>