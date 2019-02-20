<?php
/**
 * Reasons: Manage data for blockdata manage
 * Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 */
class networkModel extends Model{
	
        /*
        * @Purpose: Function to get the network list
        */
	public function getNetworkList(){
		$res=$this->db->get_results("select * from d_network order by network_name");
		return $res?$res:array();
	}
        
        /*
        * @Purpose: Function to get the zone list
        */
        public function getZoneList(){
		$res=$this->db->get_results("select * from d_zone order by zone_name");
		return $res?$res:array();
	}
        
        /*
        * @Purpose: Function to get the state list
        */
        public function getStateList(){
		$res=$this->db->get_results("select * from d_state order by state_name");
		return $res?$res:array();
	}
        
        /*
        * @Purpose: Function to get the block list
        */
        public function getBlockList(){
		$res=$this->db->get_results("select * from d_block order by block_name");
		return $res?$res:array();
	}
	
	        
        /* 
         * @Purpose : Function to get networks
         * @params : $args
         */
        public function getNetworks($args=array()){
		$args=$this->parse_arg($args,array("name_like"=>"","max_rows"=>10,"page"=>1,"order_by"=>"name","order_type"=>"asc"));
		$order_by=array("name"=>"statename","noOfClients"=>"noOfClients","province"=>"clustername");
		$sqlArgs=array();
		$sql=" SELECT SQL_CALC_FOUND_ROWS a.*
FROM   (SELECT *
        FROM   ((SELECT s.state_id          AS stateid,
                        s.state_name        AS statename,
                        ''                  AS zoneid,
                        ''                  AS zonename,
                        ''                  AS blockid,
                        ''                  AS blockname,
                        ''                  AS clusterid,
                        ''                  AS clustername,
                        Count(cs.client_id) AS noofclients,
                        0                   AS clientinzone,
                        0                   AS clientinblock,
                        0                   AS clientincluster
                 FROM   d_state s
                        LEFT JOIN h_client_state cs
                               ON cs.state_id = s.state_id
                        LEFT JOIN d_client c
                               ON c.client_id = cs.client_id
                 GROUP  BY s.state_id)
                UNION
                (SELECT s.state_id           AS stateid,
                        s.state_name         AS statename,
                        z.zone_id            AS zoneid,
                        z.zone_name          AS zonename,
                        ''                   AS blockid,
                        ''                   AS blockname,
                        ''                   AS clusterid,
                        ''                   AS clustername,
                        Count(cs.client_id)  AS noofclients,
                        Count(hcz.client_id) AS clientinzone,
                        0                    AS clientinblock,
                        0                    AS clientincluster
                 FROM   d_state s
                        LEFT JOIN h_client_state cs
                               ON cs.state_id = s.state_id
                        LEFT JOIN d_client c
                               ON c.client_id = cs.client_id
                        LEFT JOIN h_zone_state hsz
                               ON hsz.state_id = s.state_id
                        LEFT JOIN d_zone z
                               ON z.zone_id = hsz.zone_id
                        LEFT JOIN h_client_zone hcz
                               ON hcz.client_id = c.client_id
                                  AND hcz.zone_id = z.zone_id
                                  where z.zone_id is not null and z.zone_name is not null
                 GROUP  BY z.zone_id)
                UNION
                (SELECT s.state_id           AS stateid,
                        s.state_name         AS statename,
                        z.zone_id            AS zoneid,
                        z.zone_name          AS zonename,
                        n.network_id         AS blockid,
                        n.network_name       AS blockname,
                        ''                   AS clusterid,
                        ''                   AS clustername,
                        Count(cs.client_id)  AS noofclients,
                        Count(hcz.client_id) AS clientinzone,
                        Count(hcn.client_id) AS clientinblock,
                        0                    AS clientincluster
                 FROM   d_state s
                        LEFT JOIN h_client_state cs
                               ON cs.state_id = s.state_id
                        LEFT JOIN d_client c
                               ON c.client_id = cs.client_id
                        LEFT JOIN h_zone_state hsz
                               ON hsz.state_id = s.state_id
                        LEFT JOIN d_zone z
                               ON z.zone_id = hsz.zone_id
                        LEFT JOIN h_client_zone hcz
                               ON hcz.client_id = c.client_id
                                  AND hcz.zone_id = z.zone_id
                        LEFT JOIN h_network_zone_state hnzs
                               ON hnzs.zone_id = z.zone_id
                        LEFT JOIN d_network n
                               ON n.network_id = hnzs.network_id
                        LEFT JOIN h_client_network hcn
                               ON hcn.network_id = n.network_id
                                  AND hcn.client_id = c.client_id
                                  where n.network_id is not null and n.network_name is not null
                 GROUP  BY n.network_id)
                UNION
                (SELECT s.state_id           AS stateid,
                        s.state_name         AS statename,
                        z.zone_id            AS zoneid,
                        z.zone_name          AS zonename,
                        n.network_id         AS blockid,
                        n.network_name       AS blockname,
                        p.province_id        AS clusterid,
                        p.province_name      AS clustername,
                        Count(cs.client_id)  AS noofclients,
                        Count(hcz.client_id) AS clientinzone,
                        Count(hcn.client_id) AS clientinblock,
                        Count(hcp.client_id) AS clientincluster
                 FROM   d_state s
                        LEFT JOIN h_client_state cs
                               ON cs.state_id = s.state_id
                        LEFT JOIN d_client c
                               ON c.client_id = cs.client_id
                        LEFT JOIN h_zone_state hsz
                               ON hsz.state_id = s.state_id
                        LEFT JOIN d_zone z
                               ON z.zone_id = hsz.zone_id
                        LEFT JOIN h_client_zone hcz
                               ON hcz.client_id = c.client_id
                                  AND hcz.zone_id = z.zone_id
                        LEFT JOIN h_network_zone_state hnzs
                               ON hnzs.zone_id = z.zone_id
                        LEFT JOIN d_network n
                               ON n.network_id = hnzs.network_id
                        LEFT JOIN h_client_network hcn
                               ON hcn.network_id = n.network_id
                                  AND hcn.client_id = c.client_id
                        LEFT JOIN h_cluster_block_zone_state hcbzs
                               ON hcbzs.block_id = n.network_id
                        LEFT JOIN d_province p
                               ON p.province_id = hcbzs.cluster_id
                        LEFT JOIN h_client_province hcp
                               ON hcp.province_id = p.province_id
                                  AND hcp.client_id = c.client_id
                                  where p.province_id is not null and p.province_name is not null
                 GROUP  BY p.province_id)) a
        ORDER  BY statename,
                  zonename,
                  blockname,
                  clustername) a
WHERE  1 = 1 ";
                if($args['state_like']!=""){
			$sql.="and statename like ? ";
			$sqlArgs[] = "%" . $args['state_like'] . "%";
		}
		if($args['name_like']!=""){
			$sql.="and zonename like ? ";
			$sqlArgs[] = "%" . $args['name_like'] . "%";
		}
                if($args['block_like']!=""){
			$sql.="and blockname like ? ";
			$sqlArgs[] = "%" . $args['block_like'] . "%";
		}
		if($args['province_like']!=""){
			$sql.="and clustername like ? ";
			$sqlArgs[] = "%" . $args['province_like'] . "%";
		}
		$sql.=" group by  stateid,zoneid,blockid,clusterid order by ".(isset($order_by[$args["order_by"]])?$order_by[$args["order_by"]]:"statename").($args["order_type"]=="desc"?" desc ":" asc ").(",zonename,blockname,clustername").$this->limit_query($args['max_rows'],$args['page']);
                $res= $this->db->get_results($sql,$sqlArgs);
		$this->setPageCount($args['max_rows']);
		return $res;
	}
	
