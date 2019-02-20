<?php
/**
 * Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 * Reasons: Create all header and body for assessment list
 * 
 */
class assessmentListRowHelper {

    protected $user;
    protected $assRow;
    protected $columns = array(
        "GACollapse" => array("label" => "&nbsp;"),
        "ClientName" => array("label" => "School Name", "sortable" => "client_name"),
        "AssmntType" => array("label" => "Review Type", "sortable" => "assessment_type"),
        "DiagnosticName" => array("label" => "Diagnostic Name"),
        "AssmntDate" => array("label" => "Date of Review", "sortable" => "create_date"),
        "AQSDate" => array("label" => "AQS Dates", "sortable" => "aqs_start_date"),
        "AqsStatus" => array("label" => "School Profile Status"),
        "ReviewProgress" => array("label" => "Review Progress"),
        "Report" => array("label" => "Reports"),
        "Edit" => array("label" => "Edit/ View")
    );
    public $isAdmin;
    public $isNetworkAdmin;
    public $isSchoolAdmin;
    public $isPrincipal;
    public $isAdminOrNadminOrPrincipal;
    public $isAnyAdmin;
    public $isAnyLead;
    public $canEditAfterSubmit;
    public $disableAssRow;
    public $assIsUploaded;
    protected $currentRowIs = 0;

    const SCHOOL_ASSESSMENT_ROW = 1, COLLEGE_ASSESSMENT_ROW = 6, GROUP_ASSESSMENT_ROW = 2, GROUP_ASSESSMENT_STUDENT_ROW = 4, CHILDROW_OF_STUDENT_ASSESSMENT = 5, CHILDROW_OF_GROUP_ASSESSMENT = 3;

    protected static $rowCount = 0;

    function __construct($curUser) {

        $this->user = $curUser;
        $this->canCreate = in_array("create_assessment", $this->user['capabilities']) ? true : false;
        $this->isAdmin = in_array("view_all_assessments", $this->user['capabilities']) ? true : false;
        $this->isNetworkAdmin = in_array("view_own_network_assessment", $this->user['capabilities']) ? true : false;
        $this->isSchoolAdmin = in_array(5, $this->user['role_ids']) ? true : false;
        $this->isPrincipal = in_array(6, $this->user['role_ids']) ? true : false;
        $this->isAdminOrNadminOrPrincipal = $this->isNetworkAdmin || $this->isPrincipal || $this->isAdmin ? true : false;
        $this->isAnyAdmin = $this->isAdminOrNadminOrPrincipal || $this->isSchoolAdmin;
        $this->canEditAfterSubmit = in_array("edit_all_submitted_assessments", $this->user['capabilities']) ? true : false;
        $this->isExternalReviewer = in_array("take_external_assessment", $this->user['capabilities']) ? true : false;
        $this->disableAssRow = 0;
        $this->isAnyLead = isset($curUser['isLead']) ? $curUser['isLead'] : 0;
        if (!($this->isAnyAdmin || $this->isExternalReviewer)) {
            unset($this->columns['Reports']);
        }
        if (!($this->isAnyAdmin || $this->isAnyLead)) {
            unset($this->columns['Edit']);
        }
    }

    /**
     * Reasons: print header for assessment rows
     * 
     */
    public function printHeaderRow($sortBy = 'create_date', $sortType = 'desc') {
        $text = '';
        foreach ($this->columns as $val) {
            if (empty($val['sortable']))
                $text .= "<th>" . $val['label'] . "</th>";
            else
                $text .= '<th data-value="' . $val['sortable'] . '" class="sort ' . ($sortBy == $val['sortable'] ? "sorted_" . $sortType : '') . '">' . $val['label'] . '</th>';
        }
        $text .= '</tr>';
        return $text;
    }

    /**
     * print body for assessment rows
     * 
     */
    public function printBodyRow($assessment) {
        if (!$this->isAdmin)
            $this->disableAssRow = (($assessment['assessment_type_id'] == 1 && $assessment['subAssessmentType'] == 1 && ($assessment['isApproved'] == 0 || $assessment['isApproved'] == 2)) || $assessment['offlineStatus'] == 1) ? 1 : 0;
        $this->assRow = $assessment;

        if ($this->assRow['profile_status']) {

            $this->assRow['aqs_status'] = $this->assRow['profile_status'];
        }

        $this->assIsUploaded = isset($assessment['is_uploaded']) ? $assessment['is_uploaded'] : '';
        $this->assRow['assessment_type_name'] = $assessment['assessment_type_id'] == 1 && $assessment['subAssessmentType'] == 1 ? 'School (Self-Review)' : ucfirst($this->assRow['assessment_type_name']);
        $cssCls = array('ass_type_' . $assessment['assessment_type_id']);
        if ($assessment['group_assessment_id'] && $assessment['assessment_id'] && $assessment['assessment_type_id'] == 2) {
            $this->currentRowIs = $this::CHILDROW_OF_GROUP_ASSESSMENT;
            $cssCls[] = 'gpChild ga-rows-' . $assessment['group_assessment_id'];
        } else if ($assessment['group_assessment_id'] && $assessment['assessment_id'] && $assessment['assessment_type_id'] == 4) {
            $this->currentRowIs = $this::CHILDROW_OF_STUDENT_ASSESSMENT;
            $cssCls[] = 'gpChild ga-rows-' . $assessment['group_assessment_id'];
        } else {
            if ($assessment['assessment_type_id'] == 4) {
                $this->currentRowIs = $assessment['assessment_id'] > 0 ? $this::SCHOOL_ASSESSMENT_ROW : $this::GROUP_ASSESSMENT_STUDENT_ROW;
            } else {
                $this->currentRowIs = $assessment['assessment_id'] > 0 ? $this::SCHOOL_ASSESSMENT_ROW : $this::GROUP_ASSESSMENT_ROW;
            }

            $this::$rowCount++;
            $cssCls[] = $this::$rowCount % 2 == 1 ? 'odd' : 'even';
        }

        $text = '<tr class="' . implode(" ", $cssCls) . '" data-gaid="' . $assessment['group_assessment_id'] . '">';
        foreach ($this->columns as $key => $val) {
            $funName = "print" . $key . "Column";
            $text .= $this->$funName();
        }
        $text .= '</tr>';
        return $text;
    }

