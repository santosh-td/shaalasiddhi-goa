<?php

class offlineController extends controller {

    protected $_web = 0;
    protected $_api = 0;
    protected $apiResult;
    protected $_postData;

    function __construct($controller, $action, $api = 0) {
        error_reporting(E_ALL);
        ini_set('display_errors', 'off');
        $this->db = db::getInstance();
        $this->_controller = $controller;
        $this->_action = $action;
        $this->_notPermitted = 0;
        $this->_is404 = 0;
        $this->_web = 1;
        $this->_api = isset($_GET['api']) ? 1 : 0;
        $this->userModel = new userModel();
        if ($this->_api == 1) {
            $this->_render = 0;
            $this->apiResult = array("status" => 400, "message" => "");
        } else {
            $this->_template = new Template($controller, $action);
            $this->_template->set("ajaxRequest", $this->_ajaxRequest);
            $this->_template->set("web", $this->_web);
            if ($this->_ajaxRequest != 1)
                $this->initializeHeader();
        }
    }

    function getUserDetailsAction() {
        $contents = file_get_contents('php://input');
        if (!empty($contents)) {
            $values = json_decode($contents);
            $email = trim($values->email);
            $password = trim($values->password);
            if (empty($email))
                $this->apiResult ["message"] = "Username field missing";
            else if (empty($password))
                $this->apiResult ["message"] = "Password field missing";
            else if ($res = $this->userModel->authenticateUser($email, $password)) {
                $json['userid'] = (!empty($res['user_id'])) ? $res['user_id'] : 0;
                $json['usersname'] = (!empty($res['user_name'])) ? $res['user_name'] : '';
                $json['name'] = (!empty($res['name'])) ? $res['name'] : '';
                $json['email'] = (!empty($res['email'])) ? $res['email'] : '';
                $json['aqsstatusid'] = (!empty($res['aqs_status_id'])) ? $res['aqs_status_id'] : 0;
                $json['hasviewvideo'] = (!empty($res['has_view_video'])) ? $res['has_view_video'] : 0;
                $json['roles'] = (!empty($this->userModel->getRoles($res['role_ids']))) ? $this->userModel->getRoles($res['role_ids']) : array();
                $time = time();
                $issuedAt = $time;
                $notBefore = $issuedAt + 10; //Adding 10 seconds
                $expire = $notBefore + 180; //for 30 days
                $refresh = $notBefore + TOKEN_LIFE;
                $jwt_token = array(
                    "iss" => "adhyayan.asia",
                    "iat" => $issuedAt,
                    "nbf" => $notBefore,
                    "exp" => $expire,
                    "user" => $res['user_id']
                );
                $jwt_token = Firebase\JWT\JWT::encode($jwt_token, PRIVATEKEY, 'HS256');
                header("Content-Type: application/json");
                header("Jwt-Token: $jwt_token");
//                try {
//                    $jwttk = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpc3MiOiJhZGh5YXlhbi5hc2lhIiwiaWF0IjoxNTM1NTI1MDAwLCJuYmYiOjE1MzU1MjUwMTAsImV4cCI6MTUzNTUyNTE5MCwidXNlciI6Ijk4In0.OUgk7x0GaMYhc9NyGpz_86LBXMGgmmTNgYbgcXmEDQM1-2a00R3CUKVP312H48m6pW_styPG6ri7I6h01-8JYeViMDONCn7gn30bNNly7npzqPW8Po2EgBiWagcNM8ZDve_ISzpoZPeWJ-tw8kjP-hrmqpq6fNsIkiDzLnmXPL1fnHqG2w1U76hPzLpNR-o3i6zk73Vz6wEQORm15F5b_EjI2u4rt7VY2_sLSUTj_3GX67YM9BQWJaQXY7S90ytLMxsi2bXqpR580C8vH-A4mRft-MNi8aM59qtqbbKhBx0TmVcjU8GlsvzOIv1MpuO4NCaVfoNMmWE8FGEq6edU8g";
//                    $decoded = Firebase\JWT\JWT::decode($jwttk, PUBLICKEY, array('RS256'));
//                    $decoded_array = (array) $decoded;
//                    echo "<pre>";
//                    print_r($decoded_array);
//                } catch (Exception $e) {
//                    echo "<pre>";
//                    print_r($e);
//                    echo "<pre>";
//                    echo $e->getMessage();
//                }
//                die('');
                //$t = (array) $decoded_array['user'];
////                For Generate the Token Ends
                //$json['userToken'] = $jwt_token;
                $this->apiResult ["status"] = 200;
                $this->apiResult ["message"] = $json;
            } else {
                $this->apiResult ["message"] = "Invalid username or password";
            }
        } else {
            $this->apiResult ["message"] = "Fields are missing";
        }
    }

