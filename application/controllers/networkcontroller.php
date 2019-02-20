<?php

/**
 * Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 * 
 * Reasons: Manage client module
 **/

class networkController extends controller{
	
        /* 
         * @Purpose : Function to show landing MyLevels page
         */
	function networkAction(){
		if((in_array(6,$this->user['role_ids'])||in_array(5,$this->user['role_ids'])) && $this->user['has_view_video']!=1 && $this->user['is_web']==1 )
			$this->_notPermitted=1;
		elseif(in_array("create_network",$this->user['capabilities'])){
			$networkModel=new networkModel();
			$cPage=empty($_POST['page'])?1:$_POST['page'];
			$order_by=empty($_POST['order_by'])?"name":$_POST['order_by'];
			$order_type=empty($_POST['order_type'])?"asc":$_POST['order_type'];
                        
			$param=array(
					"page"=>$cPage,
                                        "state_like"=>empty($_POST['state'])?"":$_POST['state'],
					"name_like"=>empty($_POST['name'])?"":$_POST['name'],
					"block_like"=>empty($_POST['block'])?"":$_POST['block'],
					"province_like"=>empty($_POST['province'])?"":$_POST['province'],
					"order_by"=>$order_by,
					"order_type"=>$order_type,
					);
                        
			$this->set("filterParam",$param);
			$this->set("networks",$networkModel->getNetworks($param));
			
			$this->set("pages",$networkModel->getPageCount());
			$this->set("cPage",$cPage);
			$this->set("orderBy",$order_by);
			$this->set("orderType",$order_type);
		}else
			$this->_notPermitted=1;
	}
	
        
        /* 
         * @Purpose : Function to update the state
         */
        function editStateAction(){
		if((in_array(6,$this->user['role_ids'])||in_array(5,$this->user['role_ids'])) && $this->user['has_view_video']!=1 && $this->user['is_web']==1 )
			$this->_notPermitted=1;
		elseif(in_array("create_network",$this->user['capabilities'])){
			$networkModel=new networkModel();
			$this->set("eState",empty($_GET['id'])?array():$networkModel->getStateById($_GET['id']));
			$this->set("isPop",empty($_GET['ispop'])?0:$_GET['ispop']);
			$this->set("networks",$networkModel->getStateList());
		}else
			$this->_notPermitted=1;
	}
        
        
        /* 
         * @Purpose : Function to update the zone
         */
        function editZoneAction(){
		if((in_array(6,$this->user['role_ids'])||in_array(5,$this->user['role_ids'])) && $this->user['has_view_video']!=1 && $this->user['is_web']==1 )
			$this->_notPermitted=1;
		elseif(in_array("create_network",$this->user['capabilities'])){
			$networkModel=new networkModel();
			$this->set("eZone",empty($_GET['id'])?array():$networkModel->getZoneById($_GET['id']));
			$this->set("isPop",empty($_GET['ispop'])?0:$_GET['ispop']);
			$this->set("networks",$networkModel->getZoneList());
		}else
			$this->_notPermitted=1;
	}
        
        
        /* 
         * @Purpose : Function to update the network
         */
	function editNetworkAction(){
		if((in_array(6,$this->user['role_ids'])||in_array(5,$this->user['role_ids'])) && $this->user['has_view_video']!=1 && $this->user['is_web']==1 )
			$this->_notPermitted=1;
		elseif(in_array("create_network",$this->user['capabilities'])){
			$networkModel=new networkModel();
			$this->set("eNetwork",empty($_GET['id'])?array():$networkModel->getNetworkByIds($_GET['id']));
			$clientModel=new clientModel();
			$this->set("clients", empty($_GET['id'])?array():$clientModel->getClients(array("network_id"=>$_GET['id'],"max_rows"=>-1)) );
			$this->set("isPop",empty($_GET['ispop'])?0:$_GET['ispop']);
			$this->set("networks",$networkModel->getNetworkList());
		}else
			$this->_notPermitted=1;
	}
        
