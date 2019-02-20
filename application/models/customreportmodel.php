<?php
/**
 * Reasons: manage cluster and block report for overview report
 * Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 */
class customreportModel extends Model {
    
    //get report type for student 
    function getStuReportType() {
        $res = $this->db->get_results("SELECT report_id,report_name FROM d_reports where assessment_type_id=4 && isIndividualAssessmentReport=0");
        return $res ? $res : array();
    }
    //get data for  cluster report form
    function getClusterReportType($reportName = 'Hub Report', $type = 1, $report_type = 3) {
        //for cluster report Type Cassessment_type_id 8 and report id is 18
        $res = $this->db->get_results("SELECT report_id,report_name FROM d_reports where assessment_type_id=? && isIndividualAssessmentReport=0 and report_id=? and report_name LIKE ?", array($type, $report_type, "%$reportName%"));
        return $res ? $res : array();
    }

    //check duplicate name for reports
    function checkDuplicateReportName($repname) {
        $sql = "SELECT report_name from h_network_report where report_name=?";
        $res = $this->db->get_row($sql, array($repname));
        return $res ? true : false;
    }
    //save cluster report for overall report page
    function saveClusterReport($report_id, $report_name, $filter_id, $review_experience, $include_self_review = 0, $num_days_process, $is_validated = 0, $province_id = 0, $round = 1, $state = 0, $zone = 0, $block = 0) {
        if ($this->db->insert('h_network_report', array("report_id" => $report_id, "report_name" => $report_name, "filter_id" => $filter_id, "review_experience" => $review_experience, "include_self_review" => $include_self_review, "num_days_process" => $num_days_process, "is_validated" => $is_validated, "province_id" => $province_id, "round" => $round, 'state' => $state, 'zone' => $zone, 'network' => $block)))
            return $this->db->get_last_insert_id();
        return false;
    }
    //save cluster report for overall report page of clients
    function saveClusterReportClients($cluster_report_id, $client_id) {
        if ($this->db->insert('h_province_report_clients', array("province_report_id" => $cluster_report_id, "client_id" => $client_id)))
            return true;
        return false;
    }

    //Get all data for report listing on custom report page
    function getNetworkReportsList($args = array()) {
        
        $args = $this->parse_arg($args, array("report_name_like" => "", "max_rows" => 10, "page" => 1, "order_by" => "", "order_type" => ""));
        $order_by = array("create_date" => "create_date", "report_name" => "report_name", "report_type" => "c.assessment_type_id");
        $sqlArgs = array();
        $sql = "SELECT SQL_CALC_FOUND_ROWS a.*,a.network_report_id,a.report_name,c.report_name as report_type_name,a.create_date,group_concat(distinct hnrsc.client_id) client_id,group_concat( distinct a.province_id) province_id,b.network_id,b.round_id,d.assessment_type_id,d.assessment_type_name,e.group_assessment_id,g.diagnostic_id from h_network_report a 
                        left join h_network_report_student b on a.network_report_id=b.network_report_id
                        left join h_network_report_student_province hnrsp on hnrsp.h_network_report_student_id=b.h_network_report_student_id
                        left join h_network_report_student_client hnrsc on hnrsc.h_network_report_student_id=b.h_network_report_student_id
                        left join d_reports c on a.report_id=c.report_id
                        left join d_assessment_type d on c.assessment_type_id=d.assessment_type_id
                        left join d_group_assessment e on e.assessment_type_id=d.assessment_type_id && e.client_id=b.client_id && e.student_round=b.round_id
                        left join (select * from h_assessment_ass_group group by group_assessment_id) f on e.group_assessment_id=f.group_assessment_id
                        left join d_assessment g on f.assessment_id=g.assessment_id
                       
                        where 1 = 1 ";

        if ($args['report_name_like'] != "") {
            $sql .= "and a.report_name like ? ";
            $sqlArgs[] = "%" . $args['report_name_like'] . "%";
        }

        if ($args['assessment_type_id'] != "0" && $args['assessment_type_id'] != "") {
            $sql .= "and d.assessment_type_id=?";
            $sqlArgs[] = "" . $args['assessment_type_id'] . "";
        }

        if ($args['report_id'] != "0" && $args['report_id'] != "") {
            $sql .= "and a.report_id=?";
            $sqlArgs[] = "" . $args['report_id'] . "";
        }

        if ($args['network_id'] != "0" && $args['network_id'] != "") {
            $sql .= "and b.network_id=?";
            $sqlArgs[] = "" . $args['network_id'] . "";
        }

        if ($args['province_id'] != "0" && $args['province_id'] != "") {
            $sql .= " && FIND_IN_SET (" . $args['province_id'] . ",hnrsp.province_id)";
        }

        if ($args['client_id'] != "0" && $args['client_id'] != "") {
            $sql .= " && FIND_IN_SET (" . $args['client_id'] . ",hnrsc.client_id)";
        }

        if ($args['round_id'] != "0" && $args['round_id'] != "") {
            $sql .= "and b.round_id=?";
            $sqlArgs[] = "" . $args['round_id'] . "";
        }

        $sql .= " group by a.network_report_id,b.h_network_report_student_id ";

        $sql .= " order by " . (isset($order_by[$args["order_by"]]) ? $order_by[$args["order_by"]] : "create_date") . ($args["order_type"] == "desc" ? " desc " : " asc ") . $this->limit_query($args['max_rows'], $args['page']);
        $res = $this->db->get_results($sql, $sqlArgs);
        $this->setPageCount($args['max_rows']);
        return $res;
    }

