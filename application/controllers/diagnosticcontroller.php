<?php
/*
 * Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 * 
 * Perpose: Manage diagonostic module
 */
class diagnosticController extends controller {

    /** data for   diagnostic page
     *@params: Null
     */
    function diagnosticAction() {
        if ((in_array(6, $this->user['role_ids']) || in_array(5, $this->user['role_ids'])) && $this->user['has_view_video'] != 1 && $this->user['is_web'] == 1)//principal and school admin have to view video for self-review
            $this->_notPermitted = 1;
        elseif (in_array("manage_diagnostic", $this->user['capabilities'])) {

            $preferredLanguage = isset($_REQUEST['lang_id']) ? $_REQUEST['lang_id'] : 'all';

            $order_by = empty($_POST['order_by']) ? "name" : $_POST['order_by'];
            $order_type = empty($_POST['order_type']) ? "asc" : $_POST['order_type'];
            $param = array(
                "name_like" => empty($_POST['dia_name']) ? "" : $_POST['dia_name'],
                "isPublished" => empty($_POST['isPublished']) ? "" : $_POST['isPublished'],
                "assessment_type_id" => empty($_POST['assessment_type_id']) ? "" : $_POST['assessment_type_id'],
                "lang_id" => empty($preferredLanguage) ? "" : $preferredLanguage,
                "order_by" => $order_by,
                "order_type" => $order_type,
            );
            $languageCode = explode(",", DIAGNOSTIC_LANG);
            $this->set("filterParam", $param);
            $diagnosticModel = new diagnosticModel();
            $this->set("diagnostics", $diagnosticModel->getDiagnostics($param, $preferredLanguage));
            $this->set("diagnosticsLanguage", $this->userModel->getTranslationLanguale($languageCode));
            $this->set("orderBy", $order_by);
            $this->set("orderType", $order_type);
            $this->set("preferredLanguage", $preferredLanguage);
            $reviewType = $diagnosticModel->getAssessmentTypes();
            $reviewType_array = array();
            foreach ($reviewType as $key => $val) {
                if ($val['assessment_type_id'] == 3)
                    continue;
                $reviewType_array[] = $val;
            }

            $this->set("assessment_types", $reviewType_array);
        } else
            $this->_notPermitted = 1;
        $this->_template->addHeaderScript('localize.js');
    }
    /** data for   diagnostic form
     *@params: Null
     */
    function diagnosticFormAction() { 
        
        $langId = empty($_GET['langId']) ? 0 : $_GET['langId'];
        $diagnostic_id = empty($_GET['id']) ? 0 : $_GET['id'];
        $diagnostic_type = empty($_GET['diagnostic_type']) ? 0 : $_GET['diagnostic_type'];
        $diagnosticModel = new diagnosticModel();

        if ((in_array(6, $this->user['role_ids']) || in_array(5, $this->user['role_ids'])) && $this->user['has_view_video'] != 1 && $this->user['is_web'] == 1)//principal and school admin have to view video for self-review
            $this->_notPermitted = 1;
        elseif ($diagnostic_id > 0 && $diagnostic = $diagnosticModel->getDiagnosticBYLang($diagnostic_id,$langId)) {
            if (in_array("manage_diagnostic", $this->user['capabilities'])) {
                
               
                $this->set("kpas", $this->db->array_col_to_key($diagnosticModel->getKpasForDiagnosticLang($diagnostic_id,$langId), "kpa_instance_id"));
                    $this->set("kqs", $this->db->array_grouping($diagnosticModel->getKeyQuestionsForDiagnosticLang($diagnostic_id,$langId), "kpa_instance_id", "key_question_instance_id"));
                    $this->set("cqs", $this->db->array_grouping($diagnosticModel->getCoreQuestionsForDiagnosticLang($diagnostic_id,$langId), "key_question_instance_id", "core_question_instance_id"));
                    $this->set("jss", $this->db->array_grouping($diagnosticModel->getJudgementalStatementsForDiagnosticLang($diagnostic_id,$langId), "core_question_instance_id", "judgement_statement_instance_id"));
                    if($diagnostic_type == 1){
                        $ratingLevelText = $this->db->array_grouping($diagnosticModel->ratingLevalText(),'judgement_statement_instance_id');
                    }
                $this->set("ratingLevelText", $ratingLevelText);
                $this->set("diagnostic", $diagnostic);
                $this->set('ddiagnosticId', $diagnostic['diagnostic_id']);
                $this->set('kpaRecommendations', $diagnostic['kpa_recommendations']);
                $this->set('kqRecommendations', $diagnostic['kq_recommendations']);
                $this->set('cqRecommendations', $diagnostic['cq_recommendations']);
                $this->set('jsRecommendations', $diagnostic['js_recommendations']);
                $this->set('diagnostic_type', $diagnostic_type);
                $diagnosticLabels = array();                                               
                $diagnosticLabelsData = $diagnosticModel->getDiagnosticLabels($diagnostic_id,$langId);
                foreach($diagnosticLabelsData as $data) {
                    $diagnosticLabels[$data['label_key']] = $data['label_text'];
                }
                //print_r($diagnosticLabelsData);
                $this->set("diagnosticLabels", $diagnosticLabels);
                $dig_image = $diagnosticModel->getDiagnosticImage($diagnostic_id);
                $image_name = $dig_image[0]['file_name'];
                $this->set("image_name", $image_name);
                $this->_template->addHeaderScript('jquery.mCustomScrollbar.concat.min.js');
                $this->_template->addHeaderScript('bootstrap-multiselect-0.9.13.js');
                $this->_template->addHeaderStyle('jquery.mCustomScrollbar.min.css');
                $this->_template->addHeaderStyle('bootstrap-multiselect-0.9.13.css');
                $this->_template->addHeaderStyle('assessment-form.css');
                $this->_template->addFooterScript('assessment.js');
            } else
                $this->_notPermitted = 1;
        } else
            $this->_is404 = 1;
    }
    