        /* 
         * @Purpose : Function to update province
         */
	function editNetworkProvinceAction(){		
		if((in_array(6,$this->user['role_ids'])||in_array(5,$this->user['role_ids'])) && $this->user['has_view_video']!=1 && $this->user['is_web']==1 )
			$this->_notPermitted=1;
			elseif(in_array("create_network",$this->user['capabilities'])){
				$networkModel=new networkModel();
				
				$this->set("provinceData",empty($_GET['pid'])?array():$networkModel->getProvinceById($_GET['pid']));
				
				$clientModel=new clientModel();
				$this->set("clients", empty($_GET['pid'])?array():$clientModel->getClients(array("province_id"=>$_GET['pid'],"max_rows"=>-1)) );
				$this->set("isPop",empty($_GET['ispop'])?0:$_GET['ispop']);
				$this->set("networks",$networkModel->getNetworkList());
				$this->set("provinces",$networkModel->getProvinceList());
			}else
				$this->_notPermitted=1;
	}
        
        
        /* 
         * @Purpose : Function to create network
         */
	function createNetworkAction(){
		if((in_array(6,$this->user['role_ids'])||in_array(5,$this->user['role_ids'])) && $this->user['has_view_video']!=1 && $this->user['is_web']==1 )
			$this->_notPermitted=1;
		elseif(in_array("create_network",$this->user['capabilities']))
		{
			$networkModel=new networkModel();
                        $this->set("states",$networkModel->getStateList()); 
			$this->set("networks",$networkModel->getNetworkList());
                        $this->set("zones",$networkModel->getZoneList());			
			$this->set("isPop",empty($_GET['ispop'])?0:$_GET['ispop']);
                        $this->set("state_id",empty($_GET['state_id'])?0:$_GET['state_id']);
                        $this->set("zone_id",empty($_GET['zone_id'])?0:$_GET['zone_id']);
		}else
			$this->_notPermitted=1;					
	}
        
        /* 
         * @Purpose : Function to create zone
         */
        function createZoneAction(){
		if((in_array(6,$this->user['role_ids'])||in_array(5,$this->user['role_ids'])) && $this->user['has_view_video']!=1 && $this->user['is_web']==1 )
			$this->_notPermitted=1;
		elseif(in_array("create_network",$this->user['capabilities']))
		{
			$networkModel=new networkModel();
                        $this->set("states",$networkModel->getStateList()); 
			$this->set("zones",$networkModel->getZoneList());
                        $this->set("state_id", empty($_GET['state_id'])?"":$_GET['state_id']);
			//$this->set("provinces",$networkModel->getProvinceList());
			$this->set("isPop",empty($_GET['ispop'])?0:$_GET['ispop']);
		}else
			$this->_notPermitted=1;					
	}
	
        /* 
         * @Purpose : Function to add school to network
         */
	function addSchoolToNetworkAction(){
		if((in_array(6,$this->user['role_ids'])||in_array(5,$this->user['role_ids'])) && $this->user['has_view_video']!=1 && $this->user['is_web']==1 )
			$this->_notPermitted=1;
		elseif(!in_array("create_network",$this->user['capabilities'])){
			$this->_notPermitted=1;
		}else if(empty($_GET['network_id'])){
			$this->_is404=1;
		}else{
			$clientModel=new clientModel();
			$this->set("clients", $clientModel->getIndividualClientList() );
			$this->set("network_id", $_GET['network_id'] );
		}			
	}
        
        /* 
         * @Purpose : Function to add school to province
         */
	function addSchoolToProvinceAction(){
		if((in_array(6,$this->user['role_ids'])||in_array(5,$this->user['role_ids'])) && $this->user['has_view_video']!=1 && $this->user['is_web']==1 )
			$this->_notPermitted=1;
			elseif(!in_array("create_network",$this->user['capabilities'])){
				$this->_notPermitted=1;
			}else if(empty($_GET['province_id']) && empty($_GET['network_id'])){
				$this->_is404=1;
			}else{
				$clientModel=new clientModel();
				$this->set("clients", $clientModel->getIndividualClientProvinceList($_GET['network_id']) );
				$this->set("province_id", $_GET['province_id'] );
				$this->set("network_id", $_GET['network_id'] );
			}
	}
        
