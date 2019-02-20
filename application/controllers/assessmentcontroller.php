<?php

/**
 * Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 * 
 * Reasons: Manage Assessment section
 * 
 */
class assessmentController extends controller {

    /*Add new assessment data loading
     * 
     */
    function createSchoolAssessmentAction() {

        if ((in_array(6, $this->user['role_ids']) || in_array(5, $this->user['role_ids'])) && $this->user['has_view_video'] != 1 && $this->user['is_web'] == 1)//principal and school admin have to view video for self-review		
            $this->_notPermitted = 1;

        else if (in_array("create_assessment", $this->user['capabilities'])) {

            $clientModel = new clientModel();
            $languageCode = array('hi', 'en');
            $this->set("clients", $clientModel->getClients(array("client_institution_id" => 1, "max_rows" => -1)));

            $disabled = !in_array('assign_external_review_team', $this->user['capabilities']) ? 'disabled' : 0;
            $this->set('disabled', $disabled);

            $diagnosticModel = new diagnosticModel();
            $this->set("diagnostics", $diagnosticModel->getDiagnostics(array("assessment_type_id" => 1, "isPublished" => "yes"), 'all', 1));
            $assessmentModel = new assessmentModel();
            $this->set('externalReviewRoles', $assessmentModel->getReviewerSubRoles(4));
            $this->set('notifications', array());
            $this->set("aqsRounds", $assessmentModel->getRounds());
            $this->set("isPop", empty($_GET['ispop']) ? 0 : $_GET['ispop']);
            $this->_template->addHeaderStyle('bootstrap-multiselect.css');
            $this->_template->addHeaderScript('bootstrap-multiselect.js');
        } else
            $this->_notPermitted = 1;
    }

    /*
     * function to KPA list by schools 
     */