        /* 
        * Function to get block using client-id
        * @params: $client_id
        */
	function getNetworkByClientId($client_id){
		return $this->db->get_row("select n.* from d_network n inner join h_client_network cn on n.network_id=cn.network_id where cn.client_id=?;",array($client_id));
	}
	
        
        /* 
         * @Purpose : Function to get network
         * @Params : $name,$exclude_id
         */
	function getNetworkByClientName($name,$exclude_id=0){
		return $this->db->get_row("select n.* from d_network n where n.network_name=? ".($exclude_id>0?"and n.network_id!=$exclude_id":"").";",array($name));
	}
        
        
        /* 
         * @Purpose : Function to get zone
         * @Params : $name,$exclude_id
         */
        function getZoneByName($name,$exclude_id=0){
		return $this->db->get_row("select z.* from d_zone z where z.zone_name=? ".($exclude_id>0?"and z.zone_id!=$exclude_id":"").";",array($name));
	}
        
        
        /* 
         * @Purpose : Function to check zone exist
         * @Params : $zone_id, $state_id
         */
        function isZoneExistInStatusId($zone_id, $state_id){
		return $this->db->get_row("select n.* from h_zone_state n where n.zone_id=? and n.state_id= ?;", array($zone_id, $state_id));
	}
        
        /* 
         * @Purpose : Function to check block exist
         * @Params : $network_id, $zone_id, $state_id
         */
        function isBlockExistInZoneId($network_id, $zone_id, $state_id){
		return $this->db->get_row("select n.* from h_network_zone_state n where n.network_id=? and n.zone_id= ? and n.state_id= ?;", array($network_id, $zone_id, $state_id));
	}
        
        /* 
         * @Purpose : Function to check cluster exist
         * @Params : $province_id, $network_id, $zone_id, $state_id
         */
        function isClusterExistInBlockZoneState($province_id, $network_id, $zone_id, $state_id){
            return $this->db->get_row("select n.* from h_cluster_block_zone_state n where n.cluster_id=? and n.block_id=? and n.zone_id= ? and n.state_id= ?;", array($province_id, $network_id, $zone_id, $state_id));
	
        }
	
        /* 
         * @Purpose : Function to create network
         * @Params : $name
         */
	function createNetwork($name){
		if($this->db->insert("d_network",array("network_name"=>$name)))
			return $this->db->get_last_insert_id();
		else
			return false;
	}
        
        /* 
         * @Purpose : Function to create zone
         * @Params : $name
         */
        function createZone($name){
		if($this->db->insert("d_zone",array("zone_name"=>$name)))
			return $this->db->get_last_insert_id();
		else
			return false;
	}
        
        /* 
         * @Purpose : Function to add network to state
         * @Params : $network_id, $state_id
         */
        function addNetworkToState($network_id,$state_id){		
		if($this->db->insert("h_network_state",array("network_id"=>$network_id,"state_id"=>$state_id)))
			return $this->db->get_last_insert_id();
		else
			return false;
	}
        
         /* 
         * @Purpose : Function to add zone to state
         * @Params : $network_id, $state_id
         */
        function addZoneToState($network_id,$state_id){		
		if($this->db->insert("h_zone_state",array("zone_id"=>$network_id,"state_id"=>$state_id)))
			return $this->db->get_last_insert_id();
		else
			return false;
	}
        
        
        /* 
         * @Purpose : Function to add block to zone
         * @Params : $network_id, $zone_id, $state_id
         */
        function addBlockToZone($network_id, $zone_id, $state_id){		
		if($this->db->insert("h_network_zone_state",array("network_id"=>$network_id,"zone_id"=>$zone_id, "state_id" => $state_id)))
			return $this->db->get_last_insert_id();
		else
			return false;
	}
	
        
        /* 
         * @Purpose : Function to update network
         * @Params : $network_id,$name
         */
	function updateNetwork($network_id,$name){
		return $this->db->update("d_network",array("network_name"=>$name),array("network_id"=>$network_id));
	}
        
