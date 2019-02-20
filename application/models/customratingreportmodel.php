<?php
/**
 * Reasons: Manage data for Client module
* Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 */
class customratingreportModel extends Model{
    function statUserState($userId){
        $sql="SELECT a.*,b.state_name FROM h_user_admin_levels a left join d_state b on a.state_id = b.state_id  where user_id=?";
        $data = $this->db->get_results($sql,[$userId]);
        return $data;
    }
    function userStateZoneBlockCluster($userId,$type,$blockId='',$zoneId=''){
        $clustCond = '';
        $arr = [$userId];
        if(isset($blockId) && !empty($blockId)){
            $clustCond = "and a.block_id =?";
            array_push($arr,$blockId);
        }
        $blockCond = '';
        if(isset($zoneId) && !empty($zoneId)){
            $blockCond = "and a.zone_id =?";
            array_push($arr,$zoneId);
        }
        $sql = "SELECT a.*,b.state_name, c.zone_name,d.network_name,e.province_name FROM h_user_admin_levels a left join d_state b on a.state_id = b.state_id join d_zone c on a.zone_id = c.zone_id left join d_network d on d.network_id = a.block_id left join d_province e on e.province_id = a.cluster_id where user_id=? $clustCond $blockCond";
        $data = $this->db->get_results($sql,$arr);
        return $data;
    }
            
    function getKpaZoneData($stateId,$zoneId, $aqs=1, $blockId = '', $clusterId = ''){   
        $blockCond = '';
        $arr=[$stateId,$zoneId,$aqs];
        if(isset($blockId) && !empty($blockId)){
            $blockCond = "and i.block_id=?";
            array_push($arr, $blockId);
        }
        $clustCond = '';
        if(isset($clusterId) && !empty($clusterId)){
            $clustCond = "and j.province_id=?";
            array_push($arr, $clusterId);
        }
        $sql = "select count(a.rating_id) as level1, group_concat(if(m.kpa_id=1,r.translation_text,null)) as level2, n.translation_text as Kpa_name,m.kpa_id, a.judgement_statement_instance_id,p.translation_text as rating,r.translation_text as kd1_part2rating, client_name,province_name as cluster_name ,network_name as block_name,zone_name,state_name
from f_score a
inner join h_cq_js_instance b on a.judgement_statement_instance_id=b.judgement_statement_instance_id
inner join h_kq_cq c on b.core_question_instance_id=c.core_question_instance_id
inner join h_kpa_kq d on c.key_question_instance_id=d.key_question_instance_id
inner join h_kpa_diagnostic e on d.kpa_instance_id=e.kpa_instance_id
inner join d_kpa m on e.kpa_id=m.kpa_id
inner join h_lang_translation n on m.equivalence_id=n.equivalence_id
inner join d_assessment f on a.assessment_id=f.assessment_id
inner join d_client g on f.client_id=g.client_id
inner join h_client_province h on h.client_id=g.client_id
inner join h_cluster_block_zone_state i on h.province_id=i.cluster_id
inner join d_province j on i.cluster_id=j.province_id
inner join d_network l on i.block_id=l.network_id
inner join d_zone k on k.zone_id=i.zone_id
inner join d_state u on i.state_id=u.state_id
inner join d_rating o on a.rating_id=o.rating_id
inner join h_lang_translation p on o.equivalence_id=p.equivalence_id
inner join h_assessment_user s on a.assessment_id=s.assessment_id and a.assessor_id=s.user_id and role=4 and s.isFilled =1 and s.percComplete=100.00
left join d_rating q on q.rating_id=a.level2rating
left join h_lang_translation r on r.equivalence_id=q.equivalence_id
where isFinal=1 and f.isAssessmentActive=1 and u.state_id =? and k.zone_id = ?  and f.aqs_round = ? $blockCond $clustCond  group by kpa_id,a.rating_id";
    	$data = $this->db->get_results($sql,$arr);
            return $data;
    }
    
    
    function getKpaZoneBlockData($stateId, $zoneId,$aqs=1){    	
      $sql = "select count(a.rating_id) as level1, group_concat(if(m.kpa_id=1,r.translation_text,null)) as level2, n.translation_text as Kpa_name,m.kpa_id, a.judgement_statement_instance_id,p.translation_text as rating,r.translation_text as kd1_part2rating, client_name,province_name as cluster_name ,network_name as block_name,zone_name,state_name

from f_score a
inner join h_cq_js_instance b on a.judgement_statement_instance_id=b.judgement_statement_instance_id
inner join h_kq_cq c on b.core_question_instance_id=c.core_question_instance_id
inner join h_kpa_kq d on c.key_question_instance_id=d.key_question_instance_id
inner join h_kpa_diagnostic e on d.kpa_instance_id=e.kpa_instance_id
inner join d_kpa m on e.kpa_id=m.kpa_id
inner join h_lang_translation n on m.equivalence_id=n.equivalence_id
inner join d_assessment f on a.assessment_id=f.assessment_id
inner join d_client g on f.client_id=g.client_id
inner join h_client_province h on h.client_id=g.client_id
inner join h_cluster_block_zone_state i on h.province_id=i.cluster_id
inner join d_province j on i.cluster_id=j.province_id
inner join d_network l on i.block_id=l.network_id
inner join d_zone k on k.zone_id=i.zone_id
inner join d_state u on i.state_id=u.state_id
inner join d_rating o on a.rating_id=o.rating_id
inner join h_lang_translation p on o.equivalence_id=p.equivalence_id
inner join h_assessment_user s on a.assessment_id=s.assessment_id and a.assessor_id=s.user_id and role=4 and s.isFilled =1 and s.percComplete=100.00
left join d_rating q on q.rating_id=a.level2rating
left join h_lang_translation r on r.equivalence_id=q.equivalence_id
where isFinal=1 and f.isAssessmentActive=1 and u.state_id = ? and k.zone_id = ? and f.aqs_round = ? group by kpa_id,i.block_id,a.rating_id;";

    	$data = $this->db->get_results($sql,[$stateId,$zoneId,$aqs]);
            return $data;
    }
    