    function getIcon($rating) {
        if ($rating == 5)
            return '!';
        if ($rating == 6)
            return '?';
        if ($rating == 7)
            return '<img width="15" height="15" src="' . SITEURL . 'public/images/tick.png" />';
        if ($rating == 8)
            return '<img width="15" height="15" src="' . SITEURL . 'public/images/tick.png" />';
    }
   
    //load address details for aqs report
    function getAddressDetails($client_id) {

        $sql = "select s.state_id,s.state_name,cz.zone_id,z.zone_name,n.network_name as block,p.province_name as cluster from h_client_state cs 
                    inner join d_state s on cs.state_id=s.state_id
                    inner join h_client_zone cz on cz.client_id=cs.client_id
                    inner join d_zone z on cz.zone_id=z.zone_id
                    inner join h_client_network cn on cn.client_id=cs.client_id
                    inner join d_network n on cn.network_id=n.network_id
                    inner join h_client_province cp on cp.client_id=cs.client_id
                    inner join d_province p on cp.province_id=p.province_id 
                    WHERE cs.client_id=?";
        $results = $this->db->get_row($sql, array($client_id));
        return $results ? $results : array();
    }
    //load js with levels data for aqs report
    function loadJudgementalStatementsWithLevels($is7thKpaReport = false, $lang_id = DEFAULT_LANGUAGE, $round = 1, $assessment_id = 0) {
        $whrCond = 'a.assessment_id=? and';
        $params = array($lang_id, $assessment_id);
        $params[] = $lang_id;
        $sql = "
				select count(if(hls.rating_level_order = 3,hls.rating_level_order,null)) as level1,
                                count(if(hls.rating_level_order = 2,hls.rating_level_order,null)) as level2,
                                count(if(hls.rating_level_order = 1,hls.rating_level_order,null)) as level3,
                                count(if(a.level2rating = 3,a.level2rating,null)) as level_2_1,
                                count(if(a.level2rating = 2,a.level2rating,null)) as level_2_2,
                                count(if(a.level2rating = 1,a.level2rating,null)) as level_2_3,
                                group_concat(hls.rating_level_order),group_concat(a.level2rating),i.kpa_instance_id,a.assessment_id,c.core_question_instance_id,
                                c.judgement_statement_instance_id,
                                hlt.translation_text as judgement_statement_text,role,r.rating,hls.rating_level_order as 
                                numericRating from f_score a 
                                inner join h_assessment_user b on a.assessor_id = b.user_id 
                                and a.assessment_id = b.assessment_id 
                                inner join h_cq_js_instance c on
                                 a.judgement_statement_instance_id = c.judgement_statement_instance_id 
                                 inner join d_judgement_statement d 
                                 on d.judgement_statement_id = c.judgement_statement_id 
                                 inner join d_assessment g on a.assessment_id = g.assessment_id 
                                 inner join h_lang_translation hlt on d.equivalence_id = hlt.equivalence_id 
                                 inner join h_kpa_diagnostic h on h.diagnostic_id = g.diagnostic_id and h.kpa_order <=7 
                                 inner join h_kpa_kq i on h.kpa_instance_id = i.kpa_instance_id 
                                 inner join h_kq_cq j on i.key_question_instance_id = j.key_question_instance_id 
                                 and j.core_question_instance_id = c.core_question_instance_id 
                                 inner join h_diagnostic_rating_level_scheme rls on rls.diagnostic_id = g.diagnostic_id
                                 inner join h_rating_level_scheme hls on hls.rating_scheme_id =rls.rating_level_scheme_id 
                                 and a.rating_id=hls.rating_id and hls.rating_level_id=4 
                                 inner join d_rating_level rl on rl.rating_level_id = hls.rating_level_id 
                                 inner join (select hlt.translation_text as rating,rt.rating_id from d_rating rt 
                                 INNER JOIN h_lang_translation hlt on rt.equivalence_id = hlt.equivalence_id 
                                 where hlt.language_id=? ) r on a.rating_id = r.rating_id and hls.rating_id=r.rating_id 
                                 where a.isFinal = 1 and role=4 and $whrCond hlt.language_id=?
					 group by i.kpa_instance_id order by i.key_question_instance_id asc ;";
        if ($round == 2) {

            return $this->getTwoRoundsArray($this->db->get_results($sql, $params), "judgement_statement_instance_id", "core_question_instance_id");
        } else {
            return $this->get_section_Array($this->db->get_results($sql, $params), "kpa_instance_id");
        }
    }
    