        /* 
         * @Purpose : Function to update state
         * @Params : $state_id,$name
         */
	function updateState($state_id,$name){
		return $this->db->update("d_state",array("state_name"=>$name),array("state_id"=>$state_id));
	}
        
        /* 
         * @Purpose : Function to update zone
         * @Params : $zone_id,$name
         */
        function updateZone($zone_id,$name){
		return $this->db->update("d_zone",array("zone_name"=>$name),array("zone_id"=>$zone_id));
	}
        
        /* 
         * @Purpose : Function to update province/hub
         * @Params : $province_id,$name
         */
	function updateProvince($province_id,$name){
		return $this->db->update("d_province",array("province_name"=>$name),array("province_id"=>$province_id));
	}
        
         /* 
         * @Purpose : Function to get state
         * @Params : $state_id
         */
	function getStateById($state_id){
		$sql="select n.* from d_state n where n.state_id=?"; return $this->db->get_row($sql,array($state_id));
	}
        
        /* 
        * @Purpose : Function to get zone
        * @Params : $zone_id
        */
        function getZoneById($zone_id){
		$sql="select n.*,d.state_name from d_zone n left join h_zone_state hs on n.zone_id=hs.zone_id left join d_state d on d.state_id=hs.state_id where n.zone_id=?"; return $this->db->get_row($sql,array($zone_id));
	}
        
        /* 
         * @Purpose : Function to get network
         * @Params : $network_id
         */
	function getNetworkByIds($network_id){
		$sql="select n.*,d.state_name,z.zone_name 
				from d_network n
				left join h_network_zone_state hnzs on hnzs.network_id=n.network_id
                                left join d_state d on d.state_id=hnzs.state_id
                                left join d_zone z on z.zone_id=hnzs.zone_id
                		where n.network_id=?
                group by n.network_id;";
		return $this->db->get_row($sql,array($network_id));
	}
        
        /*
         * @Purpose : Function to get network
         * @Params: $network_id 
         */
	function getNetworkById($network_id){
		$sql="select n.*,d.state_name,z.zone_name, count(cn.client_id) as noOfClients
				from d_network n
				left join h_client_network cn on cn.network_id=n.network_id
                                left join h_network_zone_state hnzs on hnzs.network_id=cn.network_id
                                left join d_state d on d.state_id=hnzs.state_id
                                left join d_zone z on z.zone_id=hnzs.zone_id
                left join d_client c on c.client_id=cn.client_id
				where n.network_id=?
                group by n.network_id;";
		return $this->db->get_row($sql,array($network_id));
	}
        
        
        /*
         * @Purpose : Function to get province
         * @Params: $province_id 
         */
	function getProvinceById($province_id){
                $sql="select n.*,d.state_name,z.zone_name,dn.network_name from d_province n left join h_cluster_block_zone_state hcbzs on hcbzs.cluster_id=n.province_id left join d_state d on d.state_id=hcbzs.state_id left join d_zone z on z.zone_id=hcbzs.zone_id left join d_network dn on dn.network_id=hcbzs.block_id where n.province_id=?";
                $res = $this->db->get_row($sql,array($province_id));
		return $res ? $res :array();
	}
        
        
        /* 
        * @Purpose : Function to get province
        * @Params: $province_name
        */
	function getProvinceByName($province_name){
		$sql="Select province_name, province_id from d_province where province_name=?";
		$res = $this->db->get_row($sql,array($province_name));
		return $res?$res:array();
	}
        
        
        /* 
        * @Purpose : Function to create province
        * @Params: $province_name
        */
	function createProvince($province_name){		
		if($this->db->insert("d_province",array("province_name"=>$province_name,"is_active"=>1)))
			return $this->db->get_last_insert_id();
		else
			return false;
	}
	
        
        /* 
        * @Purpose : Function to get province
        * @Params: $cluster_id, $network_id, $zone_id, $state_id
        */
        function addProvinceToBlock($cluster_id, $network_id, $zone_id, $state_id){		
		if($this->db->insert("h_cluster_block_zone_state",array("cluster_id"=>$cluster_id,'block_id'=>$network_id,'zone_id'=>$zone_id,"state_id"=>$state_id)))
			return $this->db->get_last_insert_id();
		else
			return false;
	}
        
        
        /*
        * @Purpose: Function to get the province list
        */
	public function getProvinceList(){
		$res=$this->db->get_results("select * from d_province");
		return $res?$res:array();
	}
        
        
        /* 
        * @Purpose : Function to get province
        * @Params: $block_id
        */
	function getProvinces($block_id){

            $sql = "select a.province_id,b.block_id,a.province_name from d_province a inner join h_cluster_block_zone_state b on a.province_id=b.cluster_id
where block_id = ?";
		$res = $this->db->get_results($sql,array($block_id));
		return $res?$res:array();
	} 
        
        
        /* 
        * @Purpose : Function to get zones
        * @Params: $state_id
        */
        function getZonesInStates($state_id){
		$sql = "select a.zone_id,b.state_id,a.zone_name from d_zone a inner join h_zone_state b on a.zone_id=b.zone_id
where state_id = ?";
		$res = $this->db->get_results($sql,array($state_id));
		return $res?$res:array();
	} 
        
        
        /* 
        * @Purpose : Function to create state dropdown incase of edit when dropdown retains value
        * @Params: $userID, $usertype_id
        */
        public function getStateForAllAdminEdit($userID,$usertype_id){
            
                $sql_usetyp="select DISTINCT user_type_id from h_user_admin_levels WHERE user_id=?";
                $res_usetyp=$this->db->get_results($sql_usetyp,array($userID));
                
                $sql_state="select DISTINCT state_id from h_user_admin_levels WHERE user_id=?";
                $res_state=$this->db->get_results($sql_state,array($userID));
                
                $sql_zone="select DISTINCT zone_id from h_user_admin_levels WHERE user_id=?";
                $res_zone=$this->db->get_results($sql_zone,array($userID));
                
                $sql_block="select DISTINCT block_id from h_user_admin_levels WHERE user_id=?";
                $res_block=$this->db->get_results($sql_block,array($userID));
                
                $sql_cluster="select DISTINCT cluster_id from h_user_admin_levels WHERE user_id=?";
                $res_cluster=$this->db->get_results($sql_cluster,array($userID));
                
                if($usertype_id==1){
                    $sql_st="select DISTINCT state_id from h_user_admin_levels WHERE user_id!=? AND state_id!='NULL' AND zone_id IS NULL AND block_id IS NULL AND cluster_id IS NULL";
                    $res_st=$this->db->get_results($sql_st,array($userID));
                   
                    if(!empty($res_st)){
                    foreach($res_st as $res_st1){
                        $selected_stateIDS[]=$res_st1['state_id'];
                    }
                    $stateID_NOTIN_arr=implode(',',$selected_stateIDS);
                    $statenotin="AND s.state_id NOT IN ($stateID_NOTIN_arr)";
                    }else{
                        $statenotin="";
                    }
                }else {
                  $statenotin="";      
                }
               
               $sql="select distinct s.state_id ,s.state_name from d_state as s LEFT JOIN 
                h_user_admin_levels as ual ON s.state_id=ual.state_id WHERE 1 $statenotin order by s.state_id asc;";
                $res['newData'] = $this->db->get_results($sql);
                if(!empty($res_usetyp)) {
                    $res_usetyp = array_column($res_usetyp,'user_type_id');
                    $res['selecteduserTypeId'] = $res_usetyp;
                    
                }
                
                if(!empty($res_state)) {
                    $res_state = array_column($res_state,'state_id');
                    $res['selectedstateId'] = $res_state;
                }
                
                if(!empty($res_zone)) {
                    $res_zone = array_column($res_zone,'zone_id');
                    $res['selectedZones'] = $res_zone;
                }
                
                if(!empty($res_block)) {
                    $res_block = array_column($res_block,'block_id');
                    $res['selectedBlocks'] = $res_block;
                }
                
                if(!empty($res_cluster)) {
                    $res_cluster = array_column($res_cluster,'cluster_id');
                    $res['selectedClusters'] = $res_cluster;
                }
                
		return $res?$res:array();
                 
        
    }
        
