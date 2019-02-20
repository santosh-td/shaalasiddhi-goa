<?php

/**
 * Reasons: Manage data for diagnostic manage
 * Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 */
class diagnosticModel extends Model {

    /** get all diagnostic
     * @params: $args ,$lang_id,$isDiagnosticParent
     */
    function getDiagnostics($args = array(), $lang_id = 0, $isDiagnosticParent = 0) {
        $args = $this->parse_arg($args, array("assessment_type_id" => 0, "name_like" => "", "isPublished" => "", "order_by" => "name", "order_type" => "asc"));
        $order_by = array("name" => "hlt.translation_text", "isPublished" => "d.isPublished", "date_created" => "d.date_created", "date_published" => "d.date_published", "assessment_type" => "t.assessment_type_name", "language_id" => "dl.language_id");
        $sqlArgs = array();
        $lang = '';
        $whrCond = '';
        $langCond = '';
        if (!empty($lang_id)) {

            $lang = $lang_id;
        } else {
            $lang = $this->lang;
        }
        if ($lang != 'all') {
            $langCond = "  and hlt.language_id=$lang ";
        } else if ($isDiagnosticParent) {
            $whrCond .= " and parent_lang_translation_id is null";
        }

        $sql = "select d.diagnostic_type,d.diagnostic_id,hlt.translation_text as name,d.diagnostic_image_id,hltd.isPublished,hltd.date_created,hltd.date_published,hltd.publish_user_id,d.assessment_type_id,d.kpa_recommendations,d.kq_recommendations,d.cq_recommendations,d.js_recommendations,t.assessment_type_name ,hlt.language_id,dl.language_name
			from d_diagnostic d 
			inner join d_assessment_type t on d.assessment_type_id=t.assessment_type_id
			inner join h_lang_translation hlt on d.equivalence_id = hlt.equivalence_id
                        inner join h_lang_trans_diagnostics_details hltd on  hltd.lang_translation_id= hlt.lang_translation_id                      
			inner join d_language dl on dl.language_id=hlt.language_id
                        where 1=1  $whrCond  $langCond and hlt.isActive=1 && hlt.translation_type_id=7 ";

        if ($args['assessment_type_id'] > 0) {
            $sql .= "and d.assessment_type_id=? ";
            $sqlArgs[] = $args['assessment_type_id'];
        }
        if ($args['name_like'] != "") {
            $sql .= "and hlt.translation_text like ? ";
            $sqlArgs[] = "%" . $args['name_like'] . "%";
        }
        if ($args['isPublished'] != "") {

            $sql .= "and hltd.isPublished=" . ($args['isPublished'] == "yes" ? "1 " : "0 ");
        }
        $sql .= "order by " . (isset($order_by[$args["order_by"]]) ? $order_by[$args["order_by"]] : "hlt.translation_text") . ($args["order_type"] == "desc" ? " desc " : " asc ");

        $res = $this->db->get_results($sql, $sqlArgs);
        return $res ? $res : array();
    }

    /** get all diagnostic for language
     * @params: $diagnostic_id ,$lang_id
     */
    function getDiagnosticBYLang($diagnostic_id = 0, $lang_id = 9) {
        $sql = "select d.diagnostic_id,d.equivalence_id,hlt.lang_translation_id,hlt.translation_text as name,d.diagnostic_image_id,hltd.isPublished,hltd.date_created,hltd.date_published,hltd.publish_user_id,d.assessment_type_id,d.kpa_recommendations,d.kq_recommendations,d.cq_recommendations,d.js_recommendations,t.assessment_type_name,d.assessment_type_id ,hlt.parent_lang_translation_id,dl.language_name 
			from d_diagnostic d 
			inner join d_assessment_type t on d.assessment_type_id=t.assessment_type_id
			inner join h_lang_translation hlt on d.equivalence_id = hlt.equivalence_id 
                        inner join h_lang_trans_diagnostics_details hltd on  hltd.lang_translation_id= hlt.lang_translation_id
                        left join d_language dl on dl.language_id=hlt.language_id
			where diagnostic_id=? and hlt.translation_type_id=7 and hlt.language_id=? ";
        return $this->db->get_row($sql, array($diagnostic_id, $lang_id));
    }

    /** get  diagnostic by id
     * @params: $diagnostic_id ,$lang_id
     */
    function getDiagnosticById($diagnostic_id = 0) {
        $sql = "select d.diagnostic_id,d.equivalence_id,hlt.lang_translation_id,hlt.translation_text as name,d.diagnostic_image_id,hltd.isPublished,hltd.date_created,hltd.date_published,hltd.publish_user_id,d.assessment_type_id,d.kpa_recommendations,d.kq_recommendations,d.cq_recommendations,d.js_recommendations,t.assessment_type_name,d.assessment_type_id 
			from d_diagnostic d 
			inner join d_assessment_type t on d.assessment_type_id=t.assessment_type_id
			left join h_lang_translation hlt on d.equivalence_id = hlt.equivalence_id 
                        inner join h_lang_trans_diagnostics_details hltd on  hltd.lang_translation_id= hlt.lang_translation_id
			where diagnostic_id=?";
        return $this->db->get_row($sql, array($diagnostic_id));
    }

    /** get  language by id
     * @params: null
     */
    function getLanguageId() {

        return $this->lang;
    }

    /** get  diagnostic's language for id
     * @params: $diagnostic_id
     */
    function getDiagnosticLanguages($diagnostic_id = 0) {
        $sql = "select d.diagnostic_type,d.diagnostic_id,hlt.language_id,lan.language_words ,lan.language_code
			from d_diagnostic d 
			inner join d_assessment_type t on d.assessment_type_id=t.assessment_type_id
			inner join h_lang_translation hlt on d.equivalence_id = hlt.equivalence_id 		
			inner join d_language lan on lan.language_id = hlt.language_id 	
                        inner join h_lang_trans_diagnostics_details hltd on  hltd.lang_translation_id= hlt.lang_translation_id 
			where diagnostic_id=? AND  hltd.isPublished = 1 ";
        return $this->db->get_results($sql, array($diagnostic_id));
    }

    /** get  diagnostic's labels for id
     * @params: $diagnostic_id
     */
    function getDiagnosticLabels($diagnostic_id = 0, $language_id = 0) {

        $sql = "select d.label_name,d.label_key,a.label_text
			from d_assessment_labels d 
			inner join h_assessment_labels a on d.id=a.label_id				
			where a.language_id=?  ";
        return $this->db->get_results($sql, array($language_id));
    }

    /** get  language by id
     * @params: $lang_translation_id,$lang_code
     */
    function getLanguages($lang_translation_id, $lang_code) {
        $len = count($lang_code);
        $lang_code[] = $lang_translation_id;
        $sql = "select * from d_language
			where (language_code in (?" . str_repeat(",?", $len - 1) . ")) && (language_id NOT IN (SELECT language_id from h_lang_translation where equivalence_id IN (select equivalence_id from h_lang_translation where lang_translation_id=?)))";

        $res = $this->db->get_results($sql, $lang_code);
        return $res ? $res : array();
    }

    /** get assessment details by user id
     * @params: $assessment_id,$user_id,$lang_id
     */
    function getAssessmentByUser($assessment_id, $user_id, $lang_id = DEFAULT_LANGUAGE, $external = 0) {

        $cond = 'b.user_id=?';
        $sql_part = '';
        $columnCond = 'b.role';
        $params = array($user_id, 4, $assessment_id, $lang_id);
        if ($external == 1) {
            $cond .= 'AND b.user_role=?';
            $columnCond = 'b.user_role as role';
            $sql_part .= ' inner join `h_assessment_external_team` b on a.assessment_id=b.assessment_id ';
        } else {
            $sql_part .= ' inner join `h_assessment_user` b on a.assessment_id=b.assessment_id ';
            $columnCond .= ',hau.isLeadSave';
            unset($params[1]);
        }

        $sql = "SELECT d.diagnostic_type,a.iscollebrative,a.assessment_id,a.language_id,d.assessment_type_id,a.client_id,
                            a.diagnostic_id, a.isAssessorKeyNotesApproved,a.is_replicated, c.principal_phone_no,
                            b.percComplete, date(a.create_date) as create_date,date(b.ratingInputDate) as ratingInputDate,
                            hlt.translation_text as diagnostic_name,c.client_name,t.assessment_type_name,b.isFilled as status,b.percComplete,
                            $columnCond,b.user_id, cn.network_id, n.network_name, q.status as aqs_status,r.isPublished report_published,
                            (select group_concat(y.name order by x.role) from h_assessment_user x inner join 
                            d_user y on x.user_id=y.user_id where x.assessment_id=a.assessment_id ) as user_names,
                            a.d_sub_assessment_type_id as 'subAssessmentType',a.is_approved as isApproved,q.school_aqs_pref_start_date as aqs_sdate,q.school_aqs_pref_end_date as aqs_edate,hau.user_id as external
                             FROM `d_assessment` a  ";


        $sql .= $sql_part . "                 
                             inner join `d_client` c on c.client_id=a.client_id
                             inner join `d_diagnostic` d on d.diagnostic_id=a.diagnostic_id
                             inner join `d_assessment_type` t on d.assessment_type_id=t.assessment_type_id
                             inner join h_lang_translation hlt on d.equivalence_id = hlt.equivalence_id 		
                             left join h_client_network cn on cn.client_id=c.client_id
                             left join d_network n on cn.network_id=n.network_id
                             left join d_AQS_data q on q.id=a.aqsdata_id
                             left join h_assessment_report r on r.assessment_id=a.assessment_id and r.report_id=1";
        if ($external == 1)
            $sql .= " left join `h_assessment_external_team` hau on a.assessment_id=hau.assessment_id && hau.user_role=4";
        else
            $sql .= " left join `h_assessment_user` hau on a.assessment_id=hau.assessment_id && hau.role=4";

        $sql .= " where $cond and a.assessment_id=? and hlt.translation_type_id=7 and hlt.language_id=? ";
        $params = (array_values($params));
        $row = $this->db->get_row($sql, $params);
        if ($row) {
            $row['user_names'] = explode(",", $row['user_names']);
        }
        return $row;
    }

    /** get assessment details by role id
     * @params: $assessment_id,$role,$lang_id
     */
    function getAssessmentByRole($assessment_id, $role = 4, $lang_id = DEFAULT_LANGUAGE, $external = 0, $is_collaborative = 0, $assessor_id = 0) {
        $sqlColumn = 'b.role';
        $sqlCond = 'b.role = ?';
        $sql_part = ' inner join `h_assessment_user` b on a.assessment_id=b.assessment_id';
        $params = array($role);
        if ($external && $is_collaborative) {
            $sql_part = ' inner join `h_assessment_external_team` b on a.assessment_id=b.assessment_id';
            $sqlColumn = 'b.user_role ';
            $sqlCond = 'b.user_role = ? AND b.user_id = ? ';
            $params[] = $assessor_id;
        }
        $sql = "SELECT a.assessment_id,a.client_id,a.diagnostic_id, a.isAssessorKeyNotesApproved,a.is_replicated, c.principal_phone_no, b.percComplete, date(a.create_date) as create_date,date(b.ratingInputDate) as ratingInputDate,hlt.translation_text as diagnostic_name,c.client_name,t.assessment_type_name,b.isFilled as status, $sqlColumn as role,b.user_id, q.status as aqs_status,d.assessment_type_id,a.d_sub_assessment_type_id
		FROM `d_assessment` a ";
        $sql .= $sql_part . "  inner join `d_client` c on c.client_id=a.client_id
			inner join `d_diagnostic` d on d.diagnostic_id=a.diagnostic_id
			inner join h_lang_translation hlt on d.equivalence_id = hlt.equivalence_id 		
			inner join `d_assessment_type` t on d.assessment_type_id=t.assessment_type_id
			left join d_AQS_data q on q.id=a.aqsdata_id
			where $sqlCond and a.assessment_id=? and hlt.translation_type_id=7 and hlt.language_id=? ";
        $params = array_merge($params, array($assessment_id, $lang_id));
        return $this->db->get_row($sql, $params);
    }

    /** get assessment team by assessment id
     * @params: $assessment_id
     */
    function getAssessmentTeam($assessment_id) {
        $sql = "SELECT user_id,percComplete FROM h_assessment_external_team WHERE assessment_id = ? "
                . " UNION SELECT user_id,percComplete FROM h_assessment_user WHERE assessment_id = ? AND role = ? ";
        $res = $this->db->get_results($sql, array($assessment_id, $assessment_id, 4));
        return $res ? $res : array();
    }

    /** get assessment kpa by role id
     * @params: $assessment_id,$user_id
     */
    function getAssessmentKpa($assessment_id, $user_id = 0) {
        $sqlCond = '';
        $params = array($assessment_id);
        if (!empty($user_id)) {
            $sqlCond = ' and user_id=?';
            $params[1] = $user_id;
        }
        $sql = "SELECT count(kpa_instance_id) as kpa_num from d_assessment_kpa WHERE assessment_id = ? $sqlCond";
        $res = $this->db->get_row($sql, $params);
        return $res ? $res : array();
    }