    //load all kpas data
    function loadKpas($is7thKpaReport = false, $lang_id = DEFAULT_LANGUAGE, $assessmentId, $lang2Id = '') {
        $langCond = 'hlt.language_id IN (?';
        $params = array($lang_id, $assessmentId, $lang_id);
        $fieldCond = 'hlt.translation_text KPA_name,';
        $groupBy = '';
        $whrCond = '';
        if (!empty($lang2Id)) {
            $langCond .= ',?';
            $params[] = $lang2Id;
            $fieldCond = "GROUP_CONCAT(hlt.translation_text SEPARATOR '-#') as KPA_name,";
            array_unshift($params, $lang2Id);
            $groupBy = 'group by a.kpa_instance_id';
            $whrCond = 'and f.role=4';
        }
        $langCond .= ')';
        $sql = "select a.kpa_instance_id,$fieldCond r.rating,role,hls.rating_level_order as numericRating
					from h_kpa_instance_score a
					inner join h_kpa_diagnostic c on a.kpa_instance_id = c.kpa_instance_id 
					inner join d_kpa d on d.kpa_id = c.kpa_id
                                        inner join h_lang_translation hlt on d.equivalence_id = hlt.equivalence_id 
					inner join h_assessment_user f on a.assessor_id = f.user_id and a.assessment_id = f.assessment_id $whrCond 
					inner join d_assessment g on a.assessment_id = g.assessment_id
					inner join h_diagnostic_rating_level_scheme rls on rls.diagnostic_id = g.diagnostic_id
					inner join h_rating_level_scheme hls on hls.rating_scheme_id =rls.rating_level_scheme_id and a.d_rating_rating_id=hls.rating_id and hls.rating_level_id=1
				        inner join d_rating_level rl on rl.rating_level_id = hls.rating_level_id 
					inner join (select hlt.translation_text as rating,rt.rating_id from d_rating rt INNER JOIN h_lang_translation hlt on rt.equivalence_id = hlt.equivalence_id where $langCond) r on a.d_rating_rating_id = r.rating_id and hls.rating_id=r.rating_id
					where a.assessment_id = ? and $langCond  $groupBy
					order by c.`kpa_order` asc;";
        return $this->get_section_Array($this->db->get_results($sql, $params), "kpa_instance_id");
    }