        /* 
         * @Purpose :  Function to create zone dropdown in case of adding new user
         * @Params : $state_id, $usertype_id
        */
        function getZonesInStatesUser($state_id,$usertype_id){
            
                 $sql_zoneIds = "select distinct zone_id from h_user_admin_levels where state_id=? AND zone_id!='NULL' AND block_id IS NULL AND cluster_id IS NULL";
		$res_zoneIds = $this->db->get_results($sql_zoneIds,array($state_id));
                $ids=array();
                foreach($res_zoneIds as $res_zoneId){
                    $ids[]=$res_zoneId['zone_id'];
                }
                
                if(count($ids)>1){
                $zone_str=implode(',',$ids);
                
                $newzone_str =$zone_str; 
                $zone_qry="AND a.zone_id NOT IN ($newzone_str)";
                }else if(count($ids)==1){
                $newzone_str =$ids[0];
                $zone_qry="AND a.zone_id NOT IN ($newzone_str)";
                }else{
                $zone_qry="";    
                }
                
                if($usertype_id==2){
                 $sql = "select a.zone_id,b.state_id,a.zone_name from d_zone a inner join h_zone_state b on a.zone_id=b.zone_id
where b.state_id = ? $zone_qry";
                }else if($usertype_id==3){
                
                 $sql_zoneIds = "select distinct block_id from h_user_admin_levels where state_id=? AND zone_id!='NULL' AND block_id IS NOT NULL AND cluster_id IS NULL";
		$res_zoneIds = $this->db->get_results($sql_zoneIds,array($state_id));
                $ids=array();
                foreach($res_zoneIds as $res_zoneId){
                    $ids[]=$res_zoneId['block_id'];
                }
                if(count($ids)>1){
                $zone_str=implode(',',$ids);
                
                $newzone_str =$zone_str; 
                $zone_qry="AND b.network_id  NOT IN ($newzone_str)";
                }else if(count($ids)==1){
                $newzone_str =$ids[0];
                $zone_qry="AND b.network_id  NOT IN ($newzone_str)";
                }else{
                $zone_qry="";    
                }
                
		$sql = "select distinct a.zone_id,a.zone_name from d_zone a 
                LEFT join h_network_zone_state b on a.zone_id=b.zone_id
                where b.state_id = ? $zone_qry ";
                }else if($usertype_id==4){
                
                 $sql_zoneIds = "select distinct cluster_id from h_user_admin_levels where state_id=? AND zone_id!='NULL' AND block_id IS NOT NULL AND cluster_id IS NOT NULL";
		$res_zoneIds = $this->db->get_results($sql_zoneIds,array($state_id));
                $ids=array();
                foreach($res_zoneIds as $res_zoneId){
                    $ids[]=$res_zoneId['cluster_id'];
                }
                if(count($ids)>1){
                $zone_str=implode(',',$ids);
                
                $newzone_str =$zone_str; 
                $zone_qry="AND b.cluster_id  NOT IN ($newzone_str)";
                }else if(count($ids)==1){
                $newzone_str =$ids[0];
                $zone_qry="AND b.cluster_id  NOT IN ($newzone_str)";
                }else{
                $zone_qry="";    
                }
                
		$sql = "select distinct a.zone_id,a.zone_name from d_zone a 
                LEFT join h_cluster_block_zone_state b on a.zone_id=b.zone_id
                where b.state_id = ? $zone_qry ";
                }else{
                   $sql = "select a.zone_id,b.state_id,a.zone_name from d_zone a inner join h_zone_state b on a.zone_id=b.zone_id
where b.state_id = ?"; 
                }
                
		$res = $this->db->get_results($sql,array($state_id));
		return $res?$res:array();
	}
        
        
        /* 
        * @Purpose : Function to create zone dropdown incase of edit when dropdown retains value
        * @Params: $userID, $state_id, $usertype_id
        */
        function getZonesInStatesUserEdit($userID,$state_id,$usertype_id){
            
                 $sql_zoneIds4 = "select distinct zone_id from h_user_admin_levels where state_id=? AND zone_id!='NULL' AND block_id IS NULL AND cluster_id IS NULL AND user_id!=?";
		$res_zoneIds4 = $this->db->get_results($sql_zoneIds4,array($state_id,$userID));
                $ids4=array();
                foreach($res_zoneIds4 as $res_zoneId4){
                    $ids4[]=$res_zoneId4['zone_id'];
                }
                if(count($ids4)>1){
                $zone_str=implode(',',$ids4);
                $newzone_str =$zone_str; 
                $zone_qry="AND a.zone_id NOT IN ($newzone_str)";
                }else if(count($ids4)==1){
                $newzone_str =$ids4[0];
                $zone_qry="AND a.zone_id NOT IN ($newzone_str)";
                }else{
                $zone_qry="";    
                }
                
                
                if($usertype_id==2){
                $sql_zoneIds = "select distinct zone_id from h_user_admin_levels where user_id=? AND zone_id IS NOT NULL AND block_id IS NULL AND cluster_id IS NULL";
                }else if($usertype_id==3){
                 $sql_zoneIds = "select distinct zone_id from h_user_admin_levels where user_id=? AND block_id IS NOT NULL AND cluster_id IS NULL";
                }else if($usertype_id==4){
                 $sql_zoneIds = "select distinct zone_id from h_user_admin_levels where user_id=? AND cluster_id IS NOT NULL";
                }
                
                $res_zoneIds = $this->db->get_results($sql_zoneIds,array($userID));
                $res_zoneIds?$res_zoneIds:array();
                
                
                
                if($usertype_id==2){
		 $sql = "select a.zone_id,b.state_id,a.zone_name from d_zone a inner join h_zone_state b on a.zone_id=b.zone_id
where state_id = ? $zone_qry";
                }else{
                   $sql = "select a.zone_id,b.state_id,a.zone_name from d_zone a inner join h_zone_state b on a.zone_id=b.zone_id
where state_id = ?"; 
                }
                
                $res['newZones'] = $this->db->get_results($sql,array($state_id));
                if(!empty($res_zoneIds)) {
                    $res_zoneIds = array_column($res_zoneIds,'zone_id');
                    $res['selectedZones'] = $res_zoneIds;
                }
                
                
                $sql_usetyp="select DISTINCT user_type_id from h_user_admin_levels WHERE user_id=?";
                $res_usetyp=$this->db->get_results($sql_usetyp,array($userID));
                
                if(!empty($res_usetyp)) {
                    $res_usetyp = array_column($res_usetyp,'user_type_id');
                    $res['selecteduserTypeId'] = $res_usetyp;
                    
                }
                
		return $res?$res:array();
	}
        
