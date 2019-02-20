<?php

/**
 * Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 * 
 * Reasons: Manage User module
 **/

class userController extends controller{
    
    function __construct($controller, $action, $ajaxRequest = 0, $isPDF = 0) {
        parent::__construct($controller, $action, $ajaxRequest, $isPDF);
        $this->_template->addHeaderStyle('bootstrap-multiselect.css');
                        $this->_template->addHeaderScript('bootstrap-multiselect.js');
    }

	
        /* @Purpose: Function to display userlist     
        */
	function userAction(){
            
		if((in_array(6,$this->user['role_ids'])||in_array(5,$this->user['role_ids'])) && $this->user['has_view_video']!=1 && $this->user['is_web']==1 )

			$this->_notPermitted=1;

		elseif(in_array("manage_all_users",$this->user['capabilities']) 

				|| in_array("manage_own_users",$this->user['capabilities'])

				|| ($this->user['network_id']>0 && in_array("manage_own_network_users",$this->user['capabilities']))){
                        
			$cPage=empty($_POST['page'])?1:$_POST['page'];
                    
			$order_by=empty($_POST['order_by'])?"name":$_POST['order_by'];

			$order_type=empty($_POST['order_type'])?"asc":$_POST['order_type'];

			$param=array(

					"page"=>$cPage,

					"name_like"=>empty($_POST['name'])?"":$_POST['name'],

					"client_id"=>empty($_POST['client_id'])?0:$_POST['client_id'],

					"client_like"=>empty($_POST['client'])?"":$_POST['client'],

					"email_like"=>empty($_POST['email'])?"":$_POST['email'],

					"role_id"=>empty($_POST['role_id'])?0:$_POST['role_id'],

					"network_id"=>empty($_POST['network_id'])?0:$_POST['network_id'],

					"order_by"=>$order_by,

					"order_type"=>$order_type
                                        
					);

			if(!in_array("manage_all_users",$this->user['capabilities'])){

				if(in_array("manage_own_network_users",$this->user['capabilities'])){

					$param["network_id"]=$this->user['network_id'];

					$param["exclude_cap"]=array("manage_all_users");

				}else{

					$param["client_id"]=$this->user['client_id'];

					$param["exclude_cap"]=array("manage_all_users","manage_own_network_users");

				}

			}
                        
                        if(current($this->user['role_ids'])==8){
                            $tap_admin_role_id = 8;
                            $param['table_name']=empty($_REQUEST['table_name'])?"":$_REQUEST['table_name'];
                        } else {
                            $tap_admin_role_id = '';
                        }
                        
                        
                        $_REQUEST['ref']=!empty($_REQUEST['ref'])?$_REQUEST['ref']:0;
                        $ref_key="ASSESSOR".md5(time());
                        if(isset($_REQUEST['ref']) && $_REQUEST['ref']==1 && current($this->user['role_ids'])==8){
                            
                            $alertIds = $this->db->getAlertContentIds('d_user','CREATE_EXTERNAL_ASSESSOR');
                            $alertIds = !empty($alertIds)?$alertIds['content_id']:array();
                            
                            if(!empty($alertIds)){
                                $checkAlertRelation = $this->db->getAlertRelationIds(current($this->user['role_ids']),'ASSESSOR');
                                if(!empty($checkAlertRelation)){
                                    $this->db->update('h_alert_relation',array('alert_ids'=>trim($alertIds)),
                                            array('login_user_role'=>current($this->user['role_ids']),'type'=>'ASSESSOR','id'=>$checkAlertRelation['id']));
                                } else {
                                    $this->db->insert('h_alert_relation',array('alert_ids'=>trim($alertIds),'ref_key'=>$ref_key,'flag'=>1,
                                        'login_user_role'=>current($this->user['role_ids']),'type'=>'ASSESSOR'));
                                }
                            }
                        } else if($_REQUEST['ref']==1 && current($this->user['role_ids'])==8) {
                            $this->db->delete('h_alert_relation',array('type'=>'ASSESSOR','login_user_role'=>current($this->user['role_ids'])));
                        }
                        if($_REQUEST['ref']==1 && $ref_key!=''){
                            
                            $this->db->update('d_alerts',array('status'=>1,'ref_key'=>$ref_key),array('type'=>'CREATE_EXTERNAL_ASSESSOR',
                                'table_name'=>'d_user'));
                        }

                        if(current($this->user['role_ids'])==8){
                            $userList = $this->userModel->getAQSTeamAssessor($param,current($this->user['role_ids']),$_REQUEST['ref'],$ref_key);
                            $objAssessment = new assessmentModel();
                            $assessorsCount = $objAssessment->getReviewCountByRole();
                            $this->set("assessorsCount",$assessorsCount);
                            $this->set('mailUser', $this->userModel->getMailRecievedUserlist());
                            $this->set('tapAssessorUser', $this->userModel->getTapAssessorUserList());
                            $this->set('userSubRoleList', $this->userModel->getuserSubRoleList());
                            $this->_template->addHeaderScript('d3.v2.js');
                        } else {
                            $userList = $this->userModel->getUsers($param,$tap_admin_role_id,$_REQUEST['ref']!='');
                            $this->set("roles",$this->userModel->getRoles());
                            $networkModel=new networkModel();
                            $this->set("networks",$networkModel->getNetworkList(array("max_rows"=>-1)));
                        }
                         
                        $this->set("pages",$this->userModel->getPageCount());
			$this->set("filterParam",$param);
			$this->set("users",$userList);
			
			$this->set("cPage",$cPage);

			$this->set("orderBy",$order_by);

			$this->set("orderType",$order_type);
			
		}else
			$this->_notPermitted=1;
	}
        
       	
        /* @Purpose: Function to edit/update user */
	function editUserAction(){

		if((in_array(6,$this->user['role_ids'])||in_array(5,$this->user['role_ids'])) && $this->user['has_view_video']!=1 && $this->user['is_web']==1 )

			$this->_notPermitted=1;

		elseif(!empty($_GET['id']) && $user=$this->userModel->getUserById($_GET['id'])){

			if(in_array("manage_all_users",$this->user['capabilities']) 

				|| $this->user['user_id']==$user['user_id'] 

				|| ($user['client_id']==$this->user['client_id'] && in_array("manage_own_users",$this->user['capabilities']))

				|| ($this->user['network_id']>0 && $user['network_id']==$this->user['network_id'] && in_array("manage_own_network_users",$this->user['capabilities']))){
                                
                                $this->set("usertypes",$this->userModel->getUserTypeList());
                                
				$this->set("roles",$this->userModel->getRoles());
                                $this->set("state_admin_role",$this->userModel->getStateAdminRole());
                                $this->set("zone_admin_role",$this->userModel->getZoneAdminRole());
                                $this->set("block_admin_role",$this->userModel->getBlockAdminRole());
                                $this->set("cluster_admin_role",$this->userModel->getClusterAdminRole());
				
				$this->set("isPop",empty($_GET['ispop'])?0:$_GET['ispop']);

				$this->set("eUser",$user);
                                
                                $this->set("usertype_ID",$this->userModel->getUserTypeID($user['user_id']));
                                $this->set("state_ID",$this->userModel->getStateID($user['user_id']));
                                
                                $this->set("zone_ID",$this->userModel->getZoneID($user['user_id']));
                                
                                $this->set("cluster_ID",$this->userModel->getClusterID($user['user_id']));
                                $this->set("block_ID",$this->userModel->getBlockID($user['user_id']));
                                $this->set("statelist",$this->userModel->getStateEditList($user['user_id']));
                                $this->set("zonelist",$this->userModel->getZoneList($user['user_id']));
                                $this->set("blocklist",$this->userModel->getBlockList($user['user_id']));
                                $this->set("blocklistedit",$this->userModel->getBlockListEdit($user['user_id']));
                                $this->set("clusterlist",$this->userModel->getClusterList($user['user_id']));
                                
			}else

				$this->_notPermitted=1;

		}else
			$this->_is404=1;
	}

        
        /* @Purpose: Function to create new user */
	function createUserAction(){

		if((in_array(6,$this->user['role_ids'])||in_array(5,$this->user['role_ids'])) && $this->user['has_view_video']!=1 && $this->user['is_web']==1 )

			$this->_notPermitted=1;

		elseif( in_array("manage_all_users",$this->user['capabilities']) 

			|| in_array("manage_own_users",$this->user['capabilities'])

			|| ($this->user['network_id']>0 && in_array("manage_own_network_users",$this->user['capabilities'])) ){
                       
                        $this->set("usertypes",$this->userModel->getUserTypeList());
                                            
			$clientModel=new clientModel();

			$this->set("isPop",empty($_GET['ispop'])?0:$_GET['ispop']);

			$this->set("roles",$this->userModel->getRoles());
                        $this->set("state_admin_role",$this->userModel->getStateAdminRole());
                        $this->set("zone_admin_role",$this->userModel->getZoneAdminRole());
                        $this->set("block_admin_role",$this->userModel->getBlockAdminRole());
                        $this->set("cluster_admin_role",$this->userModel->getClusterAdminRole());
                   
		}else
			$this->_notPermitted=1;
	}