    // get all cluster data
    function getClustersData($network_id) {

        $sql = "SELECT DISTINCT hp.client_id, cl.client_name,c.cluster_id,p.province_name FROM d_network ch
              INNER JOIN h_cluster_block_zone_state c ON c.block_id=ch.network_id
              INNER JOIN d_province p ON p.province_id=c.cluster_id
              INNER JOIN h_client_province hp ON hp.province_id=p.province_id
               INNER JOIN d_client cl ON  cl.client_id=hp.client_id
               WHERE c.block_id=?";
        return $this->db->get_results($sql, array($network_id));
    }
    
    //get all schools in block
    function getTotalSchoolsWithAssessment($network_id) {

        $sql = "SELECT cl.client_name,c.cluster_id,p.province_name,hp.client_id FROM d_network ch"
                . " INNER JOIN h_cluster_block_zone_state c ON c.block_id=ch.network_id"
                . " INNER JOIN d_province p ON p.province_id=c.cluster_id"
                . " INNER JOIN h_client_province hp ON hp.province_id=p.province_id"
                . " INNER JOIN d_client cl ON  cl.client_id=hp.client_id"
                . " INNER JOIN d_assessment d ON  d.client_id=cl.client_id"
                . " INNER JOIN h_assessment_user u ON  u.assessment_id=d.assessment_id and u.role=4 and u.isFilled=1 and d.assessment_id not in (30,357)"
                . " WHERE c.block_id=? group by hp.client_id";
        return $this->db->get_results($sql, array($network_id));
    }
     // get rating of block
    function getRatingData($network_id, $grpBy = 'province_name', $lang_id = DEFAULT_LANGUAGE) {

        $sql = "select  count(j.translation_text) as sum_rating,group_concat(a.level2rating) as kpa1level2,group_concat(distinct(d.client_id)) as schoolIds,
                         network_name,province_name,c.client_id,client_name,a.assessment_id,o.translation_text as kpa,m.kpa_instance_id,f.judgement_statement_instance_id,h.translation_text as js,j.translation_text as js_rating,a.rating_id,isFinal,kpa_order,kq_order,cq_order,js_order
                        from f_score a
                        inner join h_assessment_user b on a.assessment_id=b.assessment_id and a.assessor_id=b.user_id and role=4
                        inner join d_assessment c on a.assessment_id=c.assessment_id
                        inner join d_client d on c.client_id=d.client_id
                        inner join h_client_province e on d.client_id=e.client_id
                        inner join h_cq_js_instance f on a.judgement_statement_instance_id=f.judgement_statement_instance_id
                        inner join d_judgement_statement g on f.judgement_statement_id=g.judgement_statement_id
                        inner join h_lang_translation h on g.equivalence_id=h.equivalence_id and h.language_id=?
                        inner join d_rating i on a.rating_id=i.rating_id
                        inner join h_lang_translation j on i.equivalence_id=j.equivalence_id and j.language_id=?
                        inner join h_kq_cq k on f.core_question_instance_id=k.core_question_instance_id
                        inner join h_kpa_kq l on k.key_question_instance_id=l.key_question_instance_id
                        inner join h_kpa_diagnostic m on m.kpa_instance_id=l.kpa_instance_id
                        inner join d_kpa n on n.kpa_id=m.kpa_id
                        inner join h_lang_translation o on o.equivalence_id=n.equivalence_id and o.language_id=?
                        inner join d_rating i2 on a.level2rating=i2.rating_id
                        inner join h_lang_translation j2 on i2.equivalence_id=j2.equivalence_id and j2.language_id=?
                        inner join h_cluster_block_zone_state cl on e.province_id=cl.cluster_id
                        inner join d_network nw on cl.block_id=nw.network_id
                        inner join d_province po on po.province_id=cl.cluster_id
                        where 
                         
                          nw.network_id in (?) and a.assessment_id not in (30,357)
                         and isFinal=1  and isFilled=1  
                          group by $grpBy,kpa,js,j.translation_text 
                         order by kpa_order,kq_order,cq_order,js_order;
                        ";
        return $this->db->get_results($sql, array($lang_id, $lang_id, $lang_id, $lang_id, $network_id));
    }