        /* 
        * Function to create block dropdown in case of adding new user 
        *@params: $zone_id,$state_id,$usertype_id
        */
        function getBlocksInZonesUser($zone_id, $state_id, $usertype_id){
                
                $sql_blockIds = "select distinct block_id from h_user_admin_levels where state_id=$state_id AND  zone_id IN ($zone_id) AND block_id!='NULL' AND cluster_id IS NULL";
		$res_blockIds = $this->db->get_results($sql_blockIds);
                $ids_b=array();
                foreach($res_blockIds as $res_blockId){
                    $ids_b[]=$res_blockId['block_id'];
                }
                 
                if(count($ids_b)>1){
                 $block_str=implode(',',$ids_b);
                 $newblock_str =$block_str; 
                 $sqlstr="AND b.network_id NOT IN ($newblock_str)";
                }else if(count($ids_b)==1){
                  $newblock_str=$ids_b[0];
                  $sqlstr="AND b.network_id NOT IN ($newblock_str)";
                }else if(count($ids_b)==0){
                  $sqlstr=" ";
                }
                 
                 if($usertype_id==3){
                   $sql = "select a.network_id,b.zone_id,a.network_name from d_network a inner join h_network_zone_state b on a.network_id=b.network_id
   where b.state_id = $state_id and b.zone_id IN ( $zone_id ) $sqlstr  group by a.network_id";
                 }else if($usertype_id==4){
                     
                 $sql_blockIds = "select distinct cluster_id from h_user_admin_levels where state_id=$state_id AND  zone_id IN ($zone_id) AND block_id!='NULL' AND cluster_id IS NOT NULL";
		$res_blockIds = $this->db->get_results($sql_blockIds);
                $ids_b=array();
                foreach($res_blockIds as $res_blockId){
                    $ids_b[]=$res_blockId['cluster_id'];
                }
                
                if(count($ids_b)>1){
                 $block_str=implode(',',$ids_b);
                 $newblock_str =$block_str; 
                 $sqlstr="AND b.cluster_id NOT IN ($newblock_str)";
                }else if(count($ids_b)==1){
                  $newblock_str=$ids_b[0];
                  $sqlstr="AND b.cluster_id NOT IN ($newblock_str)";
                }else if(count($ids_b)==0){
                  $sqlstr=" ";
                }
                
                  $sql = "select a.network_id,b.zone_id,a.network_name from d_network a inner join h_cluster_block_zone_state b on a.network_id=b.block_id
   where b.state_id = $state_id and b.zone_id IN ( $zone_id ) $sqlstr group by a.network_id";  
                 }
		
		$res = $this->db->get_results($sql);
                return $res?$res:array();
	}
        
        
        /* 
        * @Purpose : Function to create block dropdown incase of edit when dropdown retains value
        * @Params: $userID,$zone_id, $state_id, $usertype_id
        */
        function getBlocksInZonesUserEdit($userID,$zone_id, $state_id, $usertype_id){ 
                
                if($usertype_id==3){
                $sql_zoneIds = "select distinct zone_id from h_user_admin_levels where block_id!='NULL' AND cluster_id IS NULL AND user_id!=?";
                }else if($usertype_id==4){
                  $sql_zoneIds = "select distinct zone_id from h_user_admin_levels where cluster_id!='NULL'   AND user_id!=?";   
                }
                $res_zoneIds = $this->db->get_results($sql_zoneIds,array($userID));
                $ids_z=array();
                foreach($res_zoneIds as $res_zoneId){
                    $ids_z[]=$res_zoneId['zone_id'];
                }
                                 
               if(count($ids_z)>1){
                 $zone_str=implode(',',$ids_z);
                 $newzone_str =$zone_str; 
                 $sqlstr_z="AND zone_id NOT IN ($newzone_str)";
                }else if(count($ids_z)==1){
                  $newzone_str=$ids_z[0];
                  $sqlstr_z="AND zone_id NOT IN ($newzone_str)";
                }else if(count($ids_z)==0){
                  $sqlstr_z=" ";
                }
                
                if($usertype_id==3){
                $sql_blockIds = "select distinct block_id from h_user_admin_levels where user_id=? AND cluster_id IS NULL";
                }else if($usertype_id==4){
                 $sql_blockIds = "select distinct block_id from h_user_admin_levels where user_id=? AND cluster_id IS NOT NULL";
                }
                $res_blockIds = $this->db->get_results($sql_blockIds,array($userID));
                $res_blockIds?$res_blockIds:array();
                
                
                if($usertype_id==3){
                $sql_b = "select distinct block_id from h_user_admin_levels where block_id!='NULL' AND user_id!=? AND cluster_id IS NULL";
                $res_b = $this->db->get_results($sql_b,array($userID));
                $res_b?$res_b:array();
                
                $ids_b1=array();
                foreach($res_b as $res_b1){
                    $ids_b1[]=$res_b1['block_id'];
                }
               
                if(count($ids_b1)>1){
                 $block_str1=implode(',',$ids_b1);
                 $newblock_str1 =$block_str1; 
                 $sqlstr1="AND b.network_id NOT IN ($newblock_str1)";
                }else if(count($ids_b1)==1){
                  $newblock_str1=$ids_b1[0];
                  $sqlstr1="AND b.network_id NOT IN ($newblock_str1)";
                }else if(count($ids_b1)==0){
                  $sqlstr1=" ";
                }
                }
               
                 if($usertype_id==3){
                    
                  $sql="select a.network_id,b.zone_id,a.network_name from d_network a left join h_network_zone_state b on a.network_id=b.network_id 
left join h_user_admin_levels c ON c.block_id=a.network_id
where b.state_id = $state_id  AND b.zone_id IN ($zone_id) $sqlstr1 group by a.network_id;";    
                 
                 }else if($usertype_id==4){
                   
                     
                $sql_blockIds11 = "select distinct block_id from h_user_admin_levels where user_id!=$userID AND state_id=$state_id AND  zone_id IN ($zone_id) AND block_id!='NULL' AND cluster_id IS NOT NULL";
		$res_blockIds11 = $this->db->get_results($sql_blockIds11);
                $ids_b11=array();
                foreach($res_blockIds11 as $res_blockId12){
                    $ids_b11[]=$res_blockId12['block_id'];
                }
                  
               
                if(count($ids_b11)>1){
                 $block_str11=implode(',',$ids_b11);
                 $newblock_str11 =$block_str11; 
                 $sqlstr11="AND b.block_id NOT IN ($newblock_str11)";
                }else if(count($ids_b11)==1){
                  $newblock_str11=$ids_b11[0];
                  $sqlstr11="AND b.block_id NOT IN ($newblock_str11)";
                }else if(count($ids_b11)==0){
                  $sqlstr11=" ";
                }    
                     
                 $sql_b = "select distinct cluster_id from h_user_admin_levels where block_id!='NULL' AND user_id!=? AND cluster_id IS NOT NULL";
                $res_b = $this->db->get_results($sql_b,array($userID));
                $res_b?$res_b:array();
                
                $ids_b1=array();
                foreach($res_b as $res_b1){
                    $ids_b1[]=$res_b1['cluster_id'];
                }
                if(count($ids_b1)>1){
                 $block_str1=implode(',',$ids_b1);
                 $newblock_str1 =$block_str1; 
                 $sqlstr1="AND b.cluster_id NOT IN ($newblock_str1)";
                }else if(count($ids_b1)==1){
                  $newblock_str1=$ids_b1[0];
                  $sqlstr1="AND b.cluster_id NOT IN ($newblock_str1)";
                }else if(count($ids_b1)==0){
                  $sqlstr1=" ";
                }
                
                
                $sql = "select a.network_id,b.zone_id,a.network_name from d_network a inner join h_cluster_block_zone_state b on a.network_id=b.block_id
   where b.state_id = $state_id and b.zone_id IN ( $zone_id ) $sqlstr1 group by a.network_id";
                 
                 }
		
		$res['newBlocks'] = $this->db->get_results($sql);
                if(!empty($res_blockIds)) {
                    $res_blockIds = array_column($res_blockIds,'block_id');
                    $res['selectedBlocks'] = $res_blockIds;
                }
                
                return $res?$res:array();
	}
        
        
        /* 
        * Function to create cluster dropdown in case of adding new user 
        * @params: $block_id,$zone_id, $state_id, $usertype_id
        */
        function getClustersInBlocksUser($block_id,$zone_id, $state_id, $usertype_id){
                
                $sql_clusterIds = "select distinct cluster_id from h_user_admin_levels where state_id=$state_id AND  zone_id IN ($zone_id) AND block_id IN ($block_id)  AND cluster_id!='NULL'";
                
		$res_clusterIds = $this->db->get_results($sql_clusterIds);
                $ids_b=array();
                foreach($res_clusterIds as $res_clusterId){
                    $ids_b[]=$res_clusterId['cluster_id'];
                } 
                if(count($ids_b)>1){
                 $block_str=implode(',',$ids_b);
                 $newblock_str =$block_str; 
                 $sqlstr="AND b.cluster_id NOT IN ($newblock_str)";
                }else if(count($ids_b)==1){
                  $newblock_str=$ids_b[0];
                  $sqlstr="AND b.cluster_id NOT IN ($newblock_str)";
                }else if(count($ids_b)==0){
                  $sqlstr=" ";
                }
                
                 $sql = "select distinct a.province_id,a.province_name,b.state_id,b.zone_id,b.block_id from d_province a inner join h_cluster_block_zone_state b on a.province_id=b.cluster_id where b.state_id = $state_id and  b.zone_id IN ( $zone_id ) and b.block_id IN ( $block_id ) $sqlstr group by a.province_id  order by        a.province_name asc ";
                 
                $res = $this->db->get_results($sql);
                return $res?$res:array();
	}
        
        
        /* 
        * @Purpose : Function to create cluster dropdown incase of edit when dropdown retains value
        * @Params: $userID,$block_id,$zone_id, $state_id, $usertype_id
        */
        function getClustersInBlocksUserEdit($userID,$block_id,$zone_id, $state_id, $usertype_id){
            
            
            $sql_blockIds = "select distinct cluster_id from h_user_admin_levels where cluster_id!='NULL'   AND user_id!=?";   
               
                $res_blockIds = $this->db->get_results($sql_blockIds,array($userID));
                $ids_b=array();
                foreach($res_blockIds as $res_blockId){
                    $ids_b[]=$res_blockId['cluster_id'];
                }
                                 
               if(count($ids_b)>1){
                 $block_str=implode(',',$ids_b);
                 $newblock_str =$block_str; 
                 $sqlstr_b="AND cluster_id NOT IN ($newblock_str)";
                }else if(count($ids_b)==1){
                  $newblock_str=$ids_b[0];
                  $sqlstr_b="AND cluster_id NOT IN ($newblock_str)";
                }else if(count($ids_b)==0){
                  $sqlstr_b=" ";
                }
                 
               $sql_clusterIds = "select distinct cluster_id from h_user_admin_levels where user_id=$userID AND cluster_id IS NOT NULL";
              
                $res_clusterIds = $this->db->get_results($sql_clusterIds);
                $res_clusterIds?$res_clusterIds:array();
                
               
                if($state_id!='' && $zone_id!='' && $block_id!=''){
                $sql = "select distinct a.province_id,a.province_name,b.state_id,b.zone_id,b.block_id from d_province a inner join h_cluster_block_zone_state b on a.province_id=b.cluster_id where b.state_id = $state_id and  b.zone_id IN ( $zone_id ) and b.block_id IN ( $block_id ) $sqlstr_b group by a.province_id  order by        a.province_name asc ";
               
                $res['newClusters'] = $this->db->get_results($sql);
                if(!empty($res_clusterIds)) {
                    $res_clusterIds = array_column($res_clusterIds,'cluster_id');
                    $res['selectedClusters'] = $res_clusterIds;
                }
                return $res?$res:array();
                }
	}
       
        
        /* 
        * @Purpose : Function to get block
        * @params: $state_id,$zone_id
        */
        function getBlocksInZones($state_id,$zone_id){
		 $sql = "select a.network_id,b.zone_id,a.network_name from d_network a inner join h_network_zone_state b on a.network_id=b.network_id
where b.state_id = ? and b.zone_id IN ( ? ) group by a.network_id";
		$res = $this->db->get_results($sql,array($state_id, $zone_id));
                return $res?$res:array();
	}
        
