<?php
/*
 * Perpose: Manage resource module
 * Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 */
class resourceModel extends Model {
    function updateUploadedFile($newName, $file_id) {
        return $this->db->update("d_file", array('file_name' => $newName), array("file_id" => $file_id));
    }
    
    //function to get schools by option type
    public function getSchoolsList($option_type) {

        $sql = "select client_id,client_name from d_client ORDER BY client_name ASC";
        if ($option_type == '2') {
            $sql = "SELECT d.client_id,d.client_name,h.network_id FROM d_client d LEFT JOIN "
                    . " `h_client_network` h ON d.client_id = h.client_id WHERE h.network_id IS null ORDER BY client_name ASC";
        }
        $res = $this->db->get_results($sql);
        return $res ? $res : array();
    }
    
    //function to get school users
    function getSchoolUsers($school_ids,$user_role_ids='') {
        
          $sqlCond= '';
          if(!empty($user_role_ids)) {
              $sqlCond = "AND ur.role_id IN($user_role_ids)";
          }
          $SQL = "Select u.name,u.user_id from d_user as u "
            ." LEFT JOIN d_client as cl on u.client_id = cl.client_id "
            ." LEFT JOIN h_user_user_role urol  on u.user_id = urol.user_id "
            ." LEFT JOIN h_user_user_role ur  on u.user_id = ur.user_id " 
            ." WHERE cl.client_id IN ($school_ids ) $sqlCond  GROUP BY ur.user_id";

        if ($res = $this->db->get_results($SQL)) {
            return $res;
        } else {
            return null;
        }
    }
    //function to get school users roles
    function getSchoolUsersRoles($school_ids) {
        
            $SQL = "Select ur.role_name,ur.role_id from d_user as u "
            ." LEFT JOIN d_client as cl on u.client_id = cl.client_id "
            ." LEFT JOIN h_user_user_role urol  on u.user_id = urol.user_id "
            ." LEFT JOIN d_user_role ur  on urol.role_id = ur.role_id " 
            ." WHERE cl.client_id IN ($school_ids )   GROUP BY  ur.role_id";

        if ($res = $this->db->get_results($SQL)) {
            return $res;
        } else {
            return null;
        }
    }
    
} 