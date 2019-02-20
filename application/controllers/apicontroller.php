<?php

/**
 * Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 * 
 * Reasons: Manage All Api Of All Modules
 * 
 */
class apiController extends controller {

    protected $apiResult;
    protected $_postData;
    private $numComplete;
    private $numInComplete;
    private $isAnswerFill;

    /*
     * @Purpose:Initialize api class 
     * @params: $controller, $action
     */
    function __construct($controller, $action) {
        $this->db = db::getInstance();
        $this->_controller = $controller;
        $this->_action = $action;
        $this->apiResult = array(
            "status" => 0,
            "message" => ""
        );
        $this->userModel = new userModel ();
        if ($action != "login" && !isset($_POST ['process'])) {
            $this->checkToken();
        }
    }

    function __destruct() {
        echo json_encode($this->apiResult);
        exit();
    }

    
    /*
     * @Purpose:Function to check token for security of adhayayan app
     */
    protected function checkToken() {
        $token_refresh = isset($_COOKIE['ADH_TOKEN_REFRESH']) ? $_COOKIE['ADH_TOKEN_REFRESH'] : (isset($_POST ['token_refresh']) ? $_POST ['token_refresh'] : '');
        $token = isset($_POST ['token']) ? $_POST ['token'] : '';
        if ((empty($token_refresh) || empty($token))) {
            $this->apiResult ["message"] = "Token missing or expired. Please re-login";
            $this->apiResult ["status"] = - 1;
            exit();
        } elseif (!$this->userModel->userExist($token_refresh)) {
            $this->apiResult ["message"] = "Token missing or expired. Please re-login";
            $this->apiResult ["status"] = - 1;
            exit();
        }
        try {
            $token = isset($_POST ['token']) ? $_POST ['token'] : '';
            $decoded = Firebase\JWT\JWT::decode($token, PRIVATEKEY, array('HS256'));
            $decoded_array = (array) $decoded;
            $this->user = (array) $decoded_array['user'];
        } catch (Exception $e) {
            $adh_token_refresh = isset($_COOKIE['ADH_TOKEN_REFRESH']) ? $_COOKIE['ADH_TOKEN_REFRESH'] : $_POST ['token_refresh'];
            if (!isset($adh_token_refresh) || !$this->user = $this->userModel->checkToken($adh_token_refresh)) {
                $this->apiResult ["message"] = "Token missing or expired. Please re-login";
                $this->apiResult ["status"] = - 1;
                exit();
            } else {
                $time = time();
                $issuedAt = $time;
                $notBefore = $issuedAt; //Adding 10 seconds
                $expire = $notBefore + TOKEN_LIFE_REFRESH;
                $refresh = $notBefore + TOKEN_LIFE;
                $jwt_token = array(
                    "iss" => "adhyayan.asia",
                    "jti" => $adh_token_refresh,
                    "iat" => $issuedAt,
                    "nbf" => $notBefore,
                    "exp" => $expire,
                    "user" => $this->user
                );
                $jwt_token = Firebase\JWT\JWT::encode($jwt_token, PRIVATEKEY, 'HS256');
                setcookie('ADH_TOKEN', $jwt_token, 0, '/; samesite=strict', '', COOKIE_SECURE, COOKIE_HTTPONLY);
                if (COOKIE_GEN == 1) {
                    setcookie('ADH_TOKEN', $jwt_token, 0, '/; samesite=strict', COOKIE_DOMAIN, COOKIE_SECURE, COOKIE_HTTPONLY);
                }
            }
        }
    }

    /*
     * @Purpose:Function to get current user
     */
    public function getCurrentUserAction() {
        $this->apiResult['data'] = $this->user;
        $this->apiResult ["status"] = 1;
    }

    //@Purpose:report download for overall schools
    function reportallSchollsAction() {
        ini_set('max_execution_time', 1200000);
        $diagnosticModel = new diagnosticModel();
        $group_assessment_id = $_REQUEST['group_assessment_id'];
        $report_id = $_REQUEST['report_id'];
        $month = $_REQUEST['months'];
        $year = $_REQUEST['years'];
        $reportMod = new reportModel();
        $assData = $reportMod->getAllSchoolAssessments($_REQUEST);
        if ($assData) {
            $i = 0;
            $zip = new ZipArchive();
            $target_file_path = "" . ROOT . "uploads" . DS . "download_pdf";
            if (!is_dir($target_file_path)) {
                if (!mkdir($target_file_path, 0777, true)) {
                    $this->apiResult['message'] = "Failed to create folders.";
                    $this->apiResult ["status"] = 0;
                }
            }
            $source = "" . ROOT . "uploads" . DS . "download_pdf" . DS . "";
            $tmpPath = "" . ROOT . "uploads" . DS . "download_pdf" . DS . "";
            $zip_path = "" . $tmpPath . "reports" . strtotime("now") . ".zip";

            if ($zip->open($zip_path, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE) !== TRUE) {
                die("An error occurred creating your ZIP file.");
            }
            $pdf = new customReportController('', '');
            $now = strtotime("now");
            foreach ($assData as $key => $tchr) {
                $_REQUEST['report_id'] = $_REQUEST['report_id'];
                $_REQUEST['months'] = $_REQUEST['months'];
                $_REQUEST['years'] = $_REQUEST['years'];
                $_REQUEST['group_assessment_id'] = $_REQUEST['group_assessment_id'];
                $_REQUEST['assessment_id'] = $tchr['assessment_id'];
                $_REQUEST['diagnostic_id'] = 0;
                $_REQUEST['assessor_id'] = $tchr['user_id'];
                $_REQUEST['lang_id'] = $_REQUEST['lang_id'];
                $filename = $now . "-schoolevaluationreport-" . $tchr['client_name'];
                $pdf->generateAqsReportAction(1, $filename);
                if (isset($_REQUEST['province'])) {
                    $dstfile1 = "" . $tchr['province_name'] . "/" . $filename . ".pdf";
                } else if (isset($_REQUEST['network'])) {
                    $dstfile1 = "" . $tchr['network_name'] . "/" . $filename . ".pdf";
                } else {
                    $dstfile1 = "" . $filename . ".pdf";
                }
                $source1 = "" . $source . "" . $filename . ".pdf";
                if (is_file($source1)) {
                    $zip->addFile($source1, $dstfile1);
                    $allfiles[] = $filename;
                    $i++;
                }
            }

            $zip->close();
            if ($i > 0) {
                header('Content-type: application/zip');
                header('Content-Disposition: attachment; filename="reports' . strtotime("now") . '.zip"');
                echo readfile($zip_path);
                unlink($zip_path);
                foreach ($allfiles as $key => $val) {
                    $file_path = "" . $tmpPath . $val . ".pdf";
                    unlink($file_path);
                }
            } else {
                $this->apiResult['message'] = "Problem while downloading";
                $this->apiResult ["status"] = 0;
            }
        } else {
            $this->apiResult['message'] = "No review found";
            $this->apiResult ["status"] = 0;
        }
        exit;
    }

    //@Purpose:Login for system every users
    function loginAction() {
        $this->apiResult ["confirmstatus"] = 1;
        if (empty($_POST ['email']))
            $this->apiResult ["message"] = "Username field missing";

        else if (empty($_POST ['password']))
            $this->apiResult ["message"] = "Password field missing";

        else if ($res = $this->userModel->authenticateUser($_POST ['email'], $_POST ['password'])) {
            if (isset($res['user_id']) && isset($_POST['actionconfirm']) && $_POST['actionconfirm'] == 1) {
                $this->db->delete("session_token", array("user_id" => $res['user_id']));
            }
            $user_exists = 0;
            if (isset($res['user_id']) && !in_array(1, $res['role_ids']) && !in_array(2, $res['role_ids']) && $details_user = $this->userModel->userTokenExists($res['user_id'])) {
                $user_exists = count($details_user);
            }
            if ($user_exists > 0) {
                $server_details = unserialize($details_user['server_details']);
                $login_time = date("d-m-Y H:i:s", strtotime($details_user['created_date']));
                $ip = isset($server_details['REMOTE_ADDR']) ? "(IP:" . $server_details['REMOTE_ADDR'] . ")" : '';
                $agent = isset($server_details['HTTP_USER_AGENT']) ? "/" . $server_details['HTTP_USER_AGENT'] . "" : '';
                $this->apiResult ["errormsg"] = "You are already logged in from another computer " . $ip . " using the same credentials at " . $login_time . ". By logging in, you can lose the unsaved data from previous login.<br><b>Please confirm to proceed.</b>";
                $this->apiResult ["confirmstatus"] = 0;
                $this->apiResult ["status"] = 1;
            } else {
                if ($token = $this->userModel->generateToken($res ['user_id'], $_POST ['email'])) {
                    $time = time();
                    $issuedAt = $time;
                    $notBefore = $issuedAt; //Adding 10 seconds
                    $expire = $notBefore + TOKEN_LIFE_REFRESH;
                    $refresh = $notBefore + TOKEN_LIFE;
                    $jwt_token = array(
                        "iss" => "adhyayan.asia",
                        "jti" => $token,
                        "iat" => $issuedAt,
                        "nbf" => $notBefore,
                        "exp" => $expire,
                        "user" => $this->userModel->checkTokenJWT($res['user_id'])
                    );
                    $jwt_token = Firebase\JWT\JWT::encode($jwt_token, PRIVATEKEY, 'HS256');
                    setcookie('ADH_TOKEN_REFRESH', $token, 0, '/; samesite=strict', '', COOKIE_SECURE, COOKIE_HTTPONLY);
                    if (COOKIE_GEN == 1) {
                        setcookie('ADH_TOKEN', $jwt_token, 0, '/; samesite=strict', COOKIE_DOMAIN, COOKIE_SECURE, COOKIE_HTTPONLY);
                        setcookie('ADH_TOKEN_REFRESH', $token, 0, '/; samesite=strict', COOKIE_DOMAIN, COOKIE_SECURE, COOKIE_HTTPONLY);
                    }
                    $this->apiResult ["status"] = 1;
                    $this->apiResult ["token"] = $jwt_token;
                    $this->apiResult ["message"] = "Successfully logged in";
                } else
                    $this->apiResult ["message"] = "Unable to generate token, please try again.";
            }
        } else
            $this->apiResult ["message"] = "Invalid username or password";
    }

    /*
     * @Purpose:internal  assessor for schools
     */

    function getInternalAssessorsAction() {
        if (!(in_array("create_assessment", $this->user ['capabilities']) || in_array("create_self_review", $this->user ['capabilities']))) {
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        } else if (empty($_POST ['client_id'])) {
            $this->apiResult ["message"] = "School id cannot be empty\n";
        } else {
            $assessmentModel = new assessmentModel ();
            $this->apiResult ["assessors"] = $assessmentModel->getInternalAssessors($_POST ['client_id']);
            $this->apiResult ["rounds"] = $assessmentModel->getSchoolRounds();
            $roundsUnusedi = $assessmentModel->getSchoolRemainingRounds($_POST ['client_id']);
            $roundsUnusedf = array();
            foreach ($roundsUnusedi as $key => $val) {
                $roundsUnusedf[] = $val['aqs_round'];
            }
            $this->apiResult ["roundsUnused"] = $roundsUnusedf;
            $this->apiResult ["status"] = 1;
        }
    }

    //@Purpose:It is for save initial data kpa and js pop
    function createActionPlanNewAction() {
        $check = false;
        $final_post_array = array();
        $i = 0;
        $actionModel = new actionModel();
        $recids = array();
        $duplicate = false;
        $msg = "";
        foreach ($_POST['kpa'] as $key => $val) {
            $kpa = $val;
            $js = $_POST['js'][$key];
            if (empty($kpa) || empty($js)) {
                $check = true;
            } else {
                $final_post_array[$i]['text_data'] = '';
                $final_post_array[$i]['kpa_instance_id'] = $kpa;
                $final_post_array[$i]['rec_judgement_instance_id'] = $js;
                if (in_array($js, $recids)) {
                    $duplicate = true;
                    $key = array_search($js, $recids);
                    $msg .= "Duplicate Rows : Row-" . ($i + 1) . " is duplicate of Row-" . ($key + 1) . "\n";
                }
                $recids[] = $js;
                $i++;
            }
        }
        if (empty($_POST ['assessment_id'])) {
            $this->apiResult ["message"] = "Assessment id cannot be empty\n";
        } else if ($check) {
            $this->apiResult ["message"] = "Some field is missing.Please check\n";
        } else if ($duplicate) {
            $this->apiResult ["message"] = "" . $msg . " Please remove.\n";
        } else {
            $this->db->start_transaction();
            $success = true;
            foreach ($final_post_array as $key => $val) {
                if (!$actionModel->addactionplan($_POST ['assessment_id'], $val)) {
                    $success = false;
                }
            }
            if (!$success) {
                $this->db->rollback();
                $this->apiResult ["status"] = 0;
                $this->apiResult ["message"] = "Unable to add data";
            } else {
                $this->db->commit();
                $this->apiResult ["status"] = 1;
                $this->apiResult ["message"] = "saved successfully";
            }
        }
    }

    //@Purpose:save data for action plan 1
    function createAction1Action() {
        $current_id = (isset($_POST['id_c']) && $_POST['id_c'] > 0) ? 1 * $_POST['id_c'] : 0;
        $current_status = (isset($_POST['id_y']) && $_POST['id_y'] > 0) ? 1 * $_POST['id_y'] : 0;
        $principle_email = isset($_POST['principle_email']) ? $_POST['principle_email'] : '';
        $principle_name = isset($_POST['principle_name']) ? $_POST['principle_name'] : '';
        $school_name = isset($_POST['school_name']) ? $_POST['school_name'] : '';
        $this->apiResult ["error"] = 0;
        if (empty($_POST['assessment_id'])) {
            $this->apiResult ["message"] = "Assessment-id cannot be blank ";
        } else {
            if ($current_id > 0) {
                $current_stackholder = isset($_POST['stackholder'][$current_id]) ? $_POST['stackholder'][$current_id] : array();
                $current_impact = isset($_POST['stackholderimpact'][$current_id]) ? $_POST['stackholderimpact'][$current_id] : array();
                $valid_stack = 1;
                $valid_impact = 1;
                foreach ($current_stackholder as $key => $val) {
                    $current_impact_val = $current_impact[$key];
                    if (empty($current_impact_val)) {
                        $valid_impact = 0;
                    }

                    if (empty($val)) {
                        $valid_stack = 0;
                    }
                }
                $assessor_key_notes_id = isset($_POST['assessor_key_notes_id']) ? $_POST['assessor_key_notes_id'] : array();
                $currentkey = array_search($current_id, $assessor_key_notes_id);
                $currentid = isset($assessor_key_notes_id[$currentkey]) ? $assessor_key_notes_id[$currentkey] : '';
                $currentfrom_date = (isset($_POST['from_date'][$currentkey]) && !empty($_POST['from_date'][$currentkey])) ? $_POST['from_date'][$currentkey] : '';
                if ($current_status == 0 && empty($currentfrom_date)) {
                    $currentfrom_date = date("d-m-Y");
                }
                $currentto_date = (isset($_POST['to_date'][$currentkey]) && !empty($_POST['to_date'][$currentkey])) ? $_POST['to_date'][$currentkey] : '';
                $currentleader = isset($_POST['leader'][$currentkey]) ? $_POST['leader'][$currentkey] : '';
                $currentfrequency_report = isset($_POST['frequency_report'][$currentkey]) ? $_POST['frequency_report'][$currentkey] : '';
                $currentreporting_authority = isset($_POST['reporting_authority'][$currentkey]) ? $_POST['reporting_authority'][$currentkey] : '';

                $this->apiResult ["message"] = array();
                if (empty($currentid)) {
                    $this->apiResult ["message"]['rec_id'] = "Please check the Recommendation-id.\n";
                }
                if ($valid_stack == 0) {
                    $this->apiResult ["message"][$current_id] = "Please check  all the  Stakeholder/Impact Statement.\n";
                }
                if ($valid_impact == 0) {
                    $this->apiResult ["message"][$current_id] = "Please check  all the  Stakeholder/Impact Statement.\n";
                }
                $error_fromdate = false;
                $error_todate = false;
                if (empty($currentfrom_date) && $current_status != 0) {
                    $this->apiResult ["message"]['fromdate_' . $current_id . ''] = "From Date cannot be empty.\n";
                    $error_fromdate = true;
                }
                if (empty($currentto_date)) {
                    $this->apiResult ["message"]['todate_' . $current_id . ''] = "To Date cannot be empty.\n";
                    $error_todate = true;
                }
                if (!$error_fromdate) {
                    if (count(explode('-', $currentfrom_date)) == 3) {
                        list($dd, $mm, $yyyy) = explode('-', $currentfrom_date);
                        if (!checkdate($mm, $dd, $yyyy)) {
                            $this->apiResult ["message"]['fromdate_' . $current_id . ''] = "Check the date format.\n";
                            $error_fromdate = true;
                        }
                    } else {
                        $this->apiResult ["message"]['fromdate_' . $current_id . ''] = "Check the date format.\n";
                        $error_fromdate = true;
                    }
                }
                if (!$error_todate) {
                    if (count(explode('-', $currentto_date)) == 3) {
                        list($dd1, $mm1, $yyyy1) = explode('-', $currentto_date);
                        if (!checkdate($mm1, $dd1, $yyyy1)) {
                            $error_todate = true;
                            $this->apiResult ["message"]['todate_' . $current_id . ''] = "Check the date format.\n";
                        }
                    } else {
                        $error_todate = true;
                        $this->apiResult ["message"]['todate_' . $current_id . ''] = "Check the date format.\n";
                    }
                }

                if (!$error_todate && !$error_fromdate && date("Y-m-d", strtotime($currentto_date)) < date("Y-m-d", strtotime($currentfrom_date))) {
                    $this->apiResult ["message"]['todate_' . $current_id . ''] = "To Date should be greater than From date.\n";
                } else {
                    $actionModel = new actionModel();
                    $previous_from_date = $_POST['previous_fromdate_' . $current_id];
                    $previous_to_date = $_POST['previous_todate_' . $current_id];
                    $this->apiResult ['fromdate'] = $previous_from_date;
                    $this->apiResult ['todate'] = $previous_to_date;
                    if (!empty($previous_from_date) && date("Y-m-d", strtotime($currentfrom_date)) < date("Y-m-d", strtotime($previous_from_date))) {
                        $this->apiResult ['popup'] = "From Date should be greater than original From date.\n";
                        $this->apiResult ['error'] = 1;
                    } else {
                        $actionDateValidationStatus = $actionModel->checkActionActivityDate($_POST['assessment_id'], $current_id, date("Y-m-d", strtotime($currentfrom_date)), date("Y-m-d", strtotime($currentto_date)));
                        if (!empty($actionDateValidationStatus)) {
                            $this->apiResult ['popup'] = "Action activities date is incorrect.First change action activities date.\n";
                            $this->apiResult ['error'] = 1;
                        }

                        $dateValidationStatus = $actionModel->checkImpactStatementDate($_POST['assessment_id'], $current_id, date("Y-m-d", strtotime($currentfrom_date)), date("Y-m-d", strtotime($currentto_date)));
                        if (!empty($dateValidationStatus)) {
                            $this->apiResult ['popup'] = "Impact statements date is incorrect.First change impact statements date.\n";
                            $this->apiResult ['error'] = 1;
                        }
                    }
                }
                if (empty($currentleader)) {
                    $this->apiResult ["message"]['leader_' . $current_id . ''] = "Leader cannot be empty.\n";
                }
                if (empty($currentfrequency_report)) {
                    $this->apiResult ["message"]['frequency_r_' . $current_id . ''] = "Frequency cannot be empty.\n";
                }
                if (empty($currentreporting_authority)) {
                    $this->apiResult ["message"]['authority_' . $current_id . ''] = "Reporting Authority cannot be empty.\n";
                }
                $email_error = 0;
                if (!empty($currentreporting_authority)) {
                    $emailsids = explode(",", $currentreporting_authority);
                    foreach ($emailsids as $keye => $vale) {
                        if (preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,5})$^", trim($vale)) != 1) {
                            $email_error = 1;
                        }
                    }
                }
                if ($email_error == 1) {
                    $this->apiResult ["message"]['authority_' . $current_id . ''] = "Please check the email ids.\n";
                }
                if (count($this->apiResult ["message"]) > 0) {
                    $this->apiResult ["error"] = 1;
                }
                $this->apiResult ["status"] = 1;
            }