        /* 
        * @Purpose : Function to get cluster
        * @params: $block_id, $zone_id, $state_id
        */
        function  getClusterInZones($block_id, $zone_id, $state_id){
		$sql = "select a.province_id,b.block_id,a.province_name from d_province a inner join h_cluster_block_zone_state b on a.province_id=b.cluster_id
where block_id = ? and zone_id = ? and state_id=?";
		$res = $this->db->get_results($sql,array($block_id, $zone_id, $state_id));
		return $res?$res:array();
	} 
        

        /* 
        * @Purpose : Function to get multiprovinces
        * @Params : $network_ids
        */
        function getMultiProvinces($network_ids){
                $sql = "select a.province_id,b.block_id as network_id,a.province_name from d_province a inner join h_cluster_block_zone_state b on a.province_id=b.cluster_id";
                if(!substr_count($network_ids, 'all')) {
                    $network_ids = trim($network_ids,",");
                    $sql .= " where b.block_id IN (?)";
                }
		$res = $this->db->get_results($sql,array($network_ids));
		return $res?$res:array();
	}
        
        
        /* 
        * @Purpose : Function to get schools
        * @Params : $province_ids
        */
        function getSchools($province_ids){
		$sql = "select a.client_id,a.client_name from d_client a inner join h_client_province b on a.client_id=b.client_id";
                if(!substr_count($province_ids, 'all')) {
                    $sql .= " where b.province_id IN (?)";
                }
		$res = $this->db->get_results($sql,array($province_ids));
		return $res?$res:array();
	}
        
