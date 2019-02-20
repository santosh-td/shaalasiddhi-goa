<?php

/**
 * Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 * 
 * Reasons: Manage reset password module
 **/
class webController extends controller {

    protected $_web = 0;
    protected $_api = 0;
    protected $apiResult;
    protected $_postData;

    /*Constructor function of the class*/
    function __construct($controller, $action, $api = 0) {
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
            $this->apiResult = array("status" => 0, "message" => "");
        } else {
            $this->_template = new Template($controller, $action);
            $this->_template->set("ajaxRequest", $this->_ajaxRequest);
            $this->_template->set("web", $this->_web);
            if ($this->_ajaxRequest != 1)
                $this->initializeHeader();
        }
    }

    /* @Purpose: Function to login application     
    */
    function loginApiAction() {
        if (!isset($_POST['process']) && $_POST['process'] != 'assessor') {
            $token = empty($_COOKIE['ADH_TOKEN']) ? "" : $_COOKIE['ADH_TOKEN'];
            if ($token != "") {
                unset($_COOKIE['ADH_TOKEN']);
                $this->userModel->logoutUser($token);
            }
        }

        if (isset($_POST['autologin']) && $_POST['autologin'] = "login") {
            $res = $this->userModel->authenticateUser($_POST['email'], $_POST['password']);
            if (isset($res['user_id']) && $token = $this->userModel->generateToken($res['user_id'], $_POST['email'])) {
                setcookie('ADH_TOKEN', $token);    //
                $this->apiResult["status"] = 1;
                $this->apiResult["message"] = SITEURL;
            } else {
                $this->set("error", "Wrong username or password.");
            }
        }
    }

    /* @Purpose: Function to reset password     
    */
    function resetAction() {
        if (isset($_GET['key'])) {
            $userModel = new userModel();
            $key = $_GET['key'];
            $confirmUserKey = $userModel->getPasswordResetKey($key);

            if (isset($confirmUserKey['key']) && $key == $confirmUserKey['key']) {
                $this->set('key', 1);
                $this->set('hashkey', $key);
                if (isset($_GET['process']) && $_GET['process'] == 'assessor') {
                    $this->set('confirmUserKey', $confirmUserKey);
                }
            } else
                $this->set('key', -1);
        }
        $this->_template->addHeaderStyle("nui-login.css");
        $this->_template->addHeaderStyle("font-awesome.min.css");
        $this->_template->addHeaderStyle("bootstrap-social.css");
    }

    /* @Purpose: Function to change password     
    */
    function updatePassApiWebClientAction() {
        if (empty($_POST['password'])) {
            $this->apiResult["message"] = "Password cannot be empty\n";
        } else if (empty($_POST['confirmpassword'])) {
            $this->apiResult["message"] = "Confirm Password cannot be empty\n";
        } else if ($_POST['confirmpassword'] != $_POST['password']) {
            $this->apiResult["message"] = "Password and Confirm Password do not match\n";
        } else if (empty($_POST['hashkey'])) {
            $this->apiResult["message"] = "Hash Key cannot be empty\n";
        } else {
            $userModel = new userModel();
            $key = $_POST['hashkey'];
            $confirmUserKey = $userModel->getPasswordResetKey($key);
            if ($key != $confirmUserKey['key']) {
                $this->apiResult["message"] = "Key is not valid\n";
                return;
            }
            $email = $confirmUserKey['email'];
            $user_id = $confirmUserKey['user_id'];
            $password = $_POST['password'];
            $this->db->start_transaction();
            if (isset($_POST['process']) && $_POST['process'] == 'assessor') {
                $this->db->insert('h_tap_user_assessment', array('tap_program_status' => 1, 'user_id' => $user_id));
            }
            if ($userModel->updateUserPassword($user_id, $password)) {
                if ($userModel->deleteResetUser($user_id, $key)) {
                    $this->db->commit();
                    $this->apiResult['message'] = "Password reset. You can now log in with new password.";
                    $this->apiResult['status'] = 1;
                    $this->apiResult['siteurl'] = SITEURL;
                    return;
                }
            }
            $this->db->rollback();
            $this->apiResult['message'] = 'Password change failed.';
        }
    }
    
    /* @Purpose: Function to send reset password link in email   
    */
    function resetPassApiWebClientAction() {
        if (empty($_POST['email'])) {
            $this->apiResult["message"] = "Email cannot be empty\n";
        } else {
            $salt = "7j#jhf%gd76574rhjhfpMIs*%3";
            $email = trim($_POST['email']);
            $userModel = new userModel();
            $userId = $userModel->getUserByEmail($email);
            $userId = $userId['user_id'];
            // Create the unique user password reset key
            $key = hash('sha512', $salt . $email);
            $create_date = date('Y-m-d');
            $expiration_date = date('Y-m-d', strtotime('+1 year'));
            $this->db->start_transaction();
            //delete any existing key for the user
            if ($userModel->deleteResetUser($userId) && $userModel->createResetUser($userId, $key, $create_date, $expiration_date))
                $this->db->commit();
            else {
                $this->db->commit();
                $this->apiResult['message'] = "Something went wrong";
                return;
            }

            // Create a url which we will direct them to reset their password
            $pwrurl = SITEURL . '?controller=web&action=reset&key=' . $key;

            // Mail them their key
            $mailbody = "Dear user,<br/><br/>If this e-mail does not apply to you please ignore it. It appears that you have requested a password reset at our website " . SITEURL . "<br/><br/>To reset your password, please click the link below. If you cannot click it, please paste it into your web browser's address bar.<br/><br/>" . $pwrurl . "<br/><br/>Thanks,<br/><br/>The Administration<br/>Adhyayan";
            
            require ROOT . 'library' . DS . 'phpmailer' . DS . "PHPMailerAutoload" . '.php';
            //Create a new PHPMailer instance
            $mail = new PHPMailer;
            //Tell PHPMailer to use SMTP
            $mail->isSMTP();
            //Enable SMTP debugging
            // 0 = off (for production use)
            // 1 = client messages
            // 2 = client and server messages
            $mail->SMTPDebug = 0;
            //Ask for HTML-friendly debug output
            $mail->Debugoutput = 'html';
            //Set the hostname of the mail server
            $mail->Host = "gator3172.hostgator.com";
            //Set the SMTP port number - likely to be 25, 465 or 587
            $mail->Port = 465;
            //Whether to use SMTP authentication
            $mail->SMTPAuth = true;

            //Username to use for SMTP authentication
            $mail->SMTPSecure = 'ssl';

            $mail->Username = "info@adhyayan.asia";
            //Password to use for SMTP authentication
            $mail->Password = "!qaz@wsx1234";
            $fromName = 'Adhyayan';
            $toEmail = $email;
            
            $toName = $toEmail;
            //Set who the message is to be sent from
            $mail->setFrom('info@adhyayan.asia', $fromName);
            //Set an alternative reply-to address
            //	$mail->addReplyTo($fromEmail, $fromName);
            //Set who the message is to be sent to
            $mail->addAddress($toEmail, $toName);
            //Set the subject line
            $mail->Subject = SITEURL . ' - Password Reset';
            
            $mail->AltBody = $mailbody;
            $mail->msgHTML($mailbody);
            
            if (!$mail->send()) {
                $this->apiResult["message"] = $mail->ErrorInfo;
            } else {
                $this->apiResult["message"] = "Your password recovery key has been sent to your e-mail address.";
                $this->apiResult["status"] = 1;
            }
        }
    }

     /*Destructor function of the class*/
    function __destruct() {
        if ($this->_render) {
            $this->set("notPermitted", $this->_notPermitted);
            $this->set("is404", $this->_is404);
            $this->_template->render();
        } else {
            echo json_encode($this->apiResult);
            exit;
        }
    }

}