    /** data for   assessment form
     *@params: Null
     */
    function assessmentFormAction() {

        $lang_id = empty($_GET['lang_id']) ? 0 : $_GET['lang_id'];
        $assessment_id = empty($_GET['assessment_id']) ? 0 : $_GET['assessment_id'];
        $assessor_id = empty($_GET['assessor_id']) ? $this->user['user_id'] : $_GET['assessor_id'];
        $isAdmin = in_array("view_all_assessments", $this->user['capabilities']) ? true : false;
        $isNetworkAdmin = in_array("view_own_network_assessment", $this->user['capabilities']) && $this->user['network_id'] > 0 ? true : false;
        $isSchoolAdmin = in_array("view_own_institute_assessment", $this->user['capabilities']) ? true : false;
        $diagnosticModel = new diagnosticModel();
        $prefferedLanguage = $diagnosticModel->getAssessmentPrefferedLanguage($assessment_id);
        $lang_id_show = empty($lang_id) ? $prefferedLanguage['language_id'] : $lang_id;
        $external = empty($_GET['external']) ? 0 : $_GET['external'];
        $externalTeamStatus = $diagnosticModel->checkAssessorExternalTeam($assessment_id, $assessor_id);
        if (isset($externalStatus['isExternal']) && $externalStatus['isExternal'] == 0) {
            $external = 1;
        }
        if ((in_array(6, $this->user['role_ids']) || in_array(5, $this->user['role_ids'])) && $this->user['has_view_video'] != 1 && $this->user['is_web'] == 1)//principal and school admin have to view video for self-review
            $this->_notPermitted = 1;
        elseif ($assessment_id > 0 && $assessor_id > 0 && $assessment = $diagnosticModel->getAssessmentByUser($assessment_id, $assessor_id, $lang_id_show, $external)) {
            $this->set('ddiagnosticId', $assessment['diagnostic_id']);
            $diagData = $diagnosticModel->getDiagnosticName($assessment['diagnostic_id'], $lang_id_show);
            $this->set('diagData', $diagData[0]);
            $is_collaborative = isset($assessment['iscollebrative']) ? $assessment['iscollebrative'] : 0;
            $isLeadSave = isset($assessment['isLeadSave']) ? $assessment['isLeadSave'] : 0;
            $isLeadAssessor = 0;

            //check if assessor is lead or not
            $assessmentModel = new assessmentModel();
            //For Offline new assessment check security Start
            if (!in_array("view_all_assessments", $this->user['capabilities']) && !empty($assessmentModel->chkAssessmentOfflineStatus($assessment_id))) {
                $this->_redirect(createUrl(array("controller" => "assessment", "action" => "assessment")));
            }
            //For Offline new assessment check security Ends

            if ($assessor_id == $this->user['user_id'] || ($assessment['status'] == 1 && ( $isAdmin || ($isSchoolAdmin && $assessment['client_id'] == $this->user['client_id'] && $assessment['role'] == 3) || ($assessment['role'] == 3 && $this->user['user_id'] == $assessment['external'] && $assessment['assessment_type_id'] == 2 && $assessment['status'] == 1) || ($isNetworkAdmin && $assessment['network_id'] == $this->user['network_id'] && $assessment['role'] == 3)))) {
                $subAssessmentType = empty($assessment['subAssessmentType']) ? 0 : $assessment['subAssessmentType'];
                $assessmentModel = new assessmentModel();
                $subAssessmentType == 1 ? ($isApproved = $assessment['isApproved']) : '';

                if ($subAssessmentType == 1 && ($isApproved == 0 || $isApproved == 2) && !in_array("edit_all_submitted_assessments", $this->user['capabilities'])) {
                    $this->_notPermitted = 1;
                    return;
                }

                $isReadOnly = $assessment['report_published'] == 1 || ($assessment['status'] == 1 && !in_array("edit_all_submitted_assessments", $this->user['capabilities'])) ? 1 : 0;

                $ratingLevelText = array();
                $diagnostic_type = isset($assessment['diagnostic_type']) ? $assessment['diagnostic_type'] : 0;
                if ($diagnostic_type == 1) {
                    $ratingLevelText = $this->db->array_grouping($diagnosticModel->ratingLevalText(), 'judgement_statement_instance_id');
                }
                $this->set("ratingLevelText", $ratingLevelText);
                $this->set("isReadOnly", $isReadOnly);
                $this->set("is_collaborative", $is_collaborative);
                $this->set("external", $external);
                $this->set("submitStatus", $assessment['status']);
                $this->set("isLeadAssessorKpa", 0);

                $leadPercentage = 0;
                $percentageData = array();
                $allAccessorsId = array();
                $numAssessmentTeamMembers = 0;
                $totalPercentage = 0;
                $allTeamPercentage = 0;
                $isFilledStatus = 0;
                $isExternalFilled = 0;
                if ($is_collaborative) {

                    $externalStatus = $diagnosticModel->checkAssessorIsLead($assessment_id, $assessor_id);

                    if (isset($externalStatus['isLead']) && $externalStatus['isLead'] >= 1) {
                        $isLeadAssessor = 1;
                        $leadPercentage = $assessment['percComplete'];
                        $numAssessmentTeamMembers = 1;
                    }
                    $this->set("isLeadAssessor", $isLeadAssessor);
                    //get external team rating percentage
                    if ($isLeadAssessor) {
                        $percentageData = $diagnosticModel->getExternalTeamRatingPerc($assessment_id, $assessor_id);
                        if (!empty($percentageData)) {
                            $filledStatus = isset($percentageData['filledStatus']) ? explode(",", $percentageData['filledStatus']) : array();

                            if (!in_array(0, $filledStatus)) {
                                if (!empty($percentageData) && $percentageData['percentageSum'] >= 1) {

                                    $numAssessmentTeamMembers += $percentageData['numTeamMembers'];
                                    $allTeamPercentage = $leadPercentage + $percentageData['percentageSum'];
                                    $isExternalFilled = 1;
                                    $totalPercentage = $numAssessmentTeamMembers * 100;
                                    $allAccessorsId = explode(",", $percentageData['user_ids']);
                                }
                            }
                        } else {

                            $isFilledStatus = 1;
                        }
                    }
                }
                if ($isLeadAssessor && $totalPercentage >= 1 && $totalPercentage == $allTeamPercentage && $isExternalFilled == 1) {
                    $kpas = array();
                    $allKpas = array();
                    $kqs = array();
                    $cqs = array();
                    $jss = array();
                    $isFilledStatus = 1;
                    if ($assessment['status'] == 0 && $isLeadSave == 0) {
                        foreach ($allAccessorsId as $key => $val) {
                            $kpas = $this->db->array_col_to_key($diagnosticModel->getKpasForAssessment($assessment_id, $val, 0, $lang_id_show, $is_collaborative, 1, '', $diagnostic_type), "kpa_instance_id");
                            $userKpas = array_keys($kpas);
                            $allKpas = $allKpas + $kpas;
                            $kqs = $kqs + $this->db->array_grouping($diagnosticModel->getKeyQuestionsForAssessment($assessment_id, $val, 0, $lang_id_show, 1, $userKpas), "kpa_instance_id", "key_question_instance_id");
                            $cqs = $cqs + $this->db->array_grouping($diagnosticModel->getCoreQuestionsForAssessment($assessment_id, $val, 0, $lang_id_show, 1, $userKpas), "key_question_instance_id", "core_question_instance_id");

                            $jss = $jss + $this->db->array_grouping($diagnosticModel->getJudgementalStatementsForAssessment($assessment_id, $val, 0, $lang_id_show, 1, $userKpas, '', $diagnostic_type), "core_question_instance_id", "judgement_statement_instance_id", "level");
                        }
                        $leadKpas = $this->db->array_col_to_key($diagnosticModel->getKpasForAssessment($assessment_id, $assessor_id, 0, $lang_id_show, $is_collaborative, 0, '', $diagnostic_type), "kpa_instance_id");
                        $allKpas = $allKpas + $leadKpas;

                        ksort($allKpas);

                        $kqs = $kqs + $this->db->array_grouping($diagnosticModel->getKeyQuestionsForAssessment($assessment_id, $assessor_id, 0, $lang_id_show, 0, array_keys($leadKpas)), "kpa_instance_id", "key_question_instance_id");
                        ksort($kqs);
                        $cqs = $cqs + $this->db->array_grouping($diagnosticModel->getCoreQuestionsForAssessment($assessment_id, $assessor_id, 0, $lang_id_show, 0, array_keys($leadKpas)), "key_question_instance_id", "core_question_instance_id");
                        ksort($cqs);
                        $jss = $jss + $this->db->array_grouping($diagnosticModel->getJudgementalStatementsForAssessment($assessment_id, $assessor_id, 0, $lang_id_show, 0, array_keys($leadKpas), '', $diagnostic_type), "core_question_instance_id", "judgement_statement_instance_id");
                        ksort($jss);
                        $this->set("kpas", $allKpas);
                        $this->set("isLeadAssessorKpa", 1);
                    } else {
                        $kpas = $this->db->array_col_to_key($diagnosticModel->getKpasForAssessment($assessment_id, $assessor_id, 0, $lang_id_show, 0, $external, '', $diagnostic_type), "kpa_instance_id");

                        $kqs = $this->db->array_grouping($diagnosticModel->getKeyQuestionsForAssessment($assessment_id, $assessor_id, 0, $lang_id_show, $external), "kpa_instance_id", "key_question_instance_id");
                        $cqs = $this->db->array_grouping($diagnosticModel->getCoreQuestionsForAssessment($assessment_id, $assessor_id, 0, $lang_id_show, $external), "key_question_instance_id", "core_question_instance_id");
                        $jss = $this->db->array_grouping($diagnosticModel->getJudgementalStatementsForAssessment($assessment_id, $assessor_id, 0, $lang_id_show, $external, '', '', $diagnostic_type), "core_question_instance_id", "judgement_statement_instance_id");
                        $this->set("kpas", $kpas);
                        if ($is_collaborative == 1 && $assessment['status'] == 1 && $isLeadAssessor == 1 && $assessment['percComplete'] == 100) {
                            $this->set("isLeadAssessorKpa", 1);
                        } else if ($is_collaborative == 1 && $isLeadSave == 1 && $assessment['status'] == 0 && $isLeadAssessor == 1 && $assessment['percComplete'] == 100) {
                            $this->set("isLeadAssessorKpa", 1);
                        }
                    }
                    $this->set("kqs", $kqs);
                    $this->set("cqs", $cqs);
                    $this->set("jss", $jss);
                } else {
                    $kpas = $this->db->array_col_to_key($diagnosticModel->getKpasForAssessment($assessment_id, $assessor_id, 0, $lang_id_show, $is_collaborative, $external, '', $diagnostic_type), "kpa_instance_id");
                    $kqs = $this->db->array_grouping($diagnosticModel->getKeyQuestionsForAssessment($assessment_id, $assessor_id, 0, $lang_id_show, $external), "kpa_instance_id", "key_question_instance_id");
                    $cqs = $this->db->array_grouping($diagnosticModel->getCoreQuestionsForAssessment($assessment_id, $assessor_id, 0, $lang_id_show, $external), "key_question_instance_id", "core_question_instance_id");
                    $jss = $this->db->array_grouping($diagnosticModel->getJudgementalStatementsForAssessment($assessment_id, $assessor_id, 0, $lang_id_show, $external, '', '', $diagnostic_type), "core_question_instance_id", "judgement_statement_instance_id");
                    $this->set("kpas", $kpas);
                    $this->set("kqs", $kqs);
                    $this->set("cqs", $cqs);
                    $this->set("jss", $jss);
                }
                if (isset($assessment) && $assessment['role'] == 4) {
                    $diagData[0]['js_recommendations'] == 1 ? $this->set("ajsns", $this->db->array_grouping($diagnosticModel->getAssessorKeyNotesLevel($assessment_id, 'judgement_statement_instance_id'), 'judgement_statement_instance_id', 'id')) : $this->set("ajsns", 0);
                    $diagData[0]['kpa_recommendations'] == 1 ? $this->set("akpans", $this->db->array_grouping($diagnosticModel->getAssessorKeyNotesLevel($assessment_id, 'kpa_instance_id'), 'kpa_instance_id', 'id')) : $this->set("akpans", 0);
                    $diagData[0]['kq_recommendations'] == 1 ? $this->set("akqns", $this->db->array_grouping($diagnosticModel->getAssessorKeyNotesLevel($assessment_id, 'key_question_instance_id'), 'key_question_instance_id', 'id')) : $this->set("akqns", 0);
                    $diagData[0]['cq_recommendations'] == 1 ? $this->set("acqns", $this->db->array_grouping($diagnosticModel->getAssessorKeyNotesLevel($assessment_id, 'core_question_instance_id'), 'core_question_instance_id', 'id')) : $this->set("acqns", 0);
                    $this->set("diagnosticModel", $diagnosticModel);
                }
                $isRevCompleteNtSubmitted = 0;
                if ($is_collaborative) {
                    if ($assessment['status'] == 0 && $external == 0 && $isFilledStatus == 1) {
                        $isRevCompleteNtSubmitted = 1;
                    }
                } else {

                    if ($assessment['status'] == 0 && intval($assessment['percComplete']) == '100') {
                        $isRevCompleteNtSubmitted = 1;
                    }
                }
                $self_review = $diagnosticModel->getAssessmentByRole($assessment_id, 3, $lang_id_show);
                $this->set("self_review", $self_review);
                $this->set("isRevCompleteNtSubmitted", $isRevCompleteNtSubmitted);
                $this->set("assessment_id", $assessment_id);
                $this->set("assessor_id", $assessor_id);
                $this->set("assessment", $assessment);
                $this->set("isFilledStatus", $isFilledStatus);
                $this->set("isAdmin", $isAdmin);
                $this->set("isNetworkAdmin", $isNetworkAdmin);
                $this->set("isSchoolAdmin", $isSchoolAdmin);
                $this->set("prefferedLanguage", $lang_id_show);
                $this->set("diagnostic_type", $diagnostic_type);

                $dig_image = $diagnosticModel->getDiagnosticImage($assessment['diagnostic_id']);
                $image_name = $dig_image[0]['file_name'];
                $this->set("image_name", $image_name);
                $this->set("diagnosticLanguages", $diagnosticModel->getDiagnosticLanguages($assessment['diagnostic_id']));
                $diagnosticLabels = array();

                $diagnosticLabelsData = $diagnosticModel->getDiagnosticLabels($assessment['diagnostic_id'], $lang_id_show);
                foreach ($diagnosticLabelsData as $data) {
                    $diagnosticLabels[$data['label_key']] = $data['label_text'];
                }
                $this->set("diagnosticLabels", $diagnosticLabels);
                $this->set("isLeadAssessor", $isLeadAssessor);
                $this->_template->addHeaderStyle('bootstrap-select.min.css');
                $this->_template->addHeaderStyle('assessment-form.css');
                $this->_template->addHeaderScript('jquery.mCustomScrollbar.concat.min.js');
                $this->_template->addHeaderStyle('jquery.mCustomScrollbar.min.css');
                $this->_template->addHeaderStyle('bootstrap-multiselect.css');
                $this->_template->addHeaderScript('bootstrap-multiselect.js');
                $this->_template->addHeaderStyle('jquery-confirm.min.css');
                $this->_template->addHeaderScript('jquery-confirm.min.js');
                $this->_template->addHeaderScript('assessment.js');
                $this->_template->addHeaderScript('localize.js');
                $this->_template->addHeaderScript('assessment_rec.js');
            } else
                $this->_notPermitted = 1;
        }else {
            $this->_is404 = 1;
        }
    }