    /** get college assessment team by role id
     * @params: $assessment_id
     */
    function getCollAssessmentTeam($assessment_id) {
        $sql = "SELECT t.user_id,t.percComplete FROM h_assessment_external_team t inner join d_assessment_kpa kp on kp.assessment_id= t.assessment_id "
                . " AND t.user_id = kp.user_id WHERE kp.assessment_id = ? "
                . " UNION SELECT u.user_id,u.percComplete FROM h_assessment_user u  inner join d_assessment_kpa kp on kp.assessment_id= u.assessment_id "
                . " AND u.user_id = kp.user_id WHERE u.assessment_id = ? AND u.role = ? ";
        $res = $this->db->get_results($sql, array($assessment_id, $assessment_id, 4));
        return $res ? $res : array();
    }

    function updateAvgAssessmentPercentage($assessment_id, $avgPercntg) {
        $data = array("collaborativepercntg" => $avgPercntg);
        return $this->db->update("d_assessment", $data, array("assessment_id" => $assessment_id));
    }

    /** check Is Lead
     * @params: $user_id
     */
    function checkIsLead($user_id) {
        $sql = "SELECT count(user_id) as lead_id FROM h_assessment_user WHERE user_id = ? ";
        $res = $this->db->get_row($sql, array($user_id));
        return $res['lead_id'] >= 1 ? 1 : 0;
    }

    /** get all judgment score
     * @params: $assessment_id, $assessor_id
     */
    function getAllJudgementScore($assessment_id, $assessor_id) {
        $sql = "SELECT * from `f_score` where assessment_id=? and assessor_id=? and isFinal=1";
        $sqlArgs = array($assessment_id, $assessor_id);
        $res = $this->db->get_results($sql, $sqlArgs);
        return $res ? $res : array();
    }

    /** get all key quesions 
     * @params: $assessment_id, $assessor_id
     */
    function getAllKeyQuestionScore($assessment_id, $assessor_id) {
        $sql = "SELECT * from `h_kq_instance_score` where assessment_id=? and assessor_id=?";
        $sqlArgs = array($assessment_id, $assessor_id);
        $res = $this->db->get_results($sql, $sqlArgs);
        return $res ? $res : array();
    }

    /** get single key quesions 
     * @params: $assessment_id, $assessor_id,$kq_id
     */
    function getSingleKeyQuestionScore($kq_id, $assessment_id, $assessor_id) {
        $sql = "SELECT * from `h_kq_instance_score` where assessment_id=? and assessor_id=? && key_question_instance_id=?";
        $sqlArgs = array($assessment_id, $assessor_id, $kq_id);
        $res = $this->db->get_results($sql, $sqlArgs);
        return $res ? $res : array();
    }

    /** get all key Core quesions  score
     * @params: $assessment_id, $assessor_id,$kq_id
     */
    function getAllCoreQuestionScore($assessment_id, $assessor_id) {
        $sql = "SELECT * from `h_cq_score` where assessment_id=? and assessor_id=?";
        $sqlArgs = array($assessment_id, $assessor_id);
        $res = $this->db->get_results($sql, $sqlArgs);
        return $res ? $res : array();
    }

    /** get single key Core quesions 
     * @params: $assessment_id, $assessor_id,$kq_id
     */
    function getSingleCoreQuestionScore($cq_id, $assessment_id, $assessor_id) {
        $sql = "SELECT * from `h_cq_score` where assessment_id=? and assessor_id=? && core_question_instance_id=?";
        $sqlArgs = array($assessment_id, $assessor_id, $cq_id);
        $res = $this->db->get_results($sql, $sqlArgs);
        return $res ? $res : array();
    }

    /** get all kpa score quesions 
     * @params: $assessment_id, $assessor_id,$kq_id
     */
    function getAllKpaScore($assessment_id, $assessor_id) {
        $sql = "SELECT * from `h_kpa_instance_score` where assessment_id=? and assessor_id=?";
        $sqlArgs = array($assessment_id, $assessor_id);
        $res = $this->db->get_results($sql, $sqlArgs);
        return $res ? $res : array();
    }

    /** get get Single Kpa Score details of assessment for it's Id
     * Paramas: $assessment_id,$assessor_id,$kpa_id
     */
    function getSingleKpaScore($kpa_id, $assessment_id, $assessor_id) {
        $sql = "SELECT * from `h_kpa_instance_score` where assessment_id=? and assessor_id=? && kpa_instance_id=?";
        $sqlArgs = array($assessment_id, $assessor_id, $kpa_id);
        $res = $this->db->get_results($sql, $sqlArgs);
        return $res ? $res : array();
    }

    /** get key details of assessment for it's Id
     * Paramas: $assessment_id,$kpa_id
     */
    function getKeyNotesPer($assessment_id, $kpa_id) {
        $sql = "SELECT * from `assessor_key_notes` where assessment_id=? and 	kpa_instance_id=?";
        $sqlArgs = array($assessment_id, $kpa_id);
        $res = $this->db->get_results($sql, $sqlArgs);
        return $res ? $res : array();
    }

    /** get Additional Questions Id for it's Id
     * Paramas: $aqsData_id, $tableName
     */
    function getAdditionalQuestionsId($aqsData_id, $tableName) {
        $sql = " SELECT * from $tableName where aqs_data_id=?";
        $res = $this->db->get_row($sql, array($aqsData_id));
        return $res ? $res : array();
    }

    /** get all details of assessment for it's Id
     * Paramas: $assessment_id,$lang_id,$actionPlan
     */
    function isUserinExternalTeamAssessment($assessment_id, $user_id) {
        $sql = "SELECT count(user_id) as num from d_assessment a INNER JOIN h_assessment_external_team b on a.assessment_id =b.assessment_id "
                . " where a.assessment_id=? and b.user_id=?";
        $res = $this->db->get_row($sql, array($assessment_id, $user_id));
        return $res ? $res : null;
    }

    /** get all details of assessment for it's Id
     * Paramas: $assessment_id,$lang_id,$actionPlan
     */
    function getAssessmentById($assessment_id, $lang_id = DEFAULT_LANGUAGE, $actionPlan = 0) {
        $sqlCond = '';
        $fieldCond = '';
        if ($actionPlan == 1) {
            $fieldCond = 'u1.email as pricniple_email,u1.name as principle_name,';
            $sqlCond = ' inner join d_user u1 on u1.client_id=a.client_id '
                    . ' inner join h_user_user_role r on  u1.user_id = r.user_id and r.role_id=6';
        }
        $sql = "SELECT $fieldCond a.aqs_round,q.status as aqs_status,b.ratingInputDate as rating_date,aq.school_aqs_pref_end_date as rating_date1,dp.province_name,a.assessment_id,aq.school_aqs_pref_start_date as aqs_start_date,aq.school_aqs_pref_end_date,
                    ag.group_assessment_id,d.assessment_type_id,a.client_id,a.diagnostic_id, a.isAssessorKeyNotesApproved, 
                    c.principal_phone_no, date(a.create_date) as create_date,hlt.translation_text as diagnostic_name,c.client_name,
                    c.street,c.city,c.state,t.assessment_type_name, group_concat(b.role order by b.role) as roles, 
                    group_concat(b.user_id order by b.role) as user_ids, group_concat(u.name order by b.role) as user_names,
                    group_concat(b.isFilled order by b.role) as  statuses, cn.network_id, n.network_name,
                    r.isPublished as report_published,CASE WHEN d.assessment_type_id = 2 THEN if(sum(td.value)>0,1,0) 
                    WHEN d.assessment_type_id = 4 THEN if(sum(sd.value)>0,1,0) END as isTchrInfoFilled,a.d_sub_assessment_type_id as 
                    subAssessmentType,group_concat(b.percComplete order by b.role) as perc,a.aqsdata_id,a.is_approved as 
                    isApproved,aq.school_aqs_pref_end_date,aq.school_aqs_pref_start_date

		FROM `d_assessment` a 
			inner join `h_assessment_user` b on a.assessment_id=b.assessment_id
			inner join d_user u on b.user_id=u.user_id
                        inner join `d_client` c on c.client_id=a.client_id
                        $sqlCond
			inner join `d_diagnostic` d on d.diagnostic_id=a.diagnostic_id
			inner join h_lang_translation hlt on d.equivalence_id = hlt.equivalence_id 		
			inner join `d_assessment_type` t on d.assessment_type_id=t.assessment_type_id
                        inner join `d_AQS_data` aqs on aqs.id=a.aqsdata_id
			left join h_assessment_ass_group ag on ag.assessment_id=a.assessment_id
			left join h_client_network cn on cn.client_id=c.client_id
			left join d_network n on cn.network_id=n.network_id
                        left join h_client_province pr on pr.client_id=c.client_id
                        left join d_province dp on dp.province_id=pr.province_id
			left join h_school_profile_status q on q.assessment_id=a.assessment_id
			left join d_AQS_data aq on aq.id=a.aqsdata_id
			left join d_teacher_data td on td.teacher_id=b.user_id and b.role=3 and td.assessment_id=a.assessment_id and td.attr_id=11
                        left join d_student_data sd on sd.student_id=b.user_id and b.role=3 and sd.assessment_id=a.assessment_id and sd.attr_id=49
			left join (select * from h_assessment_report r2 where r2.assessment_id=? limit 1) r on r.assessment_id=a.assessment_id
			
			where a.assessment_id=? group by a.assessment_id and hlt.translation_type_id=7 and hlt.language_id=$lang_id ";
        if ($res = $this->db->get_row($sql, array($assessment_id, $assessment_id))) {
            $res['roles'] = $res['roles'] != "" ? explode(',', $res['roles']) : array();
            $res['user_ids'] = $res['user_ids'] != "" ? explode(',', $res['user_ids']) : array();
            $res['user_names'] = $res['user_names'] != "" ? explode(',', $res['user_names']) : array();
            $res['statuses'] = $res['statuses'] != "" ? explode(',', $res['statuses']) : array();
            $l = count($res['roles']);
            for ($i = 0; $i < $l; $i++) {
                $res['userIdByRole'][$res['roles'][$i]] = $res['user_ids'][$i];
                $res['statusByRole'][$res['roles'][$i]] = $res['statuses'][$i];
                $res['usernameByRole'][$res['roles'][$i]] = $res['user_names'][$i];
            }
            return $res;
        } else
            return null;
    }

