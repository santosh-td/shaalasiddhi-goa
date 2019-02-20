<?php

/**
 * Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 * 
 * Reasons: Manage Action Plan class
 */
class actionplanController extends controller {
    /** Show the action plan list row
     * @paerams:$assessor_key_notes_id_c,$current_stackholder,$current_impact,$currentfrom_date,$currentto_date
     */

    function actionplan1Action() {
        $assessment_id = empty($_REQUEST['assessment_id']) ? 0 : $_REQUEST['assessment_id'];
        $lang_id = empty($_REQUEST['lang_id']) ? DEFAULT_LANGUAGE : $_REQUEST['lang_id'];
        $type = "recommendation";
        $this->set("assessment_id", $assessment_id);
        $diagnosticModel = new diagnosticModel();
        $assessmentModel = new assessmentModel();
        $aqsDataModel = new aqsDataModel();
        $prefferedLanguage = $diagnosticModel->getAssessmentPrefferedLanguage($assessment_id);
        $lang_id = isset($prefferedLanguage['language_id']) ? $prefferedLanguage['language_id'] : DEFAULT_LANGUAGE;
        $assessment_details = $diagnosticModel->getAssessmentById($assessment_id, $lang_id, 1);
        $isSchoolSelfReview = 0;
        if (!empty($assessment_details)) {
            if ($assessment_details['assessment_type_id'] == 1 && $assessment_details['subAssessmentType'] == 1) {
                $isSchoolSelfReview = 1;
            }
        }
        $this->set("isSchoolSelfReview", $isSchoolSelfReview);
        $roleids = $this->user['role_ids'];
        $user_id = $this->user['user_id'];
        $this->set("roleID_all", $roleids);
        $this->set("userID", $user_id);

        if (in_array("1", $roleids) || in_array("2", $roleids) || in_array("5", $roleids) || in_array("6", $roleids) || in_array("12", $roleids)) {
            $isleader = false;
            $disabled = 0;
        } else if (in_array("3", $roleids)) {
            $isleader = true;
            $disabled = 1;
        }

        if ((in_array("1", $roleids) || in_array("2", $roleids)) || (in_array("3", $roleids) || in_array("5", $roleids) || in_array("6", $roleids) && $assessment_details['client_id'] == $this->user['client_id'])) {
            $school_name = $assessment_details['client_name'];
            if (!empty($assessment_details['city'])) {
                $school_name .= "," . $assessment_details['city'];
            }
            if (!empty($assessment_details['state'])) {
                $school_name .= "," . $assessment_details['state'];
            }
            $this->set("akns", $diagnosticModel->getAssessorKeyNotesTypeOrder($assessment_id, $type, DEFAULT_LANGUAGE, $this->user['role_ids'], $this->user['user_id']));

            $designations = $aqsDataModel->getDesignations();

            $users = $assessmentModel->getAllSchoolUsers($assessment_details['client_id']);

            $this->set("designations", $designations);
            $this->set("school_name", $school_name);
            $this->set("assessment_details", $assessment_details);
            $this->set("users", $users);
            $frequency = $assessmentModel->getfrequency();
            $this->set("frequency", $frequency);

            $this->set("assessmentModel", $assessmentModel);
            $this->set("assessment_id", $assessment_id);
            $this->set("isleader", $isleader);
            $this->set("disabled", $disabled);

            $this->_template->addHeaderStyle('jquery.mCustomScrollbar.min.css');
            $this->_template->addHeaderScript('jquery.mCustomScrollbar.concat.min.js');
            $this->_template->addHeaderStyle('bootstrap-select-n.css');
            $this->_template->addHeaderScript('bootstrap-select.min-n.js');

            $this->_template->addHeaderStyle('bootstrap-multiselect.css');
            $this->_template->addHeaderScript('bootstrap-multiselect.js');
            $this->_template->addHeaderScript('highcharts.js');
            $this->_template->addHeaderScript('exporting.js');

            $this->_template->addHeaderScript('actionplan.js');
        } else if ((in_array("12", $roleids))) {
            $school_name = $assessment_details['client_name'];
            if (!empty($assessment_details['city'])) {
                $school_name .= "," . $assessment_details['city'];
            }
            if (!empty($assessment_details['state'])) {
                $school_name .= "," . $assessment_details['state'];
            }
            $this->set("akns", $diagnosticModel->getAssessorKeyNotesTypeOrder($assessment_id, $type, DEFAULT_LANGUAGE, $this->user['role_ids'], $this->user['user_id']));

            $designations = $aqsDataModel->getDesignations();

            $users = $assessmentModel->getAllSchoolUsers($assessment_details['client_id']);

            $this->set("designations", $designations);
            $this->set("school_name", $school_name);
            $this->set("assessment_details", $assessment_details);
            $this->set("users", $users);
            $frequency = $assessmentModel->getfrequency();
            $this->set("frequency", $frequency);

            $this->set("assessmentModel", $assessmentModel);
            $this->set("assessment_id", $assessment_id);
            $this->set("isleader", $isleader);
            $this->set("disabled", $disabled);

            /*             * ***************schools of cluster ******************** */
            $clusters_details = $assessmentModel->getAllClusterDetail($this->user['user_id']);
            $cluster_ids = implode(',', array_unique(array_column($clusters_details, 'cluster_id')));
            $block_ids = implode(',', array_unique(array_column($clusters_details, 'block_id')));
            $zone_ids = implode(',', array_unique(array_column($clusters_details, 'zone_id')));
            $state_id = implode(',', array_unique(array_column($clusters_details, 'state_id')));

            $clusters_school = $assessmentModel->getAllClusterSchools($state_id, $zone_ids, $block_ids, $cluster_ids);
            $this->set("clusters_school", $clusters_school);
            /*             * ******************schools of cluster***************** */

            $this->_template->addHeaderStyle('jquery.mCustomScrollbar.min.css');
            $this->_template->addHeaderScript('jquery.mCustomScrollbar.concat.min.js');
            $this->_template->addHeaderStyle('bootstrap-select-n.css');
            $this->_template->addHeaderScript('bootstrap-select.min-n.js');

            $this->_template->addHeaderStyle('bootstrap-multiselect.css');
            $this->_template->addHeaderScript('bootstrap-multiselect.js');
            $this->_template->addHeaderScript('highcharts.js');
            $this->_template->addHeaderScript('exporting.js');
            $this->_template->addHeaderScript('actionplan.js');
        } else if (in_array("11", $roleids)) {
            $isleader = false;
            $disabled = 0;
            $userId = $this->user['user_id'];
            $objCustom = new customratingreportModel();
            $userData = $objCustom->userStateZoneBlockCluster($userId, 'zone');
            $dataZone = $this->db->array_grouping($userData, "zone_id");
            $this->set('zonelist', $dataZone);
            if ($assessment_id > 0) {
                $school_name = $assessment_details['client_name'];
                if (!empty($assessment_details['city'])) {
                    $school_name .= "," . $assessment_details['city'];
                }
                if (!empty($assessment_details['state'])) {
                    $school_name .= "," . $assessment_details['state'];
                }
                $this->set("akns", $diagnosticModel->getAssessorKeyNotesTypeOrder($assessment_id, $type, DEFAULT_LANGUAGE, $this->user['role_ids'], $this->user['user_id']));
                $designations = $aqsDataModel->getDesignations();

                $users = $assessmentModel->getAllSchoolUsers($assessment_details['client_id']);
                $objCustom = new actionModel();
                $data = [];
                $data['assessment_id'] = $assessment_id;
                $assData = $objCustom->getBlockClusterSchoolAssessments($data);
                $zoneid['zoneId'] = $assData[0]['zone_id'];
                $blockdata = $objCustom->getBlockClusterSchoolAssessments($zoneid);
                $blockList = $this->db->array_grouping($blockdata, "block_id");
                $this->set('blockList', $blockList);

                $zoneid['blockId'] = $assData[0]['block_id'];
                $clusterdata = $objCustom->getBlockClusterSchoolAssessments($zoneid);
                $clusterList = $this->db->array_grouping($clusterdata, "cluster_id");

                $this->set('clusterList', $clusterList);

                $zoneid['clusterId'] = $assData[0]['cluster_id'];
                $schooldata = $objCustom->getBlockClusterSchoolAssessments($zoneid);
                $schoolList = $this->db->array_grouping($schooldata, "client_id");
                $this->set('schoolList', $schoolList);

                $zoneid['schoolId'] = $assData[0]['client_id'];
                $rounddata = $objCustom->getBlockClusterSchoolAssessments($zoneid);
                $roundList = $this->db->array_grouping($rounddata, "aqs_round");
                $this->set('roundList', $roundList);
                $this->set('assdata', $assData['0']);
                $this->set("designations", $designations);
                $this->set("school_name", $school_name);
                $this->set("assessment_details", $assessment_details);
                $this->set("users", $users);
                $frequency = $assessmentModel->getfrequency();
                $this->set("frequency", $frequency);

                $this->set("assessmentModel", $assessmentModel);
                $this->set("assessment_id", $assessment_id);
                $this->set("isleader", $isleader);
                $this->set("disabled", $disabled);
            } else {
                $blockList = [];
                $roundList = [];
                $clusterList = [];
                $SchoolList = [];
            }
            $this->_template->addHeaderStyle('jquery.mCustomScrollbar.min.css');
            $this->_template->addHeaderScript('jquery.mCustomScrollbar.concat.min.js');
            $this->_template->addHeaderStyle('bootstrap-select-n.css');
            $this->_template->addHeaderScript('bootstrap-select.min-n.js');

            $this->_template->addHeaderStyle('bootstrap-multiselect.css');
            $this->_template->addHeaderScript('bootstrap-multiselect.js');
            $this->_template->addHeaderScript('highcharts.js');
            $this->_template->addHeaderScript('exporting.js');

            $this->_template->addHeaderScript('actionplan.js');
            $this->_template->renderTemplate('zoneactionplan1');
        } else {
            $this->_notPermitted = 1;
            return;
        }
    }