    function schoolAssessmentData($assessment_id) {

        $assessment_id = isset($assessment_id) ? $assessment_id : $_POST['assessment_id'];
        if (!in_array("create_assessment", $this->user ['capabilities']))
            $this->_notPermitted = 1;

        if (!empty($assessment_id)) {
            $assessment_id = $assessment_id;
            $clientModel = new clientModel();
            $this->set("clients", $clientModel->getClients(array("client_institution_id" => 1, "max_rows" => -1)));
            $this->set("assessment_id", $assessment_id);
            $this->set("review_type", 1);
            $diagnosticModel = new diagnosticModel();
            $this->set("diagnostics", $diagnosticModel->getDiagnostics(array("assessment_type_id" => 1, "isPublished" => "yes"), 'all', 1));
            $assessmentModel = new assessmentModel();
            $kaps = $assessmentModel->getAssessmentKpa($assessment_id);
            $assessmentKpas = array();
            foreach ($kaps as $data) {
                $assessmentKpas[$data['user_id']][] = $data['kpa_id'];
            }
            $this->set('assignedKpas', $assessmentKpas);
            $this->set('externalReviewRoles', $assessmentModel->getReviewerSubRoles(4));
            $this->set('assessmentUsers', $assessmentModel->getAssessmentUsers($assessment_id, 1));
            $assessment = $assessmentModel->getSchoolAssessment($assessment_id, 1);
            if (!empty($assessment['diagnostic_id'])) {
                $assessmentKpas = $assessmentModel->getSchoolAssessmentKpas($assessment_id, 9);
                $this->set("assessmentKpas", $assessmentKpas);
            }
        }
    }
    /*
     * Step to data list for scools
     */
    function schoolAssessmentDataAction() {

        $assessment_id = isset($assessment_id) ? $assessment_id : $_POST['assessment_id'];
        $editStatus = isset($_POST['editStatus']) ? $_POST['editStatus'] : 0;
        if (!(in_array("create_assessment", $this->user ['capabilities']) || $editStatus ))
            $this->_notPermitted = 1;
        if (!empty($assessment_id)) {
            $assessment_id = $assessment_id;
            $clientModel = new clientModel();
            $this->set("clients", $clientModel->getClients(array("client_institution_id" => 1, "max_rows" => -1)));
            $this->set("assessment_id", $assessment_id);
            $this->set("review_type", 1);
            $diagnosticModel = new diagnosticModel();
            $this->set("diagnostics", $diagnosticModel->getDiagnostics(array("assessment_type_id" => 1, "isPublished" => "yes"), 'all', 1));
            $assessmentModel = new assessmentModel();
            $kaps = $assessmentModel->getAssessmentKpa($assessment_id);
            $assessmentKpas = array();
            foreach ($kaps as $data) {
                $assessmentKpas[$data['user_id']][] = $data['kpa_id'];
            }
            $this->set('assignedKpas', $assessmentKpas);
            $this->set('externalReviewRoles', $assessmentModel->getReviewerSubRoles(4));
            $this->set('assessmentUsers', $assessmentModel->getAssessmentUsers($assessment_id, 1));
            $assessment = $assessmentModel->getSchoolAssessment($assessment_id, 1);
            if (!empty($assessment['diagnostic_id'])) {
                $assessmentKpas = $assessmentModel->getSchoolAssessmentKpas($assessment_id, 9);
                $this->set("assessmentKpas", $assessmentKpas);
            }
            $this->set("assessment", $assessment);
            $externalAssessors = $assessmentModel->getExternalAssessors($assessment['external_client']);
            $this->set("externalAssessors", $externalAssessors);
            $subroles = $assessment['subroles'];
            $subroles = explode(',', $subroles);
            $externalAssessorsTeam = array();
            foreach ($subroles as $role => $row) {
                $exTeamClientId = explode('_', $row);
                $exTeamClientId = $exTeamClientId[0];
                array_push($externalAssessorsTeam, $assessmentModel->getExternalAssessors($exTeamClientId));
            }
            $this->set("externalAssessorsTeam", $externalAssessorsTeam);
        }
    }


    
    /*Edit added assessment data loading
     * 
     */
    function editSchoolAssessmentAction() {

        $editStatus = 0;
        $isCollebrative = isset($_REQUEST['iscollebrative']) ? $_REQUEST['iscollebrative'] : 0;
        $assessmentType = isset($_REQUEST['assessment_type']) ? $_REQUEST['assessment_type'] : 0;
        $isLead = isset($_REQUEST['isLead']) ? $_REQUEST['isLead'] : 0;
        if ($isCollebrative == 1 && $isLead == 1) {
            $editStatus = 1;
        }
        if ((in_array(6, $this->user['role_ids']) || in_array(5, $this->user['role_ids'])) && $this->user['has_view_video'] != 1 && $this->user['is_web'] == 1)//principal and school admin have to view video for self-review		
            $this->_notPermitted = 1;

        else if ((in_array("create_assessment", $this->user['capabilities']) || $editStatus == 1)) {

            $assessment_id = empty($_GET['said']) ? 0 : $_GET['said'];
            $review_type = empty($_REQUEST['assessment_type']) ? 0 : $_REQUEST['assessment_type'];
            $isNewReview = empty($_REQUEST['new']) ? 0 : $_REQUEST['new'];

            $clientModel = new clientModel();

            $this->set("clients", $clientModel->getClients(array("client_institution_id" => 1, "max_rows" => -1)));

            $this->set("assessment_id", $assessment_id);
            $this->set("review_type", $review_type);
            $this->set("isNewReview", $isNewReview);

            $this->set("isPop", empty($_GET['ispop']) ? 0 : $_GET['ispop']);

            $diagnosticModel = new diagnosticModel();
            $disabled = !in_array('assign_external_review_team', $this->user['capabilities']) ? 'disabled' : 0;
            $this->set('disabled', $disabled);


            $this->set("diagnostics", $diagnosticModel->getDiagnostics(array("assessment_type_id" => 1, "isPublished" => "yes"), 'all', 1));
            $assessmentModel = new assessmentModel();
            //For Offline new assessment check security Start
            if (!in_array("view_all_assessments", $this->user['capabilities']) && !empty($assessmentModel->chkAssessmentOfflineStatus($assessment_id))) {
                $this->_redirect(createUrl(array("controller" => "assessment", "action" => "assessment")));
            }
            //For Offline new assessment check security Ends

            $this->set('externalReviewRoles', $assessmentModel->getReviewerSubRoles(4));
            $this->set('assessmentStatus', $assessmentModel->getAssessmentRatingStatus($assessment_id));
            if ($assessmentType) {
                $assessmentModel->getAssessmentRatingPercentage($assessment_id);
                $assessmentModel->getAssessmentRatingKpa($assessment_id);
                $this->set('assessmentRating', $assessmentModel->getAssessmentRatingPercentage($assessment_id));
                $this->set('assessmentRatingKpa', $assessmentModel->getAssessmentRatingKpa($assessment_id));
            }
            $this->set('allNotifications', $assessmentModel->getReviewNotifications());
            $this->set('editStatus', $editStatus);
            $notificationUsers = $assessmentModel->getReviewNotificationUsers($assessment_id);
            $assessmentNotifications = array();
            $assessmentReminders = array();
            if (!empty($notificationUsers)) {
                foreach ($notificationUsers as $users) {
                    if (array_key_exists($users['user_id'], $assessmentNotifications) && $users['type'] == 1) {
                        array_push($assessmentNotifications[$users['user_id']], $users['notification_id']);
                    } else if (array_key_exists($users['user_id'], $assessmentReminders) && $users['type'] == 2) {
                        array_push($assessmentReminders[$users['user_id']], $users['notification_id']);
                    } else if (!empty($users['notification_id']) && $users['type'] == 1)
                        $assessmentNotifications[$users['user_id']][] = $users['notification_id'];
                    else if (!empty($users['notification_id']) && $users['type'] == 2)
                        $assessmentReminders[$users['user_id']][] = $users['notification_id'];
                    else if ($users['type'] == 2)
                        $assessmentReminders['assessment_id'] = $users['assessment_id'];
                    else if ($users['type'] == 1)
                        $assessmentNotifications['assessment_id'] = $users['assessment_id'];
                }
            }
            $reimSheetUsers = $assessmentModel->getReviewReimSheetUsers($assessment_id);
            if (!empty($reimSheetUsers)) {
                $reimSheetUsers = array_column($reimSheetUsers, 'sheet_status', 'user_id');
            }
            $this->set('reviewNotifications', $assessmentNotifications);
            $this->set('reviewReminders', $assessmentReminders);
            $this->set('reimSheetUsers', $reimSheetUsers);
            $this->set('assessmentUsers', $assessmentModel->getAssessmentUsers($assessment_id, 1));
            $assessment = $assessmentModel->getSchoolAssessment($assessment_id, 1);
            $facilitators = $assessmentModel->getFacilitatorsDetails($assessment_id, 1);
            if (!empty($facilitators)) {
                $facilitatorsData = array();
                $facilitatorTeam = array();
                foreach ($facilitators as $data) {
                    $facilitatorTeam[$data['user_id']] = $assessmentModel->getFacilitators($data['client_id']);
                    $facilitatorsData[$data['user_id']] = $data;
                }
                $this->set("facilitators", $facilitatorsData);
                $this->set("facilitatorTeam", $facilitatorTeam);
            }
            $roundsUnusedi = $assessmentModel->getSchoolRemainingRounds($assessment['client_id']);
            $roundsUnusedf = array();
            foreach ($roundsUnusedi as $key => $val) {
                $roundsUnusedf[] = $val['aqs_round'];
            }
            $roundsUnusedf[] = $assessment['aqs_round'];
            $this->set("unusedRounds", $roundsUnusedf);

            $this->set("assessment", $assessment);

            $externalAssessors = $assessmentModel->getExternalAssessors($assessment['external_client']);

            $this->set("externalAssessors", $externalAssessors);

            $subroles = $assessment['subroles'];

            $subroles = explode(',', $subroles);

            $externalAssessorsTeam = array();

            foreach ($subroles as $role => $row) {

                $exTeamClientId = explode('_', $row);

                $exTeamClientId = $exTeamClientId[0];

                array_push($externalAssessorsTeam, $assessmentModel->getExternalAssessors($exTeamClientId));
            }
            $this->set("externalAssessorsTeam", $externalAssessorsTeam);
            $assessors = explode(',', $assessment['user_ids']);
            $this->set("hideDiagnostics", $assessmentModel->getDiagnosticsForInternalAssessor($assessment['client_id'], $assessors[0]));
            $this->set("aqsRounds", $assessmentModel->getRounds());
            $languageCode = array('hi', 'en');
            $this->set("languages", $diagnosticModel->getDiagnosticLanguages($assessment['diagnostic_id']));
            if (isset($_REQUEST['tab2']) && $_REQUEST['tab2'] == 1) {

                $this->schoolAssessmentData($assessment['assessment_id']);
                $this->set('step2', $_REQUEST['tab2']);
            }
        } else
            $this->_notPermitted = 1;

        $this->_template->addHeaderStyle('bootstrap-multiselect.css');
        $this->_template->addHeaderScript('bootstrap-multiselect.js');
        $this->_template->addHeaderScript('collaborative.js');
    }