    /** get all details of assessment for it's Id
     * Paramas: $assessment_id,$lang_id,$actionPlan
     */
    function getReportsByAssessmentId($assessment_id, $makeReportIdAsKey = true) {
        $res = $this->db->get_results("select a.aqs_round,r.report_name,r.report_id,ar.isPublished,date(ar.publishDate) as publishDate,date(ar.valid_until) as valid_until,if(ar.report_id>0,1,0) as isGenerated,count(k.kpa_instance_id) as kcount,0 as group_assessment_id,q.status as aqs_status,q.school_aqs_pref_end_date,a.d_sub_assessment_type_id as subAssessmentType,DATE(a.create_date) as create_date

				from `d_reports` r 

				inner join d_diagnostic d on d.assessment_type_id=r.assessment_type_id
				inner join d_assessment a on a.diagnostic_id=d.diagnostic_id and  a.assessment_id=?
                                inner join h_kpa_diagnostic k on k.diagnostic_id=a.diagnostic_id
				left join h_assessment_report ar on r.report_id=ar.report_id and ar.assessment_id=a.assessment_id
				left join d_AQS_data q on q.id=a.aqsdata_id
				where r.isIndividualAssessmentReport=1
                group by r.report_id
                ", array($assessment_id));
        if (!$res)
            return array();
        else if ($makeReportIdAsKey)
            return $this->db->array_col_to_key($res, "report_id");
        else
            return $res;
    }

    /** get all details of assessment for it's Id
     * Paramas: $assessment_id,$lang_id,$actionPlan
     */
    function updateAssessmentReport($assessment_id, $report_id, $years, $months, $aqs_last_date, $publishIt = false) {
        $totalMonths = $months + ($years * 12);
        $data = array("valid_until" => date("Y-m-d H:i:s", strtotime("+$totalMonths month", strtotime($aqs_last_date))), 'publishDate' => date("Y-m-d H:i:s"));
        if ($publishIt) {
            $data['isPublished'] = 1;
        }
        return $this->db->update("h_assessment_report", $data, array("assessment_id" => $assessment_id, "report_id" => $report_id));
    }

    /** get all details of assessment for it's Id
     * Paramas: $assessment_id,$lang_id,$actionPlan
     */
    function insertAssessmentReport($assessment_id, $report_id, $years, $months, $aqs_last_date, $publishIt = false) {
        $totalMonths = $months + ($years * 12);
        $data = array("valid_until" => date("Y-m-d H:i:s", strtotime("+$totalMonths month", strtotime($aqs_last_date))), "assessment_id" => $assessment_id, "report_id" => $report_id, 'publishDate' => date("Y-m-d H:i:s"));
        if ($publishIt) {
            $data['isPublished'] = 1;
        }
        if ($this->db->insert("h_assessment_report", $data)) {
            return $this->db->get_last_insert_id();
        } else
            return false;
    }

    /** get all details of assessment for it's Id
     * Paramas: $assessment_id,$lang_id,$actionPlan
     */
    function getAssessmentTypes() {
        $res = $this->db->get_results("select * from d_assessment_type limit 0,1;");
        return $res ? $res : array();
    }

    /** get all details of assessment for it's Id
     * Paramas: $assessment_id,$lang_id,$actionPlan
     */
    function getDiagnosticTypes() {
        $res = $this->db->get_results("select * from d_diagnostic_type;");
        return $res ? $res : array();
    }

    /** get all details of kpa for Diagnostic
     * Paramas: $diagnostic_id
     */
    function getKpasForDiagnostic($diagnostic_id) {
        $res = $this->db->get_results("select '' as score_id, hlt.translation_text kpa_name,k.kpa_id,kd.kpa_instance_id,'' as rating, NULL as numericRating
			from d_kpa k
			inner join h_kpa_diagnostic kd on k.kpa_id=kd.kpa_id
			inner join h_lang_translation hlt on k.equivalence_id = hlt.equivalence_id 		
			where kd.diagnostic_id=?  and hlt.translation_type_id=1 and hlt.language_id={$this->lang} 
			order by kd.`kpa_order` asc;", array($diagnostic_id));
        return $res ? $res : array();
    }

    /** get all details of kpa for Diagnostic
     * Paramas: $diagnostic_id
     */
    function getKpasForDiagnosticLang($diagnostic_id, $lang_id = DEFAULT_LANGUAGE) {
        $res = $this->db->get_results("select '' as score_id, hlt.translation_text kpa_name,k.kpa_id,kd.kpa_instance_id,'' as rating, NULL as numericRating
			from d_kpa k
			inner join h_kpa_diagnostic kd on k.kpa_id=kd.kpa_id
			inner join h_lang_translation hlt on k.equivalence_id = hlt.equivalence_id 		
			where kd.diagnostic_id=?  and hlt.translation_type_id=1 and hlt.language_id=? 
			order by kd.`kpa_order` asc;", array($diagnostic_id, $lang_id));
        return $res ? $res : array();
    }

    /** get all details of kpa for Diagnostic
     * Paramas: $diagnostic_id
     */
    function getKpasForAssessment($assessment_id, $assessor_id, $kpa_id = 0, $lang_id = DEFAULT_LANGUAGE, $is_collaborative = 0, $external = 0, $isLeadAssessorKpa = 0) {

        $score = ' ks.id as score_id';
        if ($isLeadAssessorKpa)
            $score = '0 as score_id';
        $sql = " SELECT $score,rjs.kpa_rating,rjs.kpa_level2_rating, hlt.translation_text kpa_name,k.kpa_id,kd.kpa_instance_id,r.rating,hls.rating_level_order as numericRating,kd.`kpa_order` as kpa_no,rls.rating_level_scheme_id as scheme_id
			FROM `d_kpa` k ";
        if ($is_collaborative) {
            $sql .= 'inner join (select ck.diagnostic_id,ck.kpa_id,ck.kpa_instance_id, ck.kpa_order from h_kpa_diagnostic ck inner join d_assessment_kpa ka on ck.kpa_instance_id=ka.kpa_instance_id WHERE ka.user_id=? AND ka.assessment_id=? ) kd on kd.kpa_id = k.kpa_id';
        } else {
            $sql .= 'inner join h_kpa_diagnostic kd on k.kpa_id=kd.kpa_id';
        }
        $sql .= " inner join d_assessment a on kd.diagnostic_id=a.diagnostic_id";
        if ($external == 1) {
            $sql .= " inner join h_assessment_external_team au on au.assessment_id=a.assessment_id";
        } else {
            $sql .= " inner join h_assessment_user au on au.assessment_id=a.assessment_id";
        }
        $sql .= " inner join h_lang_translation hlt on k.equivalence_id = hlt.equivalence_id 	
                         left join (select group_concat(rating_id) as kpa_rating,group_concat(level2rating) as kpa_level2_rating, k.*,fs.level2rating,fs.rating_id,kd.kpa_instance_id from d_kpa k inner join h_kpa_diagnostic kd on k.kpa_id = kd.kpa_id
                            left join h_kpa_kq kq on kq.kpa_instance_id = kd.kpa_instance_id
                            left join h_kq_cq cq on cq.key_question_instance_id = kq.key_question_instance_id
                            left join h_cq_js_instance js on js.core_question_instance_id = cq.core_question_instance_id
                            left join f_score fs on fs.judgement_statement_instance_id = js.judgement_statement_instance_id
                            where fs.assessment_id=1 and fs.isFinal=1 group by kpa_instance_id
                        ) rjs on rjs.kpa_instance_id = kd.kpa_instance_id    
			left join `h_kpa_instance_score` ks on kd.kpa_instance_id=ks.kpa_instance_id and a.assessment_id=ks.assessment_id and ks.assessor_id=au.user_id
			left join h_diagnostic_rating_level_scheme rls on rls.diagnostic_id = a.diagnostic_id
                        left join h_rating_level_scheme hls on hls.rating_scheme_id =rls.rating_level_scheme_id and ks.d_rating_rating_id=hls.rating_id  and hls.rating_level_id=1
                        left join d_rating_level rl on rl.rating_level_id = hls.rating_level_id 
                        left join (select hlt.translation_text as rating,r.rating_id from h_lang_translation hlt INNER JOIN d_rating r on r.equivalence_id = hlt.equivalence_id   WHERE  hlt.language_id=?) r on ks.d_rating_rating_id = r.rating_id and hls.rating_id=r.rating_id 
			where a.assessment_id=? and au.user_id=? and hlt.translation_type_id=1 and hlt.language_id=? ";
        $sqlArgs = array($lang_id, $assessment_id, $assessor_id, $lang_id);
        if ($is_collaborative)
            array_splice($sqlArgs, 0, 0, array($assessor_id, $assessment_id));

        if ($kpa_id > 0) {
            $sql .= " and kd.kpa_instance_id=?";
            $sqlArgs[] = $kpa_id;
        }
        $sql .= " order by kd.`kpa_order` asc;";
        $res = $this->db->get_results($sql, $sqlArgs);
        return $res ? $res : array();
    }

    /** get all details of kpa for Diagnostic
     * Paramas: $diagnostic_id
     */
    function getKeyQuestionsForDiagnostic($diagnostic_id) {
        $res = $this->db->get_results("SELECT '' as score_id, kq.key_question_id, hlt.translation_text key_question_text,kkq.key_question_instance_id,kkq.kpa_instance_id,'' as rating, NULL as numericRating
			FROM `d_key_question` kq
			inner join h_kpa_kq kkq on kkq.key_question_id=kq.key_question_id
			inner join h_kpa_diagnostic kd on kd.kpa_instance_id=kkq.kpa_instance_id
			inner join h_lang_translation hlt on kq.equivalence_id = hlt.equivalence_id 		
			where kd.diagnostic_id=? and hlt.translation_type_id=2 and hlt.language_id={$this->lang} 
			order by kkq.`kq_order` asc", array($diagnostic_id));
        return $res ? $res : array();
    }

    /** get all details of kpa for Diagnostic
     * Paramas: $diagnostic_id
     */
    function getKeyQuestionsForDiagnosticLang($diagnostic_id, $lang_id = DEFAULT_LANGUAGE) {
        $res = $this->db->get_results("SELECT '' as score_id, kq.key_question_id, hlt.translation_text key_question_text,kkq.key_question_instance_id,kkq.kpa_instance_id,'' as rating, NULL as numericRating
			FROM `d_key_question` kq
			inner join h_kpa_kq kkq on kkq.key_question_id=kq.key_question_id
			inner join h_kpa_diagnostic kd on kd.kpa_instance_id=kkq.kpa_instance_id
			inner join h_lang_translation hlt on kq.equivalence_id = hlt.equivalence_id 		
			where kd.diagnostic_id=? and hlt.translation_type_id=2 and hlt.language_id=?
			order by kkq.`kq_order` asc", array($diagnostic_id, $lang_id));
        return $res ? $res : array();
    }

    /** get all details of kpa for Diagnostic
     * Paramas: $diagnostic_id
     */
    function getKeyQuestionsForAssessment($assessment_id, $assessor_id, $kpa_id = 0, $lang_id = DEFAULT_LANGUAGE, $external = 0, $userKpas = array(), $isLeadAssessorKpa = 0) {
        $score = ' ks.id as score_id';
        if ($isLeadAssessorKpa)
            $score = '0 as score_id';
        $sql = "SELECT $score, kq.key_question_id, hlt.translation_text key_question_text,kkq.key_question_instance_id,kkq.kpa_instance_id,r.rating,hls.rating_level_order as numericRating,rls.rating_level_scheme_id as scheme_id
			FROM `d_key_question` kq
			inner join h_kpa_kq kkq on kkq.key_question_id=kq.key_question_id
			inner join h_kpa_diagnostic kd on kd.kpa_instance_id=kkq.kpa_instance_id
			inner join d_assessment a on kd.diagnostic_id=a.diagnostic_id";
        $cond = '';
        if ($external == 1) {
            $sql .= ' inner join h_assessment_external_team au on au.assessment_id=a.assessment_id ';
            if (!empty($userKpas))
                $cond = " and kd.kpa_instance_id IN (" . implode(",", $userKpas) . ")";
        } else
            $sql .= ' inner join h_assessment_user au on au.assessment_id=a.assessment_id';

        $sql .= " inner join h_lang_translation hlt on kq.equivalence_id = hlt.equivalence_id	
                left join `h_kq_instance_score` ks on kkq.key_question_instance_id=ks.key_question_instance_id and a.assessment_id=ks.assessment_id and ks.assessor_id=au.user_id	
                left join h_diagnostic_rating_level_scheme rls on rls.diagnostic_id = a.diagnostic_id
                left join h_rating_level_scheme hls on hls.rating_scheme_id =rls.rating_level_scheme_id and ks.d_rating_rating_id=hls.rating_id  and hls.rating_level_id=2
                left join d_rating_level rl on rl.rating_level_id = hls.rating_level_id 
                left join (select hlt.translation_text as rating,r.rating_id from h_lang_translation hlt INNER JOIN d_rating r on r.equivalence_id = hlt.equivalence_id   WHERE  hlt.language_id=?) r on ks.d_rating_rating_id = r.rating_id and hls.rating_id=r.rating_id 
                where a.assessment_id=? $cond and au.user_id=? and hlt.translation_type_id=2 and hlt.language_id=?";
        $sqlArgs = array($lang_id, $assessment_id, $assessor_id, $lang_id);
        if ($kpa_id > 0) {
            $sql .= " and kd.kpa_instance_id=?";
            $sqlArgs[] = $kpa_id;
        }
        $sql .= " order by kkq.`kq_order` asc;";
        $res = $this->db->get_results($sql, $sqlArgs);
        return $res ? $res : array();
    }

    /** get all details of kpa for Diagnostic
     * Paramas: $diagnostic_id
     */
    function getCoreQuestionsForDiagnostic($diagnostic_id) {
        $res = $this->db->get_results("SELECT '' as score_id, cq.core_question_id,hlt.translation_text as core_question_text,kqcq.core_question_instance_id,kqcq.key_question_instance_id,'' as rating, NULL as numericRating
			FROM `d_core_question` cq
			inner join h_kq_cq kqcq on kqcq.core_question_id=cq.core_question_id
			inner join h_kpa_kq kkq on kkq.key_question_instance_id=kqcq.key_question_instance_id
			inner join h_kpa_diagnostic kd on kd.kpa_instance_id=kkq.kpa_instance_id
			inner join h_lang_translation hlt on cq.equivalence_id = hlt.equivalence_id 		
			where kd.diagnostic_id=? and hlt.translation_type_id=3 and hlt.language_id={$this->lang} 
			order by kqcq.`cq_order` asc;", array($diagnostic_id));
        return $res ? $res : array();
    }

    /** get all details of kpa for Diagnostic
     * Paramas: $diagnostic_id
     */
    function getCoreQuestionsForDiagnosticLang($diagnostic_id, $lang_id = DEFAULT_LANGUAGE) {
        $res = $this->db->get_results("SELECT '' as score_id, cq.core_question_id,hlt.translation_text as core_question_text,kqcq.core_question_instance_id,kqcq.key_question_instance_id,'' as rating, NULL as numericRating
			FROM `d_core_question` cq
			inner join h_kq_cq kqcq on kqcq.core_question_id=cq.core_question_id
			inner join h_kpa_kq kkq on kkq.key_question_instance_id=kqcq.key_question_instance_id
			inner join h_kpa_diagnostic kd on kd.kpa_instance_id=kkq.kpa_instance_id
			inner join h_lang_translation hlt on cq.equivalence_id = hlt.equivalence_id 		
			where kd.diagnostic_id=? and hlt.translation_type_id=3 and hlt.language_id=? 
			order by kqcq.`cq_order` asc;", array($diagnostic_id, $lang_id));
        return $res ? $res : array();
    }

    /** get all details of kpa for Diagnostic
     * Paramas: $diagnostic_id
     */
    function getCoreQuestionsForAssessment($assessment_id, $assessor_id, $kpa_id = 0, $lang_id = DEFAULT_LANGUAGE, $external = 0, $userKpas = array(), $isLeadAssessorKpa = 0) {

        $cond = '';
        $score = ' cqs.id as score_id';
        if ($isLeadAssessorKpa)
            $score = '0 as score_id';
        $sql = "SELECT $score, cq.core_question_id,hlt.translation_text as core_question_text,kqcq.core_question_instance_id,kqcq.key_question_instance_id,r.rating,hls.rating_level_order as numericRating,rls.rating_level_scheme_id as scheme_id
			FROM `d_core_question` cq
			inner join h_kq_cq kqcq on kqcq.core_question_id=cq.core_question_id
			inner join h_kpa_kq kkq on kkq.key_question_instance_id=kqcq.key_question_instance_id
			inner join h_kpa_diagnostic kd on kd.kpa_instance_id=kkq.kpa_instance_id
			inner join d_assessment a on kd.diagnostic_id=a.diagnostic_id";
        if ($external == 1) {
            $sql .= ' inner join h_assessment_external_team au on au.assessment_id=a.assessment_id';
            if (!empty($userKpas))
                $cond = " and kd.kpa_instance_id IN (" . implode(",", $userKpas) . ")";
        } else
            $sql .= ' inner join h_assessment_user au on au.assessment_id=a.assessment_id';

        $sql .= "  inner join h_lang_translation hlt on cq.equivalence_id = hlt.equivalence_id
			left join `h_cq_score` cqs on kqcq.core_question_instance_id=cqs.core_question_instance_id and a.assessment_id=cqs.assessment_id and cqs.assessor_id=au.user_id
			left join h_diagnostic_rating_level_scheme rls on rls.diagnostic_id = a.diagnostic_id
                        left join h_rating_level_scheme hls on hls.rating_scheme_id =rls.rating_level_scheme_id and cqs.d_rating_rating_id=hls.rating_id  and hls.rating_level_id=3
                        left join d_rating_level rl on rl.rating_level_id = hls.rating_level_id 
			left join (select hlt.translation_text as rating,r.rating_id from h_lang_translation hlt INNER JOIN d_rating r on r.equivalence_id = hlt.equivalence_id   WHERE  hlt.language_id=?) r on cqs.d_rating_rating_id = r.rating_id and hls.rating_id=r.rating_id 
			where a.assessment_id=? $cond and au.user_id=? and hlt.translation_type_id=3 and hlt.language_id=? ";
        $sqlArgs = array($lang_id, $assessment_id, $assessor_id, $lang_id);
        if ($kpa_id > 0) {
            $sql .= " and kd.kpa_instance_id=?";
            $sqlArgs[] = $kpa_id;
        }
        $sql .= "
			order by kqcq.`cq_order` asc;";
        $res = $this->db->get_results($sql, $sqlArgs);
        return $res ? $res : array();
    }

    /** get all details of kpa for Diagnostic
     * Paramas: $diagnostic_id
     */
    function getCoreQuestionsForKQAssessment($assessment_id, $assessor_id, $key_question_instance_id = 0, $lang_id = DEFAULT_LANGUAGE) {
        $sql = "SELECT cqs.id as score_id, cq.core_question_id,hlt.translation_text as core_question_text,kqcq.core_question_instance_id,kqcq.key_question_instance_id,r.rating,hls.rating_level_order as numericRating,rls.rating_level_scheme_id as scheme_id
			FROM `d_core_question` cq
                        inner join h_lang_translation hlt on cq.equivalence_id = hlt.equivalence_id
			inner join h_kq_cq kqcq on kqcq.core_question_id=cq.core_question_id
			inner join h_kpa_kq kkq on kkq.key_question_instance_id=kqcq.key_question_instance_id
			inner join h_kpa_diagnostic kd on kd.kpa_instance_id=kkq.kpa_instance_id
			inner join d_assessment a on kd.diagnostic_id=a.diagnostic_id
			inner join h_assessment_user au on au.assessment_id=a.assessment_id
			left join `h_cq_score` cqs on kqcq.core_question_instance_id=cqs.core_question_instance_id and a.assessment_id=cqs.assessment_id and cqs.assessor_id=au.user_id
			left join h_diagnostic_rating_level_scheme rls on rls.diagnostic_id = a.diagnostic_id
            left join h_rating_level_scheme hls on hls.rating_scheme_id =rls.rating_level_scheme_id and cqs.d_rating_rating_id=hls.rating_id  and hls.rating_level_id=3
            left join d_rating_level rl on rl.rating_level_id = hls.rating_level_id 
			left join (select hlt.translation_text as rating,r.rating_id from h_lang_translation hlt INNER JOIN d_rating r on r.equivalence_id = hlt.equivalence_id   WHERE  hlt.language_id=?) r on cqs.d_rating_rating_id = r.rating_id and hls.rating_id=r.rating_id 
			where a.assessment_id=?  and au.user_id=? and hlt.language_id=?";
        $sqlArgs = array($lang_id, $assessment_id, $assessor_id, $lang_id);
        if ($key_question_instance_id > 0) {
            $sql .= " and kkq.key_question_instance_id=?";
            $sqlArgs[] = $key_question_instance_id;
        }
        $sql .= "
			order by kqcq.`cq_order` asc;";
        $res = $this->db->get_results($sql, $sqlArgs);
        return $res ? $res : array();
    }

    /** get all details of kpa for Diagnostic
     * Paramas: $diagnostic_id
     */
    function getJudgementalStatementsForDiagnostic($diagnostic_id) {
        $res = $this->db->get_results("SELECT '' as files, '' as score_id,js.judgement_statement_id,hlt.translation_text judgement_statement_text,'' as evidence_text, cqjs.judgement_statement_instance_id,cqjs.core_question_instance_id,'' as rating,NULL as numericRating
			FROM `d_judgement_statement` js
			inner join h_cq_js_instance cqjs on js.judgement_statement_id=cqjs.judgement_statement_id
			inner join h_kq_cq kqcq on kqcq.core_question_instance_id=cqjs.core_question_instance_id
			inner join h_kpa_kq kkq on kkq.key_question_instance_id=kqcq.key_question_instance_id
			inner join h_kpa_diagnostic kd on kd.kpa_instance_id=kkq.kpa_instance_id
			inner join h_lang_translation hlt on js.equivalence_id = hlt.equivalence_id 			
			where kd.diagnostic_id=?  and hlt.translation_type_id=4 and hlt.language_id={$this->lang} 
			order by cqjs.`js_order` asc", array($diagnostic_id));
        return $res ? $res : array();
    }

    /** get all details of kpa for Diagnostic
     * Paramas: $diagnostic_id
     */
    function getJudgementalStatementsForDiagnosticLang($diagnostic_id, $lang_id = 9) {
        $res = $this->db->get_results("SELECT '' as files, '' as score_id,js.judgement_statement_id,hlt.translation_text judgement_statement_text,'' as evidence_text, cqjs.judgement_statement_instance_id,cqjs.core_question_instance_id,'' as rating,NULL as numericRating
			FROM `d_judgement_statement` js
			inner join h_cq_js_instance cqjs on js.judgement_statement_id=cqjs.judgement_statement_id
			inner join h_kq_cq kqcq on kqcq.core_question_instance_id=cqjs.core_question_instance_id
			inner join h_kpa_kq kkq on kkq.key_question_instance_id=kqcq.key_question_instance_id
			inner join h_kpa_diagnostic kd on kd.kpa_instance_id=kkq.kpa_instance_id
			inner join h_lang_translation hlt on js.equivalence_id = hlt.equivalence_id 			
			where kd.diagnostic_id=?  and hlt.translation_type_id=4 and hlt.language_id=?
			order by cqjs.`js_order` asc", array($diagnostic_id, $lang_id));
        return $res ? $res : array();
    }

    /** get all details of kpa for Diagnostic
     * Paramas: $diagnostic_id
     */
    function getJudgementalStatementsForDiagnosticLangKpa($diagnostic_id, $lang_id = 9) {
        $res = $this->db->get_results("SELECT '' as files, '' as score_id,js.judgement_statement_id,
                            hlt.translation_text judgement_statement_text,'' as evidence_text,kqcq.core_question_instance_id, cqjs.judgement_statement_instance_id,cqjs.kpa_instance_id,
                            '' as rating,NULL as numericRating FROM `d_judgement_statement` js 
                            inner join h_kpa_js_instance cqjs on js.judgement_statement_id=cqjs.judgement_statement_id 
                            inner join h_kpa_diagnostic kd on kd.kpa_instance_id=cqjs.kpa_instance_id
                            inner join h_kpa_kq kkq on kkq.kpa_instance_id=kd.kpa_instance_id
                            inner join h_kq_cq kqcq on kqcq.key_question_instance_id=kkq.key_question_instance_id			    
                            inner join h_lang_translation hlt on js.equivalence_id = hlt.equivalence_id 
                            where kd.diagnostic_id=?  and hlt.translation_type_id=4 and hlt.language_id=?
			order by cqjs.`js_order` asc", array($diagnostic_id, $lang_id));
        return $res ? $res : array();
    }

    /** get all details of kpa for Diagnostic
     * Paramas: $diagnostic_id
     */
    function getJudgementalStatementsForAssessment($assessment_id, $assessor_id, $kpa_id = 0, $lang_id = DEFAULT_LANGUAGE, $external = 0, $userKpas = array(), $isLeadAssessorKpa = 0) {

        $cond = '';
        $score = ' fs.score_id';
        if ($isLeadAssessorKpa)
            $score = '0 as score_id';
        $sql = "SELECT fs.level2rating, 
			if(fs.score_id is NULL,cqjs.judgement_statement_instance_id,fs.score_id) as groupId, GROUP_CONCAT(  CONCAT(f.file_id,'|',f.file_name) SEPARATOR '||') as files, $score,fs.level2rating,js.judgement_statement_id,hlt.translation_text judgement_statement_text,fs.evidence_text,fs.evidence_text_lw,fs.evidence_text_co,fs.evidence_text_in,evidence_text_bl, cqjs.judgement_statement_instance_id,cqjs.core_question_instance_id,r.rating,hls.rating_level_order as numericRating,rls.rating_level_scheme_id as scheme_id
			FROM `d_judgement_statement` js
			inner join h_cq_js_instance cqjs on js.judgement_statement_id=cqjs.judgement_statement_id
			inner join h_kq_cq kqcq on kqcq.core_question_instance_id=cqjs.core_question_instance_id
			inner join h_kpa_kq kkq on kkq.key_question_instance_id=kqcq.key_question_instance_id
			inner join h_kpa_diagnostic kd on kd.kpa_instance_id=kkq.kpa_instance_id
			inner join d_assessment a on kd.diagnostic_id=a.diagnostic_id";
        if ($external == 1) {
            $sql .= ' inner join h_assessment_external_team au on au.assessment_id=a.assessment_id';
            if (!empty($userKpas))
                $cond = " and kd.kpa_instance_id IN (" . implode(",", $userKpas) . ")";
        } else
            $sql .= ' inner join h_assessment_user au on au.assessment_id=a.assessment_id';

        $sql .= " inner join h_lang_translation hlt on js.equivalence_id = hlt.equivalence_id 
                        
			left join `f_score` fs on cqjs.judgement_statement_instance_id=fs.judgement_statement_instance_id and a.assessment_id=fs.assessment_id and fs.assessor_id=au.user_id and isFinal=1
                       
			left join h_diagnostic_rating_level_scheme rls on rls.diagnostic_id = a.diagnostic_id
                        left join h_rating_level_scheme hls on hls.rating_scheme_id =rls.rating_level_scheme_id and fs.rating_id=hls.rating_id  and hls.rating_level_id=4
                        left join d_rating_level rl on rl.rating_level_id = hls.rating_level_id 
			left join (select hlt.translation_text as rating,r.rating_id from h_lang_translation hlt INNER JOIN d_rating r on r.equivalence_id = hlt.equivalence_id   WHERE  hlt.language_id=?) r on fs.rating_id = r.rating_id and hls.rating_id=r.rating_id 
			left join h_score_file sf on sf.score_id=fs.score_id
                        left join d_file f on sf.file_id=f.file_id
			where a.assessment_id=? $cond and au.user_id=? and hlt.translation_type_id=4 and hlt.language_id=?";
        $sqlArgs = array($lang_id, $assessment_id, $assessor_id, $lang_id);
        if ($kpa_id > 0) {
            $sql .= " and kd.kpa_instance_id=?";
            $sqlArgs[] = $kpa_id;
        }
        $sql .= "
			group by groupId
			order by cqjs.`js_order` asc";
        $res = $this->db->get_results($sql, $sqlArgs);
        return $res ? $res : array();
    }

    /** get all details of kpa for Diagnostic
     * Paramas: $diagnostic_id
     */
    function getJudgementalStatementsForAssessmentKpa($assessment_id, $assessor_id, $kpa_id = 0, $lang_id = DEFAULT_LANGUAGE, $external = 0, $userKpas = array(), $isLeadAssessorKpa = 0) {
        $cond = '';
        $score = ' fs.score_id';
        if ($isLeadAssessorKpa)
            $score = '0 as score_id';
        $sql = "SELECT 
			if(fs.score_id is NULL,cqjs.judgement_statement_instance_id,fs.score_id) as groupId, GROUP_CONCAT(  CONCAT(f.file_id,'|',f.file_name) SEPARATOR '||') as files, $score,js.judgement_statement_id,hlt.translation_text judgement_statement_text,fs.evidence_text, cqjs.judgement_statement_instance_id,kqcq.core_question_instance_id,r.rating,hls.rating_level_order as numericRating,rls.rating_level_scheme_id as scheme_id
			FROM `d_judgement_statement` js
			inner join h_kpa_js_instance cqjs on js.judgement_statement_id=cqjs.judgement_statement_id
                        inner join h_kpa_diagnostic kd on kd.kpa_instance_id=cqjs.kpa_instance_id			
			inner join h_kpa_kq kkq on kd.kpa_instance_id=kkq.kpa_instance_id
                        inner join h_kq_cq kqcq on kqcq.key_question_instance_id=kkq.key_question_instance_id
			inner join d_assessment a on kd.diagnostic_id=a.diagnostic_id";
        if ($external == 1) {
            $sql .= ' inner join h_assessment_external_team au on au.assessment_id=a.assessment_id';
            if (!empty($userKpas))
                $cond = " and kd.kpa_instance_id IN (" . implode(",", $userKpas) . ")";
        } else
            $sql .= ' inner join h_assessment_user au on au.assessment_id=a.assessment_id';

        $sql .= " inner join h_lang_translation hlt on js.equivalence_id = hlt.equivalence_id 	
			left join `f_score` fs on cqjs.judgement_statement_instance_id=fs.judgement_statement_instance_id and a.assessment_id=fs.assessment_id and fs.assessor_id=au.user_id and isFinal=1
			left join h_diagnostic_rating_level_scheme rls on rls.diagnostic_id = a.diagnostic_id
                        left join h_rating_level_scheme hls on hls.rating_scheme_id =rls.rating_level_scheme_id and fs.rating_id=hls.rating_id  and hls.rating_level_id=4
                        left join d_rating_level rl on rl.rating_level_id = hls.rating_level_id 
			left join (select hlt.translation_text as rating,r.rating_id from h_lang_translation hlt INNER JOIN d_rating r on r.equivalence_id = hlt.equivalence_id   WHERE  hlt.language_id=?) r on fs.rating_id = r.rating_id and hls.rating_id=r.rating_id 
			left join h_score_file sf on sf.score_id=fs.score_id
                        left join d_file f on sf.file_id=f.file_id
			where a.assessment_id=? $cond and au.user_id=? and hlt.translation_type_id=4 and hlt.language_id=?";
        $sqlArgs = array($lang_id, $assessment_id, $assessor_id, $lang_id);
        if ($kpa_id > 0) {
            $sql .= " and kd.kpa_instance_id=?";
            $sqlArgs[] = $kpa_id;
        }
        $sql .= "
			group by groupId
			";
        $res = $this->db->get_results($sql, $sqlArgs);
        return $res ? $res : array();
    }

    /** get all details of kpa for Diagnostic
     * Paramas: $diagnostic_id
     */
    function getKpasCompletenessStatus($assessment_id, $assessor_id) {
        $res = $this->db->get_results("SELECT kd.kpa_instance_id, sum(hls.rating_level_order is not  NULL) as filled,sum(hls.rating_level_order is NULL) as unfilled
			FROM `d_judgement_statement` js
			inner join h_cq_js_instance cqjs on js.judgement_statement_id=cqjs.judgement_statement_id
			inner join h_kq_cq kqcq on kqcq.core_question_instance_id=cqjs.core_question_instance_id
			inner join h_kpa_kq kkq on kkq.key_question_instance_id=kqcq.key_question_instance_id
			inner join h_kpa_diagnostic kd on kd.kpa_instance_id=kkq.kpa_instance_id
			inner join d_assessment a on kd.diagnostic_id=a.diagnostic_id
			inner join h_assessment_user au on au.assessment_id=a.assessment_id
			left join `f_score` fs on cqjs.judgement_statement_instance_id=fs.judgement_statement_instance_id and a.assessment_id=fs.assessment_id and fs.assessor_id=au.user_id and isFinal=1
			left join h_diagnostic_rating_level_scheme rls on rls.diagnostic_id = a.diagnostic_id
            left join h_rating_level_scheme hls on hls.rating_scheme_id =rls.rating_level_scheme_id and fs.rating_id=hls.rating_id  and hls.rating_level_id=4
            left join d_rating_level rl on rl.rating_level_id = hls.rating_level_id 
			where a.assessment_id=?  and au.user_id=?
            group by kd.kpa_instance_id", array($assessment_id, $assessor_id));
        return $res ? $res : array();
    }

    /** get all details of kpa for Diagnostic
     * Paramas: $diagnostic_id
     */
    function updateAssessorKeyNotesStatus($assessment_id, $status) {
        return $this->db->update("d_assessment", array("isAssessorKeyNotesApproved" => $status), array("assessment_id" => $assessment_id));
    }

    /** get all details of kpa for Diagnostic
     * Paramas: $diagnostic_id
     */
    function updateAqsDataIdInAssessment($assmntId_or_grpAssmntId, $assessment_type_id, $aqsdata_id) {
        if ($assessment_type_id == 1) {
            return $this->db->update("d_assessment", array("aqsdata_id" => $aqsdata_id), array("assessment_id" => $assmntId_or_grpAssmntId));
        } else {
            return $this->db->query("update d_assessment set aqsdata_id=? where assessment_id in (SELECT assessment_id FROM h_assessment_ass_group where group_assessment_id=?);", array($aqsdata_id, $assmntId_or_grpAssmntId));
        }
    }

    /** get all details of kpa for Diagnostic
     * Paramas: $diagnostic_id
     */
    function getAssessorKeyNotes($assessment_id, $isNotnull = 0, $kpa_id = 0) {
        $sqlArgs = array($assessment_id);
        $sqlCond = '';
        if (!empty($kpa_id)) {
            $sqlCond = ' AND kpa_instance_id = ? ';
            array_push($sqlArgs, $kpa_id);
        }
        $sql = "SELECT * FROM assessor_key_notes where assessment_id= ? $sqlCond ;";
        if ($isNotnull > 0) {
            $sql .= " and length(text_data)>=1";
        }
        $res = $this->db->get_results($sql, $sqlArgs);
        return $res ? $res : array();
    }

    /** get all details of kpa for Diagnostic
     * Paramas: $diagnostic_id
     */
    function getAssessorKeyNotesForType($assessment_id, $type, $instance_id) {
        $type_instance_id = $type . '_instance_id';
        $sqlArgs = array($assessment_id, $instance_id);
        $sql = "SELECT a.*,group_concat(DISTINCT rec_judgement_instance_id) as rec_judgement_instance_id FROM assessor_key_notes a left join h_assessor_key_notes_js b on a.id=b.assessor_key_notes_id where a.rec_type=0 && a.assessment_id= ? and a.$type_instance_id = ? group by a.id";
        $res = $this->db->get_results($sql, $sqlArgs);
        return $res ? $res : array();
    }

    //get assessor key notes for assessment
    function getAssessorKeyNotesTypeOrder($assessment_id, $type, $lang = DEFAULT_LANGUAGE, $roleids = array(), $user_id = 0) {
        $sqlArgs = array($assessment_id);
        $leaderCondition = "";
        $arg_array = array();
        $arg_array[] = $assessment_id;
        $arg_array[] = $type;
        if (in_array("1", $roleids) || in_array("2", $roleids) || in_array("5", $roleids) || in_array("6", $roleids)) {
            $leaderCondition = "";
        } else if (in_array("3", $roleids)) {
            $leaderCondition = " && n3.leader=?";
            $arg_array[] = $user_id;
        }

        $sql = "select xyz1.*,GROUP_CONCAT(kk) as kk1,GROUP_CONCAT(js SEPARATOR '@#@$') as js_text  from (select xyz.*,js_o.show_text,concat('Key Domain-',kpa_order_f,' / ','Core Standard-',js_o.show_text) as kk from (SELECT a.*,f.kpa_order_f,e.kq_order_f,d.cq_order_f,c.js_order_f,((e.kq_order_f-1)*9+(d.cq_order_f-1)*3+c.js_order_f) as final_js_order,hlt.translation_text as js,n3.* FROM assessor_key_notes a 
                      inner join h_assessor_key_notes_js b on a.id=b.assessor_key_notes_id
                      inner join (select *,if(((js_order%3)=0),3,js_order%3) as js_order_f from h_cq_js_instance) c on b.rec_judgement_instance_id=c.judgement_statement_instance_id
                      inner join d_judgement_statement djs on djs.judgement_statement_id=c.judgement_statement_id
                      inner join h_lang_translation hlt on djs.equivalence_id=hlt.equivalence_id && hlt.language_id='" . $lang . "'
                      inner join (select *,if(((cq_order%3)=0),3,cq_order%3) as cq_order_f from h_kq_cq) d on c.core_question_instance_id=d.core_question_instance_id
                      inner join (select *,if(((kq_order % 3)=0),3,kq_order % 3) as kq_order_f from h_kpa_kq) e on e.key_question_instance_id=d.key_question_instance_id
                      inner join (select *,kpa_order as kpa_order_f from h_kpa_diagnostic) f on f.kpa_instance_id=e.kpa_instance_id
                      left join ( select n1.assessor_key_notes_id as assessor_k_id,n1.from_date,n1.to_date,n1.leader,n1.frequency_report,n1.reporting_authority,n1.action_status,n1.mail_status,GROUP_CONCAT(designation_id) as designations,GROUP_CONCAT(impact_statement SEPARATOR '#--#') as impact_statements,GROUP_CONCAT(assessor_action1_impact_id) as assessor_action1_impact_ids  from h_assessor_action1 n1 left join h_assessor_action1_impact n2 on n1.h_assessor_action1_id=n2.assessor_action1_id group by n1.h_assessor_action1_id) n3 on  n3.assessor_k_id=a.id
                      where assessment_id= ?  and type=? " . $leaderCondition . " && rec_type=1 order by a.rec_type,f.kpa_order,e.kq_order,d.cq_order,c.js_order) xyz 
                      left join d_js_order js_o on xyz.final_js_order=js_o.order_id order by rec_type,kpa_order_f,kq_order_f,cq_order_f,js_order_f) xyz1 group by id order by rec_type,kpa_order_f,kq_order_f,cq_order_f,js_order_f";

        $res = $this->db->get_results($sql, $arg_array);
        return $res ? $res : array();
    }
    /** get Assessor Key Notes Type
     * Paramas: $assessment_id, $type
     */
    function getAssessorKeyNotesType($assessment_id, $type) {
        $sqlArgs = array($assessment_id);
        $sql = "SELECT * FROM assessor_key_notes where assessment_id= ?  and type=?;";
        $res = $this->db->get_results($sql, array($assessment_id, $type));
        return $res ? $res : array();
    }
    /** get Internal Assessor
     * Paramas: $assessment_id
     */
    function getInternalAssessor($assessment_id) {
        $sqlArgs = array($assessment_id);
        $sql = "SELECT * FROM h_assessment_user where assessment_id= ?  and role=?;";
        $res = $this->db->get_row($sql, array($assessment_id, 3));
        return $res ? $res['user_id'] : array();
    }
    /** get Internal Assessor
     * Paramas: $assessment_id
     */
    function getAssessorKeyNoteById($rec_id, $lang = DEFAULT_LANGUAGE) {

        $sqlArgs = array($rec_id);
        $sql = "select xyz1.*,GROUP_CONCAT(kk) as kk1,GROUP_CONCAT(js SEPARATOR '@#@$') as js_text,GROUP_CONCAT(cq SEPARATOR '@#@$') as cq_text,GROUP_CONCAT(kq SEPARATOR '@#@$') as kq_text,GROUP_CONCAT(kpa SEPARATOR '@#@$') as kpa_text  from (select xyz.*,js_o.show_text,concat('KD-',kpa_order_f,'/','KQ-',kq_order_f,'/','SQ-',cq_order_f,'/','CS-',js_o.show_text) as kk from (SELECT a.*,f.kpa_order_f,e.kq_order_f,d.cq_order_f,c.js_order_f,((e.kq_order_f-1)*9+(d.cq_order_f-1)*3+c.js_order_f) as final_js_order,hlt.translation_text as js,hlt_cq.translation_text as cq,hlt_kq.translation_text as kq,hlt_kpa.translation_text as kpa FROM assessor_key_notes a 
                      inner join h_assessor_key_notes_js b on a.id=b.assessor_key_notes_id
                      inner join (select *,if(((js_order%3)=0),3,js_order%3) as js_order_f from h_cq_js_instance) c on b.rec_judgement_instance_id=c.judgement_statement_instance_id
                      inner join d_judgement_statement djs on djs.judgement_statement_id=c.judgement_statement_id
                      inner join h_lang_translation hlt on djs.equivalence_id=hlt.equivalence_id && hlt.language_id='" . $lang . "'
                      inner join (select *,if(((cq_order%3)=0),3,cq_order%3) as cq_order_f from h_kq_cq) d on c.core_question_instance_id=d.core_question_instance_id
                      inner join d_core_question dcq on dcq.core_question_id=d.core_question_id
                      inner join h_lang_translation hlt_cq on dcq.equivalence_id=hlt_cq.equivalence_id && hlt_cq.language_id='" . $lang . "'
                      inner join (select *,if(((kq_order % 3)=0),3,kq_order % 3) as kq_order_f from h_kpa_kq) e on e.key_question_instance_id=d.key_question_instance_id
                      
                      inner join d_key_question dkq on dkq.key_question_id=e.key_question_id
                      inner join h_lang_translation hlt_kq on dkq.equivalence_id=hlt_kq.equivalence_id && hlt_kq.language_id='" . $lang . "'
                      
                      inner join (select *,kpa_order as kpa_order_f from h_kpa_diagnostic) f on f.kpa_instance_id=e.kpa_instance_id
                      
                      inner join d_kpa dka on dka.kpa_id=f.kpa_id
                      inner join h_lang_translation hlt_kpa on dka.equivalence_id=hlt_kpa.equivalence_id && hlt_kpa.language_id='" . $lang . "'
                       
                      where a.id=?  order by a.rec_type,f.kpa_order,e.kq_order,d.cq_order,c.js_order) xyz 
                      left join d_js_order js_o on xyz.final_js_order=js_o.order_id order by rec_type,kpa_order_f,kq_order_f,cq_order_f,js_order_f) xyz1 group by id order by rec_type,kpa_order_f,kq_order_f,cq_order_f,js_order_f";

        $res = $this->db->get_row($sql, array($rec_id));
        return $res ? $res : array();
    }
    /** get Internal Assessor
     * Paramas: $assessment_id
     */
    function getAssessorKeyNotesLevel($assessment_id, $level) {
        $sqlArgs = array($assessment_id);
        $sql = "SELECT * FROM assessor_key_notes where assessment_id= ?  and $level is not null";
        $res = $this->db->get_results($sql, array($assessment_id));
        return $res ? $res : array();
    }
/** get Internal Assessor
     * Paramas: $assessment_id
     */
    function getDiagnosticRatingScheme($diagnostic_id) {

        $res = $this->db->get_results("SELECT rating_id,rating_level_text `type`,rating_level_order `order`,rating_level_scheme_id as scheme FROM h_diagnostic_rating_level_scheme rls INNER JOIN h_rating_level_scheme hls on  
				rls.rating_level_scheme_id = hls.rating_scheme_id inner join d_rating_level rl on rl.rating_level_id = hls.rating_level_id
				where rls.diagnostic_id=?", array($diagnostic_id));
        return $res ? $res : array();
    }
/** get Internal Assessor
     * Paramas: $assessment_id
     */
    function insertJudgementStatementScore($js_id, $assessment_id, $assessor_id, $added_by, $rating_id, $evidence_text = '', $textLw, $textCo, $textInt, $textBl, $level = 0) {
        if ($this->db->insert('f_score', array("judgement_statement_instance_id" => $js_id, "assessment_id" => $assessment_id, "assessor_id" => $assessor_id, "added_by" => $added_by, "rating_id" => $rating_id, "evidence_text" => $evidence_text, 'evidence_text_lw' => $textLw, 'evidence_text_co' => $textCo, 'evidence_text_in' => $textInt, 'evidence_text_bl' => $textBl, "isFinal" => 1, "date_added" => date("Y-m-d H:i:s"), 'level2rating' => $level))) {
            return $this->db->get_last_insert_id();
        } else
            return false;
    }
/** get Internal Assessor
     * Paramas: $assessment_id
     */
    function updateJudgementStatementScore($js_id, $assessment_id, $assessor_id, $added_by, $rating_id, $evidence_text = '', $textLw = '', $textCo = '', $textInt = '', $textBl = '', $level = 0) {
        if ($this->db->update("f_score", array("isFinal" => 0), array("judgement_statement_instance_id" => $js_id, "assessment_id" => $assessment_id, "assessor_id" => $assessor_id))) {

            return $this->insertJudgementStatementScore($js_id, $assessment_id, $assessor_id, $added_by, $rating_id, $evidence_text, $textLw, $textCo, $textInt, $textBl, $level);
        } else
            return false;
    }
/** get Internal Assessor
     * Paramas: $assessment_id
     */
    function updateCompleteStatus($assessment_id, $assessor_id, $completedPerc, $isLead = 0) {
        $table = 'h_assessment_external_team';
        $params = array("percComplete" => $completedPerc);
        if ($completedPerc < 100)
            $params['isFilled'] = 0;
        if ($isLead == 1)
            $table = 'h_assessment_user';
        if ($this->db->update($table, $params, array("assessment_id" => $assessment_id, "user_id" => $assessor_id))) {
            return true;
        } else
            return false;
    }
/** get Internal Assessor
     * Paramas: $assessment_id
     */
    function insertCoreQuestionScore($cq_id, $assessment_id, $assessor_id, $rating_id) {
        if ($this->db->insert('h_cq_score', array("core_question_instance_id" => $cq_id, "assessment_id" => $assessment_id, "assessor_id" => $assessor_id, "d_rating_rating_id" => $rating_id))) {
            return $this->db->get_last_insert_id();
        } else
            return false;
    }
/** get Internal Assessor
     * Paramas: $assessment_id
     */
    function updateCoreQuestionScore($cq_id, $assessment_id, $assessor_id, $rating_id) {
        return $this->db->update('h_cq_score', array("d_rating_rating_id" => $rating_id), array("core_question_instance_id" => $cq_id, "assessment_id" => $assessment_id, "assessor_id" => $assessor_id));
    }
/** get Internal Assessor
     * Paramas: $assessment_id
     */
    function deleteCoreQuestionScore($cq_id, $assessment_id, $assessor_id) {
        return $this->db->delete('h_cq_score', array("core_question_instance_id" => $cq_id, "assessment_id" => $assessment_id, "assessor_id" => $assessor_id));
    }
/** get Internal Assessor
     * Paramas: $assessment_id
     */
    function deleteInternalJudgementStatementScore($assessment_id, $assessor_id) {
        return $this->db->delete('f_score', array("assessment_id" => $assessment_id, "assessor_id" => $assessor_id));
    }
/** get Internal Assessor
     * Paramas: $assessment_id
     */
    function insertKeyQuestionScore($kq_id, $assessment_id, $assessor_id, $rating_id) {
        if ($this->db->insert('h_kq_instance_score', array("key_question_instance_id" => $kq_id, "assessment_id" => $assessment_id, "assessor_id" => $assessor_id, "d_rating_rating_id" => $rating_id))) {
            return $this->db->get_last_insert_id();
        } else
            return false;
    }
/** get Internal Assessor
     * Paramas: $assessment_id
     */
    function updateKeyQuestionScore($kq_id, $assessment_id, $assessor_id, $rating_id) {
        return $this->db->update('h_kq_instance_score', array("d_rating_rating_id" => $rating_id), array("key_question_instance_id" => $kq_id, "assessment_id" => $assessment_id, "assessor_id" => $assessor_id));
    }
/** get Internal Assessor
     * Paramas: $assessment_id
     */
    function deleteKeyQuestionScore($kq_id, $assessment_id, $assessor_id) {
        return $this->db->delete('h_kq_instance_score', array("key_question_instance_id" => $kq_id, "assessment_id" => $assessment_id, "assessor_id" => $assessor_id));
    }
/** get Internal Assessor
     * Paramas: $assessment_id
     */
    function insertKpaScore($kpa_id, $assessment_id, $assessor_id, $rating_id) {
        if ($this->db->insert('h_kpa_instance_score', array("kpa_instance_id" => $kpa_id, "assessment_id" => $assessment_id, "assessor_id" => $assessor_id, "d_rating_rating_id" => $rating_id))) {
            return $this->db->get_last_insert_id();
        } else
            return false;
    }
/** get Internal Assessor
     * Paramas: $assessment_id
     */
    function updateKpaScore($kpa_id, $assessment_id, $assessor_id, $rating_id) {
        return $this->db->update('h_kpa_instance_score', array("d_rating_rating_id" => $rating_id), array("kpa_instance_id" => $kpa_id, "assessment_id" => $assessment_id, "assessor_id" => $assessor_id));
    }
/** get Internal Assessor
     * Paramas: $assessment_id
     */
    function deleteKpaScore($kpa_id, $assessment_id, $assessor_id) {
        return $this->db->delete('h_kpa_instance_score', array("kpa_instance_id" => $kpa_id, "assessment_id" => $assessment_id, "assessor_id" => $assessor_id));
    }
/** get Internal Assessor
     * Paramas: $assessment_id
     */
    function updateAssessmentPercentage($assessment_id, $assessor_id, $percentage, $is_collaborative = 0, $external = 0, $is_submit = 0, $isLeadAssessor = 0, $isLeadSave = 0) {
        $tablleName = 'h_assessment_user';
        $data = array("percComplete" => $percentage, 'ratingInputDate' => date('Y-m-d h:i:s'));
        if ($isLeadSave == 1) {
            $data['isLeadSave'] = $isLeadSave;
        }
        $params = array("assessment_id" => $assessment_id, "user_id" => $assessor_id);
        if ($is_collaborative && $is_submit == 1) {
            $tablleName = 'h_assessment_external_team';
            $data = array("percComplete" => $percentage, 'isFilled' => 1, 'ratingInputDate' => date('Y-m-d h:i:s'));
            $params = array("assessment_id" => $assessment_id);
            if ($isLeadAssessor == 0)
                $params['user_id'] = $assessor_id;
        }else if ($is_collaborative && $external && $is_submit == 0) {
            $tablleName = 'h_assessment_external_team';
        } else if ($is_submit) {
            $data = array("percComplete" => $percentage, 'isFilled' => 1, 'ratingInputDate' => date('Y-m-d h:i:s'));
            $params = array("assessment_id" => $assessment_id, "role" => 3);
        }
        return $this->db->update($tablleName, $data, $params);
    }

    function updateAssessmentReplicate($assessment_id) {
        return $this->db->update('d_assessment', array("is_replicated" => 1, "replicated_date_time" => date("Y-m-d H:i:s")), array("assessment_id" => $assessment_id));
    }

    function updateAssessmentPercentageAndStatus($assessment_id, $assessor_id, $percentage, $status) {
        $data = array("percComplete" => $percentage, "isFilled" => $status);
        if ($status > 0)
            $data['ratingInputDate'] = date("Y-m-d H:i:s");
        return $this->db->update('h_assessment_user', $data, array("assessment_id" => $assessment_id, "user_id" => $assessor_id));
    }

    function addAssessorKeyNote($assessment_id, $instance_type, $instance_type_id, $text, $type, $aknJS = array()) {
        if ($this->db->insert('assessor_key_notes', array("assessment_id" => $assessment_id, $instance_type => $instance_type_id, "text_data" => $text, "type" => $type))) {
            if (count($aknJS) <= 0) {
                return $this->db->get_last_insert_id();
            } else {
                $last_id = $this->db->get_last_insert_id();
                foreach ($aknJS as $key => $val) {
                    if (!$this->db->insert('h_assessor_key_notes_js', array("assessor_key_notes_id" => $last_id, "rec_judgement_instance_id" => $val))) {
                        return false;
                    }
                }
                return $last_id;
            }
        } else
            return false;
    }

    /** delete Assessor Key Note
     * Paramas: $keynote_id
     */
    function updateAssessorKeyNote($keynote_id, $text, $type = '', $aknJS = array(), $old_js = array()) {
        $c_old_js = array();
        if ($this->db->update('assessor_key_notes', array("text_data" => $text, "type" => $type), array("id" => $keynote_id))) {



            if (count($aknJS) <= 0) {

                if (!$this->db->delete('h_assessor_key_notes_js', array("assessor_key_notes_id" => $keynote_id))) {
                    return false;
                }

                return true;
            } else {



                foreach ($aknJS as $key => $val) {
                    if (in_array($val, $old_js)) {

                        if (!$this->db->update('h_assessor_key_notes_js', array("assessor_key_notes_id" => $keynote_id, "rec_judgement_instance_id" => $val), array("assessor_key_notes_id" => $keynote_id, "rec_judgement_instance_id" => $val))) {
                            return false;
                        }
                        $c_old_js[] = $val;
                    } else {

                        if (!$this->db->insert('h_assessor_key_notes_js', array("assessor_key_notes_id" => $keynote_id, "rec_judgement_instance_id" => $val))) {
                            return false;
                        }
                    }
                }

                $js_left = array_diff($old_js, $c_old_js);
                if (count($js_left) > 0) {
                    foreach ($js_left as $key_1 => $val_1) {

                        if (!$this->db->delete('h_assessor_key_notes_js', array("assessor_key_notes_id" => $keynote_id, "rec_judgement_instance_id" => $val_1))) {
                            return false;
                        }
                    }
                }

                return true;
            }
        }

        return false;
    }

    /** delete Assessor Key Note
     * Paramas: $keynote_id
     */
    function deleteAssessorKeyNote($keynote_id) {

        if ($this->db->delete('h_assessor_key_notes_js', array("assessor_key_notes_id" => $keynote_id))) {

            if (!$this->db->delete('assessor_key_notes', array("id" => $keynote_id))) {
                return false;
            }

            return true;
        }

        return false;
    }

    /** add Uploaded File
     * Paramas: $filesString
     */
    public function addUploadedFile($fileName, $uploaded_by, $size = 0) {
        if ($this->db->insert('d_file', array("file_name" => $fileName, "uploaded_by" => $uploaded_by, 'file_size' => $size, "upload_date" => date("Y-m-d H:i:s")))) {
            return $this->db->get_last_insert_id();
        } else
            return false;
    }

    /** get File From Score
     * Paramas: $filesString
     */
    public function getFile($file_id) {
        $sql = "SELECT 
				f.*,sf.score_file_id,sf.score_id
				FROM `d_file` f
				left join `h_score_file` sf on f.file_id=sf.file_id 
				where f.file_id=?";
        return $this->db->get_row($sql, array($file_id));
    }

    /** unlink File From Score
     * Paramas: $filesString
     */
    public function linkFileToScore($score_id, $file_id) {
        return $this->db->insert("h_score_file", array("score_id" => $score_id, "file_id" => $file_id));
    }

    /** unlink File From Score
     * Paramas: $filesString
     */
    public function unlinkFileFromScore($score_file_id) {
        return $this->db->delete("h_score_file", array("score_file_id" => $score_file_id));
    }

    /** delete File From DB
     * Paramas: $filesString
     */
    public function deleteFileFromDB($file_id) {
        return $this->db->delete("d_file", array("file_id" => $file_id));
    }

    /** decode file array
     * Paramas: $filesString
     */
    public static function decodeFileArray($filesString) {
        $files = array();
        if ($filesString != "") {
            $temp = explode("||", $filesString);
            foreach ($temp as $t) {
                $t2 = explode("|", $t);
                $files[$t2[0]] = $t2[1];
            }
        }
        return $files;
    }

    //get file extention
    public static function getFileExt($fileName) {
        $temp = explode(".", $fileName);
        return strtolower(array_pop($temp));
    }

    //calculate statement for kpa
    public static function calculateStatementResultKpa($res, $ratingScheme, $level, $kpaJs_ratings = array(), $kpaSq_ratings = array()) {

        $valuesCount = array('score1' => 0, 'score2' => 0, 'score3' => 0);
        $numJs = count($kpaJs_ratings);
        foreach ($kpaJs_ratings as $key => $val) {
            $valuesCount["score" . $val] ++;
        }
        $percScore3 = 0;
        $percScore2 = 0;
        $percScore1 = 0;
        if ($valuesCount['score3'] >= 1) {
            $percScore3 = ($valuesCount['score3'] * 100) / $numJs;
        } if ($valuesCount['score1'] >= 1) {
            $percScore1 = ($valuesCount['score1'] * 100) / $numJs;
        }
        if ($percScore3 >= 50 && $percScore1 < 50) {
            return 3;
        } else if ($percScore1 >= 50 && $percScore3 < 50) {
            return 1;
        } else {
            return 2;
        }
    }

    //calculate statement
    public static function calculateStatementResult($res, $ratingScheme, $level, $kpaJs_ratings = array(), $kpaSq_ratings = array()) {
        $resRating = 0;
        switch ($ratingScheme) {
            case 4 :
            case 2 : //currently scheme for teacher review	
                if ($level == 4) {//judgement statement
                    $valuesCount = array('s1' => 0, 's2' => 0, 's3' => 0, 's4' => 0);
                    for ($i = 0; $i < count($res); $i++)
                        $valuesCount["s" . $res[$i]] ++;
                    if (($valuesCount['s3'] + $valuesCount['s4']) == 3)//3 mostly/always->exceptional
                        return 5;
                    else if (($valuesCount['s3'] + $valuesCount['s4']) == 2)//2mostly/always->proficient
                        return 4;
                    else if (($valuesCount['s3'] + $valuesCount['s4']) == 1)//1 mostly/always->developing
                        return 3;
                    else if ($valuesCount['s2'] >= 2)//2 or more mostly-> emerging
                        return 2;
                    else if ($valuesCount['s1'] >= 2)//2 rarely->foundation
                        return 1;
                    else
                        return 0;
                }
                else if ($level == 3) {//SQ ratings received-no rating at KQ level
                    if ($ratingScheme == 4) {
                        $valuesCount = array('s1' => 0, 's2' => 0, 's3' => 0, 's4' => 0, 's5' => 0);
                        for ($i = 0; $i < count($res); $i++)
                            $valuesCount["s" . $res[$i]] ++;

                        $ftot = 0;
                        $tot = (1 * $valuesCount['s1']) + (2 * $valuesCount['s2']) + (3 * $valuesCount['s3']) + (4 * $valuesCount['s4']) + (5 * $valuesCount['s5']);
                        $ftot = round($tot / 3);
                        return $ftot;
                    } else {
                        return 6;
                    }
                } else if ($level == 2 && $ratingScheme == 2) {//rating at KPA level based on JS ratings;
                    if (empty($kpaJs_ratings) || empty($kpaSq_ratings))
                        return 0;
                    $valuesCount = array('s1' => 0, 's2' => 0, 's3' => 0, 's4' => 0);
                    for ($i = 0; $i < count($kpaJs_ratings); $i++)
                        $valuesCount["s" . $kpaJs_ratings[$i]] ++;

                    $sqValuesCount = array('s1' => 0, 's2' => 0, 's3' => 0, 's4' => 0, 's5' => 0);
                    for ($i = 0; $i < count($kpaSq_ratings); $i++)
                        $sqValuesCount["s" . $kpaSq_ratings[$i]] ++;
                    if (($valuesCount['s4'] + $valuesCount['s3']) >= 19)//mostly and/or always rating 	
                        return 5;
                    else if (($valuesCount['s4'] + $valuesCount['s3']) >= 10 && ($valuesCount['s4'] + $valuesCount['s3']) <= 18) {//mostly and/or always rating								
                        if (($sqValuesCount['s4'] + $sqValuesCount['s5']) >= 4)
                            return 4;
                        else
                            return 3;
                    }
                    else if (($valuesCount['s4'] + $valuesCount['s3']) >= 6 && ($valuesCount['s4'] + $valuesCount['s3']) <= 9) //mostly and/or always rating
                        return 3;
                    else if (($valuesCount['s4'] + $valuesCount['s3']) >= 3 && ($valuesCount['s4'] + $valuesCount['s3']) <= 5)//mostly and/or always rating
                        return 2;
                    else if (($valuesCount['s4'] + $valuesCount['s3']) >= 0 && ($valuesCount['s4'] + $valuesCount['s3']) <= 2)//mostly and/or always rating
                        return 1;
                    else
                        return 0;
                }else if ($level == 2 && $ratingScheme == 4) {
                    $valuesCount = array('s1' => 0, 's2' => 0, 's3' => 0, 's4' => 0, 's5' => 0);
                    for ($i = 0; $i < count($res); $i++)
                        $valuesCount["s" . $res[$i]] ++;

                    $sqValuesCount = array('s1' => 0, 's2' => 0, 's3' => 0, 's4' => 0, 's5' => 0);
                    for ($i = 0; $i < count($kpaSq_ratings); $i++)
                        $sqValuesCount["s" . $kpaSq_ratings[$i]] ++;

                    $ftot = 0;
                    $tot = (1 * $valuesCount['s1']) + (2 * $valuesCount['s2']) + (3 * $valuesCount['s3']) + (4 * $valuesCount['s4']) + (5 * $valuesCount['s5']);
                    $ftot = round($tot / 3);
                    //return $ftot;

                    if ($sqValuesCount['s5'] > 3) {
                        return 5;
                    } else if ($valuesCount['s5'] > 1) {
                        return 5;
                    } else if ($sqValuesCount['s4'] > 3) {
                        return 4;
                    } else if ($valuesCount['s4'] > 1) {
                        return 4;
                    } else if ($ftot > 0) {
                        return $ftot;
                    } else {
                        return 0;
                    }
                }
                break;
            case 5:
            case 1: //school reviews have this scheme currently	
                $valuesCount = array('s1' => 0, 's2' => 0, 's3' => 0, 's4' => 0);
                for ($i = 0; $i < count($res); $i++)
                    $valuesCount["s" . $res[$i]] ++;
                if ($valuesCount['s1'] > 1)
                    return 1;
                else if ($valuesCount['s2'] > 1)
                    return 2;
                else if ($valuesCount['s3'] > 1)
                    return 3;
                else if ($valuesCount['s4'] > 1)
                    return 4;
                else if ($valuesCount['s1'] == 0)
                    return 3;
                else if ($valuesCount['s2'] == 0)
                    return 3;
                else if ($valuesCount['s3'] == 0)
                    return 2;
                else if ($valuesCount['s4'] == 0)
                    return 2;
                else
                    return 0;
                break;
        }
    }

    //get js for Kpa
    public function getJSforKPA($kpaId, $langId = DEFAULT_LANGUAGE, $diagnostic_type = 0) {
        $sql = "select xyz1.*,d_js_order.show_text from (select (@cnt := @cnt + 1) AS rowNumber,xyz.* from (SELECT js.judgement_statement_id,cqjs.judgement_statement_instance_id, b.translation_text as judgement_statement_text,js_order
			FROM `d_judgement_statement` js
                        inner join h_lang_translation b on js.equivalence_id=b.equivalence_id
			inner join h_cq_js_instance cqjs on js.judgement_statement_id=cqjs.judgement_statement_id
			inner join h_kq_cq kqcq on kqcq.core_question_instance_id=cqjs.core_question_instance_id
			inner join h_kpa_kq kkq on kkq.key_question_instance_id=kqcq.key_question_instance_id
			inner join h_kpa_diagnostic kd on kd.kpa_instance_id=kkq.kpa_instance_id
			where kkq.kpa_instance_id=? AND b.language_id = ?
			order by kd.kpa_order,kkq.kq_order,kqcq.cq_order,cqjs.`js_order` asc) xyz CROSS JOIN (SELECT @cnt := 0) AS dummy) xyz1 left join d_js_order on xyz1.rowNumber=d_js_order.order_id ";
        if ($diagnostic_type == 1) {

            $sql = "select xyz1.*,d_js_order.show_text from (select (@cnt := @cnt + 1) AS rowNumber,xyz.* from (SELECT js.judgement_statement_id,cqjs.judgement_statement_instance_id, b.translation_text as judgement_statement_text,js_order
			FROM `d_judgement_statement` js
                        inner join h_lang_translation b on js.equivalence_id=b.equivalence_id
			inner join h_cq_js_instance cqjs on js.judgement_statement_id=cqjs.judgement_statement_id
			inner join h_kq_cq kqcq on kqcq.core_question_instance_id=cqjs.core_question_instance_id
			inner join h_kpa_kq kkq on kkq.key_question_instance_id=kqcq.key_question_instance_id
			inner join h_kpa_diagnostic kd on kd.kpa_instance_id=kkq.kpa_instance_id
			where kkq.kpa_instance_id=? AND b.language_id = ?
			order by kd.kpa_order,kkq.kq_order,kqcq.cq_order,cqjs.`js_order` asc) xyz CROSS JOIN (SELECT @cnt := 0) AS dummy) xyz1 left join d_js_order on xyz1.rowNumber=d_js_order.order_id";
        }
        $res = $this->db->get_results($sql, array($kpaId, $langId));
        return $res ? $res : array();
    }

    //get all the existing kpas for the review type
    function getKpasForAssessmentType($assessment_type = 1, $diagnostic_id = 0, $lang_id = 0, $diagnostic_type = 1) {

        $sqlWhr = 'where  hld.translation_type_id=1 && language_id=?';
        $sql = "select distinct a.kpa_id,hld.translation_text as kpa_name, b.diagnostic_id 
			FROM `d_kpa` a
			LEFT JOIN `h_kpa_diagnostic` b ON a.kpa_id = b.kpa_id
			LEFT JOIN `d_diagnostic` c ON c.diagnostic_id = b.diagnostic_id
			LEFT JOIN `d_assessment_type` d ON c.assessment_type_id = d.assessment_type_id AND d.assessment_type_id = ?
                        LEFT JOIN h_lang_translation hld ON a.equivalence_id= hld.equivalence_id
			$sqlWhr ";
        if ($diagnostic_id > 0)
            $sql .= " AND a.kpa_id NOT IN (select distinct a.kpa_id 
			FROM `d_kpa` a
			INNER JOIN `h_kpa_diagnostic` b ON a.kpa_id = b.kpa_id
			INNER JOIN `d_diagnostic` c ON c.diagnostic_id = b.diagnostic_id AND b.diagnostic_id = ? )";

        if ($diagnostic_id > 0)
            $sqlArgs = array($assessment_type, $lang_id, $diagnostic_id);
        else
            $sqlArgs = array($assessment_type, $lang_id);

        $sql .= " group by a.kpa_id order by a.`kpa_id` asc;";
        $res = $this->db->get_results($sql, $sqlArgs);
        return $res ? $res : array();
    }

    /*
     * get DiagnosticName
     * @params:$assessor_id,$assessor_id
     */

    function getDiagnosticName($diagnostic_id, $lang_id = DEFAULT_LANGUAGE) {
        $res = $this->db->get_results("select hlt.translation_text as 'name',kpa_recommendations,kq_recommendations,cq_recommendations,js_recommendations
			from d_diagnostic d	
			inner join h_lang_translation hlt on d.equivalence_id = hlt.equivalence_id 		
			where d.diagnostic_id=? and hlt.translation_type_id=7 and hlt.language_id=?  ", array($diagnostic_id, $lang_id));
        return $res ? $res : '';
    }

    /*
     * get checkAssessorIsLead
     * @params:$assessor_id,$assessor_id
     */

    function checkAssessorExternalTeam($assessment_id, $assessor_id) {
        $res = $this->db->get_row("select count(user_id) as isExternal
			from h_assessment_external_team		
			where assessment_id = ? AND user_id=? ", array($assessment_id, $assessor_id));
        return $res ? $res : array();
    }

    /*
     * get checkAssessorIsLead
     * @params:$assessor_id,$assessor_id
     */

    function checkAssessorIsLead($assessment_id, $assessor_id) {
        $res = $this->db->get_row("select user_id as isLead
			from h_assessment_user	
			where assessment_id = ? AND user_id=? AND role = ? ", array($assessment_id, $assessor_id, 4));
        return $res ? $res : array();
    }

    /*
     * get ExternalTeamRatingPerc
     * @params:$assessor_id
     */

    function getExternalTeamRatingPerc($assessment_id, $assessor_id = 0) {

        $res = $this->db->get_results("select t.user_id, t.isFilled ,t.user_id,t.percComplete,t.user_id
			from h_assessment_external_team t inner join d_assessment_kpa kp on t.assessment_id = kp.assessment_id and t.user_id = kp.user_id		
			where t.assessment_id = ?   and t.user_role = ? group by t.user_id ", array($assessment_id, 4));
        if (!empty($res)) {
            $dataArray = array('filledStatus' => '', 'user_ids' => '', 'percentageSum' => 0);
            foreach ($res as $data) {
                $dataArray['filledStatus'] .= $data['isFilled'] . ",";
                $dataArray['user_ids'] .= $data['user_id'] . ",";
                $dataArray['percentageSum'] = $dataArray['percentageSum'] + $data['percComplete'];
                $dataArray['numTeamMembers'] = count($res);
            }
            $dataArray['filledStatus'] = trim($dataArray['filledStatus'], ",");
            $dataArray['user_ids'] = trim($dataArray['user_ids'], ",");

            return $dataArray ? $dataArray : array();
        } else {

            return $res ? $res : array();
        }
    }

    /*
     * get AssessmentLead
     * @params:$assessment_id
     */

    function getAssessmentLead($assessment_id) {
        $res = $this->db->get_row("select user_id
			from h_assessment_user	
			where assessment_id = ? AND role=? ", array($assessment_id, 4));
        return $res ? $res : array();
    }

    /*
     * get getAllKeyQuestions by id
     * @params:$kpaId,$lang_id
     */

    function getAllKeyQuestions($diagnosticId = 0, $lang_id = 0) {
        $sql = "select  kq.key_question_id, kq.translation_text as key_question_text
		from (select a.*,b.translation_text,b.language_id from d_key_question a inner join h_lang_translation b on a.equivalence_id=b.equivalence_id) kq ";
        if ($diagnosticId > 0) {
            $sql .= "where kq.key_question_id not in (SELECT distinct kq.key_question_id
			FROM `d_key_question` kq
			inner join h_kpa_kq kkq on kkq.key_question_id=kq.key_question_id
			inner join h_kpa_diagnostic kd on kd.kpa_instance_id=kkq.kpa_instance_id
			where kd.diagnostic_id=?
			order by kkq.`kq_order` asc)
		";
            if ($lang_id > 0) {
                $sql .= " && kq.language_id=?";
                $res = $this->db->get_results($sql, array($diagnosticId, $lang_id));
            } else {
                $res = $this->db->get_results($sql, array($diagnosticId));
            }
        } else
        if ($lang_id > 0) {
            $sql .= " where kq.language_id=?";
            $res = $this->db->get_results($sql, array($lang_id));
        } else {
            $res = $this->db->get_results($sql);
        }
        return $res ? $res : array();
    }

    /*
     * get Selected Key Questions For Diagnostic by id
     * @params:$kpaId,$kpaId,$lang_id
     */

    function getSelectedKeyQuestionsForDiagnostic($diagnostic_id, $kpaId = 0, $lang_id = 0) {
        $sql = "SELECT distinct kq.key_question_id, kq.translation_text as key_question_text,kd.diagnostic_id
			FROM (select a.*,b.language_id,b.translation_text from `d_key_question` a left join h_lang_translation b on a.equivalence_id=b.equivalence_id) kq
			inner join h_kpa_kq kkq on kkq.key_question_id=kq.key_question_id
			inner join h_kpa_diagnostic kd on kd.kpa_instance_id=kkq.kpa_instance_id
			where kd.diagnostic_id=? and kd.kpa_instance_id=?";
        $array_p = array($diagnostic_id, $kpaId);
        if ($lang_id > 0) {
            $sql .= " && kq.language_id=?";
            $array_p[] = $lang_id;
        }
        $sql .= " order by kkq.`kq_order` asc;";
        $res = $this->db->get_results($sql, $array_p);
        return $res ? $res : array();
    }

    /*
     * get LangDetails by id
     * @params:$language_id,$equivalence_id
     */

    function getLangDetails($equivalence_id, $language_id) {
        $sql_trans = "select * from h_lang_translation where equivalence_id=? && language_id=?";
        $res = $this->db->get_row($sql_trans, array($equivalence_id, $language_id));
        return $res ? $res : array();
    }

    /*
     * get assessment by id
     * @params:$assessmentId
     */

    function getAssessmentTypeById($assessmentId) {
        $res = $this->db->get_results("select at.assessment_type_name from d_assessment_type at
		WHERE at.assessment_type_id = ?", array($assessmentId));
        return $res ? $res : array();
    }

    /*
     * get core question
     * @params:$diagnostic_id
     */

    function getAllCoreQuestions($diagnosticId = 0, $lang_id = 0) {
        $sql = "select  cq.core_question_id, cq.translation_text as core_question_text
		from (select a.*,b.language_id,b.translation_text from d_core_question a inner join h_lang_translation b on a.equivalence_id=b.equivalence_id) cq ";
        if ($diagnosticId > 0) {
            $sql .= " WHERE cq.core_question_id NOT IN (SELECT distinct cq.core_question_id
			FROM `d_core_question` cq
			inner join h_kq_cq kqcq on kqcq.core_question_id=cq.core_question_id
			inner join h_kpa_kq kkq on kkq.key_question_instance_id=kqcq.key_question_instance_id
			inner join h_kpa_diagnostic kd on kd.kpa_instance_id=kkq.kpa_instance_id
			where kd.diagnostic_id=? order by kqcq.`cq_order` asc)";

            if ($lang_id > 0) {
                $sql .= " && cq.language_id=? ";
            }

            $sql .= " order by cq.core_question_id asc;";
            if ($lang_id > 0) {
                $res = $this->db->get_results($sql, array($diagnosticId, $lang_id));
            } else {
                $res = $this->db->get_results($sql, array($diagnosticId));
            }
        } else
        if ($lang_id > 0) {
            $sql .= " where cq.language_id=?";
            $res = $this->db->get_results($sql, array($lang_id));
        } else {
            $res = $this->db->get_results($sql);
        }
        return $res ? $res : array();
    }

    // function for getting language id from name or name from id
    function getLanguageData($value, $type) {
        if ($type == 'id') {
            $condition = " and language_name='" . $value . "'";
            $field = "language_id";
        } else if ($type == 'name') {
            $condition = " and language_id='" . $value . "'";
            $field = "language_name";
        }
        $SQL = "Select " . $field . " from d_language where 1 " . $condition;
        $data = $this->db->get_row($SQL);
        if (!empty($data)) {
            return $data[$field];
        } else {
            return 0;
        }
    }

    /*
     * get diagnostic image
     * @params:$diagnostic_id
     */

    function getNumberOfKpasDiagnostic($diagnostic_id) {
        $res = $this->db->get_row("select count(*) as num
			from d_kpa k
			inner join h_kpa_diagnostic kd on k.kpa_id=kd.kpa_id
			where kd.diagnostic_id=?
			order by kd.`kpa_order` asc;", array($diagnostic_id));
        return $res ? $res : array();
    }

    /*
     * get diagnostic image
     * @params:$diagnostic_id
     */

    function getDiagnosticImage($diagnostic_id) {
        $res = $this->db->get_results("select d.diagnostic_image_id as 'diagnostic_image_id', f.file_name
			from d_diagnostic d
                        left join d_file f on d.diagnostic_image_id = f.file_id
			where d.diagnostic_id=? ", array($diagnostic_id));
        return $res ? $res : '';
    }

    /*
     * get report type
     * @params:$assessment_type_id
     */

    function getReportsType($assessment_type_id = 1) {
        $res = $this->db->get_results("select * from d_reports where assessment_type_id=? ", array($assessment_type_id));
        return $res ? $res : array();
    }

    /*
     * get report name
     */

    function getReportName($report_id) {
        $res = $this->db->get_row("select report_name from d_reports where report_id=? ", array($report_id));
        return $res ? $res : array();
    }

    /*
     * get self feedback submit status
     */

    function ratingLevalText() {


        $sql = " select rd.definition_id,hl.translation_text,rd.rating_id,jd.judgement_statement_id,hjs.level_type,hjd.judgement_statement_instance_id from d_rating_definition rd 
                    inner join h_js_rating_definiton hjs on rd.definition_id = hjs.definition_id
                    inner join d_judgement_statement jd on jd.judgement_statement_id = hjs.judgement_statement_id
                    inner join h_cq_js_instance hjd on hjd.judgement_statement_id = jd.judgement_statement_id
                    inner join h_lang_translation hl on hl.equivalence_id=rd.equivalence_id
                    where hl.language_id=? and hl.translation_type_id=? order by rd.definition_id";
        $res = $this->db->get_results($sql, array(9, 11));
        return $res ? $res : array();
    }

    //get assessment languages for assessment id
    function getAssessmentPrefferedLanguage($assessment_id) {
        $sql = "SELECT language_id FROM d_assessment WHERE assessment_id = ?";
        $res = $this->db->get_row($sql, array($assessment_id));
        return $res ? $res : array();
    }

    /*     * ***********************Condition in case of multiple Round 1 and Round 2 Exists End************************* */

    function getKpaQuestions($assessment_id, $language_id = DEFAULT_LANGUAGE) {
        $sql = "select sd.answer, sd.group_id,a.*,hlt.translation_text
                from d_school_profile a                
                inner join h_lang_translation hlt on a.equivalence_id = hlt.equivalence_id 
                left join f_school_profile_data sd
                on sd.school_profile_id = a.school_profile_id and sd.assessment_id =?
                where  hlt.language_id=? 
                group by a.school_profile_id,sd.group_id
                order by a.school_profile_id,a.display_order asc";
        $res = $this->db->get_results($sql, array($assessment_id, $language_id));
        return $res ? $res : array();
    }

    //get ke domain name
    function getKeyDomainName($kpa_id, $language_id = DEFAULT_LANGUAGE) {
        $sql = "select b.kpa_id,a.translation_text from h_lang_translation a
            inner join d_kpa b on a.equivalence_id=b.equivalence_id
            inner join d_school_profile c on b.kpa_id=c.kpa_id
            where b.kpa_id=? and a.language_id=?
            group by b.kpa_id order by b.kpa_id asc";
        $res = $this->db->get_results($sql, array($kpa_id, $language_id));
        return $res ? $res : array();
    }

    //get school profile status
    function getSchoolProfileStatus($assessment_id, $language_id = DEFAULT_LANGUAGE) {
        $sql = "select id as aqsStatusId,status
                from h_school_profile_status where  assessment_id = ?   ";
        $res = $this->db->get_row($sql, array($assessment_id));
        return $res ? $res : array();
    }

    //get clinet name by assessment id
    function getClientNameByAssessmentId($assessment_id) {
        $sql = "select client_name from d_assessment a
                inner join d_client b on a.client_id=b.client_id
                where assessment_id=?";
        $res = $this->db->get_results($sql, array($assessment_id));
        return $res ? $res : array();
    }

}