        /*@Purpose: Function to display externalassessorlist
        */
	function externalAssessorListAction(){

		if((in_array(6,$this->user['role_ids'])||in_array(5,$this->user['role_ids'])) && $this->user['has_view_video']!=1 && $this->user['is_web']==1 )

			$this->_notPermitted=1;

		elseif(in_array("create_assessment",$this->user['capabilities'])){

			$cPage=empty($_POST['page'])?1:$_POST['page'];

			$order_by=empty($_POST['order_by'])?"name":$_POST['order_by'];

			$order_type=empty($_POST['order_type'])?"asc":$_POST['order_type'];

			$param=array(

					"page"=>$cPage,

					"name_like"=>empty($_POST['name'])?"":$_POST['name'],

					"client_id"=>empty($_POST['client_id'])?0:$_POST['client_id'],

					"client_like"=>empty($_POST['client'])?"":$_POST['client'],

					"role_id"=>4,

					"network_id"=>empty($_POST['network_id'])?0:$_POST['network_id'],

					"order_by"=>$order_by,

					"order_type"=>$order_type,

					);

			if(!in_array("manage_all_users",$this->user['capabilities'])){

				if(in_array("manage_own_network_users",$this->user['capabilities'])){

					$param["network_id"]=$this->user['network_id'];

					$param["exclude_cap"]=array("manage_all_users");

				}else{

					$param["client_id"]=$this->user['client_id'];

					$param["exclude_cap"]=array("manage_all_users","manage_own_network_users");

				}

			}

			$this->set("filterParam",$param);

			$this->set("users",$this->userModel->getUsers($param));

			$this->set("pages",$this->userModel->getPageCount());

			$this->set("cPage",$cPage);

			$this->set("orderBy",$order_by);

			$this->set("orderType",$order_type);

			$networkModel=new networkModel();

			$this->set("networks",$networkModel->getNetworkList(array("max_rows"=>-1)));

			$currentSelectionIds=empty($_POST['eAssessor'])?array():$_POST['eAssessor'];

			$currentSelection=$this->userModel->getUsernameForIds($currentSelectionIds);

			$this->set("currentSelection",$currentSelection);

			$this->set("currentSelectionIds",$currentSelectionIds);

		}else
			$this->_notPermitted=1;
	}
       
         /* 
         * @Purpose:function to create option
         * @Params:$introductory_question,$parent_id
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
        
       
        /*@Purpose: Function to insert data in sub tables for user profile
        */
        function getInsertUserProfileDataAction() {
            
            $objUser = new userModel();
            $objUser->syncData();
           
        }

}