    //get cluster data for generating cluster report
    function getClusterReportData($state_id, $zone_id, $network_id, $cluster_id, $schools, $round, $is7thKpaReport = false, $lang_id = DEFAULT_LANGUAGE) {

        if (gettype($schools) == 'array')
            $whcond = " c.client_id in (" . implode(",", $schools) . ") ";
        else
            $whcond = " c.client_id = " . $schools . " ";

        $sql = "SELECT a.assessment_id,a.assessor_id,cjs.core_question_instance_id,cjs.judgement_statement_instance_id,hlt.translation_text AS judgement_statement_text,role,r.rating,hls.rating_level_order AS numericRating 
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
                    INNER JOIN f_score a
                            ON a.assessment_id = g.assessment_id
                    INNER JOIN h_assessment_user b
                            ON a.assessor_id = b.user_id AND a.assessment_id = b.assessment_id
                    INNER JOIN h_cq_js_instance cjs
                            ON a.judgement_statement_instance_id = cjs.judgement_statement_instance_id
                    INNER JOIN d_judgement_statement d
                            ON d.judgement_statement_id = cjs.judgement_statement_id
                    INNER JOIN h_lang_translation hlt
                            ON d.equivalence_id = hlt.equivalence_id
                    INNER JOIN h_kpa_diagnostic h
                            ON h.diagnostic_id = g.diagnostic_id and h.kpa_order " . ($is7thKpaReport ? "=" : "<") . "7
                    INNER JOIN h_kpa_kq i
                            ON h.kpa_instance_id = i.kpa_instance_id
                    INNER JOIN h_kq_cq j
                            ON i.key_question_instance_id = j.key_question_instance_id AND j.core_question_instance_id =cjs.core_question_instance_id
                    INNER JOIN h_diagnostic_rating_level_scheme rls
                            ON rls.diagnostic_id = g.diagnostic_id
                    INNER JOIN h_rating_level_scheme hls
                            ON hls.rating_scheme_id = rls.rating_level_scheme_id AND a.rating_id = hls.rating_id AND hls.rating_level_id = 4
                    INNER JOIN d_rating_level rl
                            ON rl.rating_level_id = hls.rating_level_id
                    INNER JOIN (SELECT hlt.translation_text AS rating,rt.rating_id FROM d_rating rt INNER JOIN h_lang_translation hlt ON rt.equivalence_id                    = hlt.equivalence_id WHERE  hlt.language_id = ?) r
                            ON a.rating_id = r.rating_id AND hls.rating_id = r.rating_id
                    WHERE  a.isfinal = 1 AND b.role=4 AND b.isFilled=1 AND hlt.language_id =? and s.state_id=? and z.zone_id=? and n.network_id=? and p.province_id=? and $whcond and g.aqs_round=? group by g.assessment_id";
        $results = $this->db->get_results($sql, array($lang_id, $lang_id, $state_id, $zone_id, $network_id, $cluster_id, $round));
        return $results ? $results : array();
    }

    //get block data for generating b block report
    function getBlockReportData($state_id, $zone_id, $network_id, $cluster_id, $schools, $round, $is7thKpaReport = false, $lang_id = DEFAULT_LANGUAGE) {

        if (gettype($schools) == 'array')
            $whcond = " c.client_id in (" . implode(",", $schools) . ") ";
        else
            $whcond = " c.client_id = " . $schools . " ";

        $sql = "SELECT g.client_id,a.assessment_id,a.assessor_id 
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
                    INNER JOIN f_score a
                            ON a.assessment_id = g.assessment_id
                    INNER JOIN h_assessment_user b
                            ON a.assessor_id = b.user_id AND a.assessment_id = b.assessment_id
                   
                    WHERE  a.isfinal = 1 AND b.role=4 AND b.isFilled=1 and s.state_id=? and z.zone_id=? and n.network_id=? and p.province_id=? and $whcond and g.aqs_round=? group by g.assessment_id";
        $results = $this->db->get_results($sql, array($state_id, $zone_id, $network_id, $cluster_id, $round));
        return $results ? $results : array();
    }