    function getSyncAssessmentIdsAction() {
        $contents = file_get_contents('php://input');
        if (!empty($contents)) {
            $values = json_decode($contents);
            if (empty($values->assessmentIds) || !is_array($values->assessmentIds) || empty($this->userModel->getUserById($values->userid)))
                return $this->apiResult ["message"] = "Details are not correct";

            $assessmentModel = new assessmentModel();
            $ct = 0;
            foreach ($values->assessmentIds as $key => $value) {
                if (!empty($assessmentModel->chkAssessmentID($value))) {
                    $this->db->update("d_assessment", array('is_offline' => 1, 'is_offline_date' => date('Y-m-d H:i:s')), array('assessment_id' => $value));
                    if (!empty($assessmentModel->chkAssessmentUserID($value, $values->userid))) {
                        $this->db->update("h_assessment_user", array('is_offline' => 1, 'is_offline_date' => date('Y-m-d H:i:s')), array('assessment_id' => $value, 'user_id' => $values->userid));
                        $ct++;
                    }
                    if (!empty($assessmentModel->chkAssessmentExtUserID($value, $values->userid))) {
                        $this->db->update("h_assessment_external_team", array('is_offline' => 1, 'is_offline_date' => date('Y-m-d H:i:s')), array('assessment_id' => $value, 'user_id' => $values->userid));
                        $ct++;
                    }
                } else {
                    $assessId[] = $value;
                }
            }
            $this->apiResult ["status"] = 200;
            $this->apiResult ["message"] = (empty($assessId) && $ct != 0) ? "Assessment Id is updated successfully." : "Assessment Id not updated successfully.";
        } else {
            $this->apiResult ["message"] = "Details are empty.";
        }
    }

