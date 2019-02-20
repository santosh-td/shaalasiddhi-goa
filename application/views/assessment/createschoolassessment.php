<?php
/* Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
    This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
    LICENSE file in the root directory of this source tree.*/
?>

<a id="addUserBtn" class="btn btn-primary pull-right execUrl vtip fixonmodal" title="Click to add a user." href="?controller=user&action=createUser&ispop=1">Add user</a>
<h1 class="page-title">
    <a href="<?php
    $args = array("controller" => "assessment", "action" => "assessment");
    echo createUrl($args);
    ?>">
        <i class="fa fa-chevron-circle-left vtip" title="Back"></i>
        Manage MyReviews
    </a> &rarr;
    Create School Review
</h1>
<div class="clr"></div>
<div class="">
    <div class="ylwRibbonHldr">
        <div class="collapse navbar-collapse tabitemsHldr" id="tab4_Toggle">
			<ul class="yellowTab nav nav-tabs">          
				<li class="item active" id="collaborative-step1"><a href="#ctreateSchoolAssessment-step1" data-toggle="tab" class="vtip" title="Create School Review">Step 1</a></li>
                                <li class="item disabledTab" id="collaborative-step2" style="display: none;"><a href="#ctreateSchoolAssessment-step2" data-toggle="tab" class="vtip" title="Add Collaborative details" id="step2" >Step 2</a></li>				
			</ul>
		</div>
        <div class="tabitemsHldr pad0 clearfix">
        </div>
    </div>
    <div id="ctreateSchoolAssessment-step1" role="tabpanel" class="tab-pane fade in active">
      <form method="post" id="create_school_assessment_form" action="">
            <div class="subTabWorkspace pad26">
        <div class="form-stmnt">
            
                <div class="boxBody">
                    <dl class="fldList">
                        <dt>Review Type<span class="astric">*</span>:</dt>
                        <dd><div class="row"><div class="col-sm-6">
                                    <select class="form-control " name="review_type" id="review_type" >
                                        <option value="1">Collaborative School Review</option>
                                       
                                    </select>
                                </div></div></dd>
                    </dl>
                    <dl class="fldList">
                        <dt>School<span class="astric">*</span>:</dt>
                        <dd><div class="row"><div class="col-sm-6">
                                    <select class="form-control internal_client_id" name="client_id" required>
                                        <option value=""> - Select School - </option>
                                        <?php
                                        foreach ($clients as $client)
                                            echo "<option value=\"" . $client['client_id'] . "\">" . $client['client_name'] . ($client['city'] != "" ? ", " . $client['city'] : '') . "</option>\n";
                                        ?>
                                    </select>
                                </div></div></dd>
                    </dl>

                    <dl class="fldList">
                        <dt>Internal Reviewer<span class="astric">*</span>:</dt>
                        <dd><div class="row"><div class="col-sm-6">
                                    <select class="form-control internal_assessor_id" name="internal_assessor_id" required>
                                        <option value=""> - Select Internal Reviewer - </option>
                                    </select>
                                </div></div></dd>
                    </dl>
                    
                    <dl class="fldList">
                        <dt>Review Criteria:</dt>
                        <dd><div class="row"><div class="col-sm-6">
                                    <input type="text" class="form-control" value="" name="review_criteria" maxlength="100" />
                                </div></div></dd>
                    </dl>



                    <div class="boxBody">
                        <div class="clr" style="margin-top:20px;"></div>
                        <p><b>External Review team</b><span class="astric">*</span>:</p>
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
                                        <td><select class="form-control external_client_id" id="external_client_id" required <?php echo $disabled ?>>
                                                <option value=""> - Select School - </option>
                                                <?php
                                                foreach ($clients as $client)
                                                    echo "<option value=\"" . $client['client_id'] . "\">" . $client['client_name'] . "</option>\n";
                                                ?>
                                            </select></td>
                                        <td>Mentor</td>
                                        <td><select class="form-control external_assessor_id" name="external_assessor_id" id="lead_assessor" required  <?php echo $disabled ?>>
                                                <option value=""> - Select Member - </option>
                                            </select></td>
                                        <td></td>	
                                    </tr>										
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="boxBody">
                        <div class="clr" style="margin-top:20px;"></div>
                        <p><b>Facilitator:</b></p>
                        <div class="tableHldr teamsInfoHldr facilitator_team facilitator_table noShadow">
                            <?php if (!$disabled) { ?>
                                <a href="javascript:void(0)" class="extteamAddRow"><i class="fa fa-plus"></i></a>
                            <?php } ?>
                            <table class='table customTbl'>
                                <thead>
                                    <tr><th style="width:5%">Sr. No.</th><th style="width:35%">School</th><th style="width:25%">Facilitator Role</th><th style="width:25%">Facilitator Team member</th><th style="width:5%;"></th></tr>	
                                </thead>
                                <tbody>
                                    <tr class='facilitator_row'><td class='s_no'>1</td>
                                        <td><select class="form-control facilitator_client_id" name="facilitator_client_id" id="facilitator_client_id">
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
                                                    <input autocomplete="off" id="aqsf_school_aqs_pref_start_date"  type="text" class="form-control" placeholder="DD-MM-YYYY" name="aqs[school_aqs_pref_start_date]" value="" readonly="readonly">
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                </div>
                                            </div>
                                        </td>
                                        <td style="width:45%">
                                            <label>End Date</label><span class="astric">*</span>:</p>
                                            <div class="inpFld">
                                                <div class="input-group aqsDate aqs_edate">
                                                    <input autocomplete="off" id="aqsf_school_aqs_pref_end_date" type="text" class="form-control" placeholder="DD-MM-YYYY" name="aqs[school_aqs_pref_end_date]" value="" readonly="readonly">
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
                                    <select class="form-control" name="diagnostic_id" id="diagnostic_id" required>
                                        <option value=""> - Select Diagnostic - </option>
                                        <?php
                                        foreach ($diagnostics as $diagnostic)
                                            echo "<option value=\"" . $diagnostic['diagnostic_id'] . "\">" . $diagnostic['name'] . "</option>\n";
                                        ?>
                                    </select>
                                </div></div></dd>
                    </dl>
                      <dl class="fldList">
                        <dt>Preferred Language<span class="astric">*</span>:</dt>
                        <dd><div class="row"><div class="col-sm-6">
                                    <select class="form-control" name="diagnostic_lang" id="diagnostic_lang_id" required>
                                        <option value=""> - Select Diagnostic Language - </option>
                                    </select>
                                </div></div></dd>
                    </dl>
                     <input type="hidden" name="tier_id" value="3"/>
                     <input type="hidden" name="award_scheme_name" value="1"/>
                    <dl class="fldList">
                        <dt>Review Round <span class="astric">*</span>:</dt>
                        <dd><div class="row"><div class="col-sm-6">
                                    <select class="form-control aqs_rounds" name="aqs_round" required>
                                        <option value=""> - Select AQS Round - </option>
                                    </select>
                                </div></div></dd>
                    </dl> 
                    <input type="hidden" id="diagnosticType" name="diagnostic_type" value="" />
                                <div class="text-right"><input type="submit" title="Click to create a review."  value="Create Review" id="assessment" class="btn btn-primary vtip">
                                </div>
                           

                </div>
                <div class="ajaxMsg"></div>
                <input type="hidden" class="isAjaxRequest" name="isAjaxRequest" value="<?php echo $ajaxRequest; ?>" />
           
        </div>
    </div>
      </form>
    </div>
 
    </div>

<script>
    $(function () {
   $('#team_kpa_id').multiselect({
            enableFiltering: true,
            enableCaseInsensitiveFiltering: true,
            includeSelectAllOption: true,
            buttonWidth: '420px',
            numberDisplayed: 2,
            maxHeight: 120,
            templates: {
                 filter: '<li class="multiselect-item filter"><div class="input-group"><input class="form-control multiselect-search" type="text"></div></li>',
               ul: '<ul class="multiselect-container dropdown-menu" style="width:420px;"></ul>',
               },
        });
});
    var date = new Date();
    var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
    $('.aqs_sdate').datetimepicker({format: 'DD-MM-YYYY',useCurrent: false, pickTime: false, minDate: today});
    $('.aqs_edate').datetimepicker({format: 'DD-MM-YYYY',useCurrent: false, pickTime: false, minDate: today});
    $(document).on("click", "#aqsf_school_aqs_pref_start_date,#aqsf_school_aqs_pref_end_date", function () {
        $(this).trigger('change')
    });
</script>