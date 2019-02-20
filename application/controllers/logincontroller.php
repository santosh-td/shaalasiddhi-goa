<?php

/**
 * Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 * 
 * Reasons: Manage login module
 **/
class loginController extends Controller{
	
        /*
        * @Purpose: Function to login application 
        */
	function loginAction(){
		$this->userModel->a();
		        if(isset($_POST['_action']) && $_POST['_action']="login"){
                        if(isset($_POST['submit']) && $_POST['submit']=="Cancel"){
                        $this->_redirect(SITEURL);
                        die();
                        }
                        
			$res=$this->userModel->authenticateUser($_POST['email'],$_POST['password']);
                        
                        if(isset($res['user_id']) && isset($_POST['confirm']) && $_POST['confirm']==1){
                         $this->db->delete("session_token", array("user_id" => $res['user_id']));   
                        }
                        
                        $user_exists=0;
                        $login_pass=0;
                        
                        if(isset($res['user_id']) && !in_array(1,$res['role_ids']) && !in_array(2,$res['role_ids']) && !in_array(7,$res['role_ids'])  && !in_array(10,$res['role_ids']) && !in_array(11,$res['role_ids'])&& !in_array(12,$res['role_ids']) && $details_user=$this->userModel->userTokenExists($res['user_id'])){
                            
                            $user_exists=count($details_user);
                            
                        }
                       
                        if($user_exists>0){
                        $this->set("email",$_POST['email']);
                        $this->set("password",$_POST['password']);
                        $server_details=unserialize($details_user['server_details']);
                        $login_time=date("d-m-Y H:i:s",strtotime($details_user['created_date']));
                        
                        $ip=isset($server_details['REMOTE_ADDR'])?"(IP:".$server_details['REMOTE_ADDR'].")":'';
                        $agent=isset($server_details['HTTP_USER_AGENT'])?"/".$server_details['HTTP_USER_AGENT']."":'';
                        $this->set("errorexist","You are already logged in from another computer ".$ip." using the same credentials at ".$login_time.". By logging in, you can lose the unsaved data from previous login.<br><b>Please confirm to proceed.</b>");
                        
                        }else{
			if($login_pass==0 && isset($res['user_id']) && $token=$this->userModel->generateToken($res['user_id'], $_POST['email'])){
				$time=time();
                                $issuedAt   = $time;
                                $notBefore  = $issuedAt; //Adding 10 seconds
                                $expire     = $notBefore + TOKEN_LIFE_REFRESH;
                                $refresh    = $notBefore + TOKEN_LIFE;
                                
                                $jwt_token = array(
                                "iss" => "adhyayan.asia",
                                "jti" => $token,
                                "iat" => $issuedAt,
                                "nbf" => $notBefore,
                                "exp" => $expire,
                                "user" => $this->userModel->checkTokenJWT($res['user_id'])   
                                );
                                
                                $jwt_token=Firebase\JWT\JWT::encode($jwt_token, PRIVATEKEY, 'HS256');
                                
                                setcookie('ADH_TOKEN',$jwt_token,0,'/; samesite=strict','',COOKIE_SECURE,COOKIE_HTTPONLY);
                                setcookie('ADH_TOKEN_REFRESH',$token,0,'/; samesite=strict','',COOKIE_SECURE,COOKIE_HTTPONLY);
                                if(COOKIE_GEN==1){
                                setcookie('ADH_TOKEN',$jwt_token, 0 , '/; samesite=strict',COOKIE_DOMAIN,COOKIE_SECURE,COOKIE_HTTPONLY);
                                setcookie('ADH_TOKEN_REFRESH',$token, 0 , '/; samesite=strict',COOKIE_DOMAIN,COOKIE_SECURE,COOKIE_HTTPONLY);
                                }
				$this->_redirect(empty($_GET['redirect'])?SITEURL:urldecode($_GET['redirect']));
			}else{
				$this->set("error","Wrong username or password");
			}
                        }
		}
		
		$this->_template->clearHeaderFooter();
		$this->_template->addHeaderStyle("nui-login.css");
		$this->_template->addHeaderStyle("bootstrap.min.css");
		$this->_template->addHeaderStyle("font-awesome.min.css");
		$this->_template->addHeaderStyle("bootstrap-social.css");
                $this->_template->addHeaderScript("bootstrap.min.js");
                $this->_template->addHeaderScript('jquery.ui.min.js');
	}
        
        
	
        /*
        * @Purpose: Function to logout application 
        */
	function logoutAction(){
		$token=empty($_COOKIE['ADH_TOKEN'])?"":$_COOKIE['ADH_TOKEN'];
                $token_refresh=empty($_COOKIE['ADH_TOKEN_REFRESH'])?"":$_COOKIE['ADH_TOKEN_REFRESH'];
                
                if($token!=""){
                        
			setcookie('ADH_TOKEN','',time() - 3600);
                        setcookie('ADH_TOKEN_REFRESH','',time() - 3600);
                        if(COOKIE_GEN==1){
                        setcookie('ADH_TOKEN','', -1,'/',COOKIE_DOMAIN);
                        setcookie('ADH_TOKEN_REFRESH','', -1,'/',COOKIE_DOMAIN);
                        }
			$this->userModel->logoutUser($token_refresh);        
		}
                
                if(isset($_GET['redirect']) && !empty($_GET['redirect'])){
                    $redirect=$_GET['redirect'];
                    $this->_redirect(createUrl(array("controller"=>"login","action"=>"login","redirect"=>urldecode(urlencode($redirect)))));
                }else{
                   $this->_redirect(createUrl(array("controller"=>"login","action"=>"login"))); 
                }
		
                
	}
}