    /**
     *  print body for assessment rows
     * 
     */
    public function printNoResultRow() {
        return '<tr><td colspan="' . count($this->columns) . '">No Review found</td></tr>';
    }

    /**
     * print body for assessment rows
     * 
     */
    private function printGACollapseColumn() {
        $text = '';
        if ($this->currentRowIs == $this::CHILDROW_OF_GROUP_ASSESSMENT) {
            $text = '<span class="subGARow">&nbsp;</span>';
        } else if (($this->currentRowIs == $this::GROUP_ASSESSMENT_ROW || $this->currentRowIs == $this::GROUP_ASSESSMENT_STUDENT_ROW) && $this->assRow['assessments_count'] > 0) {
            $text = '<span class="collapseGA vtip fa fa-plus-circle" title="View Reviews"></span>';
        }
        return $this->printDataCell($text);
    }

    /**
     * print client for assessment rows
     * 
     */
    private function printClientNameColumn() {
        $round = "";
        if ($this->currentRowIs == 4) {
            $round = '- Round- ' . $this->assRow['assessment_round'];
        }

        return $this->printDataCell(($this->currentRowIs == $this::CHILDROW_OF_GROUP_ASSESSMENT || $this->currentRowIs == $this::CHILDROW_OF_STUDENT_ASSESSMENT) ? $this->assRow['data_by_role'][3]['user_name'] : $this->assRow['client_name'] . '' . $round);
    }

    /**
     * print client for assessment rows
     * 
     */
    private function printAQSDateColumn() {
        $dates = "";
        $dates .= (empty($this->assRow['aqs_start_date']) || $this->assRow['aqs_start_date'] == "0000-00-00") ? '-' : "<span class='nowrap'>" . ChangeFormat($this->assRow['aqs_start_date']) . "</span>";
        $dates .= (empty($this->assRow['aqs_end_date']) || $this->assRow['aqs_end_date'] == "0000-00-00" ) ? '' : "<br>to<br><span class='nowrap'>" . ChangeFormat($this->assRow['aqs_end_date']) . "</span>";
        return $this->printDataCell($dates);
    }

    /**
     * print client for assessment rows
     * 
     */
    private function printDiagnosticNameColumn() {

        if ($this->assRow['assessment_type_id'] == 1 || $this->assRow['assessment_type_id'] == 5 || $this->currentRowIs == $this::CHILDROW_OF_GROUP_ASSESSMENT || $this->currentRowIs == $this::CHILDROW_OF_STUDENT_ASSESSMENT || $this->currentRowIs == $this::GROUP_ASSESSMENT_STUDENT_ROW)
            return $this->printDataCell($this->assRow['diagnosticName']);
        return $this->printDataCell('&nbsp;');
    }

    /**
     * print client for assessment rows
     * 
     */
    private function printAssmntTypeColumn() {
        return $this->printDataCell($this->assRow['assessment_type_name']);
    }

    /**
     * print client for assessment rows
     * 
     */
    private function printAssmntDateColumn() {
        return $this->printDataCell("<span class='nowrap'>" . ChangeFormat(substr($this->assRow['create_date'], 0, 10)) . "</span>");
    }

    /**
     * print action plan column
     * 
     */
    private function printActionPlanColumn() {
        $leader_ids = isset($this->assRow['leader_ids']) ? $this->assRow['leader_ids'] : '';
        $leader_ids_array = explode(",", $leader_ids);
        $this->isLeader = in_array($this->user['user_id'], $leader_ids_array) ? 1 : 0;
        $text = '';
        if ($this->assRow['assessment_type_id'] == 5) {
            
        } else if (($this->isAdmin && $this->currentRowIs == $this::SCHOOL_ASSESSMENT_ROW && (isset($this->assRow['subAssessmentType']) && $this->assRow['subAssessmentType'] != 1) && !empty($this->assRow['data_by_role'][4]['status']) && $this->assRow['data_by_role'][4]['status'] == 1)) {
            $text .= '<div class="merge dd pr" ><a  href="?controller=actionplan&action=actionplan1&assessment_id=' . ($this->assRow['assessment_id'] > 0 ? $this->assRow['assessment_id'] : '') . '" >Action Plan</a></div>';
        } elseif ($this->isExternalReviewer && $this->currentRowIs == $this::SCHOOL_ASSESSMENT_ROW && (isset($this->assRow['subAssessmentType']) && $this->assRow['subAssessmentType'] != 1) && isset($this->assRow['data_by_role'][4]) && intval($this->assRow['data_by_role'][4]['percComplete']) == '100' && $this->assRow['data_by_role'][4]['status'] == 1 && $this->user['user_id'] == $this->assRow['data_by_role'][4]['user_id']) {
            if (($this->isSchoolAdmin || $this->isPrincipal) && $this->assRow['client_id'] == $this->user['client_id']) {

                $text .= '<a  href="?controller=actionplan&action=actionplan1&assessment_id=' . ($this->assRow['assessment_id'] > 0 ? $this->assRow['assessment_id'] : '') . '" >Action Plan</a>';
            }

            $text .= '</div></div>';
        } else if (($this->isSchoolAdmin || $this->isPrincipal || $this->isLeader) && $this->assRow['client_id'] == $this->user['client_id'] && $this->currentRowIs == $this::SCHOOL_ASSESSMENT_ROW && (isset($this->assRow['subAssessmentType']) && $this->assRow['subAssessmentType'] != 1) && isset($this->assRow['data_by_role'][4]) && intval($this->assRow['data_by_role'][4]['percComplete']) == '100' && $this->assRow['data_by_role'][4]['status'] == 1) {
            $text .= '<div class="merge dd pr" ><a  href="?controller=actionplan&action=actionplan1&assessment_id=' . ($this->assRow['assessment_id'] > 0 ? $this->assRow['assessment_id'] : '') . '" >Action Plan</a>
                                </div>';
        } else if (($this->isAdmin || $this->isLeader || $this->isPrincipal || in_array(3, $this->user['role_ids']) ) && $this->assRow['client_id'] == $this->user['client_id'] && (isset($this->assRow['subAssessmentType']) && $this->assRow['subAssessmentType'] == 1 && $this->assRow['assessment_type_id'] == 1) && isset($this->assRow['data_by_role'][3]) && intval($this->assRow['data_by_role'][3]['percComplete']) == '100' && $this->assRow['data_by_role'][3]['status'] == 1) {

            $text .= '<div class="merge dd pr" ><a  href="?controller=actionplan&action=actionplan1&assessment_id=' . ($this->assRow['assessment_id'] > 0 ? $this->assRow['assessment_id'] : '') . '" >Action Plan</a>
                                </div>';
        } else if (($this->isAdmin) && (isset($this->assRow['subAssessmentType']) && $this->assRow['subAssessmentType'] == 1 && $this->assRow['assessment_type_id'] == 1) && isset($this->assRow['data_by_role'][3]) && intval($this->assRow['data_by_role'][3]['percComplete']) == '100' && $this->assRow['data_by_role'][3]['status'] == 1) {

            $text .= '<div class="merge dd pr" ><a  href="?controller=actionplan&action=actionplan1&assessment_id=' . ($this->assRow['assessment_id'] > 0 ? $this->assRow['assessment_id'] : '') . '" >Action Plan</a>
					
                                </div>';
        }

        return $text;

        return $text;
    }

