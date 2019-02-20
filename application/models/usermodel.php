<?php

/**
 * Reasons: Manage data for User module
 * Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 */

class userModel extends Model {

    /*
    * @Purpose: function to get the client detail
    */
    public function a(){
            $a=$this->db->get_row("select * from d_client limit 1");		
    }
    
    /*
    * @Purpose: function to authenticate the user
    * @Params: $email, $password 
    */
    public function authenticateUser($email, $password) {

        $sql = "SELECT u.*,GROUP_CONCAT(distinct ur.role_id SEPARATOR ',') as role_ids,GROUP_CONCAT(distinct c.slug SEPARATOR ',') as capabilities, cn.network_id, n.network_name

				FROM `d_user` u

                                left join h_user_user_role ur on u.user_id=ur.user_id

				left join `h_user_role_user_capability` rc on ur.role_id=rc.role_id

				left join d_user_capability c on c.capability_id=rc.capability_id

				left join h_client_network cn on cn.client_id=u.client_id

				left join d_network n on n.network_id=cn.network_id

				where email=? and password=? group by u.user_id";

        if ($res = $this->db->get_row($sql, array(trim($email), md5(trim($password))))) {

            $res['role_ids'] = $res['role_ids'] != "" ? explode(",", $res['role_ids']) : array();

            $res['capabilities'] = $res['capabilities'] != "" ? explode(",", $res['capabilities']) : array();

            return $res;
        } else
            return null;
    }
    
    /* 
    * @Purpose: Function to get user by userid 
    * @Params: $userId 
    */
    public function getUserById($userId) {

        $sql = "SELECT u.*,GROUP_CONCAT(distinct ur.role_id SEPARATOR ',') as role_ids,GROUP_CONCAT(distinct c.slug SEPARATOR ',') as capabilities, cn.network_id, n.network_name,cl.client_name

				FROM `d_user` u

                                left join h_user_user_role ur on u.user_id=ur.user_id

				left join `h_user_role_user_capability` rc on ur.role_id=rc.role_id

				left join d_user_capability c on c.capability_id=rc.capability_id

				left join h_client_network cn on cn.client_id=u.client_id

				left join d_network n on n.network_id=cn.network_id
                                
                                left join d_client cl on u.client_id=cl.client_id

				where u.user_id=? group by u.user_id";

        if ($res = $this->db->get_row($sql, array($userId))) {

            $res['role_ids'] = $res['role_ids'] != "" ? explode(",", $res['role_ids']) : array();

            $res['capabilities'] = $res['capabilities'] != "" ? explode(",", $res['capabilities']) : array();

            return $res;
        } else
            return null;
    }

    
    /*
    * @Purpose : check user-role for particular client exist or not
    * @Params : $client_id, $role_id, $user_id = 0 
    */
    public function getUsersForClientByRole($client_id, $role_id, $user_id = 0) {
        
        if ($client_id != '') {
            $condition = 'c.client_id = ? and ';
            $array = array($client_id, $role_id, $user_id);
        } else {
            $condition = '';
            $array = array($role_id, $user_id);
        }
        $sql = "SELECT c.client_id,group_concat(u.user_id) as 'users',h.role_id
				FROM  d_client c 
				inner join d_user u on c.client_id=u.client_id
				inner join h_user_user_role h on h.user_id=u.user_id
				where " . $condition . "h.role_id=? and u.user_id!=? group by c.client_id;";


        if ($res = $this->db->get_row($sql, $array)) {
            return $res;
        } else
            return null;
    }

    /*
    * @Purpose : Function to get username
    * @Params : $userIds 
    */
    function getUsernameForIds($userIds) {

        if (empty($userIds) || !is_array($userIds)) {

            return array();
        } else {

            $len = count($userIds);

            return $this->db->get_results("select name,user_id,email from d_user where user_id in (?" . str_repeat(",?", $len - 1) . ");", $userIds);
        }
    }
    
    /* @Purpose :Function to get user
    *  @params: $email
    */
    public function getUserByEmail($email) {

        $sql = "SELECT u.*,GROUP_CONCAT(distinct ur.role_id SEPARATOR ',') as role_ids,GROUP_CONCAT(distinct c.slug SEPARATOR ',') as capabilities, cn.network_id, n.network_name

				FROM `d_user` u

                left join h_user_user_role ur on u.user_id=ur.user_id

				left join `h_user_role_user_capability` rc on ur.role_id=rc.role_id

				left join d_user_capability c on c.capability_id=rc.capability_id

				left join h_client_network cn on cn.client_id=u.client_id

				left join d_network n on n.network_id=cn.network_id

				where LOWER(email)=? group by u.user_id";

        if ($res = $this->db->get_row($sql, array(strtolower($email)))) {

            $res['role_ids'] = $res['role_ids'] != "" ? explode(",", $res['role_ids']) : array();

            $res['capabilities'] = $res['capabilities'] != "" ? explode(",", $res['capabilities']) : array();

            return $res;
        } else
            return null;
    }
    
    /* @Purpose :Function to get user
    *  @params: $email,$id
    */
    
    public function getUserByEmailExceptSelf($email,$id) {

        $sql = "SELECT u.*,GROUP_CONCAT(distinct ur.role_id SEPARATOR ',') as role_ids,GROUP_CONCAT(distinct c.slug SEPARATOR ',') as capabilities, cn.network_id, n.network_name

				FROM `d_user` u

                left join h_user_user_role ur on u.user_id=ur.user_id

				left join `h_user_role_user_capability` rc on ur.role_id=rc.role_id

				left join d_user_capability c on c.capability_id=rc.capability_id

				left join h_client_network cn on cn.client_id=u.client_id

				left join d_network n on n.network_id=cn.network_id

				where LOWER(email)=? && u.user_id!=? group by u.user_id";

        if ($res = $this->db->get_row($sql, array(strtolower($email),$id))) {

            $res['role_ids'] = $res['role_ids'] != "" ? explode(",", $res['role_ids']) : array();

            $res['capabilities'] = $res['capabilities'] != "" ? explode(",", $res['capabilities']) : array();

            return $res;
        } else
            return null;
    }
    
    
    /*@Purpose: function to get principal
     *@Params: $client_id
     */
    public function getPrincipal($client_id) {

        $sql = "SELECT u.*

				FROM `d_user` u

                                left join h_user_user_role ur on u.user_id=ur.user_id

				where u.client_id=? and ur.role_id=6 group by u.user_id";

        if ($res = $this->db->get_row($sql, array($client_id))) {

            return $res;
        } else
            return null;
    }
    
    /*@Purpose: function to get Users
     *@Params: $args,$tap_admin_role_id,$ref
     */
    public function getUsers($args = array(), $tap_admin_role_id = '', $ref = '') {
        $args = $this->parse_arg($args, array("role_id" => 0, "name_like" => "", "client_id" => 0, "network_id" => 0, "exclude_cap" => array(), "client_like" => "", "email_like" => "", "max_rows" => 10, "page" => 1, "order_by" => "name", "order_type" => "asc"));
        $order_by = array("name" => "u.name", "client_name" => "c.client_name", "user_role" => "r.role_name", "email" => "u.email", "network" => "n.network_name", "create_date" => "u.create_date");
        $sqlArgs = array();
        $condition = '';
        
        if (isset($ref) && $ref == 1) {
            $SQL1 = "Select group_concat(distinct content_id) as user_id from d_alerts where status='1' and type='CREATE_EXTERNAL_ASSESSOR' and 
                            table_name='d_user'";
            $user_id = $this->db->get_row($SQL1);

            if (!empty($user_id) && $user_id['user_id'] != '') {
                $condition = " and u.user_id In (" . $user_id['user_id'] . ") ";
            }
        }
        
        $sql = "SELECT 
				SQL_CALC_FOUND_ROWS u.*,GROUP_CONCAT(distinct r.role_name SEPARATOR ',') as roles,GROUP_CONCAT(distinct ur.role_id SEPARATOR ',') as role_ids, c.client_name, cn.network_id, n.network_name
				,count(u.user_id) as count FROM `d_user` u
				left join h_user_user_role ur on u.user_id=ur.user_id
				left join d_user_role r on r.role_id=ur.role_id
				left join d_client c on c.client_id=u.client_id
				left join h_client_network cn on cn.client_id=u.client_id
				left join d_network n on n.network_id=cn.network_id
				where 1=1  " . $condition;
        

        if ($args['name_like'] != "") {
            $sql.="and u.name like ? ";
            $sqlArgs[] = "%" . $args['name_like'] . "%";
        }
        if ($args['client_like'] != "") {
            $sql.="and c.client_name like ? ";
            $sqlArgs[] = "%" . $args['client_like'] . "%";
        }
        if ($args['email_like'] != "") {
            $sql.="and u.email like ? ";
            $sqlArgs[] = $args['email_like'] . "%";
        }
        if ($args['client_id'] > 0) {
            $sql.="and u.client_id = ? ";
            $sqlArgs[] = $args['client_id'];
        }
        if ($args['network_id'] > 0) {
            $sql.="and cn.network_id = ? ";
            $sqlArgs[] = $args['network_id'];
        }

        $sql.=" group by u.user_id ";
        $havingClauseAdded = 0;
        if ($tap_admin_role_id == 8) {
            $sql.=" having role_ids  rlike concat('[[:<:]]',4,'[[:>:]]') ";
            $havingClauseAdded = 1;
        } else {
            if ($args['role_id'] > 0) {
                $sql.=" having role_ids  rlike concat('[[:<:]]'," . $args['role_id'] . ",'[[:>:]]') ";
                $havingClauseAdded = 1;
            }
        }

        if (!empty($args['exclude_cap']) && is_array($args['exclude_cap'])) {
            $exclude = array();
            $roles = $this->getRolesWithCapSlugs();
            foreach ($roles as $role) {
                foreach ($args['exclude_cap'] as $cap)
                    if (in_array($cap, explode(",", $role['caps']))) {
                        $exclude[] = $role['role_id'];
                        break;
                    }
            }
            if (!empty($exclude)) {
                $sql.=$havingClauseAdded ? " and " : " having ";
                $cnt = 0;
                foreach ($exclude as $rid) {
                    $sql.=($cnt > 0 ? "and " : "") . "role_ids not rlike concat('[[:<:]]',$rid,'[[:>:]]') ";
                    $cnt++;
                }
            }
        }
         $sql.=" order by " . (isset($order_by[$args["order_by"]]) ? $order_by[$args["order_by"]] : "u.name") . ($args["order_type"] == "desc" ? " desc " : " asc ") . $this->limit_query($args['max_rows'], $args['page']);
        $res = $this->db->get_results($sql, $sqlArgs);
        $this->setPageCount($args['max_rows']);
        if (!empty($res) && $ref != '') {
            $this->db->update('d_alerts', array('status' => 1), array('type' => 'CREATE_EXTERNAL_ASSESSOR', 'table_name' => 'd_user'));
        }
        return $res;
    }
    
