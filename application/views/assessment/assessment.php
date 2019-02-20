<?php
/* Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
    This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
    LICENSE file in the root directory of this source tree.*/
?>


<?php
$isNetworkAdmin = (in_array("view_own_network_assessment", $user['capabilities']) && $user['network_id'] > 0) ? 1 : 0;
$isSchoolAdmin = in_array("view_own_institute_assessment", $user['capabilities']) ? 1 : 0;
$isAdmin = in_array("view_all_assessments", $user['capabilities']) ? 1 : 0;
$canEditAfterSubmit = in_array("edit_all_submitted_assessments", $user['capabilities']) ? 1 : 0;
$isPrincipal = in_array(6, $user['role_ids']) ? 1 : 0;
$isTAPAdmin = in_array(8, $user['role_ids']) ? 1 : 0;
$isInternalReviewer = in_array(3, $user['role_ids']) ? 1 : 0;
$isExternalReviewer = in_array(4, $user['role_ids']) ? 1 : 0;
$addEditColumn = $isNetworkAdmin || $isSchoolAdmin || $isAdmin ? 1 : 0;
$isAdminNadminPrincipal = $isNetworkAdmin || $isPrincipal || $isAdmin ? 1 : 0;
if ($isLead >= 1)
    $user['isLead'] = $isLead;

$assessmentListRowHelper = new assessmentListRowHelper($user);
$disableSelfReview = 0;
?>

<div class="filterByAjax assessment-list" data-action="<?php echo $this->_action; ?>" data-controller="<?php echo $this->_controller; ?>">
    <div class="clearfix hdrTitle">
        <div class="pull-left"><h1 class="page-title"><?php echo empty($_REQUEST['myAssessment']) ? "Manage " : ""; ?>MyReviews
                <select class="langSel" name="lang" id="lang">
                    <option value="all">All</option>
                    <?php foreach ($diagnosticsLanguage as $val) { ?>
                        <option value="<?php echo $val['language_code']; ?>" <?php echo (isset($_COOKIE['ADH_LANG']) && $_COOKIE['ADH_LANG'] == $val['language_id']) ? "selected" : ''; ?>><?php echo $val['language_words']; ?></option>

                    <?php } ?>
                </select>
            </h1></div>

        <div class="pull-right">
            <ul class="ratInd flotedInTab">
                <?php if ($isAdmin) { ?>
                    <li><a class="execUrl vtip iconBtn" data-size="880" href="?isPop=1&controller=assessment&action=reportAllSchools" title="Download Report"><span class="btn btn-primary">Download School Evaluation report</span></a></li>
                <?php } ?>

            </ul><?php
            if ((in_array("create_assessment", $user['capabilities']) && in_array("1", $user['role_ids']) || in_array("2", $user['role_ids']) && current($user['role_ids']) != 8) || in_array("create_self_review", $user['capabilities'])) {
                ?>
                <ul class="mainNav">
                    <li class="active"><a href="javascript:void(0);">Create Review <i class="fa fa-sort-desc"></i></a>
                        <ul>

    <?php
    if (in_array("create_assessment", $user['capabilities'])) {
        if (in_array("1", $user['role_ids']) || in_array("2", $user['role_ids']) && current($user['role_ids']) != 8 || $user['user_id'] == 1) {
            ?>                                
                                    <li><a href="?controller=assessment&action=createSchoolAssessment&amp;ispop=1" data-size="800" id="addschoolAssBtn">Create School Review</a></li>
                                <?php }
                                ?>
                            <?php } ?>
                        </ul>
                    </li>
                </ul>
<?php }
?>
        </div>
    </div>

    <div class="asmntTypeContainer">