    // function copy start
    function loadCoreQuestions($is7thKpaReport = false, $lang_id = DEFAULT_LANGUAGE, $round = 1, $assessmentId) {
        $whrCond = 'a.assessment_id=? and';
        $params = array($lang_id, $assessmentId);
        $params[] = $lang_id;
        $sql = "select a.assessment_id ,c.key_question_instance_id,a.core_question_instance_id,hlt.translation_text as core_question_text,r.rating,role,hls.rating_level_order as numericRating
					 from h_cq_score a
					 inner join h_kq_cq c on a.core_question_instance_id = c.core_question_instance_id
					 inner join d_core_question d on d.core_question_id = c.core_question_id
                                         inner join h_lang_translation hlt on d.equivalence_id = hlt.equivalence_id 
					 inner join h_assessment_user f on a.assessor_id = f.user_id and a.assessment_id = f.assessment_id 
					 inner join d_assessment g on a.assessment_id = g.assessment_id
					 inner join h_kpa_diagnostic i on i.diagnostic_id = g.diagnostic_id 
					 inner join h_kpa_kq j on i.kpa_instance_id = j.kpa_instance_id and c.key_question_instance_id = j.key_question_instance_id
					 inner join h_diagnostic_rating_level_scheme rls on rls.diagnostic_id = g.diagnostic_id
					 inner join h_rating_level_scheme hls on hls.rating_scheme_id =rls.rating_level_scheme_id and a.d_rating_rating_id=hls.rating_id and hls.rating_level_id=3
                                        inner join d_rating_level rl on rl.rating_level_id = hls.rating_level_id 
					 inner join (select hlt.translation_text as rating,rt.rating_id from d_rating rt INNER JOIN h_lang_translation hlt on rt.equivalence_id = hlt.equivalence_id where hlt.language_id=? ) r on a.d_rating_rating_id = r.rating_id and hls.rating_id=r.rating_id		
					 where $whrCond hlt.language_id=?
					 order by c.`cq_order` asc;";
        if ($round == 2) {
            return $this->getTwoRoundsArray($this->db->get_results($sql, $params), "core_question_instance_id", "key_question_instance_id");
        } else {
            return $this->get_section_Array($this->db->get_results($sql, array($lang_id, $assessmentId, $lang_id)), "core_question_instance_id", "key_question_instance_id");
        }
    }

    //load qey question data for aqs 
    function loadKeyQuestions($is7thKpaReport = false, $lang_id = DEFAULT_LANGUAGE, $round = 1, $assessmentId) {
        $whrCond = 'g.assessment_id=? and';
        $params = array($lang_id, $assessmentId);
        $params[] = $lang_id;
        $sql = "select a.assessment_id,c.kpa_instance_id,a.key_question_instance_id,hlt.translation_text as key_question_text,r.rating,role,hls.rating_level_order as numericRating
					from h_kq_instance_score a
					inner join h_kpa_kq c on a.key_question_instance_id = c.key_question_instance_id
					inner join d_key_question d on d.key_question_id = c.key_question_id	
                                        inner join h_lang_translation hlt on d.equivalence_id = hlt.equivalence_id 
					inner join h_assessment_user f on a.assessor_id = f.user_id and a.assessment_id = f.assessment_id 
					inner join d_assessment g on a.assessment_id = g.assessment_id
					inner join h_kpa_diagnostic i on i.diagnostic_id = g.diagnostic_id and i.kpa_instance_id=c.kpa_instance_id 
					inner join h_diagnostic_rating_level_scheme rls on rls.diagnostic_id = g.diagnostic_id
					inner join h_rating_level_scheme hls on hls.rating_scheme_id =rls.rating_level_scheme_id and a.d_rating_rating_id=hls.rating_id and hls.rating_level_id=2
				    inner join d_rating_level rl on rl.rating_level_id = hls.rating_level_id 
					inner join (select hlt.translation_text as rating,rt.rating_id from d_rating rt INNER JOIN h_lang_translation hlt on rt.equivalence_id = hlt.equivalence_id where hlt.language_id=?) r on a.d_rating_rating_id = r.rating_id and hls.rating_id=r.rating_id	
					where $whrCond hlt.language_id=?
					order by c.`kq_order` asc;";
        if ($round == 2) {
            return $this->getTwoRoundsArray($this->db->get_results($sql, $params), "key_question_instance_id", "kpa_instance_id");
        } else {
            return $this->get_section_Array($this->db->get_results($sql, array($lang_id, $assessmentId, $lang_id)), "key_question_instance_id", "kpa_instance_id");
        }
    }
    