    function getStateKpaZoneBlockData($request,$zoneId,$aqs=1){  
    if($request['type'] == 'level2')
        $grcond=" r.translation_text";
    else
        $grcond=" a.rating_id";        
      $sql = "select count(a.rating_id) as level1, group_concat(if(m.kpa_id=1,r.translation_text,null)) as level2, n.translation_text as Kpa_name,m.kpa_id, a.judgement_statement_instance_id,p.translation_text as rating,r.translation_text as kd1_part2rating, client_name,province_name as cluster_name ,network_name as block_name,zone_name,state_name

from f_score a
inner join h_cq_js_instance b on a.judgement_statement_instance_id=b.judgement_statement_instance_id
inner join h_kq_cq c on b.core_question_instance_id=c.core_question_instance_id
inner join h_kpa_kq d on c.key_question_instance_id=d.key_question_instance_id
inner join h_kpa_diagnostic e on d.kpa_instance_id=e.kpa_instance_id
inner join d_kpa m on e.kpa_id=m.kpa_id
inner join h_lang_translation n on m.equivalence_id=n.equivalence_id
inner join d_assessment f on a.assessment_id=f.assessment_id
inner join d_client g on f.client_id=g.client_id
inner join h_client_province h on h.client_id=g.client_id
inner join h_cluster_block_zone_state i on h.province_id=i.cluster_id
inner join d_province j on i.cluster_id=j.province_id
inner join d_network l on i.block_id=l.network_id
inner join d_zone k on k.zone_id=i.zone_id
inner join d_state u on i.state_id=u.state_id
inner join d_rating o on a.rating_id=o.rating_id
inner join h_lang_translation p on o.equivalence_id=p.equivalence_id
inner join h_assessment_user s on a.assessment_id=s.assessment_id and a.assessor_id=s.user_id and role=4 and s.isFilled =1 and s.percComplete=100.00
left join d_rating q on q.rating_id=a.level2rating
left join h_lang_translation r on r.equivalence_id=q.equivalence_id
where isFinal=1 and f.isAssessmentActive=1 and k.zone_id = ? and m.kpa_id = ?  and   f.aqs_round = ? group by network_name, $grcond;";

    	$data = $this->db->get_results($sql,[$zoneId,$request['kpa_id'],$request['round']]);
            return $data;
    }
    