        /* 
         * @Purpose : Function to create province
         */
	function createProvinceAction(){
		if((in_array(6,$this->user['role_ids'])||in_array(5,$this->user['role_ids'])) && $this->user['has_view_video']!=1 && $this->user['is_web']==1 )
			$this->_notPermitted=1;
			elseif(!in_array("create_network",$this->user['capabilities'])){
				$this->_notPermitted=1;			
			}else{
				$networkModel=new networkModel();
                                $this->set("states",$networkModel->getStateList());
				$this->set("networks",$networkModel->getNetworkList());
                                $this->set("blocks",$networkModel->getNetworkList());
				$this->set("provinces",$networkModel->getProvinceList());
                                $this->set("zones",$networkModel->getZoneList());
				$this->set("isPop",empty($_GET['ispop'])?0:$_GET['ispop']);
                                $this->set("state_id",empty($_GET['state_id'])?0:$_GET['state_id']);
                                $this->set("zone_id",empty($_GET['zone_id'])?0:$_GET['zone_id']);
                                $this->set("block_id",empty($_GET['block_id'])?0:$_GET['block_id']);
			}
	}
        
        
        /*
         * @Purpose : Function to create province level
         */
        function createProvinceLevelAction(){
		if((in_array(6,$this->user['role_ids'])||in_array(5,$this->user['role_ids'])) && $this->user['has_view_video']!=1 && $this->user['is_web']==1 )
			$this->_notPermitted=1;
			elseif(!in_array("create_network",$this->user['capabilities'])){
				$this->_notPermitted=1;			
			}else{
				$networkModel=new networkModel();
                                $this->set("states",$networkModel->getStateList());
				$this->set("networks",$networkModel->getNetworkList());
                                $this->set("blocks",$networkModel->getNetworkList());
				$this->set("provinces",$networkModel->getProvinceList());
                                $this->set("zones",$networkModel->getZoneList());
				$this->set("isPop",empty($_GET['ispop'])?0:$_GET['ispop']);
			}
	}
        
        
        /*
         * @Purpose : Function to create state
         */
        function createStateAction(){
		if((in_array(6,$this->user['role_ids'])||in_array(5,$this->user['role_ids'])) && $this->user['has_view_video']!=1 && $this->user['is_web']==1 )
			$this->_notPermitted=1;
		elseif(in_array("create_network",$this->user['capabilities']))
		{
			$networkModel=new networkModel();
			$this->set("states",$networkModel->getStateList());  
			$this->set("isPop",empty($_GET['ispop'])?0:$_GET['ispop']);
		}else
			$this->_notPermitted=1;					
        }
	
        
        /*
         * @Purpose : Function to get state
         */
        function stateAction(){
		if((in_array(6,$this->user['role_ids'])||in_array(5,$this->user['role_ids'])) && $this->user['has_view_video']!=1 && $this->user['is_web']==1 )
			$this->_notPermitted=1;
		elseif(in_array("create_network",$this->user['capabilities'])){
			$networkModel=new networkModel();
			$cPage=empty($_POST['page'])?1:$_POST['page'];
			$order_by=empty($_POST['order_by'])?"name":$_POST['order_by'];
			$order_type=empty($_POST['order_type'])?"asc":$_POST['order_type'];
			$param=array(
					"page"=>$cPage,
					"name_like"=>empty($_POST['name'])?"":$_POST['name'],
					"province_like"=>empty($_POST['province'])?"":$_POST['province'],
					"order_by"=>$order_by,
					"order_type"=>$order_type,
					);
			$this->set("filterParam",$param);
			$this->set("states",$networkModel->getStates($param));
			
			$this->set("pages",$networkModel->getPageCount());
			$this->set("cPage",$cPage);
			$this->set("orderBy",$order_by);
			$this->set("orderType",$order_type);
		}else
			$this->_notPermitted=1;
	}
        
        
        /*
         * @Purpose : Function to create block
         */
        function createBlockAction(){
		if((in_array(6,$this->user['role_ids'])||in_array(5,$this->user['role_ids'])) && $this->user['has_view_video']!=1 && $this->user['is_web']==1 )
			$this->_notPermitted=1;
		elseif(in_array("create_network",$this->user['capabilities']))
		{
			$networkModel=new networkModel();
			 $this->set("states",$networkModel->getStateList()); 
			$this->set("networks",$networkModel->getNetworkList()); 
                        $this->set("blocks",$networkModel->getBlockList());
                        $this->set("zones",$networkModel->getZoneList());
			$this->set("isPop",empty($_GET['ispop'])?0:$_GET['ispop']);
                        $this->set("state_id",empty($_GET['state_id'])?0:$_GET['state_id']);
                        $this->set("zone_id",empty($_GET['zone_id'])?0:$_GET['zone_id']);
		}else
			$this->_notPermitted=1;					
	}
	
}