    function getAssessmentDeatilsAction() {

        $contents = file_get_contents('php://input');
        if (!empty($contents)) {
            $values = json_decode($contents);
            $userId = trim($values->userid);
            if (empty($userId))
                return $this->apiResult ["message"] = "UserID is missing.";

            $assessmentModel = new assessmentModel();
            $user = $this->userModel->getUserById($userId);

            if (empty($user))
                return $this->apiResult ["message"] = "User details are not found.";

            $param = array(
                "client_name_like" => "",
                "name_like" => "",
                "diagnostic_id" => "",
                "fdate_like" => "",
                "edate_like" => "",
                "status" => "",
                "network_id" => "",
                "province_id" => "",
                "assessment_type_id" => "",
                "user_id" => "",
                "sub_role_user_id" => "",
                "page" => 0,
                "order_by" => "create_date",
                "order_type" => "desc",
            );
            //make to dynamic
            $user['is_web'] = 0;

            if ((in_array(6, $user['role_ids']) || in_array(5, $user['role_ids'])) && $user['has_view_video'] != 1 && $user['is_web'] == 1) {//principal and school admin have to view video for self-review
                return $this->apiResult ["message"] = "Not Permitted";
            } else if (in_array("view_all_assessments", $user['capabilities'])) {
                
            } else if (in_array("view_own_network_assessment", $user['capabilities'])) {
                $param['user_id'] = $user['user_id'];
                $param['network_id'] = $user['network_id'];
            } else if (in_array("view_own_institute_assessment", $user['capabilities'])) {
                $param['user_id'] = $user['user_id'];
                $param['client_id'] = $user['client_id'];
                $param['network_id'] = 0;
            } else {
                $param['user_id'] = $user['user_id'];
                $param['client_id'] = 0;
                $param['network_id'] = 0;
            }

            if (in_array("take_external_assessment", $user['capabilities']))
                $param['sub_role_user_id'] = $user['user_id'];

            if (in_array('8', $user['role_ids']) && count($user['role_ids']) == 1) {
                $tap_admin_id = $user['role_ids'][0];
            } else {
                $tap_admin_id = '';
            }

            $user_id = $user['user_id'];
            $rid = '';

            $is_guest = (isset($user['is_guest']) && $user['is_guest']) ? $user['is_guest'] : 0;

            $res = $assessmentModel->getAssessmentListByUser($param, $tap_admin_id, $user_id, $rid, current($user['role_ids']), '', '', $is_guest, $user['user_id']);

            if (!empty($res)) {
                foreach ($res as $ky => $vl) {
                    //Check for remove Admins assessments allow only users assessment Start
                    $exUSers = $assessmentModel->getAssessmentUsers($vl['assessment_id'], $vl['assessment_type_id']);
                    $intUSers = $assessmentModel->getInternalAssessor($vl['assessment_id']);
                    //Check for remove Admins assessments allow only users assessment Ends
                    if (!empty($assessmentModel->chkAssKeyNotes($vl['assessment_id'])) || !empty($assessmentModel->chkFilledLeadAssessment($vl['assessment_id'])) || !empty($assessmentModel->chkFilledExtAssessment($vl['assessment_id'])) || !empty($assessmentModel->chkSyncLeadAssessment($vl['assessment_id'], $user['user_id'])) || !empty($assessmentModel->chkSyncExtAssessment($vl['assessment_id'], $user['user_id'])) || !empty($vl['perComplete']) || (array_search($user['user_id'], array_column($exUSers, 'user_id')) === FALSE && $intUSers['user_id'] != $user['user_id']))
                        unset($res[$ky]);
                }
            }

            if (!empty($res)) {
                $assessLists = array();
                foreach ($res as $key => $value) {
                    if (!empty($value['kpa_user'])) {
                        $ids = explode(",", $value['kpa_user']);
                        $assessor_id = (is_array($ids) && in_array($user['user_id'], $ids)) ? $user['user_id'] : $value['kpa_user'];
                    }
                    $allDetails = $this->getKpaDetails($value['assessment_id'], $assessor_id, $user, $value['kpa_user']);
                    $kpalist = array();

                    //Get User Details Role and permission based on assessment start
                    $externalUSers = $assessmentModel->getAssessmentUsers($value['assessment_id'], $value['assessment_type_id']);
                    $internalUSers = $assessmentModel->getInternalAssessor($value['assessment_id']);
                    $facilitatorUSers = $assessmentModel->getFacilitatorsDetails($value['assessment_id']);

                    if ($value['iscollebrative'] == 1) {
                        if (array_search($user['user_id'], array_column($externalUSers, 'user_id')) !== FALSE) {
                            $userRole = $externalUSers[array_search($user['user_id'], array_column($externalUSers, 'user_id'))]['sub_role_name'];
                            $leadRole = ($externalUSers[array_search('Lead / Sr. Associate', array_column($externalUSers, 'sub_role_name'))]['user_id'] == $user['user_id']) ? TRUE : FALSE;
                            if ($leadRole) {
                                $import = TRUE;
                                $export = TRUE;
                                $isDownload = TRUE;
                            } elseif (!empty($allDetails) && !empty($allDetails[0]) && count($allDetails) == 9) {
                                $import = FALSE;
                                $export = TRUE;
                                $isDownload = TRUE;
                            } else {
                                $import = FALSE;
                                $export = FALSE;
                                $isDownload = TRUE;
                            }
                        } elseif ($internalUSers['user_id'] == $user['user_id']) {
                            $userRole = 'Internal Reviewer';
                            $import = FALSE;
                            $export = FALSE;
                            $isDownload = TRUE;
                        } elseif (array_search($user['user_id'], array_column($facilitatorUSers, 'user_id')) !== FALSE) {
                            $userRole = "Facilitators " . $externalUSers[array_search($user['user_id'], array_column($externalUSers, 'user_id'))]['sub_role_name'];
                            $import = FALSE;
                            $export = FALSE;
                            $isDownload = TRUE;
                        }
                    } else {
                        if (array_search($user['user_id'], array_column($externalUSers, 'user_id')) !== FALSE) {
                            $userRole = $externalUSers[array_search($user['user_id'], array_column($externalUSers, 'user_id'))]['sub_role_name'];
                            $leadRole = ($externalUSers[array_search('Lead / Sr. Associate', array_column($externalUSers, 'sub_role_name'))]['user_id'] == $user['user_id']) ? TRUE : FALSE;
                            if ($leadRole) {
                                $import = TRUE;
                                $export = TRUE;
                                $isDownload = TRUE;
                            } else {
                                $import = FALSE;
                                $export = FALSE;
                                $isDownload = TRUE;
                            }
                        } elseif ($internalUSers['user_id'] == $user['user_id']) {
                            $userRole = 'Internal Reviewer';
                            $import = FALSE;
                            $export = TRUE;
                            $isDownload = TRUE;
                        } elseif (array_search($user['user_id'], array_column($facilitatorUSers, 'user_id')) !== FALSE) {
                            $userRole = "Facilitators " . $externalUSers[array_search($user['user_id'], array_column($externalUSers, 'user_id'))]['sub_role_name'];
                            $import = FALSE;
                            $export = FALSE;
                            $isDownload = TRUE;
                        }
                    }
                    //Get User Details Role and permission based on assessment Ends

                    if (!empty($allDetails) && !empty($allDetails[0]) && count($allDetails) == 9) {
                        $kp = 1;
                        foreach ($allDetails[0] as $kpaKey => $allDetail) {
                            $reclist = array();
                            $recjslist = array();
                            $celebrateName = array();
                            $jslist = array();
                            $sblist = array();
                            $kqlist = array();
                            $instance_id = $allDetail['kpa_instance_id'];

                            //Work on new empty details of Celebrate and Recommendation Start
                            $js = 1;
                            foreach ($allDetails[1][$kpaKey] as $kqkey => $kqval) {
                                foreach ($allDetails[2][$kqkey] as $sbkey => $sbvalue) {
                                    foreach ($allDetails[3][$sbkey] as $jskey => $jsvalue) {
                                        $recjslist[$jsvalue['judgement_statement_text']] = array(
                                            "position" => $js,
                                            "title" => $jsvalue['judgement_statement_text'],
                                            "selected" => FALSE,
                                            "goodlookslikelist" => $assessmentModel->getGoodLooksLikeList($jskey),
                                            "question" => (object) []
                                        );
                                        $js++;
                                    }
                                }
                            }
                            $reclist[] = array(
                                "text" => NULL,
                                "jsMap" => $recjslist
                            );

                            //Work on new empty details of Celebrate and Recommendation Ends
                            // Key Question loop Start
                            $kq = 1;
                            foreach ($allDetails[1][$kpaKey] as $kqkey => $kqval) {
                                $sb = 1;
                                // Key sub question loop 
                                foreach ($allDetails[2][$kqkey] as $sbkey => $sbvalue) {
                                    $js = 1;
                                    // Key judgement statement loop
                                    foreach ($allDetails[3][$sbkey] as $jskey => $jsvalue) {
                                        //rating level text start
                                        if (!empty($allDetails[8])) {
                                            $rating1 = $allDetails[8][$jsvalue['judgement_statement_instance_id']][0]['translation_text'];
                                            $rating2 = $allDetails[8][$jsvalue['judgement_statement_instance_id']][1]['translation_text'];
                                            $rating3 = $allDetails[8][$jsvalue['judgement_statement_instance_id']][2]['translation_text'];
                                            $rating11 = isset($allDetails[8][$jsvalue['judgement_statement_instance_id']][3]['translation_text']) ? $allDetails[8][$jsvalue['judgement_statement_instance_id']][3]['translation_text'] : '';
                                            $rating12 = isset($allDetails[8][$jsvalue['judgement_statement_instance_id']][4]['translation_text']) ? $allDetails[8][$jsvalue['judgement_statement_instance_id']][4]['translation_text'] : '';
                                            $rating13 = isset($allDetails[8][$jsvalue['judgement_statement_instance_id']][5]['translation_text']) ? $allDetails[8][$jsvalue['judgement_statement_instance_id']][5]['translation_text'] : '';
                                        }
                                        //rating level text ends
                                        if (count($allDetails[8][$jsvalue['judgement_statement_instance_id']]) == 6) {
                                            $rList = array(array(
                                                    "ratingTitle" => "A and A",
                                                    "ratingValue" => "",
                                                    "ratingLevelText" => array($rating1, $rating2, $rating3)
                                                ), array(
                                                    "ratingTitle" => "Q and U",
                                                    "ratingValue" => "",
                                                    "ratingLevelText" => array($rating11, $rating12, $rating13)
                                                )
                                            );
                                        } elseif (count($allDetails[8][$jsvalue['judgement_statement_instance_id']]) == 3) {
                                            $rList = array(array(
                                                    "ratingTitle" => "",
                                                    "ratingValue" => "",
                                                    "ratingLevelText" => array($rating1, $rating2, $rating3)
                                                )
                                            );
                                        } else {
                                            $rList = array(array(
                                                    "ratingTitle" => "",
                                                    "ratingValue" => "",
                                                    "ratingLevelText" => array($rating1, $rating2, $rating3)
                                                )
                                            );
                                        }
                                        //For getting the multiple evidence files start
                                        $files = array();
                                        if (!empty($jsvalue['files'])) {
                                            $evidenceFiles = explode("||", $jsvalue['files']);
                                            foreach ($evidenceFiles as $evidenceFile) {
                                                $vals = explode("|", $evidenceFile);
                                                $files[] = empty($vals) ? '' : $vals[1];
                                            }
                                        }
                                        //For getting the multiple evidence files Ends
                                        $jslist[] = array(
                                            "id" => $jsvalue['judgement_statement_instance_id'],
                                            "position" => $js,
                                            "title" => $jsvalue['judgement_statement_text'],
                                            "ratingScale" => $assessmentModel->countLvlBTnInDiag($value['diagnostic_id']),
                                            "ratingScaleTotal" => count($allDetails[8][$jsvalue['judgement_statement_instance_id']]),
                                            "ratingList" => $rList,
                                            "textBoxList" => array(array(
                                                    "boxTitle" => "Learning Walk",
                                                    "boxText" => $jsvalue['evidence_text_lw']
                                                ), array(
                                                    "boxTitle" => "Class Observations",
                                                    "boxText" => $jsvalue['evidence_text_co']
                                                ), array(
                                                    "boxTitle" => "Interactions",
                                                    "boxText" => $jsvalue['evidence_text_in']
                                                ), array(
                                                    "boxTitle" => "Book Look",
                                                    "boxText" => $jsvalue['evidence_text_bl']
                                                )
                                            ),
                                            "evidenceList" => $files,
                                            "question" => (object) []
                                        );
                                        unset($files, $rList, $rating1, $rating2, $rating3, $rating11, $rating12, $rating13);
                                        $js++;
                                    }
                                    $sblist[] = array(
                                        "id" => $sbvalue['core_question_instance_id'],
                                        "position" => $sb,
                                        "title" => "",
                                        "jsListWrapper" => array(
                                            "levelDisplayPrefix" => "Judgement Statement",
                                            "isLevelHidden" => FALSE,
                                            "jsList" => $jslist
                                        ),
                                    );
                                    $sb++;
                                }
                                $kqlist[] = array(
                                    "id" => $kqval['key_question_instance_id'],
                                    "position" => $kq,
                                    "title" => "",
                                    "cqListWrapper" => array(
                                        "levelDisplayPrefix" => "Sub Question",
                                        "isLevelHidden" => TRUE,
                                        "cqList" => $sblist,
                                    ),
                                );
                                $kq++;
                            }
                            //Key Question loop ends
                            $kpalist[] = array(
                                "id" => $allDetail['kpa_instance_id'],
                                "position" => $kp,
                                "title" => $allDetail['kpa_name'],
                                "keyRecommendations" => array(
                                    "isLevelHidden" => FALSE,
                                    "celebrateList" => $celebrateName,
                                    "recommendationsList" => $reclist,
                                ),
                                "kqListWrapper" => array(
                                    "levelDisplayPrefix" => "Key Question",
                                    "isLevelHidden" => TRUE,
                                    "kqList" => $kqlist,
                                ),
                            );
                            $kp++;
                            unset($celebrateName);
                        }
                    } else {
                        $kpalist = array();
                    }
                    $assessLists[] = array(
                        "id" => $value['assessment_id'],
                        "schoolName" => $value['client_name'],
                        "diagnosticName" => $value['diagnosticName'],
                        "dateOfReview" => $value['aqs_start_date'] . " to " . $value['aqs_end_date'],
                        "schoolProfileStatus" => ($value['profile_status'] == 1) ? 'done' : 'incomplete',
                        "isLead" => $leadRole,
                        "role" => $userRole,
                        "permissions" => array(
                            "isExport" => $export,
                            "isImport" => $import,
                            "isDownload" => $isDownload
                        ),
                        "reviewType" => ($value['iscollebrative'] == 1) ? "collaborative" : "validated",
                        "ctKpa" => ($leadRole) ? count(explode(",", $value['kpa'])) : 0,
                        "kpaListWrapper" => array(
                            "levelDisplayPrefix" => "KPA",
                            "isLevelHidden" => FALSE,
                            "kpaList" => $kpalist
                        ),
                    );
                }
                unset($export, $import, $isDownload);
                $userLists = array(
                    "id" => $user['user_id'],
                    'assessmentList' => $assessLists
                );
                $lists = array(
                    'user' => $userLists
                );
                $this->apiResult ["status"] = 200;
                $this->apiResult ["message"] = $lists;
            } else {
                $userLists = array(
                    "id" => $user['user_id'],
                    'assessmentList' => array()
                );
                $lists = array(
                    'user' => $userLists
                );
                $this->apiResult ["status"] = 200;
                $this->apiResult ["message"] = $lists;
            }
        } else {
            return $this->apiResult ["message"] = "Fields are empty";
        }
    }