    /*Data for show landing managemy review page
     * 
     */
    function assessmentAction() {
        $cPage = empty($_POST['page']) ? 1 : $_POST['page'];

        $order_by = empty($_POST['order_by']) ? "create_date" : $_POST['order_by'];

        $order_type = empty($_POST['order_type']) ? "desc" : $_POST['order_type'];
        // get aqs team review listing for tab admin on 02-08-2016 by Mohit Kumar
        $ref = !empty($_REQUEST['ref']) ? $_REQUEST['ref'] : 0;
        $ref_key = "REVIEW" . md5(time());
        if (isset($_REQUEST['ref']) && $_REQUEST['ref'] == 1 && current($this->user['role_ids']) == 8) {

            $alertIds = $this->db->getAlertContentIds('d_assessment', 'CREATE_REVIEW');
            $alertIds = !empty($alertIds) ? $alertIds['content_id'] : array();

            if (!empty($alertIds)) {
                $checkAlertRelation = $this->db->getAlertRelationIds(current($this->user['role_ids']), 'REVIEW');
                if (!empty($checkAlertRelation)) {
                    $this->db->update('h_alert_relation', array('alert_ids' => trim($alertIds)), array('login_user_role' => current($this->user['role_ids']), 'type' => 'REVIEW', 'id' => $checkAlertRelation['id']));
                } else {
                    $this->db->insert('h_alert_relation', array('alert_ids' => trim($alertIds), 'ref_key' => $ref_key, 'flag' => 1,
                        'login_user_role' => current($this->user['role_ids']), 'type' => 'REVIEW'));
                }
            }
        } else if (empty($_REQUEST['ref']) && current($this->user['role_ids']) == 8) {
            $this->db->delete('h_alert_relation', array('type' => 'REVIEW', 'login_user_role' => current($this->user['role_ids'])));
        }
        if ($ref == 1 && $ref_key != '') {
            $this->db->update('d_alerts', array('status' => 1, 'ref_key' => $ref_key), array('type' => 'CREATE_REVIEW', 'table_name' => 'd_assessment'));
        }

        if (isset($_REQUEST['aid']) && $_REQUEST['aid'] > 0) {
            $_POST['assessment_type_id'] = $_REQUEST['aid'];
        }
        $param = array(
            "client_name_like" => empty($_POST['client_name']) ? "" : $_POST['client_name'],
            "name_like" => empty($_POST['name']) ? "" : $_POST['name'],
            "diagnostic_id" => empty($_POST['diagnostic_id']) ? "" : $_POST['diagnostic_id'],
            "fdate_like" => empty($_POST['fdate']) ? "" : ChangeFormat($_POST['fdate'], "Y-m-d"),
            "edate_like" => empty($_POST['edate']) ? "" : ChangeFormat($_POST['edate'], "Y-m-d"),
            "status" => empty($_POST['status']) ? "" : $_POST['status'],
            "state_id" => empty($_POST['stat_id']) ? "" : $_POST['stat_id'],
            "zone_id" => empty($_POST['zone_id']) ? "" : $_POST['zone_id'],
            "network_id" => empty($_POST['network_id']) ? "" : $_POST['network_id'],
            "province_id" => empty($_POST['province_id']) ? "" : $_POST['province_id'],
            "assessment_type_id" => empty($_POST['assessment_type_id']) ? "" : $_POST['assessment_type_id'],
            "user_id" => "",
            "sub_role_user_id" => "",
            "page" => $cPage,
            "order_by" => $order_by,
            "order_type" => $order_type,
        );
        if ((in_array(6, $this->user['role_ids']) || in_array(5, $this->user['role_ids'])) && $this->user['has_view_video'] != 1 && $this->user['is_web'] == 1)//principal and school admin have to view video for self-review		
            $this->_notPermitted = 1;

        else if (!empty($_REQUEST['myAssessment'])) {

            $param['user_id'] = $this->user['user_id'];

            $param['client_id'] = 0;

            $param['state_id'] = 0;
            $param['zone_id'] = 0;
            $param['network_id'] = 0;
        } else if (in_array("view_all_assessments", $this->user['capabilities'])) {
            
        } else if (in_array("view_own_network_assessment", $this->user['capabilities'])) {

            $param['user_id'] = $this->user['user_id'];

            $param['state_id'] = $this->user['state_id'];
            $param['zone_id'] = $this->user['zone_id'];
            $param['network_id'] = $this->user['network_id'];
        } else if (in_array("view_own_institute_assessment", $this->user['capabilities'])) {

            $param['user_id'] = $this->user['user_id'];

            $param['client_id'] = $this->user['client_id'];

            $param['state_id'] = 0;
            $param['zone_id'] = 0;
            $param['network_id'] = 0;
        } else {

            $param['user_id'] = $this->user['user_id'];

            $param['client_id'] = 0;

            $param['state_id'] = 0;
            $param['zone_id'] = 0;
            $param['network_id'] = 0;
        }

        if (in_array("take_external_assessment", $this->user['capabilities']))
            $param['sub_role_user_id'] = $this->user['user_id'];
        $this->set("filterParam", $param);

        $assessmentModel = new assessmentModel();
        // make condition for tap admin on 18-05-2016 by Mohit Kumar
        if (in_array('8', $this->user['role_ids']) && count($this->user['role_ids']) == 1) {
            $tap_admin_id = $this->user['role_ids'][0];
        } else {
            $tap_admin_id = '';
        }
        if (isset($_REQUEST['uid']) && $_REQUEST['uid'] != '' && isset($_REQUEST['rid']) && $_REQUEST['rid'] != '') {
            $user_id = $_REQUEST['uid'];
            $rid = $_REQUEST['rid'];
        } else {
            $user_id = '';
            $rid = '';
        }
        $is_guest = (isset($this->user['is_guest']) && $this->user['is_guest']) ? $this->user['is_guest'] : 0;
        $this->set("assessmentList", $assessmentModel->getAssessmentList($param, $tap_admin_id, $user_id, $rid, current($this->user['role_ids']), $ref, $ref_key, $is_guest, $this->user['user_id']));
        $languageCode = explode(",", DIAGNOSTIC_LANG);
        $this->set("pages", $assessmentModel->getPageCount());

        $this->set("cPage", $cPage);

        $this->set("orderBy", $order_by);

        $this->set("orderType", $order_type);

        $networkModel = new networkModel();
        $diagnosticModel = new diagnosticModel();
        $this->set("isLead", $diagnosticModel->checkIsLead($this->user['user_id']));
        //Addead by prashant Start
        $this->set("states", $networkModel->checkGoaStateName());
        $this->set("zones", empty($_POST['stat_id']) ? array() : $networkModel->getZonesInStates($_POST['stat_id']));
        $this->set("networks", empty($_POST['zone_id']) ? array() : $networkModel->getBlocksInZones($_POST['stat_id'], $_POST['zone_id']));
        $this->set("provinces", empty($_POST['network_id']) ? array() : $networkModel->getClusterInZones($_POST['network_id'], $_POST['zone_id'], $_POST['stat_id']));
        //Addead by prashant Ends
        $this->set("diagnostics", $diagnosticModel->getDiagnostics(array("isPublished" => "yes"), 0, 1));
        $this->set("isFilter", empty($_GET['filter']) ? 0 : $_GET['filter']);
        $this->set("diagnosticsLanguage", $this->userModel->getTranslationLanguale($languageCode));
        $this->_template->addHeaderStyle('assessment-form.css');
        $this->_template->addHeaderStyle('jquery.mCustomScrollbar.min.css');
        $this->_template->addHeaderScript('jquery.mCustomScrollbar.concat.min.js');
        $this->_template->addHeaderScript('localize.js');
        $this->_template->addHeaderStyle('bootstrap-multiselect.css');
        $this->_template->addHeaderScript('bootstrap-multiselect.js');
        $this->_template->addHeaderScript('studentreport.js');
    }
    