    /*@Purpose: Function to display the roles of user according to user-type
     *@Params: $roles
     */
    function getRoles($roles = array()) {

        $sql = "select * from d_user_role WHERE role_id NOT IN(8)";
        if(!empty($roles)) {
            $params = array();
            $sql .= " AND role_id IN (";
            foreach($roles as $key=>$val){
               $sql .= "?,";
               $params[] = $val;
            }
            $sql = trim($sql,",");
            $sql .= ") order by `order`;";
            $res = $this->db->get_results($sql,$params);
        }else {
            $sql .= " order by `order`;";
             $res = $this->db->get_results($sql);
        }

        return $res ? $res : array();
    }
    
    /*
     * @Purpose: Function to get the roles and capabilities
     */
    function getRolesWithCaps() {

        $res = $this->db->get_results("select r.*,GROUP_CONCAT(rc.capability_id SEPARATOR ',') as cap_ids

			from d_user_role r

			left join h_user_role_user_capability rc on rc.role_id=r.role_id
                        where r.role_id != 8
			group by r.role_id order by `order`;");

        return $res ? $res : array();
    }

    /*
     * @Purpose: Function to get the roles and capabilities
     */
    function getRolesWithCapSlugs() {

        $res = $this->db->get_results("select r.*,GROUP_CONCAT(c.slug SEPARATOR ',') as caps

			from d_user_role r

			left join h_user_role_user_capability rc on rc.role_id=r.role_id

            left join d_user_capability c on rc.capability_id=c.capability_id

			group by r.role_id order by `order`");

        return $res ? $res : array();
    }

    /*
    * @Purpose: Function to get the user capabilities
    */
    function getCapabilities() {

        $res = $this->db->get_results("select * from d_user_capability order by `order`;");

        return $res ? $res : array();
    }
    
    /*
    * @Purpose: Function to add capabilities to user
    * @Params: $role_id, $capability_id
    */
    function addCapabilityToRole($role_id, $capability_id) {

        return $this->db->insert("h_user_role_user_capability", array("role_id" => $role_id, "capability_id" => $capability_id));
    }

    
    /*
    * @Purpose: Function to remove capabilities of user
    * @Params: $role_id, $capability_id
    */
    function removeCapabilityFromRole($role_id, $capability_id) {

        return $this->db->delete("h_user_role_user_capability", array("role_id" => $role_id, "capability_id" => $capability_id));
    }

    /* @Purpose: function to create user
     * @params: $email, $password, $name, $client_id, $isAQSApproved = 0, $createDate=NULL, $createdBy=NULL,$add_moodle=0
     */
    public function createUser($email, $password, $name, $client_id, $isAQSApproved = 0, $createDate=NULL, $createdBy=NULL,$add_moodle=0 ) {
         
        if ($this->db->insert("d_user", array('password' => md5(trim($password)), 'name' => $name, 'email' => $email, 'client_id' => $client_id, 'aqs_status_id' => $isAQSApproved, 'create_date' => $createDate, 'createdby' => $createdBy,'add_moodle'=>$add_moodle   )))
            return $this->db->get_last_insert_id();
        else
            return false;
    }
    
    /* @Purpose:function to create user whose role is state admin or zone admin or block admin or cluster admin
     * @params: $user_id, $user_type_id, $state_id=NULL, $zone_id=NULL,$block_id=NULL,$cluster_id=NULL
     */
    public function createAdminLevels($user_id, $user_type_id, $state_id=NULL, $zone_id=NULL,$block_id=NULL,$cluster_id=NULL) {
         
        if ($this->db->insert("h_user_admin_levels", array('user_id' => $user_id, 'user_type_id' => $user_type_id, 'state_id' => $state_id, 'zone_id' => $zone_id, 'block_id' => $block_id,'cluster_id' => $cluster_id)))
            return $this->db->get_last_insert_id();
        else
            return false;
    }

    /* @Purpose: Add user role
     * @params: $user_id, $role_id
     */
    public function addUserRole($user_id, $role_id) {

        return $this->db->insert('h_user_user_role', array("role_id" => $role_id, "user_id" => $user_id));
    }
    
    
    /*@Purpose: function to delete user role
     *@Params: $user_id, $role_id
     */
    public function deleteUserRole($user_id, $role_id) {

        return $this->db->delete('h_user_user_role', array("role_id" => $role_id, "user_id" => $user_id));
    }

    /*@Purpose: function to get user roles
    *@Params: $user_id
    */
    function getUserRoles($user_id) {

        return $this->db->get_var("select GROUP_CONCAT(r.role_id SEPARATOR ',') as roles from h_user_user_role r where r.user_id=?;", array($user_id));
    }
    
    
    /*@Purpose: function to update user
    *@Params: $user_id, $name ,$password = '', $client_id = 0, $isAQSApproved = -1, $modifyDate=NULL, $modifiedBy=NULL,$add_moodle=0
    */
    public function updateUser($user_id, $name ,$password = '', $client_id = 0, $isAQSApproved = -1, $modifyDate=NULL, $modifiedBy=NULL,$add_moodle=0) {

        $data = array("name" => $name);
        
        if ($password != "")
            $data['password'] = md5(trim($password));

        if ($client_id > 0)
            $data['client_id'] = $client_id;

        if ($isAQSApproved != -1)
            $data['aqs_status_id'] = $isAQSApproved;
        
        $data['modify_date']=$modifyDate;
        $data['modifyby']=$modifiedBy;
        $data['add_moodle']=$add_moodle;
        return $this->db->update('d_user', $data, array("user_id" => $user_id));
    }
    
    /* @Purpose:function to delete user whose role is state admin or zone admin or block admin or cluster admin
     * @params: $user_id
     */
    public function deleteAdminLevels($user_id) {

        if ($this->db->delete("h_user_admin_levels", array("user_id" => $user_id)))
            return true;

    }
    
    /* @Purpose:function to update User Email
     * @params: $user_id, $name , $email, $password = '', $client_id = 0, $isAQSApproved = -1, $modifyDate=NULL, $modifiedBy=NULL,$add_moodle=0
     */
    public function updateUserEmail($user_id, $name , $email, $password = '', $client_id = 0, $isAQSApproved = -1, $modifyDate=NULL, $modifiedBy=NULL,$add_moodle=0) {

        $data = array("name" => $name);
        $data['email'] = $email;
        
        if ($password != "")
            $data['password'] = md5(trim($password));

        if ($client_id > 0)
            $data['client_id'] = $client_id;

        if ($isAQSApproved != -1)
            $data['aqs_status_id'] = $isAQSApproved;
        
        $data['modify_date']=$modifyDate;
        $data['modifyby']=$modifiedBy;
        $data['add_moodle']=$add_moodle;
        return $this->db->update('d_user', $data, array("user_id" => $user_id));
    }
    
    
    /* @Purpose:function to generate session
     * @params: $user_id,$email
     */
    public function generateToken($user_id = 0, $email = '') {

        $token = "";

        if ($user_id > 0) {

            $token = md5($user_id . "_" . time() . "_" . rand(9, 99999999));

            $date = date("Y-m-d H:i:s");
            
            if (!$this->db->insert("session_token", array("token" => $token, "user_id" => $user_id, "created_date" => $date, "updated_date" => $date, "server_details"=>serialize($_SERVER)))) {
                $token = "";
            } else {
                $token .= "--" . md5(strtolower(trim($email)) );
            }
        }

        return $token;
    }

    /* @Purpose:function to delete session
     * @params: $token
     */
    public function logoutUser($token = "") {

        if ($token != "") {
            $token = current( explode("--", $token) );
            return $this->db->delete("session_token", array("token" => $token));
        }

        return false;
    }
    
    /* @Purpose:function to check session for particular user
     * @params: $user_id
     */
    public function userTokenExists($user_id){
      $res = $this->db->get_row("select * from session_token where user_id=? order by updated_date desc limit 0,1",array($user_id));
      return $res ? $res : array();
    }
    
    /* @Purpose:function to check session time-period
     * @params: $token
     */
    public function checkToken($token = "") {
        
        $token = current( explode("--", $token) );
        
        $date = date("Y-m-d H:i:s", strtotime("-" . TOKEN_LIFE . " seconds"));
        if ($token != "" && $res = $this->db->get_row("select u.user_id,u.user_name,u.name,u.email,u.client_id,u.aqs_status_id,u.has_view_video,u.create_date,u.createdby,u.modify_date,u.modifyby,u.add_moodle ,cl.is_web,cl.client_institution_id,cl.is_guest,GROUP_CONCAT(distinct ur.role_id SEPARATOR ',') as role_ids,GROUP_CONCAT(distinct c.slug SEPARATOR ',') as capabilities, cn.network_id, n.network_name,hup.is_submit  as assessor_profile,
			(Select count(id) from d_alerts where status=0 and type='CREATE_EXTERNAL_ASSESSOR') as assessor_count,
                        (Select count(id) from d_alerts where status=0 and type='CREATE_REVIEW')as review_count from `session_token` s 
			inner join `d_user` u on s.user_id=u.user_id 
		    inner join d_client cl on cl.client_id = u.client_id
            left join h_user_user_role ur on u.user_id=ur.user_id
			left join `h_user_role_user_capability` rc on ur.role_id=rc.role_id
			left join d_user_capability c on c.capability_id=rc.capability_id
			left join h_client_network cn on cn.client_id=u.client_id				
			left join d_network n on n.network_id=cn.network_id
                        left join h_user_profile hup on hup.user_id=u.user_id
			where `token`= ? and updated_date > ? group by u.user_id", array($token, $date))) {
            $res['role_ids'] = $res['role_ids'] != "" ? explode(",", $res['role_ids']) : array();
            $res['capabilities'] = $res['capabilities'] != "" ? explode(",", $res['capabilities']) : array();
            $res['has_view_video'] = $res['has_view_video'] > 0 ? 1 : 0;
            $res['is_web'] = $res['is_web'] > 0 ? 1 : 0;
            $this->db->update("session_token", array("updated_date" => date("Y-m-d H:i:s"),"server_details"=>serialize($_SERVER)), array("token" => $token));
            $today = date("m/d/Y");
            $new_date= date("m/d/Y", strtotime("$today +1 week"));

            $ass_res = $this->db->get_row("SELECT count(d.assessment_id) as num_ass from d_assessment d "
                    . " inner join d_AQS_data aqs on d.aqsdata_id =  aqs.id WHERE"
                    . "  STR_TO_DATE(aqs.school_aqs_pref_start_date, '%d-%m-%Y') BETWEEN CURRENT_DATE() and DATE_ADD(CURRENT_DATE(), INTERVAL 7 DAY)  AND aqs.status is null");
            
             
            if(in_array(1,$res['role_ids'])){
                $res["alert_count"] = $res['assessor_count'] + $res['review_count']+$ass_res['num_ass'];
                $res["ass_count"] = $ass_res['num_ass'];
            }else if(in_array(2,$res['role_ids'])){
                $res["alert_count"] = $ass_res['num_ass'];
                $res["ass_count"] = $ass_res['num_ass'];
            }else {
                $res["alert_count"] = $res['assessor_count'] + $res['review_count'];
            }
            
            return $res;
        } else {
            $date = date("Y-m-d H:i:s", strtotime("-" . (TOKEN_LIFE - 15) . " seconds"));
            $this->db->query("DELETE FROM  `session_token` where `updated_date` < ?", array($date));
        }
        return false;
    }
    
    
    /* @Purpose:function to check jwt token
     * @params: $user_id
     */
    public function checkTokenJWT($user_id) {
        
        
        $date = date("Y-m-d H:i:s", strtotime("-" . TOKEN_LIFE . " seconds"));
        if ( $res = $this->db->get_row("select u.user_id,u.user_name,u.name,u.email,u.client_id,u.aqs_status_id,u.has_view_video,u.create_date,u.createdby,u.modify_date,u.modifyby,u.add_moodle,cl.is_web,cl.client_institution_id,cl.is_guest,GROUP_CONCAT(distinct ur.role_id SEPARATOR ',') as role_ids,GROUP_CONCAT(distinct c.slug SEPARATOR ',') as capabilities, cn.network_id, n.network_name,hup.is_submit  as assessor_profile,
			(Select count(id) from d_alerts where status=0 and type='CREATE_EXTERNAL_ASSESSOR') as assessor_count,
                        (Select count(id) from d_alerts where status=0 and type='CREATE_REVIEW')as review_count from 
			`d_user` u 
		        inner join d_client cl on cl.client_id = u.client_id
                        left join h_user_user_role ur on u.user_id=ur.user_id
			left join `h_user_role_user_capability` rc on ur.role_id=rc.role_id
			left join d_user_capability c on c.capability_id=rc.capability_id
			left join h_client_network cn on cn.client_id=u.client_id				
			left join d_network n on n.network_id=cn.network_id
                        left join h_user_profile hup on hup.user_id=u.user_id
			where u.user_id= ? group by u.user_id", array($user_id))) {
            $res['role_ids'] = $res['role_ids'] != "" ? explode(",", $res['role_ids']) : array();
            $res['capabilities'] = $res['capabilities'] != "" ? explode(",", $res['capabilities']) : array();
            $res['has_view_video'] = $res['has_view_video'] > 0 ? 1 : 0;
            $res['is_web'] = $res['is_web'] > 0 ? 1 : 0;
            $this->db->update("session_token", array("updated_date" => date("Y-m-d H:i:s"),"server_details"=>serialize($_SERVER)), array("user_id" => $user_id));
            $today = date("m/d/Y");
            $new_date= date("m/d/Y", strtotime("$today +1 week"));

            $ass_res = $this->db->get_row("SELECT count(d.assessment_id) as num_ass from d_assessment d "
                    . " inner join d_AQS_data aqs on d.aqsdata_id =  aqs.id WHERE"
                    . "  STR_TO_DATE(aqs.school_aqs_pref_start_date, '%d-%m-%Y') BETWEEN CURRENT_DATE() and DATE_ADD(CURRENT_DATE(), INTERVAL 7 DAY)  AND aqs.status is null");
            
            
           
            if(in_array(1,$res['role_ids'])){
                $res["alert_count"] = $res['assessor_count'] + $res['review_count']+$ass_res['num_ass'];
                $res["ass_count"] = $ass_res['num_ass'];
            }else if(in_array(2,$res['role_ids'])){
                $res["alert_count"] = $ass_res['num_ass'];
                $res["ass_count"] = $ass_res['num_ass'];
            }else {
                $res["alert_count"] = $res['assessor_count'] + $res['review_count'];
            }
            
            return $res;
        } else {
            $date = date("Y-m-d H:i:s", strtotime("-" . (TOKEN_LIFE - 15) . " seconds"));
            $this->db->query("DELETE FROM  `session_token` where `updated_date` < ?", array($date));
        }
        return false;
    }

    
    /* @Purpose:function to create reset user
     * @params: $user_id, $key, $create_date, $expiration_date
     */
    public function createResetUser($user_id, $key, $create_date, $expiration_date) {

        if ($this->db->insert("d_user_enc", array("user_id" => $user_id, "key" => $key, "create_date" => $create_date, "expiration_date" => $expiration_date)))
            return true;

        return false;
    }

    /* @Purpose:function to reset password
     * @params: $key
     */
    public function getPasswordResetKey($key) {

        $curDate = date("Y-m-d");

        $sql = "SELECT e.`user_id`,u.email,e.key FROM `d_user_enc` e inner join d_user u on e.user_id=u.user_id  WHERE `key` = ? AND `expiration_date` >= ?";

        $res = $this->db->get_row($sql, array($key, $curDate));

        return $res ? $res : array();
    }

    /* @Purpose:function to delete reset user
     * @params: $user_id,$key
     */
    public function deleteResetUser($user_id, $key = 0) {

        if ($key == 0 && $this->db->delete("d_user_enc", array("user_id" => $user_id)))
            return true;

        else if ($this->db->delete("d_user_enc", array("user_id" => $user_id, "key" => $key)))
            return true;

        return false;
    }

    /* @Purpose:function to update user password
     * @params: $user_id,$password
     */
    public function updateUserPassword($user_id, $password) {

        $data['password'] = md5(trim($password));

        return $this->db->update('d_user', $data, array("user_id" => $user_id));
    }

    
    /* @Purpose:Function for creating temp table for storing or getting assessor data
     * @params: $roles
     */
    public function getAQSTeamAssessorData($roles = array()) {
        if (!empty($roles) && current($roles) != '') {
            $having = array();
            foreach ($roles as $key => $value) {
                $having[] = " role_ids  rlike concat('[[:<:]]'," . $value . ",'[[:>:]]') ";
            }
            $having = implode(' OR ', $having);
        } else {
            $having = " role_ids  rlike concat('[[:<:]]',4,'[[:>:]]') ";
        }

        $SQL1 = "CREATE TEMPORARY TABLE userList
                Select SQL_CALC_FOUND_ROWS z.* from (
                SELECT t1.user_id,t1.name,t1.email,t1.client_id,t2.client_name,t3.network_id,t4.network_name,
                GROUP_CONCAT(distinct t6.role_name SEPARATOR ',') as roles ,(if (t1.email!='',1,2)) as count,
                GROUP_CONCAT(distinct t5.role_id SEPARATOR ',') as role_ids,
                (SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='" . DB_NAME . "' and TABLE_NAME='d_user') as table_name
                FROM d_user t1 Left JOIN d_client t2 ON 
                (t1.client_id=t2.client_id) LEFT JOIN h_client_network t3 ON (t2.client_id=t3.client_id) LEFT JOIN d_network t4 ON 
                (t3.network_id=t4.network_id) LEFT JOIN h_user_user_role t5 ON (t1.user_id=t5.user_id) LEFT JOIN d_user_role t6 ON 
                (t5.role_id=t6.role_id) WHERE 1 GROUP by t1.user_id having " . $having . "
                UNION ALL
                SELECT a1.id as user_id,trim(a1.name) as name,a1.email,a2.client_id,a2.client_name,a3.network_id,a4.network_name,a1.designation as roles ,
                (select count(*) from d_AQS_team as tt where tt.email=a1.email) as count,a1.id as role_ids,
                (SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA='" . DB_NAME . "' and TABLE_NAME='d_AQS_team') as table_name
                FROM d_AQS_team a1 LEFT JOIN 
                d_assessment aa ON (a1.AQS_data_id=aa.aqsdata_id) LEFT JOIN d_client a2 ON (aa.client_id=a2.client_id) LEFT JOIN h_client_network a3 ON 
                (a2.client_id=a3.client_id) LEFT JOIN d_network a4 ON (a3.network_id=a4.network_id) WHERE 1 and ( a1.designation LIKE '%Principal%' OR
                a1.designation LIKE '%Teacher%' OR a1.designation LIKE '%Leader%' ) AND 
                a1.name != '' AND a1.designation != '' AND a1.email != '' AND a1.isInternal = 1 and a1.user_added_flag='0'
                ) as z;";

        $this->db->query($SQL1);
    }

     /* @Purpose:Function to get AQS Team Assessor
     * @params: $roles
     */
    public function getAQSTeamAssessor($args = array(), $tap_admin_role, $ref = '', $ref_key = '') {

        $args = $this->parse_arg($args, array("role_id" => 0, "name_like" => "", "client_id" => 0, "network_id" => 0, "exclude_cap" => array(),
            "client_like" => "", "email_like" => "", "max_rows" => 10, "page" => 1, "order_by" => "name", "order_type" => "asc",
            'table_name' => ''));
        $order_by = array("name" => "name", "client_name" => "client_name", "email" => "email", "network" => "network_name", 'user_role' => 'roles');
        $sqlArgs = array();
        $condition = '';
        if (isset($ref) && $ref == 1 && $ref_key != '') {
            $SQL1 = "Select alert_ids as user_id from h_alert_relation where login_user_role='" . $tap_admin_role . "' and type='ASSESSOR'";
            $user_id = $this->db->get_row($SQL1);

            if (!empty($user_id) && $user_id['user_id'] != '') {
                $condition = " and user_id In (" . $user_id['user_id'] . ") and table_name='d_user' ";
            }
        }
        $SQL = "SELECT  SQL_CALC_FOUND_ROWS * from userList Where 1 " . $condition . "";

        if ($args['name_like'] != "") {
            $SQL.="and name like ? ";
            $sqlArgs[] = "%" . $args['name_like'] . "%";
        }
        if ($args['client_like'] != "") {
            $SQL.="and client_name like ? ";
            $sqlArgs[] = "%" . $args['client_like'] . "%";
        }
        if ($args['email_like'] != "") {
            $SQL.="and email like ? ";
            $sqlArgs[] = $args['email_like'] . "%";
            $SQL.="and table_name like ? ";
            $sqlArgs[] = "d_user";
        }
        if ($args['client_id'] > 0) {
            $SQL.="and client_id = ? ";
            $sqlArgs[] = $args['client_id'];
        }
        if ($args['network_id'] > 0) {
            $SQL.="and network_id = ? ";
            $sqlArgs[] = $args['network_id'];
        }
        if ($args['table_name'] != '') {
            $SQL.="and table_name = ? ";
            $sqlArgs[] = $args['table_name'];
        }

        $SQL.=" group by name,client_name ";

//            print_r($args);

        $SQL.=" order by " . (isset($order_by[$args["order_by"]]) ? $order_by[$args["order_by"]] : "name") . ($args["order_type"] == "desc" ? " desc " : " asc ") . $this->limit_query($args['max_rows'], $args['page']);
        $this->getAQSTeamAssessorData(array());

        $result = $this->db->get_results($SQL, $sqlArgs);
        $this->setPageCount($args['max_rows']);

        $final = array();
        foreach ($result as $key => $value) {
            if ($value['table_name'] == 'd_aqs_team' || $value['table_name'] == 'd_AQS_team') {
                $value['roles'] = '';
                $value['email'] = '';
                $final[] = $value;
            } else {
                $final[] = $value;
            }
        }

        return $final;
    }

    
    /*
    * @Purpose: Function for getting user id by email address
    * @params: $email
    */
    public function getUserIdByEmail($email) {
        $SQL = "Select user_id from d_user where email='" . $email . "'";
        $user_id = $this->db->get_row($SQL);
        if (!empty($user_id)) {
            return $user_id['user_id'];
        } else {
            return false;
        }
    }

    
    /*
    * @Purpose: Function for getting user language list
    * @params: $user_id
    */
    public function getUserLanguage($user_id) {
        $SQL = "Select language_id,language_read,language_write,language_speak from h_user_language where user_id='" . $user_id . "'";
        return $this->db->get_results($SQL);
    }
    
    
    /*
    * @Purpose: Function for checking mails have been sent or not
    * @params: $id
    */
    public function getMailRecievedUserlist($id = NULL) {
        if ($id != '') {
            $field = 'email';
            $condition = "aqs_team_user_id='" . $id . "'";
        } else {
            $field = 'aqs_team_user_id';
            $condition = "user_type_table='d_aqs_team'";
        }
        $SQL = "Select " . $field . " from h_aqs_team_invite_user WHERE " . $condition . ";";

        if ($id != '') {
            $value = $this->db->get_row($SQL);
            $data = $value['email'];
        } else {
            $data = array();
            foreach ($this->db->get_results($SQL) as $key => $value) {
                $data[] = $value['aqs_team_user_id'];
            }
        }
        return $data;
    }

    
    /*
    * @Purpose: Function for getting tap assessors users list
    */
    public function getTapAssessorUserList() {
        $SQL = "Select user_id from h_tap_user_assessment where tap_program_status='1'";
        $result = $this->db->get_results($SQL);
        if (!empty($result)) {
            $final = array();
            foreach ($result as $value) {
                $final[] = $value['user_id'];
            }
            return $final;
        } else {
            return array();
        }
    }

    
    /*
    * @Purpose: Function for getting user sub roles list 
    */
    public function getUserSubRoleList() {
        $SQL = "Select sub_role_id,sub_role_name from d_user_sub_role where user_role_id='4'";
        return $this->db->get_results($SQL);
    }

    
    /*
    * @Purpose: Function to check date 
    * @params: $dbDate,$source
    */
    public function validateDate($dbDate,$source){
        
         $DOB = explode("-",$dbDate);
        
         $status='';
         if($source == 1){
          
             $status = strtotime($dbDate) > strtotime(date("d-m-Y"))?FALSE: checkdate($DOB[1], $DOB[0], $DOB[2]);             
         }
         else if($source == 2)
            $status = checkdate($DOB[1], $DOB[2], $DOB[0]);
         if($status === FALSE)
            return 1;
         else
             return 0;
    }

    
    /*
    * @Purpose: Function for save assessor introductory assessment data 
    * @params: $data, $uniqueID = ''
    */
    public function saveAssessorIntroductoryAssessment($data, $uniqueID = '') {
        if ($data['user_id'] == '') {
            return false;
        } else {
            $SQL = "Select id from h_user_introductory_assessment where user_id='" . $data['user_id'] . "'";
            $id = $this->db->get_row($SQL);
            $data['principal_statement'] = isset($data['principal_statement'])?$data['principal_statement']:"";
            $data['leader_statement'] = isset($data['leader_statement'])?$data['leader_statement']:"";
            $data['statement'] = isset($data['statement'])?$data['statement']:"";
            $data['school_statement'] = $data['principal_statement'] . '~' . $data['leader_statement'] . '~' . $data['statement'];
            unset($data['principal_statement']);
            unset($data['leader_statement']);
            unset($data['statement']);
            if (empty($id)) {
                $data['create_date'] = date('Y-m-d H:i:s');
                if ($this->db->insert('h_user_introductory_assessment', $data)) {
                    if (OFFLINE_STATUS == TRUE) {
                        //start---> call function for add other langauge 
                        $action_introductory_assessment_json = json_encode($data);
                        $this->db->saveHistoryData($data['user_id'], 'h_user_introductory_assessment', $uniqueID, 'updateAssessorIntroductoryAssessment', $data['user_id'], $data['user_id'], $action_introductory_assessment_json, 0, date('Y-m-d H:i:s'));
                        //end---> call function for add other langauge 
                    }
                    return true;
                } else {
                    return false;
                }
            } else {
                $where = array('user_id' => $data['user_id']);
                if ($this->db->update('h_user_introductory_assessment', $data, $where)) {
                    if (OFFLINE_STATUS == TRUE) {
                        //start---> call function for add other langauge 
                        $action_introductory_assessment_json = json_encode($data);
                        $this->db->saveHistoryData($data['user_id'], 'h_user_introductory_assessment', $uniqueID, 'updateAssessorIntroductoryAssessment', $data['user_id'], $data['user_id'], $action_introductory_assessment_json, 0, date('Y-m-d H:i:s'));
                        //end---> call function for add other langauge 
                    }
                    return true;
                } else {
                    return false;
                }
            }
        }
    }
    
    /*
    * @Purpose: Function for save assessor introductory assessment data 
    * @params: $data, $user_id,$is_submit=0
    */
    public function saveIntroductoryAssessment($data, $user_id,$is_submit=0) {
        if ($user_id == '') {
            return false;
        } else {
            
            $finalDataArray['leader_statement'] = isset($data['leader_statement'])?trim($data['leader_statement']):''; 
            $finalDataArray['statement'] = isset($data['statement'])?trim($data['statement']):''; 
            
             
            $finalDataArray['key_performance_text'] = isset($data['key_performance_text'])?trim($data['key_performance_text']):''; 
            $finalDataArray['goal'] = isset($data['goal'])?trim($data['goal']):''; 
            $finalDataArray['school_rating'] = isset($data['school_rating'])?trim($data['school_rating']):''; 
            $finalDataArray['school_rating_txt'] = isset($data['school_rating_txt'])?trim($data['school_rating_txt']):''; 
            $finalDataArray['score'] = isset($data['score'])?trim($data['score']):''; 
            if($is_submit == 1) {
                $finalDataArray['is_submit'] = 1;
            }
            
            $finalDataArray = array_filter($finalDataArray);
               
            $SQL = "Select id from h_user_introductory_assessment where user_id='" . $user_id . "'";
             $id = $this->db->get_row($SQL);
            
                 $sql = "INSERT INTO h_assessor_question_answer (question_id,option_id,user_id) VALUES ";
            
                 $sqlKey = $sqlPer = $sqlClass = $sqlStake = $sqlRate='';
            if(isset($data['key_behaviour'])) {
                foreach($data['key_behaviour']['answer_id'] as $assData) {
                    
                    $sqlKey .= "(".$data['key_behaviour']['question_id'].",".$assData.",$user_id),";
                }
                
            }
            if(isset($data['key_performance'])) {
                foreach($data['key_performance']['answer_id'] as $assData) {
                    
                     $sqlPer .= "(".$data['key_performance']['question_id'].",".$assData.",$user_id),";
                }
                
            }
            if(isset($data['classroom_observation'])) {
                foreach($data['classroom_observation']['answer_id'] as $assData) {
                    
                    $sqlClass .= "(".$data['classroom_observation']['question_id'].",".$assData.",$user_id),";
                }
                
            }if(isset($data['stakeholder_text'])) {
                foreach($data['stakeholder_text']['answer_id'] as $assData) {
                    
                    $sqlStake .= "(".$data['stakeholder_text']['question_id'].",".$assData.",$user_id),";
                }
                
            }
            if(isset($data['rating_text'])) {
                foreach($data['rating_text']['answer_id'] as $assData) {
                    
                    $sqlRate .= "(".$data['rating_text']['question_id'].",".$assData.",$user_id),";
                }
                
            }
            
            if($is_submit == 1) {
                if($sqlKey && $sqlPer && $sqlClass && $sqlStake && $sqlRate) 
                    $sql .= $sqlKey.$sqlPer.$sqlClass.$sqlStake.$sqlRate;
            }else {
                    if($sqlKey || $sqlPer || $sqlClass || $sqlStake || $sqlRate) 
                    $sql .= $sqlKey.$sqlPer.$sqlClass.$sqlStake.$sqlRate;
            }
            
                if(isset($sql)) {
                    $sql = trim($sql,",");
                    $deleteQuery = "DELETE FROM h_assessor_question_answer WHERE user_id = '$user_id'";
                    $this->db->query($deleteQuery) ;
                    $this->db->query($sql) ;
                }
            
           
            if (empty($id)) {
                $data['create_date'] = date('Y-m-d H:i:s');
                $finalDataArray['user_id'] = $user_id;
                
                if ($this->db->insert('h_user_introductory_assessment', $finalDataArray)) {
                    if (OFFLINE_STATUS == TRUE) {
                        //start---> call function for add other langauge 
                        $action_introductory_assessment_json = json_encode($data);
                        $this->db->saveHistoryData($data['user_id'], 'h_user_introductory_assessment', $uniqueID, 'updateAssessorIntroductoryAssessment', $data['user_id'], $data['user_id'], $action_introductory_assessment_json, 0, date('Y-m-d H:i:s'));
                        //end---> call function for add other langauge 
                    }
                    return true;
                } else {
                    return false;
                }
            } else {
                $where = array('user_id' => $user_id);
                if ($this->db->update('h_user_introductory_assessment', $finalDataArray, $where)) {
                    if (OFFLINE_STATUS == TRUE) {
                        //start---> call function for add other langauge 
                        $action_introductory_assessment_json = json_encode($data);
                        $this->db->saveHistoryData($data['user_id'], 'h_user_introductory_assessment', $uniqueID, 'updateAssessorIntroductoryAssessment', $data['user_id'], $data['user_id'], $action_introductory_assessment_json, 0, date('Y-m-d H:i:s'));
                        //end---> call function for add other langauge 
                    }
                    return true;
                } else {
                    return false;
                }
            }
        }
    }
    
    /*
    * @Purpose: Fuction to get reviews assessment count
    * @params: $user_id
    */
    function getReviewsAssessmentCount($user_id) {
        $query="Select a.* from d_assessment a 
                left join d_diagnostic d on a.diagnostic_id=d.diagnostic_id
                left join h_assessment_user au on a.assessment_id=au.assessment_id where d.assessment_type_id=1 && au.user_id='".$user_id."' && au.role='3'
                ";
        
        return $this->db->get_results($query);
         
    }
    
    /*
    * @Purpose: Fuction to get answers of introductory questions
    * @params: $user_id
    */
    function getAssessorIntroductoryQuestionsAnswer($user_id) {
        $query="SELECT * FROM h_user_introductory_assessment where user_id = '$user_id'";
        
        return $this->db->get_row($query);
         
    }
    
    /*
    * @Purpose: Fuction to get answers of introductory questions multiple answer
    * @params: $user_id
    */
    function getAssessorIntroductoryOption($user_id) {
        $query="SELECT * FROM h_assessor_question_answer where user_id = '$user_id'";
        
        return $this->db->get_results($query);
         
    }
    
    /*
    * @Purpose: Function to update review
    * @params: $from_user_id,$to_user_id
    */
    function transfer_reviews($from_user_id,$to_user_id){
        
       $assessments=$this->getReviewsAssessmentCount($from_user_id);
       
       $success=true; 
       foreach($assessments as $key=>$val){
       
          
       $data=array();
       $data['user_id']=$to_user_id;
       if(!$this->db->update('h_assessment_user', $data, array("assessment_id" => $val['assessment_id'],"user_id" => $from_user_id,"role"=>3))){
       return false;    
       }
           
       $data1=array();
       $data1['assessor_id']=$to_user_id;
       if(!$this->db->update('f_score', $data1, array("assessment_id" => $val['assessment_id'],"assessor_id" => $from_user_id))){
       return false;    
       }
       
       $data2=array();
       $data2['assessor_id']=$to_user_id;
       if(!$this->db->update('h_cq_score', $data2, array("assessment_id" => $val['assessment_id'],"assessor_id" => $from_user_id))){
       return false;    
       }
       
       $data3=array();
       $data3['assessor_id']=$to_user_id;
       if(!$this->db->update('h_kq_instance_score', $data3, array("assessment_id" => $val['assessment_id'],"assessor_id" => $from_user_id))){
       return false;    
       }
       
       $data4=array();
       $data4['assessor_id']=$to_user_id;
       if(!$this->db->update('h_kpa_instance_score', $data4, array("assessment_id" => $val['assessment_id'],"assessor_id" => $from_user_id))){
       return false;    
       }
           
       }
       
      return $success;
    }
    
    /*
    * @Purpose: Function for create user history
    * @params: $client_id,$user_id_login,$user_id=0,$createdBy=0
    */
    function createrandomuser($client_id,$user_id_login,$user_id=0,$createdBy=0){
        $client_info=$this->db->get_row("select client_name from d_client where client_id='".$client_id."' limit 1");
        $principal_user_row=$this->getPrincipal($client_id);
        $principal_user_id=empty ( $principal_user_row ) ? 0:$principal_user_row['user_id'];
        if($principal_user_id===null || $principal_user_id==$user_id){
        $schoolname="Auto Principal";
        if(!empty($client_info['client_name'])) $schoolname.="-".$client_info['client_name']."";
        
        if($this->db->insert('d_user',array('user_name'=>'auto_user_'.time().'','password'=>md5('#1234#'),'name'=>''.$schoolname.'','email'=>'auto_user_'.time().'@autogenerated.com','client_id'=>$client_id,'create_date'=>date("Y-m-d H:i:s"),'createdby'=>$createdBy))){
        
        $user_id = $this->db->get_last_insert_id ();
        if($this->db->insert('h_user_user_role',array('user_id'=>$user_id,'role_id'=>6)) && $this->db->insert('h_user_user_role',array('user_id'=>$user_id,'role_id'=>3))){
            if($this->add_user_history($user_id,0,$client_id,0,'6,3','Auto User Generated',$user_id_login,date("Y-m-d H:i:s"))){ 
            return $user_id;
            }
        }
        
        }
        }else{
           return $principal_user_id;    
        }
        
        return false;
    }
    
    /*
    * @Purpose: Function for adding user history
    * @params: $user_id,$client_id_from,$client_id_to,$users_roles_from,$users_roles_to,$user_action,$created_by,$action_date
    */
    function add_user_history($user_id,$client_id_from,$client_id_to,$users_roles_from,$users_roles_to,$user_action,$created_by,$action_date){
    $data=array();
    $data['user_id']=$user_id;
    $data['client_id_from']=$client_id_from;
    $data['client_id_to']=$client_id_to;
    $data['users_roles_from']=$users_roles_from;
    $data['users_roles_to']=$users_roles_to;
    $data['user_action']=$user_action;
    $data['created_by']=$created_by;
    $data['action_date']=$action_date;
    if($this->db->insert('z_history_users',$data)){
    return true;    
    }
    return false;
    }
    
    
    /*
    * @Purpose: function for getting assessor introductory assessment data
    * @params: $user_id
    */
    function getAssessorIntroductoryAssessment($user_id) {
        $SQL = "Select school_rating_txt,score,school_statement,school_rating,is_submit,rating_text,key_performance,activity,learn,goal,aqs_improvement_text
                    from h_user_introductory_assessment where user_id='" . $user_id . "'";
        $data = $this->db->get_row($SQL);
        if (!empty($data)) {
            if ($data['school_statement'] != '') {
                $school_statement = explode('~', $data['school_statement']);
                unset($data['school_statement']);
                $data['principal_statement'] = $school_statement[0];
                $data['leader_statement'] = $school_statement[1];
                $data['statement'] = $school_statement[2];
            } else {
                unset($data['school_statement']);
                $data['principal_statement'] = '';
                $data['leader_statement'] = '';
                $data['statement'] = '';
            }
            return $data;
        } else {
            return array();
        }
    }

    /*
     * @Purpose: Add user role data
     * @Method: addNewUserRole
     * @Parameters: Table Name, data values
     * @Return: last insert id or False
     * @By: Mohit Kumar
     */

    public function addNewUserRole($user_id, $role_id) {
        if ($this->db->insert('h_user_user_role', array("role_id" => $role_id, "user_id" => $user_id)))
            return $this->db->get_last_insert_id();
        else
            return false;
    }

    /*
     * @param type $state_name
     * @return type
     */
    function getStateIdByName($state_name) {
        $res = $this->db->get_row("SELECT state_id FROM d_states where state_name= ? ;", array($state_name));
        return $res ? $res['state_id'] : '';
    }

    /* 
     * @param type $role_name
     * @return type
     */
    function getRoleIdByName($role_name) {
        $res = $this->db->get_row("select role_id from d_user_role where role_name = ? ;", array($role_name));
        return $res ? $res['role_id'] : '';
    }
    
    /*
     * Purpose : Function to get the role of state admin
     */
    function getStateAdminRole() {
        $res = $this->db->get_row("select role_name from d_user_role where role_id =10");
        return $res ? $res['role_name'] : '';
    }
    
    /*
     * Purpose : Function to get the role of zone admin
     */
    function getZoneAdminRole() {
        $res = $this->db->get_row("select role_name from d_user_role where role_id =11");
        return $res ? $res['role_name'] : '';
    }
    
    /*
     * Purpose : Function to get the role of block admin
     */
    function getBlockAdminRole() {
        $res = $this->db->get_row("select role_name from d_user_role where role_id =7");
        return $res ? $res['role_name'] : '';
    }
    
    /*
     * Purpose : Function to get the role of cluster admin
     */
    function getClusterAdminRole() {
        $res = $this->db->get_row("select role_name from d_user_role where role_id =12");
        return $res ? $res['role_name'] : '';
    }
    
    /*
    * @Purpose: function to get language-id
    * @params: $language_name
    */
    function getlangIdByName($language_name) {
        $res = $this->db->get_row("select language_id from d_language where language_name = ? ;", array($language_name));
        return $res ? $res['language_id'] : '';
    }
    
    
    /*
    * @Purpose: function to add user language
    * @params: $lang_data
    */
    public function addUserLanguage($lang_data) {
        return $this->db->insert('h_user_language', $lang_data);
    }
    
    
    /*
    * @Purpose: function to check language exists for user
    * @params: $lang_id, $user_id
    */
    function checkLangExistForUser($lang_id, $user_id){
        $res = $this->db->get_row("select id from h_user_language where language_id = ? AND user_id = ? ;", array($lang_id, $user_id));
        return $res ? $res['id'] : '';
    }
    
    
    /*
    * @Purpose: function to update user language
    * @params: $lang_data, $lang_inc_id 
    */
    function updateUserLanguage($lang_data, $lang_inc_id){
        $this->db->update('h_user_language', $lang_data, array('id' => $lang_inc_id));
    }
    
    
    /*
    * @Purpose: function to get user profile
    * @params: $user_id 
    */
    function getUserProfileRowByUserId($user_id){
         $res = $this->db->get_row("select * from h_user_profile where user_id = ? ;", array($user_id));
        return $res ? $res : array();
    }
    
    /*
    * @Purpose: function to add user profile
    * @params: $user_profile_data 
    */
    public function addUserProfile($user_profile_data) {
        return $this->db->insert('h_user_profile', $user_profile_data);
    }
    
    /*
    * @Purpose: function to save errorlog
    * @params: $user_profile_error_data 
    */
    public function insertUserProfileErrorLog($user_profile_error_data){
        return $this->db->insert('z_user_profile_excel_error_log', $user_profile_error_data);
    }
    
    /*
    * @Purpose: function to sync user profile data to sub tables
    */
    public function syncData(){
        
        $query="SELECT user_id,medical_conditions,hobbies,upload_document FROM h_user_profile ";
        
        $res =  $this->db->get_results($query);
        $medicalSql = "INSERT INTO h_user_medical_condition (user_id,medical_condition_id) VALUES";
        $hobbiesSql = "INSERT INTO h_user_hobbies (user_id,hobby_id) VALUES";
        $docSql = "INSERT INTO h_user_document (user_id,document_id) VALUES";
        foreach($res as $data) {
            
            if(isset($data['medical_conditions']) && $data['medical_conditions']!='') {
                
                $medicalData = explode(",", $data['medical_conditions']);
                foreach($medicalData as $medical) {
                    $medicalSql .= "(".$data['user_id'].",".$medical."),";
                }
                
            }
            if(isset($data['hobbies']) && $data['hobbies']!='') {
                
                $hobbieData = explode("~", $data['hobbies']);
                foreach($hobbieData as $hobbies) {
                    $hobbiesSql .= "(".$data['user_id'].",".$hobbies."),";
                }
                
            }
            if(isset($data['upload_document']) && $data['upload_document']!='') {
                
                $docData = explode(",", $data['upload_document']);
                foreach($docData as $doc) {
                    $docSql .= "(".$data['user_id'].",".$doc."),";
                }
                
            }
            
        }
        $medicalSql = trim($medicalSql,",");
        echo $hobbiesSql = trim($hobbiesSql,",");
        $this->db->query($hobbiesSql);
    }
    
    /*
    * @Purpose: function to validate
    * @params: $post 
    */
     public function validateIntroAss($post) {
         
         $val = 1;
        
            $profileField = array(
                
                "leader_statement" => array('type' => 'string', 'isRequired' => $val, 'name' => 'The school leadership team works well together'),
                "statement" => array('type' => 'string', 'isRequired' => $val, 'name' => 'A school leader said that she meets her Principal every Friday to discuss reflections on what has gone well in the past week, accomplishments, areas for improvement and the plan for the coming week. There is a weekly minutes book where this information is recorded. '),
                "school_rating" => array('type' => 'string', 'isRequired' => $val, 'name' => 'Given the evidence provided, how would you rate the school on KPA: Leadership & Management, 9a. The organisation listens carefully to the views of the community? '),
                "rating_text" => array('type' => 'string', 'isRequired' => $val, 'name' => 'Why did you decide on this rating? '),
                "key_behaviour" => array('type' => 'string', 'isRequired' => $val, 'name' => 'What are the key behaviours demonstrated by an Adhyayan Assessor '),
                "key_performance" => array('type' => 'string', 'isRequired' => $val, 'name' => 'Which Key Performance Area(s) are you most interested in learning about? Please select one or more options '),
                "classroom_observation" => array('type' => 'string', 'isRequired' => $val, 'name' => 'What should you remember when doing a Classroom Observation during an AQS Review? '),
                "key_performance_text" => array('type' => 'string', 'isRequired' => $val, 'name' => 'Describe why these areas are important in your context.'),
                "stakeholder_text" => array('type' => 'string', 'isRequired' => $val, 'name' => 'What should you remember when interacting with different stakeholders during the AQS Review? '),
                
                "goal" => array('type' => 'string', 'isRequired' => $val, 'name' => 'What is your professional development goal for the next 3 months?'),
                
            );
       
       
        $errors = array();
        $values = array();

        foreach ($profileField as $key => $value) {
          
            if ($value["isRequired"] && empty($post[$key])) {
                $errors[] = "Field is required: '" . $value['name'] . "'";
            } else if (empty($val)) {
                $values[$key] = ($value["type"] == "int") ? 0 : '';
            } else if ($value["type"] == 'email' && preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,5})$^", $val) != 1) {
                $errors[] = "Invalid email: '" . $value['name'] . "'";
            } else if ($value["type"] == 'array' && !empty($val)) {
                if ($value['name'] == 'Language') {
                    foreach ($val as $k => $v) {
                        if ($v['language_id'] == '') {
                            $errors[] = "Field is required: 'Language'";
                            break;
                        } else if ($v['language_speak'] == '' && $v['language_read'] == '' && $v['language_write'] == '') {
                            $errors[] = "Field is required: 'Proficiency'";
                            break;
                        }
                    }
                } 
            }else {
                
                $values[$key] = $post[$key];
            }
                            

        }
        if(!isset($post['key_behaviour']) || isset($post['key_behaviour']) && count($post['key_behaviour']['answer_id']) < 1) {
            
            $errors[] = "Field is required: 'What are the key behaviours demonstrated by an Adhyayan Assesso'";
        }
        if(!isset($post['key_performance']) || isset($post['key_performance']) && count($post['key_performance']['answer_id']) < 1) {
            
            $errors[] = "Field is required: 'Which Key Performance Area(s) are you most interested in learning about? Please select one or more options '";
        }
        if(!isset($post['rating_text']) || isset($post['rating_text']) && count($post['rating_text']['answer_id']) < 1) {
            
            $errors[] = "Field is required: 'Why did you decide on this rating?  '";
        }else if(in_array(RATING_OTHERS_ID,$post['rating_text']['answer_id']) && empty($post['school_rating_txt']) ) {
             $errors[] = "Field is required: 'Why did you decide on this rating? '";
            
        }else {
             $values['school_rating_txt'] = $post['school_rating_txt'];
        }
        if(!isset($post['classroom_observation']) || isset($post['classroom_observation']) && count($post['classroom_observation']['answer_id']) < 1) {
            
            $errors[] = "Field is required: 'What should you remember when doing a Classroom Observation during an AQS Review? '";
        }
        if(!isset($post['stakeholder_text']) || isset($post['stakeholder_text']) && count($post['stakeholder_text']['answer_id']) < 1) {
            
            $errors[] = "Field is required: 'What should you remember when interacting with different stakeholders during the AQS Review?  '";
        }
        

        

        return array("errors" => $errors, "values" => $values);
    }
    
    
    /*
    * @Purpose: function to get id of options to calculate the score
    * @params: $finalArray 
    */
    function calculateScore($finalArray){
         
        $score = 0;
        if(isset($finalArray['key_behaviour']['answer_id']) && !empty($finalArray['key_behaviour']['answer_id'])) {
            $res = $this->db->get_results("select question_option  from d_intro_assess_que_option where o_id  IN (".implode(',',$finalArray['key_behaviour']['answer_id']).")");
            $res = array_column($res,'question_option');
            $answerOptions =  explode(',', KEY_BEHAVIOURS );
            foreach($res as $data){
                if(in_array($data, $answerOptions)) {
                    $score++;
                }
            }
        }
        if(isset($finalArray['classroom_observation']['answer_id']) && !empty($finalArray['classroom_observation']['answer_id'])) {
            $res = $this->db->get_results("select question_option  from d_intro_assess_que_option where o_id  IN (".implode(',',$finalArray['classroom_observation']['answer_id']).")");
            $res = array_column($res,'question_option');
            $answerOptions =  explode('~', CLASSROOM_OBSERVATION );
            foreach($res as $data){
                if(in_array($data, $answerOptions)) {
                    $score++;
                }
            }
        }
        if(isset($finalArray['stakeholder_text']['answer_id']) && !empty($finalArray['stakeholder_text']['answer_id'])) {
            $res = $this->db->get_results("select question_option  from d_intro_assess_que_option where o_id  IN (".implode(',',$finalArray['stakeholder_text']['answer_id']).")");
            $res = array_column($res,'question_option');
            $answerOptions =  explode('~', STAKEHOLEDER );
            foreach($res as $data){
                if(in_array($data, $answerOptions)) {
                    $score++;
                }
            }
        }
        if(isset($finalArray['leader_statement']) && !empty($finalArray['leader_statement'])) {
            if($finalArray['leader_statement'] == 'Supposition') {
                    $score++;
                }
        }
        if(isset($finalArray['statement']) && !empty($finalArray['statement'])) {
            
                if($finalArray['statement'] == 'Evidence') {
                    $score++;
                }
            
        }
        if(isset($finalArray['school_rating']) && !empty($finalArray['school_rating'])) {
            
                if($finalArray['school_rating'] == 'Sometimes') {
                    $score++;
                }
            
        }
        if(isset($finalArray['rating_text']['answer_id']) && !empty($finalArray['rating_text']['answer_id'])) {
            $res = $this->db->get_results("select question_option  from d_intro_assess_que_option where o_id  IN (".implode(',',$finalArray['rating_text']['answer_id']).")");
            $res = array_column($res,'question_option');
            $answerOptions =  explode('~', SCHOOL_RATING );
            foreach($res as $data){
                if(in_array($data, $answerOptions)) {
                    $score++;
                }
            }
        }
       return  $score;
                 
    }
    
    /*
    * @Purpose: Function to get language
    * @params: $lang_code 
    */
    function getLanguageByCode($lang_code){
    	$res = $this->db->get_row("select language_id from d_language where language_code=? ;", array($lang_code));
    	return $res ? $res['language_id'] : '';
    }
    
    /*
    * @Purpose: Function to get translated language
    * @params: $lang_code 
    */
    function getTranslationLanguale($lang_code){
        $len = count($lang_code);
    	$res = $this->db->get_results("select language_id,language_name,language_code,language_words from d_language where language_code in (?" . str_repeat(",?", $len - 1) . ") ORDER BY language_name ASC;", $lang_code);
    	return $res ? $res: array();
    }
    
    /*
    * @Purpose: Set maximum concat length 
    */
    function setmaxconcatlength(){
        $queryMaxLength="SET SESSION group_concat_max_len = 1000000;";
        $this->db->query($queryMaxLength);
        
    }
    
    /*
    * @Purpose: Function to check user session exists or not
    * @params: $token 
    */
    function userExist($token){
        $token = current( explode("--", $token) );
        $queryT="select * from session_token where token=?";
        $res = $this->db->get_results($queryT, array($token));
        if($res){
         return true;   
        }else{
         return false;  
        }
    }
    
    /*
    * @Purpose: Function to get user-type detail using userId
    * @params: $usertype_id 
    */
    function getUserTypeAjax($usertype_id){
		$sql = "select user_type_id ,user_type_name from d_user_type where user_type_id = ?";
		$res = $this->db->get_results($sql,array($usertype_id));
		return $res?$res:array();
	} 
        
    /*
    * @Purpose: Function to get the users-type detail
    */    
    function getUserTypeRef(){
            $sql = "select user_type_id ,user_type_name from d_user_type";
            $res = $this->db->get_results($sql);
            return $res?$res:array();
    } 
    
   
    /*
    * @Purpose: Function to get the users-type
    */
    public function getUserTypeList(){
		$res=$this->db->get_results("select * from d_user_type order by user_type_id asc");
		return $res?$res:array();
    }
    
    
    /*
    * @Purpose: Function to get user-type-ID using userId
    * @params: $user_id 
    */
    function getUserTypeID($user_id){
		$sql = "select user_type_id from h_user_admin_levels where user_id =?";
		$res = $this->db->get_results($sql,array($user_id)); 
		return $res?$res:array();
    }
    
    /*
    * @Purpose: Function to get state-ID using userId
    * @params: $user_id 
    */
    function getStateID($user_id){
		$sql = "select state_id from h_user_admin_levels where user_id =?";
		$res = $this->db->get_results($sql,array($user_id));
		return $res?$res:array();
    }
   
    /*
    * @Purpose: Function to get zone-ID using userId
    * @params: $user_id 
    */
    function getZoneID($user_id){
		$sql = "select distinct zone_id,state_id from h_user_admin_levels where user_id =?";
		$res = $this->db->get_results($sql,array($user_id));
		return $res?$res:array();
    }
    
    /*
    * @Purpose: Function to get block-ID using userId 
    * @params: $user_id 
    */
    function getBlockID($user_id){
		$sql = "select state_id,zone_id,block_id from h_user_admin_levels where user_id =?";
		$res = $this->db->get_results($sql,array($user_id));
		return $res?$res:array();
    }
    
    /*
    * @Purpose: Function to get cluster-ID using userId
    * @params: $user_id 
    */
    function getClusterID($user_id){
		$sql = "select state_id,zone_id,block_id,cluster_id from h_user_admin_levels where user_id =?";
		$res = $this->db->get_results($sql,array($user_id));
		return $res?$res:array();
    }
    
    /*
    * @Purpose: Function to get statelist 
    */
    public function getStateList(){
		$res=$this->db->get_results("select * from d_state order by state_id asc");
		return $res?$res:array();
    }
    
    /*
    * @Purpose: Function to create state dropdown in case of adding new user
    * @params: $usertype_id 
    */
    public function getStateForStateAdminAdd($usertype_id){
                 
        if($usertype_id==1){ 
           
                 $sql_st="select DISTINCT state_id from h_user_admin_levels WHERE state_id!='NULL' AND zone_id IS NULL AND block_id IS NULL AND cluster_id IS NULL";
                $res_st=$this->db->get_results($sql_st);
                
                if(!empty($res_st)){
                foreach($res_st as $res_st1){
                    $selected_stateIDS[]=$res_st1['state_id'];
                }
                $stateID_NOTIN_arr=implode(',',$selected_stateIDS);
                $statenotin="AND s.state_id NOT IN ($stateID_NOTIN_arr)";
                }else{
                    $statenotin="";
                }
                
		$res=$this->db->get_results("select distinct s.state_id ,s.state_name from d_state as s LEFT JOIN 
                h_user_admin_levels as ual ON s.state_id=ual.state_id WHERE 1 $statenotin order by s.state_id asc;");
                
		return $res?$res:array();
    }else if($usertype_id==2){
            
                $sql_zo="select DISTINCT zone_id from h_user_admin_levels WHERE state_id!='NULL' AND zone_id IS NOT NULL AND block_id IS NULL AND cluster_id IS NULL";
                $res_zo=$this->db->get_results($sql_zo);
                
                if(!empty($res_zo)){
                foreach($res_zo as $res_zo1){
                    $selected_zoneIDS[]=$res_zo1['zone_id'];
                }
                $zoneID_NOTIN_arr=implode(',',$selected_zoneIDS);
                $zonenotin="AND nzs.zone_id NOT IN ($zoneID_NOTIN_arr)";
                }else{
                    $zonenotin="";
                }
                
                $sql_zo="select distinct s.state_id ,s.state_name from d_state as s 
                LEFT JOIN h_network_zone_state as nzs ON nzs.state_id=s.state_id
                LEFT JOIN h_user_admin_levels as ual ON s.state_id=ual.state_id 
                WHERE 1 $zonenotin
                order by s.state_id asc";
                $res=$this->db->get_results($sql_zo);
		return $res?$res:array();
        
    }else if($usertype_id==3){
            
                $sql_zo="select DISTINCT block_id from h_user_admin_levels WHERE state_id!='NULL' AND zone_id IS NOT NULL AND block_id IS NOT NULL AND cluster_id IS NULL";
                $res_zo=$this->db->get_results($sql_zo);
                
                if(!empty($res_zo)){
                foreach($res_zo as $res_zo1){
                    $selected_zoneIDS[]=$res_zo1['block_id'];
                }
                $zoneID_NOTIN_arr=implode(',',$selected_zoneIDS);
                $zonenotin="AND nzs.network_id NOT IN ($zoneID_NOTIN_arr)";
                }else{
                    $zonenotin="";
                }
                
                $sql_zo="select distinct s.state_id ,s.state_name from d_state as s 
                LEFT JOIN h_network_zone_state as nzs ON nzs.state_id=s.state_id
                WHERE 1 $zonenotin
                order by s.state_id asc";
                $res=$this->db->get_results($sql_zo);
		return $res?$res:array();
        
    }else if($usertype_id==4){
            
                $sql_zo="select DISTINCT cluster_id from h_user_admin_levels WHERE state_id!='NULL' AND zone_id IS NOT NULL AND block_id IS NOT NULL AND cluster_id IS NOT NULL";
                $res_zo=$this->db->get_results($sql_zo);
                
                if(!empty($res_zo)){
                foreach($res_zo as $res_zo1){
                    $selected_zoneIDS[]=$res_zo1['cluster_id'];
                }
                $zoneID_NOTIN_arr=implode(',',$selected_zoneIDS);
                $zonenotin="AND nzs.cluster_id NOT IN ($zoneID_NOTIN_arr)";
                }else{
                    $zonenotin="";
                }
                
                $sql_zo="select distinct s.state_id ,s.state_name from d_state as s 
                LEFT JOIN h_cluster_block_zone_state as nzs ON nzs.state_id=s.state_id
                WHERE 1 $zonenotin
                order by s.state_id asc";
                $res=$this->db->get_results($sql_zo);
		return $res?$res:array();
        
    }else{
        
                $res=$this->db->get_results("select distinct s.state_id ,s.state_name from d_state as s LEFT JOIN 
                h_user_admin_levels as ual ON s.state_id=ual.state_id order by s.state_id asc;");
		return $res?$res:array();
        
    }
    }
    
    /*@Purpose: Function to display statelist dropdown using userId
     *@Params: $user_id
     */
    public function getStateForStateAdminEdit($user_id=NULL){
                 
        if($user_id!=""){
            $userIDD="user_id!=$user_id AND";
        }else{
            $userIDD="";
        }
                 $sql_st="select DISTINCT state_id from h_user_admin_levels WHERE $userIDD state_id!='NULL' AND zone_id IS NULL AND block_id IS NULL AND cluster_id IS NULL";
                $res_st=$this->db->get_results($sql_st);
                
                
                if(!empty($res_st)){
                foreach($res_st as $res_st1){
                    $selected_stateIDS[]=$res_st1['state_id'];
                }
                $stateID_NOTIN_arr=implode(',',$selected_stateIDS);
                $statenotin="AND s.state_id NOT IN ($stateID_NOTIN_arr)";
                }else{
                    $statenotin="";
                }
               
		$res=$this->db->get_results("select distinct s.state_id ,s.state_name from d_state as s LEFT JOIN 
                h_user_admin_levels as ual ON s.state_id=ual.state_id WHERE 1 $statenotin order by s.state_id asc;");
		return $res?$res:array();
    }
    
    /*@Purpose: Function to display statelist dropdown using userId
     *@Params: $user_id
     */
    public function getStateEditList($user_id){ 
        
        $usertypeID_arr=$this->getUserTypeID($user_id);
        if(!empty($usertypeID_arr)){
        $user_type_id=$usertypeID_arr[0]['user_type_id'];
        
        if($user_type_id==1){
        $state_sql="SELECT DISTINCT state_id from h_user_admin_levels where user_id!=? AND state_id!='NULL' AND zone_id IS NULL AND block_id IS NULL AND cluster_id IS NULL";
        
        $state_res = $this->db->get_results($state_sql,array($user_id)); 
        $state_res?$state_res:array();
        
        
        if(!empty($state_res)){
            foreach($state_res as $state_res1){
            $selected_stateIDS[]=$state_res1['state_id'];
         }
            $stateID_NOTIN_arr=implode(',',$selected_stateIDS);
            $statenotin="WHERE z.state_id NOT IN ($stateID_NOTIN_arr)";
        }else{
            $statenotin="";
        }
        
        $state_id_arr=$this->getStateID($user_id);
        
        if(!empty($state_id_arr)){
        $state_id=$state_id_arr[0]['state_id'];
        $state_qry="WHERE zs.state_id='$state_id'";
        }else{
        $state_qry='WHERE 1';    
        }
        
        
        $sql="SELECT DISTINCT  z.state_id, z.state_name FROM
        d_state AS z LEFT JOIN h_user_admin_levels AS ual ON z.state_id = ual.state_id
      $statenotin ORDER BY z.state_id ASC";
        
        $res = $this->db->get_results($sql);
        return $res?$res:array();
    }else if($user_type_id==2 || $user_type_id==3 || $user_type_id==4){
       
      $sql="SELECT DISTINCT  z.state_id, z.state_name FROM
    d_state AS z LEFT JOIN h_user_admin_levels AS ual ON z.state_id = ual.state_id
      ORDER BY z.state_id ASC";
                
        $res = $this->db->get_results($sql);
        return $res?$res:array();
                
        }
        }  
    }
    
    /*@Purpose: Function to display zonelist dropdown using userId
     *@Params: $user_id
     */
    public function getZoneList($user_id){
        
        $usertypeID_arr=$this->getUserTypeID($user_id);
        if(!empty($usertypeID_arr)){
        $user_type_id=$usertypeID_arr[0]['user_type_id'];
        
        if($user_type_id==2){
        $zone_sql="SELECT DISTINCT zone_id from h_user_admin_levels where user_id!=? AND zone_id!='NULL' AND block_id IS NULL AND cluster_id IS NULL";
        
        $zone_res = $this->db->get_results($zone_sql,array($user_id));
        $zone_res?$zone_res:array();
        
        
        if(!empty($zone_res)){
            foreach($zone_res as $zone_res1){
            $selected_zoneIDS[]=$zone_res1['zone_id'];
         }
            $zoneID_NOTIN_arr=implode(',',$selected_zoneIDS);
            $zonenotin="AND z.zone_id NOT IN ($zoneID_NOTIN_arr)";
        }else{
            $zonenotin="";
        }
        
        if($_GET['id']==$user_id){
           
        }
       
        
        $state_id_arr=$this->getStateID($user_id);
        
        if(!empty($state_id_arr)){
        $state_id=$state_id_arr[0]['state_id'];
        $state_qry="WHERE zs.state_id='$state_id'";
        }else{
        $state_qry='WHERE 1';    
        }
        
        
        $sql="SELECT DISTINCT  z.zone_id, z.zone_name FROM
    d_zone AS z INNER JOIN h_zone_state AS zs ON z.zone_id = zs.zone_id
        LEFT JOIN h_user_admin_levels AS ual ON z.zone_id = ual.zone_id
    $state_qry $zonenotin ORDER BY zs.zone_id ASC";
                
        $res = $this->db->get_results($sql);
        return $res?$res:array();
    }else if($user_type_id==3){
        
        $block_sql="SELECT DISTINCT block_id from h_user_admin_levels where user_id!=? AND zone_id!='NULL' AND block_id IS NOT NULL AND cluster_id IS NULL";
        
        
        $block_res = $this->db->get_results($block_sql,array($user_id));
        $block_res?$block_res:array();
        
        
        if(!empty($block_res)){
            foreach($block_res as $block_res1){
            $selected_blockIDS[]=$block_res1['block_id'];
         }
            $blockID_NOTIN_arr=implode(',',$selected_blockIDS);
            $blocknotin="AND nzs.network_id NOT IN ($blockID_NOTIN_arr)";
        }else{
            $blocknotin="";
        }
        
        $state_id_arr=$this->getStateID($user_id);
        
        if(!empty($state_id_arr)){
        $state_id=$state_id_arr[0]['state_id'];
        $state_qry="WHERE nzs.state_id='$state_id'";
        }else{
        $state_qry='WHERE 1';    
        }
        
        
        $sql="select DISTINCT z.zone_id, z.zone_name from d_zone z 
        INNER JOIN h_network_zone_state nzs
        ON z.zone_id = nzs.zone_id 
        INNER JOIN h_user_admin_levels AS ual 
        ON z.zone_id = ual.zone_id 
        $state_qry  $blocknotin ORDER BY z.zone_id ASC";
                
        $res = $this->db->get_results($sql);
        return $res?$res:array();
                
        }else if($user_type_id==4){
                
        $state_id_arr=$this->getStateID($user_id);
        
        if(!empty($state_id_arr)){
        $state_id=$state_id_arr[0]['state_id'];
        $state_qry="WHERE zs.state_id='$state_id'";
        }else{
        $state_qry='WHERE 1';    
        }
        
        $sql="SELECT DISTINCT  z.zone_id, z.zone_name FROM
    d_zone AS z INNER JOIN h_zone_state AS zs ON z.zone_id = zs.zone_id
        LEFT JOIN h_user_admin_levels AS ual ON z.zone_id = ual.zone_id
        $state_qry ORDER BY zs.zone_id ASC"; 
                
                $res = $this->db->get_results($sql);
		return $res?$res:array();
        }
        }  
    }
    
    /*@Purpose: Function to display blocklist dropdown using userId
     *@Params: $user_id
     */
     public function getBlockList($user_id){      
         
		$sql="select distinct n.network_id,n.network_name from d_network as n inner join
h_network_zone_state as nzs on n.network_id= nzs.network_id inner join
h_user_admin_levels as ual ON n.network_id= ual.block_id order by nzs.network_id asc";
                $res = $this->db->get_results($sql);
		return $res?$res:array();
    }
    
    /*@Purpose: Function to display blocklist dropdown using userId
     *@Params: $user_id
     */
    public function getBlockListEdit($user_id){
       
        $usertypeID_arr=$this->getUserTypeID($user_id);
        if(!empty($usertypeID_arr)){
        $user_type_id=$usertypeID_arr[0]['user_type_id'];
        
         if($user_type_id==3){
         $zone_id=$this->getZoneID($user_id);
         foreach($zone_id as $zone_ids){
            $selected_zoneIDS[]=$zone_ids['zone_id'];
         }
         if(!empty($selected_zoneIDS)){
         $zoneID_str=implode(',',$selected_zoneIDS);
         
         
         if(!empty($selected_zoneIDS)){
         
            $zonein=" AND nzs.zone_id IN ($zoneID_str)";
        }else{
            $zonein="";
        }
         
         
       $block_sql="SELECT DISTINCT block_id from h_user_admin_levels where user_id!=? AND block_id!='NULL' AND cluster_id IS NULL";
        
        $block_res = $this->db->get_results($block_sql,array($user_id));
        $block_res?$block_res:array();
        
        
        if(!empty($block_res)){
            foreach($block_res as $block_res1){
            $selected_blockIDS[]=$block_res1['block_id'];    
            }
            $blockID_NOTIN_arr=implode(',',$selected_blockIDS);
            $blocknotin=" AND nzs.network_id NOT IN ($blockID_NOTIN_arr)";
        }else{
            $blocknotin="";
        }
        
        if($_GET['id']==$user_id){ 
            
        }
        
        $state_id_arr=$this->getStateID($user_id);
        if(!empty($state_id_arr)){
        $state_id=$state_id_arr[0]['state_id'];
        $state_qry=" AND nzs.state_id='$state_id'";
        }else{
        $state_qry="";    
        }
         
         
        $sql="select distinct n.network_id,n.network_name from d_network as n inner join
h_network_zone_state as nzs on n.network_id= nzs.network_id left join
h_user_admin_levels as ual ON n.network_id= ual.block_id WHERE 1 "
                        . "$state_qry $blocknotin $zonein order by nzs.network_id asc";
        
                $res = $this->db->get_results($sql);
		return $res?$res:array();
         }
        }else if($user_type_id==4){
            
        $state_id_arr=$this->getStateID($user_id);
        if(!empty($state_id_arr)){
        $state_id=$state_id_arr[0]['state_id'];
        $state_qry=" AND nzs.state_id='$state_id'";
        }else{
        $state_qry="";    
        }
         
        $zone_id=$this->getZoneID($user_id);
         foreach($zone_id as $zone_ids){
            $selected_zoneIDS[]=$zone_ids['zone_id'];
         }
         if(!empty($selected_zoneIDS)){
         $zoneID_str=implode(',',$selected_zoneIDS);
         
         
         if(!empty($selected_zoneIDS)){
         
            $zonein=" AND nzs.zone_id IN ($zoneID_str)";
        }else{
            $zonein="";
        }
        }
        
        
        $block_sql="SELECT DISTINCT block_id from h_user_admin_levels where user_id!=? AND block_id!='NULL' AND cluster_id IS NOT NULL";
        
        $block_res = $this->db->get_results($block_sql,array($user_id));
        $block_res?$block_res:array();
        
        
        if(!empty($block_res)){
            foreach($block_res as $block_res1){
            $selected_blockIDS[]=$block_res1['block_id'];    
            }
            $blockID_NOTIN_arr=implode(',',$selected_blockIDS);
            $blocknotin=" AND nzs.network_id NOT IN ($blockID_NOTIN_arr)";
        }else{
            $blocknotin="";
        }
        
        
         $cluster_sql="SELECT DISTINCT cluster_id from h_user_admin_levels where user_id!=? AND block_id!='NULL' AND cluster_id IS NOT NULL";
        
        $cluster_res = $this->db->get_results($cluster_sql,array($user_id));
        $cluster_res?$cluster_res:array();
        
        
        if(!empty($cluster_res)){
            foreach($cluster_res as $cluster_res1){
            $selected_clusterIDS[]=$cluster_res1['cluster_id'];    
            }
            $clusterID_NOTIN_arr=implode(',',$selected_clusterIDS);
            $clusternotin=" AND nzs.cluster_id NOT IN ($clusterID_NOTIN_arr)";
        }else{
            $clusternotin="";
        }
        
        
       $sql="select distinct n.network_id,n.network_name from d_network as n inner join
h_cluster_block_zone_state as nzs on n.network_id= nzs.block_id left join
h_user_admin_levels as ual ON n.network_id= ual.block_id WHERE 1 "
                        . "$state_qry $zonein $clusternotin order by n.network_id asc";
        
        $res = $this->db->get_results($sql,array($user_id));
        return $res?$res:array();
            
        }
        
        } 
    }
    
    
    /*@Purpose: Function to display clusterlist dropdown using userId
     *@Params: $user_id
     */
    public function getClusterList($user_id){
        
        $usertypeID_arr=$this->getUserTypeID($user_id);
        if(!empty($usertypeID_arr)){
        $user_type_id=$usertypeID_arr[0]['user_type_id'];
        
         if($user_type_id==4){
         $zone_id=$this->getZoneID($user_id);
         foreach($zone_id as $zone_ids){
            $selected_zoneIDS[]=$zone_ids['zone_id'];
         }
         if(!empty($selected_zoneIDS)){
         $zoneID_str=implode(',',$selected_zoneIDS);
         
         
        $block_sql="SELECT DISTINCT block_id from h_user_admin_levels where user_id=? AND block_id!='NULL'";
        
        $block_res = $this->db->get_results($block_sql,array($user_id));
        $block_res?$block_res:array();
        
          if(!empty($block_res)){
            foreach($block_res as $block_res1){
            $selected_blockIDS[]=$block_res1['block_id'];    
            }
            
            $selected_blockIDS= array_unique($selected_blockIDS);
            $blockID_NOTIN_arr=implode(',',$selected_blockIDS);
            $blocknotin=" AND cbzs.block_id IN ($blockID_NOTIN_arr)";
            
          }else{
            $blocknotin="";
        }
         
     
       $cluster_sql="SELECT DISTINCT cluster_id from h_user_admin_levels where user_id!=? AND cluster_id!='NULL'";
        
        $cluster_res = $this->db->get_results($cluster_sql,array($user_id));
        $cluster_res?$cluster_res:array();
        
        if(!empty($cluster_res)){
            foreach($cluster_res as $cluster_res1){
            $selected_clusterIDS[]=$cluster_res1['cluster_id'];    
            }
            
            $clusterID_NOTIN_arr=implode(',',$selected_clusterIDS);
            $clusternotin=" AND cbzs.cluster_id NOT IN ($clusterID_NOTIN_arr)";
          
        }else{
            $clusternotin="";
        }
        
        if($_GET['id']==$user_id){
            
        }
         
         
        $state_id_arr=$this->getStateID($user_id);
        
        if(!empty($state_id_arr)){
        $state_id=$state_id_arr[0]['state_id'];
        $state_qry=" AND cbzs.state_id='$state_id'";
        }else{
        $state_qry="";    
        }
        
       $sql="select distinct p.province_id,p.province_name from d_province as p inner join
h_cluster_block_zone_state as cbzs on p.province_id= cbzs.cluster_id left join
h_user_admin_levels as ual ON p.province_id= ual.cluster_id WHERE 1 "
                        . "$state_qry $clusternotin $blocknotin order by cbzs.cluster_id asc";
        
                $res = $this->db->get_results($sql);
		return $res?$res:array();
         }
        }
        } 
    }
 
}