    /*     * Add pop for kpa and judgement statement
     * $assessor_key_notes_id_c:key note id,
     * $current_stackholder:stack hoder
     * $current_impact:impact row details
     * $currentfrom_date: start from date
     * $currentto_date: end date for action plan
     */

    function action1newAction() {

        $assessment_id = empty($_REQUEST['assessment_id']) ? 0 : $_REQUEST['assessment_id'];
        $lang_id = empty($_REQUEST['lang_id']) ? DEFAULT_LANGUAGE : $_REQUEST['lang_id'];
        $type = "recommendation";
        $this->set("assessment_id", $assessment_id);
        $actionModel = new actionModel();
        $kpajsHelper = new kpajsHelper();
        $this->set("kpajsHelper", $kpajsHelper);
        $this->set("actionModel", $actionModel);
        $this->set("lang_id", $lang_id);

        $kpas = $actionModel->getKpasForAssessmentNew($assessment_id, $lang_id);
        $this->set("kpas", $kpas);
        $this->_template->addHeaderStyle('jquery.mCustomScrollbar.min.css');
        $this->_template->addHeaderScript('jquery.mCustomScrollbar.concat.min.js');
        $this->_template->addHeaderStyle('bootstrap-select-n.css');
        $this->_template->addHeaderScript('bootstrap-select.min-n.js');
        $this->_template->addHeaderScript('actionplan.js');
    }