    /*Report list for assessment module 
     * 
     */
    function reportListAction() {
        $diagnosticModel = new diagnosticModel();
        $assessmentModel = new assessmentModel();
        $assessment_id = empty($_GET['assessment_id']) ? 0 : $_GET['assessment_id'];
        $diagnostic_id = empty($_GET['diagnostic_id']) ? 0 : $_GET['diagnostic_id'];
        $assessor_id = empty($_GET['assessor_id']) ? 0 : $_GET['assessor_id'];
        $this->set("diagnosticsLanguage", $diagnosticModel->getDiagnosticLanguages($diagnostic_id));
        $this->set("assessor_id", $assessor_id);
        $group_assessment_id = empty($_GET['group_assessment_id']) ? 0 : $_GET['group_assessment_id'];
        $external_download_teacher = isset($_GET['external_download_teacher']) ? $_GET['external_download_teacher'] : 0;
        if ((in_array(6, $this->user['role_ids']) || in_array(5, $this->user['role_ids'])) && $this->user['has_view_video'] != 1 && $this->user['is_web'] == 1)//principal and school admin have to view video for self-review	
            $this->_notPermitted = 1;
        $prefferedLanguage = $diagnosticModel->getAssessmentPrefferedLanguage($assessment_id);
        $lang_id = isset($prefferedLanguage['language_id']) ? $prefferedLanguage['language_id'] : DEFAULT_LANGUAGE;
        if ($assessment_id > 0 && $assessment = $diagnosticModel->getAssessmentById($assessment_id, $lang_id)) {
            $isNetworkAdmin = ($assessment['network_id'] == $this->user['network_id'] && in_array(7, $this->user['role_ids'])) == 1 ? 1 : 0;
            $isSchoolReview = ($assessment['assessment_type_id'] == 1 || $assessment['assessment_type_id'] == 5) ? 1 : 0;
            $group_asmt_external = empty($assessment['userIdByRole'][4]) ? 0 : $assessment['userIdByRole'][4];
            $group_asmt_internal = empty($assessment['userIdByRole'][3]) ? 0 : $assessment['userIdByRole'][3];
            //Admin can view all the school review reports for the submitted reviews                                   
            if (in_array("view_all_assessments", $this->user['capabilities']) && in_array("generate_submitted_asmt_reports", $this->user['capabilities']) && $isSchoolReview > 0 && $assessment['subAssessmentType'] != 1 && $assessment['statuses'][0] == '1' && $assessment['statuses'][1] == '1')
                ;
            //External reviewer can view unsubmitted assessment reports of school reviews where he was the external reviewer if he has generate_unsubmitted_asmt_reports capability
            elseif (in_array("generate_unsubmitted_asmt_reports", $this->user['capabilities']) && $isSchoolReview > 0 && $assessment['subAssessmentType'] != 1  && $assessment['statuses'][0] == '1' && intval(explode(',', $assessment['perc'])[1]) == 100 && $this->user['user_id'] == $assessment['user_ids'][1] && $assessment['report_published'] != 1)
                ;
            //view published school reports of his own school if the capabilities contain view_published_own_school_reports                                    
            elseif ($this->user['client_id'] == $assessment['client_id'] && $isSchoolReview > 0 && $assessment['subAssessmentType'] != 1 && in_array("view_published_own_school_reports", $this->user['capabilities']) && $assessment['statuses'][0] == '1' && $assessment['statuses'][1] == '1' && $assessment['report_published'] == 1)
                ;
            //for online self-review, school-admin,principal and admin must be able to view report
            elseif ($isSchoolReview > 0  && $assessment['subAssessmentType'] == 1 && $assessment['statuses'][0] == 1 && (in_array(6, $this->user['role_ids']) || in_array(5, $this->user['role_ids']) || in_array(1, $this->user['role_ids']) || in_array(2, $this->user['role_ids']) ))
                ;
            //teacher reports - principal and school admin
            elseif ($isSchoolReview == 0 && ((in_array(6, $this->user['role_ids']) || in_array(5, $this->user['role_ids']))))
                ;
            //admin - reviewer
            elseif (($assessment['assessment_type_id'] == 2 || $assessment['assessment_type_id'] == 4) && $assessment_id > 0 && ($this->user['user_id'] == $group_asmt_external || $this->user['user_id'] == $group_asmt_internal))
                ;
            elseif ($isNetworkAdmin && $isSchoolReview == 0 && (in_array("generate_unsubmitted_asmt_reports", $this->user['capabilities']) || in_array("generate_submitted_asmt_reports", $this->user['capabilities'])))
                ;
            //teacher reports - admin
            elseif ($isSchoolReview == 0 && in_array("view_all_assessments", $this->user['capabilities']))
                ;
            else {
                $this->_notPermitted = 1;
                return;
            }
            //get number of kpas for review
            $res = $diagnosticModel->getNumberOfKpasDiagnostic($assessment['diagnostic_id']);
            $this->set('numKpas', $res['num']);
            $this->set("assessment", $assessment);
            $this->set("reports", $diagnosticModel->getReportsByAssessmentId($assessment_id, false));
        } else
            $this->_is404 = 1;
        
        $assessor_id = isset($assessment['user_ids'][1])?$assessment['user_ids'][1]:'';
        $this->set("assessment_id", $assessment_id);
        $this->set("assessor_id", $assessor_id);

        $this->set("group_assessment_id", $group_assessment_id);
    }
    
    /*Download School Evaluation report
     */
    function reportAllSchoolsAction() {

        $networkModel = new networkModel();
        $assessmentModel = new assessmentModel();
        $customreportModel = new customreportModel();
        $this->set("states", $networkModel->getStateList());
        $this->_template->addHeaderStyle('bootstrap-multiselect.css');
        $this->_template->addHeaderScript('bootstrap-multiselect.js');
        $this->_template->addHeaderScript('studentreport.js');
    }

    /*
     * function to update reimbursement sheet status
     */

    function reimSheetConfirmationAction() {

        $status = isset($_REQUEST['status']) ? $_REQUEST['status'] : 0;
        $this->set("status", $status);
    }

    function unlockassessmentAction() {
        $this->_template->addHeaderScript('bootstrap-multiselect.js');
        $this->_template->addHeaderScript('studentreport.js');
    }

}
