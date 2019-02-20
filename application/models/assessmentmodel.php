<?php
/**
 * Reasons: Manage data for Assessment manage
 * Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 */
class assessmentModel extends Model {


    /*
     * Get user List by client Id
     * @params: $client_id
     */
    function getUsersbyClient($client_id){

		$res=$this->db->get_results("select u.user_id,u.name 

			from d_user u 

			inner join h_user_user_role ur on u.user_id=ur.user_id 

			inner join h_user_role_user_capability rc on rc.role_id=ur.role_id

			inner join d_user_capability c on rc.capability_id=c.capability_id

			where u.client_id=?  group by u.user_id order by u.name",array($client_id));

		return $res?$res:array();

	}

     /*
     * Get round details
     */   
    function getRounds() {

        $res = $this->db->get_results("select * from d_aqs_rounds;");

        return $res ? $res : array();
    }

     /*
     * Get assessment type details
     */   
    function getAssessmentTypes() {

        $res = $this->db->get_results("select r.report_id,t.assessment_type_id,r.report_name as assessment_type_name from d_assessment_type t inner join d_reports r"
                . " on t.assessment_type_id=r.assessment_type_id where r.report_id in (3,4)  ");

        return $res ? $res : array();
    }
    
    /*
     * Get all assessment type
     */  
    function getAllAssessmentTypes() {

        $res = $this->db->get_results("select * from d_assessment_type where assessment_type_id NOT IN (3);");

        return $res ? $res : array();
    }

    /*
     * Get get Student Rounds details for batch and student round
     * @params: $batch_id,$student_round
     */
    function getStudentRounds($batch_id, $student_round = 0) {

        $res = $this->db->get_results("select * from d_aqs_rounds where aqs_round<=2 && aqs_round NOT IN (select student_round from d_group_assessment where client_id=? && student_round!=?);", array($batch_id, $student_round));

        return $res ? $res : array();
    }

    /*
     * Get get Student Rounds details for batch and student round
     * @params: $batch_id,$student_round
     */
    function getStudentRoundsAll() {

        $res = $this->db->get_results("select * from d_aqs_rounds where aqs_round<=2", array());

        return $res ? $res : array();
    }

    /*
     * Get get internal assessors  for school
     * @params: $client_id
     */
    function getInternalAssessors($client_id) {

        $res = $this->db->get_results("select u.user_id,u.name,group_concat(if(au.isFilled=0 && assessment_type_id=1,0,1)) as filleds,group_concat(au.isFilled) as filleds2,group_concat(assessment_type_id) as assm_ids,

	  if(group_concat(d.diagnostic_id order by d.diagnostic_id)=(select group_concat(diagnostic_id order by diagnostic_id) from d_diagnostic where isPublished=1 and assessment_type_id=d.assessment_type_id group by assessment_type_id),1,0) as allDiagUsed

	   from d_user u 

	   inner join h_user_user_role ur on u.user_id=ur.user_id 

	   inner join h_user_role_user_capability rc on rc.role_id=ur.role_id

	   inner join d_user_capability c on rc.capability_id=c.capability_id

		left join h_assessment_user au on au.user_id=u.user_id and au.role=3

		left join d_assessment a on au.assessment_id=a.assessment_id

		left join d_diagnostic d on a.diagnostic_id=d.diagnostic_id  and a.d_sub_assessment_type_id!=1

		   where u.client_id=? and slug='take_internal_assessment'

            group by u.user_id 

			having allDiagUsed=0 or (allDiagUsed=1 and filleds rlike concat('[[:<:]]',1,'[[:>:]]') )           

            order by u.name;", array($client_id));
        return $res ? $res : array();
    }


     /*
     * Get get internal assessors  for school
     * @params: $client_id
     */
    function getGAIdfromClientandRound($client_id, $round) {

        $sql = "select * from d_group_assessment where client_id=? && student_round=? && assessment_type_id=?";

        $res = $this->db->get_row($sql, array($client_id, $round, 4));
        return $res;
    }

    function getGAIdfromClientandRoundUpdate($client_id, $round, $gaid) {

        $sql = "select * from d_group_assessment where client_id=? && student_round=? && assessment_type_id=? && group_assessment_id!=?";

        $res = $this->db->get_row($sql, array($client_id, $round, 4, $gaid));
        return $res;
    }
    
    /*get diagonestic data for internal assessor and school
     * @params:$client_id, $assessor_id, $assessment_id
     */
    function getDiagnosticsForInternalAssessor($client_id, $assessor_id, $assessment_id = 0) {

        $sql = "select u.user_id,u.name,

		group_concat(if(au.isFilled=1 && assessment_type_id=1,0,d.diagnostic_id)) as hidediagnostics

	   from d_user u 

	   inner join h_user_user_role ur on u.user_id=ur.user_id 

	   inner join h_user_role_user_capability rc on rc.role_id=ur.role_id

	   inner join d_user_capability c on rc.capability_id=c.capability_id

		left join h_assessment_user au on au.user_id=u.user_id and au.role=3

		left join d_assessment a on au.assessment_id=a.assessment_id

		left join d_diagnostic d on a.diagnostic_id=d.diagnostic_id 

		   where u.client_id=? and slug='take_internal_assessment'                  

             and u.user_id=? and a.assessment_id!=?

             group by u.user_id";



        $res = $this->db->get_row($sql, array($client_id, $assessor_id, $assessment_id));

        return $res;
    }

    /*edit diagonestic data for internal assessor and school
     * @params:$client_id, $user_id
     */
    function getEditInternalAssessors($client_id, $user_id) {

        $res = $this->db->get_results("select * from (select u.user_id,u.name,group_concat(if(au.isFilled=0 && assessment_type_id=1,0,1)) as filleds,group_concat(au.isFilled) as filleds2,group_concat(assessment_type_id) as assm_ids,

	  if(group_concat(d.diagnostic_id order by d.diagnostic_id)=(select group_concat(diagnostic_id order by diagnostic_id) from d_diagnostic where isPublished=1 and assessment_type_id=d.assessment_type_id group by assessment_type_id),1,0) as allDiagUsed

	   from d_user u 

	   inner join h_user_user_role ur on u.user_id=ur.user_id 

	   inner join h_user_role_user_capability rc on rc.role_id=ur.role_id

	   inner join d_user_capability c on rc.capability_id=c.capability_id

		left join h_assessment_user au on au.user_id=u.user_id and au.role=3

		left join d_assessment a on au.assessment_id=a.assessment_id and a.d_sub_assessment_type_id!=1

		left join d_diagnostic d on a.diagnostic_id=d.diagnostic_id 

		   where u.client_id=? and slug='take_internal_assessment'

            group by u.user_id 

			having allDiagUsed=0 or (allDiagUsed=1 and filleds rlike concat('[[:<:]]',1,'[[:>:]]') )           

                   

		union        



	   select u.user_id,u.name,group_concat(if(au.isFilled=0 && assessment_type_id=1,0,1)) as filleds,group_concat(au.isFilled) as filleds2,group_concat(assessment_type_id) as assm_ids,

		'' as allDiagUsed

	   from d_user u 

	   inner join h_user_user_role ur on u.user_id=ur.user_id 

	   inner join h_user_role_user_capability rc on rc.role_id=ur.role_id

	   inner join d_user_capability c on rc.capability_id=c.capability_id

		left join h_assessment_user au on au.user_id=u.user_id and au.role=3

		left join d_assessment a on au.assessment_id=a.assessment_id

		left join d_diagnostic d on a.diagnostic_id=d.diagnostic_id 

		   where u.client_id=? and slug='take_internal_assessment' and u.user_id = ?

            group by u.user_id              

           order by 2) intAssess group by user_id order by name ", array($client_id, $client_id, $user_id));
        return $res ? $res : array();
    }


    /*/function to get school review facilitators data
     * @params:$assessment_id
     */
    function getFacilitatorsDetails($assessment_id) {

        $res = $this->db->get_results("select u.user_id,u.name ,c.client_id,c.client_name,r.sub_role_id,r.sub_role_name

			from d_user u 
			inner join h_user_user_role ur on u.user_id=ur.user_id 
			inner join h_facilitator_user f on f.user_id=u.user_id
			inner join d_client c on c.client_id=f.client_id
			inner join d_user_sub_role r on r.sub_role_id=f.sub_role_id
			where f.assessment_id= ? group by user_id", array($assessment_id));

        return $res ? $res : array();
    }

    
    /*/function to get school external reviewer list
     * @params:$client_id
     */
    function getExternalAssessors($client_id) {

        $res = $this->db->get_results("select u.user_id,u.name 

			from d_user u 

			inner join h_user_user_role ur on u.user_id=ur.user_id 

			inner join h_user_role_user_capability rc on rc.role_id=ur.role_id

			inner join d_user_capability c on rc.capability_id=c.capability_id

			where u.client_id=? and slug='take_external_assessment'  group by u.user_id order by u.name", array($client_id));

        return $res ? $res : array();
    }

    /*function to facilitators data
     * @params:$client_id
     */
    function getFacilitators($client_id) {

        $res = $this->db->get_results("select u.user_id,u.name 

			from d_user u 

			inner join h_user_user_role ur on u.user_id=ur.user_id 

			inner join h_user_role_user_capability rc on rc.role_id=ur.role_id

			inner join d_user_capability c on rc.capability_id=c.capability_id

			where u.client_id=? and ur.role_id='9'  group by u.user_id order by u.name", array($client_id));

        return $res ? $res : array();
    }

    /*function to get all users of school
     * @params:$client_id
     */
    function getAllSchoolUsers($client_id) {

        $res = $this->db->get_results("select u.user_id,u.name 

			from d_user u 

			inner join h_user_user_role ur on u.user_id=ur.user_id 

			inner join h_user_role_user_capability rc on rc.role_id=ur.role_id

			inner join d_user_capability c on rc.capability_id=c.capability_id

			where u.client_id=? group by u.user_id order by u.name", array($client_id));

        return $res ? $res : array();
    }

     /*get all frequency
     */
    function getfrequency() {

        $res = $this->db->get_results("select * from d_frequency");

        return $res ? $res : array();
    }

    /*function to get admin of school
     * @params:$client_id
     */
    function getSchoolAdmins($client_id) {

        $res = $this->db->get_results("select u.user_id,u.name 

			from d_user u 

			inner join h_user_user_role ur on u.user_id=ur.user_id 

			inner join h_user_role_user_capability rc on rc.role_id=ur.role_id

			inner join d_user_capability c on rc.capability_id=c.capability_id

			where u.client_id=? and slug='add_n_assign_tchr_to_tchr_asmnt'  group by u.user_id order by u.name", array($client_id));

        return $res ? $res : array();
    }


    /*function to update status of assessment
     * @params:$assessment_id
     */
    function editAssessmentStatus($assessment_id) {
        return $this->db->update("h_assessment_user", array('isLeadSave' => 0), array('assessment_id' => $assessment_id, 'role' => 4));
    }

   
    /*/function to get school external reviewer list
     * @params:$external_assessor_id, $role, $assessment_id
     */
    function getExternalAssessor($external_assessor_id, $role, $assessment_id) {

        $res = $this->db->get_results("SELECT user_id FROM h_assessment_user WHERE  user_id = ? and role = ? and assessment_id = ?", array($external_assessor_id, $role, $assessment_id));
        return $res ? $res : array();
    }

    
    /*function to get school all rounds
     */
    function getSchoolRounds() {

        $res = $this->db->get_results("select aqs_round from d_aqs_rounds");

        return $res ? $res : array();
    }

    /*function to get school all not used rounds
     */
    function getSchoolRemainingRounds($client_id) {

        $res = $this->db->get_results("select * from d_aqs_rounds where aqs_round NOT IN (select aqs_round from d_assessment where client_id=?)", array($client_id));

        return $res ? $res : array();
    }

    /*function to get assessor for school
     */
    function getInternalAssessor($assessment_id) {

        $res = $this->db->get_row("SELECT a.user_id,b.name,d.client_name FROM h_assessment_user a left join d_user b on a.user_id=b.user_id left join d_assessment c on a.assessment_id=c.assessment_id left join d_client d on c.client_id=d.client_id WHERE  a.role = ? and a.assessment_id = ?", array(3, $assessment_id));
        return $res ? $res : array();
    }

    /*function to update external essessors
     * @params:$role, $assessment_id
     */
    function getExternalAssessorUpdate($role, $assessment_id) {

        $res = $this->db->get_row("SELECT a.user_id,b.name,d.client_name FROM h_assessment_user a left join d_user b on a.user_id=b.user_id left join d_assessment c on a.assessment_id=c.assessment_id left join d_client d on c.client_id=d.client_id WHERE  a.role = ? and a.assessment_id = ?", array($role, $assessment_id));
        return $res ? $res : array();
    }

    /*function to get review member
     * @params:$role, $assessment_id
     */
    function getReviewNotificationMembers($assessment_id) {
        $res = $this->db->get_results("SELECT user_id FROM h_user_review_notification WHERE assessment_id = ?", array($assessment_id));
        return $res ? $res : array();
    }
/*function to get review member
     * @params:$role, $assessment_id
     */
    function getReviewTeamMembers($assessment_id) {
        $res = $this->db->get_results("SELECT user_id FROM h_assessment_external_team WHERE assessment_id = ?", array($assessment_id));
        return $res ? $res : array();
    }

    
    /*function to update reviw sheet status
     * @params:$remSheetData = array(), $status = 2
     */
    function updateReimSheetSettings($assessment_id, $remSheetData = array(), $status = 2) {

        if ($status == 2)
            $this->db->delete("h_user_review_reim_sheet_status", array("assessment_id" => $assessment_id));
        if ($assessment_id && !empty($remSheetData)) {

            $sheetData = array();
            $sheetSql = "INSERT INTO h_user_review_reim_sheet_status (assessment_id,user_id,sheet_status,date) VALUES ";
            foreach ($remSheetData as $key => $val) {

                $sheetSql .= "(?,?,?,?),";
                $sheetData[] = $assessment_id;
                $sheetData[] = $key;
                $sheetData[] = $val;
                $sheetData[] = date('Y-m-d:h:i:s');
            }
            $sheetSql = trim($sheetSql, ",");
            return $this->db->query("$sheetSql", $sheetData);
        }
        return false;
    }

    function addReviewNotificationSettings($assessment_id, $notificationsArray) {

        if ($assessment_id && !empty($notificationsArray)) {
            $notificationsData = array();
            $notificationsSql = "INSERT INTO h_user_review_notification (notification_id,assessment_id,user_id,status,date,type) VALUES ";
            $i = 0;
            foreach ($notificationsArray as $key => $val) {

                foreach ($val as $k => $v) {
                    $notificationsSql .= "(?,?,?,?,?,?),";
                    $notificationsData[] = $v;
                    $notificationsData[] = $assessment_id;
                    $notificationsData[] = $key;
                    $notificationsData[] = 1;
                    $notificationsData[] = date('Y-m-d:h:i:s');
                    $notificationsData[] = 1;
                }
                $i++;
            }
            $notificationsSql = trim($notificationsSql, ",");
            if (!empty($notificationsData))
                return $this->db->query("$notificationsSql", $notificationsData);
            //else 
            return false;
        }
    }

     /*function to update assessment of school
     * @params:$assessment_id, $internal_assessor_id, $external_assessor_id, $facilitator_id, $diagnostic_id, $tier_id, $award_scheme_id, $aqs_round, $ext_team, $start_date = '', $end_date = '', $aqsdata_id = '', $facilitatorDataArray = array(), $notificationID = 0, $notificationsArray = array(), $notificationUsers = array(), $lang_id = DEFAULT_LANGUAGE, $review_criteria = "", $iscollebrative = 1
     */
    function updateSchoolAssessment($assessment_id, $internal_assessor_id, $external_assessor_id, $facilitator_id, $diagnostic_id, $tier_id, $award_scheme_id, $aqs_round, $ext_team, $start_date = '', $end_date = '', $aqsdata_id = '', $facilitatorDataArray = array(), $notificationID = 0, $notificationsArray = array(), $notificationUsers = array(), $lang_id = DEFAULT_LANGUAGE, $review_criteria = "", $iscollebrative = 1) {
        $review_criteria = trim($review_criteria);
        if (OFFLINE_STATUS == TRUE) {
            //start---> call function for creating unique id for creating assessment on 01-04-2016 by Mohit Kumar
            $uniqueID = $this->db->createUniqueID('updateSchoolAssessment');
            //end---> call function for creating unique id for creating assessment on 01-04-2016 by Mohit Kumar
        }
        if ($this->db->update("d_assessment", array('facilitator_id' => $facilitator_id, 'diagnostic_id' => $diagnostic_id, 'tier_id' => $tier_id, 'award_scheme_id' => $award_scheme_id, 'aqs_round' => $aqs_round, 'language_id' => $lang_id, 'review_criteria' => $review_criteria), array('assessment_id' => $assessment_id))) {

            if ($assessment_id && !empty($facilitatorDataArray)) {
                $this->db->delete("h_facilitator_user", array("assessment_id" => $assessment_id));
                $facilitatorSql = "INSERT INTO h_facilitator_user (assessment_id,client_id,sub_role_id,user_id) VALUES ";
                $i = 0;
                $facilitatorData = array();
                foreach ($facilitatorDataArray as $data) {

                    $facilitatorSql .= "(?,?,?,?),";
                    $facilitatorData[] = $assessment_id;
                    $facilitatorData[] = $data['client_id'];
                    $facilitatorData[] = $data['role_id'];
                    $facilitatorData[] = $data['user_id'];
                    $i++;
                }
                $facilitatorSql = trim($facilitatorSql, ",");
                $this->db->query("$facilitatorSql", $facilitatorData);
            }

            $this->db->update("d_AQS_data", array('school_aqs_pref_start_date' => $start_date, 'school_aqs_pref_end_date' => $end_date), array('id' => $aqsdata_id));
            if (OFFLINE_STATUS == TRUE) {
                //start---> save the history for insert school assessment data into d_assessment table on 01-04-2016 By Mohit Kumar
                $action_assessment_json = json_encode(array(
                    'diagnostic_id' => $diagnostic_id,
                    'tier_id' => $tier_id,
                    'award_scheme_id' => $award_scheme_id,
                    'create_date' => date("Y-m-d H:i:s")
                ));
                $this->db->saveHistoryData($assessment_id, 'd_assessment', $uniqueID, 'updateSchoolAssessment', $assessment_id, $assessment_id, $action_assessment_json, 0, date('Y-m-d H:i:s'));
                //end---> save the history for insert school assessment data into d_assessment table on 01-04-2016 By Mohit Kumar                    
            }
            $collaborativeExternalUser = array();
            $collaborativeExternalUserList = array();
            if ($iscollebrative == 1) {
                $collaborativeExternalUserList = $this->getExternalAssessorCollaborative($assessment_id);
                if (!empty($collaborativeExternalUserList)) {

                    $collaborativeExternalUser = array_column($collaborativeExternalUserList, 'user_id');
                }
            }
            $checkExternalUser = $this->getExternalAssessorUpdate(4, $assessment_id);
            if (count($checkExternalUser) < 1) {
                $this->db->insert("h_assessment_user", array("user_id" => $external_assessor_id, "assessment_id" => $assessment_id, "role" => 4));
            }
            if ($this->db->update("h_assessment_user", array("user_id" => $internal_assessor_id), array("assessment_id" => $assessment_id, "role" => 3)) && $this->db->update("h_assessment_user", array("user_id" => $external_assessor_id), array("assessment_id" => $assessment_id, "role" => 4))) {

                if (OFFLINE_STATUS == TRUE) {
                    //start---> save the history for insert school assessment data into d_assessment table on 10-03-2016 By Mohit Kumar
                    $action_assessment_user_json = json_encode(array(
                        'assessment_id' => $assessment_id
                    ));
                    $this->db->saveHistoryData($assessment_id, 'h_assessment_user', $uniqueID, 'updateSchoolAssessmentUserRemove', $assessment_id, $assessment_id, $action_assessment_user_json, 0, date('Y-m-d H:i:s'));
                    //end---> save the history for insert school assessment data into d_assessment table on 10-03-2016 By Mohit Kumar
                }

                if ($ext_team === 0)
                    return true;
                else {

                    $idsToBeDeleted = array($assessment_id);
                    $sql = 'DELETE FROM h_assessment_external_team WHERE assessment_id = ? ';
                    if ($iscollebrative == 1) {
                        $sql .= 'AND user_id IN (';
                        if (!empty($collaborativeExternalUserList) && !empty($ext_team)) {
                            foreach ($collaborativeExternalUserList as $list) {
                                if ($list['isFilled'] != 1 && !in_array($list['user_id'], array_flip($ext_team))) {
                                    $idsToBeDeleted[] = $list['user_id'];
                                    $sql .= '?,';
                                }
                            }
                            $sql = trim($sql, ",") . ")";
                        } else if (!empty($collaborativeExternalUserList) && empty($ext_team)) {
                            $sql = 'DELETE FROM h_assessment_external_team WHERE assessment_id = ? AND isFilled!=?';
                            $idsToBeDeleted[] = 1;
                        }
                        if (count($idsToBeDeleted) >= 2)
                            $this->db->query($sql, $idsToBeDeleted);
                    } else
                        $this->db->query($sql, $idsToBeDeleted);

                    if (OFFLINE_STATUS == TRUE) {
                        //start---> save the history for insert school assessment data into d_assessment table on 10-03-2016 By Mohit Kumar
                        $action_assessment_user_json = json_encode(array(
                            'assessment_id' => $assessment_id
                        ));
                        $this->db->saveHistoryData($assessment_id, 'h_assessment_external_team', $uniqueID, 'updateSchoolAssessmentExternalRemove', $assessment_id, $assessment_id, $action_assessment_user_json, 0, date('Y-m-d H:i:s'));
                    }
                    if (!empty($ext_team))
                        foreach ($ext_team as $member_user_id => $roleClient) {
                        
                            $roleClient = explode('_', $roleClient);
                            $externalClientId = $roleClient[0];
                            $roleId = $roleClient[1];
                            if(!empty($collaborativeExternalUser) && $iscollebrative == 1 && ( in_array($member_user_id,$collaborativeExternalUser))){
                                $this->db->update("h_assessment_external_team", array( "user_sub_role" => $roleId, "user_id" => $member_user_id),array("assessment_id"=>$assessment_id,"user_id" => $member_user_id));
                            }else{ 
                                

                                if (!$this->db->insert("h_assessment_external_team", array("assessment_id" => $assessment_id, "user_role" => 4, "user_sub_role" => $roleId, "user_id" => $member_user_id))) {

                                    return false;
                                } else {
                                    if (OFFLINE_STATUS == TRUE) {
                                        // get last insert id for external assessor team on 10-03-2016 by Mohit Kumar
                                        $etuid = $this->db->get_last_insert_id();
                                        //start---> save the history for insert external school assessment team data into h_assessment_user table on 10-03-2016 By Mohit Kumar
                                        $action_external_assessment_team_json = json_encode(array(
                                            'user_role' => 4,
                                            'assessment_id' => $assessment_id,
                                            'user_sub_role' => $roleId,
                                            'user_id' => $member_user_id,
                                            "external_client_id" => $externalClientId
                                        ));
                                        $this->db->saveHistoryData($etuid, 'h_assessment_external_team', $uniqueID, 'updateSchoolAssessmentExternalTeam', $member_user_id, $assessment_id, $action_external_assessment_team_json, 0, date('Y-m-d H:i:s'));
                                    }
                                }
                            }
                        }
                }
                return true;
            }
        }

        return false;
    }

     /*function to get collaborative assessors list
     * @params:$assessment_id
     */
    function getExternalAssessorCollaborative($assessment_id) {

        $res = $this->db->get_results("SELECT a.user_id,b.name,d.client_name,isFilled FROM h_assessment_external_team a left join d_user b on a.user_id=b.user_id left join d_assessment c on a.assessment_id=c.assessment_id left join d_client d on c.client_id=d.client_id WHERE  a.user_role = ? and a.assessment_id = ?", array(4, $assessment_id));
        return $res ? $res : array();
    }


     /*function to get assessment list
     * @params:$args = array(), $tap_admin_id = '', $user_id = '', $rid = '', $tap_admin_role, $ref = '', $ref_key = '', $is_guest = 0, $logged_user = 0
     */
    function getAssessmentList($args = array(), $tap_admin_id = '', $user_id = '', $rid = '', $tap_admin_role, $ref = '', $ref_key = '', $is_guest = 0, $logged_user = 0) {


        $args = $this->parse_arg($args, array("user_id" => 0, "sub_role_user_id" => 0, "client_id" => 0, "zone_id" => 0, "state_id" => 0, "network_id" => 0, "province_id" => 0, "client_name_like" => "", "diagnostic_id" => 0, "name_like" => "", "status" => "", "fdate_like" => "", "edate_like" => "", "max_rows" => 10, "page" => 1, "order_by" => "diagnostic_name", "order_type" => "asc"));

        $order_by = array("client_name" => "client_name", "name" => "name", "assessment_type" => "assessment_type_name", "create_date" => "create_date", "aqs_start_date" => "aqs_start_date");

        $schoolSqlArgs = array();

        $teacherSqlArgs = array();

        $schoolWhereClause = "";

        $teacherWhereClause = "";

        $teacherHavingClause = " having 1 ";

        $schoolHavingClause = " having 1 ";
        $pendingAssessmentCondition = "";
        if (isset($ref) && $ref == 2) {

            $pendingAssessmentCondition = " AND STR_TO_DATE(q.school_aqs_pref_start_date, '%d-%m-%Y') BETWEEN CURRENT_DATE() and DATE_ADD(CURRENT_DATE(), INTERVAL 7 DAY)  AND q.status is null ";
        }


        if ($args['client_name_like'] != "") {

            $schoolWhereClause .= "and CONCAT(c.client_name,IF(a.review_criteria IS NOT NULL,' ',''),IF(a.review_criteria IS NOT NULL,a.review_criteria,'')) like ? ";

            $teacherWhereClause .= "and c.client_name like ? ";

            $schoolSqlArgs[] = "%" . $args['client_name_like'] . "%";

            $teacherSqlArgs[] = "%" . $args['client_name_like'] . "%";
        }

        if ($args['diagnostic_id'] > 0) {

            $schoolWhereClause .= "and a.diagnostic_id = ? ";

            $teacherWhereClause .= "and a.diagnostic_id = ? ";

            $schoolSqlArgs[] = $args['diagnostic_id'];

            $teacherSqlArgs[] = $args['diagnostic_id'];
        }

        if ($args['fdate_like'] != '' && $args['edate_like'] == '') {
            $schoolWhereClause .= "and ( (STR_TO_DATE(q.school_aqs_pref_start_date, '%d-%m-%Y') Between ? And ?) || (STR_TO_DATE(q.school_aqs_pref_start_date, '%d-%m-%Y') Between ? And ?) ) ";
            $schoolSqlArgs[] = "" . $args['fdate_like'] . "";
            $schoolSqlArgs[] = "" . $args['fdate_like'] . "";
            $schoolSqlArgs[] = "" . $args['fdate_like'] . "";
            $schoolSqlArgs[] = "" . $args['fdate_like'] . "";

            $teacherWhereClause .= "and ( (STR_TO_DATE(q.school_aqs_pref_start_date, '%d-%m-%Y') Between ? And ?) || (STR_TO_DATE(q.school_aqs_pref_start_date, '%d-%m-%Y') Between ? And ?) ) ";
            $teacherSqlArgs[] = "" . $args['fdate_like'] . "";
            $teacherSqlArgs[] = "" . $args['fdate_like'] . "";
            $teacherSqlArgs[] = "" . $args['fdate_like'] . "";
            $teacherSqlArgs[] = "" . $args['fdate_like'] . "";
        } else if ($args['fdate_like'] == '' && $args['edate_like'] != '') {
            $schoolWhereClause .= "and ( (STR_TO_DATE(q.school_aqs_pref_end_date, '%d-%m-%Y') Between ? And ?) || (STR_TO_DATE(q.school_aqs_pref_end_date, '%d-%m-%Y') Between ? And ?) ) ";
            $schoolSqlArgs[] = "" . $args['edate_like'] . "";
            $schoolSqlArgs[] = "" . $args['edate_like'] . "";
            $schoolSqlArgs[] = "" . $args['edate_like'] . "";
            $schoolSqlArgs[] = "" . $args['edate_like'] . "";

            $teacherWhereClause .= "and ( (STR_TO_DATE(q.school_aqs_pref_end_date, '%d-%m-%Y') Between ? And ?) || (STR_TO_DATE(q.school_aqs_pref_end_date, '%d-%m-%Y') Between ? And ?) ) ";
            $teacherSqlArgs[] = "" . $args['edate_like'] . "";
            $teacherSqlArgs[] = "" . $args['edate_like'] . "";
            $teacherSqlArgs[] = "" . $args['edate_like'] . "";
            $teacherSqlArgs[] = "" . $args['edate_like'] . "";
        } else if ($args['fdate_like'] != '' && $args['edate_like'] != '') {
            $schoolWhereClause .= "and ( (STR_TO_DATE(q.school_aqs_pref_start_date, '%d-%m-%Y') Between ? And ?) && (STR_TO_DATE(q.school_aqs_pref_end_date, '%d-%m-%Y') Between ? And ?) ) ";
            $schoolSqlArgs[] = "" . $args['fdate_like'] . "";
            $schoolSqlArgs[] = "" . $args['edate_like'] . "";
            $schoolSqlArgs[] = "" . $args['fdate_like'] . "";
            $schoolSqlArgs[] = "" . $args['edate_like'] . "";

            $teacherWhereClause .= "and ( (STR_TO_DATE(q.school_aqs_pref_start_date, '%d-%m-%Y') Between ? And ?) && (STR_TO_DATE(q.school_aqs_pref_end_date, '%d-%m-%Y') Between ? And ?) ) ";
            $teacherSqlArgs[] = "" . $args['fdate_like'] . "";
            $teacherSqlArgs[] = "" . $args['edate_like'] . "";
            $teacherSqlArgs[] = "" . $args['fdate_like'] . "";
            $teacherSqlArgs[] = "" . $args['edate_like'] . "";
        }

        if ($args['name_like'] != "") {

            $schoolHavingClause .= " and group_concat(u.name order by b.role) like ?";

            $teacherHavingClause .= " and group_concat(u.name order by b.role) like ?";

            $schoolSqlArgs[] = "%" . $args['name_like'] . "%";

            $teacherSqlArgs[] = "%" . $args['name_like'] . "%";
        }
        if ($args['state_id'] > 0 && $args['user_id'] > 0) {

            $teacherHavingClause .= " and (cs.state_id = " . $args['state_id'] . " or user_ids  rlike concat('[[:<:]]'," . $args['user_id'] . ",'[[:>:]]')) ";
            $schoolHavingClause .= " and (cs.state_id = " . $args['state_id'] . " or user_ids  rlike concat('[[:<:]]'," . $args['user_id'] . ",'[[:>:]]') or externalTeam rlike concat('[[:<:]]'," . ($args['sub_role_user_id'] ? $args['sub_role_user_id'] : 0) . ",'[[:>:]]')) ";
        } else if ($args['state_id'] > 0) {

            $teacherHavingClause .= " and cs.state_id = " . $args['state_id'] . " ";

            $schoolHavingClause .= " and cs.state_id = " . $args['state_id'] . " ";
        }
        if ($args['zone_id'] > 0 && $args['user_id'] > 0) {

            $teacherHavingClause .= " and (cz.zone_id = " . $args['zone_id'] . " or user_ids  rlike concat('[[:<:]]'," . $args['user_id'] . ",'[[:>:]]')) ";
            $schoolHavingClause .= " and (cz.zone_id = " . $args['zone_id'] . " or user_ids  rlike concat('[[:<:]]'," . $args['user_id'] . ",'[[:>:]]') or externalTeam rlike concat('[[:<:]]'," . ($args['sub_role_user_id'] ? $args['sub_role_user_id'] : 0) . ",'[[:>:]]')) ";
        } else if ($args['zone_id'] > 0) {

            $teacherHavingClause .= " and cz.zone_id = " . $args['zone_id'] . " ";

            $schoolHavingClause .= " and cz.zone_id = " . $args['zone_id'] . " ";
        }
        if ($args['network_id'] > 0 && $args['user_id'] > 0) {

            $teacherHavingClause .= " and (cn.network_id = " . $args['network_id'] . " or user_ids  rlike concat('[[:<:]]'," . $args['user_id'] . ",'[[:>:]]')) ";
            $schoolHavingClause .= " and (cn.network_id = " . $args['network_id'] . " or user_ids  rlike concat('[[:<:]]'," . $args['user_id'] . ",'[[:>:]]') or externalTeam rlike concat('[[:<:]]'," . ($args['sub_role_user_id'] ? $args['sub_role_user_id'] : 0) . ",'[[:>:]]')) ";
        } else if ($args['client_id'] > 0 && $args['user_id'] > 0) {

            $teacherHavingClause .= " and (client_id = " . $args['client_id'] . " or user_ids  rlike concat('[[:<:]]'," . $args['user_id'] . ",'[[:>:]]')) ";

            $schoolHavingClause .= " and (client_id = " . $args['client_id'] . " or user_ids  rlike concat('[[:<:]]'," . $args['user_id'] . ",'[[:>:]]') or externalTeam rlike concat('[[:<:]]'," . ($args['sub_role_user_id'] ? $args['sub_role_user_id'] : 0) . ",'[[:>:]]')) ";
        } else if ($args['network_id'] > 0) {

            $teacherHavingClause .= " and cn.network_id = " . $args['network_id'] . " ";

            $schoolHavingClause .= " and cn.network_id = " . $args['network_id'] . " ";
        } else if ($args['client_id'] > 0) {

            $teacherHavingClause .= " and client_id = " . $args['client_id'] . " ";

            $schoolHavingClause .= " and client_id = " . $args['client_id'] . " ";
        } else if ($args['user_id'] > 0) {

            $teacherHavingClause .= " and user_ids  rlike concat('[[:<:]]'," . $args['user_id'] . ",'[[:>:]]') ";

            $schoolHavingClause .= " and user_ids  rlike concat('[[:<:]]'," . $args['user_id'] . ",'[[:>:]]') or externalTeam rlike concat('[[:<:]]'," . ($args['sub_role_user_id'] ? $args['sub_role_user_id'] : 0) . ",'[[:>:]]') or leader_ids  rlike concat('[[:<:]]'," . $args['user_id'] . ",'[[:>:]]') ";
        }
        $kpaCond = '';
        $kpaCond = 'left join d_assessment_kpa kp on kp.assessment_id=a.assessment_id ';
        if ($args['province_id'] > 0) {

            $teacherWhereClause .= " and cp.province_id = " . $args['province_id'] . " ";

            $schoolWhereClause .= " and cp.province_id = " . $args['province_id'] . " ";
        }
        //type of review
        if ($args['assessment_type_id'] != "") {
            switch ($args['assessment_type_id']) {
                case 'sch' :
                case 1 :
                    $schoolWhereClause .= " and a.d_sub_assessment_type_id!=1 and d.assessment_type_id=1";
                    $teacherWhereClause .= " and 1=0";
                    break;
                case 'schs' :
                    $schoolWhereClause .= " and a.d_sub_assessment_type_id=1 and d.assessment_type_id=1";
                    $teacherWhereClause .= " and 1=0";
                    break;
                case 'tchr' :
                case 2 :
                    $schoolWhereClause .= " and 1=0";
                    $teacherWhereClause .= " and dt.assessment_type_id=2 ";
                    break;
                case 'stu' :
                case 4 :
                    $schoolWhereClause .= " and 1=0";
                    $teacherWhereClause .= " and dt.assessment_type_id=4 ";
                    break;
                case 'col' :
                case 5 :
                    $schoolWhereClause .= " and a.d_sub_assessment_type_id!=1 and d.assessment_type_id=5";
                    $teacherWhereClause .= " and 1=0";
                    break;
            }
        }
        // make condition for getting only school reviews for tap admin on 18-05-2016 by Mohit Kumar
        if ($tap_admin_id == 8) {
            $tap_condition = " and d.assessment_type_id='1' and d_sub_assessment_type_id!='1' ";
        } else {
            $tap_condition = '';
        }
        if ($user_id != '' && $rid != '') {
            $getExternalReviewsId = $this->getAssessmentIDsNew($user_id, $rid);

            if ($getExternalReviewsId['assessment_id'] != '') {
                $externalAssessorCondition = " and a.assessment_id IN (" . $getExternalReviewsId['assessment_id'] . ") ";
            } else {
                $externalAssessorCondition = '';
            }
        } else {
            $externalAssessorCondition = '';
        }
        $condition = '';

        if (isset($ref) && $ref == 1 && $ref_key != '') {
            $SQL1 = "Select alert_ids as assessment_id from h_alert_relation where login_user_role='" . $tap_admin_role . "' and type='REVIEW'";
            $assessment_id = $this->db->get_row($SQL1);
            if (!empty($assessment_id) && $assessment_id['assessment_id'] != '') {
                $condition = " and  a.assessment_id In (" . $assessment_id['assessment_id'] . ")  ";
            }
        }

        $langCond = '';
        if ($this->lang != 'all') {
            $langCond = "and a.language_id=" . $this->lang;
        }
        $guestCond = $is_guest ? " && c.is_guest=1 " : " && c.is_guest!=1 ";
        $isActive_Inactive_Assessment = " && a.isAssessmentActive=1 ";
        $isActive_Inactive_Group_Assessment = " && dt.isGroupAssessmentActive=1 ";


        $sql = "
		select SQL_CALC_FOUND_ROWS z.* 

		from (

			(

				SELECT  ss.status as profile_status,ss.perComplete,a.collaborativepercntg as avg,b.isFilled,a.iscollebrative,d.diagnostic_id,dt.group_assessment_id,dt.admin_user_id,dt.student_round as assessment_round,q.is_uploaded,q.percComplete as aqspercent,STR_TO_DATE(q.school_aqs_pref_start_date, '%d-%m-%Y') as aqs_start_date,STR_TO_DATE(q.school_aqs_pref_end_date, '%d-%m-%Y') as aqs_end_date, 0 as assessment_id, dt.assessment_type_id, dt.client_id,dt.creation_date as create_date ,c.client_name ,t.assessment_type_name, cn.network_id,cs.state_id,cz.zone_id, group_concat(b.isFilled order by b.role,a.assessment_id) as  statuses, group_concat(distinct b.role order by b.role) as roles, group_concat(b.percComplete order by b.role,a.assessment_id) as percCompletes, group_concat(if(b.ratingInputDate is null,'',date(b.ratingInputDate)) order by b.role,a.assessment_id) as ratingInputDates, group_concat(b.user_id order by b.role,a.assessment_id) as user_ids, group_concat(u.name order by b.role,a.assessment_id) as user_names, q.status as aqs_status,group_concat(concat(r.report_id,'|',r.isPublished,'|',r.publishDate)) as report_data,count(distinct s.assessment_id) as assessments_count, CASE WHEN dt.assessment_type_id = 2 THEN group_concat(if(td.value is null,'',td.value) order by b.role,a.assessment_id)  WHEN dt.assessment_type_id = 4 THEN  group_concat(if(sd.value is null,'',sd.value) order by b.role,a.assessment_id) END as teacherInfoStatuses, ifnull(a.d_sub_assessment_type_id,0) as 'subAssessmentType',

				aqsdata_id,p.status as 'post_rev_status',p.percComplete as 'postreviewpercent',a.is_approved as 'isApproved', hlt.translation_text as diagnosticName, '' as externalTeam,'' as externalPercntage,'' as extFilled,'' as kpa,'' as leader_ids,'' as kpa_user,a.is_offline as offlineStatus

				FROM d_group_assessment dt

				left join h_assessment_ass_group s on s.group_assessment_id = dt.group_assessment_id

				left join `d_assessment` a  on a.assessment_id = s.assessment_id

				left join `h_assessment_user` b on a.assessment_id=b.assessment_id

				left join d_teacher_data td on td.teacher_id=b.user_id and b.role=3 and td.assessment_id=b.assessment_id and td.attr_id=11
                                
                                left join d_student_data sd on sd.student_id=b.user_id and b.role=3 and sd.assessment_id=b.assessment_id and sd.attr_id=49

				left join `d_client` c on c.client_id=dt.client_id

				left join `d_diagnostic` d on d.diagnostic_id=a.diagnostic_id
                                left join h_lang_translation hlt on d.equivalence_id = hlt.equivalence_id && hlt.language_id=a.language_id

				left join `d_assessment_type` t on dt.assessment_type_id=t.assessment_type_id

				left join d_user u on u.user_id=b.user_id

				left join h_client_state cs on cs.client_id=c.client_id
				left join h_client_zone cz on cz.client_id=c.client_id
				left join h_client_network cn on cn.client_id=c.client_id

				left join d_AQS_data q on q.id=a.aqsdata_id
                                left join h_school_profile_status ss on ss.assessment_id=a.assessment_id

				left join h_assessment_report r on r.assessment_id=a.assessment_id
                                $kpaCond
				left join d_post_review p on p.assessment_id=a.assessment_id
                                left join h_client_province cp on cp.client_id = c.client_id	
                                left join h_province_network pn on pn.network_id = cn.network_id and cp.province_id = pn.province_id	
                                
                                where 1=1  " . $isActive_Inactive_Group_Assessment . " " . $guestCond . " " . $langCond . " " . $tap_condition . $externalAssessorCondition . $condition . " " . $teacherWhereClause . $pendingAssessmentCondition . "


				
                                group by dt.group_assessment_id

				$teacherHavingClause

			)

			union

			(


				SELECT ss.status as profile_status,ss.perComplete,a.collaborativepercntg as avg,b.isFilled,a.iscollebrative,d.diagnostic_id,0 as group_assessment_id,0 as admin_user_id,a.aqs_round as assessment_round,q.is_uploaded,q.percComplete as aqspercent,STR_TO_DATE(q.school_aqs_pref_start_date, '%d-%m-%Y') as aqs_start_date,STR_TO_DATE(q.school_aqs_pref_end_date, '%d-%m-%Y') as aqs_end_date,a.assessment_id, d.assessment_type_id, a.client_id,a.create_date as create_date ,CONCAT(c.client_name,IF((a.review_criteria IS NOT NULL && a.review_criteria!=''),' - ',''),IF((a.review_criteria IS NOT NULL  && a.review_criteria!=''),a.review_criteria,'')) ,t.assessment_type_name, cs.state_id,cz.zone_id,cn.network_id, group_concat(b.isFilled order by b.role) as  statuses, group_concat(b.role order by b.role) as roles, group_concat(b.percComplete order by b.role) as percCompletes, group_concat(if(b.ratingInputDate is null,'',date(b.ratingInputDate)) order by b.role) as ratingInputDates, group_concat(b.user_id order by b.role) as user_ids, group_concat(u.name order by b.role) as user_names, q.status as aqs_status,

				group_concat(concat(r.report_id,'|',r.isPublished,'|',r.publishDate)) as report_data, 1 as assessments_count,'' as teacherInfoStatuses, ifnull(a.d_sub_assessment_type_id,0) as 'subAssessmentType'				

                ,aqsdata_id,p.status as 'post_rev_status',p.percComplete as 'postreviewpercent',

                a.is_approved as 'isApproved',

                 hlt.translation_text as diagnosticName ,group_concat(distinct ext.user_id) as externalTeam,ext.percComplete as externalPercntage,ext.isFilled as extFilled,group_concat(distinct kp.kpa_instance_id) as kpa,group_concat(haa1.leader) as leader_ids,group_concat(distinct kp.user_id) as kpa_user,a.is_offline as offlineStatus

					FROM `d_assessment` a 

					inner join `h_assessment_user` b on a.assessment_id=b.assessment_id 

					inner join `d_client` c on c.client_id=a.client_id

					inner join `d_diagnostic` d on d.diagnostic_id=a.diagnostic_id

					inner join `d_assessment_type` t on d.assessment_type_id=t.assessment_type_id

					inner join d_user u on u.user_id=b.user_id
                                        inner join h_lang_translation hlt on d.equivalence_id = hlt.equivalence_id && hlt.language_id=a.language_id

					left join h_client_state cs on cs.client_id=c.client_id
					left join h_client_zone cz on cz.client_id=c.client_id
					left join h_client_network cn on cn.client_id=c.client_id

					left join d_AQS_data q on q.id=a.aqsdata_id

					left join h_assessment_report r on r.assessment_id=a.assessment_id					

					left join d_post_review p on p.assessment_id=a.assessment_id
                                        left join h_client_province cp on cp.client_id = c.client_id	
                                        left join h_province_network pn on pn.network_id = cn.network_id and cp.province_id = pn.province_id
                                        left join h_assessment_external_team ext on ext.assessment_id = a.assessment_id and ext.user_id = " . (!empty($args['sub_role_user_id']) ? $args['sub_role_user_id'] : 0) . "					


                                        left join assessor_key_notes akn on a.assessment_id=akn.assessment_id && akn.type='recommendation'
                                        
                                        left join h_assessor_action1 haa1 on akn.id=haa1.assessor_key_notes_id
                                         left join h_school_profile_status ss on ss.assessment_id=a.assessment_id

                                        $kpaCond
                                        where (d.assessment_type_id=1 || d.assessment_type_id=5) " . $isActive_Inactive_Assessment . " " . $guestCond . " " . $langCond . "  " . $tap_condition . $externalAssessorCondition . $condition . " " . $schoolWhereClause . $pendingAssessmentCondition . "
					
                                        group by a.assessment_id

				$schoolHavingClause

			)

		) as z";


        $sql .= " order by " . (isset($order_by[$args["order_by"]]) ? $order_by[$args["order_by"]] : "create_date") . ($args["order_type"] == "desc" ? " desc " : " asc ") . $this->limit_query($args['max_rows'], $args['page']);

        $res = $this->db->get_results($sql, array_merge($teacherSqlArgs, $schoolSqlArgs));
        $this->setPageCount($args['max_rows']);
        //get external team roles
        $roleRes = array();
        if (!empty($logged_user) && !in_array($tap_admin_role, array(1, 2, 5, 8))) {
            $roleSql = "SELECT ex.assessment_id,ex.user_sub_role,ex.user_id FROM h_assessment_external_team ex "
                    . " INNER JOIN d_assessment d ON d.assessment_id = ex.assessment_id "
                    . "WHERE ex.user_id = '$logged_user'";
            $roleRes = $this->db->get_results($roleSql);
            $roleRes = array_column($roleRes, 'user_sub_role', 'assessment_id');
        }

        $lnt = count($res);

        for ($j = 0; $j < $lnt; $j++) {

            if ($res[$j]['assessment_type_id'] == 1 || $res[$j]['assessment_type_id'] == 5) {

                $res[$j]['report_data'] = $res[$j]['report_data'] == "" ? array() : explode(",", $res[$j]['report_data']);

                $rdc = count($res[$j]['report_data']);

                for ($k = 0; $k < $rdc; $k++) {

                    $tm = explode("|", $res[$j]['report_data'][$k]);

                    $res[$j]['report_data'][$k] = array("report_id" => $tm[0], "isPublished" => $tm[1], "publishDate" => $tm[2]);
                }
                $roles = $res[$j]['roles'] != "" ? explode(',', $res[$j]['roles']) : array();
                $statuses = $res[$j]['statuses'] != "" ? explode(',', $res[$j]['statuses']) : array();
                $percCompletes = $res[$j]['percCompletes'] != "" ? explode(',', $res[$j]['percCompletes']) : array();
                $ratingInputDates = $res[$j]['ratingInputDates'] != "" ? explode(',', $res[$j]['ratingInputDates']) : array();
                $user_ids = $res[$j]['user_ids'] != "" ? explode(',', $res[$j]['user_ids']) : array();
                $user_names = $res[$j]['user_names'] != "" ? explode(',', $res[$j]['user_names']) : array();
                $ln = count($roles);
                for ($i = 0; $i < $ln; $i++) {
                    $res[$j]['data_by_role'][$roles[$i]] = array("status" => $statuses[$i], "percComplete" => $percCompletes[$i], "ratingInputDate" => empty($ratingInputDates[$i]) ? '' : $ratingInputDates[$i], "user_id" => $user_ids[$i], "user_name" => $user_names[$i]);
                    if (!empty($roleRes) && isset($roleRes[$res[$j]['assessment_id']])) {
                        $res[$j]['data_by_role'][$roleRes[$res[$j]['assessment_id']]] = array("status" => $statuses[$i], "percComplete" => $percCompletes[$i], "ratingInputDate" => empty($ratingInputDates[$i]) ? '' : $ratingInputDates[$i], "user_id" => $user_ids[$i], "user_name" => $user_names[$i]);
                    }
                }
            } else {
                $roles = $res[$j]['roles'] != "" ? explode(',', $res[$j]['roles']) : array();
                $percCompletes = $res[$j]['percCompletes'] != "" ? explode(',', $res[$j]['percCompletes']) : array();
                $statuses = $res[$j]['statuses'] != "" ? explode(',', $res[$j]['statuses']) : array();
                $sz = count($percCompletes);
                $allStatuses = array(array(), array());
                $user_ids = $res[$j]['user_ids'] != "" ? explode(',', $res[$j]['user_ids']) : array();
                $user_names = $res[$j]['user_names'] != "" ? explode(',', $res[$j]['user_names']) : array();
                if ($sz && $sz % 2 == 0) {
                    $sz = $sz / 2;
                    $percCompletes = array_chunk($percCompletes, $sz);
                    $percCompletes = array(round(array_sum($percCompletes[0]) / $sz, 2), round(array_sum($percCompletes[1]) / $sz, 2));
                    $allStatuses = array_chunk($statuses, $sz);

                    $statuses = array(in_array(0, $allStatuses[0]) ? 0 : 1, in_array(0, $allStatuses[1]) ? 0 : 1);

                    $allUsers = array_chunk($user_ids, $sz);

                    $user_ids = array($allUsers[0], $allUsers[1]);
                }
                $ln = count($roles);
                for ($i = 0; $i < $ln; $i++)
                    $res[$j]['data_by_role'][$roles[$i]] = array("status" => $statuses[$i], "percComplete" => $percCompletes[$i], "allStatuses" => $allStatuses[$i], "user_ids" => $user_ids[$i]);

                $res[$j]['teacherInfoStatuses'] = explode(",", $res[$j]['teacherInfoStatuses']);
            }
        }
        return $res;
    }

    /*function to get assessment list
     * @params:$group_assessment_id, $user_id = 0, $lang_id = DEFAULT_LANGUAGE
     */
    function getAssessmentsInGroupAssessment($group_assessment_id, $user_id = 0, $lang_id = DEFAULT_LANGUAGE) {
        $sql = "SELECT a.iscollebrative,ag.group_assessment_id,0 as admin_user_id,a.assessment_id, d.assessment_type_id, a.client_id,a.create_date as create_date ,c.client_name ,t.assessment_type_name, cn.network_id, group_concat(b.isFilled order by b.role) as  statuses, group_concat(b.role order by b.role) as roles, group_concat(b.percComplete order by b.role) as percCompletes, group_concat(if(b.ratingInputDate is null,'',date(b.ratingInputDate)) order by b.role) as ratingInputDates, group_concat(b.user_id order by b.role) as user_ids, group_concat(CONCAT(u.name,IF(sd_1.value IS NOT NULL ,' (',''),IFNULL(sd_1.value, ''),IF(sd_1.value IS NOT NULL ,')','')) order by b.role) as user_names, q.status as aqs_status, CASE WHEN d.assessment_type_id = 2 THEN if(sum(td.value)>0,1,0) WHEN d.assessment_type_id = 4 THEN if(sum(sd.value)>0,1,0) END as isTchrInfoFilled,

			group_concat(concat(r.report_id,'|',r.isPublished,'|',r.publishDate)) as report_data, 1 as assessments_count,d.diagnostic_id,hlt.translation_text as diagnosticName

			FROM h_assessment_ass_group ag

			inner join `d_assessment` a on ag.assessment_id=a.assessment_id

			inner join `h_assessment_user` b on a.assessment_id=b.assessment_id 

			inner join `d_client` c on c.client_id=a.client_id

			inner join `d_diagnostic` d on d.diagnostic_id=a.diagnostic_id
                        
                        inner join h_lang_translation hlt on d.equivalence_id = hlt.equivalence_id

			inner join `d_assessment_type` t on d.assessment_type_id=t.assessment_type_id

			inner join d_user u on u.user_id=b.user_id

			left join h_client_network cn on cn.client_id=c.client_id

			left join d_AQS_data q on q.id=a.aqsdata_id

			left join h_assessment_report r on r.assessment_id=a.assessment_id
                      
                        left join d_teacher_data td on td.teacher_id=b.user_id and b.role=3 and td.assessment_id=a.assessment_id and td.attr_id=11
                       
                        left join d_student_data sd on sd.student_id=b.user_id and b.role=3 and sd.assessment_id=a.assessment_id and sd.attr_id=49
                        
                        left join d_student_data sd_1 on sd_1.student_id=b.user_id and b.role=3 and sd_1.assessment_id=a.assessment_id and sd_1.attr_id=4

			where d.assessment_type_id>1 and ag.group_assessment_id=? and hlt.language_id=?

			group by a.assessment_id";

        $sqlArgs = array($group_assessment_id, $lang_id);

        if ($user_id > 0) {

            $sql .= " having user_ids  rlike concat('[[:<:]]',?,'[[:>:]]')";

            $sqlArgs[] = $user_id;
        }

        $res = $this->db->get_results($sql, $sqlArgs);

        $lnt = count($res);

        for ($j = 0; $j < $lnt; $j++) {

            $res[$j]['report_data'] = $res[$j]['report_data'] == "" ? array() : explode(",", $res[$j]['report_data']);

            $rdc = count($res[$j]['report_data']);

            for ($k = 0; $k < $rdc; $k++) {

                $tm = explode("|", $res[$j]['report_data'][$k]);

                $res[$j]['report_data'][$k] = array("report_id" => $tm[0], "isPublished" => $tm[1], "publishDate" => $tm[2]);
            }
            $roles = $res[$j]['roles'] != "" ? explode(',', $res[$j]['roles']) : array();
            $statuses = $res[$j]['statuses'] != "" ? explode(',', $res[$j]['statuses']) : array();
            $percCompletes = $res[$j]['percCompletes'] != "" ? explode(',', $res[$j]['percCompletes']) : array();
            $ratingInputDates = $res[$j]['ratingInputDates'] != "" ? explode(',', $res[$j]['ratingInputDates']) : array();
            $user_ids = $res[$j]['user_ids'] != "" ? explode(',', $res[$j]['user_ids']) : array();
            $user_names = $res[$j]['user_names'] != "" ? explode(',', $res[$j]['user_names']) : array();
            $ln = count($roles);
            for ($i = 0; $i < $ln; $i++)
                $res[$j]['data_by_role'][$roles[$i]] = array("status" => $statuses[$i], "percComplete" => $percCompletes[$i], "ratingInputDate" => empty($ratingInputDates[$i]) ? '' : $ratingInputDates[$i], "user_id" => $user_ids[$i], "user_name" => $user_names[$i]);
        }

        return $res;
    }

    /*function to get assessment list
     * @params:$group_assessment_id, $external_team = 0
     */
    function getSchoolAssessment($assessment_id, $external_team = 0) {



        if ($external_team == 1) {

            $sql = "SELECT a.iscollebrative,a.review_criteria,a.language_id,a.aqsdata_id,aq.school_aqs_pref_start_date,aq.school_aqs_pref_end_date,a.assessment_id, a.facilitator_id, u3.client_id as f_client_id, group_concat(distinct concat(u2.client_id,'_',et.user_id,'_',et.user_sub_role,'_',et.isFilled))  as subroles,a.tier_id , a.award_scheme_id, a.aqs_round ,d.assessment_type_id,d.diagnostic_id, a.client_id,a.create_date as create_date ,

c.client_name ,t.assessment_type_name, group_concat( b.isFilled order by b.role) as statues,

group_concat(distinct b.user_id order by b.role) as user_ids,group_concat(distinct ifnull(b.percComplete,'0') order by b.role) as percCompletes,(SELECT tc.client_id FROM 

h_assessment_user hc, d_user tc where tc.user_id=hc.user_id and hc.assessment_id=a.assessment_id and hc.role=4) as external_client, group_concat(distinct u.name order by b.role) as user_names,

				 1 as assessments_count

					FROM `d_assessment` a 

                    left join d_award_scheme das on a.award_scheme_id = das.award_scheme_id

					inner join `h_assessment_user` b on a.assessment_id=b.assessment_id 

					inner join `d_client` c on c.client_id=a.client_id

					inner join `d_diagnostic` d on d.diagnostic_id=a.diagnostic_id

					inner join `d_assessment_type` t on d.assessment_type_id=t.assessment_type_id

					inner join d_user u on u.user_id=b.user_id
                                        
                                        left join d_user u3 on u3.user_id=a.facilitator_id
                                        left join d_AQS_data aq  on aq.id=a.aqsdata_id

                    left join h_assessment_external_team et on et.assessment_id=a.assessment_id
                    left join d_user u2 on u2.user_id=et.user_id

where a.assessment_id=?;";
        } else {

            $sql = "SELECT a.iscollebrative,a.review_criteria,a.language_id ,a.aqsdata_id,aq.school_aqs_pref_start_date,aq.school_aqs_pref_end_date, a.assessment_id, a.facilitator_id, a.tier_id , a.award_scheme_id, a.aqs_round ,d.assessment_type_id,d.diagnostic_id, a.client_id,a.create_date as create_date ,

c.client_name ,t.assessment_type_name,group_concat( b.isFilled order by b.role) as statues,

group_concat(b.user_id order by b.role) as user_ids,group_concat(b.percComplete order by b.role) as percCompletes,(SELECT tc.client_id FROM 

h_assessment_user hc, d_user tc where tc.user_id=hc.user_id and hc.assessment_id=a.assessment_id and hc.role=4) as external_client, group_concat(u.name order by b.role) as user_names,

				 1 as assessments_count

					FROM `d_assessment` a 


                    left join d_award_scheme das on a.award_scheme_id = das.award_scheme_id

					inner join `h_assessment_user` b on a.assessment_id=b.assessment_id 

					inner join `d_client` c on c.client_id=a.client_id

					inner join `d_diagnostic` d on d.diagnostic_id=a.diagnostic_id

					inner join `d_assessment_type` t on d.assessment_type_id=t.assessment_type_id

					inner join d_user u on u.user_id=b.user_id	
                                        left join d_AQS_data aq  on aq.id=a.aqsdata_id

where a.assessment_id= ?;";
        }



        $res = $this->db->get_row($sql, array($assessment_id));

        return $res ? $res : array();
    }
    
    /*function to get all sub role
     * @params:$roleid
     */
    function getReviewerSubRoles($roleid) {

        $sql = "Select  sub_role_id,sub_role_name from d_user_sub_role us

				where user_role_id=? order by sub_role_order";
        $res = $this->db->get_results($sql, array($roleid));

        return $res ? $res : array();
    }

     /*function for getting review count by role for a user
     * @params:$email
     */
    public function getReviewCountByRole($email = '') {
        $condition = '';
        $condition1 = '';
        if ($email != '') {
            $condition = "AND t2.email = '" . $email . "'";
        }
        $SQL = "SELECT sub_role_name,ifnull(num,0) num,sub_role_id from (SELECT sub_role_name,num,sub_role_order,sub_role_id FROM d_user_sub_role a left JOIN "
                . "(SELECT COUNT(distinct t1.assessment_id) num,t1.user_sub_role from d_assessment u INNER JOIN d_diagnostic d on u.diagnostic_id = d.diagnostic_id  INNER JOIN h_assessment_external_team t1 ON t1.assessment_id = u.assessment_id LEFT JOIN d_user t2 on "
                . " t1.user_id = t2.user_id WHERE 1=1 " . $condition . " group by t1.user_sub_role ) b on a.sub_role_id=b.user_sub_role where a.sub_role_id!=1 AND a.user_role_id=4
                        UNION
			SELECT sub_role_name,num,sub_role_order,sub_role_id FROM d_user_sub_role a left JOIN 
                        (SELECT COUNT(t1.assessment_id) num,1 as user_sub_role FROM h_assessment_user t1 
                        Left Join d_user t2 On (t1.user_id=t2.user_id) Left Join d_assessment t3 On
                        (t3.assessment_id=t1.assessment_id) Left Join d_diagnostic t4 on (t3.diagnostic_id=t4.diagnostic_id) 
                        WHERE t1.role = '4' " . $condition . " and t4.assessment_type_id='1' and t3.d_sub_assessment_type_id!='1') b 
                        on a.sub_role_id=b.user_sub_role where a.sub_role_id=1 ) a	order by sub_role_order    	
                ";
        $result = $this->db->get_results($SQL);
        return $result;
    }

    public function getReviewCountByRoleNew($email = '') {
        $condition = '';
        $condition1 = '';
        if ($email != '') {
            $condition = "AND t2.email = '" . $email . "'";
        }
        $SQL = "select assessment_type_id,d_sub_assessment_type_id,sub_role_name,num,sub_role_order,sub_role_id,group_assessment_id,sum(school) as `School Review`,sum(teacher) as `Teacher Review`,sum(student)  as `Student Review`,sum(college) as `College Review` from (
                            (select *,0 as group_assessment_id,sum(if(assessment_type_id=1,num,0)) as school,sum(if(assessment_type_id=2,1,0)) as teacher ,sum(if(assessment_type_id=4,1,0)) as student,sum(if(assessment_type_id=5,num,0)) as college  
                            from (SELECT b.assessment_type_id ,0 as d_sub_assessment_type_id,sub_role_name,num,sub_role_order,sub_role_id 
                            FROM d_user_sub_role a 
                            left JOIN (SELECT dd.assessment_type_id,COUNT(distinct t1.assessment_id) num,t1.user_sub_role from d_assessment u 
                            INNER JOIN d_diagnostic d on u.diagnostic_id = d.diagnostic_id 
                            INNER JOIN h_assessment_external_team t1 ON t1.assessment_id = u.assessment_id 
                            LEFT JOIN d_user t2 on t1.user_id = t2.user_id 
                            left join d_diagnostic dd on u.diagnostic_id=dd.diagnostic_id 
                            WHERE 1=1 " . $condition . " && u.isAssessmentActive=1
                            group by assessment_type_id,t1.user_sub_role ) b on a.sub_role_id=b.user_sub_role where a.sub_role_id!=1 AND a.user_role_id=4
                            ) xyz1 group by xyz1.assessment_type_id,sub_role_id)
                            UNION
                           (select *,sum(if(assessment_type_id=1,num,0)) as school,sum(if(assessment_type_id=2,1,0)) as teacher ,sum(if(assessment_type_id=4,1,0)) as student,sum(if(assessment_type_id=5,num,0)) as student 
                           from (SELECT b.assessment_type_id,b.d_sub_assessment_type_id,sub_role_name,num,sub_role_order,sub_role_id,group_assessment_id 
                           FROM d_user_sub_role a 
                           left JOIN (SELECT t4.assessment_type_id,t3.d_sub_assessment_type_id,count(t1.assessment_id) num,1 as user_sub_role,dga.group_assessment_id 
                           FROM h_assessment_user t1 Left Join d_user t2 On (t1.user_id=t2.user_id) 
                           Left Join d_assessment t3 On (t3.assessment_id=t1.assessment_id) 
                           Left Join d_diagnostic t4 on (t3.diagnostic_id=t4.diagnostic_id) 
                           LEFT JOIN h_assessment_ass_group hag on hag.assessment_id=t3.assessment_id
                           LEFT JOIN d_group_assessment dga on hag.group_assessment_id=dga.group_assessment_id
                           WHERE (t1.role = '4' " . $condition . " ) && (((t4.assessment_type_id=1 || t4.assessment_type_id=5 ) && t3.isAssessmentActive=1 && dga.group_assessment_id IS NULL) || ((t4.assessment_type_id=2 || t4.assessment_type_id=4 ) && dga.isGroupAssessmentActive=1 && dga.group_assessment_id IS NOT NULL))
                           group by assessment_type_id,dga.group_assessment_id) b on a.sub_role_id=b.user_sub_role where a.sub_role_id=1) xyz group by xyz.assessment_type_id
                           )) xxx group by sub_role_id order by sub_role_order";
        $result = $this->db->get_results($SQL);
        return $result;
    }

    // function for getting group concat of assessment ids on 25-05-2016 by Mohit Kumar
    public function getAssessmentIDs($user_id, $rid) {
        if ($rid == 1) {
            $SQL = "SELECT group_concat(distinct t1.assessment_id) as assessment_id FROM h_assessment_user t1 Left Join d_user t2 On 
                    (t1.user_id=t2.user_id) Left Join d_assessment t3 On (t3.assessment_id=t1.assessment_id) Left Join d_diagnostic t4 on 
                    (t3.diagnostic_id=t4.diagnostic_id) WHERE t1.role = '4' AND t2.user_id = '" . $user_id . "' 
                    and t4.assessment_type_id='1' and t3.d_sub_assessment_type_id!='1'";
        } else {
            $SQL = "SELECT group_concat(distinct t1.assessment_id) as assessment_id  "
                    . "FROM d_assessment u INNER JOIN d_diagnostic d on u.diagnostic_id = d.diagnostic_id "
                    . "INNER JOIN h_assessment_external_team t1 on t1.assessment_id = u.assessment_id "
                    . "Left Join d_user t2 On (t1.user_id=t2.user_id)  WHERE  t1.user_sub_role = '" . $rid . "' "
                    . "AND t2.user_id = '" . $user_id . "' AND d.assessment_type_id = 1;";
        }
        $result = $this->db->get_row($SQL);
        return $result;
    }

    public function getAssessmentIDsNew($user_id, $rid) {
        if ($rid == 1) {
            $SQL = "SELECT group_concat(distinct t1.assessment_id) as assessment_id FROM h_assessment_user t1 Left Join d_user t2 On 
                    (t1.user_id=t2.user_id) Left Join d_assessment t3 On (t3.assessment_id=t1.assessment_id) Left Join d_diagnostic t4 on 
                    (t3.diagnostic_id=t4.diagnostic_id) WHERE t1.role = '4' AND t2.user_id = '" . $user_id . "' 
                    and (t4.assessment_type_id='1' || t4.assessment_type_id='2' || t4.assessment_type_id='4' ||  t4.assessment_type_id='5') and t3.d_sub_assessment_type_id!='1'";
        } else {
            $SQL = "SELECT group_concat(distinct t1.assessment_id) as assessment_id  "
                    . "FROM d_assessment u INNER JOIN d_diagnostic d on u.diagnostic_id = d.diagnostic_id "
                    . "INNER JOIN h_assessment_external_team t1 on t1.assessment_id = u.assessment_id "
                    . "Left Join d_user t2 On (t1.user_id=t2.user_id)  WHERE  t1.user_sub_role = '" . $rid . "' "
                    . "AND t2.user_id = '" . $user_id . "' AND (d.assessment_type_id = 1 || d.assessment_type_id = 5);";
        }
        $result = $this->db->get_row($SQL);
        return $result;
    }

    // new function for creating school assessment according to tap admin functionality on 06-06-2016 bt Mohit Kumar
    function createSchoolAssessmentNew($client_id, $internal_assessor_id, $facilitator_id, $diagnostic_id, $tier_id, $award_scheme_id, $aqs_round, $external_assessor_id, $ext_team, $start_date = '', $end_date = '', $facilitatorDataArray = array(), $notificationSettingData = array(), $notificationTeam = array(), $lang_id = DEFAULT_LANGUAGE, $review_criteria = "", $review_type = 0) {
        $aid = 0;
        $review_criteria = trim($review_criteria);
        if (OFFLINE_STATUS == TRUE) {
            //start---> call function for creating unique id for creating assessment on 10-03-2016 by Mohit Kumar
            $uniqueID = $this->db->createUniqueID('createSchoolAssessment');
            //end---> call function for creating unique id for creating assessment on 10-03-2016 by Mohit Kumar
        }

        if ($this->db->insert("d_assessment", array('client_id' => $client_id, 'review_criteria' => $review_criteria, 'facilitator_id' => $facilitator_id, 'diagnostic_id' => $diagnostic_id, 'tier_id' => $tier_id,
                    'award_scheme_id' => $award_scheme_id, 'aqs_round' => $aqs_round, 'd_sub_assessment_type_id' => '2', 'create_date' => date("Y-m-d H:i:s"), 'language_id' => $lang_id, 'iscollebrative' => $review_type))) {
            $aid = $this->db->get_last_insert_id();

            if ($aid) {
                $this->db->insert("d_AQS_data", array('school_aqs_pref_start_date' => $start_date, 'school_aqs_pref_end_date' => $end_date));
                $aqs_ass_id = $this->db->get_last_insert_id();
                $this->db->update("d_assessment", array('aqsdata_id' => $aqs_ass_id), array("assessment_id" => $aid));
            }
            if ($aid && !empty($facilitatorDataArray)) {
                $facilitatorSql = "INSERT INTO h_facilitator_user (assessment_id,client_id,sub_role_id,user_id) VALUES ";
                $i = 0;
                $facilitatorData = array();
                foreach ($facilitatorDataArray as $data) {

                    $facilitatorSql .= "(?,?,?,?),";
                    $facilitatorData[] = $aid;
                    $facilitatorData[] = $data['client_id'];
                    $facilitatorData[] = $data['role_id'];
                    $facilitatorData[] = $data['user_id'];
                    $i++;
                }
                $facilitatorSql = trim($facilitatorSql, ",");
                $this->db->query("$facilitatorSql", $facilitatorData);
            }


            if ($aid && !empty($notificationSettingData)) {
                foreach ($notificationSettingData as $key => $val) {
                    foreach ($notificationTeam as $team) {
                        $notificationDataArray[] = array('assessment_id' => $aid,
                            'notification_id' => $val,
                            'user_id' => $team,
                            'status' => 1,
                            'date' => date('Y-m-d:h:i:s'));
                    }
                }
            }
            if (OFFLINE_STATUS == TRUE) {
                //start---> save the history for insert school assessment data into d_assessment table on 10-03-2016 By Mohit Kumar
                $action_assessment_json = json_encode(array(
                    'client_id' => $client_id,
                    'diagnostic_id' => $diagnostic_id,
                    'tier_id' => $tier_id,
                    'award_scheme_id' => $award_scheme_id,
                    'd_sub_assessment_type_id' => '2',
                    'create_date' => date("Y-m-d H:i:s")
                ));
                $this->db->saveHistoryData($aid, 'd_assessment', $uniqueID, 'createSchoolAssessment', $client_id, $client_id, $action_assessment_json, 0, date('Y-m-d H:i:s'));
                //end---> save the history for insert school assessment data into d_assessment table on 10-03-2016 By Mohit Kumar
            }
            if ($this->db->insert("h_assessment_user", array("user_id" => $external_assessor_id, "role" => 4, "assessment_id" => $aid))) {
                if (OFFLINE_STATUS == TRUE) {
                    // get last insert id for external assessor on 01-042016 by Mohit Kumar
                    $euid = $this->db->get_last_insert_id();
                    //start---> save the history for insert external school assessment data into h_assessment_user table on 01-04-2016 By Mohit Kumar
                    $action_external_assessment_json = json_encode(array(
                        'user_id' => $external_assessor_id,
                        'role' => 4,
                        'assessment_id' => $aid
                    ));
                    $this->db->saveHistoryData($auid, 'h_assessment_user', $uniqueID, 'updateSchoolAssessmentExternal', $external_assessor_id, $aid, $action_external_assessment_json, 0, date('Y-m-d H:i:s'));
                    //end---> save the history for insert external school assessment data into h_assessment_user table on 01-04-2016 By Mohit Kumar
                }
            }
            if (!empty($ext_team))
                foreach ($ext_team as $member_user_id => $roleClient) {

                    $roleClient = explode('_', $roleClient);

                    $externalClientId = $roleClient[0];

                    $roleId = $roleClient[1];

                    if (!$this->db->insert("h_assessment_external_team", array("assessment_id" => $aid, "user_role" => 4, "user_sub_role" => $roleId, "user_id" => $member_user_id))) {

                        return false;
                    } else {
                        if (OFFLINE_STATUS == TRUE) {
                            // get last insert id for external assessor team on 10-03-2016 by Mohit Kumar
                            $etuid = $this->db->get_last_insert_id();
                            //start---> save the history for insert external school assessment team data into h_assessment_user table on 10-03-2016 By Mohit Kumar
                            $action_external_assessment_team_json = json_encode(array(
                                'user_role' => 4,
                                'assessment_id' => $aid,
                                'user_sub_role' => $roleId,
                                'user_id' => $member_user_id
                            ));
                            $this->db->saveHistoryData($etuid, 'h_assessment_external_team', $uniqueID, 'updateSchoolAssessmentExternalTeam', $member_user_id, $aid, $action_external_assessment_team_json, 0, date('Y-m-d H:i:s'));
                        }
                    }
                }

            if ($this->db->insert("h_assessment_user", array("user_id" => $internal_assessor_id, "role" => 3, "assessment_id" => $aid))) {
                $auid = $this->db->get_last_insert_id();
                if (OFFLINE_STATUS == TRUE) {
                    //start---> save the history for insert internal school assessment data into h_assessment_user table on 10-03-2016 By Mohit Kumar
                    $action_internal_assessment_json = json_encode(array(
                        'user_id' => $internal_assessor_id,
                        'role' => 3,
                        'assessment_id' => $aid
                    ));
                    $this->db->saveHistoryData($auid, 'h_assessment_user', $uniqueID, 'createSchoolAssessmentInternal', $internal_assessor_id, $aid, $action_internal_assessment_json, 0, date('Y-m-d H:i:s'));
                    //end---> save the history for insert internal school assessment data into h_assessment_user table on 10-03-2016 By Mohit Kumar

                    $this->db->addAlerts('d_assessment', $aid, $client_id, $aid, 'CREATE_REVIEW');
                    $alertid = $this->db->get_last_insert_id();
                    $action_alert_json = json_encode(array(
                        'table_name' => 'd_assessment',
                        'content_id' => $aid,
                        'content_title' => $client_id,
                        'content_description' => $aid,
                        "type" => 'CREATE_REVIEW'
                    ));
                    $this->db->saveHistoryData($alertid, 'd_alerts', $uniqueID, 'createSchoolAssessmentAlert', $aid, $aid, $action_alert_json, 0, date('Y-m-d H:i:s'));
                }
                return $aid;
            } else {
                $this->db->delete("d_assessment", array("assessment_id" => $aid));
                $this->db->delete('z_history', array('table_name' => 'd_assessment', 'table_id' => $aid, 'action_unique_id' => $uniqueID,
                    'action' => 'createSchoolAssessment', 'action_id' => $client_id));
            }
        }
        return false;
    }

    function getKPAratingsforAssessment($assessment_id, $role = 4) {

        $sql = "SELECT ks.id as score_id, k.kpa_name,k.kpa_id,kd.kpa_instance_id,r.rating,hls.rating_level_order as numericRating,r.rating_id,kd.`kpa_order` as kpa_no
			FROM `d_kpa` k
			inner join h_kpa_diagnostic kd on k.kpa_id=kd.kpa_id
			inner join d_assessment a on kd.diagnostic_id=a.diagnostic_id
			inner join h_assessment_user au on au.assessment_id=a.assessment_id
			left join `h_kpa_instance_score` ks on kd.kpa_instance_id=ks.kpa_instance_id and a.assessment_id=ks.assessment_id and ks.assessor_id=au.user_id
			left join h_diagnostic_rating_level_scheme rls on rls.diagnostic_id = a.diagnostic_id
            left join h_rating_level_scheme hls on hls.rating_scheme_id =rls.rating_level_scheme_id and ks.d_rating_rating_id=hls.rating_id  and hls.rating_level_id=1
            left join d_rating_level rl on rl.rating_level_id = hls.rating_level_id 
			left join d_rating r on ks.d_rating_rating_id = r.rating_id and hls.rating_id=r.rating_id      
			where a.assessment_id=? and au.role=?";
        $sqlArgs = array($assessment_id, $role);
        $sql .= " order by kd.`kpa_order` asc;";
        $res = $this->db->get_results($sql, $sqlArgs);
        return $res ? $res : array();
    }

    // function to get assessment kpa
    function getAssessmentKpa($assessment_id) {

        $sql = "SELECT user_id,kpa_instance_id as kpa_id FROM d_assessment_kpa WHERE assessment_id = ? ";
        return $this->db->get_results($sql, array($assessment_id));
    }

    //function to insert assessment kpa's 
    function addAssessmentKpa($data, $assessment_id) {
        $sql = "INSERT INTO d_assessment_kpa (`assessment_id`,`user_id`,`kpa_instance_id`) VALUES ";
        $param = array();
        $inner_array = array();
        foreach ($data as $key => $val) {
            foreach ($val as $user => $kpa) {
                $sql .= "(" . "?,?,?" . "),";
                $param[] = $assessment_id;
                $param[] = $key;
                $param[] = $kpa;
            }
        }
        $sql = trim($sql, ",");
        if ($this->db->query($sql, $param))
            $aid = $this->db->get_last_insert_id();
        if ($aid) {
            return $aid;
        } else
            return false;
    }

    //function to edit assessment kpa's 
    function editAssessmentKpa($data, $assessment_id) {
        $this->db->delete("d_assessment_kpa", array('assessment_id' => $assessment_id));
        $sql = "INSERT INTO d_assessment_kpa (`assessment_id`,`user_id`,`kpa_instance_id`) VALUES ";
        $param = array();
        $inner_array = array();
        foreach ($data as $key => $val) {
            foreach ($val as $user => $kpa) {
                $sql .= "(" . "?,?,?" . "),";
                $param[] = $assessment_id;
                $param[] = $key;
                $param[] = $kpa;
            }
        }
        $sql = trim($sql, ",");
        if ($this->db->query($sql, $param))
            $aid = $this->db->get_last_insert_id();
        if ($aid) {
            return $aid;
        } else
            return false;
    }
    
    //function to get feedback assessment
    function getFeedbackAssessment($id) {

        $sql = " SELECT u.name,u.email,u.user_id,au.assessment_id FROM d_user u 
                    INNER JOIN (SELECT r.sub_role_name ,et.user_id,et.assessment_id FROM `h_assessment_external_team` et 
                    INNER JOIN d_user_sub_role r ON et.user_sub_role = r.sub_role_id AND et.user_role!=3                     
                    UNION SELECT r.role_name ,et.user_id ,et.assessment_id
                    FROM `h_assessment_user` et INNER JOIN d_user_role r ON et.role = r.role_id 
                    AND et.role!=3 AND et.isFilled!=1) au ON au.user_id = u.user_id ORDER by au.assessment_id";
        $res = $this->db->get_results($sql);
        return $res ? $res : array();
    }

    //function to get  assessment kpa's
    function getSchoolAssessmentKpas($aid, $lang_id = 9) {

        $params = array($aid, $lang_id);
        $sql = " SELECT e.translation_text as kpa_name,kd.kpa_instance_id as kpa_id FROM d_assessment a "
                . "INNER JOIN h_kpa_diagnostic kd ON a.diagnostic_id = kd.diagnostic_id "
                . "INNER JOIN d_kpa k on k.kpa_id = kd.kpa_id "
                . "INNER JOIN h_lang_translation e ON e.equivalence_id = k.equivalence_id"
                . " WHERE a.assessment_id=? AND e.language_id = ? ORDER BY kd.kpa_order ";


        $res = $this->db->get_results($sql, $params);
        return $res ? $res : array();
    }
    
    //function to get all review notifications

    function getReviewNotifications($type = 0) {
        $params = array(1);
        $sqlCond = '';
        if ($type) {
            $sqlCond = 'AND nt.id = ?';
            $params[] = $type;
        }
        $res = $this->db->get_results("SELECT n.id,n.notification_name,n.notification_label,nt.id as type FROM d_notifications n "
                . " INNER JOIN h_notification_type t ON t.notification_id = n.id "
                . " INNER JOIN d_notification_type nt ON nt.id = t.notification_type_id "
                . " WHERE n.status = ? $sqlCond  ORDER BY n.notification_name ASC ", $params);
        return $res ? $res : array();
    }

    //function to get all users with notifications

    function getNotificationUsers($assessment_id, $type = 1) {

        $res = $this->db->get_results("SELECT un.sub_role_name,un.sub_role_id,un.user_id,un.assessment_id,un.name,n.notification_id,n.type FROM h_user_review_notification n 
                    RIGHT JOIN (SELECT r.sub_role_name,r.sub_role_id ,et.user_id,et.assessment_id,us.name FROM `h_assessment_external_team` et 
                    INNER JOIN d_user_sub_role r ON et.user_sub_role = r.sub_role_id
                     INNER JOIN d_user us ON us.user_id = et.user_id WHERE et.assessment_id= ?                     
                    UNION SELECT 'Lead' as sub_role_name ,1 as sub_role_id ,et.user_id ,et.assessment_id,us.name
                    FROM `h_assessment_user` et INNER JOIN d_user_role r ON et.role = r.role_id 
                    INNER JOIN d_user us ON us.user_id = et.user_id WHERE et.assessment_id= ? AND et.role=4
                    ) un ON un.assessment_id = n.assessment_id  AND un.user_id = n.user_id  WHERE n.type = ?", array($assessment_id, $assessment_id, $type));
        return $res ? $res : array();
    }

    //function to create notifications queue

    function createNotificationQueue($notifications, $edate = '') {

        if (!empty($edate)) {
            $edate = date("Y-m-d", strtotime($edate));
        }
        $sql = "INSERT INTO d_notification_queue (notification_id,user_id,assessment_id,status,type,date) VALUES ";
        $values = array();
        $delQueryCond = '';
        foreach ($notifications as $data) {

            $this->db->delete("d_notification_queue", array("assessment_id" => $data['assessment_id'], 'type' => $data['type'], 'user_id' => $data['user_id'], 'notification_id' => $data['notification_id']));
            if ($data['sub_role_name'] != 'Observer' && !empty($data['notification_id'])) {
                $sql .= '(?,?,?,?,?,?),';
                $values[] = $data['notification_id'];
                $values[] = $data['user_id'];
                $values[] = $data['assessment_id'];
                $values[] = 1;
                $values[] = $data['type'];
                $values[] = $edate;
            } else if ($data['sub_role_name'] == 'Observer') {

                $this->db->delete("d_notification_queue", array("assessment_id" => $data['assessment_id'], 'type' => $data['type'], 'user_id' => $data['user_id'], 'notification_id' => '0'));

                $sql .= '(?,?,?,?,?,?),';
                $values[] = 0;
                $values[] = $data['user_id'];
                $values[] = $data['assessment_id'];
                $values[] = 1;
                $values[] = $data['type'];
                $values[] = $edate;
            } else if ($data['sub_role_name'] != 'Observer' && empty($data['notification_id'])) {
                $sql .= '(?,?,?,?,?,?),';
                $values[] = 0;
                $values[] = $data['user_id'];
                $values[] = $data['assessment_id'];
                $values[] = 1;
                $values[] = $data['type'];
                $values[] = $edate;
            }
        }
        if (!empty($values)) {
            $sql = trim($sql, ",");
            return $this->db->query($sql, $values);
        } else
            return false;
    }

    //function to get all users of a review with notifications

    function getAssessmentUsers($assessment_id, $type = 0) {

        $sqlCond = '';
        if ($type)
            $sqlCond = 'AND et.role=4';

        $res = $this->db->get_results("SELECT r.sub_role_name,r.sub_role_order,et.user_id,et.assessment_id,us.name FROM `h_assessment_external_team` et 
                    INNER JOIN d_user_sub_role r ON et.user_sub_role = r.sub_role_id
                    INNER JOIN d_user us ON us.user_id = et.user_id WHERE et.assessment_id= $assessment_id                     
                    UNION SELECT 'Lead / Sr. Associate' as role_name,1 as sub_role_order ,et.user_id ,et.assessment_id,us.name
                    FROM `h_assessment_user` et INNER JOIN d_user_role r ON et.role = r.role_id 
                    INNER JOIN d_user us ON us.user_id = et.user_id WHERE et.assessment_id= $assessment_id $sqlCond  order by sub_role_order
                    ");
        return $res ? $res : array();
    }

    //function to get all users of a review with notifications

    function getReviewNotificationUsers($assessment_id) {

        $res = $this->db->get_results("SELECT user_id,assessment_id,notification_id,type FROM h_user_review_notification 
                WHERE assessment_id=?", array($assessment_id));
        return $res ? $res : array();
    }

    //function to get all users who had send reimbursement Sheet
    function getReviewReimSheetUsers($assessment_id) {

        $res = $this->db->get_results("SELECT user_id,sheet_status FROM h_user_review_reim_sheet_status 
                WHERE assessment_id=?", array($assessment_id));
        return $res ? $res : array();
    }

    //used in group report
    function ExternalAssessorsGrouped($client_id, $group_ass_id) {

        $sql = "select a.* from (select * from d_group_assessment where group_assessment_id=?) a 
              inner join h_assessment_ass_group b on a.group_assessment_id=b.group_assessment_id
              inner join d_assessment c on b.assessment_id=c.assessment_id
              inner join h_assessment_user d on c.assessment_id=d.assessment_id && d.role=4
              inner join d_user e on d.user_id=e.user_id        where e.client_id=?     ";
        $res = $this->db->get_results($sql, array($group_ass_id, $client_id));
        return $res ? $res : array();
    }

    //function to get assessment rating
    function getAssessmentRatingStatus($assessment_id) {

        $sql = "select isFilled FROM h_assessment_user where assessment_id=? AND role = ?     ";
        $res = $this->db->get_row($sql, array($assessment_id, 4));
        return $res ? $res['isFilled'] : array();
    }

    //function to get assessment rating percentage
    function getAssessmentRatingPercentage($assessment_id) {

        $sql = "select collaborativepercntg FROM d_assessment where assessment_id=? ";
        $res = $this->db->get_row($sql, array($assessment_id));
        return $res ? $res['collaborativepercntg'] : array();
    }

    //function to get assessment rating for kpa
    function getAssessmentRatingKpa($assessment_id) {

        $sql = "select count(kpa_instance_id) as kpa FROM d_assessment_kpa where assessment_id=? ";
        $res = $this->db->get_row($sql, array($assessment_id));
        return $res ? $res['kpa'] : array();
    }

    //function to delete old rating for kpa
    function deleteOldKpaRating($teamKpas, $assessment_id) {
        $newKpas = array();
        $newKqIds = array();
        $newCqIds = array();
        $newJsIds = array();
        //get previous assigned kpas
        foreach ($teamKpas as $key => $data) {
            $sql = "SELECT kpa_instance_id FROM d_assessment_kpa WHERE assessment_id=? AND user_id = ?     ";
            $res = $this->db->get_results($sql, array($assessment_id, $key));
            $res = array_column($res, 'kpa_instance_id');
            $newKpas = $newKpas + array_values(array_merge(array_diff($res, $data), array_diff($data, $res)));
        }
        //get key question instance id
        if (!empty($newKpas)) {
            $sql = "SELECT kqs.key_question_instance_id FROM h_kpa_kq  kq INNER JOIN  h_kq_instance_score kqs"
                    . " ON kq.key_question_instance_id =  kqs.key_question_instance_id "
                    . " WHERE kqs.assessment_id = ? AND kq.kpa_instance_id  IN (" . implode(",", $newKpas) . ")    ";
            $newKqIds = $this->db->get_results($sql, array($assessment_id));
            $newKqIds = array_column($newKqIds, 'key_question_instance_id');
        }

        //get core question instance id
        if (!empty($newKqIds)) {
            $sql = "SELECT cqs.core_question_instance_id FROM h_kq_cq  kq INNER JOIN  h_cq_score cqs"
                    . " ON kq.core_question_instance_id =  cqs.core_question_instance_id "
                    . " WHERE cqs.assessment_id = ? AND kq.key_question_instance_id  IN (" . implode(",", $newKqIds) . ")    ";
            $newCqIds = $this->db->get_results($sql, array($assessment_id));
            $newCqIds = array_column($newCqIds, 'core_question_instance_id');
        }
        //get judgement statement instance id
        if (!empty($newCqIds)) {
            $sql = "SELECT cqs.judgement_statement_instance_id FROM h_cq_js_instance  kq INNER JOIN  f_score cqs"
                    . " ON kq.judgement_statement_instance_id =  cqs.judgement_statement_instance_id "
                    . " WHERE cqs.assessment_id = ? AND kq.core_question_instance_id  IN (" . implode(",", $newCqIds) . ")    ";
            $newJsIds = $this->db->get_results($sql, array($assessment_id));
            $newJsIds = array_column($newJsIds, 'judgement_statement_instance_id');
        }

        $params = array($assessment_id);
        $delKeyNotesSql = '';

        if (!empty($newKpas)) {

            $keyNotesIds = array();
            $keyNotesSql = "SELECT id  FROM assessor_key_notes where kpa_instance_id  IN (" . implode(",", $newKpas) . ") AND  assessment_id=? ;    ";
            $keyNotesIds = $this->db->get_results($keyNotesSql, array($assessment_id));
            $keyNotesIds = array_column($keyNotesIds, 'id');
            if (!empty($keyNotesIds)) {
                $asscKeyNotesSql = "delete FROM h_assessor_key_notes_js where assessor_key_notes_id  IN (" . implode(",", $keyNotesIds) . ");";
                $res = $this->db->query($asscKeyNotesSql);
            }
            $delKeyNotesSql .= "delete FROM assessor_key_notes where kpa_instance_id  IN (" . implode(",", $newKpas) . ") AND  assessment_id=? ;";
            $delKeyNotesSql .= "delete FROM h_kpa_instance_score where kpa_instance_id  IN (" . implode(",", $newKpas) . ") AND  assessment_id=? ;";
            array_push($params, $assessment_id);
        }
        if (!empty($newKqIds)) {
            $delKeyNotesSql .= "delete FROM h_kq_instance_score where  key_question_instance_id  IN (" . implode(",", $newKqIds) . ") AND  assessment_id=? ;";
            array_push($params, $assessment_id);
        }
        if (!empty($newCqIds)) {
            $delKeyNotesSql .= "delete FROM h_cq_score where core_question_instance_id  IN (" . implode(",", $newCqIds) . ") AND  assessment_id=? ;";
            array_push($params, $assessment_id);
        }
        if (!empty($newJsIds)) {
            $delKeyNotesSql .= "delete FROM f_score where judgement_statement_instance_id  IN (" . implode(",", $newJsIds) . ") AND  assessment_id=? ;";
            array_push($params, $assessment_id);
        }
        if (!empty($delKeyNotesSql))
            $res = $this->db->query($delKeyNotesSql, $params);
        return true;
    }

    
    //////////// Offline Start Here
    function getAssessmentListByUser($args = array(), $tap_admin_id = '', $user_id = '', $rid = '', $tap_admin_role, $ref = '', $ref_key = '', $is_guest = 0, $logged_user = 0) {

        $args = $this->parse_arg($args, array("user_id" => 0, "sub_role_user_id" => 0, "client_id" => 0, "network_id" => 0, "province_id" => 0, "client_name_like" => "", "diagnostic_id" => 0, "name_like" => "", "status" => "", "fdate_like" => "", "edate_like" => "", "max_rows" => 10, "page" => 1, "order_by" => "diagnostic_name", "order_type" => "asc"));

        $order_by = array("client_name" => "client_name", "name" => "name", "assessment_type" => "assessment_type_name", "create_date" => "create_date", "aqs_start_date" => "aqs_start_date");

        $schoolSqlArgs = array();

        $teacherSqlArgs = array();

        $schoolWhereClause = "";

        $teacherWhereClause = "";

        $teacherHavingClause = " having 1 ";

        $schoolHavingClause = " having 1 ";
        $pendingAssessmentCondition = "";
        if (isset($ref) && $ref == 2) {

            $pendingAssessmentCondition = " AND STR_TO_DATE(q.school_aqs_pref_start_date, '%d-%m-%Y') BETWEEN CURRENT_DATE() and DATE_ADD(CURRENT_DATE(), INTERVAL 7 DAY)  AND q.status is null ";
        }


        if ($args['client_name_like'] != "") {

            $schoolWhereClause .= "and CONCAT(c.client_name,IF(a.review_criteria IS NOT NULL,' ',''),IF(a.review_criteria IS NOT NULL,a.review_criteria,'')) like ? ";

            $teacherWhereClause .= "and c.client_name like ? ";

            $schoolSqlArgs[] = "%" . $args['client_name_like'] . "%";

            $teacherSqlArgs[] = "%" . $args['client_name_like'] . "%";
        }

        if ($args['diagnostic_id'] > 0) {

            $schoolWhereClause .= "and a.diagnostic_id = ? ";

            $teacherWhereClause .= "and a.diagnostic_id = ? ";

            $schoolSqlArgs[] = $args['diagnostic_id'];

            $teacherSqlArgs[] = $args['diagnostic_id'];
        }

        if ($args['fdate_like'] != '' && $args['edate_like'] == '') {
            $schoolWhereClause .= "and ( (STR_TO_DATE(q.school_aqs_pref_start_date, '%d-%m-%Y') Between ? And ?) || (STR_TO_DATE(q.school_aqs_pref_start_date, '%d-%m-%Y') Between ? And ?) ) ";
            $schoolSqlArgs[] = "" . $args['fdate_like'] . "";
            $schoolSqlArgs[] = "" . $args['fdate_like'] . "";
            $schoolSqlArgs[] = "" . $args['fdate_like'] . "";
            $schoolSqlArgs[] = "" . $args['fdate_like'] . "";

            $teacherWhereClause .= "and ( (STR_TO_DATE(q.school_aqs_pref_start_date, '%d-%m-%Y') Between ? And ?) || (STR_TO_DATE(q.school_aqs_pref_start_date, '%d-%m-%Y') Between ? And ?) ) ";
            $teacherSqlArgs[] = "" . $args['fdate_like'] . "";
            $teacherSqlArgs[] = "" . $args['fdate_like'] . "";
            $teacherSqlArgs[] = "" . $args['fdate_like'] . "";
            $teacherSqlArgs[] = "" . $args['fdate_like'] . "";
        } else if ($args['fdate_like'] == '' && $args['edate_like'] != '') {
            $schoolWhereClause .= "and ( (STR_TO_DATE(q.school_aqs_pref_end_date, '%d-%m-%Y') Between ? And ?) || (STR_TO_DATE(q.school_aqs_pref_end_date, '%d-%m-%Y') Between ? And ?) ) ";
            $schoolSqlArgs[] = "" . $args['edate_like'] . "";
            $schoolSqlArgs[] = "" . $args['edate_like'] . "";
            $schoolSqlArgs[] = "" . $args['edate_like'] . "";
            $schoolSqlArgs[] = "" . $args['edate_like'] . "";

            $teacherWhereClause .= "and ( (STR_TO_DATE(q.school_aqs_pref_end_date, '%d-%m-%Y') Between ? And ?) || (STR_TO_DATE(q.school_aqs_pref_end_date, '%d-%m-%Y') Between ? And ?) ) ";
            $teacherSqlArgs[] = "" . $args['edate_like'] . "";
            $teacherSqlArgs[] = "" . $args['edate_like'] . "";
            $teacherSqlArgs[] = "" . $args['edate_like'] . "";
            $teacherSqlArgs[] = "" . $args['edate_like'] . "";
        } else if ($args['fdate_like'] != '' && $args['edate_like'] != '') {
            $schoolWhereClause .= "and ( (STR_TO_DATE(q.school_aqs_pref_start_date, '%d-%m-%Y') Between ? And ?) && (STR_TO_DATE(q.school_aqs_pref_end_date, '%d-%m-%Y') Between ? And ?) ) ";
            $schoolSqlArgs[] = "" . $args['fdate_like'] . "";
            $schoolSqlArgs[] = "" . $args['edate_like'] . "";
            $schoolSqlArgs[] = "" . $args['fdate_like'] . "";
            $schoolSqlArgs[] = "" . $args['edate_like'] . "";

            $teacherWhereClause .= "and ( (STR_TO_DATE(q.school_aqs_pref_start_date, '%d-%m-%Y') Between ? And ?) && (STR_TO_DATE(q.school_aqs_pref_end_date, '%d-%m-%Y') Between ? And ?) ) ";
            $teacherSqlArgs[] = "" . $args['fdate_like'] . "";
            $teacherSqlArgs[] = "" . $args['edate_like'] . "";
            $teacherSqlArgs[] = "" . $args['fdate_like'] . "";
            $teacherSqlArgs[] = "" . $args['edate_like'] . "";
        }

        if ($args['name_like'] != "") {

            $schoolHavingClause .= " and group_concat(u.name order by b.role) like ?";

            $teacherHavingClause .= " and group_concat(u.name order by b.role) like ?";

            $schoolSqlArgs[] = "%" . $args['name_like'] . "%";

            $teacherSqlArgs[] = "%" . $args['name_like'] . "%";
        }
        if ($args['network_id'] > 0 && $args['user_id'] > 0) {

            $teacherHavingClause .= " and (cn.network_id = " . $args['network_id'] . " or user_ids  rlike concat('[[:<:]]'," . $args['user_id'] . ",'[[:>:]]')) ";
            $schoolHavingClause .= " and (cn.network_id = " . $args['network_id'] . " or user_ids  rlike concat('[[:<:]]'," . $args['user_id'] . ",'[[:>:]]') or externalTeam rlike concat('[[:<:]]'," . ($args['sub_role_user_id'] ? $args['sub_role_user_id'] : 0) . ",'[[:>:]]')) ";
        } else if ($args['client_id'] > 0 && $args['user_id'] > 0) {

            $teacherHavingClause .= " and (client_id = " . $args['client_id'] . " or user_ids  rlike concat('[[:<:]]'," . $args['user_id'] . ",'[[:>:]]')) ";

            //$schoolHavingClause.=" and (client_id = ".$args['client_id']." or user_ids  rlike concat('[[:<:]]',".$args['user_id'].",'[[:>:]]')) ";
            $schoolHavingClause .= " and (client_id = " . $args['client_id'] . " or user_ids  rlike concat('[[:<:]]'," . $args['user_id'] . ",'[[:>:]]') or externalTeam rlike concat('[[:<:]]'," . ($args['sub_role_user_id'] ? $args['sub_role_user_id'] : 0) . ",'[[:>:]]')) ";
        } else if ($args['network_id'] > 0) {

            $teacherHavingClause .= " and cn.network_id = " . $args['network_id'] . " ";

            $schoolHavingClause .= " and cn.network_id = " . $args['network_id'] . " ";
        } else if ($args['client_id'] > 0) {

            $teacherHavingClause .= " and client_id = " . $args['client_id'] . " ";

            $schoolHavingClause .= " and client_id = " . $args['client_id'] . " ";
        } else if ($args['user_id'] > 0) {

            $teacherHavingClause .= " and user_ids  rlike concat('[[:<:]]'," . $args['user_id'] . ",'[[:>:]]') ";

            $schoolHavingClause .= " and user_ids  rlike concat('[[:<:]]'," . $args['user_id'] . ",'[[:>:]]') or externalTeam rlike concat('[[:<:]]'," . ($args['sub_role_user_id'] ? $args['sub_role_user_id'] : 0) . ",'[[:>:]]') or leader_ids  rlike concat('[[:<:]]'," . $args['user_id'] . ",'[[:>:]]') ";
        }
        $kpaCond = '';
        $kpaCond = 'left join d_assessment_kpa kp on kp.assessment_id=a.assessment_id ';
        if ($args['province_id'] > 0) {

            $teacherWhereClause .= " and pn.province_id = " . $args['province_id'] . " ";

            $schoolWhereClause .= " and pn.province_id = " . $args['province_id'] . " ";
        }
        //type of review
        if ($args['assessment_type_id'] != "") {
            switch ($args['assessment_type_id']) {
                case 'sch' :
                case 1 :
                    $schoolWhereClause .= " and a.d_sub_assessment_type_id!=1 and d.assessment_type_id=1";
                    $teacherWhereClause .= " and 1=0";
                    break;
                case 'schs' :
                    $schoolWhereClause .= " and a.d_sub_assessment_type_id=1 and d.assessment_type_id=1";
                    $teacherWhereClause .= " and 1=0";
                    break;
                case 'tchr' :
                case 2 :
                    $schoolWhereClause .= " and 1=0";
                    $teacherWhereClause .= " and dt.assessment_type_id=2 ";
                    break;
                case 'stu' :
                case 4 :
                    $schoolWhereClause .= " and 1=0";
                    $teacherWhereClause .= " and dt.assessment_type_id=4 ";
                    break;
                case 'col' :
                case 5 :
                    $schoolWhereClause .= " and a.d_sub_assessment_type_id!=1 and d.assessment_type_id=5";
                    $teacherWhereClause .= " and 1=0";
                    break;
            }
        }
        // make condition for getting only school reviews for tap admin on 18-05-2016 by Mohit Kumar
        if ($tap_admin_id == 8) {
            $tap_condition = " and d.assessment_type_id='1' and d_sub_assessment_type_id!='1' ";
        } else {
            $tap_condition = '';
        }
        if ($user_id != '' && $rid != '') {
            $getExternalReviewsId = $this->getAssessmentIDsNew($user_id, $rid);

            if ($getExternalReviewsId['assessment_id'] != '') {
                $externalAssessorCondition = " and a.assessment_id IN (" . $getExternalReviewsId['assessment_id'] . ") ";
            } else {
                $externalAssessorCondition = '';
            }
        } else {
            $externalAssessorCondition = '';
        }
        $condition = '';

        if (isset($ref) && $ref == 1 && $ref_key != '') {
            $SQL1 = "Select alert_ids as assessment_id from h_alert_relation where login_user_role='" . $tap_admin_role . "' and type='REVIEW'";
            $assessment_id = $this->db->get_row($SQL1);
            if (!empty($assessment_id) && $assessment_id['assessment_id'] != '') {
                $condition = " and  a.assessment_id In (" . $assessment_id['assessment_id'] . ")  ";
            }
        }

        $langCond = '';
        if ($this->lang != 'all') {
            $langCond = "and a.language_id=" . $this->lang;
        }



        $guestCond = $is_guest ? " && c.is_guest=1 " : " && c.is_guest!=1 ";
        $isActive_Inactive_Assessment = " && a.isAssessmentActive=1 ";
        $isActive_Inactive_Group_Assessment = " && dt.isGroupAssessmentActive=1 ";
        $isFilledCnd = " && b.isFilled=0 && b.percComplete=0 ";
        $extFilledCnd = " && ext.isFilled=0 && ext.percComplete=0 ";


        $sql = "
                        select SQL_CALC_FOUND_ROWS z.* 

                        from (

                                (


                                        SELECT  ss.status as profile_status,ss.perComplete,a.collaborativepercntg as avg,b.isFilled,a.iscollebrative,d.diagnostic_id,dt.group_assessment_id,dt.admin_user_id,dt.student_round as assessment_round,q.is_uploaded,q.percComplete as aqspercent,STR_TO_DATE(q.school_aqs_pref_start_date, '%d-%m-%Y') as aqs_start_date,STR_TO_DATE(q.school_aqs_pref_end_date, '%d-%m-%Y') as aqs_end_date, 0 as assessment_id, dt.assessment_type_id, dt.client_id,dt.creation_date as create_date ,c.client_name ,t.assessment_type_name, cn.network_id, group_concat(b.isFilled order by b.role,a.assessment_id) as  statuses, group_concat(distinct b.role order by b.role) as roles, group_concat(b.percComplete order by b.role,a.assessment_id) as percCompletes, group_concat(if(b.ratingInputDate is null,'',date(b.ratingInputDate)) order by b.role,a.assessment_id) as ratingInputDates, group_concat(b.user_id order by b.role,a.assessment_id) as user_ids, group_concat(u.name order by b.role,a.assessment_id) as user_names, q.status as aqs_status,group_concat(concat(r.report_id,'|',r.isPublished,'|',r.publishDate)) as report_data,count(distinct s.assessment_id) as assessments_count, CASE WHEN dt.assessment_type_id = 2 THEN group_concat(if(td.value is null,'',td.value) order by b.role,a.assessment_id)  WHEN dt.assessment_type_id = 4 THEN  group_concat(if(sd.value is null,'',sd.value) order by b.role,a.assessment_id) END as teacherInfoStatuses, ifnull(a.d_sub_assessment_type_id,0) as 'subAssessmentType',

                                        aqsdata_id,p.status as 'post_rev_status',p.percComplete as 'postreviewpercent',a.is_approved as 'isApproved', hlt.translation_text as diagnosticName, '' as externalTeam,'' as externalPercntage,'' as extFilled,'' as kpa,'' as leader_ids,'' as kpa_user

                                        FROM d_group_assessment dt

                                        left join h_assessment_ass_group s on s.group_assessment_id = dt.group_assessment_id

                                        left join `d_assessment` a  on a.assessment_id = s.assessment_id

                                        left join `h_assessment_user` b on a.assessment_id=b.assessment_id

                                        left join d_teacher_data td on td.teacher_id=b.user_id and b.role=3 and td.assessment_id=b.assessment_id and td.attr_id=11

                                        left join d_student_data sd on sd.student_id=b.user_id and b.role=3 and sd.assessment_id=b.assessment_id and sd.attr_id=49

                                        left join `d_client` c on c.client_id=dt.client_id

                                        left join `d_diagnostic` d on d.diagnostic_id=a.diagnostic_id
                                        left join h_lang_translation hlt on d.equivalence_id = hlt.equivalence_id && hlt.language_id=a.language_id

                                        left join `d_assessment_type` t on dt.assessment_type_id=t.assessment_type_id

                                        left join d_user u on u.user_id=b.user_id

                                        left join h_client_network cn on cn.client_id=c.client_id

                                        left join d_AQS_data q on q.id=a.aqsdata_id
                                        left join h_school_profile_status ss on ss.assessment_id=a.assessment_id

                                        left join h_assessment_report r on r.assessment_id=a.assessment_id
                                        $kpaCond
                                        left join d_post_review p on p.assessment_id=a.assessment_id
                                        left join h_client_province cp on cp.client_id = c.client_id	
                                        left join h_province_network pn on pn.network_id = cn.network_id and cp.province_id = pn.province_id	

                                        where 1=1 && a.is_approved=1 && a.iscollebrative=1 " . $isFilledCnd . " " . $isActive_Inactive_Group_Assessment . " " . $guestCond . " " . $langCond . " " . $tap_condition . $externalAssessorCondition . $condition . " " . $teacherWhereClause . $pendingAssessmentCondition . "



                                        group by dt.group_assessment_id

                                        $teacherHavingClause

                                )

                                union

                                (


                                        SELECT ss.status as profile_status,ss.perComplete,a.collaborativepercntg as avg,b.isFilled,a.iscollebrative,d.diagnostic_id,0 as group_assessment_id,0 as admin_user_id,a.aqs_round as assessment_round,q.is_uploaded,q.percComplete as aqspercent,STR_TO_DATE(q.school_aqs_pref_start_date, '%d-%m-%Y') as aqs_start_date,STR_TO_DATE(q.school_aqs_pref_end_date, '%d-%m-%Y') as aqs_end_date,a.assessment_id, d.assessment_type_id, a.client_id,a.create_date as create_date ,CONCAT(c.client_name,IF((a.review_criteria IS NOT NULL && a.review_criteria!=''),' - ',''),IF((a.review_criteria IS NOT NULL  && a.review_criteria!=''),a.review_criteria,'')) ,t.assessment_type_name, cn.network_id, group_concat(b.isFilled order by b.role) as  statuses, group_concat(b.role order by b.role) as roles, group_concat(b.percComplete order by b.role) as percCompletes, group_concat(if(b.ratingInputDate is null,'',date(b.ratingInputDate)) order by b.role) as ratingInputDates, group_concat(b.user_id order by b.role) as user_ids, group_concat(u.name order by b.role) as user_names, q.status as aqs_status,
                                        group_concat(concat(r.report_id,'|',r.isPublished,'|',r.publishDate)) as report_data, 1 as assessments_count,'' as teacherInfoStatuses, ifnull(a.d_sub_assessment_type_id,0) as 'subAssessmentType'				

                        ,aqsdata_id,p.status as 'post_rev_status',p.percComplete as 'postreviewpercent',

                        a.is_approved as 'isApproved',

                         hlt.translation_text as diagnosticName ,group_concat(distinct ext.user_id) as externalTeam,ext.percComplete as externalPercntage,ext.isFilled as extFilled,group_concat(distinct kp.kpa_instance_id) as kpa,group_concat(haa1.leader) as leader_ids,group_concat(distinct kp.user_id) as kpa_user

                                                FROM `d_assessment` a 

                                                inner join `h_assessment_user` b on a.assessment_id=b.assessment_id 

                                                inner join `d_client` c on c.client_id=a.client_id

                                                inner join `d_diagnostic` d on d.diagnostic_id=a.diagnostic_id

                                                inner join `d_assessment_type` t on d.assessment_type_id=t.assessment_type_id

                                                inner join d_user u on u.user_id=b.user_id
                                                inner join h_lang_translation hlt on d.equivalence_id = hlt.equivalence_id && hlt.language_id=a.language_id

                                                left join h_client_network cn on cn.client_id=c.client_id

                                                left join d_AQS_data q on q.id=a.aqsdata_id

                                                left join h_assessment_report r on r.assessment_id=a.assessment_id					

                                                left join d_post_review p on p.assessment_id=a.assessment_id
                                                left join h_client_province cp on cp.client_id = c.client_id	
                                                left join h_province_network pn on pn.network_id = cn.network_id and cp.province_id = pn.province_id
                                                left join h_assessment_external_team ext on ext.assessment_id = a.assessment_id 
                                                left join assessor_key_notes akn on a.assessment_id=akn.assessment_id 

                                                left join h_assessor_action1 haa1 on akn.id=haa1.assessor_key_notes_id
                                                 left join h_school_profile_status ss on ss.assessment_id=a.assessment_id

                                                $kpaCond
                                                where a.is_approved=1 && a.iscollebrative=1 && (d.assessment_type_id=1 || d.assessment_type_id=5) && ((b.isfilled=0 && b.perccomplete=0 && ext.perccomplete IS NULL) || (b.isfilled=0 && b.perccomplete=0 && ext.perccomplete IS NOT NULL && ext.perccomplete =0)) " . $isActive_Inactive_Assessment . " " . $guestCond . " " . $langCond . "  " . $tap_condition . $externalAssessorCondition . $condition . " " . $schoolWhereClause . $pendingAssessmentCondition . "

                                                group by a.assessment_id

                                        $schoolHavingClause

                                )

                        ) as z";


        $sql .= " order by " . (isset($order_by[$args["order_by"]]) ? $order_by[$args["order_by"]] : "create_date") . ($args["order_type"] == "desc" ? " desc " : " asc ") . $this->limit_query($args['max_rows'], $args['page']);

        $res = $this->db->get_results($sql, array_merge($teacherSqlArgs, $schoolSqlArgs));
        $this->setPageCount($args['max_rows']);
        //get external team roles
        $roleRes = array();
        if (!empty($logged_user) && !in_array($tap_admin_role, array(1, 2, 5, 8))) {
            $roleSql = "SELECT ex.assessment_id,ex.user_sub_role,ex.user_id FROM h_assessment_external_team ex "
                    . " INNER JOIN d_assessment d ON d.assessment_id = ex.assessment_id "
                    . "WHERE ex.user_id = '$logged_user'";
            $roleRes = $this->db->get_results($roleSql);
            $roleRes = array_column($roleRes, 'user_sub_role', 'assessment_id');
        }

        $lnt = count($res);

        for ($j = 0; $j < $lnt; $j++) {

            if ($res[$j]['assessment_type_id'] == 1 || $res[$j]['assessment_type_id'] == 5) {

                $res[$j]['report_data'] = $res[$j]['report_data'] == "" ? array() : explode(",", $res[$j]['report_data']);

                $rdc = count($res[$j]['report_data']);

                for ($k = 0; $k < $rdc; $k++) {

                    $tm = explode("|", $res[$j]['report_data'][$k]);

                    $res[$j]['report_data'][$k] = array("report_id" => $tm[0], "isPublished" => $tm[1], "publishDate" => $tm[2]);
                }



                $roles = $res[$j]['roles'] != "" ? explode(',', $res[$j]['roles']) : array();

                $statuses = $res[$j]['statuses'] != "" ? explode(',', $res[$j]['statuses']) : array();

                $percCompletes = $res[$j]['percCompletes'] != "" ? explode(',', $res[$j]['percCompletes']) : array();

                $ratingInputDates = $res[$j]['ratingInputDates'] != "" ? explode(',', $res[$j]['ratingInputDates']) : array();



                $user_ids = $res[$j]['user_ids'] != "" ? explode(',', $res[$j]['user_ids']) : array();

                $user_names = $res[$j]['user_names'] != "" ? explode(',', $res[$j]['user_names']) : array();
            } else {

                $roles = $res[$j]['roles'] != "" ? explode(',', $res[$j]['roles']) : array();

                $percCompletes = $res[$j]['percCompletes'] != "" ? explode(',', $res[$j]['percCompletes']) : array();

                $statuses = $res[$j]['statuses'] != "" ? explode(',', $res[$j]['statuses']) : array();

                $sz = count($percCompletes);

                $allStatuses = array(array(), array());

                $user_ids = $res[$j]['user_ids'] != "" ? explode(',', $res[$j]['user_ids']) : array();

                $user_names = $res[$j]['user_names'] != "" ? explode(',', $res[$j]['user_names']) : array();


                if ($sz && $sz % 2 == 0) {

                    $sz = $sz / 2;

                    $percCompletes = array_chunk($percCompletes, $sz);

                    $percCompletes = array(round(array_sum($percCompletes[0]) / $sz, 2), round(array_sum($percCompletes[1]) / $sz, 2));



                    $allStatuses = array_chunk($statuses, $sz);

                    $statuses = array(in_array(0, $allStatuses[0]) ? 0 : 1, in_array(0, $allStatuses[1]) ? 0 : 1);

                    $allUsers = array_chunk($user_ids, $sz);

                    $user_ids = array($allUsers[0], $allUsers[1]);
                }

                $ln = count($roles);

                for ($i = 0; $i < $ln; $i++) {
                    $res[$j]['data_by_role'][$roles[$i]] = array("status" => $statuses[$i], "percComplete" => $percCompletes[$i], "allStatuses" => $allStatuses[$i], "user_ids" => $user_ids[$i]);
                }

                $res[$j]['teacherInfoStatuses'] = explode(",", $res[$j]['teacherInfoStatuses']);
            }
        }
        return $res;
    }

    function chkFilledLeadAssessment($assessment_id) {

        $res = $this->db->get_results("SELECT assessment_user_id FROM h_assessment_user WHERE percComplete!=0.00 and assessment_id = ?", array($assessment_id));
        return $res ? $res : array();
    }

    function chkSyncLeadAssessment($assessment_id, $user_id) {

        $res = $this->db->get_results("SELECT assessment_user_id FROM h_assessment_user WHERE is_offline=1 and assessment_id = ? and user_id = ?", array($assessment_id, $user_id));
        return $res ? $res : array();
    }

    function chkAssKeyNotes($assessment_id) {

        $res = $this->db->get_results("SELECT id FROM assessor_key_notes WHERE assessment_id = ? and trim(text_data)!=''", array($assessment_id));
        return $res ? $res : array();
    }

    function chkFilledExtAssessment($assessment_id) {

        $res = $this->db->get_results("SELECT assessment_row_id FROM h_assessment_external_team WHERE percComplete!=0.00 and assessment_id = ?", array($assessment_id));
        return $res ? $res : array();
    }

    function chkSyncExtAssessment($assessment_id, $user_id) {

        $res = $this->db->get_results("SELECT assessment_row_id FROM h_assessment_external_team WHERE is_offline=1 and assessment_id = ? and user_id = ?", array($assessment_id, $user_id));
        return $res ? $res : array();
    }

    function checkAssessorExternal($assessment_id, $assessor_id) {
        $res = $this->db->get_row("select count(user_id) as isExternal
                                from h_assessment_external_team		
                                where assessment_id = ? AND user_id=? ", array($assessment_id, $assessor_id));
        return $res ? $res : array();
    }

    function countLvlBTnInDiag($diagnostic_id) {
        $sql = " SELECT Count(h_rating_level_scheme.rating_id) AS lvtct
                    FROM   h_diagnostic_rating_level_scheme
                           INNER JOIN h_rating_level_scheme
                                   ON h_diagnostic_rating_level_scheme.rating_level_scheme_id =
                                      h_rating_level_scheme.rating_scheme_id
                           LEFT JOIN d_rating_level
                                  ON d_rating_level.rating_level_id =
                                     h_rating_level_scheme.rating_level_id
                    WHERE  h_diagnostic_rating_level_scheme.diagnostic_id = ?
                           AND h_rating_level_scheme.rating_level_id = 4";
        $res = $this->db->get_results($sql, array($diagnostic_id));
        return $res ? $res[0]['lvtct'] : 0;
    }

    function getGoodLooksLikeList($instance_id) {
        $diagnosticModel = new diagnosticModel();

        $js_instance_id = $instance_id;
        $sqljs = "select * FROM h_cq_js_instance where judgement_statement_instance_id in($js_instance_id)";
        $resjs = $this->db->get_results($sqljs);

        foreach ($resjs as $resultjs) {
            $js_id_arr = $resultjs['judgement_statement_id'];
            $sqljs1 = "select * FROM d_judgement_statement where judgement_statement_id in ($js_id_arr)";
            $resjs1 = $this->db->get_results($sqljs1);
            $js_statement_text[] = $resjs1[0]['judgement_statement_text1'];
            $js_id[] = $resjs1[0]['judgement_statement_id'];
        }
        $js_ids = implode(',', $js_id);
        $sql_new = "select a.translation_text as judgement_statement_text1,e.translation_text,c.display_js_txt
            from h_lang_translation a
            inner join d_judgement_statement b on a.equivalence_id=b.equivalence_id
            inner join h_js_mostly_statements c on b.judgement_statement_id=c.judgement_statement_id
            inner join d_good_look_like_statement d on c.mostly_statements_id=d.good_looks_like_statement_id
            inner join h_lang_translation e on d.equivalence_id=e.equivalence_id
            inner join h_cq_js_instance f on c.judgement_statement_id=f.judgement_statement_id
            where b.judgement_statement_id IN ($js_ids) and judgement_statement_instance_id IN ($js_instance_id) and a.language_id=9 and e.language_id=9";
        $res_new = $this->db->get_results($sql_new); //print_r($res_new);
        return empty($res_new) ? array() : $res_new;
    }

    function chkAssessmentID($assessment_id) {
        $sql = "select count(assessment_id) as assessid FROM d_assessment where assessment_id=? ";
        $res = $this->db->get_row($sql, array($assessment_id));
        return $res ? $res['assessid'] : array();
    }

    function chkAssessmentUserID($assessment_id, $user_id) {
        $sql = "select count(assessment_id) as assessid FROM h_assessment_user where assessment_id=? and user_id=? ";
        $res = $this->db->get_row($sql, array($assessment_id, $user_id));
        return $res ? $res['assessid'] : array();
    }

    function chkAssessmentExtUserID($assessment_id, $user_id) {
        $sql = "select count(assessment_id) as assessid FROM h_assessment_external_team where assessment_id=? and user_id=? ";
        $res = $this->db->get_row($sql, array($assessment_id, $user_id));
        return $res ? $res['assessid'] : array();
    }

    function chkAssessmentOfflineStatus($assessment_id) {
        $sql = "select count(assessment_id) as assessid FROM d_assessment where assessment_id=? and is_offline=1 ";
        $res = $this->db->get_row($sql, array($assessment_id));
        return $res ? $res['assessid'] : array();
    }

    ////////////End Offline Hrer

    //function to get all customer details for action plan
    function getAllClusterDetail($user_id) {

        $res = $this->db->get_results("select * from h_user_admin_levels where user_id=?  
                group by cluster_id", array($user_id));
        return $res ? $res : array();
    }

    /** Fetch all schools for action plan 
     * $state_id:state id
     * $zone_ids:zone id
     * $block_ids:block ids
     * $cluster_ids: cluster ids
     */
    function getAllClusterSchools($state_id, $zone_ids, $block_ids, $cluster_ids) {
        $res = $this->db->get_results("SELECT DISTINCT
    g.assessment_id,
    z.zone_id,
    n.network_name,
    n.network_id AS block_id,
    p.province_name AS cluster_name,
    p.province_id AS cluster_id,
    g.client_id,
    c.client_name,
    aqs_round,
    b.user_id
FROM
    d_state s
        INNER JOIN
    h_zone_state zs ON s.state_id = zs.state_id
        INNER JOIN
    d_zone z ON z.zone_id = zs.zone_id
        INNER JOIN
    h_network_zone_state AS nzs ON nzs.zone_id = z.zone_id
        INNER JOIN
    d_network n ON n.network_id = nzs.network_id
        INNER JOIN
    h_cluster_block_zone_state cbzs ON cbzs.block_id = n.network_id
        INNER JOIN
    d_province p ON p.province_id = cbzs.cluster_id
        INNER JOIN
    h_client_province cp ON cp.province_id = p.province_id
        INNER JOIN
    d_client c ON c.client_id = cp.client_id
        INNER JOIN
    d_assessment g ON g.client_id = c.client_id
        INNER JOIN
    h_assessment_user b ON g.assessment_id = b.assessment_id
WHERE
    b.role = 4 AND b.isFilled = 1
        AND s.state_id = $state_id
        AND z.zone_id IN ($zone_ids)
        AND n.network_id IN ($block_ids)
        AND p.province_id IN ($cluster_ids)
        AND percComplete = 100.00  AND g.isAssessmentActive = 1 group by c.client_id order by c.create_date desc", array($state_id, $zone_ids, $block_ids, $cluster_ids));
        return $res ? $res : array();
    }

    /** Update as freeze review for school 
     */
    function unlockassesment($request) {
        
        $assessment_id = $request['assessment_id'];
        $type = explode(",", $request['unlocktypes']);
        if (in_array('demographic', $type)) {
            $res = $this->db->get_results("UPDATE h_school_profile_status SET status=0 WHERE assessment_id =" . $assessment_id);
        }
        if (in_array('rating', $type)) {
            $res = $this->db->get_results("UPDATE h_assessment_user SET isFilled=0 WHERE role=4 and assessment_id=" . $assessment_id);
        }
    }

}