    /*     * Show the recommedations
     */

    function kparecommendationAction() {
        $diagnosticModel = new diagnosticModel();
        $rec_id = isset($_GET['rec_id']) ? $_GET['rec_id'] : 0;

        $recommendation = $diagnosticModel->getAssessorKeyNoteById($rec_id);
        $this->set("recommendation", $recommendation);
    }

    /*     * Show the details and manage action plan 2
     */

    function actionplan2Action() {
        $assessment_id = empty($_REQUEST['assessment_id']) ? 0 : $_REQUEST['assessment_id'];
        $lang_id = empty($_REQUEST['lang_id']) ? DEFAULT_LANGUAGE : $_REQUEST['lang_id'];
        $type = "recommendation";
        $this->set("assessment_id", $assessment_id);
        $id_c = empty($_REQUEST['id_c']) ? 0 : $_REQUEST['id_c'];
        $this->set("id_c", $id_c);
        $actionModel = new actionModel();
        $diagnosticModel = new diagnosticModel();
        $assessment_details = $diagnosticModel->getAssessmentById($assessment_id, $lang_id);
        $roleids = $this->user['role_ids'];
        $this->set('roleids', $roleids);
        $user_id = $this->user['user_id'];
        $this->set("roleID_all", $roleids);
        if (in_array("1", $roleids) || in_array("2", $roleids) || in_array("5", $roleids) || in_array("6", $roleids)) {
            $isleader = false;
        } else if (in_array("3", $roleids)) {
            $isleader = true;
        }
        if ((in_array("1", $roleids) || in_array("2", $roleids)) || (in_array("3", $roleids) || in_array("5", $roleids) || in_array("6", $roleids) && $assessment_details['client_id'] == $this->user['client_id']) || in_array("11", $roleids)) {

            $details = $actionModel->getDetailsofAssessment($id_c);
            $impactStatements = $actionModel->getDetailsofImpactStmnt($id_c);
            $impactStatementsDataRaw = $actionModel->getimpactStmntData($id_c, $assessment_id);
            if (!empty($impactStatementsDataRaw)) {
                $statementData = array();
                foreach ($impactStatementsDataRaw as $data) {
                    $statementData[$data['statement_id']][] = $data;
                }
                $this->set("statementData", $statementData);
            }
            $rating_date = '';
            if (!empty($assessment_details)) {
                if ($assessment_details['assessment_type_id'] == 1 && $assessment_details['subAssessmentType'] == 1) {
                    $rating_date = !empty($assessment_details['rating_date']) ? date("d-m-Y", strtotime($assessment_details['rating_date'])) : '';
                } else {
                    $rating_date = !empty($assessment_details['rating_date1']) ? date("d-m-Y", strtotime($assessment_details['rating_date1'])) : '';
                }
            }

            $this->set("methods", $actionModel->getImpactMethod());
            $this->set("rating_date", $rating_date);
            $this->set("details", $details);
            $this->set("assessment_details", $assessment_details);
            $this->set("impactStatements", $impactStatements);
            $h_assessor_action1_id = isset($details['h_assessor_action1_id']) ? $details['h_assessor_action1_id'] : '';
            $this->set("h_assessor_action1_id", $h_assessor_action1_id);
            $teamDetails = $actionModel->getTeamAction2($h_assessor_action1_id);
            $activityDetails = $actionModel->getActivityAction2($h_assessor_action1_id);
            $this->set("teamDetails", $teamDetails);
            $this->set("activityDetails", $activityDetails);
            $this->_template->addHeaderStyle('jquery.mCustomScrollbar.min.css');
            $this->_template->addHeaderScript('jquery.mCustomScrollbar.concat.min.js');
            $this->_template->addHeaderStyle('bootstrap-select-n.css');
            $this->_template->addHeaderScript('bootstrap-select.min-n.js');
            $this->_template->addHeaderStyle('bootstrap-multiselect.css');
            $this->_template->addHeaderScript('bootstrap-multiselect.js');
            $this->_template->addHeaderScript('code/highcharts.js');
            $this->_template->addHeaderScript('code/modules/exporting.js');
            $this->_template->addHeaderScript('actionplan.js');
        } else if ((in_array("12", $roleids))) {

            $details = $actionModel->getDetailsofAssessment($id_c);
            $impactStatements = $actionModel->getDetailsofImpactStmnt($id_c);
            $impactStatementsDataRaw = $actionModel->getimpactStmntData($id_c, $assessment_id);
            if (!empty($impactStatementsDataRaw)) {
                $statementData = array();
                foreach ($impactStatementsDataRaw as $data) {
                    $statementData[$data['statement_id']][] = $data;
                }
                $this->set("statementData", $statementData);
            }
            $rating_date = '';
            if (!empty($assessment_details)) {
                if ($assessment_details['assessment_type_id'] == 1 && $assessment_details['subAssessmentType'] == 1) {
                    $rating_date = !empty($assessment_details['rating_date']) ? date("d-m-Y", strtotime($assessment_details['rating_date'])) : '';
                } else {
                    $rating_date = !empty($assessment_details['rating_date1']) ? date("d-m-Y", strtotime($assessment_details['rating_date1'])) : '';
                }
            }

            $this->set("methods", $actionModel->getImpactMethod());
            $this->set("rating_date", $rating_date);
            $this->set("details", $details);
            $this->set("assessment_details", $assessment_details);
            $this->set("impactStatements", $impactStatements);
            $h_assessor_action1_id = isset($details['h_assessor_action1_id']) ? $details['h_assessor_action1_id'] : '';
            $this->set("h_assessor_action1_id", $h_assessor_action1_id);
            $teamDetails = $actionModel->getTeamAction2($h_assessor_action1_id);
            $activityDetails = $actionModel->getActivityAction2($h_assessor_action1_id);
            $this->set("teamDetails", $teamDetails);
            $this->set("activityDetails", $activityDetails);
            $this->_template->addHeaderStyle('jquery.mCustomScrollbar.min.css');
            $this->_template->addHeaderScript('jquery.mCustomScrollbar.concat.min.js');
            $this->_template->addHeaderStyle('bootstrap-select-n.css');
            $this->_template->addHeaderScript('bootstrap-select.min-n.js');
            $this->_template->addHeaderStyle('bootstrap-multiselect.css');
            $this->_template->addHeaderScript('bootstrap-multiselect.js');
            $this->_template->addHeaderScript('code/highcharts.js');
            $this->_template->addHeaderScript('code/modules/exporting.js');
            $this->_template->addHeaderScript('actionplan.js');
        } else {
            $this->_notPermitted = 1;
            return;
        }
    }