    /** question form hierarchy
     *@params: Null
     */
    function sort_questions_hierarchicaly(Array &$questions, Array &$into, $parentId = 0, $key) {
        foreach ($questions as $i => $question) {
            if ($question['parent_id'] == $parentId) {
                $into[$question['school_profile_id'] . '_' . (empty($question['group_id']) ? '1' : $question['group_id'])] = $question;
                unset($questions[$i]);
            }
        }
        foreach ($into as $index => $topQuestion) {
            $into[$index]['sub_questions'] = array();

            $this->sort_questions_hierarchicaly($questions, $into[$index]['sub_questions'], $topQuestion['school_profile_id'], $key);
        }
    }

    /** data for   aqs form form
     *@params: Null
     */
    function aqsFormAction() {

        $assmntId_or_grpAssmntId = isset($_REQUEST['assmntId_or_grpAssmntId']) ? $_REQUEST['assmntId_or_grpAssmntId'] : '';
        $assessment_type_id = isset($_REQUEST['assessment_type_id']) ? $_REQUEST['assessment_type_id'] : '';
        $diagnosticModel = new diagnosticModel();
        $assessment = ($assessment_type_id == 1 || $assessment_type_id == 5) ? $diagnosticModel->getAssessmentById($assmntId_or_grpAssmntId) : array();

        if ($assessment_type_id >= 1 && $assmntId_or_grpAssmntId >= 1) {
            $isReadOnly = '';
            $kdDetailsQuestions = $this->db->array_grouping($diagnosticModel->getKpaQuestions($assmntId_or_grpAssmntId), 'kpa_id');
            $allKpaQuestionsData = array();

            foreach ($kdDetailsQuestions as $key => $kpaData) {
                $questionHierarchy1 = array();
                $this->sort_questions_hierarchicaly($kpaData, $questionHierarchy1, 0, $key);
                $allKpaQuestionsData[$key] = $questionHierarchy1;
            }

            if ($assessment && ($assessment_type_id == 1 || $assessment_type_id == 5 || $assessment['assessmentAssigned'] > 0)) {
                $isNetworkAdmin = in_array("view_own_network_assessment", $this->user['capabilities']) && $assessment['network_id'] == $this->user['network_id'] && $this->user['network_id'] > 0 ? 1 : 0;
                $isSchoolAdmin = in_array("view_own_institute_assessment", $this->user['capabilities']) && $assessment['client_id'] == $this->user['client_id'] ? 1 : 0;
                $isPrincipal = $isSchoolAdmin && in_array(6, $this->user['role_ids']) ? 1 : 0;
                //also check if user is in external team for the review
                if ($assessment_type_id == 1 || $assessment_type_id == 5)
                    $checkExternalTeam = $diagnosticModel->isUserinExternalTeamAssessment($assessment['assessment_id'], $this->user['user_id']);

                if (( (($assessment_type_id == 1 || $assessment_type_id == 5 ) && ($checkExternalTeam = $diagnosticModel->isUserinExternalTeamAssessment($assessment['assessment_id'], $this->user['user_id']))) && $checkExternalTeam['num'] > 0 && in_array('take_external_assessment', $this->user['capabilities'])) || in_array($this->user['user_id'], $assessment['user_ids']) || in_array("view_all_assessments", $this->user['capabilities']) || $isSchoolAdmin || $isNetworkAdmin) {
                    $isReadOnly = 1;
                    if ($assessment_type_id == 1 || $assessment_type_id == 5) {
                           $isReadOnly = $assessment['report_published'] != 1 && ( ($assessment['aqs_status'] == 1 && in_array("edit_all_submitted_assessments", $this->user['capabilities'])) || (empty($assessment['aqs_status']) && ( $assessment['userIdByRole'][3] == $this->user['user_id'] || $isSchoolAdmin || $isNetworkAdmin ) )) ? 1 : 0;
                    } else {
                        $isReadOnly = ($assessment['aqs_status'] == 1 && in_array("edit_all_submitted_assessments", $this->user['capabilities'])) || ($assessment['aqs_status'] == 0 && ( $assessment['admin_user_id'] == $this->user['user_id'] || $isPrincipal || $isNetworkAdmin)) ? 0 : 1;
                    }
                    //$isReadOnly = 1;
                    $aqs_status = !empty($assessment['aqs_status']) ? $assessment['aqs_status'] : 0;
                    $this->set("isReadOnly", $isReadOnly);
                    $this->set("aqs_status", $aqs_status);

                    $this->set("assmntId_or_grpAssmntId", $assmntId_or_grpAssmntId);
                    $this->set("assessment_type_id", $assessment_type_id);
                    $this->set("assessment", $assessment);
                    $this->set("allKpaQuestionsData", $allKpaQuestionsData);
                    $this->_template->addHeaderStyle('bootstrap-select.min.css');
                    $this->_template->addHeaderStyle('assessment-form.css');
                    $this->_template->addHeaderScript('bootstrap-select.min.js');
                    $this->_template->addHeaderScript('schoolProfile.js');
                    $this->_template->addHeaderStyle('jquery.mCustomScrollbar.min.css');
                    $this->_template->addHeaderScript('jquery.mCustomScrollbar.concat.min.js');
                } else
                    $this->_notPermitted = 1;
            }else {
                $this->_is404 = 1;
            }
            $isReadOnly = '';
            $diagnosticModel = new diagnosticModel();
            $kdDetailsQuestions = $this->db->array_grouping($diagnosticModel->getKpaQuestions($assmntId_or_grpAssmntId), 'kpa_id');

            $allKpaQuestionsData = array();
            foreach ($kdDetailsQuestions as $key => $kpaData) {
                $questionHierarchy1 = array();
                $this->sort_questions_hierarchicaly($kpaData, $questionHierarchy1, 0, $key);
                $allKpaQuestionsData[$key] = $questionHierarchy1;
            }
            $this->set("allKpaQuestionsData", $allKpaQuestionsData);
            $aqsformHelper = new aqsformHelper();
            $this->set('aqsformHelper', $aqsformHelper);
        } else {
            $this->_is404 = 1;
        }
    }

