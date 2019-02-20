<?php

/**
 * Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 * 
 * Reasons: Manage client module
 **/

class clientController extends controller{
	
        /* 
         * @Purpose : Function to show landing Myschools page
         */
	function clientAction(){
            	if((in_array(6,$this->user['role_ids'])||in_array(5,$this->user['role_ids'])) && $this->user['has_view_video']!=1 && $this->user['is_web']==1 )
			$this->_notPermitted=1;
		elseif(in_array("create_client",$this->user['capabilities']) || in_array("manage_own_network_clients",$this->user['capabilities'])){
			$clientModel=new clientModel();	
			$cPage=empty($_POST['page'])?1:$_POST['page'];
			$order_by=empty($_POST['order_by'])?"create_date":$_POST['order_by'];
			$order_type=empty($_POST['order_type'])?"desc":$_POST['order_type'];
			$param=array(
					"page"=>$cPage,
                                        "client_institution_id"=>empty($_POST['client_institution_id'])?0:$_POST['client_institution_id'],
					"name_like"=>empty($_POST['name'])?"":$_POST['name'],
					"street_like"=>empty($_POST['street'])?"":$_POST['street'],
					"city_like"=>empty($_POST['city'])?"":$_POST['city'],
					"state_id"=>empty($_POST['state_id'])?0:$_POST['state_id'],
					"country_id"=>empty($_POST['country_id'])?0:$_POST['country_id'],
					"zone_id"=>empty($_POST['zone_id'])?"":$_POST['zone_id'],
					"province_id"=>empty($_POST['province_id'])?"":$_POST['province_id'],
					"network_id"=>empty($_POST['network_id'])?0:$_POST['network_id'],
					"order_by"=>$order_by,
					"order_type"=>$order_type,
					);
			$canCreateClient=1;
			if(!in_array("create_client",$this->user['capabilities'])){
				$param['stat_id']=$this->user['stat_id'];
				$param['zone_id']=$this->user['zone_id'];
				$param['network_id']=$this->user['network_id'];
				$param['province_id']=$this->user['province_id'];
				$canCreateClient=0;
			}
                        
			$this->set("canCreateClient",$canCreateClient);
			$this->set("filterParam",$param);
			$this->set("clients",$clientModel->getClients($param));
			$this->set("countries",$clientModel->getCountryList());			
			
			$this->set("states",empty($_POST['country_id'])?array():$clientModel->getStateList($_POST['country_id']));

			$this->set("cities",empty($_POST['state_id'])?array():$clientModel->getCityList($_POST['state_id']));						
			$this->set("pages",$clientModel->getPageCount());
			$this->set("cPage",$cPage);
			$this->set("orderBy",$order_by);
			$this->set("orderType",$order_type);
                        $this->set("client_institution_type",$clientModel->getInstitutionTypeList());
			$networkModel=new networkModel();
                        
                        if(!empty($networkModel->checkGoaStateName())){
                            $gsid=$networkModel->checkGoaStateName();
                            $_POST['stat_id']=$gsid[0]['state_id'];
                        }
                        $this->set("stats",$networkModel->checkGoaStateName());
			$this->set("zones",empty($_POST['stat_id'])?array():$networkModel->getZonesInStates($_POST['stat_id']));
                        $this->set("networks",empty($_POST['zone_id'])?array():$networkModel->getBlocksInZones($_POST['stat_id'],$_POST['zone_id']));
                        $this->set("provinces",empty($_POST['network_id'])?array():$networkModel->getClusterInZones($_POST['network_id'], $_POST['zone_id'], $_POST['stat_id']));
		}else
			$this->_notPermitted=1;
	}
        
	
        /* 
         * @Purpose : Function to create new School
         */
	function createClientAction(){
		if((in_array(6,$this->user['role_ids'])||in_array(5,$this->user['role_ids'])) && $this->user['has_view_video']!=1 && $this->user['is_web']==1 )
			$this->_notPermitted=1;
		elseif(in_array("create_client",$this->user['capabilities'])){
			$networkModel=new networkModel();
			$clientModel = new clientModel();
			$this->set("isPop",empty($_GET['ispop'])?0:$_GET['ispop']);
                        $this->set("states",$networkModel->getStateList());
			$this->set("networks",$networkModel->getNetworkList());
			$this->set("countries",$clientModel->getCountryList());	
			$this->set("provinces",$networkModel->getProvinceList());
                        $this->set("client_institution_type",$clientModel->getInstitutionTypeList());
                        $this->set("countryCodeList",$clientModel->getCountryWithCode());
                        
		}else
			$this->_notPermitted=1;
	}
        
	
        /* 
         * @Purpose : Function to edit School Info
         */
	function editClientAction(){
		if((in_array(6,$this->user['role_ids'])||in_array(5,$this->user['role_ids'])) && $this->user['has_view_video']!=1 && $this->user['is_web']==1 )
			$this->_notPermitted=1;
		elseif(in_array("create_client",$this->user['capabilities'])){
			$clientModel=new clientModel();	
			$client_data = empty($_GET['id'])?array():$clientModel->getClientById($_GET['id']);
                       
			$this->set("eClient",$client_data);
			$networkModel=new networkModel(); 
			$this->set("stats",$networkModel->getStateList());
			$this->set("zones",$networkModel->getZonesInStates($client_data['statId']));
                        $this->set("blocks",$networkModel->getBlocksInZones($client_data['statId'],$client_data['zone_id']));
			$this->set("principal",$this->userModel->getPrincipal($_GET['id']));
			$this->set("isPop",empty($_GET['ispop'])?0:$_GET['ispop']);
			$this->set("countries",$clientModel->getCountryList());
			$this->set("states",$clientModel->getStateList($client_data['country_id']));
                        $this->set("client_institution_type",$clientModel->getInstitutionTypeList());
                        $this->set("countryCodeList",$clientModel->getCountryWithCode());
                        $cities = array();
                        if(isset($client_data['state_id']) && $client_data['state_id'] >= 1) {
                           
                            $cities = $clientModel->getCityList($client_data['state_id']);
                        }
			$this->set("cities",$cities);
			$this->set("provinces",empty($client_data['block_id'])?array():$networkModel->getClusterInZones($client_data['block_id'], $client_data['zone_id'], $client_data['statId']));			
		}else
			$this->_notPermitted=1;
	}
        
	
        /* 
         * @Purpose : Function to display School-list on adding or editing user when select school
         */
	function clientListAction(){
		if((in_array(6,$this->user['role_ids'])||in_array(5,$this->user['role_ids'])) && $this->user['has_view_video']!=1 && $this->user['is_web']==1 )
			$this->_notPermitted=1;
		elseif(in_array("manage_all_users",$this->user['capabilities']) || ($this->user['network_id']>0 && in_array("manage_own_network_users",$this->user['capabilities'])) || in_array("create_assessment",$this->user['capabilities'])){
			$clientModel=new clientModel();	
			$cPage=empty($_POST['page'])?1:$_POST['page'];
			$order_by=empty($_POST['order_by'])?"create_date":$_POST['order_by'];
			$order_type=empty($_POST['order_type'])?"desc":$_POST['order_type'];
			$param=array(
					"page"=>$cPage,
                                        "client_institution_id"=>empty($_POST['client_institution_id'])?0:$_POST['client_institution_id'],
					"name_like"=>empty($_POST['name'])?"":$_POST['name'],
					"street_like"=>empty($_POST['street'])?"":$_POST['street'],
					"city_like"=>empty($_POST['city'])?"":$_POST['city'],
					"state_id"=>empty($_POST['state_id'])?0:$_POST['state_id'],
					"country_id"=>empty($_POST['country_id'])?0:$_POST['country_id'],
                                        "zone_id"=>empty($_POST['zone_id'])?"":$_POST['zone_id'],
					"province_id"=>empty($_POST['province_id'])?"":$_POST['province_id'],
					"network_id"=>empty($_POST['network_id'])?0:$_POST['network_id'],
					"order_by"=>$order_by,
					"order_type"=>$order_type,
					);
			if(!in_array("manage_all_users",$this->user['capabilities'])){
				$param["network_id"]=$this->user['network_id'];
			}
			$this->set("for",empty($_POST['for'])?"":$_POST['for']);
			$this->set("filterParam",$param);

			$states = $clientModel->getStateList();
			$this->set("clients",$clientModel->getClients($param));
			$this->set("countries",$clientModel->getCountryList());			

			$this->set("states",empty($_POST['country_id'])?array():$clientModel->getStateList($_POST['country_id']));

			$this->set("cities",empty($_POST['state_id'])?array():$clientModel->getCityList($_POST['state_id']));			
			$this->set("pages",$clientModel->getPageCount());
			$this->set("cPage",$cPage);
			$this->set("orderBy",$order_by);
			$this->set("orderType",$order_type);
			$networkModel=new networkModel();

                        if(!empty($networkModel->checkGoaStateName())){
                            $gsid=$networkModel->checkGoaStateName();
                            $_POST['stat_id']=$gsid[0]['state_id'];
                        }
                        $this->set("stats",$networkModel->checkGoaStateName());
			$this->set("zones",empty($_POST['stat_id'])?array():$networkModel->getZonesInStates($_POST['stat_id']));
                        $this->set("networks",empty($_POST['zone_id'])?array():$networkModel->getBlocksInZones($_POST['stat_id'],$_POST['zone_id']));
                        $this->set("provinces",empty($_POST['network_id'])?array():$networkModel->getClusterInZones($_POST['network_id'], $_POST['zone_id'], $_POST['stat_id']));
                        
		}else
			$this->_notPermitted=1;
	}
}