    /*     * Fetch and show dropdown for the cluster
     */

    function clusterListAction() {
        $objCustom = new actionModel();
        $clusterForBlockOld = $objCustom->getBlockClusterSchoolAssessments($_REQUEST);
        $clusterForBlockNew = $this->db->array_grouping($clusterForBlockOld, "cluster_id");
        $this->set('clusterList', $clusterForBlockNew);
    }

    /*     * Fetch and show dropdown for the block
     */

    function blockListAction() {
        $objCustom = new actionModel();
        $clusterForBlockOld = $objCustom->getBlockClusterSchoolAssessments($_REQUEST);
        $blockForZoneNew = $this->db->array_grouping($clusterForBlockOld, "block_id");
        $this->set('blockList', $blockForZoneNew);
    }

    /*     * Fetch and show dropdown for the school
     */

    function schoolListAction() {
        $objCustom = new actionModel();
        $clusterForBlockOld = $objCustom->getBlockClusterSchoolAssessments($_REQUEST);
        $blockForZoneNew = $this->db->array_grouping($clusterForBlockOld, "client_id");
        $this->set('schoolList', $blockForZoneNew);
    }

    /*     * Fetch and show dropdown for the round
     */

    function roundListAction() {
        $objCustom = new actionModel();
        $clusterForBlockOld = $objCustom->getBlockClusterSchoolAssessments($_REQUEST);
        $blockForZoneNew = $this->db->array_grouping($clusterForBlockOld, "aqs_round");
        $this->set('roundList', $blockForZoneNew);
    }

}