<?php
$ajaxFilter = new ajaxFilter();
if (!empty($_REQUEST['myAssessment'])) {
    $ajaxFilter->addHidden("myAssessment", 1);
    if ($isAdmin || $isNetworkAdmin || $isTAPAdmin || $isInternalReviewer || $isExternalReviewer) {
        if ($isAdmin || $isNetworkAdmin || $isTAPAdmin) {
            $ajaxFilter->addTextBoxEtc("client_name", $filterParam["client_name_like"], "School", "style='width:9%;'");
        } else
            $ajaxFilter->addTextBox("client_name", $filterParam["client_name_like"], "School");
    }
    if ($isAdmin || $isNetworkAdmin || $isTAPAdmin) {
        $ajaxFilter->addTextBoxEtc("name", $filterParam["name_like"], "Reviewer Name", "style='width:9%;'");
    } else
        $ajaxFilter->addTextBox("name", $filterParam["name_like"], "Reviewer Name");
}else {
    if ($isAdmin || $isNetworkAdmin || $isTAPAdmin || $isInternalReviewer || $isExternalReviewer) {
        if ($isAdmin || $isNetworkAdmin || $isTAPAdmin) {
            $ajaxFilter->addTextBoxEtc("client_name", $filterParam["client_name_like"], "School", "style='width:9%;'");
        } else
            $ajaxFilter->addTextBox("client_name", $filterParam["client_name_like"], "School");
    }
    if ($isAdmin || $isNetworkAdmin || $isTAPAdmin) {
        $ajaxFilter->addTextBoxEtc("name", $filterParam["name_like"], "Reviewer Name", "style='width:9%;'");
    } else
        $ajaxFilter->addTextBox("name", $filterParam["name_like"], "Reviewer Name");

    if ($isAdmin || $isTAPAdmin) {
        $ajaxFilter->addDropDown("stat_id", $states, 'state_id', 'state_name', $filterParam["state_id"], "State");
        $ajaxFilter->addDropDown("zone_id", $zones, 'zone_id', 'zone_name', $filterParam["zone_id"], "Zone");
        $ajaxFilter->addDropDown("network_id", $networks, 'network_id', 'network_name', $filterParam["network_id"], "Block");
        $ajaxFilter->addDropDown("province_id", $provinces, 'province_id', 'province_name', $filterParam["province_id"], "Hub");
    }
}
$ajaxFilter->addDropDown("diagnostic_id", $diagnostics, 'diagnostic_id', 'name', $filterParam["diagnostic_id"], "Diagnostic");
if ($isAdmin || $isNetworkAdmin || $isTAPAdmin) {
    $ajaxFilter->addDateBox("fdate", ChangeFormat($filterParam["fdate_like"], "d-m-Y", ""), "AQS Start Date", '', 'style="width:9%;"');
    $ajaxFilter->addDateBox("edate", ChangeFormat($filterParam["edate_like"], "d-m-Y", ""), "AQS End Date", '', 'style="width:9%;"');
} else {
    $ajaxFilter->addDateBox("fdate", ChangeFormat($filterParam["fdate_like"], "d-m-Y", ""), "AQS Start Date", '');
    $ajaxFilter->addDateBox("edate", ChangeFormat($filterParam["edate_like"], "d-m-Y", ""), "AQS End Date", '');
}
if (isset($_REQUEST['uid']) && $_REQUEST['uid'] != '' && isset($_REQUEST['rid']) && $_REQUEST['rid'] != '') {
    $ajaxFilter->addHidden("uid", $_REQUEST['uid']);
    $ajaxFilter->addHidden("rid", $_REQUEST['rid']);
}
if (isset($_REQUEST['ref']) && $_REQUEST['ref'] != '') {
    $ajaxFilter->addHidden("ref", $_REQUEST['ref']);
}

$ajaxFilter->generateFilterBar(1);
?><script type="text/javascript">
            // function for change the end date according to from date on 28-07-2016 by Mohit Kumar
            $(function () {
                
                $('.fdate').datetimepicker({format: 'DD-MM-YYYY', useCurrent: false, pickTime: false}).off('focus')
                        .click(function () {
                            $(this).data("DateTimePicker").show();
                        });
                $('.edate').datetimepicker({format: 'DD-MM-YYYY', useCurrent: false, pickTime: false}).off('focus')
                        .click(function () {
                            $(this).data("DateTimePicker").show();
                        });
                $(".fdate").on("dp.change", function (e) {
                    console.log(e);
                    $('.edate').data("DateTimePicker").setMinDate(e.date);
                    $('.edate').val('');
                });
                $(".edate").on("dp.change", function (e) {
                    $('.fdate').data("DateTimePicker").setMaxDate(e.date);
                });

                $(".fdate").on("blur", function (e) {
                    $('.edate').data("DateTimePicker").setMinDate(e.date);
                });
                $(".edate").on("click", function (e) {
                    if($('.fdate').datetimepicker({format: 'DD-MM-YYYY'}).val()!=""){
                        var date = $('.fdate').datetimepicker({format: 'MM DD YYYY'}).val().split('-')[1]+' '+$('.fdate').datetimepicker({format: 'MM DD YYYY'}).val().split('-')[0]+' '+$('.fdate').datetimepicker({format: 'MM DD YYYY'}).val().split('-')[2];
                        console.log(date);
                        $('.edate').data("DateTimePicker").setMinDate(date);
                   } 
                   $(this).val("");
                });
                $(".edate").on("blur", function (e) {
                    if ($(this).val() != "") {
                        $('.fdate').data("DateTimePicker").setMaxDate($(this).val());
                    }
                });


            });
        </script>
        <div class="tableHldr assessmentaqs">
            <table class="cmnTable">
                <thead>
<?php echo $assessmentListRowHelper->printHeaderRow($orderBy, $orderType); ?>
                </thead>
                <tbody>
                    <?php
                    if (count($assessmentList)) {

                        foreach ($assessmentList as $assessment)
                            echo $assessmentListRowHelper->printBodyRow($assessment);
                    } else {
                        echo $assessmentListRowHelper->printNoResultRow();
                    }
                    ?>
                </tbody>
            </table>
        </div>


<?php echo $this->generateAjaxPaging($pages, $cPage); ?>

<!--        <div class="ajaxMsg"></div>-->


    </div>
</div>

<script>
    $(document).ready(function () {
        $('[disabled] .vtip').removeClass('vtip');
        $('[disabled] a,[disabled] span').css('cursor', 'not-allowed');
        $('[disabled] a,[disabled] i').attr('title', '');
        $('[disabled] a').attr('href', '');
        $('[disabled] a').on('click', function (e) {
            e.preventDefault();
        });
    });
</script>