            if ($this->apiResult ["error"] == 0) {
                $assessor_key_notes_id = isset($_POST['assessor_key_notes_id']) ? $_POST['assessor_key_notes_id'] : array();
                $this->db->start_transaction();
                $success = true;
                $actionModel = new actionModel();
                foreach ($assessor_key_notes_id as $key => $val) {
                    $assessor_key_notes_id_c = $val;
                    $current_stackholder = isset($_POST['stackholder'][$assessor_key_notes_id_c]) ? $_POST['stackholder'][$assessor_key_notes_id_c] : array();
                    $current_impact = isset($_POST['stackholderimpact'][$assessor_key_notes_id_c]) ? $_POST['stackholderimpact'][$assessor_key_notes_id_c] : array();
                    $already_ids = isset($_POST['assessor_action1_impact_id'][$assessor_key_notes_id_c]) ? $_POST['assessor_action1_impact_id'][$assessor_key_notes_id_c] : array();
                    $currentfrom_date = (isset($_POST['from_date'][$key]) && !empty($_POST['from_date'][$key])) ? date("Y-m-d", strtotime($_POST['from_date'][$key])) : '0000-00-00';
                    if ($current_status == 0 && $current_id == $val && (empty($currentfrom_date) || $currentfrom_date == "0000-00-00")) {
                        $currentfrom_date = date("Y-m-d");
                    }
                    $currentto_date = (isset($_POST['to_date'][$key]) && !empty($_POST['to_date'][$key])) ? date("Y-m-d", strtotime($_POST['to_date'][$key])) : '0000-00-00';
                    $currentleader = isset($_POST['leader'][$key]) ? $_POST['leader'][$key] : '';
                    $currentfrequency_report = isset($_POST['frequency_report'][$key]) ? $_POST['frequency_report'][$key] : '';
                    $currentreporting_authority = isset($_POST['reporting_authority'][$key]) ? $_POST['reporting_authority'][$key] : '';
                    $status = 0;
                    if ($current_id == $val && $current_status == 0) {
                        $status = 1;
                    } else if ($current_id == $val && $current_status > 0) {
                        $status = $current_status;
                    }
                    $createdBy = $this->user['user_id'];
                    if (!$actionModel->addaction1($assessor_key_notes_id_c, $current_stackholder, $current_impact, $currentfrom_date, $currentto_date, $currentleader, $currentfrequency_report, $currentreporting_authority, $status, $createdBy, $current_id, $already_ids)) {
                        $success = false;
                    }
                }
                if (!$actionModel->updateOverallstatus($_POST['assessment_id'], 1)) {
                    $success = false;
                }
                if (!$success) {
                    $this->db->rollback();
                    $this->apiResult ["status"] = 0;
                    $this->apiResult ["message"] = "Unable to add data";
                } else {
                    $this->db->commit();
                    $leaderIds = array();
                    $leaderIdsString = '';
                    $emailParams = array();
                    if (!empty($_POST['id_c']) && !empty($_POST['leader'])) {
                        if (isset($_POST['leader'][$currentkey])) {
                            $leaderIds[] = empty($_POST['mail_status'][$currentkey]) ? $_POST['leader'][$currentkey] : '';
                        }
                        if (!empty($leaderIds)) {
                            $leaderIds = array_unique($leaderIds);
                            $leaderIdsString = implode(',', $leaderIds);
                            if (!empty($leaderIdsString)) {
                                $emailParams[] = $actionModel->getLeaderData($leaderIdsString);
                            }
                            if (!empty($emailParams[0]) && !in_array($principle_email, array_column($emailParams[0], 'email'))) {
                                $emailParams[0][] = array('email' => $principle_email, 'name' => $principle_name);
                            }
                            if (!empty($leaderIdsString) && !empty($emailParams)) {
                                $actionModel->sendNotificationMail($emailParams[0], $school_name, $current_id, $_POST['assessor_key_notes_id']);
                            }
                        }
                    }
                    $this->apiResult ["message"] = "Data Saved Successfully!";
                    $this->apiResult ["status"] = 1;
                }
            }
        }
    }

    //@Purpose:save action plan 2
    function createAction2Action() {

        $impactFiles = array();
        if (empty($_POST['assessment_id'])) {
            $this->apiResult ["message"] = "Assessment-id cannot be blank ";
        } else if (empty($_POST['id_c'])) {
            $this->apiResult ["message"] = "Recommendation-id cannot be blank ";
        } else if (empty($_POST['h_assessor_action1_id'])) {
            $this->apiResult ["message"] = "Action-id cannot be blank ";
        } else {
            $this->apiResult ["message"] = array();
            $this->apiResult ["message"]['team_designation'] = array();
            $this->apiResult ["message"]['team_member_name'] = array();
            $start_date = !empty($_POST['from_date']) ? $_POST['from_date'] : '';
            $end_date = !empty($_POST['to_date']) ? $_POST['to_date'] : '';
            $team_designation = $_POST['team_designation'];
            $is_submit = !empty($_POST['is_submit']) ? $_POST['is_submit'] : '';
            $error = 0;
            $team_add = array();
            $count = 0;
            foreach ($team_designation as $keyd => $vald) {
                $name = $_POST['team_member_name'][$keyd];
                if (empty($vald)) {
                    $this->apiResult ["message"]['team_designation'][$keyd] = "Designation cannot be blank";
                    $error = 1;
                }
                if (empty($name)) {
                    $this->apiResult ["message"]['team_member_name'][$keyd] = "Name cannot be blank";
                    $error = 1;
                }
                if (!empty($vald) && !empty($name)) {
                    $team_add[$count]['team_designation'] = $vald;
                    $team_add[$count]['team_member_name'] = $name;
                    $count++;
                }
            }
            $activity_stackholder_mutiple = array();
            if (!empty($_POST['activity_stackholder_check'])) {
                foreach ($_POST['activity_stackholder_check'] as $keyac => $valac) {
                    if (!isset($_POST['activity_stackholder'][$keyac])) {
                        $activity_stackholder_mutiple[] = array();
                    } else {
                        $activity_stackholder_mutiple[] = $_POST['activity_stackholder'][$keyac];
                    }
                }
            }
            $activity_stackholder = $activity_stackholder_mutiple;
            $this->apiResult ["message"]['activity_stackholder'] = array();
            $this->apiResult ["message"]['activity_details'] = array();
            $this->apiResult ["message"]['activity_status'] = array();
            $this->apiResult ["message"]['activity_date'] = array();
            $this->apiResult ["message"]['activity_comments'] = array();
            $this->apiResult ["message"]['activity'] = array();
            $this->apiResult ["message"]['activity_actual_date'] = array();
            $activity_add = array();
            $count1 = 0;
            foreach ($activity_stackholder as $keya => $vala) {
                $ad = $_POST['activity_details'][$keya];
                $astatus = $_POST['activity_status'][$keya];
                $adate = $_POST['activity_date'][$keya];
                $a = $_POST['activity'][$keya];
                $acomments = $_POST['activity_comments'][$keya];
                $acdate = $_POST['activity_actual_date'][$keya];
                $a_old_id = $_POST['activity_old_id'][$keya];
                if (!empty($vala) || !empty($ad) || $astatus != "" || !empty($adate) || !empty($acomments) || !empty($a)) {
                    if (empty($vala)) {
                        $this->apiResult ["message"]['activity_stackholder'][$keya] = "Stackholder cannot be blank";
                        $error = 1;
                    }
                    if (empty($a)) {
                        $this->apiResult ["message"]['activity'][$keya] = "Activity cannot be blank";
                        $error = 1;
                    }
                    if (empty($ad)) {
                        $this->apiResult ["message"]['activity_details'][$keya] = "Activity Details cannot be blank";
                        $error = 1;
                    }
                    if ($astatus == "") {
                        $this->apiResult ["message"]['activity_status'][$keya] = "Activity Status cannot be blank";
                        $error = 1;
                    }
                    $error_adate = false;
                    if (empty($adate)) {
                        $this->apiResult ["message"]['activity_date'][$keya] = "Activity Date cannot be blank";
                        $error = 1;
                        $error_adate = true;
                    }
                    if (!$error_adate) {
                        if (count(explode('-', $adate)) == 3) {
                            list($dd, $mm, $yyyy) = explode('-', $adate);
                            if (!checkdate($mm, $dd, $yyyy)) {
                                $this->apiResult ["message"]['activity_date'][$keya] = "Check the date format.It should be DD-MM-YYYY\n";
                                $error_adate = true;
                                $error = 1;
                            } else if (strlen($mm) != 2 || strlen($dd) != 2 || strlen($yyyy) != 4) {
                                $this->apiResult ["message"]['activity_date'][$keya] = "Check the date format.It should be DD-MM-YYYY\n";
                                $error_adate = true;
                                $error = 1;
                            }
                        } else {
                            $this->apiResult ["message"]['activity_date'][$keya] = "Check the date format.It should be DD-MM-YYYY\n";
                            $error_adate = true;
                            $error = 1;
                        }
                    }
                    if (!$error_adate) {
                        if (strtotime($adate) < strtotime($start_date) || strtotime($adate) > strtotime($end_date)) {
                            $this->apiResult['message']['activity_date'][$keya] = 'Date must be between action plan start and end date (' . $start_date . " to " . $end_date . ")";
                            $error = 1;
                        }
                    }
                    if ($astatus == "2") {
                        $error_fdate = false;
                        if (empty($acdate)) {
                            $this->apiResult ["message"]['activity_actual_date'][$keya] = "Actual Day cannot be blank for completed activities";
                            $error = 1;
                            $error_fdate = true;
                        }
                        if (!$error_fdate) {
                            if (count(explode('-', $acdate)) == 3) {
                                list($dd, $mm, $yyyy) = explode('-', $acdate);
                                if (!checkdate($mm, $dd, $yyyy)) {
                                    $this->apiResult ["message"]['activity_actual_date'][$keya] = "Check the date format.It should be DD-MM-YYYY\n";
                                    $error_fdate = true;
                                    $error = 1;
                                } else if (strlen($mm) != 2 || strlen($dd) != 2 || strlen($yyyy) != 4) {
                                    $this->apiResult ["message"]['activity_actual_date'][$keya] = "Check the date format.It should be DD-MM-YYYY\n";
                                    $error_fdate = true;
                                    $error = 1;
                                }
                            } else {
                                $this->apiResult ["message"]['activity_actual_date'][$keya] = "Check the date format.It should be DD-MM-YYYY\n";
                                $error_fdate = true;
                                $error = 1;
                            }
                            if (!$error_adate && !$error_fdate && (date("Y-m-d", strtotime($acdate)) < date("Y-m-d", strtotime($adate)))) {
                                $this->apiResult ["message"]['activity_actual_date'][$keya] = "Actual Day should be greater than Date.\n";
                                $error = 1;
                            }
                        }
                    }

                    if (empty($a_old_id) && $astatus == "3") {
                        $this->apiResult ["message"]['activity_status'][$keya] = "Activity Status cannot be postponed";
                        $error = 1;
                    }
                }

                if (!empty($vala) && !empty($ad) && $astatus != "" && !empty($adate) && !empty($a)) {
                    $activity_add[$count1]['activity_stackholder'] = $vala;
                    $activity_add[$count1]['activity'] = $a;
                    $activity_add[$count1]['activity_details'] = $ad;
                    $activity_add[$count1]['activity_status'] = $astatus;
                    $activity_add[$count1]['activity_date'] = $adate;
                    $activity_add[$count1]['activity_actual_date'] = $acdate;
                    $activity_add[$count1]['activity_comments'] = $acomments;
                    $activity_add[$count1]['activity_old_id'] = $a_old_id;
                    $count1++;
                }
            }
            $actionModel = new actionModel();
            if (!empty($_POST['impactStmnt'])) {
                $impactFiles = isset($_POST['impactStmnt']['files']) ? $_POST['impactStmnt']['files'] : array();
                if (isset($_POST['impactStmnt']['files'])) {
                    unset($_POST['impactStmnt']['files']);
                }
                $rowNo = 0;
                $numInsertedRows = 0;
                foreach ($_POST['impactStmnt'] as $stmntKey => $stmntData) {
                    foreach ($stmntData as $key => $values) {
                        if ((!empty($values['activity_method']) && $values['activity_method'] == 4)) {
                            unset($values['activity_option']);
                        } else if ((!empty($values['activity_method']) && $values['activity_method'] == 2)) {
                            unset($values['stakeholder']);
                        }
                        if (!empty(array_filter(array_values($values)))) {

                            if (empty($values['date'])) {
                                $this->apiResult['message']['impact_date'][$rowNo] = 'Date cannot be blank' .
                                        $error = 1;
                            } else if (strtotime($values['date']) < strtotime($start_date) || strtotime($values['date']) > strtotime($end_date)) {
                                $this->apiResult['message']['impact_date'][$rowNo] = 'Date must be between action plan start and end date (' . $start_date . " to " . $end_date . ")";
                                $error = 1;
                            }
                            if (empty($values['activity_method'])) {
                                $this->apiResult['message']['impact_activity_method'][$rowNo] = 'Activity method cannot be blank';
                                $error = 1;
                            }if ((!empty($values['activity_method']) && $values['activity_method'] == 4) && empty($values['stakeholder'])) {
                                $this->apiResult ['message']['impact_stakeholder'][$rowNo] = 'Stakeholder cannot be blank';
                                $error = 1;
                            }if ((!empty($values['activity_method']) && $values['activity_method'] == 2) && empty($values['activity_option'])) {
                                $this->apiResult ['message']['impact_activity_option'][$rowNo] = 'Classess cannot be blank';
                                $error = 1;
                            }if (empty($values['comments'])) {
                                $this->apiResult['message']['impact_comments'][$rowNo] = 'Comments method cannot be blank';
                                $error = 1;
                            }
                            $numInsertedRows++;
                        }
                        $rowNo++;
                    }
                }
                if (empty($numInsertedRows)) {
                    $deleteStatus = $actionModel->deleteImpactStatement($_POST['assessment_id'], $_POST['id_c']);
                }
            } else {
                $deleteStatus = $actionModel->deleteImpactStatement($_POST['assessment_id'], $_POST['id_c']);
            }
            if ($error > 0) {
                $this->apiResult ["error"] = 1;
            }
            $this->apiResult ["status"] = 1;
            if ($error == 0) {
                $success = true;
                $this->db->start_transaction();
                $h_assessor_action1_id = $_POST['h_assessor_action1_id'];
                $createdBy = $this->user['user_id'];
                if (!$actionModel->deleteTeamAction2($h_assessor_action1_id)) {
                    $success = false;
                }
                foreach ($team_add as $keyd => $vald) {
                    $td = $vald['team_designation'];
                    $tmn = $vald['team_member_name'];
                    if (!$actionModel->addTeamAction2($h_assessor_action1_id, $td, $tmn, $createdBy)) {
                        $success = false;
                    }
                }
                $ids_not_delete = array();
                if (!empty($_POST['activity_old_id'])) {
                    $ids_not_delete = array_filter($_POST['activity_old_id'], function($value) {
                        return $value !== '';
                    });
                }
                if (!$actionModel->deleteActionActivity2($h_assessor_action1_id, $ids_not_delete)) {
                    $success = false;
                }
                foreach ($activity_add as $keya => $vala) {
                    $valas = $vala['activity_stackholder'];
                    $a = $vala['activity'];
                    $ad = $vala['activity_details'];
                    $as = $vala['activity_status'];
                    $ada = date("Y-m-d", strtotime($vala['activity_date']));
                    $o_id = $vala['activity_old_id'];
                    if ($as == 2) {
                        $ac_date = (!empty($vala['activity_actual_date']) && $vala['activity_actual_date'] != "0000-00-00") ? (date("Y-m-d", strtotime($vala['activity_actual_date']))) : '';
                    } else {
                        $ac_date = "";
                    }
                    $ac = $vala['activity_comments'];
                    if (!$actionModel->addActionActivity2($h_assessor_action1_id, $valas, $a, $ad, $as, $ada, $ac_date, $ac, $createdBy, $o_id)) {
                        $success = false;
                    }
                }
                if (!$this->addImpactStatement($_POST, $impactFiles)) {
                    $success = false;
                }
                if ($is_submit == 1 && $success) {
                    if (!$actionModel->chngeActionActivity2Status($h_assessor_action1_id)) {
                        $success = false;
                    }
                }
                if (!$success) {
                    $this->db->rollback();
                    $this->apiResult ["status"] = 0;
                    $this->apiResult ["message"] = "Unable to add data";
                } else {
                    $this->db->commit();
                    $this->apiResult ["message"] = "Records added Successfully.";
                    $this->apiResult ["status"] = 1;
                }
            }
        }
    }

    //@Purpose:Add impact data for action plan
    function addImpactStatement($data, $files = array()) {
        $impactStmnt = isset($data['impactStmnt']) ? $data['impactStmnt'] : '';
        if (!empty($impactStmnt)) {
            $paramsData = array();
            $rowData = array();
            $error = 0;
            $actionPlanModel = new actionModel;
            foreach ($impactStmnt as $key => $stmnt) {
                foreach ($stmnt as $dateKey => $values) {
                    if (!empty(array_filter(array_values($values)))) {
                        $rowData['date'] = isset($values['date']) ? $values['date'] : '';
                        $rowData['activity_method_id'] = $values['activity_method'];
                        $rowData['activity_option_id'] = 0;
                        if (!empty($values['activity_option']) && isset($values['activity_method']) && ($values['activity_method'] == 2 || $values['activity_method'] == 4))
                            $rowData['activity_option_id'] = $values['activity_option'];
                        else if (!empty($values['stakeholder']) && isset($values['activity_method']) && ($values['activity_method'] == 4 )) {
                            $rowData['activity_option_id'] = $values['stakeholder'];
                        }
                        $rowData['comments'] = $values['comments'];
                        $rowData['assessment_id'] = $data['assessment_id'];
                        $rowData['action_plan_id'] = $data['id_c'];
                        $rowData['row_id'] = $dateKey;
                        $rowData['statement_id'] = $key;
                        $paramsData[] = $rowData;
                        $rowData = array();
                    }
                }
            }
            if (!empty($paramsData) && empty($error)) {
                $deleteStatus = $actionPlanModel->deleteImpactStatement($data['assessment_id'], $data['id_c'], $files);
                $insertStatus = $actionPlanModel->addImpactStatement($paramsData, $files);
                if ($insertStatus == 1) {
                    $error++;
                    return true;
                } else {
                    return false;
                }
            }
        }
        return true;
    }

    //@Purpose:Delete action plan row
    function deletePlanningrowAction() {
        if (empty($_POST['id_c'])) {
            $this->apiResult ["message"] = "Invalid key notes-id.\n";
        } else if (empty($_POST['assessment_id'])) {
            $this->apiResult ["message"] = "Invalid assessment-id.\n";
        } else {
            $assessment_id = $_POST['assessment_id'];
            $assessor_key_notes_id = $_POST['id_c'];
            $actionModel = new actionModel();
            $details_key = $actionModel->getrowdetails($assessor_key_notes_id);
            $success = true;
            $this->db->start_transaction();
            if ((isset($details_key['action_status']) && $details_key['action_status'] == 0) || !isset($details_key['action_status'])) {
                if (!$actionModel->deleteRec($_POST['assessment_id'], $assessor_key_notes_id)) {
                    $success = false;
                }
                if (!$success) {
                    $this->db->rollback();
                    $this->apiResult ["status"] = 0;
                    $this->apiResult ["message"] = "Unable to delete data";
                } else {
                    $this->db->commit();
                    $this->apiResult ["message"] = "Records deleted Successfully.";
                    $this->apiResult ["status"] = 1;
                }
            } else {
                $this->apiResult ["message"] = "Not allowed to delete.\n";
            }
        }
    }

    //@Purpose:for facilitator List
    function getFacilitatorsAction() {
        if (empty($_POST ['client_id'])) {
            $this->apiResult ["message"] = "School id cannot be empty\n";
        } else {
            $assessmentModel = new assessmentModel ();
            $this->apiResult ["assessors"] = $assessmentModel->getFacilitators($_POST ['client_id']);
            $this->apiResult ["status"] = 1;
        }
    }

    //@Purpose:Edit internal assessors data
    function getEditInternalAssessorsAction() {
        $isEditable = isset($_POST ['isEditable']) ? $_POST ['isEditable'] : 0;
        if (!(in_array("create_assessment", $this->user ['capabilities']) || $isEditable == 1)) {
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        } else if (empty($_POST ['client_id'])) {
            $this->apiResult ["message"] = "School id cannot be empty\n";
        } else if (empty($_POST ['user_id'])) {
            $this->apiResult ["message"] = "User id cannot be empty\n";
        } else {
            $assessmentModel = new assessmentModel ();
            $user_id = explode(',', $_POST ['user_id']);
            $user_id = $user_id [0]; // internal reviwer is at the first position in the list
            $this->apiResult ["assessors"] = $assessmentModel->getEditInternalAssessors($_POST ['client_id'], $user_id);
            $this->apiResult ["status"] = 1;
        }
    }

    /*
     * @Purpose:External reviewer for assessment
     */
    function getExternalAssessorsAction() {
        if (!in_array("create_assessment", $this->user ['capabilities'])) {
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        } else if (empty($_POST ['client_id'])) {
            $this->apiResult ["message"] = "School id cannot be empty\n";
        } else {
            $assessmentModel = new assessmentModel ();
            $this->apiResult ["assessors"] = $assessmentModel->getExternalAssessors($_POST ['client_id']);
            $this->apiResult ["status"] = 1;
        }
    }

    //@Purpose:fetch list of all diagnostics
    function getDiagnosticsForInternalAssessorAction() {
        if (!(in_array("create_assessment", $this->user ['capabilities']) || in_array("create_self_review", $this->user ['capabilities']))) {
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        } else if (empty($_POST ['internal_assessor_id'])) {

            $this->apiResult ["message"] = "Internal assessor id cannot be empty\n";
        } else if (empty($_POST ['client_id'])) {
            $this->apiResult ["message"] = "School id cannot be empty\n";
        } else {
            $assessmentModel = new assessmentModel ();
            $diagnosticModel = new diagnosticModel ();
            $asstId = empty($_POST ['assessment_id']) ? 1 : $_POST ['assessment_id'];
            $hideDiag = $assessmentModel->getDiagnosticsForInternalAssessor($_POST ['client_id'], $_POST ['internal_assessor_id'], $asstId);
            $this->apiResult ["hidediagnostics"] = $hideDiag;
            $this->apiResult ["allDiagnostics"] = $diagnosticModel->getDiagnostics(array(
                "assessment_type_id" => 1,
                "isPublished" => "yes"
                    ), 'all', 1);
            $this->apiResult ["status"] = 1;
        }
    }

    //@Purpose:get school admins for posted schools
    function getSchoolAdminsAction() {
        if (!in_array("create_assessment", $this->user ['capabilities'])) {
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        } else if (empty($_POST ['client_id'])) {
            $this->apiResult ["message"] = "School id cannot be empty\n";
        } else {
            $assessmentModel = new assessmentModel ();
            $this->apiResult ["schoolAdmins"] = $assessmentModel->getSchoolAdmins($_POST ['client_id']);
            $this->apiResult ["status"] = 1;
        }
    }

    /*
     * @Purpose:language list for selected diagonestic for assessment
     */
    function getDiagnosticLanguagesAction() {
        if (empty($_POST ['diagnostic_id'])) {
            $this->apiResult ["message"] = "Diagnostic language id cannot be empty\n";
        } else {
            $diagnosticModel = new diagnosticModel;
            $this->apiResult ["langDiagnostics"] = $diagnosticModel->getDiagnosticLanguages($_POST ['diagnostic_id']);
            $this->apiResult ["status"] = 1;
        }
    }

    //@Purpose:get rounds list
    function getroundsAction() {
        if (!in_array("create_assessment", $this->user ['capabilities'])) {
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        } else if (empty($_POST ['client_id'])) {
            $this->apiResult ["message"] = "School id cannot be empty\n";
        } else {
            $assessmentModel = new assessmentModel ();
            $this->apiResult ["rounds"] = $assessmentModel->getStudentRoundsAll();
            $roundsUnusedi = $assessmentModel->getStudentRounds($_POST ['client_id'], 0);
            $roundsUnusedf = array();
            foreach ($roundsUnusedi as $key => $val) {
                $roundsUnusedf[] = $val['aqs_round'];
            }
            $this->apiResult ["roundsUnused"] = $roundsUnusedf;
            $this->apiResult ["status"] = 1;
        }
    }

    //@Purpose:get network lists
    function getNetworkListAction() {
        if (!in_array("create_network", $this->user ['capabilities'])) {
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        } else {
            $networkModel = new networkModel ();
            $this->apiResult ["networks"] = $networkModel->getNetworkList();
            $this->apiResult ["status"] = 1;
        }
    }

    //@Purpose:get zone list
    function getZoneListAction() {
        if (!in_array("create_network", $this->user ['capabilities'])) {
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        } else {
            $networkModel = new networkModel ();
            $this->apiResult ["zones"] = $networkModel->getZoneList();
            $this->apiResult ["status"] = 1;
        }
    }

    //@Purpose:get state list
    function getStateListAction() {
        if (!in_array("create_network", $this->user ['capabilities'])) {
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        } else {
            $networkModel = new networkModel ();
            $this->apiResult ["states"] = $networkModel->getStateList();
            $this->apiResult ["status"] = 1;
        }
    }

    //@Purpose:get block list
    function getBlockListAction() {
        if (!in_array("create_network", $this->user ['capabilities'])) {
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        } else {
            $networkModel = new networkModel ();
            $this->apiResult ["blocks"] = $networkModel->getBlockList();
            $this->apiResult ["status"] = 1;
        }
    }

    //@Purpose:add school for a block
    function addSchoolToNetworkAction() {
        $networkModel = new networkModel ();
        if (!in_array("create_client", $this->user ['capabilities'])) {
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        } else if (empty($_POST ['client_ids']) || !is_array($_POST ['client_ids']) || count($_POST ['client_ids']) == 0) {
            $this->apiResult ["message"] = "School id cannot be empty\n";
        } else if (empty($_POST ['network_id'])) {
            $this->apiResult ["message"] = "Zone id cannot be empty\n";
        } else if ($network = $networkModel->getNetworkById($_POST ['network_id'])) {
            $clientModel = new clientModel ();
            $this->db->start_transaction();
            $success = true;
            $this->apiResult ["content"] = '';
            foreach ($_POST ['client_ids'] as $client_id) {
                $client = $clientModel->getClientById($client_id);
                if (empty($client ['client_id'])) {
                    $this->apiResult ["message"] = "School id $client_id does not exists\n";
                    $success = false;
                } else if ($client ['network_id'] > 0) {
                    $this->apiResult ["message"] = "School " . $client ['client_name'] . " is already associated with a zone '" . $client ['network_name'] . "'\n";
                    $success = false;
                } else if ($clientModel->addClientToNetwork($client_id, $_POST ['network_id'])) {
                    $this->apiResult ["content"] .= kpajsHelper::getEditSchoolsInnetworkRowHtml($_POST ['network_id'], $client);
                } else {
                    $this->apiResult ["message"] = "Unable to add school " . $client ['client_name'] . " to the network.";
                    $success = false;
                }
            }

            if ($success && $this->db->commit()) {
                $this->apiResult ["status"] = 1;
                $this->apiResult ["message"] = "Schools successfully added to the network.";
                $this->apiResult ["network_id"] = $_POST ['network_id'];
            }
        } else
            $this->apiResult ["message"] = "Zone does not exists\n";
    }

    //@Purpose:add school for a cluster
    function addSchoolToProvinceAction() {
        $networkModel = new networkModel ();
        if (!in_array("create_client", $this->user ['capabilities'])) {
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        } else if (empty($_POST ['client_ids']) || !is_array($_POST ['client_ids']) || count($_POST ['client_ids']) == 0) {
            $this->apiResult ["message"] = "School id cannot be empty\n";
        } else if (empty($_POST ['province_id'])) {
            $this->apiResult ["message"] = "Province id cannot be empty\n";
        } else if ($province = $networkModel->getProvinceById($_POST ['province_id'])) {
            $clientModel = new clientModel ();
            $this->db->start_transaction();
            $success = true;
            $this->apiResult ["content"] = '';
            foreach ($_POST ['client_ids'] as $client_id) {
                $client = $clientModel->getClientById($client_id);
                if (empty($client ['client_id'])) {
                    $this->apiResult ["message"] = "School id $client_id does not exists\n";
                    $success = false;
                } else if ($client ['province_id'] > 0) {
                    $this->apiResult ["message"] = "School " . $client ['client_name'] . " is already associated with a province '" . $client ['province_name'] . "'\n";
                    $success = false;
                } else if ($clientModel->addClientToProvince($client_id, $_POST ['province_id'])) {
                    $this->apiResult ["content"] .= kpajsHelper::getEditSchoolsInnetworkProvinceRowHtml($_POST ['province_id'], $client);
                } else {
                    $this->apiResult ["message"] = "Unable to add school " . $client ['client_name'] . " to the network.";
                    $success = false;
                }
            }

            if ($success && $this->db->commit()) {
                $this->apiResult ["status"] = 1;
                $this->apiResult ["message"] = "Schools successfully added to the province.";
                $this->apiResult ["province_id"] = $_POST ['province_id'];
            }
        } else
            $this->apiResult ["message"] = "Province does not exist\n";
    }

    /*
     * @Purpose:Edit review details for schools
     */
    function editSchoolAssessmentAction() {
        $assessmentModel = new assessmentModel();
        $assessment = $assessmentModel->getSchoolAssessment($_POST ['assessment_id']);
        $diagnostic_id = $assessment['diagnostic_id'];
        $externalRevPerc = null;
        $internalRevPerc = null;
        $notificationStatus = 1;
        $rev = explode(',', $assessment['percCompletes']);
        if (count($rev) > 1) {
            $externalRevPerc = $rev[1];
            $internalRevPerc = $rev[0];
        } else
            $externalRevPerc = $internalRevPerc = $assessment['percCompletes'];
        if (!in_array("create_assessment", $this->user ['capabilities'])) {
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        } else if (empty($_POST ['assessment_id'])) {
            $this->apiResult ["message"] = "Assessment id cannot be empty.\n";
        } else if ($internalRevPerc == 0 && empty($_POST ['internal_assessor_id'])) {
            $this->apiResult ["message"] = "Internal ratings have not been submitted.\n";
        } else if (($internalRevPerc > 0 || $externalRevPerc > 0) && (!empty($_POST ['internal_assessor_id']) || !empty($_POST ['diagnostic_id']))) {
            $this->apiResult ["message"] = "Internal reviewer cannot be assigned after filling review.\n";
        } else if ($externalRevPerc > 0 && !empty($_POST ['external_assessor_id'])) {
            $this->apiResult ["message"] = "External reviewer cannot be assigned after filling external-review.\n";
        } else if (empty($_POST ['school_aqs_pref_start_date'])) {
            $this->apiResult ["message"] = "AQS Start Date cannot be empty.\n";
        } else if (empty($_POST ['school_aqs_pref_end_date'])) {
            $this->apiResult ["message"] = "AQS End Date cannot be empty.\n";
        } else if (empty($_POST ['diagnostic_lang'])) {
            $this->apiResult ["message"] = "Diagnostic language cannot be empty.\n";
        } else if (!($internalRevPerc > 0 || $externalRevPerc > 0) && empty($_POST ['diagnostic_id'])) {
            $this->apiResult ["message"] = "Diagnostic cannot be empty.\n";
        } else if (empty($_POST ['tier_id'])) {
            $this->apiResult ["message"] = "Tier cannot be empty.\n";
        } else if (empty($_POST ['award_scheme_name'])) {
            $this->apiResult ["message"] = "Award Scheme cannot be empty.\n";
        } else if (empty($_POST ['aqs_round'])) {
            $this->apiResult ["message"] = "AQS Round cannot be empty.\n";
        } else if (!empty($_POST ['external_assessor_id']) && !empty($_POST ['internal_assessor_id']) && $_POST ['internal_assessor_id'] == $_POST ['external_assessor_id']) {
            $this->apiResult ["message"] = "Internal and External reviewer cannot be same.\n";
        } else if (isset($_POST ['externalReviewTeam'] ['role']) && empty($_POST ['externalReviewTeam'] ['role']) && count($_POST ['externalReviewTeam'] ['member']) != count($_POST ['externalReviewTeam'] ['role'])) {
            $this->apiResult ["message"] = "External reviewer role cannot be empty.\n";
        } else if (isset($_POST ['externalReviewTeam'] ['member']) && empty($_POST ['externalReviewTeam'] ['member']) && count($_POST ['externalReviewTeam'] ['member']) != count($_POST ['externalReviewTeam'] ['role'])) {
            $this->apiResult ["message"] = "External reviewer member cannot be empty.\n";
        } else if (!in_array("assign_external_review_team", $this->user ['capabilities']) && (!empty($_POST ['externalReviewTeam'] ['clientId']) || !empty($_POST ['external_assessor_id']) )) {
            $this->apiResult ["message"] = "You are not authorized to update external review team.\n";
        } else if (isset($_POST ['facilitatorReviewTeam'] ['member']) && count(array_unique($_POST ['facilitatorReviewTeam'] ['member'])) < count($_POST ['facilitatorReviewTeam'] ['member'])) {
            $this->apiResult ["message"] = "Cannot assign multiple role to same facilitator.\n";
        } else if (isset($_POST ['facilitatorReviewTeam'] ['member']) && isset($_POST ['facilitator_id']) && in_array($_POST ['facilitator_id'], $_POST ['facilitatorReviewTeam'] ['member'])) {
            $this->apiResult ["message"] = "Cannot assign multiple role to same facilitator.\n";
        } else {
            if (isset($_POST['notifySett'])) {
                foreach ($_POST['notifySett'] as $data) {
                    if (count($data) == 1 && in_array(10, $data)) {
                        $this->apiResult ["message"] = "Invalid notification settings.Please change  notification settings\n";
                        $notificationStatus = 0;
                        break;
                    }
                }
            }
            if (!empty($_POST['school_aqs_pref_end_date']) && !empty($_POST['school_aqs_pref_start_date'])) {
                $eDate = explode("-", $_POST['school_aqs_pref_end_date']);
                $sDate = explode("-", $_POST['school_aqs_pref_start_date']);
                if ($eDate[2] < $sDate[2] || ($eDate[2] == $sDate[2] && $eDate[1] < $sDate[1]) || ($eDate[2] == $sDate[2] && $eDate[1] == $sDate[1] && $eDate[0] < $sDate[0]))
                    $this->apiResult ["message"] = "End date can't be less than Start date";
                else if ($notificationStatus) {
                    $externalReviewTeam = '';
                    $i = 0;
                    $externalRoleClient = array();
                    if (!empty($_POST ['externalReviewTeam'] ['clientId']) && in_array("assign_external_review_team", $this->user ['capabilities']))
                        foreach ($_POST ['externalReviewTeam'] ['clientId'] as $client) {
                            array_push($externalRoleClient, $client . '_' . $_POST ['externalReviewTeam'] ['role'] [$i]);
                            $i ++;
                        }
                    $externalReviewTeam = empty($_POST ['externalReviewTeam'] ['clientId']) ? '' : array_combine($_POST ['externalReviewTeam'] ['member'], $externalRoleClient);
                    $facilitatorCount = 0;
                    $facilitatorDataArray = array();
                    if (isset($_POST ['facilitatorReviewTeam'] ['clientId'])) {
                        foreach ($_POST ['facilitatorReviewTeam'] ['clientId'] as $client => $val) {
                            $facilitatorDataArray[$facilitatorCount]['client_id'] = $val;
                            $facilitatorDataArray[$facilitatorCount]['role_id'] = $_POST ['facilitatorReviewTeam'] ['role'][$facilitatorCount];
                            $facilitatorDataArray[$facilitatorCount]['user_id'] = $_POST ['facilitatorReviewTeam'] ['member'][$facilitatorCount];
                            $facilitatorCount++;
                        }
                    }
                    if (!empty($_POST['facilitator_client_id']) && !empty($_POST['facilitator_id'])) {

                        $facilitatorDataArray[$facilitatorCount]['client_id'] = $_POST['facilitator_client_id'];
                        $facilitatorDataArray[$facilitatorCount]['role_id'] = 1;
                        $facilitatorDataArray[$facilitatorCount]['user_id'] = $_POST['facilitator_id'];
                    }
                    $assessmentModel = new assessmentModel ();
                    $this->db->start_transaction();
                    $existing_assessor_id = explode(',', $assessment ['user_ids']); //$externalRevPerc
                    $external_assessor_id = empty($_POST ['external_assessor_id']) ? $existing_assessor_id[1] : $_POST ['external_assessor_id']; //
                    $internal_assessor_id = empty($_POST ['internal_assessor_id']) ? $existing_assessor_id[0] : $_POST ['internal_assessor_id'];
                    $diagnostic_id = empty($_POST ['diagnostic_id']) ? $diagnostic_id : $_POST ['diagnostic_id'];
                    $facilitator_id = empty($_POST ['facilitator_id']) ? '' : $_POST ['facilitator_id'];
                    $aqs_round = empty($_POST ['aqs_round']) ? '' : $_POST ['aqs_round'];
                    $aqsdata_id = $assessment['aqsdata_id'];
                    $notificationID = '';
                    $notificationsArray = array();
                    $notificationUsers = array();
                    $lang_id = '';
                    if (isset($_POST ['diagnostic_lang']) && !empty($_POST ['diagnostic_lang'])) {
                        $lang_id = $_POST ['diagnostic_lang'];
                    }
                    if (isset($_POST['notifySett'])) {
                        $notificationsArray = $_POST['notifySett'];
                    }
                    $notificationOldUsers = $assessmentModel->getReviewTeamMembers($_POST ['assessment_id']);
                    if (!in_array("assign_external_review_team", $this->user ['capabilities']) && $assessmentModel->updateSchoolAssessment($_POST ['assessment_id'], $internal_assessor_id, $external_assessor_id, $facilitator_id, $diagnostic_id, $_POST ['tier_id'], $_POST ['award_scheme_name'], $aqs_round, 0, $_POST['school_aqs_pref_start_date'], $_POST['school_aqs_pref_end_date'], $aqsdata_id, $facilitatorDataArray, $notificationID, $notificationsArray, $notificationUsers)) {
                        $this->db->commit();
                        $this->apiResult ["status"] = 1;
                        $this->apiResult ["assessment_id"] = $_POST ['assessment_id'];
                        $this->apiResult ["message"] = "Review successfully updated";
                    } elseif ($assessmentModel->updateSchoolAssessment($_POST ['assessment_id'], $internal_assessor_id, $external_assessor_id, $facilitator_id, $diagnostic_id, $_POST ['tier_id'], $_POST ['award_scheme_name'], $aqs_round, $externalReviewTeam, $_POST['school_aqs_pref_start_date'], $_POST['school_aqs_pref_end_date'], $aqsdata_id, $facilitatorDataArray, $notificationID, $notificationsArray, $notificationUsers, $lang_id, $_POST['review_criteria'])) {
                        $this->db->commit();
                        $this->apiResult ["status"] = 1;
                        $this->apiResult ["assessment_id"] = $_POST ['assessment_id'];
                        $this->apiResult ["message"] = "Review successfully updated";
                        if (!empty($externalReviewTeam))
                            $this->updateReviewNotificationSettings($_POST ['assessment_id'], $externalReviewTeam, $external_assessor_id, $notificationOldUsers);
                    } else {
                        $this->db->rollback();
                        $this->apiResult ["message"] = "Unable to create review";
                    }
                }
            }
        }
    }

    //@Purpose:update notification
    function updateReviewNotificationSettings($assessment_id, $externalReviewTeam, $external_assessor_id, $notificationOldUsers = array()) {
        $assessmentModel = new assessmentModel();
        $notificationUsers = $assessmentModel->getReviewNotificationMembers($assessment_id);
        $notificationOldUsers = array_unique(array_column($notificationOldUsers, 'user_id'));
        if (!empty($external_assessor_id))
            $externalReviewTeam[$external_assessor_id] = $external_assessor_id;

        if (!empty($notificationUsers)) {
            $notificationTeam = array();
            if (!empty($notificationOldUsers)) {
                $notificationOldUsers[] = $external_assessor_id;
                foreach ($externalReviewTeam as $key => $val) {
                    if (!in_array($key, $notificationOldUsers)) {
                        $notificationTeam[] = $key;
                    }
                }
            } else {
                $notificationTeam = array_keys($externalReviewTeam);
            }
        } else {
            $notificationTeam = array_keys($externalReviewTeam);
        }
        $notificationData = array();
        if (!empty($notificationTeam)) {
            $notifications = array_column($assessmentModel->getReviewNotifications(), 'id');
            foreach ($notificationTeam as $key => $val) {
                $notificationData[$val] = $notifications;
            }
        }
        if (!empty($notificationData)) {
            return $assessmentModel->addReviewNotificationSettings($assessment_id, $notificationData);
        }
        return false;
    }

    //@Purpose:add review notification
    function addReviewNotificationSettings($assessment_id, $externalReviewTeam = array(), $external_assessor_id, $type = 1) {
        $assessmentModel = new assessmentModel();
        if (!empty($external_assessor_id))
            $externalReviewTeam[$external_assessor_id] = $external_assessor_id;
        $notificationTeam = array();
        $notificationTeam = array_keys($externalReviewTeam);
        $notificationData = array();
        if (!empty($notificationTeam)) {
            $notifications = array_column($assessmentModel->getReviewNotifications($type), 'id');
            foreach ($notificationTeam as $key => $val) {
                $notificationData[$val] = $notifications;
            }
        }
        if (!empty($notificationData)) {
            return $assessmentModel->addReviewNotificationSettings($assessment_id, $notificationData);
        }

        return false;
    }

    //@Purpose:create zone
    function createZoneAction() {
        if (!in_array("create_network", $this->user ['capabilities'])) {
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        } else if (empty(trim($_POST ['name']))) {
            $this->apiResult ["message"] = "Zone Name cannot be empty.\n";
        } else {
            $state_id = trim($_POST ['state_id']);
            $name = trim($_POST ['name']);
            $networkModel = new networkModel ();
            $zone_exist = $networkModel->getZoneByName($name);
            if (!empty($zone_exist)) {
                $isZoneAssociateWithState = $networkModel->isZoneExistInStatusId($zone_exist['zone_id'], $state_id);
            }
            if (!empty($isZoneAssociateWithState)) {
                $this->apiResult ["message"] = "Zone Name already exists\n";
            }
            if (empty($zone_exist) || empty($isZoneAssociateWithState)) {
                if (empty($isZoneAssociateWithState) && $zone_exist) {
                    $nid = $zone_exist['zone_id'];
                    $networkModel->addZoneToState($nid, $state_id);
                    $this->apiResult ["status"] = 1;
                    $this->apiResult ["assessment_id"] = $nid;
                    $this->apiResult ["message"] = "Zone successfully added";
                    $this->db->commit();
                } else if (empty($zone_exist)) {
                    $pid = null;
                    $nid = null;
                    $this->db->start_transaction();
                    if (($nid = $networkModel->createZone($name)) && $networkModel->addZoneToState($nid, $state_id)) {
                        if (OFFLINE_STATUS == TRUE) {
                            $uniqueID = $this->db->createUniqueID('addZone');
                            // start---> call function for saving add school client data on 04-03-2016 by Mohit Kumar
                            $action_json = json_encode(array(
                                'zone_name' => $name
                            ));
                            $this->db->saveHistoryData($nid, 'd_zone', $uniqueID, 'addZone', $nid, $name, $action_json, 0, date('Y-m-d H:i:s'));
                            // end---> call function for saving add school client data on 04-03-2016 by Mohit Kumar
                        }
                        $this->apiResult ["status"] = 1;
                        $this->apiResult ["assessment_id"] = $nid;
                        $this->apiResult ["message"] = "Zone successfully added";
                        $this->db->commit();
                    } else {
                        $this->apiResult ["message"] = "Unable to add network\n";
                        $this->db->rollback();
                    }
                } else {
                    $this->apiResult ["message"] = "Unable to add network\n";
                    $this->db->rollback();
                }
            }
        }
    }

    //@Purpose:create state 
    function createStateAction() {
        if (!in_array("create_network", $this->user ['capabilities'])) {
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        } else if (empty(trim($_POST ['name']))) {
            $this->apiResult ["message"] = "State Name cannot be empty.\n";
        } else {
            $name = trim($_POST ['name']);
            $networkModel = new networkModel ();
            if (!empty($networkModel->getStateByClientName($name)))
                $this->apiResult ["message"] = "State Name already exists\n";
            else {
                $pid = null;
                $nid = null;
                $this->db->start_transaction();
                if ($nid = $networkModel->createState($name)) {
                    if (OFFLINE_STATUS == TRUE) {
                        $uniqueID = $this->db->createUniqueID('addState');
                        // start---> call function for saving add school client data on 04-03-2016 by Mohit Kumar
                        $action_json = json_encode(array(
                            'state_name' => $name
                        ));
                        $this->db->saveHistoryData($nid, 'd_state', $uniqueID, 'addState', $nid, $name, $action_json, 0, date('Y-m-d H:i:s'));
                        // end---> call function for saving add school client data on 04-03-2016 by Mohit Kumar						
                    }
                    $this->apiResult ["status"] = 1;
                    $this->apiResult ["assessment_id"] = $nid;
                    $this->apiResult ["message"] = "State successfully added";
                    $this->db->commit();
                } else {
                    $this->apiResult ["message"] = "Unable to add State\n";
                    $this->db->rollback();
                }
            }
        }
    }

    //@Purpose:create network 
    function createNetworkAction() {
        if (!in_array("create_network", $this->user ['capabilities'])) {
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        } else if (empty(trim($_POST ['name']))) {
            $this->apiResult ["message"] = "Block Name cannot be empty.\n";
        } else {
            $state_id = trim($_POST ['state_id']);
            $zone_id = trim($_POST ['zone_id']);
            $name = trim($_POST ['name']);
            $networkModel = new networkModel ();
            $network_exist = $networkModel->getBlockByName($name);
            if (!empty($network_exist)) {
                $isBlockAssociateWithZone = $networkModel->isBlockExistInZoneId($network_exist['network_id'], $zone_id, $state_id);
            }
            if (!empty($isBlockAssociateWithZone)) {
                $this->apiResult ["message"] = "Block Name already exists\n";
            }
            if (empty($network_exist) || empty($isBlockAssociateWithZone)) {
                if (empty($isBlockAssociateWithZone) && $network_exist) {
                    $nid = $network_exist['network_id'];
                    $networkModel->addBlockToZone($nid, $zone_id, $state_id);
                    $this->apiResult ["status"] = 1;
                    $this->apiResult ["assessment_id"] = $nid;
                    $this->apiResult ["message"] = "Block successfully added";
                    $this->db->commit();
                } else if (empty($network_exist)) {
                    $pid = null;
                    $nid = null;
                    $this->db->start_transaction();
                    if (($nid = $networkModel->createNetwork($name)) && $networkModel->addBlockToZone($nid, $zone_id, $state_id)) {
                        if (OFFLINE_STATUS == TRUE) {
                            $uniqueID = $this->db->createUniqueID('addNetwork');
                            // start---> call function for saving add school client data on 04-03-2016 by Mohit Kumar
                            $action_json = json_encode(array(
                                'block_name' => $name
                            ));
                            $this->db->saveHistoryData($nid, 'd_network', $uniqueID, 'addNetwork', $nid, $name, $action_json, 0, date('Y-m-d H:i:s'));
                            // end---> call function for saving add school client data on 04-03-2016 by Mohit Kumar						
                        }
                        $this->apiResult ["status"] = 1;
                        $this->apiResult ["assessment_id"] = $nid;
                        $this->apiResult ["message"] = "Block successfully added";
                        $this->db->commit();
                    } else {
                        $this->apiResult ["message"] = "Unable to add Block\n";
                        $this->db->rollback();
                    }
                } else {
                    $this->apiResult ["message"] = "Unable to add Block\n";
                    $this->db->rollback();
                }
            }
        }
    }

    //@Purpose:create network 
    function createBlockAction() {
        if (!in_array("create_network", $this->user ['capabilities'])) {
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        } else if (empty(trim($_POST ['name']))) {

            $this->apiResult ["message"] = "Block Name cannot be empty.\n";
        } else {
            $state_id = trim($_POST ['state_id']);
            $zone_id = trim($_POST ['zone_id']);
            $name = trim($_POST ['name']);
            $networkModel = new networkModel ();
            $network_exist = $networkModel->getBlockByName($name);
            if (!empty($network_exist)) {
                $isBlockAssociateWithZone = $networkModel->isBlockExistInZoneId($network_exist['network_id'], $zone_id, $state_id);
            }
            if (!empty($isBlockAssociateWithZone)) {
                $this->apiResult ["message"] = "Block Name already exists\n";
            }
            if (empty($network_exist) || empty($isBlockAssociateWithZone)) {
                if (empty($isBlockAssociateWithZone) && $network_exist) {
                    $nid = $network_exist['network_id'];
                    $networkModel->addBlockToZone($nid, $zone_id, $state_id);
                    $this->apiResult ["status"] = 1;
                    $this->apiResult ["assessment_id"] = $nid;
                    $this->apiResult ["message"] = "Block successfully added";
                    $this->db->commit();
                } else if (empty($network_exist)) {
                    $pid = null;
                    $nid = null;
                    $this->db->start_transaction();
                    if (($nid = $networkModel->createNetwork($name)) && $networkModel->addBlockToZone($nid, $zone_id, $state_id)) {
                        if (OFFLINE_STATUS == TRUE) {
                            $uniqueID = $this->db->createUniqueID('addNetwork');
                            // start---> call function for saving add school client data on 04-03-2016 by Mohit Kumar
                            $action_json = json_encode(array(
                                'block_name' => $name
                            ));
                            $this->db->saveHistoryData($nid, 'd_network', $uniqueID, 'addNetwork', $nid, $name, $action_json, 0, date('Y-m-d H:i:s'));
                            // end---> call function for saving add school client data on 04-03-2016 by Mohit Kumar			
                        }
                        $this->apiResult ["status"] = 1;
                        $this->apiResult ["assessment_id"] = $nid;
                        $this->apiResult ["message"] = "Block successfully added";
                        $this->db->commit();
                    } else {
                        $this->apiResult ["message"] = "Unable to add Block\n";
                        $this->db->rollback();
                    }
                } else {
                    $this->apiResult ["message"] = "Unable to add Block\n";
                    $this->db->rollback();
                }
            }
        }
    }

    //@Purpose:create cluster field
    function addProvinceFieldAction() {
        if (!in_array("create_network", $this->user ['capabilities']))
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        else {
            $networkModel = new networkModel();
            $html = $networkModel->getProvinceField();
            $this->apiResult ["content"] = $html;
            $this->apiResult ["status"] = 1;
        }
    }

    //@Purpose:update network 
    function updateNetworkAction() {
        if (!in_array("create_network", $this->user ['capabilities'])) {
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        } else if (empty($_POST ['id'])) {
            $this->apiResult ["message"] = "Block ID missing.\n";
        } else if (empty(trim($_POST ['name']))) {
            $this->apiResult ["message"] = "Block Name cannot be empty.\n";
        } else {
            $networkModel = new networkModel ();
            $name = trim($_POST ['name']);
            $network_id = trim($_POST ['id']);
            $network = $networkModel->getNetworkById($network_id);
            if (empty($network)) {
                $this->apiResult ["message"] = "Block does not exist.\n";
            } else if ($networkModel->getNetworkByClientName($name, $network_id)) {
                $this->apiResult ["message"] = "Block Name already exists.\n";
            } else {
                if ($networkModel->updateNetwork($network_id, $name)) {
                    if (OFFLINE_STATUS == TRUE) {
                        $uniqueID = $this->db->createUniqueID('updateNetwork');
                        // start---> call function for saving add school client data on 04-03-2016 by Mohit Kumar
                        $action_json = json_encode(array(
                            'network_name' => $name
                        ));
                        $this->db->saveHistoryData($network_id, 'd_network', $uniqueID, 'updateNetwork', $network_id, $name, $action_json, 0, date('Y-m-d H:i:s'));
                        // end---> call function for saving add school client data on 04-03-2016 by Mohit Kumar
                    }
                    $this->apiResult ["status"] = 1;
                    $this->apiResult ["message"] = "Block successfully updated.";
                } else {
                    $this->apiResult ["message"] = "Unable to update block.\n";
                }
            }
        }
    }

    /*
     * @Purpose:update state
     */
    function updateStateAction() {
        if (!in_array("create_network", $this->user ['capabilities'])) {
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        } else if (empty($_POST ['id'])) {
            $this->apiResult ["message"] = "State ID missing.\n";
        } else if (empty(trim($_POST ['name']))) {
            $this->apiResult ["message"] = "State Name cannot be empty.\n";
        } else {
            $networkModel = new networkModel ();
            $name = trim($_POST ['name']);
            $network_id = trim($_POST ['id']);
            $network = $networkModel->getStateById($network_id);
            if (empty($network)) {
                $this->apiResult ["message"] = "State does not exist.\n";
            } else if ($networkModel->getStateByClientName($name, $network_id)) {
                $this->apiResult ["message"] = "State Name already exists.\n";
            } else {
                if ($networkModel->updateState($network_id, $name)) {
                    $this->apiResult ["status"] = 1;
                    $this->apiResult ["message"] = "State successfully updated.";
                } else {
                    $this->apiResult ["message"] = "Unable to update state.\n";
                }
            }
        }
    }

    //@Purpose:update zone 
    function updateZoneAction() {
        if (!in_array("create_network", $this->user ['capabilities'])) {
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        } else if (empty($_POST ['id'])) {
            $this->apiResult ["message"] = "Zone ID missing.\n";
        } else if (empty(trim($_POST ['name']))) {
            $this->apiResult ["message"] = "Zone Name cannot be empty.\n";
        } else {
            $networkModel = new networkModel ();
            $name = trim($_POST ['name']);
            $network_id = trim($_POST ['id']);
            $network = $networkModel->getZoneById($network_id);
            if (empty($network)) {
                $this->apiResult ["message"] = "Zone does not exist.\n";
            } else if ($networkModel->getStateByClientName($name, $network_id)) {
                $this->apiResult ["message"] = "Zone Name already exists.\n";
            } else {
                if ($networkModel->updateZone($network_id, $name)) {
                    $this->apiResult ["status"] = 1;
                    $this->apiResult ["message"] = "Zone successfully updated.";
                } else {
                    $this->apiResult ["message"] = "Unable to update zone.\n";
                }
            }
        }
    }

    //@Purpose:update cluster 
    function updateProvinceAction() {
        if (!in_array("create_network", $this->user ['capabilities'])) {
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        } else if (empty($_POST ['id'])) {
            $this->apiResult ["message"] = "Hub ID missing.\n";
        } else if (empty(trim($_POST ['name']))) {
            $this->apiResult ["message"] = "Hub Name cannot be empty.\n";
        } else {
            $networkModel = new networkModel ();
            $name = trim($_POST ['name']);
            $province_id = trim($_POST ['id']);
            $province = $networkModel->getProvinceById($province_id);
            if (empty($province)) {
                $this->apiResult ["message"] = "Hub does not exist.\n";
            } else if ($networkModel->getProvinceByName($name, $province_id)) {
                $this->apiResult ["message"] = "Hub Name already exists.\n";
            } else {
                if ($networkModel->updateProvince($province_id, $name)) {
                    if (OFFLINE_STATUS == TRUE) {
                        $uniqueID = $this->db->createUniqueID('updateProvince');
                        // start---> call function for saving add school client data on 04-03-2016 by Mohit Kumar
                        $action_json = json_encode(array(
                            'province_name' => $name
                        ));
                        $this->db->saveHistoryData($network_id, 'd_province', $uniqueID, 'updateProvince', $province_id, $name, $action_json, 0, date('Y-m-d H:i:s'));
                        // end---> call function for saving add school client data on 04-03-2016 by Mohit Kumar
                    }
                    $this->apiResult ["status"] = 1;
                    $this->apiResult ["message"] = "Hub successfully updated.";
                } else {
                    $this->apiResult ["message"] = "Unable to update network.\n";
                }
            }
        }
    }

    //@Purpose:delete school from block page
    function removeClientFromNetworkAction() {
        if (!in_array("create_network", $this->user ['capabilities'])) {
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        } else if (empty($_POST ['network_id'])) {
            $this->apiResult ["message"] = "Zone ID missing\n";
        }
        if (empty($_POST ['client_id'])) {
            $this->apiResult ["message"] = "School ID missing\n";
        } else {
            $clientModel = new clientModel ();
            $network_id = $_POST ['network_id'];
            $client_id = $_POST ['client_id'];
            $this->db->start_transaction();
            if ($clientModel->removeClientFromNetworkProvince($client_id) && $clientModel->removeClientFromNetwork($client_id, $network_id) && $this->db->commit()) {

                if (OFFLINE_STATUS == TRUE) {
                    $clientUniqueID = $this->db->createUniqueID('removeNetworkSchool');
                    // start---> call function for saving remove school network data on 08-03-2016 by Mohit Kumar
                    $action_network_json = json_encode(array(
                        'client_id' => $client_id,
                        'network_id' => $network_id
                    ));
                    $this->db->saveHistoryData($client_id, 'h_client_network', $clientUniqueID, 'removeNetworkSchool', $client_id, $network_id, $action_network_json, 0, date('Y-m-d H:i:s'));
                    // end---> call function for saving remove school network data on 08-03-2016 by Mohit Kumar
                }

                if (OFFLINE_STATUS == TRUE) {
                    $clientUniqueID = $this->db->createUniqueID('removeProvinceSchool');
                    // start---> call function for saving remove school network data on 08-03-2016 by Mohit Kumar
                    $action_network_json = json_encode(array(
                        'client_id' => $client_id,
                        'client_id' => $client_id
                    ));
                    $this->db->saveHistoryData($client_id, 'h_client_province', $clientUniqueID, 'removeProvinceSchool', $client_id, $client_id, $action_network_json, 0, date('Y-m-d H:i:s'));
                    // end---> call function for saving remove school network data on 08-03-2016 by Mohit Kumar
                }
                $this->apiResult ["status"] = 1;
                $this->apiResult ["message"] = "School successfully removed from network.";
            } else {
                $this->db->rollback();
                $this->apiResult ["message"] = "Unable to remove school from network\n";
            }
        }
    }

    //@Purpose:delete school from cluster page
    function removeClientFromProvinceAction() {
        if (!in_array("create_network", $this->user ['capabilities'])) {
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        } else if (empty($_POST ['province_id'])) {
            $this->apiResult ["message"] = "Hub ID missing\n";
        }
        if (empty($_POST ['client_id'])) {
            $this->apiResult ["message"] = "School ID missing\n";
        } else {
            $clientModel = new clientModel ();
            $province_id = $_POST ['province_id'];
            $client_id = $_POST ['client_id'];
            if ($clientModel->removeClientFromProvince($client_id, $province_id)) {
                if (OFFLINE_STATUS == TRUE) {
                    $clientUniqueID = $this->db->createUniqueID('removeProvinceSchool');
                    // start---> call function for saving remove school network data on 08-03-2016 by Mohit Kumar
                    $action_network_json = json_encode(array(
                        'client_id' => $client_id,
                        'province_id' => $province_id
                    ));
                    $this->db->saveHistoryData($client_id, 'h_client_network', $clientUniqueID, 'removeProvinceSchool', $client_id, $province_id, $action_network_json, 0, date('Y-m-d H:i:s'));
                    // end---> call function for saving remove school network data on 08-03-2016 by Mohit Kumar
                }
                $this->apiResult ["status"] = 1;
                $this->apiResult ["message"] = "School successfully removed from hub.";
            } else {
                $this->apiResult ["message"] = "Unable to remove school from hub\n";
            }
        }
    }

    /*
     * @Purpose:Function to get user roles
     */
    function checkUserRoleAction() {
        $networkModel = new networkModel ();
        if (isset($_POST['usertype_id'])) {
            if ($_POST ['usertype_id'] == 1) {
                $_POST['roles'][0] = 10;
            } else if ($_POST ['usertype_id'] == 2) {
                $_POST['roles'][0] = 11;
            } else if ($_POST ['usertype_id'] == 3) {
                $_POST['roles'][0] = 7;
            } else if ($_POST ['usertype_id'] == 4) {
                $_POST['roles'][0] = 12;
            }
        }
        $user_profile = 1;
        if (isset($_POST['edit_request_from']) && $_POST['edit_request_from'] == 'user_profile') {
            $_POST ['name'] = $_POST ['first_name'];
        }
        if (isset($_POST['user_profile']) && $_POST['user_profile'] == 1) {
            $user_profile = 0;
        }
        if (empty($_POST ['client_id']) && $_POST ['usertype_id'] == 5) {
            $this->apiResult ["message"] = "School cannot be empty\n";
        } else if (isset($_POST ['usertype_id']) && $_POST ['usertype_id'] == 1 && empty(isset($_POST ['state_id']))) {
            $this->apiResult ["message"] = "Please select a state\n";
        } else if ($user_profile && empty($_POST ['name'])) {
            $this->apiResult ["message"] = "Name cannot be empty\n";
        } else if (empty($_POST ['roles']) && in_array("manage_all_users", $this->user ['capabilities'])) {
            $this->apiResult ["message"] = "User Role cannot be empty\n";
        } else {
            $client_id = $_POST ['client_id'];
            $client_id_old = isset($_POST ['client_id_old']) ? $_POST ['client_id_old'] : $_POST ['client_id'];
            $name = trim($_POST ['name']);
            $user_id = empty($_POST ['id']) ? 0 : $_POST ['id'];
            if ($user_profile && preg_match('/^[A-Za-z0-9\s,.\'@#$%&*+_-]+$/', $name) != 1) {
                $this->apiResult ["message"] = "Invalid Characters are not allowed in Name.\n";
            } else if (isset($_POST ['roles'])) {
                $roles = $_POST ['roles'];
                $rolePrincipal = 6;
                // check if the user with school principal role already exists
                $allRoles = implode(",", $roles);
                if (count($roles) == 1 && in_array(8, $roles)) {
                    $usersRole8 = $this->userModel->getUsersForClientByRole('', 8, $user_id);
                    $this->apiResult ["message"] = "The tap admin already exists.\n";
                    $this->apiResult ["status"] = 1;
                    $this->apiResult ["duplicate"] = $usersRole8 ['users'];
                    return;
                } else {
                    if (in_array($rolePrincipal, $roles)) {
                        $usersRole6 = $this->userModel->getUsersForClientByRole($client_id, $rolePrincipal, $user_id);
                        if (!empty($usersRole6)) {
                            if ($client_id != $client_id_old) {
                                $this->apiResult ["message"] = "The principal already exists in the new school.\nPlease note that it will create a new user and all the reviews (if any) of current principal will get transfered to new user.\n";
                            } else
                                $this->apiResult ["message"] = "The principal already exists.\n";
                            $this->apiResult ["status"] = 1;
                            $this->apiResult ["duplicate"] = $usersRole6 ['users'];
                            return;
                        }
                    }
                }
                $this->apiResult ["message"] = "success.\n";
                $this->apiResult ["status"] = 1;
                $this->apiResult ["duplicate"] = 0;
            }
            else {
                $this->apiResult ["message"] = "success.\n";
                $this->apiResult ["status"] = 1;
                $this->apiResult ["duplicate"] = 0;
            }
        }
    }

    /*
     * @Purpose: Function to delete user role 
     */
    function deleteUserRoleAction() {
        if (empty($_POST ['client_id'])) {
            $this->apiResult ["message"] = "School cannot be empty\n";
        } elseif (empty($_POST ['users_id'])) {
            $this->apiResult ["message"] = "UsersId cannot be empty\n";
        } elseif (empty($_POST ['role_id'])) {
            $this->apiResult ["message"] = "RoleId cannot be empty\n";
        } else {
            $userModel = new userModel ();
            $roleId = $_POST ['role_id'];
            $usersId = $_POST ['users_id'];
            $this->db->start_transaction();
            $usersId = explode(",", $usersId);
            $queryFailed = 0;
            foreach ($usersId as $user => $id) {

                if (!$userModel->deleteUserRole($id, $roleId))
                    $queryFailed = 10;
            }

            if (!$queryFailed && $this->db->commit()) {
                $this->apiResult ["message"] = "delete user action.\n";
                $this->apiResult ["status"] = 1;
                return;
            }
            $this->apiResult ["message"] = "Error in deleting user role\n";
            $this->apiResult ["status"] = 0;
        }
    }

    /*
     * @Purpose: Function to create new user
     */
    function createUserAction() {
        if(isset($_POST ['usertype_id'])){
        if ($_POST ['usertype_id'] == 1) {
            $sql = "select client_id from d_client where client_name_user ='State'";
            $res = $this->db->get_results($sql);
            $_POST['client_id'] = $res[0]['client_id'];
            $_POST ['roles'] [0] = 10;
        } else if ($_POST ['usertype_id'] == 2) {
            $sql = "select client_id from d_client where client_name_user ='Zone'";
            $res = $this->db->get_results($sql);
            $_POST['client_id'] = $res[0]['client_id'];
            $_POST ['roles'] [0] = 11;
        } else if ($_POST ['usertype_id'] == 3) {
            $sql = "select client_id from d_client where client_name_user ='Block'";
            $res = $this->db->get_results($sql);
            $_POST['client_id'] = $res[0]['client_id'];
            $_POST ['roles'] [0] = 7;
        } else if ($_POST ['usertype_id'] == 4) {
            $sql = "select client_id from d_client where client_name_user ='Cluster'";
            $res = $this->db->get_results($sql);
            $_POST['client_id'] = $res[0]['client_id'];
            $_POST ['roles'] [0] = 12;
        } else if ($_POST ['client_id'] == '') {
            $_POST['client_id'] = NULL;
        }
    }
        $check = function ($input, $allowed) {
            foreach ($input as $val) {
                if (!in_array($val, $allowed))
                    return 0;
            }
            return 1;
        };
        $networkModel = new networkModel ();
        if (empty($_POST ['client_id']) && $_POST ['usertype_id'] == 5) {
            $this->apiResult ["message"] = "School/College cannot be empty\n";
        } else if (!in_array("manage_all_users", $this->user ['capabilities']) &&
                ($_POST ['client_id'] != $this->user ['client_id'] || !in_array("manage_own_users", $this->user ['capabilities'])) &&
                (empty($this->user ['network_id']) || $this->db->get_array_value("network_id", $networkModel->getNetworkByClientId($_POST ['client_id'])) != $this->user ['network_id'] || !in_array("manage_own_network_users", $this->user ['capabilities']))) {
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        } else if (empty($_POST ['name'])) {
            $this->apiResult ["message"] = "Name cannot be empty\n";
        } else if (empty($_POST ['email'])) {
            $this->apiResult ["message"] = "Email cannot be empty\n";
        } else if (empty($_POST ['password'])) {
            $this->apiResult ["message"] = "Password cannot be empty\n";
        } else if (strlen($_POST ['password']) < 6) {
            $this->apiResult ["message"] = "Password too short. Minimum 6 characters required.\n";
        } else if (empty($_POST ['roles']) && in_array("manage_all_users", $this->user ['capabilities'])) {
            $this->apiResult ["message"] = "User Role cannot be empty\n";
        } else if (in_array("manage_own_users", $this->user ['capabilities']) && in_array(6, $this->user ['role_ids']) && (count($_POST ['roles']) > 2 || !$check($_POST ['roles'], array(
                    3,
                    5
                )))) { // principal is allowed to add internal reviewer or school admin only
            $this->apiResult ["message"] = "You are not authorised to perform this task\n";
        } else if (in_array("manage_own_users", $this->user ['capabilities']) && in_array(7, $this->user ['role_ids']) && (count($_POST ['roles']) > 3 || !$check($_POST ['roles'], array(
                    3,
                    5,
                    6
                )))) { // network admin is allowed to add  school principal or school admin only
            $this->apiResult ["message"] = "You are not authorised to perform this task\n";
        } else {
            $client_id = $_POST ['client_id'];
            $name = trim($_POST ['name']);
            $email = strtolower(trim($_POST ['email']));
            $moodle_user = isset($_POST['moodle_user']) ? $_POST['moodle_user'] : 0;
            if ($_POST ['roles'] [0] == 8 && !empty($_POST ['roles'])) {
                $roleId = $_POST ['roles'] [0];
            } else {
                $roleId = empty($_POST ['role_id']) ? 0 : $_POST ['role_id'];
            }
            $usersId = empty($_POST ['users_id']) ? 0 : $_POST ['users_id'];
            $usersId = explode(",", $usersId);
            $clientModel = new clientModel();
            $clientDetails = $clientModel->getClientById($client_id);
            if (preg_match('/^[A-Za-z0-9\s,.\'@#$%&*+_-]+$/', $name) != 1) {
                $this->apiResult ["message"] = "Invalid Characters are not allowed in Name.\n";
            } else if (preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,5})$^", $email) != 1) {
                $this->apiResult ["message"] = "Invalid email.\n";
            } else if ($this->userModel->getUserByEmail($email)) {
                $this->apiResult ["message"] = "Email already exists.\n";
            } else if (in_array(7, $_POST ['roles']) && empty($clientDetails['network_id']) && empty($_POST ['usertype_id'])) {
                $this->apiResult ["message"] = "Zone admin role is allowed only for schools/colleges which are under some network.\n";
            } else {
                $currentUser = isset($this->user ['user_id']) ? $this->user['user_id'] : NULL;
                $this->db->start_transaction();
                $queryFailed = 0;
                if (OFFLINE_STATUS == TRUE) {
                    $userUniqueID = $this->db->createUniqueID('addUser');
                }
                foreach ($usersId as $user => $id) {
                    if (!$this->userModel->deleteUserRole($id, $roleId)) {
                        $queryFailed = 10;
                    } else {
                        if (OFFLINE_STATUS == TRUE && $id != 0 && $roleId != 0) {
                            $action_user_deleted_role_json = json_encode(array(
                                'user_id' => $id,
                                'role_id' => $roleId
                            ));
                            $this->db->saveHistoryData($id, 'h_user_user_role', $userUniqueID, 'removeUserRole', $id, $roleId, $action_user_deleted_role_json, 0, date('Y-m-d H:i:s'));
                            $queryFailed = 0;
                        }
                    }
                }
                $roles = 3;
                if ((in_array("manage_all_users", $this->user ['capabilities'])) || (in_array("manage_own_users", $this->user ['capabilities']) && in_array(6, $this->user ['role_ids']))) {
                    $roles = $_POST ['roles'];
                } else if ((in_array("manage_all_users", $this->user ['capabilities'])) || (in_array("manage_own_users", $this->user ['capabilities']) && in_array(7, $this->user ['role_ids']))) {
                    $roles = $_POST ['roles'];
                } else
                    $roles = array(
                        3
                    );
                if ($_POST ['client_id'] == '') {
                    $client_id = NULL;
                }
                $uid = $this->userModel->createUser($email, $_POST ['password'], $name, $client_id, 0, date("Y-m-d H:i:s"), $currentUser, $moodle_user);
                if(isset($_POST ['usertype_id'])){
                if ($_POST ['state_id'] != '' && $_POST ['usertype_id'] == 1) {
                    $admin_levels_id = $this->userModel->createAdminLevels($uid, $_POST ['usertype_id'], $_POST ['state_id']);
                } else if (!empty($_POST ['zone_id']) && $_POST ['usertype_id'] == 2) {
                    foreach ($_POST ['zone_id'] as $zone_ids) {//echo '<pre>';print_r($zone_ids);
                        $admin_levels_id = $this->userModel->createAdminLevels($uid, $_POST ['usertype_id'], $_POST ['state_id'], $zone_ids);
                    }
                } else if (!empty($_POST ['block_id']) && $_POST ['usertype_id'] == 3) {
                    foreach ($_POST ['block_id'] as $block_ids) {
                        $sql = "select state_id,zone_id from h_network_zone_state where network_id = $block_ids";
                        $res = $this->db->get_results($sql);
                        $stateID = $res[0]['state_id'];
                        $zoneID = $res[0]['zone_id'];
                        $admin_levels_id = $this->userModel->createAdminLevels($uid, $_POST ['usertype_id'], $stateID, $zoneID, $block_ids);
                    }
                } else if (!empty($_POST ['cluster_id']) && $_POST ['usertype_id'] == 4) {
                    foreach ($_POST ['cluster_id'] as $cluster_ids) {
                        $sql = "select state_id,zone_id,block_id from h_cluster_block_zone_state where cluster_id = $cluster_ids";
                        $res = $this->db->get_results($sql);
                        $stateID = $res[0]['state_id'];
                        $zoneID = $res[0]['zone_id'];
                        $blockID = $res[0]['block_id'];
                        $admin_levels_id = $this->userModel->createAdminLevels($uid, $_POST ['usertype_id'], $stateID, $zoneID, $blockID, $cluster_ids);
                    }
                }
            }
                if (OFFLINE_STATUS == TRUE) {
                    $userUniqueID = $this->db->createUniqueID('addUser');
                    // start--> call function for save history for new insert user in history table on 03-03-2016 by Mohit Kumar
                    $action_user_json = json_encode(array(
                        'email' => $email,
                        'name' => $name,
                        'password' => $_POST ['password'],
                        'client_id' => $_POST ['client_id']
                    ));
                    $this->db->saveHistoryData($uid, 'd_user', $userUniqueID, 'addUser', $uid, $email, $action_user_json, 0, date('Y-m-d H:i:s'));
                    // end--> call function for save history for new insert user in history table on 03-03-2016 by Mohit Kumar
                }
                $rolesAdded = true;
                $alert = 1;
                foreach ($roles as $role) {
                    if (!$this->userModel->addUserRole($uid, $role)) {
                        $rolesAdded = false;
                        break;
                    } else {
                        if (OFFLINE_STATUS == TRUE) {
                            $user_role_id = $this->db->get_last_insert_id();
                            // start--> call function for save history for new insert user in history table on 03-03-2016 by Mohit Kumar
                            $action_user_role_json = json_encode(array(
                                'user_id' => $uid,
                                'role_id' => $role
                            ));
                            $this->db->saveHistoryData($user_role_id, 'h_user_user_role', $userUniqueID, 'addUserRole', $uid, $role, $action_user_role_json, 0, date('Y-m-d H:i:s'));
                            // end--> call function for save history for new insert user in history table on 03-03-2016 by Mohit Kumar
                            $rolesAdded = true;
                        }
                    }

                    if ($role == 4 && $this->db->addAlerts('d_user', $uid, $name, $email, 'CREATE_EXTERNAL_ASSESSOR')) {
                        $alertid = $this->db->get_last_insert_id();
                        if (OFFLINE_STATUS == TRUE) {
                            $action_alert_json = json_encode(array(
                                'table_name' => 'd_user',
                                'content_id' => $uid,
                                'content_title' => $name,
                                'content_description' => $email,
                                "type" => 'CREATE_EXTERNAL_ASSESSOR'
                            ));
                            $this->db->saveHistoryData($alertid, 'd_alerts', $userUniqueID, 'addUserAlert', $uid, $email, $action_alert_json, 0, date('Y-m-d H:i:s'));
                        }

                        $this->db->insert('h_tap_user_assessment', array(
                            'tap_program_status' => 1,
                            'user_id' => $uid
                        ));
                        if (OFFLINE_STATUS == TRUE) {
                            $tapuserid = $this->db->get_last_insert_id();
                            $action_tab_user_json = json_encode(array(
                                'table_name' => 'h_tap_user_assessment',
                                'tap_program_status' => 1,
                                'user_id' => $uid
                            ));
                            $this->db->saveHistoryData($tapuserid, 'h_tap_user_assessment', $userUniqueID, 'addUserTabAssessorAlert', $uid, $uid, $action_tab_user_json, 0, date('Y-m-d H:i:s'));
                        }

                        $alert = 1;
                    }
                }
                if ($uid > 0 && $rolesAdded && !$queryFailed && $this->db->commit() && $alert) {
                    $this->apiResult ["status"] = 1;
                    $this->apiResult ["message"] = "User successfully added";
                    $nameArray = explode(" ", $name);
                    $firstname = array_shift($nameArray);
                    $last_name = is_array($nameArray) ? implode(" ", $nameArray) : '';
                    $student_moodle_data = $this->prepare_student_data($email, $_POST ['password'], $firstname, $last_name, $email);
                    $moodle_user = isset($_POST['moodle_user']) ? $_POST['moodle_user'] : 0;
                    $prepare_check_data = $this->prepare_check_data($email);
                    $update_data = array();
                    if ($moodle_user == 1 && !add_update_user_moodle($student_moodle_data, $prepare_check_data, $update_data)) {
                        $this->apiResult ["message"] = "User successfully added but not added/updated in Moodle";
                    }
                } else {
                    $this->db->rollback();
                    $this->apiResult ["message"] = "Error occurred, please check the error logs.\n";
                }
            }
        }
    }

    function prepare_student_data($username, $password, $firstname, $lastname, $email) {
        $student_data['users'][0]['username'] = $username;
        if (!empty($password)) {
            $student_data['users'][0]['password'] = $password;
        }
        $student_data['users'][0]['firstname'] = $firstname;
        $student_data['users'][0]['lastname'] = $lastname;
        $student_data['users'][0]['email'] = $email;
        return $student_data;
    }

    function prepare_check_data($email) {
        $username = array('key' => 'email', 'value' => $email);
        $params = array('criteria' => array($username));
        return $params;
    }

    /*
     * @Purpose:function to update user
     */
    function updateUserAction() {
        if (isset($_POST['usertype_id']) && $_POST ['usertype_id'] == 1) {
            $sql = "select client_id from d_client where client_name_user ='State'";
            $res = $this->db->get_results($sql);
            $_POST['client_id'] = $res[0]['client_id'];
            unset($_POST ['roles']);
            $_POST ['roles'] [0] = 10;
        } else if (isset($_POST['usertype_id']) && $_POST ['usertype_id'] == 2) {
            $sql = "select client_id from d_client where client_name_user ='Zone'";
            $res = $this->db->get_results($sql);
            $_POST['client_id'] = $res[0]['client_id'];
            unset($_POST ['roles']);
            $_POST ['roles'] = array();
            $_POST ['roles'] [0] = 11;
        } else if (isset($_POST['usertype_id']) && $_POST ['usertype_id'] == 3) {
            $sql = "select client_id from d_client where client_name_user ='Block'";
            $res = $this->db->get_results($sql);
            $_POST['client_id'] = $res[0]['client_id'];
            unset($_POST ['roles']);
            $_POST ['roles'] [0] = 7;
        } else if (isset($_POST['usertype_id']) && $_POST ['usertype_id'] == 4) {
            $sql = "select client_id from d_client where client_name_user ='Cluster'";
            $res = $this->db->get_results($sql);
            $_POST['client_id'] = $res[0]['client_id'];
            unset($_POST ['roles']);
            $_POST ['roles'] [0] = 12;
        } else if ($_POST ['client_id'] == '') {
            $_POST['client_id'] = NULL;
        }
        $email_update = isset($_POST ['email']) ? strtolower(trim($_POST ['email'])) : '';
        $principal_user_row_new = $this->userModel->getPrincipal($_POST['client_id']);
        $principal_user_id_new = empty($principal_user_row_new) ? 0 : $principal_user_row_new['user_id'];
        $moodle_user = isset($_POST['moodle_user']) ? $_POST['moodle_user'] : 0;
        $user_profile = 1;
        if (isset($_POST['user_profile']) && $_POST['user_profile'] == 1) {
            $user_profile = 0;
        }
        if (isset($_POST['edit_request_from'])) {
            $_POST ['name'] = $_POST ['first_name'];
        }
        if ($user_profile && empty($_POST ['name'])) {
            $this->apiResult ["message"] = "Name cannot be empty\n";
        } else if (empty($_POST ['id'])) {
            $this->apiResult ["message"] = "User ID missing\n";
        } else if (!empty($_POST ['password']) && strlen($_POST ['password']) < 6) {
            $this->apiResult ["message"] = "Password too short. Minimum 6 characters required.\n";
        } else if ($user_profile && empty($_POST ['email']) && isset($_POST ['email'])) {
            $this->apiResult ["message"] = "Email cannot be empty\n";
        } else if ($user_profile && preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,5})$^", $email_update) != 1 && isset($_POST ['email'])) {
            $this->apiResult ["message"] = "Invalid email.\n";
        } else if ($principal_user_id_new == $_POST ['id'] && isset($_POST['roles']) && !in_array(6, $_POST ['roles']) && $_POST ['usertype_id'] == 5) {
            $this->apiResult ["message"] = "Not allowed to remove the Principal Role. Please first assign another user with the Principal Role\n";
        } else if (isset($_POST['roles']) && in_array("8", $_POST['roles']) && count($_POST['roles']) > 1) {
            $this->apiResult ["message"] = "TAP Admin cannot be assigned other roles.\n";
        } else {
            $user_id = trim($_POST ['id']);
            $user = $this->userModel->getUserById($user_id);
            $client_id_old = $user['client_id'];
            $networkModel = new networkModel ();
            $clientModel = new clientModel();
            $clientDetails = $clientModel->getClientById($_POST['client_id']);
            $currentUser = isset($this->user ['user_id']) ? $this->user['user_id'] : NULL;
            if (empty($user)) {
                $this->apiResult ["message"] = "User does not exist\n";
            } else if ($this->userModel->getUserByEmailExceptSelf($email_update, $user_id) && isset($_POST ['email'])) {
                $this->apiResult ["message"] = "Email already exists.\n";
            } else if (!in_array(6, $user ['role_ids']) && empty($_POST ['roles']) && in_array("manage_all_users", $this->user ['capabilities'])) {
                $this->apiResult ["message"] = "User Role cannot be empty\n";
            } else if (isset($_POST ['roles']) && in_array(7, $_POST ['roles']) && empty($clientDetails['network_id']) && empty($_POST ['usertype_id'])) {
                $this->apiResult ["message"] = "Zone admin role is allowed only for schools/colleges which are under some network.\n";
            } else if (in_array("manage_all_users", $this->user ['capabilities']) ||
                    $this->user ['user_id'] == $user ['user_id'] ||
                    ($user ['client_id'] == $this->user ['client_id'] && in_array("manage_own_users", $this->user ['capabilities'])) ||
                    ($this->user ['network_id'] > 0 && $this->db->get_array_value("network_id", $networkModel->getNetworkByClientId($_POST ['client_id'])) == $this->user ['network_id'] && in_array("manage_own_network_users", $this->user ['capabilities']))) {
                $name = trim($_POST ['name']);
                if ($user_profile && preg_match('/^[A-Za-z0-9\s,.\'@#$%&*+_-]+$/', $name) != 1) {
                    $this->apiResult ["message"] = "Invalid Characters are not allowed in Name.\n";
                } else {
                    // check role for tap admin on 12-05-2016 by Mohit Kumar
                    if (isset($_POST ['roles']) && $_POST ['roles'] [0] == 8 && !empty($_POST ['roles'])) {
                        $roleId = $_POST ['roles'] [0];
                    } else {
                        $roleId = empty($_POST ['role_id']) ? 0 : $_POST ['role_id'];
                    }
                    $usersId = empty($_POST ['users_id']) ? 0 : $_POST ['users_id'];
                    $usersId = explode(",", $usersId);
                    $queryFailed = 0;
                    $email = $user ['email'];
                    $this->db->start_transaction();
                    if (OFFLINE_STATUS == TRUE) {
                        $userUniqueID = $this->db->createUniqueID('editUser');
                    }
                    $oldRoles = empty($this->userModel->getUserRoles($user_id)) ? '' : $this->userModel->getUserRoles($user_id);
                    $review_trasfer_status = true;
                    $new_principal_generated_id = 0;
                    if (in_array("manage_own_users", $this->user ['capabilities']) && in_array("edit_all_submitted_assessments", $this->user ['capabilities'])) {
                        if ($_POST['client_id'] != $client_id_old) {

                            $principal_user_row_old = $this->userModel->getPrincipal($client_id_old);
                            $principal_user_id_old = empty($principal_user_row_old) ? 0 : $principal_user_row_old['user_id'];
                            $principal_user_row_new = $this->userModel->getPrincipal($_POST['client_id']);
                            $principal_user_id_new = empty($principal_user_row_new) ? 0 : $principal_user_row_new['user_id'];
                            if ($principal_user_id_old == $_POST['id']) {
                                $new_principal_generated_id = $this->userModel->createrandomuser($client_id_old, $this->user['user_id'], $_POST['id'], $currentUser);
                                if ($new_principal_generated_id > 0) {
                                    if (!$this->userModel->transfer_reviews($principal_user_id_old, $new_principal_generated_id)) {
                                        $review_trasfer_status = false;
                                    }
                                    if (!$this->userModel->transfer_reviews($principal_user_id_new, $principal_user_id_old)) {
                                        $review_trasfer_status = false;
                                    }
                                } else {
                                    $review_trasfer_status = false;
                                }
                            } else {
                                if ($principal_user_id_old === 0) {
                                    $new_principal_generated_id = $this->userModel->createrandomuser($client_id_old, $this->user['user_id'], 0, $currentUser);
                                    if ($new_principal_generated_id > 0) {
                                        if (!$this->userModel->transfer_reviews($_POST['id'], $new_principal_generated_id)) {
                                            $review_trasfer_status = false;
                                        }
                                    } else {
                                        $review_trasfer_status = false;
                                    }
                                } else {
                                    if (!$this->userModel->transfer_reviews($_POST['id'], $principal_user_id_old)) {
                                        $review_trasfer_status = false;
                                    }
                                }
                            }
                        }
                    }
                    // find clientId by userId
                    foreach ($usersId as $user => $id) {
                        if ($id != 0) {
                            if (!$this->userModel->deleteUserRole($id, $roleId)) {
                                $queryFailed = 1;
                            } else {
                                if (OFFLINE_STATUS == TRUE) {
                                    $action_user_deleted_role_json = json_encode(array(
                                        'user_id' => $id,
                                        'role_id' => $roleId
                                    ));
                                    $this->db->saveHistoryData($id, 'h_user_user_role', $userUniqueID, 'removeUserRole', $id, $roleId, $action_user_deleted_role_json, 0, date('Y-m-d H:i:s'));
                                    $queryFailed = 0;
                                }
                            }
                        }
                    }
                    if (in_array("manage_own_users", $this->user ['capabilities']) && in_array("edit_all_submitted_assessments", $this->user ['capabilities'])) {
                        $userUpdated = $this->userModel->updateUserEmail($user_id, $name, $email_update, $_POST ['password'], $_POST['client_id'], -1, date("Y-m-d H:i:s"), $currentUser, $moodle_user);
                    } else {
                        $userUpdated = $this->userModel->updateUser($user_id, $name, $_POST ['password'], 0, -1, date("Y-m-d H:i:s"), $currentUser, $moodle_user);
                    }

                    $this->userModel->deleteAdminLevels($user_id); //echo '<pre>';print_r($_POST);
                    if (isset($_POST ['state_id']) != '' && $_POST ['usertype_id'] == 1) {
                        $admin_levels_id = $this->userModel->createAdminLevels($user_id, $_POST ['usertype_id'], $_POST ['state_id']);
                    } else if (!empty($_POST ['zone_id']) && $_POST ['usertype_id'] == 2) {
                        foreach ($_POST ['zone_id'] as $zone_ids) {//echo '<pre>';print_r($zone_ids);
                            $admin_levels_id = $this->userModel->createAdminLevels($user_id, $_POST ['usertype_id'], $_POST ['state_id'], $zone_ids);
                        }
                    } else if (!empty($_POST ['block_id']) && $_POST ['usertype_id'] == 3) {
                        foreach ($_POST ['block_id'] as $block_ids) {
                            $sql = "select state_id,zone_id from h_network_zone_state where network_id = $block_ids";
                            $res = $this->db->get_results($sql); //print_r($res);
                            $stateID = $res[0]['state_id'];
                            $zoneID = $res[0]['zone_id'];
                            $admin_levels_id = $this->userModel->createAdminLevels($user_id, $_POST ['usertype_id'], $stateID, $zoneID, $block_ids);
                        }
                    } else if (!empty($_POST ['cluster_id']) && $_POST ['usertype_id'] == 4) {
                        foreach ($_POST ['cluster_id'] as $cluster_ids) {
                            $sql = "select state_id,zone_id,block_id from h_cluster_block_zone_state where cluster_id = $cluster_ids";
                            $res = $this->db->get_results($sql);
                            $stateID = $res[0]['state_id'];
                            $zoneID = $res[0]['zone_id'];
                            $blockID = $res[0]['block_id'];
                            $admin_levels_id = $this->userModel->createAdminLevels($user_id, $_POST ['usertype_id'], $stateID, $zoneID, $blockID, $cluster_ids);
                        }
                    }

                    if (OFFLINE_STATUS == TRUE) {
                        // start---> save edited user details on history table on 08-03-2016 by Mohit Kumar
                        $action_user_json = json_encode(array(
                            'name' => $name,
                            'password' => $_POST ['password']
                        ));
                        $this->db->saveHistoryData($user_id, 'd_user', $userUniqueID, 'editUser', $user_id, $email, $action_user_json, 0, date('Y-m-d H:i:s'));
                        // start---> save edited user details on history table on 08-03-2016 by Mohit Kumar
                    }
                    $rolesUpdated = true;
                    if ((in_array("manage_all_users", $this->user ['capabilities']) || in_array(6, $this->user ['role_ids']) || in_array(7, $this->user ['role_ids'])) && !empty($_POST ['roles'])) { // principal can update user role to school admin and internal reviewer
                        $currentRoles = $this->userModel->getUserRoles($user_id);
                        if ($currentRoles === null) {
                            // add tap admin role on 12-05-2016 by Mohit Kumar
                            if (current($_POST ['roles']) == 8) {
                                foreach ($_POST ['roles'] as $role) {
                                    if (!$this->userModel->addUserRole($user_id, $role)) {
                                        $rolesUpdated = false;
                                        break;
                                    } else {
                                        if (OFFLINE_STATUS == TRUE) {
                                            $action_user_deleted_role_json = json_encode(array(
                                                'user_id' => $user_id,
                                                'role_id' => $role
                                            ));
                                            $this->db->saveHistoryData($user_id, 'h_user_user_role', $userUniqueID, 'removeUserRole', $user_id, $role, $action_user_deleted_role_json, 0, date('Y-m-d H:i:s'));
                                        }
                                    }
                                }
                            } else {
                                if (isset($_POST['roles'])) {
                                    foreach ($_POST ['roles'] as $role) {
                                        if (!$this->userModel->addUserRole($user_id, $role)) {
                                            $rolesUpdated = false;
                                            break;
                                        } else {
                                            if (OFFLINE_STATUS == TRUE) {
                                                $action_user_deleted_role_json = json_encode(array(
                                                    'user_id' => $user_id,
                                                    'role_id' => $role
                                                ));
                                                $this->db->saveHistoryData($user_id, 'h_user_user_role', $userUniqueID, 'removeUserRole', $user_id, $role, $action_user_deleted_role_json, 0, date('Y-m-d H:i:s'));
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            $currentRoles = $currentRoles != "" ? explode(",", $currentRoles) : array();
                            if (in_array(6, $this->user ['role_ids'])) {
                                $array_allow_roles = array(3, 5);
                                $currentRoles = array_intersect($currentRoles, $array_allow_roles);
                            } else if (in_array(7, $this->user ['role_ids'])) {
                                $array_allow_roles = array(3, 6, 5);
                                $currentRoles = array_intersect($currentRoles, $array_allow_roles);
                            }
                            $commonValues = array_intersect($currentRoles, $_POST ['roles']);
                            $rolesNeedToBeAdded = array_diff($_POST ['roles'], $commonValues);
                            $rolesNeedToBeDeleted = array_diff($currentRoles, $commonValues);
                            if (($key = array_search(6, $rolesNeedToBeDeleted)) !== false && in_array(6, $this->user ['role_ids'])) { // principal can not delete his own principal role
                                unset($rolesNeedToBeDeleted [$key]);
                            }

                            foreach ($rolesNeedToBeDeleted as $role)
                                if (!$this->userModel->deleteUserRole($user_id, $role)) {

                                    $rolesUpdated = false;

                                    break;
                                } else {
                                    if (OFFLINE_STATUS == TRUE) {
                                        $action_user_role_json = json_encode(array(
                                            'user_id' => $user_id,
                                            'role_id' => $role
                                        ));
                                        $this->db->saveHistoryData($user_id, 'h_user_user_role', $userUniqueID, 'editUserRole', $user_id, $role, $action_user_role_json, 0, date('Y-m-d H:i:s'));
                                    }
                                }

                            foreach ($rolesNeedToBeAdded as $role)
                                if (!$this->userModel->addUserRole($user_id, $role)) {

                                    $rolesUpdated = false;

                                    break;
                                } else {
                                    if (OFFLINE_STATUS == TRUE) {
                                        $action_user_role_json = json_encode(array(
                                            'user_id' => $user_id,
                                            'role_id' => $role
                                        ));
                                        $this->db->saveHistoryData($user_id, 'h_user_user_role', $userUniqueID, 'editUserRole', $user_id, $role, $action_user_role_json, 0, date('Y-m-d H:i:s'));
                                    }
                                }
                        }
                    }
                    $currentRoles_new = empty($this->userModel->getUserRoles($user_id)) ? '' : $this->userModel->getUserRoles($user_id);
                    $add_user_history = true;
                    if (!$this->userModel->add_user_history($user_id, $client_id_old, $_POST['client_id'], $oldRoles, $currentRoles_new, 'Updated', $this->user['user_id'], date("Y-m-d H:i:s"))) {
                        $add_user_history = false;
                    }
                    if ($userUpdated && !$queryFailed && $rolesUpdated && $review_trasfer_status && $add_user_history && $this->db->commit()) {

                        if ($new_principal_generated_id > 0 && in_array("manage_own_users", $this->user ['capabilities']) && in_array("edit_all_submitted_assessments", $this->user ['capabilities'])) {
                            $new_user_details = $this->userModel->getUserById($new_principal_generated_id);
                            $toEmail = 'deepakchauhan89@gmail.com';
                            $body_mail = "Dear Admin<br><br>User with Email Id: " . $new_user_details['email'] . " as Role of Principal for <b>" . $new_user_details['client_name'] . "</b> is generated automatically."
                                    . "<br>This is requested to modify the user<br><br>This is auto generated email, need not to reply<br><br>Thanks";
                            sendEmail('' . $this->user['email'] . '', '' . $this->user['name'] . '', $toEmail, 'Shraddha Khedekar', '', '', 'Adhyayan:: Auto Generated User as Principal for ' . $new_user_details['client_name'] . '', $body_mail, 'poonam.choksi@adhyayan.asia');
                        }
                        $this->apiResult ["status"] = 1;
                        $this->apiResult ["message"] = "User successfully updated";
                        $this->apiResult ["client_id"] = $_POST['client_id'];
                        $nameArray = explode(" ", $name);
                        $firstname = array_shift($nameArray);
                        $last_name = is_array($nameArray) ? implode(" ", $nameArray) : '';
                        $password_moodle = empty($_POST ['password']) ? "adhyayan_123456" : $_POST ['password'];
                        $student_moodle_data = $this->prepare_student_data($email_update, $password_moodle, $firstname, $last_name, $email_update);
                        $moodle_user = isset($_POST['moodle_user']) ? $_POST['moodle_user'] : 0;
                        $prepare_check_data = $this->prepare_check_data($email);
                        $update_data = array();
                        if ($moodle_user == 1 && !add_update_user_moodle($student_moodle_data, $prepare_check_data)) {
                            $this->apiResult ["message"] = "User successfully updated but not added/updated in Moodle";
                        }
                    } else {
                        $this->db->rollback();
                        $this->apiResult ["message"] = "Error occurred, please check the error logs.\n";
                    }
                }
            } else {
                $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
            }
        }
    }

    //@Purpose:create school data 
    function createClientAction() {
        if (!in_array("create_client", $this->user ['capabilities'])) {
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        } else if (empty($_POST ['client_institution_id'])) {
            $this->apiResult ["message"] = "Type of Institution cannot be empty\n";
        } else if (empty($_POST ['client_name'])) {
            $this->apiResult ["message"] = "Name of the school/college cannot be empty\n";
        } else if (empty($_POST ['street'])) {
            $this->apiResult ["message"] = "Street cannot be empty\n";
        } else if (empty($_POST ['city'])) {
            $this->apiResult ["message"] = "City cannot be empty\n";
        } else if (empty($_POST ['country'])) {
            $this->apiResult ["message"] = "Country cannot be empty\n";
        } else if (empty($_POST ['state'])) {
            $this->apiResult ["message"] = "State cannot be empty\n";
        } else if (empty($_POST ['principal_name'])) {
            $this->apiResult ["message"] = "Principal name cannot be empty\n";
        } else if (empty($_POST ['email'])) {
            $this->apiResult ["message"] = "Email cannot be empty\n";
        } else if (empty($_POST ['password'])) {
            $this->apiResult ["message"] = "Password cannot be empty\n";
        } else if (strlen($_POST ['password']) < 6) {
            $this->apiResult ["message"] = "Password too short. Minimum 6 characters required.\n";
        } else if (!empty($_POST ['haveNetwork']) && $_POST ['haveNetwork'] == 1 && empty($_POST ['network'])) {
            $this->apiResult ["message"] = "Zone cannot be empty\n";
        } else if (!empty($_POST ['phone']) && !preg_match("/^[1-9][0-9]*$/", $_POST ['phone'])) {
            $this->apiResult ["message"] = "Invalid phone number\n";
        } else {
            $cname = trim($_POST ['client_name']);
            $pname = trim($_POST ['principal_name']);
            $email = strtolower(trim($_POST ['email']));
            $currentUser = isset($this->user ['user_id']) ? $this->user['user_id'] : NULL;
            if (preg_match('/^[\.A-Za-z0-9\s,.\'-]+$/', $cname) != 1) {
                $this->apiResult ["message"] = "Only Alphabets(a-z), numbers (0-9) ,period(.),apostrophe(') and hyphen(-) allowed in name of the school/college.\n";
            } else if (preg_match('/^[A-Za-z0-9\s,.\'@#$%&*+_-]+$/', $pname) != 1) {
                $this->apiResult ["message"] = "Invalid Characters are not allowed in Principal Name.\n";
            } else if (preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,5})$^", $email) != 1) {
                $this->apiResult ["message"] = "Invalid email.\n";
            } else if ($this->userModel->getUserByEmail($email)) {
                $this->apiResult ["message"] = "Email already exists.\n";
            } else {
                $this->db->start_transaction();
                if (OFFLINE_STATUS == TRUE) {
                    // start--> call function for creating unique id for add school on 07-03-2016 by Mohit Kumar
                    $clientUniqueID = $this->db->createUniqueID('addSchool');
                    // end--> call function for creating unique id for add school on 07-03-2016 by Mohit Kumar
                }
                $clientModel = new clientModel ();
                $principle_ph = "(+" . $_POST ['country_code'] . ")" . $_POST ['phone'];
                $cid = $clientModel->createClient($_POST ['client_institution_id'], $cname, $_POST ['street'], $_POST ['addrline2'], $_POST ['city'], $_POST ['state'], $_POST ['country'], $principle_ph, $_POST ['remarks'], 0);

                if (OFFLINE_STATUS == true) {
                    // start---> call function for saving add school client data on 04-03-2016 by Mohit Kumar
                    $action_client_json = json_encode(array(
                        'client_name' => $cname,
                        'street' => $_POST ['street'],
                        'addressLine2' => $_POST ['addrline2'],
                        'city_id' => $_POST ['city'],
                        'state_id' => $_POST ['state'],
                        'country_id' => $_POST ['country'],
                        "principal_phone_no" => $principle_ph,
                        'remarks' => $_POST ['remarks']
                    ));
                    $this->db->saveHistoryData($cid, 'd_client', $clientUniqueID, 'addSchool', $cid, $email, $action_client_json, 0, date('Y-m-d H:i:s'));
                    // end---> call function for saving add school client data on 04-03-2016 by Mohit Kumar
                }

                $uid = $this->userModel->createUser($email, $_POST ['password'], $pname, $cid, 0, date("Y-m-d H:i:s"), $currentUser);
                // start---> call function for saving add school principal data on 04-03-2016 by Mohit Kumar
                $action_principal_json = json_encode(array(
                    'email' => $email,
                    'name' => $pname,
                    'password' => $_POST ['password'],
                    'client_id' => $cid
                ));
                // end-----> i have commented existing function bcoz i need auto increment id for history table
                // start----> i have used used new function for add the user role.
                $roleAdded = $this->userModel->addNewUserRole($uid, 6);
                $roleAdded = $this->userModel->addNewUserRole($uid, 3);
                // start---> call function for saving add school principal role data on 04-03-2016 by Mohit Kumar
                $action_role_json = json_encode(array(
                    'user_id' => $uid,
                    'role_id' => 6
                ));
                if (OFFLINE_STATUS == TRUE) {
                    $this->db->saveHistoryData($uid, 'd_user', $clientUniqueID, 'addSchoolPrincipal', $cid, $email, $action_principal_json, 0, date('Y-m-d H:i:s'));
                    $this->db->saveHistoryData($roleAdded, 'h_user_user_role', $clientUniqueID, 'addSchoolPrincipalRole', $uid, 6, $action_role_json, 0, date('Y-m-d H:i:s'));
                    // end---> call function for saving add school principal role data on 04-03-2016 by Mohit Kumar
                }

                $addedToNetwork = true;
                $addedToProvince = true;
                if (!empty($_POST ['state_id'])) {
                    //add state
                    $addedToState = $clientModel->addClientToState($cid, $_POST ['state_id']);
                    // start---> call function for saving add school network data on 04-03-2016 by Mohit Kumar
                    $action_network_json = json_encode(array(
                        'client_id' => $cid,
                        'state_id' => $_POST ['state_id']
                    ));
                    if (OFFLINE_STATUS == TRUE)
                        $this->db->saveHistoryData($cid, 'h_client_state', $clientUniqueID, 'addSchoolNetwork', $cid, $_POST ['state_id'], $action_network_json, 0, date('Y-m-d H:i:s'));

                    //add to zone
                    $addedToZone = $clientModel->addClientToZone($cid, $_POST ['zone_id']);
                    // start---> call function for saving add school network data on 04-03-2016 by Mohit Kumar
                    $action_zone_json = json_encode(array(
                        'client_id' => $cid,
                        'zone_id' => $_POST ['zone_id']
                    ));
                    if (OFFLINE_STATUS == TRUE)
                        $this->db->saveHistoryData($cid, 'h_client_zone', $clientUniqueID, 'addSchoolNetwork', $cid, $_POST ['zone_id'], $action_zone_json, 0, date('Y-m-d H:i:s'));
                    // end---> call function for saving add school network data on 04-03-2016 by Mohit Kumar
                    //add block
                    $addedToState = $clientModel->addClientToNetwork($cid, $_POST ['block_id']);
                    // start---> call function for saving add school network data on 04-03-2016 by Mohit Kumar
                    $action_network_json = json_encode(array(
                        'client_id' => $cid,
                        'network_id' => $_POST ['block_id']
                    ));
                    if (OFFLINE_STATUS == TRUE)
                        $this->db->saveHistoryData($cid, 'h_client_network', $clientUniqueID, 'addSchoolNetwork', $cid, $_POST ['network_id'], $action_network_json, 0, date('Y-m-d H:i:s'));
                    //province to be added if selected
                    $province_id = $_POST['province'];
                    if (!empty($province_id)) {
                        $addedToProvince = $clientModel->addClientToProvince($cid, $province_id);
                        // start---> call function for saving add school network data on 04-03-2016 by Mohit Kumar
                        $action_network_json = json_encode(array(
                            'client_id' => $cid,
                            'province_id' => $province_id
                        ));
                        if (OFFLINE_STATUS == TRUE)
                            $this->db->saveHistoryData($cid, 'h_client_province', $clientUniqueID, 'addSchoolProvince', $cid, $_POST ['province'], $action_network_json, 0, date('Y-m-d H:i:s'));
                        // end---> call function for saving add school network data on 04-03-2016 by Mohit Kumar
                    }
                }
                if ($cid > 0 && $uid > 0 && $roleAdded && $addedToNetwork && $addedToProvince && $this->db->commit()) {
                    $this->apiResult ["status"] = 1;
                    $this->apiResult ["message"] = "School/College successfully added";
                } else {
                    $this->db->rollback();
                    $this->apiResult ["message"] = "Error occurred, please check the error logs.\n";
                }
            }
        }
    }

    //@Purpose:edit school data 
    function updateClientAction() {
        if (!in_array("create_client", $this->user ['capabilities'])) {
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        } else if (empty($_POST ['id'])) {
            $this->apiResult ["message"] = "School/College ID missing\n";
        } else if (empty($_POST ['client_institution_id'])) {
            $this->apiResult ["message"] = "Type of Institution cannot be empty\n";
        } else if (empty($_POST ['client_name'])) {
            $this->apiResult ["message"] = "Name of the school/college cannot be empty\n";
        } else if (empty($_POST ['principal_name'])) {
            $this->apiResult ["message"] = "Principal name cannot be empty\n";
        } else if (empty($_POST ['street'])) {
            $this->apiResult ["message"] = "Street cannot be empty\n";
        } else if (empty($_POST ['city'])) {
            $this->apiResult ["message"] = "City cannot be empty\n";
        } else if (empty($_POST ['state'])) {
            $this->apiResult ["message"] = "State cannot be empty\n";
        } else if (!empty($_POST ['haveNetwork']) && $_POST ['haveNetwork'] == 1 && empty($_POST ['network'])) {
            $this->apiResult ["message"] = "Zone cannot be empty\n";
        } else if (!empty($_POST ['phone']) && !preg_match("/^[1-9][0-9]*$/", $_POST ['phone'])) {
            $this->apiResult ["message"] = "Invalid phone number\n";
        } else {
            $pname = trim($_POST ['principal_name']);
            $cname = trim($_POST ['client_name']);
            $clientModel = new clientModel ();
            $client_id = trim($_POST ['id']);
            $client = $clientModel->getClientById($client_id);
            $principal = $this->userModel->getPrincipal($client_id);
            $currentUser = isset($this->user ['user_id']) ? $this->user['user_id'] : NULL;
            $principle_ph = "(+" . $_POST ['country_code'] . ")" . $_POST ['phone'];
            if (empty($client)) {
                $this->apiResult ["message"] = "School/College does not exist\n";
            }if (preg_match('/^[\.A-Za-z0-9\s,.\'-]+$/', $cname) != 1) {
                $this->apiResult ["message"] = "Only Alphabets(a-z), numbers (0-9) ,period(.),apostrophe(') and hyphen(-) allowed in name of the school/college.\n";
            } else if (preg_match('/^[A-Za-z0-9\s,.\'@#$%&*+_-]+$/', $pname) != 1) {
                $this->apiResult ["message"] = "Invalid Characters are not allowed in Principal Name.\n";
            } else if (empty($principal ['user_id'])) {
                $this->apiResult ["message"] = "Principal does not exist for this school/school.\n";
            } else {

                if (OFFLINE_STATUS == TRUE) {
                    // start--> call function for creating unique id for add school on 08-03-2016 by Mohit Kumar
                    $clientUniqueID = $this->db->createUniqueID('editSchool');
                    // end--> call function for creating unique id for add school on 08-03-2016 by Mohit Kumar
                }
                $this->db->start_transaction();
                $updated = $clientModel->updateClient($_POST ['client_institution_id'], $client_id, $cname, $_POST ['street'], $_POST ['addrline2'], $_POST ['city'], $_POST ['state'], $_POST ['country'], $principle_ph, $_POST ['remarks']);

                if (OFFLINE_STATUS == true) {
                    // start---> call function for saving edit school client data on 08-03-2016 by Mohit Kumar
                    $action_client_json = json_encode(array(
                        'client_name' => $cname,
                        'street' => $_POST ['street'],
                        'addressLine2' => $_POST ['addrline2'],
                        'city_id' => $_POST ['city'],
                        'state_id' => $_POST ['state'],
                        'country_id' => $_POST ['country'],
                        "principal_phone_no" => $principle_ph,
                        'remarks' => $_POST ['remarks']
                    ));
                    $this->db->saveHistoryData($client_id, 'd_client', $clientUniqueID, 'editSchool', $client_id, $principal ['user_id'], $action_client_json, 0, date('Y-m-d H:i:s'));
                    // end---> call function for saving edit school client data on 08-03-2016 by Mohit Kumar
                }

                if (!$this->userModel->updateUser($principal ['user_id'], $pname, '', 0, -1, date("Y-m-d H:i:s"), $currentUser)) {

                    $updated = false;
                } else {
                    if (OFFLINE_STATUS == TRUE) {
                        // start---> call function for saving edit school principal data on 08-03-2016 by Mohit Kumar
                        $action_principal_json = json_encode(array(
                            'name' => $pname
                        ));
                        $this->db->saveHistoryData($principal ['user_id'], 'd_user', $clientUniqueID, 'editSchoolPrincipal', $client_id, $principal ['user_id'], $action_principal_json, 0, date('Y-m-d H:i:s'));
                        // end---> call function for saving add school principal data on 08-03-2016 by Mohit Kumar
                        $updated = true;
                    }
                }
                $networkUpdated = true;
                $provinceUpdated = true;
                $stateUpdated = $clientModel->updateClientFromState($client_id, $_POST ['state_id']);
                $zoneUpdated = $clientModel->updateClientFromZone($client_id, $_POST ['zone_id']);
                $blockUpdated = $clientModel->updateClientFromNetwork($client_id, $_POST ['block_id']);
                $provinceUpdated = $clientModel->updateClientFromCluster($client_id, $_POST ['province']);
                if ($updated && $networkUpdated && $this->db->commit()) {
                    $this->apiResult ["status"] = 1;

                    $this->apiResult ["message"] = "School successfully updated";
                } else {
                    $this->db->rollback();
                    $this->apiResult ["message"] = "Error occurred, please check the error logs.\n";
                }
            }
        }
    }

    //@Purpose:upload files for diagnostic 
    function uploadFileAction() {
        $maxUploadFileSize = 104857600; // in bytes
        $allowedExt = array(
            "jpeg",
            "png",
            "gif",
            "jpg",
            "avi",
            "mp4",
            "mov",
            "doc",
            "docx",
            "txt",
            "xls",
            "xlsx",
            "pdf",
            "csv",
            'xml',
            'pptx',
            'ppt',
            'cdr',
            'mp3',
            'wav'
        );

        if (!in_array("upload_file", $this->user ['capabilities'])) {
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        } else if (empty($_FILES ['file'])) {
            $this->apiResult ["message"] = "No file uploaded with this request\n";
        } else if ($_FILES ['file'] ['error'] > 0) {
            $this->apiResult ["message"] = "File contains error or error occurred while uploading\n";
        } else if (!($_FILES ['file'] ['size'] > 0)) {
            $this->apiResult ["message"] = "Invalid file size or empty file\n";
        } else if ($_FILES ['file'] ['size'] > $maxUploadFileSize) {
            $this->apiResult ["message"] = "File too big\n";
        } else {
            $nArr = explode(".", $_FILES ['file'] ['name']);
            $ext = strtolower(array_pop($nArr));
            if (in_array($ext, $allowedExt) && count($nArr) > 0) {
                $newName = sanitazifileName(str_replace(" ", "_", substr(implode("_", $nArr), 0, 35)) . "_" . rand(1, 9999) . "_" . time()) . "." . $ext;
                if (upload_file(UPLOAD_PATH . "" . $newName, $_FILES ['file'] ['tmp_name'])) {
                    $diagnosticModel = new diagnosticModel ();
                    $id = $diagnosticModel->addUploadedFile($newName, $this->user ['user_id']);
                    if ($id > 0) {
                        $this->apiResult ["message"] = "File successfully uploaded";
                        $this->apiResult ["status"] = 1;
                        $this->apiResult ["id"] = $id;
                        $this->apiResult ["name"] = $newName;
                        $this->apiResult ["ext"] = $ext;
                        $this->apiResult ["url"] = UPLOAD_URL . "" . $newName;
                    } else {
                        $this->apiResult ["message"] = "Unable to make entry in database";
                        @unlink(UPLOAD_PATH . "" . $newName);
                    }
                } else {
                    $this->apiResult ["message"] = "Error occurred while moving file\n";
                }
            } else {
                $this->apiResult ["message"] = "Invalid file extension. Only " . implode(", ", $allowedExt) . " type files are allowed\n";
            }
        }
    }

    
    /* 
    * @Purpose : Function to add key recommendation
    */
    function addKeyNoteAction() {
        $diagnosticModel = new diagnosticModel ();
        if (empty($_POST ['assessment_id'])) {
            $this->apiResult ["message"] = "Review id cannot be empty\n";
        } else if (empty($_POST ['level_type'])) {
            $this->apiResult ["message"] = "Level type  cannot be empty\n";
        } else if (empty($_POST ['instance_id'])) {
            $this->apiResult ["message"] = "Instance Id  cannot be empty\n";
        } else if ($assessment = $diagnosticModel->getAssessmentByRole($_POST ['assessment_id'], 4)) {
            if ($assessment ["status"] == 1 && !in_array("edit_all_submitted_assessments", $this->user ['capabilities'])) {
                $this->apiResult ["message"] = "You are not authorized to update review after submission\n";
            }else {
                $type = empty($_POST ['type']) ? '' : $_POST ['type'];
                $instance_type = $_POST ['level_type'];
                $instance_type_id = $_POST ['instance_id'];
                $type_q = isset($_POST['type_q']) ? $_POST['type_q'] : '';
                $this->apiResult ["status"] = 1;
                if ($type_q == "kpa" && $type == "recommendation" && $assessment['assessment_type_id'] == 1) {
                    $getJSforKPA = $diagnosticModel->getJSforKPA($instance_type_id);
                    $this->apiResult ["content"] = kpajsHelper::getAssessorKeyNoteHtmlRow('', $instance_type_id, 'new', '', $type, 0, 1, $type_q, $getJSforKPA);
                } else {
                    $this->apiResult ["content"] = kpajsHelper::getAssessorKeyNoteHtmlRow('', $instance_type_id, 'new', '', $type);
                }
                $this->apiResult ["message"] = "Successfully added";
            }
        } else {
            $this->apiResult ["message"] = "Wrong review id\n";
        }
    }

    /*
     * @Purpose:Add External reviewer team member
     */
    function addExternalReviewTeamAction() {
        if (empty($_POST ['sn'])) {
            $this->apiResult ["message"] = "Please provide a serial no.\n";
        } else {
            $assessmentModel = new assessmentModel ();
            $this->apiResult ["status"] = 1;
            if ($_POST ['frm'] == "edit_college_assessment_form" || $_POST ['frm'] == "create_college_assessment_form") {
                $this->apiResult ["content"] = kpajsHelper::getExternalReviewTeamHTMLRow($_POST ['sn'], 'college');
            } else if ($_POST ['frm'] == "create_school_assessment_form" || $_POST ['frm'] == "edit_school_assessment_form") {
                $this->apiResult ["content"] = kpajsHelper::getExternalReviewTeamHTMLRow($_POST ['sn'], 'school');
            } else {
                $this->apiResult ["content"] = kpajsHelper::getExternalReviewTeamHTMLRow($_POST ['sn']);
            }
        }
    }

    //@Purpose:Add new row of impact section in action plan
    function addImpactTeamAction() {
        if (empty($_POST ['sn'])) {
            $this->apiResult ["message"] = "Please provide a serial no.\n";
        } else if (empty($_POST ['id_c'])) {
            $this->apiResult ["message"] = "Please provide a id.\n";
        } else {
            $assessmentModel = new assessmentModel ();
            $this->apiResult ["status"] = 1;
            $this->apiResult ["content"] = kpajsHelper::getExternalImpactTeamHTMLRow($_POST ['sn'], $_POST ['id_c']);
        }
    }

    //@Purpose:Add team member for action plan 
    function addActionTeamAction() {
        if (empty($_POST ['sn'])) {
            $this->apiResult ["message"] = "Please provide a serial no.\n";
        } else {
            $this->apiResult ["status"] = 1;
            $this->apiResult ["content"] = kpajsHelper::getActionTeamHTMLRow($_POST ['sn']);
        }
    }

    //@Purpose:Add new row for activity for actionplan
    function addActionActityAction() {
        if (empty($_POST ['sn'])) {
            $this->apiResult ["message"] = "Please provide a serial no.\n";
        } else {
            $this->apiResult ["status"] = 1;
            $this->apiResult ["content"] = kpajsHelper::getActionActivityHTMLRow($_POST ['sn']);
        }
    }

    //Add new row for impact for actionplan
    function addActionImpactStmntAction() {
        if (empty($_POST ['sn'])) {
            $this->apiResult ["message"] = "Please provide a serial no.\n";
        } else {
            $impactStmntId = !empty($_POST ['impactStmntId']) ? $_POST ['impactStmntId'] : 0;
            $aqsDataModel = new aqsDataModel();
            $actionModel = new actionModel;
            $designations = $aqsDataModel->getDesignations();
            $classes = $aqsDataModel->getSchoolClassList();
            $methods = $actionModel->getImpactMethod();
            $this->apiResult ["status"] = 1;
            $this->apiResult ["content"] = kpajsHelper::getActionImpactStmntHTMLRow($_POST ['sn'], '', $impactStmntId, $designations, $classes, '', $methods);
        }
    }

    //@Purpose:add facilitator derails
    function addFacilitatorReviewTeamAction() {
        if (empty($_POST ['sn'])) {
            $this->apiResult ["message"] = "Please provide a serial no.\n";
        } else {
            $assessmentModel = new assessmentModel ();
            $this->apiResult ["status"] = 1;
            if ($_POST ['frm'] == "edit_college_assessment_form" || $_POST ['frm'] == "create_college_assessment_form") {
                $this->apiResult ["content"] = kpajsHelper::getFacilitatorReviewTeamHTMLRow($_POST ['sn'], 'college');
            } else if ($_POST ['frm'] == "create_school_assessment_form" || $_POST ['frm'] == "edit_school_assessment_form") {
                $this->apiResult ["content"] = kpajsHelper::getFacilitatorReviewTeamHTMLRow($_POST ['sn'], 'school');
            } else {
                $this->apiResult ["content"] = kpajsHelper::getFacilitatorReviewTeamHTMLRow($_POST ['sn']);
            }
        }
    }

    //@Purpose:for workshop add
    function addFilterRowAction() {
        if (empty($_POST ['sn'])) {
            $this->apiResult ["message"] = "Please provide a serial no.\n";
        } else {
            $isDashboard = !empty($_POST ['isDashboard']) ? $_POST ['isDashboard'] : 0;
            $this->apiResult ["status"] = 1;
            $this->apiResult ["content"] = customreportModel::getFilterRow($_POST ['sn'], 0, $isDashboard);
        }
    }

    function addNetworkExpRowAction() {
        if (empty($_POST ['sn'])) {
            $this->apiResult ["message"] = "Please provide a serial no.\n";
        } else {
            $this->apiResult ["status"] = 1;
            $this->apiResult ["content"] = customreportModel::getExperienceRow($_POST ['sn']);
        }
    }

    //@Purpose:add team row 
    function addTeamRowAction() {
        if (empty($_POST ['sn'])) {
            $this->apiResult ["message"] = "Please provide a serial no.\n";
        } else if (empty($_POST ['type'])) {
            $this->apiResult ["message"] = "Please provide the type of row(school/adhyayan/teacherAssessor)\n";
        } else {
            $this->apiResult ["status"] = 1;
            switch ($_POST ['type']) {
                case 'adhyayan' :

                case 'school' :
                    $aqsDataModel = new aqsDataModel ();
                    $this->apiResult ["content"] = $aqsDataModel->getAqsTeamHtmlRow($_POST ['sn'], $_POST ['type'] == "school" ? 1 : 0, '', '', '', '', '', '', 1);

                    break;
                default :
                    $this->apiResult ["status"] = 0;
                    $this->apiResult ["message"] = "Please provide a valid type of row(school/adhyayan/teacherAssessor)\n";
                    break;
            }
        }
    }

    
    function replicateAssessmentAction() {
        $diagnosticModel = new diagnosticModel ();
        if (empty($_POST ['assessment_id'])) {
            $this->apiResult ["message"] = "Review id cannot be empty\n";
        } else {
            $self_review = $diagnosticModel->getAssessmentByRole($_POST ['assessment_id'], 3);
            $external_review = $diagnosticModel->getAssessmentByRole($_POST ['assessment_id'], 4);
            if ($self_review['status'] != 1 || $self_review['percComplete'] < 100) {
                $this->apiResult ["message"] = "Self Review should be fully completed\n";
            } else if ($external_review['percComplete'] >= 100 && $external_review['status'] == 1) {
                $this->apiResult ["message"] = "This is not allowed\n";
            } else if ($external_review['is_replicated'] == 1) {
                $this->apiResult ["message"] = "This is not allowed\n";
            } else {
                $internal_reviewer = $self_review['user_id'];
                $external_reviewer = $external_review['user_id'];
                $a = $diagnosticModel->getAllJudgementScore($_POST ['assessment_id'], $internal_reviewer);
                $b = $diagnosticModel->getAllCoreQuestionScore($_POST ['assessment_id'], $internal_reviewer);
                $c = $diagnosticModel->getAllKeyQuestionScore($_POST ['assessment_id'], $internal_reviewer);
                $d = $diagnosticModel->getAllKpaScore($_POST ['assessment_id'], $internal_reviewer);
                $this->db->start_transaction();
                $success = true;
                $success_1 = true;
                $success_2 = true;
                $success_3 = true;
                $fail = 0;
                $noOfComplete = 0;
                $total = 0;
                foreach ($a as $key => $val) {
                    $js_id = $val['judgement_statement_instance_id'];
                    $assessment_id = $val['assessment_id'];
                    $assessor_id = $external_reviewer;
                    $added_by = $this->user ['user_id'];
                    $rating_id = $val['rating_id'];
                    $text = "";
                    $score_id = $diagnosticModel->updateJudgementStatementScore($js_id, $assessment_id, $assessor_id, $added_by, $rating_id, $text);
                    if ($score_id == false) {
                        $success = false;
                        $fail = 1;
                        break;
                    } else {
                        $noOfComplete++;
                    }
                    $total++;
                }
                foreach ($b as $key => $val) {
                    $cq_id = $val['core_question_instance_id'];
                    $assessment_id = $val['assessment_id'];
                    $assessor_id = $external_reviewer;
                    $rating_id = $val['d_rating_rating_id'];
                    $cqQestionStatus = $diagnosticModel->getSingleCoreQuestionScore($cq_id, $assessment_id, $assessor_id);
                    if (count($cqQestionStatus) > 0) {
                        $score_id = $diagnosticModel->updateCoreQuestionScore($cq_id, $assessment_id, $assessor_id, $rating_id);
                    } else {
                        $score_id = $diagnosticModel->insertCoreQuestionScore($cq_id, $assessment_id, $assessor_id, $rating_id);
                    }
                    if ($score_id == false) {
                        $success_1 = false;
                        $fail = 2;
                        break;
                    } else {
                        $noOfComplete++;
                    }
                    $total++;
                }

                foreach ($c as $key => $val) {
                    $kq_id = $val['key_question_instance_id'];
                    $assessment_id = $val['assessment_id'];
                    $assessor_id = $external_reviewer;
                    $rating_id = $val['d_rating_rating_id'];
                    $kqQestionStatus = $diagnosticModel->getSingleKeyQuestionScore($kq_id, $assessment_id, $assessor_id);
                    if (count($kqQestionStatus) > 0) {
                        $score_id = $diagnosticModel->updateKeyQuestionScore($kq_id, $assessment_id, $assessor_id, $rating_id);
                    } else {
                        $score_id = $diagnosticModel->insertKeyQuestionScore($kq_id, $assessment_id, $assessor_id, $rating_id);
                    }
                    if ($score_id == false) {
                        $success_2 = false;
                        $fail = 3;
                        break;
                    } else {
                        $noOfComplete++;
                    }
                    $total++;
                }

                foreach ($d as $key => $val) {
                    $kpa_id = $val['kpa_instance_id'];
                    $assessment_id = $val['assessment_id'];
                    $assessor_id = $external_reviewer;
                    $rating_id = $val['d_rating_rating_id'];
                    $kpaQestionStatus = $diagnosticModel->getSingleKpaScore($kpa_id, $assessment_id, $assessor_id);
                    if (count($kpaQestionStatus) > 0) {
                        $score_id = $diagnosticModel->updateKpaScore($kpa_id, $assessment_id, $assessor_id, $rating_id);
                    } else {
                        $score_id = $diagnosticModel->insertKpaScore($kpa_id, $assessment_id, $assessor_id, $rating_id);
                    }
                    if ($score_id == false) {

                        $success_3 = false;
                        $fail = 4;
                        break;
                    } else {
                        $noOfComplete++;
                    }
                    $total++;
                }

                $kpa_id_percentage = isset($kpa_id) ? $kpa_id : 0;
                $keynotes = $diagnosticModel->getKeyNotesPer($_POST ['assessment_id'], $kpa_id_percentage);
                if ($keynotes > 0) {
                    $completedPerc = round((100 * $noOfComplete) / $total, 2);
                } else {
                    $completedPerc = round((100 * $noOfComplete - 2) / $total, 2);
                }

                if ($success && !$diagnosticModel->updateAssessmentPercentage($_POST ['assessment_id'], $external_reviewer, $completedPerc)) {

                    $success = false;
                }
                if ($success && !$diagnosticModel->updateAssessmentReplicate($_POST ['assessment_id'])) {
                    $success = false;
                }
                if ($success && $success_1 && $success_2 && $success_3 && $this->db->commit()) {
                    $this->apiResult ["status"] = 1;
                    $this->apiResult ["message"] = "Successfully saved";
                } else {
                    $this->db->rollback();
                    $this->apiResult ["message"] = "Error occurred, please check the error logs.\n";
                }
            }
        }
    }

    //@Purpose:save internal assessor rating data
    function saveInternalAssessorRatings($assessment_id, $completedPerc, $is_submit, $lang_id) {
        $diagnosticModel = new diagnosticModel ();
        if (empty($assessment_id)) {
            $this->apiResult ["message"] = "Review id cannot be empty\n";
        } else {
            $external_review = $diagnosticModel->getAssessmentByRole($assessment_id, 3, $lang_id);
            $self_review = $diagnosticModel->getAssessmentByRole($assessment_id, 4, $lang_id);
            $internal_reviewer = $self_review['user_id'];
            $external_reviewer = $external_review['user_id'];
            $a = $diagnosticModel->getAllJudgementScore($assessment_id, $internal_reviewer);
            $b = $diagnosticModel->getAllCoreQuestionScore($assessment_id, $internal_reviewer);
            $c = $diagnosticModel->getAllKeyQuestionScore($assessment_id, $internal_reviewer);
            $d = $diagnosticModel->getAllKpaScore($assessment_id, $internal_reviewer);
            $this->db->start_transaction();
            $success = true;
            $success_1 = true;
            $success_2 = true;
            $success_3 = true;
            $fail = 0;
            $noOfComplete = 0;
            $total = 0;
            foreach ($a as $key => $val) {
                $js_id = $val['judgement_statement_instance_id'];
                $assessment_id = $val['assessment_id'];
                $assessor_id = $external_reviewer;
                $added_by = $this->user ['user_id'];
                $rating_id = $val['rating_id'];
                $text = "";
                $score_id = $diagnosticModel->updateJudgementStatementScore($js_id, $assessment_id, $assessor_id, $added_by, $rating_id, $text);
                if ($score_id == false) {
                    $success = false;
                    $fail = 1;
                    break;
                } else {
                    $noOfComplete++;
                }
                $total++;
            }


            foreach ($b as $key => $val) {
                $cq_id = $val['core_question_instance_id'];
                $assessment_id = $val['assessment_id'];
                $assessor_id = $external_reviewer;
                $rating_id = $val['d_rating_rating_id'];
                $cqQestionStatus = $diagnosticModel->getSingleCoreQuestionScore($cq_id, $assessment_id, $assessor_id);
                if (count($cqQestionStatus) > 0) {
                    $score_id = $diagnosticModel->updateCoreQuestionScore($cq_id, $assessment_id, $assessor_id, $rating_id);
                } else {
                    $score_id = $diagnosticModel->insertCoreQuestionScore($cq_id, $assessment_id, $assessor_id, $rating_id);
                }
                if ($score_id == false) {
                    $success_1 = false;
                    $fail = 2;
                    break;
                } else {
                    $noOfComplete++;
                }
                $total++;
            }

            foreach ($c as $key => $val) {
                $kq_id = $val['key_question_instance_id'];
                $assessment_id = $val['assessment_id'];
                $assessor_id = $external_reviewer;
                $rating_id = $val['d_rating_rating_id'];
                $kqQestionStatus = $diagnosticModel->getSingleKeyQuestionScore($kq_id, $assessment_id, $assessor_id);
                if (count($kqQestionStatus) > 0) {
                    $score_id = $diagnosticModel->updateKeyQuestionScore($kq_id, $assessment_id, $assessor_id, $rating_id);
                } else {
                    $score_id = $diagnosticModel->insertKeyQuestionScore($kq_id, $assessment_id, $assessor_id, $rating_id);
                }
                if ($score_id == false) {

                    $success_2 = false;
                    $fail = 3;
                    break;
                } else {
                    $noOfComplete++;
                }
                $total++;
            }

            foreach ($d as $key => $val) {
                $kpa_id = $val['kpa_instance_id'];
                $assessment_id = $val['assessment_id'];
                $assessor_id = $external_reviewer;
                $rating_id = $val['d_rating_rating_id'];
                $kpaQestionStatus = $diagnosticModel->getSingleKpaScore($kpa_id, $assessment_id, $assessor_id);
                if (count($kpaQestionStatus) > 0) {
                    $score_id = $diagnosticModel->updateKpaScore($kpa_id, $assessment_id, $assessor_id, $rating_id);
                } else {
                    $score_id = $diagnosticModel->insertKpaScore($kpa_id, $assessment_id, $assessor_id, $rating_id);
                }
                if ($score_id == false) {

                    $success_3 = false;
                    $fail = 4;
                    break;
                } else {
                    $noOfComplete++;
                }
                $total++;
            }
            $kpa_id_percentage = isset($kpa_id) ? $kpa_id : 0;
            $keynotes = $diagnosticModel->getKeyNotesPer($assessment_id, $kpa_id_percentage);
            if (empty($is_submit)) {
                if ($success && !$diagnosticModel->updateAssessmentPercentage($assessment_id, $external_reviewer, $completedPerc)) {

                    $success = false;
                }
            } else if ($success && !$diagnosticModel->updateAssessmentPercentageAndStatus($assessment_id, $external_reviewer, $completedPerc, 1)) {
                $success = false;
            }

            if ($success && !$diagnosticModel->updateAssessmentReplicate($assessment_id)) {
                $success = false;
            }

            if ($success && $success_1 && $success_2 && $success_3 && $this->db->commit()) {
                
            } else {
                $this->db->rollback();
            }
        }
    }

    /* 
    * @Purpose : Function to save assessment
    */
    function saveAssessmentAction() {
        $diagnosticModel = new diagnosticModel ();
        $assessmentModel = new assessmentModel ();
        $lang_id = isset($_POST ['lang_id']) ? $_POST ['lang_id'] : DEFAULT_LANGUAGE;
        $external = isset($_POST ['external']) ? $_POST ['external'] : 0;
        $is_collaborative = isset($_POST ['is_collaborative']) ? $_POST ['is_collaborative'] : 0;
        $diagnostic_type = isset($_POST ['diagnostic_type']) ? $_POST ['diagnostic_type'] : 0;
        $isLeadSave = 0;
        if (empty($_POST ['assessment_id'])) {
            $this->apiResult ["message"] = "Review id cannot be empty\n";
        } else if (empty($_POST ['assessor_id'])) {
            $this->apiResult ["message"] = "Reviewer id cannot be empty\n";
        } else if ($assessment = $diagnosticModel->getAssessmentByUser($_POST ['assessment_id'], $_POST ['assessor_id'], $lang_id, $external)) {
            if ($assessment ['report_published'] == 1) {
                $this->apiResult ["message"] = "You can't update data after publishing reports\n";
            } else if ($assessment ["status"] == 1 && !in_array("edit_all_submitted_assessments", $this->user ['capabilities'])) {
                $this->apiResult ["message"] = "You are not authorized to update review after submission\n";
            } else if ($assessment ["status"] == 0 && $assessment ["user_id"] != $this->user ['user_id']) {
                $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
            } else {
                $assessment_id = $_POST ['assessment_id'];
                $assessor_id = $_POST ['assessor_id'];
                $added_by = $this->user ['user_id'];
                $singleKpaId = 0; // empty($_POST['kpa_id'])?0:$_POST['kpa_id']; //if kpa id is given then we will check & save data for single kpa otherwise if it is 0 then we will check & save data for all kpas

                $isLeadAssessorKpa = isset($_POST ['isLeadAssessorKpa']) ? trim($_POST ['isLeadAssessorKpa']) : '';
                $isLeadAssessor = isset($_POST ['isLeadAssessor']) ? trim($_POST ['isLeadAssessor']) : '';
                $isRevCompleteNtSubmitted = isset($_POST ['isRevCompleteNtSubmitted']) ? trim($_POST ['isRevCompleteNtSubmitted']) : '';
                $added_by = $this->user ['user_id'];
                $kpas = array();
                $userKpas = array();
                $allKpas = array();
                $kqs = array();
                $cqs = array();
                $jss = array();
                $singleKpaId = 0; // empty($_POST['kpa_id'])?0:$_POST['kpa_id']; //if kpa id is given then we will check & save data for single kpa otherwise if it is 0 then we will check & save data for all kpas
                if ($isLeadAssessorKpa == 1 && $is_collaborative == 1) {
                    $isLeadSave = 1;
                }
                if ($isLeadAssessorKpa && $is_collaborative && $isLeadSave == 0) {
                    $percentageData = $diagnosticModel->getExternalTeamRatingPerc($assessment_id);
                    if (!empty($percentageData)) {
                        $allAccessorsId = explode(",", $percentageData['user_ids']);
                        foreach ($allAccessorsId as $key => $val) {
                            $kpas = $this->db->array_col_to_key($diagnosticModel->getKpasForAssessment($assessment_id, $val, 0, $lang_id, $is_collaborative, 1, $isLeadAssessorKpa), "kpa_instance_id");
                            $userKpas = array_keys($kpas);
                            $allKpas = $allKpas + $kpas;
                            $kqs = $kqs + $this->db->array_grouping($diagnosticModel->getKeyQuestionsForAssessment($assessment_id, $val, 0, $lang_id, 1, $userKpas, $isLeadAssessorKpa), "kpa_instance_id", "key_question_instance_id");
                            $cqs = $cqs + $this->db->array_grouping($diagnosticModel->getCoreQuestionsForAssessment($assessment_id, $val, 0, $lang_id, 1, $userKpas, $isLeadAssessorKpa), "key_question_instance_id", "core_question_instance_id");
                            $jss = $jss + $this->db->array_grouping($diagnosticModel->getJudgementalStatementsForAssessment($assessment_id, $val, 0, $lang_id, 1, $userKpas, $isLeadAssessorKpa), "core_question_instance_id", "judgement_statement_instance_id");
                        }
                    }
                }
                if ($is_collaborative && (($isLeadAssessor && $isLeadSave == 0) || (!$isLeadAssessor))) {
                    $kpas = $allKpas + $this->db->array_col_to_key($diagnosticModel->getKpasForAssessment($assessment_id, $assessor_id, $singleKpaId, $lang_id, $is_collaborative, $external), "kpa_instance_id");
                    ksort($kpas);
                    $kqs = $kqs + $this->db->array_grouping($diagnosticModel->getKeyQuestionsForAssessment($assessment_id, $assessor_id, $singleKpaId, $lang_id, $external), "kpa_instance_id", "key_question_instance_id");
                    ksort($kqs);
                    $cqs = $cqs + $this->db->array_grouping($diagnosticModel->getCoreQuestionsForAssessment($assessment_id, $assessor_id, $singleKpaId, $lang_id, $external), "key_question_instance_id", "core_question_instance_id");
                    ksort($cqs);
                    $jss = $jss + $this->db->array_grouping($diagnosticModel->getJudgementalStatementsForAssessment($assessment_id, $assessor_id, $singleKpaId, $lang_id, $external), "core_question_instance_id", "judgement_statement_instance_id");
                    ksort($jss);
                } else {
                    $kpas = $allKpas + $this->db->array_col_to_key($diagnosticModel->getKpasForAssessment($assessment_id, $assessor_id, $singleKpaId, $lang_id, 0, 0), "kpa_instance_id");
                    ksort($kpas);
                    $kqs = $kqs + $this->db->array_grouping($diagnosticModel->getKeyQuestionsForAssessment($assessment_id, $assessor_id, $singleKpaId, $lang_id, 0), "kpa_instance_id", "key_question_instance_id");
                    ksort($kqs);
                    $cqs = $cqs + $this->db->array_grouping($diagnosticModel->getCoreQuestionsForAssessment($assessment_id, $assessor_id, $singleKpaId, $lang_id, 0), "key_question_instance_id", "core_question_instance_id");
                    ksort($cqs);

                    $jss = $jss + $this->db->array_grouping($diagnosticModel->getJudgementalStatementsForAssessment($assessment_id, $assessor_id, $singleKpaId, $lang_id, 0), "core_question_instance_id", "judgement_statement_instance_id");
                    ksort($jss);
                }
                $rScheme = $this->db->array_grouping($diagnosticModel->getDiagnosticRatingScheme($assessment ['diagnostic_id']), "type", "order");
                if (count($rScheme ['js']) == 0 || count($rScheme ['kpa']) == 0 || count($rScheme ['sq']) == 0 || count($rScheme ['kq']) == 0) {
                    $this->apiResult ["message"] = "Rating scheme does not exists for this diagnostic";
                    return;
                }
                $rSchemeId = $rScheme ['js'] [1] ['scheme']; // echo 'sch: ';print_r($rScheme['js']);die;
                $akns = $assessment ['role'] == 4 ? $this->db->array_grouping($diagnosticModel->getAssessorKeyNotes($assessment_id), "kpa_instance_id", "id") : array();
                if (OFFLINE_STATUS == TRUE) {
                    $uniqueID = $this->db->createUniqueID('internalAssessment');
                }
                $this->db->start_transaction();
                $success = true;
                $complete = true;
                $kpa_count = 0;
                $noOfComplete = 0;
                $noOfIncomplete = 0;
                $kpaIndex = 0;
                foreach ($kpas as $kpa_id => $kpa) {
                    $kpaJs_ratings = array();
                    $kpaSq_ratings = array();
                    $kpa_count ++;
                    $kq_ratings = array();
                    $kq_count = 0;
                    foreach ($kqs [$kpa_id] as $kq_id => $kq) {
                        $kq_count ++;
                        $cq_ratings = array();
                        $cq_count = 0;
                        foreach ($cqs [$kq_id] as $cq_id => $cq) {
                            $cq_count ++;
                            $js_ratings = array();
                            $js_count = 0;
                            foreach ($jss [$cq_id] as $js_id => $js) {

                                $js_count ++;

                                if (empty($js['score_id'])) { //echo $js['score_id'];
                                }
                                $val = empty($_POST ['data'] ["$kpa_id-$kq_id-$cq_id-$js_id"] ['value']) ? "" : $_POST ['data'] ["$kpa_id-$kq_id-$cq_id-$js_id"] ['value'];
                                $valLevel2 = empty($_POST ['data'] ["$kpa_id-$kq_id-$cq_id-$js_id"] ['value1']) ? "" : $_POST ['data'] ["$kpa_id-$kq_id-$cq_id-$js_id"] ['value1'];
                                $textCond = 0;
                                $textLw = '';
                                $textCo = '';
                                $textInt = '';
                                $textBl = '';
                                if ($diagnostic_type != 1) {
                                    $text = empty($_POST ['data'] ["$kpa_id-$kq_id-$cq_id-$js_id"] ['text']) ? "" : trim($_POST ['data'] ["$kpa_id-$kq_id-$cq_id-$js_id"] ['text']);

                                    if (!empty($js ['score_id'])) {
                                        $textCond = ($text != $js ['evidence_text']) ? 1 : 0;
                                    }
                                } else {
                                    $textLw = empty($_POST ['data'] ["$kpa_id-$kq_id-$cq_id-$js_id"] ['text']['lw']) ? "" : trim($_POST ['data'] ["$kpa_id-$kq_id-$cq_id-$js_id"] ['text']['lw']);
                                    $textCo = empty($_POST ['data'] ["$kpa_id-$kq_id-$cq_id-$js_id"] ['text']['co']) ? "" : trim($_POST ['data'] ["$kpa_id-$kq_id-$cq_id-$js_id"] ['text']['co']);
                                    $textInt = empty($_POST ['data'] ["$kpa_id-$kq_id-$cq_id-$js_id"] ['text']['in']) ? "" : trim($_POST ['data'] ["$kpa_id-$kq_id-$cq_id-$js_id"] ['text']['in']);
                                    $textBl = empty($_POST ['data'] ["$kpa_id-$kq_id-$cq_id-$js_id"] ['text']['bl']) ? "" : trim($_POST ['data'] ["$kpa_id-$kq_id-$cq_id-$js_id"] ['text']['bl']);
                                    $text = '';
                                    if (!empty($js ['score_id']) && ($textLw != $js ['evidence_text_lw'] || $textCo != $js ['evidence_text_co'] || $textInt != $js ['evidence_text_in'] || $textBl != $js ['evidence_text_bl'])) {
                                        $textCond = 1;
                                    }
                                }
                                $files = empty($_POST ['data'] ["$kpa_id-$kq_id-$cq_id-$js_id"] ['files']) ? array() : $_POST ['data'] ["$kpa_id-$kq_id-$cq_id-$js_id"] ['files'];
                                sort($files, SORT_NUMERIC);
                                $existing_files = array_keys(diagnosticModel::decodeFileArray($js ['files']));
                                sort($existing_files, SORT_NUMERIC);
                                $score_id = - 1;
                                $rating_id = 0;
                                $rating_id_level2 = 0;
                                if ($val > 0) {
                                    $js_ratings [] = $val;
                                    array_push($kpaJs_ratings, $val);
                                    $rating_id = $rScheme ['js'] [$val] ['rating_id'];
                                    $noOfComplete ++;
                                } else
                                    $noOfIncomplete ++;

                                if (isset($_POST ['data'] ["$kpa_id-$kq_id-$cq_id-$js_id"] ['value1']) && $valLevel2 > 0) {
                                    array_push($kpaJs_ratings, $valLevel2);
                                    if (empty($val)) {
                                        $rating_id = 0;
                                    }
                                    $rating_id_level2 = $rScheme ['js'] [$valLevel2] ['rating_id'];

                                    $noOfComplete ++;
                                } else if (isset($_POST ['data'] ["$kpa_id-$kq_id-$cq_id-$js_id"] ['value1']))
                                    $noOfIncomplete ++;

                                if (empty($js ['score_id']) && ($val != "" || $valLevel2 != "" || $text != "" || count($files) > 0)) {
                                    $score_id = $diagnosticModel->insertJudgementStatementScore($js_id, $assessment_id, $assessor_id, $added_by, $rating_id, $text, $textLw, $textCo, $textInt, $textBl, $rating_id_level2);
                                    if (OFFLINE_STATUS == TRUE) {
                                        // start--> call function for save history for insert internal rating in history table on 21-03-2016 by Mohit Kumar
                                        $action_insert_judgement_statement_json = json_encode(array(
                                            'judgement_statement_instance_id' => $js_id,
                                            'assessment_id' => $assessment_id,
                                            "assessor_id" => $assessor_id,
                                            'added_by' => $added_by,
                                            'rating_id' => $rating_id,
                                            'evidence_text' => $text,
                                            'isFinal' => 1,
                                            'date_added' => date("Y-m-d H:i:s")
                                        ));
                                        $this->db->saveHistoryData($score_id, 'f_score', $uniqueID, 'internalAssessmentJudgementStatementInsert', $assessment_id, $assessor_id, $action_insert_judgement_statement_json, 0, date('Y-m-d H:i:s'));
                                        // end--> call function for save history for insert internal rating in history table on 03-03-2016 by Mohit Kumar
                                    }
                                } else if (!empty($js ['score_id']) && ($val != $js ['numericRating'] || $valLevel2 != $js ['level2rating'] || $textCond == 1 || $existing_files != $files)) {
                                    $score_id = $diagnosticModel->updateJudgementStatementScore($js_id, $assessment_id, $assessor_id, $added_by, $rating_id, $text, $textLw, $textCo, $textInt, $textBl, $rating_id_level2);
                                    if (OFFLINE_STATUS == true) {
                                        // start--> call function for save history for update internal rating in history table on 21-03-2016 by Mohit Kumar
                                        $action_update_judgement_statement_json = json_encode(array(
                                            'judgement_statement_instance_id' => $js_id,
                                            'assessment_id' => $assessment_id,
                                            "assessor_id" => $assessor_id,
                                            'added_by' => $added_by,
                                            'rating_id' => $rating_id,
                                            'evidence_text' => $text
                                        ));
                                        $this->db->saveHistoryData($score_id, 'f_score', $uniqueID, 'internalAssessmentJudgementStatementUpdate', $assessment_id, $assessor_id, $action_update_judgement_statement_json, 0, date('Y-m-d H:i:s'));
                                        $this->db->saveHistoryData($score_id, 'f_score', $uniqueID, 'internalAssessmentJudgementStatementUpdateInsert', $assessment_id, $assessor_id, $action_update_judgement_statement_json, 0, date('Y-m-d H:i:s'));
                                        // end--> call function for save history for update internal rating in history table on 03-03-2016 by Mohit Kumar
                                    }
                                } // else neither we need to insert new nor we need to update existing score

                                if ($score_id == false) {

                                    $success = false;
                                } else if ($score_id > 0) {

                                    foreach ($files as $file_id)
                                        if (!$diagnosticModel->linkFileToScore($score_id, $file_id)) {

                                            $success = false;
                                        } else {
                                            if (OFFLINE_STATUS == TRUE) {
                                                $linkFileToScoreId = $this->db->get_last_insert_id();
                                                // start--> call function for save history for insert link file to score in history table on 21-03-2016 by Mohit Kumar
                                                $action_insert_score_file_json = json_encode(array(
                                                    'score_id' => $score_id,
                                                    'file_id' => $file_id
                                                ));
                                                $this->db->saveHistoryData($linkFileToScoreId, 'h_score_file', $uniqueID, 'internalAssessmentScoreFileInsert', $score_id, $file_id, $action_insert_score_file_json, 0, date('Y-m-d H:i:s'));
                                                // end--> call function for save history for insert link file to score in history table on 03-03-2016 by Mohit Kumar
                                            }
                                        }
                                }
                            }
                            if ($js_count == count($js_ratings)) {
                                $js_res = diagnosticModel::calculateStatementResult($js_ratings, $rSchemeId, 4);
                                $cq_ratings [] = $js_res;
                                $rating_id = $rScheme ['sq'] [$js_res] ['rating_id'];
                                array_push($kpaSq_ratings, $js_res);

                                if (($cq ['score_id'] > 0 && !$diagnosticModel->updateCoreQuestionScore($cq_id, $assessment_id, $assessor_id, $rating_id)) || (empty($cq ['score_id']) && !$diagnosticModel->insertCoreQuestionScore($cq_id, $assessment_id, $assessor_id, $rating_id))) {

                                    $success = false;
                                } else {
                                    if (OFFLINE_STATUS == TRUE) {
                                        // start--> call function for save history for update and insert core question score in history table on 21-03-2016 by Mohit Kumar
                                        $action_core_question_json = json_encode(array(
                                            'd_rating_rating_id' => $rating_id,
                                            'core_question_instance_id' => $cq_id,
                                            'assessment_id' => $assessment_id,
                                            'assessor_id' => $assessor_id
                                        ));
                                        if ($cq ['score_id'] > 0) {
                                            $this->db->saveHistoryData($cq_id, 'h_cq_score', $uniqueID, 'internalAssessmentCoreQuestionUpdate', $assessment_id, $assessor_id, $action_core_question_json, 0, date('Y-m-d H:i:s'));
                                        } else if (empty($cq ['score_id'])) {
                                            $coreQuestionId = $this->db->get_last_insert_id();
                                            $this->db->saveHistoryData($coreQuestionId, 'h_cq_score', $uniqueID, 'internalAssessmentCoreQuestionInsert', $assessment_id, $assessor_id, $action_core_question_json, 0, date('Y-m-d H:i:s'));
                                        }
                                        // end--> call function for save history for update and insert core qiestion score in history table on 03-03-2016 by Mohit Kumar
                                    }
                                }
                            } else if ($cq ['score_id'] > 0) {
                                $diagnosticModel->deleteCoreQuestionScore($cq_id, $assessment_id, $assessor_id);
                                if (OFFLINE_STATUS == TRUE) {
                                    // start--> call function for save history for delete core question score in history table on 21-03-2016 by Mohit Kumar
                                    $action_core_question_json = json_encode(array(
                                        "core_question_instance_id" => $cq_id,
                                        "assessment_id" => $assessment_id,
                                        "assessor_id" => $assessor_id
                                    ));
                                    $this->db->saveHistoryData($cq_id, 'h_cq_score', $uniqueID, 'internalAssessmentCoreQuestionDelete', $assessment_id, $assessor_id, $action_core_question_json, 0, date('Y-m-d H:i:s'));
                                    // end--> call function for save history for delete core qiestion score in history table on 03-03-2016 by Mohit Kumar
                                }
                            }
                        }
                        if ($cq_count == count($cq_ratings)) {
                            $cq_res = diagnosticModel::calculateStatementResult($cq_ratings, $rSchemeId, 3);
                            $kq_ratings [] = $cq_res;
                            $rating_id = $rScheme ['sq'] [$cq_res] ['rating_id'];
                            if (($kq ['score_id'] > 0 && !$diagnosticModel->updateKeyQuestionScore($kq_id, $assessment_id, $assessor_id, $rating_id)) || (empty($kq ['score_id']) && !$diagnosticModel->insertKeyQuestionScore($kq_id, $assessment_id, $assessor_id, $rating_id))) {
                                $success = false;
                            } else {
                                if (OFFLINE_STATUS == TRUE) {
                                    // start--> call function for save history for update and insert core question score in history table on 21-03-2016 by Mohit Kumar
                                    $action_key_question_json = json_encode(array(
                                        'd_rating_rating_id' => $rating_id,
                                        'key_question_instance_id' => $kq_id,
                                        'assessment_id' => $assessment_id,
                                        'assessor_id' => $assessor_id
                                    ));
                                    if ($kq ['score_id'] > 0) {
                                        $this->db->saveHistoryData($kq_id, 'h_kq_instance_score', $uniqueID, 'internalAssessmentKeyQuestionUpdate', $assessment_id, $assessor_id, $action_key_question_json, 0, date('Y-m-d H:i:s'));
                                    } else if (empty($kq ['score_id'])) {
                                        $keyQuestionId = $this->db->get_last_insert_id();
                                        $this->db->saveHistoryData($keyQuestionId, 'h_kq_instance_score', $uniqueID, 'internalAssessmentKeyQuestionInsert', $assessment_id, $assessor_id, $action_key_question_json, 0, date('Y-m-d H:i:s'));
                                    }
                                    // end--> call function for save history for update and insert core qiestion score in history table on 03-03-2016 by Mohit Kumar
                                }
                            }
                        } else if ($kq ['score_id'] > 0) {
                            $diagnosticModel->deleteKeyQuestionScore($kq_id, $assessment_id, $assessor_id);
                            if (OFFLINE_STATUS == TRUE) {
                                // start--> call function for save history for delete key question score in history table on 21-03-2016 by Mohit Kumar
                                $action_key_question_json = json_encode(array(
                                    "key_question_instance_id" => $kq_id,
                                    "assessment_id" => $assessment_id,
                                    "assessor_id" => $assessor_id
                                ));
                                $this->db->saveHistoryData($kq_id, 'h_kq_instance_score', $uniqueID, 'internalAssessmentKeyQuestionDelete', $assessment_id, $assessor_id, $action_key_question_json, 0, date('Y-m-d H:i:s'));
                                // end--> call function for save history for delete key qiestion score in history table on 03-03-2016 by Mohit Kumar
                            }
                        }
                    }
                    if ($kq_count == count($kq_ratings)) {
                        if ($diagnostic_type == 1) {
                            $kq_res = diagnosticModel::calculateStatementResultKpa($kq_ratings, $rSchemeId, 2, $kpaJs_ratings, $kpaSq_ratings);
                        } else {
                            $kq_res = diagnosticModel::calculateStatementResult($kq_ratings, $rSchemeId, 2, $kpaJs_ratings, $kpaSq_ratings);
                        }
                        $kpa_ratings [] = $kq_res;
                        $rating_id = $rScheme ['kpa'] [$kq_res] ['rating_id'];
                        if (($kpa ['score_id'] > 0 && !$diagnosticModel->updateKpaScore($kpa_id, $assessment_id, $assessor_id, $rating_id)) || (empty($kpa ['score_id']) && !$diagnosticModel->insertKpaScore($kpa_id, $assessment_id, $assessor_id, $rating_id))) {

                            $success = false;
                        } else {
                            if (OFFLINE_STATUS == TRUE) {
                                // start--> call function for save history for update and insert kpa score in history table on 21-03-2016 by Mohit Kumar
                                $action_kpa_json = json_encode(array(
                                    'd_rating_rating_id' => $rating_id,
                                    'kpa_instance_id' => $kpa_id,
                                    'assessment_id' => $assessment_id,
                                    'assessor_id' => $assessor_id
                                ));
                                if ($kpa ['score_id'] > 0) {
                                    $this->db->saveHistoryData($kpa_id, 'h_kpa_instance_score', $uniqueID, 'internalAssessmentKpaUpdate', $assessment_id, $assessor_id, $action_kpa_json, 0, date('Y-m-d H:i:s'));
                                } else if (empty($kpa ['score_id'])) {
                                    $kpaScoreId = $this->db->get_last_insert_id();
                                    $this->db->saveHistoryData($kpaScoreId, 'h_kpa_instance_score', $uniqueID, 'internalAssessmentKpaInsert', $assessment_id, $assessor_id, $action_kpa_json, 0, date('Y-m-d H:i:s'));
                                }
                                // end--> call function for save history for update and insert kpa score in history table on 03-03-2016 by Mohit Kumar
                            }
                        }
                    } else if ($kpa ['score_id'] > 0) {

                        $diagnosticModel->deleteKpaScore($kpa_id, $assessment_id, $assessor_id);
                        if (OFFLINE_STATUS == TRUE) {
                            // start--> call function for save history for delete key question score in history table on 21-03-2016 by Mohit Kumar
                            $action_kpa_json = json_encode(array(
                                "kpa_instance_id" => $kpa_id,
                                "assessment_id" => $assessment_id,
                                "assessor_id" => $assessor_id
                            ));
                            $this->db->saveHistoryData($kpa_id, 'h_kpa_instance_score', $uniqueID, 'internalAssessmentKpaDelete', $assessment_id, $assessor_id, $action_kpa_json, 0, date('Y-m-d H:i:s'));
                            // end--> call function for save history for delete key qiestion score in history table on 03-03-2016 by Mohit Kumar
                        }
                    }
                    if ($assessment ['role'] == 4) {
                        $isKNComplete = true;
                        $akns = isset($_POST ['aknotes']) ? $_POST ['aknotes'] : array();
                        if (isset($akns) && count($akns)) {
                            if ($is_collaborative == 1) {
                                $isKNComplete = false;
                                if (isset($akns[$kpaIndex]) && $akns[$kpaIndex] == 1) {
                                    $isKNComplete = true;
                                }
                            } else {
                                foreach ($akns as $akn) :
                                    if ($akn == 0)
                                        $isKNComplete = false;
                                endforeach
                                ;
                            }
                        }
                        if ($isKNComplete)
                            $noOfComplete ++;
                        else
                            $noOfIncomplete ++;

                        $kpaIndex++;

                        if (in_array("edit_all_submitted_assessments", $this->user ['capabilities']))
                            if (empty($_POST ['approveKeyNotes'])) {
                                $diagnosticModel->updateAssessorKeyNotesStatus($assessment_id, 0);
                                if (OFFLINE_STATUS == TRUE) {
                                    // start--> call function for save history for update assessor key status in d_assessment in history table on 21-03-2016 by Mohit Kumar
                                    $action_assessor_key_note_json = json_encode(array(
                                        "assessment_id" => $assessment_id,
                                        "isAssessorKeyNotesApproved" => 0
                                    ));
                                    $this->db->saveHistoryData($assessment_id, 'd_assessment', $uniqueID, 'internalAssessmentDAssessmentStatusUpdate', $assessment_id, 0, $action_assessor_key_note_json, 0, date('Y-m-d H:i:s'));
                                    // end--> call function for save history for update assessor key status in d_assessment in history table on 03-03-2016 by Mohit Kumar
                                }
                            } else {
                                $diagnosticModel->updateAssessorKeyNotesStatus($assessment_id, 1);
                                if (OFFLINE_STATUS == TRUE) {
                                    // start--> call function for save history for update assessor key status in d_assessment in history table on 21-03-2016 by Mohit Kumar
                                    $action_assessor_key_note_json = json_encode(array(
                                        "assessment_id" => $assessment_id,
                                        "isAssessorKeyNotesApproved" => 1
                                    ));
                                    $this->db->saveHistoryData($assessment_id, 'd_assessment', $uniqueID, 'internalAssessmentDAssessmentStatusUpdate', $assessment_id, 1, $action_assessor_key_note_json, 0, date('Y-m-d H:i:s'));
                                    // end--> call function for save history for update assessor key status in d_assessment in history table on 03-03-2016 by Mohit Kumar
                                }
                            }
                    }
                }
                $success = true;
                $completedPerc = 0;
                $total = $noOfIncomplete + $noOfComplete;
                $avgPercntg = 0;
                if ($total > 0 && $success) {
                    if ($noOfIncomplete == 0 && !empty($_POST ['submit'])) {
                        $completedPerc = 100;
                        $isForSubmit = 1;
                        $aid = 0;
                        if ($is_collaborative == 1 && $isLeadAssessor == 0) {
                            $isForSubmit = 0;
                            $aid = 1;
                        }
                        if ($isForSubmit)
                            $aid = $diagnosticModel->updateAssessmentPercentageAndStatus($assessment_id, $assessor_id, $completedPerc, 1);
                        if (!$aid) {

                            $success = false;
                        } else {
                            if (OFFLINE_STATUS == TRUE) {
                                // start--> call function for save history for update assessor key status in d_assessment in history table on 21-03-2016 by Mohit Kumar
                                $action_assessor_key_note_json = json_encode(array(
                                    "assessment_id" => $assessment_id,
                                    "isAssessorKeyNotesApproved" => 1
                                ));
                                $this->db->saveHistoryData($assessment_id, 'd_assessment', $uniqueID, 'internalAssessmentDAssessmentStatusUpdate', $assessment_id, 1, $action_assessor_key_note_json, 0, date('Y-m-d H:i:s'));
                                // end--> call function for save history for update assessor key status in d_assessment in history table on 03-03-2016 by Mohit Kumar
                                $success = true;
                            }
                            if ($is_collaborative) {
                                $diagnosticModel->updateAssessmentPercentage($assessment_id, $assessor_id, $completedPerc, $is_collaborative, $external, 1, $isLeadAssessor);
                                if ($isLeadAssessor)
                                    $diagnosticModel->updateAssessmentPercentage($assessment_id, $assessor_id, $completedPerc, 0, 0, 1, 0);

                                if ($is_collaborative == 1) {
                                    $avgPercntg = $this->calculatePercentage($assessment_id);
                                }
                                if ($avgPercntg)
                                    $diagnosticModel->updateAvgAssessmentPercentage($assessment_id, $avgPercntg);
                            }
                            if ($assessment ['role'] == 4) {
                                $notifications = $assessmentModel->getNotificationUsers($assessment_id, 1);
                                if (!empty($notifications)) {
                                    $assessmentModel->createNotificationQueue($notifications, $assessment['aqs_edate']);
                                }
                            }
                            if ($assessment ['role'] == 4) {
                                $notifications = $assessmentModel->getNotificationUsers($assessment_id, 2);
                                if (!empty($notifications)) {
                                    $assessmentModel->createNotificationQueue($notifications, $assessment['aqs_edate']);
                                }
                            }
                        }
                    } else if ($assessment ["status"] == 1 && $noOfIncomplete > 0) {
                        $success = false;
                        $this->apiResult ["message"] = "Some fields are empty.";

                        return false;
                    } else {
                        $completedPerc = round((100 * $noOfComplete) / $total, 2);
                        if (!$diagnosticModel->updateAssessmentPercentage($assessment_id, $assessor_id, $completedPerc, $is_collaborative, $external, '', '', $isLeadSave)) {
                            $success = false;
                        } else {
                            if (OFFLINE_STATUS == TRUE) {
                                // start--> call function for save history for update assessment percentage in d_assessment in history table on 21-03-2016 by Mohit Kumar
                                $action_assessment_percentage_json = json_encode(array(
                                    "percComplete" => $completedPerc,
                                    "assessment_id" => $assessment_id,
                                    "user_id" => $assessor_id
                                ));
                                $this->db->saveHistoryData($assessment_id, 'd_assessment', $uniqueID, 'internalAssessmentPercentageUpdate', $assessment_id, $assessor_id, $action_assessment_percentage_json, 0, date('Y-m-d H:i:s'));
                                // end--> call function for save history for update assessor key status in d_assessment in history table on 03-03-2016 by Mohit Kumar
                                $success = true;
                            }
                            if ($is_collaborative == 1) {
                                $avgPercntg = $this->calculatePercentage($assessment_id);
                            }
                            if ($is_collaborative && $avgPercntg) {
                                $diagnosticModel->updateAvgAssessmentPercentage($assessment_id, $avgPercntg);
                            }
                        }
                    }
                } else {
                    $success = false;
                }
                $leadAssessorStatus = 0;
                if ($isLeadAssessor && empty($isLeadAssessorKpa)) {
                    $leadAssessorStatus = 1;
                }
                if ($success && $this->db->commit()) {
                    $this->apiResult ["status"] = 1;
                    $this->apiResult ["completedPerc"] = $completedPerc;
                    $this->apiResult ["completedStatus"] = $isRevCompleteNtSubmitted;
                    $this->apiResult ["leadAssessorStatus"] = $leadAssessorStatus;
                    $this->apiResult ["submit"] = $assessment ["status"];
                    $this->apiResult ["message"] = "Successfully saved";
                    if ($is_collaborative == 1 && (in_array(1, $this->user['role_ids']) || in_array(2, $this->user['role_ids']))) {
                        $internalAssessorId = $diagnosticModel->getInternalAssessor($assessment_id);
                        $diagnosticModel->deleteInternalJudgementStatementScore($assessment_id, $internalAssessorId);
                    }
                    if ($assessment['iscollebrative']) {
                        $submit = isset($_POST ['submit']) ? $_POST ['submit'] : 0;
                        if ($isLeadAssessor == 1 && $submit == 1 && !in_array(1, $this->user['role_ids']) && !in_array(2, $this->user['role_ids']))
                            $this->saveInternalAssessorRatings($assessment_id, $completedPerc, $submit, $lang_id);
                        else if ((in_array(1, $this->user['role_ids']) || in_array(2, $this->user['role_ids']))) {
                            $this->saveInternalAssessorRatings($assessment_id, $completedPerc, $submit, $lang_id);
                        }
                    }
                } else {
                    $this->db->rollback();
                    $this->apiResult ["message"] = "Error occurred, please check the error logs.\n";
                }
            }
        } else {
            $this->apiResult ["message"] = "Wrong review id or reviewer id\n";
        }
    }

    //@Purpose:publish report data for the assessment report
    function publishReportAction() {
        $diagnosticModel = new diagnosticModel ();
        if (!in_array("view_all_assessments", $this->user ['capabilities'])) {
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        } else if (empty($_POST ['ass_or_group_ass_id'])) {

            $this->apiResult ["message"] = "Review id cannot be empty\n";
        } else if (empty($_POST ['assessment_type_id'])) {

            $this->apiResult ["message"] = "Review type id cannot be empty\n";
        } else if (empty($_POST ['years']) && empty($_POST ['months'])) {

            $this->apiResult ["message"] = "Year and month both can't be empty\n";
        } else if ($assessment = ($_POST ['assessment_type_id'] == 1 || $_POST ['assessment_type_id'] == 5) ? $diagnosticModel->getAssessmentById($_POST ['ass_or_group_ass_id']) : $diagnosticModel->getTeacherAssessmentReports($_POST ['ass_or_group_ass_id'])) {
            $assessment_id = ($_POST ['assessment_type_id'] == 1 || $_POST ['assessment_type_id'] == 5) ? $_POST ['ass_or_group_ass_id'] : 0;
            $group_assessment_id = ($_POST ['assessment_type_id'] == 1 || $_POST ['assessment_type_id'] == 5 ) ? 0 : $_POST ['ass_or_group_ass_id'];
            $reports = $assessment_id > 0 ? $diagnosticModel->getReportsByAssessmentId($_POST ['ass_or_group_ass_id'], false) : array();
            $years = empty($_POST ['years']) ? 0 : $_POST ['years'];
            $months = empty($_POST ['months']) ? 0 : $_POST ['months'];
            $isPublished = $group_assessment_id > 0 ? (isset($reports [0] ['report_data'] [0]) ? $reports [0] ['report_data'] [0] ['isPublished'] : 0) : $reports [0] ['isPublished'];
            if ($assessment ['aqs_status'] != 1 && $assessment['assessment_type_id'] != 4) {
                $this->apiResult ["message"] = "School profile still not submitted\n";
            } else if ($assessment_id > 0 && $assessment ['statusByRole'] [4] != 1) {
                $this->apiResult ["message"] = "External review form still not submitted\n";
            } else if ($group_assessment_id > 0 && ($assessment ['allStatusFilled'] != 1 || $assessment ['allTchrInfoFilled'] != 1)) {
                if ($_POST ['assessment_type_id'] == 4) {
                    $this->apiResult ["message"] = "Either all student info form still not submitted or all external review forms not submitted\n";
                } else {
                    $this->apiResult ["message"] = "Either all teacher info form still not submitted or all external review forms not submitted\n";
                }
            } else if (count($reports) == 0) {
                $this->apiResult ["message"] = "Reports does not exists\n";
            } else if ($group_assessment_id > 0 ? (isset($reports [0] ['report_data'] [0]) ? $reports [0] ['report_data'] [0] ['isPublished'] : 0) : $reports [0] ['isPublished']) {
                $this->apiResult ["message"] = "Reports already published\n";
            } else {

                if (OFFLINE_STATUS == TRUE) {
                    $uniqueID = $this->db->createUniqueID('publishReport');
                }
                $aids = array();
                $grs = array();
                $reportsType = null;
                foreach ($reports as $report) {
                    $aid = $group_assessment_id > 0 ? $report ['assessment_id'] : $assessment_id;
                    $aids [$aid] = $aid;
                    if ($assessment['school_aqs_pref_end_date'] == "" || $assessment['school_aqs_pref_end_date'] == "0000-00-00") {
                        $valid_start_date = $assessment['create_date'];
                    } else {
                        $valid_start_date = $assessment['school_aqs_pref_end_date'];
                    }
                    if ($report ['isGenerated'] == 1) {
                        $diagnosticModel->updateAssessmentReport($aid, $report ['report_id'], $years, $months, $valid_start_date, 1);
                        if ($_POST ['assessment_type_id'] == 1 && OFFLINE_STATUS == TRUE) {
                            // save publish report history on local server on 01-04-2016 By Mohit Kumar
                            $totalMonths = $months + ($years * 12);
                            $data = json_encode(array(
                                "valid_until" => date("Y-m-d H:i:s", strtotime("+$totalMonths month", strtotime($valid_start_date))),
                                'publishDate' => date("Y-m-d H:i:s"),
                                'isPublished' => 1
                            ));
                            $this->db->saveHistoryData($aid, 'h_assessment_report', $uniqueID, 'publishReportUpdate', $aid, $report ['report_id'], $data, 0, date('Y-m-d H:i:s'));
                            // end---> call function for saving add school client data on 04-03-2016 by Mohit Kumar
                        }
                    } else {
                        $diagnosticModel->insertAssessmentReport($aid, $report ['report_id'], $years, $months, $valid_start_date, 1);
                        $assessmentReportId = $this->db->get_last_insert_id();
                        if ($_POST ['assessment_type_id'] == 1 && OFFLINE_STATUS == TRUE) {
                            // save publish report history on local server on 01-04-2016 By Mohit Kumar
                            $totalMonths = $months + ($years * 12);
                            $data = json_encode(array(
                                "valid_until" => date("Y-m-d H:i:s", strtotime("+$totalMonths month", strtotime($valid_start_date))),
                                'publishDate' => date("Y-m-d H:i:s"),
                                'isPublished' => 1,
                                'report_id' => $report ['report_id']
                            ));
                            $this->db->saveHistoryData($assessmentReportId, 'h_assessment_report', $uniqueID, 'publishReportInsert', $aid, $report ['report_id'], $data, 0, date('Y-m-d H:i:s'));
                            // end---> call function for saving add school client data on 04-03-2016 by Mohit Kumar
                        }
                    }
                }
                foreach ($aids as $aid) {
                    $diagnosticModel->updateAssessorKeyNotesStatus($aid, 1);
                    if (OFFLINE_STATUS == TRUE) {
                        // save publish report history on local server on 01-04-2016 By Mohit Kumar
                        $data = json_encode(array(
                            "isAssessorKeyNotesApproved" => 1
                        ));
                        $this->db->saveHistoryData($aid, 'd_assessment', $uniqueID, 'publishReportAssessorKeyNotesStatusUpdate', $aid, 1, $data, 0, date('Y-m-d H:i:s'));
                        // end---> call function for saving add school client data on 04-03-2016 by Mohit Kumar
                    }
                }

                $this->apiResult ["status"] = 1;
            }

            if ($this->apiResult ["status"] == 1) {

                $reports = $assessment_id > 0 ? $diagnosticModel->getReportsByAssessmentId($assessment_id, false) : array();
                if (isset($assessment['assessment_type_id']) && $assessment['assessment_type_id'] == 4) {
                    $reportsType = $diagnosticModel->getReportsType(4);
                } else {
                    $reportsType = $diagnosticModel->getReportsType(2);
                }

                ob_start();
                $reportsIndividual = array_filter($reports, function($var) {
                    if ($var['report_id'] == 5 || $var['report_id'] == 9)
                        return array($var['assessment_id'] => $var['user_names'][0]);
                });
                $reportsSingleTeacher = array_filter($reports, function($var) {
                    if ($var['report_id'] == 7 || $var['report_id'] == 10)
                        return $var;
                });
                $groupAssessmentId = $group_assessment_id;
                $diagnosticsForGroup = (!empty($grs[0]) ? $grs[0]['diagnostic_ids'] : 0);
                $res = $group_assessment_id > 0 ? 0 : $diagnosticModel->getNumberOfKpasDiagnostic($assessment['diagnostic_id']);
                $numKpas = $group_assessment_id > 1 ? 0 : $res['num'];
                if (isset($assessment['diagnostic_id']) && $assessment['diagnostic_id'] != '')
                    $diagnosticsLanguage = $diagnosticModel->getDiagnosticLanguages($assessment['diagnostic_id']);
                $assessor_id = isset($assessment['user_ids'][1]) ? $assessment['user_ids'][1] : '';
                include (ROOT . 'application' . DS . 'views' . DS . "assessment" . DS . 'reportlist.php');
                $this->apiResult ["content"] = ob_get_contents();
                ob_end_clean();
            }
        } else {
            $this->apiResult ["message"] = "Wrong review id\n";
        }
    }

    //@Purpose:postponed action plan
    function getActivityPostponedAction() {
        $actionModel = new actionModel ();
        if (empty($_POST ['id'])) {
            $this->apiResult ["message"] = "ID is empty.\n";
        } else if ($postponed = $actionModel->getActivityPostponedAction2($_POST ['id'])) {
            if (count($postponed)) {
                $this->apiResult ["content"] = '';
                foreach ($postponed as $a) {
                    $this->apiResult ["content"] .= kpajsHelper::getActionActivityViewRow($a);
                }
                $this->apiResult ["id"] = $_POST ['id'];
                $this->apiResult ["status"] = 1;
            } else {
                $this->apiResult ["message"] = "No review found.\n";
            }
        } else {
            $this->apiResult ["message"] = "Invalid  Activity ID.\n";
        }
    }

    /* 
    * @Purpose : Function to get network report data
    */
    function getNetworkReportDataAction() {
        if (!(in_array("view_all_assessments", $this->user ['capabilities']))) {
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        } else if (empty($_POST ['network_report_id'])) {
            $this->apiResult ["message"] = "Network Report Id cannot be empty\n";
        } else {
            $customreportModel = new customreportModel ();
            $networkReportId = $_POST ['network_report_id'];
            $networkReportData = $customreportModel->getNetworkReportData($networkReportId);
            $this->apiResult ["data"] = $networkReportData;
            $this->apiResult ["status"] = 1;
        }
    }

    // @Purpose:function for updating the user profile on 27-05-2016 by Mohit Kumar
    function updateUserIntroAssAction() {
        $uniqueID = "";
        $user_id = $_POST['user_id'];
        $finalArray = array();
        if (isset($_POST['key_behaviour'])) {
            $questiion_id = array_shift($_POST['key_behaviour']);
            $finalArray['key_behaviour'] = array('question_id' => $questiion_id, 'answer_id' => $_POST['key_behaviour']);
        }
        if (isset($_POST['leader_statement'])) {
            $finalArray['leader_statement'] = isset($_POST['leader_statement'][1]) ? trim($_POST['leader_statement'][1]) : '';
        }
        if (isset($_POST['statement'])) {
            $finalArray['statement'] = isset($_POST['statement'][1]) ? trim($_POST['statement'][1]) : '';
        }

        if (isset($_POST['rating_text'])) {
            $finalArray['rating_text'] = isset($_POST['rating_text'][1]) ? trim($_POST['rating_text'][1]) : '';
        }
        if (isset($_POST['classroom_observation'])) {
            $questiion_id = array_shift($_POST['classroom_observation']);
            $finalArray['classroom_observation'] = array('question_id' => $questiion_id, 'answer_id' => $_POST['classroom_observation']);
        }
        if (isset($_POST['stakeholder_text'])) {

            $questiion_id = array_shift($_POST['stakeholder_text']);
            $finalArray['stakeholder_text'] = array('question_id' => $questiion_id, 'answer_id' => $_POST['stakeholder_text']);
        }
        if (isset($_POST['key_performance'])) {
            $questiion_id = array_shift($_POST['key_performance']);
            $finalArray['key_performance'] = array('question_id' => $questiion_id, 'answer_id' => $_POST['key_performance']);
        }
        if (isset($_POST['key_performance_text'])) {
            $finalArray['key_performance_text'] = isset($_POST['key_performance_text'][1]) ? trim($_POST['key_performance_text'][1]) : '';
        }
        if (isset($_POST['goal'])) {

            $finalArray['goal'] = isset($_POST['goal'][1]) ? trim($_POST['goal'][1]) : '';
        }
        if (isset($_POST['school_rating'])) {
            $finalArray['school_rating'] = isset($_POST['school_rating'][1]) ? trim($_POST['school_rating'][1]) : '';
        }
        if (isset($_POST['rating_text'])) {
            $questiion_id = array_shift($_POST['rating_text']);
            $finalArray['rating_text'] = array('question_id' => $questiion_id, 'answer_id' => $_POST['rating_text']);
        }
        if (isset($_POST['school_rating_txt'])) {

            $finalArray['school_rating_txt'] = $_POST['school_rating_txt'];
        }
        $postData = array();
        $score = $this->userModel->calculateScore($finalArray);
        if ((!empty($_POST['is_submit']) && $_POST['is_submit'] == 1) || ($_POST['submit_value'] == 1)) {
            $postData = $this->userModel->validateIntroAss($finalArray);
            $errors = !empty($postData ['errors']) ? $postData ['errors'] : array();

            if (isset($postData['errors']) && count($postData['errors'])) {
                $this->apiResult ["message"] = "Data is either incorrect or blank.\n";
                $this->apiResult ["errors"] = $errors;
            } else {
                $postData['values']['score'] = $score;
                $introductoryAssessment = $this->userModel->saveIntroductoryAssessment($postData['values'], $user_id, 1);
            }
        } else {
            $finalArray['score'] = $score;
            $introductoryAssessment = $this->userModel->saveIntroductoryAssessment($finalArray, $user_id);
        }
        if (isset($introductoryAssessment) && $introductoryAssessment) {
            $this->apiResult ["status"] = 1;
            if (isset($score))
                $this->apiResult ["score"] = $score;
            $this->apiResult ["message"] = "User Profile successfully updated";
        } else {
            $this->apiResult ["message"] = "Error occurred, please check the error logs.\n";
        }
    }

    // @Purpose:function for updating the user profile on 27-05-2016 by Mohit Kumar
    function updateUserAssessmentAction() {
        $uniqueID = "";
        if (empty($_POST ['first_name']) && empty($_POST ['last_name'])) {
            $this->apiResult ["message"] = "First and Last Name cannot be empty\n";
        } else if (empty($_POST ['id'])) {
            $this->apiResult ["message"] = "User ID missing\n";
        } else if (empty($_POST ['email'])) {
            $this->apiResult ["message"] = "Email cannot be empty\n";
        } else {
            
        }
    }

    // @Purpose:FUNCTION for getting for getting new alert counts
    public function getAlertCountAction() {
        $count = $this->db->getAlertCount();
        $this->apiResult ["status"] = 1;
        $this->apiResult ["totalCount"] = $count ['assessor_count'] + $count ['review_count'];
        $this->apiResult ["assessorCount"] = $count ['assessor_count'];
        $this->apiResult ["reviewCount"] = $count ['review_count'];
    }

    // @Purpose:new function for creating school review according to tap admin functionality on 06-06-2016 by Mohit Kumar
    function createSchoolAssessmentNewAction() {
        $diagnostic_type = isset($_POST['diagnostic_type']) ? $_POST['diagnostic_type'] : 0;
        if (!in_array("create_assessment", $this->user ['capabilities'])) {
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        } else if (isset($_POST ['review_type']) && $_POST ['review_type'] == '') {
            $this->apiResult ["message"] = "Review type cannot be empty.\n";
        } else if (empty($_POST ['client_id'])) {
            $this->apiResult ["message"] = "School id cannot be empty.\n";
        } else if (empty($_POST ['internal_assessor_id'])) {
            $this->apiResult ["message"] = "Internal reviewer cannot be empty.\n";
        } else if (empty($_POST ['diagnostic_id'])) {
            $this->apiResult ["message"] = "Diagnostic cannot be empty.\n";
        } else if (empty($_POST ['tier_id'])) {
            $this->apiResult ["message"] = "Tier cannot be empty.\n";
        } else if (empty($_POST ['award_scheme_name'])) {
            $this->apiResult ["message"] = "Award Scheme cannot be empty.\n";
        } else if (empty($_POST ['aqs_round'])) {
            $this->apiResult ["message"] = "AQS Round cannot be empty.\n";
        } else if (empty($_POST['aqs']['school_aqs_pref_start_date'])) {
            $this->apiResult ["message"] = "AQS start date cannot be empty.\n";
        } else if (empty($_POST['aqs']['school_aqs_pref_end_date'])) {
            $this->apiResult ["message"] = "AQS end date cannot be empty.\n";
        } else if (!empty($_POST ['external_assessor_id']) && !empty($_POST ['internal_assessor_id']) && $_POST ['internal_assessor_id'] == $_POST ['external_assessor_id']) {
            $this->apiResult ["message"] = "Internal and External reviewer cannot be same.\n";
        } else if (isset($_POST ['externalReviewTeam'] ['role']) && empty($_POST ['externalReviewTeam'] ['role']) && count($_POST ['externalReviewTeam'] ['member']) != count($_POST ['externalReviewTeam'] ['role'])) {
            $this->apiResult ["message"] = "External reviewer role cannot be empty.\n";
        } else if (isset($_POST ['externalReviewTeam'] ['member']) && empty($_POST ['externalReviewTeam'] ['member']) && count($_POST ['externalReviewTeam'] ['member']) != count($_POST ['externalReviewTeam'] ['role'])) {
            $this->apiResult ["message"] = "External reviewer member cannot be empty.\n";
        } else if (in_array("assign_external_review_team", $this->user ['capabilities']) && empty($_POST ['external_assessor_id'])) {
            $this->apiResult ["message"] = "You are not authorized to update external review team.\n";
        } else if (!in_array("assign_external_review_team", $this->user ['capabilities']) && !empty($_POST ['externalReviewTeam'] ['clientId'])) {
            $this->apiResult ["message"] = "You are not authorized to update external review team.\n";
        } else if (isset($_POST ['facilitatorReviewTeam'] ['member']) && count(array_unique($_POST ['facilitatorReviewTeam'] ['member'])) < count($_POST ['facilitatorReviewTeam'] ['member'])) {
            $this->apiResult ["message"] = "Cannot assign multiple role to same facilitator.\n";
        } else if (isset($_POST ['facilitatorReviewTeam'] ['member']) && isset($_POST ['facilitator_id']) && in_array($_POST ['facilitator_id'], $_POST ['facilitatorReviewTeam'] ['member'])) {
            $this->apiResult ["message"] = "Cannot assign multiple role to same facilitator.\n";
        } else if (empty($_POST ['diagnostic_lang'])) {
            $this->apiResult ["message"] = "Diagnostic language cannot be empty.\n";
        } else {
            if (!empty($_POST['aqs']['school_aqs_pref_end_date']) && !empty($_POST['aqs']['school_aqs_pref_start_date'])) {
                $eDate = explode("-", $_POST['aqs']['school_aqs_pref_end_date']);
                $sDate = explode("-", $_POST['aqs']['school_aqs_pref_start_date']);
                if ($eDate[2] < $sDate[2] || ($eDate[2] == $sDate[2] && $eDate[1] < $sDate[1]) || ($eDate[2] == $sDate[2] && $eDate[1] == $sDate[1] && $eDate[0] < $sDate[0]))
                    $this->apiResult ["message"] = "End date can't be less than Start date";

                else {
                    $externalRoleClient = array();
                    $facilitatorDataArray = array();
                    $assessmentModel = new assessmentModel ();
                    $i = 0;
                    if (!empty($_POST ['externalReviewTeam'] ['clientId']) && in_array("assign_external_review_team", $this->user ['capabilities']))
                        foreach ($_POST ['externalReviewTeam'] ['clientId'] as $client) {
                            array_push($externalRoleClient, $client . '_' . $_POST ['externalReviewTeam'] ['role'] [$i]);
                            $i ++;
                        }
                    $facilitatorCount = 0;
                    if (isset($_POST ['facilitatorReviewTeam'] ['clientId'])) {
                        foreach ($_POST ['facilitatorReviewTeam'] ['clientId'] as $client => $val) {
                            $facilitatorDataArray[$facilitatorCount]['client_id'] = $val;
                            $facilitatorDataArray[$facilitatorCount]['role_id'] = $_POST ['facilitatorReviewTeam'] ['role'][$facilitatorCount];
                            $facilitatorDataArray[$facilitatorCount]['user_id'] = $_POST ['facilitatorReviewTeam'] ['member'][$facilitatorCount];
                            $facilitatorCount++;
                        }
                    }
                    if (!empty($_POST['facilitator_client_id']) && !empty($_POST['facilitator_id'])) {
                        $facilitatorDataArray[$facilitatorCount]['client_id'] = $_POST['facilitator_client_id'];
                        $facilitatorDataArray[$facilitatorCount]['role_id'] = 1;
                        $facilitatorDataArray[$facilitatorCount]['user_id'] = $_POST['facilitator_id'];
                    }
                    $externalReviewTeam = empty($_POST ['externalReviewTeam'] ['clientId']) ? '' : array_combine($_POST ['externalReviewTeam'] ['member'], $externalRoleClient);
                    if ($diagnostic_type != 1) {
                        $notificationSettingData = isset($_POST ['notifySett']) ? $_POST ['notifySett'] : array();
                        $notificationTeam = isset($_POST ['externalReviewTeam'] ['member']) ? $_POST ['externalReviewTeam'] ['member'] : array();
                        $sheetStatusData = array();
                        foreach ($notificationTeam as $key => $val) {
                            if (isset($_POST ['externalReviewTeam'] ['role'][$key]) && $_POST ['externalReviewTeam'] ['role'][$key] != 8)
                                $sheetStatusData[$val] = 0;
                        }
                        if (isset($_POST ['external_assessor_id']))
                            $sheetStatusData[$_POST ['external_assessor_id']] = 0;
                        $notificationTeam[] = isset($_POST ['external_assessor_id']) ? $_POST ['external_assessor_id'] : '';
                        $notificationTeam[] = isset($_POST ['internal_assessor_id']) ? $_POST ['internal_assessor_id'] : '';
                    }
                    $this->db->start_transaction();
                    if ($diagnostic_type != 1) {
                        $aid = $assessmentModel->createSchoolAssessmentNew($_POST ['client_id'], $_POST ['internal_assessor_id'], $_POST ['facilitator_id'], $_POST ['diagnostic_id'], $_POST ['tier_id'], $_POST ['award_scheme_name'], $_POST ['aqs_round'], $_POST ['external_assessor_id'], $externalReviewTeam, $_POST['aqs']['school_aqs_pref_start_date'], $_POST['aqs']['school_aqs_pref_end_date'], $facilitatorDataArray, $notificationSettingData, $notificationTeam, $_POST ['diagnostic_lang'], $_POST ['review_criteria'], $_POST ['review_type']);
                        $reviwSettingStatus = $this->addReviewNotificationSettings($aid, $externalReviewTeam, $_POST ['external_assessor_id'], 1);
                        $reviwSheetStatus = $assessmentModel->updateReimSheetSettings($aid, $sheetStatusData, 1);
                    } else {
                        $aid = $assessmentModel->createSchoolAssessmentNew($_POST ['client_id'], $_POST ['internal_assessor_id'], $_POST ['facilitator_id'], $_POST ['diagnostic_id'], $_POST ['tier_id'], $_POST ['award_scheme_name'], $_POST ['aqs_round'], $_POST ['external_assessor_id'], $externalReviewTeam, $_POST['aqs']['school_aqs_pref_start_date'], $_POST['aqs']['school_aqs_pref_end_date'], $facilitatorDataArray, '', '', $_POST ['diagnostic_lang'], $_POST ['review_criteria'], $_POST ['review_type']);
                        $reviwSettingStatus = 1;
                        $reviwSheetStatus = 1;
                    }
                    if ($aid && $reviwSettingStatus && $reviwSheetStatus) {
                        $this->db->addAlerts('d_assessment', $aid, $_POST ['client_id'], $aid, 'CREATE_REVIEW');
                        $this->apiResult ["status"] = 1;
                        $this->apiResult ["assessment_id"] = $aid;
                        $this->apiResult ["message"] = "Review successfully created.";
                        $this->db->commit();
                    } else {
                        $this->apiResult ["message"] = "Unable to create review.";
                        $this->db->rollback();
                    }
                }
            }
        }
    }

    //@Purpose:save school assessment for kpa
    function createSchoolAssessmentKpaAction() {
        if (!in_array("create_assessment", $this->user ['capabilities'])) {
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        } else {
            if (empty($_POST['team_kpa_id']))
                $this->apiResult ["message"] = "KPAs can't empty";

            else {
                $allAssessmentKpas = array();
                $num_kpa = isset($_POST['num_kpa']) ? $_POST['num_kpa'] : 0;
                foreach ($_POST['team_kpa_id'] as $teamKpa) {
                    foreach ($teamKpa as $key => $val) {

                        $allAssessmentKpas[] = $val;
                    }
                }
                if (!empty($allAssessmentKpas) && count($allAssessmentKpas) != $num_kpa) {
                    $this->apiResult ["message"] = "All KPAs must be assigned to assessors";
                } else if (!empty($allAssessmentKpas) && count($allAssessmentKpas) != count(array_unique($allAssessmentKpas))) {
                    $this->apiResult ["message"] = "Same KPA cannot  assigned to multiple assessors";
                } else {
                    $externalRoleClient = array();
                    $facilitatorDataArray = array();
                    $assessmentModel = new assessmentModel ();
                    $assessment_id = isset($_POST['assessment_id']) ? $_POST['assessment_id'] : '';
                    $i = 0;
                    $aid = $assessmentModel->addAssessmentKpa($_POST['team_kpa_id'], $assessment_id);
                    if ($aid) {

                        $this->apiResult ["status"] = 1;
                        $this->apiResult ["message"] = "KPAs assigned successfully";
                    } else {
                        $this->apiResult ["message"] = "Unable to create review.";
                    }
                }
            }
        }
    }

    /*
     * @Purpose:Edit assessment for kpa for Step 2 data
     */
    function editSchoolAssessmentKpaAction() {
        $editStatus = isset($_POST['editStatus']) ? $_POST['editStatus'] : 0;
        if (!(in_array("create_assessment", $this->user ['capabilities']) || $editStatus)) {
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        } else {
            if (empty($_POST['team_kpa_id']))
                $this->apiResult ["message"] = "KPAs can't empty";

            else {
                $assessmentModel = new assessmentModel ();
                $diagnosticModel = new diagnosticModel;
                $assessment_id = isset($_POST['assessment_id']) ? $_POST['assessment_id'] : '';
                $allAssessmentKpas = array();
                $num_kpa = isset($_POST['num_kpa']) ? $_POST['num_kpa'] : 0;
                $assessmentStatus = $assessmentModel->getAssessmentRatingStatus($assessment_id);
                $percentageData = $diagnosticModel->getExternalTeamRatingPerc($assessment_id);
                $leadAssessor = $diagnosticModel->getAssessmentLead($assessment_id);
                $allAccessorsId = array();
                if (!empty($percentageData)) {
                    $allAccessorsId = explode(",", $percentageData['user_ids']);
                }
                if ($assessmentStatus == 1)
                    $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
                else if (!array_key_exists($leadAssessor['user_id'], $_POST['team_kpa_id'])) {
                    $this->apiResult ["message"] = "KPAs must be assigned to lead assessor";
                } else {
                    foreach ($_POST['team_kpa_id'] as $teamKpa) {
                        foreach ($teamKpa as $key => $val) {

                            $allAssessmentKpas[] = $val;
                        }
                    }
                    if (!empty($allAssessmentKpas) && count($allAssessmentKpas) != $num_kpa) {

                        $this->apiResult ["message"] = "All KPAs must be assigned to assessors";
                    } else if (!empty($allAssessmentKpas) && count($allAssessmentKpas) != count(array_unique($allAssessmentKpas))) {

                        $this->apiResult ["message"] = "Same KPA can't  assigned to multiple assessors";
                    } else {
                        $externalRoleClient = array();
                        $facilitatorDataArray = array();

                        $this->db->start_transaction();
                        if (isset($_POST['isNewReview']) && $_POST['isNewReview'] == 0)
                            $assessmentModel->deleteOldKpaRating($_POST['team_kpa_id'], $assessment_id);
                        $i = 0;
                        $aid = $assessmentModel->editAssessmentKpa($_POST['team_kpa_id'], $assessment_id);
                        $assessmentModel->editAssessmentStatus($assessment_id);
                        if ($aid) {

                            $this->apiResult ["status"] = 1;
                            $this->apiResult ["message"] = "KPAs assigned successfully";
                            $this->db->commit();
                            if (isset($_POST['isNewReview']) && $_POST['isNewReview'] == 0) {
                                $this->updateCollaborativeAssessmentPercentage($assessment_id, $allAccessorsId, 1, 0);
                                if (!empty($leadAssessor)) {
                                    $this->updateCollaborativeAssessmentPercentage($assessment_id, $leadAssessor, 0, 1);
                                }
                            }
                        } else {
                            $this->apiResult ["message"] = "Unable to create review.";
                            $this->db->rollback();
                        }
                    }
                }
            }
        }
    }

    // @Purpose:function for check language is already exist or not
    function checkLanguageExistAction() {
        if (isset($_POST ['language']) && trim($_POST ['language']) != '') {
            $objDianosticModel = new diagnosticModel ();
            $checkData = $objDianosticModel->getLanguageData(trim($_POST ['language']), 'id');
            if (!empty($checkData)) {
                $this->apiResult ["message"] = 'This language is already exist. Please enter new language!';
            } else {
                $this->apiResult ["status"] = 1;
            }
        } else {
            $this->apiResult ["message"] = 'Please enter new language!';
        }
    }

    //@Purpose:function for updating user password on 25-07-2016 by Mohit Kumar
    public function updateUserPasswordAction() {
        if (isset($_POST ['id']) && isset($_POST ['password'])) {
            $user = $this->userModel->getUserById($_POST ['id']);
            if (empty($user)) {
                $this->apiResult ["message"] = 'This user is invalid user!';
            } else {
                if ($this->db->update('d_user', array(
                            'password' => md5(trim($_POST ['password']))
                                ), array(
                            'user_id' => $_POST ['id']
                        ))) {
                    if (OFFLINE_STATUS == TRUE) {
                        // start---> call function for add other langauge on 10-08-2016 by Mohit Kumar
                        $action_json = json_encode(array(
                            'password' => $_POST ['password'],
                            'user_id' => $_POST ['id']
                        ));
                        $this->db->saveHistoryData($_POST ['id'], 'd_user', $uniqueID, 'updateAssessorIntroductoryAssessment', $_POST ['id'], $_POST ['password'], $action_json, 0, date('Y-m-d H:i:s'));
                        // end---> call function for add other langauge on 10-08-2016 by Mohit Kumar
                    }
                    $this->apiResult ["message"] = 'Password Updated Successfully!';
                    $this->apiResult ["status"] = 1;
                }
            }
        }
    }

    //@Purpose:get list of state by country
    public function getStateByCountryAction() {
        if (empty($_POST ['country'])) {
            $this->apiResult ["message"] = "Country can not be empty\n";
        } else {
            $clientModel = new clientModel ();
            $countryId = $_POST ['country'];
            $stateId = empty($_POST ['state']) ? 0 : $_POST ['state'];
            $states = $clientModel->getStateList($countryId, $stateId);
            $this->apiResult ["states"] = $states;
            $this->apiResult ["status"] = 1;
        }
    }

    /* 
    * @Purpose : Function to get cities using state
    */
    public function getCityByStateAction() {
        if (empty($_POST ['state'])) {
            $this->apiResult ["message"] = "State can not be empty\n";
        } else {
            $clientModel = new clientModel ();
            $stateId = $_POST ['state'];
            $cities = $clientModel->getCityList($stateId);
            $this->apiResult ["cities"] = $cities;
            $this->apiResult ["status"] = 1;
        }
    }

    //@Purpose:function to find all parent of a directory
    public function getParentDirectory(array &$elements, $child_dir_id = 0, $parent_path = '') {
        $branch = array();
        foreach ($elements as $element) {
            if ($element['directory_id'] == $child_dir_id) {
                $parent_path .= $element['directory_id'] . "/";
                $parent_path = $this->getParentDirectory($elements, $element['ParentCategoryId'], $parent_path);
            }
        }
        return $parent_path;
    }

    /* 
    * @Purpose : Function to save key recommendation
    */
    function saveKeyRecommendationsAction() {
        $diagnosticModel = new diagnosticModel ();
        $lang_id = DEFAULT_LANGUAGE;
        $external = isset($_POST ['external']) ? $_POST ['external'] : 0;
        $is_collaborative = isset($_POST ['is_collaborative']) ? $_POST ['is_collaborative'] : 0;
        $assessor_id = isset($_POST ['assessor_id']) ? $_POST ['assessor_id'] : 0;
        if (empty($_POST ['assessment_id'])) {
            $this->apiResult ["message"] = "Review id cannot be empty\n";
        } else if (empty($_POST ['level_type'])) {
            $this->apiResult ["message"] = "Level type  cannot be empty\n";
        } else if (empty($_POST ['instance_id'])) {
            $this->apiResult ["message"] = "Instance Id  cannot be empty\n";
        } else if ($assessment = $diagnosticModel->getAssessmentByRole($_POST ['assessment_id'], 4, $lang_id, $external, $is_collaborative, $assessor_id)) {
            $report = $diagnosticModel->getAssessmentByUser($_POST ['assessment_id'], $assessment ["user_id"], $lang_id, $external);
            if ($report ['report_published'] == 1) {
                $this->apiResult ["message"] = "You can't update data after publishing reports\n";
            } else if ($assessment ["status"] == 1 && !in_array("edit_all_submitted_assessments", $this->user ['capabilities'])) {
                $this->apiResult ["message"] = "You are not authorized to update review after submission\n";
            } else if ($assessment ["status"] == 0 && $assessment ["user_id"] != $this->user ['user_id']) {
                $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
            } else {
                $type = empty($_POST ['type']) ? '' : $_POST ['type'];
                $instance_type = $_POST ['level_name'];
                $instance_type_id = $_POST ['instance_id'];
                $assessment_id = $_POST ['assessment_id'];
                $success = true;
                $this->db->start_transaction();
                $formKnsCelebrateNew = $_POST ['data'] ['celebrate'] [$instance_type_id];
                $formKnsImproveNew = $_POST ['data'] ['recommendation'] [$instance_type_id];
                foreach ($formKnsCelebrateNew as $key => $cel) {
                    if (preg_match('/^new_*/', $key)) {
                        if ($akn_id = $diagnosticModel->addAssessorKeyNote($_POST ['assessment_id'], $_POST ['level_type'], $instance_type_id, $cel, 'celebrate'))
                            $_POST ['data'] ['celebrate'] [$instance_type_id] [$akn_id] = $cel;
                        else
                            $success = false;
                        unset($_POST ['data'] ['celebrate'] [$instance_type_id] [$key]);
                    }
                }
                foreach ($formKnsImproveNew as $key => $imp) {
                    if (preg_match('/^new_*/', $key)) {
                        $aknJS = isset($_POST ['js'] ['recommendation'] [$instance_type_id][$key]) ? $_POST ['js'] ['recommendation'] [$instance_type_id][$key] : array();
                        if ($akn_id = $diagnosticModel->addAssessorKeyNote($_POST ['assessment_id'], $_POST ['level_type'], $instance_type_id, $imp, 'recommendation', $aknJS)) {
                            $_POST ['data'] ['recommendation'] [$instance_type_id] [$akn_id] = $imp;
                            $_POST ['js'] ['recommendation'] [$instance_type_id] [$akn_id] = $aknJS;
                        } else {
                            $success = false;
                            unset($_POST ['data'] ['recommendation'] [$instance_type_id] [$key]);
                            unset($_POST ['js'] ['recommendation'] [$instance_type_id] [$key]);
                        }
                    }
                }
                $akns = $diagnosticModel->getAssessorKeyNotesForType($assessment_id, $instance_type, $instance_type_id);
                if (OFFLINE_STATUS == TRUE) {
                    $uniqueID = $this->db->createUniqueID('internalAssessment');
                }
                $isKNComplete = true;
                $akn_count = 0;
                if (isset($akns) && count($akns)) {
                    $oldKN = array_filter($akns, function ($var) {
                        if ($var ['type'] == '' || $var ['type'] == null)
                            return $var;
                    });
                    $celebrateKN = array_filter($akns, function ($var) {
                        if ($var ['type'] == 'celebrate')
                            return $var;
                    });
                    $recommendationKN = array_filter($akns, function ($var) {
                        if ($var ['type'] == 'recommendation')
                            return $var;
                    });
                    if (!empty($oldKN)) {
                        foreach ($akns as $k => $akn) {
                            $akn_id = $akn ['id'];
                            if (isset($_POST ['data'] [$akn_id])) {
                                $akn_count ++;
                                $aknText = trim($_POST ['data'] [$akn_id]);
                                $aknJS = isset($_POST ['js'] [$akn_id]) ? $_POST ['js'] [$akn_id] : array();
                                $diff11 = array_diff($aknJS, explode(",", $akn ['rec_judgement_instance_id']));
                                $diff22 = array_diff(explode(",", $akn ['rec_judgement_instance_id']), $aknJS);
                                $client_diff = array_merge($diff11, $diff22);
                                if (($aknText != $akn ['text_data']) || (isset($aknJS) && count($client_diff) > 0)) {
                                    if (!$diagnosticModel->updateAssessorKeyNote($akn_id, $aknText, '', $aknJS, explode(",", $akn ['rec_judgement_instance_id']))) {
                                        $success = false;
                                    } else {
                                        if (OFFLINE_STATUS == TRUE) {
                                            // start--> call function for save history for update assessor key notes in history table on 21-03-2016 by Mohit Kumar
                                            $action_assessor_key_note_json = json_encode(array(
                                                "id" => $akn_id,
                                                "text_data" => $aknText,
                                                'assessment_id' => $assessment_id
                                            ));
                                            $this->db->saveHistoryData($akn_id, 'assessor_key_notes', $uniqueID, 'internalAssessmentAssessorKeyNoteUpdate', $akn_id, $aknText, $action_assessor_key_note_json, 0, date('Y-m-d H:i:s'));
                                            // end--> call function for save history for update assessor key notes in history table on 03-03-2016 by Mohit Kumar
                                        }
                                    }
                                }
                                if ($aknText == "")
                                    $isKNComplete = false;
                            } else {
                                if (!$diagnosticModel->deleteAssessorKeyNote($akn_id)) {
                                    $success = false;
                                } else {
                                    if (OFFLINE_STATUS == TRUE) {
                                        // start--> call function for save history for delete assessor key notes in history table on 21-03-2016 by Mohit Kumar
                                        $action_assessor_key_note_json = json_encode(array(
                                            "id" => $akn_id,
                                            'assessment_id' => $assessment_id
                                        ));
                                        $this->db->saveHistoryData($akn_id, 'assessor_key_notes', $uniqueID, 'internalAssessmentAssessorKeyNoteDelete', $akn_id, $akn_id, $action_assessor_key_note_json, 0, date('Y-m-d H:i:s'));
                                        // end--> call function for save history for delete assessor key notes in history table on 03-03-2016 by Mohit Kumar
                                    }
                                }
                            }
                        }
                    } else {
                        if (!empty($celebrateKN))
                            foreach ($celebrateKN as $k => $akn) {
                                $akn_id = $akn ['id'];
                                if (isset($_POST ['data'] ['celebrate'] [$instance_type_id] [$akn_id])) {
                                    $akn_count ++;
                                    $aknText = trim($_POST ['data'] ['celebrate'] [$instance_type_id] [$akn_id]);
                                    if ($aknText != $akn ['text_data']) {
                                        if (!$diagnosticModel->updateAssessorKeyNote($akn_id, $aknText, 'celebrate')) {
                                            $success = false;
                                        } else {
                                            if (OFFLINE_STATUS == TRUE) {
                                                // start--> call function for save history for update assessor key notes in history table on 21-03-2016 by Mohit Kumar
                                                $action_assessor_key_note_json = json_encode(array(
                                                    "id" => $akn_id,
                                                    "text_data" => $aknText,
                                                    "type" => 'celebrate',
                                                    'assessment_id' => $assessment_id
                                                ));
                                                $this->db->saveHistoryData($akn_id, 'assessor_key_notes', $uniqueID, 'internalAssessmentAssessorKeyNoteUpdate', $akn_id, $aknText, $action_assessor_key_note_json, 0, date('Y-m-d H:i:s'));
                                                // end--> call function for save history for update assessor key notes in history table on 03-03-2016 by Mohit Kumar
                                            }
                                        }
                                    }
                                    if ($aknText == "")
                                        $isKNComplete = false;
                                } else {
                                    if (!$diagnosticModel->deleteAssessorKeyNote($akn_id)) {
                                        $success = false;
                                    } else {
                                        if (OFFLINE_STATUS == TRUE) {
                                            // start--> call function for save history for delete assessor key notes in history table on 21-03-2016 by Mohit Kumar
                                            $action_assessor_key_note_json = json_encode(array(
                                                "id" => $akn_id,
                                                'assessment_id' => $assessment_id
                                            ));
                                            $this->db->saveHistoryData($akn_id, 'assessor_key_notes', $uniqueID, 'internalAssessmentAssessorKeyNoteDelete', $akn_id, $akn_id, $action_assessor_key_note_json, 0, date('Y-m-d H:i:s'));
                                            // end--> call function for save history for delete assessor key notes in history table on 03-03-2016 by Mohit Kumar
                                        }
                                    }
                                }
                            }
                        if (!empty($recommendationKN))
                            foreach ($recommendationKN as $k => $akn) {
                                $akn_id = $akn ['id'];
                                if (isset($_POST ['data'] ['recommendation'] [$instance_type_id] [$akn_id])) {
                                    $akn_count ++;
                                    $aknText = trim($_POST ['data'] ['recommendation'] [$instance_type_id] [$akn_id]);
                                    $aknJS = isset($_POST ['js'] ['recommendation'] [$instance_type_id] [$akn_id]) ? $_POST ['js'] ['recommendation'] [$instance_type_id] [$akn_id] : array();
                                    $diff11 = array_diff($aknJS, explode(",", $akn ['rec_judgement_instance_id']));
                                    $diff22 = array_diff(explode(",", $akn ['rec_judgement_instance_id']), $aknJS);
                                    $client_diff = array_merge($diff11, $diff22);
                                    if (($aknText != $akn ['text_data']) || (isset($aknJS) && count($client_diff) > 0)) {
                                        if (!$diagnosticModel->updateAssessorKeyNote($akn_id, $aknText, 'recommendation', $aknJS, explode(",", $akn ['rec_judgement_instance_id']))) {

                                            $success = false;
                                        } else {
                                            if (OFFLINE_STATUS == TRUE) {
                                                // start--> call function for save history for update assessor key notes in history table on 21-03-2016 by Mohit Kumar
                                                $action_assessor_key_note_json = json_encode(array(
                                                    "id" => $akn_id,
                                                    "text_data" => $aknText,
                                                    "type" => 'recommendation',
                                                    'assessment_id' => $assessment_id
                                                ));
                                                $this->db->saveHistoryData($akn_id, 'assessor_key_notes', $uniqueID, 'internalAssessmentAssessorKeyNoteUpdate', $akn_id, $aknText, $action_assessor_key_note_json, 0, date('Y-m-d H:i:s'));
                                                // end--> call function for save history for update assessor key notes in history table on 03-03-2016 by Mohit Kumar
                                            }
                                        }
                                    }
                                    if ($aknText == "")
                                        $isKNComplete = false;
                                } else {
                                    if (!$diagnosticModel->deleteAssessorKeyNote($akn_id)) {
                                        $success = false;
                                    } else {
                                        if (OFFLINE_STATUS == TRUE) {
                                            // start--> call function for save history for delete assessor key notes in history table on 21-03-2016 by Mohit Kumar
                                            $action_assessor_key_note_json = json_encode(array(
                                                "id" => $akn_id,
                                                'assessment_id' => $assessment_id
                                            ));
                                            $this->db->saveHistoryData($akn_id, 'assessor_key_notes', $uniqueID, 'internalAssessmentAssessorKeyNoteDelete', $akn_id, $akn_id, $action_assessor_key_note_json, 0, date('Y-m-d H:i:s'));
                                            // end--> call function for save history for delete assessor key notes in history table on 03-03-2016 by Mohit Kumar
                                        }
                                    }
                                }
                            }
                    }
                }
                $this->apiResult ["knid"] = $type . '_' . $instance_type_id;
                $this->apiResult ["kncomplete"] = $isKNComplete ? 1 : 0;
                if ($akn_count == 0 || !$success) {
                    $this->db->rollback();
                    $this->apiResult ["message"] = "Atleast one assessor key recommendation is required.\n";
                } else {
                    $this->db->commit();
                    $this->apiResult ["message"] = "Saved successfully";
                    $this->apiResult ["status"] = 1;
                }
            }
        } else {
            $this->apiResult ["message"] = "Wrong review id or reviewer id\n";
        }
    }

    //@Purpose:add overview recommendations
    function addOverviewRecommendationsAction() {
        if (empty($_POST ['sn']))
            $this->apiResult ["message"] = "Please provide a serial no.\n";
        elseif (empty($_POST ['type']))
            $this->apiResult ["message"] = "Please provide type\n";
        elseif (empty($_POST ['instance_id']))
            $this->apiResult ["message"] = "Please provide instance_id\n";
        else {
            $this->apiResult ["status"] = 1;
            $instance_id = $_POST ['instance_id'];
            $this->apiResult ["content"] = kpajsHelper::getRecommendationRow($_POST ['type'], $instance_id, $_POST ['sn']);
        }
    }

    //@Purpose:save clusters
    function createProvinceAction() {
        if (!in_array("create_network", $this->user ['capabilities'])) {
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        } else if (empty(trim($_POST ['name']))) {
            $this->apiResult ["message"] = "Block Name cannot be empty.\n";
        } else {
            $state_id = trim($_POST ['state_id']);
            $zone_id = trim($_POST ['zone_id']);
            $network_id = trim($_POST ['block_id']);
            $name = trim($_POST ['name']);
            $networkModel = new networkModel ();
            $province_exist = $networkModel->getProvinceByName($name);
            if (!empty($province_exist)) {
                $isProvinceAssociateWithZoneBlockState = $networkModel->isClusterExistInBlockZoneState($province_exist['province_id'], $network_id, $zone_id, $state_id);
            }
            if (!empty($isProvinceAssociateWithZoneBlockState)) {
                $this->apiResult ["message"] = "Hub Name already exists\n";
            }
            if (empty($province_exist) || empty($isProvinceAssociateWithZoneBlockState)) {
                if (empty($isProvinceAssociateWithZoneBlockState) && $province_exist) {
                    $nid = $province_exist['province_id'];
                    $networkModel->addProvinceToBlock($nid, $network_id, $zone_id, $state_id);
                    $this->apiResult ["status"] = 1;
                    $this->apiResult ["assessment_id"] = $nid;
                    $this->apiResult ["message"] = "Hub successfully added";
                    $this->db->commit();
                } else if (empty($block_exist)) {
                    $pid = null;
                    $nid = null;
                    $this->db->start_transaction();
                    if (($nid = $networkModel->createProvince($name)) && $networkModel->addProvinceToBlock($nid, $network_id, $zone_id, $state_id)) {
                        if (OFFLINE_STATUS == TRUE) {
                            $uniqueID = $this->db->createUniqueID('addProvince');
                            // start---> call function for saving add school client data on 04-03-2016 by Mohit Kumar
                            $action_json = json_encode(array(
                                'block_name' => $name
                            ));
                            $this->db->saveHistoryData($nid, 'd_province', $uniqueID, 'addProvince', $nid, $name, $action_json, 0, date('Y-m-d H:i:s'));
                            // end---> call function for saving add school client data on 04-03-2016 by Mohit Kumar			
                        }
                        $this->apiResult ["status"] = 1;
                        $this->apiResult ["assessment_id"] = $nid;
                        $this->apiResult ["message"] = "Hub successfully added";
                        $this->db->commit();
                    } else {
                        $this->apiResult ["message"] = "Unable to add Hub\n";
                        $this->db->rollback();
                    }
                } else {
                    $this->apiResult ["message"] = "Unable to add Hub\n";
                    $this->db->rollback();
                }
            }
        }
    }

    /* 
    * @Purpose : Function to get province list
    */
    function getProvinceListAction() {
        if (!in_array("create_network", $this->user ['capabilities'])) {
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        } else {
            $networkModel = new networkModel ();
            $this->apiResult ["provinces"] = $networkModel->getProvinceList();
            $this->apiResult ["status"] = 1;
        }
    }

    //@Purpose:get all list of cluster for block
    function getProvincesInNetworkAction() {
        if (!in_array("create_network", $this->user ['capabilities']))
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        if (empty($_POST['network_id']))
            $this->apiResult ["message"] = "Zone Id cannot be empty.\n";
        else {
            $networkModel = new networkModel ();
            $network_id = $_POST['network_id'];
            $this->apiResult ["message"] = $networkModel->getProvinces($network_id);
            $this->apiResult ["status"] = 1;
        }
    }

    /**
     *  get province by network ids
     * Author Deepak
     * Input  Network Ids
     */
    function getProvincesInMultiNetworkAction() {
        if (!in_array("create_network", $this->user ['capabilities']))
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        if (empty($_POST['network_id']))
            $this->apiResult ["message"] = "Zone Id cannot be empty.\n";
        else {
            $networkModel = new networkModel ();
            $network_ids = $_POST['network_id'];
            $this->apiResult ["message"] = $networkModel->getMultiProvinces($network_ids);
            $this->apiResult ["status"] = 1;
        }
    }

    /**
     *  get schools by provience id
     * Author Deepak
     * Input  Provience ID
     */
    function getSchoolsInProvincesAction() {
        if (!in_array("create_network", $this->user ['capabilities']))
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        if (empty($_POST['province_id']))
            $this->apiResult ["message"] = "Province Id cannot be empty.\n";
        else {
            $networkModel = new networkModel ();
            $provience_ids = $_POST['province_id'];
            $schools = $networkModel->getSchools($provience_ids);
            if (count($schools)) {
                $this->apiResult ["message"] = $networkModel->getSchools($provience_ids);
                $this->apiResult ["status"] = 1;
            } else
                $this->apiResult ["message"] = "School does not exist for this province";
        }
    }

    /**
     *  get schools by network id
     * Author Deepak
     * Input  Network ID
     */
    function getSchoolsInNetworksAction() {
        if (!in_array("create_network", $this->user ['capabilities']))
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        if (empty($_POST['network_id']))
            $this->apiResult ["message"] = "Zone Id cannot be empty.\n";
        else {
            $networkModel = new networkModel ();
            $network_ids = $_POST['network_id'];
            $this->apiResult ["message"] = $networkModel->getSchoolsByNetwork($network_ids);
            $this->apiResult ["status"] = 1;
        }
    }

    /**
     *  get schools by type option (Schools associated to network/non network,all)
     * Author Deepak
     * Input  User Option 
     */
    function getAllSchoolsAction() {
        if (!in_array("create_network", $this->user ['capabilities']))
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        if (isset($_POST['school_related_to']) && empty($_POST['school_related_to']))
            $this->apiResult ["message"] = "Option Type cannot be empty.\n";
        else {
            $resourceModel = new resourceModel ();
            $option_type = $_POST['school_related_to'];
            $this->apiResult ["message"] = $resourceModel->getSchoolsList($option_type);
            $this->apiResult ["status"] = 1;
        }
    }

    /**
     *  get all users of schools  (Schools associated to network/non network,all)
     * Author Deepak
     * Input  Schools Ids 
     */
    function getSchoolAllUsersAction() {
        if (!in_array("create_network", $this->user ['capabilities']))
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        if (empty($_POST['school_ids']) && empty($_POST['user_role_ids']))
            $this->apiResult ["message"] = "School id/User Role cannot be empty.\n";
        else {
            $resourceModel = new resourceModel ();
            $school_ids = $_POST['school_ids'];
            $user_role_ids = $_POST['user_role_ids'];
            $user_list = $resourceModel->getSchoolUsers($school_ids, $user_role_ids);
            if (count($user_list) >= 1) {
                $this->apiResult ["status"] = 1;
                $this->apiResult ["message"] = $resourceModel->getSchoolUsers($school_ids, $user_role_ids);
            } else {
                $this->apiResult ["status"] = 0;
                $this->apiResult ["message"] = "User not exist for this schools";
            }
        }
    }

    //Fetch row of added action plan 
    function addPlanningDiagnosticRowNewAction() {
        if (empty($_POST['assessment_id']))
            $this->apiResult ["message"] = "Assessment Id cannot be empty.\n";
        elseif (empty($_POST['sn']))
            $this->apiResult ["message"] = "Serial number cannot be empty.\n";
        else {
            $sn = $_POST['sn'];
            $assessment_id = $_POST['assessment_id'];
            $lang_id = isset($_POST['lang_id']) ? $_POST['lang_id'] : DEFAULT_LANGUAGE;
            $row = kpajsHelper::getAction1HTMLNew($sn, $assessment_id, 1, array(), $lang_id);
            $this->apiResult ["content"] = $row;
            $this->apiResult ["status"] = 1;
        }
    }

    //@Purpose:Fetch key questions
    function getKeyQuestionsAction() {
        if (empty($_POST['sn']))
            $this->apiResult ["message"] = "Serial number cannot be empty.\n";
        elseif (empty($_POST['assessment_id']))
            $this->apiResult ["message"] = "Assessment Id cannot be empty.\n";
        elseif (empty($_POST['assessor_id']))
            $this->apiResult ["message"] = "Assessor Id cannot be empty.\n";
        elseif (empty($_POST['kpa_instance_id']))
            $this->apiResult ["message"] = "KPA instance Id cannot be empty.\n";

        else {
            $diagnosticModel = new diagnosticModel();
            $assessment_id = $_POST['assessment_id'];
            $assessor_id = $_POST['assessor_id'];
            $kpa = $_POST['kpa_instance_id'];
            $lang_id = isset($_POST['lang_id']) ? $_POST['lang_id'] : DEFAULT_LANGUAGE;
            $content = $diagnosticModel->getKeyQuestionsForAssessment($assessment_id, $assessor_id, $kpa, $lang_id);
            $this->apiResult ["content"] = $content;
            $this->apiResult ["status"] = 1;
        }
    }

    //@Purpose:fetch all js list for kpa
    function getJSPlanningNewAction() {
        if (empty($_POST['sn']))
            $this->apiResult ["message"] = "Serial number cannot be empty.\n";
        elseif (empty($_POST['assessment_id']))
            $this->apiResult ["message"] = "Assessment Id cannot be empty.\n";
        elseif (empty($_POST['kpa_instance_id']))
            $this->apiResult ["message"] = "CQ instance Id cannot be empty.\n";
        else {
            $actionModel = new actionModel();
            $assessment_id = $_POST['assessment_id'];
            $kpa_id = $_POST['kpa_instance_id'];
            $lang_id = isset($_POST['lang_id']) ? $_POST['lang_id'] : DEFAULT_LANGUAGE;
            $content = $actionModel->getJSForKpaAssessment($assessment_id, $kpa_id, $lang_id);
            $this->apiResult ["content"] = $content;
            $this->apiResult ["status"] = 1;
        }
    }

    //@Purpose:fetch all core questions list for kpa
    function getCoreQuestionsAction() {
        if (empty($_POST['sn']))
            $this->apiResult ["message"] = "Serial number cannot be empty.\n";
        elseif (empty($_POST['assessment_id']))
            $this->apiResult ["message"] = "Assessment Id cannot be empty.\n";
        elseif (empty($_POST['assessor_id']))
            $this->apiResult ["message"] = "Assessor Id cannot be empty.\n";
        elseif (empty($_POST['key_question_instance_id']))
            $this->apiResult ["message"] = "KQ instance Id cannot be empty.\n";
        else {
            $diagnosticModel = new diagnosticModel();
            $assessment_id = $_POST['assessment_id'];
            $assessor_id = $_POST['assessor_id'];
            $kq = $_POST['key_question_instance_id'];
            $lang_id = isset($_POST['lang_id']) ? $_POST['lang_id'] : DEFAULT_LANGUAGE;
            $content = $diagnosticModel->getCoreQuestionsForKQAssessment($assessment_id, $assessor_id, $kq, $lang_id);
            $this->apiResult ["content"] = $content;
            $this->apiResult ["status"] = 1;
        }
    }

    /*
     * @Purpose:Save aqs form 
     */
    function saveAqsFormAction() {
        $submit = isset($_POST ['is_submit']) && $_POST ['is_submit'] == 1 ? 1 : 0;
        $assessment_type_id = isset($_POST['assessment_type_id']) ? $_POST['assessment_type_id'] : '';
        $assessment_id = isset($_POST['assmntId_or_grpAssmntId']) ? $_POST['assmntId_or_grpAssmntId'] : '';
        $aqsDataModel = new aqsDataModel();
        $diagnosticModel = new diagnosticModel();
        $params = array('level' => 1);
        $assessment_type_id = isset($_POST['assessment_type_id']) ? $_POST['assessment_type_id'] : '';
        $assessment_id = isset($_POST['assmntId_or_grpAssmntId']) ? $_POST['assmntId_or_grpAssmntId'] : '';
        if (!empty($_POST['aqs']) && $assessment_id > 0) {
            $this->db->start_transaction();
            $aqsId = $aqsDataModel->saveAqsFormData($_POST['aqs'], $assessment_id);
            $totalPercentage = $this->calculateAqsPercentage($assessment_id);
            $aqsPerStatus = 0;
            $aqsStatusId = 0;
            $diagnosticModel = new diagnosticModel();
            $aqsStatusData = $diagnosticModel->getSchoolProfileStatus($assessment_id);
            $aqsProfileStatus = 0;
            if (!empty($aqsStatusData)) {
                $aqsStatusId = $aqsStatusData['aqsStatusId'];
                $aqsProfileStatus = $aqsStatusData['status'];
            }
            $noSave = 0;
            if ($submit == 1) {
                $totalPercentage = 100;
            }
            if ($aqsProfileStatus == 1) {
                if ($totalPercentage < 100) {

                    $noSave = 1;
                    $this->apiResult ["status"] = 0;
                    $this->apiResult ["message"] = "Please fill all the values";
                } else {
                    $totalPercentage = 100;
                    $submit = 1;
                }
            }
            $aqsStatusId = $aqsDataModel->saveAqsStatus($assessment_id, $submit, $totalPercentage, $aqsStatusId);
            if (empty($noSave) && $aqsId > 0 && $this->db->commit()) {
                $this->apiResult ["status"] = 1;
                $this->apiResult ["message"] = "Successfully saved";
            } else {
                $this->db->rollback();
                $this->apiResult ["message"] = $noSave ? "Please fill all the values" : "Error occurred";
            }
        }
    }

    function sort_questions_hierarchicaly(Array &$questions, Array &$into, $parentId = 0, $key) {
        foreach ($questions as $i => $question) {
            if ($question['parent_id'] == $parentId) {
                $into[$question['school_profile_id']] = $question;
                unset($questions[$i]);
            }
        }
        foreach ($into as $index => $topQuestion) {
            $into[$index]['sub_questions'] = array();
            $this->sort_questions_hierarchicaly($questions, $into[$index]['sub_questions'], $topQuestion['school_profile_id'], $key);
        }
    }

    /* 
    * @Purpose : Function to calculate aqsform fill percentage
    */
    private function calculateAqsPercentage($assmntId_or_grpAssmntId) {

        $diagnosticModel = new diagnosticModel;
        $kdDetailsQuestions = $this->db->array_grouping($diagnosticModel->getKpaQuestions($assmntId_or_grpAssmntId), 'kpa_id');
        $allKpaQuestionsData = array();
        $totalPercentage = 0;
        if (!empty($kdDetailsQuestions)) {
            foreach ($kdDetailsQuestions as $key => $kpaData) {
                $questionHierarchy1 = array();
                $this->sort_questions_hierarchicaly($kpaData, $questionHierarchy1, 0, $key);
                $allKpaQuestionsData[$key] = $questionHierarchy1;
            }
            $numOfKpa = count($allKpaQuestionsData);
            foreach ($allKpaQuestionsData as $kpaData) {
                $numOfQuestionPerKpa = count(array_keys($kpaData));
                array_walk($kpaData, array($this, 'calculateTotalPercentage'));
                if ($this->numComplete >= 1) {
                    $singleKpaPercentage = round((($this->numComplete * 100) / $numOfQuestionPerKpa) / $numOfKpa, 2);
                    $totalPercentage = $totalPercentage + $singleKpaPercentage;
                }
                $this->numComplete = 0;
            }
            if ($totalPercentage >= 100) {
                $totalPercentage = round($totalPercentage);
            }
        }
        return $totalPercentage;
    }

    /* 
    * @Purpose : Function to calculate total percentage
    */
    function calculateTotalPercentage($data) {
        $callRecursive = 1;
        $this->isAnswerFill == 0;
        $data['answer'] = strlen(trim($data['answer']));
        if (empty($data['sub_questions']) && !empty($data['answer'])) {
            $this->numComplete++;
        } else if ($data['school_profile_id'] == 7 && empty($data['answer'])) {
            $this->isAnswerFill == 0;
            $callRecursive = 0;
        } else if ($callRecursive && !empty($data['sub_questions'])) {
            $parent_id = $data['school_profile_id'];
            $this->calculateTotalPercentageLevel($data['sub_questions'], $parent_id, $count = 0);
        }if ($this->isAnswerFill == 1) {
            $this->numComplete++;
            $this->isAnswerFill = 0;
        }
    }

    /* 
    * @Purpose : Function to calculate total percentage level
    */
    function calculateTotalPercentageLevel($aqsData, $parent_id, $count = 0) {

        $i = 0;
        foreach ($aqsData as $data) {
            $i++;
            $data['answer'] = strlen(trim($data['answer']));
            if (!empty($data['answer'])) {
                $this->isAnswerFill = 1;
            }
            if (empty($data['answer']) && $data['html_type_id'] == 4) {
                $this->isAnswerFill = 0;
                if (!empty($data['sub_questions'])) {
                    $this->calculateTotalPercentageLevel($data['sub_questions'], $data['school_profile_id'], $count);
                }
                break;
            } else if (empty($data['answer']) && $data['html_type_id'] == 5) {
                $this->isAnswerFill = 0;
                if (!empty($data['sub_questions'])) {
                    $this->calculateTotalPercentageLevel($data['sub_questions'], $data['school_profile_id'], $count);
                }
                break;
            } else if (!empty($data['answer']) && $data['html_type_id'] == 3) {
                $this->isAnswerFill = 1;
                if (!empty($data['sub_questions'])) {
                    $this->calculateTotalPercentageLevel($data['sub_questions'], $data['school_profile_id'], $count);
                }
                break;
            } else if (!empty($data['answer']) && $data['html_type_id'] == 2) {
                $this->isAnswerFill = 1;
                if (!empty($data['sub_questions'])) {
                    $this->calculateTotalPercentageLevel($data['sub_questions'], $data['school_profile_id'], $count);
                }
                break;
            } else if (!empty($data['answer']) && $data['html_type_id'] == 1) {
                $this->isAnswerFill = 1;
                if (!empty($data['sub_questions'])) {
                    $this->calculateTotalPercentageLevel($data['sub_questions'], $data['school_profile_id'], $count);
                }
                break;
            } else if (!empty($data['sub_questions']) && ($data['html_type_id'] == 1 || $data['html_type_id'] == 6 || $data['html_type_id'] == 7)) {
                if (!empty($data['sub_questions'])) {
                    $this->calculateTotalPercentageLevel($data['sub_questions'], $data['school_profile_id'], $count);
                    if ($this->isAnswerFill == 0)
                        break;
                }
            }
        }
    }

    /*
     * @Purpose:function to get users roles by schools 
     */
    function getSchoolUsersRoleAction() {
        if (!in_array("create_network", $this->user ['capabilities']))
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        if (empty($_POST['school_ids']) && empty($_POST['user_role_ids']))
            $this->apiResult ["message"] = "School id/User Role cannot be empty.\n";
        else {
            $resourceModel = new resourceModel ();
            $school_ids = $_POST['school_ids'];
            $user_list = $resourceModel->getSchoolUsersRoles($school_ids);
            $user_list = array_values(array_filter(array_map('array_filter', $user_list)));
            if (count($user_list) >= 1) {
                $this->apiResult ["status"] = 1;
                $this->apiResult ["message"] = 'Success';
                $this->apiResult ["role_list"] = $user_list;
            } else {
                $this->apiResult ["status"] = 0;
                $this->apiResult ["message"] = "User not exist for this schools";
            }
        }
    }

    public function validateDate($dbDate) {
        $DOB = explode("-", $dbDate);
        if (checkdate($DOB[1], $DOB[0], $DOB[2]) === FALSE)
            return 1;
        else
            return 0;
    }

    //@Purpose:update assessment complete percentage
    function updateCollaborativeAssessmentPercentage($assessment_id, $allAccessorsId, $external = 0, $isLeadAccessor = 0) {
        $kpas = array();
        $userKpas = array();
        $allKpas = array();
        $kqs = array();
        $cqs = array();
        $jss = array();
        $diagnosticModel = new diagnosticModel();
        $lang_id = DEFAULT_LANGUAGE;
        $singleKpaId = 0; //if kpa id is given then we will check & save data for single kpa otherwise if it is 0 then we will check & save data for all kpas
        $is_collaborative = 1;
        $user_id = 0;
        foreach ($allAccessorsId as $key => $val) {
            $user_id = $val;
            $kpas = $this->db->array_col_to_key($diagnosticModel->getKpasForAssessment($assessment_id, $val, 0, $lang_id, $is_collaborative, $external, $isLeadAccessor), "kpa_instance_id");
            $userKpas = array_keys($kpas);
            $kpas = $allKpas + $kpas;
            $kqs = $kqs + $this->db->array_grouping($diagnosticModel->getKeyQuestionsForAssessment($assessment_id, $val, 0, $lang_id, $external, $userKpas, $isLeadAccessor), "kpa_instance_id", "key_question_instance_id");
            $cqs = $cqs + $this->db->array_grouping($diagnosticModel->getCoreQuestionsForAssessment($assessment_id, $val, 0, $lang_id, $external, $userKpas, $isLeadAccessor), "key_question_instance_id", "core_question_instance_id");
            $jss = $jss + $this->db->array_grouping($diagnosticModel->getJudgementalStatementsForAssessment($assessment_id, $val, 0, $lang_id, $external, $userKpas, $isLeadAccessor), "core_question_instance_id", "judgement_statement_instance_id");
            $success = true;
            $complete = true;
            $kpa_count = 0;
            $noOfComplete = 0;
            $noOfIncomplete = 0;
            foreach ($kpas as $kpa_id => $kpa) {
                $kpaJs_ratings = array();
                $kpaSq_ratings = array();
                $kpa_count ++;
                $kq_ratings = array();
                $kq_count = 0;
                foreach ($kqs [$kpa_id] as $kq_id => $kq) {
                    $kq_count ++;
                    $cq_ratings = array();
                    $cq_count = 0;
                    foreach ($cqs [$kq_id] as $cq_id => $cq) {
                        $cq_count ++;
                        $js_ratings = array();
                        $js_count = 0;
                        foreach ($jss [$cq_id] as $js_id => $js) {
                            $js_count ++;
                            $val = empty($js['numericRating']) ? "" : $js['numericRating'];
                            if ($val > 0) {
                                $noOfComplete ++;
                            } else
                                $noOfIncomplete ++;
                        }
                    }
                }
            }
            $completedPerc = 0;
            $total = $noOfIncomplete + $noOfComplete;
            $completedPerc = @((100 * $noOfComplete) / $total);
            $diagnosticModel->updateCompleteStatus($assessment_id, $user_id, $completedPerc, $isLeadAccessor);
            $avgPercntg = $this->calculatePercentage($assessment_id);
            $diagnosticModel->updateAvgAssessmentPercentage($assessment_id, $avgPercntg);
        }
        return true;
    }

    //@Purpose:function to calculate overall % for collaborative review
    function calculatePercentage($assessment_id) {
        $diagnosticModel = new diagnosticModel();
        $externalTeam = $diagnosticModel->getCollAssessmentTeam($assessment_id);
        $assessmentKpas = $diagnosticModel->getAssessmentKpa($assessment_id);
        $totalMembers = 0;
        $totalPercntg = 0;
        $avgPercntg = 0;
        if (!empty($externalTeam)) {
            $totalMembers = count($externalTeam);
            foreach ($externalTeam as $perc) {
                if ($perc['percComplete'] > 0) {
                    $userKpas = $diagnosticModel->getAssessmentKpa($assessment_id, $perc['user_id']);
                    $totalPercntg += ($perc['percComplete'] * $userKpas['kpa_num']) / $assessmentKpas['kpa_num'];
                }
            }
        }
        return $totalPercntg;
    }

    /* 
    * @Purpose : Function to get action plan data
    */
    public function actionplandataAction() {
        $id_c = $_POST['id_c'];
        $assessment_id = $_POST['assessment_id'];
        $actionModel = new actionModel();
        $aqsDataModel = new aqsDataModel();
        $details = $actionModel->getDetailsofAssessment($id_c);
        $datesrange = isset($_POST['datesrange']) ? $_POST['datesrange'] : '';
        if (isset($_POST['datesrange']) && !empty($_POST['datesrange'])) {
            $datesrangeex = explode("/", $datesrange);
            $from_date = $datesrangeex['0'];
            $to_date = $datesrangeex['1'];
        } else {
            if (isset($_POST['type']) && $_POST['type'] == "image") {
                $date = (isset($details['from_date']) && $details['from_date'] != "0000-00-00") ? $details['from_date'] : '';
                $date_start_real = (isset($details['from_date']) && $details['from_date'] != "0000-00-00") ? $details['from_date'] : '';
                $date_end_real = (isset($details['to_date']) && $details['to_date'] != "0000-00-00") ? $details['to_date'] : date("Y-m-d");
                $end_date = $date_end_real;
                $array_dates = array();
                $ii = 0;
                if (!empty($date)) {
                    $ii = 0;
                    while (strtotime($date) <= strtotime($end_date)) {
                        $sdate = date("Y-m-d", strtotime($date));
                        $dateex = date("Y-m-d", strtotime("" . $details['frequency_days'] . "", strtotime($date)));
                        $date = date("Y-m-d", strtotime("-1 day", strtotime($dateex)));
                        if ($date > $date_end_real) {
                            $dateex = date("Y-m-d", strtotime("+1 day", strtotime($date_end_real)));
                            $date = date("Y-m-d", strtotime("-1 day", strtotime($dateex)));
                            $array_dates[$ii]['fromDate'] = $sdate;
                            $array_dates[$ii]['endDate'] = $date_end_real;
                            $date = date("Y-m-d", strtotime("+1 day", strtotime($date_end_real)));
                            $ii++;
                            break;
                        } else {
                            $array_dates[$ii]['fromDate'] = $sdate;
                            $array_dates[$ii]['endDate'] = $date;
                            $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
                        }
                        $ii++;
                    }
                }
                if ($ii == 0) {
                    $sdate = date("Y-m-d", strtotime($date));
                    $dateex = date("Y-m-d", strtotime($end_date));
                    $array_dates[$ii]['fromDate'] = $sdate;
                    $array_dates[$ii]['endDate'] = $dateex;
                }
                $array_dates_f = array();
                foreach ($array_dates as $key => $val) {
                    $array_dates_f[] = $val;
                }
                $from_date = $details['from_date'];
                $to_date = $details['to_date'];
                foreach ($array_dates_f as $key => $val) {
                    if ($val['endDate'] <= date("Y-m-d")) {
                        $to_date = $val['endDate'];
                    }
                }
            } else {
                $from_date = $details['from_date'];
                $to_date = $details['to_date'];
            }
        }
        $frequency = $details['frequency_days'];
        $begin = new DateTime($from_date);
        $end = new DateTime($to_date);
        $end = $end->modify('+1 day');
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);
        $activity = $aqsDataModel->getActivity();
        $h_assessor_action1_id = isset($details['h_assessor_action1_id']) ? $details['h_assessor_action1_id'] : '';
        $activityDetails = $actionModel->getActivityAction2($h_assessor_action1_id);
        $activityDetails_final = $this->db->array_grouping($activityDetails, "activity", "");
        $activity_data = array();
        $a = array();
        $date_count = array();
        $tickarray = array();
        foreach ($activity as $key => $val) {
            $data_p = array();
            $categories = array();
            $data_c = array();
            $data_e = array();
            $activity_group = isset($activityDetails_final[$val['activity_id']]) ? $activityDetails_final[$val['activity_id']] : array();
            if (count($activity_group) > 0) {
                $activity_data_c = array();
                $activity_data_p = array();
                $activity_data_e = array();
                $group_status_overall = $this->db->array_grouping($activity_group, "activity_status", "");
                $status_notstarted_overall = isset($group_status_overall[0]) ? $group_status_overall[0] : array();
                $status_started_overall = isset($group_status_overall[1]) ? $group_status_overall[1] : array();
                $status_completed_overall = isset($group_status_overall[2]) ? $group_status_overall[2] : array();
                $status_started_overall_date = $this->db->array_grouping($status_started_overall, "activity_date", "");
                $status_notstarted_overall_date = $this->db->array_grouping($status_notstarted_overall, "activity_date", "");
                $exp = 0;
                $expp = 0;
                foreach ($status_started_overall_date as $expkey => $expval) {
                    if ($expkey < date("Y-m-d")) {
                        $exp++;
                    } else {
                        $expp++;
                    }
                }
                foreach ($status_notstarted_overall_date as $expkey => $expval) {
                    if ($expkey < date("Y-m-d")) {
                        $exp++;
                    } else {
                        $expp++;
                    }
                }
                if (count($status_completed_overall) > 0) {
                    $activity_data_c["name"] = '' . $val['activity'] . '-C';
                    $activity_data_c["color"] = 'rgba(3, 168, 3,1)';
                    $activity_data_c["marker"] = array("symbol" => isset($val['symbol']) ? $val['symbol'] : '');
                }
                if ((count($status_started_overall) > 0 || count($status_notstarted_overall) > 0) && $expp > 0) {
                    $activity_data_p = array("name" => '' . $val['activity'] . '-P',
                        "color" => 'rgba(255,140,0,1)');
                    $activity_data_p["marker"] = array("symbol" => isset($val['symbol']) ? $val['symbol'] : '');
                }
                if ((count($status_started_overall) > 0 || count($status_notstarted_overall) > 0) && $exp > 0) {
                    $activity_data_e = array("name" => '' . $val['activity'] . '-Ex',
                        "color" => 'rgba(255,0,0,1)');
                    $activity_data_e["marker"] = array("symbol" => isset($val['symbol']) ? $val['symbol'] : '');
                }
                $activity_group_date = $this->db->array_grouping($activity_group, "activity_date", "");
                foreach ($period as $dt) {
                    $date = $dt->format("Y-m-d");
                    if ($date == date("Y-m-d")) {
                        $categories[] = 'Today';
                    } else if ($date == $from_date || $date == $to_date) {
                        $categories[] = date("d-m-Y", strtotime($date));
                    } else {
                        $categories[] = '';
                    }
                    $final_date = isset($activity_group_date[$date]) ? $activity_group_date[$date] : array();
                    $group_status = $this->db->array_grouping($final_date, "activity_status", "");

                    if (count($group_status) > 0) {
                        
                    }
                    $status_notstarted = isset($group_status[0]) ? $group_status[0] : array();
                    $status_started = isset($group_status[1]) ? $group_status[1] : array();
                    $status_completed = isset($group_status[2]) ? $group_status[2] : array();
                    $expcount = 0;
                    $pendcount = 0;
                    foreach ($status_started as $k1 => $v1) {
                        if ($v1['activity_date'] < date("Y-m-d")) {
                            $expcount++;
                        } else {
                            $pendcount++;
                        }
                    }
                    foreach ($status_notstarted as $k2 => $v2) {
                        if ($v2['activity_date'] < date("Y-m-d")) {
                            $expcount++;
                        } else {
                            $pendcount++;
                        }
                    }
                    $start = (isset($date_count[$date]) ? $date_count[$date] : 0) * 0.25;
                    date_default_timezone_set('UTC');
                    $myDateTime = strtotime($date) * 1000;
                    if ($date == date("Y-m-d") || $date == $from_date || $date == $to_date) {
                        if (!in_array($myDateTime, $tickarray)) {
                            $tickarray[] = $myDateTime;
                        }
                    }
                    $tot_point = 0;
                    if (count($status_completed) > 0) {
                        $data_c[] = array($myDateTime, $start);
                        $start = $start + 0.25;

                        $tot_point++;
                    } else {
                        $data_c[] = array($myDateTime, -1);
                    }
                    if (count($status_started) > 0 && $pendcount > 0) {
                        $data_p[] = array($myDateTime, $start);
                        $start = $start + 0.25;
                        $tot_point++;
                    } else if (count($status_started) == 0 && count($status_notstarted) > 0 && $pendcount > 0) {
                        $data_p[] = array($myDateTime, $start);
                        $start = $start + 0.25;

                        $tot_point++;
                    } else {
                        $data_p[] = array($myDateTime, -1);
                    }
                    if (count($status_started) > 0 && $expcount > 0) {
                        $data_e[] = array($myDateTime, $start);
                        $start = $start + 0.25;
                        $tot_point++;
                    } else if (count($status_started) == 0 && count($status_notstarted) > 0 && $expcount > 0) {
                        $data_e[] = array($myDateTime, $start);
                        $start = $start + 0.25;
                        $tot_point++;
                    } else {
                        $data_e[] = array($myDateTime, -1);
                    }
                    $date_count[$date] = (isset($date_count[$date]) ? $date_count[$date] : 0) + $tot_point;
                }
                $activity_data_c['data'] = $data_c;
                $activity_data_p['data'] = $data_p;
                $activity_data_e['data'] = $data_e;
                if (count($status_completed_overall) > 0) {
                    $a[] = $activity_data_c;
                }
                if ((count($status_started_overall) > 0 || count($status_notstarted_overall) > 0) && $expp > 0) {
                    $a[] = $activity_data_p;
                }
                if ((count($status_started_overall) > 0 || count($status_notstarted_overall) > 0) && $exp > 0) {
                    $a[] = $activity_data_e;
                }
            }
        }
        $xais = array('type' => 'datetime',
            'dateTimeLabelFormats' => array('second' => '%H:%M:%S',
                'minute' => '%H:%M',
                'hour' => '%H:%M',
                'day' => '%e. %b',
                'week' => '%e. %b',
                'month' => '%b \'%y',
                'year' => '%Y'
            ),
            'tickInterval' => 24 * 3600 * 1000,
            'tickPositions' => $tickarray,
            'labels' => array("format" => '{value:%e .%m. %y}', 'style' => array("color" => "#000000"), 'autoRotation' => array(-10, -20, -30, -40, -50, -60, -70, -80, -90)),
            'lineColor' => '#000000',
            'gridLineColor' => '#000000',
            'tickColor' => '#000000',
        );
        if (isset($_POST['chartdata'])) {
            $chartdata = json_decode($_POST['chartdata'], 1);
            $chartdata['xAxis'] = $xais;
            $chartdata['series'] = $a;
            $graph_final["options"] = $chartdata;
            $graph_final["type"] = 'image/png';
            $graph_final["async"] = true;
            $response = $this->curlPostGraph(DOWNLOAD_CHART_URL, $graph_final);
            if (!$response['path'] == '') {
                $this->apiResult ["file_path"] = $response['path'];
                $this->apiResult ["status"] = 1;
            } else {
                $this->apiResult ["file_path"] = null;
                $this->apiResult ["status"] = 0;
            }
        } else {
            $this->apiResult ["data"] = $a;
            $this->apiResult ["xaxis"] = $xais;
            $this->apiResult ["status"] = 1;
        }
    }

    function curlPostGraph($url, $postfields) {
        $payload = json_encode($postfields);
        set_time_limit(0);
        $url_new = $url;
        $ch = curl_init($url_new);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 400); //timeout in seconds
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        $result = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $data = array();
        if ($httpcode == 200) {
            $data["path"] = $result;
            $data['statuscode'] = $httpcode;
        } else {
            $data['statuscode'] = $httpcode;
            $data["path"] = '';
        }
        return $data;
    }

    /* 
    * @Purpose : Function to get action plan tooltip data
    */
    function actionplantipAction() {
        $id_c = $_POST['id_c'];
        $assessment_id = $_POST['assessment_id'];
        $actionModel = new actionModel();
        $aqsDataModel = new aqsDataModel();
        $details = $actionModel->getDetailsofAssessment($id_c);
        $from_date = $details['from_date'];
        $to_date = $details['to_date'];
        $frequency = $details['frequency_days'];
        $begin = new DateTime($from_date);
        $end = new DateTime($to_date);
        $end = $end->modify('+1 day');
        $interval = DateInterval::createFromDateString('1 day');
        $period = new DatePeriod($begin, $interval, $end);
        $date = array();
        foreach ($period as $dt) {

            $date[] = $dt->format("Y-m-d");
        }
        $date_a = $date[$_POST['index']];
        $planorc = explode("-", $_POST['series']);
        $planorcf = $planorc[1];
        $activity = $planorc[0];
        $h_assessor_action1_id = isset($details['h_assessor_action1_id']) ? $details['h_assessor_action1_id'] : '';
        $activityDetails = $actionModel->getActivityActionTip2($h_assessor_action1_id, $date_a, $activity, $planorcf);
        $text = "";
        $i = 1;
        foreach ($activityDetails as $keyac => $valac) {
            $text .= "" . $i . "-" . $valac['activity_stackholder_ids'] . "";
            $i++;
        }
        $array = array();
        $array['series'] = $_POST['series'];
        $array['a_date'] = "" . date("d-m-Y", strtotime($date_a)) . " (" . ($i - 1) . ")";
        $array['textshow'] = $text;
        $this->apiResult ["data"] = $array;
        $this->apiResult ["status"] = 1;
    }

    
    /* 
    * @Purpose : Function to save action plan
    */
    function actionplanchartsaveAction() {
        $file = $_POST['file'];
        $id_c = $_POST['id_c'];
        $assessment_id = $_POST['assessment_id'];
        $actionModel = new actionModel();
        $details = $actionModel->getDetailsofAssessment($id_c);
        $datesrange = isset($_POST['datesrange']) ? $_POST['datesrange'] : '';
        if (isset($_POST['datesrange']) && !empty($_POST['datesrange'])) {
            $datesrangeex = explode("/", $datesrange);
            $from_date = $datesrangeex['0'];
            $to_date = $datesrangeex['1'];
        } else {
            $from_date = $details['from_date'];
            $to_date = $details['to_date'];
        }
        $chartname = explode("charts/", $file);
        $chartname_f = $chartname[1];
        if ($_POST['type'] == "outside") {
            $chart_url = "http://export.highcharts.com/" . $file . "";
        } else {
            $chart_url = "" . DOWNLOAD_CHART_URL . "" . $file . "";
        }
        $contents = getdata($chart_url);
        if (isset($_POST['datesrange']) && !empty($_POST['datesrange'])) {
            $upload_url = "" . UPLOAD_PATH . "charts/" . $assessment_id . "_" . $id_c . "_" . $from_date . "_" . $to_date . ".png";
        } else {
            $upload_url = "" . UPLOAD_PATH . "charts/" . $assessment_id . "_" . $id_c . ".png";
        }
        file_put_contents($upload_url, $contents);
        upload_file($upload_url, $upload_url);
        @unlink($upload_url);
        if ($_POST['type'] == "local") {
            @unlink("" . CHART_URL_GENERATE . "" . $chartname_f . "");
        }
        $this->apiResult ["file"] = $chartname_f;
        $this->apiResult ["status"] = 1;
    }

    /*
     * @Purpose: Function to get User type in case of edit
     */
    function getUserTypeAction() {
        if (!in_array("create_network", $this->user ['capabilities']))
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        if (empty($_POST['usertype_id']))
            $this->apiResult ["message"] = "User Id cannot be empty.\n";
        else {
            $userModel = new userModel ();
            $usertype_id = $_POST['usertype_id'];
            $this->apiResult ["message"] = $userModel->getUserTypeAjax($usertype_id);
            $this->apiResult ["status"] = 1;
        }
    }

    /*
     * @Purpose: Function to get User Type 
     */
    function getUserTypeRefreshAction() {
        if (!in_array("create_network", $this->user ['capabilities']))
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";

        $userModel = new userModel ();
        //echo $network_id;
        $this->apiResult ["message"] = $userModel->getUserTypeRef();
        $this->apiResult ["status"] = 1;
    }

    /*
     * @Purpose:Function to create state dropdown in case of adding new user 
     */
    function getStateAction() {
        if (!in_array("create_network", $this->user ['capabilities']))
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        if (empty($_POST['usertype_id']))
            $this->apiResult ["message"] = "User Id cannot be empty.\n";
        else {
            $userModel = new userModel ();
            $usertype_id = $_POST['usertype_id'];
            $this->apiResult ["message"] = $userModel->getStateForStateAdminAdd($usertype_id);
            $this->apiResult ["status"] = 1;
        }
    }

    /*
     * @Purpose: Function to get State dropdown 
     */
    function getStateEditAction() {
        if (!in_array("create_network", $this->user ['capabilities']))
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        if (empty($_POST['usertype_id']))
            $this->apiResult ["message"] = "User Id cannot be empty.\n";
        else {
            $networkModel = new networkModel ();
            $usertype_id = $_POST['usertype_id'];
            $userID = $_POST['userID'];
            $Data = $networkModel->getStateForAllAdminEdit($userID, $usertype_id);
            $this->apiResult ["message"] = $Data['newData'];
            if (!empty($Data['selecteduserTypeId'])) {
                $this->apiResult ["selecteduserTypeId"] = $Data['selecteduserTypeId'];
            }
            if (!empty($Data['selectedstateId'])) {
                $this->apiResult ["selectedstateId"] = $Data['selectedstateId'];
            }
            if (!empty($Data['selectedZones'])) {
                $this->apiResult ["selectedZones"] = $Data['selectedZones'];
            }
            if (!empty($Data['selectedBlocks'])) {
                $this->apiResult ["selectedBlocks"] = $Data['selectedBlocks'];
            }
            if (!empty($Data['selectedClusters'])) {
                $this->apiResult ["selectedClusters"] = $Data['selectedClusters'];
            }
            $this->apiResult ["status"] = 1;
        }
    }

    /*
     * @Purpose: Function to get zones dropdown 
     */
    function getZonesInStateAction() {
        if (!in_array("create_network", $this->user ['capabilities']))
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        if (empty($_POST['state_id']))
            $this->apiResult ["message"] = "State Id cannot be empty.\n";
        else {
            $networkModel = new networkModel ();
            $state_id = $_POST['state_id'];
            $this->apiResult ["message"] = $networkModel->getZonesInStates($state_id);
            $this->apiResult ["status"] = 1;
        }
    }

    /*
     * @Purpose: Function to create zone dropdown in case of adding new user 
     */
    function getZonesInStateUserAction() {
        if (!in_array("create_network", $this->user ['capabilities']))
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        if (empty($_POST['state_id']))
            $this->apiResult ["message"] = "State Id cannot be empty.\n";
        else {
            $networkModel = new networkModel ();
            $state_id = $_POST['state_id'];
            $usertype_id = $_POST['usertype_id'];
            $this->apiResult ["message"] = $networkModel->getZonesInStatesUser($state_id, $usertype_id);
            $this->apiResult ["status"] = 1;
        }
    }

    /*
     * @Purpose: Function to get Zone dropdown in case of edit
     */

    function getZonesInStateUserEditAction() {
        if (!in_array("create_network", $this->user ['capabilities']))
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        if (empty($_POST['state_id']))
            $this->apiResult ["message"] = "State Id cannot be empty.\n";
        else {
            $networkModel = new networkModel ();
            $state_id = $_POST['state_id'];
            $usertype_id = $_POST['usertype_id'];
            $userID = $_POST['userID'];
            $zoneData = $networkModel->getZonesInStatesUserEdit($userID, $state_id, $usertype_id);
            $this->apiResult ["message"] = $zoneData['newZones'];
            if (!empty($zoneData['selectedZones'])) {
                $this->apiResult ["selectedZones"] = $zoneData['selectedZones'];
            }
            if (!empty($zoneData['selecteduserTypeId'])) {
                $this->apiResult ["selecteduserTypeId"] = $zoneData['selecteduserTypeId'];
            }
            $this->apiResult ["status"] = 1;
        }
    }

    /*
     * @Purpose: Function to create block dropdown in case of adding new user 
     */

    function getBlocksInZoneUserAction() {
        if (!in_array("create_network", $this->user ['capabilities']))
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        if (empty($_POST['zone_id']))
            $this->apiResult ["message"] = "Zone Id cannot be empty.\n";
        else {
            $networkModel = new networkModel ();
            $zone_id = $_POST['zone_id'];
            $state_id = $_POST['state_id'];
            $usertype_id = $_POST['usertype_id'];
            $this->apiResult ["message"] = $networkModel->getBlocksInZonesUser($zone_id, $state_id, $usertype_id);
            $this->apiResult ["status"] = 1;
        }
    }

    /*
     * @Purpose: Function to get Block dropdown in case of edit 
     */

    function getBlocksInZoneUserEditAction() {
        if (!in_array("create_network", $this->user ['capabilities']))
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        if (empty($_POST['zone_id']))
            $this->apiResult ["message"] = "Zone Id cannot be empty.\n";
        else {
            $networkModel = new networkModel ();
            $userID = $_POST['userID'];
            $zone_id = $_POST['zone_id'];
            $state_id = $_POST['state_id'];
            $usertype_id = $_POST['usertype_id'];
            $blockData = $networkModel->getBlocksInZonesUserEdit($userID, $zone_id, $state_id, $usertype_id);
            $this->apiResult ["message"] = $blockData['newBlocks'];
            if (!empty($blockData['selectedBlocks'])) {
                $this->apiResult ["selectedBlocks"] = $blockData['selectedBlocks'];
            }
            $this->apiResult ["status"] = 1;
        }
    }

    /*
     * @Purpose: Function to create cluster dropdown in case of adding new user 
     */

    function getClustersInBlockUserAction() {
        if (!in_array("create_network", $this->user ['capabilities']))
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        if (empty($_POST['zone_id']))
            $this->apiResult ["message"] = "Zone Id cannot be empty.\n";
        else {
            $networkModel = new networkModel ();
            $block_id = $_POST['block_id'];
            $zone_id = $_POST['zone_id'];
            $state_id = $_POST['state_id'];
            $usertype_id = $_POST['usertype_id'];
            $this->apiResult ["message"] = $networkModel->getClustersInBlocksUser($block_id, $zone_id, $state_id, $usertype_id);
            $this->apiResult ["status"] = 1;
        }
    }

    /*
     * @Purpose: Function to get Cluster dropdown in case of edit
     */

    function getClustersInBlockUserEditAction() {
        if (!in_array("create_network", $this->user ['capabilities']))
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        if (empty($_POST['zone_id']))
            $this->apiResult ["message"] = "Zone Id cannot be empty.\n";
        else {
            $networkModel = new networkModel ();
            $userID = $_POST['userID'];
            $block_id = $_POST['block_id'];
            $zone_id = $_POST['zone_id'];
            $state_id = $_POST['state_id'];
            $usertype_id = $_POST['usertype_id'];
            $clusterData = $networkModel->getClustersInBlocksUserEdit($userID, $block_id, $zone_id, $state_id, $usertype_id);
            $this->apiResult ["message"] = $clusterData['newClusters'];
            if (!empty($clusterData['selectedClusters'])) {
                $this->apiResult ["selectedClusters"] = $clusterData['selectedClusters'];
            }
            $this->apiResult ["status"] = 1;
        }
    }

    /*
     * @Purpose: Function to get Block dropdown 
     */
    function getBlocksInZoneAction() {//echo '<pre>';print_r($_POST);
        if (!in_array("create_network", $this->user ['capabilities']))
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        if (empty($_POST['zone_id']))
            $this->apiResult ["message"] = "Zone Id cannot be empty.\n";
        else {
            $networkModel = new networkModel ();
            $zone_id = $_POST['zone_id'];
            $state_id = $_POST['state_id'];
            $this->apiResult ["message"] = $networkModel->getBlocksInZones($state_id,$zone_id);
            $this->apiResult ["status"] = 1;
        }
    }

    /*
     * @Purpose: Function to get Cluster dropdown 
     */
    function getClusterInZoneAction() {
        if (!in_array("create_network", $this->user ['capabilities']))
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        if (empty($_POST['zone_id']))
            $this->apiResult ["message"] = "Zone Id cannot be empty.\n";
        else {
            $networkModel = new networkModel ();
            $block_id = $_POST['block_id'];
            $zone_id = $_POST['zone_id'];
            $state_id = $_POST['state_id'];
            $this->apiResult ["message"] = $networkModel->getClusterInZones($block_id, $zone_id, $state_id);
            $this->apiResult ["status"] = 1;
        }
    }

    /*
     * @Purpose:Get cluster Report
     * 
     */
    function createClusterReportAction() {
        if (!(in_array("create_assessment", $this->user ['capabilities']) || in_array("create_self_review", $this->user ['capabilities']))) {
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        } else if (empty($_POST ['report_type'])) {
            $this->apiResult ["message"] = "Report type cannot be empty\n";
        } else if (empty($_POST ['state'])) {
            $this->apiResult ["message"] = "State cannot be empty\n";
        } else if (empty($_POST ['zone'])) {
            $this->apiResult ["message"] = "Zone cannot be empty\n";
        } else if (empty($_POST ['network'])) {
            $this->apiResult ["message"] = "Block cannot be empty\n";
        } else if (empty($_POST ['province'])) {
            $this->apiResult ["message"] = "Hub cannot be empty\n";
        } else if (empty($_POST ['school'])) {
            $this->apiResult ["message"] = "School cannot be empty\n";
        } else if (empty($_POST ['round'])) {
            $this->apiResult ["message"] = "Round cannot be empty\n";
        } else if (empty($_POST ['report_name'])) {
            $this->apiResult ["message"] = "Report Name cannot be empty\n";
        } else {
            $province = isset($_POST ['province']) ? $_POST ['province'] : array();
            $school = isset($_POST ['school']) ? $_POST ['school'] : array();
            $report_type = $_POST ['report_type'];
            $state = $_POST ['state'];
            $zone = $_POST ['zone'];
            $network = $_POST ['network'];
            $round = $_POST ['round'];
            $report_name = trim($_POST ['report_name']);
            $wt_review = empty($_POST ['un_review']) ? 0 : $_POST ['un_review'];
            $assessmentModel = new assessmentModel();
            $diagnosticModel = new diagnosticModel();
            $customreportModel = new customreportModel();
            $networkModel = new networkModel();
            $clientModel = new clientModel;
            $centre_id = $province;
            $organisation_id = $network;
            $batch_id = $school;
            $cluster_res = $customreportModel->getClusterReportData($state, $zone, $network, $province[0], $batch_id, $round);
            if (is_array($cluster_res)) {
                if (count($cluster_res) > 0) {
                    if ($customreportModel->checkDuplicateReportName($report_name)) {
                        $this->apiResult ["message"] = "Hub report name already exists.";
                        $this->apiResult ["status"] = 0;
                        return;
                    }

                    $this->db->start_transaction();
                    $clRes = $customreportModel->getClusterReportType();
                    $clusterReportId = $customreportModel->saveClusterReport($clRes[0]['report_id'], $report_name, 0, '', 0, 0, 0, $province[0], $round);

                    if (!$clusterReportId) {
                        $this->db->rollback();
                        $this->apiResult ["message"] = "Error in saving report.";
                    } else {

                        foreach ($batch_id as $client_id)
                            if (!$customreportModel->saveClusterReportClients($clusterReportId, $client_id)) {
                                $this->db->rollback();
                                $this->apiResult ["message"] = "Error in saving report.";
                            }
                    }
                    $this->db->commit();
                    $this->apiResult ["status"] = 1;
                    $this->apiResult ["message"] = "Report Generated Successfully. Click <a href='?controller=customReport&action=clusterDataReport&report_id=" . $report_type . "&centre_id=" . $province[0] . "&state=" . $state . "&zone=" . $zone . "&block=" . $network . "&name=" . $report_name . "&batch_id=" . implode(",", $batch_id) . "&round_id=" . $round . "'  target='_blank'>Here</a> to View";
                } else {
                    $this->apiResult ["message"] = "No Records found for this criteria";
                }
            } else {
                $this->apiResult ["message"] = "Problem occurred found for this criteria";
            }
        }
    }

    /* 
    * @Purpose : Function to create block report
    */
    function createBlockReportAction() {
        if (!(in_array("create_assessment", $this->user ['capabilities']) || in_array("create_self_review", $this->user ['capabilities']))) {
            $this->apiResult ["message"] = "You are not authorized to perform this task.\n";
        } else if (empty($_POST ['report_type'])) {
            $this->apiResult ["message"] = "Report type cannot be empty\n";
        } else if (empty($_POST ['state'])) {
            $this->apiResult ["message"] = "State cannot be empty\n";
        } else if (empty($_POST ['zone'])) {
            $this->apiResult ["message"] = "Zone cannot be empty\n";
        } else if (empty($_POST ['network'])) {
            $this->apiResult ["message"] = "Block cannot be empty\n";
        } else if (empty($_POST ['round'])) {
            $this->apiResult ["message"] = "Round cannot be empty\n";
        } else if (empty($_POST ['report_name'])) {
            $this->apiResult ["message"] = "Report Name cannot be empty\n";
        } else {
            $school = isset($_POST ['school']) ? $_POST ['school'] : array();
            $report_type = $_POST ['report_type'];
            $state = $_POST ['state'];
            $zone = $_POST ['zone'];
            $network = $_POST ['network'];
            $round = $_POST ['round'];
            $report_name = trim($_POST ['report_name']);
            $wt_review = empty($_POST ['un_review']) ? 0 : $_POST ['un_review'];
            $assessmentModel = new assessmentModel();
            $diagnosticModel = new diagnosticModel();
            $customreportModel = new customreportModel();
            $networkModel = new networkModel();
            $clientModel = new clientModel;
            $organisation_id = $network;
            $batch_id = $school;
            $networkModel = new networkModel();
            $cluster_res = $networkModel->getProvinces($network);
            $cluster_res = implode(',', array_column($cluster_res, 'province_id'));
            $schools = $this->db->array_grouping($customreportModel->getClustersData($network), 'cluster_id');
            $schoolsPro = $customreportModel->getClustersData($network);
            if (is_array($schools)) {
                if (count($schools) > 0) {
                    // check network with same name exists or not
                    if ($customreportModel->checkDuplicateReportName($report_name)) {
                        $this->apiResult ["message"] = "Hub report name already exists.";
                        $this->apiResult ["status"] = 0;
                        return;
                    }

                    $this->db->start_transaction();
                    $clRes = $customreportModel->getClusterReportType('Block Report', 1, $report_type);
                    $clusterReportId = $customreportModel->saveClusterReport($clRes[0]['report_id'], $report_name, 0, '', 0, 0, 0, $cluster_res, $round, $state, $zone, $network);

                    if (!$clusterReportId) {
                        $this->db->rollback();
                        $this->apiResult ["message"] = "Error in saving report.";
                    } else {
                        foreach ($batch_id as $client_id)
                            if (!$customreportModel->saveClusterReportClients($clusterReportId, $client_id)) {
                                $this->db->rollback();
                                $this->apiResult ["message"] = "Error in saving report.";
                            }
                    }
                    $this->db->commit();
                    $this->apiResult ["status"] = 1;
                    $this->apiResult ["message"] = "Report Generated Successfully. Click <a href='?controller=customReport&action=blockDataReport&report_id=" . $report_type . "&centre_id=" . $cluster_res . "&state=" . $state . "&zone=" . $zone . "&block=" . $network . "&wtr=" . $wt_review . "&name=" . $report_name . "&batch_id=" . implode(",", $batch_id) . "&round_id=" . $round . "'  target='_blank'>Here</a> to View";
                } else {
                    $this->apiResult ["message"] = "No Records found for this criteria";
                }
            } else {
                $this->apiResult ["message"] = "Problem occurred found for this criteria";
            }
        }
    }
    
    /* 
    * @Purpose:Function to get assessment detail
    */
    function roundAssessmentAction() {
        $objCustom = new actionModel();
        $clusterForBlockOld = $objCustom->getBlockClusterSchoolAssessments($_REQUEST);
        $this->apiResult ["message"] = $clusterForBlockOld;
        $this->apiResult ["status"] = 1;
    }

    /* @Purpose:get assessement id for school id
     * @params:$client_id, $round
     */
    function getAssessmentIdFromClientAction($client_id, $round) {
        $client_id = $_POST ['client_id'];
        $round = $_POST ['round'];
        $sql = "select * from d_assessment where client_id=? and aqs_round=? and isAssessmentActive=1 order by create_date asc limit 0,1";
        $res = $this->db->get_results($sql, array($client_id, $round));
        $this->apiResult ["message"] = $res[0]['assessment_id'];
        $this->apiResult ["status"] = 1;
    }

    //@Purpose:get assessment round for selected school
    function getAssessmentRoundAction($client_id) {
        $client_id = $_POST ['client_id'];
        $actionModel = new actionModel();
        $round_res = $actionModel->getAssessmentNumRound($client_id);
        $num_of_rounds = count($round_res);
        $this->apiResult ["message"] = $num_of_rounds;
        $this->apiResult ["status"] = 1;
    }

    //@Purpose:unlock review for fill the review again/// 
    function unblockreviewAction() {
        $ulocktypes = $_POST;
        $actionModel = new assessmentModel();
        $round_res = $actionModel->unlockassesment($ulocktypes);
        $this->apiResult ["message"] = $round_res;
        $this->apiResult ["status"] = 1;
    }

}
