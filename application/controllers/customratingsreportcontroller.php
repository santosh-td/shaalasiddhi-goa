<?php
/**
 * Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 * 
 * 
 * Reasons: Manage Dashboard for State/Zone/Block and cluster user
 * Description: This class created for show the ratings details of all schools which assigned to that users. 
 */
class customRatingsReportController extends controller {
        //landing for show dashboard for all types of user
        public function indexAction(){
            $userId = $this->user['user_id'];
            $round = 1;
            $stateload = 0;
            $this->_template->addHeaderStyle ( 'rating-report.css' );
            if (in_array(10,$this->user['role_ids'])) {
                $objCustom = new customratingreportModel();
                $userData = $objCustom->statUserState($userId);
                $stateId = $userData?$userData['0']['state_id']:"";
                $stateName = $userData?$userData['0']['state_name']:"";
                $roundListState = $objCustom->getRoundState($stateId);
                $this->set('roundlist', $roundListState);
                $round = $roundListState?$roundListState[0]['aqs_round']:"";
                if(isset($_POST['round']) && !empty($_POST['round'])){
                    $round = $_POST['round'];
                    $stateload = 1;
                }
               
                $dataBlock = $objCustom->getStateKpaZoneData($round,$stateId);
                $dataBlock = $this->db->array_grouping($dataBlock,"kpa_id");
                foreach ($dataBlock as $key => $val){
                    $dataKpaBlock[$key]=$this->db->array_grouping($val,"zone_name");
                    foreach ($dataKpaBlock[$key] as $key1 => $val1){
                        $dataKpaBlockData[$key][$key1]=$this->db->array_grouping($val1,"rating");

                    }
                }
                if(!isset($dataKpaBlockData)){
                    $dataKpaBlockData = '';
                }
                $this->set('stateName', $stateName);
                $this->set('block_data',$dataKpaBlockData);
                $this->_template->renderTemplate('statelevelgraph');
                if($stateload==1){
                    $this->_template->renderTemplate('statelevelgraphajax');
                }
            }elseif(in_array(11,$this->user['role_ids'])){
                $this->_template->addHeaderScript ( 'rating-report.js' );
                $objCustom = new customratingreportModel();
                $userData = $objCustom->userStateZoneBlockCluster($userId,'zone');
                $stateId = $userData['0']['state_id'];
                $roundListState = $objCustom->getRoundState($stateId);
                $this->set('roundlist', $roundListState);
                $round = $roundListState[0]['aqs_round'];
                
                $stateName = $userData['0']['state_name'];
                $zoneId = $userData['0']['zone_id']; 
                $zoneload = 0;
                if(isset($_POST['zone_id']) && !empty($_POST['zone_id'])){
                    $zoneId = $_POST['zone_id'];
                    $round = $_POST['round'];
                    $zoneload = 1;
                }
                $data = $objCustom->getKpaZoneData($stateId,$zoneId,$round);
                $dataBlock = $objCustom->getKpaZoneBlockData($stateId,$zoneId,$round);
                $dataBlock = $this->db->array_grouping($dataBlock,"kpa_id");
                $data=$this->db->array_grouping($data,"kpa_id");
                foreach ($dataBlock as $key => $val){
                    $dataKpaBlock[$key]=$this->db->array_grouping($val,"block_name");
                    foreach ($dataKpaBlock[$key] as $key1 => $val1){
                        $dataKpaBlockData[$key][$key1]=$this->db->array_grouping($val1,"rating");

                    }
                }
                
               

                foreach ($data as $key => $val){
                    $dataKpa[$key]=$this->db->array_grouping($val,"rating");
                }
                if(!isset($dataKpa)||!isset($dataKpaBlockData)){
                    $dataKpaBlockData = '';
                    $dataKpa = '';
                }
                $this->set('userdata',$userData);
                $this->set('data',$dataKpa);
                $this->set('block_data',$dataKpaBlockData);
                if($zoneload==1){
                    $this->_template->renderTemplate('getzonedataajax');
                } else {
                    $this->_template->renderTemplate('index');
                }     
            } elseif(in_array(7,$this->user['role_ids'])) {
                $objCustom = new customratingreportModel();
                $userData = $objCustom->userStateZoneBlockCluster($userId,'block');
                $datalist = 0;
                $blockload = 0;
                if($userData){
                    $datalist = 1;
                    $zoneList = $this->db->array_grouping($userData,'zone_id');
                    //$blockList = $this->db->array_grouping($userData,'block_id');
                    $stateId = $userData['0']['state_id'];
                    $stateName = $userData['0']['state_name'];
                    $zoneId = $userData['0']['zone_id']; 
                    $roundListState = $objCustom->getRoundState($stateId);
                    $this->set('roundlist', $roundListState);
                    $round = $roundListState[0]['aqs_round'];
                
                    $zoneName = $userData['0']['zone_name'];
                    $blockZone = $objCustom->userStateZoneBlockCluster($userId,'block','',$zoneId);
                    $blockList = $this->db->array_grouping($blockZone,'block_id');
                    $block_id = $blockZone['0']['block_id'];
                    $block = $blockZone['0']['network_name'];
                    if(isset($_POST['zone_id']) && !empty($_POST['zone_id'])){
                        $zoneId = $_POST['zone_id'];
                        $zoneName = $_POST['zone_name'];
                        $block_id = $_POST['blockId'];
                        $block = $_POST['block'];
                        $round = $_POST['round'];
                        $blockload = 1;
                    }

                    $this->set('zone_id', $zoneId);
                    $this->set('zone_name', $zoneName);
                    $this->set('blocklist', $blockList);
                    $this->set('zonelist', $zoneList);
                    $this->set('blockId', $block_id);
                    $this->set('block', $block);
                    $objCustom = new customratingreportModel();
                    $data = $objCustom->getKpaZoneData($stateId,$zoneId,$round,$block_id);
                    $data=$this->db->array_grouping($data,"kpa_id");
                    foreach ($data as $key => $val){
                        $dataKpa[$key]=$this->db->array_grouping($val,"rating");
                    }
                }
                
                if(!isset($dataKpa)){
                    $dataKpa='';
                }
                $this->set('datalist',$datalist);
                $this->set('data',$dataKpa);
                if($blockload==1){
                    $this->_template->renderTemplate('blocklevelgraphajax');
                } else {
                    $this->_template->renderTemplate('blocklevelgraph');
                }        

            }elseif (in_array(12,$this->user['role_ids'])) {
                $datalist = 0;
                $objCustom = new customratingreportModel();
                $userData = $objCustom->userStateZoneBlockCluster($userId,'cluster');
                $clusterload = 0;
                if($userData){
                    $datalist = 1;
                    $zoneList = $this->db->array_grouping($userData,'zone_id');
                    $blockList = $this->db->array_grouping($userData,'block_id');
                    $stateId = $userData['0']['state_id'];
                    $roundListState = $objCustom->getRoundState($stateId);
                    $this->set('roundlist', $roundListState);
                    $round = $roundListState[0]['aqs_round'];
                    
                    $stateName = $userData['0']['state_name'];
                    $zoneId = $userData['0']['zone_id']; 
                    $zoneName = $userData['0']['zone_name'];
                    $blockZone = $objCustom->userStateZoneBlockCluster($userId,'cluster','',$zoneId);
                    $blockList = $this->db->array_grouping($blockZone,'block_id');
                    $block_id = $blockZone['0']['block_id'];
                    $block = $blockZone['0']['network_name'];
                    $blockCluster = $objCustom->userStateZoneBlockCluster($userId,'cluster',$block_id);
                    $clusterList = $this->db->array_grouping($blockCluster,'cluster_id');
                    $cluster_id = $blockCluster['0']['cluster_id'];
                    $cluster = $blockCluster['0']['province_name'];
                    if(isset($_POST['zone_id']) && !empty($_POST['zone_id'])){
                        $block_id = $_POST['blockId'];
                        $block = $_POST['block'];
                        $zoneId = $_POST['zone_id']; 
                        $cluster_id = $_POST['clusterId'];
                        $cluster = $_POST['cluster'];
                        $round = $_POST['round'];
                        $clusterload = 1;
                    } 

                    $this->set('zone_id', $zoneId);
                    $this->set('zone_name', $zoneName);
                    $this->set('blocklist', $blockList);
                    $this->set('clusterlist', $clusterList);
                    $this->set('zonelist', $zoneList);
                    $this->set('blockId', $block_id);
                    $this->set('block', $block);
                    $this->set('clusterId', $cluster_id);
                    $this->set('cluster', $cluster);
                    $data = $objCustom->getKpaZoneData($stateId,$zoneId,$round,$block_id ,$cluster_id);
                    $data=$this->db->array_grouping($data,"kpa_id");
                    foreach ($data as $key => $val){
                        $dataKpa[$key]=$this->db->array_grouping($val,"rating");
                    }
                }
                
                if(!isset($dataKpa)){
                    $dataKpa='';
                }
                $this->set('datalist',$datalist);
                $this->set('data',$dataKpa);
                $this->_template->renderTemplate('clusterlevelgraph');
                if($clusterload==1){
                    $this->_template->renderTemplate('clusterlevelgraphajax');
                }     
            } else{
                $this->_template->renderTemplate('404','index');
            }
    }
    //It shows block data
    function blockAction(){
          $this->_template->addHeaderStyle ( 'rating-report.css' );
          if(isset($_POST['blockId']) && !empty($_POST['blockId'])){
                    $block_id = $_POST['blockId'];
                    $block = $_POST['block'];
                    $blockload = 1;
                } else {
                    $block_id = 27;
                    $blockload = 0;
                    $block = "SATTARI";
                }
                $this->set('blockId', $block_id);
                $this->set('block', $block);
                $objCustom = new customratingreportModel();
                $data = $objCustom->getKpaZoneData(6,6, 1, $block_id);
                $data=$this->db->array_grouping($data,"kpa_id");
                //print_r($data);die;
                foreach ($data as $key => $val){
                    $dataKpa[$key]=$this->db->array_grouping($val,"rating");
                }
                $this->set('data',$dataKpa);
                $this->_template->renderTemplate('blocklevelgraph');
                if($blockload==1){
                    $this->_template->renderTemplate('blocklevelgraphajax');
                }   
                
    }
    //It shows cluster data
    function clusterAction(){
        $this->_template->addHeaderStyle ( 'rating-report.css' );
        $objCustom = new customratingreportModel();
        if(isset($_POST['blockId']) && !empty($_POST['blockId'])){
            $blockId = $_POST['blockId'];
            $block = $_POST['block'];
        } else {
            $blockId = 27;
            $block = 'SATTARI';
        }
        $clusterForBlockOld = $objCustom->getClusterListBlock(1,$blockId);
        $clusterForBlockNew = $this->db->array_grouping($clusterForBlockOld,"cluster_id");
        $this->set('clusterList', $clusterForBlockNew);
        if(isset($_POST['clusterId']) && !empty($_POST['clusterId'])){
            $cluster_id = $_POST['clusterId'];
            $cluster = $_POST['cluster'];
        } else {
            $cluster_id = $clusterForBlockOld[0]['cluster_id'];
            $cluster = $clusterForBlockOld[0]['cluster_name'];
        }

        if((isset($_POST['clusterId']) && !empty($_POST['clusterId'])) || (isset($_POST['blockId']) && !empty($_POST['blockId']))){
            $clusterload = 1;
        } else {
            $clusterload = 0;
        }

        $this->set('clusterId', $cluster_id);
        $this->set('cluster', $cluster);
        $this->set('block', $block);
        $data = $objCustom->getKpaZoneData(6, 1, '',$cluster_id);
        $data=$this->db->array_grouping($data,"kpa_id");
        //print_r($data);die;
        foreach ($data as $key => $val){
            $dataKpa[$key]=$this->db->array_grouping($val,"rating");
        }
        $this->set('data',$dataKpa);
        $this->_template->renderTemplate('clusterlevelgraph');
        if($clusterload==1){
            $this->_template->renderTemplate('clusterlevelgraphajax');
        }                 
    }
    //It shows state data
    function stateAction(){
        $this->_template->addHeaderStyle ( 'rating-report.css' );
        $objCustom = new customratingreportModel();
        $dataBlock = $objCustom->getStateKpaZoneData(1);
        $dataBlock = $this->db->array_grouping($dataBlock,"kpa_id");
        foreach ($dataBlock as $key => $val){
            $dataKpaBlock[$key]=$this->db->array_grouping($val,"zone_name");
            foreach ($dataKpaBlock[$key] as $key1 => $val1){
                $dataKpaBlockData[$key][$key1]=$this->db->array_grouping($val1,"rating");

            }
        }
        

        $this->set('block_data',$dataKpaBlockData);

        $this->_template->renderTemplate('statelevelgraph');                
    }
    //It shows cluster list for selected blocks
    function clusterListAction(){
        $blockId = $_POST['blockId'];
        $objCustom = new customratingreportModel();
        $userId = $this->user['user_id'];
        $clusterForBlockOld = $objCustom->getClusterListBlock($userId,$blockId);
        $clusterForBlockNew = $this->db->array_grouping($clusterForBlockOld,"cluster_id");
        $this->set('clusterList', $clusterForBlockNew);
    }
    //It shows cluster list for selected state
    function blockListAction(){
        $zoneId = $_POST['zone_id'];
        $objCustom = new customratingreportModel();
        $userId = $this->user['user_id'];
        $blockForZoneOld = $objCustom->getBlockListZone($userId,$zoneId);
        $blockForZoneNew = $this->db->array_grouping($blockForZoneOld,"block_id");
        $this->set('blockList', $blockForZoneNew);
    }
    //It shows cluster data for selected blocks        
    function getClusterDataAction() {
        $this->apiResult ["status"] = 1;
        $objCustom = new customratingreportModel();
        $cdata = $objCustom->getKpaBlockClusterData($_POST);
        $cdata=$this->db->array_grouping($cdata,"cluster_name");
        foreach ($cdata as $key => $val){
            if($_POST['type']=='level2'){
                $clusterData[$key]=$this->db->array_grouping($val,"kd1_part2rating");
            } else {
                $clusterData[$key]=$this->db->array_grouping($val,"rating");
            }
        }
        $this->set('request', $_POST);
        $this->set('data',$clusterData);
        
    }
    //It shows block list for selected state
    function getBlockDataAction() { 
        $this->apiResult ["status"] = 1;
        $objCustom = new customratingreportModel();
        $zone_id = $objCustom->getZoneId($_POST['zone_name']);
        $cdata = $objCustom->getStateKpaZoneBlockData($_POST,$zone_id[0]['zone_id'],1);
        $cdata=$this->db->array_grouping($cdata,"block_name");
        foreach ($cdata as $key => $val){
            if($_POST['type']=='level2'){
                $clusterData[$key]=$this->db->array_grouping($val,"kd1_part2rating");
            } else {
                $clusterData[$key]=$this->db->array_grouping($val,"rating");
            }
        }
        $this->set('request', $_POST);
        $this->set('data',$clusterData);
        
    }
    //It shows school details for selected cluster
    function getSchoolDataAction() {
        $this->apiResult ["status"] = 1;
        $objCustom = new customratingreportModel();
        $cid = $objCustom->getClusterId($_POST['cluster_name']);
        $sdata = $objCustom->getKpaBlockSchoolData($_POST,$cid);
        $this->set('kpa',$sdata['0']['kpa_name']);
        $clientData = $this->db->array_grouping($sdata,"client_id");
        $schoolName = $clientData;
        foreach ($clientData as $key => $val){
            $clientData[$key]=$this->db->array_grouping($val,"judgement_statement_instance_id");
        }
        $headerData = $this->db->array_grouping($sdata,"judgement_statement_instance_id");
        foreach ($headerData as $key =>$val){
            if($_POST['type']=='level2'){
                $leveldata[$key] = $this->db->array_grouping($val,"kd1_part2rating");
            } else {
                $leveldata[$key] = $this->db->array_grouping($val,"rating");
            }
            
        }
        
        $this->set('request', $_POST);
        $this->set('data',$clientData);
        $this->set('header',$headerData);
        $this->set('lcount', $leveldata);
        $this->set('sname', $schoolName);

    }
    
	
}