    /**
     * print aqs status column
     * 
     */
    private function printAqsStatusColumn() {
        $isReportPublished = empty($this->assRow['report_data']) || !empty($this->assRow['report_data'][0]['isPublished']) && $this->assRow['report_data'][0]['isPublished'] != 1 ? false : true;
        $text = '&nbsp;';
        if ($this->assIsUploaded) {
            $text = '<span class="vtip" title="School Profile filled percentage">' . ($this->assRow['aqspercent'] ? $this->assRow['aqspercent'] : ($this->assRow['aqs_status'] ? '100' : '0')) . '%</span>'
                    . '<br/><a class="vtip" href="?controller=diagnostic&action=aqsForm&assmntId_or_grpAssmntId=' . ($this->assRow['assessment_id'] > 0 ? $this->assRow['assessment_id'] : $this->assRow['group_assessment_id']) . '&assessment_type_id=' . $this->assRow['assessment_type_id'] . '"' . ' title="View/Edit School Profile Data">Edit' . '</a>';
        }  else if ($this->assRow['assessments_count'] && $this->canEditAfterSubmit && $this->currentRowIs == $this::GROUP_ASSESSMENT_STUDENT_ROW) {
            $text = '-';
        } else if ($this->assRow['assessments_count'] && $this->currentRowIs == $this::GROUP_ASSESSMENT_STUDENT_ROW) {
            $text = '-';
        } else if ($this->assRow['assessments_count'] && $this->canEditAfterSubmit && $this->assRow['assessment_type_id'] == 5) {
            $text = '<span class="vtip" title="College Profile filled percentage">' . ($this->assRow['aqspercent'] ? $this->assRow['aqspercent'] : ($this->assRow['aqs_status'] ? '100' : '0')) . '%</span>
			<br/><a class="vtip" href="?controller=diagnostic&action=aqsForm&assmntId_or_grpAssmntId=' . ($this->assRow['assessment_id'] > 0 ? $this->assRow['assessment_id'] : $this->assRow['group_assessment_id']) . '&assessment_type_id=' . $this->assRow['assessment_type_id'] . '"' . ($this->assRow['aqs_status'] == 1 && !$isReportPublished ? ' title="View/Edit College Profile Data">Edit' : ' title="View College Profile Data">View') . '</a>';
        } else if ($this->assRow['assessments_count'] && $this->assRow['assessment_type_id'] == 5) {
            $text = '<span class="vtip" title="College Profile filled percentage">' . ($this->assRow['aqspercent'] ? $this->assRow['aqspercent'] : ($this->assRow['aqs_status'] ? '100' : '0')) . '%</span>
			<br/><a class="vtip" href="?controller=diagnostic&action=aqsForm&assmntId_or_grpAssmntId=' . ($this->assRow['assessment_id'] > 0 ? $this->assRow['assessment_id'] : $this->assRow['group_assessment_id']) . '&assessment_type_id=' . $this->assRow['assessment_type_id'] . '"' . ($this->assRow['aqs_status'] == 1 ? ' title="View College Profile Data">View' : ' title="View/Edit College Profile Data">Edit') . '</a>';
        } else if ($this->assRow['assessments_count'] && $this->canEditAfterSubmit) {
            $text = '<span class="vtip" title="School Profile filled percentage">' . ($this->assRow['perComplete'] ? $this->assRow['perComplete'] : '0') . '%</span>
			<br/><a class="vtip" href="?controller=diagnostic&action=aqsForm&assmntId_or_grpAssmntId=' . ($this->assRow['assessment_id'] > 0 ? $this->assRow['assessment_id'] : $this->assRow['group_assessment_id']) . '&assessment_type_id=' . $this->assRow['assessment_type_id'] . '"' . ($this->assRow['profile_status'] == 1 ? ' title="View/Edit School Profile Data">Edit' : ' title="View School Profile Data">View') . '</a>';
        } else if ($this->assRow['assessments_count']) {
            $text = '<span class="vtip" title="School Profile filled percentage">' . ($this->assRow['perComplete'] ? $this->assRow['perComplete'] : '0') . '%</span>
			<br/><a class="vtip" href="?controller=diagnostic&action=aqsForm&assmntId_or_grpAssmntId=' . ($this->assRow['assessment_id'] > 0 ? $this->assRow['assessment_id'] : $this->assRow['group_assessment_id']) . '&assessment_type_id=' . $this->assRow['assessment_type_id'] . '"' . ($this->assRow['profile_status'] == 1 ? ' title="View School Profile Data">View' : ' title="View/Edit School Profile Data">Edit') . '</a>';
        }

        return $this->printDataCell($text);
    }