    function keysrecommendations($assessment_id, $lang_id, $type, $instance_id, $assessor_id, $diagnostic_type, $external, $is_collaborative, $kpa7id, $user) {
        $assessment_id = empty($assessment_id) ? 0 : $assessment_id;
        $lang_id = empty($lang_id) ? DEFAULT_LANGUAGE : $lang_id;
        $type = empty($type) ? 0 : $type;
        $instance_id = empty($instance_id) ? 0 : $instance_id;
        $assessor_id = empty($assessor_id) ? 0 : $assessor_id;
        $diagnostic_type = empty($diagnostic_type) ? 0 : $diagnostic_type;
        $external = empty($external) ? 0 : $external;
        $is_collaborative = empty($is_collaborative) ? 0 : $is_collaborative;
        $kpa7id = empty($kpa7id) ? 0 : 0;
        $isAdmin = in_array("view_all_assessments", $user['capabilities']) ? true : false;
        $isNetworkAdmin = in_array("view_own_network_assessment", $user['capabilities']) && $user['network_id'] > 0 ? true : false;
        $isSchoolAdmin = in_array("view_own_institute_assessment", $user['capabilities']) ? true : false;
        $diagnosticModel = new diagnosticModel();
        if ((in_array(6, $user['role_ids']) || in_array(5, $user['role_ids'])) && $user['has_view_video'] != 1 && $user['is_web'] == 1)//principal and school admin have to view video for self-review
            return array('Not Allowed.');
        elseif ($assessment_id > 0 && $assessment = $diagnosticModel->getAssessmentByUser($assessment_id, $assessor_id, $lang_id, $external)) {
            $assessor_id = $assessment['user_id'];

            if ($assessor_id == $user['user_id'] || ($assessment['status'] == 1 && ( $isAdmin || ($isSchoolAdmin && $assessment['client_id'] == $user['client_id'] && $assessment['role'] == 3) || ($isNetworkAdmin && $assessment['network_id'] == $user['network_id'] && $assessment['role'] == 3)
                    )
                    )
            ) {


                $isReadOnly = $assessment['report_published'] == 1 || ($assessment['status'] == 1 && !in_array("edit_all_submitted_assessments", $user['capabilities'])) ? 1 : 0;
                $diagnosticModel = new diagnosticModel();
                $akns = $diagnosticModel->getAssessorKeyNotesForType($assessment_id, $type, $instance_id);
                if (!empty($akns)) {
                    $celebrate = array();
                    $recommendation = array();
                    foreach ($akns as $key => $value) {
                        if ($akns[$key]['type'] == 'celebrate') {
                            $celebrate[] = $akns[$key];
                        }
                        if ($akns[$key]['type'] == 'recommendation') {
                            $recommendation[] = $akns[$key];
                        }
                    }
                    return array($celebrate, $recommendation);
                } else {
                    return array('Recommendation details are not found.');
                }
            } else {
                return array('Not Allowed.');
            }
        }
    }