    /** data for assessment preview
     *@params: Null
     */
    function assessmentPreviewAction() {
        $diagnosticModel = new diagnosticModel();
        $assessment_id = empty($_GET['assessment_id']) ? 0 : $_GET['assessment_id'];
        $assessor_id = empty($_GET['assessor_id']) ? $this->user['user_id'] : $_GET['assessor_id'];
        $external = empty($_GET['external']) ? 0 : $_GET['external'];
        $is_collaborative = 0;
        if (isset($external) && $external == 1) {
            $external = 1;
            $is_collaborative = 1;
        }
        $prefferedLanguage = $diagnosticModel->getAssessmentPrefferedLanguage($assessment_id);
        if (count($prefferedLanguage) == 0) {
            $lang_id = empty($_GET['lang_id']) ? DEFAULT_LANGUAGE : $_GET['lang_id'];
        } else {
            $lang_id = $prefferedLanguage['language_id'];
        }

        $isAdmin = in_array("view_all_assessments", $this->user['capabilities']) ? true : false;
        $isNetworkAdmin = in_array("view_own_network_assessment", $this->user['capabilities']) && $this->user['network_id'] > 0 ? true : false;
        $isSchoolAdmin = in_array("view_own_institute_assessment", $this->user['capabilities']) ? true : false;

        if ((in_array(6, $this->user['role_ids']) || in_array(5, $this->user['role_ids'])) && $this->user['has_view_video'] != 1 && $this->user['is_web'] == 1)//principal and school admin have to view video for self-review
            $this->_notPermitted = 1;
        elseif ((in_array(6, $this->user['role_ids']) || in_array(5, $this->user['role_ids'])) && $this->user['has_view_video'] != 1 && $this->user['is_web'] == 1)//principal and school admin have to view video for self-review
            $this->_notPermitted = 1;
        elseif ($assessment_id > 0 && $assessor_id > 0 && $assessment = $diagnosticModel->getAssessmentByUser($assessment_id, $assessor_id, $lang_id, $external)) {

            $subAssessmentType = empty($assessment['subAssessmentType']) ? 0 : $assessment['subAssessmentType'];
            $assessmentModel = new assessmentModel();
            $subAssessmentType == 1 ? ($isApproved = $assessment['isApproved']) : '';

            if ($subAssessmentType == 1 && ($isApproved == 0 || $isApproved == 2) && !in_array("edit_all_submitted_assessments", $this->user['capabilities'])) {
                $this->_notPermitted = 1;
                return;
            }

            if ($assessor_id == $this->user['user_id'] || ($assessment['status'] == 1 && ( $isAdmin || ($isSchoolAdmin && $assessment['client_id'] == $this->user['client_id'] && $assessment['role'] == 3) || ($assessment['role'] == 3 && $this->user['user_id'] == $assessment['external'] && $assessment['assessment_type_id'] == 2 && $assessment['status'] == 1) || ($isNetworkAdmin && $assessment['network_id'] == $this->user['network_id'] && $assessment['role'] == 3)
                    )
                    )
            ) {
                $kpa_id = empty($_GET['kpa_id']) ? 0 : $_GET['kpa_id'];
                $kpas = $this->db->array_col_to_key($diagnosticModel->getKpasForAssessment($assessment_id, $assessor_id, $kpa_id, $lang_id, $is_collaborative, $external, $isLeadAssessorKpa = 0), "kpa_instance_id");
                $kpas = array_column($kpas, 'kpa_instance_id');
                $this->set("kpas", $this->db->array_col_to_key($diagnosticModel->getKpasForAssessment($assessment_id, $assessor_id, $kpa_id, $lang_id, $is_collaborative, $external, $isLeadAssessorKpa = 0), "kpa_instance_id"));
                $this->set("kqs", $this->db->array_grouping($diagnosticModel->getKeyQuestionsForAssessment($assessment_id, $assessor_id, $kpa_id, $lang_id, $external, $kpas), "kpa_instance_id", "key_question_instance_id"));
                $this->set("cqs", $this->db->array_grouping($diagnosticModel->getCoreQuestionsForAssessment($assessment_id, $assessor_id, $kpa_id, $lang_id, $external, $kpas), "key_question_instance_id", "core_question_instance_id"));
                $this->set("jss", $this->db->array_grouping($diagnosticModel->getJudgementalStatementsForAssessment($assessment_id, $assessor_id, $kpa_id, $lang_id, $external, $kpas), "core_question_instance_id", "judgement_statement_instance_id"));
                if (isset($assessment) && $assessment['role'] == 4) {
                    $this->set("akns", $this->db->array_grouping($diagnosticModel->getAssessorKeyNotes($assessment_id), "kpa_instance_id", "id"));
                    $this->set("diagnosticModel", $diagnosticModel);
                }

                $diagnosticLabels = array();

                $diagnosticLabelsData = $diagnosticModel->getDiagnosticLabels($assessment['diagnostic_id'], $lang_id);
                foreach ($diagnosticLabelsData as $data) {
                    $diagnosticLabels[$data['label_key']] = $data['label_text'];
                }
                $this->set("diagnosticLabels", $diagnosticLabels);

                $this->set("assessment_id", $assessment_id);
                $this->set("assessor_id", $assessor_id);
                $this->set("assessment", $assessment);

                $this->set("isAdmin", $isAdmin);
                $this->set("isNetworkAdmin", $isNetworkAdmin);
                $this->set("isSchoolAdmin", $isSchoolAdmin);

                $this->_template->addHeaderStyle('assessment-form.css');
                $this->_template->addHeaderStyle('jquery.mCustomScrollbar.min.css');
                $this->_template->addHeaderScript('jquery.mCustomScrollbar.concat.min.js');
            } else
                $this->_notPermitted = 1;
        }else {
            $this->_is404 = 1;
        }
    }
    /** get  kay   data
     *@params: $array,$key,$value
     */
    function findKey($array, $key, $value) {
        foreach ($array as $item)
            if (isset($item[$key]) && $item[$key] == $value)
                return true;
        return false;
    }