    /**
     * print assessment report column
     * 
     */
    private function printReportColumn() {
        $diagnostic_id = $this->assRow['diagnostic_id'];
        $group_asmt_external = empty($this->assRow['data_by_role'][4]['user_id']) ? 0 : $this->assRow['data_by_role'][4]['user_id'];
        $group_asmt_internal = empty($this->assRow['data_by_role'][3]['user_id']) ? 0 : $this->assRow['data_by_role'][3]['user_id'];

        //if school review and admin
        if (($this->isAdmin && $this->currentRowIs == $this::SCHOOL_ASSESSMENT_ROW && $this->assRow['subAssessmentType'] != 1 && in_array('generate_submitted_asmt_reports', $this->user['capabilities'])  && !empty($this->assRow['data_by_role'][3]['status']) && $this->assRow['data_by_role'][4]['status'] == 1))
            return $this->printDataCell('<a class="execUrl manageReportBtn vtip iconBtn" data-size="880" href="?isPop=1&controller=assessment&action=reportList&diagnostic_id=' . $diagnostic_id . '&assessment_id=' . $this->assRow['assessment_id'] . '&group_assessment_id=' . $this->assRow['group_assessment_id'] . '&assessor_id=' . $this->assRow['data_by_role'][4]['user_id'] . '" title="Print/View Report"><i class="fa fa-print"></i></a>');
        //if school review and external reviewer
        elseif ($this->assRow['isFilled'] == 1 && $this->currentRowIs == $this::SCHOOL_ASSESSMENT_ROW && $this->assRow['subAssessmentType'] != 1 && in_array('generate_unsubmitted_asmt_reports', $this->user['capabilities'])  && intval($this->assRow['data_by_role'][3]['status']) == 1 && intval($this->assRow['data_by_role'][4]['percComplete']) == '100' && $this->user['user_id'] == $this->assRow['data_by_role'][4]['user_id'] && (empty($this->assRow['report_data']) || $this->assRow['report_data'][0]['isPublished'] != 1))
            return $this->printDataCell('<a class="execUrl manageReportBtn vtip iconBtn" data-size="880" href="?isPop=1&controller=assessment&action=reportList&diagnostic_id=' . $diagnostic_id . '&assessment_id=' . $this->assRow['assessment_id'] . '&group_assessment_id=' . $this->assRow['group_assessment_id'] . '&assessor_id=' . $this->assRow['data_by_role'][4]['user_id'] . '" title="Print/View Report"><i class="fa fa-print"></i></a>');
        //view published school reports of his own school
        elseif ($this->assRow['client_id'] == $this->user['client_id'] && $this->currentRowIs == $this::SCHOOL_ASSESSMENT_ROW && $this->assRow['subAssessmentType'] != 1 && in_array('view_published_own_school_reports', $this->user['capabilities'])  && intval($this->assRow['data_by_role'][3]['status']) == 1 && intval($this->assRow['data_by_role'][4]['status']) == 1 && (!empty($this->assRow['report_data']) && $this->assRow['report_data'][0]['isPublished'] == 1))
            return $this->printDataCell('<a class="execUrl manageReportBtn vtip iconBtn" data-size="880" href="?isPop=1&controller=assessment&action=reportList&diagnostic_id=' . $diagnostic_id . '&assessment_id=' . $this->assRow['assessment_id'] . '&group_assessment_id=' . $this->assRow['group_assessment_id'] . '&assessor_id=' . $this->assRow['data_by_role'][4]['user_id'] . '" title="Print/View Report"><i class="fa fa-print"></i></a>');
        elseif ($this->currentRowIs == $this::SCHOOL_ASSESSMENT_ROW &&  $this->assRow['data_by_role'][3]['status'] == 1 && $this->assRow['subAssessmentType'] == 1 && ($this->isAdmin || $this->isSchoolAdmin || $this->isPrincipal || $this->isNetworkAdmin))
            return $this->printDataCell('<a class="execUrl manageReportBtn vtip iconBtn" data-size="880" href="?isPop=1&controller=assessment&action=reportList&diagnostic_id=' . $diagnostic_id . '&assessment_id=' . $this->assRow['assessment_id'] . '&group_assessment_id=' . $this->assRow['group_assessment_id'] . '&assessor_id=' . $this->assRow['data_by_role'][4]['user_id'] . '" title="Print/View Report"><i class="fa fa-print"></i></a>');
        elseif ((($this->currentRowIs == $this::CHILDROW_OF_GROUP_ASSESSMENT && $this->assRow['isTchrInfoFilled'] == 1 && (($this->user['user_id'] == $group_asmt_external || $this->isNetworkAdmin) && intval($this->assRow['data_by_role'][4]['percComplete']) == '100' )) || ($this->currentRowIs == $this::CHILDROW_OF_GROUP_ASSESSMENT && $this->isAdmin && !empty($this->assRow['data_by_role'][4]['status']) && $this->assRow['data_by_role'][4]['status'] == 1) ) || $this->printReportColInGA())
            return $this->printDataCell('<a class="execUrl manageReportBtn vtip iconBtn" data-size="880" href="?isPop=1&controller=assessment&action=reportList&diagnostic_id=' . $diagnostic_id . '&assessment_id=' . $this->assRow['assessment_id'] . '&group_assessment_id=' . $this->assRow['group_assessment_id'] . '&greporttype=' . $this->assRow['assessment_type_id'] . '&assessor_id=' . $this->assRow['data_by_role'][4]['user_id'] . '" title="Print/View Report"><i class="fa fa-print"></i></a>');
        elseif ((($this->currentRowIs == $this::CHILDROW_OF_STUDENT_ASSESSMENT && $this->assRow['isTchrInfoFilled'] == 1 && (($this->user['user_id'] == $group_asmt_external || $this->isNetworkAdmin) && intval($this->assRow['data_by_role'][4]['percComplete']) == '100' )) || ($this->currentRowIs == $this::CHILDROW_OF_STUDENT_ASSESSMENT && $this->isAdmin && !empty($this->assRow['data_by_role'][4]['status']) && $this->assRow['data_by_role'][4]['status'] == 1) ) || $this->printReportColInGA())
            return $this->printDataCell('<a class="execUrl manageReportBtn vtip iconBtn" data-size="880" href="?isPop=1&controller=assessment&action=reportList&diagnostic_id=' . $diagnostic_id . '&assessment_id=' . $this->assRow['assessment_id'] . '&group_assessment_id=' . $this->assRow['group_assessment_id'] . '&greporttype=' . $this->assRow['assessment_type_id'] . '&assessor_id=' . $this->assRow['data_by_role'][4]['user_id'] . '" title="Print/View Report"><i class="fa fa-print"></i></a>');
        elseif (isset($this->assRow['data_by_role']['4']['user_ids']) && in_array($this->user['user_id'], $this->assRow['data_by_role']['4']['user_ids']) && in_array(1, $this->assRow['data_by_role']['4']['allStatuses']) && $this->assRow['assessment_type_id'] == 4)
            return $this->printDataCell('<a class="execUrl manageReportBtn vtip iconBtn" data-size="880" href="?isPop=1&controller=assessment&action=reportList&diagnostic_id=' . $diagnostic_id . '&assessment_id=' . $this->assRow['assessment_id'] . '&group_assessment_id=' . $this->assRow['group_assessment_id'] . '&greporttype=' . $this->assRow['assessment_type_id'] . '&external_download_teacher=' . $this->user['user_id'] . '&assessor_id=' . $this->assRow['data_by_role'][4]['user_id'] . '" title="Print/View Report"><i class="fa fa-print"></i></a>');

        return $this->printDataCell('&nbsp;');
    }