    //load aqs data for aqs report
    function loadAqsData($assessmentId, $reportId = 1) {
        $sql = "
				select a.school_aqs_pref_start_date as sdate,a.school_aqs_pref_end_date as edate,date(valid_until) as valid_until,dc.client_name as school_name,du.name as principal_name,CONCAT(COALESCE(`street`,''),' ',COALESCE(`addressLine2`,''),', ',COALESCE(cty.city_name,''),', ',COALESCE(st.state_name,'')) as school_address,ctr.country_name,st.state_name,cty.city_name,DATE(b.create_date) as create_date,b.award_scheme_id,date(c.publishDate) as publishDate,date(valid_until) as valid_until,b.tier_id,b.client_id,b.review_criteria
				from d_AQS_data a
				inner join d_assessment b on a.id = b.aqsdata_id
				inner join d_client dc on dc.client_id = b.client_id
				left join d_countries ctr on ctr.country_id = dc.country_id
				left join d_states st on dc.state_id = st.state_id and st.country_id = dc.country_id
				left join d_cities cty on cty.city_id = dc.city_id and cty.state_id = dc.state_id
				left join h_assessment_report c on b.assessment_id = c.assessment_id and c.report_id= ? 
                                left join d_user du on dc.client_id = du.client_id    
                                left join h_user_user_role dur on du.user_id = dur.user_id && dur.role_id=6
				where  dur.role_id=6 && b.assessment_id = ?  
				group by b.assessment_id;";
        return $this->db->get_row($sql, array($reportId, $assessmentId));
        //}
    }

    //load js data for aqs report
    function loadJudgementalStatements($assessment_id = 0, $assessor_id, $lang_id = DEFAULT_LANGUAGE, $lang_id2 = 0) {
        $langCond = 'hlt.language_id IN (?';
        $params = array($lang_id, $assessment_id, $assessor_id, $lang_id);
        $fieldCond = 'hlt.translation_text judgement_statement_text,';
        if (!empty($lang_id2)) {
            $langCond .= ',?';
            $params[] = $lang_id2;
            $fieldCond .= "GROUP_CONCAT(hlt.translation_text SEPARATOR '-#') as judgement_statement_text,";
            array_unshift($params, $lang_id2);
        }
        $langCond .= ')';
        $sql = "SELECT role,fs.level2rating, if(fs.score_id is NULL,cqjs.judgement_statement_instance_id,fs.score_id)"
                . " as groupId, GROUP_CONCAT( CONCAT(f.file_id,'|',f.file_name) SEPARATOR '||') as files,"
                . " fs.score_id,fs.level2rating,js.judgement_statement_id,$fieldCond"
                . "fs.evidence_text,fs.evidence_text_lw,fs.evidence_text_co,fs.evidence_text_in,evidence_text_bl, "
                . "cqjs.judgement_statement_instance_id,cqjs.core_question_instance_id,r.rating,hls.rating_level_order "
                . "as numericRating,rls.rating_level_scheme_id as scheme_id FROM `d_judgement_statement` js "
                . "inner join h_cq_js_instance cqjs on js.judgement_statement_id=cqjs.judgement_statement_id "
                . "inner join h_kq_cq kqcq on kqcq.core_question_instance_id=cqjs.core_question_instance_id "
                . "inner join h_kpa_kq kkq on kkq.key_question_instance_id=kqcq.key_question_instance_id "
                . "inner join h_kpa_diagnostic kd on kd.kpa_instance_id=kkq.kpa_instance_id "
                . "inner join d_assessment a on kd.diagnostic_id=a.diagnostic_id "
                . "inner join h_assessment_user au on au.assessment_id=a.assessment_id "
                . "inner join h_lang_translation hlt on js.equivalence_id = hlt.equivalence_id "
                . "left join `f_score` fs on cqjs.judgement_statement_instance_id=fs.judgement_statement_instance_id "
                . "and a.assessment_id=fs.assessment_id and fs.assessor_id=au.user_id and isFinal=1 "
                . "left join h_diagnostic_rating_level_scheme rls on rls.diagnostic_id = a.diagnostic_id "
                . "left join h_rating_level_scheme hls on hls.rating_scheme_id =rls.rating_level_scheme_id "
                . "and fs.rating_id=hls.rating_id and hls.rating_level_id=4 left join d_rating_level rl "
                . "on rl.rating_level_id = hls.rating_level_id left join "
                . "(select hlt.translation_text as rating,r.rating_id from h_lang_translation hlt "
                . "INNER JOIN d_rating r on r.equivalence_id = hlt.equivalence_id WHERE $langCond) r "
                . "on fs.rating_id = r.rating_id and hls.rating_id=r.rating_id left join h_score_file sf "
                . "on sf.score_id=fs.score_id left join d_file f on sf.file_id=f.file_id where a.assessment_id=? "
                . "and au.user_id=? and hlt.translation_type_id=4 and $langCond group by groupId "
                . "order by cqjs.`js_order` asc ";
        return $this->db->get_results($sql, $params);
    }