    function getKpaDetails($assessment_id, $assessor_id, $user, $kapUsers) {
        $lang_id = empty($_GET['lang_id']) ? 0 : $_GET['lang_id'];
        $assessment_id = empty($assessment_id) ? 0 : $assessment_id;
        $assessor_id = empty($assessor_id) ? $user['user_id'] : $assessor_id;
        $isAdmin = in_array("view_all_assessments", $user['capabilities']) ? true : false;
        $isNetworkAdmin = in_array("view_own_network_assessment", $user['capabilities']) && $user['network_id'] > 0 ? true : false;
        $isSchoolAdmin = in_array("view_own_institute_assessment", $user['capabilities']) ? true : false;
        $diagnosticModel = new diagnosticModel();
        $prefferedLanguage = $diagnosticModel->getAssessmentPrefferedLanguage($assessment_id);
        $lang_id_show = empty($lang_id) ? $prefferedLanguage['language_id'] : $lang_id;
        $kpa_user = array();
        //$kpa_user = explode(',',$this->assRow['kpa_user']);
        $external = empty($_GET['external']) ? 0 : $_GET['external'];
        $externalTeamStatus = $diagnosticModel->checkAssessorExternalTeam($assessment_id, $assessor_id);

        if (isset($externalTeamStatus['isExternal']) && $externalTeamStatus['isExternal'] == 0) {
            $external = 0;
        } elseif (isset($externalTeamStatus['isExternal']) && $externalTeamStatus['isExternal'] == 1) {
            $external = 1;
        }

        if ((in_array(6, $user['role_ids']) || in_array(5, $user['role_ids'])) && $user['has_view_video'] != 1 && $user['is_web'] == 1)//principal and school admin have to view video for self-review
            return array();
        elseif ($assessment_id > 0 && $assessor_id > 0 && $assessment = $diagnosticModel->getAssessmentByUser($assessment_id, $assessor_id, $lang_id_show, $external)) {
            $diagData = $diagnosticModel->getDiagnosticName($assessment['diagnostic_id'], $lang_id_show);
            $is_collaborative = isset($assessment['iscollebrative']) ? $assessment['iscollebrative'] : 0;
            $isLeadSave = isset($assessment['isLeadSave']) ? $assessment['isLeadSave'] : 0;
            $isLeadAssessor = 0;

            if ($assessor_id == $user['user_id'] || ($assessment['status'] == 1 && ( $isAdmin || ($isSchoolAdmin && $assessment['client_id'] == $user['client_id'] && $assessment['role'] == 3) || ($assessment['role'] == 3 && $user['user_id'] == $assessment['external'] && $assessment['assessment_type_id'] == 2 && $assessment['status'] == 1) || ($isNetworkAdmin && $assessment['network_id'] == $user['network_id'] && $assessment['role'] == 3)))) {
                $subAssessmentType = empty($assessment['subAssessmentType']) ? 0 : $assessment['subAssessmentType'];
                $assessmentModel = new assessmentModel();
                $subAssessmentType == 1 ? ($isApproved = $assessment['isApproved']) : '';

                if ($subAssessmentType == 1 && ($isApproved == 0 || $isApproved == 2) && !in_array("edit_all_submitted_assessments", $user['capabilities'])) {
                    return array();
                }

                $isReadOnly = $assessment['report_published'] == 1 || ($assessment['status'] == 1 && !in_array("edit_all_submitted_assessments", $user['capabilities'])) ? 1 : 0;

                $ratingLevelText = array();
                $diagnostic_type = isset($assessment['diagnostic_type']) ? $assessment['diagnostic_type'] : 0;
                if ($diagnostic_type == 1) {
                    $ratingLevelText = $this->db->array_grouping($diagnosticModel->ratingLevalText(), 'judgement_statement_instance_id');
                }
                //get all 
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
                    } else {
                        $kpas = $this->db->array_col_to_key($diagnosticModel->getKpasForAssessment($assessment_id, $assessor_id, 0, $lang_id_show, 0, $external, '', $diagnostic_type), "kpa_instance_id");
                        $kqs = $this->db->array_grouping($diagnosticModel->getKeyQuestionsForAssessment($assessment_id, $assessor_id, 0, $lang_id_show, $external), "kpa_instance_id", "key_question_instance_id");
                        $cqs = $this->db->array_grouping($diagnosticModel->getCoreQuestionsForAssessment($assessment_id, $assessor_id, 0, $lang_id_show, $external), "key_question_instance_id", "core_question_instance_id");
                        $jss = $this->db->array_grouping($diagnosticModel->getJudgementalStatementsForAssessment($assessment_id, $assessor_id, 0, $lang_id_show, $external, '', '', $diagnostic_type), "core_question_instance_id", "judgement_statement_instance_id");
                        if ($is_collaborative == 1 && $assessment['status'] == 1 && $isLeadAssessor == 1 && $assessment['percComplete'] == 100) {
//                            $this->set("isLeadAssessorKpa", 1);
                        } else if ($is_collaborative == 1 && $isLeadSave == 1 && $assessment['status'] == 0 && $isLeadAssessor == 1 && $assessment['percComplete'] == 100) {
                            
                        }
                    }
                } else {
                    $kpas = $this->db->array_col_to_key($diagnosticModel->getKpasForAssessment($assessment_id, $assessor_id, 0, $lang_id_show, $is_collaborative, $external, '', $diagnostic_type), "kpa_instance_id");
                    $kqs = $this->db->array_grouping($diagnosticModel->getKeyQuestionsForAssessment($assessment_id, $assessor_id, 0, $lang_id_show, $external), "kpa_instance_id", "key_question_instance_id");
                    $cqs = $this->db->array_grouping($diagnosticModel->getCoreQuestionsForAssessment($assessment_id, $assessor_id, 0, $lang_id_show, $external), "key_question_instance_id", "core_question_instance_id");
                    $jss = $this->db->array_grouping($diagnosticModel->getJudgementalStatementsForAssessment($assessment_id, $assessor_id, 0, $lang_id_show, $external, '', '', $diagnostic_type), "core_question_instance_id", "judgement_statement_instance_id");
                }
                if (isset($assessment) && $assessment['role'] == 4) {
                    $diagData[0]['js_recommendations'] == 1 ? $this->db->array_grouping($diagnosticModel->getAssessorKeyNotesLevel($assessment_id, 'judgement_statement_instance_id'), 'judgement_statement_instance_id', 'id') : 0;
                    $diagData[0]['kpa_recommendations'] == 1 ? $this->db->array_grouping($diagnosticModel->getAssessorKeyNotesLevel($assessment_id, 'kpa_instance_id'), 'kpa_instance_id', 'id') : 0;
                    $diagData[0]['kq_recommendations'] == 1 ? $this->db->array_grouping($diagnosticModel->getAssessorKeyNotesLevel($assessment_id, 'key_question_instance_id'), 'key_question_instance_id', 'id') : 0;
                    $diagData[0]['cq_recommendations'] == 1 ? $this->db->array_grouping($diagnosticModel->getAssessorKeyNotesLevel($assessment_id, 'core_question_instance_id'), 'core_question_instance_id', 'id') : 0;
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
                $dig_image = $diagnosticModel->getDiagnosticImage($assessment['diagnostic_id']);
                $image_name = $dig_image[0]['file_name'];
                $diagnosticLabels = array();

                $diagnosticLabelsData = $diagnosticModel->getDiagnosticLabels($assessment['diagnostic_id'], $lang_id_show);
                foreach ($diagnosticLabelsData as $data) {
                    $diagnosticLabels[$data['label_key']] = $data['label_text'];
                }
                return array($kpas, $kqs, $cqs, $jss, $diagnostic_type, $external, $is_collaborative, $prefferedLanguage, $ratingLevelText);
            } else {
                return array();
            }
        } else {
            return array();
        }
    }

    function __destruct() {
        if ($this->_render) {
            $this->set("notPermitted", $this->_notPermitted);
            $this->set("is404", $this->_is404);
            $this->_template->render();
        } else {
            echo json_encode($this->apiResult, JSON_PRETTY_PRINT);
            exit;
        }
    }

}