    /**
     * print assessment Edit
     * 
     */
    private function printEditColumn() {
        $text = "";
        if (($this->assRow['aqs_status'] == 1 || $this->assRow['data_by_role'][4]['status'] == 1) && $this->isAdmin) {
            $text .= '<a class="execUrl  vtip" title="Unlock Settings" href="?isPop=1&controller=assessment&action=unlockassessment&diagnostic_id=' . $this->assRow['diagnostic_id'] . '&assessment_id=' . $this->assRow['assessment_id'] . '&group_assessment_id=' . $this->assRow['group_assessment_id'] . '&greporttype=' . $this->assRow['assessment_type_id'] . '&profile_status=' . $this->assRow['aqs_status'] . '&is_filled=' . $this->assRow['data_by_role'][4]['status'] . '&external_download_teacher=' . $this->user['user_id'] . '&assessor_id=' . $this->assRow['data_by_role'][4]['user_id'] . '" class="mLR"><i class="fa fa-unlock-alt"></i></a>';
        }

        if ($this->currentRowIs == $this::GROUP_ASSESSMENT_ROW && ($this->canCreate)) {
            return $this->printDataCell('<a href="?isPop=1&controller=assessment&action=editTeacherAssessment&assessment_type=' . $this->assRow['iscollebrative'] . '&amp;gaid=' . $this->assRow['group_assessment_id'] . '">' . ($this->assRow['assessments_count'] == 0 ? '<i title="Edit" class="vtip glyphicon glyphicon-pencil"></i>' : '<i title="View/Edit" class="vtip glyphicon glyphicon-pencil"></i>') . '</a>' . $text);
        } else if ($this->currentRowIs == $this::GROUP_ASSESSMENT_ROW && ($this->isAdminOrNadminOrPrincipal || $this->assRow['admin_user_id'] == $this->user['user_id'])) {
            return $this->printDataCell('<a href="?isPop=1&controller=assessment&action=createTeacherAssessor&assessment_type=' . $this->assRow['iscollebrative'] . '&amp;taid=' . $this->assRow['group_assessment_id'] . '">' . ($this->assRow['assessments_count'] == 0 ? '<i title="Edit" class="vtip glyphicon glyphicon-pencil"></i>' : '<i title="View/Edit" class="vtip glyphicon glyphicon-pencil"></i>') . '</a>' . $text);
        } else if ($this->currentRowIs == $this::GROUP_ASSESSMENT_STUDENT_ROW && ($this->canCreate)) {
            return $this->printDataCell('<a href="?isPop=1&controller=assessment&action=editStudentAssessment&assessment_type=' . $this->assRow['iscollebrative'] . '&amp;gaid=' . $this->assRow['group_assessment_id'] . '">' . ($this->assRow['assessments_count'] == 0 ? '<i title="Edit" class="vtip glyphicon glyphicon-pencil"></i>' : '<i title="View/Edit" class="vtip glyphicon glyphicon-pencil"></i>') . '</a>' . $text);
        } else if ($this->currentRowIs == $this::GROUP_ASSESSMENT_STUDENT_ROW && ($this->isAdminOrNadminOrPrincipal || $this->assRow['admin_user_id'] == $this->user['user_id'])) {

            return $this->printDataCell('<a href="?isPop=1&controller=assessment&action=createStudentAssessor&assessment_type=' . $this->assRow['iscollebrative'] . '&amp;taid=' . $this->assRow['group_assessment_id'] . '">' . ($this->assRow['assessments_count'] == 0 ? '<i title="Edit" class="vtip glyphicon glyphicon-pencil"></i>' : '<i title="View/Edit" class="vtip glyphicon glyphicon-pencil"></i>') . '</a>' . $text);
        } else if ($this->currentRowIs == $this::SCHOOL_ASSESSMENT_ROW && $this->assRow['assessment_type_id'] == 5 && ($this->isAdminOrNadminOrPrincipal || $this->assRow['admin_user_id'] == $this->user['user_id']) && $this->assRow['subAssessmentType'] == 1) {
            return $this->printDataCell('');
        } else if ($this->currentRowIs == $this::SCHOOL_ASSESSMENT_ROW && $this->assRow['assessment_type_id'] == 5 && ($this->isAdmin || $this->assRow['admin_user_id'] == $this->user['user_id'])) {
            return $this->printDataCell('<a href="?isPop=1&controller=assessment&action=editCollegeAssessment&assessment_type=' . $this->assRow['iscollebrative'] . '&amp;said=' . $this->assRow['assessment_id'] . '"><i title="Edit" class="vtip glyphicon glyphicon-pencil"></i></a>' . $text);
        } else if ($this->currentRowIs == $this::SCHOOL_ASSESSMENT_ROW && ($this->isAdmin || $this->assRow['admin_user_id'] == $this->user['user_id'])) {
            return $this->printDataCell('<a href="?isPop=1&controller=assessment&action=editSchoolAssessment&assessment_type=' . $this->assRow['iscollebrative'] . '&amp;said=' . $this->assRow['assessment_id'] . '"><i title="Edit" class="vtip glyphicon glyphicon-pencil"></i></a>' . $text);
        } else if ($this->currentRowIs == $this::SCHOOL_ASSESSMENT_ROW && $this->isAnyLead >= 1 && (isset($this->assRow['data_by_role'][4]['user_id']) && $this->assRow['data_by_role'][4]['user_id'] == $this->user['user_id']) && $this->assRow['iscollebrative']) {
            return $this->printDataCell('<a href="?isPop=1&controller=assessment&action=editSchoolAssessment&isLead=1&assessment_type=' . $this->assRow['iscollebrative'] . '&iscollebrative=' . $this->assRow['iscollebrative'] . '&amp;said=' . $this->assRow['assessment_id'] . '"><i title="Edit" class="vtip glyphicon glyphicon-pencil"></i></a>' . $text);
        }
        return $this->printDataCell('&nbsp;');
    }

