<?php

/**
 * Reasons: manage all data for school assessment report
 * Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 */
class reportModel extends Model {

    /**
     * Get all list of assessment
     * @param object $request condition for fetch data
     */
    function getAllSchoolAssessments($request) {
        $cond = '';
        if (isset($request['state'])) {
            $cond .= "and s.state_id=" . $request['state'];
        }
        if (isset($request['zone'])) {
            $cond .= " and s.state_id=" . $request['state'];
        }
        if (isset($request['province'])) {
            $cond .= " and p.province_id IN( " . implode(',', $request['province']) . ")";
        }
        if (isset($request['network'])) {
            $cond .= " and n.network_id IN( " . implode(',', $request['network']) . ")";
        }
        $sql = "SELECT distinct g.assessment_id,n.network_name,p.province_name,g.client_id,c.client_name,b.user_id
                  FROM d_state s
                  INNER JOIN h_zone_state zs
                          ON s.state_id = zs.state_id
                  INNER JOIN d_zone z
                          ON z.zone_id = zs.zone_id
                  INNER JOIN h_network_zone_state AS nzs
                          ON nzs.zone_id = z.zone_id
                  INNER JOIN d_network n
                          ON n.network_id = nzs.network_id
                  INNER JOIN h_cluster_block_zone_state cbzs
                          ON cbzs.block_id = n.network_id
                  INNER JOIN d_province p
                          ON p.province_id = cbzs.cluster_id
                  INNER JOIN h_client_province cp
                          ON cp.province_id = p.province_id
                  INNER JOIN d_client c
                          ON c.client_id = cp.client_id
                  INNER JOIN d_assessment g
                          ON g.client_id = c.client_id
                  INNER JOIN h_assessment_user b
                          ON g.assessment_id = b.assessment_id
                    WHERE b.role=4 AND b.isFilled=1  $cond 
                  and  percComplete=100.00 and aqs_round=1";
        $res = $this->db->get_results($sql);
        return $res;
    }

}