    /** get  kay  recommendation  data
     *@params: $array,$key,$value
     */
    function keyrecommendationsAction() {
        $assessment_id = empty($_GET['assessment_id']) ? 0 : $_GET['assessment_id'];
        $lang_id = empty($_GET['lang_id']) ? DEFAULT_LANGUAGE : $_GET['lang_id'];
        $type = empty($_GET['type']) ? 0 : $_GET['type'];
        $instance_id = empty($_GET['instance_id']) ? 0 : $_GET['instance_id'];
        $assessor_id = empty($_GET['assessor_id']) ? 0 : $_GET['assessor_id'];
        $diagnostic_type = empty($_GET['diagnostic_type']) ? 0 : $_GET['diagnostic_type'];
        $external = empty($_GET['external']) ? 0 : $_GET['external'];
        $is_collaborative = empty($_GET['is_collaborative']) ? 0 : $_GET['is_collaborative'];
        $kpa7id = empty($_GET['kpa7id']) ? 0 : 0;
        $isAdmin = in_array("view_all_assessments", $this->user['capabilities']) ? true : false;
        $isNetworkAdmin = in_array("view_own_network_assessment", $this->user['capabilities']) && $this->user['network_id'] > 0 ? true : false;
        $isSchoolAdmin = in_array("view_own_institute_assessment", $this->user['capabilities']) ? true : false;
        $diagnosticModel = new diagnosticModel();
        if ((in_array(6, $this->user['role_ids']) || in_array(5, $this->user['role_ids'])) && $this->user['has_view_video'] != 1 && $this->user['is_web'] == 1)//principal and school admin have to view video for self-review
            $this->_notPermitted = 1;
        elseif ($assessment_id > 0 && $assessment = $diagnosticModel->getAssessmentByUser($assessment_id, $assessor_id, $lang_id, $external)) {
            $assessor_id = $assessment['user_id'];
            if ($assessor_id == $this->user['user_id'] || ($assessment['status'] == 1 && ( $isAdmin || ($isSchoolAdmin && $assessment['client_id'] == $this->user['client_id'] && $assessment['role'] == 3) || ($isNetworkAdmin && $assessment['network_id'] == $this->user['network_id'] && $assessment['role'] == 3)
                    )
                    )
            ) {
                $isReadOnly = $assessment['report_published'] == 1 || ($assessment['status'] == 1 && !in_array("edit_all_submitted_assessments", $this->user['capabilities'])) ? 1 : 0;
                $this->set("isReadOnly", $isReadOnly);
                $this->set('type', $type);
                $this->set('instance_id', $instance_id);
                $diagnosticModel = new diagnosticModel();
                $this->set("diagnosticModel", $diagnosticModel);
                $this->set("akns", $diagnosticModel->getAssessorKeyNotesForType($assessment_id, $type, $instance_id));
                $this->set("assessment_id", $assessment_id);
                $this->set("assessment", $assessment);
                $this->set("diagnostic_type", $diagnostic_type);
                $source_link = '';
                switch (strtolower($type)) {
                    case 'kpa':$source_link = 'kpa';
                        $tab_type_kn = 'kpa';
                        break;
                    case 'key_question': $source_link = 'kq';
                        $tab_type_kn = 'keyQ';
                        break;
                    case 'core_question':$source_link = 'cq';
                        $tab_type_kn = 'coreQ';
                        break;
                    case 'judgement_statement':$source_link = 'js';
                        $tab_type_kn = 'judgementS';
                }
                $this->set('kpa7', "0");
                if ($kpa7id == $instance_id) {
                    $this->set('kpa7', "1");
                }
                $this->set('tab_type_kn', $tab_type_kn);
                $this->set('sourceLink', $source_link);
                $this->set("isAdmin", $isAdmin);
                $this->set("isNetworkAdmin", $isNetworkAdmin);
                $this->set("isSchoolAdmin", $isSchoolAdmin);
                $this->set("lang_id", $lang_id);
                $this->set("external", $external);
                $this->set("is_collaborative", $is_collaborative);
                $this->set("assessor_id", $assessor_id);
                $this->_template->addHeaderStyle('bootstrap-select.min.css');
                $this->_template->addHeaderStyle('assessment-form.css');
                $this->_template->addHeaderScript('jquery.mCustomScrollbar.concat.min.js');
                $this->_template->addHeaderStyle('jquery.mCustomScrollbar.min.css');
                $this->_template->addHeaderStyle('bootstrap-multiselect.css');
                $this->_template->addHeaderScript('bootstrap-multiselect.js');
                $this->_template->addHeaderScript('assessment.js');
                $this->_template->addHeaderScript('assessment_rec.js');
            } else
                $this->_notPermitted = 1;
        }
    }