    /**
     * print assessment progress
     * 
     */
    private function getAssProgressColumn($roleId) {
        $text = '';
        $percentage = '';
        $reviewType = '';
        $externalTeam = array();
        $takeReview = 0;
        $is_collaborative = 0;
        $is_external = 0;
        $title = '';
        $reviewType = '';
        $kpa = '';
        $percentage = isset($this->assRow['data_by_role'][$roleId]['percComplete']) ? $this->assRow['data_by_role'][$roleId]['percComplete'] : '0.00';
        $kpa_user = array();
        if (isset($this->assRow['iscollebrative']) && $this->assRow['iscollebrative'] == 1) {
            $is_collaborative = 1;
            $kpa = $this->assRow['kpa'];
            $kpa_user = explode(',', $this->assRow['kpa_user']);
        }
        if ($roleId == 4) {
            if ((isset($this->assRow['iscollebrative']) && $this->assRow['iscollebrative'] == 1) && !empty($this->assRow['externalTeam'])) {
                $externalTeam = explode(",", $this->assRow['externalTeam']);
                $externalTeam[] = $this->assRow['data_by_role'][$roleId]['user_id'];
                if (in_array($this->user['user_id'], $externalTeam)) {
                    $takeReview = 1;
                    if (!empty($this->assRow['extFilled']))
                        $is_external = 1;
                    $percentage = !empty($this->assRow['externalPercntage']) ? $this->assRow['externalPercntage'] : '0.00';
                }
            }else if (isset($this->assRow['iscollebrative']) && $this->assRow['iscollebrative'] == 1 && $this->isAdmin) {
                $percentage = !empty($this->assRow['avg']) ? $this->assRow['avg'] : '0.00';
            }
        } else if (isset($this->assRow['iscollebrative']) && $this->assRow['iscollebrative'] == 1) {
            $takeReview = 1;
        }
        if ($roleId == 3)
            $reviewType = ($roleId == 3) ? "sr" : "";
        else if ($roleId == 4)
            $reviewType = ($roleId == 4) ? "er" : "";

        if ($this->assRow['assessment_id'] > 0) {
            $optionCount = 0;
            $selfReview = 1;
            $divCount = 0;

            $row = $roleId == 4 ? (empty($this->assRow['data_by_role'][$roleId]['user_name']) ? ('<span>0.00%</span>') : ('<span>' . $percentage . '%</span>')) : ('<span >' . $percentage . '%</span>');
            if ($roleId == 4)
                $title = (empty($this->assRow['data_by_role'][$roleId]['user_name']) ? ('External Review') : ('<span>External Review:</span>' . $this->assRow['data_by_role'][$roleId]['user_name']));
            else if (empty($is_collaborative))
                $title = ('<span >Self Review:</span>' . $this->assRow['data_by_role'][$roleId]['user_name']);

            if ($roleId == 4) {
                $text .= ' <div class=" merge dd ' . $reviewType . ' ">' . $row;
                $divCount = 1;
            } else if (empty($is_collaborative)) {
                $text .= ' <div class=" merge dd ' . $reviewType . ' ">' . $row;
                $divCount = 1;
            }
            if ($this->assRow['iscollebrative'] && $roleId == 3) {
                $text = '';
                $selfReview = 0;
                $title = '';
            } else if ($this->assRow['iscollebrative'] && $roleId == 4 && !(!empty($kpa) && in_array($this->user['user_id'], $kpa_user)) && !$this->isAdmin) {
                $text = ' <div class=" merge ' . $reviewType . ' ">' . $row;
            }
            if ($takeReview == 1 && $roleId == 4 && $this->assRow['isFilled'] != 1 && $is_external == 0) {
                $aqsDate = DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime($this->assRow['aqs_start_date'])));
                $today = DateTime::createFromFormat('Y-m-d', date('Y-m-d'));
                $optionCount++;
                if ($is_collaborative) {
                    if (!empty($kpa) && in_array($this->user['user_id'], $kpa_user))
                        $text .= $this->assRow['subAssessmentType'] == 1 || $today >= $aqsDate ? '<div class="subOptions"><a  href="?isPop=1&controller=diagnostic&action=assessmentForm&assessment_id=' . $this->assRow['assessment_id'] . '&assessor_id=' . $this->user['user_id'] . '&external=1" >Take Review</a></div>' : '';
                } else
                    $text .= $this->assRow['subAssessmentType'] == 1 || $today >= $aqsDate ? '<div class="subOptions"><a  href="?isPop=1&controller=diagnostic&action=assessmentForm&assessment_id=' . $this->assRow['assessment_id'] . '&assessor_id=' . $this->user['user_id'] . '&external=1" >Take Review</a></div>' : '';
            }else if ($takeReview == 1 && $roleId == 4 && $is_external == 1) {
                $title = ('<span >External Review:</span>' . $this->user['name']);
                $editViewText = 'View';
                $optionCount++;
                $text .= '<div class="subOptions"><h3>' . $title . '</h3> <a  href="?controller=diagnostic&action=assessmentForm&assessment_id=' . $this->assRow['assessment_id'] . '&assessor_id=' . $this->user['user_id'] . '&external=1" >' . $editViewText . '</a></div>';
            } else if (isset($this->assRow['data_by_role'][$roleId]['status']) && $this->assRow['data_by_role'][$roleId]['status'] == 1) {
                $isReportPublished = empty($this->assRow['report_data']) || $this->assRow['report_data'][0]['isPublished'] != 1 ? false : true;
                if ($this->user['user_id'] == $this->assRow['data_by_role'][$roleId]['user_id'] || $this->isAdmin || ($roleId == 3 && $this->user['user_id'] == $this->assRow['data_by_role'][4]['user_id'] && $this->assRow['assessment_type_id'] == 2 && $this->assRow['data_by_role'][$roleId]['status'] == 1) || ($roleId == 3 && (($this->isSchoolAdmin && $this->assRow['client_id'] == $this->user['client_id']) || ($this->isNetworkAdmin && $this->assRow['network_id'] == $this->user['network_id'])) )) {
                    if (($roleId == 3 && $is_collaborative == 0) || ($roleId == 4 )) {
                        $editViewText = $this->canEditAfterSubmit && !$isReportPublished ? 'Edit' : 'View';
                        $optionCount++;
                        $text .= '<div class="subOptions"><h3>' . $title . '</h3> <a  href="?controller=diagnostic&action=assessmentForm&assessment_id=' . $this->assRow['assessment_id'] . '&assessor_id=' . $this->assRow['data_by_role'][$roleId]['user_id'] . '" >' . $editViewText . '</a></div>';
                    }
                }
            } else if (empty($takeReview) && isset($this->assRow['aqs_start_date']) && isset($this->assRow['data_by_role'][$roleId]['user_id']) && $this->user['user_id'] == $this->assRow['data_by_role'][$roleId]['user_id']) {
                $aqsDate = DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime($this->assRow['aqs_start_date'])));
                $today = DateTime::createFromFormat('Y-m-d', date('Y-m-d'));
                $optionCount++;
                if ($is_collaborative) {
                    if (!empty($kpa) && in_array($this->user['user_id'], $kpa_user))
                        $text .= $this->assRow['subAssessmentType'] == 1 || $today >= $aqsDate ? '<div class="subOptions"><a  href="?isPop=1&controller=diagnostic&action=assessmentForm&assessment_id=' . $this->assRow['assessment_id'] . '&assessor_id=' . $this->assRow['data_by_role'][$roleId]['user_id'] . '" >Take Review</a></div>' : '';
                } else
                    $text .= $this->assRow['subAssessmentType'] == 1 || $today >= $aqsDate ? '<div class="subOptions"><a  href="?isPop=1&controller=diagnostic&action=assessmentForm&assessment_id=' . $this->assRow['assessment_id'] . '&assessor_id=' . $this->assRow['data_by_role'][$roleId]['user_id'] . '" >Take Review</a></div>' : '';
            }else if ($selfReview && $this->currentRowIs == $this::CHILDROW_OF_GROUP_ASSESSMENT && $this->user['user_id'] == $this->assRow['data_by_role'][$roleId]['user_id']) {

                if ($is_collaborative) {
                    if (!empty($kpa) && in_array($this->user['user_id'], $kpa_user))
                        $text .= '<div class="subOptions"><h3>' . $title . '</h3><a href="?isPop=1&controller=diagnostic&action=assessmentForm&assessment_id=' . $this->assRow['assessment_id'] . '&assessor_id=' . $this->assRow['data_by_role'][$roleId]['user_id'] . '" >Take Review</a></div>';
                } else
                    $text .= '<div class="subOptions"><h3>' . $title . '</h3><a href="?isPop=1&controller=diagnostic&action=assessmentForm&assessment_id=' . $this->assRow['assessment_id'] . '&assessor_id=' . $this->assRow['data_by_role'][$roleId]['user_id'] . '" >Take Review</a></div>';
                $optionCount++;
            }else if ($selfReview && $this->currentRowIs == $this::CHILDROW_OF_STUDENT_ASSESSMENT && $this->user['user_id'] == $this->assRow['data_by_role'][$roleId]['user_id']) {

                if ($is_collaborative) {
                    if (!empty($kpa) && in_array($this->user['user_id'], $kpa_user))
                        $text .= '<div class="subOptions"><h3>' . $title . '</h3><a href="?isPop=1&controller=diagnostic&action=assessmentForm&assessment_id=' . $this->assRow['assessment_id'] . '&assessor_id=' . $this->assRow['data_by_role'][$roleId]['user_id'] . '" >Take Review</a></div>';
                } else
                    $text .= '<div class="subOptions"><h3>' . $title . '</h3><a href="?isPop=1&controller=diagnostic&action=assessmentForm&assessment_id=' . $this->assRow['assessment_id'] . '&assessor_id=' . $this->assRow['data_by_role'][$roleId]['user_id'] . '" >Take Review</a></div>';
                $optionCount++;
            }else if (!empty($title)) {
                $text .= '<div class="subOptions"><h3>' . $title . '</h3></div>';
                $optionCount++;
            }

            if ($optionCount >= 1) {
                $text .= '</div>';
            } else if (!($roleId == 3 && $is_collaborative)) {
                $text = ' <div class=" merge ' . $reviewType . ' ">' . $row . '</div>';
            } else if ($divCount == 1) {
                $text .= '</div>';
            }
        } else if ($this->assRow['assessments_count'] > 0) {
            $data_by_role = $this->assRow['data_by_role'][4]['user_ids'];
            $data_by_status = $this->assRow['data_by_role'][3]['allStatuses'];
            $showdownload = 0;
            foreach ($data_by_role as $key => $val) {
                if ($this->user['user_id'] == $val && $data_by_status[$key] == 1) {
                    $showdownload = 1;
                }
            }
            if (($this->currentRowIs == $this::GROUP_ASSESSMENT_ROW || $this->currentRowIs == $this::GROUP_ASSESSMENT_STUDENT_ROW) && $this->assRow['data_by_role'][$roleId]['percComplete'] > 0 && (in_array("manage_all_users", $this->user['capabilities']) || (in_array($this->user['user_id'], $data_by_role) && $showdownload == 1))) {
                
            } else {
                $text .= '<div class=" merge dd ' . $reviewType . '">' . $this->assRow['data_by_role'][$roleId]['percComplete'] . '%' . ($this->assRow['data_by_role'][$roleId]['status'] == 1 ? '' : '') . "";
            }

            $text .= '</div>';
        }
        return $text;
    }

    /**
     * print assessment progress percent and action plan
     * 
     */
    private function printReviewProgressColumn() {
        $output = '<div class="statsWrap">';
        $output .= $this->getAssProgressColumn(3);
        if ($this->currentRowIs == $this::GROUP_ASSESSMENT_ROW || $this->currentRowIs == $this::CHILDROW_OF_GROUP_ASSESSMENT || $this->currentRowIs == $this::CHILDROW_OF_STUDENT_ASSESSMENT || (isset($this->assRow['subAssessmentType']) && $this->assRow['subAssessmentType'] != 1)) {
            $output .= $this->getAssProgressColumn(4);
        }
        $output .= $this->printActionPlanColumn();
        $output .= "</div>";

        return $this->printDataCell($output, 'merged');
    }

    /**
     * print td for all column
     * 
     */
    private function printDataCell($text, $cssCls = '') {
        return "<td" . ($cssCls == '' ? '' : ' class="' . $cssCls . '"') . ($this->disableAssRow ? ' disabled ' : '') . ">$text</td>";
    }

    private function printReportColInGA() {

        if ($this->currentRowIs == $this::GROUP_ASSESSMENT_ROW && $this->assRow['assessments_count'] > 0 && ($this->isAdmin)) {
            for ($i = 0; $i < $this->assRow['assessments_count']; $i++) {
                if (isset($this->assRow['data_by_role'][4]['allStatuses'][$i]) && $this->assRow['data_by_role'][4]['allStatuses'][$i] == 1 && $this->assRow['teacherInfoStatuses'][$i] == 1) {
                    return true;
                }
            }
        }

        if ($this->currentRowIs == $this::GROUP_ASSESSMENT_STUDENT_ROW && $this->assRow['assessments_count'] > 0 && ($this->isAdmin)) {
            for ($i = 0; $i < $this->assRow['assessments_count']; $i++) {
                if (isset($this->assRow['data_by_role'][4]['allStatuses'][$i]) && $this->assRow['data_by_role'][4]['allStatuses'][$i] == 1 && $this->assRow['teacherInfoStatuses'][$i] == 1) {
                    return true;
                }
            }
        }

        if ($this->currentRowIs == $this::GROUP_ASSESSMENT_ROW && $this->assRow['assessments_count'] > 0 && ($this->isSchoolAdmin || $this->isPrincipal || $this->isNetworkAdmin )) {

            $anyReportPublished = 0;
            $report_data = explode(',', $this->assRow['report_data']);
            for ($i = 0; $i < $this->assRow['assessments_count']; $i++) {
                if ($this->assRow['teacherInfoStatuses'][$i] == 1 && $this->assRow['data_by_role'][4]['allStatuses'][$i] == 1 && intval($this->assRow['data_by_role'][4]['percComplete']) > 0) {
                    $check_published = empty($report_data[$i]) ? array() : explode('|', $report_data[$i]);
                    $anyReportPublished = $anyReportPublished || (empty($check_published[1]) ? 0 : $check_published[1]);
                    return !$anyReportPublished;
                }
            }
        }

        if ($this->currentRowIs == $this::GROUP_ASSESSMENT_STUDENT_ROW && $this->assRow['assessments_count'] > 0 && ($this->isSchoolAdmin || $this->isPrincipal || $this->isNetworkAdmin )) {

            $anyReportPublished = 0;
            $report_data = explode(',', $this->assRow['report_data']);
            for ($i = 0; $i < $this->assRow['assessments_count']; $i++) {
                if ($this->assRow['teacherInfoStatuses'][$i] == 1 && $this->assRow['data_by_role'][4]['allStatuses'][$i] == 1 && intval($this->assRow['data_by_role'][4]['percComplete']) > 0) {
                    $check_published = empty($report_data[$i]) ? array() : explode('|', $report_data[$i]);
                    $anyReportPublished = $anyReportPublished || (empty($check_published[1]) ? 0 : $check_published[1]);
                    return !$anyReportPublished;
                }
            }
        }

        $group_asmt_external = empty($this->assRow['data_by_role'][4]['user_id']) ? 0 : $this->assRow['data_by_role'][4]['user_id'];
        $group_asmt_internal = empty($this->assRow['data_by_role'][3]['user_id']) ? 0 : $this->assRow['data_by_role'][3]['user_id'];

        if ($this->currentRowIs == $this::CHILDROW_OF_GROUP_ASSESSMENT && $this->user['user_id'] == $group_asmt_internal) {
            $anyReportPublished = 0;
            return $anyReportPublished;
        }

        if ($this->currentRowIs == $this::CHILDROW_OF_GROUP_ASSESSMENT && ($this->isPrincipal || $this->isSchoolAdmin)) {
            $anyReportPublished = 0;

            foreach ($this->assRow['report_data'] as $key => $val) {
                $check_published = $val['isPublished'] == 1 ? true : false;
                return $check_published;
            }
        }




        if ($this->currentRowIs == $this::CHILDROW_OF_STUDENT_ASSESSMENT && $this->user['user_id'] == $group_asmt_internal) {
            $anyReportPublished = 0;
            return $anyReportPublished;
        }


        if ($this->currentRowIs == $this::CHILDROW_OF_STUDENT_ASSESSMENT && ($this->isPrincipal || $this->isSchoolAdmin)) {
            $anyReportPublished = 0;

            foreach ($this->assRow['report_data'] as $key => $val) {
                $check_published = $val['isPublished'] == 1 ? true : false;
                return $check_published;
            }
        }
        return false;
    }

}