        /* 
        * @Purpose : Function to get schools
        * @Params : $network_ids
        */
        function getSchoolsByNetwork($network_ids){
		$sql = "select a.client_id,a.client_name from d_client a inner join h_client_network b on a.client_id=b.client_id";
                if(!substr_count($network_ids, 'all')) {
                    $sql .= " where b.network_id IN (?)";
                }
		$res = $this->db->get_results($sql,array($network_ids)); 
		return $res?$res:array();
	}
        
        /* 
        * @Purpose : Function to get province Field
        */
	function getProvinceField(){
		$html = '<dl class="fldList provinceField">
					<dt>Hub Name<span class="astric">*</span>:</dt>										
					<dd class="the-basics province inputHldr"><input type="text" value="" class="form-control typeahead tt-query" name="province_name[]" required /><a href="javascript:void(0)" class="delete_row"><i class="fa fa-times-circle"></i></a></dd>
				</dl>';
		return $html;		
	}
        
        
        /* 
        * @Purpose : Function to create state
        * @Params: $name
        */
        function createState($name){ 
		if($this->db->insert("d_state",array("state_name"=>$name)))
			return $this->db->get_last_insert_id();
		else
			return false;
        }
        
        
        /* 
        * @Purpose : Function to create block
        * @Params: $name
        */
        function createBlock($name){ 
		if($this->db->insert("d_block",array("block_name"=>$name)))
			return $this->db->get_last_insert_id();
		else
			return false;
	}
        
        
        /* 
        * @Purpose : Function to get states
        * @Params: $args
        */
        public function getStates($args=array()){
		$args=$this->parse_arg($args,array("name_like"=>"","max_rows"=>10,"page"=>1,"order_by"=>"name","order_type"=>"asc"));
		$order_by=array("name"=>"network_name","noOfClients"=>"noOfClients","province"=>"province_name");
		$sqlArgs=array();
		$sql="select SQL_CALC_FOUND_ROWS a.* from(
		
		select * from (
(select n.*, count(cn.client_id) as noOfClients,0 as clientInProvince,'' as province_id,''as province_name from d_network n left join h_client_network cn on cn.network_id=n.network_id left join d_client c on c.client_id=cn.client_id group by n.network_id )
 UNION 
 (select n.*, count(cn.client_id) as noOfClients,count(cp.client_id) as clientInProvince,IFNULL(p.province_id,''),IFNULL(p.province_name,'') from d_network n left join h_client_network cn on cn.network_id=n.network_id left join d_client c on c.client_id=cn.client_id	left join h_province_network pn on pn.network_id = n.network_id left join d_province p on p.province_id = pn.province_id left join h_client_province cp on cp.client_id = c.client_id and cp.province_id = pn.province_id group by n.network_id,p.province_id)
 ) a order by network_name,province_id 
) a WHERE 1=1  ";
		if($args['name_like']!=""){
			$sql.="and network_name like ? ";
			$sqlArgs[]=$args['name_like']."%";
		}
		if($args['province_like']!=""){
			$sql.="and province_name like ? ";
			$sqlArgs[]=$args['province_like']."%";
		}
		$sql.=" group by  network_id,province_id order by ".(isset($order_by[$args["order_by"]])?$order_by[$args["order_by"]]:"network_name").($args["order_type"]=="desc"?" desc ":" asc ").(",province_id").$this->limit_query($args['max_rows'],$args['page']);
		$res= $this->db->get_results($sql,$sqlArgs);
		$this->setPageCount($args['max_rows']);
		return $res;
	}
        
        
        /*
         * @Purpose : Function to get state
         * @Params : $name,$exclude_id
         */
        function getStateByClientName($name,$exclude_id=0){
		return $this->db->get_row("select n.* from d_state n where n.state_name=? ".($exclude_id>0?"and n.state_id!=$exclude_id":"").";",array($name));
	}
        
        /*
         * @Purpose : Function to get block
         * @Params : $name,$exclude_id
         */
        function getBlockByName($name,$exclude_id=0){
		return $this->db->get_row("select n.* from d_network n where n.network_name=? ".($exclude_id>0?"and n.network_id!=$exclude_id":"").";",array($name));
	}
        
        /*
         * @Purpose : Function for Goa Project for filter
         */
        function checkGoaStateName(){
            $name="Goa";
            $exclude_id=0;
            if (!empty($this->getStateByClientName($name))){
                return $this->db->get_results("select n.* from d_state n where n.state_name=? ".($exclude_id>0?"and n.state_id!=$exclude_id":"").";",array($name));
            }else{
                if($this->db->insert("d_state",array("state_name"=>$name)))
                        return $this->db->get_results("select n.* from d_state n where n.state_name=? ".($exclude_id>0?"and n.state_id!=$exclude_id":"").";",array($name));
            }
        }
}