    /** gopd looks like
     *@params: Null
     */
    function goodlookslikeAction() {
        $assessment_id = empty($_GET['assessment_id']) ? 0 : $_GET['assessment_id'];
        $lang_id = empty($_GET['lang_id']) ? DEFAULT_LANGUAGE : $_GET['lang_id'];
        $type = empty($_GET['type']) ? 0 : $_GET['type'];
        $instance_id = empty($_GET['instance_id']) ? 0 : $_GET['instance_id'];
        $assessor_id = empty($_GET['assessor_id']) ? 0 : $_GET['assessor_id'];
        $external = empty($_GET['external']) ? 0 : $_GET['external'];
        $is_collaborative = empty($_GET['is_collaborative']) ? 0 : $_GET['is_collaborative'];
        $isAdmin = in_array("view_all_assessments", $this->user['capabilities']) ? true : false;
        $isNetworkAdmin = in_array("view_own_network_assessment", $this->user['capabilities']) && $this->user['network_id'] > 0 ? true : false;
        $isSchoolAdmin = in_array("view_own_institute_assessment", $this->user['capabilities']) ? true : false;
        $diagnosticModel = new diagnosticModel();
        $js_instance_id = $_GET['instance_id'];
        $sqljs = "select * FROM h_cq_js_instance where judgement_statement_instance_id in($js_instance_id)";
        $resjs = $this->db->get_results($sqljs);

        foreach ($resjs as $resultjs) {
            $js_id_arr = $resultjs['judgement_statement_id'];
            $sqljs1 = "select * FROM d_judgement_statement where judgement_statement_id in ($js_id_arr)";
            $resjs1 = $this->db->get_results($sqljs1);
            $js_statement_text[] = $resjs1[0]['judgement_statement_text1'];
            $js_id[] = $resjs1[0]['judgement_statement_id'];
        }
        $js_ids = implode(',', $js_id);
        $sql_new = "select a.translation_text as judgement_statement_text1,e.translation_text,c.display_js_txt
            from h_lang_translation a
            inner join d_judgement_statement b on a.equivalence_id=b.equivalence_id
            inner join h_js_mostly_statements c on b.judgement_statement_id=c.judgement_statement_id
            inner join d_good_look_like_statement d on c.mostly_statements_id=d.good_looks_like_statement_id
            inner join h_lang_translation e on d.equivalence_id=e.equivalence_id
            inner join h_cq_js_instance f on c.judgement_statement_id=f.judgement_statement_id
            where b.judgement_statement_id IN ($js_ids) and judgement_statement_instance_id IN ($js_instance_id) and a.language_id=9 and e.language_id=9";
        $res_new = $this->db->get_results($sql_new);
        $this->set("res_new", $res_new);
        $this->set("noHeader", "1");
        $this->_template->clearHeaderFooter();
    }

    /** create options
     *@params: $introductory_question,$parent_id
     */
    function createOption(array &$introductory_question, $parent_id = 0) {

        $branch = array();
        foreach ($introductory_question as $element) {
            if ($element['parent_id'] == $parent_id) {
                $children = $this->createOption($introductory_question, $element['q_id']);
                if ($children) {
                    $element['child_question'] = $children;
                }
                $branch[$element['q_id']] = $element;
            }
        }
        return $branch;
    }



}