    function getStateKpaZoneData($aqs=1,$stateId){    	
      $sql = "select count(a.rating_id) as level1, group_concat(if(m.kpa_id=1,r.translation_text,null)) as level2, n.translation_text as Kpa_name,m.kpa_id, a.judgement_statement_instance_id,p.translation_text as rating,r.translation_text as kd1_part2rating, client_name,province_name as cluster_name ,network_name as block_name,zone_name,state_name

from f_score a
inner join h_cq_js_instance b on a.judgement_statement_instance_id=b.judgement_statement_instance_id
inner join h_kq_cq c on b.core_question_instance_id=c.core_question_instance_id
inner join h_kpa_kq d on c.key_question_instance_id=d.key_question_instance_id
inner join h_kpa_diagnostic e on d.kpa_instance_id=e.kpa_instance_id
inner join d_kpa m on e.kpa_id=m.kpa_id
inner join h_lang_translation n on m.equivalence_id=n.equivalence_id
inner join d_assessment f on a.assessment_id=f.assessment_id
inner join d_client g on f.client_id=g.client_id
inner join h_client_province h on h.client_id=g.client_id
inner join h_cluster_block_zone_state i on h.province_id=i.cluster_id
inner join d_province j on i.cluster_id=j.province_id
inner join d_network l on i.block_id=l.network_id
inner join d_zone k on k.zone_id=i.zone_id
inner join d_state u on i.state_id=u.state_id
inner join d_rating o on a.rating_id=o.rating_id
inner join h_lang_translation p on o.equivalence_id=p.equivalence_id
inner join h_assessment_user s on a.assessment_id=s.assessment_id and a.assessor_id=s.user_id and role=4 and s.isFilled =1 and s.percComplete=100.00
left join d_rating q on q.rating_id=a.level2rating
left join h_lang_translation r on r.equivalence_id=q.equivalence_id
where isFinal=1 and f.isAssessmentActive=1 and f.aqs_round = ? and u.state_id=? group by kpa_id,k.zone_id,a.rating_id;";

    	$data = $this->db->get_results($sql,[$aqs,$stateId]);
            return $data;
    }
    
    function getKpaBlockClusterData($request,$aqs=1){    	

    if($request['type'] == 'level2')
        $grcond=" r.translation_text";
    else
        $grcond=" a.rating_id";

 $sql = "select count(a.rating_id) as level1, group_concat(if(m.kpa_id=1,r.translation_text,null)) as level2, n.translation_text as Kpa_name,m.kpa_id, a.judgement_statement_instance_id,p.translation_text as rating,r.translation_text as kd1_part2rating, client_name,province_name as cluster_name ,network_name as block_name,zone_name,state_name

from f_score a
inner join h_cq_js_instance b on a.judgement_statement_instance_id=b.judgement_statement_instance_id
inner join h_kq_cq c on b.core_question_instance_id=c.core_question_instance_id
inner join h_kpa_kq d on c.key_question_instance_id=d.key_question_instance_id
inner join h_kpa_diagnostic e on d.kpa_instance_id=e.kpa_instance_id
inner join d_kpa m on e.kpa_id=m.kpa_id
inner join h_lang_translation n on m.equivalence_id=n.equivalence_id
inner join d_assessment f on a.assessment_id=f.assessment_id
inner join d_client g on f.client_id=g.client_id
inner join h_client_province h on h.client_id=g.client_id
inner join h_cluster_block_zone_state i on h.province_id=i.cluster_id
inner join d_province j on i.cluster_id=j.province_id
inner join d_network l on i.block_id=l.network_id
inner join d_zone k on k.zone_id=i.zone_id
inner join d_state u on i.state_id=u.state_id
inner join d_rating o on a.rating_id=o.rating_id
inner join h_lang_translation p on o.equivalence_id=p.equivalence_id
inner join h_assessment_user s on a.assessment_id=s.assessment_id and a.assessor_id=s.user_id and role=4 and s.isFilled =1 and s.percComplete=100.00
left join d_rating q on q.rating_id=a.level2rating
left join h_lang_translation r on r.equivalence_id=q.equivalence_id
where isFinal=1 and f.isAssessmentActive=1 and f.aqs_round = ? and m.kpa_id = ? and network_name =? group by i.cluster_id,".$grcond.";";

    	$data = $this->db->get_results($sql,[$request['round'],$request['kpa_id'],$request['block']]);
        return $data;
    }
    