    protected function get_section_Array($arr, $instanceIdKey, $groupingIdKey = "") {

        $res = array();
        if (count($arr)) {
            if ($groupingIdKey == "") {
                foreach ($arr as $v) {
                    if (isset($res[$v[$instanceIdKey]])) {
                        $res[$v[$instanceIdKey]][$v['role'] == 3 ? 'internalRating' : 'externalRating'] = array("rating" => $v['rating'], "score" => $v['numericRating']);
                    } else {
                        $v[$v['role'] == 3 ? 'internalRating' : 'externalRating'] = array("rating" => $v['rating'], "score" => $v['numericRating']);
                        unset($v['numericRating']);
                        unset($v['rating']);
                        unset($v['role']);
                        $res[$v[$instanceIdKey]] = $v;
                    }
                }
            } else {
                foreach ($arr as $v) {
                    if (isset($res[$v[$groupingIdKey]]) && isset($res[$v[$groupingIdKey]][$v[$instanceIdKey]])) {
                        $res[$v[$groupingIdKey]][$v[$instanceIdKey]][$v['role'] == 3 ? 'internalRating' : 'externalRating'] = array("rating" => $v['rating'], "score" => $v['numericRating']);
                    } else {
                        $v[$v['role'] == 3 ? 'internalRating' : 'externalRating'] = array("rating" => $v['rating'], "score" => $v['numericRating']);
                        unset($v['numericRating']);
                        unset($v['rating']);
                        unset($v['role']);
                        $res[$v[$groupingIdKey]][$v[$instanceIdKey]] = $v;
                    }
                }
            }
        }
        return $res;
    }

    function getClientName($client_id) {
        $sql = "SELECT client_name from d_client where client_id=?";
        $res = $this->db->get_results($sql, array($client_id));
        return $res ? $res : array();
    }

    function getZoneName($zone_id) {
        $sql = "SELECT zone_name from d_zone where zone_id=?";
        $res = $this->db->get_results($sql, array($zone_id));
        return $res ? $res : array();
    }
    function getBlockName($block_id) {
        $sql = "SELECT network_name from d_network where network_id=?";
        $res = $this->db->get_results($sql, array($block_id));
        return $res ? $res : array();
    }
    function getClusterName($cluster_id) {
        $sql = "SELECT province_name from d_province where province_id=?";
        $res = $this->db->get_results($sql, array($cluster_id));
        return $res ? $res : array();
    }
    //function copy ends
    function getClsReportDetail($province_report_id) {
        $sql = "SELECT hcbzs.*,hprc.province_report_id,hprc.client_id
                    FROM h_network_report hnr
                    INNER JOIN h_province_report_clients hprc
                            ON hprc.province_report_id = hnr.network_report_id
                    INNER JOIN h_cluster_block_zone_state hcbzs
                            ON hcbzs.cluster_id = hnr.province_id
                    WHERE  hnr.network_report_id=?";
        $results = $this->db->get_results($sql, array($province_report_id));
        return $results ? $results : array();
    }

}