    function getKpaBlockSchoolData($request,$clsId,$languageId = DEFAULT_LANGUAGE,$zone_id = 6,$aqs=1){    	
        $grcond = '';
        $ncond="a.rating_id";
        if($request['type'] == 'level2'){
            $grcond=" and hjs.level_type=2";
            $ncond="a.level2rating";
        
        }
        
        
        
        
 $sql = "select  hl.translation_text as rating_level, hltjs.translation_text as jstext,m.kpa_id, a.judgement_statement_instance_id,p.translation_text as rating,r.translation_text as kd1_part2rating,n.translation_text as kpa_name, client_name, g.client_id, province_name as cluster_name ,network_name as block_name,zone_name,state_name
from f_score a
inner join h_cq_js_instance b on a.judgement_statement_instance_id=b.judgement_statement_instance_id 
INNER JOIN d_judgement_statement js ON js.judgement_statement_id = b.judgement_statement_id
INNER JOIN h_lang_translation hltjs ON js.equivalence_id = hltjs.equivalence_id and hltjs.language_id=?

inner join h_js_rating_definiton hjs on js.judgement_statement_id = hjs.judgement_statement_id $grcond
inner join d_rating_definition_copy rd on rd.definition_id = hjs.definition_id and $ncond=rd.rating_id
inner join h_lang_translation hl on hl.equivalence_id=rd.equivalence_id and hl.language_id =9 and hl.translation_type_id=11

inner join h_kq_cq c on b.core_question_instance_id=c.core_question_instance_id
inner join h_kpa_kq d on c.key_question_instance_id=d.key_question_instance_id
inner join h_kpa_diagnostic e on d.kpa_instance_id=e.kpa_instance_id
inner join d_kpa m on e.kpa_id=m.kpa_id
inner join h_lang_translation n on m.equivalence_id=n.equivalence_id  and n.language_id=?
inner join d_assessment f on a.assessment_id=f.assessment_id
inner join d_client g on f.client_id=g.client_id
inner join h_client_province h on h.client_id=g.client_id
inner join h_cluster_block_zone_state i on h.province_id=i.cluster_id
inner join d_province j on i.cluster_id=j.province_id
inner join d_network l on i.block_id=l.network_id
inner join d_zone k on k.zone_id=i.zone_id
inner join d_state u on i.state_id=u.state_id
inner join d_rating o on a.rating_id=o.rating_id
inner join h_lang_translation p on o.equivalence_id=p.equivalence_id  and p.language_id=?
inner join h_assessment_user s on a.assessment_id=s.assessment_id and a.assessor_id=s.user_id and role=4 and s.isFilled =1 and s.percComplete=100.00
left join d_rating q on q.rating_id=a.level2rating
left join h_lang_translation r on r.equivalence_id=q.equivalence_id and r.language_id=?
where isFinal=1 and f.isAssessmentActive=1 and f.aqs_round = ? and m.kpa_id=? and j.province_id=?;";
    	$data = $this->db->get_results($sql,[$languageId,$languageId,$languageId,$languageId,$request['round'],$request['kpa_id'],$clsId[0]['province_id']]);
        return $data;
    }
    
    function getClusterId($clusterName){
        $sql="select province_id from d_province where province_name=?";
        $data = $this->db->get_results($sql,[$clusterName]);
        return $data;
    }
    
        function getClusterListBlock($user_id,$blockId){
        $sql = "SELECT a.*,b.state_name, c.zone_name,d.network_name,e.province_name as cluster_name FROM h_user_admin_levels a left join d_state b on a.state_id = b.state_id join d_zone c on a.zone_id = c.zone_id left join d_network d on d.network_id = a.block_id left join d_province e on e.province_id = a.cluster_id where user_id=? and a.block_id=?";
        $data = $this->db->get_results($sql,[$user_id,$blockId]);
        return $data;
    }
    
    function getBlockListZone($user_id,$zoneId){
        $sql = "SELECT a.*,b.state_name, c.zone_name,d.network_name FROM h_user_admin_levels a left join d_state b on a.state_id = b.state_id join d_zone c on a.zone_id = c.zone_id left join d_network d on d.network_id = a.block_id where user_id=? and a.zone_id=?";
        $data = $this->db->get_results($sql,[$user_id,$zoneId]);
        return $data;
    }
    
 
    
    function getZoneId($zoneName){
        $sql="select * from d_zone where zone_name=?";
        $data = $this->db->get_results($sql,[$zoneName]);
        return $data;
    }
    
    function getRoundState($stateId){
        $sql="select aqs_round from h_client_state a 
inner join d_assessment b on a.client_id = b.client_id
where a.state_id = ?  group by aqs_round ";
        $data = $this->db->get_results($sql,[$stateId]);
        return $data;
    }
    
}